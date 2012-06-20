<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

require_once INCLUDES."theme_functions_include.php";

function render_page() {

        global $settings, $main_style, $locale, $return_lnk, $userdata, $lastvisited;
        require_once BASEDIR."hottopic.php";
        if(!iMEMBER){
                $reg_lnk .= "<td align='center'><strong><a style='font-size: 10px' href='".BASEDIR."register.php'>";
                $reg_lnk .= "<img src='".IMAGES."register.gif' title='Ghi danh' border=0><br /><font color='red'>Ghi Danh</font></a></strong></td>\n";
        } else $reg_lnk = "";
        //Header
        echo '
<body>
<a name="top"></a>
<table border="0" width="100%" cellpadding="0" cellspacing="0" align="center">
<tbody>
<tr>
<td>

<div class="table-top-bg"><div class="table-top-left"><div class="table-top-right"></div></div></div>
<div class="table-border-left">
<div class="table-border-right">
<div align="center">
        <div class="page" style="width:100%; text-align:left">
                <div style="padding:0px 11px 0px 11px" align="left">'."\n";
        echo "<table cellpadding='0' cellspacing='0' width='100%' border=0>\n<tr>\n";
        echo "<td class='bg_banner' width='".((!iMEMBER) ? "40%" : "30%")."'>\n".showbanners()."</td>\n";
        echo "<td align='right' valign='bottom'>\n";
        echo "<form name='loginform' method='post' action='".FUSION_SELF."'>
                <table align='right' cellpadding='0' class='banner3' cellspacing='0' width='100%' border=0>
                <tr><td colspan='8'>
                <table align='right' cellpadding='0' cellspacing='0' border=0 class='logout_bg' width='330' height='120'>
                        <tr><td><table align='right' cellpadding='0' cellspacing='0' border=0>
                <tr><td colspan='23' align='left' height='21px' style='padding-left: 4px'><font color='green'><strong>".((!iMEMBER) ? $locale['global_104']. "|<a style='font-size: 11px' href='".BASEDIR."lostpassword.php' class='side'>".$locale['global_108']."</a>" : "<font size='3'>Chào mừng </font> <br /><b><font size='4'>".color_group(ucwords(trimlink($userdata['user_name'],15)),$userdata['user_level'])." ".pic_group($userdata['user_level']))."</font></td></tr>";
                if(!iMEMBER) {
                        echo "
                <tr><td height='28px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>Nick&nbsp;:&nbsp;</td>
                   <td><input type='text' name='user_name' class='textbox' style='background-image:url(".IMAGES."u.gif); background-position:left; background-repeat:no-repeat ; height: 18px; width:90%; padding-left:18px; width: 125px;' /></td>
                </tr>
                <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>Pass&nbsp;:&nbsp;</td>
                   <td><input type='password' name='user_pass' class='textbox' style='background-image:url(".IMAGES."p.gif); background-position:left; background-repeat:no-repeat ; height: 18px; width:90%; display:block; padding-left:18px; width:125px' /></td>
                </tr>
                <tr><td colspan=2 align='right' height='24px'>&nbsp;<input type='submit' name='login' class='button' value='Login...'><br /></td></tr>";
                } else {
                        if (!isset($lastvisited) || !isnum($lastvisited)) $lastvisited = time();
                        $result = dbquery("SELECT COUNT(post_id), tf.* FROM ".DB_POSTS." tp INNER JOIN ".DB_FORUMS." tf ON tp.forum_id = tf.forum_id WHERE ".groupaccess('tf.forum_access')." AND tp.post_datestamp > '".$lastvisited."' GROUP BY tp.post_id");
                        $rows = dbrows($result);
                        if ($rows) $num_new_post = sprintf("<a href='".BASEDIR."viewpage.php?page_id=9' class='small'><strong>".$locale['global_055']."</strong></a> <img src='".THEMES."forum/lastpost.gif' border=0>", $rows); else $num_new_post = $locale['global_061'];
                        unset($row);
                        $msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
                        echo "<tr><td><div style='margin: 5px'><strong><a href='".BASEDIR."messages.php' class='side'><span style='font-size: 11px'>".$locale['global_121']."</span></a> | \n";
                        if(iADMIN) echo "<a href='".ADMIN."'><span style='font-size: 11px'><font color='red'>AdminCP</font></span></a> | ";
                        echo "<a onClick='return logout()' href='".BASEDIR."setuser.php?logout=yes&return_lnk=".urlencode(FUSION_REQUEST)."' class='side'>
                                <span style='font-size: 11px'><font color='red'>Logout</span></a>\n";
                        if ($msg_count) {
                                echo "<div style='text-align:center'><strong><a href='".BASEDIR."messages.php' class=''><span style='font-size: 11px; font-color: red'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</span></a></strong>\n";
                        }
                        echo "<br /></font><span class='small'>Online lần cuối lúc: ".showdate("longdate", $userdata['user_lastvisit'])."<br /><strong>$num_new_post</strong></span>";
                        echo "</div></td></tr>";
                }
                echo "
                  </td></tr></table>
                </table>
                <input type='hidden' name='return_lnk' value='".($_GET['return_lnk'] == "" ? urlencode(FUSION_REQUEST) : $_GET['return_lnk'])."'>
                </td></tr>
                <tr>".showsublinks().$reg_lnk."</tr>
                </table></form></td>\n";
        echo "</tr>\n</table>\n";
        if($hoptopic) echo "<br />".$hoptopic;
        //Content
        echo "<div style='margin-left: 6px; margin-right: 6px'>\n";
        if(FUSION_SELF == "index.php") make_nav($settings['description']);
        echo "<table cellpadding='0' cellspacing='0' width='100%' border=0>\n<tr>\n";
        if (LEFT) { echo "<td class='side-border-left' valign='top' width='210px'>".LEFT."</td>"; }
        echo "<td class='main-bg' valign='top'>\n";
        echo U_CENTER.CONTENT.L_CENTER;
        if(FUSION_SELF == "index.php") include INCLUDES."thongke.php";
        echo "</td>";
        echo "</tr>\n</table>\n";
        echo "</div></div>\n";
        //Footer
        echo '
        <center><b><font color="#0000FF" size="2">Design by: Nguyen Phuoc</font></b><br />
        <div class="small" align="center"><span class="copyright">'.stripslashes($settings['footer']).'</span></div>
        <div class="small" align="center">Múi giờ GMT. Hiện tại là <span class="time">'.strftime("%H:%M",time()).'.</span></div>
</div>
<table cellpadding="6" cellspacing="0" border="0" width="100%" class="page" align="center">
<tr>
        <td width="20%">
            <span class="small">'.sprintf($locale['global_172'], substr((get_microtime() - START_TIME),0,4)).'</span>
        </td>
        <td align="right" width="100%">
                <a href="'.BASEDIR.'mainpage.php" rel="nofollow"><span class="small"><strong>Home</a> -
                <a href="'.BASEDIR.'forum/"><span class="small"><strong>Forum</a> -
                <a href="'.BASEDIR.'faq.php"><span class="small"><strong>FAQ</a> -
                <a href="'.BASEDIR.'view_rss.php"><span class="small"><strong>RSS</a> -
                <a href="'.BASEDIR.'contact.php"><span class="small"><strong>Contact</a> -
                <a href="#top" onclick="self.scrollTo(0, 0); return false;"><span class="small"><strong>Trở Lên Trên</a></strong>
                </span>
        </td>
</tr>
</table>
</div></div> </div>
<div class="table-bottom-bg"><div class="table-bottom-left"><div class="table-bottom-right"></div></div></div>
</td>
</tr>
</tbody>
</table>';
}

