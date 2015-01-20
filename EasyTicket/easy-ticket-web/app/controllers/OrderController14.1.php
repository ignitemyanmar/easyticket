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


	public function destroy($id)
	{
		$this->del_order_history_trans($id);	
		
		SaleOrder::whereid($id)->delete();
		// AgentDeposit::whereorder_ids('["'.$id.'"]')->delete();
		$message="Successfully delete order.";
		return Redirect::to('orderlist')->with('message',$message);
	}

	public function del_order_history_trans($id){
		$objsaleorder=SaleOrder::whereid($id)->first();
		$objsaleitem=SaleItem::whereorder_id($id)->get();

		if($objsaleorder){
			try {
				$chksaleorder=DeleteSaleOrder::whereid($id)->first();
				$partiallydel_ticketamount=0;
				if($chksaleorder){
					$saletotal_ticket=SaleItem::whereorder_id($id)->count();
					$delsaletotal_ticket=DeleteSaleItem::whereorder_id($id)->count();
					$total_tickets=$saletotal_ticket + $delsaletotal_ticket;

					$total_amt=SaleOrder::whereid($id)->pluck('total_amount');
					$total_agentcommission=SaleOrder::whereid($id)->pluck('agent_commission');
					$agent_commission=0;
					// if($total_amt >0){
					$ticket_price=$total_amt / $total_tickets;
					$agent_commission=$total_agentcommission / $total_tickets;
					// }
					$finalticketprice=$ticket_price-$agent_commission;
					$partiallydel_ticketamount=$finalticketprice * $delsaletotal_ticket;
				}else{
					$objdeletesaleorder=new DeleteSaleOrder();
					$objdeletesaleorder->id=$id;
					$objdeletesaleorder->orderdate=$objsaleorder->orderdate;
					$objdeletesaleorder->departure_date=$objsaleorder->departure_date;
					$objdeletesaleorder->departure_datetime=$objsaleorder->departure_datetime;
					$objdeletesaleorder->device_id=$objsaleorder->device_id;
					$objdeletesaleorder->reference_no=$objsaleorder->reference_no;
					$objdeletesaleorder->agent_id=$objsaleorder->agent_id;
					$objdeletesaleorder->name=$objsaleorder->name;
					$objdeletesaleorder->nrc_no=$objsaleorder->nrc_no;
					$objdeletesaleorder->phone=$objsaleorder->phone;
					$objdeletesaleorder->operator_id=$objsaleorder->operator_id;
					$objdeletesaleorder->cash_credit=$objsaleorder->cash_credit;
					$objdeletesaleorder->booking=$objsaleorder->booking;
					$objdeletesaleorder->total_amount=$objsaleorder->total_amount;
					$objdeletesaleorder->agent_commission=$objsaleorder->agent_commission;
					$objdeletesaleorder->user_id=Auth::user()->id;
					$objdeletesaleorder->nationality=$objsaleorder->nationality;
					$objdeletesaleorder->remark_type=$objsaleorder->remark_type;
					$objdeletesaleorder->remark=$objsaleorder->remark;
					$objdeletesaleorder->created_at=$objsaleorder->created_at;
					$objdeletesaleorder->updated_at=$objsaleorder->updated_at;
					$objdeletesaleorder->expired_at=$objsaleorder->expired_at;
					$objdeletesaleorder->save();
				}
				
				$total_amount 				= $objsaleorder->total_amount - $objsaleorder->agent_commission;
				$total_amount =$total_amount - $partiallydel_ticketamount;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$objdepositpayment_trans->pay_date			= $this->getDate();
	    		$objdepositpayment_trans->order_ids			= '["'.$id.'"]';

	    		$objdepositpayment_trans->payment 			= $total_amount;
	    		$agentdeposit 								= AgentDeposit::whereagent_id($objsaleorder->agent_id)->whereoperator_id($objsaleorder->operator_id)->orderBy('id','desc')->first();
	    		if($agentdeposit){
	    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
	    			$objdepositpayment_trans->balance 		= $agentdeposit->balance + $total_amount;
	    		}else{
	    			$objdepositpayment_trans->deposit 		= 0;
	    			$objdepositpayment_trans->balance 		= 0 + $total_amount;
	    		}  		
	    		$objdepositpayment_trans->debit 			= 0;
	    		$objdepositpayment_trans->deleted_flag 		= 1;
	    		$objdepositpayment_trans->save();

			} catch (Exception $e) {
				return Redirect::to('orderlist')->with('message','Database Exception occured!'.$e);	
			}
		}

		if($objsaleitem){
			foreach ($objsaleitem as $result) {
				try {
					$objdeletesaleitem=new DeleteSaleItem();
					$objdeletesaleitem->order_id=$result->order_id;
					$objdeletesaleitem->device_id=$result->device_id;
					$objdeletesaleitem->ticket_no=$result->ticket_no;
					$objdeletesaleitem->seat_no=$result->seat_no;
					$objdeletesaleitem->nrc_no=$result->nrc_no;
					$objdeletesaleitem->name=$result->name;
					$objdeletesaleitem->phone=$result->phone;
					$objdeletesaleitem->busoccurance_id=$result->busoccurance_id;
					$objdeletesaleitem->trip_id=$result->trip_id;
					$objdeletesaleitem->from=$result->from;
					$objdeletesaleitem->to=$result->to;
					$objdeletesaleitem->extra_destination_id=$result->extra_destination_id;
					$objdeletesaleitem->extra_city_id=$result->extra_city_id;
					$objdeletesaleitem->operator=$result->operator;
					$objdeletesaleitem->agent_id=$result->agent_id;
					$objdeletesaleitem->price=$result->price;
					$objdeletesaleitem->free_ticket=$result->free_ticket;
					$objdeletesaleitem->class_id=$result->class_id;
					$objdeletesaleitem->foreign_price=$result->foreign_price;
					$objdeletesaleitem->departure_date=$result->departure_date;
					$objdeletesaleitem->save();
				} catch (Exception $e) {
					return Redirect::to('orderlist')->with('message','Database Exception occured!'.$e);
				}
			}
		}
	}

	
	/**
	 * Delete by ticket_id ;
	 *
	 * 
	 * @return Response
	 */
	//Delete only each order records.
	public function ticketdelete($id){
		$saleitem_id=$id;
		$objsaleitem=SaleItem::find($id);

		if(!$objsaleitem){
			$message="Record has been deleted.";
			return Redirect::to('/order-tickets/'.$id)->with('message',$message);
		}
		$orderid=$objsaleitem->order_id;
		$objsaleorder=SaleOrder::whereid($orderid)->first();
		
		$objagent_commission=AgentCommission::whereagent_id($objsaleitem->agent_id)->wheretrip_id($objsaleitem->trip_id)->first();
		// return Response::json($objsaleitem);
		//calculate agent commission
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
		//end calculate agent commission

		$price=$objsaleitem->price -$commission;
		$this->del_orderticket_history_trans($objsaleitem,$objsaleorder,$price);

		$objsaleitem->delete();
		$saleitems=SaleItem::whereorder_id($orderid)->count();
		if($saleitems ==0){
			SaleOrder::whereid($orderid)->delete();
		}
		$message="Successfully delete ticket.";
		return Redirect::to('/order-tickets/'.$orderid)->with('message',$message);
	}


	public function del_orderticket_history_trans($objsaleitem, $objsaleorder, $price){
		if($objsaleorder){
			$id=$objsaleorder->id;
			try {
				$chksaleorder=DeleteSaleOrder::whereid($id)->first();
				if($chksaleorder){
				}else{
					$objdeletesaleorder=new DeleteSaleOrder();
					$objdeletesaleorder->id=$id;
					$objdeletesaleorder->orderdate=$objsaleorder->orderdate;
					$objdeletesaleorder->departure_date=$objsaleorder->departure_date;
					$objdeletesaleorder->departure_datetime=$objsaleorder->departure_datetime;
					$objdeletesaleorder->device_id=$objsaleorder->device_id;
					$objdeletesaleorder->reference_no=$objsaleorder->reference_no;
					$objdeletesaleorder->agent_id=$objsaleorder->agent_id;
					$objdeletesaleorder->name=$objsaleorder->name;
					$objdeletesaleorder->nrc_no=$objsaleorder->nrc_no;
					$objdeletesaleorder->phone=$objsaleorder->phone;
					$objdeletesaleorder->operator_id=$objsaleorder->operator_id;
					$objdeletesaleorder->cash_credit=$objsaleorder->cash_credit;
					$objdeletesaleorder->booking=$objsaleorder->booking;
					$objdeletesaleorder->total_amount=$objsaleorder->total_amount;
					$objdeletesaleorder->agent_commission=$objsaleorder->agent_commission;
					$objdeletesaleorder->user_id=Auth::user()->id;
					$objdeletesaleorder->nationality=$objsaleorder->nationality;
					$objdeletesaleorder->remark_type=$objsaleorder->remark_type;
					$objdeletesaleorder->remark=$objsaleorder->remark;
					$objdeletesaleorder->created_at=$objsaleorder->created_at;
					$objdeletesaleorder->updated_at=$objsaleorder->updated_at;
					$objdeletesaleorder->expired_at=$objsaleorder->expired_at;
					$objdeletesaleorder->save();
				}
				
				$total_amount 				= $price;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$objdepositpayment_trans->pay_date			= $this->getDate();
	    		$objdepositpayment_trans->order_ids			= '["'.$id.'"]';

	    		$objdepositpayment_trans->payment 			= $total_amount;
	    		$agentdeposit 								= AgentDeposit::whereagent_id($objsaleorder->agent_id)->whereoperator_id($objsaleorder->operator_id)->orderBy('id','desc')->first();
	    		if($agentdeposit){
	    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
	    			$objdepositpayment_trans->balance 		= $agentdeposit->balance + $total_amount;
	    		}else{
	    			$objdepositpayment_trans->deposit 		= 0;
	    			$objdepositpayment_trans->balance 		= 0 + $total_amount;
	    		}  		
	    		$objdepositpayment_trans->debit 			= 0;
	    		$objdepositpayment_trans->deleted_flag 		= 1;
	    		$objdepositpayment_trans->save();

			} catch (Exception $e) {
				return Redirect::to('orderlist')->with('message','Database Exception occured!');	
			}
		}

		if($objsaleitem){
			try {
				$objdeletesaleitem=new DeleteSaleItem();
				$objdeletesaleitem->order_id=$objsaleitem->order_id;
				$objdeletesaleitem->device_id=$objsaleitem->device_id;
				$objdeletesaleitem->ticket_no=$objsaleitem->ticket_no;
				$objdeletesaleitem->seat_no=$objsaleitem->seat_no;
				$objdeletesaleitem->nrc_no=$objsaleitem->nrc_no;
				$objdeletesaleitem->name=$objsaleitem->name;
				$objdeletesaleitem->phone=$objsaleitem->phone;
				$objdeletesaleitem->busoccurance_id=$objsaleitem->busoccurance_id;
				$objdeletesaleitem->trip_id=$objsaleitem->trip_id;
				$objdeletesaleitem->from=$objsaleitem->from;
				$objdeletesaleitem->to=$objsaleitem->to;
				$objdeletesaleitem->extra_destination_id=$objsaleitem->extra_destination_id;
				$objdeletesaleitem->extra_city_id=$objsaleitem->extra_city_id;
				$objdeletesaleitem->operator=$objsaleitem->operator;
				$objdeletesaleitem->agent_id=$objsaleitem->agent_id;
				$objdeletesaleitem->price=$objsaleitem->price;
				$objdeletesaleitem->free_ticket=$objsaleitem->free_ticket;
				$objdeletesaleitem->class_id=$objsaleitem->class_id;
				$objdeletesaleitem->foreign_price=$objsaleitem->foreign_price;
				$objdeletesaleitem->departure_date=$objsaleitem->departure_date;
				$objdeletesaleitem->save();
			} catch (Exception $e) {
				return Redirect::to('orderlist')->with('message','Database Exception occured!');
			}
		}
	}

}