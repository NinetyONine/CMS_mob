<?php
/*-------------------------------------------------------+
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: panel_functions.php
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

function opentable($title) {

	echo "<div class='cap'>
	<div class='triangle'>
	<div style='margin-left: 10px;'>".$title."</div>\n
	</div>\n
	</div>\n
	<div class='content'>\n";

}

function closetable() {

	echo "</div>\n";

}

function openside($title, $collapse = false, $state = "on") {

	global $panel_collapse; $panel_collapse = $collapse;

	echo "<section>
	<div class='cap'>
	<div class='triangle'>
	<div style='margin-left: 10px;'>".$title."</div>\n
	</div>\n
	</div>\n";
	
	if ($collapse == true) {
		$boxname = str_replace(" ", "", $title);
		echo "<div class='flright' style='padding-top: 2px;'>".panelbutton($state, $boxname)."</div>\n";
	}

	echo "<div class='content'>\n";
	if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside() {

	global $panel_collapse;

	if ($panel_collapse == true) { echo "</div>\n</section>\n"; }
	echo "</section>\n";

}

?>