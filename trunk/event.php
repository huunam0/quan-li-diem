<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
//echo '<link rel="alternate" type="application/rss+xml" title="" href="../view_rss.php" />';
//echo "<script type='text/javascript' src='".INCLUDES."refresh.js'></script>\n";


//echo $userdata['user_id'];

if (isset($_GET['date'])) {
	$cdate=$_GET['date'];
	$cyear=intval(substr($cdate,0,4));
	$cmonth=intval(substr($cdate,4,2));
	if ($cmonth<1 || $cmonth>12) $cmonth=1;
	$cday=intval(substr($cdate,6,2));
	if ($cday<1 || $cday>numberOfDays($cyear,$cmonth)) $cday=1;
} else {
	$ctoday=getdate();
	$cyear=$ctoday['year'];
	$cmonth=$ctoday['mon'];
	$cday=$ctoday['mday'];
	
}
$cdate= YYMMDD($cyear,$cmonth,$cday);

if (isset($_POST['addevent'])) { //Add new event
	$result=dbquery("insert into events (event_title,event_location,event_date,event_date_create,event_day_before,event_author,event_date_begin,event_group) value ('".stripinput($_POST['eventtitle'])."','".$_POST['eventlocation']."','".(isset($_POST['hangnam'])?substr($cdate,4,4):$cdate)."',".$today.",'".$_POST['eventbeforeday']."',".$userdata['user_id'].",'".addDate($cdate,-$_POST['eventbeforeday'])."','".$_POST['eventgroup']."')");
	redirect(FUSION_SELF."?date=".$cdate);
} elseif (isset($_GET['act'])) { // execute some works
	if (($_GET['act']=="delete")  && isset($_GET['event'])) { //Delete an event
		$result=dbquery("delete  from events where event_id=".$_GET['event']." and event_author=".$userdata['user_id']);
		redirect(FUSION_SELF."?date=$cdate");
	} elseif (($_GET['act']=="edit")  && isset($_GET['event'])) { //Edit an event
		$event_id=$_GET['event'];
		if (isset($_POST['update'])) {
			if (isset($_POST['eventyear']) && isset($_POST['eventmonth']) && isset($_POST['eventday'])) {
				$new_event_date = right(trim($_POST['eventyear']),4).right(trim($_POST['eventmonth']),2).right(trim($_POST['eventday']),2);
				$result=dbquery("update events set event_title='".stripinput($_POST['eventtitle'])."',event_location='".$_POST['eventlocation']."',event_date='".$new_event_date."', event_day_before='".$_POST['eventbeforeday']."',event_date_begin='".addDate($cdate,-$_POST['eventbeforeday'])."',event_group='".$_POST['eventgroup']."' where event_id=".$event_id);
			} else {
			//echo $new_event_date;
				$result=dbquery("update events set event_title='".stripinput($_POST['eventtitle'])."',event_location='".$_POST['eventlocation']."', event_day_before='".$_POST['eventbeforeday']."',event_date_begin='".addDate($cdate,-$_POST['eventbeforeday'])."',event_group='".$_POST['eventgroup']."' where event_id=".$event_id);
			}
			redirect(FUSION_SELF."?date=$cdate",5);
			//header("refresh: 3; url=".MYSELF."?date=".$cdate);
		} else {
			$result=dbquery("select * from events where event_id=".$_GET['event']." and event_author=".$userdata['user_id']);
			if (dbrows($result)) {
				$data=dbarray($result);
				opentable("Sửa sự kiện"); //edit events
				echo "<form method='post' action='".FUSION_SELF."?date=$cdate&act=edit&event=".$event_id."'><table class='center'>";
				echo "<tr><td align=right>Sự kiện</td><td><input type='text' name='eventtitle' value= '".$data['event_title']."' size='50px'/></td></tr>";
				echo "<tr><td align=right>Địa điểm</td><td><input type='text' name='eventlocation' value= '".$data['event_location']."'/></td></tr>";
				echo "<tr><td align=right>Báo trước</td><td><input type='text' name='eventbeforeday' value='1' value= '".$data['event_day_before']."' size='10px'/>ngày</td></tr>";
				/*
				echo "<tr><td align=right>$wo9</td><td><select name='eventgroup' /><option value='0'></option>";
				$result2 = dbquery("select groups.*,users.user_name FROM groups left join users on groups.group_author=users.user_id where group_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+group_id+'.')>0 ");
				if (dbrows($result2)) {
					while ($data2=dbarray($result2)) {
						echo "<option value='".$data2['group_id']."' ".($data2['group_id']==$data['event_group']?"selected":"").">".$data2['user_name'].".".$data2['group_name']."</option>";
					}
				}
				echo "</select></td></tr>";
				*/
				echo "<tr><td align=right><i>Di chuyển đến </td><td>ngày<input type='text' name='eventday' size='1px'/>tháng<input type='text' name='eventmonth'  size='1px'/>năm<input type='text' name='eventyear' size='2px'/></td></tr>";
				echo "<tr><td></td><td><input type='submit' name='update' value='Cập nhật'/></td></tr>";
				echo "</table></form>";
				echo "<a href='".FUSION_SELF."?date=$cdate&act=delete&event=".$data['event_id']."'>Xóa sự kiện này</a> ";
				closetable();
				
			} else {
				echo "Không tìm thấy sự kiện";
			}
		}
	} 
	//redirect("".MYSELF."?date=$cdate");
} else { //Display des events
	$cond=($userdata['user_id']?"(event_author=".$userdata['user_id']." or event_author=1)  ":"event_author=1 ");
	
	if (isset($_GET['date'])) {
		$cdate=$_GET['date'];
		opentable("Sự kiện, ngày ".DateFormat($cdate,"DDMMMYY"));
		$result=dbquery("select * from events where $cond and  ((event_date>='".$cdate."' and event_date_begin<='".$cdate."') or event_date='".substr($cdate,4,4)."')  order by event_date");
	} elseif (isset($_GET['month'])) {
		$cdate=$_GET['month'];
		opentable("Sự kiện, ".DateFormat($cdate."00","MMYY"));
		$result=dbquery("select * from events where $cond and (left(event_date,6) = '$cdate' or left(event_date,2) = '".substr($cdate,4,2)."') order by event_date");
	} elseif (isset($_GET['week'])) {
		$cdate=$_GET['week'];
		opentable("Sự kiện, tuần ".DateFormat($cdate));
		$result=dbquery("select * from events where $cond and if(event_date>'20',event_date,CONCAT('$current_year',event_date)) between '".$cdate."' and '".addDate($cdate,6)."' order by event_date");
	} else {
		//redirect("index.php");
	}
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
					echo "<td align=center>".$i++."</td><td><a href='".FUSION_SELF."?date=$cdate&act=edit&event=".$data['event_id']."'>".$data['event_title']."</a></td><td>".$data['event_location']."</td><td>".DateFormat($data['event_date'])."</td><td><font color=#999999>".$data['event_group']."</td><td><font color=#999999>".getuser($data['event_author'])."</td><td><font color=#999999>".DateFormat($data['event_date_create'])."</td>";
					echo "</tr>";
				}
			}
			echo "</table>";
		} else {
			echo "Không có sự kiện nào";
		}
	closetable();
	echo "<br><br><br>";
	if (($userdata['user_id']!=0) && (isset($_GET['date']))){
		opentable("Thêm sự kiện cho ngày ".DateFormat($cdate)); //Add new events
			echo "<form method='post' action='".FUSION_SELF."?date=$cdate'><table class='center'>";
			echo "<tr><td align=right>Sự kiện</td><td><input type='text' name='eventtitle' size='50px'/></td></tr>";
			echo "<tr><td align=right>Địa điểm</td><td><input type='text' name='eventlocation'/></td></tr>";
			echo "<tr><td align=right>Báo trước </td><td><input type='text' name='eventbeforeday' value='1' size='10px'/>ngày</td></tr>";
			echo "<tr><td align=right></td><td>Lặp lại hàng năm <input type='checkbox' name='hangnam' /></td></tr>";
			/*
			echo "<tr><td align=right>Chia se cho nhom</td><td><select name='eventgroup' /><option value='0'></option>";
			$result = dbquery("select qlt_user_groups.*,qlt_users.user_name FROM qlt_user_groups left join qlt_users on groups.group_author=users.user_id where group_author=".$userdata['user_id']." or instr('".$userdata['user_groups']."','.'+group_id+'.')>0 ");
			if (dbrows($result)) {
				while ($data=dbarray($result)) {
					echo "<option value='".$data['group_id']."'>".$data['user_name'].".".$data['group_name']."</option>";
				}
			}
			echo "</select></td></tr>";
			*/
			echo "<tr><td></td><td align=center><input type='submit' name='addevent' value='Thêm sự kiện'/></td></tr>";
			echo "</table></form>";
		closetable();
	}
}
//echo "<a href='index.htm' onmouseover='Tip(&quot;Some text&quot;)' onmouseout='UnTip()'>Homepage </a>";
require_once THEMES."templates/footer.php";
?>
