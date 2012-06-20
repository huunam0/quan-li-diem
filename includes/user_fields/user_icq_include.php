<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        echo "<tr>\n";
        echo "<td class='tbl'>".$locale['uf_icq'].":</td>\n";
        echo "<td class='tbl'><input type='text' name='user_icq' value='".(isset($user_data['user_icq']) ? $user_data['user_icq'] : "")."' maxlength='16' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        if ($user_data['user_icq']) {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_icq']."</td>\n";
                echo "<td align='right' class='tbl1'><font size='2'>".$user_data['user_icq']."&nbsp;</td>\n";
                echo "</tr>\n";
        }
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_icq";
        $db_values .= ", '".(isset($_POST['user_icq']) && isnum($_POST['user_icq']) ? $_POST['user_icq'] : "")."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_icq='".(isset($_POST['user_icq']) && isnum($_POST['user_icq']) ? $_POST['user_icq'] : "")."'";
}
?>
