<?php

/**
* @file
* Module main class.
*/

class RzModule extends RzIntegration {
  /**
   * Module system name.
   */
  protected $sModule = "rzboard";
  /**
   * Users db table.
   */
  protected $usersDbTable;
  /**
   * Boards db table.
   */
  protected $boardsDbTable;
  /**
   * Current users db table.
   */
  protected $currentUsersDbTable;

  const USER_STATUS_NEW = "new";
  const USER_STATUS_OLD = "old";
  const USER_STATUS_IDLE = "idle";
  const BOARD_STATUS_NEW = "new";
  const BOARD_STATUS_NORMAL = "normal";
  const BOARD_STATUS_DELETE = "delete";
  const BOARD_STATUS_UPDATED = "updated";
  const BOARD_TYPE_EDIT = "edit";
  const BOARD_TYPE_VIEW = "view";

  /**
   * Class constructor.
   */
  public function __construct($s_path, $s_url) {
    parent::__construct($s_path, $s_url);
    $this->usersDbTable = $this->dbPrefix . $this->sModule . "_users";
    $this->boardsDbTable = $this->dbPrefix . $this->sModule . "_boards";
    $this->currentUsersDbTable = $this->dbPrefix . $this->sModule . "_current_users";
    $this->aXmlTemplates["user"] = array (
      2 => '<user id="#1#" status="#2#" />',
      3 => '<user id="#1#" status="#2#" type="#3#" />',
      6 => '<user id="#1#" sex="#3#" age="#4#" photo="#5#" profile="#6#"><nick><![CDATA[#2#]]></nick></user>',
      8 => '<user id="#1#" status="#2#" sex="#4#" age="#5#" photo="#6#" profile="#7#"><nick><![CDATA[#3#]]></nick><desc><![CDATA[#8#]]></desc></user>',
    );
    $this->aXmlTemplates["board"] = array (
      2 => '<board id="#1#" status="#2#" />',
      3 => '<board id="#1#" in="#2#" out="#3#" />',
      5 => '<board id="#1#" status="#2#" owner="#3#" password="#4#"><title><![CDATA[#5#]]></title></board>',
      6 => '<board id="#1#" status="#2#" owner="#3#" password="#4#" in="#5#"><title><![CDATA[#6#]]></title></board>',
      7 => '<board id="#1#" status="#2#" owner="#3#" password="#4#" in="#5#" backFile="#7#"><title><![CDATA[#6#]]></title></board>',
    );
  }

  /**
   * Config generator.
   */
  public function actionConfig() {
    $s_ñontents = parent::actionConfig();
    $s_ñontents = str_replace("#soundsUrl#", $this->sUrl . "data/sounds/", $s_ñontents);
    return $s_ñontents;
  }
	
  /**
   * Gets plugins.
   */
  public function actionGetPlugins() {
    $s_ñontents = "";
    $s_plugins_path = $this->sPath . "plugins/";
    if (is_dir($s_plugins_path)) {
      if ($r_dir_handle = opendir($s_plugins_path)) {
        while (FALSE !== ($s_plugin = readdir($r_dir_handle))) {
          if (strpos($s_plugin, ".swf") === strlen($s_plugin) - 4) {
            $s_ñontents .= $this->parseXml(array(1 => '<plugin><![CDATA[#1#]]></plugin>'), $this->sUrl . $s_folder . $s_plugin);
          }
        }
      }
      closedir($r_dir_handle);
    }
    return $this->makeGroup($s_ñontents, "plugins");
  }

