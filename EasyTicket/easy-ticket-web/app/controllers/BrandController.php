<?php

class BrandController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$objbrand =Brand::with(array('menu'))->orderBy('id', 'desc')->get();
		return View::make('brand.list', array('response'=>$objbrand));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$objmenu =Menu::orderBy('name', 'asc')->get();
		$listsorting=Brand::where('priority','!=',0)->groupBy('priority')->lists('priority');
		return View::make('brand.add', array('menu'=>$objmenu,'listsorting'=>$listsorting));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$menu_id=Input::get('menu_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('gallery1');
		$priority=Input::get('priority');
		if(!$name && !$name_mm){
			$response['status'] =0;
			$response['info'] ='Required parameters.';
			return Redirect::to('/brand')->with('message', $response);
		}
		$check_exiting =Brand::wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ='This record is already exit';
			return Redirect::to('/brand')->with('message', $response);
		}

		$objbrand= new Brand();
		$objbrand->menu_id=$menu_id;
		$objbrand->name=$name;
		$objbrand->name_mm=$name_mm;
		$objbrand->search_key_mm=$search_key_mm;
		$objbrand->priority=$priority;
		$objbrand->image=$image;
		$objbrand->save();
		$response['status'] =1;
		$response['info'] 	="Successfully save one record.";
		return Redirect::to('/brand')->with('message', $response);
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
		$objmenu =Menu::orderBy('name', 'asc')->get();
		$response=Brand::find($id);
		return View::make('brand.edit', array('response'=>$response,'menu'=>$objmenu));
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
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('gallery1');
		$oldimage=Input::get('hdimage');
		$priority=Input::get('priority');
		if(!$name && !$name_mm){
			return Redirect::to('/brand/create');
		}
		$check_exiting =Brand::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response ='This record is already exit';
			return Redirect::to('/brand')->with('message', $response);
		}

		$objbrand= Brand::find($id);
		$objbrand->menu_id=$menu_id;
		$objbrand->name=$name;
		$objbrand->name_mm=$name_mm;
		$objbrand->search_key_mm=$search_key_mm;
		if($image)
		$objbrand->image=$image;
		else
		$objbrand->image=$oldimage;
		$objbrand->priority=$priority;
		$objbrand->update();
		$response['status'] =1;
		$response['info']="Successfully update one record.";
		return Redirect::to('/brand')->with('message', $response);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=Item::wherebrand_id($id)->first();
		if($check_exiting){
			$response['status']=0;
			$response['info']="You can't delete this record for has links.";
			return Redirect::to('brand')->with('message',$response);
		}
		Brand::whereid($id)->delete();
		$response['status']=1;
		$response['info']='Successfully delete one record.';
		return Redirect::to('brand')->with('message',$response);
	}

}