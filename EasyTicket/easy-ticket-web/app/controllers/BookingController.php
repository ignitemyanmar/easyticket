<?php

class BookingController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getBookingList()
	{
		$response=SaleOrder::wherebooking(1)->with(array('agent','saleitems'))->get();
		$i=0;
		foreach ($response as $row) {
			$from=City::whereid($row->saleitems[0]->from)->pluck('name');
			$to=City::whereid($row->saleitems[0]->to)->pluck('name');
			$response[$i]['trip']=$from.'-'.$to;
			$response[$i]['total_seat']=count($row->saleitems);
			if($row->nationality=='foreign')
			$response[$i]['total_amount']=count($row->saleitems) * $row->saleitems[0]->foreign_price;
			else
			$response[$i]['total_amount']=count($row->saleitems) * $row->saleitems[0]->price;
			$seat_nos='';
			foreach ($row->saleitems as $ticket) {
				$seat_nos .=$ticket->seat_no .', ';
			}
			$seat_nos=substr($seat_nos,0, -2);
			$response[$i]['seat_numbers']=$seat_nos;
			$response[$i]['departure_date']=$row->departure_date;
			$objbus=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
			$response[$i]['departure_time']=$objbus->departure_time;
			$response[$i]['class']=Classes::whereid($objbus->classes)->pluck('name');
			$i++;
		}
		// return Response::json($response);
		return View::make('busreport.booking.list', array('response'=>$response));

	}

	public function getBookingListByBus($id)
	{
		$order_ids=SaleItem::wherebusoccurance_id($id)->lists('order_id');
		$response=array();
		if($order_ids){
			$response=SaleOrder::wherein('id',$order_ids)->wherebooking(1)->with(array('agent','saleitems'))->get();
			$i=0;
			foreach ($response as $row) {
				$from=City::whereid($row->saleitems[0]->from)->pluck('name');
				$to=City::whereid($row->saleitems[0]->to)->pluck('name');
				$response[$i]['trip']=$from.'-'.$to;
				$response[$i]['total_seat']=count($row->saleitems);
				if($row->nationality=='foreign')
				$response[$i]['total_amount']=count($row->saleitems) * $row->saleitems[0]->foreign_price;
				else
				$response[$i]['total_amount']=count($row->saleitems) * $row->saleitems[0]->price;
				$seat_nos='';
				foreach ($row->saleitems as $ticket) {
					$seat_nos .=$ticket->seat_no .', ';
				}
				$seat_nos=substr($seat_nos,0, -2);
				$response[$i]['seat_numbers']=$seat_nos;
				$response[$i]['departure_date']=date('d/m/Y',strtotime($row->departure_date));
				$objbus=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
				$response[$i]['departure_time']=$objbus->departure_time;
				$response[$i]['class']=Classes::whereid($objbus->classes)->pluck('name');
				$i++;
			}
		}
		
		// return Response::json($response);
		return View::make('busreport.booking.list', array('response'=>$response));

	}

	public function getTodayBookingList()
	{
		$today=$this->Date;
		$today=substr($today, 0,10);
		// dd($today);
		$order_ids=SaleOrder::whereorderdate($today)->wherebooking(1)->lists('id');
		// $order_ids=SaleItem::wherebusoccurance_id($id)->lists('order_id');
		$response=array();
		if($order_ids){
			$response=SaleOrder::wherein('id',$order_ids)->wherebooking(1)->with(array('agent','saleitems'))->get();
			$i=0;
			foreach ($response as $row) {
				$from=City::whereid($row->saleitems[0]->from)->pluck('name');
				$to=City::whereid($row->saleitems[0]->to)->pluck('name');
				$response[$i]['trip']=$from.'-'.$to;
				$response[$i]['total_seat']=count($row->saleitems);
				if($row->nationality=='foreign')
				$response[$i]['total_amount']=count($row->saleitems) * $row->saleitems[0]->foreign_price;
				else
				$response[$i]['total_amount']=count($row->saleitems) * $row->saleitems[0]->price;
				$seat_nos='';
				foreach ($row->saleitems as $ticket) {
					$seat_nos .=$ticket->seat_no .', ';
				}
				$seat_nos=substr($seat_nos,0, -2);
				$response[$i]['seat_numbers']=$seat_nos;
				$response[$i]['departure_date']=date('d/m/Y',strtotime($row->departure_date));
				$objbus=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
				$response[$i]['departure_time']=$objbus->departure_time;
				$response[$i]['class']=Classes::whereid($objbus->classes)->pluck('name');
				$i++;
			}
		}
		
		// return Response::json($response);
		return View::make('busreport.booking.list', array('response'=>$response));

	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getBookingComfirm($id)
	{
    	$payment_amount=Input::get('payment_amount') ? Input::get('payment_amount') : 0;
    	$order_ids=Input::get('order_id');
        $objsaleorder=SaleOrder::whereid($id)->first();
        $agent_id=$objsaleorder->agent_id;
        $operator_id=$objsaleorder->operator_id;
    	$response=array();
    	$total_amount=0;
    	$grand_total = 0;
    	$ticketcount= SaleItem::whereorder_id($id)->wherefree_ticket(0)->count();
    		if($objsaleorder->nationality == "local"){
    			$localprice= SaleItem::whereorder_id($id)->wherefree_ticket(0)->pluck('price');
    			$grand_total =$ticketcount * $localprice;
    		}
    		else{
    			$foreignprice = SaleItem::whereorder_id($id)->wherefree_ticket(0)->pluck('foreign_price');
    			$grand_total =$ticketcount * $foreignprice;
    		}

		$agent_commission=SaleOrder::whereid($id)->pluck('agent_commission');
		$agent_commission = $ticketcount * $agent_commission;
		$total_amount=$grand_total-$agent_commission;

    	if($total_amount >0){
    		$current_balance=0;
    		$balance=0;
    		$debit=0;
            
    		$objagentdeposit=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id', 'desc')->first();
    		if($objagentdeposit){
    			$current_balance= ($objagentdeposit->balance + $payment_amount) - $objagentdeposit->debit;
    		}else{
    			$current_balance=$payment_amount;
    		}

    		$objdepositpayment_trans=new AgentDeposit();
    		$objdepositpayment_trans->agent_id=$agent_id;
    		$objdepositpayment_trans->operator_id=$operator_id;
    		$objdepositpayment_trans->total_ticket_amt=$total_amount;
    		$today=date("Y-m-d");
    		$objdepositpayment_trans->pay_date=$today;
    		$objdepositpayment_trans->payment=$payment_amount;
    		
    		$objdepositpayment_trans->deposit=0;
    		$balance=$current_balance - $total_amount;
    		$agentdeposit=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id','desc')->first();
    		if($agentdeposit){
    			$objdepositpayment_trans->deposit=$agentdeposit->balance;
    		}

    		$objdepositpayment_trans->balance=$balance;
    		$objdepositpayment_trans->debit=0;
    		$objdepositpayment_trans->save();

			$response =SaleOrder::whereid($id)
							->wherecash_credit(2)
							->update(array('cash_credit'=>1));
    		$message['status']=1;
    		$message['message']="Success transaction.";

    		
    		// return Response::json($message);
    	}else{
    		$response['status']=0;
    		$response['message']='There is no debit.';
    		// return Response::json($response);
    	}

    	$objsaleorder=SaleOrder::find($id);
		$objsaleorder->booking=0;
		$objsaleorder->update();
		$message="Successfully Comfirm order.";
		return Redirect::to('/report/booking')->with('message',$message);
    }



	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function getBookingDelete($id)
	{
		$saleOrder = SaleOrder::whereid($id)->first();
		if($saleOrder){
			SaleOrder::find($id)->delete();
		}
		
		$message="Successfully delete one record.";
		return Redirect::to('/report/booking')->with('message',$message);
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