  /**
   * Authorizes user.
   */
  public function actionUserAuthorize() {
    $s_password = $this->getRequestVar("password", "db");
    if ($this->loginUser($this->sId, $s_password)) {
      $a_user = $this->getUserInfo($this->sId);
      $this->oDb->getResult("REPLACE " . $this->currentUsersDbTable . " SET ID='" . $this->sId . "', Nick='" . $a_user['nick'] . "', Sex='" . $a_user['sex'] . "', Age='" . $a_user['age'] . "', Photo='" . $a_user['photo'] . "', Profile='" . $a_user['profile'] . "', Description='" . $a_user['desc'] . "', Time='" . time() . "', Status='" . self::USER_STATUS_NEW . "'");
      $this->oDb->getResult("DELETE FROM " . $this->usersDbTable . " WHERE User='" . $this->sId . "'");
      $r_files = $this->oDb->getResult("SELECT ID FROM " . $this->boardsDbTable . " WHERE OwnerID='" . $this->sId . "'");
      while (($a_file = $this->oDb->fetch($r_files)) !== NULL) {
        @unlink($this->sFilesPath . $a_file['ID'] . ".jpg");
      }
      $this->oDb->getResult("DELETE FROM " . $this->boardsDbTable . ", " . $this->usersDbTable . " USING " . $this->boardsDbTable . " LEFT JOIN " . $this->usersDbTable . " ON " . $this->boardsDbTable . ".ID=" . $this->usersDbTable . ".Board WHERE " . $this->boardsDbTable . ".OwnerID='" . $this->sId . "'");
      $s_ñontents = $this->parseXml($this->aXmlTemplates['result'], "", self::SUCCESS_VAL);
      $s_ñontents .= $this->parseXml($this->aXmlTemplates['user'], $this->sId, self::USER_STATUS_NEW, $a_user['nick'], $a_user['sex'], $a_user['age'], $a_user['photo'], $a_user['profile'], $a_user['desc']);
    }
    else {
      $s_ñontents = $this->parseXml($this->aXmlTemplates['result'], "msgUserAuthenticationFailure", self::FAILED_VAL);
    }	
    return $s_ñontents;
  }

  /**
   * Gets sounds.
   */
  public function actionGetSounds() {
    $s_file_name = $this->sPath . "data/sounds.xml";
    if (file_exists($s_file_name)) {
      $r_handle = fopen($s_file_name, "rt");
      $s_ñontents = fread($r_handle, filesize($s_file_name));
      fclose($r_handle);
    }
    else {
      $s_ñontents = $this->makeGroup("", "items");
    }
    return $s_ñontents;
  }

  /**
   * Gets boards.
   */
  public function actionGetBoards() {
    return $this->makeGroup($this->getBoards("all", $this->sId), "boards");
  }

  /**
   * Creates board.
   */
  public function actionCreateBoard() {
    $s_title = $this->getRequestVar("title", "db");
    $s_password = $this->getRequestVar("password", "db");
    $i_board_id = $this->doBoard('insert', $this->sId, 0, $s_title, $s_password);
    $this->doBoard('enter', $this->sId, $i_board_id);
    if (empty($i_board_id)) {
      return $this->parseXml($this->aXmlTemplates['result'], "msgErrorCreatingBoard", self::FAILED_VAL);
    }
    else {
      return $this->parseXml($this->aXmlTemplates['result'], $i_board_id, self::SUCCESS_VAL);
    }
  }

  /**
   * Edits board.
   */
  public function actionEditBoard() {
    $i_board_id = $this->getRequestVar("boardId", "int");
    $s_title = $this->getRequestVar("title", "db");
    $s_password = $this->getRequestVar("password", "db");
    $this->doBoard('update', 0, $i_board_id, $s_title, $s_password);
    return $this->parseXml($this->aXmlTemplates['result'], "", self::SUCCESS_VAL);
  }

  /**
   * Deletes board.
   */
  public function actionDeleteBoard() {
    $i_board_id = $this->getRequestVar("boardId", "int");
    $this->doBoard('delete', 0, $i_board_id);
    return $this->parseXml($this->aXmlTemplates['result'], self::TRUE_VAL);
  }

  /**
   * Enter board.
   */
  public function actionEnterBoard() {
    $i_board_id = $this->getRequestVar("boardId", "int");
    $this->doBoard('enter', $this->sId, $i_board_id);
  }

  /**
   * Exit board.
   */
  public function actionExitBoard() {
    $i_board_id = $this->getRequestVar("boardId", "int");
    $this->doBoard('exit', $this->sId, $i_board_id);
  }

