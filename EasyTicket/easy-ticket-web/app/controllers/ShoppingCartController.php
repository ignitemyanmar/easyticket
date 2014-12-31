<?php

class ShoppingCartController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		$color			= Input::get('color');
		$size			= Input::get('size');
		$qty			= Input::get('qty');
		$item 			= Item::with('itempricebyqty')->whereid($id)->first();
		$itempricebyqty = 0;
		foreach ($item->itempricebyqty as $rows) {
			$qtyrange = QtyrangeforPrice::whereid($rows->priceqtyrange_id)->first();

			if($qty > $qtyrange->startqty && $qty <= $qtyrange->endqty){
				$itempricebyqty = $rows->price;
			}
		}
		$add_to_cart 	= Input::get('add_to_cart');
		$buy_now 		= Input::get('buy_now');
		if($color && !$size) {
			$itemdetail 	= ItemDetail::whereitem_id($id)->wherecolor_id($color)->first();
		}else if($size && !$color) {
			$itemdetail 	= ItemDetail::whereitem_id($id)->wheresize_id($size)->first();
		}else if($size && $color){
			$itemdetail 	= ItemDetail::whereitem_id($id)->wherecolor_id($color)->wheresize_id($size)->first();
		}else{
			return Redirect::to(URL::previous());
		}
		// Cart::destroy();
		if(isset($add_to_cart)){
			$rowId =  Cart::search(array('id' => $id, 'options' => array('item_id' => $id,'color_id' => $color,'size_id' => $size)));
			$color = Color::find($color);
			$size = Size::find($size);
			Cart::add(
					$itemdetail->id, 
					$item->name, $qty, 
					$itempricebyqty != 0 ? 
					$itempricebyqty : 
					$itemdetail->price, 
					array(
						'color_id' => $color != null ? $color->id : 0, 
						'color' => $color != null ? $color->name : 'None', 
						'size_id' => $size != null ? $size->id : 0, 
						'size' => $size != null ? $size->name : 'None', 
						'image' => $itemdetail->image != null ? $itemdetail->image : $item->image));

			return Redirect::to('/cart');
		}

		if(isset($buy_now)){
			$color = Color::find($color);
			$size = Size::find($size);
			Cart::add(
					$itemdetail->id, 
					$item->name, $qty, 
					$itempricebyqty != 0 ? $itempricebyqty : $itemdetail->price, 
					array(
						'color_id' => $color != null ? $color->id : 0, 
						'color' => $color != null ? $color->name : 'None', 
						'size_id' => $size != null ? $size->id : 0, 
						'size' => $size != null ? $size->name : 'None', 
						'image' => $itemdetail->image != null ? $itemdetail->image : $item->image));

			return Redirect::to('/delivery?access_token='.Auth::user()->access_token);
		}

		
		
	}

	public function view(){
		$content = Cart::content();
		//return Response::json($content);
		return View::make('shoppingcart.index', array('shoppingcart'=>$content));
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
	public function update($id,$color_id,$size_id,$qty)
	{
		$rowId = 0;
		$old_qty = 0;
		$content = Cart::content();
		foreach ($content as $rows) {
			if($rows->id == $id && $rows->options->color_id == $color_id && $rows->options->size_id == $size_id){
				$rowId = $rows->rowid;
				$old_qty = $rows->qty;
			}
		}
		Cart::update($rowId, $qty);
		return Response::json("1");
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id, $color, $size)
	{
		$rowId = 0;
		$content = Cart::content();
		foreach ($content as $rows) {
			if($rows->id == $id && $rows->options->color_id == $color && $rows->options->size_id == $size){
				$rowId = $rows->rowid;
			}
		}
		Cart::remove($rowId);
		return Redirect::to('/cart');
	}


}
