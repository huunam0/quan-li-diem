<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        echo "<tr>\n";
        echo "<td class='tbl'>".$locale['uf_location'].":</td>\n";
        echo "<td class='tbl'><input type='text' name='user_location' value='".(isset($user_data['user_location']) ? $user_data['user_location'] : "")."' maxlength='50' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        if ($user_data['user_location']) {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_location']."</td>\n";
                echo "<td align='right' class='tbl1'>".$user_data['user_location']."</td>\n";
                echo "</tr>\n";
        }
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_location";
        $db_values .= ", '".(isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "")."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_location='".(isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "")."'";
}
?>
