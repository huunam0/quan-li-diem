<?php


if (isset($_GET['cd'])) {
	$cdto = "&cd=".$_GET['cd'];
} else {
	$cdto="";
}
//echo $userdata['user_amlich'];
function DisplayCalendar($month, $year,$cdto,$user_id=0) {
	
	$user_lang="vn";

	$user_amlich = 0;

	echo "<table border=0 width=100%>";
	echo "<tr  align=center><td><a href='event.php?cd=".($year-1).$month."' title='Năm trước'>|<</a></td>";
	echo "<td><a href='event.php?cd=".($month==1?($year-1)."12":$year."".($month-1))."' title='Tháng trước'><<</a></td>";
	echo "<td  ><b><a href='event.php?month=".$year.($month>9?"":"0").$month.$cdto."'>".$month." / ".$year."</a></td>";
	echo "<td><a href='event.php?cd=".($month==12?($year+1)."01":$year."".($month+1))."' title='Tháng sau'>>></a></td>";
	echo "<td><a href='event.php?cd=".($year+1).$month."' title='Năm sau'>>|</a></td></tr></table>";
	echo "<table class='mau2' width=100%><tr align=center class='tbl3'><td width=9%>W</td><td width=13%><b>T2</td><td width=13%><b>T3</td><td width=13%><b>T4</td>";
	echo "<td width=13%><b>T5</td><td width=13%><b>T6</td><td width=13%><b><font color=#ff00ff>T7</td><td width=13%><b><font color=red>CN</td></tr>";
	
	$fd = FirstDay($month, $year);
	$myear=$year.aSpase($month,2);
	
	if ($fd==0) $fd=7;
	if ($fd>1) {
		
		$d = numberOfDays($month-1, $year);
		$d=$d+2-$fd;
		echo "<tr align=center><td ><a href='event.php?week=".$myear.aSpase(1,2).$cdto."'>W</a></td>";
		for ($i=1; $i<$fd; $i++) {
			echo "<td><font color='gray'>".$d."</font></td>"; 
			$d++;
			}
	}
	$xtoday=getdate();
	$thangnay =(($xtoday['mon']==$month) &&( $xtoday['year']==$year))?1:0;
	$lastday = numberOfDays($month, $year);
	
	$tip="";
	$$ii=0;
	$chem="";
	for ($i=1; $i<=$lastday+15-$fd-($lastday % 7); $i++) {
		
		$ii++;
		if ($ii>$lastday) {
			$ii-=$lastday;
			$month++;
			$myear=$year.aSpase($month,2);
			$chem="<font size=1px><i>";
		}
		if (($i +$fd -1) % 7 ==1) echo "<tr align=center><td><a href='event.php?week=".$myear.aSpase($ii,2).$cdto."'>W</a></td>";
		if ($thangnay && $i==$xtoday['mday']) {
			$bkcolor = "#33ff55";
			$tip="<b><u>Hôm nay: </b></u> ";
			$addbefore="<font color=#0000ff><b>";
			$addafter="*</b></font>";
		} else {
			$bkcolor="#ffffff";
			$addbefore="<font color=#000000>";
			$addafter="</b></font>";
			$tip="<b><u>Ngày ".$ii."/".$month.":</b></u> ";
		}
		$curdate=YYMMDD($year,$month,$ii);
		if ($user_amlich) $tip.="(<i>".D2A($year, $month, $ii)."</i>)";
		//$tip=$curdate;
		//if ($userdata['user_id']>"0") { $user_id=$userdata['user_id'];} else {$user_id=0;}
			//$result=dbquery("select * from events where event_author=".$user_id." and event_date=".$curdate." ");
			$user_groups=dblookup("user_groups","users","user_id=".$user_id);
			$result = dbquery("select * from events where (event_author=".$user_id." or event_author=1 or instr('".$user_groups."','.'+event_group+'.')>0) and (event_date='".$curdate."' or event_date='".substr($curdate,4,4)."') order by event_date");
			if (dbrows($result)) {
				if ($bkcolor=="#ffffff")$bkcolor="#ffff00";
				$addbefore="<font color=#ff0000><b>";
				$j=1;
				$tiplen=10;
				while ($data=dbarray($result)) {
					$tip .= "<br>[".$j++."] ".$data['event_title'];
					$tmptitle=strlen(utf8_decode($data['event_title']))+4;
					//$tip.= $tmptitle;
					if ($tiplen<$tmptitle) $tiplen=$tmptitle;
				}
				if ($tiplen>40) $tiplen=40;
				$tiplen*=6;
			}
		//}
		echo "<td bgcolor='".$bkcolor."' ><a href='event.php?date=".$myear.aSpase($ii,2).$cdto."' ";
		echo  <<<NAM
		onmouseover="ddrivetip('$tip','#ffee66',$tiplen)"
NAM;
		echo " onmouseout='hideddrivetip()'>".$addbefore.$chem.$ii.$addafter."</a></td>";
		if (($i +$fd -1) % 7 ==0) echo "</tr>";
	}
	$d=1+$ii;
	// while (($i +$fd -1) % 7 !=0)  {
		// echo "<td>".$d."</td>";
		// $i++;
		// $d++;
		// }
	// echo "<td>".$d."</td>";
	echo "</table>";
}
openside("Lịch làm việc",true);
if (isset($_GET['cd'])) {
	$cyear=intval(substr($_GET['cd'],0,4));
	$cmonth=intval(substr($_GET['cd'],4,2));
	//DisplayCalendar(,,$userdata['user_id']);
} elseif (isset($_GET['date'])){
	$cdate=$_GET['date'];
	$cyear=intval(substr($cdate,0,4));
	$cmonth=intval(substr($cdate,4,2));
} else {
	$xtoday=getdate();
	$cyear=$xtoday['year'];
	$cmonth=$xtoday['mon'];
}
//echo $cmonth,$cyear;
DisplayCalendar($cmonth,$cyear,$cdto,($userdata['user_id']?$userdata['user_id']:0));
//echo "<a href='index.htm' onmouseover='Tip(\'Some text\')' onmouseout='UnTip()'>Homepage </a>";
echo "<a href='index.php'>Hôm nay: ".DateFormat($today)."</a>";
//echo $cdto;
closeside();


?>
