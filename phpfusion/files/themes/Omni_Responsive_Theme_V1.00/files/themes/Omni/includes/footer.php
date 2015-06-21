<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: footer.php
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

global $locale, $aidlink;

echo "<footer>
		<div class='resp-grid'>
		<div class='row'>
		<section class='col-1-3'>
		<div class='heading'>".$locale['omni_006']."</div>
		<div class='content'>";
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_lastvisit !='0'  AND user_status ='0' ORDER BY  user_lastvisit DESC LIMIT 6");
	if (dbrows($result)) {
			while ($data = dbarray($result)) {

	$lseen = time() - stripinput($data['user_lastvisit']);
	if($lseen < 60) { 
	if ($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar']) && $data['user_status']!=6 && $data['user_status']!=5) { echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'><img class='lstsn-users-online' src='".IMAGES."avatars/".$data['user_avatar']."'   title='".$data['user_name'].$locale['omni_007']."' alt='' /></a>";
	} else { echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'><img class='lstsn-users-online' src='".IMAGES."avatars/noavatar100.png' alt=''  title=' ".$data['user_name'].$locale['omni_007']."' /></a>";
	} 
	} elseif($lseen < 300) {  if ($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar']) && $data['user_status']!=6 && $data['user_status']!=5) { echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'><img class='lstsn-users-five lstsn-user' src='".IMAGES."avatars/".$data['user_avatar']."'   title='".$data['user_name'].$locale['omni_008']."' alt='' /></a>";
	} else { echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'><img class='lstsn-users-five lstsn-user' src='".IMAGES."avatars/noavatar100.png'  alt=''  title=' ".$data['user_name'].$locale['omni_008']."' /></a>";
	} 
	}else{ if ($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar']) && $data['user_status']!=6 && $data['user_status']!=5) { echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'><img class='lstsn-users-offline lstsn-user' src='".IMAGES."avatars/".$data['user_avatar']."'   title='".$data['user_name'].$locale['omni_009'].showdate("forumdate", $data['user_lastvisit'])."' alt='' /></a>";
	} else { echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'><img class='lstsn-users-offline lstsn-user' src='".IMAGES."avatars/noavatar100.png' alt=''  title=' ".$data['user_name'].$locale['omni_009'].showdate("forumdate", $data['user_lastvisit'])."' /></a>";
	}
	}
	}
	}

	$result = dbquery("SELECT * FROM ".DB_ONLINE." WHERE online_user=".($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".USER_IP."'"));
	if (dbrows($result)) {
	$result = dbquery("UPDATE ".DB_ONLINE." SET online_lastactive='".time()."' WHERE online_user=".($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".USER_IP."'")."");
	} else {
	$result = dbquery("INSERT INTO ".DB_ONLINE." (online_user, online_ip, online_lastactive) VALUES ('".($userdata['user_level'] != 0 ? $userdata['user_id'] : "0")."', '".USER_IP."', '".time()."')");
	}
	$result = dbquery("DELETE FROM ".DB_ONLINE." WHERE online_lastactive<".(time()-60)."");

	$result = dbquery("SELECT ton.*, tu.user_id,
	tu.user_name, tu.user_status,
	tu.user_level FROM ".DB_ONLINE." ton
	LEFT JOIN ".DB_USERS." tu ON ton.online_user=tu.user_id");

	$guests = 0; $members = array();
	while ($data = dbarray($result)) {
		if ($data['online_user'] == "0") {
		$guests++;
		} else {
		array_push($members, array($data['user_id'], $data['user_name'], $data['user_status'], $data['user_level']));
		}
		}
	$count_total = dbcount("(online_user)", DB_ONLINE, (iMEMBER ? "online_user='".$userdata['user_id']."'" : "online_user='0' AND online_ip='".USER_IP."'") == 1);
	$new_members_today = number_format(dbcount("(user_id)", DB_USERS, "user_status<='1' AND user_joined > UNIX_TIMESTAMP(CURDATE())"));
	//Guests and Members online
	echo "<div id='users'>".$locale['omni_010'].": <strong> ".$count_total."</strong>&nbsp;::&nbsp;".$locale['omni_011'].": <strong>".count($members)."</strong>&nbsp;::&nbsp;".$locale['omni_012'].": <strong>".$guests."</strong>";
	echo"<br />".$locale['omni_013'].": <strong>".number_format(dbcount("(user_id)", DB_USERS, "user_status<='1' AND user_lastvisit > UNIX_TIMESTAMP(CURDATE())"))."</strong>";
	echo"<br />".$locale['omni_014'].": <strong>".$new_members_today."</strong>";
	$count_new = dbcount("(user_id)", DB_USERS, "user_status='2'");

	if (iADMIN && checkrights("M") && $settings['admin_activation'] == "1" && $count_new > 0) {
	echo "<br /><strong><span style='color:#FF6100;'>".$locale['omni_015'].":</span> <a href='".ADMIN."members.php".$aidlink."&amp;status=2'>".$locale['omni_016']."</a>\n";
	echo ":</strong> ".dbcount("(user_id)", DB_USERS, "user_status='2'")."\n";
	}
	
	
	echo"</div>\n
	</section>\n";
	
	
	echo"<section class='col-1-3'>
	<div class='heading' style=''>".$locale['omni_017']."</div>\n
	<div class='content' style=''> ".$locale['omni_018']."
	<div style='padding-top: 3px;'>";

	$members_registered = dbcount("(user_id)", DB_USERS, "user_status<='1' OR user_status='3' OR user_status='5'");
	$members_today = number_format(dbcount("(user_id)", DB_USERS, "user_status<='1' AND user_lastvisit > UNIX_TIMESTAMP(CURDATE())"));

	$downloads = dbcount("(download_id)", DB_DOWNLOADS);
	$counter = "<strong>".number_format($settings['counter'])."</strong> ".($settings['counter'] == 1 ? $locale['global_170'] : $locale['global_171']."");
	$threads = dbcount("(thread_id)", DB_THREADS);
	$posts = dbcount("(post_id)", DB_POSTS);
	$comments = dbcount("(comment_id)", DB_COMMENTS);
	include_once INFUSIONS."shoutbox_panel/infusion_db.php";
	$shouts = dbcount("(shout_id)", DB_SHOUTBOX);
	$site_opened = dbarray(dbquery("SELECT user_id, user_joined FROM ".DB_USERS." WHERE user_id='1'"));
	echo $locale['stat005']."".$counter."".$locale['stat006']."<strong>".$members_registered."</strong>".$locale['stat007']." <strong>".showdate("%B %d %Y", $site_opened['user_joined'])."</strong>.   <br /><strong>".$members_today."</strong> ".($members_today == 1 ? $locale['stat001'] : $locale['stat002']."").$locale['stat008']."<strong>".$new_members_today."</strong>".($new_members_today == 1 ? $locale['stat017'] : $locale['stat018']).$locale['stat010']."<br />\n";
	echo $locale['stat011']."<strong>".$downloads."</strong>".$locale['stat012']." \n";

	$total_downloaded = dbresult(dbquery("SELECT SUM(download_count) FROM ".DB_DOWNLOADS), 0);

	echo $locale['stat019']."<strong>".$total_downloaded."</strong>".$locale['stat020'];
	echo" <strong>".$threads."</strong>".$locale['stat013']."<strong>".$posts."</strong>".$locale['stat014']." &amp; \n";
	echo "<strong>".$comments."</strong>".$locale['stat016']."\n";

	echo"</div>\n
	</div>\n
	</section>\n";
	
	echo"<section class='col-1-3'>
	<div class='heading'>".$locale['omni_019']."</div>\n
	<div class='content'>
	<ul>
	Connect With PHP-Fusion Mods UK!
	Follow, Add, Like Or Subscribe!<br />
	We are on <a title='Facebook' href='http://www.facebook.com/fangreeproductions' target='_blank'>Facebook</a>, <a title='Twitter' href='http://www.twitter.com/fangree' target='_blank'>Twitter</a> &amp;  <a title='YouTube' href='http://www.youtube.com/fangree' target='_blank'>YouTube</a>.
	Also keep up to date with our latest news via our <a title='PHP-Fusion Mods UK RSS News Feed' href='http://www.phpfusionmods.co.uk/news_rss.php' target='_blank'>News RSS Feed</a>!<br />
	<a href='http://www.facebook.com/fangreeproductions' target='_blank'><img class='social-icons' src='".THEME."images/fb.png'  alt='PHP-Fusion Mods UK On Facebook' title='PHP-Fusion Mods UK On Facebook' /></a>
	<a href='http://www.twitter.com/fangree' target='_blank'><img class='social-icons' src='".THEME."images/twitterb.png'  alt='PHP-Fusion Mods UK On Twitter' title='PHP-Fusion Mods UK On Twitter' /></a>
	<a href='http://www.youtube.com/fangree' target='_blank'><img class='social-icons' src='".THEME."images/youtube.png'  alt='PHP-Fusion Mods UK On YouTube' title='PHP-Fusion Mods UK On YouTube' /></a>
	<a href='http://www.phpfusionmods.co.uk/news_rss.php' target='_blank'><img class='social-icons' src='".THEME."images/rss.png'  alt='PHP-Fusion Mods UK RSS News Feed' title='PHP-Fusion Mods UK RSS News Feed' /></a>

	<br /><br /><div id='fb-root'></div>\n
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = '//connect.facebook.net/en_GB/all.js#xfbml=1&appId=614061355285635';
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<br /><div class='fb-like' data-href='https://www.facebook.com/pfmodsuk' data-width='50' data-height='20' data-colorscheme='dark' data-layout='button_count' data-action='like' data-show-faces='true' data-send='false'></div>
	&nbsp;&nbsp; <a href='https://twitter.com/share' class='twitter-share-button' data-text='Visit PHP-Fusion Mods UK For Free PHP-Fusion Addons & Themes.'data-url='http://www.phpfusionmods.co.uk' data-lang='en'>Tweet</a>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>
	</div>\n
	</section>\n";
	echo"</div>\n
	</div>\n
	</footer>";

	echo"<div id='copyright'>
	<div class='left-copyright'>".stripslashes($settings['footer'])."";
	//Removal of this copyright is strictly prohibited without written permission from the original author(s).
	if (!$license) { echo "<p>".showcopyright()."</p>"; }
	echo"</div>\n";
	//Removal of this copyright is strictly prohibited without written permission from the original author(s).
	echo"<div class='right-copyright'>".showomnicopyright();

	if($settings['visitorcounter_enabled']) {
	echo"<br />".showcounter();
	}
	if ($settings['rendertime_enabled'] =='1' || $settings['rendertime_enabled'] =='2') {
	if($settings['visitorcounter_enabled']) { echo" | "; }
	echo showrendertime();
	}
	
	echo"<br />Your Screen Resolution is 
	<script type='text/javascript'>
	document.write(screen.width+' x '+screen.height);
	</script>
	</div>\n
	</div>\n";
	
?>