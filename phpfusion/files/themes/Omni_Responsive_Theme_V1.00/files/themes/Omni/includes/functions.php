<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: functions.php
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

define("THEME_BULLET", "<img src='".THEME."images/bullet.png' class='bullet'  alt='&raquo;' />");

set_image("stickythread", THEME."forum/stickythread.png");
set_image("printer", THEME."images/icons/printer.png");
set_image("up", THEME."images/up.png");
set_image("down", THEME."images/down.png");
set_image("left", THEME."images/left.png");
set_image("right", THEME."images/right.png");
set_image("reply", "reply");
set_image("newthread", "newthread");
set_image("web", "web");
set_image("pm", "pm");
set_image("quote", "quote");
set_image("forum_edit", "forum_edit");

// From Stylo Theme By Falcon
function theme_output($output) {

	$search = array(
		"@><img src='reply' alt='(.*?)' style='border:0px' />@si",
		"@><img src='newthread' alt='(.*?)' style='border:0px;?' />@si",
		"@><img src='web' alt='(.*?)' style='border:0;vertical-align:middle' />@si",
		"@><img src='pm' alt='(.*?)' style='border:0;vertical-align:middle' />@si",
		"@><img src='quote' alt='(.*?)' style='border:0px;vertical-align:middle' />@si",
		"@><img src='forum_edit' alt='(.*?)' style='border:0px;vertical-align:middle' />@si",
		"@<a href='".ADMIN."comments.php(.*?)&amp;ctype=(.*?)&amp;cid=(.*?)'>(.*?)</a>@si"
	);
	$replace = array(
		' class="big button"><span class="reply-button icon"></span>$1',
		' class="big button"><span class="newthread-button icon"></span>$1',
		' class="button" rel="nofollow" title="$1"><span class="web-button icon"></span>Web',
		' class="button" title="$1"><span class="pm-button icon"></span>PM',
		' class="button" title="$1"><span class="quote-button icon"></span>$1',
		' class="negative button" title="$1"><span class="edit-button icon"></span>$1',
		'<a href="'.ADMIN.'comments.php$1&amp;ctype=$2&amp;cid=$3" class="big button"><span class="settings-button icon"></span>$4</a>'
	);
	$output = preg_replace($search, $replace, $output);

	return $output;
}
///////////////////////

// Navigation Function by Johan Wilson (Barspin) & Craig
function navigation($main_menu=true){
	
	if ($main_menu) {
		$result = dbquery("SELECT link_name, link_url, link_window, link_visibility FROM ".DB_SITE_LINKS." WHERE link_position='3' ORDER BY link_order");
		if (dbrows($result) > 0) {
		
	
			add_to_head("<script type='text/javascript' src='".THEME."includes/menu/menu.js'></script>");
			

			while ($data = dbarray($result)) {
				$link[] = $data;
			}
			
			$lifirstclass=" class='home'";

echo"\n<ul id='menu'><li$lifirstclass><a href='".BASEDIR."index.php'><span>Home</span></a></li>\n";
			$i = 0;
			$flysub_class = "";
			
			foreach($link as $data) {
				if (checkgroup($data['link_visibility'])) {
					$link_target = $data['link_window'] == "1" ? " target='_blank'" : "";
					$li_class = preg_match("/^".preg_quote(START_PAGE, '/')."/i", $data['link_url']) ? " class='current'" : "";
					
					if (strstr($data['link_name'], "%submenu% ")) {
						echo "<li$li_class><a href='".BASEDIR.$data['link_url']."'$link_target><span>".parseubb(str_replace("%submenu% ", "",$data['link_name']), "b|i|u|color")."</span></a>\n";
						echo "<ul>\n";
						$i++;
					} elseif (strstr($data['link_name'], "%endmenu% ")) {
						echo "<li$li_class><a href='".BASEDIR.$data['link_url']."'$link_target><span>".parseubb(str_replace("%endmenu% ", "",$data['link_name']), "b|i|u|color")."</span></a></li>\n";
						echo "</ul>\n";
						echo "</li>\n";
					} elseif (preg_match("!(ht|f)tp(s)?://!i", $data['link_url'])) {
						// Some magic here
						echo "<li$li_class><a href='".str_replace(array("%endmenu% ", "%submenu% "), "", $data['link_url'])."'$link_target><span>".parseubb($data['link_name'], "b|i|u|color")."</span></a></li>\n";
					} else {
						echo "<li$li_class><a href='".BASEDIR.$data['link_url']."'$link_target><span>".parseubb($data['link_name'], "b|i|u|color")."</span></a></li>\n";
					}
				}
			}
			echo "</ul>\n";
		}
	} else {
		
	}
}

add_to_footer("<script type='text/javascript'>
	$(function() {
	if ($.browser.msie && $.browser.version.substr(0,1)<7)
	{
	$('li').has('ul').mouseover(function(){
	$(this).children('ul').css('visibility','visible');
	}).mouseout(function(){
	$(this).children('ul').css('visibility','hidden');
	})
	}

	
	</script>");
	
	///////////////////////
	
	
function showomnicopyright($url = "http://www.phpfusionmods.co.uk", $author = "Craig") {

	$copyright = "Omni Responsive Theme By <a href='".$url."' target='_blank'>".$author."</a>";

	return $copyright;
}

?>