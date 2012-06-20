<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

if (iMEMBER) {
        include LOCALE.LOCALESET."search/members.php";

        if ($_GET['stype'] == "members" || $_GET['stype'] == "all") {
   $rows = dbcount("(user_id)", DB_USERS, "user_name LIKE '%".$_GET['stext']."%'");
   if ($rows != 0) {
                        $items_count .= THEME_BULLET."&nbsp;<a href='".FUSION_SELF."?stype=members&amp;stext=".$_GET['stext']."&amp;".$composevars."'>".$rows." ".($rows == 1 ? $locale['m401'] : $locale['m402'])." ".$locale['522']."</a><br />\n";
                        $result = dbquery("
                                SELECT * FROM ".DB_USERS."
                                WHERE user_name LIKE '%".$_GET['stext']."%'
                                ORDER BY user_name".($_GET['stype'] != "all" ? " LIMIT ".$_GET['rowstart'].",10" : "")
      );
                        while ($data = dbarray($result)) {
                                $search_result = "<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br />\n";
                                search_globalarray($search_result);
      }
                } else {
                        $items_count .= THEME_BULLET."&nbsp;".$locale['m402']." 0 ".$locale['522']."<br />\n";
                }
                $navigation_result = search_navigation($rows);
        }
}
?>
