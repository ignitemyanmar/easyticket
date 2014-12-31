<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
		<title>528go Shopping</title> 
	</head>
	<body>
		<div style="width:100%;position:relative; background:#fff; border:1px solid #eee;padding-top:35px;box-shadow:0 0 1px 2px #ccc;">
			<div style="width:90%;position:relative;margin:0 auto;font-family:Georgia;">
				<div style="background: url('http://fte.dev/skins/logo.png') no-repeat;height:94px;font-size:1.4rem; padding-left:160px; margin-left:20px;"><b style="position:absolute; text-shadow: 1px 2px 3px #666; font-size:1.4rem; padding-top:45px;">528go Account Password Reset</b></div>
				<hr style="border-bottom:4px solid #222;">
				<div style="padding:12px 24px;">
						Dear {{$customername}},<br><br>
						<p>We heard you need a password reset. Click the link below and you'll be redirected to a secure site from which you can set a new password.<br><br></p>
						<div style='position:relative;margin:0 auto;'>
							<a href="http://fte.dev/users/resetpassword?forgot_password_code={{$forgot_password_code}}" style="background:#FD8FBE; padding:10px 24px; color:#fff;width:300px;margin:0 auto;">Reset Password</a>
						</div>
						<br><br>
						Have a nice day.<br>
						528go Shopping.<br>
				</div>
			</div>
		</div>
		</div>
	</body>
</html>
