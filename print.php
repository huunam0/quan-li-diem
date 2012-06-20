<?php
require_once "maincore.php";
include LOCALE.LOCALESET."print.php";

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<style type=\"text/css\">\n";
echo "body { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:14px; }\n";
echo "hr { height:1px;color:#ccc; }\n";
echo ".small { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }\n";
echo ".small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }\n";
echo "</style>\n</head>\n<body>\n";
if ((isset($_GET['type']) && $_GET['type'] == "F") && (isset($_GET['thread']) && isNum($_GET['thread'])) && !isset($_GET['post'])) {
        $result = dbquery("SELECT fp.*, fu.user_name AS user_name, fe.user_name AS edit_name, ft.thread_subject, ff.forum_access FROM ".DB_THREADS." ft INNER JOIN ".DB_POSTS." fp ON ft.thread_id = fp.thread_id INNER JOIN ".DB_FORUMS." ff ON ff.forum_id = ft.forum_id INNER JOIN ".DB_USERS." fu ON fu.user_id = fp.post_author LEFT JOIN ".DB_USERS." fe ON fe.user_id = fp.post_edituser WHERE ft.thread_id=".$_GET['thread']." ORDER BY fp.post_datestamp");
        $res = false; $i = 0;
        if (dbrows($result)) {
                while ($data = dbarray($result)) {
                        if (checkgroup($data['forum_access'])) {
                                $res = true;
                                if ($i == 0) echo $locale['500']." <strong>".$settings['sitename']." :: ".$data['thread_subject']."</strong><hr /><br />\n";
                                echo "<div style='margin-left:20px'>\n";
                                echo "<div style='float:left'>".$locale['501'].$data['user_name'].$locale['502'].showdate("forumdate", $data['post_datestamp'])."</div><div style='float:right'>#".($i+1)."</div><div style='float:none;clear:both'></div><hr />\n";
                                echo parseubb(nl2br($data['post_message']));
                                if ($data['edit_name']!='') {
                                        echo "<div style='margin-left:20px'>\n<hr />\n";
                                        echo $locale['503'].$data['edit_name'].$locale['502'].showdate("forumdate", $data['post_edittime']);
                                        echo "</div>\n";
                                }
                                echo "</div>\n";
                                echo "<br />\n";
                                $i++;
                        }
                }               
        }
        if (!$res) { redirect("index.php"); }
} elseif ((isset($_GET['type']) && $_GET['type'] == "F") && (isset($_GET['thread']) && isNum($_GET['thread'])) && (isset($_GET['post']) && isNum($_GET['post'])) && (isset($_GET['nr']) && isNum($_GET['nr']))) {
        $result = dbquery("SELECT fp.*, fu.user_name AS user_name, fe.user_name AS edit_name, ft.thread_subject, ff.forum_access FROM ".DB_THREADS." ft INNER JOIN ".DB_POSTS." fp ON ft.thread_id = fp.thread_id INNER JOIN ".DB_FORUMS." ff ON ff.forum_id = ft.forum_id INNER JOIN ".DB_USERS." fu ON fu.user_id = fp.post_author LEFT JOIN ".DB_USERS." fe ON fe.user_id = fp.post_edituser WHERE ft.thread_id=".$_GET['thread']." AND fp.post_id = ".$_GET['post']);
        $res = false;
        if (dbrows($result)) {
                $data = dbarray($result);
                if (checkgroup($data['forum_access'])) {
                        $res = true;
                        echo $locale['500']." <strong>".$settings['sitename']." :: ".$data['thread_subject']."</strong><hr /><br />\n";
                        echo "<div style='margin-left:20px'>\n";
                        echo "<div style='float:left'>".$locale['501'].$data['user_name'].$locale['502'].showdate("forumdate", $data['post_datestamp'])."</div><div style='float:right'>#".$_GET['nr']."</div><div style='float:none;clear:both'></div><hr />\n";
                        echo parseubb(nl2br($data['post_message']));
                        if ($data['edit_name']!='') {
                                echo "<div style='margin-left:20px'>\n<hr />\n";
                                echo $locale['503'].$data['edit_name'].$locale['502'].showdate("forumdate", $data['post_edittime']);
                                echo "</div>\n";
                        }
                        echo "</div>\n";
                        echo "<br />\n";
                }
        }
        if (!$res) { redirect("index.php"); }
} else {
        redirect("index.php");
}
echo "<script>\n";
?>
