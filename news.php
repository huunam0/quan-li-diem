<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

add_to_title(" - Tin tức hoạt động");
opentable("Tin tức hoạt động");

if (isset($_GET["view"])) {
	$result=dbquery("select * from qlt_tintuc where id=".$_GET["view"]);
	if (dbrows($result)) {
		$data=dbarray($result);
		$message = parseubb($data['noidung']);
		echo "<table border=0 width=100%><tr><td><b>".$data['tieude']." </td></tr><tr><td>".$message."</td></tr>";
		echo "<tr><td align=right><a href='".FUSION_SELF."'>Xem tin khác</a></td></tr>";
		$author = dblookup("user_name","qlt_users","user_id=".$data['user_id']);
		echo "<tr><td align=right>Ðăng bởi <b>".$author."</b> lúc ".showdate("forumdate", $data['ngaygio'])."</td></tr></table>";
	} else {
		echo "Không tìm thấy";
	}
} else {
	$result = dbquery("select * from qlt_tintuc order by ngaygio desc limit 10");
	if (dbrows($result)) {
		echo "<table border=0><tr><td>Ngày đăng tin</td><td>Tiêu đề</td></tr>";
		while ($data=dbarray($result)) {
			echo "<tr class='info0'><td>".showdate("%d/%m/%y", $data['ngaygio'])."</td>";
			//$message = parseubb($message);
			echo "<td><a href ='".FUSION_SELF."?view=".$data[id]."'>".$data["tieude"]."</td></tr>";
		}
		echo "</table>";
	} else {
		echo "Không có tin nào";
	}
}
closetable();

require_once THEMES."templates/footer.php";
?>
