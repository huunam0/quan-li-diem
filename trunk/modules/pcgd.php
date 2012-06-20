<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//include LOCALE.LOCALESET."admin/members.php";
//include LOCALE.LOCALESET."user_fields.php";

if (!checkrights("PC")) { redirect("index.php"); }

//if (!isset($_GET['lop'])) { $_GET['lop'] = ""; }

if (isset($_GET['lop'])) {
	$lop=$_GET['lop'];
	if (isset($_POST['themmon'])) {
		//$result = dbquery("select * from qlt_phanconggd where lop='".$lop."' and mon='".$_POST['mamon']."'");
		if (dbcount('(*)','qlt_phanconggd',"lop='".$lop."' and mon='".$_POST['mamon']."' and donvi=$madonvi")>0) {
			$result=dbquery("update qlt_phanconggd set gvbm=".$_POST['gvbm'].", xeploai=".(isset($_POST['xeploai'])?1:0).",kchl=".(isset($_POST['kcxeploai'])?1:0).", heso=".(isset($_POST['xeploai'])?0:$_POST['heso']).", sotiet=".$_POST['sotiet']." where lop='".$lop."' and mon='".$_POST['mamon']."' and donvi=$madonvi");
		} else {
			
			$result=dbquery("insert into qlt_phanconggd (lop,mon,gvbm,xeploai, heso, sotiet,kchl,donvi) value ('".$lop."','".$_POST['mamon']."',".$_POST['gvbm'].",".(isset($_POST['xeploai'])?1:0).",".(isset($_POST['xeploai'])?0:$_POST['heso']).",".$_POST['sotiet'].",".(isset($_POST['kcxeploai'])?1:0).",$madonvi)");
		}
		//echo "insert into qlt_phanconggd (lop,mon,gvbm,xeploai, heso, sotiet) value ('".$lop."','".$_POST['mamon']."',".$_POST['gvbm'].",".(isset($_POST['xeploai'])?1:0).",".$_POST['heso'].",".$_POST['sotiet'].")";
		redirect(FUSION_SELF.$aidlink."&lop=".$lop);
	} elseif (isset($_GET['act'])&&isset($_GET['id'])) {
	if ($_GET['act']=="delete") {
		$result=dbquery("delete from qlt_phanconggd where donvi=$madonvi and id=".$_GET['id']);
		echo "Da xoa xong";
	}
	redirect(FUSION_SELF.$aidlink."&lop=".$lop);
	} else {
		
		$result=dbquery("select qlt_phanconggd.*, qlt_monhoc.tenmon from qlt_phanconggd, qlt_monhoc where qlt_phanconggd.mon=qlt_monhoc.mamon and qlt_phanconggd.donvi=$madonvi and lop='".$lop."' order by qlt_monhoc.stt");
		
		opentable("Phân công giáo dục của lớp ".$lop);
		if (dbrows($result)) {
			echo "<table class='tbl-border center'><tr bgcolor=#bbbbbb align=center><td align=center>X</td><td align=center>Môn học</td><td align=center>Xếp loại</td><td align=center>Số tiết /tuần</td><td align=center>GVBM</td><td>Khong che hoc luc</td><td align=center>Hệ số tính TBCM</td></tr>";
			while ($data=dbarray($result)) {
				//$thamso="&lop=".$lop."&mon=".$data['mon']."&act=delete";
				$thamso="&act=delete&id=".$data['id']."&lop=".$lop;
				echo "<tr class='info0'><td align=center><a href='".FUSION_SELF.$aidlink.$thamso."'>[Xoá]</a></td><td>".$data[tenmon]."</td><td align=center>".($data[xeploai]?"XL":"-")."</td><td align=center>".$data['sotiet']."</td>";
				echo "<td>".dblookup('user_name','qlt_users','user_id='.$data['gvbm'])."</td>";
				echo "<td align=center>".($data['kchl']?"Co":"-")."</td>";
				echo "<td align=center>".($data['heso'])."</td>";
				echo "</tr>";
			} 
			echo "</table>";
		} else {
			echo "Chưa phân công.";
		}
		closetable();
		opentable("Phân công thên môn mới cho lớp ".$lop);
		$result = dbquery("select * from qlt_monhoc where (select count(*) from qlt_phanconggd where mon=mamon and donvi=$madonvi and lop='".$lop."')=0");
		if (dbrows($result)) {
			echo "<form name='addform' method='post' action='".FUSION_SELF.$aidlink."&lop=".$lop."'>\n";
			echo "<table class='tbl-border center'><tr><td align=right>Môn học:</td><td><select name='mamon'>";
			while ($data=dbarray($result)) {
				echo "<option value='".$data['mamon']."'>".$data['tenmon']."</option>";
			}
			echo "</select></td>";
			echo "<tr><tr><td align=right>Giáo viên bộ môn:</td><td><select name='gvbm'>";
			$result2=dbquery("select * from qlt_users where donvi=$madonvi");
			while ($data2=dbarray($result2)) {
				echo "<option value='".$data2[user_id]."'>".$data2[user_name]."</option>";
			}
			echo "</option></td></tr>";
			echo "<tr><td></td><td><input type='checkbox' name='xeploai' />Môn xếp loại</td></tr>";
			echo "<tr><td></td><td><input type='checkbox' name='kcxeploai' />Môn khong che xep loai</td></tr>";
			echo "<tr><td>Hệ số tính TBCM:</td><td><input type='text' name='heso' value='1'></td></tr>";
			echo "<tr><td align=right>Số tiết / tuần: </td><td><input type='text' name='sotiet' maxlength='20' class='textbox' style='width:60px;' value='2'/> tiết/tuần</td></tr>";
			echo "<tr><td><i>Thêm môn mới → </td><td align=center><input type='submit' name='themmon' value='Thêm' class='button' /></td>\n";
			echo "</table></form>";
		} else {
			echo "Đã phân công hết môn.";
		}
		closetable();
		opentable("Chọn lớp khác");
		$result=dbquery("select tenlop,gvcn from qlt_dslop where donvi=$madonvi order by stt");
		if (dbrows($result)) {
			echo "<table class='mau center'><tr>";
			$i=1;
			while ($data=dbarray($result)) {
				echo "<td><a href='".$FUSION_SELF.$aidlink."&lop=".$data['tenlop']."'>".$data['tenlop']." - \t".dblookup('user_name','qlt_users','user_id='.$data['gvcn'])."</a></td>";
				if ($i==5) {
					echo "</tr><tr>";
					$i=1;
				} else $i++;
			}
			while ($i<=5) {
				echo "<td></td>";
				$i++;
			}
			echo "</tr></table>";
		} else {
			echo "Chưa có lớp nào<br>";
			echo "<a href='dslop.php'>Nhập danh sách lớp</a>";
		}
		closetable();
		
	}
}  elseif (isset($_GET['step']) && ($_GET['step']=='2')) {//phan cong theo gv
	$gvid=$_POST['gv'];
	$gvhoten=dblookup("user_name","qlt_users","user_id=".$gvid);
	//echo $gvhoten."-";
	$mon=$_POST['mon'];
	if (isset($_POST['them'])) {
		$heso=$_POST['heso'];
		$xeploai=($_POST['xeploai']?1:0);
		$kcxeploai=($_POST['kcxeploai']?1:0);
		$sotiet=$_POST['sotiet'];
		if($xeploai==1) $heso=0;
		$result=dbquery("select * from qlt_dslop where donvi=$madonvi order by stt");
		if (dbrows($result)){
			while($data=dbarray($result)){
				if (isset($_POST['lop'.$data['tenlop']])) {
					if (dbcount2("qlt_phanconggd","lop='".$data['tenlop']."' and mon='$mon' and donvi=$madonvi ")>0) {
						$result2=dbquery("update qlt_phanconggd set gvbm=".$gvid.", xeploai=".$xeploai.", kchl=$kcxeploai, heso=".$heso.", sotiet=".$sotiet." where lop='".$data['tenlop']."' and mon='".$mon."'");//chua hoan chinh
						//echo "update qlt_phanconggd set gvbm=".$gvid.", xeploai=".$xeploai.", heso=".$heso.", sotiet=".$sotiet." where lop='".$data['tenlop']."' and mon='".$mon."'";
					} else {
						$result2=dbquery("insert into qlt_phanconggd (lop,mon,gvbm,xeploai, heso, sotiet,kchl,donvi) value ('".$data['tenlop']."','".$mon."',".$gv.",".$xeploai.",".$heso.",".$sotiet.",$kcxeploai,$madonvi)");
						//echo "insert into qlt_phanconggd (lop,mon,gvbm,xeploai, heso, sotiet) value ('".$data['tenlop']."','".$_mon."',".$gv.",".$xeploai.",".$heso.",".$sotiet.")";
					}
				}
			}
		}
		redirect(FUSION_SELF.$aidlink);
	} else {
		if ($gvhoten=='') {
			echo "Không có GV này";
		} else {
			opentable("Phân công giáo dục cho giáo viên ".$gvhoten." - giảng dạy môn ".$mon);
			$xeploai=dblookup("xeploai","qlt_monhoc","mamon='".$mon."'")==1;//mon xep loai hay khong?
			$kcxeploai=dblookup("kchl","qlt_monhoc","mamon='".$mon."'")==1;//mon khong che hoc luc?
			$result=dbquery("select * from qlt_dslop where donvi=$madonvi order by stt");
			if (dbrows($result)){
				echo "<form method='POST' action='".$FUSION_SELF.$aidlink."&step=2'>";
				echo "<input type='checkbox' name='xeploai' ".($xeploai?"checked":"").">Môn xếp loại<br>";
				echo "<input type='checkbox' name='kcxeploai' ".($kcxeploai?"checked":"").">Môn khong che hoc luc<br><br>";
				echo "Hệ số tính TBCM:<input type='text' name='heso' value='1'><br><br>\n";
				echo "Số tiết:<input type='text' name='sotiet' value='2'/> tiết/tuần<br><br>\n";
				echo "<table border=0><tr>";
				$i=1;
				while ($data=dbarray($result)){
					echo "<td width><input type='checkbox' name='lop".$data['tenlop']."' ";
					if (dbcount2("qlt_phanconggd","lop='".$data['tenlop']."' and mon='".$mon."' and gvbm=".$gvid)>0) echo "checked";
					echo ">".$data['tenlop']."<td>";
					if ($i==10) {
						echo "</tr>\n<tr>";
						$i=1;
					} else $i++;
				}
			}
			echo "</tr><tr><td colspan=10 align=center><input type='submit' name='them' value='Lưu'></td></tr></table>";
			echo "<input type='hidden' name='gv' value='".$gvid."'><input type='hidden' name='mon' value='".$mon."'></form>";
			closetable();
		}
	}
}  elseif (isset($_GET['viewbygv'])) {//Xem phan cong GV
	opentable("Bảng phân công giáo dục theo giao vien");
	//echo ;
	//$result=dbquery("select pcgd.*, qlt_users.user_name, from qlt_phanconggd pcgd, qlt_users where pcgd.gvbm=qlt_users.user_id and pcgd.donvi=qlt_users.donvi and pcgd.donvi=$madonvi order by gvbm, mon,lop");
	$result =dbquery("select * from qlt_phanconggd where donvi=$madonvi order by gvbm, mon,lop");
	echo "<table class='mau center'><tr class='tbl3' align=center><td >Giao vien</td><td>Phu trach cac mon/lop";
	$gvhien=0;
	$monhien="";
	$rightcolumn="";
	$sotiet=0;
	if (dbrows($result)) {
		$i=1;
		while ($data=dbarray($result)) {
			if ($data['gvbm']!=$gvhien) {
				$gvhien=$data['gvbm'];
				echo $rightcolumn."</td><td>$sotiet</td></tr><tr class='info0 tbl$i'><td>".getgv($gvhien)." ($gvhien)</td>";
				$i=4-$i;
				$rightcolumn="<td>";
				$monhien="";
				$sotiet=0;
			} 
			if ($data['mon']!=$monhien) {
				$monhien=$data['mon'];
				$rightcolumn.=(strlen($rightcolumn)>4?";<br>":"").$monhien.": ";
			} else {
				$rightcolumn.= ", ";
			}
			
			$rightcolumn.="<b>".$data['lop']."</b><i>(".$data['sotiet'].")</i>";
			$sotiet+=$data['sotiet'];
		}
	}
	echo $rightcolumn."</td><td>$sotiet</td></tr></table>";
	echo "<a href='".FUSION_SELF."'>Xem bang phan cong giao duc theo mon/lop</a>";
	
	closetable();
} else {
	opentable("Bảng phân công giáo dục");
	//echo "select qlt_phanconggd.*,qlt_users.short_name,qlt_dslop.stt as sttlop,qlt_monhoc.stt as sttmon from qlt_phanconggd as pcgd,qlt_users,qlt_dslop,qlt_monhoc where pcgd.gvbm=qlt_users.user_id and pcgd.lop=qlt_dslop.tenlop and pcgd.mon=qlt_monhoc.mamon and pcgd.donvi=qlt_users.donvi and pcgd.donvi=qlt_dslop.donvi and pcgd.donvi=qlt_monhoc.donvi and pcgd.donvi=$madonvi";
	//$result=dbquery("select pcgd.*,qlt_users.short_name,qlt_dslop.stt as sttlop,qlt_monhoc.stt as sttmon from qlt_phanconggd pcgd,qlt_users,qlt_dslop,qlt_monhoc 
	//where pcgd.gvbm=qlt_users.user_id and pcgd.lop=qlt_dslop.tenlop and pcgd.mon=qlt_monhoc.mamon and pcgd.donvi=qlt_users.donvi and pcgd.donvi=qlt_dslop.donvi and pcgd.donvi=$madonvi");
	$result=dbquery("select qlt_phanconggd.*, qlt_users.user_name, qlt_users.short_name  from qlt_phanconggd, qlt_users where qlt_phanconggd.gvbm=qlt_users.user_id and qlt_phanconggd.donvi=qlt_users.donvi and qlt_phanconggd.donvi=$madonvi order by lop, mon");
	if (dbrows($result)) {
		while ($data=dbarray($result)){
			$pcgd[$data['lop']][$data['mon']]=($data['short_name']?$data['short_name']:getlastname($data['user_name']))." (".$data['sotiet'].")";
			$pcgd[$data['lop']]["SOTIET"]+=$data['sotiet'];
		}
		//echo $somon;
		echo "<table class='mau center'><tr class='tbl3' align=center><td>LỚP/MÔN</td>";
		$result2=dbquery("select * from qlt_monhoc order by stt");
		$somon=dbrows($result2);
		$i=1;
		$monhoc[0]="0";
		while ($data2=dbarray($result2)) {
			echo "<td>".$data2['tenmon']."</td>";
			$monhoc[$i++]=$data2['mamon'];
		}
		$monhoc[$i]="SOTIET";
		echo "<td>So tiet</td></tr>";
		$result2=dbquery("select * from qlt_dslop where donvi=$madonvi order by stt");
		$x=1;
		while ($data2=dbarray($result2)) {
			echo "<tr class='tbl".($x % 3+1)."'><td align=center>";
			echo $data2['tenlop']."</td>";
			//$sotiet=0;
			for ($i=1;$i<=$somon+1;$i++){
				echo "<td>".$pcgd[$data2['tenlop']][$monhoc[$i]]."</td>";
				//$sotiet+=$data2['sotiet'];
			}
			//echo "<td>$sotiet</td>";
			echo "</tr>";
			$x++;
			}
		echo "</table>";
		echo "<div align=right><a href='".FUSION_SELF."?viewbygv=1'>Xem bang phan cong giao duc theo giao vien</a></div>";
	} else {
		echo "<i>Chua phan cong</i>";
	}
	closetable();
	opentable("Chọn phân công giáo dục");
	$result=dbquery("select tenlop,gvcn from qlt_dslop where donvi=$madonvi order by stt");
	if (dbrows($result)) {
		echo "Phân công theo lớp.<br>Chọn lớp: <table class='mau center'><tr>";
		$i=1;
		while ($data=dbarray($result)) {
			echo "<td><a href='".$FUSION_SELF.$aidlink."&lop=".$data['tenlop']."'>".$data['tenlop']."</a> - \t".dblookup('user_name','qlt_users','user_id='.$data['gvcn'])."</td>";
			if ($i==5) {
				$i=1;
				echo "</tr><tr>";
			} else $i++;
		}
		while ($i<=5) {
			echo "<td></td>";
			$i++;
		}
		echo "</tr></table>";
	} else {
		echo "Chưa có lớp nào<br>";
		echo "<a href='dslop.php'>Nhập danh sách lớp</a>";
	}
	//chon gv de phan cong
	echo "<hr><form method='POST' action='".$FUSION_SELF.$aidlink."&step=2'>Phân công theo giáo viên:";
	$result=dbquery("select * from qlt_users where  donvi=$madonvi");
	if (dbrows($result)) {
		echo "<br>Chọn giáo viên để phân công: <select name='gv'>";
		while ($data=dbarray($result)) {
			echo "<option value='".$data['user_id']."'>".$data['user_name']."</option>";
		}
		echo "</select>";
	}
	//chon mon hoc
	$result=dbquery("select * from qlt_monhoc order by stt");
	if (dbrows($result)) {
		echo " Chọn môn cho gv này: <select name='mon'>";
		while ($data=dbarray($result)) {
			echo "<option value='".$data['mamon']."'>".$data['tenmon']."</option>";
		}
		echo "</select>";
		echo " <input type='submit' name='chonlop' value='Chọn các lớp'></form>";
	}
	closetable();
}
require_once THEMES."templates/footer.php";
?>
