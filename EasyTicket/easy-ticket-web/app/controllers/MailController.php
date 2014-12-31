<?php
class MailController extends BaseController
{

public static function index()
	{
	/* TO SENT admin ****************************************/
		$objorder=Order::orderBy('id','desc')->first();
		$order_no=$objorder->order_no;

		$subjectStr="New Order ... Order ID:".$order_no;
		$mailBodyText=View::make('mail.adminmail');
		$fromAddr =Auth::user()->email;
		// $recipientAddr ='sawnaythuaung92@gmail.com';
		$recipientAddr ='zawwinhtike92@gmail.com';
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: ".$fromAddr."\r\n";  
		$headers .= "Reply-To: ".$fromAddr."\r\n";  
		$headers .= "Return-Path: ".$fromAddr."\r\n";   
		// $headers .= "Content-type: text/html\r\n"; 
		mail($recipientAddr,$subjectStr ,$mailBodyText,$headers);


	// to sent customer *****************************************
		$recipientAddr =Auth::user()->email;
		$subjectStr="Thank for shopping with K & M Eazy Shopping";
		$fromAddr="zawwinhtike92@gmail.com";
		$mailBodyText=View::make('mail.customerorder');
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= "From: ".$fromAddr."\r\n";  
		$headers .= "Reply-To: ".$fromAddr."\r\n";  
		$headers .= "Return-Path: ".$fromAddr."\r\n";   
		// $headers .= "Content-type: text/html\r\n"; 
		mail($recipientAddr,$subjectStr ,$mailBodyText,$headers);
	return Redirect::to('/');				
}

public static function useractivate($user_id, $activation_code)
	{
	$objuser=User::find($user_id);
	// to sent customer *****************************************
	$customername=$objuser->name;
	$parameters="comfirmation_code=".$activation_code."&email=".$objuser->email;
	$subjectStr ="528go Account Confirmation";
	$fromAddr ="zawwinhtike92@gmail.com";
	$recipientAddr =$objuser->email;
	$mailBodyText=View::make('mail.adminmail', array('customername'=>$customername,'parameters'=>$parameters));


	$headers = "From: ".$fromAddr."\r\n";  
	$headers .= "Reply-To: ".$fromAddr."\r\n";  
	$headers .= "Return-Path: ".$fromAddr."\r\n";   
	$headers .= "Content-type: text/html\r\n"; 
	mail($recipientAddr,$subjectStr ,$mailBodyText,$headers);
	// return Redirect::to('/');				
}

public static function forgotpassword($email)
{
	// return Response::json($orders);
	$objuser=User::whereemail($email)->first();
	$From='zawwinhtike92@gmail.com';

	// to sent customer *****************************************
	$email="zawwinhtike92@gmail.com";
	$To=$objuser->email;
	$Name=$objuser->name;
	$password_code=$objuser->forgot_password_code;
	
	$link="http://128.199.238.75/img/logo1.png";
	$logo='<div style="background: url('.$link.') no-repeat center; width:110px; height:92px;">.</div>';
	$subjectStr ="528go Account Password Reset";
	$message  ="<p style='padding:10px;'><b>Dear ".$Name.",</b></p><div style='padding:5px 10px;'>";
	$message .="We heard you need a password reset. Click the link below and you'll be redirected to a secure site from which you can set a new password.<br><br>";
	$url='http://www.knmezshopping.com.mm/users/resetpassword?forgot_password_code='.$objuser->forgot_password_code;
	$message .='<a href="'.$url.'" style="background:#FD8FBE; padding:10px 24px; color:#fff;width:300px;margin:0 auto;">Reset Password</a>';
	$message .="<br><br>Have a nice day.<br>";
	$message .="528go Eazy Shopping.<br>";

	$fromAddr =$email;
	$recipientAddr =$To;
	$bodytext=$message;
	$footer="";

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	// Additional headers
	$headers .= 'To: '.$Name.' <'.$To.'>' . "\r\n";
	$headers .= 'From: K&N Eazy Shopping <'.$fromAddr.'>' . "\r\n";
	$headers .= "Reply-To: ".$fromAddr."\r\n";  
	$headers .= "Return-Path: ".$fromAddr."\r\n";  
	mail($recipientAddr,$subjectStr ,$mailBodyText,$headers);
	return Redirect::to('/');				
}


}