<?php
require_once "maincore.php";
include THEME."theme.php";

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta http-equiv='refresh' content='".(isset($_GET['double'])?10:5)."; url=".urldecode($_GET['return_lnk'])."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<style type='text/css'>html, body { height:100%; }</style>\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' />\n";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "</head>\n<body class='tbl2'>\n";
//include(forum/index.php);
echo "<table style='width:100%;height:100%' class='bg_banner'>\n<tr>\n<td>\n";

echo "<table cellpadding='0' cellspacing='1' width='450' align='center' class='bg_banner'>\n<tr>\n";
echo "<td>\n";

openside($settings['sitename']);
echo "<div align='center' class='bg_banner'><br />\n";
if (iMEMBER && (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes")) {
                header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
                unset($_SESSION['user_name'], $_SESSION['user_id'], $_SESSION['user_password'], $_SESSION['lastvisit']);
                $result = dbquery("DELETE FROM ".DB_ONLINE." WHERE online_ip='".USER_IP."'");
                echo "<strong>".$locale['global_192'].$userdata['user_name']."</strong><br /><br />\n";
				if (isset($_GET['double'])) {
					echo "<font color=red>User ID mà bạn dùng đang bị người khác sử dụng trên một máy tính khác. Do đó, kết nối hiện tại sẽ bị ngắt.<br></font>";
				}
} else {
        if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo "<strong>".$locale['global_194']."</strong><br /><br />\n";
        } elseif (isset($_GET['error']) && $_GET['error'] == 2) {
                echo "<strong>".$locale['global_195']."</strong><br /><br />\n";
        } elseif (isset($_GET['error']) && $_GET['error'] == 3) {
                echo "<strong>".$locale['global_196']."</strong><br /><br />\n";
        } else {
                if($_SESSION['user_name'] != "") {
                        $user_pass = $_SESSION['user_password'];
                        $user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_GET['user']));
                        if (!dbcount("(user_id)", DB_USERS, "user_name='".$user_name."' AND user_password='".$user_pass."'")) {
                                echo "<strong>".$locale['global_196']."</strong><br /><br />\n";
                        } else {
                                $result = dbquery("DELETE FROM ".DB_ONLINE." WHERE online_user='0' AND online_ip='".USER_IP."'");
                                $sql = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name = '".$user_name."' AND user_password = '".$user_pass."';");
                                $data = dbarray($sql);
                                if($data['user_avatar'] == "") {
                                        $user_avatar = "avatars/noavatar.gif";
                                } else if(stristr($data['user_avatar'], "[".$data['user_id']."].")) {
                                       $user_avatar = "user_avatars/".$data['user_avatar'];
                                } else $user_avatar = "avatars/".$data['user_avatar'];
                                echo "<strong>".$locale['global_193'].color_group($_GET['user'], $data['user_level'])."</strong>\n";
                                echo "&nbsp;<br />\n";
                                if($data['user_avatar']) echo "<div style='padding: 10px'><img class='textbox' src='".IMAGES.$user_avatar."' border=0></div>\n";
                        }
                }
        }
}

echo $locale['global_197']."<br /><br />\n";

closeside();

echo "</div>\n</td>\n</tr>\n</table>\n";

echo "</td>\n</tr>\n</table>\n";

echo "<script>\n";

mysql_close();

ob_end_flush();
?>
