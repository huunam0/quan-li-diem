<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."search/custompages.php";

$form_elements['custompages']['enabled'] = array("fields1", "fields2", "fields3", "order1", "order2", "chars");
$form_elements['custompages']['disabled'] = array("datelimit", "sort");
$form_elements['custompages']['display'] = array();
$form_elements['custompages']['nodisplay'] = array();

$radio_button['custompages'] = "<input type='radio' name='stype' id='custompages' value='custompages'".($_GET['stype'] == "custompages" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> <label for=\"custompages\">".$locale['c400']."</label>";
?>
