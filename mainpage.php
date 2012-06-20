<?php
require_once "maincore.php";
define(THIS_FILE, "index.php");
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."mainpage.php";
if($_GET['action'] == "") $_GET['action'] = "diemchuan2008";
echo "<table cellpadding='0' cellspacing='0' width='100%' class='$main_style'>\n<tr>
        <td class='side-border-left' valign='top'>";
        openside($locale['mpage_001']);
        echo "<div id='navigation'><ul>\n";
        echo "<li><strong><a href='?action=' class='side'><span class='bullet'>".THEME_BULLET."</span> ".$locale['mpage_002']."</span></a></li>\n";
        echo "<li><strong><a href='?action=' class='side'><span class='bullet'>".THEME_BULLET."</span> ".$locale['mpage_003']."</span></a></li>\n";
        echo "<li><strong><a href='?action=' class='side'><span class='bullet'>".THEME_BULLET."</span> ".$locale['mpage_004']."</span></a></li>\n";
        echo "<li><strong><a href='?action=dshsg' class='side'><span class='bullet'>".THEME_BULLET."</span> ".$locale['mpage_005']."</span></a></li>\n";
        echo "<hr class='side-hr'/>";
        echo "<li><strong><a href='?action=diemchuan2008&khuvuc=hanoi' class='side'><span class='bullet'>".THEME_BULLET."</span> ".$locale['mpage_006']."</span></a></li>\n";
        echo "<li><strong><a href='?action=diemchuan2007&khuvuc=mbac' class='side'><span class='bullet'>".THEME_BULLET."</span> ".$locale['mpage_007']."</span></a></li>\n";
        echo "</ul></div>";
        closeside();

        openside("Đề tài Hot... Diễn Đàn!", $collapse = false);
        $limit = 10;
                list($min_posts) = dbarraynum(dbquery("SELECT thread_postcount FROM ".DB_THREADS." ORDER BY thread_postcount DESC LIMIT 20,25"));
                $result4 = dbquery("
                        SELECT tf.forum_id, tf.forum_name, tt.thread_id, tt.thread_subject, tt.thread_postcount, au.user_id, au.user_name
                        FROM ".DB_FORUMS." tf
                        INNER JOIN ".DB_THREADS." tt USING(forum_id)
                        LEFT JOIN ".DB_USERS." au ON au.user_id = tt.thread_author
                        WHERE ".groupaccess('forum_access')." AND tt.thread_postcount >= '$min_posts'
                        ORDER BY thread_postcount DESC, thread_lastpost DESC LIMIT 0, $limit
                ");
                if (dbrows($result4) != 0) {
                        echo "<table cellpadding='0' cellspacing='1' width='200' class='tbl-border' style='border-left: 0px'>\n<tr>\n";
                        echo "<td width='100%' class='thead'><span class='small'><strong><font color='green'>".$locale['global_044']."</span></strong></td>\n";
                        echo "<td width='2%' class='thead' style='text-align:center;white-space:nowrap'><span class='small'><strong><font color='green'>Xem</span></td>\n";
                        echo "</tr>\n";
                        while($data = dbarray($result4)) {
                                $lastpost = dbquery("
                                        SELECT p.*, t2.thread_subject FROM ".DB_POSTS." p
                                        LEFT JOIN ".DB_THREADS." t2 ON t2.thread_id = p.thread_id
                                        WHERE p.forum_id = '".$data['forum_id']."' ORDER BY post_id DESC LIMIT 1;
                                ");
                                $lastpost_arr = dbarray($lastpost);
                                $itemsubject = trimlink($data['thread_subject'], 35);
                                echo "<tr>\n<td class='side-small'><div style='padding: 2px'>";
                                echo "<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&pid=".$lastpost_arr['post_id']."#post_".$lastpost_arr['post_id']."' title='".$data['thread_subject']."\n\t[Gởi bởi: ".$data['user_name']."]\n[Diễn đàn: ".$data['forum_name']."]' class='side'>$itemsubject</a></div></td>\n";
                                echo "<td align='right' class='side-small'>[".($data['thread_postcount'] - 1)."]</td>\n</tr>\n";
                        }
                        echo "</table>\n";
                }

        closeside();

        openside("Hỗ trợ Trực tuyến", $collapse = true);
$result=dbquery("SELECT user_name,user_yahoo FROM ".DB_USERS." WHERE user_level > 101 AND user_yahoo != '' ORDER BY user_id ASC LIMIT 0,4");
if (dbrows($result)) {
	$x=0;
	echo "<table border=0 cellspacing=0 cellpadding=0>";
	while ($data = dbarray($result)){
		$x++;
                $cls = ($x%2==0) ? "tbl1" : "tbl2";
		print <<<HUUNAM
                <tr><td class='$cls'>
		<A HREF="ymsgr:sendIM?{$data[user_yahoo]}&m=Can ho tro truc tuyen "><strong>{$data[user_name]}</strong>
		<IMG SRC="http://osi.techno-st.net:8000/yahoo/{$data[user_yahoo]}/onurl=thptnguyendu.com/images/yahooonline.gif/offurl=thptnguyendu.com/images/yahoooffline.gif/unknownurl=thptnguyendu.com/images/ircoffline.gif" align="absmiddle" border="0" ALT="Yahoo! {$data[user_yahoo]}"
		onerror="this.onerror=null;this.src='http://thptnguyendu.com/images/ircoffline.gif';"></A><br />
		</td></tr>
HUUNAM;
	}
	echo "</table>";
}
closeside();

echo "  </td>
        <td class='main-bg' valign='top'>\n";
        if($_GET['action'] == "diemchuan2008"){
                include MODULES."diemchuan2008.php";
        } else if($_GET['action'] == "dshsg"){
                include MODULES."dshsg.php";
        } elseif($_GET['action'] == "diemchuan2007"){
                include MODULES."diemchuan2007.php";
        }
        closetable();
echo"   </td>
        </tr>
</table>\n";
require_once THEMES."templates/footer.php";
?>
