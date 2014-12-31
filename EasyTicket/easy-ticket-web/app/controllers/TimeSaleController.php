<?php

class TimeSaleController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=array();
		$advertisement=Advertisement::whereposition("Morning & Evening")->orderBy('id','desc')->limit('5')->get();
		$response['advertisement']=$advertisement;
		$menu_id=1;
		$timesale=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->limit(10)->get();
		$i=0;
		foreach ($timesale as $row) {
			$timesale[$i]=$row;
			if(count($row->itemdetail)>0){
				$timesale[$i]->price=$row->itemdetail[0]->price;
				$timesale[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$timesale[$i]->price=0;
				$timesale[$i]->oldprice=0;
			}
			$i++;
		}								
		$response['timesale']=$timesale;
		// return Response::json($response['timesale']);

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

		// return Response::json($response['timesale']);
		return View::make('timesale.index', array('response'=>$response));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function morningevening($type)
	{
		if($type=='morning'){
			$title='မနက္ေစ်း';
		}else{
			$title='ညေစ်း';
		}
		$search='';

		$response=array();
		$advertisement=Advertisement::orderBy('id','desc')->limit('5')->get();
		$response['advertisement']=$advertisement;
		$menu_id=1;
		$timesale=Item::with(array('itemdetail'))
								->whereHas('itemdetail',  function($query) use ($menu_id) {
										// $query->where('price','>=', $min_price)
								})
								->orderBy('id','desc')->paginate(30);
		$i=0;
		foreach ($timesale as $row) {
			$timesale[$i]=$row;
			if(count($row->itemdetail)>0){
				$timesale[$i]->price=$row->itemdetail[0]->price;
				$timesale[$i]->oldprice=$row->itemdetail[0]->oldprice;
			}else{
				$timesale[$i]->price=0;
				$timesale[$i]->oldprice=0;
			}
			$i++;
		}								
		$response['timesale']=$timesale;
		// return Response::json($response['timesale']);

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

		// return Response::json($response['timesale']);
		return View::make('timesale.type', array('response'=>$response,'timesale'=>$timesale,'title'=>$title,'search'=>$search));
	}


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
