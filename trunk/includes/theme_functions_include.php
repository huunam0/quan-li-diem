<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

function check_panel_status($side) {
        
        global $settings;
        
        $exclude_list = "";
        
        if ($side == "left") {
                if ($settings['exclude_left'] != "") {
                        $exclude_list = explode("\r\n", $settings['exclude_left']);
                }
        } elseif ($side == "upper") {
                if ($settings['exclude_upper'] != "") {
                        $exclude_list = explode("\r\n", $settings['exclude_upper']);
                }
        } elseif ($side == "lower") {
                if ($settings['exclude_lower'] != "") {
                        $exclude_list = explode("\r\n", $settings['exclude_lower']);
                }
        } elseif ($side == "right") {
                if ($settings['exclude_right'] != "") {
                        $exclude_list = explode("\r\n", $settings['exclude_right']);
                }
        }
        
        if (is_array($exclude_list)) {
                $script_url = explode("/", $_SERVER['PHP_SELF']);
                $url_count = count($script_url);
                $base_url_count = substr_count(BASEDIR, "/")+1;
                $match_url = "";
                while ($base_url_count != 0) {
                        $current = $url_count - $base_url_count;
                        $match_url .= "/".$script_url[$current];
                        $base_url_count--;
                }
                if (!in_array($match_url, $exclude_list) && !in_array($match_url.(FUSION_QUERY ? "?".FUSION_QUERY : ""), $exclude_list)) {
                        return true;
                } else {
                        return false;
                }
        } else {
                return true;
        }
}

function showbanners() {
        global $settings;
        ob_start();
        if ($settings['sitebanner2']) {
                eval("?><div style='float: right;'>".stripslashes($settings['sitebanner2'])."</div>\n<?php ");
        }
        if ($settings['sitebanner1']) {
                eval("?>".stripslashes($settings['sitebanner1'])."\n<?php ");
        } elseif ($settings['sitebanner']) {
                echo "<a href='".$settings['siteurl']."'><img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' style='border: 0;' /></a>\n";
        } else {
                echo "<div style='padding: 10px'><a href='".$settings['siteurl']."'><strong>".$donvi."</strong></a></div>\n";
        }       
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
}

function showsublinks($sep = "&middot;", $class = "") {
        $sres = dbquery(
                "SELECT link_window, link_visibility, link_url, link_name,logo FROM ".DB_SITE_LINKS."
                WHERE ".groupaccess('link_visibility')." AND link_position>='2' AND link_url!='---' ORDER BY link_order ASC"
        );
        if(dbrows($sres)) {
                $i = 0;
                $res = "";
                while ($sdata = dbarray($sres)) {
                        $link_target = $sdata['link_window'] == "1" ? " target='_blank'" : "";
                        $link_url = explode(".",$sdata['link_url']);
                        $link_url = $link_url[0];
                        //$image_sublink = IMAGES.(stristr($link_url, "/") ? basename($link_url) : $link_url).".gif";
						$image_sublink = IMAGES.($sdata['logo']);
                        //$image_sublink = str_replace(array("?","&",),"_",$image_sublink);
                        if (strstr($sdata['link_url'], "http://") || strstr($sdata['link_url'], "https://")) {
                                $res .= "<td align='center'><strong><a style='font-size: 9px' href='".$sdata['link_url']."'$link_target>";
                                $res .= "<img  style='FILTER: alpha(opacity=60);-moz-opacity: 1.0; opacity: 1.0;' onmouseover='BeginOpacity(this,60,100)' onmouseout='EndOpacity(this,60)' src='$image_sublink' title='".$sdata['link_name']."' border=0><br /><font color='red'>".$sdata['link_name']."</font></a></strong></td>\n";
                        } else {
                                $res .= "<td align='center'><strong><a style='font-size: 9px' href='".BASEDIR.$sdata['link_url']."'$link_target>";
                                $res .= "<img  style='FILTER: alpha(opacity=60);-moz-opacity: 1.0; opacity: 1.0;' onmouseover='BeginOpacity(this,60,100)' onmouseout='EndOpacity(this,60)' src='$image_sublink' title='".$sdata['link_name']."' border=0><br /><font color='red'>".$sdata['link_name']."</font></a></strong></td>\n";
                        }
                        $i++;
                }
                return $res;
        }
}

function showsubdate() {
        global $settings;
        return ucwords(showdate($settings['subheaderdate'], time()));
}

function newsposter($info,$sep = "",$class = "") {
        global $locale;
        $res = "";
        $link_class = $class ? " class='$class' " : "";
        $res = THEME_BULLET." <a href='profile.php?lookup=".$info['user_id']."'".$link_class.">".$info['user_name']."</a> ";
        $res .= $locale['global_071'].showdate("longdate", $info['news_date']);
        $res .= $info['news_ext'] == "y" || $info['news_allow_comments'] ? $sep."\n" : "\n";
        return "<!--news_poster-->".$res;
}

function newsopts($info, $sep, $class = "") {
        global $locale; $res = "";
        $link_class = $class ? " class='$class' " : "";
        if (!isset($_GET['readmore']) && $info['news_ext'] == "y") $res = "<a href='news.php?readmore=".$info['news_id']."'".$link_class.">".$locale['global_072']."</a> ".$sep." ";
        if ($info['news_allow_comments']) $res .= "<a href='news.php?readmore=".$info['news_id']."#comments'".$link_class.">".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a> ".$sep." ";
        if ($info['news_ext'] == "y" || $info['news_allow_comments']) $res .= $info['news_reads'].$locale['global_074']."\n";
        $res .= $sep." <a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".get_image("printer")."' alt='".$locale['global_075']."' style='vertical-align:middle;border:0;' /></a>\n";
        return "<!--news_opts-->".$res;
}

function itemoptions($item_type, $item_id) {
        global $locale, $aidlink; $res = "";
        if ($item_type == "N") {
                if (iADMIN && checkrights($item_type)) { $res .= "<!--article_news_opts--> &middot; <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
        } elseif ($item_type == "A") {
        if (iADMIN && checkrights($item_type)) { $res .= "<!--article_admin_opts--> &middot; <a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$item_id."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' style='vertical-align:middle;border:0;' /></a>\n"; }
        }
        return $res;
}

function showcounter() {
        global $locale,$settings;
        return "<!--counter-->".($settings['counter'] == 1 ? $locale['global_170'] : $locale['global_171'])." ".number_format($settings['counter']);
}

function panelbutton($state, $bname) {
   if (isset($_COOKIE["fusion_box_".$bname])) {
      if ($_COOKIE["fusion_box_".$bname] == "none") {
         $state = "off";
      } else {
         $state = "on";
      }
   }
   return "<img src='".get_image("panel_".($state == "on" ? "off" : "on"))."' id='b_$bname' class='panelbutton' alt='' onclick=\"javascript:flipBox('$bname')\" />";
}

function panelstate($state, $bname) {
   if (isset($_COOKIE["fusion_box_".$bname])) {
      if ($_COOKIE["fusion_box_".$bname] == "none") {
         $state = "off";
      } else {
         $state = "on";
      }
   }
   return "<div id='box_$bname'".($state == "off" ? " style='display:none'" : "").">\n";
}

// v6 compatibility
function opensidex($title, $state = "on") {
        
        openside($title, true, $state);

}

function closesidex() {

        closeside();

}

function tablebreak() {
        return true;
}
?>
