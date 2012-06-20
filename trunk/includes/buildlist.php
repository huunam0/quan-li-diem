<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }

// images ------------------------
$temp = opendir(IMAGES);
while ($file = readdir($temp)) {
        if (!in_array($file, array(".", "..", "/", "index.php")) && !is_dir($file)) {
                $image_files[] = "['Images: ".$file."','".$settings['siteurl']."images/".$file."'],\n";
        }
}
closedir($temp);

// compile list -----------------
if (isset($image_files)) {
        $indhold = "var tinyMCEImageList = new Array(\n";
        for ($i = 0; $i < count($image_files); $i++){
                $indhold .= $image_files[$i];
        }
        $lang = strlen($indhold) - 2;
        $indhold = substr($indhold, 0, $lang);
        $indhold = $indhold.");\n\n";
        $fp = fopen(IMAGES."imagelist.js", "w");
        fwrite($fp, $indhold);
        fclose($fp);
}
?>
