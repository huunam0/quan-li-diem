<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

$user_field_name = $locale['uf_birthdate'];
$user_field_desc = $locale['uf_birthdate_desc'];
$user_field_dbname = "user_birthdate";
$user_field_group = 2;
$user_field_dbinfo = "DATE NOT NULL DEFAULT '0000-00-00'";
?>
