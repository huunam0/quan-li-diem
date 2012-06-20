<?php
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."admin/admins.php";

if ((!checkrights("AD")) && ($userdata['user_level']<102)) { redirect("index.php"); }


if (isset($_POST['cancel'])) {
        redirect(FUSION_SELF);
}

if (isset($_POST['add_admin']) && (isset($_POST['user_id']) && isnum($_POST['user_id']))) {
	if (isset($_POST['all_rights']) || isset($_POST['make_super'])) {
		$admin_rights = "";
		$result = dbquery("SELECT DISTINCT admin_rights AS admin_right FROM ".DB_ADMIN." ".($userdata['user_level']==102?" where admin_page>=4":"")." ORDER BY admin_right");
		while ($data = dbarray($result)) {
			$admin_rights .= (isset($admin_rights) ? "." : "").$data['admin_right'];
		}
		$result = dbquery("UPDATE ".DB_USERS." SET user_level='".(isset($_POST['make_super']) ? "103" : "102")."', user_rights='$admin_rights' WHERE user_id='".$_POST['user_id']."'");
	} else {
		$result = dbquery("UPDATE ".DB_USERS." SET user_level='102' WHERE user_id='".$_POST['user_id']."'");
	}
	
	redirect(FUSION_SELF."?status=sn", true);
}

if (isset($_GET['remove']) && (isset($_GET['remove']) && isnum($_GET['remove']) && $_GET['remove'] != 1)) {
	$result=dbquery("update qlt_users set user_level=101 where user_id=".$_GET['remove']);
}

if (isset($_POST['update_admin']) && (isset($_GET['user_id']) && isnum($_GET['user_id']) )) {
       
	$user_rights = dblookup("user_rights","qlt_users","user_id=".$_GET['user_id']);
       $result2 = dbquery("SELECT * FROM ".DB_ADMIN." ".($userdata['user_level']==102?" where admin_page>=4":"")." ORDER BY admin_page ASC,admin_title");
       if (dbrows($result2)) {
       	       while ($data2=dbarray($result)) {
       	       	       $user_rights = remove_right($user_rights,$data2['admin_rights']);
       	       }
       }
       if (isset($_POST['rights'])) {
		
		for ($i = 0;$i < count($_POST['rights']);$i++) {
			//$user_rights .= ($user_rights != "" ? "." : "").stripinput($_POST['rights'][$i]);
			$user_rights = add_right($user_rights,stripinput($_POST['rights'][$i]));
		}
		
	} 
	$result = dbquery("UPDATE ".DB_USERS." SET user_rights='$user_rights' WHERE user_id='".$_GET['user_id']."' AND user_level>='102'");
	redirect(FUSION_SELF);
       
}

