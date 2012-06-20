<?php
/*
create crosstab query - written by Tran Huu Nam 9/2009 thnam@ifi.edu.vn
*/

function crosstab($rowfield,$columnfield,$reffield,$crosstable){
	$resrow=dbquery("SELECT  distinct $rowfield  from $crosstable");
	$rescolumn=dbquery("SELECT distinct $columnfield  from $crosstable");
	$reselement=dbquery("SELECT $reffield as CROSSVALUE,$rowfield,$columnfield from $crosstable group by $rowfield,$columnfield");
	
	$i=0;
	while($wrow=dbarray($resrow)){
		$row[$i]=$wrow[$rowfield];
		$rowid[$row[$i]]=$i;
		//echo $row[$i]." ";
		$i++;
	}
	
	echo "<br>";
	$i=0;
	while($wcolumn=dbarray($rescolumn)){
		$column[$i]=$wcolumn[$columnfield];
		$columnid[$column[$i]]=$i;
		//echo $column[$i]." ";
		$i++;
	}
	
	
	while($welement=dbarray($reselement))	{
		$element[$rowid[$welement[$rowfield]]][$columnid[$welement[$columnfield]]]=$welement[CROSSVALUE];
		//echo $rowid[$rowfield]."/".$columnid[$columnfield].": ".$welement[CROSSVALUE]."<br>";
	
	}
	
	
	echo "<font face=arial><table class='center' border=1><tr><td>".$rowfield."\\".$columnfield."</td>";
	
	for ($n=0;$n<=(count($column)-1);$n++){
		echo "<td>".$column[$n]."</td>";
	}
	echo "</tr>";
	
	
	for ($m=0;$m<=(count($row)-1);$m++){
		echo "<tr>";
		for ($n=0;$n<=(count($column)-1);$n++){
	
			//echo "<td>".$column[$n]."<td>";
			if($n==0) 
				echo "<td>".$row[$m]."</td>";
			//} else {
				if($element[$m][$n]){
			   	 	echo "<td>".$element[$m][$n]."</td>";
			  	 	$total[$n]++;
			  	 	//$ttotal=$ttotal+$element[$row[$m]][$column[$n]];
			 	} else
		    			echo "<td>-</td>";
			
		}
		echo "</tr>\n";
	
	}
	
	echo "<td>Cộng</td>";
	for ($n=0;$n<=(count($column)-1);$n++){
		echo "<td><i><font size=-1>".$total[$n]."</font></i></td>";
	}
	echo "</tr>";
	
	
	echo "</table>";
	//echo " Total Records $ttotal";	
}

?>
