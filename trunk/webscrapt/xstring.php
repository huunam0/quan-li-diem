<?php   
// PHP String functions - written by Tran Huu Nam -3/2011- huunam0@gmail.com

function getTagByName($goc,$tag,$tt=1) {
	$rs1 = getSubstringPair($goc,"<".$tag,"</$tag>",$tt);
	$v1 = strpos($rs1,">");
	//echo $v1;
	return substr($rs1,$v1+1);
	//return $rs1;
}

function removeAllTags($goc) {
	$g=$goc;
	$fir = strpos($g,"<");
	$tag = getSubstringPair($g,"<",">");
	while (!($fir===false)) {
		$g=substr($g,0,$fir).substr($g,$fir+strlen($tag)+2);
		$fir = strpos($g,"<");
		$tag = getSubstringPair($g,"<",">");
	}
	return $g;
}

function getSubstringBetween($goc,$dau,$cuoi) {
	$v1=strpos($goc,$dau);
	$l=strlen($dau);
	if ($v1===false) {
		return "";
	} else {
		$v1+=$l;
		$v2=strpos($goc,$cuoi,$v1);
		if ($v1===false) {
			return substr($goc,$v1);
		} else {
			return substr($goc,$v1,$v2-$v1);
		}
	}	
}

function getSubstringPair($goc,$dau,$cuoi,$tt=1) {
	$v1=strpos($goc,$dau);
	$l=strlen($dau);
	$sl=1;
	while ($sl<$tt) {
		if ($v1===false) {
			return "";
		} else {
			$v1=strpos($goc,$dau,$v1+$l);
			$sl++;
		}
	}
	if ($v1===false) {
		return "";
	} else {
		$v1+=$l;
		$v2=strpos($goc,$cuoi,$v1);
		$v3=strpos($goc,$dau,$v1);
		while (true) {
			if ($v2===false) return substr($goc,$v1);
			if (($v2<=$v3)||($v3===false)) {
				$sl--;
				if ($sl<$tt) return substr($goc,$v1,$v2-$v1);
				$v2=strpos($goc,$cuoi,$v2+$l);
			} else {
				$sl++;
				$v3=strpos($goc,$dau,$v3+$l);
			}
		}
	}
	
	
}

?>
