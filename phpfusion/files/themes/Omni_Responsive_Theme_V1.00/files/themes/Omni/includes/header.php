<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: header.php
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
if (file_exists(THEME."locale/".$settings['locale'].".php")) {
	include THEME."locale/".$settings['locale'].".php";
} else {
	include THEME."locale/English.php";
}
	      echo "<header>\n
		  <div class='resp-grid'>
		<div class='row'>
			<div class='col05'>
				<div id='logo'><a href='".BASEDIR."index.php'>".showbanners()."</a></div>\n
			</div>\n
			<div class='col06 offset05'>
			   <div id='search-box'>
				  <form action='".BASEDIR."search.php?stype=all' id='search-form' method='get' target='_top'>
				  <input type='hidden' name='stype' value='all' />
					<input id='search-text' name='stext'  placeholder='".$locale['omni_001']."' type='text'/>
					<button id='search-button' type='submit'><span>".$locale['omni_001']."</span></button>
				  </form>
				</div>\n
			</div>\n
		</div>\n
		</div>\n</header>";
?>