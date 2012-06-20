<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: table_bbcode_include_var.php
| Author: Jeepers1993
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
 
$__BBCODE__[] = array(
"description"	=>	"Table",
"value"			=>	"table",
"bbcode_start"	=>	"[table] ",
"bbcode_end"	=>	"[/table]",
"usage"			=>	"[table]Content[/table]"
);

$__BBCODE__[] = array(
"description"	=>	"Row",
"value"			=>	"row",
"bbcode_start"	=>	"  [row]",
"bbcode_end"	=>	"  [/row]",
"usage"			=>	"[row]Content[/row]"
);

$__BBCODE__[] = array(
"description"	=>	"Cell",
"value"			=>	"cell",
"bbcode_start"	=>	"    [cell]",
"bbcode_end"	=>	"    [/cell]",
"usage"			=>	"[cell]Content[/cell]"
);

$__BBCODE__[] = array(
"description"	=>	"auto tr",
"value"			=>	"auto tr",
"bbcode_start"	=>	"[tr]",
"bbcode_end"	=>	"[/tr]",
"usage"			=>	"[tr]Content[/tr]"
);

$__BBCODE__[] = array(
"description"	=>	"auto td",
"value"			=>	"auto td",
"bbcode_start"	=>	"[td]",
"bbcode_end"	=>	"",
"usage"			=>	"[td]"
);
?>
