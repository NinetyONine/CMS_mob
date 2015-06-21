<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: nav.php
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
 $menu = 1;
 
	   	if ($menu == 1) {

			echo"<nav>";
			echo "<div class='switcher'>".$colour_switcher->makeForm("flright")."</div>\n";
				echo navigation();
			echo"</nav>\n";
			}else{
			echo"<nav>";
			echo "<div class='switcher'>".$colour_switcher->makeForm("flright")."</div>\n";
				echo"".showsublinks("")."
			</nav>\n";
		}

		echo"<script>

$('<select />').appendTo('nav');

$('<option />', {
   'selected': 'selected',
   'value'   : '',
   'text'    : '".$locale['omni_002']."'
}).appendTo('nav select');

$('nav a').each(function() {
 var el = $(this);
 $('<option />', {
     'value'   : el.attr('href'),
     'text'    : el.text()
 }).appendTo('nav select');
});

$('nav select').change(function() {
  window.location = $(this).find('option:selected').val();
});
</script>";

?>