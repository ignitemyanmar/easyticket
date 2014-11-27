<?php
require 'pdfcrowd.php';
class ReportController extends BaseController
{
	public static function getTime($operator_id, $from_city, $to_city){
		if($operator_id && $from_city && $to_city){
			$objtrip=BusOccurance::whereoperator_id($operator_id)->wherefrom($from_city)->whereto($to_city)->groupBy('departure_time')->get();
		}elseif($operator_id && !$from_city && !$to_city){
			$objtrip=BusOccurance::whereoperator_id($operator_id)->groupBy('departure_time')->get();
		}else{
			$objtrip=BusOccurance::groupBy('departure_time')->get();
		}
		$times=array();
		if($objtrip){
			foreach ($objtrip as $row) {
				$temp['tripid']=$row->id;
				$temp['time']=$row->departure_time;
				$times[]=$temp;
			}
		}
		return $times; 
	}

	public function getTimes($operator_id, $from_city, $to_city){
		if($operator_id && $from_city && $to_city){
			$objtrip=BusOccurance::wherein('operator_id',$operator_id)->wherefrom($from_city)->whereto($to_city)->groupBy('departure_time')->get();
		}elseif($operator_id && !$from_city && !$to_city){
			$objtrip=BusOccurance::wherein('operator_id',$operator_id)->groupBy('departure_time')->get();
		}else{
			$objtrip=BusOccurance::groupBy('departure_time')->get();
		}
		$times=array();
		if($objtrip){
			foreach ($objtrip as $row) {
				$temp['tripid']=$row->id;
				$temp['time']=$row->departure_time;
				$times[]=$temp;
			}
		}
		return $times; 
	}

	public function getOperatorsbyAgent($id){
		$agent_id=$id;
		$operator_ids= SaleOrder::whereagent_id($agent_id)->groupBy('operator_id')->lists('operator_id');
    	$operators=array();
    	$objoperator=Operator::wherein('id', $operator_ids)->get();
    	if($objoperator){
    		foreach ($objoperator as $operator) {
    			$temp['id']=$operator->id;
    			$temp['name']=$operator->name;
    			$operators[]=$temp;
    		}
    	}
    	$response['agent_id']=$agent_id;
    	$response['operators']=$operators;
		// return Response::json($response);
		return View::make('busreport.operatorsbyagent', array('response'=>$response));
	}

	public function getAgentsbyOperator($id){
		$operator_id=$id;
    	if(!$operator_id){
    		$response['message']="operator_id is required.";
    		return Response::json($response);
    	}

    	$agent_ids= SaleOrder::whereoperator_id($operator_id)->groupBy('agent_id')->lists('agent_id');
    	$agents=array();
    	$response=array();
    	if($agent_ids){

	    	
	    	$objagents=Agent::wherein('id', $agent_ids)->get();
	    	if($objagents){
	    		foreach ($objagents as $agent) {
	    			$temp['id']=$agent->agentgroup_id;
	    			$temp['name']=AgentGroup::whereid($agent->agentgroup_id)->pluck('name');
	    			$agents[]=$temp;
	    		}
	    	}
	    	$unique_agent = array();
			foreach ($agents as $val) {
			    $unique_agent[$val['id']] = $val;    
			}
			$agent_list = array_values($unique_agent);

	    	$response['operator_id']=$operator_id;
	    	$response['agents']=$agent_list;
	    }
		return View::make('busreport.agentsbyoperator', array('response'=>$response));	
	}

