<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: u_bbcode_include_var.php
| Author: Wooya
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("QLTRUONG")) { die("Access Denied"); }

$__BBCODE__[] = 
array(
"description"		=>	$locale['bb_u_description'],
"value"			=>	"u",
"bbcode_start"		=>	"[u]",
"bbcode_end"		=>	"[/u]",
"usage"			=>	"[u]".$locale['bb_u_usage']."[/u]"
);
?>