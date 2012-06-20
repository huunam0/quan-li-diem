<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
echo "<script type='text/javascript' src='".INCLUDES."combobox.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $heade';lr_reg; 
if (!iMEMBER) {redirect("index.php");}

if ((!iMOD) && (!checkrights("PL"))) {redirect("index.php");}

$hpanel=false;
if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }


echo "<form method='post' action='".FUSION_SELF."?act=view'>";
echo "Chon danh sach hoc sinh <select name='lop' onchange='this.form.submit()'><option></option><option value='0' ".("0"==$_POST['lop']?"selected":"").">Hoc sinh chua phan lop</option>";
$result=dbquery("select * from qlt_dslop where donvi=$madonvi ");
if (dbrows($result)) {
	while ($data=dbarray($result)) {
		echo "<option value='".$data['tenlop']."' ".($data['tenlop']==$_POST['lop']?"selected":"").">".$data['tenlop']."</option>";
	}
}
echo "</select></form><br>";
if (isset($_POST['luu'])) { //cap nhat
	$result=dbquery("select * from qlt_dshocsinh where  lop='".$_POST['lop']."' and  donvi=$madonvi order by stt");
	$sql="xxx";
	if (dbrows($result)) {
		while ($data=dbarray($result)) {
			$xxx="if(id=".$data['id'].",'".$_POST['id_'.$data['id']]."',xxx)";
			$sql=str_replace("xxx",$xxx,$sql);
		}
	}
	$sql=str_replace("xxx","lop",$sql);
	$result=dbquery("update qlt_dshocsinh set lopcu=lop, lop=$sql where   lop='".$_POST['lop']."' and  donvi=$madonvi ");
}

	opentable("Danh sách học sinh can phan lớp # ".($_POST['lop']=='0'?"Chua phan lop":$_POST['lop']));
		//echo "select * from qlt_dshocsinh where ".($_POST['lop']=="all"?"":" lop='".$_POST['lop']."' and ")." donvi=$madonvi order by stt";
		$result=dbquery("select * from qlt_dshocsinh where  lop='".$_POST['lop']."' and  donvi=$madonvi order by stt");
		if ($siso=dbrows($result)) {//liet ke ds hs lopcn
			echo "<form method='post' action='".FUSION_SELF."?act=update'>";
			echo "<table class='mau small center'><tr class='tbl3 toosmall' align=center><td class='toosmall'>Mã HS</td><td>TT</td><td  class='toosmall'>Họ và tên</td><td class='toosmall'>Hệ</td>";
			echo "<td class='toosmall'>Nữ</td><td class='toosmall'>Ngày sinh</td><td class='toosmall'>Lớp</td><td>Nơi sinh</td><td>Địa chỉ</td><td  class='toosmall'>Số đ-thoại</td><td class='toosmall'>Họ tên Cha - Nghề</td>";
			echo "<td class='toosmall'>Họ tên Mẹ - Nghề</td><td class='toosmall'>Đ. viên</td><td class='toosmall'>Con LS</td><td class='toosmall'>Con TB</td><td>Lớp cũ</td></tr>";
			$i=3;
			while ($data=dbarray($result)) {
				$i=4-$i;
				echo "<tr class='info0 tbl$i'><td>".$data['id']."</font></td><td  align=center>".$data['stt']."</td><td class='toosmall'><a href='".FUSION_SELF."?lop=".$data['lop']."&act=edit&id=".$data['id']."'>".$data['hoten']."</a></td><td class='toosmall'>".$data['he']."</td><td align=center>".($data['gioinu']?"X":"")."</td>";
				echo "<td class='toosmall'>".date('d-m-Y', strtotime($data['ngaysinh']))."</td><td ><select name='id_".$data['id']."'><option value='0'>Nghi</option>";
				
				$result2=dbquery("select * from qlt_dslop where donvi=$madonvi ");
				if (dbrows($result2)) {
					while ($data2=dbarray($result2)) {
						echo "<option value='".$data2['tenlop']."' ".($data2['tenlop']==$data['lop']?"selected":"").">".$data2['tenlop']."</option>";
					}
				}
				echo"</select></td><td  class='toosmall'>".$data['noisinh']."</td><td class='toosmall'>".$data[dcthon].", ".$data['dcxa']."</td><td class='toosmall'>".$data['sodt']."</td>";
				echo "<td class='toosmall'>".$data['hotencha']." - ".$data['nghecha']."</td><td  class='toosmall'>".$data['hotenme']." - ".$data['ngheme']."</td><td align=center>".($data['doanvien']?"X":"")."</td>";
				echo "<td align=center>".($data['conlietsi']?"X":"")."</td><td class='toosmall'>".($data['conthuongbinh']?$data['conthuongbinh']."%":"")."</td><td >".$data['lopcu']."</td>";
				
				echo "</tr>";
			}
			
			echo "</table><br>";
			echo "<input type='hidden' name='lop' value='".$_POST['lop']."'>";
			echo "<div align=center><input type='submit' name='luu' value='Cap nhat'></div>";
			echo "</form>";
			
		} else {
			
			echo "Chưa có học sinh nào.<br>";
		}
		closetable();




require_once THEMES."templates/footer.php";
?>
