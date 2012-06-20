<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

cache_smileys();
$smiley_images = array();
foreach ($smiley_cache as $smiley) {
        $smiley_images["smiley_".$smiley['smiley_text']] = IMAGES."smiley/".$smiley['smiley_image'];
}

$result = dbquery("SELECT admin_title, admin_image FROM ".DB_ADMIN);
$ac_images = array();
while($data = dbarray($result)){
        $ac_images["ac_".$data['admin_title']] = file_exists(ADMIN."images/".$data['admin_image']) ? ADMIN."images/".$data['admin_image'] : ADMIN."images/infusion_panel.gif";
}

$images = array(
        //A
        //B
        "blank" => THEME."images/blank.gif",
        //C
        //D
        "down" => THEME."images/down.gif",
        //E
        "edit" => BASEDIR."images/edit.gif",
        //F
        "folder" => THEME."forum/folder.gif",
        "folderlock" => THEME."forum/folderlock.gif",
        "foldernew" => THEME."forum/foldernew.gif",
        "forum_edit" => THEME."forum/edit.gif",
        //G
        //H
        //I
        "imagenotfound" => IMAGES."imagenotfound.jpg",
        //J
        //K
        //L
        "left" => THEME."images/left.gif",
        //M
        //N
        "newthread" => THEME."forum/newthread.gif",
        "navbits_start" => THEME."forum/navbits_start.gif",
        "navbits_finallink" => THEME."forum/navbits_finallink.gif",
        //O
        //P
        "panel_on" => THEME."images/panel_on.gif",
        "panel_off" => THEME."images/panel_off.gif",
        "pm" => THEME."forum/pm.gif",
        "pollbar" => THEME."images/pollbar.gif",
        "printer" => THEME."images/printer.gif",
        "post_title" => THEME."forum/post_title.gif",
        //Q
        "quote" => THEME."forum/quote.gif",
        //R
        "reply" => THEME."forum/reply.gif",
        "right" => THEME."images/right.gif",
        //S
        "star" => IMAGES."star.gif",
        "stickythread" => THEME."forum/stickythread.gif",
        "tree" => THEME."forum/tree.gif",
        //T
        //U
        "up" => THEME."images/up.gif",
        //V
        //W
        "web" => THEME."forum/web.gif"
        //X
        //Y
        //Z
);

$fusion_images = array_merge($ac_images, $images, $smiley_images);

function get_image($image, $alt = "", $style = "", $title = "", $atts = "") {
        global $fusion_images;
        if (isset($fusion_images[$image])) {
                $url = $fusion_images[$image];
        } else {
                $url = BASEDIR."images/not_found.gif";
        }
        if (!$alt && !$style && !$title) {
                return $url;
        } else {
                return "<img src='".$url."' alt='".$alt."'".($style ? " style='$style'" : "").($title ? " title='".$title."'" : "")." ".$atts." />";
        }
}

function set_image($name, $new_dir){
        global $fusion_images;
        $fusion_images[$name] = $new_dir;
}

function redirect_img_dir($source, $target){
        global $fusion_images;
        $new_images = array();
        foreach ($fusion_images as $name => $url) {
                $new_images[$name] = str_replace($source, $target, $url);
        }
        $fusion_images = $new_images;
}
?>
