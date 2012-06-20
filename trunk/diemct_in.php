<?php
require_once "maincore.php";
 if (!iMEMBER) {redirect("index.php");}
 ?>
 <!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>
<head>
<title>Quan li diem</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name='description' content='Group&#39;s Events Calendar' />
<meta name='keywords' content='THN, group, event, calendar' />
<link rel='stylesheet' href='themes/Bleu/styles.css' type='text/css' media='screen' />
<link rel='shortcut icon' href='images/favicon.ico' type='image/x-icon' />
<script type='text/javascript' src='includes/jscript.js'></script>
<script type='text/javascript' src='includes/jquery.js'></script>
</head>
<body>

 <?
if (isset($_GET['hocki']) && isnum($_GET['hocki'])) {
	$hocki=$_GET['hocki'];
} else {
	$hocki=dblookup("value","qlt_thamso","name='hocki'");
}
$lop=strtoupper($_GET['lop']);
	$mon=strtoupper($_GET['mon']);
	$tenmon = dblookup("tenmon","qlt_monhoc","mamon='$mon'");
	$gvbm=dblookup("gvbm","qlt_phanconggd","lop='".$lop."' and mon='".$mon."' and donvi=$madonvi");
	$xeploai=dblookup("xeploai","qlt_phanconggd","lop='".$lop."' and mon='".$mon."' and donvi=$madonvi")==1;//mon xep loai hay khong?
	$socotdiem=0;
	for ($i=1;$i<4;$i++) {
		$result2=dbquery("select max(qlt_diemct.stt) from qlt_diemct,qlt_dshocsinh where qlt_diemct.mahs=qlt_dshocsinh.id and lop='".$lop."' and mon='".$mon."' and donvi=$madonvi and hocki=".$hocki." and heso=".$i);
		$coths[$i]=dbresult($result2);
		if (!$coths[$i]) {$coths[$i]=0;}
		//echo $i."-".$coths[$i]." ";
		$socotdiem+=$coths[$i];
	}
	
		include("bacxloai.php");
		echo("<div align=center>$donvi - $captren</div><BR>");
		echo("<div align=center><h3>BẢNG ĐIỂM CHI TIẾT HỌC KÌ ".$hocki."</h3>");
		echo("<div align=center><h4>Lớp: ".$lop." - Môn: ".$tenmon." - GVBM: ".dblookup("user_name","qlt_users","user_id=".$gvbm)."</h4></div><br>");
		//opentable("Bảng điểm chi tiết học kì ".$hocki." - Lớp: ".$lop." - Môn: ".$tenmon." - GVBM: ".dblookup("user_name","qlt_users","user_id=".$gvbm));
		$result=dbquery("select id,hoten,stt from qlt_dshocsinh where lop='".$lop."' and donvi=$madonvi order by stt"); //lay danh sach lop
		if (dbrows($result)) { //neu co hoc sinh
			echo "<table class='mau center' ><tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td>";
			if ($xeploai) {
				echo "<td colspan=".($socotdiem>0?$socotdiem:1).">XL bài kiểm tra </td><td><b>XLHK$hocki</b></td>".($hocki==2?"<td><b>XL ca nam</b></td>":"")."</tr>";
			} else {
				for ($i=1;$i<=3;$i++) {
					echo "<td colspan=".$coths[$i].">Hệ số ".$i." (".$coths[$i].") </td>";
				}
				echo "<td><b>TBM.hk$hocki</b></td>".($hocki==2?"<td><b>TBM ca nam</b></td>":"")."</tr>";
			}
			$t=1;
			while ($data=dbarray($result)) {
				echo "<tr class='info0 tbl".$t."' align=center><td>".$data['stt']."</td><td align=left>".$data['hoten']."</td>";
				$t=4-$t;
				$result2=dbquery("select * from qlt_diemct where mon='".$mon."' and mahs=".$data['id']." and hocki=".$hocki." order by heso,stt");
				
				if ($xeploai) {//mon hoc xep lloai
					if ($socotdiem==0) $socotdiem++;
					if (dbrows($result2)) {
							$x=1; 
							while ($diem=dbarray($result2)) {
									while ($x<$diem['stt']) {
										echo "<td>-</td>";
										$x++;
									}
									echo "<td>".(isset($diem['diem'])?$bacxl[$diem['diem']]:"-")."</td>";
									$x++;
								}
							
							while ($x<=$socotdiem) {
								echo "<td>-</td>";
								$x++;
							}
							
						//}
					} else {
						for ($i=1;$i<=$socotdiem;$i++) {echo "<td>-</td>";}
					}
				} else { //khong xep loai, mon tinh trung binh
					$hs=1;
					for ($i=1;$i<4;$i++) {
						if ($coths[$i]==0) {
							$coths[$i]=1;
							$socotdiem++;
						} else {
							//$socotdiem++;
						}
						
					}
					if (dbrows($result2)) { //da co cot diem chi tiet
						//for ($h=1;$h<4;$h++) {
							 $x=1;
							while ($diem=dbarray($result2)) {
								//$hs=1;
								if ($diem['heso']>$hs) {
									while ($diem['heso']>$hs) {
										while ($x<=$coths[$hs]) {
											echo "<td>-</td>";
											$x++;
										}
										$hs++;
										$x=1;
									}
									if ($diem['heso']==$hs) {
										echo "<td>".$diem['diem']."</td>";
									}
									$x=2;
									
								} else {
									while ($x<$diem['stt']) {
										echo "<td>-</td>";
										$x++;
									}
									echo "<td>".(isset($diem['diem'])?$diem['diem']:"-")."</td>";
									$x++;
								}
							}
							while ($x<=$coths[$hs]) {
								echo "<td>-</td>";
								$x++;
							}
							while ($hs<3) {
								echo "<td>-</td>";
								$hs++;
							}
							
						//}
					} else { //CHua co cot diem nao
						for ($i=1;$i<=$socotdiem;$i++) {echo "<td>-</td>";}
					}
				}
				$diemtb=dblookup("tbm","qlt_diemth","hocki=".$hocki." and mahs='".$data['id']."' and mon='".$mon."'");
				echo "<td><b>".($diemtb?($xeploai?$bacxl[$diemtb]:$diemtb):"...")."</b></td>";
				if ($hocki==2) {
					$diemtb=dblookup("tbm","qlt_diemth","hocki=3 and mahs='".$data['id']."' and mon='".$mon."'");
					echo "<td><b>".($diemtb?($xeploai?$bacxl[$diemtb]:$diemtb):"...")."</b></td>";
				}
				echo "</tr>";
			}
			
			echo "</table>";
			$ngaytinhtb=dblookup("ngaytinhtb","qlt_phanconggd","lop='".$lop."' and mon='".$mon."' and donvi=$madonvi");
			if($ngaytinhtb) echo "<br><div align=center>Điểm TBM cập nhật lúc ".showdate("forumdate", $ngaytinhtb)."</div>";
		} else { //chua co hoc sinh
			echo "Chưa có học sinh nào!";
		} 
		//closetable();
	
?>
</body>
</html>
