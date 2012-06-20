<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
echo "<script type='text/javascript' src='".INCLUDES."tinymce/tiny_mce.js'></script>";
echo "<script type='text/javascript' src='".INCLUDES."tinymce/tiny_mce_init.js'></script>";

if (!iMEMBER) {
	//redirect("index.php");
}

$notes_per_page = 20;

//add_to_title("Notes");
$note_id=$_GET['id'];
if (isset($_GET["act"]) && ($_GET["act"]=="edit")) { //sua
	if (isset($_POST['save'])) { //luu message
		$note_id=$_POST['id'];
		$message=$_POST['message'];
		//echo $message;
		$title=$_POST['title'];
		$result = dbquery("update notes set note_title='$title', note_content='".$message."', note_group=".$_POST['notegroup'].",note_edittime='$today' where note_id=".$note_id);
		redirect(FUSION_SELF."?id=".$note_id);
	} else { //hien thi message for editing
		$result=dbquery("select * from notes where note_id=".$note_id." and note_author=".$userdata['user_id']);
		if (dbrows($result)) {
			$data=dbarray($result);
			//require_once INCLUDES."bbcode_include.php";
			opentable("Edit note");
			$message=  $data['note_content'];
			echo "<form method='post' action='".FUSION_SELF."?act=edit'>";
			echo "<table width=100%>";
			echo "<tr><td><input type='hidden' value='".$note_id."' name='id'>";
			echo "<input type='text' value='".$data['note_title']."' name='title' style='width:90%'></td></tr>";
			echo "<tr><td><textarea name='message' cols='60' rows='35' class='textbox' style='width:98%'>".$message."</textarea></td></tr>";
			echo "<tr><td>Share for group:<select name='notegroup' /><option value='0'></option>";
				$result2 = dbquery("select groups.*,users.user_name FROM groups left join users on groups.group_author=users.user_id where group_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+group_id+'.')>0 ");
				if (dbrows($result2)) {
					while ($data2=dbarray($result2)) {
						echo "<option value='".$data2['group_id']."' ".($data2['group_id']==$data['note_group']?"selected":"").">".$data2['user_name'].".".$data2['group_name']."</option>";
					}
				}
				
				echo "<option value='1' ".($data['note_group']==1?"selected":"").">Public</option></select></td></tr>";
			echo "<tr><td align='center'><input type='submit' value='Save' name='save'></td></tr>";
			//echo "<tr><td align='center'>".display_bbcodes("99%", "message")."</td></tr></table>";
			
			echo "</form>";
			closetable();
		} else {
			echo "Not found";
		}
	}
	
} elseif (isset($_GET["act"]) && ($_GET["act"]=="add")) { //Them moi
	if (isset($_POST['save'])) { //add message
		$note_id=$_POST['id'];
		$message=$_POST['message'];
		$title=$_POST['title'];
		$result = dbquery("insert into notes (note_title,note_content,note_author,note_group,note_datestamp,note_edittime) value ('".$title."','".$message."',".$userdata['user_id'].",".$_POST['notegroup'].",'".$today."','".$today."')");
		redirect(FUSION_SELF."?id=".$note_id);
	} else { 
			if (!iMEMBER) redirect("index.php");
			opentable("add new note");
			echo "<form method='post' action='".FUSION_SELF."?act=add'>";
			echo "<table width=100%>";
			echo "<tr><td>Subject:<input type='text' name='title' style='width:90%'></td></tr>";
			echo "<tr><td><textarea name='message' cols='60' rows='35' class='textbox' style='width:98%'></textarea></td></tr>";
			echo "<tr><td>Share for group:<select name='notegroup' /><option value='0'></option>";
				$result2 = dbquery("select groups.*,users.user_name FROM groups left join users on groups.group_author=users.user_id where group_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+group_id+'.')>0 ");
				if (dbrows($result2)) {
					while ($data2=dbarray($result2)) {
						echo "<option value='".$data2['group_id']."'>".$data2['user_name'].".".$data2['group_name']."</option>";
					}
				}
				
				echo "<option value='1'>Public</option></select></td></tr>";
			if (iMEMBER) echo "<tr><td align='center'><input type='submit' value='Save' name='save'></td></tr>";
			
			echo "</form>";
			closetable();
	}
} elseif (isset($_GET["act"]) && ($_GET["act"]=="delete")) { //xoa	
	$result= dbquery("delete from notes where note_id=".$note_id." and note_author=".$userdata['user_id']);
	redirect(FUSION_SELF);
} else { //xem 
	
	if ($note_id=='') { //khong co tham so id -> xem theo trang
		if (!iMEMBER) redirect("index.php");
		$page=$_GET['page'];
		if (!is_numeric($page)) {
			$page=0;
		}
		$note_from = $notes_per_page* $page;
		$result=dbquery("select note_id, note_title, note_datestamp, note_edittime, note_group from notes where note_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+note_group+'.')>0 order by note_datestamp desc limit ".$note_from.",".$notes_per_page);
		opentable("List of notes");
		
		if (dbrows($result)) {
			echo "<table border='0'>";
			$i=1;
			while ($data=dbarray($result)) {
				echo "<tr><td><a href='".FUSION_SELF."?act=view&id=".$data['note_id']."'>";
				
				echo "[".$i++."] <b>";
				echo $data['note_title'];
				//echo " - ";
				//echo $data['note_group'];
				echo "</b></a></td></tr>";
			}
			echo "</table>";
		} else {
			echo "Not found";
		}
		echo "<hr>";
		if ($page>0) echo "<a href='".FUSION_SELF."?page=".($page-1)."'>Previous Page</a>"; 
		echo "<a href='".FUSION_SELF."?page=".($page+1)."'>Next Page</a> <a href='".FUSION_SELF."?act=add'>Add new note</a>";
		closetable();
		//echo word(1)."NAM".$language;
	} else { //xem 1 id
		if (iMEMBER) {
			$result=dbquery("select * from notes where note_id=".$note_id." and (note_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+note_group+'.')>0 or note_group=1)");
		} else {
			$result=dbquery("select * from notes where note_id=".$note_id." and  note_group=1");
		}
		
		if (dbrows($result)) {
			$data=dbarray($result);
			opentable($data['note_title']);
			//echo "<textarea name='message' cols='60' rows='15' class='textbox' style='width:98%'>".$message."</textarea>"
			echo $data['note_content'];
			echo "<hr>";
			echo "<i>Share for  ";
			if ($data['note_group']==0) {
				echo "none";
			} elseif ($data['note_group']==1) {
				echo "Public. Created by <b>".getUser($data['note_author'])."</b>";
			} else {
				echo getGroup2($data['note_group']);
			}
			
			if (iMEMBER) {
				if ($data['note_author']==$userdata['user_id'])
					echo "</i><hr><table border=0 width=100%><tr><td><a href=".FUSION_SELF."?act=edit&id=".$note_id.">Edit</a></td><td align=right><a href=".FUSION_SELF."?act=delete&id=".$note_id.">Delete</a></td></tr></table>";
			}
			
			closetable();
			
		} else {
			echo "Not found";
		}
		
	}
}

require_once THEMES."templates/footer.php";
?>