  /**
   * Checks board's password.
   */
  public function actionCheckBoardPassword() {
    $i_board_id = $this->getRequestVar("boardId", "int");
    $s_password = $this->getRequestVar("password", "db");
    $s_id = $this->oDb->getValue("SELECT ID FROM " . $this->boardsDbTable . " WHERE ID='" . $i_board_id . "' AND Password='" . $s_password . "' LIMIT 1");
    if (empty($s_id)) {
      return $this->parseXml($this->aXmlTemplates['result'], "msgWrongRoomPassword", self::FAILED_VAL);
	}
    else {
      return $this->parseXml($this->aXmlTemplates['result'], "", self::SUCCESS_VAL);
	}
  }

  /**
   * Gets online users.
   */
  public function actionGetOnlineUsers() {
    $i_num_rows = (int)$this->oDb->getValue("SELECT COUNT(ID) FROM " . $this->currentUsersDbTable);
    if ($i_num_rows == 0) {
      $this->oDb->getResult("TRUNCATE TABLE " . $this->currentUsersDbTable);
    }
    return $this->refreshUsersInfo($this->sId);
  }

  /**
   * Gets an update.
   */
  public function actionUpdate() {
    $s_ñontents = $this->refreshUsersInfo($this->sId, 'update');
    $s_ñontents .= $this->makeGroup($this->getBoards('update', $this->sId), "boards");
    $s_ñontents .= $this->makeGroup($this->getBoards('updateUsers', $this->sId), "boardsUsers");
    return $s_ñontents;
  }

  /**
   * Creates image.
   */
  public function actionTransmit() {
    if (!function_exists("imagecreatetruecolor")) {
      return $this->parseXml($this->aXmlTemplates['result'], 'msgErrorGD', self::FAILED_VAL);
    }
    // Prepares data.
    $i_board_id = $this->getRequestVar("boardId", "int");
    $i_width = $this->getRequestVar("width", "int");
    $i_height = $this->getRequestVar("height", "int");
    $i_back_color = $this->getRequestVar("backColor", "int");
    if (empty($i_back_color)) {
      $i_back_color = 16777216;
    }
    $s_data = $this->getRequestVar("data");
    $i_quality = 100;
    $a_data = explode(',', $s_data);
    $a_image_data = array();
    for ($i = 0; $i < count($a_data); $i++) {
      $a_pixel = explode("=", $a_data[$i], 2);
      $a_image_data[$a_pixel[0]] = base_convert($a_pixel[1], 36, 10);
    }
    // Creates Image Resource.
    $r_image = @imagecreatetruecolor($i_width, $i_height);
    for ($i = 0, $y = 0; $y < $i_height; $y++) {
      for ($x = 0; $x < $i_width; $x++, $i++) {
        @imagesetpixel($r_image, $x, $y, isset($a_image_data[$i]) ? $a_image_data[$i] : $i_back_color);
      }
    }
    // Saves image file.
    $s_file_name = $this->sFilesPath . $i_board_id . ".jpg";
    $b_file_created = @imagejpeg($r_image, $s_file_name, $i_quality);
    if ($b_file_created) {
      return $this->parseXml($this->aXmlTemplates['result'], "", self::SUCCESS_VAL);
    }
    else {
      return $this->parseXml($this->aXmlTemplates['result'], "msgErrorFile", self::FAILED_VAL);
    }
  }

