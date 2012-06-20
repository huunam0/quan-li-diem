<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
if (!iMEMBER) {redirect("index.php");}
$rows=30;
$page=0; 
$begin=(isset($_GET['begin'])?$_GET['begin']+0:1);
if (isset($_GET['act']) && ($_GET['act']=="add")) {
	if (isset($_POST['addnew'])) {
		$result=dbquery("insert into phonebook (contact_author,contact_name,contact_address,contact_number,contact_nickchat,contact_email,contact_birth,contact_note,contact_group) value ('".$userdata['user_id']."','".$_POST['contactname']."','".$_POST['contactaddress']."','".$_POST['contactnumber']."','".$_POST['contactnickchat']."','".$_POST['contactemail']."','".$_POST['contactbirth']."','".$_POST['contactnote']."','".$_POST['contactgroup']."') ");
		redirect(FUSION_SELF);
	} else {
		opentable("Add new contact");
		echo "<form method='post' action='contact.php?act=add'><table>";
		echo "<tr><td align=right>Họ và tên</td><td><input type='text' name='contactname' size='50px'/></td></tr>";
		echo "<tr><td align=right>Địa chỉ</td><td><input type='text' name='contactaddress' size='50px'/></td></tr>";
		echo "<tr><td align=right>Số điện thoại</td><td><input type='text' name='contactnumber' size='50px'/></td></tr>";
		echo "<tr><td align=right>Nick chat</td><td><input type='text' name='contactnickchat' size='50px'/></td></tr>";
		echo "<tr><td align=right>E-mail</td><td><input type='text' name='contactemail' size='50px'/></td></tr>";
		echo "<tr><td align=right>Ngày sinh</td><td><input type='text' name='contactbirth' size='50px'/></td></tr>";
		echo "<tr><td align=right>Ghi chú</td><td><input type='text' name='contactnote' size='50px'/></td></tr>";
		echo "<tr><td align=right>Chia sẻ tên này cho nhóm</td><td><select name='eventgroup' /><option value='0'></option>";
		$result2 = dbquery("select groups.*,users.user_name FROM groups left join users on groups.group_author=users.user_id where group_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+group_id+'.')>0 ");
		if (dbrows($result2)) {
			while ($data2=dbarray($result2)) {
				echo "<option value='".$data2['group_id']."' >".$data2['user_name'].".".$data2['group_name']."</option>";
			}
		}
		echo "</select></td></tr>";
		echo "<tr><td></td><td align=center><input type='submit' name='addnew' value='Thêm mới'/></td></tr>";
		echo "</table></form>";
		closetable();
	}
} elseif (isset($_GET['act']) && ($_GET['act']=="edit")) {
	//$getid=($_GET['id']?$_GET['id']:"");
	if (isset($_POST['update'])) {
		$result=dbquery("update phonebook set contact_name='".trim($_POST['contactname'])."',contact_address='".$_POST['contactaddress']."',contact_number='".$_POST['contactnumber']."',contact_nickchat='".$_POST['contactnickchat']."',contact_email='".$_POST['contactemail']."',contact_birth='".$_POST['contactbirth']."',contact_note='".$_POST['contactnote']."' where id=".$_POST['contactid']);
		redirect(FUSION_SELF);
	} else {
		$result=dbquery("select * from phonebook where id=".$_GET['id']." and contact_author='".$userdata['user_id']."'");
		if (dbrows($result)) {
			$data=dbarray($result);
			opentable("Edit contact");
			echo "<form method='post' action='contact.php?act=edit'><table>";
			echo "<tr><td align=right>Họ và tên</td><td><input type='text' name='contactname' value='".$data['contact_name']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>Địa chỉ</td><td><input type='text' name='contactaddress' value='".$data['contact_address']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>Số điện thoại</td><td><input type='text' name='contactnumber' value='".$data['contact_number']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>Nick chat</td><td><input type='text' name='contactnickchat' value='".$data['contact_nickchat']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>E-mail</td><td><input type='text' name='contactemail' value='".$data['contact_email']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>Ngày sinh</td><td><input type='text' name='contactbirth' value='".$data['contact_birth']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>Ghi chú</td><td><input type='text' name='contactnote' value='".$data['contact_note']."' size='50px'/></td></tr>";
			echo "<tr><td align=right>Chia sẻ cho nhóm</td><td><select name='contactgroup' /><option value='0'></option>";
			$result2 = dbquery("select groups.*,users.user_name FROM groups left join users on groups.group_author=users.user_id where group_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+group_id+'.')>0 ");
			if (dbrows($result2)) {
				while ($data2=dbarray($result2)) {
					echo "<option value='".$data2['group_id']."' ".($data2['group_id']==$data['contact_group']?"selected":"").">".$data2['user_name'].".".$data2['group_name']."</option>";
				}
			}
			echo "</select></td></tr>";
			echo "<tr><td><input type='hidden' name='contactid' value='".$data['id']."' size='50px'/></td><td align=center><input type='submit' name='update' value='Cập nhật'/></td></tr>";
			echo "</table></form>";
			echo "<a href='contact.php?act=delete&id=".$data['id']."'>[Xóa tên này]</a>";
			closetable();
		} else {
			echo "Contact not found!";
		}
		
	}
} elseif (isset($_GET['act']) && isset($_GET['id']) && ($_GET['act']=="delete")) {
	$result=dbquery("delete from phonebook where id=".$_GET['id']);
	redirect("contact.php");
} elseif (isset($_GET['act']) && ($_GET['act']=="search")) { //search contacts
	$result=dbquery("select * from phonebook where (contact_author='".$userdata['user_id']."' or instr('".$userdata['user_groups']."','.'+contact_group+'.')>0)   and contact_name like '".$_POST['searchname']."%'  and contact_number like '%".$_POST['searchnumber']."%' order by contact_name limit ".$rows);
	//echo("select * from phonebook where (contact_author='".$userdata['user_id']."' or instr('".$userdata['user_groups']."','.'+contact_group+'.')>0) and contact_name like '".$_POST['searchname']."*'  and contact_number like '*".$_POST['searchnumber']."*' order by contact_name limit ".$rows);
	opentable("Kết quả tìm kiếm với  tên là '".$_POST['searchname']."' và số ĐT là '".$_POST['searchnumber']."' ");
	if (dbrows($result)) {
		echo "<table class='mau'><tr class='tbl3' align=center><td>TT</td><td>Họ và tên</td><td>Địa chỉ</td><td>Số điện thoại</td><td>Nick chat</td><td>E-mail</td></tr>";
		$i=1+$page*$rows;
		while ($data=dbarray($result)) {
			echo "<tr class='info0'><td align=center>".$i++."</td><td><a href='contact.php?act=edit&id=".$data['id']."' ";
			if ($data['contact_birth']) {$tip = "Birthday: ".DateFormat($data['contact_birth'])."<br>";} else {$tip="";}
			//$tip.="nam";
			if ($data['contact_note']) {$tip .= "Notes: ".$data['contact_note'];}
			if ($tip) {
				echo  <<<NAM
		onmouseover="ddrivetip('$tip','#ffee66',200)"
NAM;
		//echo " >".$addbefore.$ii.$addafter."</a></td>";
				echo " onmouseout='hideddrivetip()'";
			}
			echo ">".$data['contact_name']."</a></td><td>".$data['contact_address']."</td><td>".$data['contact_number']." <a target='_blank' href='http://vinaphone.com.vn/messaging/sms/sms.do?to=".$data['contact_number']."'>SMS</a></td><td>".$data['contact_nickchat']."</td><td>".$data['contact_email']."</td>";
			echo "</tr\>";
		}
		echo "</table>";

	} else {
		echo "Không tìm thấy<br><a href='".FUSION_SELF."?act=add'>[Thêm tên mới]</a>";
	}
	echo "<div align=right><a href='".FUSION_SELF."?act=add'>[Thêm tên mới]</a></div>";
	closetable();
} else {//list contacts
	if (isset($_GET['page'])) $page=$_GET['page'];
	opentable("Danh sách - Trang ".($page+1));
	$result=dbquery("select * from phonebook where contact_author='".$userdata['user_id']."' or instr('".$userdata['user_groups']."','.'+contact_group+'.')>0 order by contact_name limit ".$page*$rows.",".$rows);
	if (dbrows($result)) {
		echo "<table class='mau'><tr class='tbl3' align=center><td>TT</td><td>Họ và tên</td><td>Địa chỉ</td><td>Số điện thoại</td><td>Nick chat</td><td>E-mail</td></tr>";
		$i=1+$page*$rows;
		while ($data=dbarray($result)) {
			echo "<tr class='info0'><td align=center>".$i++."</td><td><a href='contact.php?act=edit&id=".$data['id']."' ";
			if ($data['contact_birth']) {$tip = "Birthday: ".DateFormat($data['contact_birth'])."<br>";} else {$tip="";}
			//$tip.="nam";
			if ($data['contact_note']) {$tip .= "Notes: ".$data['contact_note'];}
			if ($tip) {
				echo  <<<NAM
		onmouseover="ddrivetip('$tip','#ffee66',200)"
NAM;
		//echo " >".$addbefore.$ii.$addafter."</a></td>";
				echo " onmouseout='hideddrivetip()'";
			}
			$fnumber=$data['contact_number'];
			/*$ii=0;
			for ($ii=0; $ii<len($fnumber); $ii++) {
				if (!strpos("1234567890",substr($fnumber,$ii,1))) break;
			}
			$fnumber=substr($fnumber,0,$ii);
			*/
			$fnumber=" <a target='_blank' href='http://vinaphone.com.vn/messaging/sms/sms.do?to=$fnumber'>SMS</a>";
			echo "><div>".$data['contact_name']."</div></a></td><td>".$data['contact_address']."</td><td>".$data['contact_number'].$fnumber."</td><td>".$data['contact_nickchat']."</td><td>".$data['contact_email']."</td>";
			echo "</tr\>";
		}
		echo "</table><hr>";
		if ($page>0) echo "[<a href='".FUSION_SELF."?page=".($page-1)."'>Trang trước</a>] ";
		echo "[<a href='".FUSION_SELF."?page=".($page+1)."'>Trang sau</a>] <div align=right><a href='".FUSION_SELF."?act=add'>[Thêm tên mới]</a></div>";
	} else {
		echo "Không tìm thấy";
	}

	closetable();
	
}
opentable("Tìm kiếm theo tên hoặc số điện thoại");
	echo "<form method='post' action='".FUSION_SELF."?act=search'><table width=100%><tr>";
	echo "<td>Họ và tên: <input type='text' name='searchname' value='".$_POST['searchname']."' /> </td>";
	echo "<td>Số điện thoại: <input type='text' name='searchnumber'  value='".$_POST['searchnumber']."' /></td>";
	echo "<td><input type='submit' name='search' value='Tìm kiếm'/></td>";
	echo "</tr></table></form>";
	closetable();
require_once THEMES."templates/footer.php";
?>
