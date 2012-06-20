<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//include LOCALE.LOCALESET."forum/main.php";

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
//neu khong phai admin he thong thi thoat
if ($userdata['user_level']!=103) {
	redirect("index.php");
}
if (isset($_GET['act'])&&($_GET['act']=="edit")) {//sua don vi
	if (isset($_POST['update'])) { //cap nhat sua don vi
		$result=dbquery("update qlt_donvi set ten='".$_POST['tendonvi']."', quanli=".(isset($_POST['cqquanli'])?1:0).", diachi='".$_POST['dcdonvi']."'".(isset($_POST['chonadmin'])?", admin=".$_POST['chonadmin']:"").(isset($_POST['choncqql'])?", captren=".$_POST['choncqql']:"")." where id=".$_GET['id']);
		redirect(FUSION_SELF);
	} else {//cho phep sua don vi
		opentable("Xem va sua thong tin cu don vi");
		$result=dbquery("select * from qlt_donvi where id=".$_GET['id']);
		if (dbrows($result)) { // co id
			$data=dbarray($result);
			echo "<form method='post' action='".FUSION_SELF."?act=edit&id=".$_GET['id']."'>";
			echo "Ten don vi: <input name='tendonvi' value='".$data['ten']."'>";
			echo " La co quan quan li?<input type='checkbox' name='cqquanli' ".($data['quanli']==1?"checked":"")."><br>";
			echo "Dia chi: <input name='dcdonvi' value='".$data['diachi']."'>";
			echo "<br>Admin cua don vi: <select name='chonadmin'><option></option>";
			$result=dbquery("select user_id, user_name, donvi from qlt_users where user_level=102 order by donvi");
			if (dbrows($result)) {
				while ($data2=dbarray($result)) {
					echo "<option value='".$data2['user_id']."' ".($data['admin']==$data2['user_id']?"selected":"").">".$data2['user_id']."_".$data2['user_name']." #".$data2['donvi']."</option>";
				}
			}
			echo "</select>";
			
			echo "<br>Co quan cap tren: <select name='choncqql'><option></option>";
			$result=dbquery("select id, ten from qlt_donvi where quanli=1");
			if (dbrows($result)) {
				while ($data2=dbarray($result)) {
					echo "<option value='".$data2['id']."' ".($data['captren']==$data2['id']?"selected":"").">".$data2['id']."_".$data2['ten']."</option>";
				}
			}
			echo "</select>";
			echo "<br> <input type='submit' name='update' value='Save'>";
		
			
		} else { //khong co id
			echo "Khong tim thay";
		}
		closetable();
		echo "<div align=right><a href='".FUSION_SELF."?act=delete&id=".$_GET['id']."'>Xoa don vi nay</a></div>";
	}
} elseif (isset($_GET['act'])&&($_GET['act']=="delete")) {//xoa don vi
	if (isset($_GET['id'])) {
		$result=dbquery("delete from qlt_donvi where id=".$_GET['id']);
	}
	redirect(FUSION_SELF);
} elseif (isset($_GET['act'])&&($_GET['act']=="add")) {//themdon vi
	if (isset($_POST['tendonvi']) && isset($_POST['addnew'])) {
		$result=dbquery("insert into qlt_donvi (ten,diachi,quanli,admin,captren) value ('".$_POST['tendonvi']."','".$_POST['dcdonvi']."',".(isset($_POST['cqquanli'])?1:0).",".$_POST['chonadmin'].",".$_POST['choncqql'].") ");
	}
	if (isset($_POST['tenadmin']) && isset($_POST['addadmin'])) {
		$result=dbquery("insert into qlt_users (user_name, user_password, user_level,user_rights) value ('".$_POST['tenadmin']."','".md5(md5($_POST['matkhau']))."',102,'UG.AD.LP.PC.PL') ");
	}
	redirect(FUSION_SELF);
} else {
	
	opentable("Danh sach admin cua cac don vi");
	$result=dbquery("select * from qlt_donvi");
	if (dbrows($result)) {
		echo "<table class='mau center'><tr class='tbl3' align=center><td>Ma so</td><td>Ten don vi</td><td>Admin</td><td>Co quan quan li</td><td>Ghi chu</td></tr>";
		$i=1;
		while ($data=dbarray($result)) {
			echo "<tr class='info0 tbl$i'><td>".$data['id']."</td><td><a href='".FUSION_SELF."?act=edit&id=".$data['id']."'>".$data['ten']."</a></td><td><a href='qluser.php?act=view&id=".$data['admin']."'>".dblookup("user_name","qlt_users","user_id=".$data['admin'])."</a></td>";
			echo "<td>".($data['captren']?dblookup("ten","qlt_donvi","id=".$data['captren']):"<i>Chua xac lap</i>")."</td><td>".($data['quanli']==1?"Co quan quan li":"Truong hoc")."</td></tr>";
			$i=4-$i;
		}
		echo "</table>";
	} else {
		echo "Chua co don vi nao!";
	}
	
	closetable();
	
	opentable("Bo sung them don vi moi");
	
	echo "<form method='post' action='".FUSION_SELF."?act=add'>";
	echo "Ten don vi: <input name='tendonvi'>";
	echo " La co quan quan li?<input type='checkbox' name='cqquanli'><br>";
	echo "Dia chi: <input name='dcdonvi'>";
	echo " Admin cua don vi: <select name='chonadmin'><option></option>";
	$result=dbquery("select user_id, user_name, donvi from qlt_users where user_level=102 order by donvi");
	if (dbrows($result)) {
		while ($data=dbarray($result)) {
			echo "<option value='".$data['user_id']."'>".$data['user_id']."_".$data['user_name']." #".$data['donvi']."</option>";
		}
	}
	echo "</select>";
	
	echo " Co quan cap tren: <select name='choncqql'><option></option>";
	$result=dbquery("select id, ten from qlt_donvi where quanli=1");
	if (dbrows($result)) {
		while ($data=dbarray($result)) {
			echo "<option value='".$data['id']."'>".$data['id']."_".$data['ten']."</option>";
		}
	}
	echo "</select>";
	echo "<br> <input type='submit' name='addnew' value='Them don vi'>";
	echo "<hr>Them admin: ";
	echo "Ten admin: <input name='tenadmin'>";
	echo "Password: <input name='matkhau'>";
	echo "<br> <input type='submit' name='addadmin' value='Them admin'>";
	echo "</form>";
	closetable();
}
	
//echo $userdata['user_password'];
require_once THEMES."templates/footer.php";
?>
