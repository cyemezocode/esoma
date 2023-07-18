$('#start').click(function(){
	initAudioPlayer();
	$(this).hide();
	$('#playpausebtn').show();
})
$('#button').click(function(){
	var play=true;
	var t =$('#t').val();
	$.ajax({
		url:'play.php',
		method:"POST",
		data:{play:play,t:t},
		success:function(data){
			location.reload();
		}
	})
})
$('#playpausebtn').click(function(){
	$('#playpausebtn').removeClass('active');
	if($(this).attr('class')=='fa fa-play'){
		$(this).attr('class','fa fa-pause');
		$('#playpausebtn').addClass('active');
	}else{
		$(this).attr('class','fa fa-play');
		$('#playpausebtn').addClass('active');

	}
})
$('#triDoc').click(function(){
	$('#document').trigger('click');
})