	//date range

	
	public function getTripslistreportOperator()
	{
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$trips  				=Input::get('trips');
		if($agent_id=="All")
			$agent_id='';
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date');
		$end_date  				=Input::get('end_date');
		$departure_time  		=Input::get('departure_time');
		$departure_time			=str_replace('-', ' ', $departure_time);
		
		if($departure_time && $operator_id){
			$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->wheredeparture_time($departure_time)->lists('id');
		}elseif(!$departure_time && $operator_id){
			$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->lists('id');
		}elseif($departure_time && !$operator_id){
			$busoccurance_ids=BusOccurance::wherefrom($from)
								->wheredeparture_time($departure_time)
								->whereto($to)
								->lists('id');
		}else{
			$busoccurance_ids=BusOccurance::wherefrom($from)
								->whereto($to)
								->lists('id');
		}
		
		if($busoccurance_ids){
			$orderids=SaleItem::wherein('busoccurance_id',$busoccurance_ids)->groupBy('order_id')->lists('order_id');
			$orderdates=array();
			if($orderids){
				if(!$agent_id){
					$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->groupBy('orderdate')
								->lists('orderdate');
				}else{
					// $agent_branches=Agent::whereagentgroup_id($agent_id)->lists('id');
					$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->where('agent_id', $agent_id)
								->groupBy('orderdate')
								->lists('orderdate');
				}
				
			}

			if(count($orderdates) >0){
				$from_city 				=City::whereid($from)->pluck('name');
				$to_city 				=City::whereid($to)->pluck('name');
				foreach ($orderdates as $order_date) {
					if($agent_id){
						$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->whereagent_id($agent_id)->lists('id');
					}else{
						$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->lists('id');
					}
					// return Response::json($orderids_bydaily);
					$occuranceids_bydaily		=SaleItem::wherein('order_id', $orderids_bydaily)->wherein('busoccurance_id',$busoccurance_ids)->groupBy('busoccurance_id')->lists('busoccurance_id');
					$amount=$ticketamt=0;
					$seat_total=0;
					$tickets=0;
					if($occuranceids_bydaily){
						foreach ($occuranceids_bydaily as $occuranceid) {
							$tickets			+=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$orderids_bydaily)->count('id');
							$ticketamt			+=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$orderids_bydaily)->sum('price');
							$obj_busoccurance	=BusOccurance::whereid($occuranceid)->first();
							if($obj_busoccurance){
								$seatplanid			=$obj_busoccurance->seat_plan_id;
								$price				=$obj_busoccurance->price;
							}

							if($seatplanid){
								$seat_total	+=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
							}
							$amount    		+=$tickets * $price;
						}
						$soldtickets 					=SaleItem::wherein('order_id', $orderids_bydaily)->count('id');
						$temp['from']					=$from_city;
						$temp['to']						=$to_city;
						$temp['order_date']				=$order_date;
						$temp['order_date']				=$order_date;
						$temp['total_seat']				=$seat_total;
						$temp['purchased_total_seat']	=$tickets;
						$temp['total_amout']			=$ticketamt;
						$report_info[]					=$temp;
					}
					
				}
			}
		}

		$search=array();
		
    	$cities=array();
    	$cities=$this->getCitiesByoperatorId($operator_id);
    	
		$search['cities']=$cities;
		
		$times=array();
		$times=$this->getTime($operator_id, $from, $to);
		
		$search['times']=$times;

		$search['operator_id']=$operator_id;
		$search['trips']=$trips;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;
		$search['agent_id']=$agent_id;

		$agent=Agent::whereoperator_id($operator_id)->get();
		$search['agent']=$agent;
		return View::make('busreport.operatortripticketsolddaterange', array('response'=>$report_info, 'search'=>$search));
	}



	//date range
	public function getTripslistdaterangeAgent(){
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date');
		$end_date  				=Input::get('end_date');
		$departure_time  		=Input::get('departure_time');
		$departure_time			=str_replace('-', ' ', $departure_time);

		if($departure_time && $operator_id){
			$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->wheredeparture_time($departure_time)->lists('id');
		}elseif(!$departure_time && $operator_id){
			$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->lists('id');
		}elseif($departure_time && !$operator_id){
			$busoccurance_ids=BusOccurance::wherefrom($from)
								->wheredeparture_time($departure_time)
								->whereto($to)
								->lists('id');
		}else{
			$busoccurance_ids=BusOccurance::wherefrom($from)
								->whereto($to)
								->lists('id');
		}
		
		if($busoccurance_ids){
			$orderids=SaleItem::wherein('busoccurance_id',$busoccurance_ids)->groupBy('order_id')->lists('order_id');
			$orderdates=array();
			if($orderids){
				if(!$agent_id){
					$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->groupBy('orderdate')
								->lists('orderdate');
				}else{
					$agent_branches=Agent::whereagentgroup_id($agent_id)->lists('id');
					$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->wherein('agent_id', $agent_branches)
								->groupBy('orderdate')
								->lists('orderdate');
				}
				
			}

			if(count($orderdates) >0){
				$from_city 				=City::whereid($from)->pluck('name');
				$to_city 				=City::whereid($to)->pluck('name');
				foreach ($orderdates as $order_date) {
					if($agent_id){
						$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->wherein('agent_id',$agent_branches)->lists('id');
					}else{
						$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->lists('id');
					}
					$occuranceids_bydaily		=SaleItem::wherein('order_id', $orderids_bydaily)->wherein('busoccurance_id',$busoccurance_ids)->groupBy('busoccurance_id')->lists('busoccurance_id');
					$amount=0;
					$seat_total=0;
					$tickets=0;
					if($occuranceids_bydaily){
						foreach ($occuranceids_bydaily as $occuranceid) {
							$tickets			+=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$orderids_bydaily)->count('id');
							$obj_busoccurance	=BusOccurance::whereid($occuranceid)->first();
							if($obj_busoccurance){
								$seatplanid			=$obj_busoccurance->seat_plan_id;
								$price				=$obj_busoccurance->price;
							}

							if($seatplanid){
								$seat_total	+=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
							}
							$amount    		+=$tickets * $price;
						}
						$soldtickets 					=SaleItem::wherein('order_id', $orderids_bydaily)->count('id');
						$temp['from']					=$from_city;
						$temp['to']						=$to_city;
						$temp['order_date']				=$order_date;
						$temp['order_date']				=$order_date;
						$temp['total_seat']				=$seat_total;
						$temp['purchased_total_seat']	=$tickets;
						$temp['total_amout']			=$amount;
						$report_info[]					=$temp;
					}
					
				}
			}
		}

		$search=array();
		$responsecities=$this->getCitiesByoperatorId($operator_id, $from, $to);
		$search['cities']=$responsecities;

		$responsetime=$this->getTime($operator_id, $from, $to);
		$search['times']=$responsetime;
		$search['operator_id']=$operator_id;
		$search['agent_id']=$agent_id;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;

		return View::make('busreport.agenttripticketsolddaterange', array('response'=>$report_info, 'search'=>$search));
	}

	public function getTripsSellingReportbyDaily($date){
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$from  					=Input::get('from_city');
		$to  					=Input::get('to_city');
		$date  				    =Input::get('date');
		$departure_time  		=Input::get('time');
		$departure_time 		=str_replace('-', ' ', $departure_time);
		$agent_id  				=Input::get('agent_id');
		if($agent_id=="All")
			$agent_id='';
		
		if($departure_time){
			$busoccurance_id=BusOccurance::wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->lists('id');
		}else{
			$busoccurance_id=BusOccurance::wherefrom($from)
										->whereto($to)
										->lists('id');
		}
		// return Response::json($busoccurance_id);

		if($busoccurance_id){
			$orderidsbyoccuranceid 		=SaleItem::wherein('busoccurance_id', $busoccurance_id)->groupBy('order_id')->lists('order_id');
			// $orderidsbyoccuranceid=SaleOrder::whereorderdate($date)->wherein('id',$sorder)->lists('id');
			if($orderidsbyoccuranceid && !$agent_id){
				$filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->whereorderdate($date)->lists('id');
			}elseif($orderidsbyoccuranceid){
				// $agentids = Agent::whereagentgroup_id($agent_id)->lists('id');
				// $filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->wherein('agent_id', $agentids)->whereorderdate($date)->lists('id');
				$filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->whereagent_id($agent_id)->whereorderdate($date)->lists('id');
			}else{
				$filterorderidsbydate=array();
			}

		}
		$response=array();
		if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
			$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
			if($busoccurance_ids){
				$from_city =City::whereid($from)->pluck('name');
				$to_city =City::whereid($to)->pluck('name');
				$temp['purchased_total_seat']=0;
				$temp['total_amout']=0;
				foreach ($busoccurance_ids as $occuranceid) {
					if($departure_time){
						$objbusoccurance=BusOccurance::whereid($occuranceid)->wheredeparture_time($departure_time)->first();
					}else{
						$objbusoccurance=BusOccurance::whereid($occuranceid)->first();
					}
					$temp['bus_id']					=$objbusoccurance->id;
					$temp['bus_no']					=$objbusoccurance->bus_no;
					$temp['from']					=$from_city;
					$temp['to']						=$to_city;
					$temp['departure_date']			=$objbusoccurance->departure_date;
					$temp['time']					=$objbusoccurance->departure_time;
					$seat_plan_id					=$objbusoccurance->seat_plan_id;
					$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
					$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$filterorderidsbydate)->count();
					$temp['total_amout']			=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$filterorderidsbydate)->sum('price');
					// $temp['purchased_total_seat'] * $objbusoccurance->price;
					$response[]						=$temp;
				}
			}
		}

		$response_par['operator_id']=$operator_id;
		$response_par['date']=$date;
		$response_par['agent_id']=$agent_id;
		// return Response::json($response);
		return View::make('busreport.operatortripticketsolddaily', array('response'=>$response,'parameter'=>$response_par));
	}

	

	public function getTripsSellingReportbyDailyAgent($date){
		$operator_id =Input::get('operator_id');
		$from =Input::get('from_city');
		$agent_id =Input::get('agent_id');
		$to =Input::get('to_city');
		$date =Input::get('date');
		$access_token =Input::get('access_token');
		$departure_time=Input::get('time');
		$response=$this->getdailyreportbyagentoroperator($operator_id, $agent_id, $from, $to, $date, $departure_time);
		
		$response_par['access_token']=$access_token;
		$response_par['operator_id']=$operator_id;
		$response_par['date']=$date;
		return View::make('busreport.operatortripticketsolddaily', array('response'=>$response,'parameter'=>$response_par));
	}

	public function getTripsSellingReportbyBusid($bus_id){
		$operator_id 	=Input::get('operator_id');
    	$agent_id 		=Input::get('agent_id');
    	$from_to=		BusOccurance::whereid($bus_id)->first();
    	$from 			=$from_to->from;
    	$to 			=$from_to->to;
    	$date 			=Input::get('date');
    	$time 			=Input::get('time');
    	$orderids=array();
    	if($agent_id){
	    	$orederids=SaleOrder::whereorderdate($date)->whereagent_id($agent_id)->lists('id');
    	}else{
	    	$orederids=SaleOrder::whereorderdate($date)->lists('id');
    	}
    	$seat_info=array();
    	
    	if($orederids){
    		$saletickets =SaleItem::wherein('order_id', $orederids)->wherebusoccurance_id($bus_id)->get();
    		if($saletickets){
    			$objbusoccurance =BusOccurance::whereid($bus_id)->first();
    			$from_city 	=City::whereid($from)->pluck('name');
    			$to_city 	=City::whereid($to)->pluck('name');
    			foreach ($saletickets as $rows) {
    				$temp['from']			= $from_city;
    				$temp['to']				= $to_city;
    				$temp['seat_no']		= $rows->seat_no;
    				$temp['ticket_no']		= $rows->ticket_no;
    				$temp['price']			= $rows->price;
    				// $objbusoccurance->price;
    				$temp['customer_name']	= $rows->name ? $rows->name : "-";
    				$objorder 				=SaleOrder::whereid($rows->order_id)->first();
    				$agent_name				='-';
    				$temp['commission_id']="-";
    				$temp['commission']="-";
    				$commission=0;
    				if($agent_id !=0 || $objorder->agent_id !=0){
	    				$agent_name			= Agent::whereid($objorder->agent_id)->pluck('name');

	    				$objcommission=AgentCommission::wheretrip_id($objbusoccurance->trip_id)->whereagent_id($objorder->agent_id)->first();
	    				// dd($objcommission);
	    				if($objcommission){
	    					$temp['commission_id']=$objcommission->commission_id;
	    					$commission=$objcommission->commission;

	    					if($objcommission->commission_id !=1){
	    						$commission=($objcommission->commission * $objbusoccurance->price) / 100;
	    					}
	    				}
	    				if($commission==0){
	    					$commission=Trip::whereid($objbusoccurance->trip_id)->pluck('commission');
	    				}
    				}
    				$temp['commission']=$commission;
    				if($objorder->agent_id !=0){
	    				$agent_name			= Agent::whereid($objorder->agent_id)->pluck('name');
    				}
    				
    				$temp['agent_name'] 	=$agent_name !='' ? $agent_name : '-';
    				$temp['invoice_no']		= $objorder->id;
					$seat_info[]=$temp;
    			}
    		}
    	}
    	$response=$seat_info;

		$response_par['date']=$date;
		$response_par['time']=$time;
		// return Response::json($response);
		return View::make('busreport.operatorreportbybusid', array('response'=>$response,'parameter'=>$response_par));
	}


	public function getSeatOccupancyReportbybus(){
		$userid=Auth::user()->id;
		$usertype=Auth::user()->type;
		$operator_id =Operator::whereuser_id($userid)->pluck('id');
		$responsecity		= $this->getCitiesByoperatorId($operator_id);
		$search['cities']=$responsecity;

		$from =$to = $departure_time ='';
		$responsetime=$this->getTime($operator_id, $from, $to);
		$search['times']=$responsetime;

		$search['access_token']=$access_token;
		$search['operator_id']=$operator_id;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;

		$response=array();
		return View::make('busreport.seatoccupiedbybus', array('response'=>$response,'search' => $search));
	}

	public function getSeatOccupancyBytrip(){
		$from =$to = $departure_time ='';
		$operator_id=Input::get('operator_id') !=null ? Input::get('operator_id') : ''; 
		$access_token='';
		if(Session::has('access_token')){
			$access_token=Session::get('access_token');
		}
		$agent_id=0;
		$bus_id=0;
		$operator_id 	=Input::get('operator_id');
		$from	 		=Input::get('from');
		$to	 			=Input::get('to');
		$date 			=Input::get('departure_date');
		$time 			=Input::get('departure_time');
		$offset 		=Input::get('offset');
		$limit 			=Input::get('limit');
		$responsetrips=$this->getSeatOccupiedReportByBus($operator_id, $agent_id, $from, $to, $date, $time, $bus_id, $offset, $limit);
		return Response::json($responsetrips);
		$search=array();
		$responsecity		= $this->getCitiesByoperatorId($operator_id);
		$search['cities']=$responsecity;

		$responsetime=$this->getTime($operator_id, $from, $to);
		$search['times']=$responsetime;

		$search['access_token']=$access_token;
		$search['operator_id']=$operator_id;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$time;

		// return View::make('busreport.seatoccupiedbybus', array('search' => $search));
		return View::make('busreport.seatoccupiedbybus', array('response'=>$responsetrips, 'search' => $search));	
	}

	/**
	 * General functions
	 *
	 */

	public static function getCitiesByoperatorId($operator_id){
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return $response;
    	}
    	$orderids 			=SaleOrder::whereoperator_id($operator_id)->lists('id');
    	$cities=array();
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		// return Response::json($busoccurance_ids);
    		if($busoccurance_ids){
    			foreach ($busoccurance_ids as $busoccuranceid) {
    				$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    				if($objbusoccurance){
    					$objfromcities				=City::whereid($objbusoccurance->from)->first();
    					$tempfrom['from']			=$objfromcities->id;
    					$tempfrom['from_city']		=$objfromcities->name;
    					$objtocities				=City::whereid($objbusoccurance->to)->first();
    					$tempto['to']				=$objtocities->id;
    					$tempto['to_city']			=$objtocities->name;
    					$from[]						=$tempfrom;
    					$to[]						=$tempto;
    				}
    			}
    			$unique_from=array_unique($from, SORT_REGULAR);
    			$unique_to=array_unique($to, SORT_REGULAR);

    			if($unique_from){
		    		foreach ($unique_from as $from) {
		    			$tmpfrom['id']=$from['from'];
		    			$tmpfrom['name']=$from['from_city'];
		    			$last_from[]=$tmpfrom;
		    		}
		    	}

		    	if($unique_to){
		    		foreach ($unique_to as $to) {
		    			$tmpto['id']=$to['to'];
		    			$tmpto['name']=$to['to_city'];
		    			$last_to[]=$tmpto;
		    		}
		    	}
    			$cities['from']=$last_from;
    			$cities['to']=$last_to;
    		}
    	}else{
    		$response['message']='There is no records.';
    		return $response;
    	}

    	return $cities;
    }

    public function getCitiesByoperatorIds($operator_id){
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return $response;
    	}
    	$orderids 			=SaleOrder::wherein('operator_id',$operator_id)->lists('id');
    	$cities=array();
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		// return Response::json($busoccurance_ids);
    		if($busoccurance_ids){
    			foreach ($busoccurance_ids as $busoccuranceid) {
    				$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    				if($objbusoccurance){
    					$objfromcities				=City::whereid($objbusoccurance->from)->first();
    					$tempfrom['from']			=$objfromcities->id;
    					$tempfrom['from_city']		=$objfromcities->name;
    					$objtocities				=City::whereid($objbusoccurance->to)->first();
    					$tempto['to']				=$objtocities->id;
    					$tempto['to_city']			=$objtocities->name;
    					$from[]						=$tempfrom;
    					$to[]						=$tempto;
    				}
    			}
    			$unique_from=array_unique($from, SORT_REGULAR);
    			$unique_to=array_unique($to, SORT_REGULAR);

    			if($unique_from){
		    		foreach ($unique_from as $from) {
		    			$tmpfrom['id']=$from['from'];
		    			$tmpfrom['name']=$from['from_city'];
		    			$last_from[]=$tmpfrom;
		    		}
		    	}

		    	if($unique_to){
		    		foreach ($unique_to as $to) {
		    			$tmpto['id']=$to['to'];
		    			$tmpto['name']=$to['to_city'];
		    			$last_to[]=$tmpto;
		    		}
		    	}
    			$cities['from']=$last_from;
    			$cities['to']=$last_to;
    		}
    	}else{
    		$response['message']='There is no records.';
    		return $response;
    	}

    	return $cities;
    }

    
	public function getSeatOccupiedReportByBus($operator_id, $agent_id, $from, $to, $date, $time, $bus_id, $offset, $limit){
    	$offset=$offset !=null ? $offset : 1;
    	$limit=$limit !=null ? $limit : 12;
    	$offset=($offset -1) * $limit;
    	$objbusoccuranceids=array();
    	if(!$bus_id && $time){
	    		$objbusoccuranceids =	BusOccurance::wherefrom($from)
    									->whereoperator_id($operator_id)
    									->whereto($to)
    									->wheredeparture_date($date)
    									->wheredeparture_time($time)
    									->lists('id');	
    	}elseif(!$bus_id && !$time){
    		$objbusoccuranceids =	BusOccurance::wherefrom($from)
    									->whereto($to)
    									->wheredeparture_date($date)
    									->lists('id');	
    	}else{
    		$objbusoccuranceids[]=(int) $bus_id;
    	}
    	$response =array();
    	if($objbusoccuranceids){
    		$temp['operator_id']	=$operator_id;
    		$temp['operator_name']	=Operator::whereid($operator_id)->pluck('name');
    		
    		$seattingplan=array();
    		$seatplan=array();
    			foreach ($objbusoccuranceids as $busid) {
    				$objbus=BusOccurance::whereid($busid)->first();
    				if($objbus){
    				$tmp_seatplan['bus_id']=$busid;
		    		$tmp_seatplan['bus_no']=$objbus->bus_no;
		    		$objseatplan=SeatingPlan::whereid($objbus->seat_plan_id)->first();
		    		$tmp_seatplan['row']=$objseatplan->row;
		    		$tmp_seatplan['column']=$objseatplan->column;
		    		$tmp_seatplan['classes']=$objbus->classes;

		    		if($bus_id){
		    			$seatlist=array();
		    			$objseatinfo=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->get();
		    			if($objseatinfo){
		    				foreach ($objseatinfo as $seats) {
		    					$tmp_seatlist['id']=$seats['id'];
		    					$tmp_seatlist['seat_no']=$seats['seat_no'];
					    		$tmp_seatlist['price']=$objbus->price;
		    					if($seats['status']==0){
		    						$tmp_seatlist['seat_no']='xxx';
					    			$tmp_seatlist['price']='xxx';
		    					}
					    		
					    		$tmp_seatlist['status']=$seats['status'];
					    		$checkbuy	=SaleItem::wherebusoccurance_id($objbus->id)->whereseat_no($seats['seat_no'])->first();
					    		$customer=array();
					    		if($checkbuy){
					    			$tmp_seatlist['status']=2;
					    			$customer['id']=000;
					    			$customer['name']=$checkbuy->name != '' ? $checkbuy->name : '-';
					    			$customer['nrc']=$checkbuy->nrc != '' ? $checkbuy->nrc : '-';
					    		}
					    		$tmp_seatlist['customer']=$customer;

					    		$seatlist[]=$tmp_seatlist;
		    				}
		    			}
			    		
		    			$tmp_seatplan['seat_list']=$seatlist;
		    		}
		    		
		    		
		    		$seattingplan=$tmp_seatplan;

		    		$seatplan[]=$seattingplan;
    					
    				}
    					
    			}
	    		
				$temp['seat_plan']=$seatplan;
			$response=$temp;
    	}
    	return $response;
    }

    public function getdailyreportbyagentoroperator($operator_id, $agent_id, $from, $to, $date, $departure_time){
    	$report_info 			=array();
		
		if(!$from || !$to || !$date){
			$response['message']='Required fields are operator_id, from, to, date and departure_time';
			return Response::json($response);
		}
				

		if($departure_time){
			$busoccurance_id=BusOccurance::wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->lists('id');
		}else{
			$busoccurance_id=BusOccurance::wherefrom($from)
										->whereto($to)
										->lists('id');
		}
		// return Response::json($busoccurance_id);

		if($busoccurance_id){
			$orderidsbyoccuranceid 		=SaleItem::wherein('busoccurance_id', $busoccurance_id)->groupBy('order_id')->lists('order_id');
			// $orderidsbyoccuranceid=SaleOrder::whereorderdate($date)->wherein('id',$sorder)->lists('id');
			if($orderidsbyoccuranceid && !$agent_id){
				$filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->whereorderdate($date)->lists('id');
			}elseif($orderidsbyoccuranceid){
				$agentids = Agent::whereagentgroup_id($agent_id)->lists('id');
				$filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->wherein('agent_id', $agentids)->whereorderdate($date)->lists('id');
			}else{
				$filterorderidsbydate=array();
			}

		}
		$response=array();
		if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
			$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
			if($busoccurance_ids){
				$from_city =City::whereid($from)->pluck('name');
				$to_city =City::whereid($to)->pluck('name');
				$temp['purchased_total_seat']=0;
				$temp['total_amout']=0;
				foreach ($busoccurance_ids as $occuranceid) {
					if($departure_time){
						$objbusoccurance=BusOccurance::whereid($occuranceid)->wheredeparture_time($departure_time)->first();
					}else{
						$objbusoccurance=BusOccurance::whereid($occuranceid)->first();
					}
					$temp['bus_id']					=$objbusoccurance->id;
					$temp['bus_no']					=$objbusoccurance->bus_no;
					$temp['from']					=$from_city;
					$temp['to']						=$to_city;
					$temp['departure_date']			=$objbusoccurance->departure_date;
					$temp['time']					=$objbusoccurance->departure_time;
					$seat_plan_id					=$objbusoccurance->seat_plan_id;
					$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
					$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$filterorderidsbydate)->count();
					$temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
					$response[]						=$temp;
				} 
			}
		}
    return $response;
		
    }

    /** 
	 *Daily and daily advance sale reports
	 *
	 */
	  public function getDailyReportByDepartureDate(){
			$report_info 			=array();
			$operator_id  			=Input::get('operator_id');
			$agent_id  				=Input::get('agent_id');
			$from  					=Input::get('from');
			$to  					=Input::get('to');
			$start_date  			=Input::get('start_date');
			$end_date  				=Input::get('end_date');
			$departure_time  		=Input::get('departure_time');
			$departure_time			=str_replace('-', ' ', $departure_time);

			if($departure_time && $operator_id){
				$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->wheredeparture_time($departure_time)->lists('id');
			}elseif(!$departure_time && $operator_id){
				$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->lists('id');
			}elseif($departure_time && !$operator_id){
				$busoccurance_ids=BusOccurance::wherefrom($from)
									->wheredeparture_time($departure_time)
									->whereto($to)
									->lists('id');
			}else{
				$busoccurance_ids=BusOccurance::wherefrom($from)
									->whereto($to)
									->lists('id');
			}
			

			if($busoccurance_ids){
				$orderids=SaleItem::wherein('busoccurance_id',$busoccurance_ids)->groupBy('order_id')->lists('order_id');
				$orderdates=array();
				if($orderids){
					if(!$agent_id){
						$orderdates=SaleOrder::wherein('id',$orderids)
									->where('orderdate','>=', $start_date)
									->groupBy('orderdate')
									->lists('orderdate');
					}else{
						$agent_branches=Agent::whereagentgroup_id($agent_id)->lists('id');
						$orderdates=SaleOrder::wherein('id',$orderids)
									->where('orderdate','>=', $start_date)
									->wherein('agent_id', $agent_branches)
									->groupBy('orderdate')
									->lists('orderdate');
					}
					
				}

				if(count($orderdates) >0){
					$from_city 				=City::whereid($from)->pluck('name');
					$to_city 				=City::whereid($to)->pluck('name');
					foreach ($orderdates as $order_date) {
						if($agent_id){
							$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->wherein('agent_id',$agent_branches)->lists('id');
						}else{
							$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->lists('id');
						}
						$occuranceids_bydaily		=SaleItem::wherein('order_id', $orderids_bydaily)->wherein('busoccurance_id',$busoccurance_ids)->groupBy('busoccurance_id')->lists('busoccurance_id');
						$amount=0;
						$seat_total=0;
						$tickets=0;
						if($occuranceids_bydaily){
							foreach ($occuranceids_bydaily as $occuranceid) {
								$tickets			+=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$orderids_bydaily)->count('id');
								$obj_busoccurance	=BusOccurance::whereid($occuranceid)->first();
								if($obj_busoccurance){
									$seatplanid			=$obj_busoccurance->seat_plan_id;
									$price				=$obj_busoccurance->price;
								}

								if($seatplanid){
									$seat_total	+=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
								}
								$amount    		+=$tickets * $price;
							}
							$soldtickets 					=SaleItem::wherein('order_id', $orderids_bydaily)->count('id');
							$temp['from']					=$from_city;
							$temp['to']						=$to_city;
							$temp['order_date']				=$order_date;
							$temp['order_date']				=$order_date;
							$temp['total_seat']				=$seat_total;
							$temp['purchased_total_seat']	=$tickets;
							$temp['total_amout']			=$amount;
							$report_info[]					=$temp;
						}
						
					}
				}
			}

			// return Response::json($report_info);
			$operator_ids=Operator::lists('id');
			$user_id =Auth::user()->id;
			$operator_id =Operator::whereuser_id($user_id)->pluck('id');
			$search=array();
			if($operator_id){
				$responsecities=$this->getCitiesByoperatorId($operator_id, $from, $to);
			}else{
				$responsecities=$this->getCitiesByoperatorIds($operator_ids, $from, $to);
			}
			$search['cities']=$responsecities;
			$responsetime=array();
			if($operator_id){
				$responsetime=$this->getTime($operator_id, $from, $to);
			}else{
				$responsetime=$this->getTimes($operator_ids, $from, $to);
			}
			
			$search['times']=$responsetime;
			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['from']=$from;
			$search['to']=$to;
			$search['time']=$departure_time;

			return View::make('busreport.tripdate.index', array('response'=>$report_info, 'search'=>$search));
		}
		public function getDailyReportByDepartureDatesearch(){
	    	// $operator_id 		=Input::get('operator_id');
	    	$operator_id 		=$this->myGlob->operator_id;
	    	$departure_time 	=Input::get('departure_time');	
	    	$departure_date 	=Input::get('departure_date');	
	    	$from 				=Input::get('from');	
	    	$to 				=Input::get('to');	
	    	$time 				=Input::get('departure_time');
	    	$agent_id 			=Input::get('agent_id');
	    	$objbusoccuranceids=null;	
	    	$busids=null;
	    	if(!$time && $from && $to && $departure_date){
	    		$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wheredeparture_date($departure_date)->wherefrom($from)->whereto($to)->lists('id');
	    	}elseif(!$from && !$to && !$time && $departure_date){
				$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wheredeparture_date($departure_date)->lists('id');
	    	}elseif($time && $from && $to && $departure_date){
	    		$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->wheredeparture_date($departure_date)->whereto($to)->wheredeparture_time($time)->lists('id');
	    	}else{
	    		$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->lists('id');
	    	}

	    	if($objbusoccuranceids !=null){
	    		$busids=SaleItem::wherein('busoccurance_id', $objbusoccuranceids)->groupBy('busoccurance_id')->lists('busoccurance_id');
	    	}

	    	$response=array();
			if($busids){
				foreach ($busids as $busid) {
					$objbus=BusOccurance::whereid($busid)->first();
					$temp['id']			=$objbus->id;
					$from 				=City::whereid($objbus->from)->pluck('name');
					$to 				=City::whereid($objbus->to)->pluck('name');
					$temp['trip']		=$from.'-'.$to;
					$temp['bus_no']		=$objbus->bus_no;
					$temp['class']		=Classes::whereid($objbus->classes)->pluck('name');
					$temp['departure_date']		=$objbus->departure_date;
					$temp['departure_time']		=$objbus->departure_time;
					$price 				=$objbus->price;
					$temp['sold_seats']	=SaleItem::wherebusoccurance_id($busid)->count();
					$temp['total_seats']=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();
					// $temp['total_amount']=$price * $temp['sold_seats'];
					$temp['total_amount']=SaleItem::wherebusoccurance_id($busid)->sum('price');

					$response[]			=$temp;
				}
			}
			// return Response::json($response);
			$operator_ids=Operator::lists('id');
			$user_id =Auth::user()->id;
			$operator_id =Operator::whereuser_id($user_id)->pluck('id');
			$search=array();
			if($operator_id){
				$responsecities=$this->getCitiesByoperatorId($operator_id, $from, $to);
			}else{
				$responsecities=$this->getCitiesByoperatorIds($operator_ids, $from, $to);
			}
			$search['cities']=$responsecities;
			$responsetime=array();
			if($operator_id){
				$responsetime=$this->getTime($operator_id, 0, 0);;
			}else{
				$responsetime=$this->getTimes($operator_ids, $from, $to);
			}
			$search['times']=$responsetime;
			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['from']=$from;
			$search['to']=$to;
			$search['time']=$departure_time;
			// return Response::json($response);
			return View::make('busreport.tripdate.index', array('response'=>$response, 'search'=>$search));
	    }

	    public function getDailyReportbydepartdateandbusid(){
	    	$busid 			=Input::get('bus_id');
	    	$orderids 		=SaleItem::wherebusoccurance_id($busid)->groupBy('order_id')->lists('order_id');
	    	$objbus 	 	=BusOccurance::whereid($busid)->first();
	    	$objorderagent=null;
	    	$objagentids= $response=array();
	    	if($orderids){
	    		$objagentids =SaleOrder::wherein('id', $orderids)->lists('agent_id','id');
	    	}
	    	$i=0;
	    	$main_gate_total=0;
	    	$f_main_gate_total=0;
	    	$agent_gate_total=0;
	    	$f_agent_gate_total=0;
	    	$grand_total=0;
	    	if($objagentids){
				$objbusoccurance    	=BusOccurance::whereid($busid)->first();
	    		foreach ($objagentids as $key=>$agentid) {
	    			$price					=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->pluck('price');
	    			$agent_name				=Agent::whereid($agentid)->pluck('name');	
	    			if($i==0){
	    				$temp['bus_id']			=$busid;
	    				$temp['bus_no']			=$objbusoccurance->bus_no;
	    				$temp['class']			=Classes::whereid($objbusoccurance->classes)->pluck('name');
	    				$temp['departure_time']			=$objbusoccurance->departure_time;
	    				$from 					=City::whereid($objbus->from)->pluck('name');
	    				$to 					=City::whereid($objbus->to)->pluck('name');
	    				$temp['trip']			=$from.'-'.$to;
	    				$temp['agent_id']		=$agentid;

	    				$temp['agent'] 			=$agent_name != null ? $agent_name : '-';
	    				$temp['sold_tickets']	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
		    			// $price 					=$objbus->price;
		    			$temp['total_amount'] 	=$price * $temp['sold_tickets'];
	    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();
	    				$response[]=$temp;
	    				$checkowner=Agent::whereid($agentid)->pluck('owner');
	    				if($checkowner==1){
	    					$f_main_gate_total =$temp['total_amount'];
	    				}else{
	    					$f_agent_gate_total =$temp['total_amount'];
	    				}
	    			}else{
	    				$sameagent=0;
	    				$samekey=0;
	    				foreach ($response as $s_key=>$value) {
		    				if($value['agent_id']==$agentid ){
		    					$sameagent +=1;
		    					$samekey=$s_key;
		    				}
		    			}
		    			if($sameagent==0){
		    				$temp['bus_id']			=$busid;
		    				$temp['bus_no']			=$objbusoccurance->bus_no;
		    				$temp['class']			=Classes::whereid($objbusoccurance->classes)->pluck('name');
		    				$temp['departure_time']			=$objbusoccurance->departure_time;
		    				$from 					=City::whereid($objbus->from)->pluck('name');
		    				$to 					=City::whereid($objbus->to)->pluck('name');
		    				$temp['trip']			=$from.'-'.$to;
		    				$temp['agent_id']		=$agentid;
		    				$temp['agent']			=$agent_name != null ? $agent_name : '-';
		    				$temp['sold_tickets']	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
			    			// $price 					=$objbus->price;
			    			$temp['total_amount'] 	=$price * $temp['sold_tickets'];
		    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();	
		    				$response[]=$temp;
		    				$checkowner=Agent::whereid($agentid)->pluck('owner');
		    				if($checkowner==1){
		    					$f_main_gate_total =$temp['total_amount'];
		    				}else{
		    					$f_agent_gate_total =$temp['total_amount'];
		    				}
		    			}else{
		    				$sold_tickets	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
			    			// $price 			=$objbus->price;
			    			$total_amount	=$price * $sold_tickets;
		    				
		    				$response[$samekey]['sold_tickets'] +=$sold_tickets;
		    				$response[$samekey]['total_amount'] +=$total_amount;
		    				$checkowner=Agent::whereid($agentid)->pluck('owner');
		    				if($checkowner==1){
		    					$main_gate_total +=$total_amount;
		    				}else{
		    					$agent_gate_total +=$total_amount;
		    				}
		    			}
	    			}
	    			$i++;
	    		}	
	    	}
	    	$total_sold['main_gate_total']=$main_gate_total + $f_main_gate_total;
	    	$total_sold['agent_gate_total']=$agent_gate_total + $f_agent_gate_total;
	    	$total_sold['grand_total']=$total_sold['main_gate_total'] + $total_sold['agent_gate_total'];
	    	// return Response::json($response);
	    	return View::make('busreport.tripdate.filterbybusid', array('response'=>$response,'total_sold'=>$total_sold));
	    }

	    public function getDailyReportbydepartdatedetail(){
	    	$agent_id 	=Input::get('agent_id');
	    	$bus_id 	=Input::get('bus_id');

	    	$objbus=BusOccurance::whereid($bus_id)->first();
	    	$objorderids=SaleItem::wherebusoccurance_id($bus_id)->groupBy('order_id')->lists('order_id');
	    	$response=array();
	    	if($objorderids){
	    		foreach ($objorderids as $key => $id) {
	    			$objorderinfo=SaleOrder::whereid($id)->whereagent_id($agent_id)->first();
	    			if($objorderinfo){
	    				$objsaleitems=SaleItem::wherebusoccurance_id($bus_id)->whereorder_id($id)->get();
	    				if($objsaleitems){
	    					foreach ($objsaleitems as $rows) {
	    						$temp['bus_no'] 	=$objbus->bus_no;
	    						$from 				=City::whereid($objbus->from)->pluck('name');
	    						$to 				=City::whereid($objbus->to)->pluck('name');
	    						$temp['trip']=$from.'-'.$to;
	    						$temp['class']=Classes::whereid($objbus->classes)->pluck('name');
	    						$temp['departure_date'] 	=$objbus->departure_date;
	    						$temp['departure_time'] 	=$objbus->departure_time;
	    						$temp['seat_no'] 	=$rows['seat_no'];
	    						$temp['ticket_no'] 	=$rows['ticket_no'];
	    						$temp['orderdate']=$objorderinfo->orderdate;
				    			$agent_name=Agent::whereid($objorderinfo->agent_id)->pluck('name');
				    			$temp['agent']=$agent_name != null ? $agent_name : '-';
				    			$temp['customer_name']=$objorderinfo->name;
				    			$temp['operator']=Operator::whereid($objorderinfo->operator_id)->pluck('name');
				    			$temp['price'] 	 =$rows->price;
				    			$response[] 	 =$temp;
	    					}
	    				}
	    			}
	    		}
	    	}
	    	// return Response::json($response);
	    	return View::make('busreport.tripdate.detail', array('response'=>$response));
	    }

	    public function getDailyReportforTrip(){
	    	$departure_time		=Input::get('departure_time');
	    	$agent_id			=Input::get('agent_id');
	    	$todaydate 			=date('Y-m-d');
	    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
	    	$operator_id=$this->myGlob->operator_id;
	    	// $operator_id  		=Input::get('operator_id');
	    	Session::put('search_daily_date', $date);
	    	if($departure_time){
	    		$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
										->wheredeparture_date($date)
										->wheredeparture_time($departure_time)
										->lists('id');
	    	}else{
	    		$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
										->wheredeparture_date($date)
										->lists('id');
	    	}
			
			$salerecord=array();
			if($departure_time){
				if($objbusoccurance_ids){
					foreach ($objbusoccurance_ids as $occurrent_id) {
							$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
							$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
							$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
							if($order_ids){
								$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
								$soldticketamount	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->sum('price');
								$temp['bus_id']		=$busoccuranceinfo->id;
								$temp['bus_no']		=$busoccuranceinfo->bus_no;
								$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
								$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to'] 		=$to_city;
								$temp['class']		=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$soldticketamount;
								$salerecord[] 		=$temp;
							}
					}
				}
			}else{
				$salerecord=array();
				if($objbusoccurance_ids){
					$i=0;
					foreach ($objbusoccurance_ids as $occurrent_id) {
						if($i==0){
							$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
							$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
							$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
							if($order_ids){
								$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
								$soldticketamount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->sum('price');
								$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
								$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
								$temp['busid'] 		=$occurrent_id;
								$temp['class'] 		=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to'] 		=$to_city;
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$soldticketamount;
								$salerecord[] 		=$temp;
							}
						}else{
							$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
							$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
							$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
							if($order_ids){
								$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
								$soldticketamount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->sum('price');
								$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
								$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
								$temp['busid'] 		=$occurrent_id;
								$temp['class'] 		=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to'] 		=$to_city;
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$soldticketamount;
								$sametime=0;
								foreach ($salerecord as $key => $value) {
									if($value['time']== $temp['time'] && $value['busid']== $temp['busid']){
										$salerecord[$key]['sold_seat']=$value['sold_seat'] + $temp['sold_seat'];
										$salerecord[$key]['total_seat']=$value['total_seat'] + $temp['total_seat'];
										$salerecord[$key]['sold_amount']=$value['sold_amount'] + $temp['sold_amount'];
										$sametime +=1;
									}
								}
								if($sametime==0){
									$salerecord[] 		=$temp;
								}else{

								}
							}
						}
						$i++;
					}

				}
			}

			$advancesale=$this->getDailyAdvancedTrips($operator_id, $date, $agent_id);

			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['date']=$date;
			// return Response::json($salerecord);
			return View::make('busreport.daily.index', array('dailyforbus'=>$salerecord, 'dailyforadvance'=>$advancesale, 'search'=>$search));	
	    }

	    public function getDailyReportforTripFilterbyTime(){
	    	$departure_time		=Input::get('departure_time');
	    	$agent_id			=Input::get('agent_id');
	    	$todaydate 			=date('Y-m-d');
	    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
	    	$operator_id  		=Input::get('operator_id');
	    	Session::put('daily_rp_time', $departure_time);
	    	if($departure_time){
	    		$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
										->wheredeparture_date($date)
										->wheredeparture_time($departure_time)
										->lists('id');
	    	}else{
	    		$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
										->wheredeparture_date($date)
										->lists('id');
	    	}
			
			$salerecord=array();
			if($departure_time){
				if($objbusoccurance_ids){
					foreach ($objbusoccurance_ids as $occurrent_id) {
							$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
							$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
							$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
							if($order_ids){
								$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
								$temp['bus_id']		=$busoccuranceinfo->id;
								$temp['bus_no']		=$busoccuranceinfo->bus_no;
								$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
								$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to'] 		=$to_city;
								$temp['class']		=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$temp['price'] * $soldticketcount;
								$salerecord[] 		=$temp;
							}
					}
				}
			}else{
				$salerecord=array();
				if($objbusoccurance_ids){
					$i=0;
					foreach ($objbusoccurance_ids as $occurrent_id) {
						if($i==0){
							$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
							$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
							$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
							if($order_ids){
								$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
								$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
								$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to'] 		=$to_city;
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$temp['price'] * $soldticketcount;
								$salerecord[] 		=$temp;
							}
						}else{
							$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
							$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
							$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
							if($order_ids){
								$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
								$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
								$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to'] 		=$to_city;
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$temp['price'] * $soldticketcount;
								$sametime=0;
								foreach ($salerecord as $key => $value) {
									if($value['time']== $temp['time']){
										$salerecord[$key]['sold_seat']=$value['sold_seat'] + $temp['sold_seat'];
										$salerecord[$key]['total_seat']=$value['total_seat'] + $temp['total_seat'];
										$salerecord[$key]['sold_amount']=$value['sold_amount'] + $temp['sold_amount'];
										$sametime +=1;
									}
								}
								if($sametime==0){
									$salerecord[] 		=$temp;
								}else{

								}
							}
							

						}
						$i++;
					}

				}
			}

			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['date']=$date;
			// return Response::json($salerecord);
			return View::make('busreport.daily.filterbytime', array('dailyforbus'=>$salerecord, 'search'=>$search));	

	    }

	    public function getDailyAdvancedByFilterDate(){
	    	$report_info 			=array();
	    	$order_date 					=Input::get('order_date');
	    	$departure_date 				=Input::get('departure_date');
			$operator_id  					=Input::get('operator_id');
			$agent_id  						=Input::get('agent_id');
			
			$orderids 				=SaleOrder::whereorderdate($order_date)->lists('id');
			if($orderids){
				$busoccuranceids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
			}
			$filterbusids=array();
			if(isset($busoccuranceids)){
				foreach($busoccuranceids as $busid){
					$filterid=BusOccurance::wheredeparture_date($departure_date)->whereid($busid)->pluck('id');
					if($filterid){
						$filterbusids[]=$filterid;
					}
				}
				if($filterbusids){
					$filterorderidsbydate=SaleItem::wherein('busoccurance_id',$filterbusids)->lists('order_id');
				}
			}

			if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
				$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
				if($busoccurance_ids){
					$temp['purchased_total_seat']=0;
					$temp['total_amout']=0;
					foreach ($busoccurance_ids as $occuranceid) {
						$objbusoccurance=BusOccurance::whereid($occuranceid)->first();
							$temp['bus_id']					=$objbusoccurance->id;
							$temp['bus_no']					=$objbusoccurance->bus_no;
							$class 							=Classes::whereid($objbusoccurance->classes)->pluck('name');
							$temp['class'] 					=$class !=null ? $class : '-';
							$temp['from']					=City::whereid($objbusoccurance->from)->pluck('name');
							$temp['to']						=City::whereid($objbusoccurance->to)->pluck('name');
							$temp['departure_date']			=$objbusoccurance->departure_date;
							$temp['time']					=$objbusoccurance->departure_time;
							$seat_plan_id					=$objbusoccurance->seat_plan_id;
							$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
							$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
							
							// $temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
							$temp['total_amout']			=SaleItem::wherebusoccurance_id($occuranceid)->sum('price');
							$response[]						=$temp;
					}
				}
			}else{
				$response['message']='There is no record.';
				return Response::json($response);
			}
	    	// return Response::json($response);
			return View::make('busreport.daily.filterbydate', array('response'=>$response));	

	    }

	    public function getDetailDailyReportforBus(){
	    	$userid =Auth::user()->id;
	    	$operator_name =Operator::whereuser_id($userid)->pluck('name');
	    	$todaydate 		=date('Y-m-d');
	    	$bus_id  		=Input::get('bus_id');
			$objbusoccurance=BusOccurance::whereid($bus_id)->first();
			$class 			=Classes::whereid($objbusoccurance->classes)->pluck('name');
			$salerecord=array();
			if($objbusoccurance){
				$saleiteminfo 	=SaleItem::wherebusoccurance_id($objbusoccurance->id)->get();
				$order_ids 		=SaleItem::wherebusoccurance_id($objbusoccurance->id)->lists('order_id','id');
				
				$from 					=City::whereid($objbusoccurance->from)->pluck('name');
				$to 					=City::whereid($objbusoccurance->to)->pluck('name');
				$trip_id				=$objbusoccurance->trip_id;
				
				if($order_ids){
					foreach ($order_ids as $key => $orderid) {
						$objorderinfo 			=SaleOrder::whereid($orderid)->first();
						$temp['bus_id']			=$bus_id;
						$temp['bus_no']			=$objbusoccurance->bus_no;
						
						$temp['Trip_id']			=$trip_id;
						$temp['agent_id']			=$objorderinfo->agent_id;
						$temp['Trip']			=$from.'-'.$to;
						$temp['class']			=$class;
						$temp['operator']		=$operator_name;
						$temp['sale_id']		=$key;
						$temp['order_id']		=$objorderinfo->id;
						$temp['order_date']		=$objorderinfo->orderdate;
						$obj_saleitem 			=SaleItem::whereid($key)->first();
						$temp['seat_no'] 		=$obj_saleitem->seat_no;
						$temp['ticket_no']		=$obj_saleitem->ticket_no !=null ? $obj_saleitem->ticket_no : '-';
						$temp['name']			=$objorderinfo->name !=null ? $objorderinfo->name : '-';
						$agent_name 			=Agent::whereid($objorderinfo->agent_id)->pluck('name');
						$temp['agent'] 			=$agent_name !=null ? $agent_name : '-';
						if($agent_name !=null){
							$objcommission=AgentCommission::wheretrip_id($objbusoccurance->trip_id)->whereagent_id($objorderinfo->agent_id)->first();
							// return Response::json($objcommission);
							if($objcommission){
								$temp['commission_id']	=$objcommission->commission_id;
								$temp['commission']		=$objcommission->commission;	
								if($objcommission->commission_id !=1){
									$temp['commission']=($objbusoccurance->price * $objcommission->commission )/100;
								}
							}
							else{
								$commission=Trip::whereid($trip_id)->pluck('commission');
								$temp['commission_id']='';
								$temp['commission']=$commission;
							}
							
						}else{
							$temp['commission_id']='';
							$temp['commission']='';
						}


						$temp['price']			=$obj_saleitem->price;
						$temp['departure_date']	=$objbusoccurance->departure_date;
						$temp['departure_time']	=$objbusoccurance->departure_time;
						$salerecord[] 		=$temp;
					}
				}
			}
			// return Response::json($salerecord);
			return View::make('busreport.daily.detail', array('response'=>$salerecord));	

	    }

	    public function getSeatOccupancyReport(){
	    	$userid 			=Auth::user()->id;
	    	$operator_id 		=Operator::whereuser_id($userid)->pluck('id');
	    	$today 				=date('Y-m-d');
	    	$from_date 			=Input::get('from_date') !=null ? Input::get('from_date') : $today;

	    	$departure_time 	=Input::get('time') !=null ? Input::get('time') : "";
	    	$from 				=Input::get('from_city');
	    	$to 				=Input::get('to_city');

	    	if($from_date){
	    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
	    									->wherefrom($from)
	    									->whereto($to)
	    									->where('departure_date','=',$from_date)
	    									// ->where('departure_date','<=',$to_date)
	    									->wheredeparture_time($departure_time)
	    									->lists('id');	
	    	}else{
	    		$busoccurance_ids   =BusOccurance::whereoperator_id($operator_id)
	    									->wherefrom($from)
	    									->whereto($to)
	    									->wheredeparture_time($departure_time)
	    									->limit(12)
	    									->lists('id');
	    	}
	    	

	    	$seat_plan_array=array();
	    	$temp=array();
	    	if($busoccurance_ids){
	    		$temp['operator_id']=$operator_id;
	    		$temp['operator']	=Operator::whereid($operator_id)->pluck('name');
	    		$temp_seat_plan=array();
	    		foreach ($busoccurance_ids as $busoccuranceid) {
	    			$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
	    			if($objbusoccurance){
	    				$tmp['id'] 				=$objbusoccurance->id;
		    			$tmp['seat_no'] 		=$objbusoccurance->seat_no !='' ? $objbusoccurance->seat_no : '-';
		    			$tmp['bus_no'] 			=$objbusoccurance->bus_no;
		    			$objseat_plan			=SeatingPlan::whereid($objbusoccurance->seat_plan_id)->first();
		    			
		    			if($objseat_plan){
		    				$tmp['row'] 			=0;
		    				$tmp['column'] 			=0;
		    				$tmp['row'] 			=$objseat_plan->row;
		    				$tmp['column'] 			=$objseat_plan->column;
		    				$tmp['classes'] 		=$objbusoccurance->classes;
		    				$objseatinfo			=SeatInfo::whereseat_plan_id($objseat_plan->id)->get();
		    				$seat_list_array=array();
		    				if($objseatinfo){
		    					foreach ($objseatinfo as $seats) {
		    						$seat['id'] 			=$seats->id;
		    						$seat['seat_no'] 		=$seats->seat_no;
		    						$seat['status'] 		=$seats->status;
		    						$seat['price'] 			=$objbusoccurance->price;
		    						if($seats->status==0){
		    							$seat['price'] 	="xxx";
		    						}
		    						$checkoccupied 			=SaleItem::wherebusoccurance_id($objbusoccurance->id)->whereseat_no($seats->seat_no)->first();
		    						$customer=array();

		    						if($checkoccupied){
		    							$seat['status'] 		=2;
		    							$customer['id'] 		=333333;
			    						$customer['name'] 		=$checkoccupied->name ? $checkoccupied->name : "-";
			    						$customer['nrc'] 		=$checkoccupied->nrc_no ? $checkoccupied->nrc_no : "-";
		    						}
		    						if(count($customer)<1){
			    						$customer 				='-';
			    					}else{
			    						$seat['customer'] 		=$customer;
		    						}
		    						$seat_list_array[]=$seat;
		    					}
		    				}
		    				$tmp['seat_list']		=$seat_list_array;
		    			}
		    			$temp_seat_plan[]=$tmp;
	    			}
	    			
	    		}
	    		$temp['seat_plan']=$temp_seat_plan;
	    	}
	    	$agent_id=0;
	    	$search=array();
			if($operator_id){
				$responsecities=$this->getCitiesByoperatorId($operator_id, $from, $to);
			}else{
				$responsecities=$this->getCitiesByoperatorIds($operator_ids, $from, $to);
			}
			$search['cities']=$responsecities;

			if($operator_id){
				$responsetime=$this->getTime($operator_id, $from, $to);
			}else{
				$responsetime=$this->getTimes($operator_ids, $from, $to);
			}
			$search['times']=$responsetime;
			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['from']=$from;
			$search['to']=$to;
			$search['time']=$departure_time;

			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['from']=$from;
			$search['to']=$to;
			$search['time']=$departure_time;

	    	return Response::json($temp);
	    	return View::make('busreport.seatoccupiedbybus.index', array('response'=>$temp, 'search'=>$search));
	    }

	    public function getDailyAdvancedTrips($operator_id, $date,$agent_id){
			$report_info 			=array();
	    	/*$date 					=Input::get('date');
			$operator_id  			=Input::get('operator_id');
			$agent_id  				=Input::get('agent_id');*/
			
			$orderids 				=SaleOrder::whereorderdate($date)->lists('id');
			if($orderids){
				$busoccuranceids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
			}
			
			if(isset($busoccuranceids)){
				$filterorderidsbydate=SaleItem::wherein('busoccurance_id',$busoccuranceids)->lists('order_id');
			}

			$response=array();
			if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
				$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
				if($busoccurance_ids){
					$temp['purchased_total_seat']=0;
					$temp['total_amout']=0;
					$i=0;
					foreach ($busoccurance_ids as $occuranceid) {
						$objbusoccurance=BusOccurance::whereid($occuranceid)->where('departure_date','>',$date)->first();
						if($objbusoccurance){
							if($i==0){
								$temp['from']					=City::whereid($objbusoccurance->from)->pluck('name');
								$temp['to']						=City::whereid($objbusoccurance->to)->pluck('name');
								$temp['departure_date']			=$objbusoccurance->departure_date;
								$seat_plan_id					=$objbusoccurance->seat_plan_id;
								$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
								$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
								$total_amount					=SaleItem::wherebusoccurance_id($occuranceid)->sum('price');
								// $temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
								$temp['total_amout']			=$total_amount;
								$response[]						=$temp;
							}else{
								$samedate=0;
								$samekey=0;
								foreach ($response as $key => $value) {
									if($value['departure_date'] == $objbusoccurance->departure_date){
										$samedate +=1;
										$samekey=$key;
									}

								}
								$temp['from']					=City::whereid($objbusoccurance->from)->pluck('name');
								$temp['to']						=City::whereid($objbusoccurance->to)->pluck('name');
								$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
								$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
								$total_amount					=SaleItem::wherebusoccurance_id($occuranceid)->sum('price');
								
								// $temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
								$temp['total_amout']			=$total_amount;
								if($samedate==0){
									$temp['departure_date']			=$objbusoccurance->departure_date;
									$seat_plan_id					=$objbusoccurance->seat_plan_id;
									$response[]						=$temp;
								}else{
									$response[$samekey]['purchased_total_seat'] =$response[$samekey]['purchased_total_seat'] + $temp['purchased_total_seat'];
									$response[$samekey]['total_amout'] 			=$response[$samekey]['total_amout'] + $temp['total_amout'];
									$response[$samekey]['total_seat'] 			=$response[$samekey]['total_seat'] + $temp['total_seat'];
								}
							}
							$i++;
						}						
					}
				}
			}else{
				$response=array();
				return $response;
			}
			return $response;
	    }

	    public function getSeatOccupancydetail(){
	    	$bus_id =Input::get('bus_id');
	    	$objbusoccuranceids[]=(int) $bus_id;
	    	$response =array();
	    	if($objbusoccuranceids){
	    		$objbusoccurance =BusOccurance::whereid($bus_id)->first();
	    		$temp['operator_id']	=$objbusoccurance->operator_id;
	    		$temp['operator_name']	=Operator::whereid($objbusoccurance->operator_id)->pluck('name');
	    		
	    		$seattingplan=array();
	    			$from_city 	=City::whereid($objbusoccurance->from)->pluck('name');
	    			$to_city 	=City::whereid($objbusoccurance->to)->pluck('name');
	    			foreach ($objbusoccuranceids as $busid) {
	    				$objbus=BusOccurance::whereid($busid)->first();
	    				$tmp_seatplan['bus_id']	=$busid;
	    				$tmp_seatplan['from']	=$from_city;
	    				$tmp_seatplan['to']		=$to_city;
			    		$tmp_seatplan['bus_no']=$objbus->bus_no;
			    		$objseatplan=SeatingPlan::whereid($objbus->seat_plan_id)->first();
			    		$tmp_seatplan['row']=$objseatplan->row;
			    		$tmp_seatplan['column']=$objseatplan->column;
			    		$tmp_seatplan['classes']=$objbus->classes;
			    		$tmp_seatplan['class']=Classes::whereid($objbus->classes)->pluck('name');
			    		$tmp_seatplan['date']=$objbus->departure_date;
			    		$tmp_seatplan['time']=$objbus->departure_time;
			    		$tmp_seatplan['total_seats']=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count('id');
			    		$tmp_seatplan['total_sold_seats']=SaleItem::wherebusoccurance_id($objbus->id)->count('id');
			    		$tmp_seatplan['total_amount']=$objbus->price * $tmp_seatplan['total_sold_seats'];

			    		$seatlist=array();
			    			$objseatinfo=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->get();
			    			if($objseatinfo){
			    				foreach ($objseatinfo as $seats) {
			    					$tmp_seatlist['id']=$seats['id'];
			    					$tmp_seatlist['seat_no']=$seats['seat_no'];
						    		$tmp_seatlist['price']=$objbus->price;
			    					if($seats['status']==0){
			    						$tmp_seatlist['seat_no']='xxx';
						    			$tmp_seatlist['price']='xxx';
			    					}
						    		
						    		$tmp_seatlist['status']=$seats['status'];
						    		$checkbuy	=SaleItem::wherebusoccurance_id($objbus->id)->whereseat_no($seats['seat_no'])->first();
						    		


						    		$tmp_seatlist['agent_name']='-';
						    		$customer=null;
						    		$customerphone=$nrc_no=null;
						    		if($checkbuy){
						    			$saleorder=SaleOrder::whereid($checkbuy->order_id)->first();
						    			$agent_name=Agent::whereid($checkbuy->agent_id)->pluck('name');
						    			$nrc_no=$checkbuy->nrc_no ? $checkbuy->nrc_no : $saleorder->nrc_no;
						    			$customerphone=$saleorder->phone;
						    			
						    			if($saleorder->booking==1){
							    			$tmp_seatlist['status']=3;
						    				$agent_name=Agent::whereid($saleorder->agent_id)->pluck('name');
						    			}else{
						    				$tmp_seatlist['status']=2;
						    			}
						    			// dd($agent_name);
						    			$tmp_seatlist['agent_name']=$agent_name;
						    			$customer['id']=null;
						    			$customer['saleitem_id']=$checkbuy->id;
						    			$customer['name']=$checkbuy->name != '' ? $checkbuy->name : '-';
						    			$customer['phone']=$customerphone;
						    			$customer['nrc_no']=$nrc_no;
						    		}
						    		$tmp_seatlist['customer']=$customer;

						    		$seatlist[]=$tmp_seatlist;
			    				}
			    			}
				    		
			    		$tmp_seatplan['seat_list']=$seatlist;
			    		
			    		$seattingplan=$tmp_seatplan;

			    		$seatplan=$seattingplan;	
	    			}
		    		
					$temp['seat_plan']=$seatplan;
				$response=$temp;
	    	}
	    	// return Response::json($response);
	    	return View::make('busreport.seatoccupiedbybus.detail', array('response'=>$response));
	    }

	    public static function prints($link){
	    	try
			{   
			    // create an API client instance
			    $client = new Pdfcrowd("SawNayThuAung", "221765819e6f30e871db3c8f250c2407");

			    // convert a web page and store the generated PDF into a $pdf variable
			    $pdf = $client->convertURI($link);

			    // set HTTP response headers
			    header("Content-Type: application/pdf");
			    header("Cache-Control: max-age=0");
			    header("Accept-Ranges: none");
			    header("Content-Disposition: attachment; filename=\"busocc.pdf\"");

			    // send the generated PDF 
			    echo $pdf;
			}
			catch(PdfcrowdException $why)
			{
			    echo "Pdfcrowd Error: " . $why;
			}
	    }

	public function postCustomerInfoUpdate(){
		$saleitem_id=Input::get('saleitem_id');
		$name=Input::get('customer_name');
		$nrc=Input::get('customer_nrc');
		$obj_saleitem =SaleItem::find($saleitem_id);
		$bus_id=$obj_saleitem->busoccurance_id;
		$obj_saleitem->name=$name;
		$obj_saleitem->nrc_no=$nrc;
		$obj_saleitem->update();
		return Redirect::to('report/seatoccupiedbybus/detail?bus_id='.$bus_id);
	}


}