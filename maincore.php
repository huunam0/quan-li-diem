<?php
if (@eregi("maincore.php", $_SERVER['PHP_SELF'])) { die(); }
header('Expires: Mon, 26 Jul 2007 05:00:00 GMT');
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
session_start();
//error_reporting(0);//disable notice-warning (to enable, delete number 0)
date_default_timezone_set("Asia/Ho_Chi_Minh");
if (ini_get('register_globals') != 1) {
      if ((isset($_POST) == true) && (is_array($_POST) == true)) extract($_POST, EXTR_OVERWRITE);
      if ((isset($_GET) == true) && (is_array($_GET) == true)) extract($_GET, EXTR_OVERWRITE);
}

foreach ($_GET as $check_url) {
        $check_url = str_replace("\"", "", $check_url);
        if ((eregi("<[^>]*script*\"?[^>]*>", $check_url)) || (eregi("<[^>]*object*\"?[^>]*>", $check_url)) ||
                (eregi("<[^>]*iframe*\"?[^>]*>", $check_url)) || (eregi("<[^>]*applet*\"?[^>]*>", $check_url)) ||
                (eregi("<[^>]*meta*\"?[^>]*>", $check_url)) || (eregi("<[^>]*style*\"?[^>]*>", $check_url)) ||
                (eregi("<[^>]*form*\"?[^>]*>", $check_url)) || (eregi("\([^>]*\"?[^)]*\)", $check_url)) ||
                (eregi("\"", $check_url))) {
        die ();
        }
}
unset($check_url);

ob_start();

$folder_level = ""; $i = 0;
while (!file_exists($folder_level."config.php")) {
        $folder_level .= "../"; $i++;
        if ($i == 5) { die("Config file not found"); }
}
require_once $folder_level."config.php";
define("BASEDIR", $folder_level);

if (!isset($db_name)) { redirect("setup.php"); }

define("COOKIE_PREFIX", "fusion_");
define("DB_ADMIN", DB_PREFIX."admin");
define("DB_BBCODES", DB_PREFIX."bbcodes");
define("DB_BLACKLIST", DB_PREFIX."blacklist");
define("DB_CAPTCHA", DB_PREFIX."captcha");
define("DB_COMMENTS", DB_PREFIX."comments");
define("DB_CUSTOM_PAGES", DB_PREFIX."custom_pages");
define("DB_FAQ_CATS", DB_PREFIX."faq_cats");
define("DB_FAQS", DB_PREFIX."faqs");
define("DB_FLOOD_CONTROL", DB_PREFIX."flood_control");
define("DB_FORUM_ATTACHMENTS", DB_PREFIX."forum_attachments");
define("DB_FORUM_POLL_OPTIONS", DB_PREFIX."forum_poll_options");
define("DB_FORUM_POLL_VOTERS", DB_PREFIX."forum_poll_voters");
define("DB_FORUM_POLLS", DB_PREFIX."forum_polls");
define("DB_FORUM_RANKS", DB_PREFIX."forum_ranks");
define("DB_FORUMS", DB_PREFIX."forums");
define("DB_MESSAGES", DB_PREFIX."messages");
define("DB_MESSAGES_OPTIONS", DB_PREFIX."messages_options");
define("DB_NEW_USERS", DB_PREFIX."new_users");
define("DB_ONLINE", DB_PREFIX."online");
define("DB_PANELS", DB_PREFIX."panels");
define("DB_POLL_VOTES", DB_PREFIX."poll_votes");
define("DB_POLLS", DB_PREFIX."polls");
define("DB_POSTS", DB_PREFIX."posts");
define("DB_RATINGS", DB_PREFIX."ratings");
define("DB_SETTINGS", DB_PREFIX."settings");
define("DB_SITE_LINKS", DB_PREFIX."site_links");
define("DB_SMILEYS", DB_PREFIX."smileys");
define("DB_THREAD_NOTIFY", DB_PREFIX."thread_notify");
define("DB_THREADS", DB_PREFIX."threads");
define("DB_USER_FIELDS", DB_PREFIX."user_fields");
define("DB_USER_GROUPS", DB_PREFIX."user_groups");
define("DB_USERS", DB_PREFIX."users");
define("DB_NEWS", DB_PREFIX."tintuc");
define("MAIL_DOMAINE","@qltruong.com");

include BASEDIR."functions.php";

$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
mysql_query("SET NAMES utf8");
$settings = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));

define("START_TIME", get_microtime());

