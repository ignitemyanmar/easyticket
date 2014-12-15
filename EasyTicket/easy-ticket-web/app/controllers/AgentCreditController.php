<?php
/***********************************************************
*   Agent Credit List
*   Agent Credit Search
*   Agent Group Credit Search Only one Group
*   Agent Credit Search Only one agent
*   Payment Transaction
*/

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

    // Agent Credit sale list
    /* format is
        {
            "1876": [
                {
                    "id": 608,
                    "agentgroup_id": 0,
                    "name": "1876",
                    "phone": "1876",
                    "address": "",
                    "commission_id": 1,
                    "commission": 0,
                    "user_id": 0,
                    "old_credit": 0,
                    "owner": 0,
                    "operator_id": 11,
                    "groupname": "1876",
                    "credit": 0,
                    "agent_commission": 0,
                    "to_pay_credit": 9700,
                    "deposit_balance": -124000
                }
            ],
        "Key":  [
                {
        
                }
            ]
        }
    */
	public function getCreditSaleold()
	{
    	$operator_id=$this->myGlob->operator_id;
    	$objagentlist=Agent::whereoperator_id($operator_id)->orderBy('name')->get();
    	if($objagentlist){
    		$i=0;
    		foreach ($objagentlist as $row) {
                $objagentlist[$i]=$row;
                $groupname=AgentGroup::whereid($row->agentgroup_id)->pluck('name');
    			$objagentlist[$i]['groupname']=$groupname ? $groupname : $row->name;

                $objorders=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->wherecash_credit(2)->lists('id');
    			// $objorders=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->wherecash_credit(2)->sum('total_amount');
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
        
        foreach ($objagentlist AS $arr) {
            $response[$arr['groupname']][] = $arr->toarray();
            
            if($arr['groupname'] ==$response[$arr['groupname']][0]['groupname'])
            {   
                $j=0;$topayamount=0;$topaycredit=0;
                $grandtotalcredit=0;
                foreach($response[$arr['groupname']] as $grouparray)
                {
                    $topayamount +=$grouparray['deposit_balance'];
                    $topaycredit +=$grouparray['credit'];
                    $response[$arr['groupname']][$j]['topayamount']=$topayamount;
                    $response[$arr['groupname']][$j]['topaycredit']=$topaycredit;
                    $j++; 
                }
                $grandtotalcredit=str_replace('-', "", $topayamount)+$topaycredit;
                $j=0;
                foreach($response[$arr['groupname']] as $grouparrays)
                {   $groupheader ='<span>'.$arr['groupname'].'</span>';
                    $groupheader.='<a class="btn mini purple blue-stripe" href="/report/agentcreditsales/492" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                    $groupheader.='<b style="float:right; margin-left:35px;">Closing Balance :'.str_replace('-', "", $grandtotalcredit).'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Receivable :'.str_replace('-', "", $topayamount).'</b>';
                    $groupheader.=' <b style="float:right; margin-left:35px;"> Receipt :'.str_replace('-', "", $topaycredit).'</b>';
                    $response[$arr['groupname']][$j]['groupheader']=$groupheader;
                    $j++; 
                }
            }

        }

    	return Response::json($response);
        // $response=$objagentlist;
        $search['agentgroup']="All";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['end_date']='';
        $search['datestatus']='All';
        $search['start_date']='';
        $search['agentgroup_id']='';
        $search['agent_id']='';
        $search['agent']='';
		return View::make('busreport.agentcredit.list', array('response'=>$response,'search'=>$search));
    }

    /**
    *  Agent Credit List
    */
    public function getCreditSaleMOD()
    {
        $operator_id=$this->myGlob->operator_id;
        $objagentlist=Agent::whereoperator_id($operator_id)->orderBy('name')->get();
        if($objagentlist){
            $i=0;
            foreach ($objagentlist as $row) {
                $objagentlist[$i]=$row;
                $groupname=AgentGroup::whereid($row->agentgroup_id)->pluck('name');
                $objagentlist[$i]['groupname']=$groupname ? $groupname : $row->name;

                $grand_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->sum('total_amount');
                $cash_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->wherecash_credit(1)->sum('total_amount');
                $credit_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->wherecash_credit(2)->sum('total_amount');
                $agentcommission_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->sum('agent_commission');
                $receivable_total=$grand_total-$agentcommission_total;
                $grand_cash_total=$cash_total-$agentcommission_total;
                
                $objagentlist[$i]['opening_balance']=0;
                $objagentlist[$i]['receivable']=$receivable_total;
                $objagentlist[$i]['receipt']=$grand_cash_total;
                $objagentlist[$i]['closing_balance']=$grand_cash_total-$receivable_total;
                $i++;
                
            }
        }
        $response=array();
        foreach ($objagentlist AS $arr) {
            // Grouping by Agent
            $response[$arr['groupname']][] = $arr->toarray();
            if($arr['groupname'] ==$response[$arr['groupname']][0]['groupname'])
            {   
                $j=0;$grand_reciept=0;$grand_receivable_total=0;
                $grandtotalcredit=0;
                $opening_balance=0;
                $closing_balance=0;
                foreach($response[$arr['groupname']] as $grouparray)
                {
                    $grand_reciept +=$grouparray['receipt'];
                    $grand_receivable_total +=$grouparray['receivable'];
                    $response[$arr['groupname']][$j]['grand_reciept']=$grand_reciept;
                    $response[$arr['groupname']][$j]['grand_receivable_total']=$grand_receivable_total;
                    $j++; 
                }
                $grandtotalcredit=$opening_balance+$grand_receivable_total;
                $closing_balance=$grand_reciept-$grandtotalcredit;
                $j=0;
                
                // For closing balance is "-" condition 
                if($closing_balance < 0) 
                    $closing_balance='('. $closing_balance .')';

                // For Agent group Header link
                foreach($response[$arr['groupname']] as $grouparrays)
                {   
                    $parameter='';
                    if($grouparrays['agentgroup_id']==0){
                        $parameter='?agent_id='.$grouparrays['id'];
                    }

                    $groupheader ='<span>'.$arr['groupname'].'</span>';
                    $groupheader.='<a class="btn mini purple blue-stripe" href="/report/agentcreditsales/group/'.$grouparrays['agentgroup_id'].$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                    $groupheader.='<b style="float:right; margin-left:35px;">Closing Balance :'. $closing_balance .'</b>';
                    $groupheader.=' <b style="float:right; margin-left:35px;"> Receipt :'.$grand_reciept.'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Receivable :'.$grandtotalcredit.'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Opening Balance :'.$opening_balance.'</b>';
                    $response[$arr['groupname']][$j]['groupheader']=$groupheader;
                    $j++; 
                }
            }

        }

        // return Response::json($response);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']='All';
        $search['end_date']=date('d-m-Y');
        $search['start_date']=date('d-m-Y');
        $search['agentgroup_id']='';
        $search['agent_id']='';
        $search['agent']='';
        return View::make('busreport.agentcredit.list', array('response'=>$response,'search'=>$search));
    }

    /**
    *  Agent Credit List All
    */
    public function getAgentCreditAll()
    {
        $operator_id=$this->myGlob->operator_id;
        $objagentlist=Agent::whereoperator_id($operator_id)->orderBy('name')->get();
        if($objagentlist){
            $i=0;
            foreach ($objagentlist as $row) {
                $objagentlist[$i]=$row;
                $groupname=AgentGroup::whereid($row->agentgroup_id)->pluck('name');
                $objagentlist[$i]['groupname']=$groupname ? $groupname : $row->name;

                $payments=AgentDeposit::whereagent_id($row->id)->sum('payment');
                $receivable=AgentDeposit::whereagent_id($row->id)->wherepayment(0)->sum('total_ticket_amt');

                /*$grand_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->sum('total_amount');
                $agentcommission_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->sum('agent_commission');
                $receivable_total=$grand_total-$agentcommission_total;
                */
                $receivable_total=$receivable;
                $opening_balance=0;
                $objagentlist[$i]['opening_balance']=0;
                $objagentlist[$i]['receivable']=$receivable_total;
                /*$payments=AgentDeposit::whereagent_id($row->id)->whereoperator_id($operator_id)->sum('payment');
                $deposit=AgentDeposit::whereagent_id($row->id)->wherepayment(0)->sum('total_ticket_amt');
                */// $deposit=AgentDeposit::whereagent_id($row->id)->whereoperator_id($operator_id)->sum('deposit');
                // return Response::json($deposit);
                // $total_reciept=$payments + $deposit;
                $total_reciept=$payments;
                $total_reciept=$payments;
                $objagentlist[$i]['receipt']=$total_reciept;
                // $objagentlist[$i]['closing_balance']=$total_reciept-$receivable_total;
                $objagentlist[$i]['closing_balance']=($opening_balance + $payments) - $receivable;
                $i++;
                
            }
        }
        $response=array();
        foreach ($objagentlist AS $arr) {
            // Grouping by Agent
            $response[$arr['groupname']][] = $arr->toarray();
            if($arr['groupname'] ==$response[$arr['groupname']][0]['groupname'])
            {   
                $j=0;$grand_reciept=0;$grand_receivable_total=0;
                $grandtotalcredit=0;
                $opening_balance=0;
                $closing_balance=0;
                foreach($response[$arr['groupname']] as $grouparray)
                {
                    $grand_reciept +=$grouparray['receipt'];
                    $grand_receivable_total +=$grouparray['receivable'];
                    $response[$arr['groupname']][$j]['grand_reciept']=$grand_reciept;
                    $response[$arr['groupname']][$j]['grand_receivable_total']=$grand_receivable_total;
                    $j++; 
                }
                $grandtotalcredit=$opening_balance+$grand_receivable_total;
                $closing_balance=$grand_reciept-$grandtotalcredit;
                $j=0;
                
                // For closing balance is "-" condition 
                if($closing_balance < 0) 
                    $closing_balance='('. $closing_balance .')';

                // For Agent group Header link
                foreach($response[$arr['groupname']] as $grouparrays)
                {   
                    $parameter='';
                    if($grouparrays['agentgroup_id']==0){
                        $parameter='?agent_id='.$grouparrays['id'];
                    }

                    $groupheader ='<span>'.$arr['groupname'].'</span>';
                    $groupheader.='<a class="btn mini purple blue-stripe" href="/report/agentcreditlist/group/'.$grouparrays['agentgroup_id'].$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                    $groupheader.='<b style="float:right; margin-left:35px;">Closing Balance :'. $closing_balance .'</b>';
                    $groupheader.=' <b style="float:right; margin-left:35px;"> Receipt :'.$grand_reciept.'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Receivable :'.$grandtotalcredit.'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Opening Balance :'.$opening_balance.'</b>';
                    $response[$arr['groupname']][$j]['groupheader']=$groupheader;
                    $j++; 
                }
            }

        }

        // return Response::json($response);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']='All';
        $search['end_date']=date('d-m-Y');
        $search['start_date']=date('d-m-Y');
        $search['agentgroup_id']='';
        $search['agent_id']='';
        $search['agent']='';
        return View::make('busreport.agentcredit.list', array('response'=>$response,'search'=>$search));
    }

    /**
    *  Agent Credit Search
    */
    public function getCreditSearch()
    {
        $datestatus=Input::get('cbodate');
        $start_date=Input::get('start_date');
        $end_date=Input::get('end_date');
        $agentgroup=Input::get('agentgroup');
        $agent_id=Input::get('agent_id');

        $operator_id=$this->myGlob->operator_id;

        // Change date format
        $start_date=date('Y-m-d', strtotime($start_date));
        $end_date=date('Y-m-d', strtotime($end_date));

        $agentids=array();
        $agentid =($agent_id=="All") ? 0 : $agent_id;

        // Getting Agent IDs
        if($agentgroup && !$agentid){
            if($agentgroup !="All")
                $agentids=Agent::whereagentgroup_id($agentgroup)->lists('id');
            else
                $agentids=Agent::lists('id');

        }elseif($agentgroup && $agentid){
            if($agentgroup !="All")
                $agentids=Agent::lists('id');
            else
                $agentids[]=$agentid;
        }else{
            $agentids[]=$agentid;
        }

        if($datestatus=="Custom" && $start_date && $end_date){
            $agent_ids=SaleOrder::wherein('agent_id',$agentids)->whereoperator_id($operator_id)
                            ->where('orderdate','>=',$start_date)
                            ->where('orderdate','<=',$end_date)
                            ->groupBy('agent_id')
                            ->lists('agent_id');
        }else{
            $agent_ids=Agent::lists('id');
        }
        
        

        $objagentlist=Agent::wherein('id',$agent_ids)->orderBy('name')->get();
        $prev_closing_balance=0;
        if($objagentlist){
            $i=0;
            foreach ($objagentlist as $row) {
                $objagentlist[$i]=$row;
                $groupname=AgentGroup::whereid($row->agentgroup_id)->pluck('name');
                $objagentlist[$i]['groupname']=$groupname ? $groupname : $row->name;

                if ($start_date && $end_date) {
                    $grand_total=SaleOrder::whereoperator_id($operator_id)
                                            ->whereagent_id($row->id)
                                            ->where('orderdate','>=',$start_date)
                                            ->where('orderdate','<=',$end_date)
                                            ->sum('total_amount');
                    $agentcommission_total=SaleOrder::whereoperator_id($operator_id)
                                            ->whereagent_id($row->id)
                                            ->where('orderdate','>=',$start_date)
                                            ->where('orderdate','<=',$end_date)
                                            ->sum('agent_commission');
                    $payments=AgentDeposit::whereagent_id($row->id)
                                            ->whereoperator_id($operator_id)
                                            ->where('pay_date','>=',$start_date)
                                            ->where('pay_date','<=',$end_date)
                                            ->sum('payment');
                    $payments +=AgentDeposit::whereagent_id($row->id)
                                            ->whereoperator_id($operator_id)
                                            ->where('pay_date','>=',$start_date)
                                            ->where('pay_date','<=',$end_date)
                                            ->where('payment','=',0)
                                            ->sum('deposit');

                } else {
                    $grand_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->sum('total_amount');
                    $agentcommission_total=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->sum('agent_commission');
                    $payments=AgentDeposit::whereagent_id($row->id)->whereoperator_id($operator_id)->sum('payment');
                }
                
                $receivable_total=$grand_total-$agentcommission_total;
                $objagentlist[$i]['opening_balance']=0;
                $objagentlist[$i]['receivable']=$receivable_total;
                // $deposit=AgentDeposit::whereagent_id($row->id)->whereoperator_id($operator_id)->sum('deposit');
                // return Response::json($deposit);
                $objagentlist[$i]['receipt']=$payments ? $payments : 0;
                $objagentlist[$i]['closing_balance']=$payments-$receivable_total;
                $i++;
                
            }
        }

        $response=array();
        foreach ($objagentlist AS $arr) {
            // Grouping by Agent
            $response[$arr['groupname']][] = $arr->toarray();
            if($arr['groupname'] ==$response[$arr['groupname']][0]['groupname'])
            {   
                $j=0;$grand_reciept=0;$grand_receivable_total=0;
                $grandtotalcredit=0;
                $opening_balance=0;
                $closing_balance=0;
                foreach($response[$arr['groupname']] as $grouparray)
                {
                    $grand_reciept +=$grouparray['receipt'];
                    $grand_receivable_total +=$grouparray['receivable'];
                    $response[$arr['groupname']][$j]['grand_reciept']=$grand_reciept;
                    $response[$arr['groupname']][$j]['grand_receivable_total']=$grand_receivable_total;
                    $j++; 
                }
                $grandtotalcredit=$opening_balance+$grand_receivable_total;
                $closing_balance=$grand_reciept-$grandtotalcredit;
                $j=0;
                
                // For closing balance is "-" condition 
                if($closing_balance < 0) 
                    $closing_balance='('. $closing_balance .')';

                // For Agent group Header link
                foreach($response[$arr['groupname']] as $grouparrays)
                {   
                    $parameter='';
                    if($grouparrays['agentgroup_id']==0){
                        $parameter='?agent_id='.$grouparrays['id'];
                    }

                    $groupheader ='<span>'.$arr['groupname'].'</span>';
                    $groupheader.='<a class="btn mini purple blue-stripe" href="/report/agentcreditlist/group/'.$grouparrays['agentgroup_id'].$parameter.'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                    $groupheader.='<b style="float:right; margin-left:35px;">Closing Balance :'. $closing_balance .'</b>';
                    $groupheader.=' <b style="float:right; margin-left:35px;"> Receipt :'.$grand_reciept.'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Receivable :'.$grandtotalcredit.'</b>';
                    $groupheader.='<b style="float:right; margin-left:35px;"> Opening Balance :'.$opening_balance.'</b>';
                    $response[$arr['groupname']][$j]['groupheader']=$groupheader;
                    $j++; 
                }
            }

        }

        // return Response::json($response);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']=$datestatus;
        $search['start_date']=$start_date;
        $search['end_date']=$end_date;
        $search['agentgroup_id']='';
        $search['agent_id']='';
        $search['agent']='';
        return View::make('busreport.agentcredit.list', array('response'=>$response,'search'=>$search));
    }

    /**
    *  Agent Group Credit Search Only one Group
    */
    public function getAgentGroupCredit($groupid)
    {
        $agent_id=Input::get('agent_id');
        $agent_ids=array();
        if($groupid){
            $agent_ids=Agent::whereagentgroup_id($groupid)->lists('id');
        }
        if($agent_id){
            $agent_ids=array();
            $agent_ids[]=$agent_id;
        }

        $operator_id=$this->myGlob->operator_id;
        $objagentList=Agent::wherein('id',$agent_ids)->whereoperator_id($operator_id)->orderBy('name')->get();
        
        if($objagentList){
            $groupname=AgentGroup::whereid($groupid)->pluck('name');
            $i=0;
            foreach($objagentList as $objagent){
                $objagentList[$i]=$objagent;
                $objagentList[$i]['agentgroup_name']=$groupname;
                $groupheader='<a class="btn mini blue green-stripe" href="/report/agentcreditsales/detail/'.$objagent['id'].'" style="float:right; right:0;margin-left:25px;">အေသးစိတ္ All</a>';
                $objagentList[$i]['group_header']=$objagent->name.$groupheader;
                $transactions=array();
                $transactionsdates=AgentDeposit::whereagent_id($objagent->id)->groupBy('pay_date')->orderBy('id','asc')->lists('pay_date');
                if($transactionsdates){
                    $opening_balance=0;
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
        $search['datestatus']='All';
        $search['end_date']=date('d-m-Y');
        $search['start_date']=date('d-m-Y');
        $search['agentgroup_id']=$groupid;
        $search['agent_id']=$agent_id;
        $search['agent']='';
        return View::make('busreport.agentcredit.agentgroupcreditbydate', array('response'=>$objagentList,'search'=>$search));
    }

    /**
    *  Agent Transaction Detail
    */
    public function getAgentTransactionDetail($id)
    {

    }

    /**
    *  Agent Credit Search Only one agent
    */
    public function getAgentCredit($id)
    {
        $operator_id=$this->myGlob->operator_id;
        $objagent=Agent::whereid($id)->whereoperator_id($operator_id)->orderBy('name')->first();
        if($objagent){
            $groupname=AgentGroup::whereid($objagent->agentgroup_id)->pluck('name');
            $objagent['agentgroup_name']=$groupname;
            $transactions=array();
            $objtransaction=AgentDeposit::whereagent_id($id)->get();
            if($objtransaction){
                $opening_balance=0;
                $receivable=0;
                foreach ($objtransaction as $rows) {
                    $ticketamount=$rows->total_ticket_amt;
                    if($rows->payment>0)
                        $ticketamount=0;

                    $temp['pay_date']=$rows->pay_date;
                    $temp['opening_balance']=$opening_balance;
                    $temp['receivable']=$ticketamount;
                    $temp['receipt']=$rows->payment;
                    
                    $temp['closing_balance']=($opening_balance + $rows->payment) - $ticketamount;
                    $opening_balance=$temp['closing_balance'];
                    $receivable     =$ticketamount;
                    $transactions[]=$temp;
                }

            }
            $grouping=array();
            if($transactions){
                foreach ($transactions AS $arr) {
                    $grouping[$arr['pay_date']][] = $arr;
                }
            }
            $objagent['transactions']=$grouping;
        }


        // return Response::json($objagent);
        $search['agentgroup']="";
        $agentgroup=array();
        $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
        $search['agentgroup']=$agentgroup;
        $search['datestatus']='All';
        $search['end_date']=date('d-m-Y');
        $search['start_date']=date('d-m-Y');
        $search['agentgroup_id']='';
        $search['agent_id']='';
        $search['agent']='';
        return View::make('busreport.agentcredit.agentcreditbydate', array('response'=>$objagent,'search'=>$search));
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
		// dd(count($trips));
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
	    	$objagentdeposit->pay_date=$deposit_date;
	    	if(!$check_exiting){
	    		$objagentdeposit->payment=$deposit;
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
        $cash=Input::get('cash') ? Input::get('cash') : 2;
		$operator_id=$this->myGlob->operator_id;
    	$response =SaleOrder::where('operator_id','=',$operator_id)
    							->where('agent_id','=',$agent_id)
    							// ->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
                                ->wherecash_credit($cash)
                                ->orderBy('id','desc')
								->get(array('id', 'orderdate','departure_date', 'agent_id', 'operator_id','cash_credit'));

    	$filter=array();
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
    			$class="";
                if(count($row->saleitems)>0)
                {
    				$objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
    				if($objbusoccurance){
    					$from=City::whereid($objbusoccurance->from)->pluck('name');
    					$to=City::whereid($objbusoccurance->to)->pluck('name');
    					$trip=$from.'-'.$to;
    					$price= $objbusoccurance->price;
    					$amount= $objbusoccurance->price * $tickets;
                        $class=Classes::whereid($objbusoccurance->classes)->pluck('name');
                        $response[$i]['departure_time']=$objbusoccurance->departure_time;
    				}
    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
    				$response[$i]['customer']=$objorderinfo->name;
                    $response[$i]['phone']=$objorderinfo->phone;
                    $response[$i]['class']=$class;

                    $operator = Operator::whereid($row->operator_id)->pluck('name');
                    $trip_id  = BusOccurance::whereid($row['saleitems'][0]['busoccurance_id'])->pluck('trip_id');
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
    			}else{
    				$response[$i]['customer']='-';
    				$response[$i]['phone']='-';
                    $response[$i]['departure_time']="";
    			}
    			$i++;
    		}
    	}

        $responsegrouping = array();
        foreach ($response AS $arr) {
          $responsegrouping[$arr['orderdate']][] = $arr;
        }
        ksort($responsegrouping);

    	$agent_name=Agent::whereid($agent_id)->pluck('name');
    	$agent_balance=AgentDeposit::whereagent_id($agent_id)->orderBy('id','desc')->pluck('balance');
        $parameter['agent_id']=$agent_id;
        $parameter['operator_id']=$this->myGlob->operator_id;
        $parameter['cash']=$cash;
        if($cash==1)
            $cash_status=2;
        else
            $cash_status=1;
        $parameter['cash_status']=$cash_status;
    	return View::make('busreport.agentcredit.creditsalelist', array('response'=>$responsegrouping, 'agent_name'=>$agent_name,'agent_balance'=>$agent_balance,'parameter'=>$parameter));
	}

    public function getAgentPaymentSaleList($agent_id)
    {
        $cash=Input::get('cash') ? Input::get('cash') : 2;
        $operator_id=$this->myGlob->operator_id;

        $responses=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('pay_date','desc')->get();
        $order_ids=array();
        $i=0;
        $responsevalue=array();
        foreach ($responses as $rows) {
            $order_ids=json_decode($rows->order_ids);
            if($order_ids)
            {
                $response =SaleOrder::wherein('id',$order_ids)
                            ->with(array('saleitems'=> function($query) {
                                                            $query->wherefree_ticket(0);
                                                        }))
                            ->orderBy('id','desc')
                            ->get(array('id', 'orderdate','departure_date', 'agent_id', 'operator_id','cash_credit'));
            }
                
            if($response)
            {
                foreach ($response as $row) {
                    $temp['id']=$row->id;
                    $temp['paydate']=$rows->pay_date;
                    $temp['orderdate']=$row->orderdate;
                    $temp['departure_date']=$row->departure_date;
                    $temp['agent_id']=$row->agent_id;
                    $temp['operator_id']=$row->operator_id;
                    $temp['cash_credit']=$row->cash_credit;
                    $temp['saleitems']=$row->saleitems->toarray();

                    $trip='-';
                    $price=0;
                    $amount=0;
                    $tickets=count($row->saleitems);
                    $saleitems=array();
                    $class="";
                    if(count($row->saleitems)>0){
                        $objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
                        if($objbusoccurance){
                            $from=City::whereid($objbusoccurance->from)->pluck('name');
                            $to=City::whereid($objbusoccurance->to)->pluck('name');
                            $trip=$from.'-'.$to;
                            $price= $objbusoccurance->price;
                            $amount= $objbusoccurance->price * $tickets;
                            $class=Classes::whereid($objbusoccurance->classes)->pluck('name');
                            $temp['departure_time']=$objbusoccurance->departure_time;
                        }
                        $objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
                        $temp['customer']=$objorderinfo->name;
                        $temp['phone']=$objorderinfo->phone;
                    }else{
                        $temp['customer']='-';
                        $temp['phone']='-';
                        $temp['departure_time']="";
                    }
                    
                    $temp['class']=$class;

                    $operator = Operator::whereid($row->operator_id)->pluck('name');
                    $trip_id  = BusOccurance::whereid($row['saleitems'][0]['busoccurance_id'])->pluck('trip_id');
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

                    $temp['trip']=$trip;
                    $temp['total_ticket']=$tickets;
                    $temp['price']=$price;
                    $temp['amount']=$amount;
                    $temp['commission']=$response_value['commission'];
                    $temp['grand_total']=($amount - $response_value['agent_commission']);
                    $responsevalue[]=$temp;
                }
                
            }
        }
        $responsegrouping = array();
        foreach ($responsevalue AS $arr) {
          $responsegrouping[$arr['paydate']][] = $arr;
        }
        $agent_name=Agent::whereid($agent_id)->pluck('name');
        $agent_balance=AgentDeposit::whereagent_id($agent_id)->orderBy('id','desc')->pluck('balance');
        $parameter['agent_id']=$agent_id;
        $parameter['operator_id']=$this->myGlob->operator_id;
        $parameter['cash']=$cash;
        if($cash==1)
            $cash_status=2;
        else
            $cash_status=1;
        $parameter['cash_status']=$cash_status;
        return View::make('busreport.agentcredit.creditsalelist', array('response'=>$responsegrouping, 'agent_name'=>$agent_name,'agent_balance'=>$agent_balance,'parameter'=>$parameter));
        
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
        $str_order_ids=json_encode($order_ids);
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
    		$objdepositpayment_trans->order_ids=$str_order_ids;
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

    // Payment Transaction
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