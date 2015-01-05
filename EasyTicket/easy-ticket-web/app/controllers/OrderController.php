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
	/*public function destroy($id)
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
	}*/

	public function destroy($id)
	{
		$saleOrder = SaleOrder::whereid($id)->first();
		if($saleOrder){
			SaleOrder::whereid($id)->delete();
			AgentDeposit::whereorder_ids('["'.$id.'"]')->delete();
			$message="Successfully delete order.";
			return Redirect::to('orderlist')->with('message',$message);
		}
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
		$operator_id=$this->myGlob->operator_id;
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

	/*public function ticketdelete($id){
		$objsaleitem=SaleItem::find($id);
		$orderid=$objsaleitem->order_id;
		$objsaleitem->delete();
		$saleitems=SaleItem::whereorder_id($orderid)->count();
		if($saleitems ==0){
			SaleOrder::whereid($orderid)->delete();
		}
		$message="Successfully delete ticket.";
		return Redirect::to('/order-tickets/'.$orderid)->with('message',$message);
	}*/
	public function ticketdelete($id){
		$objsaleitem=SaleItem::find($id);
		if(!$objsaleitem){
			$message="Record has been deleted.";
			return Redirect::to('/order-tickets/'.$id)->with('message',$message);
		}
		$orderid=$objsaleitem->order_id;
		$saleitems=SaleItem::whereorder_id($orderid)->count();
		$objagent_commission=AgentCommission::whereagent_id($objsaleitem->agent_id)->wheretrip_id($objsaleitem->trip_id)->first();
		$commission=0;
		if($objagent_commission){
			if($objagent_commission->commission_id==1){
				$commission=$objagent_commission->commission;
			}else{
				if($nationality=='local'){
    				$commission +=($objsaleitems->price * $objagent_commission->commission) / 100;
    			}else{
    				$commission +=($objsaleitems->foreign_price * $objagent_commission->commission) / 100;
    			}
			}
		}
		if($commission==0){
			$tripcommission=Trip::whereid($objsaleitem->trip_id)->pluck('commission');
			$commission= $tripcommission;
		}
		$objsaleitem->delete();

		$price=$objsaleitem->price -$commission;


		if($saleitems ==0){
			SaleOrder::whereid($orderid)->delete();
			AgentDeposit::whereorder_ids('["'.$orderid.'"]')->delete();
		}else{
			$objagentdeposit=AgentDeposit::whereorder_ids('["'.$orderid.'"]')->first();
			if($objagentdeposit){
				$objagentdeposit->total_ticket_amt= $objagentdeposit->total_ticket_amt - $price;
				$objagentdeposit->update();
			}
		}
		$message="Successfully delete ticket.";
		return Redirect::to('/order-tickets/'.$orderid)->with('message',$message);
	}

}