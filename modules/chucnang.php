<?php


openside("Lựa chọn công việc", true);
echo "* Thời khoá biểu trường";
if (iMEMBER) {
	echo "<br>* Thời khoá biểu cá nhân";
	$result=dbquery("select tenlop from qlt_dslop where donvi=$madonvi and gvcn=".$userdata['user_id']);
	if (dbrows($result)) {
		$data=dbarray($result);
		echo "<hr>Lớp chủ nhiệm: <b>".$data['tenlop']."</b>";
		echo "<br>* <a href='lilich.php?lop=".$data['tenlop']."'>Nhập danh sách HS</a>";
		echo "<br>* <a href='diemth.php?lop=".$data['tenlop']."'>Bảng điểm tổng hợp</a>";
	}
	//echo "select lop, mon, tenmon from qlt_phanconggd, qlt_monhoc where qlt_phanconggd.mon=qlt_monhoc.mamon and gvbm=".$userdata['user_id'];
	$result = dbquery("select lop, mon, tenngan from qlt_phanconggd, qlt_monhoc where qlt_phanconggd.mon=qlt_monhoc.mamon and gvbm=".$userdata['user_id']." and donvi=$madonvi order by stt,lop");
	if (dbrows($result)) {
		echo "<hr>Nhập điểm cho các lớp:";
		echo "<table border=0 width=100%><tr>";
		$i=1;
		while ($data=dbarray($result)) {
			echo "<td width=50% ><a href='diemct.php?lop=".$data['lop']."&mon=".$data['mon']."'>".$data['lop']."-".$data['tenngan']."</a></td>";
			if ($i>=2) {
				$i=1;
				echo "</tr><tr>";
			} else $i++;
			//$i=1-$i;
		}
		echo "</tr></table>";
		//echo "</table>";
	}
}

if (iMOD) {
	$result = dbquery("SELECT * FROM qlt_admin  ORDER BY admin_page, admin_title ASC");
	$rows = dbrows($result);
	if ($rows != 0) {
		//echo "<hr>Công việc quản lí: <br>";
		$cnang="";
		while ($data = dbarray($result)) {
			if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
				$cnang.= " [<a href='".$data['admin_link'].$aidlink."'>".$data['admin_title']."</a>]<br>";
			}
		}
		if ($cnang) {echo "<hr>Công việc quản lí: <br>".$cnang; }
	}
	
}
closeside();


?>
