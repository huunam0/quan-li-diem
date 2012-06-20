<?php
require_once "../maincore.php";
require_once THEMES."templates/header.php";
echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
//echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $header_reg; 
$hluc=array ("Chưa XL","Giỏi","Khá","TB","Yếu","Kém");
if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
if (isset($_GET['hocki']) && isnum($_GET['hocki'])) {
	$hocki=$_GET['hocki'];
} else {
	$hocki=dblookup("value","qlt_thamso","name='hocki'");
}
if ($hocki<1 || $hocki>3) {redirect("index.php");}
$hk = array ("Dau nam","Học kì 1","Học kì 2","Cả năm");
if (!isset($_GET['lop'])) {redirect("index.php");}
$lop=strtoupper($_GET['lop']);
if (isset($_GET['act']) && ($_GET['act']=="tinhtbcm")) {
	$result=dbquery("select hocki,mahs,round(sum(tbm*heso)/sum(heso)) as tbcm,min(tbm) as min_tbm,max(tbm*kchl) as max_tbmkc from (qlt_diemth join qlt_phanconggd on qlt_diemth.mon= qlt_phanconggd.mon) join qlt_dshocsinh on qlt_phanconggd.lop=qlt_dshocsinh.lop and qlt_diemth.mahs=qlt_dshocsinh.id where  xeploai=0 and qlt_dshocsinh.lop='".$lop."' group by mahs");
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
	redirect(FUSION_SELF."?lop=".$lop);
} elseif (isset($_GET['act']) && ($_GET['act']=="xetdhtd")) { //xet danh hieu thi dua
	$result=dbquery("update qlt_tongket set dhtd=if(hl=1 and hk=1,1,if(hl<=2 and hk<=2,2,if(hl<=3 and hk<=3,3,if(hl=4 and hk<4,4,if(hl<4 and hk=4,5,6))))) where mahs in (select id from qlt_dshocsinh where lop='".$lop."') and hocki=".$hocki);
	redirect(FUSION_SELF."?lop=".$lop);
} elseif (isset($_GET['act']) && ($_GET['act']=="nhaphk")) { //nhap hanh kiem
	if (isset($_POST['luu'])) { //cap nhat
		$result=dbquery("select stt,id,hoten from qlt_dshocsinh where lop='".$lop."' order by stt");
		if (dbrows($result)) {
			
			
			while ($data=dbarray($result)){
				$hkid='hk'.$data['id'];
				if (isset($_POST[$hkid])) {
					$result2=dbquery("update qlt_tongket set hk=".$_POST[$hkid]." where hocki=".$hocki." and mahs=".$data['id']);
				}
			}
	
		}
		redirect(FUSION_SELF."?lop=".$lop);
	} else {// nhap moi, chinh sua
		$result=dbquery("select stt,id,hoten from qlt_dshocsinh where lop='".$lop."' order by stt");
		if (dbrows($result)) {
			opentable("Nhập hạnh kiểm ".$hk[$hocki]." lớp ".$lop);
			echo "<form method='post' action='".FUSION_SELF."?lop=".$lop."&act=nhaphk&hocki=".$hocki."'><table class='mau center'>";
			echo "<tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td><td>Hoc luc</td><td>Hạnh kiểm</td></tr>";
			$i=1;
			while ($data=dbarray($result)){
				$tongket=dbarray("select hl,hk from qlt_tongket where hocki=".$hocki." and mahs=".$data['id']);
				//$hkcu=dblookup("hk","qlt_tongket","hocki=".$hocki." and mahs=".$data['id']);
				$data2=dbarray($tongket);
				$hkcu=$data2['hk'];
				echo "<tr class='tbl$i'><td align=center>".$data['stt']."</td><td>".$data['hoten']."</td><td>".$hluc[$data2['hl']?$data2['hl']:0]."</td><td><select name='hk".$data['id']."'><option value='0' ".($hkcu==0?"selected":"")."> </option>";
				//$hkcu=dblookup("hk","qlt_tongket","hocki=".$hocki." and mahs=".$data['id']);
				echo "<option value='1' ".($hkcu==1?"selected":"").">Tốt</option><option value='2' ".($hkcu==2?"selected":"").">Khá</option><option value='3' ".($hkcu==3?"selected":"").">TB</option><option value='4' ".($hkcu==4?"selected":"").">Yếu</option></select></td></tr>";
				$i=4-$i;
			}
			echo "<tr align=center><td></td><td></td><td></td><td><input type='submit' name='luu' value='Lưu'></td></tr>";
			echo "</table></form>";
			closetable();
		}
	}
	//select hocki,mahs,sum(tbm*heso)/sum(heso) from (qlt_diemth join qlt_phanconggd on qlt_diemth.mon= qlt_phanconggd.mon) join qlt_dshocsinh on qlt_phanconggd.lop=qlt_dshocsinh.lop and qlt_diemth.mahs=qlt_dshocsinh.id where  qlt_dshocsinh.lop='11a2' group by mahs
	//redirect(FUSION_SELF."?lop=".$lop);
} else {
	
	$hkiem=array("-","Tốt","Khá","TB","Yếu");
	$dhtd=array("-","HS Giỏi","HS Tiên tiến","Lên lớp thẳng","Thi lại","Rèn luyện HK","Ở lại lớp");
	$result=dbquery("select id, hoten, stt from qlt_dshocsinh where lop='".$lop."' order by stt");
	if ($siso=dbrows($result)) {
		while ($data=dbarray($result)) {
			//$tbm[1][$data['stt']]=$data['id'];
			$tbm[1][$data['stt']]=$data['hoten'];
		}
		
		//diem tbcm
		$somon=dbcount2("qlt_monhoc","");
		
		
		opentable("Bảng điểm tổng hợp ".$hk[$hocki]." - lớp ".$lop);
		echo "<table class='mau center'><tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td>";
		$result=dbquery("select stt,xeploai,tenngan from qlt_monhoc order by stt");
		if (dbrows($result)) {
			while ($data=dbarray($result)) {
				echo "<td width=4%>".$data['tenngan']."</td>";
				$xloai[$data['stt']]=$data['xeploai']==1;
			}
		}
		include("bacxloai.php");
		$result=dbquery("select qlt_dshocsinh.stt as stths,qlt_diemth.*,qlt_monhoc.stt as sttmon from qlt_dshocsinh, qlt_diemth,qlt_monhoc where qlt_diemth.mon=qlt_monhoc.mamon and qlt_dshocsinh.id=qlt_diemth.mahs and hocki=".$hocki." and lop='".$lop."' order by stths,sttmon");
		if (dbrows($result)) {
			while ($data=dbarray($result)) {
				$tbm[$data['sttmon']+1][$data['stths']]=($xloai[$data['sttmon']]?$bacxl[$data['tbm']]:$data['tbm']);
			}
		}
		$result=dbquery("select qlt_dshocsinh.stt,qlt_tongket.* from qlt_dshocsinh join qlt_tongket on qlt_dshocsinh.id=qlt_tongket.mahs where lop='".$lop."' and hocki=".$hocki);
		while ($data=dbarray($result)){
			$tbm[$somon+2][$data['stt']]=($xloai[$data['stt']]?$data['tbcm']:$data['tbcm']);
			$tbm[$somon+3][$data['stt']]=$hluc[$data['hl']];
			$tbm[$somon+4][$data['stt']]=$hkiem[$data['hk']];
			$tbm[$somon+5][$data['stt']]=$dhtd[$data['dhtd']];
		}
		echo "<td>TBCM</td><td>HL</td><td>HK</td><td>Ghi chú</td><tr>";
		$t=1;
		$somon+=5;
		for ($i=1; $i<=$siso; $i++) {
			echo "<tr class='tbl".$t." info0' align=center><td>".$i."</td><td align=left>".$tbm[1][$i]."</td>";
			$t=4-$t;
			for ($j=2; $j<=$somon;$j++) {
				echo "<td >".$tbm[$j][$i]."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		//cac lua chon
		$khoadiem=dblookup("khoadiem","qlt_dslop","tenlop='".$lop."'");
		if ($khoadiem<$hocki) {
			echo "<br>Lựa chọn các chức năng:";
			echo "<br>* <a href='".FUSION_SELF."?lop=".$lop."&act=tinhtbcm&hocki=".$hocki."'>Tính điểm TB các môn và xếp loại học lực ".$hk[$hocki]."</a>";
			echo "<br>* <a href='".FUSION_SELF."?lop=".$lop."&act=nhaphk&hocki=".$hocki."'>Nhập hạnh kiểm ".$hk[$hocki]."</a>";
			if ($hocki==3) {
				echo "<br>* <a href='".FUSION_SELF."?lop=".$lop."&act=xetdhtd&hocki=".$hocki."'>Xét DHTĐ và lên lớp...</a>";
			} else {
				echo "<br>* <a href='".FUSION_SELF."?lop=".$lop."&act=xetdhtd&hocki=".$hocki."'>Xét DHTĐ</a>";
				echo "<br>* <a href='".FUSION_SELF."?lop=".$lop."&hocki=".($hocki+1)."'>Bảng điểm tổng hợp ".$hk[$hocki+1]."</a>";
			}
			if ($hocki>1) echo "<br>* <a href='".FUSION_SELF."?lop=".$lop."&hocki=".($hocki-1)."'>Bảng điểm tổng hợp ".$hk[$hocki-1]."</a>";
			echo "<br><br> <a href='".FUSION_SELF."?lop=".$lop."&act=khoadiem&hocki=".$hocki."'>Khoá điểm ".$hk[$hocki]."</a>";
		}
		echo "";
		closetable();
	}
}
require_once THEMES."templates/footer.php";
?>
