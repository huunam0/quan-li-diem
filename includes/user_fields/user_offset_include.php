<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
                echo "<tr>\n";
                echo "<td class='tbl'>".$locale['uf_offset']."</td>\n";
                echo "<td class='tbl'><select name='user_offset' class='textbox' style='width:100px;'>\n<option value='0'".(($user_data['user_offset'] == 2) ? " selected" : "")."> Nam </option>\n<option value='1'".(($user_data['user_offset'] == 1) ? " selected" : "")."> Nữ </option>\n</select></td>\n";
                echo "</tr>\n";
} elseif ($profile_method == "display") {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_offset']."</td>\n";
                echo "<td align='right' class='tbl1'>".(($user_data['user_offset'] == 1) ? " Nữ " : " Nam " )."</td>\n";
                echo "</tr>\n";
} elseif ($profile_method == "validate_insert") {
        $db_fields .= ", user_offset";
        $db_values .= ", '".$_POST['user_offset']."'";
} elseif ($profile_method == "validate_update") {
        $db_values .= ", user_offset='".$_POST['user_offset']."'";
}
?>
