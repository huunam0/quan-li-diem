<?php
require_once "maincore.php";
 if (!iMEMBER) {redirect("index.php");}
 ?>
 <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<title>Quan li diem</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name='description' content='Quan li diem' />
<link rel='stylesheet' href='themes/Bleu/styles.css' type='text/css' media='screen' />
<link rel='shortcut icon' href='images/favicon.ico' type='image/x-icon' />
<script type='text/javascript' src='includes/jscript.js'></script>
<script type='text/javascript' src='includes/jquery.js'></script>
</head>
<body>

 <?
 $thamso=decodetext($_GET['id']);

if ($ghocki=getget($thamso,'hocki')) {
	$hocki = intval($ghocki);
} else {
	$hocki=dblookup("value","qlt_thamso","name='hocki'");
}

$lop=strtoupper(getget($thamso,'lop'));
$gvcn=dblookup("gvcn","qlt_dslop","tenlop='$lop' and donvi=$madonvi ");
	$hluc=array ("Chưa XL","Giỏi","Khá","TB","Yếu","Kém");
		$hk = array ("Dau nam","Học kì 1","Học kì 2","Cả năm");
		echo("<div align=center>$donvi - $captren</div><BR>");
		echo("<div align=center><h3>BẢNG ĐIỂM TỔNG HỢP ".$hk[$hocki]."</h3>");
		echo("<div align=center><h4>Lớp: ".$lop."  - GVCN: ".dblookup("user_name","qlt_users","user_id=".$gvcn)."</h4></div><br>");
		$hkiem=array("-","Tốt","Khá","TB","Yếu");
	$dhtd=array("-","HS Giỏi","HS Tiên tiến","Lên lớp thẳng","Thi lại","Rèn luyện HK","Ở lại lớp");
	//opentable("Bảng điểm tổng hợp ".$hk[$hocki]." - lớp ".$lop);
	echo "<table class='mau center'><tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td>";
	//hien thi cac mon hoc (hang ngang)
	$result=dbquery("select * from qlt_monhoc order by stt");
	$mamon[0]="";
	$i=1;
	if ($somon=dbrows($result)) {
		while ($data=dbarray($result)) {
			echo "<td >".$data['tenngan']."</td>";
			$mamon[$i++]=$data['mamon'];
			$xeploai[$data['mamon']]=dblookup("xeploai","qlt_phanconggd","lop='$lop' and mon='".$data['mamon']."' and donvi=$madonvi");
		}
	}
	$mamon[$i++]="TBCM";
	$mamon[$i++]="HL";
	$mamon[$i++]="HK";
	$mamon[$i++]="DHTD";
	$somon+=4;
	echo "<td>TBCM</td><td>HL</td><td>HK</td><td>Ghi chú</td><tr>";
	include("bacxloai.php");
	
	$result=dbquery("select * from qlt_dshocsinh where lop='$lop' and donvi=$madonvi order by stt");
	
	if ($siso=dbrows($result)) {
		//lay bang diem tbm hk
		$result2=dbquery("select qlt_diemth.* from qlt_dshocsinh, qlt_diemth where qlt_dshocsinh.id=qlt_diemth.mahs and hocki=".$hocki." and lop='".$lop."' and donvi=$madonvi order by mahs,mon");
		if (dbrows($result2)) {
			while ($data=dbarray($result2)) {
				$tbm[$data['mahs']][$data['mon']]=($xeploai[$data['mon']]?$bacxl[$data['tbm']]:$data['tbm']);
			}
		}
		//lay thong tin tong ket
		$result2=dbquery("select qlt_tongket.* from qlt_dshocsinh, qlt_tongket where qlt_dshocsinh.id=qlt_tongket.mahs and lop='$lop' and donvi=$madonvi and hocki=".$hocki);
		while ($data=dbarray($result2)){
			$tbm[$data['mahs']]['TBCM']=$data['tbcm'];
			$tbm[$data['mahs']]['HL']=$hluc[$data['hl']];
			$tbm[$data['mahs']]['HK']=$hkiem[$data['hk']];
			$tbm[$data['mahs']]['DHTD']=$dhtd[$data['dhtd']];
		}
		//hien thi danh sach
		$i=1;
		while ($data=dbarray($result)) {
			//$tbm[1][$data['stt']]=$data['id'];
			//$tbm[1][$data['stt']]=$data['hoten'];
			echo "<tr class='tbl$i info0' align=center><td>".$data['stt']."</td><td align=left>".$data['hoten']."</td>";
			for ($j=1; $j<=$somon; $j++) {
				echo "<td>".$tbm[$data['id']][$mamon[$j]]."</td>";
			}
			echo "</tr>";
			$i=4-$i;
		}

		
		
		
		
	}
	echo "</table>";
	
	
?>
</body>
</html>