  /**
   * Gets boards helper.
   */
  protected function getBoards($s_mode = 'new', $s_id = "") {
    $i_current_time = time();
    $i_update_interval = (int)$this->getSettingValue("updateInterval");
    $i_new_time = $i_update_interval * 2;
    $i_idle_time = $i_update_interval * 3;
    $i_delete_time = $i_update_interval * 6;
    $s_boards = "";
    switch ($s_mode) {
      case 'update':
        $this->oDb->getResult("UPDATE " . $this->boardsDbTable . " SET Time='" . $i_current_time . "', Status='" . self::BOARD_STATUS_NORMAL . "' WHERE OwnerID='" . $s_id . "' AND (Status='" . self::BOARD_STATUS_NORMAL . "' OR (Status='" . self::BOARD_STATUS_NEW . "' AND Time<='" . ($i_current_time - $i_new_time) . "'))");
        // Deletes old boards.
        $r_files = $this->oDb->getResult("SELECT ID FROM " . $this->boardsDbTable . " WHERE Status='" . self::BOARD_STATUS_DELETE . "' AND Time<=(" . ($i_current_time - $i_delete_time) . ")");
        while ($a_file = $this->oDb->fetch($r_files)) {
          @unlink($this->sFilesPath . $a_file['ID'] . ".jpg");
        }
        $this->oDb->getResult("DELETE FROM " . $this->boardsDbTable . ", " . $this->usersDbTable . " USING " . $this->boardsDbTable . " LEFT JOIN " . $this->usersDbTable . " ON " . $this->boardsDbTable . ".ID=" . $this->usersDbTable . ".Board WHERE " . $this->boardsDbTable . ".Status='" . self::BOARD_STATUS_DELETE . "' AND " . $this->boardsDbTable . ".Time<=(" . ($i_current_time - $i_delete_time) . ")");
        $this->oDb->getResult("UPDATE " . $this->boardsDbTable . " SET Status='" . self::BOARD_STATUS_DELETE . "' WHERE Time<'" . ($i_current_time - $i_idle_time) . "' AND Status<>'" . self::BOARD_STATUS_DELETE . "'");
        $r_result = $this->oDb->getResult("SELECT * FROM " . $this->boardsDbTable . " WHERE OwnerID<>'" . $s_id . "' AND Status<>'" . self::BOARD_STATUS_NORMAL . "'");
        while ($a_board = $this->oDb->fetch($r_result)) {
          switch ($a_board['Status']) {
            case self::BOARD_STATUS_DELETE:
              $s_boards .= $this->parseXml($this->aXmlTemplates['board'], $a_board['ID'], self::BOARD_STATUS_DELETE);
              break;
            case self::BOARD_STATUS_NEW:
              $s_boards .= $this->parseXml($this->aXmlTemplates['board'], $a_board['ID'], self::BOARD_STATUS_NORMAL, $a_board['OwnerID'], empty($a_board['Password']) ? self::FALSE_VAL : self::TRUE_VAL, stripslashes($a_board['Name']));
              break;
          }
        }
        $r_result = $this->oDb->getResult("SELECT boards.ID FROM " . $this->boardsDbTable . " AS boards INNER JOIN " . $this->usersDbTable . " AS users ON boards.ID=users.Board WHERE boards.OwnerID<>'" . $s_id . "'");
        while (($a_board = $this->oDb->fetch($r_result)) !== NULL) {
          $s_file = $this->sFilesPath . $a_board['ID'] . ".jpg";
          if (file_exists($s_file)) {
            $i_modified_time = filemtime($s_file);
            if ($i_modified_time >= ($i_current_time - $i_update_interval)) {
              $s_boards .= $this->parseXml($this->aXmlTemplates['board'], $a_board['ID'], self::BOARD_STATUS_UPDATED);
            }
          }
        }
        break;

	  case 'updateUsers':
	    $r_result = $this->oDb->getResult("SELECT r.ID AS BoardID, GROUP_CONCAT(DISTINCT IF(ru.Status<>'" . self::BOARD_STATUS_DELETE . "',ru.User,'') SEPARATOR ',') AS UsersIn, GROUP_CONCAT(DISTINCT IF(ru.Status='" . self::BOARD_STATUS_DELETE . "',ru.User,'') SEPARATOR ',') AS UsersOut FROM " . $this->boardsDbTable . " AS r INNER JOIN " . $this->usersDbTable . " AS ru WHERE r.ID=ru.Board AND r.Status='" . self::BOARD_STATUS_NORMAL . "' AND ru.Time>=" . ($i_current_time - $i_update_interval) . " GROUP BY r.ID");
	    while (($a_board = $this->oDb->fetch($r_result)) !== NULL) {
	      $s_boards .= $this->parseXml($this->aXmlTemplates['board'], $a_board['BoardID'], $a_board['UsersIn'], $a_board['UsersOut']);
	    }
	    break;

      case 'all':
        $i_current_time -= floor($this->getRequestVar("_t", "int") / 1000);
        $i_num_rows = (int)$this->oDb->getValue("SELECT COUNT(ID) FROM " . $this->usersDbTable);
        if ($i_num_rows == 0) {
          $this->oDb->getResult("TRUNCATE TABLE " . $this->usersDbTable);
        }
        $r_result = $this->oDb->getResult("SELECT r.ID AS BoardID, r.*, GROUP_CONCAT(DISTINCT IF(ru.Status='" . self::BOARD_STATUS_NORMAL . "' AND ru.User<>'" . $s_id . "',ru.User,'') SEPARATOR ',') AS UsersIn, GROUP_CONCAT(DISTINCT IF(ru.Status='" . self::BOARD_STATUS_DELETE . "' AND ru.User<>'" . $s_id . "',ru.User,'') SEPARATOR ',') AS UsersOut FROM " . $this->boardsDbTable . " AS r LEFT JOIN " . $this->usersDbTable . " AS ru ON r.ID=ru.Board GROUP BY r.ID ORDER BY r.ID");
        while (($a_board = $this->oDb->fetch($r_result)) !== NULL) {
          $s_file = $a_board['BoardID'] . ".jpg";
          $s_file_path = $this->sFilesPath . $s_file;
          $s_boards .= $this->parseXml($this->aXmlTemplates['board'], $a_board['BoardID'], self::BOARD_STATUS_NORMAL, $a_board['OwnerID'], empty($a_board['Password']) ? self::FALSE_VAL : self::TRUE_VAL, $a_board['UsersIn'], stripslashes($a_board['Name']), (file_exists($s_file_path) && filesize($s_file_path) > 0) ? $s_file : "");
        }
        if (empty($s_boards) == "") {
          $this->oDb->getResult("TRUNCATE TABLE " . $this->boardsDbTable);
          $this->oDb->getResult("TRUNCATE TABLE " . $this->usersDbTable);
        }
        break;
    }
    return $s_boards;
  }

