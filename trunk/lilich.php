<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//echo '<link rel="alternate" type="application/rss+xml" title="Thpt Nguyen Du Forum - RSS" href="../view_rss.php" />';
echo "<script type='text/javascript' src='".INCLUDES."combobox.js'></script>\n";
//include LOCALE.LOCALESET."forum/main.php";
//echo $heade';lr_reg; 
if (!iMEMBER) {redirect("index.php");}

$llop=dblookup("tenlop","qlt_dslop","gvcn=".$userdata['user_id']);
if ((!iMOD) && (!$llop)) {redirect("index.php");}

$hpanel=false;
if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
//$lopcn=(isset($_GET['lop'])?$_GET['lop']:"");

if (isset($_GET['lop'])) {
	$lopcn=$_GET['lop'];
	if (!((iMOD && checkrights("DS"))||($lopcn=$llop))) {echo "Khu vực riêng.";}
	if (isset($_GET['act'])) {
		if ($_GET['act']=="add") { //thêm học sinh
			if (isset($_POST['themhs'])) {
				//echo "Cập nhật dữ liệu";
				if (strlen(trim($_POST['hoten']))<4) { //ten qua ngan
					echo "Tên không đúng";
				} else {
					//kiem tra trung hoc sinh
					if (dbcount2("qlt_dshocsinh","hoten='".$_POST['hoten']."' and ngaysinh='".$_POST[ngaysinh]."'")==0) {
						$xa=($_POST['dcxa']?$_POST['dcxa']:$_POST['dsxa']);
						//echo $xa;
						
						if (dbcount("(*)","qlt_dsxa","tenxa='".$xa."'")==0) { //neu chua co xa
							$result=dbquery("insert into qlt_dsxa (tenxa) value ('".$xa."')");
						}
						
						
						if ($_POST['dcthon']) {
							$thon=$_POST['dcthon'];
							if (dbcount("(*)","qlt_dsthon","tenthon='".$thon."'")==0) { //neu chua co thon nay
								$xaid=dblookup("id","qlt_dsxa","tenxa='".$xa."'");
								$result=dbquery("insert into qlt_dsthon (xa,tenthon) value (".$xaid.",'".$thon."')");
								echo "ID:".mysql_insert_id();
							}
						}
						//if (isnum($_POST['ngay']) && isnum($_POST['thang']) && isnum($_POST['nam'])) {
						
						$stt=dbcount2("qlt_dshocsinh","lop='".$lopcn."' and donvi=$madonvi ")+1;
						
						$sql="insert into qlt_dshocsinh (stt,hoten,ngaysinh,gioinu,lop,noisinh,he,dcthon,dcxa,sodt,hotencha,nghecha,hotenme,ngheme,doanvien,conlietsi,conthuongbinh,conchinhsach,ghichu,donvi) value (";
						$sql.=" ".$stt.",'".$_POST['hoten']."','".$_POST['nam']."-".$_POST['thang']."-".$_POST['ngay']."',".($_POST['nu']?1:0).",'".$lopcn."','".$_POST['noisinh']."','".$_POST['he']."','".$thon."','".$xa."','";
						$sql.=$_POST['sodt']."','".$_POST['hotencha']."','".$_POST['nghecha']."','".$_POST['hotenme']."','".$_POST['ngheme']."',".($_POST['doanvien']?1:0).",".($_POST['conlietsi']?1:0).",'".$_POST['conthuongbinh']."','".$_POST['conchinhsach']."','".$_POST['ghichu']."',$madonvi)";
						//echo $sql; 
						$result=dbquery($sql);
						redirect(FUSION_SELF."?lop=".$lopcn);
					}
				}
			} else {
				opentable("Nhập thêm học sinh cho lớp ".$lopcn);
				echo "<form method='post' action='".FUSION_SELF."?lop=".$lopcn."&act=add'>";
				echo "<table border=0>";
				echo "<tr class='tbl1'><td>Họ và tên</td><td> <input type='text' name='hoten'></td</tr>";
				//echo "<tr><td>Hệ</td><td><select name='tmp_dcxa' id='dcxa_tmp' onclick='ListToBox(this,\'dcxatext\')'><option>A</option><option>B</option></select>";
				//echo "Nếu không có trong danh sách trên thì nhập vào đây: <input type='text' name='dcxa' id='dcxatext'>";
				echo "<tr class='tbl3'><td>Hệ</td><td><select name='he'><option value='A'>A</option><option value='B'>B</option></td></tr>";
				echo "<tr><td>Giới tính Nữ</td><td><input type='checkbox' name='nu' checked></td></tr>";
				echo "<tr class='tbl3'><td>Ngày sinh:</td><td>Ngày<select name='ngay'>";
				for ($i=1;$i<=31;$i++) {
					echo "<option value='".$i."'>".$i."</option>";
				}
				echo "</select> Tháng<select name='thang'>";
				for ($i=1; $i<=12; $i++) {
					echo "<option value='".$i."'>".$i."</option>";
				}
				echo "</select> Năm<select name='nam'>";
				$curyear=date('Y');
				for($i=$curyear-15; $i>=$curyear-20; $i--) {
					echo "<option value='".$i."'>".$i."</option>";
				}
				$i=$curyear-14;
				echo "<option value='".$i."'>".$i."</option>";
				echo "</select></td></tr>";
				echo "<tr><td>Nơi sinh (huyện, tỉnh)</td><td><input type='text' name='noisinh' value='Hoài Nhơn, Bình Định'></td></tr>";
				echo "<tr class='tbl3'><td>Địa chỉ - Xã</td><td>";
				echo  <<<cb
<select name="dsxa" id="dsxaid" onchange="DropDownTextToBox(this,'dcxaid');"><option></option>
cb;
				$result=dbquery("select tenxa from qlt_dsxa");
				if (dbrows($result)) {
					while ($data=dbarray($result)) {
						echo "<option>".$data['tenxa']."</option>";
					}
				}
				//$luutru=0;
				echo "</select> Nếu không có trong danh sách thì nhập vào đây (không ghi tắt): <input name='dcxa' type='text' id='dcxaid' value='Hoài Hương'/></td></tr>";
				//echo "<script scr='javascript'></script>";
				//$luutru=valu('dsxaid');
				echo "<tr><td>Địa chỉ - Thôn</td><td>";
				echo  <<<cb
<select name="dsthon" id="dsthonid" onchange="DropDownTextToBox(this,'dcthonid');"><option> </option>
cb;
				$result=dbquery("select tenthon from qlt_dsthon order by xa");
				if (dbrows($result)) {
					while ($data=dbarray($result)) {
						echo "<option>".$data['tenthon']."</option>";
					}
				}
				echo "</select> Nếu không có trong danh sách thì nhập vào đây(không ghi tắt): <input name='dcthon' type='text' id='dcthonid' value='Thiện Đức'/></td></tr>";
				echo "<tr class='tbl3'><td>Số điện thoại</td><td><input type='text' name='sodt'></td></tr>";
				echo "<tr><td>Họ tên cha</td><td><input type='text' name='hotencha'></td></tr>";
				echo "<tr class='tbl3'><td>Nghề nghiệp cha</td><td><input type='text' name='nghecha'></td></tr>";
				echo "<tr><td>Họ tên mẹ</td><td><input type='text' name='hotenme'></td></tr>";
				echo "<tr class='tbl3'><td>Nghề nghiệp mẹ</td><td><input type='text' name='ngheme'></td></tr>";
				echo "<tr><td>Đoàn viên</td><td><input type='checkbox' name='doanvien' checked></td></tr>";
				echo "<tr class='tbl3'><td>Con LS</td><td><input type='checkbox' name='conlietsi' ></td></tr>";
				echo "<tr><td>Con TB (%)</td><td><input type='text' name='conthuongbinh'></td></tr>";
				echo "<tr class='tbl3'><td>Con gia đình chính sách</td><td><input type='text' name='conchinhsach'></td></tr>";
				//echo "<tr><td>Ngày nghỉ học (ra khỏi trường)</td><td><input type='text' name='ngaynghi'></td></tr>";
				echo "<tr><td>Ghi chú khác</td><td><input type='text' name='ghichu'></td></tr>";
				echo "<tr><td></td><td><input type='submit' name='themhs' value='Thêm HS'></td></tr>";
				echo "</table></form>";
				closetable();
			}
		} elseif (($_GET['act']=="del")&&isset($_GET['id'])) { //Xoá học sinh
			$luustt = dblookup("stt","qlt_dshocsinh","id=".$_GET['id']);
			$result=dbquery("delete from qlt_dshocsinh where donvi=$madonvi and id=".$_GET['id']);
			$result=dbquery("update qlt_dshocsinh set stt=stt-1 where lop=$lopcn and donvi=$madonvi and stt>".$luustt);
			redirect(FUSION_SELF."?lop=".$lopcn);
			
		} elseif (($_GET['act']=="edit")&&isset($_GET['id'])) {//sửa học sinh
			//echo "Edit";
			if (isset($_POST['luu'])) {
				//echo "Cập nhật dữ liệu";
				if (strlen(trim($_POST['hoten']))<4) { //ten qua ngan
					echo "Tên không đúng";
				} else {
					//kiem tra trung hoc sinh
					//if (dbcount2("qlt_dshocsinh","hoten='".$_POST['hoten']."' and ngaysinh='".$_POST[ngaysinh]."'")==0) {
						$xa=($_POST['dcxa']?$_POST['dcxa']:$_POST['dsxa']);
						//echo $xa;
						if (dbcount("(*)","qlt_dsxa","tenxa='".$xa."'")==0) { //neu chua co xa
							$result=dbquery("insert into qlt_dsxa (tenxa) value ('".$xa."')");
						}
						$thon=($_POST['dcthon']?$_POST['dcthon']:$_POST['dsthon']);
						//echo $xa;
						if (dbcount("(*)","qlt_dsthon","tenthon='".$thon."'")==0) { //neu chua co thon nay
							$xaid=dblookup("id","qlt_dsxa","tenxa='".$xa."'");
							$result=dbquery("insert into qlt_dsthon (xa,tenthon) value (".$xaid.",'".$thon."')");
							echo "ID:".mysql_insert_id();
						}
						//$stt=dbcount2("qlt_dshocsinh","lop='".$lopcn."'")+1;
						$sql="update qlt_dshocsinh set hoten='".$_POST['hoten']."',ngaysinh='".$_POST['nam']."-".$_POST['thang']."-".$_POST['ngay']."',gioinu=".($_POST['nu']?1:0).",noisinh='".$_POST['noisinh']."',he='".$_POST['he']."',";
						$sql.="dcthon='".$_POST['dcthon']."',dcxa='".$_POST['dcxa']."',sodt='".$_POST['sodt']."',hotencha='".$_POST['hotencha']."',nghecha='".$_POST['nghecha']."',hotenme='".$_POST['hotenme']."',ngheme='".$_POST['ngheme']."',";
						$sql.="doanvien=".($_POST['doanvien']?1:0).",conlietsi=".($_POST['conlietsi']?1:0).",conthuongbinh='".$_POST['conthuongbinh']."',conchinhsach='".$_POST['conchinhsach']."',ghichu='".$_POST['ghichu']."' where lop='".$lopcn."' and stt=".$_GET['stt'];
						echo $sql;
						$result=dbquery($sql);
						redirect(FUSION_SELF."?lop=".$lopcn);
					
				}
			} else {
				$result=dbquery("select * from qlt_dshocsinh where donvi=$madonvi and id=".$_GET['id']);
				if (dbrows($result)) {
					$data=dbarray($result);
					opentable("Sửa thông tin học sinh");
					echo "<form method='post' action='".FUSION_SELF."?lop=".$lopcn."&act=edit&stt=".$_GET['stt']."'>";
					echo "<table border=0>";
					echo "<tr class='tbl1'><td>Họ và tên</td><td> <input type='text' name='hoten' value='".$data['hoten']."'></td</tr>";
					//echo "<tr><td>Hệ</td><td><select name='tmp_dcxa' id='dcxa_tmp' onclick='ListToBox(this,\'dcxatext\')'><option>A</option><option>B</option></select>";
					//echo "Nếu không có trong danh sách trên thì nhập vào đây: <input type='text' name='dcxa' id='dcxatext'>";
					echo "<tr class='tbl3'><td>Hệ</td><td><select name='he' value='".$data['he']."'><option value='A'>A</option><option value='B'>B</option></td></tr>";
					echo "<tr><td>Giới tính Nữ</td><td><input type='checkbox' name='nu' ".($data['gioinu']?"checked":"")."></td></tr>";
					//echo "<tr class='tbl3'><td>Ngày sinh</td><td>Ngày<input type='text' name='ngay' value='".date("d",$data['ngaysinh'])."'> Tháng<input type='text' name='thang' value='".date("m",$data['ngaysinh'])."'> Năm<input type='text' name='nam' value='".date("Y",$data['ngaysinh'])."'></td></tr>";
					echo "<tr class='tbl3'><td>Ngày sinh:</td><td>Ngày<select name='ngay'>";
					for ($i=1;$i<=31;$i++) {
						echo "<option value='".$i."' ".($i==date("d",strtotime($data['ngaysinh']))?"selected='selected'":"").">".$i."</option>";
					}
					echo "</select> Tháng<select name='thang'>";
					for ($i=1; $i<=12; $i++) {
						echo "<option value='".$i."' ".($i==date("m",strtotime($data['ngaysinh']))?"selected='selected'":"").">".$i."</option>";
					}
					echo "</select> Năm<select name='nam'>";
					$curyear=date('Y');
					for($i=$curyear-15; $i>=$curyear-20; $i--) {
						echo "<option value='".$i."' ".($i==date("Y",strtotime($data['ngaysinh']))?"selected='selected'":"").">".$i."</option>";
					}
					$i=$curyear-14;
					echo "<option value='".$i."'>".$i."</option>";
					echo "</select></td></tr>";
					echo "<tr><td>Nơi sinh (huyện, tỉnh)</td><td><input type='text' name='noisinh' value='".$data['noisinh']."'></td></tr>";
					echo "<tr class='tbl3'><td>Địa chỉ - Xã</td><td>";
					echo  <<<cb
	<select name="dsxa" id="dsxaid" onchange="DropDownTextToBox(this,'dcxaid');" value='
cb;
					echo $data['dcxa']."'>";
					$result2=dbquery("select tenxa from qlt_dsxa");
					if (dbrows($result2)) {
						while ($data2=dbarray($result2)) {
							if ($data2['tenxa']==$data['dcxa']) {
								echo "<option selected='selected'>".$data2['tenxa']."</option>";
							} else {
								echo "<option>".$data2['tenxa']."</option>";
							}
						}
					}
					//$luutru=0;
					echo "</select> Nếu không có trong danh sách thì nhập vào đây (không ghi tắt): <input name='dcxa' type='text' id='dcxaid' value='".$data['dcxa']."'/></td></tr>";
					
					//$luutru=valu('dsxaid');
					echo "<tr><td>Địa chỉ - Thôn</td><td>";
					echo  <<<cb
	<select name="dsthon" id="dsthonid" onchange="DropDownTextToBox(this,'dcthonid');" value='>
cb;
					echo $data['dcthon']."'>";
					$result2=dbquery("select tenthon from qlt_dsthon order by xa");
					if (dbrows($result2)) {
						while ($data2=dbarray($result2)) {
							if ($data2['tenthon']==$data['dcthon']) {
								echo "<option selected='selected'>".$data2['tenthon']."</option>";
							} else {
								echo "<option>".$data2['tenthon']."</option>";
							}
						}
					}
					echo "</select> Nếu không có trong danh sách thì nhập vào đây (không ghi tắt): <input name='dcthon' type='text' id='dcthonid' value='".$data['dcthon']."'/></td></tr>";
					echo "<tr class='tbl3'><td>Số điện thoại</td><td><input type='text' name='sodt' value='".$data['sodt']."'></td></tr>";
					echo "<tr><td>Họ tên cha</td><td><input type='text' name='hotencha' value='".$data['hotencha']."'></td></tr>";
					echo "<tr class='tbl3'><td>Nghề nghiệp cha</td><td><input type='text' name='nghecha' value='".$data['nghecha']."'></td></tr>";
					echo "<tr><td>Họ tên mẹ</td><td><input type='text' name='hotenme' value='".$data['hotenme']."'></td></tr>";
					echo "<tr class='tbl3'><td>Nghề nghiệp mẹ</td><td><input type='text' name='ngheme' value='".$data['ngheme']."'></td></tr>";
					echo "<tr><td>Đoàn viên</td><td><input type='checkbox' name='doanvien' ".($data['doanvien']?"checked":"")."></td></tr>";
					echo "<tr class='tbl3'><td>Con LS</td><td><input type='checkbox' name='conlietsi' value='".$data['conlietsi']."'></td></tr>";
					echo "<tr><td>Con TB (%)</td><td><input type='text' name='conthuongbinh' value='".$data['conthuongbinh']."'>%</td></tr>";
					echo "<tr class='tbl3'><td>Con gia đình chính sách</td><td><input type='text' name='conchinhsach' value='".$data['conchinhsach']."'></td></tr>";
					//echo "<tr><td>Ngày nghỉ học (ra khỏi trường)</td><td><input type='text' name='ngaynghi'></td></tr>";
					echo "<tr><td>Ghi chú khác</td><td><input type='text' name='ghichu' value='".$data['ghichu']."'></td></tr>";
					echo "<tr><td></td><td><input type='submit' name='luu' value='Lưu lại'></td></tr>";
					echo "</table></form>";
					echo "<div align=right><a href='".FUSION_SELF."?lop=$lopcn&act=del&id=".$_GET['id']."'>Xoa hoc sinh nay</a></div>";
					closetable();
				} else {
					echo "Khong co hoc sinh";
				}
			}
		} elseif (($_GET['act']=="movedown")&&isset($_GET['stt'])) {
			$stt=$_GET['stt'];
			$result=dbquery("update qlt_dshocsinh set stt=-stt+2*".$stt."+1 where donvi=$madonvi  and lop='".$lopcn."' and (stt=".$stt." or stt=".($stt+1).")");
			redirect(FUSION_SELF."?lop=".$lopcn);
		} elseif (($_GET['act']=="moveup")&&isset($_GET['stt'])) {
			$stt=$_GET['stt'];
			$result=dbquery("update qlt_dshocsinh set stt=-stt+2*".$stt."-1 where donvi=$madonvi  and lop='".$lopcn."' and (stt=".$stt." or stt=".($stt-1).")");
			redirect(FUSION_SELF."?lop=".$lopcn);
		}
	} else { //liệt kê danh sách lớp
		$result=dbquery("select * from qlt_dshocsinh where lop='".$lopcn."' and donvi=$madonvi order by stt");
		opentable("Danh sách học sinh lớp ".$lopcn);
		if ($siso=dbrows($result)) {//liet ke ds hs lopcn
			echo "<table class='mau small center'><tr class='tbl3' align=center  class='toosmall'><td class='toosmall'>Mã HS</td><td>TT</td><td  class='toosmall'>Họ và tên</td><td class='toosmall'>Hệ</td>";
			echo "<td class='toosmall'>Nữ</td><td class='toosmall'>Ngày sinh</td><td>Nơi sinh</td><td>Địa chỉ</td><td  class='toosmall'>Số đ-thoại</td><td class='toosmall'>Họ tên Cha - Nghề</td>";
			echo "<td class='toosmall'>Họ tên Mẹ - Nghề</td><td class='toosmall'>Đ. viên</td><td class='toosmall'>Con LS</td><td class='toosmall'>Con TB</td><td class='toosmall'>Con chính sách</td><td class='toosmall'>Ghi chú</td><td class='toosmall'>Chọn</td></tr>";
			$i=3;
			while ($data=dbarray($result)) {
				$i=4-$i;
				echo "<tr class='info0 tbl".$i."'><td class='toosmall'>".$data['id']."</font></td><td class='toosmall'  align=center>".$data['stt']."</td><td class='toosmall'><a href='".FUSION_SELF."?lop=".$data['lop']."&act=edit&id=".$data['id']."'>".$data['hoten']."</a></td><td class='toosmall'>".$data['he']."</td><td class='toosmall'>".($data['gioinu']?"X":"")."</td>";
				echo "<td class='toosmall'>".date('d-m-Y', strtotime($data['ngaysinh']))."</td><td class='toosmall'>".$data['noisinh']."</td><td class='toosmall' align=left>".$data[dcthon].", ".$data['dcxa']."</td><td class='toosmall'>".$data['sodt']."</td>";
				echo "<td class='toosmall'>".$data['hotencha']." - ".$data['nghecha']."</td><td class='toosmall'>".$data['hotenme']." - ".$data['ngheme']."</td><td class='toosmall'>".($data['doanvien']?"X":"")."</td>";
				echo "<td class='toosmall'>".($data['conlietsi']?"X":"")."</td><td class='toosmall'>".($data['conthuongbinh']?$data['conthuongbinh']."%":"")."</td><td class='toosmall'>".$data['conchinhsach']."</td><td class='toosmall'>".$data['ghichu']."</td>";
				echo "<td class='toosmall'>";
				if ($data['stt']<$siso) echo "<a href='".FUSION_SELF."?lop=".$data['lop']."&act=movedown&stt=".$data['stt']."'><img alt='[xuống]' title='xuống' src='".get_image("down")."'></a>";
				if ($data['stt']>1) echo "<a href='".FUSION_SELF."?lop=".$data['lop']."&act=moveup&stt=".$data['stt']."'><img alt='[lên]' title='lên' src='".get_image("up")."'></a>";
				echo "</td></tr>";
			}
			
			echo "</table>";
			echo "<div align=right>[<a href='".FUSION_SELF."?lop=".$lopcn."&act=add'>Thêm học sinh</a>]</div>";
		} else {
			if (dblookup("id","qlt_dslop","tenlop='$lopcn'") || checkrights("DS"))
			echo "Chưa có học sinh nào.<br><a href='".FUSION_SELF."?lop=".$lopcn."&act=add'>Thêm học sinh</a>";
		}
		closetable();
	}
} else {
	opentable("Danh sách lớp");
	//echo $llop;
	if ($llop!="") {echo "<a href='".FUSION_SELF."?lop=".$llop."'>Lớp CN: ".$llop."</a><br>";}
	//echo "ABC";
	if ( checkrights("DS")) {
		echo "Chọn lớp:<br>";
		$result=dbquery("select qlt_dslop.*,qlt_users.user_name from qlt_dslop,qlt_users where qlt_dslop.gvcn=qlt_users.user_id and qlt_dslop.donvi=qlt_users.donvi and qlt_dslop.donvi=$madonvi order by stt");
		if (dbrows($result)) {
			echo "<table class='tbl-border center'><tr class='tbl3' align=center><td>STT</td><td>Lớp</td><td>GVCN</td></tr>";
			while ($data=dbarray($result)) {
				echo "<tr class='info0' align=center><td>".$data['stt']."</td><td><a href='".FUSION_SELF."?lop=".$data['tenlop']."'>".$data['tenlop']."</a></td><td align=left>".$data['user_name']."</td></tr>";
			}
			echo "</table>";
		}
		echo "<a href='".FUSION_SELF."?lop=0'>Tuyen sinh</a>"; 
	}
	closetable();

}

require_once THEMES."templates/footer.php";
?>
