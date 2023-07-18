<?php 
function getSounds($text){
$all='';
$s = explode(" ",$text); 
$word = array('amabanga','rusange','yo');
foreach ($s as $k) {
	if(in_array($k, $word)){
	   $all.=$k.' ';
	}
	else{
		$a=str_split($k);
		$vowels=array('a','e','i','o','u');
		foreach ($a as $x) {
			if(in_array($x, $vowels)){
				$all.=$x.' ';
			}else{
				$all.=$x;
			}
		}
		$all.='';
	}
}
$all;
$all  = explode(' ', $all);
$out='';
$c=array('a','b','c','d','e');
foreach ($all as $w) {
	if(in_array($w, $c)){	
	$out.='"'.$w.'",';
	}else{
	$out.='"a",';

	}
}
$outs=strrev($out);
$outs=substr($outs, 1);
return $out=strrev($outs);
}
?>