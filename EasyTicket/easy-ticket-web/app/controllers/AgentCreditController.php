<?php

class AgentCreditController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	function msort($array, $key, $sort_flags = SORT_REGULAR, $order) {
	    if (is_array($array) && count($array) > 0) {
	        if (!empty($key)) {
	            $mapping = array();
	            foreach ($array as $k => $v) {
	                $sort_key = '';
	                if (!is_array($key)) {
	                    $sort_key = $v[$key];
	                } else {
	                    // @TODO This should be fixed, now it will be sorted as string
	                    foreach ($key as $key_key) {
	                        $sort_key .= $v[$key_key];
	                    }
	                    $sort_flags = SORT_STRING;
	                }
	                $mapping[$k] = $sort_key;
	            }
	            switch ($order) {
		            case SORT_ASC:
		                asort($mapping, $sort_flags);
		            break;
		            case SORT_DESC:
		                arsort($mapping, $sort_flags);
		            break;
		        }
	            // asort($mapping, $sort_flags);
	            // arsort($mapping, $sort_flags);
	            $sorted = array();
	            foreach ($mapping as $k => $v) {
	                $sorted[] = $array[$k];
	            }
	            return $sorted;
	        }
	    }
	    return $array;
	}

	public function getCreditSale()
	 {
    	$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
    	
    	$objagentlist=Agent::whereoperator_id($operator_id)->orderBy('name')->get();
    	if($objagentlist){
    		$i=0;
    		foreach ($objagentlist as $row) {
    			$objagentlist[$i]=$row;
    			$objorders=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->wherecash_credit(2)->lists('id');
    			$credit=0;
    			$grand_total=0;
    			$agent_commission=0;
    			if($objorders){
    				$nationality = SaleOrder::whereid($objorders[0])->pluck('nationality');
    				if($nationality == "local")
    					$grand_total=SaleItem::wherein('order_id',$objorders)->wherefree_ticket(0)->sum('price');
    				else
    					$grand_total=SaleItem::wherein('order_id',$objorders)->wherefree_ticket(0)->sum('foreign_price');

    				$agent_commission=SaleOrder::wherein('id',$objorders)->sum('agent_commission');
    				$credit=$grand_total-$agent_commission;
    			}
    			$objagentdeposit=AgentDeposit::whereagent_id($row->id)->orderBy('id','desc')->first();
    			$objagentlist[$i]['credit']=$credit;
    			$objagentlist[$i]['agent_commission']=$agent_commission;

                if($objagentdeposit)
    			$objagentlist[$i]['to_pay_credit']=$credit ? $credit : $objagentdeposit->total_ticket_amt;
    			else
                    $objagentlist[$i]['to_pay_credit']=$credit;
                $objagentlist[$i]['deposit_balance']=0;
    			if($objagentdeposit)
    			$objagentlist[$i]['deposit_balance']=$objagentdeposit->balance;
    			$i++;
    		}
    	}
    	$response=array();
    	$response=$objagentlist;
    	// return Response::json($objagentlist);
		return View::make('busreport.agentcredit.list', array('response'=>$response));
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function getActionForm($id)
	{
		$response=array();
		$response=Agent::whereid($id)->first();
        $operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
        $trips=Trip::whereoperator_id($operator_id)->with(array('from_city','to_city','busclass'))->get();
        // return Response::json($trips);
		return View::make('busreport.agentcredit.actionform', array('response'=>$response,'trips'=>$trips));
	}


	public function postAgentDeposit()
	{
    	$agent_id 		=Input::get('agent_id');
    	$operator_id 	=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
    	$deposit_date	=date('Y-m-d');
    	$deposit     	=Input::get('deposit');
    	$objagentdeposit=new AgentDeposit();
    	try {
    		$check_exiting=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id','desc')->first();
    		$objagentdeposit->agent_id=$agent_id;
    		$objagentdeposit->operator_id=$operator_id;
	    	$objagentdeposit->deposit_date=$deposit_date;
	    	if(!$check_exiting){
	    		$objagentdeposit->payment=0;
	    		$objagentdeposit->deposit=$deposit;
	    		$objagentdeposit->balance=$deposit;
	    	}else{
	    			$objagentdeposit->deposit=$check_exiting->balance;
	    			$objagentdeposit->payment=$deposit;
	    			$balance =$check_exiting->balance;
	    			$objagentdeposit->balance= $deposit + $balance;	
	    	}

	    	
	    	$objagentdeposit->save();
	    	$message="Successfully save one record.";
    		// return Response::json($response);
    		return Redirect::to('report/agentcredit/'.$agent_id)->with('message',$message);
    	} catch (Exception $e) {
    		$response['status']=0;
    		$response['message']=$e->errorInfo[2];
    		return Response::json($response);
    	}
    }

    public function postAgentCommission()
    {
        $agent_id       =Input::get('agent_id');
        $trip_id        =Input::get('trip_id');
        $commission_id  =Input::get('commission_id');
        $commission     =Input::get('commission');
        $check_exiting=AgentCommission::whereagent_id($agent_id)->wheretrip_id($trip_id)->first();
        if($check_exiting){
            $check_exiting->commission_id=$commission_id;
            $check_exiting->commission=$commission;
            $check_exiting->update();
            $message='Successfully Update one record.';
            return Redirect::to('/report/agentcredit/'.$agent_id)->with('message',$message);
        }
        $objagentcommission=new AgentCommission();
        $objagentcommission->agent_id=$agent_id;
        $objagentcommission->trip_id=$trip_id;
        $objagentcommission->commission_id=$commission_id;
        $objagentcommission->commission=$commission;
        $objagentcommission->save();
        $message='Successfully save one record.';
        return Redirect::to('/report/agentcredit/'.$agent_id)->with('message',$message);
    }

    public function getAgentCommission($id)
    {
        $operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
        $trip_ids=Trip::whereoperator_id($operator_id)->lists('id');
        $response=array();
        if($trip_ids){
            $response=AgentCommission::whereagent_id($id)->wherein('trip_id',$trip_ids)
                        ->with(array(
                            'agent'=>function($query){
                                $query->addSelect(array('id','name'));
                            },
                            'trip',
                            'commissiontype'
                        ))
                        ->get();
        }
        $i=0;
        if($response){
            foreach ($response as $row) {
                $from=City::whereid($row->trip->from)->pluck('name');
                $to=City::whereid($row->trip->to)->pluck('name');
                $response[$i]['tripname']=$from.'-'.$to;
                $i++;
            }
        }

        // return Response::json($response);
        return View::make('busreport.agentcredit.commissionlist', array('response'=>$response));
    }


    public function postAgentOldCredit()
	{
    	$agent_id 		=Input::get('agent_id');
    	$operator_id 	=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
    	$deposit_date	=date('Y-m-d');
    	$credit     	=Input::get('credit');
    	$objagentdeposit=new AgentDeposit();
    	try {
    		$check_exiting=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id','desc')->first();
    		$objagentdeposit->agent_id=$agent_id;
    		$objagentdeposit->operator_id=$operator_id;
	    	$objagentdeposit->deposit_date=$deposit_date;
	    	if(!$check_exiting){
	    		$objagentdeposit->payment=0;
	    		$objagentdeposit->deposit=0;
	    		$objagentdeposit->balance='-'.$credit;
                $objagentdeposit->total_ticket_amt=0;
	    		$objagentdeposit->debit='#'.$credit;

	    	}else{
	    		$objagentdeposit->payment=0;
                $objagentdeposit->deposit=0;
	    		$objagentdeposit->total_ticket_amt=$check_exiting->total_ticket_amt;
	    		$objagentdeposit->balance=$check_exiting->balance - $credit;
    			$objagentdeposit->debit=$objagentdeposit->debit + $credit;
	    	}
	    	$objagentdeposit->save();
	    	$message="Successfully save one record.";
    		// return Response::json($response);
    		return Redirect::to('report/agentcredit/'.$agent_id)->with('message',$message);
    	} catch (Exception $e) {
    		$message=$e->errorInfo[2];
    		return Redirect::to('report/agentcredit/'.$agent_id)->with('message',$message);

    	}
    }

	public function getAgentCreditSaleList($agent_id)
	{
		$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
    	$response =SaleOrder::where('operator_id','=',$operator_id)
    							->where('agent_id','=',$agent_id)
    							->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
    							->orderBy('orderdate','asc')
								->get(array('id', 'orderdate', 'agent_id', 'operator_id'));

    	$filter=array();
    	// for from to filter
    	
		if($response){
    		$i=0;
    		// $objbus=BusOccurance::wherefrom($from)->whereto($to)->pluck('id');
    		foreach ($response as $row) {
    			$response[$i]=$row;
    			$trip='-';
    			$price=0;
    			$amount=0;
    			$tickets=count($row->saleitems);
    			$saleitems=array();
    			if(count($row->saleitems)>0){
    				$objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
    				if($objbusoccurance){
    					$from=City::whereid($objbusoccurance->from)->pluck('name');
    					$to=City::whereid($objbusoccurance->to)->pluck('name');
    					$trip=$from.'-'.$to;
    					$price= $objbusoccurance->price;
    					$amount= $objbusoccurance->price * $tickets;
    				}
    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
    				$response[$i]['customer']=$objorderinfo->name;
    				$response[$i]['phone']=$objorderinfo->phone;
    			}else{
    				$response[$i]['customer']='-';
    				$response[$i]['phone']='-';
    			}
				$operator = Operator::whereid($row->operator_id)->pluck('name');
				$trip_id  = Busoccurance::whereid($row['saleitems'][0]['busoccurance_id'])->pluck('trip_id');
				$objagent = AgentCommission::whereagent_id($row['agent_id'])->wheretrip_id($trip_id)->first();
    			$response_value['operator']=$operator;
    			$response_value['agent']='-';
    			$response_value['agent_commission']=0;
    			if($objagent){
					$response_value['agent']=$objagent->name;
					$commissiontype=CommissionType::whereid($objagent->commission_id)->pluck('name');
					$response_value['commission']=$objagent->commission;	
					if(strtolower($commissiontype)=='fixed'){
						$response_value['commission_type']='fixed';	
						$response_value['agent_commission']=$objagent->commission * $tickets ;	
					}else{
						$response_value['commission_type']='percentage';	
						$response_value['agent_commission']=($amount * $objagent->commission) / 100;	
					}
				}else{
					$response_value['commission']= Trip::whereid($trip_id)->pluck('commission');
					$response_value['commission_type']='trip';	
					$response_value['agent_commission']= SaleOrder::whereid($row['id'])->pluck('agent_commission');
				}

    			$response[$i]['trip']=$trip;
    			$response[$i]['total_ticket']=$tickets;
    			$response[$i]['price']=$price;
    			$response[$i]['amount']=$amount;
    			$response[$i]['commission']=$response_value['commission'];
    			$response[$i]['grand_total']=($amount - $response_value['agent_commission']);
    			$i++;
    		}
    	}
    	$agent_name=Agent::whereid($agent_id)->pluck('name');
    	$agent_balance=AgentDeposit::whereagent_id($agent_id)->orderBy('id','desc')->pluck('balance');
    	// return Response::json($response);
    	return View::make('busreport.agentcredit.creditsalelist', array('response'=>$response, 'agent_name'=>$agent_name,'agent_balance'=>$agent_balance));
    	
	}

	public function postCreditPayment()
	{
    	$agent_id=Input::get('agent_id');
    	$operator_id=Input::get('operator_id');
    	$paywithdeposit=Input::get('paywithdeposit');
    	$payment_amount=Input::get('payment_amount') ? Input::get('payment_amount') : 0;
    	$order_ids=Input::get('order_id');
    	if(count($order_ids)==0){
    		return Redirect::to('report/agentcreditsales/'.$agent_id);
    	}
    	$response=array();
    	$total_amount=0;
    	
    	$response=$order_ids;
    	$grand_total = 0;
    	foreach ($response as $id) {
    		$nationality = SaleOrder::whereid($id)->pluck('nationality');
    		if($nationality == "local")
    			$grand_total += SaleItem::whereorder_id($id)->wherefree_ticket(0)->sum('price');
    		else
    			$grand_total += SaleItem::whereorder_id($id)->wherefree_ticket(0)->sum('foreign_price');
    	}

		$agent_commission=SaleOrder::wherein('id', $response)->sum('agent_commission');
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

    		if($order_ids){
    			$response =SaleOrder::wherein('id',$order_ids)
    							->wherecash_credit(2)
    							->update(array('cash_credit'=>1));
    		}

    		$message['status']=1;
    		$message['message']="Success transaction.";
    		return Redirect::to('report/agentcreditsales/'.$agent_id);
    	}else{
    		$response['status']=0;
    		$response['message']='There is no debit.';
    		return Response::json($response);
    	}
    }

    public function getPaymentTransaction($agent_id)
    {
    	$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$objpayment=AgentDeposit::whereagent_id($agent_id)
							->whereoperator_id($operator_id)
							->orderBy('id','desc')
							->get();
	
    	if($objpayment){
    		$i=0;
    		foreach ($objpayment as $row) {
    			$objpayment[$i]=$row;
    			$objpayment[$i]['agent']=Agent::whereid($row->agent_id)->pluck('name');
    			$i++;
    		}
    	}
    	//return Response::json($objpayment);
    	return View::make('busreport.agentcredit.paymenttransactions', array('response'=>$objpayment));
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