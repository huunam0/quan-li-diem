<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }
require_once INCLUDES."output_handling_include.php";
require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) { redirect(BASEDIR."maintenance.php"); }
//if (iMEMBER) { $result = dbquery("UPDATE ".DB_USERS." SET user_lastvisit='".time()."' WHERE user_id='".$userdata['user_id']."'"); }


echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>Quan li diem</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' media='screen' />\n";
echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n";

if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "<script type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."jquery.js'></script>\n";

echo "</head>\n<body>\n";
if (iMEMBER &&($userdata['user_ip']!=USER_IP)) {
	//echo "Many users logging same time";
	redirect(BASEDIR."setuser.php?logout=yes&return_lnk=".urlencode(FUSION_REQUEST)."&double");
}
//echo $leftpanel;
echo "<div style='left: 100px; top: 3px; ' id='dhtmltooltip'>This DIV has a tip!!</div>";
echo "<script type='text/javascript' src='tooltip.js'></script>";
require_once THEMES."templates/panels.php";

ob_start();
if (isset($donvi)) echo "<div class='admin-message'><h2>$donvi - $captren </h2></div>";
?>
