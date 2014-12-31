<?php

class MenuController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=Menu::all();
		return View::make('menu.list', array('response'=>$response));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('menu.add');
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
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/menu/create');
		}
		$check_exiting =Menu::wherename($name)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ="This Record is already exit.";
			return Redirect::to('/menu')->with('message', $response);
		}
		$objmenu= new Menu();
		$objmenu->name=$name;
		$objmenu->name_mm=$name_mm;
		$objmenu->alias=strtolower(str_replace(' ', '-', $name));
		$objmenu->alias_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objmenu->image=$image;
		$itemcode_prefix=Input::get('itemcode_prefix');
		$objmenu->itemcode_prefix=$itemcode_prefix;
		$objmenu->save();
		$response['status']=1;
		$response['info'] ="Successfully insert one record.";
		return Redirect::to('/menu')->with('message', $response);
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
		$response=Menu::find($id);
		return View::make('menu.edit', array('response'=>$response));
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
		$search_key_mm=Input::get('search_key_mm');
		$oldimage=Input::get('hdimage');
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/menu/create');
		}
		$check_exiting =Menu::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$message['status']=1;
			$message['info'] ="This Record is already exit.";
			return Redirect::to('/menu')->with('message', $message);
		}
		$objmenu= Menu::find($id);
		$objmenu->name=$name;
		$objmenu->name_mm=$name_mm;
		$objmenu->alias=strtolower(str_replace(' ', '-', $name));
		$objmenu->alias_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objmenu->search_key_mm=$search_key_mm;
		if($image)
			$objmenu->image=$image;
		else
			$objmenu->image=$oldimage;
		$itemcode_prefix=Input::get('itemcode_prefix');
		$objmenu->itemcode_prefix=$itemcode_prefix;
		$objmenu->update();
		$message['info']="Successfully update.";
		$message['status']=1;
		return Redirect::to('/menu')->with('message', $message);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Menu::whereid($id)->delete();
		$message['info']="Successfully has been delete one record.";
		$message['status']=1;
		return Redirect::to('menu')->with('message',$message);
	}

}