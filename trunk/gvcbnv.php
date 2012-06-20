<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."admin/members.php";
include LOCALE.LOCALESET."user_fields.php";

if ((!checkrights("GV")) && ($userdata['level']<102) ) { redirect("index.php"); }

if (!isset($_GET['step'])) { $_GET['step'] = ""; }

if ($_GET['step'] == "add") { //them nhan vien
        if (isset($_POST['add_user'])) { //cap nhat
                $error = "";
                $username = trim(eregi_replace(" +", " ", $_POST['username']));
		if ($username == "" || trim($_POST['password1']) == "") { $error .= $locale['451']."<br />\n"; }
                if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['password1'])) {
                        $error .= $locale['457']."<br />\n";
                }
                $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='$username'");
                if (dbrows($result)) { $error = $locale['453']."<br />\n"; }
                $profile_method = "validate_insert"; $db_fields = ""; $db_values = "";
                $result = dbquery("SELECT * FROM ".DB_USER_FIELDS." ORDER BY field_order");
                if (dbrows($result)) {
                        while($data = dbarray($result)) {
                                if (file_exists(LOCALE.LOCALESET."user_fields/".$data['field_name'].".php")) {
                                        include LOCALE.LOCALESET."user_fields/".$data['field_name'].".php";
                                }
                                if (file_exists(INCLUDES."user_fields/".$data['field_name']."_include.php")) {
                                        include INCLUDES."user_fields/".$data['field_name']."_include.php";
                                }
                        }
                }
		$group_ids = "";
		if (isset($_POST['groupe']) && is_array($_POST['groupe'])) {
			foreach ($_POST['groupe'] as $thisnum) {
				if (isnum($thisnum)) { $group_ids .= ".".$thisnum; }
				}
		}
                if ($error == "") { //add user
                        $shortname=($_POST['shortname']?$_POST['shortname']:getlastname($username));
                	$result = dbquery("INSERT INTO qlt_users (user_name, short_name, user_password, user_joined,  user_lastvisit, user_level,donvi) VALUES('$username','".$shortname."','".md5(md5($_POST['password1']))."', '".time()."', '".time()."', 101,$madonvi)");
                        opentable($locale['480']);
                        echo "<div style='text-align:center'><br />\n".$locale['481']."<br /><br />\n";
                        echo "<a href='".FUSION_SELF."'>".$locale['432']."</a><br /><br />\n";
                        echo "<a href='index.php"."'>".$locale['433']."</a><br /><br />\n";
                        echo "</div>\n";
                        closetable();
                        redirect(FUSION_SELF,5);
                } else {
                        opentable($locale['480']);
                        echo "<div style='text-align:center'><br />\n".$locale['482']."<br /><br />\n".$error."<br />\n";
                        echo "<a href='".FUSION_SELF."'>".$locale['432']."</a><br /><br />\n";
                        echo "<a href='index.php"."'>".$locale['433']."</a><br /><br />\n";
                        echo "</div>\n";
                        closetable();
                }
        } else { //hien form nhap lieu
                opentable("them giáo viên, CB, nhân viên");
                echo "<form name='addform' method='post' action='".FUSION_SELF."?step=add'>\n";
                echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
                echo "<td class='tbl'>Họ và tên<span style='color:#ff0000'>*</span></td>\n";
                echo "<td class='tbl'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
		 echo "<td class='tbl'>Ten ngan</td>\n";
                echo "<td class='tbl'><input type='text' name='shortname' maxlength='15' class='textbox' style='width:100px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td class='tbl'>".$locale['u002']."<span style='color:#ff0000'>*</span></td>\n";
                echo "<td class='tbl'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;' value='123456'/></td>\n";
                echo "</tr>\n<tr>\n";
		echo "<td class='tbl'>Tham gia vao cac nhom:</td>\n";
		echo "<td class='tbl'>";
		$result=dbquery("select * from qlt_user_groups where donvi=".$userdata['donvi']);
		if (dbrows($result)) {
			while ($data=dbarray($result)) {
				echo " <input type='checkbox' name='groupe[]' value='".$data['group_id']."'>".$data['group_name']."; ";
			}
		} else { 
			echo "<i>chua co nhom nao. Hay them nhom, VD: nhom Toan-Tin, nhom Xa hoi,...</i>";
		}
		echo "</td>";
                //echo "<td class='tbl'><input type='text' name='chucvu' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td align='center' colspan='2'><br />\n";
                echo "<input type='submit' name='add_user' value='".$locale['480']."' class='button' /></td>\n";
                echo "</tr>\n</table>\n</form>\n";
                closetable();
        }
} elseif ($_GET['step'] == "edit" && isnum($_GET['user_id'])) { //chinh sua 1 thanh vien
        $user_data = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
	if (!$user_data and ($user_data['user_level'] >= 102 or iSUPERADMIN)) { redirect(FUSION_SELF); }
        if (isset($_POST['savechanges'])) { //save change luu thay doi
                require_once "updateuser.php";
                if ($error == "") {
                        opentable($locale['430']);
                        echo "<div style='text-align:center'><br />\n";
                        echo $locale['431']."<br /><br />\n";
                        echo "<a href='".FUSION_SELF."'>".$locale['432']."</a><br /><br />\n";
                        echo "<a href='index.php'>".$locale['433']."</a><br /><br />\n";
                        echo "</div>\n";
                        closetable();
                         redirect(FUSION_SELF,3);
                } else {
                        opentable($locale['430']);
                        echo "<div style='text-align:center'><br />\n";
                        echo $locale['434']."<br /><br />\n".$error."<br />\n";
                        echo "<a href='".FUSION_SELF."'>".$locale['432']."</a><br /><br />\n";
                        echo "<a href='index.php'>".$locale['433']."</a><br /><br />\n";
                        echo "</div>\n";
                        closetable();
                }
        } else { //hien form thay doi
                //require_once INCLUDES."bbcode_include.php";
                $offset_list = "";
                for ($i = -13; $i < 17; $i++) {
                        if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
                        $offset_list .= "<option".($offset == $data['user_offset'] ? " selected='selected'" : "").">".$offset."</option>\n";
                }
                opentable($locale['430']);
                echo "<form name='inputform' method='post' action='".FUSION_SELF."?step=edit&amp;user_id=".$_GET['user_id']."' enctype='multipart/form-data'>\n";
                echo "<table cellpadding='0' cellspacing='0' class='center'>\n";
                echo "<tr>\n<td class='tbl'>".$locale['u001'].":<span style='color:#ff0000'>*</span></td>\n";
                echo "<td class='tbl'><input type='text' name='user_name' value='".$user_data['user_name']."' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td class='tbl'>Ten ngan</td>\n";
                echo "<td class='tbl'><input type='text' name='shortname' maxlength='20' class='textbox' style='width:100px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td class='tbl'>".$locale['u003'].":</td>\n";
                echo "<td class='tbl'><input type='password' name='user_new_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td class='tbl'>".$locale['u004'].":</td>\n";
                echo "<td class='tbl'><input type='password' name='user_new_password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td class='tbl'>".$locale['u005'].":<span style='color:#ff0000'>*</span></td>\n";
                echo "<td class='tbl'><input type='text' name='user_email' value='".$user_data['user_email']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
                echo "<td class='tbl'>".$locale['u006'].":</td>\n";
                echo "<td class='tbl'><input type='radio' name='user_hide_email' value='1'".($user_data['user_hide_email'] == "1" ? " checked='checked'" : "")." />".$locale['u007']." ";
                echo "<input type='radio' name='user_hide_email' value='0'".($user_data['user_hide_email'] == "0" ? " checked='checked'" : "")." />".$locale['u008']."</td>\n";
                echo "</tr>\n";

                if (!$user_data['user_avatar']) {
                        echo "<tr>\n";
                        echo "<td valign='top' class='tbl'>".$locale['u010'].":</td>\n";
                        echo "<td class='tbl'><select name='avatar_list'
                onKeyUp=\"document.images.view_avatar.style.display = ''; document.images.view_avatar.src = '".IMAGES."avatars/'+this.options[this.selectedIndex].value; document.user_avatar.disabled = true;\"
                onChange=\"document.images.view_avatar.style.display = ''; document.images.view_avatar.src = '".IMAGES."avatars/'+this.options[this.selectedIndex].value;  document.user_avatar.disabled = true;\"
                class='textbox'>\n";
			echo "<option value=''>-- Select Here --</option>\n";
			$dir_avatar = opendir(IMAGES."avatars/");
			while($ima_ava = readdir($dir_avatar)){
				if($ima_ava != "." and $ima_ava != ".." and $ima_ava != "index.html" and $ima_ava != "index.php") echo "<option value='".$ima_ava."'>".$ima_ava."</option>\n";
			}
			closedir($dir_avatar);
			echo "</select><br /><br /><span style='height: 250px'><img name='view_avatar' style='display: none' border=1 class='textbox'></span>";
			echo "<br /><br /><strong>".$locale['u051']."</strong><br >\n<input size='45' type='file' name='user_avatar' class='textbox' /><br />\n";
			echo "<span class='small2'>".$locale['u011']."</span><br />\n";
			echo "<span class='small2'>".sprintf($locale['u012'], parsebytesize(51200), 160, 160)."</span></td>\n";
			echo "</tr>\n";
                } else {
                        if(stristr($user_data['user_avatar'], "[".$user_data['user_id']."].")) {
                                $user_avatar = "user_avatars/".$user_data['user_avatar'];
                        } else $user_avatar = "avatars/".$user_data['user_avatar'];
                        echo "<tr>\n";
                        echo "<td valign='top' class='tbl'>".$locale['u010'].":</td>\n";
                        echo "<td class='tbl'><img src='".IMAGES.$user_avatar."' alt='".$locale['u010']."' /><br />\n";
                        echo "<input type='checkbox' name='del_avatar' value='y' /> ".$locale['u013']."\n";
                        echo "<input type='hidden' name='user_avatar' value='".$user_data['user_avatar']."' /></td>\n";
                        echo "</tr>\n";
                }
                $profile_method = "input";
                $result2 = dbquery("SELECT * FROM ".DB_USER_FIELDS." WHERE field_group != '4' GROUP BY field_group");
                while($data2 = dbarray($result2)) {
                        $result3 = dbquery("SELECT * FROM ".DB_USER_FIELDS." WHERE field_group='".$data2['field_group']."' ORDER BY field_order");
                        if (dbrows($result3)) {
                                echo "<tr>\n<td class='tbl2'></td>\n";
                                echo "<td class='tbl2'><strong>";
                                if ($data2['field_group'] == 1) {
                                        echo $locale['u044'];
                                } elseif ($data2['field_group'] == 2) {
                                        echo $locale['u045'];
                                } elseif ($data2['field_group'] == 3) {
                                        echo $locale['u046'];
                                }
                                echo "</strong></td>\n</tr>\n";
                                while($data3 = dbarray($result3)) {
                                        if (file_exists(LOCALE.LOCALESET."user_fields/".$data3['field_name'].".php")) {
                                                include LOCALE.LOCALESET."user_fields/".$data3['field_name'].".php";
                                        }
                                        if (file_exists(INCLUDES."user_fields/".$data3['field_name']."_include.php")) {
                                                include INCLUDES."user_fields/".$data3['field_name']."_include.php";
                                        }
                                }
                        }
                }
                if(iADMIN && checkrights("UG")){
			echo "<tr>\n<td align='left' class='tbl2'>Nhóm thành viên</td>\n<td class='tbl2'>";
                        $user_groups2 = (strpos($user_data['user_groups'], ".") == 0 ? explode(".", substr($user_data['user_groups'], 1)) : explode(".", $user_data['user_groups']));
			for ($i = 0; $i < count($user_groups2); $i++) {
				echo getgroupname($user_groups2[$i])."; ";
			}
			$result = dbquery("SELECT * FROM ".DB_USER_GROUPS." ORDER BY group_id ASC");

			echo "</td>\n";
                }
                echo "<tr>\n<td align='center' colspan='2' class='tbl'><br />\n";
                echo "<input type='hidden' name='user_hash' value='".$user_data['user_password']."' />\n";
                echo "<input type='submit' name='savechanges' value='".$locale['440']."' class='button' /></td>\n";
                echo "</tr>\n</table>\n</form>\n";
                closetable();
                echo "<div align=right><a href='".FUSION_SELF."?step=delete&user_id=".$_GET['user_id']."'>Xoa thanh vien ".$user_data['user_name']."</a></div>";
        }
} elseif ($_GET['step'] == "delete" && isnum($_GET['user_id'])) {
                //$udata = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
                $result = dbquery("delete from qlt_users where user_id=".$_GET['user_id']." and donvi=$madonvi");
                redirect(FUSION_SELF);
        }	else { // liet ke danh sach nhan vien
        	opentable("Danh sach giao vien, can bo, nhan vien");
		$result=dbquery("select * from qlt_users where donvi=".$userdata['donvi']);//lay danh sach   giao vien
		$rows=dbrows($result);
        echo "<div style='text-align:center;margin-bottom:10px;'>\n";
        echo "<a href='".FUSION_SELF."?step=add'>Thêm GV-CB-NV</a>\n";
        echo "</div>\n";
        if ($rows) {
                $i = 0;
                echo "<table class='mau center'>\n<tr>\n";
		echo "<td class='tbl3'><strong>Mã số</strong></td>\n";
                echo "<td class='tbl3'><strong>Họ và tên</strong></td>\n";
                 echo "<td class='tbl3'><strong>Ten ngan</strong></td>\n";
                echo "<td align='center'  class='tbl3' style='white-space:nowrap'><strong>".$locale['403']."</strong></td>\n";
		echo "<td align='center'  class='tbl3' style='white-space:nowrap'><strong>Nhóm</strong></td>\n";
		echo "<td align='center' class='tbl3' style='white-space:nowrap'><strong>Last visited</strong></td>\n";
                echo "</tr>\n";
                while ($data = dbarray($result)) {
                        $cell_color = ($i % 2 == 0 ? "tbl1" : "tbl3");
                        if($_GET['status'] == 2) {
                                $user_info = unserialize($data['user_info']);
                                echo "<tr>\n<td class='$cell_color'>".$data['user_id']."</td><td class='$cell_color'>".$user_info['user_name']."</td><td align='center' class='$cell_color'>####</td>
                                <td align='center' class='$cell_color'>
                                        <a title='Kich hoat thanh vien nay' href='".FUSION_SELF."?step=activate&amp;".$list_link."&amp;status=".$_GET['status']."&amp;rowstart=".$_GET['rowstart']."&amp;user_code=".$data['user_code']."'>Activate</a>
                                </td></tr>";
                        } else {
                                echo "<tr>\n<td class='$cell_color'>".$data['user_id']."</td><td class='$cell_color'><a href='".FUSION_SELF."?step=edit&amp;user_id=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
                                echo "<td align='center' class='$cell_color' style='white-space:nowrap'>".$data['short_name']."</td>\n";
                                echo "<td align='center' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n";	
				echo "<td  class='$cell_color'>";
				echo getgroups($data['user_groups']);
					
				echo "</td>\n<td  class='$cell_color' align=center>";
				echo showdate("%d-%m-%y", $data['user_lastvisit']);
				echo"</td></tr>\n"; $i++;
                        }
                }
                echo "</table>\n";
        }
        closetable();
        if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?sortby=$sortby&amp;")."\n</div>\n";
}

require_once THEMES."templates/footer.php";
?>
