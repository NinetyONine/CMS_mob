<?php
/**
 * @version 1.0
 * @copyright Copyright (C) 2014 rayzzz.com. All rights reserved.
 * @license GNU/GPL2, see LICENSE.txt
 * @website http://rayzzz.com
 * @twitter @rayzzzcom
 * @email rayzexpert@gmail.com
 */
if (!defined("IN_FUSION")) { die("Access Denied"); }
$sIncFile = dirname(__FILE__) . "/include/init.php";
if(!file_exists($sIncFile))
	die("Init file not found");
	
require_once($sIncFile);
$inf_folder = "rzboard_panel";

// Infusion general information
$inf_title = RzboardInit::$aRzInfo['title'];
$inf_description = RzboardInit::$aRzInfo['desc'];
$inf_version = RzboardInit::$aRzInfo['version'];
$inf_developer = RzboardInit::$aRzInfo['author'];
$inf_email = RzboardInit::$aRzInfo['email'];
$inf_weburl = RzboardInit::$aRzInfo['url'];

$inf_sitelink[1] = array(
	'title' => $inf_title,
	'url' => 'index.php',
	'visibility' => '0'
);

$i=1;
foreach(RzboardInit::$aDBTables as $sName => $aTable)
{
	$inf_newtable[$i] = DB_PREFIX . $sName . " (";
	$inf_droptable[$i] = DB_PREFIX . $sName;
	foreach($aTable['fields'] as $sField => $aField)
	{
		$inf_newtable[$i] .= "`" . $sField . "` " . $aField['type'] . (isset($aField['length']) ? "(" . $aField['length'] . ") " : " ") . ($aField['not null'] ? "NOT NULL " : " ") . (isset($aField['auto_increment']) && $aField['auto_increment'] ? "auto_increment " : " ");
		if(isset($aField['default']))
		{
			if(is_int($aField['default']))
				$inf_newtable[$i] .= "default " . $aField['default'];
			else
				$inf_newtable[$i] .= "default '" . $aField['default'] . "'";
		}
		$inf_newtable[$i] .= ",";
	}
	$inf_newtable[$i] .= "PRIMARY KEY (`" . implode("`,`", $aTable['primary key']) . "`))";
	$i++;
}

$inf_insertdbrow[1] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES('".$inf_title."', '".$inf_folder."', '', '2', '1', 'file', '0', '0', '0')";
$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";
?>