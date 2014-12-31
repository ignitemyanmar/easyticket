<?php

class DeliveryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$content = Cart::content();
		$township = Township::get();
		$city = City::get();
		$deliveryto = Shipping::whereuser_id(Auth::user()->id)->first();
		return View::make('delivery.index', array('shoppingcart'=>$content, 'township'=>$township, 'city'=>$city, 'deliveryto'=>$deliveryto));
	}

	public function confirmgroupsale($group_id){
		if(!Auth::check()){
			return Redirect::to('/login_page/'.$group_id);
		}
		$group_item = GroupItem::whereid($group_id)->whereuser_id(Auth::user()->id)->first();
		if($group_item){
			$group_person = GroupPerson::wheregroup_id($group_id)->get();
			//if(count($group_person) == $group_item->number_person){
				foreach ($group_person as $rows) {
					$id 			= $rows->item_id;
					$color			= $rows->color_id;
					$size			= $rows->size_id;
					$qty			= $rows->qty;
					$item 			= Item::whereid($id)->first();
										
					$itemdetail 	= ItemDetail::whereitem_id($id)->wherecolor_id($color)->wheresize_id($size)->first();

					if($itemdetail){
						$color = Color::find($color);
						$size  = Size::find($size);
						Cart::add(
								$itemdetail->id, 
								$item->name, $qty, 
								$itempricebyqty != 0 ? 
								$itempricebyqty : 
								$itemdetail->price, 
								array(
									'color_id' => $color->id, 
									'color' => $color->name, 
									'size_id' => $size->id, 
									'size' => $size->name, 
									'image' => $itemdetail->image != null ? $itemdetail->image : $item->image));

						
					}
				}

				return Redirect::to('/delivery?access_token='.Auth::user()->access_token);
			//}else{
			//	return Redirect::to('/');
			//}
		}
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
		$btn_delivery =  Input::get('btn_delivery');

		if(isset($btn_delivery)){
			$delivery = Input::all();
			$deliveryto = new Shipping();
			$deliveryto->name 			= $delivery['name'];
			$deliveryto->phone			= $delivery['phone'];
			$deliveryto->address 		= $delivery['address'];
			$deliveryto->township_id 	= $delivery['township'];
			$deliveryto->city_id		= $delivery['city'];
			$deliveryto->user_id		= Auth::user()->id;
			$deliveryto->save();

			Session::put('delivery_to_city', $delivery['city']);
			Session::put('delivery_to_township', $delivery['township']);

			return Redirect::to(URL::previous());
		}
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
		$btn_delivery =  Input::get('btn_delivery');

		if(isset($btn_delivery)){
			$delivery = Input::all();
			$deliveryto = Shipping::whereuser_id(Auth::user()->id)->first();
			$deliveryto->name 			= $delivery['name'];
			$deliveryto->phone			= $delivery['phone'];
			$deliveryto->address 		= $delivery['address'];
			$deliveryto->township_id 	= $delivery['township'];
			$deliveryto->city_id		= $delivery['city'];
			$deliveryto->user_id		= Auth::user()->id;
			$deliveryto->save();

			Session::put('delivery_to_city', $delivery['city']);
			Session::put('delivery_to_township', $delivery['township']);

			return Redirect::to(URL::previous());
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
	}


}
