<?php   
	require("xstring.php");
	require("sfunctions.php");
	require("csdl.php");
	if ($_POST['ok']) {
		$site=$_POST['site'];
		$v1=strpos($site,"&no=");
		if ($v1===false) {
			echo "bad link!";
		} else {
			$id=substr($site,$v1+4);
			$output = get_url_contents("http://www.missycoupons.com/chg/1/view/".getSubstringBetween($site,"id=","&")."/".$id);
			$title=getTagByName($output,"div");
			echo "<table border=1><tr><td>Link</td><td>";
			echo $_POST['site'];
			echo "</td></tr><tr><td>Title</td><td>";
			echo $title;
			echo "</td></tr><tr><td>Link(s)</td><td>";
			$table=getTagByName($output,"table");
			$table=getTagByName($table,"table",2);
			$link=getTagByName($table,"td");
			$v1=strpos($link,"SiteLink ");
			while (!($v1===false)) {
				$link=substr($link,$v1+10);
				$a=getTagByName($link,"a");
				echo $a."<br>";
				$v1=strpos($link,"SiteLink ");
			}
			echo "</td></tr><tr><td>Description</td><td>";
			$table=getTagByName($table,"table");
			$table=getTagByName($table,"table");
			$table=getTagByName($table,"tr",2);
			$table=getTagByName($table,"td");
			echo $table;
			echo "</td></tr><tr><td>Image</td><td>";
			$img=getTagByName($table,"div");
			$img=getSubstringBetween($img,"loadMemberImage","border");
			/*
			if ($img) {
				$add="http://i.missycoupons.com/mi3/164/";
				$add=$add.getSubstringBetween($img,"(",",")."/";
				$add=$add.getSubstringBetween($img,"'","'")."/";
				$add=$add.getSubstringBetween($img,",",",",2)."/";
				$add=$add.getSubstringBetween($img,",",",",3)."/";
				echo "<img src='".$add."'";
			}
			*/
			echo "</td></tr></table>";
			nhatki("nam3",$site);
		}
	} else {
		echo "<form method='post' action='nam3.php'><input type='text' size='80' name='site' value='http://www.missycoupons.com/zero/board.php#id=hotdeals&no=52471'><input type='submit' name='ok' value='GET'></form>";
	}
	echo "<hr>Written by Tran Huu Nam - huunam0@gmail.com ";
	
	
	  
?>
