$(document).ready(function() {

	$('.btn_signin').click(function(){
		$('.loading_indicator').addClass('loading');
		var l_login = $('#l_username').val();
		var l_password = $('#l_password').val();
		if(l_login == ""){
			$('.message').addClass('alert_error');
			$('.message').html('Error: Please enter username.');
			$('.loading_indicator').removeClass('loading');
			$('#l_username').focus();
			return false;
		}
		if(l_password == ""){
			$('.message').addClass('alert_error');
			$('.message').html('Error: Please enter password.');
			$('.loading_indicator').removeClass('loading');
			$('#l_password').focus();
			return false;
		}
		var dataString={
						"username" : l_login,
						"password" : l_password
					};

		var dataJSON=JSON.stringify(dataString);
		$.ajax({
				type: "POST",
				url: "/login",
				data: dataJSON,
				}).done(function( response ) {
					if(response["status"] === 1){
						$('.message').addClass('alert_success');
						$('.message').html('Success: You are already logged in!');
						$('.loading_indicator').removeClass('loading');
						window.location.href= response["redirect_uri"];
					}else{
						$('.message').addClass('alert_error');
						$('.message').html('Error: '+response['error']);
						$('.loading_indicator').removeClass('loading');
					}
				});
	});

	$('.btn_signup').click(function(){
		$('.register_indicator').addClass('loading');
		var s_name 			= $('#s_name').val();
		var s_email 		= $('#s_email').val();
		var s_password 		= $('#s_password').val();
		var sc_password 	= $('#sc_password').val();
		if(s_name == ""){
			$('.register_message').addClass('alert_error');
			$('.register_message').html('Error: Please enter name.');
			$('.register_indicator').removeClass('loading');
			$('#s_name').focus();
			return false;
		}
		if(s_email == ""){
			$('.register_message').addClass('alert_error');
			$('.register_message').html('Error: Please enter email.');
			$('.register_indicator').removeClass('loading');
			$('#s_email').focus();
			return false;
		}
		if(s_password == ""){
			$('.register_message').addClass('alert_error');
			$('.register_message').html('Error: Please enter password.');
			$('.register_indicator').removeClass('loading');
			$('#s_password').focus();
			return false;
		}
		if(sc_password == ""){
			$('.register_message').addClass('alert_error');
			$('.register_message').html('Error: Please enter confirm password.');
			$('.register_indicator').removeClass('loading');
			$('#sc_password').focus();
			return false;
		}

		if(s_password != sc_password){
			$('.register_message').addClass('alert_error');
			$('.register_message').html('Error: Don\'t match two passwords.' );
			$('.register_indicator').removeClass('loading');
			$('#sc_password').val("");
			$('#sc_password').focus();
			return false;
		}

		var dataString={
						"name" 		: s_name,
						"email" 	: s_email,
						"password" 	: s_password
					};

		var dataJSON=JSON.stringify(dataString);
		$.ajax({
				type: "POST",
				url: "/newaccount",
				data: dataJSON,
				}).done(function( response ) {
					if(response["status"] === 1){
						$('.register_message').addClass('alert_success');
						$('.register_message').html('Success: You are already logged in!');
						$('.register_indicator').removeClass('loading');
						window.location.href= response["redirect_uri"];
					}else{
						$('.register_message').addClass('alert_error');
						$('.register_message').html('Error: '+response["error"]);
						$('.register_indicator').removeClass('loading');
					}
				});
	});

	$('.delivery_city').change(function(){
		$('.indicator').addClass('loading small');
		var dataString={
					"delivery" : $('#delivery_to_city').val(),
				};
		var dataJSON=JSON.stringify(dataString);
		$.ajax({
			type: "POST",
			url: "/setcity",
			data: dataJSON,
		}).done(function( data ) {
			$('.indicator').removeClass('loading small');
			var str  = '<select id="delivery_to_township" name="delivery_address" class="delivery_township left">';
				str	+= '<option value="0">Select to Township</option>';
			$('#delivery_to_township').html("");
			for (var i = 0;i < data.length; i++) {
				str += '<option value="'+data[i]["id"]+'">'+ data[i]["name"] +'</option>';
			};
				str += '</select>';
				str += '<script>';
				str += '$(".delivery_township").select2();';
				str += '$(".delivery_township").change(function(){ changeTownship(); });';
				str += '</script>';

				$('.township-container').html(str);
				
		});
	});

	$('.frm_delivery_city').change(function(){
		$('.frm_indicator').addClass('loading small');
		var dataString={
					"delivery" : $('#frm_delivery_to_city').val(),
				};
		var dataJSON=JSON.stringify(dataString);
		$.ajax({
			type: "POST",
			url: "/setcity",
			data: dataJSON,
		}).done(function( data ) {
			$('.frm_indicator').removeClass('loading small');
			var str  = '<select id="frm_delivery_to_township" name="delivery_address" class="frm_delivery_township left">';
				str	+= '<option value="0">Select to Township</option>';
			for (var i = 0;i < data.length; i++) {
				str += '<option value="'+data[i]["id"]+'">'+ data[i]["name"] +'</option>';
			};
				str += '</select>';
				str += '<script>';
				str += '$(".frm_delivery_township").select2();';
				str += '$(".frm_delivery_township").change(function(){ changeFrmTownship(); });';
				str += '</script>';

				$('.frm-township-container').html(str);
				
		});
	});

	$('.delivery_township').change(function(){
		changeTownship();
	});

	$('.frm_delivery_township').change(function(){
		changeFrmTownship();
	});

	window.changeTownship = function(){
		$('.indicator').addClass('loading small');
		var dataString={
					"delivery" : $('#delivery_to_township').val(),
				};
		var dataJSON=JSON.stringify(dataString);
		$.ajax({
			type: "POST",
			url: "/settownship",
			data: dataJSON,
		}).done(function( data ) {
			$('.indicator').removeClass('loading small');
			//window.location.href=data;	NOT NEED.	
		});
	};

	window.changeFrmTownship = function(){
		$('.frm_indicator').addClass('loading small');
		var dataString={
					"delivery" : $('#frm_delivery_to_township').val(),
				};
		var dataJSON=JSON.stringify(dataString);
		$.ajax({
			type: "POST",
			url: "/settownship",
			data: dataJSON,
		}).done(function( data ) {
			$('.frm_indicator').removeClass('loading small');
			window.location.href=data;
		});
	};

	$('.menu_city').on('click',function(){
		var id = $(this).data("id");
		$(this).parent().parent().children().children('div').removeClass('active');
		$(this).addClass('active');
		$('#township-'+id).addClass('active');
	});

	$('.each-township').on('click',function(){
		var str = $(this).data("id");
		$(this).parent().parent().children().children('span').removeClass('active');
		$(this).addClass('active');
		var split = str.split("/");
		$('#delivery-info').html(split[0]);
		$('#city-township').val(split[1]);
	});
	$('.number_person').change(function(){
		var val = $(this).val();
		//var index = val.substring(0, 1); 
		var discounted_price = $('.primary_price').html() - ($('.primary_price').html() * val/100);
		$('.discounted_price').html(discounted_price);
		$('.discount b').html(val+'% OFF');
	});
	$('#group_sale').foundation('reveal', 'open');
	$('#delivery_window').foundation('reveal', 'open');
	$('.frm_delivery_city, .delivery_city').select2();
	$('.frm_delivery_township, .delivery_township').select2();
});