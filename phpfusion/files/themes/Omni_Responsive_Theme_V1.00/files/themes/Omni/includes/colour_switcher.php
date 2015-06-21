<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: colour_switcher.php
| Version: 1.00
| Author: PHP-Fusion Mods UK
| Developer & Designer: Craig
| Site: http://www.phpfusionmods.co.uk
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

$enable_colour_switcher = true;
require_once THEMES."templates/switcher.php";
$colour_switcher = new Switcher("select", "colour", "png", "blue", "switcherbutton");
if(!$enable_colour_switcher){
	$colour_switcher->disable();
}

function get_head_tags(){
	global $colour_switcher;
	echo $colour_switcher->makeHeadTag();
	echo "<!--[if lte IE 7]><style type='text/css'>.clearfix {display:inline-block;} * html .clearfix{height: 1px;}</style><![endif]-->";
}

?>