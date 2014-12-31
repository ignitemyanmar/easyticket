<?php

class SizeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=Size::with(array('category'))->get();
		// return Response::json($response);
		return View::make('size.list', array('response'=>$response));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$objcategory =Category::orderBy('name', 'asc')->get();
		return View::make('size.add', array('category'=>$objcategory));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$category_id=Input::get('category_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		if(!$name && !$name_mm){
			$response['status'] =0;
			$response['info'] ='Required parameters.';
			return Redirect::to('/size')->with('message', $response);
		}
		$check_exiting =Size::wherecategory_id($category_id)->wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ='This record is already exit';
			return Redirect::to('/size')->with('message', $response);
		}

		$objsize= new Size();
		$objsize->category_id=$category_id;
		$objsize->name=$name;
		$objsize->name_mm=$name_mm;
		$objsize->save();
		$response['status'] =1;
		$response['info'] ="Successfully save one record.";
		return Redirect::to('/size')->with('message', $response);
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
		$objcategory =Category::orderBy('name', 'asc')->get();
		$response=Size::find($id);
		return View::make('size.edit', array('response'=>$response,'category'=>$objcategory));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$category_id=Input::get('category_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		if(!$name && !$name_mm){
			return Redirect::to('/size/create');
		}
		$check_exiting =Size::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response['status'] =1;
			$response['info'] ='This record is already exit';
			return Redirect::to('/size')->with('message', $response);
		}

		$objsize= Size::find($id);
		$objsize->category_id=$category_id;
		$objsize->name=$name;
		$objsize->name_mm=$name_mm;
		$objsize->update();
		$response['status'] =1;
		$response['info'] ="Successfully update one record.";
		return Redirect::to('/size')->with('message', $response);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=ItemDetail::wheresize_id($id)->first();
		if($check_exiting){
			$message['status']=0;
			$message['info']="You can't delete this record. Has links.";
		}

		Size::find($id)->delete();
		$message['status']=1;
		$message['info']="Successfully delete one record.";
		return Redirect::to('/size')->with('message', $message);
	}


}
