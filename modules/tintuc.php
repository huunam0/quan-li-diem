<?php
if (FUSION_SELF!="news.php") {
openside("Tin tức hoạt động", $collapse = false);
//echo FUSION_SELF;

	$result = dbquery("select * from qlt_tintuc order by ngaygio desc limit 5");
	if (dbrows($result)) {
		echo "<table border=0>";
		while ($data=dbarray($result)) {
			echo "<tr class='info0'><td><a href ='".BASEDIR."news.php?view=".$data[id]."'>".$data["tieude"]."</a> [<i>";
			echo showdate("%d/%m/%y", $data['ngaygio'])."</i>]</td>";
			//onclick='".PHP_SELF."?id=".$data[id]."'
		}
		echo "</table>";
	}


closeside();
}
?>