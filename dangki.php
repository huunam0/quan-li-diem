<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//include LOCALE.LOCALESET."forum/main.php";

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

if (isset($userdata['user_id'])) {
	echo "Ban phai log out truoc khi tiep tuc";
} else {
	opentable("Dang ki admin moi");
	echo "Dang ki moi";
	closetable();
}
//echo $userdata['user_password'];
require_once THEMES."templates/footer.php";
?>