$_SERVER['PHP_SELF'] = cleanurl($_SERVER['PHP_SELF']);
$_SERVER['QUERY_STRING'] = isset($_SERVER['QUERY_STRING']) ? cleanurl($_SERVER['QUERY_STRING']) : "";
$_SERVER['REQUEST_URI'] = isset($_SERVER['REQUEST_URI']) ? cleanurl($_SERVER['REQUEST_URI']) : "";
$PHP_SELF = cleanurl($_SERVER['PHP_SELF']);

define("QLTRUONG", TRUE);
define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "");
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("USER_IP", $_SERVER['REMOTE_ADDR']);
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));

define("ADMIN", BASEDIR."administration/");
define("IMAGES", BASEDIR."images/");
define("RANKS", IMAGES."ranks/");
define("INCLUDES", BASEDIR."includes/");
define("LOCALE", BASEDIR."locale/");
define("LOCALESET", $settings['locale']."/");
define("FORUM", BASEDIR."forum/");
define("MODULES", BASEDIR."modules/");
define("THEMES", BASEDIR."themes/");
define("THEME", THEMES.$settings['theme']."/"); 
$smiley_cache = ""; $bbcode_cache = ""; $groups_cache = ""; $forum_rank_cache = ""; $forum_mod_rank_cache = "";
$hpanel=true;
$locale = array();

include LOCALE.LOCALESET."global.php";

$sub_ip1 = substr(USER_IP, 0, strlen(USER_IP) - strlen(strrchr(USER_IP, ".")));
$sub_ip2 = substr($sub_ip1, 0, strlen($sub_ip1) - strlen(strrchr($sub_ip1, ".")));

if (dbcount("(*)", DB_BLACKLIST, "blacklist_ip='".USER_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'")) {
}
if (!isset($_COOKIE[COOKIE_PREFIX.'visited'])) {
        $result = dbquery("UPDATE ".DB_SETTINGS." SET counter=counter+1");
        setcookie(COOKIE_PREFIX."visited", "yes", time() + 31536000, "/", "", "0");
}

if(isset($_POST['login'])) {
        $user_pass = md5($_POST['user_pass']);
		//$user_pass = md5('tandau');
        $user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_POST['user_name']));
		$user_id = $_POST['user_id'];
		//echo "SELECT * FROM ".DB_USERS." WHERE user_id=$user_id AND user_password='".md5($user_pass)."' LIMIT 1";
        $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id=$user_id AND user_password='".md5($user_pass)."' LIMIT 1");
        $return_lnk = $_POST['return_lnk'];
        if (dbrows($result)) {
                $data = dbarray($result);
				$result2=dbquery("update qlt_users set user_ip='".USER_IP."' where user_id=".$user_id);
                if ($data['user_status'] == 0) {
                        header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
                        $_SESSION['user_name'] = $data['user_name'];
                        $_SESSION['user_id'] = $data['user_id'];
                        $_SESSION['user_password'] = $data['user_password'];
                        redirect(BASEDIR."setuser.php?return_lnk=".$return_lnk."&user=".$data['user_name'], true);
                } elseif ($data['user_status'] == 1) {
                        redirect(BASEDIR."setuser.php?return_lnk=".$return_lnk."&error=1", true);
                } elseif ($data['user_status'] == 2) {
                        redirect(BASEDIR."setuser.php?return_lnk=".$return_lnk."&error=2", true);
                }
        } else {
                //echo "khong dang nhap duoc:";
				redirect(BASEDIR."setuser.php?return_lnk=".$return_lnk."&error=3");
        }
}

