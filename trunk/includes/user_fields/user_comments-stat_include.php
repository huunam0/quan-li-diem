<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

if ($profile_method == "input") {
        //Nothing here
} elseif ($profile_method == "display") {
        echo "<tr>\n";
        echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_comments-stat']."</td>\n";
        echo "<td align='right' class='tbl1'>".number_format(dbcount("(comment_id)", DB_COMMENTS, "comment_name='".$user_data['user_id']."'"))."</td>\n";
        echo "</tr>\n";
} elseif ($profile_method == "validate_insert") {
        //Nothing here
} elseif ($profile_method == "validate_update") {
        //Nothing here
}
?>
