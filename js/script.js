$('#document').change(function(){
	var documen=true;
	var str='';
	var doc= document.getElementById('document').files[0]; 
	var fdata = new FormData();
	fdata.append("file",doc);
	fdata.append("upload",documen);
	$.ajax({
		url:'driver/document.php',
		method:'POST',
		 data: fdata, //add the FormData object to the data parameter
		 processData: false, //tell jquery not to process data
		 contentType: false, //tell jquery not to set content-type
		beforeSend:function(){
				$('#triDoc').addClass('active');
			},
		success:function(data){
				$('#triDoc').removeClass('active');
				if(data=='no'){
					$('#see').trigger('click');
				}else{
					$('#getString').val(data);
					saveData(data);
				}
			}
})
})
$('#bookupload').submit(function(e){
	e.preventDefault();
	var fdata = new FormData(this);
	$.ajax({
		url:'driver/document.php',
		method:'POST',
		 data: fdata, //add the FormData object to the data parameter
		 processData: false, //tell jquery not to process data
		 contentType: false, //tell jquery not to set content-type
		success:function(data){
				if(data=='no'){
					$('#see').trigger('click');
				}else{
					$('#bookupload').trigger('reset');
					$('#getString').val(data);
					saveData(data);
				}
			}
})
})
$('#check').click(function(){
	var save=true;
	var t = $('#getString').val();
	$.ajax({
		url:'driver/play.php',
		method:'POST',
		data:{save:save,t:t},
		success:function(data){
			// location.reload();
			$('#word').html(data);
			// alert(data);
			initAudioPlayer();
		}
	})
})
$('#modal').click(function(){
	$('#alert').removeClass('active');
	$('#popup').removeClass('active');
	$('#profile').removeClass('active');
	$('#modal').fadeOut();
})
$('#alertclose').click(function(){
	$('#modal').trigger('click');
})
$('#popclose').click(function(){
	$('#modal').trigger('click');
})
$('#proclose').click(function(){
	$('#modal').trigger('click');
})
$('#pro').click(function(){
	$('#modal').addClass('active');
	$('#profile').addClass('active');
})
$('#check2').click(function(){
	$('#check').trigger('click');
})

$(document).ready(function(){
	var text = $('#getString').val();
	console.log(text);
})
$('.listen').click(function(){
	var listen=$(this).data('word');
	$.ajax({
		url:'driver/play.php',
		method:'POST',
		data:{t:listen},
		success:function(data){
			$('#word').html(data);
			initAudioPlayer();
		}
	})
})

$('.details').click(function(){
	var t=$(this).text();
	var details=$(this).data('desc');
	$.ajax({
		url:'driver/play.php',
		method:'POST',
		data:{t:t,details:details},
		success:function(data){
			$('#modal').fadeIn();
			$('#popup').addClass('active');
			$('#popup h2 span').html(data);
			$('#popup p').html(data);
			findData();
		}
	})
})
$('.read').click(function(){
	var book=$(this).data('book');
	$.ajax({
		url:'driver/action.php',
		method:'POST',
		data:{book:book},
		success:function(data){
			$('#getString').val(data.book);
			$('#check').trigger('click');
			
		}
	})
})
$('.form').submit(function(e){
	e.preventDefault();
	var data=$(this).serialize();
	$.ajax({
		url:'driver/action.php',
		method:'POST',
		data:data,
		success:function(data){
			if(data.none){
					$('#see').trigger('click');
				}else{
					$('#popup h2 span').html(data.title);
					$('#popup p').html(data.details);
				}
		}
	})
})
$('.logout').click(function(e){
	$.ajax({
		url:'driver/action.php',
		method:'POST',
		data:{logout:true},
		success:function(data){
			window.location.reload();
		}
	})
})
function findData(){
	$.ajax({
		url:'driver/action.php',
		method:'POST',
		data:{findData:true},
		success:function(data){
			$('#popup h2 span').html(data.title);
			$('#popup p').html(data.details);
			initAudioPlayer();
		}
	})
}
function saveData(d){
	$.ajax({
		url:'driver/action.php',
		method:'POST',
		data:{saveData:d},
		success:function(data){
		}
	})
}
$(function(){

    $('.search').keyup(function(){

        var searchText = $(this).val();

        $('.word > li').each(function(){

            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;

            $(this).toggle(showCurrentLi);

        });     
    });

    $('.searchbook').keyup(function(){

        var searchText = $(this).val();

        $('.book > li').each(function(){

            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;

            $(this).toggle(showCurrentLi);

        });     
    });

});

  function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();
    // console.log("ID: " + profile.getId()); // Don't send this directly to your server!
    // console.log('Full Name: ' + profile.getName());
    // console.log('Given Name: ' + profile.getGivenName());
    // console.log('Family Name: ' + profile.getFamilyName());
    // console.log("Image URL: " + profile.getImageUrl());
    // console.log("Email: " + profile.getEmail());

    // // The ID token you need to pass to your backend:
    // var id_token = googleUser.getAuthResponse().id_token;
    // console.log("ID Token: " + id_token);
    $.ajax({
				url:'driver/action.php',
				method:'POST',
				data:{userLog:true,name:profile.getName(),email:profile.getEmail(),image:profile.getImageUrl(),id:profile.getId()},
				success:function(data){
			window.location.reload();
				}
			})
  }

   function makePayment(amt,id) {
    FlutterwaveCheckout({
    	// demo version
      // public_key: "FLWPUBK_TEST-8cc2785bad48feda4a07da4d10b74dda-X",
           // live version
      //public_key: "FLWPUBK-847c26cd1c61468d3598be3d50016594-X", Cyemezo 
      public_key: "FLWPUBK-012b0c2723502c658c933947f81cb3b3-X",
      tx_ref: "hooli-tx-1920xbbtyt",
      amount: amt,
      currency: "RWF",
      country: "RW",
      payment_options: "card, mobilemoneyrwanda, ussd",
      redirect_url: // specified redirect URL
        "http://localhost/final/download?download="+id,
      meta: {
        consumer_id: 23,
        consumer_mac: "92a3-912ba-1192a",
      },
      customer: {
        email: "neponzisa@gmail.com",
        phone_number: "0788648134",
        name: "NZISABIRA Jean Nepo",
      },
      callback: function (data) {
        console.log(data);
      },
      onclose: function() {
        // close modal
      },
      customizations: {
        title: "E-Soma System - Kinyarwanda Natural Reader",
        description: "Payment for Book",
        logo: "http://localhost/final/img/logo.png"
      },
    });
  }