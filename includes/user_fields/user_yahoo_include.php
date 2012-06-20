<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        echo "<tr>\n";
        echo "<td class='tbl'>".$locale['uf_yahoo'].":</td>\n";
        echo "<td class='tbl'><input type='text' name='user_yahoo' value='".(isset($user_data['user_yahoo']) ? $user_data['user_yahoo'] : "")."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        if ($user_data['user_yahoo']) {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_yahoo']."</td>\n";
                echo "<td align='right' valign='top' class='tbl1'><A title='Yahoo! ".$user_data['user_yahoo']."' HREF='ymsgr:sendIM?".$user_data['user_yahoo']."'>".$user_data['user_yahoo']."&nbsp;
                <IMG SRC=\"http://osi.techno-st.net:8000/yahoo/".$user_data['user_yahoo']."/onurl=thptnguyendu.com/images/yahooonline.gif/offurl=thptnguyendu.com/images/yahoooffline.gif/unknownurl=thptnguyendu.com/images/ircoffline.gif\"
                align='absmiddle' border=0 ALT='Yahoo! ".$user_data['user_yahoo']."' onerror=\"this.onerror=null;this.src='http://thptnguyendu.com/images/ircoffline.gif';\"></A></td>
                </tr>
                <tr><td colspan='2' align='right' valign='top' class='tbl1'><img alt='".$user_data['user_yahoo']." Yahoo Avatar' src=\"http://img.msg.yahoo.com/avatar.php?yids=?".$user_data['user_yahoo']."\" border=0></td>\n";
                echo "</tr>\n";
        }
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_yahoo";
        $db_values .= ", '".(isset($_POST['user_yahoo']) ? stripinput(trim($_POST['user_yahoo'])) : "")."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_yahoo='".(isset($_POST['user_yahoo']) ? stripinput(trim($_POST['user_yahoo'])) : "")."'";
}
?>
