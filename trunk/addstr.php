<?php
//addstr.php?ma=abc&str=xyz
require_once "config.php";
require_once "functions.php";
$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
mysql_query("SET NAMES utf8"); //connect in decode utf8
echo "<html><head></head><body>";
$result=dbquery("insert into luutru (ma,string) value ('".phpentities($_GET['ma'])."','".phpentities($_GET['str'])."')");
echo "Wellcome to my site! Hava a good day!";
//echo "<script>window.close();</script>";
//echo "<script>self.close();</script>";
echo "</body></html>";
?>
