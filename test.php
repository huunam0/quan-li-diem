<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $header_reg; 

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

add_to_title($locale['global_200'].$locale['400']);
if (isset($_GET['id'])) {
	echo "Tham so la ".decodetext($_GET['id']);
}
opentable("abc");
$temp="test.php?act=edit&lop=10a1&mon=toan1";
$temp= "".mahoaurl($temp)."";
echo "<a href='$temp'>$temp</a>";
closetable();

require_once THEMES."templates/footer.php";
?>
