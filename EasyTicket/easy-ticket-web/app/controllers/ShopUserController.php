<?php

class ShopUserController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=User::whererole(4)->get();
		return View::make('shopuser.list', array('response'=>$response));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('shopuser.add');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$name=Input::get('name');
		$email=Input::get('email');
		$password=Input::get('password');
		$address=Input::get('address');
		$phone=Input::get('phone');
		$permit_count=Input::get('permit_count');
		$role=4;
		$expired_at=Input::get('expired_at');

		$objuser=new User();
		$objuser->name		=$name;
		$objuser->email		=$email;
		$objuser->password 	=Hash::make($password);
		$objuser->address 	=$address;
		$objuser->phone 	=$phone;
		$objuser->role 		=$role;
		$objuser->permit_count	=$permit_count;
		$objuser->expired_at	=$expired_at;
		$objuser->save();
		$message="Success save one user.";
		return Redirect::to('shopuser')->with('message',$message);

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if(Auth::user()->role!=8)
		{
			$message="You can't update seller's info.";
			return Redirect::to('shopuser')->with('message', $message);
		}

		$response=User::find($id);
		return View::make('shopuser.edit', array('response'=>$response));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		if(Auth::user()->role!=8)
		{
			$message="You can't update seller's info.";
			return Redirect::to('shopuser')->with('message', $message);
		}
		$name=Input::get('name');
		$email=Input::get('email');
		$password=Input::get('password');
		$address=Input::get('address');
		$phone=Input::get('phone');
		$permit_count=Input::get('permit_count');
		$role=4;
		$expired_at=Input::get('expired_at');

		$objuser=User::find($id);
		$objuser->name		=$name;
		$objuser->email		=$email;
		if($password)
		$objuser->password 	=Hash::make($password);
		$objuser->address 	=$address;
		$objuser->phone 	=$phone;
		$objuser->role 		=$role;
		$objuser->permit_count	=$permit_count;
		$objuser->expired_at	=$expired_at;
		$objuser->update();
		$message="Success Update one user.";
		return Redirect::to('shopuser')->with('message',$message);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Auth::user()->role!=8)
		{
			$message="You can't update seller's info.";
			return Redirect::to('shopuser')->with('message', $message);
		}
		User::find($id)->delete();
		$message="Successfully delete one record.";
		return Redirect::to('shopuser')->with('message', $message);
	}


}
