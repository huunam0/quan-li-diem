<?php

if (!isset($_GET['user_id']) || !isnum($_GET['user_id'])) { redirect(FUSION_SELF.$aidlink); }

$error = ""; $db_values = ""; $set_avatar = "";

$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'");
$user_data = dbarray($result);

$user_name = trim(eregi_replace(" +", " ", $_POST['user_name']));

$user_new_password = trim(stripinput($_POST['user_new_password']));


//if ($user_name == "" || $user_email == "") {
if ($user_name == "") {
        $error .= $locale['451']."<br />\n";
} 

if ($user_new_password != "") {
       
                if ($_POST['user_hash'] == $user_data['user_password']) {
                        if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $user_new_password)) {
                                $error .= $locale['457']."<br />\n";
                        }
                } else {                        
                        $error .= $locale['458']."<br />\n";
                }
        }


$user_hide_email = "0";

if ($error == "") {
        if($_POST['avatar_list'] != ""){
                $set_avatar = ", user_avatar='".$_POST['avatar_list']."'";
        } else if(!$user_data['user_avatar'] && !empty($_FILES['user_avatar']['name']) && is_uploaded_file($_FILES['user_avatar']['tmp_name'])) {
                $newavatar = $_FILES['user_avatar'];
                $avatarext = strrchr($newavatar['name'],".");
                $avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
                $avatarname = $avatarname."[".$_GET['user_id']."]".$avatarext;
                move_uploaded_file($newavatar['tmp_name'], IMAGES."user_avatars/".$avatarname); //upload file
                chmod(IMAGES."user_avatars/".$avatarname,0644); //dat thuoc tinh
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
                if(stristr($user_data['user_avatar'], "[".$user_data['user_id']."].")) @unlink(IMAGES."user_avatars/".$user_data['user_avatar']);
                $set_avatar = ", user_avatar=''";
        }
        
       

        if ($user_new_password) { $new_pass = " user_password='".md5(md5($user_new_password))."', "; } else { $new_pass = " "; }
        if($_POST['user_group']) $user_group = " user_groups = '".$_POST['user_group']."', ";
        $shortname=($_POST['shortname']?$_POST['shortname']:getlastname($user_name));
        $result = @dbquery("UPDATE ".DB_USERS." SET user_name='$user_name',short_name='$shortname',$user_group".$new_pass."user_email='$user_email', user_hide_email='$user_hide_email'".($set_avatar ? $set_avatar : "").$db_values." WHERE user_id='".$user_data['user_id']."'");
}
?>
