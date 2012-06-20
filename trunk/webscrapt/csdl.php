<?php
function getIPv4() {
	$ip;
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else
		$ip = "UNKNOWN";
	return $ip;
} 

function nhatki($page, $extra) {
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	$dbname = 'csdl';
	mysql_select_db($dbname);
	mysql_query("INSERT INTO visitors (ipv4, time, page,extra)
	VALUES ('".getIPv4()."', ".time().", '".$page."','".$extra."')");
	mysql_close($conn);
}
?>
