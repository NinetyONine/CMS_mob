<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2014 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Omni Theme
| Filename: render_news-articles.php
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

function render_news($subject, $news, $info) {
global $locale, $settings, $aidlink;

	set_image("edit", THEME."images/icons/news_edit.png");

	$breaking_news = 7200; //Breaking News Time (1 Hour/60 Mins/3600 Seconds)
	if(time()-$info['news_date'] < $breaking_news) {
	echo "<div class='floatfix'>
	<div class='cap'>
	<div class='new-news'>".$locale['omni_003']."</div>\n
	<div class='triangle-new'>
	<div style='margin-left: 10px;'>\n";
	echo $subject." </div>\n";
	echo "</div>\n";

	}else{
	echo "<div class='floatfix'>
	<div class='cap'>
	<div class='triangle'>
	<div style='margin-left: 10px;'>\n";
	echo $subject."</div>\n";
	echo "</div>\n";
	}


	echo"</div>\n";
	echo"</div>\n";
	echo "<div class='spacer'>\n";
	echo "<div class='news_info middle-border floatfix'>\n";
	echo "<ul>\n";
	echo "<li class='print'><a href='".BASEDIR."print.php?type=N&amp;item_id=".$info['news_id']."'><span>".$locale['global_075']."</span></a></li>\n";

	$date = 1;

	if ($date !== 1) {
	echo "<li class='date'>".showdate($settings['newsdate'], $info['news_date'])."</li>\n";
	}
	echo "<li class='author'>".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</li>\n";
	if ($date == 1) {
	if ($info['cat_id']) { echo "<li class='cat'><a href='".BASEDIR."news_cats.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a></li>\n";
	} else { echo "<li class='cat'><a href='".BASEDIR."news_cats.php?cat_id=0'>".$locale['global_080']."</a></li>\n"; }
	}
	echo "<li class='reads'>".$info['news_reads'].$locale['global_074']."</li>\n"; 
	if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") {
	echo "<li class='comments'><a href='".BASEDIR."news.php?readmore=".$info['news_id']."#comments'>".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a></li>\n"; }

	if (iADMIN && checkrights("N")) {
	echo"<li style='padding-top: 6px; float: right;'>";
	echo "<a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$info['news_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' /></a>\n";
	echo"</li>";
	}
	echo "</ul>\n";
	echo "</div>\n";
	echo "<div class='main-body floatfix'>\n";
	if ($info['news_sticky'] == "1") {
	echo "<div style='position:absolute; padding-top:3px;'><img src='".THEME."images/icons/sticky.png' alt='".$locale['omni_004']."' width='41px' border='0' height='41px' /></div>";
	}

	if ($date == 1) {
	echo "<div class='news-img-div'>
	<a title='".$locale['omni_005'].showdate("%B %d %Y at %H:%M", $info['news_date'])."' href='".BASEDIR."news.php?readmore=".$info['news_id']."'> 
	<div class='news-published'>
	<span class='news-pub-month'>".date("F", $info['news_date'])."</span>
	<span class='news-pub-date'>".date("d", $info['news_date'])."</span>
	<span class='news-pub-year'>".date("Y", $info['news_date'])."</span>
	</div></a></div>";

	echo"<div class='floatfix'>".$news."</div>\n";
	}else{

	echo $info['cat_image'].$news."<br />\n";
	}

	if (!isset($_GET['readmore']) && $info['news_ext'] == "y") {
	echo "<div class='flright'>\n";
	echo "<a href='".BASEDIR."news.php?readmore=".$info['news_id']."' class='button'>".$locale['global_072']." &raquo;</a>\n";
	echo "</div>\n";
	}
	echo "</div>\n";
	echo "</div>\n";

	}

	function render_article($subject, $article, $info) {
	global $locale, $settings, $aidlink;

	set_image("edit", THEME."images/icons/article_edit.png");

	echo "<div class='capmain-top'></div>\n";
	echo "<div class='capmain-articles floatfix'>\n";
	echo "<div class='flleft'>".$subject."</div>\n";
	if (iADMIN && checkrights("A")) {
	echo "<div class='flright clearfix' style='padding-right: 13px;'>\n";
	echo "<a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$info['article_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' /></a>\n";
	echo "</div>\n"; }
	echo "</div>\n";
	echo "<div class='spacer'>\n";
	echo "<div class='news_info middle-border floatfix'>\n";
	echo "<ul>\n";
	echo "<li class='print'><a href='".BASEDIR."print.php?type=A&amp;item_id=".$info['article_id']."'><span>".$locale['global_075']."</span></a></li>\n";
	echo "<li class='date'>".showdate("%d %b %Y", $info['article_date'])."</li>\n";
	echo "<li class='author'>".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</li>\n";
	if ($info['cat_id']) {
	echo "<li class='cat'><a href='".BASEDIR."articles.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a></li>\n";
	} else { echo "<li class='cat'><a href='".BASEDIR."articles.php?cat_id=0'>".$locale['global_080']."</a></li>\n"; }
	echo "<li class='reads'>".$info['article_reads'].$locale['global_074']."</li>\n";
	if ($info['article_allow_comments'] && $settings['comments_enabled'] == "1") {
	echo "<li class='comments'><a href='".BASEDIR."articles.php?article_id=".$info['article_id']."#comments'>".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a></li>\n"; }
	echo "</ul>\n";
	echo "</div>\n";
	echo "<div class='main-body floatfix'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>\n";
	echo "</div>\n";

}

?>