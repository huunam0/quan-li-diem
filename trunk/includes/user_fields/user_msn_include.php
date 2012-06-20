<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        echo "<tr>\n";
        echo "<td class='tbl'>".$locale['uf_msn'].":</td>\n";
        echo "<td class='tbl'><input type='text' name='user_msn' value='".(isset($user_data['user_msn']) ? $user_data['user_msn'] : "")."' maxlength='50' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        if ($user_data['user_msn']) {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_msn']."</td>\n";
                echo "<td align='right' class='tbl1'>".hide_email($user_data['user_msn'])."</td>\n";
                echo "</tr>\n";
        }
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_msn";
        $db_values .= ", '".(isset($_POST['user_msn']) ? stripinput(trim($_POST['user_msn'])) : "")."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_msn='".(isset($_POST['user_msn']) ? stripinput(trim($_POST['user_msn'])) : "")."'";
}
?>
