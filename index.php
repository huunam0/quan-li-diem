<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//include LOCALE.LOCALESET."admin/members.php";
//include LOCALE.LOCALESET."user_fields.php";

$cond=($userdata['user_id']?"(event_author=".$userdata['user_id']." or event_author=1)  ":"event_author=1 ");
		opentable("Sự kiện, ngày ".DateFormat($today));
		$result=dbquery("select * from events where $cond and  event_date>='".$today."' and event_date_begin<='".$today."' order by event_date");

	//echo "select * from events where $cond and  event_date>='".$cdate."' and event_date_begin<='".$cdate."' order by event_date";
	if (!isset($userdata['user_id'])) $userdata['user_id']=0;
	 //The events in that day
		//$result=dbquery("select * from events where event_author=".$userdata['user_id']." and event_date>='".$cdate."' and event_date_begin<='".$cdate."' order by event_date");
		
		if (dbrows($result)) {
			//echo "So luong su kien $slsk";
			echo "<table class='mau center'><tr align=center><td><b>STT</td><td><b>Sự kiện</td><td><b>Địa điểm</td><td><b>Thời điểm</td><td>Nhóm</td><td>Người đăng</td><td>Ngày đăng</td></tr>";
			$i=1;
			while ($data=dbarray($result)) {
				 if ($data['event_date']==$today) {
					 echo "<tr class='redalert'>";
				 } else {
					 echo "<tr class='info0'>";
				 }
				if ($data['event_author']!=$userdata['user_id']) { //commun event
					echo "<td align=center>".$i++."</td><td>".$data['event_title']."</td><td></td><td>".DateFormat($data['event_date'])."</td><td></td><td></td><td></td><td></td></tr>";
				} else {
					echo "<td align=center>".$i++."</td><td><a href='event.php?date=$today&act=edit&event=".$data['event_id']."'>".$data['event_title']."</a></td><td>".$data['event_location']."</td><td>".DateFormat($data['event_date'])."</td><td><font color=#999999>".$data['event_group']."</td><td><font color=#999999>".getuser($data['event_author'])."</td><td><font color=#999999>".DateFormat($data['event_date_create'])."</td>";
					echo "</tr>";
				}
			}
			echo "</table>";
		} else {
			echo "Không có sự kiện nào";
		}
	closetable();
require_once THEMES."templates/footer.php";
?>
