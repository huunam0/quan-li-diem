openside("Lựa chọn công việc", true);
echo "Thời khoá biểu trường";
if (checkgroup(11) ) {
	echo "<br>Thời khoá biểu cá nhân";
	$result=dbquery("select tenlop from qlt_dslop where gvcn=".$userdata['user_id']);
	if (dbrows($result)) {
		$data=dbarray($result);
		echo "<br>Lớp chủ nhiệm ".$data['tenlop'];
	}
	$result = dbquery("select lop, mon, tenmon from qlt_phanconggd, qlt_monhoc where qlt_phanconggd.mon=qlt_monhoc.mamon and gvbm=".$userdata['user_id']);
	if dbrows($result) {
		echo "<br>Các lớp dạy:";
		while ($data=dbarray($result)) {
			echo "<br>".$data['lop']." ".$data['tenmon']; 
		}
	}
}

closeside();