<?php

class OrderConfirmController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$generate_ref_id = Date('md').Order::orderby('id','desc')->pluck('id') + 1;
		$deliveryto = Shipping::whereuser_id(Auth::user()->id)->first();
		if($deliveryto && Cart::count() > 0){
			$content 			= Cart::content();
			$order 				= new Order();
			$order->ref_id 	= $generate_ref_id;
			$order->user_id 	= Auth::user()->id;
			$order->shipping_id = $deliveryto->id;
			$order->total = Cart::total();
			$order->save();
			
			if(count($order)>0){
				foreach ($content as $rows) {
					$orderdt 			= new OrderDetail();
					$orderdt->order_id 	= $order->id;
					$orderdt->itemdt_id = $rows->id;
					$orderdt->quantity 	= $rows->qty;
					$orderdt->price 	= $rows->price;
					$orderdt->amount 	= $rows->subtotal;
					$orderdt->save();
				}	
				Cart::destroy();
			}			

			return View::make('orderconfirm.index', array('generate_ref_id'=>$generate_ref_id, 'shoppingcart'=>$content, 'deliveryto'=>$deliveryto));

		}elseif(count($deliveryto) == 0 && Cart::count() > 0){

			return Redirect::to('/delivery');

		}else{

			return Redirect::to('/');

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