if (isset($_GET['edit']) && isnum($_GET['edit']) && $_GET['edit'] != 1) {
        $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['edit']."' AND user_level>='102' ORDER BY user_id");
        if (dbrows($result)) {
                $data = dbarray($result);
                $user_rights = explode(".", $data['user_rights']);
                $result2 = dbquery("SELECT * FROM ".DB_ADMIN." ".($userdata['user_level']==102?" where admin_page>=4":"")." ORDER BY admin_page ASC,admin_title");
                opentable($locale['440']." [".$data['user_name']."]");
                $columns = 2; $counter = 0; $page = 1;
                $admin_page = array($locale['441'], $locale['442'], $locale['443'], $locale['444'],"Quản lí các báo cáo");
                echo "<form name='rightsform' method='post' action='".FUSION_SELF.$aidlink."&amp;user_id=".$_GET['edit']."'>\n";
                echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
                echo "<tr>\n<td colspan='2' bgcolor=#aaaaaa align=center>".$admin_page['0']."</td>\n</tr>\n<tr>\n";
                while ($data2 = dbarray($result2)) {
                        while ($page < $data2['admin_page']) {
                                echo ($counter % $columns == 0 ? "</tr>\n" : "<td width='50%' class='tbl1'></td>\n</tr>\n");
                                echo "<tr>\n<td colspan='2'  bgcolor=#aaaaaa align=center>".$admin_page[$page]."</td>\n</tr>\n<tr>\n";
                                $page++; $counter = 0;
                        }
						
                        if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
                        echo "<td width='50%' class='tbl1'><label><input type='checkbox' name='rights[]' value='".$data2['admin_rights']."'".(in_array($data2['admin_rights'], $user_rights) ? " checked='checked'" : "")." /> ".$data2['admin_title']."</label></td>\n";
                        $counter++;
                }
                echo "</tr>\n<tr>\n</table>\n";
                echo "<div style='text-align:center'><br />\n";
                echo "<input type='button' class='button' onclick=\"setChecked('rightsform','rights[]',1);\" value='".$locale['445']."' />\n";
                echo "<input type='button' class='button' onclick=\"setChecked('rightsform','rights[]',0);\" value='".$locale['446']."' /><br /><br />\n";
                
                echo "<input type='submit' name='update_admin' value='".$locale['448']."' class='button' />\n";
                echo "</div>\n</form>\n";
                closetable();
                echo "<script type='text/javascript'>"."\n"."function setChecked(frmName,chkName,val) {"."\n";
                echo "dml=document.forms[frmName];"."\n"."len=dml.elements.length;"."\n"."for(i=0;i < len;i++) {"."\n";
                echo "if(dml.elements[i].name == chkName) {"."\n"."dml.elements[i].checked = val;"."\n";
                echo "}\n}\n}\n</script>\n";
        }
} else {
        opentable($locale['410']);
        if (!isset($_POST['search_users']) || !isset($_POST['search_criteria'])) {
                echo "<form name='searchform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
                echo "<table cellpadding='0' cellspacing='0' width='450' class='center'>\n";
                echo "<tr>\n<td align='center' class='tbl'>".$locale['411']."<br /><br />\n";
                echo "<input type='text' name='search_criteria' class='textbox' style='width:300px' />\n</td>\n";
                echo "</tr>\n<tr>\n<td align='center' class='tbl'>\n";
                echo "<label><input type='radio' name='search_type' value='user_name'  />".$locale['413']."</label>\n";
                echo "<label><input type='radio' name='search_type' value='user_id' checked='checked' />".$locale['412']."</label></td>\n";
                echo "</tr>\n<tr>\n<td align='center' class='tbl'><input type='submit' name='search_users' value='".$locale['414']."' class='button' /></td>\n";
                echo "</tr>\n</table>\n</form>\n";
        } elseif (isset($_POST['search_users']) && isset($_POST['search_criteria'])) {
                $mysql_search = ""; 
                if ($_POST['search_type'] == "user_id" && isnum($_POST['search_criteria'])) {
                        $mysql_search .= "user_id='".$_POST['search_criteria']."' ";
                } elseif ($_POST['search_type'] == "user_name" && preg_match("/^[-0-9A-Z_@\s]+$/i", $_POST['search_criteria'])) {
                        $mysql_search .= "user_name LIKE '".$_POST['search_criteria']."%' ";
                }
                if ($mysql_search) {
                        $result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE ".$mysql_search." AND user_level='101' ORDER BY user_name");
                }
                if (isset($result) && dbrows($result)) {
                        echo "<form name='add_users_form' method='post' action='".FUSION_SELF.$aidlink."'>\n";
                        echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
                        $i = 0; $users = "";
                        while ($data = dbarray($result)) {
                                $row_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
                                $users .= "<tr>\n<td class='$row_color'><label><input type='radio' name='user_id' value='".$data['user_id']."' /> ".$data['user_name']."</label></td>\n</tr>";
                        }
                        if ($i > 0) {
                                echo "<tr>\n<td class='tbl2'><strong>".$locale['413']."</strong></td>\n</tr>\n";
                                echo $users."<tr>\n<td align='center' class='tbl'>\n";
                                echo "<label><input type='checkbox' name='all_rights' value='1' /> ".$locale['415']."</label><br />\n";
                                if ($userdata['user_level'] == 103) { echo "<label><input type='checkbox' name='make_super' value='1' /> ".$locale['416']."</label><br />\n"; }
                                
                                echo "<br />\n<input type='submit' name='add_admin' value='".$locale['417']."' class='button' />\n";
                                echo "</td>\n</tr>\n";
                        } else {
                                echo "<tr>\n<td align='center' class='tbl'>".$locale['418']."<br /><br />\n";
                                echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['419']."</a>\n</td>\n</tr>\n";
                        }
                        echo "</table>\n</form>\n";
                } else {
                        echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
                        echo "<tr>\n<td align='center' class='tbl'>".$locale['418']."<br /><br />\n";
                        echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['419']."</a>\n</td>\n</tr>\n</table>\n";
                }
        }
        closetable();

        opentable($locale['420']);
        $i = 0;
        $result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_level='102' ".($userdata['user_level']==102?"and donvi=$madonvi":"")." ORDER BY donvi, user_level DESC, user_name");
        echo "<table class='mau center'>\n<tr>\n";
        echo "<td class='tbl2'>".$locale['421']."</td>\n";
        echo "<td align='center' class='tbl2' style='white-space:nowrap'>Quyen</td>\n";
        echo "<td align='center' class='tbl2' style='white-space:nowrap'>".$locale['423']."</td>\n";
        echo "</tr>\n";
        while ($data = dbarray($result)) {
                $row_color = $i % 2 == 0 ? "tbl1" : "tbl2";
                echo "<tr>\n<td class='$row_color'><span title='".($data['user_rights'] ? str_replace(".", " ", $data['user_rights']) : "".$locale['425']."")."' style='cursor:hand;'>".$data['user_name']."</span></td>\n";
                echo "<td align='center'  class='$row_color' style='white-space:nowrap'>".$data['user_rights']."</td>\n";
                echo "<td align='center'  class='$row_color' style='white-space:nowrap'>\n";
                if ($data['user_level'] == "103" && $userdata['user_id'] == "1") { $can_edit = true;
                } elseif ($data['user_level'] != "103") { $can_edit = true;
                } else { $can_edit = false; }
                if ($can_edit == true && $data['user_id'] != "1") {
                        echo "<a href='".FUSION_SELF.$aidlink."&amp;edit=".$data['user_id']."'>".$locale['426']."</a> |\n";
                        echo "<a href='".FUSION_SELF.$aidlink."&amp;remove=".$data['user_id']."' onclick=\"return confirm('".$locale['460']."');\">".$locale['427']."</a>\n";
                }
                echo "</td>\n</tr>\n";
                $i++;
        }
        echo "</table>\n";
        closetable();
}
require_once THEMES."templates/footer.php";
?>
