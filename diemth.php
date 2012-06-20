<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";

//echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $header_reg; 
if (!iMEMBER) redirect("index.php");
$hluc=array ("Chưa XL","Giỏi","Khá","TB","Yếu","Kém");
if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
$thamso=decodetext($_GET['id']);
//echo $thamso;
if ($ghocki=getget($thamso,'hocki')) {
	$hocki = intval($ghocki);
} else {
	$hocki=dblookup("value","qlt_thamso","name='hocki'");
}

if ($hocki<1 || $hocki>3) {redirect("index.php");}

$hk = array ("Dau nam","Học kì 1","Học kì 2","Cả năm");

if (!getget($thamso,"lop")) {redirect("index.php");}
$hpanel=true;
$lop=strtoupper(getget($thamso,"lop"));
$gvcn=dblookup("gvcn","qlt_dslop","tenlop='$lop' and donvi=$madonvi ");
$tengvcn = dblookup("user_name","qlt_users","user_id=".$gvcn);
$p_act=getget($thamso,'act');
if ($p_act=="tinhtbcm") { //tinh tbcm
	$result=dbquery("select hocki,mahs,round(sum(tbm*heso)/sum(heso)) as tbcm,min(tbm) as min_tbm,max(tbm*kchl) as max_tbmkc from (qlt_diemth join qlt_phanconggd on qlt_diemth.mon= qlt_phanconggd.mon) join qlt_dshocsinh on qlt_phanconggd.lop=qlt_dshocsinh.lop and qlt_diemth.mahs=qlt_dshocsinh.id and qlt_phanconggd.donvi=qlt_dshocsinh.donvi where  hocki=$hocki and xeploai=0 and qlt_dshocsinh.lop='".$lop."' and qlt_phanconggd.donvi=$madonvi group by mahs");
	if (dbrows($result)) {
		while ($data=dbarray($result)) {
			if ($data['tbcm']>=80 && $data['max_tbmkc']>=80 && $data['min_tbm']>=65) {
				$hocluc=1;//gioi
			} elseif ($data['tbcm']>=65 && $data['max_tbmkc']>=65 && $data['min_tbm']>=50) {
				$hocluc=2;//kha
			} elseif ($data['tbcm']>=50 && $data['max_tbmkc']>=50 && $data['min_tbm']>=35) {
				$hocluc=3;//tb
			} elseif ($data['tbcm']>=35 && $data['min_tbm']>=20) {
				$hocluc=4;//yeu
			} else $hocluc=5; //kem
			$hocluc1 =($data['tbcm']>=80?1:($data['tbcm']>=65?2:($data['tbcm']>=50?2:($data['tbcm']>=35?4:5))));
			if ($hocluc1==1 && $hocluc>=3) {
				$hocluc--; 
				if($hocluc>3) $hocluc=3;
				}
			if ($hocluc1==2 && $hocluc>=4) $hocluc--;
			
			if (dbcount2("qlt_tongket","hocki=".$data['hocki']." and mahs=".$data['mahs'])>0) {
				$result2=dbquery("update qlt_tongket set tbcm=".$data['tbcm'].", hl=".$hocluc." where hocki=".$data['hocki']." and mahs=".$data['mahs']);
			} else $result2=dbquery("insert into qlt_tongket (hocki,mahs,tbcm,hl) value (".$data['hocki'].",".$data['mahs'].",".$data['tbcm'].",".$hocluc.") ");
		}
	}
	
	redirect(mahoaurl(FUSION_SELF."?lop=$lop&hocki=$hocki"));
} elseif ($p_act=="xetdhtd") { //xet danh hieu thi dua
	$result=dbquery("update qlt_tongket set dhtd=if(hl=1 and hk=1,1,if(hl<=2 and hk<=2,2,if(hl<=3 and hk<=3,3,if(hl=4 and hk<4,4,if(hl<4 and hk=4,5,6))))) where mahs in (select id from qlt_dshocsinh where lop='".$lop."') and hocki=".$hocki);
	redirect(mahoaurl(FUSION_SELF."?lop=$lop&hocki=$hocki"));
} elseif ($p_act=="nhaphk") { //nhap hanh kiem
	if (isset($_POST['luu'])) { //cap nhat
		$result=dbquery("select stt,id,hoten from qlt_dshocsinh where lop='".$lop."' and donvi=$madonvi order by stt");
		if (dbrows($result)) {
			
			
			while ($data=dbarray($result)){
				$hkid='hk'.$data['id'];
				if (isset($_POST[$hkid])) {
					$result2=dbquery("update qlt_tongket set hk=".$_POST[$hkid]." where hocki=".$hocki." and mahs=".$data['id']);
				}
			}
	
		}
		redirect(mahoaurl(FUSION_SELF."?lop=$lop&hocki=$hocki"));
	} else {// nhap moi, chinh sua
		$result=dbquery("select stt,id,hoten from qlt_dshocsinh where lop='$lop' and donvi=$madonvi order by stt");
		if (dbrows($result)) {
			opentable("Nhập hạnh kiểm ".$hk[$hocki]." - Lớp: $lop - GVCN: $tengvcn");
			echo "<form method='post' action='".mahoaurl(FUSION_SELF."?lop=".$lop."&act=nhaphk&hocki=".$hocki)."'><table class='mau center'>";
			echo "<tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td><td>Hoc luc</td><td>Hạnh kiểm</td></tr>";
			$i=1;
			while ($data=dbarray($result)){
				$tongket=dbquery("select hl,hk from qlt_tongket where hocki=$hocki and mahs=".$data['id']);
				//$hkcu=dblookup("hk","qlt_tongket","hocki=".$hocki." and mahs=".$data['id']);
				$data2=dbarray($tongket);
				$hkcu=$data2['hk'];
				echo "<tr class='tbl$i info0'><td align=center>".$data['stt']."</td><td>".$data['hoten']."</td><td align=center>".$hluc[$data2['hl']]."</td><td><select name='hk".$data['id']."'><option value='0' ".($hkcu==0?"selected":"")."> </option>";
				//$hkcu=dblookup("hk","qlt_tongket","hocki=".$hocki." and mahs=".$data['id']);
				echo "<option value='1' ".($hkcu==1?"selected":"").">Tốt</option><option value='2' ".($hkcu==2?"selected":"").">Khá</option><option value='3' ".($hkcu==3?"selected":"").">TB</option><option value='4' ".($hkcu==4?"selected":"").">Yếu</option></select></td></tr>";
				$i=4-$i;
			}
			
			echo "</table><br><div align=center><input type='submit' name='luu' value='Lưu'></div></form>";
			closetable();
		}
	}
	//select hocki,mahs,sum(tbm*heso)/sum(heso) from (qlt_diemth join qlt_phanconggd on qlt_diemth.mon= qlt_phanconggd.mon) join qlt_dshocsinh on qlt_phanconggd.lop=qlt_dshocsinh.lop and qlt_diemth.mahs=qlt_dshocsinh.id where  qlt_dshocsinh.lop='11a2' group by mahs
	//redirect(FUSION_SELF."?lop=".$lop);
} else {
	
	$hkiem=array("-","Tốt","Khá","TB","Yếu");
	$dhtd=array("-","HS Giỏi","HS Tiên tiến","Lên lớp thẳng","Thi lại","Rèn luyện HK","Ở lại lớp");
	opentable("Bảng điểm tổng hợp ".$hk[$hocki]." - Lớp: ".$lop." - GVCN: $tengvcn");
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
			$tbm[$data['mahs']]['DHTD']=$dhtd[$hocki==3?$data['dhtd']:$data['dhtd']<3?$data['dhtd']:0];
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
	
	//cac lua chon
	$khoadiem=dblookup("khoadiem","qlt_dslop","tenlop='".$lop."' and donvi=$madonvi");
	if ($khoadiem<$hocki) {
		echo "<br>Lựa chọn các chức năng:";
		echo "<br>* <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&act=tinhtbcm&hocki=".$hocki)."'>Tính điểm TB các môn và xếp loại học lực ".$hk[$hocki]."</a>";
		echo "<br>* <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&act=nhaphk&hocki=".$hocki)."'>Nhập hạnh kiểm ".$hk[$hocki]."</a>";
		if ($hocki==3) {
			echo "<br>* <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&act=xetdhtd&hocki=".$hocki)."'>Xét DHTĐ và lên lớp...</a>";
		} else {
			echo "<br>* <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&act=xetdhtd&hocki=".$hocki)."'>Xét DHTĐ</a>";
			echo "<br>* <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&hocki=".($hocki+1))."'>Bảng điểm tổng hợp ".$hk[$hocki+1]."</a>";
		}
		if ($hocki>1) echo "<br>* <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&hocki=".($hocki-1))."'>Bảng điểm tổng hợp ".$hk[$hocki-1]."</a>";
		echo "<br><br> <a href='".mahoaurl(FUSION_SELF."?lop=".$lop."&act=khoadiem&hocki=".$hocki)."'>Khoá điểm ".$hk[$hocki]."</a>";
	}
	echo "<br><br><a href='".mahoaurl("diemth_in.php?lop=$lop&hocki=$hocki")."'>In bảng điểm tổng hợp</a>";
	
	closetable();
}
require_once THEMES."templates/footer.php";
?>
