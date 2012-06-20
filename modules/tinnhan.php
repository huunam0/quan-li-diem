<?php
openside((iMEMBER ? " <strong>".$userdata['user_name']."</strong>" : "Xin chào<b>, bạn là ai?</b>"),true);

if(!iMEMBER) {
	//echo $_SERVER['PHP_SELF'];
	//if ($_SERVER['PHP_SELF']=="index.php" or $_SERVER['PHP_SELF']=="/index.php") {
	echo "<form name='voteform' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>\n
<table><tr><td height='28px'><font color='red'>User ID: </td>
<td><input type='text' name='user_id' class='textbox' style='background-image:url(".IMAGES."u.gif); background-position:left; background-repeat:no-repeat ; height: 18px; width:100%; display:block; padding-left:18px; width: 120px;' /></td></tr>
<tr><td><font color='red'>".$locale['global_102']."</td>
<td><input type='password' name='user_pass' class='textbox' style='background-image:url(".IMAGES."p.gif); background-position:left; background-repeat:no-repeat ; height: 18px; width:100%; display:block; padding-left:18px; width:120px' /></td></tr>
<tr><td> </td><td align=center><input type='submit' name='login' class='button' value='Đăng nhập'><br /></td></tr>
<tr><td colspan=2><a style='font-size: 11px' href='".BASEDIR."lostpassword.php' class='side'>".$locale['global_108']."</a><input type='hidden' name='return_lnk' value='".($_GET['return_lnk'] == "" ? urlencode(FUSION_REQUEST) : $_GET['return_lnk'])."'></td></tr>
</table>";} //else {echo "<a href='".BASEDIR."'>Vào trang chủ để đăng nhập.</a>";}

else {
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
	echo "[<a href='messages.php'>".$locale['global_121']."</a>] \n";
	echo "[<a href='contact.php'>Sổ địa chỉ</a>] <br>\n";
	echo "[<a href='notes.php'>Ghi chú</a>] \n";
	echo "[<a onClick='return logout()' href='".BASEDIR."setuser.php?logout=yes&return_lnk=".urlencode(FUSION_REQUEST)."'>Thoát</a>]\n";
	if ($msg_count) {
		echo "<br><a href='".BASEDIR."messages.php' class=''>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</a>\n";
	} else {echo "<br>Không có tin nhắn mới.";}
	echo "<br><hr><span class='small'>";
	$result=dbquery("select t1.message_id, t1.message_subject, t1.message_message,t2.user_name from ".DB_MESSAGES." as t1 inner join ".DB_USERS." as t2 on t1.message_from=t2.user_id where message_to=".$userdata['user_id']." AND message_read=0 AND message_folder=0 limit 5");

	while ($data=dbarray($result)) {
$text=$data['message_message'];
$text = preg_replace('#\[(.*?)\]#si', '', $text);
//$text = left($text,30);
               echo "<a href='".BASEDIR."messages.php?folder=inbox&msg_read=".$data['message_id']."'>";
		echo "<font color=#ff0000>[".$data['user_name']."</font>] <font color=#0000ff><b>".trimlink($data['message_subject'],25)."</b></font></a><br><font color=#222222><i>".trimlink($text,80)."</i></font><hr>";
		}
	echo "</span>";
}

closeside();
?>
