<?
//$hotimage = "20-11.gif";   
//$hottoppic_arr = array(11,83);   //Id cac bai viet muon hien thi Hot Topic
$hotimage = "";  
$hottoppic_arr = ""; 
if($hottoppic_arr != ""){
        $topic_id = "";
        for($i=0; $i<sizeof($hottoppic_arr); $i++){
            $topic_id .= "thread_id = ".$hottoppic_arr[$i]." OR ";
        }
        $topic_id = substr($topic_id,0,-4);
        $result = dbquery("SELECT thread_id, thread_subject FROM ".DB_THREADS." WHERE $topic_id");
        $hoptopic = "";
        $i = 0;
        if (dbrows($result)) {
                $hoptopic .= "<br />";
                $hoptopic .= "<table cellpadding='0' cellspacing='1' width='100%'                 
                $style_hotimage>\n<tr><td>\n";
                while($data = dbarray($result)) {
                        $itemsubject = trimlink($data['thread_subject'], 200);
                        $hoptopic .= "<span style='padding: 2px; text-align: left'><strong>
                    <marquee behavior='alternate' width='100%' onmouseout='this.start()' onmouseover='this.stop()'>
                    <img src='".IMAGES."new-icon.gif' width='32' height='28' alt='Hot topic' border=0>
                   <a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."' class='hosttopic'>$itemsubject</a></span></marquee>";
                        //$hoptopic .= ($i % 2 != 0 ? "<br />\n" : "");
                        $hoptopic .= "<br />";
                        $i++;
                }
        }
}
if($hotimage != "") {
        $style_hotimage = "style='background-image:url(".IMAGES.$hotimage."); background-repeat: no-repeat;'";
        $hoptopic .= "</td><td $style_hotimage width='50%'>&nbsp;</td></tr></table>\n";
        $hoptopic .= "<br />";
}
?>
