<?
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: mp3_bbcode_include.php
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

$text = preg_replace('#\[mp3\](.*?)\[/mp3\]#si', '<center><fieldset style=\'width:280px; height:58px; padding: 3px;\'><legend align=\'center\'><span class=\'small\'><strong><a href=\'#show_play\' onClick=\'document.getElementById("\1").style.display = "block";document.getElementById("Help_\1").style.display = "none"\'>[MP3 / WMA Player]</a></strong></span></legend><OBJECT id=\'\1\' style=\'FILTER: Alpha(Opacity=50); display: none\' type=\'application/x-oleobject\' CLASSID=\'CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6\' codebase=\'http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=5,1,52,701\' width=\'280\' HEIGHT=\'46\'><param name=\'url\' value=\'\1\'><param name=\'EnableContextMenu\' value=0><param name=\'wmode\' value=\'opaque\'><PARAM NAME=\'stretchToFit\' VALUE=\'true\'><PARAM NAME=\'autostart\' VALUE=\'false\'><EMBED type=\'application/x-mplayer2\' wmode=\'opaque\' quality=high pluginspage=\'http://www.microsoft.com/Windows/MediaPlayer/\' file=\'\1\' src=\'\1\' width=\'280\' HEIGHT=\'46\' AutoStart=\'0\' EnableContextMenu=\'0\' Mute=\'0\' ShowStatusBar=\'1\'></OBJECT><span id=\'Help_\1\'>&nbsp;Click to Show Player</span></fieldset></center>', $text);
?>
