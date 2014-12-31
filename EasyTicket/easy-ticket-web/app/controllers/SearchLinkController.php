<?php

class SearchLinkController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=SearchLink::orderBy('priority','desc')->get();
		return View::make('searchlink.list', array('response'=>$response));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$objmenu =Menu::orderBy('name', 'asc')->get();
		$listsorting=SearchLink::where('priority','!=',0)->groupBy('priority')->lists('priority');
		return View::make('searchlink.add', array('menu'=>$objmenu,'listsorting'=>$listsorting));
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
		$priority=Input::get('priority');
		if(!$name && !$name_mm){
			$response['status'] =0;
			$response['info'] ='Required parameters.';
			return Redirect::to('/search_link')->with('message', $response);
		}
		$check_exiting =SearchLink::wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ='This record is already exit';
			return Redirect::to('/search_link')->with('message', $response);
		}

		$objsearch_link= new SearchLink();
		$objsearch_link->menu_id=$menu_id;
		$objsearch_link->name=$name;
		$objsearch_link->name_mm=$name_mm;
		$objsearch_link->priority=$priority;
		$objsearch_link->save();
		$response['status'] =1;
		$response['info'] 	="Successfully save one record.";
		return Redirect::to('/search_link')->with('message', $response);
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
		$response=SearchLink::find($id);
		return View::make('searchlink.edit', array('response'=>$response,'menu'=>$objmenu));
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
		$priority=Input::get('priority');
		if(!$name && !$name_mm){
			return Redirect::to('/search_link/create');
		}
		$check_exiting =SearchLink::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response ='This record is already exit';
			return Redirect::to('/search_link')->with('message', $response);
		}

		$objsearch_link= SearchLink::find($id);
		$objsearch_link->menu_id=$menu_id;
		$objsearch_link->name=$name;
		$objsearch_link->name_mm=$name_mm;
		$objsearch_link->priority=$priority;
		$objsearch_link->update();
		$response['status'] =1;
		$response['info']="Successfully update one record.";
		return Redirect::to('/search_link')->with('message', $response);
	}



	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		SearchLink::whereid($id)->delete();
		$response['status']=1;
		$response['info']='Successfully delete one record.';
		return Redirect::to('search_link')->with('message',$response);
	}


}
