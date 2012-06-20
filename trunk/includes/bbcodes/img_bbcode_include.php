<?php
if (!defined("QLTRUONG")) { die("Access Denied"); }
$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#sie","'<span style=\'display: block; width: 300px; max-height: 300px; overflow: no;\' class=\'forum-img-wrapper\'>'.(('\\1\\3\4' != 'http://thptnguyendu.com/images/download.jpg') ? '<a href=\'\\1\\3\\4\' style=\'cursor: url('.INCLUDES.'highslide/graphics/zoomin.cur)\' onclick=\'return hs.expand(this)\'>' : '').'<img style=\'border:0px\' class=\'forum-img\' src=\'\\1\\3\\4\'/></span></a>'",$text);
?>
