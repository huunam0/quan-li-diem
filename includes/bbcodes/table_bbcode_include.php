<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: table_bbcode_include.php
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
 
$text = preg_replace('#\[table\](.*?)\[/table\]#si', '<table>\1</table>', $text);
$text = preg_replace('#\[row\](.*?)\[/row\]#si', '<tr>\1</tr>', $text);
$text = preg_replace('#\[cell\](.*?)\[/cell\]#si', '<td>\1</td>', $text);
$text = preg_replace('#\[cell c\](.*?)\[/cell\]#si', '<td align=center>\1</td>', $text);
$text = preg_replace('#\[cell r\](.*?)\[/cell\]#si', '<td align=right>\1</td>', $text);
$text = preg_replace('#\[tr\](.*?)\[/tr\]#si', '<tr><td>\1</td></tr>', $text);
$text = preg_replace('#\[td\]#si', '</td><td>', $text);

?>
