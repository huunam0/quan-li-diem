<?php

// Calculate script start/end time
function get_microtime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

// MySQL database functions
function dbquery($query) {
        //echo "<br>".$query;
		$result = @mysql_query($query);
        if (!$result) {
                echo mysql_error();
                return false;
        } else {
                return $result;
        }
}

function dbcount($field, $table, $conditions = "") {
        $cond = ($conditions ? " WHERE ".$conditions : "");
        $result = @mysql_query("SELECT Count".$field." FROM ".$table.$cond);
        if (!$result) {
                echo mysql_error();
                return false;
        } else {
                $rows = mysql_result($result, 0);
                return $rows;
        }
}
function dbcount2($table, $conditions = "") {
        $cond = ($conditions ? " WHERE ".$conditions : "");
        $result = @mysql_query("SELECT Count(*) FROM ".$table.$cond);
        if (!$result) {
                echo mysql_error();
                return false;
        } else {
                $rows = mysql_result($result, 0);
                return $rows;
        }
}
function dbresult($query, $row) {
        $result = @mysql_result($query, $row);
        if (!$result) {
                echo mysql_error();
                return false;
        } else {
                return $result;
        }
}

function dbrows($query) {
        $result = @mysql_num_rows($query);
        return $result;
}

function dbarray($query) {
        $result = @mysql_fetch_assoc($query);
        if (!$result) {
                echo mysql_error();
                return false;
        } else {
                return $result;
        }
}

function dbarraynum($query) {
        $result = @mysql_fetch_row($query);
        if (!$result) {
                echo mysql_error();
                return false;
        } else {
                return $result;
        }
}

function dblookup($field, $table, $conditions = "") {
        $cond = ($conditions ? " WHERE ".$conditions : "");
        $result = @mysql_query("SELECT ".$field." FROM ".$table.$cond);
        if (!$result) {
                echo mysql_error();
                return "";
        } else {
                $data = dbarray($result);
                return $data[$field];
        }
}

function getthamso($thamso) {
	return dblookup("value","qlt_thamso","name=".$thamso."");
}
function getgv($gvid) {
	return dblookup("user_name","qlt_users","user_id=".$gvid."");
}
function getgroups($groups) { //$groups = .14.15.4
	if (!$groups) {
		return "[]";
		exit;
	}
	$result=dbquery("select group_name from qlt_user_groups where instr('$groups.','.'+group_id+'.')>0  ");
	$gr_name="";
	if (dbrows($result)) {
		while ($data=dbarray($result)) {
			$gr_name.="[".$data['group_name']."]";
		}
	}
	return $gr_name;
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
        @mysql_connect($db_host, $db_user, $db_pass) or die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br />".mysql_errno()." : ".mysql_error()."</div>");
        $db_select = @mysql_select_db($db_name);
		mysql_query("SET CHARACTER SET 'utf8'");//Bo sung de hien thi tieng viet
        if (!$db_select) {
                die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br />".mysql_errno()." : ".mysql_error()."</div>");
        }
}

function redirect($location, $delaytime = 0) {
	if ($delaytime>0) {	
		header( "refresh: $delaytime; url='".str_replace("&amp;", "&", $location)."'" );
	} else {
        	header("Location: ".str_replace("&amp;", "&", $location));
	}
        
}

// Clean URL Function, prevents entities in server globals
function cleanurl($url) {
        $bad_entities = array("&", "\"", "'", '\"', "\'", "<", ">", "(", ")", "*");
        $safe_entities = array("&amp;", "", "", "", "", "", "", "", "", "");
        $url = str_replace($bad_entities, $safe_entities, $url);
        return $url;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
        global $_SESSION;
        if (QUOTES_GPC) $text = stripslashes($text);
        if($_SESSION['user_id'] == 1) {
                $search = array("&", "\"", "'", "\\", '\"', "\'", "&nbsp;");     //'
                $replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", " ");
        } else {
                $search = array("&", "\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");     //'
                $replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
        }
        $text = str_replace($search, $replace, $text);
        return $text;
}
function stripinput2($text) {
        return $text;
}
// stripslash function, only stripslashes if magic_quotes_gpc is on
function stripslash($text) {
        if (QUOTES_GPC) { $text = stripslashes($text); }
        return $text;
}

