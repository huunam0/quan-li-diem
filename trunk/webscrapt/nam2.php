<?php   
	require("xstring.php");
	require("sfunctions.php");
function get_url_contents($url){
        $crl = curl_init();
        $timeout = 5;
        curl_setopt ($crl, CURLOPT_URL,$url);
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
        $ret = curl_exec($crl);
        curl_close($crl);
        return $ret;
}
header('Content-Type: text/html; charset=utf-8');
	if ($_POST['ok']) {
		$id=$_POST['site'];
		$v1=strpos($id,"&no=");
		if ($v1===false) {
			echo "bad link!";
		} else {
			$id=substr($id,$v1+4);
			$output = get_url_contents("http://www.missycoupons.com/chg/1/view/hotdeals/".$id);
			//$output = file_get_contents("http://www.missycoupons.com/chg/1/view/hotdeals/".$id);

			$title=getTagByName($output,"div");
			echo "<table border=1><tr><td>Link</td><td>";
			echo $_POST['site'];
			echo "</td></tr><tr><td>Title</td><td>";
			echo $title;
			echo "</td></tr><tr><td>Description</td><td>";
			$table=getTagByName($output,"table");
			$table=getTagByName($table,"table",2);
			$table=getTagByName($table,"table");
			$table=getTagByName($table,"table");
			$table=getTagByName($table,"tr",2);
			$table=getTagByName($table,"td");
			echo $table;
			echo "</td></tr></table>";

//echo $output;
		}
	} else {
		echo "<form method='post' action='nam2.php'><input type='text' size='80' name='site' value='http://www.missycoupons.com/zero/board.php#id=hotdeals&no=52471'><input type='submit' name='ok' value='GET'></form>";
	}
	echo "<hr>Written by Tran Huu Nam - huunam0@gmail.com ";
	
	
	  
?>
