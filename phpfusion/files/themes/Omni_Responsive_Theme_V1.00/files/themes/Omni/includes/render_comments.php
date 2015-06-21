<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: render_comments.php
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

function render_comments($c_data, $c_info){
	global $locale, $settings;

	if (!empty($c_data)){
		echo "<div class='comments floatfix'>\n";	
	    echo "<div style='margin-bottom: 15px;' class='floatfix'>\n";
		$c_makepagenav = '';
    if ($c_info['c_makepagenav'] !== false) { echo $c_makepagenav = "<div class='flleft'>".$c_info['c_makepagenav']."</div>\n"; }
	if ($c_info['admin_link'] !== false) { echo "<div class='flright'>".$c_info['admin_link']."</div>\n"; }
		echo "</div>\n";
		
		echo "<div class='comment-main'>\n";
	foreach($c_data as $data) {
			$comm_count = "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$data['i']."</a>";
	if ($settings['comments_avatar'] == "1") { 
	    echo "<div class='comment-avatar-wrap'>".$data['user_avatar']."</div>\n";
	}
        echo "<div class='comment'>\n";
		echo "<div class='flright'>".$comm_count."\n</div>\n";
		echo "<div class='user'>".$data['comment_name']."\n";
		echo "<span class='date small'>".$data['comment_datestamp']."</span>\n";
		echo "</div>\n";
		echo "<div class='comment-body'><p>".$data['comment_message']."</p></div>\n";
	if ($data['edit_dell'] !== false) { echo "<span class='comment_actions'>".$data['edit_dell']."\n</span>\n"; }
		echo "</div>\n";
	}
		echo "</div>\n";
		
		echo $c_makepagenav;
		echo "</div>\n";

	} else {
	    echo "<div class='nocomments-message spacer'>".$locale['c101']."</div>\n";
	}

}
?>