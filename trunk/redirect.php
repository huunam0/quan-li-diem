<?php
/* 	Tu dong chuyen trang sang mot Website ben ngoai
	Viet boi Admin
*/
require_once "maincore.php";
require_once THEMES."templates/header.php";
$lnk = $_GET['url']."&url=";
foreach($_GET as $key => $value) $url .= "&".$key."=".$value;
$full_lnk = str_replace($lnk,"",$url);
?>
<meta http-equiv="refresh" content="3;<?php echo $full_lnk; ?>">
<br /><br/><br/>

<center>


<br/><br/>

Đang chuyển hướng đến trang <br /><strong><?=$full_lnk;?></strong><br />Bạn vui lòng đợi ít giây.
<br />
<br /><img src="images/loading_reg.gif" border=0><br />
Nếu trình duyệt không tự chuyênr. Bạn có thể <a href='<?=$full_lnk?>'>Click vào đây</a>
<br/>
......

</center>
</body>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6357219-1");
pageTracker._trackPageview();
} catch(err) {}</script>
