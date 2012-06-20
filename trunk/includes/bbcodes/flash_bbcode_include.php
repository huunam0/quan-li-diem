<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: flash_bbcode_include.php
| Author: Wooya
| Improoved by: jantom
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

$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\]([^\s\'\";\+]*?)(\.(swf|flv))\[/flash\]#si', '<embed type=\'application/x-shockwave-flash\' src=\''.IMAGES.'tv/player.swf\' style=\'\' id=\'ply\' name=\'ply\' bgcolor=\'#000000\' quality=\'high\' allowfullscreen=\'true\' wmode=\'transparent\' allowscriptaccess=\'always\' flashvars=\'file=\3\4&amp;autostart=false\' width=\'\1\' height=\'\2\'>', $text);

?>