  /**
   * Boards actions helper.
   */
  protected function doBoard($s_switch, $s_user_id = "", $i_board_id = 0, $s_title = "", $s_password = "")	{
    $i_current_time = time();
    switch ($s_switch) {
      case 'insert':
        $i_board_id = $this->oDb->getValue("SELECT ID FROM " . $this->boardsDbTable . " WHERE Name='" . $s_title . "' AND OwnerID='" . $s_user_id . "'");
        if (empty($i_board_id)) {
          $this->oDb->getResult("INSERT INTO " . $this->boardsDbTable . " (ID, Name, Password, OwnerID, Time) VALUES ('" . $i_board_id . "', '" . $s_title . "', '" . $s_password . "', '" . $s_user_id . "', '" . $i_current_time . "')");
          $i_board_id = (int)$this->oDb->getValue("SELECT MAX(ID) FROM " . $this->boardsDbTable);
        }
        return $i_board_id;
        break;

      case 'update':
        $this->oDb->getResult("UPDATE " . $this->boardsDbTable . " SET Name='" . $s_title . "', Password='" . $s_password . "', Time='" . $i_current_time . "', Status='" . self::BOARD_STATUS_NEW . "' WHERE ID='" . $i_board_id . "'");
        break;

      case 'delete':
        $this->oDb->getResult("UPDATE " . $this->boardsDbTable . " SET Time='" . $i_current_time . "', Status='" . self::BOARD_STATUS_DELETE . "' WHERE ID = '" . $i_board_id . "'");
        break;

      case 'enter':
        $s_id = $this->oDb->getValue("SELECT ID FROM " . $this->usersDbTable . " WHERE Board='" . $i_board_id . "' AND User='" . $s_user_id . "' LIMIT 1");
        if (empty($s_id)) {
          $this->oDb->getResult("INSERT INTO " . $this->usersDbTable . "(Board, User, Time) VALUES('" . $i_board_id . "', '" . $s_user_id . "', '" . $i_current_time . "')");
        }
        else {
          $this->oDb->getResult("UPDATE " . $this->usersDbTable . " SET Time='" . $i_current_time . "', Status='" . self::BOARD_STATUS_NORMAL . "' WHERE ID='" . $s_id . "'");
        }
        break;

      case 'exit':
        $this->oDb->getResult("UPDATE " . $this->usersDbTable . " SET Time='" . $i_current_time . "', Status='" . self::BOARD_STATUS_DELETE . "' WHERE Board='" . $i_board_id . "' AND User='" . $s_user_id . "' LIMIT 1");
        break;
    }
  }

