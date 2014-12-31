<?php

class AdvertisementController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=Advertisement::with(array('menu'))->get();
		// return Response::json($response);
		return View::make('advertisement.list', array('response'=>$response));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$menu=Menu::all();
		$position=array('Slider', 'Category Advertisement','Free Gift', 'Group Sale', 'Morning & Evening');
		return View::make('advertisement.add', array('menu'=>$menu, 'position'=>$position));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$menu_id=Input::get('menu_id');
		$position=Input::get('position');
		$link=Input::get('link');
		$image=Input::get('gallery1');
		$objadvertisement= new Advertisement();
		$objadvertisement->menu_id=$menu_id;
		$objadvertisement->position=$position;
		$objadvertisement->image=$image;
		$objadvertisement->link=$link;
		$objadvertisement->save();
		$response['status']=1;
		$response['info'] ="Successfully insert one record.";
		return Redirect::to('/advertisement')->with('message', $response);
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
		$response=Advertisement::find($id);
		$menu=Menu::all();
		$position=array('Slider', 'Category Advertisement','Free Gift', 'Group Sale', 'Morning & Evening');
		return View::make('advertisement.edit', array('response'=>$response,'menu'=>$menu, 'position'=>$position));
		// return View::make('advertisement.edit', array('response'=>$response));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$menu_id=Input::get('menu_id');
		$position=Input::get('position');
		$link=Input::get('link');
		$oldimage=Input::get('hdimage');
		$image=Input::get('gallery1');
		
		$objadvertisement= Advertisement::find($id);
		$objadvertisement->menu_id=$menu_id;
		$objadvertisement->position=$position;
		$objadvertisement->link=$link;
		if($image)
			$objadvertisement->image=$image;
		else
			$objadvertisement->image=$oldimage;
		$objadvertisement->update();
		$message['info']="Successfully update.";
		$message['status']=1;
		return Redirect::to('/advertisement')->with('message', $message);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Advertisement::whereid($id)->delete();
		$message['info']="Successfully has been delete one record.";
		$message['status']=1;
		return Redirect::to('advertisement')->with('message',$message);
	}

}