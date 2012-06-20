<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        if (isset($user_data['user_birthdate']) && $user_data['user_birthdate'] != "0000-00-00") {
                $user_birthdate = explode("-", $user_data['user_birthdate']);
                $user_month = number_format($user_birthdate['1']);
                $user_day = number_format($user_birthdate['2']);
                $user_year = $user_birthdate['0'];
        } else {
                $user_month = 0; $user_day = 0; $user_year = 0;
        }
        echo "<tr>\n";
        echo "<td class='tbl'>".$locale['uf_birthdate'].": <span class='small2'>(mm/dd/yyyy)</span></td>\n";
        echo "<td class='tbl'><select name='user_month' class='textbox'>\n<option value=''>&nbsp;</option>\n";
        for ($i = 1; $i <= 12; $i++) { echo "<option".($user_month == $i ? " selected='selected'" : "").">".$i."</option>\n"; }
        echo "</select>\n<select name='user_day' class='textbox'>\n<option value=''>&nbsp;</option>\n";
        for ($i = 1; $i <= 31; $i++) { echo "<option".($user_day == $i ? " selected='selected'" : "").">".$i."</option>\n"; }
        echo "</select>\n<select name='user_year' class='textbox'>\n<option value=''>&nbsp;</option>\n";
        for ($i = date("Y"); $i > 1975; $i--) { echo "<option".($user_year == $i ? " selected='selected'" : "").">".$i."</option>\n"; }
        echo "</select>\n</td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "display") {
        if ($user_data['user_birthdate'] != "0000-00-00") {
                echo "<tr>\n";
                echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_birthdate']."</td>\n";
                echo "<td align='right' class='tbl1'>";
                $months = explode("|", $locale['months']);
                $user_birthdate = explode("-", $user_data['user_birthdate']);
                echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0'];
                echo "</td>\n</tr>\n";
        }
} elseif ($profile_method == "validate_insert" || $profile_method == "validate_update") {
        if ((isset($_POST['user_month']) && $_POST['user_month'] != 0) && (isset($_POST['user_day']) && $_POST['user_day'] != 0) && (isset($_POST['user_year']) && $_POST['user_year'] != 0)) {
                $user_birthdate = (isnum($_POST['user_year']) ? $_POST['user_year'] : "0000")
                ."-".(isnum($_POST['user_month']) ? $_POST['user_month'] : "00")
                ."-".(isnum($_POST['user_day']) ? $_POST['user_day'] : "00");
        } else {
                $user_birthdate = "0000-00-00";
        }
        if ($profile_method == "validate_insert") {
                $db_fields .= ", user_birthdate";
                $db_values .= ", '".$user_birthdate."'";
        } elseif ($profile_method == "validate_update") {
                $db_values .= ", user_birthdate='".$user_birthdate."'";
        }
}
?>
