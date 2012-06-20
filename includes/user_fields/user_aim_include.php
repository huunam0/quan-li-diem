<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        echo "<tr>\n";
        echo "<td class='tbl'>".$locale['uf_aim'].":</td>\n";
        echo "<td class='tbl'><input type='text' name='user_aim' value='".(isset($user_data['user_aim']) ? $user_data['user_aim'] : "")."' maxlength='16' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        if ($user_data['user_aim']) {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_aim']."</td>\n";
                echo "<td align='right' class='tbl1'>".$user_data['user_aim']."&nbsp;<img style='vertical-align:middle;border:none' src='http://www.IMStatusCheck.com/status/aim/".$user_data['user_aim']."' alt='".$user_data['user_aim']."' /></td>\n";
                echo "</tr>\n";
        }
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_aim";
        $db_values .= ", '".(isset($_POST['user_aim']) ? stripinput(trim($_POST['user_aim'])) : "")."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_aim='".(isset($_POST['user_aim']) ? stripinput(trim($_POST['user_aim'])) : "")."'";
}
?>
