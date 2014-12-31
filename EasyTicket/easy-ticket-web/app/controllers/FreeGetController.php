<?php

class FreeGetController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=array();
		$advertisement=Advertisement::whereposition("Free Gift")->orderBy('id','desc')->limit('5')->get();
		$response['advertisement']=$advertisement;
		$menu_id=1;
		$freeget=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->limit(10)->get();
		$i=0;
		foreach ($freeget as $row) {
			$freeget[$i]=$row;
			if(count($row->itemdetail)>0){
				$freeget[$i]->price=$row->itemdetail[0]->price;
				$freeget[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$freeget[$i]->price=0;
				$freeget[$i]->oldprice=0;
			}
			$i++;
		}								
		$response['freeget']=$freeget;
		// return Response::json($response['freeget']);

		$clothing=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->skip(5)->limit(5)->get();
		$i=0;
		foreach ($clothing as $row) {
			$clothing[$i]=$row;
			if(count($row->itemdetail)>0){
				$clothing[$i]->price=$row->itemdetail[0]->price;
				$clothing[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$clothing[$i]->price=0;
				$clothing[$i]->oldprice=0;
			}
			$i++;
		}								
		$response['clothing']=$clothing;

		$clothing_list=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->limit(5)->get();
		$i=0;
		foreach ($clothing_list as $row) {
			$clothing_list[$i]=$row;
			if(count($row->itemdetail)>0){
				$clothing_list[$i]->price=$row->itemdetail[0]->price;
				$clothing_list[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$clothing_list[$i]->price=0;
				$clothing_list[$i]->oldprice=0;
			}
			$i++;
		}
		$response['list_clothing']=$clothing_list;

		$electronic=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->limit(5)->get();
		$i=0;
		foreach ($electronic as $row) {
			$electronic[$i]=$row;
			if(count($row->itemdetail)>0){
				$electronic[$i]->price=$row->itemdetail[0]->price;
				$electronic[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$electronic[$i]->price=0;
				$electronic[$i]->oldprice=0;
			}
			$i++;
		}								
		$response['electronic']=$electronic;

		$electronic_list=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->limit(5)->get();
		$i=0;
		foreach ($electronic_list as $row) {
			$electronic_list[$i]=$row;
			if(count($row->itemdetail)>0){
				$electronic_list[$i]->price=$row->itemdetail[0]->price;
				$electronic_list[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$electronic_list[$i]->price=0;
				$electronic_list[$i]->oldprice=0;
			}
			$i++;
		}
		$response['electronic_list']=$electronic_list;


		$hotitems=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->skip(5)->limit(5)->get();
		$i=0;
		foreach ($hotitems as $row) {
			$hotitems[$i]=$row;
			if(count($row->itemdetail)>0){
				$hotitems[$i]->price=$row->itemdetail[0]->price;
				$hotitems[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$hotitems[$i]->price=0;
				$hotitems[$i]->oldprice=0;
			}
			$i++;
		}								
		$response['hotitems']=$hotitems;

		$clothing_id=Menu::where('name_mm','like','%အဝတ္%')->pluck('id');
		$cosmetic_id=Menu::where('name','like','%cosmetic%')->pluck('id');
		$electronic_id=Menu::where('name','like','%electronic%')->pluck('id');
		$babies_id=Menu::where('name','like','%bab%')->pluck('id');
		$kitchen_id=Menu::where('name','like','%kitchen%')->pluck('id');
		$tabmenulink=array();
		$menulink['clothingmenuid']=$clothing_id;
		$menulink['cosmeticmenuid']=$cosmetic_id;
		$menulink['electronicmenuid']=$electronic_id;
		$menulink['babiesmenuid']=$babies_id;
		$menulink['kitchenmenuid']=$kitchen_id;
		$tabmenulink=$menulink;

		// return Response::json($response['freeget']);
		return View::make('freeget.index', array('response'=>$response, 'tabmenulink'=> $tabmenulink));
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
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
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
