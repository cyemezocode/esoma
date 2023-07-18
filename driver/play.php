<?php
require 'config.php';
session_start();

$text=strtolower($_POST['t']);
$_SESSION['t']=$text;

if(isset($_POST['details']) and $_POST['details']!=''){
	$text.=' bisobanura '.strtolower($_POST['details']);
}
$_SESSION['alltext']=$text;
$text = $_SESSION['alltext'];
$text=str_replace("’", "", $text);
$text=str_replace("'", "", $text);	
$text=str_replace(".", "", $text);
$text=str_replace(",", "", $text);	
$text=str_replace(" ", "", $text);	
$text=str_replace('""', "", $text);	
$alls = str_split($text);
$i=0;
$vowel =array('a','i','e','o','u');
$word = '';
$cou = count($alls);
$cou-=1;

// checking whether there is some two vowels or consonant which are the like aa or bb
while ($i < count($alls)) {
	$f = $i-1;
	$alls[$i];
	if($f==-1)
		$f=0;
	$p= $alls[$f];
	$c= $alls[$i];
	if(in_array($c, $vowel) and in_array($p, $vowel)){
		// do nothing
	}else {
		if($p!=$c or is_numeric($p) and $f!=0)
			// concatinate previous character to word
			$word .=$p;
		if($i==$cou)
			// concatinate current character to word
			$word.=$c;
	}
	$i++;
}
$all='';
$text = $word;
// after  removing repeating characters then put text in array
$s = explode(" ",$text); 
$word = array();
foreach ($s as $k) {
	if(in_array($k, $word)){
	   $all.=$k.' ';
	}
	else{
		$a=str_split($k);
		$vowels=array('a','e','i','o','u','0');
		$numbers =array('1','2','3','4','5','6','7','8','9');
		foreach ($a as $x) {
			if(in_array($x, $vowels) or in_array($x, $numbers)){
				// put space after if it is a vowel or number
				$all.=$x.' ';
			}
			else{
				// just merge for consonants
				$all.=$x;
			}
		}
		$all.='null ';
	}
}
 $all;
 // after put all text in array
$all  = explode(' ', $all);
$out='';
foreach ($all as $w) {	
	if($w!="")
		// then add double quotes around the word
		$out.='"'.$w.'",';
}
$outs=strrev($out);
$outs=substr($outs, 1);
$_SESSION['play']=$out=strrev($outs);

// echo $text;

if(isset($_POST['save'])){
	$tosave=str_replace('<br />', '', $_POST['t']);
$tosave = explode(' ', $tosave);
	foreach ($tosave as $key) {
		// looking for duplicate values
		$sql="SELECT w_string from word where w_string=?";
		$word = $pdo->prepare($sql);
		$word->bindValue(1,$key);
		$word->execute();
		$found = $word->fetch();
		if(!$found){

			if(!isset($_SESSION['userID'])){
				$_SESSION['userID']=0;
			}
			echo $_SESSION['userID'];
			$key=strtolower($key);
			$sql3="INSERT into word VALUES(?,?,?,?)";
			$book3=$pdo->prepare($sql3);
			$book3->bindValue(1,'');
			$book3->bindValue(2,$key);
			$book3->bindValue(3,'');
			$book3->bindValue(4,$_SESSION['userID']);
			$book3->execute();
		}
	}
}
?>

<script>
	
function initAudioPlayer(){
	var audio, playbtn, mutebtn, seekslider, volumeslider, seeking=false, seekto, curtimetext, durtimetext, playlist_status, dir, playlist, ext, agent;
	dir = "audio/";
	var words = $('#word').val();
	playlist = [<?php echo $_SESSION['play']; ?>];
	playlist_index = 0;
	ext = ".mp3";
    agent = navigator.userAgent.toLowerCase();
    if(agent.indexOf('firefox') != -1 || agent.indexOf('opera') != -1) {
        ext = ".mp3";
    }
	// Set object references
	playbtn = document.getElementById("playpausebtn");
	// mutebtn = document.getElementById("mutebtn");
	seekslider = document.getElementById("seekslider");
	volumeslider = document.getElementById("volumeslider");
	curtimetext = document.getElementById("curtimetext");
	durtimetext = document.getElementById("durtimetext");
	playlist_status = document.getElementById("playlist_status");
	// Audio Object
	audio = new Audio();
	audio.src = dir+playlist[0]+ext;
	audio.loop = false;
	audio.play();

	playlist_status.innerHTML = "Track "+(playlist_index+1)+" - "+ playlist[playlist_index]+ext;
	// Add Event Handling
	playbtn.addEventListener("click",playPause);
	// mutebtn.addEventListener("click", mute);
	audio.addEventListener("ended", function(){ 
		if(playlist_index != playlist.length-1){
			switchTrack();
		}else{
	    $('.wave img').hide();

		} });
	// Functions
	function switchTrack(){
		if(playlist_index == (playlist.length - 1)){
			playlist_index = 0;
		} else {
		    playlist_index++;	
		}
		audio.src = dir+playlist[playlist_index]+ext;
	    audio.play();
	    $('.wave img').show();
	}
	function playPause(){
		if(audio.paused){
		    audio.play();
		   
	    } else {
		    audio.pause();
	    }
	}
	function setvolume(){
	    audio.volume = volumeslider.value / 100;
    }
}
</script>