if(isset($_SESSION['user_name'])) {
        $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_SESSION['user_id']."' AND user_password='".$_SESSION['user_password']."' LIMIT 1");
        unset($cookie_vars,$cookie_1,$cookie_2);
        if (dbrows($result)) {
                $userdata = dbarray($result);
                $madonvi=$userdata['donvi'];
                $donvi = dblookup("ten","qlt_donvi","id=".$userdata['donvi']);
                $macaptren =dblookup("captren","qlt_donvi","id=$madonvi"); 
                $captren =dblookup("ten","qlt_donvi","id=$macaptren"); 
                if ($userdata['user_status'] == 0) {
                        if (!isset($_SESSION['lastvisit'])) {
                                $result = dbquery("UPDATE ".DB_USERS." SET user_threads='' WHERE user_id='".$userdata['user_id']."'");
                                $_SESSION['lastvisit'] = $userdata['user_lastvisit'];
                                $lastvisited = $userdata['user_lastvisit'];
                        } else {
                                $lastvisited = $_SESSION['lastvisit'];
                        }
                } else {
                        header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
                        setcookie(COOKIE_PREFIX."user", "", time() - 7200, "/", "", "0");
                        setcookie(COOKIE_PREFIX."lastvisit", "", time() - 7200, "/", "", "0");
						//echo "loi maincore.php line 169";
                        redirect(BASEDIR."index.php", true);
                }
        } else {
                header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
                setcookie(COOKIE_PREFIX."user", "", time() - 7200, "/", "", "0");
                setcookie(COOKIE_PREFIX."lastvisit", "", time() - 7200, "/", "", "0");
                //redirect(BASEDIR."setuser.php?logout=yes&return_lnk=index.php", true);
				//echo "loi maincore line 174";
				//unset($_SESSION['user_name'], $_SESSION['user_id'], $_SESSION['user_password'], $_SESSION['lastvisit']);
        }
} else {
        unset($_SESSION);
        unset($userdata, $userdata['user_level'], $userdata['user_rights'], $userdata['user_groups']);
}
define("iGUEST", $userdata['user_level'] == 0 ? 1 : 0);
define("iMEMBER", $userdata['user_level'] >= 101 ? 1 : 0);
define("iADMIN", $userdata['user_level'] >= 102 ? 1 : 0);
define("iSUPERADMIN", $userdata['user_level'] == 103 ? 1 : 0);
define("iUSER", $userdata['user_level']);
define("iUSER_RIGHTS", $userdata['user_rights']);
define("iUSER_GROUPS", substr($userdata['user_groups'], 1));

if (iADMIN) {
        define("iAUTH", substr($userdata['user_password'], 16, 32));
        $aidlink = "?aid=".iAUTH;
}

cache_smileys();
$smiley_images = array();
foreach ($smiley_cache as $smiley) {
        $smiley_images["smiley_".$smiley['smiley_text']] = IMAGES."smiley/".$smiley['smiley_image'];
}

$result = dbquery("SELECT admin_title, admin_image FROM ".DB_ADMIN);
$ac_images = array();
while($data = dbarray($result)){
        $ac_images["ac_".$data['admin_title']] = file_exists(ADMIN."images/".$data['admin_image']) ? ADMIN."images/".$data['admin_image'] : ADMIN."images/infusion_panel.gif";
}

$images = array(
        "blank" => THEME."images/blank.gif",
        "down" => THEME."images/down.gif",
        "edit" => BASEDIR."images/edit.gif",
        "folder" => THEME."forum/folder.gif",
        "folderlock" => THEME."forum/folderlock.gif",
        "foldernew" => THEME."forum/foldernew.gif",
        "forum_edit" => THEME."forum/edit.gif",
        "imagenotfound" => IMAGES."imagenotfound.jpg",
        "left" => THEME."images/left.gif",
        "newthread" => THEME."forum/newthread.gif",
        "navbits_start" => THEME."forum/navbits_start.gif",
        "navbits_finallink" => THEME."forum/navbits_finallink.gif",
        "panel_on" => THEME."images/panel_on.gif",
        "panel_off" => THEME."images/panel_off.gif",
        "pm" => THEME."forum/pm.gif",
        "pollbar" => THEME."images/pollbar.gif",
        "printer" => THEME."images/printer.gif",
        "post_title" => THEME."forum/post_title.gif",
        "quote" => THEME."forum/quote.gif",
        "reply" => THEME."forum/reply.gif",
        "right" => THEME."images/right.gif",
        "star" => IMAGES."star.gif",
        "stickythread" => THEME."forum/stickythread.gif",
        "tree" => THEME."forum/tree.gif",
        "up" => THEME."images/up.gif",
        "web" => THEME."forum/web.gif"
);

$fusion_images = array_merge($ac_images, $images, $smiley_images);
$ctoday=getdate();
$current_year = $ctoday['year'];
$today=YYMMDD($ctoday['year'],$ctoday['mon'],$ctoday['mday']);

/*
if(iGUEST) $header_reg = <<<NP
<style>
a#floatingbar, a:link#floatingbar, a#active.floatingbar, a:hover#floatingbar {
	background-color: #F1EEC8;
	position: fixed;
	display:block;
	top: 0;
	left: 0;
	z-index: 100;
	border-bottom:1px solid gray;
	width: 100%;
	padding:5px 10px;
	color:red;
	font-size:11px;
	font-family:tahoma;
	margin:0;
	text-decoration:none;
}
a:hover#floatingbar { background-color: #fff; color:#000; }
</style>
NP;
<a href="../register.php" id="floatingbar">&nbsp;[Click] Để đăng ký thành viên Forum Thpt Nguyễn Du nào! [Nếu đã là thành viên thì đăng nhập đi bạn ơi!]</a>
*/
?>