  /**
   * Refreshes users info.
   */
  protected function refreshUsersInfo($s_id = "", $s_mode = 'all') {
    $i_update_interval = (int)$this->getSettingValue("updateInterval");
    $i_idle_time = $i_update_interval * 3;
    $i_delete_time = $i_update_interval * 6;
    $s_content = "";
    $i_current_time = time();
    // Refresh current user's track.
    $this->oDb->getResult("UPDATE " . $this->currentUsersDbTable . " SET Status='" . self::USER_STATUS_OLD . "', Time='" . $i_current_time . "' WHERE ID='" . $s_id . "' AND (Status<>'" . self::USER_STATUS_NEW . "' OR (" . $i_current_time . "-Time)>" . $i_update_interval . ") LIMIT 1");
    // Refresh other users' states.
    $this->oDb->getResult("UPDATE " . $this->currentUsersDbTable . " SET Time=" . $i_current_time . ", Status='" . self::USER_STATUS_IDLE . "' WHERE Status<>'" . self::USER_STATUS_IDLE . "' AND Time<=(" . ($i_current_time - $i_idle_time) . ")");
    $this->oDb->getResult("DELETE FROM " . $this->usersDbTable . " WHERE Status='" . self::BOARD_STATUS_DELETE . "' AND Time<=(" . ($i_current_time - $i_delete_time) . ")");
    // Delete idle users, whose track was not refreshed more than delete time.
    $this->oDb->getResult("DELETE FROM " . $this->currentUsersDbTable . ", " . $this->usersDbTable . " USING " . $this->currentUsersDbTable . " LEFT JOIN " . $this->usersDbTable . " ON " . $this->currentUsersDbTable . ".ID=" . $this->usersDbTable . ".User WHERE " . $this->currentUsersDbTable . ".Status='" . self::USER_STATUS_IDLE . "' AND " . $this->currentUsersDbTable . ".Time<=" . ($i_current_time - $i_delete_time));
    // Get information about users in the chat.
    switch ($s_mode) {
      case 'update':
        $r_res = $this->oDb->getResult("SELECT * FROM " . $this->currentUsersDbTable . " ORDER BY Time");
        while (($a_user = $this->oDb->fetch($r_res)) !== NULL) {
          switch ($a_user['Status']) {
            case self::USER_STATUS_NEW:
              $s_content .= $this->parseXml($this->aXmlTemplates['user'], $a_user['ID'], $a_user['Status'], $a_user['Nick'], $a_user['Sex'], $a_user['Age'], $a_user['Photo'], $a_user['Profile'], $a_user['Description']);
              break;
            case self::USER_STATUS_IDLE:
              $s_content .= $this->parseXml($this->aXmlTemplates['user'], $a_user['ID'], $a_user['Status']);
              break;
          }
        }
        break;

      case 'all':
        $i_current_time -= floor($this->getRequestVar("_t", "int") / 1000);
        $r_res = $this->oDb->getResult("SELECT * FROM " . $this->currentUsersDbTable . " WHERE Status<>'" . self::USER_STATUS_IDLE . "' ORDER BY Time");
        while (($a_user = $this->oDb->fetch($r_res)) !== NULL) {
          $s_content .= $this->parseXml($this->aXmlTemplates['user'], $a_user['ID'], self::USER_STATUS_NEW, $a_user['Nick'], $a_user['Sex'], $a_user['Age'], $a_user['Photo'], $a_user['Profile'], $a_user['Description']);
        }
        break;
    }
    return $this->makeGroup($s_content, "users");
  }
}
