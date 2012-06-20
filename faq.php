<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."faq.php";

add_to_title($locale['global_203']);

if (!isset($_GET['cat_id']) || !isnum($_GET['cat_id'])) {
        opentable($locale['400']);
        $result = dbquery("SELECT * FROM ".DB_FAQ_CATS." ORDER BY faq_cat_name");
        $rows = dbrows($result);
        if ($rows) {
                $columns = 2; $counter = 0;
                echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl'>\n<tr>\n";
                while($data = dbarray($result)) {
                        if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
                        $num = dbcount("(faq_id)", DB_FAQS, "faq_cat_id='".$data['faq_cat_id']."'");
                        echo "<td valign='top'><a href='".FUSION_SELF."?cat_id=".$data['faq_cat_id']."'>".$data['faq_cat_name']."</a> <span class='small2'>($num)</span>\n";
                        if ($data['faq_cat_description']) { echo "<br />\n<span class='small'>".$data['faq_cat_description']."</span>"; }
                        echo "</td>\n";
                        $counter++;
                }
                echo "</tr>\n</table>\n";
        } else {
                echo "<div style='text-align:center'><br />\n".$locale['410']."<br /><br />\n</div>\n";
        }
        closetable();
} else {
        if ($data = dbarray(dbquery("SELECT * FROM ".DB_FAQ_CATS." WHERE faq_cat_id='".$_GET['cat_id']."'"))) {
                add_to_title($locale['global_201'].$data['faq_cat_name']);
                opentable($locale['401'].": ".$data['faq_cat_name']);
                $rows = dbcount("(faq_id)", DB_FAQS, "faq_cat_id='".$_GET['cat_id']."'");
                if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
                if ($rows != 0) {
                        $result = dbquery("SELECT * FROM ".DB_FAQS." WHERE faq_cat_id='".$_GET['cat_id']."' ORDER BY faq_id LIMIT ".$_GET['rowstart'].",15");
                        $numrows = dbrows($result);
                        $i = 1;
                        while ($data = dbarray($result)) {
                                echo "<strong><font color='red'>".$data['faq_question']."</font></strong><div style='padding: 20px; text-align: jutisfy'>\n".nl2br(stripslashes($data['faq_answer']))."</div>";
                                echo ($i != $numrows ? "<br />\n" : "\n");
                                $i++;
                        }
                        closetable();
                        if ($rows != 0) { echo "<div align='center' style='margin-top:5px;'>".makepagenav($_GET['rowstart'], 15, $rows, 3, FUSION_SELF."?cat_id=".$_GET['cat_id']."&amp;")."\n</div>\n"; }
                } else {
                        echo $locale['411']."\n";
                        closetable();
                }
        } else {
                redirect(FUSION_SELF);
        }
}

require_once THEMES."templates/footer.php";
?>
