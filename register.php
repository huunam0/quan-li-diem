<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."register.php";
include LOCALE.LOCALESET."user_fields.php";

if (iMEMBER || !$settings['enable_registration']) { redirect("index.php"); }

if (isset($_GET['activate'])) {
//Da dang ki va kich hoat
        if (!preg_check("/^[0-9a-z]{32}$/", $_GET['activate'])) { redirect("index.php"); }
        $result = dbquery("SELECT * FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['activate']."'");
        if (dbrows($result)) {
                $data = dbarray($result);
                $user_info = unserialize($data['user_info']);
                $user_status = $settings['admin_activation'] == "1" ? "2" : "0";
                                
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
                
                $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status".$db_fields.") VALUES('".$user_info['user_name']."', '".$user_info['user_password']."', '', '".$user_info['user_email']."', '".$user_info['user_hide_email']."', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$user_status'".$db_values.")");
                $result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['activate']."'");      
                add_to_title($locale['global_200'].$locale['401']);
                opentable($locale['401']);
                if ($settings['admin_activation'] == "1") {
                        echo "<div style='text-align:center'><br />\n".$locale['455']."<br /><br />\n".$locale['453']."<br /><br />\n</div>\n";
                } else {
                        echo "<div style='text-align:center'><br />\n".$locale['455']."<br /><br />\n".$locale['452']."<br /><br />\n</div>\n";
                }
                closetable();
        } else {
                redirect("index.php");
        }
} elseif (isset($_POST['register'])) {
//dang ki chua kich hoat
        $error = ""; $db_fields = ""; $db_values = "";
        $username = stripinput(trim(eregi_replace(" +", " ", $_POST['username'])));
        $email = stripinput(trim(eregi_replace(" +", "", $_POST['email'])));
		//$email = $username.MAIL_DOMAINE;
        $password1 = stripinput(trim(eregi_replace(" +", "", $_POST['password1'])));
        
        if ($username == "" || $password1 == "" || $email == "") {
                $error .= $locale['402']."<br />\n";
        }
        
        //if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
                //$error .= $locale['403']."<br />\n";
        //}
        
        if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
                if ($password1 != $_POST['password2']) $error .= $locale['404']."<br />\n";
        } else {
                $error .= $locale['405']."<br />\n";
        }
 
        if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
                $error .= $locale['406']."<br />\n";
        }
        //Kiem tra danh sach den
        $email_domain = substr(strrchr($email, "@"), 1);
        $result = dbquery("SELECT * FROM ".DB_BLACKLIST." WHERE blacklist_email='$email' OR blacklist_email='$email_domain'");
        if (dbrows($result) != 0) { $error = $locale['411']."<br />\n"; }
        //KT trung username
        $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='$username'");
        if (dbrows($result) != 0) { $error = $locale['407']."<br />\n"; }
        //KT trung email
        $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_email='$email'");
        if (dbrows($result) != 0) { $error = $locale['408']."<br />\n"; }
        //Dang nam trong danh sach cho kich hoat
        if ($settings['email_verification'] == "1") {
                $result = dbquery("SELECT * FROM ".DB_NEW_USERS);
                while ($new_users = dbarray($result)) {
                        $user_info = unserialize($new_users['user_info']); 
                        if ($new_users['user_email'] == $email) { $error = $locale['409']."<br />\n"; }
                        if ($user_info['user_name'] == $username) { $error = $locale['407']."<br />\n"; break; }
                }
        }
        
        if ($settings['display_validation'] == "1") {
                if (!check_captcha($_POST['captcha_encode'], $_POST['captcha_code'])) {
                        $error .= $locale['410']."<br />\n";
                }
        }
        
        $user_hide_email = isnum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
        
        if ($settings['email_verification'] == "0") {
                $user_offset = isset($_POST['user_offset']) ? is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0" : "0";
                                
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
        }
        
        if ($error == "") {
                if ($settings['email_verification'] == "1") {
                        require_once INCLUDES."sendmail_include.php";
                        mt_srand((double)microtime()*1000000); $salt = "";
                        for ($i = 0; $i <= 7; $i++) { $salt .= chr(rand(97, 122)); }
                        $user_code = md5($email.$salt);
                        $activation_url = $settings['siteurl']."register.php?activate=".$user_code;
                        if (mail($email,$settings['siteusername'], $locale['449'].$locale['450'].$activation_url)) {
                                $user_info = serialize(array(
                                        "user_name" => $username,
                                        "user_password" => md5(md5($password1)),
                                        "user_email" => $email,
                                        "user_hide_email" => isnum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1"
                                ));
                                $result = dbquery("INSERT INTO ".DB_NEW_USERS." (user_code, user_email, user_datestamp, user_info) VALUES('$user_code', '".$email."', '".time()."', '$user_info')");
                                opentable($locale['400']);
                                echo "<div style='text-align:center'><br />\n".$locale['454']."<br /><br />\n</div>\n";
                                closetable();
                        } else {
                                opentable($locale['456']);
                                echo "<div style='text-align:center'><br />\n".$locale['457']."<br /><br />\n</div>\n";
                                closetable();
                        }
                } else {
                        $user_status = $settings['admin_activation'] == "1" ? "2" : "0";
                        $result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status".$db_fields.") VALUES('$username', '".md5(md5($password1))."', '', '".$email."', '$user_hide_email', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '$user_status'".$db_values.")");
                        opentable($locale['400']);
                        if ($settings['admin_activation'] == "1") {
                                echo "<div style='text-align:center'><br />\n".$locale['451']."<br /><br />\n".$locale['453']."<br /><br />\n</div>\n";
                        } else {
                                echo "<div style='text-align:center'><br />\n".$locale['451']."<br /><br />\n".$locale['452']."<br /><br />\n</div>\n";
                        }
                        closetable();
                }
        } else {
                opentable($locale['456']);
                echo "<div style='text-align:center'><br />\n".$locale['458']."<br /><br />\n$error<br />\n<a href='".FUSION_SELF."'>".$locale['459']."</a></div></br>\n";
                closetable();
        }
} else {
//Chua dang ki
        if ($settings['email_verification'] == "0") {
                $offset_list = "";
                for ($i = -13; $i < 17; $i++) {
                        if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
                        $offset_list .= "<option".($offset == "0" ? " selected='selected'" : "").">".$offset."</option>\n";
                }
        }
        opentable($locale['400']);
        echo "<div style='text-align:center'>".$locale['500']."\n";
        if ($settings['email_verification'] == "1") echo $locale['501']."\n";
        echo $locale['502'];
        if ($settings['email_verification'] == "1") echo "\n".$locale['503'];
        echo "</div><br />\n";
        echo "<form name='inputform' method='post' action='".FUSION_SELF."' onsubmit='return ValidateForm(this)'>\n";
        
		echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
        echo "<td class='tbl'>".$locale['u001']."<span style='color:#ff0000'>*</span></td>\n";
        echo "<td class='tbl'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;' /> <i>Nickname hoặc tên của bạn. Ví dụ: 'LENA' hoặc 'Hữu Nam',...</td>\n";
		echo "</tr>\n<tr>\n";
        echo "<td class='tbl'>".$locale['u002']."<span style='color:#ff0000'>*</span></td>\n";
        echo "<td class='tbl'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;' /> <i>Mật khẩu của bạn: Gồm chữ cái và chữ số, tối thiểu 6 kí tự.</td>\n";
		echo "</tr>\n<tr>\n";
        echo "<td class='tbl'>".$locale['u004']."<span style='color:#ff0000'>*</span></td>\n";
        echo "<td class='tbl'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;' /> <i>Gõ lại giống hệt như trên </td>\n";
		echo "</tr>\n<tr>\n";
        echo "<td class='tbl'>".$locale['u005']."<span style='color:#ff0000'>*</span></td>\n";
        echo "<td class='tbl'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px;' /> <i>E-mail của bạn. Phải chính xác, đặc biệt hữu ích trong trường hợp quên mật khẩu.</i></td>\n";
		echo "</tr>\n<tr>\n";
        echo "<td class='tbl'>".$locale['u006']."</td>\n";
        echo "<td class='tbl'><label><input type='radio' name='user_hide_email' value='1' />".$locale['u007']."</label>\n";
        echo "<label><input type='radio' name='user_hide_email' value='0' checked='checked'/>".$locale['u008']."</label></td>\n";
        echo "</tr>\n";
        if ($settings['display_validation'] == "1") {
                echo "<tr>\n<td class='tbl'>".$locale['504']."</td>\n<td class='tbl'>";
                echo make_captcha();
                echo "</td>\n</tr>\n<tr>";
                echo "<td class='tbl'>".$locale['505']."<span style='color:#ff0000'>*</span></td>\n";
                echo "<td class='tbl'><input type='text' name='captcha_code' class='textbox' style='width:100px' /></td>\n";
                echo "</tr>\n";
        }
        if ($settings['email_verification'] == "0") {
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
                                        $profile_method = "input";
                                        if (file_exists(LOCALE.LOCALESET."user_fields/".$data3['field_name'].".php")) {
                                                include LOCALE.LOCALESET."user_fields/".$data3['field_name'].".php";
                                        }
                                        if (file_exists(INCLUDES."user_fields/".$data3['field_name']."_include.php")) {
                                                include INCLUDES."user_fields/".$data3['field_name']."_include.php";
                                        }
                                }
                        }
                }
        }
        
        if ($settings['enable_terms'] == 1) {
                echo "<tr>\n<td colspan='2' class='tbl1'>";
                echo $locale['508'].":<span style='color:#ff0000'>*</span>\n";
                echo "<hr />".$settings['license_agreement']."\n</td>\n</tr>\n";
                echo "<tr>\n<td class='tbl' colspan=2><input type='checkbox' id='agreement' name='agreement' value='1' onclick='checkagreement()' /> <span class='small'><label for='agreement'>".$locale['509'] ."</label></span></td>\n";
                echo "</tr>\n";
        }
        echo "<tr>\n<td align='center' colspan='2'><br />\n";
        echo "<input type='submit' name='register' value='".$locale['506']."' class='button'".($settings['enable_terms'] == 1 ? " disabled='disabled'" : "")." />\n";
        echo "</td>\n</tr>\n</table>\n</form>\n";
        closetable();
        echo "<script type='text/javascript'>
function ValidateForm(frm) {
        if (frm.username.value==\"\") {
                alert(\"".$locale['550']."\");
                return false;
        }
        if (frm.password1.value==\"\") {
                alert(\"".$locale['551']."\");
                return false;
        }
        if (frm.email.value==\"\") {
                alert(\"".$locale['552']."\");
                return false;
        }
}
</script>\n";

        if ($settings['enable_terms'] == 1) {
                echo "<script language='JavaScript' type='text/javascript'>
                        function checkagreement() {
                                if(document.inputform.agreement.checked) {
                                        document.inputform.register.disabled=false;
                                } else {
                                        document.inputform.register.disabled=true;
                                }
                        }
                </script>";
        }
}

require_once THEMES."templates/footer.php";
?>