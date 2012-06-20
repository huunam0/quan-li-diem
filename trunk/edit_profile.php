<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."edit_profile.php";
include LOCALE.LOCALESET."user_fields.php";

if (!iMEMBER) { redirect("index.php"); }

$user_data = $userdata;

if (isset($_POST['update_profile'])) { require_once INCLUDES."update_profile_include.php"; }

require_once INCLUDES."bbcode_include.php";
opentable($locale['400']);
$offset_list = "";
for ($i = -13; $i < 17; $i++) {
        if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
        $offset_list .= "<option".($offset == $user_data['user_offset'] ? " selected='selected'" : "").">".$offset."</option>\n";
}
echo "<form name='inputform' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
echo "<table cellpadding='0' cellspacing='0' class='center' width='100%'>\n";
if (isset($_GET['update_profile'])) {
        echo "<tr>\n<td align='center' colspan='2' class='tbl'>".$locale['411']."<br />\n</td>\n</tr>\n";
} elseif (!isset($_POST['update_profile'])) {
        echo "<tr>\n<td align='center' colspan='2' class='tbl'><font color='red'>".$locale['410']."<br />\n</td>\n</tr>\n";
}
echo "<tr>\n<td class='tbl'>".$locale['u001'].":</td>\n";
echo "<td class='tbl'><input type='hidden' name='user_name' value='".$user_data['user_name']."'/>\n";
echo "<strong>".$user_data['user_name']."</strong> <img src='".IMAGES.$user_data['user_level'].".gif' border=0></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['420'].":</td>\n";
echo "<td class='tbl'><input type='password' name='user_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['u003'].":</td>\n";
echo "<td class='tbl'><input type='password' name='user_new_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['u004'].":</td>\n";
echo "<td class='tbl'><input type='password' name='user_new_password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
if (iADMIN) {
        if ($user_data['user_admin_password']) {
                echo "<td class='tbl2'><font color='blue'>".$locale['421'].":</td>\n";
                echo "<td class='tbl2'><input type='password' name='user_admin_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
                echo "</tr>\n<tr>\n";
        }
        echo "<td class='tbl2'><font color='blue'>".$locale['422'].":</td>\n";
        echo "<td class='tbl2'><input type='password' name='user_new_admin_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n<tr>\n";
        echo "<td class='tbl2'><font color='blue'>".$locale['423'].":</td>\n";
        echo "<td class='tbl2'><input type='password' name='user_new_admin_password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
        echo "</tr>\n<tr>\n";
}
echo "<td class='tbl'>".$locale['u005'].":<span style='color:#ff0000'>*</span></td>\n";
echo "<td class='tbl'><input type='text' name='user_email' value='".$user_data['user_email']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['u006'].":</td>\n";
echo "<td class='tbl'><label><input type='radio' name='user_hide_email' value='1'".($user_data['user_hide_email'] == "1" ? " checked='checked'" : "")." />".$locale['u007']."</label> ";
echo "<label><input type='radio' name='user_hide_email' value='0'".($user_data['user_hide_email'] == "0" ? " checked='checked'" : "")." />".$locale['u008']."</label></td>\n";
echo "</tr>\n";

if (!$user_data['user_avatar']) {
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
        echo "<input type='checkbox' id='del_avatar' name='del_avatar' value='y' /><label for='del_avatar'> ".$locale['u013']."</label>\n";
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

echo "<tr>\n<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='hidden' name='user_hash' value='".$user_data['user_password']."' />\n";
echo "<input type='submit' name='update_profile' value='".$locale['424']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
