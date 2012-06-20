<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (iMEMBER) {
        include LOCALE.LOCALESET."search/members.php";

        $form_elements['members']['enabled'] = array("order1", "order2");
        $form_elements['members']['disabled'] = array("datelimit", "fields1", "fields2", "fields3", "sort", "chars");
        $form_elements['members']['display'] = array();
        $form_elements['members']['nodisplay'] = array();

        $radio_button['members'] = "<label><input type='radio' name='stype' id='members' value='members'".($_GET['stype'] == "members" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> <label for=\"members\">".$locale['m400']."</label>";
}
?>
