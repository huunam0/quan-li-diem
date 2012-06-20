<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

define("CONTENT", ob_get_contents());
ob_end_clean();
render_page(false);
echo "<script type='text/javascript' src='".INCLUDES."vietkey.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."equation/js/render.js'></script>\n";
echo "\n ";
echo "<script>";
if (iADMIN) $result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_datestamp < '".(time()-1209600)."'");

$output = ob_get_contents();
ob_end_clean();
echo handle_output($output);

if(ob_get_length () !== FALSE){
        ob_end_flush();
}
mysql_close();
?>
