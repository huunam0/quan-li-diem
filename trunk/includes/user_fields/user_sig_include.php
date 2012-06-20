<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        require_once INCLUDES."bbcode_include.php";
        echo "<tr>\n";
        echo "<td valign='top' class='tbl'>".$locale['uf_sig']."</td>\n";
        echo "<td class='tbl'><textarea name='user_sig' cols='80' rows='9' class='textbox' style='width: 380px'>".(isset($user_data['user_sig']) ? $user_data['user_sig'] : "")."</textarea><br />\n";
        echo display_bbcodes("360px", "user_sig", "inputform", "smiley|b|i|u||left|right|center|small|url|mail|img|color")."</td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        // Not shown in profile
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_sig";
        $db_values .= ", '".(isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "")."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_sig='".(isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "")."'";
}
?>
