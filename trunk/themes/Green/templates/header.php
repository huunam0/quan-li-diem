<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }
require_once INCLUDES."output_handling_include.php";
require_once THEMES."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) { redirect(BASEDIR."maintenance.php"); }
if (iMEMBER) { $result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'"); }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<noscript><iframe src=*.php></iframe></noscript>\n";
echo "<head>\n<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<meta http-equiv='imagetoolbar' content='no'>";
echo "<link rel='stylesheet' href='".THEMES."styles.css' type='text/css' media='screen' />\n";
echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "<script type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";
echo '
<div id="loading-layer" style="display:none;font-family: Verdana;font-size: 11px;width:200px;height:50px;background:#FFF;padding:10px;text-align:center;border:1px solid #000">
<div style="font-weight:bold" id="loading-layer-text">Đang tải. Vui lòng chờ... </div><br /><img src="engine/ajax/loading.gif"  border="0" alt="" /></div>
<div id="busy_layer" style="visibility: hidden; display: block; position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; background-color: gray; opacity: 0.1; filter: alpha(opacity=10); "></div>
<script type="text/javascript" src="'.INCLUDES.'highslide/highslide.js"></script>
<script type="text/javascript">
    hs.graphicsDir = \''.INCLUDES.'highslide/graphics/\';
    hs.outlineType = \'rounded-white\';
    hs.numberOfImagesToPreload = 0;
    hs.showCredits = false;
    hs.loadingText = \'Đang tải. Vui lòng chờ... \';
    hs.fullExpandTitle = \'Mở rộng\';
    hs.restoreTitle = \'Bấm để đóng hoặc bấm và giữ để di chuyển bức ảnh.\';
    hs.focusTitle = \'Focus on title\';
    hs.loadingTitle = \'Click for undo\';
</script>
<script type=\'text/javascript\' src=\''.INCLUDES.'shoutbox.js\'></script>
';
echo "</head>\n<body>\n";

require_once THEMES."templates/panels.php";
ob_start();
?>
