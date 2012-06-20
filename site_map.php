<?
require_once "maincore.php";
require_once THEMES."templates/header.php";

include LOCALE.LOCALESET."site_map.php";
opentable($locale['SM100'].": ".$settings['sitename']);

//custom pages
$result = dbquery("SELECT * FROM ".DB_CUSTOM_PAGES." ORDER BY page_title ASC");
$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM123']."</b>";
        echo "<div style='margin: 7px; font-weight:500; word-spacing: 1; line-height: 180%;'>";
        while ($data = dbarray($result)) {
                echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."viewpage.php?page_id=".$data['page_id']."'>".$data['page_title']."</a><br  />";
        }
        echo "</div>";
}
//faq cats
if (!defined("LANGUAGE")) {
        $result = dbquery("SELECT * FROM ".DB_FAQ_CATS." ORDER BY faq_cat_name ASC");
}
$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM124']."</b>";
        echo "<div style='margin: 7px; font-weight:500; word-spacing: 1; line-height: 180%;'>";
        while ($data = dbarray($result)) {
      if (!defined("LANGUAGE")) {
        $faqcats_count=dbcount("(*)",DB_FAQS,"faq_cat_id=".$data['faq_cat_id']);
      } else {
              $res = dbarray(dbquery("
              SELECT Count(*) AS num FROM ".DB_FAQS." ff
              INNER JOIN ".DB_FAQ_CATS." fc ON fc.faq_cat_id = ff.faq_cat_id
              WHERE ff.faq_cat_id='".$data['faq_cat_id']."'
              "));
              $faqcats_count = $res['num'];
           }
                echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."faq.php?cat_id=".$data['faq_cat_id']."'>".$data['faq_cat_name']."</a> - <span class='small'>[".$faqcats_count."]</span><br  />";
        }
        echo "</div>";
}
//faqs
if (!defined("LANGUAGE")) {
   $result = dbquery("SELECT * FROM ".DB_FAQS."");
} else {
   $result = dbquery("
   SELECT * FROM ".DB_FAQS." ff
   INNER JOIN ".DB_FAQ_CATS." fc ON fc.faq_cat_id = ff.faq_cat_id
   ");
}
$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM125']."</b>";
        echo "<div style='margin: 7px; font-weight:500; word-spacing: 1; line-height: 180%;'>";
        while ($data = dbarray($result)) {
                echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."faq.php?cat_id=".$data['faq_cat_id']."'>".$data['faq_question']."</a><br  />";
        }
        echo "</div>";
}

//forums cats
$result = dbquery("SELECT * FROM ".DB_FORUMS." WHERE forum_cat=0 ORDER BY forum_order ASC");

$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM130']."</b>";
        echo "<div style='margin: 7px; font-weight:500; word-spacing: 1; line-height: 180%;'>";
        while ($data = dbarray($result)) {
                $forums_count = dbcount("(*)", DB_FORUMS, "forum_cat='".$data['forum_id']."'");
                echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."forum/viewforum.php?forum_id=".$data['forum_id']."'>".$data['forum_name']."</a> - <span class='small'>[".$locale['SM142'].": ".$forums_count."]</span><br  />";
        }
        echo "</div>";
}
//forums
$result = dbquery("SELECT * FROM ".DB_FORUMS." WHERE forum_cat!=0 ORDER BY forum_order ASC");

$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM131']."</b>";
        echo "<div style='margin: 7px; font-weight:500; word-spacing: 1; line-height: 180%;'>";
        while ($data = dbarray($result)) {
                $threads_count = dbcount("(*)", DB_THREADS, "forum_id='".$data['forum_id']."'");
                echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> <a href='".BASEDIR."forum/viewforum.php?forum_id=".$data['forum_id']."'>".$data['forum_name']."</a> - <span class='small'>[".$locale['SM143'].": ".$threads_count."]</span><br  />";
        }
        echo "</div>";
}
//polls
$result = dbquery("SELECT * FROM ".DB_POLLS." ORDER BY poll_started DESC");

$rows = dbrows($result);
if ($rows!=0) {
   echo "<b>".$locale['SM132']."</b>";
        echo "<div style='margin: 7px; font-weight:500; word-spacing: 1; line-height: 180%;'>";
        while ($data = dbarray($result)) {
                $polls_count = dbcount("(*)",DB_POLL_VOTES,"poll_id=".$data['poll_id']);
                echo "<img src='".THEME."images/bullet.gif' alt='bullet' /> ".$data['poll_title']." - <span class='small'>[".$locale['SM144'].": ".$polls_count."]</span><br  />";
        }
        echo "</div>";
}

closetable();

require_once THEMES."templates/footer.php";
?>
