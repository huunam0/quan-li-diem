<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";

add_to_title($locale['global_200'].$locale['global_100']);

if (iMEMBER) {
        opentable($locale['global_193']." ".$userdata['user_name']);
        echo "<div style='text-align:center'><br />\n";
        $msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
        echo THEME_BULLET." <a href='".BASEDIR."edit_profile.php' class='side'>".$locale['global_120']."</a><br />\n";
        echo THEME_BULLET." <a href='".BASEDIR."messages.php' class='side'>".$locale['global_121']."</a><br />\n";
        echo THEME_BULLET." <a href='".BASEDIR."members.php' class='side'>".$locale['global_122']."</a><br />\n";
        if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
                echo THEME_BULLET." <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['global_123']."</a><br />\n";
        }
        echo THEME_BULLET." <a onClick='return logout()' href='".BASEDIR."setuser.php?logout=yes' class='side'>".$locale['global_124']."</a>\n";
        if ($msg_count) { echo "<br /><br /><strong><a href='".BASEDIR."messages.php' class='side'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</a></strong>\n"; }
        echo "<br /><br /></div>\n";
} else {
        opentable($locale['global_100']);
        echo "<div style='text-align:center'><br />\n";
        echo "<form name='loginform' method='post' action='".FUSION_SELF."'>\n";
        echo $locale['global_101']."<br />\n<input type='text' name='user_name' class='textbox' style='width:100px' /><br />\n";
        echo $locale['global_102']."<br />\n<input type='password' name='user_pass' class='textbox' style='width:100px' /><br />\n";
        echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' /><br />\n";
        echo "<br /></form>\n";
        if ($settings['enable_registration']) {
                echo "".$locale['global_105']."<br /><br />\n";
        }
        echo $locale['global_106'];
        echo "<br /><br /></div>\n";
}
closetable();

require_once THEMES."templates/footer.php";
?>
