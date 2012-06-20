<?php   

require("xstring.php");

$a= getTagByName("<a> bc<b>xyz</b>","b");
echo $a." with ".strlen($a);

?>
