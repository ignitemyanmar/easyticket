<?php

class TownshipController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=Township::all();
		return View::make('township.list', array('response'=>$response));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$objcity =City::orderBy('name', 'asc')->get();
		return View::make('township.add', array('city'=>$objcity));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$city_id=Input::get('city_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		if(!$name && !$name_mm){
			$response['status'] =0;
			$response['info'] ='Required parameters.';
			return Redirect::to('/township')->with('message', $response);
		}
		$check_exiting =Township::wherecity_id($city_id)->wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ='This record is already exit';
			return Redirect::to('/township')->with('message', $response);
		}

		$objtownship= new Township();
		$objtownship->city_id=$city_id;
		$objtownship->name=$name;
		$objtownship->name_mm=$name_mm;
		$objtownship->save();
		$response['status'] =1;
		$response['info'] ="Successfully save one record.";
		return Redirect::to('/township')->with('message', $response);
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
		$objcity =City::orderBy('name', 'asc')->get();
		$response=Township::find($id);
		return View::make('township.edit', array('response'=>$response,'city'=>$objcity));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$city_id=Input::get('city_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		if(!$name && !$name_mm){
			return Redirect::to('/township/create');
		}
		$check_exiting =Township::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response['status'] =1;
			$response['info'] ='This record is already exit';
			return Redirect::to('/township')->with('message', $response);
		}

		$objtownship= Township::find($id);
		$objtownship->city_id=$city_id;
		$objtownship->name=$name;
		$objtownship->name_mm=$name_mm;
		$objtownship->update();
		$response['status'] =1;
		$response['info'] ="Successfully update one record.";
		return Redirect::to('/township')->with('message', $response);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=Shop::wheretownship_id($id)->first();
		$check_exiting2=Shipping::wheretownship_id($id)->first();
		if($check_exiting || $check_exiting2){
			$message['status']=0;
			$message['info']="You can't delete this record. Has links.";
		}

		Township::find($id)->delete();
		$message['status']=1;
		$message['info']="Successfully delete one record.";
		return Redirect::to('/township')->with('message', $message);
	}


}
