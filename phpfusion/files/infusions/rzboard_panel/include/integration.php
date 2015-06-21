<?php

/**
 * @file
 * Module integration class.
 */
 
$s_file = realpath(dirname(__FILE__) . '/../../../config.php');
$s_file1 = realpath(dirname(__FILE__) . '/../../../maincore.php');
if (file_exists($s_file) && file_exists($s_file1)) {
  require_once($s_file);
  require_once($s_file1);
}
else {
  die("Init file is not found");
}

class RzIntegration extends RzBase {
  /**
   * Database tables prefix.
   */
  protected $dbPrefix;
  /**
   * Site home url.
   */
  protected $sSiteUrl;
  /**
   * Site login user url.
   */
  protected $sLoginUrl;

  /**
   * Class constructor.
   */
  public function __construct($s_path, $s_url) {
    parent::__construct($s_path, $s_url);
    $this->oDb = new RzDbConnect();
    $this->dbPrefix = DB_PREFIX;
    $a_url = explode("infusions/", $this->sUrl);
    $this->sLoginUrl = $this->sSiteUrl = $a_url[0];
  }

  /**
   * Checks user login and password.
   */
  protected function loginUser($s_name, $s_password, $b_login = FALSE) {
    $s_field = $b_login ? "user_name" : "user_id";
    $s_id = $this->oDb->getValue("SELECT user_id FROM " . $this->dbPrefix . "users WHERE " . $s_field . "='" . $s_name . "' AND user_password='" . $s_password . "' LIMIT 1");
    return !empty($s_id);
  }

  /**
   * Checks admin login and password.
   */
  protected function loginAdmin($s_id, $s_password) {
    $i_id = $this->oDb->getValue("SELECT user_id FROM " . $this->dbPrefix . "users WHERE user_id='" . $s_id . "' AND user_password='" . $s_password . "' AND user_admin_password!='' LIMIT 1");
    return !empty($s_id);
  }

  /**
   * Gets user info.
   */
  protected function getUserInfo($s_id, $b_nick = FALSE) {
    $s_where_part = ($b_nick ? "user_name" : "user_id") . " = '" . $s_id . "'";
    $a_user = $this->oDb->getArray("SELECT *, YEAR(FROM_DAYS(DATEDIFF(NOW(), user_birthdate))) AS user_age FROM " . $this->dbPrefix . "users WHERE " . $s_where_part . " LIMIT 1");
    $s_profile = $this->sSiteUrl . "profile.php?lookup=" . $a_user["user_id"];
    if (!empty($a_user['user_avatar'])) {
      $s_photo = $this->sSiteUrl . "images/avatars/" . $a_user['user_avatar'];
    }
    else {
      $s_photo = $this->sUrl . "data/male.jpg";
    }
    return array("id" => (int)$a_user["user_id"], "nick" => $a_user['user_name'], "sex" => "M", "age" => $a_user['user_age'], "desc" => $a_user['user_sig'], "photo" => $s_photo, "profile" => $s_profile);
  }

  /**
   * Searches for user.
   */
  protected function searchUser($s_value, $s_field = "ID") {
    if ($s_field == "ID") {
      $s_field = "user_id";
    }
    else {
      $s_field = "user_name";
    }
    $s_id = $this->oDb->getValue("SELECT user_id FROM " . $this->dbPrefix . "users WHERE " . $s_field . " = '" . $s_value . "' LIMIT 1");
    return $s_id;
  }

  /**
   * Gets current language.
   */
  protected function getCurrentLang($s_user_id = "") {
    $s_lang = $this->oDb->getValue("SELECT settings_value FROM " . $this->dbPrefix . "settings WHERE settings_name='locale' LIMIT 1");
    if (!empty($s_lang)) {
      return substr(strtolower($s_lang), 0, 2);
    }
    return parent::getCurrentLang($s_user_id);
  }

  /**
   * Gets membership id by user id.
   */
  protected function getMembershipId($s_user_id) {
    $s_groups = $this->oDb->getValue("SELECT user_groups FROM " . $this->dbPrefix . "users WHERE user_id='" . $s_user_id . "' LIMIT 1");
    if (empty($s_groups)) {
      return 0;
    }
    $s_groups = substr($s_groups, 1);
    $a_groups = explode('.', $s_groups);
    return (int)$a_groups[0];
  }

  /**
   * Gets memberships levels.
   */
  protected function getMemberships() {
    $a_groups = array();
    $a_groups[0] = 'Without Group';
    $r_res = $this->oDb->getResult("SELECT * FROM " . $this->dbPrefix . "user_groups");
    while (($a_group = $this->oDb->fetch($r_res)) !== NULL) {
      $a_groups[$a_group['group_id']] = $a_group['group_name'];
    }
    return $a_groups;
  }
}
