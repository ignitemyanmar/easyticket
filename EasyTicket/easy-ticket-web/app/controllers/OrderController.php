<?php

class OrderController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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
		$saleOrder = SaleOrder::with('saleitems')->whereid($id)->first();
		if($saleOrder){
			SaleOrder::whereid($id)->delete();
			DeleteSaleOrder::create($saleOrder->toarray());
			foreach ($saleOrder->saleitems as $rows) {
				DeleteSaleItem::create($rows->toarray());
			}
		}
		$message="Successfully delete order.";
		return Redirect::to('orderlist')->with('message',$message);
	}

	public function deleteNotConfirmOrder($id){
		$saleOrder = SaleOrder::whereid($id)->where('name','=','')->where('total_amount','=','')->first();
		if($saleOrder){
			SaleOrder::whereid($id)->where('name','=','')->where('total_amount','=','')->delete();
			DeleteSaleOrder::create($saleOrder->toarray());
			foreach ($saleOrder->saleitems as $rows) {
				DeleteSaleItem::create($rows->toarray());
			}
		}
		$message="Successfully delete order.";
		return Redirect::to('orderlist')->with('message',$message);
	}

	public function orderlist(){
		$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$response=SaleOrder::whereoperator_id($operator_id)->with(array('agent','saleitems'))->get();
		// return Response::json($response);
		if($response){
			$i=0;
			foreach($response as $orders){
				if(count($orders->saleitems)>0){
					$from=City::whereid($orders->saleitems[0]->from)->pluck('name');
					$to=City::whereid($orders->saleitems[0]->to)->pluck('name');
					$from_to=$from.'-'.$to;
					$response[$i]['from_to']=$from_to;
					$busclass=Classes::whereid($orders->saleitems[0]->class_id)->pluck('name');
					$response[$i]['busclass']=$busclass;
					$departure_time=BusOccurance::whereid($orders->saleitems[0]->busoccurance_id)->pluck('departure_time');
					$response[$i]['departure_time']=$departure_time;
					$response[$i]['price']=$orders->saleitems[0]->price;
					$response[$i]['total_ticket']=count($orders->saleitems);
				}else{
					SaleOrder::whereid($orders->id)->delete();
					$response[$i]['from_to']='-';
					$response[$i]['busclass']='-';
					$response[$i]['departure_time']='-';
					$response[$i]['price']='-';
					$response[$i]['total_ticket']='-';
				}
				
				$i++;
			}
		}
		// return Response::json($response);

		return View::make('order.list',array('response'=>$response));

	}

	public function ticketlist($id){
		$response=SaleItem::whereorder_id($id)->get();
		return View::make('order.tickets', array('response'=>$response));
	}

	public function ticketdelete($id){
		$objsaleitem=SaleItem::find($id);
		$orderid=$objsaleitem->order_id;
		$objsaleitem->delete();
		$saleitems=SaleItem::whereorder_id($orderid)->count();
		if($saleitems ==0){
			SaleOrder::whereid($orderid)->delete();
		}
		$message="Successfully delete ticket.";
		return Redirect::to('/order-tickets/'.$orderid)->with('message',$message);
	}

}