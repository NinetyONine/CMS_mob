<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: content.php
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

echo "<section id='content'>
		<div class='resp-grid'>
		<div style='-webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;' class='row block'>";
		
		if (!defined("ADMIN_PANEL") && LEFT || RIGHT) {
		echo"<div class='main-content col11'>";
		}else{
			echo"<div class='main-content col16'>";
			
		}
if (defined("ADMIN_PANEL")) {
	echo"<div class='main-content col12'>";
		}
		
		echo "<article>".U_CENTER."</article>\n";
		echo "<article>".CONTENT."</article>\n";
		echo "<article>".L_CENTER."</article>\n";
		echo "</div>\n";
	
	if (!defined("ADMIN_PANEL") && LEFT || RIGHT) { echo "<div class='sidebar col05'>".LEFT."".RIGHT."</div>\n"; }
	if (defined("ADMIN_PANEL")) {
	echo"<div class='sidebar coladmin'>".LEFT."".RIGHT."</div>\n";
		}
		
	echo "</div>\n
			</div>\n
			</section>\n";
?>