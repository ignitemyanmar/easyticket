<?php

class ColorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=Color::all();
		return View::make('color.list', array('response'=> $response));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('color.add');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		if(!$name && !$name_mm){
			return Redirect::to('/color/create');
		}
		$check_exiting =Color::wherename($name)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ="This Record is already exit.";
			return Redirect::to('/color')->with('message', $response);
		}
		$objcolor= new Color();
		$objcolor->name=$name;
		$objcolor->name_mm=$name_mm;
		$objcolor->save();
		$response['status']=1;
		$response['info'] ="Successfully insert one record.";
		return Redirect::to('/color')->with('message', $response);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$response=Color::find($id);
		return View::make('color.edit', array('response'=> $response));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		if(!$name && !$name_mm){
			return Redirect::to('/color/create');
		}
		$check_exiting =Color::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$message['status']=1;
			$message['info'] ="This Record is already exit.";
			return Redirect::to('/color')->with('message', $message);
		}
		$objcolor= Color::find($id);
		$objcolor->name=$name;
		$objcolor->name_mm=$name_mm;
		$objcolor->update();
		$message['info']="Successfully update.";
		$message['status']=1;
		return Redirect::to('/color')->with('message', $message);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=ItemDetail::wherecolor_id($id)->first();
		if($check_exiting){
			$message['status']=0;
			$message['info']="You can't delete this record. Has links.";
		}

		Color::find($id)->delete();
		$message['status']=1;
		$message['info']="Successfully delete one record.";
		return Redirect::to('/color')->with('message', $message);
	}


}
