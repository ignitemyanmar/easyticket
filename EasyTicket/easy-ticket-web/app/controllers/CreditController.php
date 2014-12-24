<?php

class CreditController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $datestatus=Input::get('cbodate') ? Input::get('cbodate') : "All" ;
		$groupid=Input::get('agentgroup') ? Input::get('agentgroup') : "All";
        $agent_id=Input::get('agent_id');
        
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');
        if($start_date !="All"){
            $start_date=Input::get('start_date') ? date('Y-m-d',strtotime(Input::get('start_date'))) : "All";
            $end_date=Input::get('end_date') ? date('Y-m-d',strtotime(Input:: get('end_date'))) :  "All";
        }

        $agent_ids=$objagentList=array();
        if($groupid){
            if($groupid !="All"){
                $agent_ids=Agent::whereagentgroup_id($groupid)->lists('id');
            }
            else{
                $agent_ids=Agent::lists('id');
            }
        }
        if($agent_id && $agent_id !="All"){
            $agent_ids=array();
            $agent_ids[]=$agent_id;
        }
        $operator_id=$this->myGlob->operator_id;
        if($agent_ids)
            $objagentList=Agent::wherein('id',$agent_ids)->whereoperator_id($operator_id)->orderBy('name')->get();

        if($objagentList){
            $i=0;$last=null; $groupheader=null;
            $closing_balance=$grand_reciept=$grandtotalcredit=$opening_balance=0;
            foreach($objagentList as $objagent){
                $opening_balance=0;
                $groupname=AgentGroup::whereid($objagent->agentgroup_id)->pluck('name');
                $groupname=$groupname ? $groupname : $objagent->name;
                $objagentList[$i]=$objagent;
                $objagentList[$i]['groupname']=$groupname;
                $transactions =$transactionsdates =array();
                $prev_payment = $prev_receivable=0;

                if($datestatus !="All" && $start_date){
                    $transactionsdates=AgentDeposit::whereagent_id($objagent->id)
                                    ->where('pay_date','>=', $start_date)
                                    ->where('pay_date','<=', $end_date)
                                    ->groupBy('pay_date')->orderBy('id','asc')->lists('pay_date');

                    $prev_payment=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('payment');
                    $prev_receivable=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('payment','=', 0)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('total_ticket_amt');
                    $opening_balance =$prev_payment - $prev_receivable;
                }else{
                    $transactionsdates=AgentDeposit::whereagent_id($objagent->id)
                                    ->groupBy('pay_date')->orderBy('id','asc')->lists('pay_date');                    
                }

                if($transactionsdates){
                    $opening_balance=$prev_payment - $prev_receivable;
                    $receivable=0;                    
                    $res_paydate='';
                    $payments=AgentDeposit::whereagent_id($objagent->id)->wherein("pay_date",$transactionsdates)->where('pay_date','!=', "0000-00-00")->sum('payment');
                    $receivable=AgentDeposit::whereagent_id($objagent->id)->wherepayment(0)->wherein("pay_date",$transactionsdates)->sum('total_ticket_amt');
                    $temp['pay_date']=$res_paydate;
                    $temp['opening_balance']=$opening_balance;
                    $temp['receivable']=$receivable;
                    $temp['receipt']=$payments;
                    $temp['closing_balance']=($opening_balance + $payments) - $receivable;
                    if($start_date !="All" || $datestatus !="All")
                    $transactions[]=$temp;
                    $objagentList[$i]['transactions']=$transactions;
                    
                    $objagentList[$i]['opening_balance']= $opening_balance;
                    $objagentList[$i]['receivable']=$receivable;
                    $objagentList[$i]['receipt']=$payments;
                    $objagentList[$i]['closing_balance'] = $closing_balance=($opening_balance + $payments) - $receivable;
                    $objagentList[$i]['grand_reciept'] =$grand_reciept=$payments;
                    $objagentList[$i]['grand_receivable_total']= $grandtotalcredit=$receivable;
                    
                    $parameter='';
                    if(!$objagent['agentgroup_id']){
                        $parameter='?agent_id='.$objagent['id']."&start_date=".$start_date."&end_date=".$end_date;
                    }else{
                        $parameter="?start_date=".$start_date."&end_date=".$end_date;
                    }
                    $groupheader ='<span>'.$groupname.'</span>';
                    $groupheader.='<a class="btn mini purple blue-stripe" href="/report/agentcreditlist/group/'.$objagent->agentgroup_id.$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                    
                }else{
                    $objagentList[$i]['opening_balance']=0;
                    $objagentList[$i]['receivable']=0;
                    $objagentList[$i]['receipt']=0;
                    $objagentList[$i]['closing_balance'] = 0;
                    $objagentList[$i]['grand_reciept'] =0;
                    $objagentList[$i]['grand_receivable_total']= 0;

                    $parameter='';
                    if($groupname==""){
                        $parameter='?agent_id='.$objagent['id'];
                    }
                    $closing_balance=$grand_reciept=$grandtotalcredit=$opening_balance=0;
                    $groupheader ='<span>'.$groupname.'</span>';
                    $groupheader.='<a class="btn mini purple blue-stripe" href="/report/agentcreditlist/group/'.$objagent->agentgroup_id.$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                }
                $objagentList[$i]['groupheader']=$groupheader;

                $i++;
            }
        }
        // return Response::json($objagentList);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']=$datestatus;
        $search['start_date']=Input::get('start_date');
        $search['end_date']=$end_date;

        $search['agentgroup_id']=$groupid;
        $search['agent_id']=$agent_id;
        $search['agent']='';
        return View::make('busreport.agentcredit.list', array('response'=>$objagentList,'search'=>$search));
    }

	/**
	 * 
	 *
	 * @return Response
	 */
	public function second($groupid)
	{

        $agent_id=Input::get('agent_id');
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');
        if($start_date !="All"){
            $start_date=Input::get('start_date') ? date('Y-m-d',strtotime(Input::get('start_date'))) : "All";
            $end_date=Input::get('end_date') ? date('Y-m-d',strtotime(Input:: get('end_date'))) :  "All";
        }
        $agent_ids=$objagentList=array();
        if($groupid){
            if($groupid !="All"){
                $agent_ids=Agent::whereagentgroup_id($groupid)->lists('id');
            }
            else{
                $agent_ids=Agent::lists('id');
            }
        }
        if($agent_id && $agent_id !="All"){
            $agent_ids=array();
            $agent_ids[]=$agent_id;
        }
        $operator_id=$this->myGlob->operator_id;
        if($agent_ids)
            $objagentList=Agent::wherein('id',$agent_ids)->whereoperator_id($operator_id)->orderBy('name')->get();
        
        if($objagentList){
            $groupname=AgentGroup::whereid($groupid)->pluck('name');
            $i=0;
           

            foreach($objagentList as $objagent){
                $objagentList[$i]=$objagent;
                $objagentList[$i]['agentgroup_name']=$groupname;
                $groupheader='<a class="btn mini blue green-stripe" href="/report/agentcreditlist/paymentdetail/'.$objagent['id'].'?start_date='.$start_date.'&end_date='.$end_date.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                $objagentList[$i]['group_header']=$objagent->name.$groupheader;
                $transactions=array();
                
                $prev_payment = $prev_receivable=0;
                
                if($start_date && $start_date !="All"){

                    $transactionsdates=AgentDeposit::whereagent_id($objagent->id)
                                    ->where('pay_date','>=', $start_date)
                                    ->where('pay_date','<=', $end_date)
                                    ->groupBy('pay_date')->orderBy('pay_date','asc')->lists('pay_date');

                    $prev_payment=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('payment');
                    $prev_receivable=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('payment','=', 0)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('total_ticket_amt');
                }else{
                    $transactionsdates=AgentDeposit::whereagent_id($objagent->id)
                                    ->groupBy('pay_date')->where('pay_date','!=','0000-00-00')->orderBy('pay_date','asc')->lists('pay_date');
                }
                
                if($transactionsdates){
                    $opening_balance=$prev_payment - $prev_receivable;
                    $receivable=0;                    
                    foreach ($transactionsdates as $res_paydate) {
                        $payments=AgentDeposit::whereagent_id($objagent->id)->wherepay_date($res_paydate)->sum('payment');
                        $receivable=AgentDeposit::whereagent_id($objagent->id)->wherepayment(0)->wherepay_date($res_paydate)->sum('total_ticket_amt');
                        
                        $temp['pay_date']=$res_paydate;
                        $temp['opening_balance']=$opening_balance;
                        $temp['receivable']=$receivable;
                        $temp['receipt']=$payments;
                        $temp['closing_balance']=($opening_balance + $payments) - $receivable;
                        $opening_balance=$temp['closing_balance'];
                        $receivable     =$payments;
                        
                        $transactions[]=$temp;
                    }
                }
                
                $objagentList[$i]['transactions']=$transactions;
                $i++;
            }
        }
        // return Response::json($objagentList);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']="All";
        $search['start_date']=$start_date;
        $search['end_date']=$end_date;
        $search['agentgroup_id']=$groupid;
        $search['agent_id']=$agent_id;
        $search['agent']='';
        return View::make('busreport.agentcredit.agentgroupcreditbydate', array('response'=>$objagentList,'search'=>$search));
    }

    public function detail($agent_id)
    {
        $groupid=Input::get('agentgroup_id');
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');
        if($start_date !="All"){
            $start_date=Input::get('start_date') ? date('Y-m-d',strtotime(Input::get('start_date'))) : "All";
            $end_date=Input::get('end_date') ? date('Y-m-d',strtotime(Input:: get('end_date'))) :  "All";
        }
        $agent_ids=$objagentList=array();
        if($groupid){
            if($groupid !="All"){
                $agent_ids=Agent::whereagentgroup_id($groupid)->lists('id');
            }
            else{
                $agent_ids=Agent::lists('id');
            }
        }
        if($agent_id && $agent_id != "All"){
            $agent_ids=array();
            $agent_ids[]=$agent_id;
            $groupid=Agent::whereid($agent_id)->pluck('agentgroup_id');

        }
        $operator_id=$this->myGlob->operator_id;
        if($agent_ids)
            $objagentList=Agent::wherein('id',$agent_ids)->whereoperator_id($operator_id)->orderBy('name')->get();
        
        if($objagentList){
            $groupname=AgentGroup::whereid($groupid)->pluck('name');
            $i=0;
            $balanceforward=0;
            foreach($objagentList as $objagent){
                $objagentList[$i]=$objagent;
                $objagentList[$i]['agentgroup_name']=$groupname;
                if($start_date && $start_date !="All"){
                    $prev_payment=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('payment');
                    $prev_receivable=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('payment','=', 0)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('total_ticket_amt');
                    $balanceforward =$prev_payment - $prev_receivable;

                }
                $transactions=array();
                
                $prev_payment = $prev_receivable=0;
                if($start_date && $start_date !="All"){
                    if(Input::get('end_date')=="All"){
                        $end_date=$start_date;
                    }
                    $transactionsdates=AgentDeposit::whereagent_id($objagent->id)
                                    ->where('pay_date','>=', $start_date)
                                    ->where('pay_date','<=', $end_date)
                                    ->groupBy('pay_date')->orderBy('pay_date','asc')->lists('pay_date');


                    $prev_payment=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->sum('payment');
                    $prev_receivable=AgentDeposit::whereagent_id($objagent->id)
                                        ->where('pay_date','<', $start_date)
                                        ->where('pay_date','!=', "0000-00-00")
                                        ->where('payment','=', 0)
                                        ->sum('total_ticket_amt');
                }else{
                    $transactionsdates=AgentDeposit::whereagent_id($objagent->id)
                                    ->groupBy('pay_date')->where('pay_date','!=','0000-00-00')->orderBy('pay_date','asc')->lists('pay_date');
                }
                
                $transactions = $temp =array();
                if($transactionsdates){
                    $opening_balance=$prev_payment - $prev_receivable;
                    $receivable=0;     

                    foreach ($transactionsdates as $res_paydate) {
                        $temp['pay_date']=$res_paydate;
                        $temp['voucher_no']='-';
                        $temp['trip']="-";
                        $temp['time']="-";
                        $temp['class']="-";
                        $temp['total_amount']=0;
                        $temp['agent_commission']=0;
                        $temp['balanceforward']=0;
                        
                        $objagentdeposit=AgentDeposit::wherepay_date($res_paydate)->whereagent_id($objagent->id)->whereoperator_id($operator_id)->get();
                        if(count($objagentdeposit)>0){
                            foreach ($objagentdeposit as $rows) {
                                if($rows->payment==0)
                                    $receivable =$rows->total_ticket_amt;
                                else
                                    $receivable =0;
                                $payments   =$rows->payment;
                                $order_ids=AgentDeposit::whereagent_id($objagent->id)->wherepay_date($res_paydate)->pluck('order_ids');
                                $order_id=json_decode($order_ids);//test 
                                if($order_id){
                                    $saleorders=SaleItem::whereorder_id($order_id[0])->get();
                                    if(count($saleorders)>0){
                                        $from=City::whereid($saleorders[0]['from'])->pluck('name');
                                        $to=City::whereid($saleorders[0]['to'])->pluck('name');
                                        $class=Classes::whereid($saleorders[0]['class_id'])->pluck('name');
                                        $time=Trip::whereid($saleorders[0]['trip_id'])->pluck('time');
                                        $temp['voucher_no']=$saleorders[0]['order_id'];
                                        $temp['trip']=$from.'-'.$to;
                                        $temp['time']=$time;
                                        $temp['class']=$class;
                                        $temp['total_amount']=0;
                                        $temp['agent_commission']=0;
                                        $temp['balanceforward']=0;
                                    }
                                }
                                $temp['opening_balance']=$opening_balance;
                                $temp['receivable']=$receivable;
                                $temp['receipt']=$payments;
                                $temp['closing_balance']=($opening_balance + $payments) - $receivable;
                                $opening_balance=$temp['closing_balance'];
                                $receivable     =$payments;
                                
                                $transactions[]=$temp;
                            }
                        }else{
                            $transactions[]=$temp;
                        }
                        
                    }

                }
                
                $objagentList[$i]['balanceforward']=$balanceforward;
                $objagentList[$i]['transactions']=$transactions;
                $i++;
            }
        }
        // return Response::json($objagentList);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']="All";
        $search['end_date']=date('d-m-Y');
        $search['start_date']=date('d-m-Y');
        $search['agentgroup_id']=$groupid;
        $search['agent_id']=$agent_id;
        $search['agent']='';
        return View::make('busreport.agentcredit.detail', array('response'=>$objagentList,'search'=>$search));
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