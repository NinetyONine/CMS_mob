<?php
/**
 * @version 1.0
 * @copyright Copyright (C) 2014 rayzzz.com. All rights reserved.
 * @license GNU/GPL2, see LICENSE.txt
 * @website http://rayzzz.com
 * @twitter @rayzzzcom
 * @email rayzexpert@gmail.com
 */
require_once "../../maincore.php";
require_once THEMES."templates/header.php";

if (!defined("IN_FUSION")) { die("Access Denied"); }
$sIncFile = dirname(__FILE__) . "/include/init.php";
if(!file_exists($sIncFile))
	die("Init file not found");
	
require_once($sIncFile);
include_once INCLUDES."infusions_include.php";
$sApp = iADMIN ? "admin" : "user";
openside(RzboardInit::$aRzInfo['title']);
?>
<script type="text/javascript" src="js/swfobject.js"></script>
<div id="rz_app"></div>
<script type="text/javascript">
	swfobject.embedSWF("app/user.swf", "rz_app", "<?php echo RzboardInit::$aRzInfo['width'];?>", "<?php echo RzboardInit::$aRzInfo['height'];?>", "10", "app/expressInstall.swf", {app:"<?php echo $sApp;?>",url:"XML.php",id:"<?php echo $userdata['user_id'];?>",password:"<?php echo $userdata['user_password'];?>"}, {allowScriptAccess:"always",allowFullScreen:"true",base:"",wmode:"opaque"}, {style:"display:block;"});
</script>

<?php
closeside();
require_once THEMES."templates/footer.php";
?>