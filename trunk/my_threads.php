<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";

if($_GET['action'] == "mytrackedthreads") {
        if (!iMEMBER) { redirect("index.php"); }
        if (isset($_GET['delete']) && isnum($_GET['delete']) && dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='".$_GET['delete']."' AND notify_user='".$userdata['user_id']."'")) {
                $result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id=".$_GET['delete']." AND notify_user=".$userdata['user_id']);
                redirect(FUSION_SELF);
        }

        if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

        opentable($locale['global_056']);

        $rows = dbcount("(thread_id)", DB_THREAD_NOTIFY, "notify_user=".$userdata['user_id']);

        if ($rows) {
                $result = dbquery("
                SELECT
                tf.forum_access,
                tn.thread_id, tn.notify_datestamp, tn.notify_user,
                tt.thread_subject, tt.forum_id, tt.thread_lastpost, tt.thread_lastuser,
                tu.user_id AS user_id1, tu.user_name AS user_name1,
                tu2.user_id AS user_id2, tu2.user_name AS user_name2,
                tp.post_datestamp,
                COUNT(post_id)-1 as replies FROM ".DB_THREAD_NOTIFY." tn
                LEFT JOIN ".DB_THREADS." tt ON tn.thread_id = tt.thread_id
                LEFT JOIN ".DB_FORUMS." tf ON tt.forum_id = tf.forum_id
                LEFT JOIN ".DB_USERS." tu ON tt.thread_author = tu.user_id
                LEFT JOIN ".DB_USERS." tu2 ON tt.thread_lastuser = tu2.user_id
                INNER JOIN ".DB_POSTS." tp ON tt.thread_id = tp.thread_id
                WHERE tn.notify_user=".$userdata['user_id']." AND ".groupaccess('forum_access')."
                GROUP BY tn.thread_id
                ORDER BY tn.notify_datestamp DESC
                LIMIT ".$_GET['rowstart'].",10
                ");
          echo "<table class='tbl-border' cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
                echo "<td class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
                echo "<td class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_050']."</strong></td>\n";
                echo "<td class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_047']."</strong></td>\n";
                echo "<td class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_046']."</strong></td>\n";
                echo "<td class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_057']."</strong></td>\n";
                echo "</tr>\n";
                $i = 0;
                while ($data = dbarray($result)) {
                        $row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
                        echo "<tr>\n<td class='".$row_color."'><a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a></td>\n";
                        echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=".$data['user_id1']."'>".$data['user_name1']."</a><br />
                        ".showdate("forumdate", $data['post_datestamp'])."</td>\n";
                        echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=".$data['user_id2']."'>".$data['user_name2']."</a><br />
                        ".showdate("forumdate", $data['thread_lastpost'])."</td>\n";
                        echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap'>".$data['replies']."</td>\n";
                        echo "<td class='".$row_color."' style='text-align:center;white-space:nowrap'><a href='".BASEDIR."mythreads.php?action=mytrackedthreads&delete=".$data['thread_id']."' onclick=\"return confirm('".$locale['global_060']."');\">".$locale['global_058']."</a></td>\n";
                        echo "</tr>\n";
                        $i++;
                }
                echo "</table>\n";
                closetable();
                echo "<div align='center' style='margin-top:5px;'>".makePageNav($_GET['rowstart'],10,$rows,3,FUSION_SELF."?")."</div>\n";
        } else {
                echo "<div style='text-align:center;'>".$locale['global_059']."</div>\n";
                closetable();
        }
}
else if($_GET['action'] == "myposts") {
        add_to_title($locale['global_200'].$locale['global_042']);
        if($_GET['user_id']) $userdata['user_id'] = $_GET['user_id'];
        $result = dbquery(
                "SELECT COUNT(post_id) FROM ".DB_POSTS." tp
                INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
                WHERE ".groupaccess('tf.forum_access')." AND post_author='".$userdata['user_id']."'
                ORDER BY tp.post_datestamp DESC LIMIT 100"
        );
        $rows = dbrows($result);
        if ($rows) {
                if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
                $result = dbquery(
                        "SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp,
                        tf.forum_name, tf.forum_access, tt.thread_subject
                        FROM ".DB_POSTS." tp
                        INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
                        INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
                        WHERE ".groupaccess('tf.forum_access')." AND tp.post_author='".$userdata['user_id']."'
                        ORDER BY tp.post_datestamp DESC LIMIT ".$_GET['rowstart'].",20"
                );
                $i=0;
                opentable($locale['global_042']);
                echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n<tr>\n";
                echo "<td width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_048']."</strong></td>\n";
                echo "<td width='100%' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
                echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_049']."</strong></td>\n";
                echo "</tr>\n";
                while ($data = dbarray($result)) {
                        if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
                        echo "<tr>\n";
                        echo "<td width='1%' class='".$row_color."' style='white-space:nowrap'>".trimlink($data['forum_name'], 50)."</td>\n";
                        echo "<td width='100%' class='".$row_color."'><a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 40)."</a></td>\n";
                        echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>".showdate("forumdate", $data['post_datestamp'])."</td>\n";
                        echo "</tr>\n";
                        $i++;
                }
                echo "</table>\n";
                closetable();
                if ($rows > 20) { echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 20, $rows, 3)."\n</div>\n"; }
        } else {
                opentable($locale['global_042']);
                echo "<div style='text-align:center'><br />\n".$locale['global_054']."<br /><br />\n</div>\n";
                closetable();
        }
}
else if($_GET['action'] == "mythreads") {
        add_to_title($locale['global_200'].$locale['global_041']);

        global $lastvisited;
        if($_GET['user_id']) $userdata['user_id'] = $_GET['user_id'];
        if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

        $result = dbquery(
                "SELECT COUNT(thread_id) FROM ".DB_THREADS." tt
                INNER JOIN ".DB_FORUMS." tf ON tt.forum_id = tf.forum_id
                INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser = tu.user_id
                WHERE ".groupaccess('tf.forum_access')." AND tt.thread_author='".$userdata['user_id']."' LIMIT 100"
        );
        $rows = dbrows($result);
        if ($rows) {
                if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
                $result = dbquery(
                        "SELECT tt.forum_id, tt.thread_id, tt.thread_subject, tt.thread_views, tt.thread_lastuser,
                        tt.thread_lastpost, tt.thread_postcount, tf.forum_name, tf.forum_access, tu.user_id, tu.user_name
                        FROM ".DB_THREADS." tt
                        INNER JOIN ".DB_FORUMS." tf ON tt.forum_id = tf.forum_id
                        INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser = tu.user_id
                        WHERE ".groupaccess('tf.forum_access')." AND tt.thread_author = '".$userdata['user_id']."'
                        ORDER BY tt.thread_lastpost DESC LIMIT ".$_GET['rowstart'].",20"
                );
                $i=0;
                opentable($locale['global_041']);
                echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
                echo "<td class='tbl2'>&nbsp;</td>\n";
                echo "<td width='100%' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
                echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_045']."</strong></td>\n";
                echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_046']."</strong></td>\n";
                echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_047']."</strong></td>\n";
                echo "</tr>\n";
                while ($data = dbarray($result)) {
                        if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
                        echo "<tr>\n";
                        echo "<td class='".$row_color."'>";
                        if ($data['thread_lastpost'] > $lastvisited) {
                                $thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
                                if (iMEMBER && preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
                                        echo "<img src='".get_image("folder")."' alt='' />";
                                } else {
                                        echo "<img src='".get_image("foldernew")."' alt='' />";
                                }
                        } else {
                                echo "<img src='".get_image("folder")."' alt='' />";
                        }
                        echo "</td>\n";
                        echo "<td width='100%' class='".$row_color."'><a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 30)."</a><br />\n".$data['forum_name']."</td>\n";
                        echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".$data['thread_views']."</td>\n";
                        echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".($data['thread_postcount']-1)."</td>\n";
                        echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_name']."</a><br />\n".showdate("forumdate", $data['thread_lastpost'])."</td>\n";
                        echo "</tr>\n";
                        $i++;
                }
                echo "</table>\n";
                closetable();
                if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 20, $rows, 3)."\n</div>\n";
        } else {
                opentable($locale['global_041']);
                echo "<div style='text-align:center'><br />\n".$locale['global_053']."<br /><br />\n</div>\n";
                closetable();
        }
}
else if($_GET['action'] == "newposts") {
        if (!iMEMBER) { redirect("index.php"); }
        if (!isset($lastvisited) || !isnum($lastvisited)) $lastvisited = time();

        add_to_title($locale['global_200'].$locale['global_043']);

        opentable($locale['global_043']);
        $result = dbquery(
                "SELECT COUNT(post_id), tf.* FROM ".DB_POSTS." tp
                INNER JOIN ".DB_FORUMS." tf ON tp.forum_id = tf.forum_id
                WHERE ".groupaccess('tf.forum_access')." AND tp.post_datestamp > '".$lastvisited."'
                GROUP BY tp.post_id"
        );
        $rows = dbrows($result);
        if ($rows) {
                if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
                // $result = dbquery(
                        // "SELECT DISTINCT tp.thread_id, tp.forum_id, tp.post_id, tp.post_author, tp.post_datestamp,
                        // tf.forum_name, tf.forum_access, tt.thread_subject, tu.user_id, tu.user_name
                        // FROM ".DB_POSTS." tp
                        // INNER JOIN ".DB_FORUMS." tf ON tp.forum_id = tf.forum_id
                        // INNER JOIN ".DB_THREADS." tt ON tp.thread_id = tt.thread_id
                        // LEFT JOIN ".DB_USERS." tu ON tp.post_author = tu.user_id
                        // WHERE ".groupaccess('tf.forum_access')." AND tp.post_datestamp > '".$lastvisited."'
                        // ORDER BY tp.post_datestamp DESC LIMIT ".$_GET['rowstart'].",20"
                // );
                $result = dbquery(
                        "SELECT  tt.thread_id, tt.forum_id, tt.thread_subject, tf.forum_name, tt.thread_lastpost, tt.thread_lastpostid,  tf.forum_access, tu.user_name
                        FROM ".DB_THREADS." tt
                        INNER JOIN ".DB_FORUMS." tf ON tt.forum_id = tf.forum_id
                        LEFT JOIN ".DB_USERS." tu ON tt.thread_lastuser = tu.user_id
                        WHERE ".groupaccess('tf.forum_access')." AND tt.thread_lastpost > '".$lastvisited."'
						ORDER BY tt.thread_lastpost DESC LIMIT ".$_GET['rowstart'].",50"
                );
				
                $i = 0;
                echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
                echo "<td width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_048']."</strong></td>\n";
                echo "<td class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
                echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_050']."</strong></td>\n";
                echo "</tr>\n";
                while ($data = dbarray($result)) {
                        if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
                        echo "<tr>\n";
                        echo "<td width='1%' class='".$row_color."' style='white-space:nowrap'>".$data['forum_name']."</td>\n";
                        echo "<td class='".$row_color."'><a href='".BASEDIR."forum/viewthread.php?thread_id=".$data['thread_id']."&pid=".$data['thread_lastpostid']."'>".$data['thread_subject']."</a></td>\n";
                        echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=".$data['thread_lastpostid']."'>".$data['user_name']."</a><br />\n".showdate("forumdate",$data['thread_lastpost'])."</td>\n";
                        echo "</tr>\n";
                        $i++;
                }
                echo "<tr>\n<td align='center' colspan='4' class='tbl1'>".sprintf($locale['global_055'], $rows)."</td>\n</tr>\n</table>\n";
        } else {
                echo "<div style='text-align:center'><br />".sprintf($locale['global_055'], $rows)."<br /><br /></div>\n";
        }
        closetable();
        if ($rows > 50) { echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 50, $rows, 3)."\n</div>\n"; }
} else redirect("index.php");
require_once THEMES."templates/footer.php";
?>