// stripslash function, add correct number of slashes depending on quotes_gpc
function addslash($text) {
        if (!QUOTES_GPC) {
                $text = addslashes(addslashes($text));
        } else {
                $text = addslashes($text);
        }
        return $text;
}

// htmlentities is too agressive so we use this function
function phpentities($text) {
        $search = array("&", "\"", "'", "\\", "<", ">");
        $replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
        $text = str_replace($search, $replace, $text);
        return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
        global $str_from, $str_to;
        $dec = array("&", "\"", "'", "\\", '\"', "\'", "<", ">");       //'
        $enc = array("&amp;", "&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
        $text = str_replace($enc, $dec, $text);
        if (strlen($text) > $length) {
                $text = substr($text, 0, ($length-3))."...";
        }
        $text = str_replace($dec, $enc, $text);
        return $text;
}

// Validate numeric input
function isnum($value) {
        if (!is_array($value)) {
                return (preg_match("/^[0-9]+$/", $value));
        } else {
                return false;
        }
}

// custom preg-match function
function preg_check($expression, $value) {
        if (!is_array($value)) {
                return preg_match($expression, $value);
        } else {
                return false;
        }
}

// Cache smileys mysql
function cache_smileys() {
        global $smiley_cache;
        $result = dbquery("SELECT * FROM ".DB_SMILEYS);
        if (dbrows($result)) {
                $smiley_cache = array();
                while ($data = dbarray($result)) {
                        $smiley_cache[] = array(
                                "smiley_code" => $data['smiley_code'],
                                "smiley_image" => $data['smiley_image'],
                                "smiley_text" => $data['smiley_text']
                        );
                }
        } else {
                $smiley_cache = array();
        }
}

// Parse smiley bbcode
function parsesmileys($message) {
        global $smiley_cache;
        if (!preg_match("#(\[code\](.*?)\[/code\]|\[geshi=(.*?)\](.*?)\[/geshi\]|\[php\](.*?)\[/php\])#si", $message)) {
                if (!$smiley_cache) { cache_smileys(); }
                if (is_array($smiley_cache) && count($smiley_cache)) {
                        foreach ($smiley_cache as $smiley) {
                                $smiley_code = preg_quote($smiley['smiley_code']);
                                $smiley_image = "<img src='".get_image("smiley_".$smiley['smiley_text'])."' alt='".$smiley['smiley_text']."' style='vertical-align:middle;' />";
                                $message = preg_replace("#{$smiley_code}#si", $smiley_image, $message);
                        }
                }
        }
        return $message;
}

// Show smiley icons in comments, forum and other post pages
function displaysmileys($textarea, $form = "inputform") {
        global $smiley_cache;
        $smileys = ""; $i = 0;
        if (!$smiley_cache) { cache_smileys(); }
        if (is_array($smiley_cache) && count($smiley_cache)) {
                foreach ($smiley_cache as $smiley) {
                        if ($i != 0 && ($i % 10 == 0)) { $smileys .= "<br />\n"; $i++; }
                        $smileys .= "<img src='".get_image("smiley_".$smiley['smiley_text'])."' alt='".$smiley['smiley_text']."' onclick=\"insertText('".$textarea."', '".$smiley['smiley_code']."', '".$form."');\" />\n";
                }
        }
        return $smileys;
}

// Cache bbcode mysql
function cache_bbcode() {
        global $bbcode_cache;
        $result = dbquery("SELECT * FROM ".DB_BBCODES." ORDER BY bbcode_order ASC");
        if (dbrows($result)) {
                $bbcode_cache = array();
                while ($data = dbarray($result)) {
                        $bbcode_cache[] = $data['bbcode_name'];
                }
        } else {
                $bbcode_cache = array();
        }
}

// Parse bbcode
function parseubb2($text, $selected=false) {
        return $text;
}
function parseubb($text, $selected=false) {
        global $bbcode_cache;
        if (!$bbcode_cache) { cache_bbcode(); }
        if (is_array($bbcode_cache) && count($bbcode_cache)) {
                if ($selected) { $sel_bbcodes = explode("|", $selected); }
                foreach ($bbcode_cache as $bbcode) {
                        if ($selected && in_array($bbcode, $sel_bbcodes)) {
                                if (file_exists(INCLUDES."bbcodes/".$bbcode."_bbcode_include.php")) {
                                        if (file_exists(LOCALE.LOCALESET."bbcodes/".$bbcode.".php")) {
                                                include (LOCALE.LOCALESET."bbcodes/".$bbcode.".php");
                                        }
                                        include (INCLUDES."bbcodes/".$bbcode."_bbcode_include.php");
                                }
                        } elseif (!$selected) {
                                if (file_exists(INCLUDES."bbcodes/".$bbcode."_bbcode_include.php")) {
                                        if (file_exists(LOCALE.LOCALESET."bbcodes/".$bbcode.".php")) {
                                                include (LOCALE.LOCALESET."bbcodes/".$bbcode.".php");
                                        }
                                        include (INCLUDES."bbcodes/".$bbcode."_bbcode_include.php");
                                }
                        }
                }
        }
        $text = descript($text, false);
        return $text;
}
// Remove bbcode
function removeubb($text, $selected=false) {
        global $bbcode_cache;
        if (!$bbcode_cache) { cache_bbcode(); }
        if (is_array($bbcode_cache) && count($bbcode_cache)) {
                if ($selected) { $sel_bbcodes = explode("|", $selected); }
                foreach ($bbcode_cache as $bbcode) {
                        if ($selected && in_array($bbcode, $sel_bbcodes)) {
                                if (file_exists(INCLUDES."bbcodes/".$bbcode."_bbcode_remove.php")) {
                                        include (INCLUDES."bbcodes/".$bbcode."_bbcode_remove.php");
                                }
                        } elseif (!$selected) {
                                if (file_exists(INCLUDES."bbcodes/".$bbcode."_bbcode_remove.php")) {
                                        include (INCLUDES."bbcodes/".$bbcode."_bbcode_remove.php");
                                }
                        }
                }
        }
        $text = descript($text, false);
        return $text;
}
// Javascript email encoder by Tyler Akins
// http://rumkin.com/tools/mailto_encoder/
function hide_email($email, $title = "", $subject = "") {
        if (strpos($email, "@")) {
                $parts = explode("@", $email);
                $MailLink = "<a href='mailto:".$parts[0]."@".$parts[1];
                if ($subject != "") { $MailLink .= "?subject=".urlencode($subject); }
                $MailLink .= "'>".($title?$title:$parts[0]."@".$parts[1])."</a>";
                $MailLetters = "";
                for ($i = 0; $i < strlen($MailLink); $i++) {
                        $l = substr($MailLink, $i, 1);
                        if (strpos($MailLetters, $l) === false) {
                                $p = rand(0, strlen($MailLetters));
                                $MailLetters = substr($MailLetters, 0, $p).$l.substr($MailLetters, $p, strlen($MailLetters));
                        }
                }
                $MailLettersEnc = str_replace("\\", "\\\\", $MailLetters);
                $MailLettersEnc = str_replace("\"", "\\\"", $MailLettersEnc);
                $MailIndexes = "";
                for ($i = 0; $i < strlen($MailLink); $i ++) {
                        $index = strpos($MailLetters, substr($MailLink, $i, 1));
                        $index += 48;
                        $MailIndexes .= chr($index);
                }
                $MailIndexes = str_replace("\\", "\\\\", $MailIndexes);
                $MailIndexes = str_replace("\"", "\\\"", $MailIndexes);

                $res = "<script type='text/javascript'>";
                $res .= "ML=\"".str_replace("<", "xxxx", $MailLettersEnc)."\";";
                $res .= "MI=\"".str_replace("<", "xxxx", $MailIndexes)."\";";
                $res .= "ML=ML.replace(/xxxx/g, '<');";
                $res .= "MI=MI.replace(/xxxx/g, '<');"; $res .= "OT=\"\";";
                $res .= "for(j=0;j < MI.length;j++){";
                $res .= "OT+=ML.charAt(MI.charCodeAt(j)-48);";
                $res .= "}document.write(OT);";
                $res .= "</script>";

                return $res;
        } else {
                return $email;
        }
}

// Format spaces and tabs in code bb tags
function formatcode($text) {
        $text = str_replace("  ", "&nbsp; ", $text);
        $text = str_replace("  ", " &nbsp;", $text);
        $text = str_replace("\t", "&nbsp; &nbsp;", $text);
        $text = preg_replace("/^ {1}/m", "&nbsp;", $text);
        return $text;
}

// Highlights given words in subject
function highlight_words($word, $subject) {
        if (is_array($word)) {
                $regex_chars = "*|#.+?(){}[]^$/";
                for ($j = 0; $j < count($word); $j++) {
                        for ($i = 0; $i < strlen($regex_chars); $i++) {
                                $char = substr($regex_chars, $i, 1);
                                $word[$j] = str_replace($char, '\\'.$char, $word[$j]);    //'
                        }
                        $subject = preg_replace("/(".$word[$j].")/is", "<span style='background-color:yellow;font-weight:bold;padding-left:2px;padding-right:2px'>\\1</span>", $subject);
                }
        }
        return $subject;
}

// This function sanitises news & article submissions
function descript($text, $striptags = true) {
        // Convert problematic ascii characters to their true values
        $search = array("40","41","58","65","66","67","68","69","70",
                "71","72","73","74","75","76","77","78","79","80","81",
                "82","83","84","85","86","87","88","89","90","97","98",
                "99","100","101","102","103","104","105","106","107",
                "108","109","110","111","112","113","114","115","116",
                "117","118","119","120","121","122"
                );
        $replace = array("(",")",":","a","b","c","d","e","f","g","h",
                "i","j","k","l","m","n","o","p","q","r","s","t","u",
                "v","w","x","y","z","a","b","c","d","e","f","g","h",
                "i","j","k","l","m","n","o","p","q","r","s","t","u",
                "v","w","x","y","z"
                );
        $entities = count($search);
        for ($i=0; $i < $entities; $i++) {
                $text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
        }
        $text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
        $text = preg_replace('#(<[^>]+[/\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|ondblclick|onfocus|onload|xmlns)[^>]*>#iU', ">", $text);
        $text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
        $text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
        $text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
        $text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
        $text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
        if ($striptags) {
                do {
                        $thistext = $text;
                        $text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
                } while ($thistext != $text);
        }
        return $text;
}

// Scan image files for malicious code
function verify_image($file) {
        $txt = file_get_contents($file);
        $image_safe = true;
        if (preg_match('#&(quot|lt|gt|nbsp|<?php);#i', $txt)) { $image_safe = false; }
        elseif (preg_match("#&\#x([0-9a-f]+);#i", $txt)) { $image_safe = false; }
        elseif (preg_match('#&\#([0-9]+);#i', $txt)) { $image_safe = false; }
        elseif (preg_match("#([a-z]*)=([\`\'\"]*)script:#iU", $txt)) { $image_safe = false; }
        elseif (preg_match("#([a-z]*)=([\`\'\"]*)javascript:#iU", $txt)) { $image_safe = false; }
        elseif (preg_match("#([a-z]*)=([\'\"]*)vbscript:#iU", $txt)) { $image_safe = false; }
        elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU", $txt)) { $image_safe = false; }
        elseif (preg_match("#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU", $txt)) { $image_safe = false; }
        elseif (preg_match("#</*(applet|link|style|script|iframe|frame|frameset)[^>]*>#i", $txt)) { $image_safe = false; }
        return $image_safe;
}

// captcha routines
function make_captcha() {
        global $settings;
        $captcha_string = ""; $captcha_encode = "";
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for ($i = 0; $i < 5; $i++) {
                $captcha_string .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        for ($i = 0; $i < 31; $i++) {
                $captcha_encode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        $result = mysql_query("INSERT INTO ".DB_PREFIX."captcha (captcha_datestamp, captcha_ip, captcha_encode, captcha_string) VALUES('".time()."', '".USER_IP."', '$captcha_encode', '$captcha_string')");
        if ($settings['validation_method'] == "image") {
                return "<input type='hidden' name='captcha_encode' value='".$captcha_encode."' /><img src='".INCLUDES."captcha_include.php?captcha_code=".$captcha_encode."' alt='' />\n";
        } else {
                return "<input type='hidden' name='captcha_encode' value='".$captcha_encode."' /><strong>".$captcha_string."</strong>\n";
        }
}

function check_captcha($captchs_encode, $captcha_string) {
        if (preg_check("/^[0-9A-Za-z]+$/", $captchs_encode) && preg_check("/^[0-9A-Za-z]+$/", $captcha_string)) {
                $result = dbquery("SELECT * FROM ".DB_CAPTCHA." WHERE captcha_ip='".USER_IP."' AND captcha_encode='".$captchs_encode."' AND captcha_string='".$captcha_string."'");
                if (dbrows($result)) {
                        $result = dbquery("DELETE FROM ".DB_CAPTCHA." WHERE captcha_ip='".USER_IP."' AND captcha_encode='".$captchs_encode."' AND captcha_string='".$captcha_string."'");
                        return true;
                } else {
                        return false;
                }
        } else {
                return false;
        }
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
        global $settings;
        if ($settings['bad_words_enabled'] == "1" && $settings['bad_words'] != "" ) {
                $word_list = explode("\r\n", $settings['bad_words']);
                for ($i=0; $i < count($word_list); $i++) {
                        if ($word_list[$i] != "") $text = preg_replace("/".$word_list[$i]."/si", $settings['bad_word_replace'], $text);
                }
        }
        return $text;
}

// Display the user's level
function getuserlevel($userlevel) {
        global $locale;
        if ($userlevel == 101) { return $locale['user1'];
        } elseif ($userlevel == 102) { return $locale['user2'];
        } elseif ($userlevel == 103) { return $locale['user3']; }
}

// Check if Administrator has correct rights assigned
function checkrights($right) {
        if (iADMIN && in_array($right, explode(".", iUSER_RIGHTS))) {
                return true;
        } else {
                return false;
        }
}

// Check if user is assigned to the specified user group
function checkgroup($group) {
        if (iSUPERADMIN) { return true; }
        elseif (iADMIN && ($group == "0" || $group == "101" || $group == "102")) { return true;
        } elseif (iMEMBER && ($group == "0" || $group == "101")) { return true;
        } elseif (iGUEST && $group == "0") { return true;
        } elseif (iMEMBER && in_array($group, explode(".", iUSER_GROUPS))) {
                return true;
        } else {
                return false;
        }
}

// Cache groups mysql
function cache_groups() {
        global $groups_cache;
        $result = dbquery("SELECT * FROM ".DB_USER_GROUPS." ORDER BY group_id ASC");
        if (dbrows($result)) {
                $groups_cache = array();
                while ($data = dbarray($result)) {
                        $groups_cache[] = $data;
                }
        } else {
                $groups_cache = array();
        }
}

// Compile access levels & user group array
function getusergroups() {
        global $locale, $groups_cache;
        $groups_array = array(
                array("0", $locale['user0']),
                array("101", $locale['user1']),
                array("102", $locale['user2']),
                array("103", $locale['user3'])
        );
        if (!$groups_cache) { cache_groups(); }
        if (is_array($groups_cache) && count($groups_cache)) {
                foreach ($groups_cache as $group) {
                        array_push($groups_array, array($group['group_id'], $group['group_name']));
                }
        }
        return $groups_array;
}

// Get the name of the access level or user group
function getgroupname($group_id, $return_desc = false) {
        global $locale, $groups_cache;
        if ($group_id == "0") { return $locale['user0'];
        } elseif ($group_id == "101") { return $locale['user1']; exit;
        } elseif ($group_id == "102") { return $locale['user2']; exit;
        } elseif ($group_id == "103") { return $locale['user3']; exit;
        } else {
                if (!$groups_cache) { cache_groups(); }
                if (is_array($groups_cache) && count($groups_cache)) {
                        foreach ($groups_cache as $group) {
                                if ($group_id == $group['group_id']) { return ($return_desc ? ($group['group_description'] ? $group['group_description'] : '-') : $group['group_name']); exit; }
                        }
                }
        }
        return "N/A";
}

function groupaccess($field) {
        if (iGUEST) { return "$field = '0'";
  } elseif (iSUPERADMIN) { return "1 = 1";
        } elseif (iADMIN) { $res = "($field='0' OR $field='101' OR $field='102'";
        } elseif (iMEMBER) { $res = "($field='0' OR $field='101'";
        }
        if (iUSER_GROUPS != "" && !iSUPERADMIN) { $res .= " OR $field='".str_replace(".", "' OR $field='", iUSER_GROUPS)."'"; }
        $res .= ")";
        return $res;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
        $res = array();
        $filter = explode("|", $filter);
        $temp = opendir($folder);
        while ($file = readdir($temp)) {
                if ($type == "files" && !in_array($file, $filter)) {
                        if (!is_dir($folder.$file)) { $res[] = $file; }
                } elseif ($type == "folders" && !in_array($file, $filter)) {
                        if (is_dir($folder.$file)) { $res[] = $file; }
                }
        }
        closedir($temp);
        if ($sort) { sort($res); }
        return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = "") {
        $res = "";
        for ($i = 0; $i < count($files); $i++) {
                $sel = ($selected == $files[$i] ? " selected='selected'" : "");
                $res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
        }
        return $res;
}

function makepagenav($start, $count, $total, $range = 0, $link = "") {

        global $locale;

        if ($link == "") { $link = FUSION_SELF."?"; }

        $pg_cnt = ceil($total / $count);
        if ($pg_cnt <= 1) { return ""; }

        $idx_back = $start - $count;
        $idx_next = $start + $count;
        $cur_page = ceil(($start + 1) / $count);

        $res = $locale['global_092']." ".$cur_page.$locale['global_093'].$pg_cnt.": ";
        if($idx_back >= 0) {
                if($cur_page > ($range + 1)) {
                        $res .= "<a href='".$link."rowstart=0'>1</a>...";
                }
        }
        $idx_fst = max($cur_page - $range, 1);
        $idx_lst = min($cur_page + $range, $pg_cnt);
        if ($range == 0) {
                $idx_fst = 1;
                $idx_lst = $pg_cnt;
        }
        for ($i = $idx_fst; $i <= $idx_lst; $i++) {
                $offset_page = ($i - 1) * $count;
                if ($i == $cur_page) {
                        $res .= "<span><strong>".$i."</strong></span>";
                } else {
                        $res .= "<a href='".$link."rowstart=".$offset_page."'>".$i."</a>";
                }
        }
        if ($idx_next < $total) {
                if ($cur_page < ($pg_cnt - $range)) {
                        $res .= "...<a href='".$link."rowstart=".($pg_cnt - 1) * $count."'>".$pg_cnt."</a>\n";
                }
        }

        return "<div class='pagenav'>\n".$res."</div>\n";
}

// Format the date & time accordingly
function showdate($format, $val) {
        global $settings;
        if ($format == "shortdate" || $format == "longdate" || $format == "forumdate") {
                return strftime($settings[$format], $val);
        } else {
                return strftime($format, $val);
        }
}

// Translate bytes into kb, mb, gb or tb by CrappoMan
function parsebytesize($size, $digits = 2, $dir = false) {
        $kb = 1024; $mb = 1024 * $kb; $gb= 1024 * $mb; $tb = 1024 * $gb;
        if (($size == 0) && ($dir)) { return "Empty"; }
        elseif ($size < $kb) { return $size."Bytes"; }
        elseif ($size < $mb) { return round($size / $kb,$digits)."Kb"; }
        elseif ($size < $gb) { return round($size / $mb,$digits)."Mb"; }
        elseif ($size < $tb) { return round($size / $gb,$digits)."Gb"; }
        else { return round($size / $tb, $digits)."Tb"; }
}

function color_group($user_name, $user_level){
        if($user_level == '102'){
                return "<span style='color: blue'><strong>".$user_name."</strong></span>";
        } else if($user_level == '103'){
                return "<span style='color: red'><strong>".$user_name."</strong></span>";
        } else {
                return "<span style='color: black'><strong>".$user_name."</strong></span>";
        }

}
function pic_group($user_level){
        if($user_level <= 103){
                $ima_val = @getimagesize(IMAGES.$user_level.".gif");
                $pic_group = "<img src='".IMAGES.$user_level.".gif' width='".$ima_val[0]."' height='".$ima_val[1]."' border=0>";
                return $pic_group;
        } else {
                return "";
        }

}

function get_image($image, $alt = "", $style = "", $title = "", $atts = "") {
        global $fusion_images;
        if (isset($fusion_images[$image])) {
                $url = $fusion_images[$image];
        } else {
                $url = BASEDIR."images/not_found.gif";
        }
        if (!$alt && !$style && !$title) {
                return $url;
        } else {
                return "<img src='".$url."' alt='".$alt."'".($style ? " style='$style'" : "").($title ? " title='".$title."'" : "")." ".$atts." />";
        }
}
function get_image2($image) {
        return THEME."/images/".$image."gif";
}
function set_image($name, $new_dir){
        global $fusion_images;
        $fusion_images[$name] = $new_dir;
}

function redirect_img_dir($source, $target){
        global $fusion_images;
        $new_images = array();
        foreach ($fusion_images as $name => $url) {
                $new_images[$name] = str_replace($source, $target, $url);
        }
        $fusion_images = $new_images;
}

function fileext($file,$mode = 0) {
    $p = pathinfo($file);
    $mime = array(
            'js'   => "JScript Script File",
            'swf'  => "Shockwave Flash Object",
            'rar'  => "WinRAR archive",
            'zip'  => "WINZIP archive",
            'gz'   => "WINZIP archive",
            'bz2'  => "WINZIP archive",
            'gtar' => "WINZIP archive",
            'tar'  => "WINZIP archive",
            'tgz'  => "WINZIP archive",
            'bmp'  => "BMP Image",
            'gif'  => "GIF Image",
            'jpe'  => "JPEG Image",
            'jpeg' => "JPEG Image",
            'jpg'  => "JPEG Image",
            'png'  => "PNG Image",
            'css'  => "Cascading Style Sheet Document",
            'htm'  => "HTML File",
            'html' => "HTML File",
            'php'  => "PHP File",
            'xml'  => "XML Document",
            'sql'  => "SQL File",
            'pdf'  => "Adobe Acrobat Document",
    );
    if($mode == 0) return $p[extension];
    else return $mime[$p[extension]];
}

function add_right($oldright,$aright) {
	if ($oldright) {
		$newright = "11.".$oldright.".";
		if (!strpos($newright,".".$aright.".")) {
			$newright = $oldright.".".$aright;
		} else {
			$newright = $oldright;
		}
		
	} else {
		$newright = $aright;
	}
	return $newright;
}
function remove_right($oldright,$aright) {
	$newright = "11.".$oldright.".";
	$temp=".".$aright.".";
	if (strpos($newright,$temp)>0) {
		$newright = str_replace($temp,".",$newright);
	} 
	$newright .= "11";
	$newright = str_replace(".11","",$newright);
	$newright = str_replace("11.","",$newright);
	//$newright = strtr($newright,2,strlen($newright)-2);
	return $newright;
}
function getlastname($strname) {
	
	if($k=strrpos($strname," ")) {
		//return strtr($strname,$k+1,strlen($strname)-$k);
		return substr($strname,$k+1);
	} else {
		return $strname;
	}
}

function splitname($strname) {
	$tmp = array("",$strname);
	if($k=strrpos($strname," ")) {
		$tmp[0]=substr($strname,1,$k-1);
		$tmp[1]= substr($strname,$k+1);
	} 
	return $tmp;
}

function imax($a, $b) {
	return $a>$b?$a:$b;
}
function imin($a, $b) {
	return $a<$b?$a:$b;
}

function encodetext($text) {
	$kitu = "0123456789abcdefghijklmnopqrstuvwxyz";
	$klen = strlen($kitu);
	//$chiso="";
	for ($i=0; $i<$klen;$i++) {
		$chiso[$kitu[$i]]=$i;	
	}
	$khoa1 = rand() % ($klen-1);
	$khoa2 = rand() % $klen;
	if ($khoa2==$khoa1) $khoa2++;
	
	$tmp = $text;
	if (strlen($tmp) % 2 >0) {
		$tmp.=" ";
	}
	$tmp = str_replace(" ","zz0",$tmp);
	$tmp = str_replace("=","zz1",$tmp);
	$tmp = str_replace("_","zz2",$tmp);
	$tmp = str_replace("&","zz3",$tmp);
	$ret=$kitu[$khoa1].$kitu[$khoa2];
	$len = strlen($tmp);

	for ($i=0; $i<$len; $i+=2) {
		$ret.= $kitu[($chiso[$tmp[$i]]+$khoa1) % $klen];
		$ret.= $kitu[($chiso[$tmp[$i+1]]+$khoa2) % $klen];
	}
	return $ret;
}
function decodetext($text) {
	$kitu = "0123456789abcdefghijklmnopqrstuvwxyz";
	$klen = strlen($kitu);
	//$chiso="";
	for ($i=0; $i<$klen;$i++) {
		$chiso[$kitu[$i]]=$i;	
	}
	$khoa1 = $chiso[$text[0]];
	$khoa2= $chiso[$text[1]];
	
	$tmp = $text;
	
	$ret="";
	$len = strlen($tmp);
	if ($len % 2 >0) {
		$tmp.=" ";
		$len++;
	}
	for ($i=2; $i<$len; $i+=2) {
		$ret.= $kitu[($chiso[$tmp[$i]]-$khoa1+$klen) % $klen];
		$ret.= $kitu[($chiso[$tmp[$i+1]]-$khoa2+$klen) % $klen];
	}
	$tmp=$ret;
	$tmp = str_replace("zz0"," ",$tmp);
	$tmp = str_replace("zz1","=",$tmp);
	$tmp = str_replace("zz2","_",$tmp);
	$tmp = str_replace("zz3","&",$tmp);
	$tmp = str_replace(" ","",$tmp);
	return $tmp;
}

function getget($gettext, $mau) {
	$tmp="a&".$gettext."&";
	$k = strpos($tmp,"&".$mau."=");
	if($k) {
		$batdau=$k+strlen($mau)+2;
		$k = strpos($tmp,"&",$k+1);
		return substr($tmp,$batdau,$k-$batdau);
	} else {
		return "";
	}
}

function mahoaurl($url) {
	$k=strpos($url,"?");
	if ($k) {
		$thamso=strtolower(substr($url,$k+1));
		return substr($url,0,$k+1)."id=".encodetext($thamso);
	} else {
		return $url;
	}
}
//====================DATE============
function YYMMDD($year,$month,$day) {
	return aSpase($year,4).aSpase($month,2).aSpase($day,2);
}
function numberOfDays($month, $year) {
	while ($month > 12) {
		$month-=12;
		$year++;
	}
	while ($month < 1) {
		$month+=12;
		$year--;
	}
	$numDays=array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	if (($year % 400==0)||($year % 4 ==0 && $year % 100 != 0)) $numDays[2]=29;
	return $numDays[$month];
}
function FirstDay($month, $year) {
	$first_day = getdate(mktime(0,0,0,$month,1,$year));
    return $first_day['wday'];
}

function aSpase($intV,$nspace) {
	$tmp="".$intV;
	$j=strlen($tmp);
	while ($j++ < $nspace) $tmp = "0".$tmp;
	return $tmp;
}
function addDate($strdate,$intd) {
	$xyear=intval(substr($strdate,0,4));
	$xmonth=intval(substr($strdate,4,2));
	$xday=intval(substr($strdate,6,2));
	$xday+=$intd;
	while ($xday>numberOfDays($xmonth,$xyear)) {
		$xday-=numberOfDays($xmonth,$xyear);
		$xmonth++;
		if ($xmonth>12) {$xmonth-=12;$xyear++;}
	}
	while ($xday<1) {
		$xmonth--;
		if ($xmonth<1) {$xmonth+=12;$xyear--;}
		$xday+=numberOfDays($xmonth,$xyear);
		
	}
	return YYMMDD($xyear,$xmonth,$xday);
}
function DateFormat($sDate,$formats="DDMMYY") {
	if (strlen($sDate)>=8) {
		$xyear=substr($sDate,0,4);
		$xmonth=intval(substr($sDate,4,2));
		$xday=substr($sDate,6,2);
	} else {
		$xyear="---";
		$xmonth=intval(substr($sDate,0,2));
		$xday=substr($sDate,2,2);
	}
	$strmonth=array("","Tháng Một", "Tháng Hai","Tháng Ba","Tháng Tư","Tháng Năm","Tháng Sáu","Tháng Bảy","Tháng Tám","Tháng Chín","Tháng Mười","Tháng Mười một","Tháng Mười hai");
	switch ($formats) {
		case "DDMMYY": return $xday."/".$xmonth."/".$xyear;
		case "MMYY": return $strmonth[$xmonth].", ".$xyear;
		case "DDMMM" : return $xday.$strmonth[$xmonth];
		case "DDMMMYY": return $xday." ".$strmonth[$xmonth].", ".$xyear;
		default: return $sDate;
	}
}
function getGroup2($groupid) {
	
		return "";
	
}
function getUser($userid) {
	return dblookup("user_name","qlt_users","user_id=$userid");
}

function getGroup($groupid) {
	return dblookup("group_name","qlt_user_groups","group_id=$groupid");
}

function hasgroupcommon($usergroups, $eventgroups) {
	$usergroups.=".";
	$k=strpos($usergroups, ".");
	
}

?>
