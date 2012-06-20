<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

function flood_control($field, $table, $where) {
        
        global $userdata, $settings;
        
        $flood = false;
        
        if (!iSUPERADMIN && !iADMIN && (!defined("iMOD") || !iMOD)) {
                $result = dbquery("SELECT MAX(".$field.") AS last_post FROM ".$table." WHERE ".$where);
                if (dbrows($result)) {
                        $data = dbarray($result);
                        if ((time() - $data['last_post']) < $settings['flood_interval']) {
                                $flood = true;
                                $result = dbquery("INSERT INTO ".DB_FLOOD_CONTROL." (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
                                if (dbcount("(flood_ip)", DB_FLOOD_CONTROL, "flood_ip='".USER_IP."'") > 4) {
                                        if (iMEMBER) {
                                                $result = dbquery("UPDATE ".DB_USERS." SET user_status='1' WHERE user_id='".$userdata['user_id']."'");
                                        } else {
                                                $result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_ip, blacklist_email, blacklist_reason) VALUES ('".USER_IP."', '', 'Automatic Ban')");
                                        }
                                }
                        }
                }
        }       
        return $flood;
}
?>
