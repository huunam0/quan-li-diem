<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

define("THEME_BULLET", "<span class='bullet'><img src='".IMAGES."bullet_tiny.gif' border=0></span>");

require_once INCLUDES."theme_functions_include.php";

function render_page() {

        global $settings, $main_style, $locale, $return_lnk, $userdata;
        require_once BASEDIR."hottopic.php";
        if(!iMEMBER){
                $reg_lnk .= "<td align='center'><strong><a style='font-size: 10px' href='".BASEDIR."register.php'>";
                $reg_lnk .= "<img style='FILTER: alpha(opacity=60);-moz-opacity: 1.0; opacity: 1.0;' onmouseover='BeginOpacity(this,60,100)' onmouseout='EndOpacity(this,60)' src='".IMAGES."register.gif' title='Ghi danh' border=0><br /><font color='red'>Ghi Danh</font></a></strong></td>\n";
        } else $reg_lnk = "";
        //Header
        echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
        echo "<td class='bg_banner' width='".((!iMEMBER) ? "50%" : "40%")."'>\n".showbanners()."</td>\n";
        echo "<td align='right' valign='bottom'>\n";
        echo "<form name='loginform' method='post' action='".FUSION_SELF."'>
                <table align='right' cellpadding='0' cellspacing='0' width='100%'>
                <tr><td colspan='8'>
                <table align='right' cellpadding='0' cellspacing='0'>
                <tr><td colspan='3' align='left'><font color='blue'><strong>".((!iMEMBER) ? $locale['global_104'] : $locale['global_193']." <b>".color_group($userdata['user_name'],$userdata['user_level']).pic_group($userdata['user_level']))."</td></tr>";
                if(!iMEMBER) {
                        echo "
                <tr><td height='28px'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>".$locale['global_101']."&nbsp;:&nbsp;</td>
                   <td><input type='text' name='user_name' class='textbox' style='background-image:url(".IMAGES."u.gif); background-position:left; background-repeat:no-repeat ; height: 18px; width:100%; display:block; padding-left:18px; width: 120px;' /></td>
                   <td>&nbsp;</td>
                </tr>
                <tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'>".$locale['global_102']."&nbsp;:&nbsp;</td>
                   <td><input type='password' name='user_pass' class='textbox' style='background-image:url(".IMAGES."p.gif); background-position:left; background-repeat:no-repeat ; height: 18px; width:100%; display:block; padding-left:18px; width:120px' /></td>
                   <td>&nbsp;<input type='submit' name='login' class='button' value='Login...'><br /></td>
                </tr>
                <tr><td colspan='3'><div style='margin: 4px'><a style='font-size: 11px' href='".BASEDIR."lostpassword.php' class='side'>".$locale['global_108']."</a></div></td></tr>";
                } else {
                        $msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
                        echo "<tr><td><div style='margin: 8px'><strong><a href='".BASEDIR."messages.php' class='side'><span style='font-size: 11px'>".$locale['global_121']."</span></a>\n";
                        echo "<a onClick='return logout()' href='".BASEDIR."setuser.php?logout=yes&return_lnk=".urlencode(FUSION_REQUEST)."' class='side'>
                                <span style='font-size: 11px'><font color='red'>".$locale['global_124']."</span></a>\n";
                        if ($msg_count) {
                                echo "<br /><div style='text-align:center'><strong><a href='".BASEDIR."messages.php' class=''><span style='font-size: 11px; font-color: red'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</span></a></strong>\n";
                        } else echo "<br />&nbsp;";
                        echo "</div></td></tr>
                        <tr><td colspan='3'>&nbsp;</td></tr>";
                }
                echo "
                </table>
                <input type='hidden' name='return_lnk' value='".($_GET['return_lnk'] == "" ? urlencode(FUSION_REQUEST) : $_GET['return_lnk'])."'>
                </td></tr>
                <tr>".showsublinks().$reg_lnk."</tr>
                </table></form></td>\n";
        echo "</tr>\n</table>\n";
        if($hoptopic) echo "<br />".$hoptopic."<br />";

        //include INCLUDES."googlesearch.php";
        //Content
        if(THIS_FILE != "index.php") echo "<table cellpadding='0' cellspacing='0' width='100%' class='$main_style'>\n<tr>\n";
        if (LEFT) { echo "<td class='side-border-left' valign='top'>".LEFT."</td>"; }
        echo "<td class='main-bg' valign='top'>".U_CENTER.CONTENT.L_CENTER."</td>";
        if (RIGHT) { echo "<td class='side-border-right' valign='top'>".RIGHT."</td>"; }
        if(THIS_FILE != "index.php") echo "</tr>\n</table>\n";

        //Footer
        echo '
<div id="footermainPan">
   	<div id="footerPan">
  	<ul>
		
		<li><a href="'.BASEDIR.'forum/">Forum</a>| </li>
		<li><a href="'.BASEDIR.'register.php">Register</a>| </li>
		
		
                <li><a href="#">Go Top</a></li>
		</ul>
		<span class="copyright">'.stripslashes($settings['footer']).'<br /><span class="small">'.sprintf($locale['global_172'], substr((get_microtime() - START_TIME),0,4)).'</span>
  </div>
';
}

function opentable($title) {

        global $settings;
        echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
        echo "<td class='capmain-left'><img src='".THEMES.$settings['theme']."/images/cap_blue_left.gif' border=0></td>\n";
        echo "<td class='capmain' width='100%'><img src='".THEMES.$settings['theme']."/images/bullet_table.png' border=0><strong>$title</strong></td>\n";
        echo "<td class='capmain-right'><img src='".THEMES.$settings['theme']."/images/cap_blue_right.gif' border=0></td>\n";
        echo "</tr>\n</table>\n";
        echo "<table cellpadding='0' cellspacing='0' width='100%' style='border: 1px solid #955adc; border-top: 0px;'>\n<tr>\n";
        echo "<td class='main-body'>\n";

}

function closetable() {

        echo "</td>\n";
        echo "</tr><tr>\n";
        echo "<td style='height:5px;background-color:#f6a504;'></td>\n";
        echo "</tr>\n</table><br />\n";

}

function openside($title, $collapse = false, $state = "on") {

        global $panel_collapse, $settings;
        $panel_collapse = $collapse;

        echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
        echo "<td class='scapmain-left'><img src='".THEMES.$settings['theme']."/images/cap_green_left.gif' border=0></td>\n";
        echo "<td class='scapmain' width='100%'><img src='".THEMES.$settings['theme']."/images/bullet_side.png' border=0><strong>$title</td>\n";
        if ($collapse == true) {
                $boxname = str_replace(" ", "", $title);
                echo "<td class='scapmain' align='right'>".panelbutton($state, $boxname)."</td>\n";
        }
        echo "<td class='scapmain-right'><img src='".THEMES.$settings['theme']."/images/cap_green_right.gif' border=0></td>\n";
        echo "</tr>\n</table>\n";
        echo "<table cellpadding='0' cellspacing='0' width='100%' class='spacer' style='border: 1px solid #955adc; border-top: 0px;'>\n<tr>\n";
        echo "<td class='side-body'>\n";
        if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside() {
        global $panel_collapse;
        if ($panel_collapse == true) { echo "</div>\n"; }
        echo "</td>\n</tr>\n</table>\n";
}
?>

