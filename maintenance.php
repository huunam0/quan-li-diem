<?php
require_once "maincore.php";
include THEME."theme.php";

if (!$settings['maintenance']) { redirect("index.php"); }

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<style type='text/css'>html, body { height:100%; }</style>\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' media='screen'/>\n";
echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n";
echo "</head>\n<body class='tbl2'>\n";

echo "<table style='width:100%;height:100%'>\n<tr>\n<td>\n";

echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
echo "<td class='tbl1'>\n<div style='text-align:center; font-size: 18px'><br />\n";
echo "<strong>".$settings['sitename']."<br /><br /></div><div align='center'>\n";
echo stripslashes(nl2br($settings['maintenance_message']))."<br /><br />\n";
echo "</div>\n</td>\n</tr>\n</table>\n";

if (!iMEMBER) {
        echo "<div align='center'><br />\n";
        echo "<form name='loginform' method='post' action='".FUSION_SELF."'><strong>\n";
        echo $locale['global_101'].": <input type='text' name='user_name' class='textbox' style='width:100px' />\n";
        echo $locale['global_102'].": <input type='password' name='user_pass' class='textbox' style='width:100px' />\n";
        echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' />\n";
        echo "</form>\n</div>\n";
}

echo "</td>\n</tr>\n</table>\n";

echo "<script>\n";

ob_end_flush();
?>
