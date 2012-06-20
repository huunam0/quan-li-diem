<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."search/faqs.php";

$form_elements['faqs']['enabled'] = array("fields1", "fields2", "fields3", "order1", "order2");
$form_elements['faqs']['disabled'] = array("datelimit", "sort", "chars");
$form_elements['faqs']['display'] = array();
$form_elements['faqs']['nodisplay'] = array();
$radio_button['faqs'] = "<label><input type='radio' id='faqs' name='stype' value='faqs'".($_GET['stype'] == "faqs" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> <label for=\"faqs\">".$locale['fq400']."</label>";
?>