function opentable($title) {
        global $settings;
        echo "
<div class=\"tren\"><div class=\"tren_trai\"><div class=\"tren_phai\"></div></div></div>
<div class=\"doc_trai\">
<div class=\"doc_phai\">
<div class=\"doc_duoi\">
<div style=\"padding-left:10px; padding-right:10px;\">\n";
        echo "<table cellpadding='2' cellspacing='0' width='100%' border=0 style=\"border: 0px;\">\n";
        echo "  <tr>
                        <td class=\"tcat\" colspan=\"2\" nowrap='nowrap'><strong>$title</strong></td>
                </tr>";
        echo "</table>\n";
        echo "<table cellpadding='0' cellspacing='0' width='100%' border=0>\n<tr>\n";
        echo "<td class=\"tborder\"><div style='margin: 0px'>\n";
}

function closetable() {
        echo "</div></td>\n";
        echo "</tr>\n";
        echo "</table>
        </div>
        </div></div></div></div>
<div class=\"duoi\"><div class=\"duoi_trai\"><div class=\"duoi_phai\"></div></div></div>\n<br />\n";
}

function openside($title, $collapse = false, $state = "on") {
        global $panel_collapse, $settings;
        $panel_collapse = $collapse;
        echo "<div class=\"tren\"><div class=\"tren_trai\"><div class=\"tren_phai\"></div></div></div>
<div class=\"doc_trai\">
<div class=\"doc_phai\">
<div class=\"doc_duoi\">
<div style=\"padding-left:10px; padding-right:10px;\">\n";
        echo "<table cellpadding='2' cellspacing='0' width='100%' border=0 style=\"border: 0px;\"><tr>\n";
        echo "<td class=\"tcat\" width='100%'><strong><font size='4'>$title</font></strong></td>\n";
        echo "</tr>\n</table>\n";
        echo "<table cellpadding='0' cellspacing='0' width='100%' border=0>\n<tr>\n";
        echo "<td class=\"tborder\"><div style='margin: 5px'>\n";
        if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside() {
        global $panel_collapse;
        if ($panel_collapse == true) { echo "</div>\n"; }
        echo "</div></td>\n";
        echo "</tr>\n";
        echo "</table>
        </div>
        </div></div></div></div>
<div class=\"duoi\"><div class=\"duoi_trai\"><div class=\"duoi_phai\"></div></div></div>\n<br />\n";
}

function make_lnk_nav($mode=0){
        $str = "";
        $result = dbquery(
                "SELECT tl.link_name, tl.link_url, tl.link_window, tl.link_order FROM ".DB_SITE_LINKS." tl
                WHERE ".groupaccess('tl.link_visibility')." AND link_position <= '2'
                ORDER BY link_order"
        );
        if (dbrows($result)) {
            $str .= "<table cellpadding=\"0\" class=\"tcat\" cellspacing=\"0\" border=\"0\" width=\"100%\" align=\"center\"><tr>";
            while($data = dbarray($result)) {
                if ($data['link_name'] != "---" && $data['link_url'] == "---") {
                    $str .= "<td align='center'><h2><strong>".$data['link_name']."</h2></td>\n";
                    $result = dbquery("SELECT tl.link_name, tl.link_url, tl.link_window, tl.link_order FROM ".DB_SITE_LINKS." tl WHERE ".groupaccess('tl.link_visibility')." AND link_position<='2' ORDER BY link_order");
                    if (dbrows($result)) {
                        while($data = dbarray($result)) {
                            if ($data['link_name'] != "---" && $data['link_url'] == "---") {
                                $str .= "<td align='center' height='20px'><h2><strong>".$data['link_name']."</h2></td>\n";
                            } else {
                                $link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
                                if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
                                    $str .= "<td align='center' height='20px'><a href='".$data['link_url']."'".$link_target." class='side'><span class='small'><strong>".$data['link_name']."</strong></span></a></td>\n";
                                } else {
                                    $str .= "<td align='center' height='20px'><a href='".BASEDIR.$data['link_url']."'".$link_target." class='side'><span class='small'><strong>".$data['link_name']."</strong></span></a></td>\n";
                                }
                            }
                        }
                    } else $str .= $locale['global_002'];
                } else {
                    $link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
                    if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
                        $str .= "<td align='center' height='20px'><a href='".$data['link_url']."'".$link_target." class='side'><span class='small'><strong>".$data['link_name']."</strong></span></a></td>\n";
                    } else {
                        $str .= "<td align='center' height='20px'><a href='".BASEDIR.$data['link_url']."'".$link_target." class='side'><span class='small'><strong>".$data['link_name']."</strong></span></a></td>\n";
                    }
                }
            }
            $str .= "</tr>";
        } else {
            $str .= $locale['global_002'];
        }
        if($mode == 0) echo $str; else return $str;
}
function make_nav($str1='', $str2=''){
        global $settings;
        echo "<table cellpadding='0' cellspacing='0' width='100%' border=0><tr>\n";
        echo "<td valign='top'>\n";
        echo "<div class=\"tren\"><div class=\"tren_trai\"><div class=\"tren_phai\"></div></div></div>
<div class=\"doc_trai\">
<div class=\"doc_phai\">
<div class=\"doc_duoi\">
<div style=\"padding-left:10px; padding-right:10px;\">";
                echo "<table cellpadding='0' cellspacing='0' width='100%' border=0><tr><td class=\"tborder\" style='border-bottom: 0px'>\n";
                echo "<div style='margin:0px 0px 4px 0px'>
                <strong>&nbsp;&nbsp;<img src='".get_image("navbits_start")."' border=0><a href='".BASEDIR."forum/index.php'>".$donvi."</a> ".(($str2 != "" && $str2 != "0") ? " :: $str2" : "")."</strong>\n";
                if($str1 != "") echo "<br /><span style='margin-left: 0px'><img src='".get_image("navbits_finallink")."' border=0><strong>".$str1."</span></strong></div>\n";
                echo "</td></tr>\n";
                echo "<tr><td style='padding-top: 5px'>".@make_lnk_nav()."\n</td></tr>";
        closetable();
        echo "</td></tr></table>";
}
?>

