<?php

class MailConfiController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public static function index()
	{
		/*$response=MailConfig::orderBy('id','desc')->first();
		$config = array(
                'driver' => "smtp",
                'host' => "smtp.gmail.com",
                'port' => "587",
                'encryption' => "tls",
                'username' => $response->email,
                'password' => $response->password,
                'from' => array('address' => $response->email, 'name' => $response->name),
                'sendmail' => '/usr/sbin/sendmail -bs',
                'pretend' => false
        );*/

		$config = array(
                'driver' => "smtp",
                'host' => "smtp.gmail.com",
                'port' => "587",
                'encryption' => "tls",
                'username' => "zawwinhtike92@gmail.com",
                'password' => "zawwinhtike1992",
                'from' => array('address' => "zawwinhtike92@gmail.com", 'name' => "525go Website"),
                'sendmail' => '/usr/sbin/sendmail -bs',
                'pretend' => false
        );
		Config::set('mail',$config);
		
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showlist()
	{
		$response=MailConfig::orderBy('id','desc')->first();
		return View::make('mail.config', array('response'=>$response));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(Auth::user()->role !=4){
			return Redirect::to('/itemlist');
		}
		$response=MailConfig::find($id);
		return View::make('mail.changeemailpassword', array('response'=>$response));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(Auth::user()->role !=4){
			return Redirect::to('/itemlist');
		}
		$name=Input::get('name');
		$email=Input::get('email');
		$password=Input::get('password');
		$objmailconfig=MailConfig::find($id);
		$objmailconfig->name=$name;
		$objmailconfig->email=$email;
		$objmailconfig->password=$password;
		$objmailconfig->update();
		return Redirect::to('mailconfig')->with('message','Successfully Update mail info.');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
