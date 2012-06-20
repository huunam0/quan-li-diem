<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."admin/members.php";
include LOCALE.LOCALESET."user_fields.php";

if (!checkrights("LP")) { redirect("index.php"); }

if (!isset($_GET['act'])) { $_GET['act'] = ""; }

if (($_GET['act']=='add')&&isset($_POST['tenlop'])) {//them 1 lop moi 
	$stt = dbcount2('qlt_dslop',"donvi=$madonvi");
	$stt++;
	$result= dbquery("insert into qlt_dslop (tenlop,gvcn,kieulop,stt,donvi) value ('".$_POST['tenlop']."','".$_POST['gvcn']."','".$_POST['kieulop']."',".$stt.",$madonvi) ");
	redirect(FUSION_SELF.$aidlink);
} elseif (($_GET['act']=='moveup')&&isset($_GET['lop'])) {
	$lopid=dblookup("stt","qlt_dslop","id=".$_GET['lop']);
	$step=((isset($_GET['step'])&& isnum($_GET['step']))?$_GET['step']:1);
	//$result=dbquery("update qlt_dslop set stt=(stt+1-".$lopid.") % (".$step."+1)+".$lopid." where stt>=".$lopid." and stt<=".($lopid+$step));
	$result=dbquery("update qlt_dslop set stt=-stt-1+2*".$lopid." where (stt=".$lopid." or stt=".($lopid-1).") and donvi=$madonvi");
	redirect(FUSION_SELF);
} elseif (($_GET['act']=='movedown')&&isset($_GET['lop'])) {
	//$lopid=$_GET['lop'];
	$lopid=dblookup("stt","qlt_dslop","id=".$_GET['lop']);
	$step=((isset($_GET['step'])&& isnum($_GET['step']))?$_GET['step']:1);
	//$result=dbquery("update qlt_dslop set stt=(stt-".($lopid-$step).") % (".$step."+1)+".$lopid." where stt>=".$lopid." and stt<=".($lopid+$step));
	$result=dbquery("update qlt_dslop set stt=-stt+1+2*".$lopid." where (stt=".$lopid." or stt=".($lopid+1).") and donvi=$madonvi");
	redirect(FUSION_SELF);
} elseif (($_GET['act']=='del')&&isset($_GET['lop'])) {
	//$lopid=$_GET['lop'];
	$lopid=dblookup("stt","qlt_dslop","id=".$_GET['lop']);
	$result=dbquery("delete from qlt_dslop where id=".$_GET['lop']);
	$result=dbquery("update qlt_dslop set stt=stt-1 where donvi=$madonvi and  stt>".$lopid);
	redirect(FUSION_SELF.$aidlink);
} elseif (($_GET['act']=='edit')&&isset($_GET['lop'])) {
	$lopid=$_GET['lop'];
	//$result=dbquery("select * from qlt_dslop where stt=".$lopid." limit 1");
	if (isset($_POST['save'])) {
		//echo "update qlt_dslop set tenlop='".$_POST['tenlop']."',gvcn=".$_POST['gvcn'].",kieulop=".$_POST['kieulop'].", stt=".$_POST['sttlop']." where id=".$lopid;
		$result= dbquery("update qlt_dslop set tenlop='".$_POST['tenlop']."',gvcn=".$_POST['gvcn'].",kieulop=".$_POST['kieulop'].", stt=".$_POST['sttlop']." where id=".$lopid);
		redirect(FUSION_SELF,9);

	} else {
		$result=dbquery("select * from qlt_dslop where id=".$lopid." and donvi=$madonvi limit 1");
		if (dbrows($result)) {
			$data=dbarray($result);
			opentable("Sửa thông tin lớp ".$data['tenlop']);
			echo "<form name='addform' method='post' action='".FUSION_SELF.$aidlink."&act=edit&lop=".$lopid."'>\n";
			echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
			echo "<td class='tbl'>Tên lớp:<span style='color:#ff0000'>*</span></td>\n";
			echo "<td class='tbl'><input type='text' name='tenlop' maxlength='30' value='".$data['tenlop']."' class='textbox' style='width:200px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>Kiểu lớp:</td>\n";
			echo "<td class='tbl'><input type='text' name='kieulop' maxlength='100' value='".$data['kieulop']."' class='textbox' style='width:200px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>Giáo viên CN:</td>\n";
			echo "<td class='tbl'><select name='gvcn'  style='width:200px;' />";
			echo "<option value='".$data[gvcn]."'>".dblookup('user_name','qlt_users','user_id='.$data['gvcn'])."</option>";
			//$dsgv = dbquery("select user_id, user_name from qlt_users where chucvu='GV'");
			$dsgv = dbquery("select user_id, user_name from qlt_users where (select count(*) from qlt_dslop where gvcn=user_id)=0");
			if (dbrows($dsgv)) {
				while ($gv=dbarray($dsgv)) {
					echo "<option value='".$gv['user_id']."'>".$gv['user_name']."</option>";
				}
			}
			echo "</select></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td class='tbl'>STT lớp:</td>\n";
			echo "<td class='tbl'><input type='text' name='sttlop' value='".$data['stt']."' class='textbox' style='width:200px;' /></td>\n";
			echo "</tr>\n<tr>\n";
			echo "<td align='center' colspan='2'><br />\n";
			echo "<input type='submit' name='save' value='Lưu lại' class='button' /></td>\n";
			echo "</tr>\n</table>\n</form>\n";
			closetable();
		} else {
			echo "Khong tim thay";
			redirect(FUSION_SELF,10);
		}
	}
	//$result=dbquery("update qlt_dslop set stt=stt-1 where stt>".$lopid);
	//redirect(FUSION_SELF.$aidlink);
	//echo "Da sua xong";
} else {//hien thi dan sach lop hoc
 //no parameters
        opentable("Danh sách lớp học");
        //$result = dbquery("update qlt_dslop set stt=id where stt=0");
		$result = dbquery("select * from qlt_dslop where donvi=$madonvi order by stt");
        if ($rows=dbrows($result)) {
                $i = 0;
                echo "<table cellpadding='0' cellspacing='1' class='tbl-border center'>\n<tr >\n";
				echo "<td class='tbl3'></td><td class='tbl3'><strong>STT</strong></td>\n";
                echo "<td class='tbl3'><strong>Tên lớp</strong></td>\n";
                echo "<td align='center'  class='tbl3' style='white-space:nowrap'><strong>GVCN</strong></td>\n";
                echo "<td align='center'  class='tbl3' style='white-space:nowrap'><strong>Kiểu lớp</strong></td>\n";
				echo "<td align='center'  class='tbl3' style='white-space:nowrap'><strong>Tuỳ chọn</strong></td>\n";
                echo "</tr>\n";
                while ($data = dbarray($result)) {
                        //$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl3");
								$i++;
                                echo "<tr class='info0'>\n<td>";
								if ($i<$rows) { 
									echo "<a href='".FUSION_SELF.$aidlink."&amp;act=movedown&lop=".$data['id']."'><img alt='[xuống]' title='xuống' src='".get_image("down")."'></a>";
								} else {echo "<img alt='[xuống]' title='xuống' src='".get_image("down")."'>";}
								if ($i>1) {
									echo " <a href='".FUSION_SELF.$aidlink."&amp;act=moveup&lop=".$data['id']."'><img alt='[lên]' title='lên' src='".get_image("up")."'></a>";
								} else {echo " <img alt='[lên]' title='lên' src='".get_image("up")."'>";}
								echo "</td>";
								echo "<td align='center'>".$data['stt']."</td><td align='center'><b>".$data['tenlop']."</td>\n";
                                echo "<td style='white-space:nowrap'>".dblookup('user_name','qlt_users','user_id='.$data['gvcn'])."</td>\n";
                                echo "<td align='center' style='white-space:nowrap'>".$data['kieulop']."</td>\n";
								echo "<td><a href='".FUSION_SELF."?act=del&lop=".$data['id']."'>[Xoá]</a> <a href='".FUSION_SELF."?act=edit&lop=".$data['id']."'>[Sửa]</a> ";
								//echo "";
								echo "</td>";
								echo "</tr>\n"; 
                        
                }
                echo "</table>\n";
                echo "<a href='".FUSION_SELF."?act=stt'> Cap nhat lai STT</a>";
        } else {echo "không có lớp nào";}
        closetable();
        opentable("Thêm lớp mới");
		echo "<form name='addform' method='post' action='".FUSION_SELF.$aidlink."&amp;act=add'>\n";
		echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
		echo "<td class='tbl'>Tên lớp:<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='text' name='tenlop' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>Kiểu lớp:</td>\n";
		echo "<td class='tbl'><input type='text' name='kieulop' maxlength='100' class='textbox' style='width:200px;' value='1'/></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>Giáo viên CN:</td>\n";
		echo "<td class='tbl'><select name='gvcn'  style='width:200px;' />";
		//$dsgv = dbquery("select user_id, user_name from qlt_users where chucvu='GV'");
		$dsgv = dbquery("select user_id, user_name from qlt_users where donvi=$madonvi and (select count(*) from qlt_dslop where gvcn=user_id)=0");
		if (dbrows($dsgv)) {
			while ($gv=dbarray($dsgv)) {
				echo "<option value='".$gv['user_id']."'>".$gv['user_name']."</option>";
			}
		}
		echo "</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td align='center' colspan='2'><br />\n";
		echo "<input type='submit' name='themlop' value='Thêm' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
		closetable();
}
require_once THEMES."templates/footer.php";
?>
