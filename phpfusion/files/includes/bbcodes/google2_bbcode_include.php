<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: google_bbcode_include.php
| Author: Wooya
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
global $settings;
// Check if locale file is available matching the current site locale setting.
if (file_exists(LOCALE.LOCALESET."/global.php")) {
	// Load the locale file matching the current site locale setting.
	include LOCALE.LOCALESET."/global.php";
} else {
	// Load the infusion's default locale file.
	include "locale/English/global.php";
}

$lingo = $locale['xml_lang'];

$text = preg_replace('#\[google2\](.*?)\[/google2\]#si', '<img src=\'https://www.google.com/s2/favicons?domain=www.google.de\' width=\'18\' height=\'18\' alt=\'Google Search\' border=\'0\' style=\'vertical-align:middle;\'> <a href=\'http://'.$lingo.'.lmgtfy.com/?q=\1\' target=\'_blank\'>\1</a>', $text);


?>