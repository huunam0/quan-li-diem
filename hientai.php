<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//echo '<link rel="alternate" type="application/rss+xml" title="" href="../view_rss.php" />';
echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";

//if (!iMEMBER) {
	//redirect("index.php");
//}
//echo $userdata['user_id'];

if (isset($_GET['hoanthanh'])) { //1 muc da hoan thanh - an di
	$result=dbquery("update hientai set hoanthanh=1 where nguoitao=".$userdata['user_id']." and id=".$_GET['hoanthanh']);
	redirect(FUSION_SELF);
} elseif (isset($_GET['tang'])) { //tang muc do uu tien
	$sxl=$_GET['tang'];
	echo $sxl;
	echo("update hientai set uutien=-uutien-1+2*".$sxl." where nguoitao=".$userdata['user_id']." and stt=".$sxl." or stt=".($sxl-1));
	$result=dbquery("update hientai set uutien=-uutien-1+2*".$sxl." where nguoitao=".$userdata['user_id']." and uutien=".$sxl." or uutien=".($sxl-1));
	redirect(FUSION_SELF);
} elseif (isset($_GET['giam'])) { //giam muc do uu tien
	$sxl=$_GET['giam'];
	$result=dbquery("update hientai set uutien=-uutien+1+2*".$sxl." where nguoitao=".$userdata['user_id']." and uutien=".$sxl." or uutien=".($sxl+1));
	redirect(FUSION_SELF);
} elseif (isset($_GET['xoa'])) { //xoa
	if (isset($_GET['stt'])) {
		$result=dbquery("delete from hientai where nguoitao=".$userdata['user_id']." and uutien=".$_GET['stt']." and id=".$_GET['xoa']);
		$result=dbquery("update hientai set uutien=uutien-1 where nguoitao=".$userdata['user_id']." and uutien>".$_GET['xoa']);
	}
	redirect(FUSION_SELF);
} else {
	if (isset($_POST['add'])) { //them moi
		$stt=dbcount2("hientai","nguoitao=".$userdata['user_id']);
		$stt++;
		$ctoday=getdate();
		$cdate=YYMMDD($ctoday['year'],$ctoday['mon'],$ctoday['mday']);
		$result=dbquery("insert into hientai (noidung,ngaytao,nguoitao,uutien) value ('".$_POST['noidung']."','".$cdate."',".$userdata['user_id'].",".$stt.")");
		redirect(FUSION_SELF);
	} else { //binh thuong
		$result=dbquery("select * from hientai where hoanthanh=0 and nguoitao=".$userdata['user_id']." order by uutien");
		opentable("Công việc hiện tại");
		if ($sl=dbrows($result)) { //co thong tin
			//echo "<table class='mau center'><tr align=center><td>STT</td><td>Noi dung</td><td>Ngay dang</td><td>Lua chon</td></tr>";
			while ($data=dbarray($result)) {
				echo "<br>".$data['noidung']." ";
				if ($data[uutien]<$sl)
				echo "<a href='".FUSION_SELF."?giam=".$data['uutien']."'><img alt='[xuống]' title='xuống' src='./images/down.gif'></a> ";
				if ($data[uutien]>1)
				echo "<a href='".FUSION_SELF."?tang=".$data['uutien']."'><img alt='[lên]' title='lên' src='./images/up.gif'></a> ";
				echo "<a href='".FUSION_SELF."?xoa=".$data['id']."&stt=".$data['uutien']."'><img alt='[xoá]' title='xoá' src='./images/delete.gif'></a> ";
				echo "<a href='".FUSION_SELF."?hoanthanh=".$data['id']."'><img alt='[hoàn thành]' title='hoàn thành' src='images/right.gif'></a> ";
				echo " <font color='white'>(".DateFormat($data['ngaytao']).")</font>";
			}
			
			//echo "</table>";
		} else {//khong co thong tin
			echo "Không hề có 1 công việc nào!";
		}
		echo "<br/><br/><hr/>Thêm ghi nhớ:<br>";
		echo "<form method='post' action='".FUSION_SELF."'>";
		echo "<input type='text' name='noidung' size='100px'/><input type='submit' name='add' value='Thêm vào'/>";
		echo "</form>";
		closetable();
	}
}

//echo "<a href='index.htm' onmouseover='Tip(&quot;Some text&quot;)' onmouseout='UnTip()'>Homepage </a>";
require_once THEMES."templates/footer.php";
?>
