<?php
require_once "../maincore.php";
require_once THEMES."templates/header.php";
echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
//echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $header_reg; 

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
//if (!checkgroup(11) && !checkrights("ND")) {redirect("../index.php")} //neu khong phai giao vien va khong co quyen nhap diem thi...
if (!iMEMBER) {redirect("../index.php");}
$hocki=dblookup("value","qlt_thamso","name='hocki'");
if (isset($_GET['lop']) && isset($_GET['mon'])) {
	//bang diem chi tiet+ nhap/sua diem
	$lop=strtoupper($_GET['lop']);
	$mon=strtoupper($_GET['mon']);
	$gvbm=dblookup("gvbm","qlt_phanconggd","lop='".$lop."' and mon='".$mon."'");
	$xeploai=dblookup("xeploai","qlt_phanconggd","lop='".$lop."' and mon='".$mon."'")==1;//mon xep loai hay khong?
	$socotdiem=0;
	for ($i=1;$i<4;$i++) {
		$result2=dbquery("select max(qlt_diemct.stt) from qlt_diemct,qlt_dshocsinh where qlt_diemct.mahs=qlt_dshocsinh.id and lop='".$lop."' and mon='".$mon."' and hocki=".$hocki." and heso=".$i);
		$coths[$i]=dbresult($result2);
		if (!$coths[$i]) {$coths[$i]=0;}
		//echo $i."-".$coths[$i]." ";
		$socotdiem+=$coths[$i];
	}
	if (isset($_GET['act']) && ($_GET['act']=="add") && (isset($_GET['heso']))) { //them cot diem
		$heso=$_GET['heso'];
		if (isset($_POST['luu']) && ($_POST)) {
			$result=dbquery("select id, hoten, stt from qlt_dshocsinh where lop='".$lop."' order by stt");
			if (dbrows($result)) {
				$newstt=$coths[$heso]+1;
				while ($data=dbarray($result)) { //duyet ds hoc sinh
					$diemhs='diem'.$data['id'];
					if (isset($_POST[$diemhs]) && isnum($_POST[$diemhs]) and ($_POST[$diemhs]<=100)) {
						
						//echo "insert into qlt_diemct (hocki,mahs,mon,heso,stt,diem) value (".$hocki.",".$data['id'].",'".$mon."',".$_GET['heso'].",".$newstt.",".$_POST[$diemhs].")";
						$result2=dbquery("insert into qlt_diemct (hocki,mahs,mon,heso,stt,diem) value (".$hocki.",".$data['id'].",'".$mon."',".$heso.",".$newstt.",".$_POST[$diemhs].")");
						
					}
				}
			}
			redirect(FUSION_SELF."?lop=".$lop."&mon=".$mon);
		} else {
			if ($heso<1 || $heso>3) {
				echo "Hệ số điểm không phù hợp.";
				redirect(FUSION_SELF."?lop=".$lop."&mon=".$mon);
			}
			opentable($donvi." - Nhập thêm cột điểm hệ số ".$heso);
			//lay danh sach hoc sinh
			$result=dbquery("select id, hoten, stt from qlt_dshocsinh where lop='".$lop."' order by stt");
			if (dbrows($result)) {
				echo "<form method='post' action='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=add&heso=".$heso."'>";
				echo "<table class='mau center'><tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td><td>Điểm (Cột thứ ".($coths[$heso]+1).")</td></tr>";
				$i=1;
				while ($data=dbarray($result)) {
					echo "<tr class='tbl".$i."'><td>".$data['stt']."</td><td>".$data['hoten']."</td><td>";
					if ($xeploai) {
						echo "<select name='diem".$data['id']."'>";
						echo "<option> </option><option value='80'>Giỏi</option><option value='65'>Khá</option><option value='50'>TB</option><option value='35'>Yếu</option><option value='20'>Kém</option></select>";
					} else {
						echo "<input name='diem".$data['id']."'>";
					}
					echo "</td></tr>";
					$i=4-$i;
				}
				echo "<tr><td></td><td></td><td><input type='submit' name='luu' value='Lưu'></td></tr>";
				echo "</table></form>";
			} else {
				echo "Không có học sinh nào.";
			}
			closetable();
		}
	} elseif (isset($_GET['act']) && ($_GET['act']=="del") && (isset($_GET['heso'])) && isset($_GET['stt'])) {//xoa cot diem
		$result=dbquery("delete from qlt_diemct where mon='".$mon."' and hocki=".$hocki." and heso=".$_GET['heso']." and stt=".$_GET['stt']." and mahs in (select id from qlt_dshocsinh where lop='".$lop."') ");
		redirect(FUSION_SELF."?lop=".$lop."&mon=".$mon);
	} elseif (isset($_GET['act']) && ($_GET['act']=="edit") && (isset($_GET['heso'])) && isset($_GET['stt'])) {//sua cot diem
		if (isset($_POST['luu']) && $_POST) {
			$result=dbquery("select id,hoten,stt from qlt_dshocsinh where lop='".$lop."' order by stt"); //lay danh sach lop
			if (dbrows($result)) { //neu co hoc sinh
				while ($data=dbarray($result)) {
					$diemhs='diem'.$data['id'];
					$diemcu='diemcu'.$data['id'];
					if (isset($_POST[$diemhs]) && ($_POST[$diemhs]!="") && ($_POST[$diemhs])){
						if (isset($_POST[$diemcu])) {
							$sql="update qlt_diemct set diem=".$_POST[$diemhs]." where  mon='".$mon."' and hocki=".$hocki." and heso=".$_GET['heso']." and stt=".$_GET['stt']." and mahs=".$data['id'];
						} else {
							$sql="insert into qlt_diemct (hocki,mahs,mon,heso,stt,diem) value (".$hocki.",".$data['id'].",'".$mon."',".$_GET['heso'].",".$_GET['stt'].",".$_POST[$diemhs].")";
						}	
					} else {
						if (isset($_POST[$diemcu])) {
							$sql="delete from qlt_diemct where  mon='".$mon."' and hocki=".$hocki." and heso=".$_GET['heso']." and stt=".$_GET['stt']." and mahs=".$data['id'];
						} else $sql="";
					}
					if ($sql) {
						$result2=dbquery($sql);
						//echo $sql."<br>";
					}
				}
				
			}
			redirect(FUSION_SELF."?lop=".$lop."&mon=".$mon);
		} else {
			opentable($donvi." - Sửa cột điểm ".$_GET['stt']." he so ".$_GET['heso']." cua mon $mon cua lop $lop.");
			$result=dbquery("select id,hoten,stt from qlt_dshocsinh where lop='".$lop."' order by stt"); //lay danh sach lop
			if (dbrows($result)) { //neu co hoc sinh
				echo "<form method='post' action='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=edit&heso=".$_GET['heso']."&stt=".$_GET['stt']."'>";
				echo "<table class='mau center' ><tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td><td>Điểm</td></tr>";
				$i=1;
				while ($data=dbarray($result)) {
					echo "<tr class='tbl".$i."'><td align=center>".$data['stt']."</td><td>".$data['hoten']."</td><td>";
					$result2=dbquery("select diem from qlt_diemct where mon='".$mon."' and mahs=".$data['id']." and hocki=".$hocki." and heso=".$_GET['heso']." and stt=".$_GET['stt']);
					if ($xeploai) {
						if (dbrows($result2)) {
							$data2=dbarray($result2);
							echo "<select name='diem".$data['id']."'><option></option><option value='80' ".($data2['diem']==80?"selected":"").">Giỏi</option><option value='65' ".($data2['diem']==65?"selected":"").">Khá</option><option value='50' ".($data2['diem']==50?"selected":"").">TB</option>";
							echo "<option value='35' ".($data2['diem']==35?"selected":"").">Yếu</option><option value='20' ".($data2['diem']==20?"selected":"").">Kém</option></select><input type='hidden' name='diemcu".$data['id']."' value='1'>";
							echo "<input type='hidden' name='diemcu".$data['id']."' value='1'>";
						} else {
							echo "<select name='diem".$data['id']."' ><option></option><option value='80'>Giỏi</option><option value='65'>Khá</option><option value='50'>TB</option><option value='35'>Yếu</option><option value='20'>Kém</option></select><input type='hidden' name='diemcu".$data['id']."' value='0'>";
						}
					} else {
						if (dbrows($result2)) {
							$data2=dbarray($result2);
							echo "<input name='diem".$data['id']."' value='".$data2['diem']."'>";
							echo "<input type='hidden' name='diemcu".$data['id']."' value='1'>";
							
						} else {
							echo "<input name='diem".$data['id']."'>";
						}						
					}
					echo "</td></tr>";
					$i=4-$i;
				}
				echo "<tr align=center><td></td><td></td><td><input type='submit' name='luu' value='Lưu'></td></tr></table></form>";
			}
			closetable();
		}
		//$result=dbquery("delete from qlt_diemct where mon='".$mon."' and hocki=".$hocki." and heso=".$_GET['heso']." and stt=".$_GET['stt']." and mahs in (select id from qlt_dshocsinh where lop='".$lop."') ");
		//redirect(FUSION_SELF."?lop=".$lop."&mon=".$mon);
	} elseif (isset($_GET['act']) && ($_GET['act']=="tbm")) {//tinh TBM
		$result=dbquery("delete from qlt_diemth where mon='".$mon."' and hocki=".$hocki." and  mahs in (select id from qlt_dshocsinh where lop='".$lop."')");//xoa diem tbm cu
		if ($xeploai) {
			$result=dbquery("insert into qlt_diemth (hocki,mahs,mon,tbm) select hocki,mahs,mon,if(sum(if(diem=80,1,0))/count(diem)>=2/3 and min(diem)>=65,80,if(sum(if(diem>=65,1,0))/count(diem)>=2/3 and min(diem)>=50,65,if(sum(if(diem>=50,1,0))/count(diem)>=2/3 and min(diem)>=35,50,if(sum(if(diem>=35,1,0))/count(diem)>=2/3 and min(diem)>=20,35,20)))) from qlt_diemct where mon='".$mon."' and hocki=".$hocki." and  mahs in (select id from qlt_dshocsinh where lop='".$lop."') group by hocki,mahs,mon");//tinh diem TBM moi
		} else {
			$result=dbquery("insert into qlt_diemth (hocki,mahs,mon,tbm) select hocki,mahs,mon,round(sum(diem*heso)/sum(heso)) from qlt_diemct where mon='".$mon."' and hocki=".$hocki." and  mahs in (select id from qlt_dshocsinh where lop='".$lop."') group by hocki,mahs,mon");//tinh diem TBM moi
		}
		$result=dbquery("update qlt_phanconggd set ngaytinhtb=".time()." where lop='".$lop."' and mon='".$mon."'");//cap nhat ngay tinh diem
		redirect(FUSION_SELF."?lop=".$lop."&mon=".$mon);
		//echo "<br>delete from qlt_diemth where mon='".$mon."' and hocki=".$hocki." and  mahs in (select id from qlt_dshocsinh where lop='".$lop."')<br>";
		//echo "insert into qlt_diemth (hocki,mahs,mon,tbm) select hocki,mahs,mon,round(sum(diem*heso)/sum(heso)) from qlt_diemct where mon='".$mon."' and hocki=".$hocki." and  mahs in (select id from qlt_dshocsinh where lop='".$lop."') group by hocki,mahs,mon<br>";
		//echo "update qlt_phanconggd set ngaytinhtb=".time()." where lop='".$lop."' and mon='".$mon."'<br>";
	} else {
		include("bacxloai.php");
		opentable($donvi." - Bảng điểm chi tiết học kì ".$hocki." Lớp: ".$lop."-".$mon." GVBM: ".dblookup("user_name","qlt_users","user_id=".$gvbm));
		$result=dbquery("select id,hoten,stt from qlt_dshocsinh where lop='".$lop."' order by stt"); //lay danh sach lop
		if (dbrows($result)) { //neu co hoc sinh
			//if($coths[3]) $coths[3]-=$coths[2];
			//if($coths[2]) $coths[2]-=$coths[1];
			
			echo "<table class='mau center' ><tr class='tbl3' align=center><td>STT</td><td>Họ và tên</td>";
			if ($xeploai) {
				echo "<td colspan=".($socotdiem>0?$socotdiem:1).">XL bài kiểm tra <a href='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=add&heso=1' title='Thêm cột điểm xếp loại'>[+]</a></td><td><b>XLHK</b></td></tr>";
				//if ($socotdiem==0) $socotdiem++;
				
			} else {
				for ($i=1;$i<=3;$i++) {
					echo "<td colspan=".$coths[$i].">Hệ số ".$i." (".$coths[$i].") <a href='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=add&heso=".$i."' title='Thêm cột điểm hệ số ".$i."'>[+]</a></td>";
				}
				echo "<td><b>TBM</b></td></tr>";
			}
			$t=1;
			while ($data=dbarray($result)) {
				echo "<tr class='info0 tbl".$t."' align=center><td>".$data['stt']."</td><td align=left>".$data['hoten']."</td>";
				$t=4-$t;
				$result2=dbquery("select * from qlt_diemct where mon='".$mon."' and mahs=".$data['id']." and hocki=".$hocki." order by heso,stt");
				
				if ($xeploai) {//mon hoc xep lloai
					if ($socotdiem==0) $socotdiem++;
					if (dbrows($result2)) {
						//for ($h=1;$h<4;$h++) {
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
				echo "<td><b>".($diemtb?($xeploai?$bacxl[$diemtb]:$diemtb):"...")."</b></td></tr>";
			}
			echo "<tr lass='info0' align=center><td></td><td></td>";
			for ($hs=1; $hs<4; $hs++) {
				for ($i=1;$i<=$coths[$hs];$i++){
					echo "<td><a href='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=del&heso=".$hs."&stt=".$i."'><img alt='[xoá]' title='xoá cột điểm' src='".THEME."images/delete.gif'></a>";
					echo "<a href='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=edit&heso=".$hs."&stt=".$i."'><img alt='[sửa]' title='sửa điểm' src='".THEME."images/edit2.gif'></a></td>";
				}
			}
			echo "<td><a href='".FUSION_SELF."?lop=".$lop."&mon=".$mon."&act=tbm'>Tính TBM</a></td></tr>";
			echo "</table>";
			$ngaytinhtb=dblookup("ngaytinhtb","qlt_phanconggd","lop='".$lop."' and mon='".$mon."'");
			if($ngaytinhtb) echo "<div align=right>Điểm TBM cập nhật lúc ".showdate("forumdate", $ngaytinhtb)."</div>";
		} else { //chua co hoc sinh
			echo "Chưa có học sinh nào!";
		} 
		closetable();
	}
} else { //liet ke danh sach lop/mon
	if (checkrights("ND")) {
		$result=dbquery("select qlt_phanconggd.*,qlt_monhoc.tenmon from qlt_phanconggd, qlt_monhoc where qlt_phanconggd.mon=qlt_monhoc.mamon order by lop");
	} else {
		$result=dbquery("select qlt_phanconggd.*,qlt_monhoc.tenmon from qlt_phanconggd, qlt_monhoc where qlt_phanconggd.mon=qlt_monhoc.mamon and gvbm=".$userdata['user_id']." order by lop");
	}
	if (dbrows($result)) {
		opentable($donvi." - Chọn lớp/môn để nhập điểm:");
		echo "<table width=100% class='tbl-border'><tr>";
		$i=1;
		while ($data=dbarray($result)) {
			echo "<td align=center width=10%><a href='".FUSION_SELF."?lop=".$data['lop']."&mon=".$data['mon']."'>".$data['lop']."-".$data['tenmon']."</a></td>";
			if ($i % 10==0) {echo "</tr><tr>";}
			$i++;
		}
		echo "</tr></table>";
		closetable();
	}
}

require_once THEMES."templates/footer.php";
?>
