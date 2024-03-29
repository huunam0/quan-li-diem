<?php
if (!defined("IN_FUSION")) { die("Access Denied"); }

include LOCALE.LOCALESET."search/custompages.php";

if ($_GET['stype'] == "custompages" || $_GET['stype'] == "all") {
        $sortby = "page_title";
        $ssubject = search_querylike("page_title");
        $smessage = search_querylike("page_content");
        if ($_GET['fields'] == 0) {
                $fieldsvar = search_fieldsvar($ssubject);
        } else if ($_GET['fields'] == 1) {
                $fieldsvar = search_fieldsvar($smessage);
        } else if ($_GET['fields'] == 2) {
                $fieldsvar = search_fieldsvar($ssubject, $smessage);
        }
        $result = dbquery("SELECT * FROM ".DB_CUSTOM_PAGES." WHERE ".groupaccess('page_access')." AND ".$fieldsvar);     
        $rows = dbrows($result);
        if ($rows != 0) {
                $items_count .= THEME_BULLET."&nbsp;<a href='".FUSION_SELF."?stype=custompages&amp;stext=".$_GET['stext']."&amp;".$composevars."'>".($rows == 1 ? $locale['c401'] : $locale['c402'])." ".$rows." ".$locale['522']."</a><br />\n";
                $result = dbquery(
                        "SELECT * FROM ".DB_CUSTOM_PAGES."
                        WHERE ".groupaccess('page_access')." AND ".$fieldsvar."
                        ORDER BY ".$sortby." ".($_GET['order'] == 1 ? "ASC" : "DESC").($_GET['stype'] != "all" ? " LIMIT ".$_GET['rowstart'].",10" : "")
                );       
                while ($data = dbarray($result)) {
                        $search_result = "";
                        $text_all = stripslashes($data['page_content']);
                        ob_start();
                        eval ("?>".$text_all."<?php ");
                        $text_all = ob_get_contents();
                        ob_end_clean();
                        $text_all = search_striphtmlbbcodes($text_all);
                        $text_frag = search_textfrag($text_all);
                        $subj_c = search_stringscount($data['page_title']);
                        $text_c = search_stringscount($text_all);
                        $text_frag = highlight_words($swords, $text_frag);
                        $search_result .= "<a href='viewpage.php?page_id=".$data['page_id']."'>".highlight_words($swords, $data['page_title'])."</a>"."<br /><br />\n";
                        $search_result .= "<div class='quote' style='width:auto;height:auto;overflow:auto'>".$text_frag."</div><br />\n";
                        $search_result .= "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['520'] : $locale['521'])." ".$locale['c403']." ".$locale['c404'].", ";
                        $search_result .= $text_c." ".($text_c == 1 ? $locale['520'] : $locale['521']).$locale['c403']." ".$locale['c405']."</span><br /><br />\n";
                        search_globalarray($search_result);
                }
        } else {
                $items_count .= THEME_BULLET."&nbsp;".$locale['c402']." 0 ".$locale['522']."<br />\n";
        }

        $navigation_result = search_navigation($rows);
}
?>
