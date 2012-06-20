<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if (!iMEMBER || !isset($_POST['user_hash']) || $_POST['user_hash'] != $user_data['user_password']) { redirect("index.php"); }

$error = ""; $db_values = ""; $set_avatar = "";

$user_name = trim(eregi_replace(" +", " ", $_POST['user_name']));
$user_email = trim(stripinput($_POST['user_email']));
$user_new_password = trim(stripinput($_POST['user_new_password']));
$user_new_password2 = trim(stripinput($_POST['user_new_password2']));

if (iADMIN) {
        $user_new_admin_password = trim(stripinput($_POST['user_new_admin_password']));
        $user_new_admin_password2 = trim(stripinput($_POST['user_new_admin_password2']));
} else {
        $user_new_admin_password = "";
}

if ($user_name == "") {
        $error .= $locale['430']."<br />\n";
} else {
        //if (preg_check("/^[-0-9A-Z_@\s]+$/i", $user_name)) {
                if ($user_name != $user_data['user_name']) {
                        $result = dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_name='".$user_name."' AND user_id<>'".$userdata['user_id']."'");
                        if (dbrows($result)) {
                                $error .= $locale['432']."<br />\n";
                        }
                }
        //} else {
        //        $error .= $locale['431']."<br />\n";
        //}
if ($user_email) {
        if (preg_check("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $user_email)) {
                if ($user_email != $user_data['user_email']) {
                        if ((isset($_POST['user_password'])) && md5(md5($_POST['user_password'])) == $user_data['user_password']) {
                                $result = dbquery("SELECT user_email FROM ".DB_USERS." WHERE user_email='".$user_email."'");
                                if (dbrows($result)) {
                                        $error .= $locale['434']."<br />\n";
                                }
                        } else {
                                $error .= $locale['437']."<br />\n";
                        }
                }
        } else {
                $error .= $locale['433']."<br />\n";
        }
	}
}

if ($user_new_password) {
        if ((isset($_POST['user_password'])) && md5(md5($_POST['user_password'])) == $user_data['user_password']) {
                if ($user_new_password2 != $user_new_password) {
                        $error .= $locale['435']."<br />";
                } else {
                        if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $user_new_password)) {
                                $error .= $locale['436']."<br />\n";
                        }
                        if ((md5(md5($user_new_password)) == md5(md5($user_new_admin_password))) || (md5(md5($user_new_password)) == $user_data['user_admin_password'])) {
                                $error .= $locale['439']."<br><br>\n";
                        }
                }
        } else {
                $error .= $locale['437']."<br />\n";
        }
}

if (iADMIN && $user_new_admin_password) {
        if ($user_data['user_admin_password']) {
                if ((!isset($_POST['user_admin_password'])) || md5(md5($_POST['user_admin_password'])) != $user_data['user_admin_password']) {
                        $error .= $locale['441']."<br />\n";
                }
        }
        if (!$error) {
                if ($user_new_admin_password2 != $user_new_admin_password) {
                        $error .= $locale['438']."<br />";
                } else {
                        if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $user_new_admin_password)) {
                                $error .= $locale['440']."<br />\n";
                        }
                        if ((md5(md5($user_new_admin_password)) == md5(md5($user_new_password))) || (md5(md5($user_new_admin_password)) == $user_data['user_password'])) {
                                $error .= $locale['439']."<br><br>\n";
                        }
                }
        }
}

$user_hide_email = isnum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";

if (!$error) {
        if($_POST['avatar_list'] != ""){
                $set_avatar = ", user_avatar='".$_POST['avatar_list']."'";
        } else if(!$user_data['user_avatar'] && !empty($_FILES['user_avatar']['name']) && is_uploaded_file($_FILES['user_avatar']['tmp_name'])) {
                $newavatar = $_FILES['user_avatar'];
                $avatarext = strrchr($newavatar['name'],".");
                $avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
                $avatarname = $avatarname."[".$userdata['user_id']."]".$avatarext;
                move_uploaded_file($newavatar['tmp_name'], IMAGES."user_avatars/".$avatarname);
                chmod(IMAGES."user_avatars/".$avatarname,0644);
                $set_avatar = ", user_avatar='".$avatarname."'";
                if ($size = getimagesize(IMAGES."user_avatars/".$avatarname)) {
                        if ($size['0'] > 161 || $size['1'] > 161) {
                                unlink(IMAGES."user_avatars/".$avatarname);
                                $set_avatar = "";
                        }
                } else {
                        unlink(IMAGES."user_avatars/".$avatarname);
                        $set_avatar = "";
                }
        }

        if (isset($_POST['del_avatar'])) {
                if(stristr($userdata['user_avatar'], "[".$userdata['user_id']."].")) @unlink(IMAGES."user_avatars/".$userdata['user_avatar']);
                $set_avatar = ", user_avatar=''";
        }

        $result = dbquery("SELECT * FROM ".DB_USER_FIELDS." ORDER BY field_order");
        if (dbrows($result)) {
                $profile_method = "validate_update";
                while($data = dbarray($result)) {
                        if (file_exists(LOCALE.LOCALESET."user_fields/".$data['field_name'].".php")) {
                                include LOCALE.LOCALESET."user_fields/".$data['field_name'].".php";
                        }
                        if (file_exists(INCLUDES."user_fields/".$data['field_name']."_include.php")) {
                                include INCLUDES."user_fields/".$data['field_name']."_include.php";
                        }
                }
        }

        if ($user_new_password) { $new_pass = " user_password='".md5(md5($user_new_password))."', "; } else { $new_pass = " "; }
        if (iADMIN && $user_new_admin_password) { $new_admin_pass = " user_admin_password='".md5(md5($user_new_admin_password))."', "; } else { $new_admin_pass = " "; }

        $result = dbquery("UPDATE ".DB_USERS." SET user_name='$user_name',".$new_pass.$new_admin_pass."user_email='$user_email', user_hide_email='$user_hide_email'".($set_avatar ? $set_avatar : "").$db_values." WHERE user_id='".$user_data['user_id']."'");
        $_SESSION['user_password']=md5(md5($user_new_password));
		redirect("edit_profile.php?update_profile=ok");
		
		//redirect("setuser.php?logout=yes&return_lnk=index.php");

} else {
        echo "<div style='text-align:center'><strong>".$locale['412']."</strong><br />\n".$error."<br />\n</div>\n";
}
?>
