<!DOCTYPE html>
  <html>
    <head>
    <title>528Go Online Shopping</title>
    {{HTML::style('../../../css/font.css')}}
	{{HTML::style('../../../css/foundation.min.css')}}
	{{HTML::style('../../../css/frontnavcus.css')}}
	{{HTML::style('../../../css/lstyle.css')}}
	{{HTML::style('../../../css/sstyle.css')}}
	{{HTML::style('../../../css/smkstyle.css')}}
	{{HTML::style('../../../src/select2.css')}}
	{{HTML::style('../../../css/pagestyle.css')}}
	{{HTML::style('../../../css/dropdown.css')}}
	{{HTML::style('../../../css/pages/master.css')}}

	{{HTML::script('../../../js/jquery.js')}}
	{{HTML::script('../../../js/app.js')}}
      
    </head>
    <body>
	<style type="text/css">
		.login_page label{
			color: #222 !important;
			font-size: 14px !important;
		}
		.login_page .button{
			height: 45px !important;
			padding: 6px 36px !important;
		}
		.login_page form fieldset img{
			width: 50px !important;
			max-width: none;
		}
		.header{
		    margin-top: 52px;
		    margin-bottom: 30px;
		}
	</style>
	<div class="row text-center header"><img src="../../../skins/logo.png"></div>
	<div class="row login_page">
		<div class="large-4 columns">&nbsp;</div>
		<div class="large-4 columns">
			<form> 
				<fieldset> 
				<legend>Please first login!</legend> 
					<div class="row">
					    <div class="large-12 columns">
					      	<label>Email</label>
					      	<input type="text" id="l_username" name="email" required/>
					    </div>
					 </div>
					 <div class="row">
					    <div class="large-12 columns">
					      	<label>Password</label>
					      	<input type="password" id="l_password" name="password" required/>
					    </div>
					 </div>
					 <div class="row">
					    <div class="large-6 columns left">
					      	<input type="button" class="button btn_signin" value="Login" />
					    </div>
					    <div class="large-2 columns left">
                          	<div class="loading_indicator" style="width: 36px;height: 35px;position: relative;float: right;margin-top: 9px;"></div>
                        </div>
					 </div>

					<p>
				        <div class="large-2 columns"><a href="https://528go.com/loginwithgoogle"><img src="../../../../img/google_ci.png"></a></div> 
	                    <div class="large-2 columns"><a href="https://528go.com/loginwithfacebook"><img src="../../../../img/facebook_ci.png"></a></div> 
	                    <div class="large-2 columns"><a href="https://528go.com/loginwithtwitter"><img src="../../../../img/twitter_ci.png"></a></div> 
	                    <div class="large-2 columns"><a href="https://528go.com/loginwithlinkedin"><img src="../../../../img/linkedin_ci.png"></a></div> 
	                    <div class="large-2 columns"><a href=""><img src="../../../../img/qq_ci.png"></a></div> 
                    </p>
				</fieldset> 
			</form>
		</div>
		<div class="large-4 columns">&nbsp;</div>
	</div>
	{{HTML::script('../../../js/foundation.min.js')}}

	<script type="text/javascript">
	$(document).foundation(); 
	</script>
  </body>
</html>
