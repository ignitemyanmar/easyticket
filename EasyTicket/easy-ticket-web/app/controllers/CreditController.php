<?php

class CreditController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	
    public function index()
    {
        $min_amount =(!Input::get('min_amount')) ? -1000000 : Input::get('min_amount');
        $max_amount =(!Input::get('max_amount')) ? 1000000 : Input::get('max_amount') ;
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
        $operator_id=$this->myGlob->operator_id;
        $agent_ids  =$this->myGlob->agent_ids;
        $objagent_groupid=array();
        
        if($groupid){
            if($groupid =="All"){
                $objagent_groupid=AgentGroup::lists('id');
            }else{
                $objagent_groupid[]=$groupid;
            }
        }

        if($this->myGlob->agentgroup_id){
            $objagent_groupid=array();
            $objagent_groupid[]=$this->myGlob->agentgroup_id;
        }

        if($start_date && $end_date && $end_date !="All"){
            if($agent_ids){
                $transaction_group_ids=AgentDeposit::wherein('agent_id',$agent_ids)->where('pay_date','>=',$start_date)->where('pay_date','<=',$end_date)->lists('agentgroup_id');
            }else{
                $transaction_group_ids=AgentDeposit::where('pay_date','>=',$start_date)->where('pay_date','<=',$end_date)->lists('agentgroup_id');
            }
        }elseif($start_date && $start_date !="All" && $end_date=="All"){
            if($agent_ids){
                $transaction_group_ids=AgentDeposit::wherein('agent_id',$agent_ids)->where('pay_date','>=',$start_date)->lists('agentgroup_id');
            }else{
                $transaction_group_ids=AgentDeposit::where('pay_date','>=',$start_date)->lists('agentgroup_id');
            }
        }else{
            if($agent_ids){
                $transaction_group_ids=AgentDeposit::wherein('agent_id',$agent_ids)->lists('agentgroup_id');
            }else{
                $transaction_group_ids=AgentDeposit::lists('agentgroup_id');
            }
        }

        $transaction_group_ids=array_intersect($objagent_groupid, $transaction_group_ids);

        $objagentgrouplist=array();
        if($transaction_group_ids){
            if($agent_ids){
                $objagentgrouplist=AgentGroup::whereid($this->myGlob->agentgroup_id)->wherein('id',$transaction_group_ids)->with(array('agents'))->get();
            }else{
                $objagentgrouplist=AgentGroup::wherein('id',$transaction_group_ids)->with(array('agents'))->get();
            }
        }
        if($objagentgrouplist){
            foreach ($objagentgrouplist as $grp_key=>$res_agentgroup) {
                //For Calculate Agent Group Remain Balance
                    $grouptotalcredit=0;
                    $grouptotalcredit=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->sum('payment');
                    
                    $child_totalcredits=0;
                    $ch_agent_ids=array();
                    if($res_agentgroup->id){
                        $ch_agent_ids=Agent::whereagentgroup_id($res_agentgroup->id)->lists('id');
                    }
                    if($ch_agent_ids){
                        foreach ($ch_agent_ids as $chagent_id) {
                            $child_totalcredits +=AgentDeposit::whereagent_id($chagent_id)->orderBy('id','desc')->pluck('balance');
                        }
                    }

                    $rm_balances= $remain_balances=$grouptotalcredit + $child_totalcredits;

                    $remain_balances=number_format( $remain_balances , 0 , '.' , ',' );
                    $noti="";
                    if($rm_balances <= 0){
                        $noti="noti";
                    }elseif($rm_balances > 0 && $rm_balances <= 300000){
                        $noti="alerts";
                    }elseif($rm_balances > 300000){
                        $noti="available";
                    }else{

                    }
                //End Calculate Agent Group Remain Balance

                //for showing with Remain Balance 
                $groupname =$res_agentgroup->name;

                $L_openingbalance=0;  
                $L_colsingbalance=0;  
                $L_receivable=0;  
                $L_totalreceipt=0;  
                if(count($res_agentgroup->agents)>0){
                    foreach ($res_agentgroup->agents as $key=> $objagent) {
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

                            $objagentgrouplist[$grp_key]['agents'][$key]['opening_balance']=$opening_balance;
                            $objagentgrouplist[$grp_key]['agents'][$key]['receivable']=$receivable;
                            $objagentgrouplist[$grp_key]['agents'][$key]['receipt']=$payments;
                            $objagentgrouplist[$grp_key]['agents'][$key]['closing_balance']=$closing_balance=($opening_balance + $payments) - $receivable;
                            $objagentgrouplist[$grp_key]['agents'][$key]['grand_reciept']=$grand_reciept=$payments;
                            $objagentgrouplist[$grp_key]['agents'][$key]['grand_receivable_total']=$grandtotalcredit=$receivable;
                            
                            $parameter='';
                            if(!$objagent['agentgroup_id']){
                                $parameter='?access_token='.Auth::user()->access_token.'&agent_id='.$objagent['id']."&start_date=".$start_date."&end_date=".$end_date;
                            }else{
                                $parameter="?access_token=".Auth::user()->access_token."&start_date=".$start_date."&end_date=".$end_date;
                            }
                            $groupheader='<a class="btn mini purple blue-stripe" href="/report/agentcreditlist/group/'.$objagent->agentgroup_id.$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                            $L_openingbalance +=$opening_balance;
                            $L_receivable     +=$receivable;
                            $L_colsingbalance +=$temp['closing_balance'];
                        }else{
                            $objagentgrouplist[$grp_key]['agents'][$key]['opening_balance']=0;
                            $objagentgrouplist[$grp_key]['agents'][$key]['receivable']=0;
                            $objagentgrouplist[$grp_key]['agents'][$key]['receipt']=0;
                            $objagentgrouplist[$grp_key]['agents'][$key]['closing_balance']=0;
                            $objagentgrouplist[$grp_key]['agents'][$key]['grand_reciept']=0;
                            $objagentgrouplist[$grp_key]['agents'][$key]['grand_receivable_total']=0;

                            /*$parameter='';
                            if($groupname==""){
                                $parameter='?agent_id='.$objagent['id'];
                            }*/
                            $parameter='';
                            if(!$objagent['agentgroup_id']){
                                $parameter='?agent_id='.$objagent['id']."&start_date=".$start_date."&end_date=".$end_date;
                            }else{
                                $parameter="?start_date=".$start_date."&end_date=".$end_date;
                            }

                            $closing_balance=$grand_reciept=$grandtotalcredit=$opening_balance=0;
                            $groupheader='<a class="btn mini purple blue-stripe" href="/report/agentcreditlist/group/'.$objagent->agentgroup_id.$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                        }
                        $objagentgrouplist[$grp_key]['agents'][$key]['groupheader']=$groupname. $groupheader;
                    }
                }

                $payment_transactions=array();
                if($start_date && $end_date && $end_date !="All"){
                    $payment_transactions=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->where('payment','>',0)/*->where('pay_date','>=',$start_date)*/->where('pay_date','<=',$end_date)->orderBy('id','pay_date')->get(array('pay_date','payment'));
                    $L_totalreceipt=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->where('payment','>',0)/*->where('pay_date','>=',$start_date)*/->where('pay_date','<=',$end_date)->orderBy('id','pay_date')->sum('payment');
                }elseif($start_date && $start_date !="All" && $end_date=="All"){
                    $payment_transactions=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->where('payment','>',0)/*->where('pay_date','>=',$start_date)*/->orderBy('id','pay_date')->get(array('pay_date','payment'));
                    $L_totalreceipt=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->where('payment','>',0)/*->where('pay_date','>=',$start_date)*/->orderBy('id','pay_date')->sum('payment');
                }else{
                    $payment_transactions=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->where('payment','>',0)->orderBy('id','pay_date')->get(array('pay_date','payment'));
                    $L_totalreceipt=AgentDeposit::whereagentgroup_id($res_agentgroup->id)->where('payment','>',0)/*->where('pay_date','>=',$start_date)*/->orderBy('id','pay_date')->sum('payment');
                }

                $total_receiptamt=$L_totalreceipt ? $L_totalreceipt : 0;
                $objagentgrouplist[$grp_key]['L_openingbalance']=$L_openingbalance;
                $objagentgrouplist[$grp_key]['L_receivable']=$L_receivable;
                $objagentgrouplist[$grp_key]['L_colsingbalance']=$L_colsingbalance;
                $objagentgrouplist[$grp_key]['remain_balances']=$total_receiptamt + $L_colsingbalance;//$remain_balances
                $objagentgrouplist[$grp_key]['L_totalreceipt']=$total_receiptamt;
                $objagentgrouplist[$grp_key]['payment_transactions']=$payment_transactions->toarray();
            }
        }
        // return Response::json($objagentgrouplist);

        $search['agentgroup']="";
        $agentgroup=array();
        if($agent_ids){
            $agentgroup=AgentGroup::whereid($this->myGlob->agentgroup_id)->whereoperator_id($operator_id)->get();
        }else{
            $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        }
        $search['agentgroup']=$agentgroup;
        $search['datestatus']=$datestatus;
        $search['start_date']=Input::get('start_date');
        $search['end_date']=$end_date;

        $search['agentgroup_id']=$groupid;
        $search['agent_id']=$agent_id;
        $search['min_amount']=$min_amount;
        $search['max_amount']=$max_amount;
        $search['agent']='';
        // return Response::json($objagentgrouplist);
        return View::make('busreport.agentcredit.list', array('response'=>$objagentgrouplist,'search'=>$search));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function second($groupid)
	{
        $operator_id=$this->myGlob->operator_id;
        $agentgroup_id =$this->myGlob->agentgroup_id;
        $glob_agent_ids     =$this->myGlob->agent_ids;

        $agent_id=Input::get('agent_id');
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');
        if($start_date !="All"){
            $start_date=Input::get('start_date') ? date('Y-m-d',strtotime(Input::get('start_date'))) : "All";
            $end_date=Input::get('end_date') ? date('Y-m-d',strtotime(Input:: get('end_date'))) :  "All";
        }
        $agent_ids=$objagentList=array();
        if($glob_agent_ids){
            $agent_ids=$glob_agent_ids;
        }else{
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
        }

        if($agent_ids)
            $objagentList=Agent::wherein('id',$agent_ids)->whereoperator_id($operator_id)->orderBy('name')->get();
        
        if($objagentList){
            $groupname=AgentGroup::whereid($groupid)->pluck('name');
            $i=0;
            foreach($objagentList as $objagent){
                $agent_group_id=$objagent->agentgroup_id ? $objagent->agentgroup_id : '';
                $objagentList[$i]=$objagent;
                $objagentList[$i]['agentgroup_name']=$groupname;
                $L_agent_id=$agent_group_id ? "All" : $objagent->id;
                $groupheader='<a class="btn mini blue green-stripe" href="/report/agentcreditlist/paymentdetail/'.$L_agent_id.'?access_token='.Auth::user()->access_token.'&agentgroup_id='.$agent_group_id.'start_date='.$start_date.'&end_date='.$end_date.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                $groupname=AgentGroup::whereid($objagent->agentgroup_id)->pluck('name');
                $groupname=$groupname ? $groupname : $objagent->name;

                //For Calculate Agent Group Remain Balance
                    $grouptotalcredit=0;
                    if($objagent->agentgroup_id)
                        $grouptotalcredit=AgentDeposit::whereagentgroup_id($objagent->agentgroup_id)->sum('payment');
                    
                    $child_totalcredits=0;
                    $ch_agent_ids=array();
                    if($objagent->agentgroup_id){
                        $ch_agent_ids=Agent::whereagentgroup_id($objagent->agentgroup_id)->lists('id');
                    }else{
                        $ch_agent_ids[]=$objagent->id;
                    }
                    if($ch_agent_ids){
                        foreach ($ch_agent_ids as $chagent_id) {
                            $child_totalcredits +=AgentDeposit::whereagent_id($chagent_id)->orderBy('id','desc')->pluck('balance');
                        }
                    }

                    $rm_balances= $remain_balances=$grouptotalcredit + $child_totalcredits;

                    $remain_balances=number_format( $remain_balances , 0 , '.' , ',' );
                    $noti="";
                    if($rm_balances <= 0){
                        $noti="noti";
                    }elseif($rm_balances > 0 && $rm_balances <= 300000){
                        $noti="alerts";
                    }elseif($rm_balances > 300000){
                        $noti="available";
                    }else{

                    }
                //End Calculate Agent Group Remain Balance

                //for showing with Remain Balance 
                $groupname .=' ==> Remain Balance (<span class="'.$noti.'">'.str_replace('-', '', $remain_balances).'</span>)';

                $objagentList[$i]['group_header']=$groupname.$groupheader;
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
        $search['agent_id']=$agent_id ? $agent_id : "All";
        $search['agent']='';
        return View::make('busreport.agentcredit.agentgroupcreditbydate', array('response'=>$objagentList,'search'=>$search));
    }

    public function detail($agent_id)
    {
        $glob_agentgroup_id  =$this->myGlob->agentgroup_id;
        $glob_agent_ids      =$this->myGlob->agent_ids;

        $groupid=Input::get('agentgroup_id');
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');
        if($start_date !="All"){
            $start_date=Input::get('start_date') ? date('Y-m-d',strtotime(Input::get('start_date'))) : "All";
            $end_date=Input::get('end_date') ? date('Y-m-d',strtotime(Input:: get('end_date'))) :  "All";
        }
        // dd($start_date); 
        $agent_ids=$objagentList=array();
        if($glob_agent_ids){
            $agent_ids =$glob_agent_ids;
            $groupid =$glob_agentgroup_id;
        }else{
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
                /*$groupheader='<a class="btn mini blue green-stripe" href="/report/agentcreditlist/paymentdetail/'.$objagent['id'].'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                $objagentList[$i]['group_header']=$objagent->name.$groupheader;*/
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
                                $order_id=json_decode($order_ids);
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

                                $temp['deleted_flag'] = $rows->deleted_flag;
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