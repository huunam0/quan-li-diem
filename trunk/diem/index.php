<?php
require_once "../maincore.php";
require_once THEMES."templates/header.php";
echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
//echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $header_reg; 

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

add_to_title(" - Quản lí điểm");
opentable("Quản lí điểm");
echo "Quản lí điểm";
closetable();
require_once THEMES."templates/footer.php";
?>
