<?php

class ReportApiController extends \BaseController {

	public function ifExist($key, $arr){
    	if(is_array($arr)){
    		$i = 0;
    		foreach ($arr as $key_row) {
    			if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date']){
    				return $i;
    			}
    			$i++;
    		}
    	}
    	return -1;
    }

    public function ifExistTicket($key, $arr){
		if(is_array($arr)){
    		$i = 0;
    		foreach ($arr as $key_row) {
    			if($key_row['ticket_no']."-".$key_row['vr_no'] == $key['ticket_no']."-".$key['order_id']){
    				return $i;
    			}
    			$i++;
    		}
    	}
    	return -1;
	}

	public function ifExistAgent($key, $arr){
    	if(is_array($arr)){
    		$i = 0;
    		foreach ($arr as $key_row) {
    			if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date'] && $key_row['agent_id'] == $key['agent_id']){
    				return $i;
    			}
    			$i++;
    		}
    	}
    	return -1;
    }

	// Calculate Commission by Trip, Agent and Date
	public function calculatecommission($trip_id, $agent_id, $order_date){
		$agent_commission = AgentCommission::wheretrip_id($trip_id)->whereagent_id($agent_id)
											->where('start_date','<=',$order_date)
											->where('end_date','>=',$order_date)
											->first();
		if($agent_commission){
			$commission = $agent_commission->commission;
		}else{
			$commission = Trip::whereid($trip_id)->pluck('commission');
		}
		return $commission;
	}



	/**
	 * Daily Report
	 *
	 * @return Response
	 */
	public function getDailyReport()
    {
    	$param=json_decode(MCrypt::decrypt(Input::get('param')));
    	// return Response::json($param);
    	$access_token	=$param->access_token;
    	$operator_id	=$param->operator_id;
    	$encu_name		=$param->encu_name;
    	$agent_codeno	=$param->code_no;
    	$group_branch	=$param->group_branch;
    	$agent_ids		=array();
    	if($group_branch==0){
    		$agentgroup_id=AgentGroup::wherecode_no($agent_codeno)->pluck('id');
    		$agent_ids =Agent::whereagentgroup_id($agentgroup_id)->lists('id');
    	}else{
    		$agent_ids[] =Agent::wherecode_no($agent_codeno)->pluck('id');
    	}
    	// dd($agent_ids)
    	$today   			=$this->getDate();
    	$date				=$param->date ? $param->date : $today;
    	$date  	=str_replace('/', '-', $date);
    	$date   =date('Y-m-d', strtotime($date));
    	Session::put('search_daily_date', $date);
    	$sale_item=array();
    	
    	if($agent_ids){
		    $order_ids = SaleOrder::wherein('agent_id',$agent_ids)->where('orderdate','=',$date)->where('operator_id','=',$operator_id)->where('name','!=','')->wherebooking(0)->lists('id');
    	}else{
	    	$order_ids = SaleOrder::where('orderdate','=',$date)->where('operator_id','=',$operator_id)->where('name','!=','')->wherebooking(0)->lists('id');
    	}

    	if($order_ids)
			$sale_item = SaleItem::wherein('order_id', $order_ids)
								->selectRaw('order_id, count(*) as sold_seat, trip_id, agent_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket , SUM(discount) as discount')
								->groupBy('order_id')->orderBy('departure_date','asc')->get();
		$lists = array();
		foreach ($sale_item as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$trip = Trip::whereid($rows->trip_id)->first();
			if($trip){
				$list['access_token']	= $access_token;
				$list['operator_id'] 	= $operator_id;
				$list['encu_name'] 		= $encu_name;
				$list['id'] 			= $rows->trip_id;
				$list['departure_date']	= $rows->departure_date;
				$list['from_id'] 		= $trip->from;
				$list['to_id'] 			= $trip->to;
				$list['from_to'] 		= City::whereid($trip->from)->pluck('name').' => '.City::whereid($trip->to)->pluck('name');
				$operator_name 			=Operator::whereid($trip->operator_id)->pluck('name');
				
				if($agent_ids){
					$list['from_to'] 	.= " ( ".$operator_name. " )";
				}
				$extra_city_id= ExtraDestination::wheretrip_id($rows->trip_id)->pluck('city_id');
				if($extra_city_id){
					$list['from_to'] .=' => '.City::whereid($extra_city_id)->pluck('name');
				}
				$list['time'] = $trip->time;
				//To Change AM to PM;
	    		$time 			= $trip->time;
				$subtime 		= substr($time, 0, 8);
				$time 			= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
	    		$list['time_unit'] = strtotime($rows->departure_date.' '.substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
				$list['class_id'] = $trip->class_id;
				$list['class_name'] = Classes::whereid($trip->class_id)->pluck('name');
				$list['from_to_time']=$list['from_to']. " (".$trip->time.")";
				if(SaleOrder::whereid($rows->order_id)->pluck('nationality') == 'local'){
					$local_person += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->price * $rows->sold_seat) - ($rows->price * $rows->free_ticket) : $rows->price * $rows->sold_seat ;
					$total_amount  = $total_amount - $rows->discount;
				}else{
					$foreign_price += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->foreign_price * $rows->sold_seat) - ($rows->foreign_price * $rows->free_ticket) : $rows->foreign_price * $rows->sold_seat ;
					$total_amount  = $total_amount - $rows->discount;
				}
				// Substraction with Agent Commission;
				$agent_commission = AgentCommission::wheretrip_id($rows->trip_id)->whereagent_id($rows->agent_id)->pluck('commission');
				if($agent_commission){
					$total_amount = $total_amount - ($agent_commission * $rows->sold_seat);
				// Else Substraction with Trip Commission;
				}else{
					$trip_commission = Trip::whereid($rows->trip_id)->pluck('commission');
					$total_amount = $total_amount - ($trip_commission * $rows->sold_seat);
				}
				$list['local_person'] = $local_person;
				$list['foreign_person'] = $foreign_price;
				$list['local_price'] = $rows->price;
				$list['foreign_price'] = $rows->foreign_price;
				$list['sold_seat'] = $rows->sold_seat;
				$list['free_ticket'] = $rows->free_ticket;
				$list['discount'] = $rows->discount;
				$list['total_amount'] = $total_amount;
				$lists[] = $list;
			}
		}
		$stack = array();
		foreach ($lists as $rows) {
			$check = $this->ifExist($rows, $stack);
			if($check != -1){
				$stack[$check]['local_person'] += $rows['local_person'];
				$stack[$check]['foreign_person'] += $rows['foreign_person'];
				$stack[$check]['sold_seat'] += $rows['sold_seat'];
				$stack[$check]['free_ticket'] += $rows['free_ticket'];
				$stack[$check]['discount'] += $rows['discount'];
				$stack[$check]['total_amount'] += $rows['total_amount'];
			}else{
				array_push($stack, $rows);
			}
		}
    	$search['operator_id']=$operator_id !=null ? $operator_id : 0;
		$search['date']=$date;
		$sorted_stack=$this->msort($stack, array("time_unit"), $sort_flags=SORT_REGULAR, $order=SORT_ASC);
		return Response::json($sorted_stack);
		// return View::make('busreport.daily.index', array('dailyforbus'=>$sorted_stack, 'search'=>$search));	
    }

	/**
	 * Display a Daily Sale Detail.
	 *
	 * @return Response
	 */
	public function getDailyReportDetail()
    {
    	$encryptparams  =Input::get('param');
    	$decryptparams 	=json_decode(MCrypt::decrypt($encryptparams));
    	// return Response::json($decryptparams);

    	$operator_id    =$decryptparams->operator_id;
    	// $operator_id    =MCrypt::decrypt($operator_id);
    	$encu_name    	=$decryptparams->encu_name;
    	$code_no   		=$decryptparams->code_no;
    	$group_branches =$decryptparams->group_branch;
    	$date 			=$decryptparams->date;
    	$departure_date =$decryptparams->departure_date;
    	$trip_id  		=$decryptparams->trip_id;
    	// dd($operator_id);
    	$agent_ids=array();
    	if($group_branches==0){
    		$agentgroup_id=AgentGroup::wherecode_no($code_no)->pluck('id');
    		$agent_ids=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
    	}else{
    		$agent_ids[]=Agent::wherecode_no($code_no)->pluck('id');
    	}


		$search=array();
    	if($agent_ids){
			$sale_order = SaleOrder::wherein('agent_id',$agent_ids)
								->with('saleitems')->whereHas('saleitems',function($query) use($trip_id,$departure_date) {
										if($trip_id){
											$query->wheretrip_id($trip_id)->where('departure_date','=',$departure_date);
										}
								})->where('orderdate','=',$date)
								  ->where('operator_id','=',$operator_id)
								  ->where('booking','=',0)
								  ->where('name','!=','')
								  ->get();
    		
    	}
    	
    	$lists = array();
    	foreach ($sale_order as $rows) {
    		
    		$seats_no = "";
    		$from_to  = null;
    		$time = null;
    		$price = 0;
    		$foreign_price = 0;
    		$class_id = null;
    		$agent_commission = null;
    		$commission = 0;
    		$local_person = 0;
    		$foreign_person = 0;
    		$free_ticket = 0;
    		$total_amount = 0;

    		foreach ($rows->saleitems as $seat_row) {
    			$check = $this->ifExistTicket($seat_row, $lists);
    			// Already exist ticket no.
    			if($check != -1){
    				$seats_no = $lists[$check]['seat_no'] .", ".$seat_row->seat_no;
    				$free_ticket = $lists[$check]['free_ticket'] + $seat_row->free_ticket;
    				$commission = $lists[$check]['commission'];
    				if($rows->nationality == 'local'){
						$local_person = $lists[$check]['local_person'] + 1;
						$total_amount = $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission);
						$total_amount = $total_amount - $seat_row->discount;
					}else{
						$foreign_person = $lists[$check]['foreign_person'] + 1;
						$total_amount 	=  $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission);
						$total_amount 	= $total_amount - $seat_row->discount;
					}
					$lists[$check]['seat_no'] = $seats_no;
					$lists[$check]['local_person'] = $local_person;
					$lists[$check]['foreign_person'] = $foreign_price;
					$lists[$check]['free_ticket'] = $free_ticket;
					$lists[$check]['discount'] =  $lists[$check]['discount'] + $seat_row->discount;
					$lists[$check]['sold_seat'] += 1;
		    		$lists[$check]['total_amount'] = $total_amount;
    			}else{
    				$list['vr_no'] = $rows->id;
    				$list['ticket_no'] = $seat_row->ticket_no;
		    		$list['order_date'] = $rows->orderdate;
		    		$list['from_to'] = '';
		    		$list['departure_date'] = $rows->departure_date;
	    			$seats_no = $seat_row->seat_no.", ";
	    			$from_to   = City::whereid($seat_row->from)->pluck('name').' => '.City::whereid($seat_row->to)->pluck('name');
	    			if($seat_row->extra_city_id){
	    				$from_to .=' => '. City::whereid($seat_row->extra_city_id)->pluck('name');
	    			}
	    			$time = Trip::whereid($seat_row->trip_id)->pluck('time');
	    			$price = $seat_row->price;
	    			$free_ticket = $seat_row->free_ticket;
	    			$foreign_price = $seat_row->foreign_price;
	    			$class_id = Trip::whereid($seat_row->trip_id)->pluck('class_id');
	    			$owner = Agent::whereid($rows->agent_id)->pluck('owner');
	    			if($owner == 0){
			    		$commission =$this->calculatecommission($seat_row->trip_id, $rows->agent_id, $rows->orderdate);
	    			}else{
	    				$commission = 0;
	    			}

		    		if($rows->nationality == 'local'){
						$local_person = 1;
						$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission;
						$total_amount = $total_amount - $seat_row->discount;
					}else{
						$foreign_person = 1;
						$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission;
						$total_amount = $total_amount - $seat_row->discount;
					}

					$list['from_to'] = $from_to;
					if($agent_ids){
						$operator_name =Operator::whereid($seat_row->operator)->pluck('name');
						$list['from_to'] .= " ( ".$operator_name." )";
					}
		    		$list['time'] = $time;
		    		//To Change AM to PM;
		    		$subtime 		= substr($time, 0, 8);
					$time 			= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
		    		$list['time_unit'] = strtotime($rows->departure_date.' '.substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
		    		$list['classes'] = Classes::whereid($class_id)->pluck('name');
		    		//for group trip and class
		    		$list['from_to_class']=$from_to.' ( '.$list['classes'].' )';
		    		$list['agent_name'] = Agent::whereid($rows->agent_id)->pluck('name');
		    		$list['buyer_name'] = $seat_row->name;
		    		$list['commission'] = $commission;
		    		$list['seat_no'] = substr($seats_no, 0, -2);
		    		$list['sold_seat'] = 1;
		    		$list['local_person'] = $local_person;
					$list['foreign_person'] = $foreign_price;
		    		$list['price'] = $price;
		    		$list['foreign_price'] = $foreign_price;
		    		$list['free_ticket'] = $free_ticket;
		    		$list['discount'] = $seat_row->discount;
		    		$list['total_amount'] = $total_amount;
		    		$lists[] = $list;
		    		/*if($bus_id)
		    			$search['trip']=$list['from_to_class'];*/
    			}
    			
    		}		
    	}
    	//return Response::json($lists);
    	$lists = $this->msort($lists,array("time_unit"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
    	// SORTING AND GROUP BY TRIP AND BUSCLASS
	    	// group
	    	$tripandclassgroup = array();
			foreach ($lists AS $arr) {
			  $tripandclassgroup[$arr['from_to']][] = $arr;
			}
	    	// sorting
			// ksort($tripandclassgroup);
    	return Response::json($tripandclassgroup);
    	// return View::make('busreport.daily.detail', array('response'=>$tripandclassgroup,'trip_id'=>$trip_id,'search'=>$search));	
    }


    public function getReportByAgent()
    {
    	$params =Input::get('param');
    	$params=json_decode(MCrypt::decrypt($params));
    	// return Response::json($params);
		$report_info 			=array();
		$search=array();
		$agent_ids=array();


		$operator_id  			=$params->operator_id;
		$agent_id 				=$params->agent_id;
		$from  					=$params->from;
		$to  					=$params->to;
		$start_date  			=$params->start_date ? $params->start_date : $this->getDate();
		$end_date  				=$params->end_date ? $params->end_date : $this->getDate();
		$start_date				=date('Y-m-d', strtotime($start_date));
		$end_date				=date('Y-m-d', strtotime($end_date));
		$departure_time  		=$params->departure_time;
		$departure_time			=str_replace('-', ' ', $departure_time);
		$agentgroup_codeno 		=$params->agentgroup_codeno;
		$administrator 			=$params->administrator;
		if($agentgroup_codeno){
			$agentgroup_ids = AgentGroup::wherein('code_no',$agentgroup_codeno)->lists('id');
			if($agentgroup_ids){
				$agent_ids=Agent::wherein('agentgroup_id', $agentgroup_ids)->lists('id');
			}
		}/*else{
			if($agent_id){
				$agent_ids[] =$agent_id;
			}else{
				$agent_ids =Agent::whereagentgroup_id($agentgroup_id)->lists('id');
			}
		}*/
		
				
		$trip_ids=array();
		$sale_item=array();
    	$order_ids=array();
		if($from && $to)
		{
			if($departure_time){
				$trip_ids=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->wheretime($departure_time)->lists('id');
			}else{
				$trip_ids=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->lists('id');
			}
		}else{
			if($departure_time){
				$trip_ids=Trip::whereoperator_id($operator_id)
									->wheretime($departure_time)->lists('id');
			}else{
				$trip_ids=Trip::whereoperator_id($operator_id)
									->lists('id');
			}
		}
		// dd($trip_ids);
    	//select order_ids
    	// dd($agent_ids);
    	if($trip_ids){
    		if($agent_ids){
	    		$order_ids=SaleItem::wherein('agent_id',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
			}else{
    			$order_ids=SaleItem::wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
			}
    	}
		
    	//check bookin status
    	if($order_ids)
    		$order_ids=SaleOrder::wherein('id',$order_ids)->where('name','!=','')->wherebooking(0)->lists('id');
		
		// return Response::json($order_ids);
    	if($order_ids)
    	{	
    		/******************************************************************************** 
			*	For agent report by agent group and branches OR don't have branch agent
    		***/
    			if($agent_ids){
    				$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
									->wherein('agent_id',$agent_ids)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
    			}
    	}
			
		$lists = array();
		foreach ($sale_item as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$commission=0;
			$percent_total=0;
			$trip = Trip::whereid($rows->trip_id)->first();
			$order_date=SaleOrder::whereid($rows->order_id)->pluck('orderdate');
			$list['order_date'] = $order_date;
			$agent_name=Agent::whereid($rows->agent_id)->pluck('name');
			$list['agent_id']=$rows->agent_id ? $rows->agent_id : 0;
			$list['agent_name']=$agent_name ? $agent_name : "-";
			
			if($trip){
				$list['id'] = $rows->trip_id;
				$list['bus_id'] = $rows->busoccurance_id;
				$list['departure_date'] = $rows->departure_date;
				$list['from_id'] = $trip->from;
				$list['to_id'] = $trip->to;
				$list['from_to'] = City::whereid($trip->from)->pluck('name').' => '.City::whereid($trip->to)->pluck('name');
				$extra_city_id=ExtraDestination::wheretrip_id($trip->id)->pluck('city_id');
				if($extra_city_id){
					$list['from_to'] .=' => '.City::whereid($extra_city_id)->pluck('name');
				}
				if($agent_ids){
					$list['from_to'] .=' ( '.Operator::whereid($rows->operator)->pluck('name').' )';
				}
				
				$list['time'] = $trip->time;
	    		//To Change AM to PM;
	    		$time 			= $trip->time;
				$subtime 		= substr($time, 0, 8);
				$time 			= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
	    		$list['time_unit'] = strtotime($rows->departure_date.' '.substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
				$list['class_id'] = $trip->class_id;
				$list['class_name'] = Classes::whereid($trip->class_id)->pluck('name');
				
				$list['from_to_class']=$list['from_to']. "(".$list['class_name'].")";
				
				$nationality=SaleOrder::whereid($rows->order_id)->pluck('nationality');
				
				$owner = Agent::whereid($rows->agent_id)->pluck('owner');
				if($owner == 0){
		    		$commission =$this->calculatecommission($rows->trip_id, $rows->agent_id, $order_date);
				}else{
					$commission = 0;
				}

				if( $nationality== 'local'){
					$local_person += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->price * $rows->sold_seat) - ($rows->price * $rows->free_ticket) : $rows->price * $rows->sold_seat ;
					$tmptotal      =$rows->price * ($rows->sold_seat- $rows->free_ticket);
					$percent_total +=$tmptotal - ($commission * ($rows->sold_seat- $rows->free_ticket));
					$percent_total  = $percent_total - $rows->discount;
				}else{
					$foreign_price += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->foreign_price * $rows->sold_seat) - ($rows->foreign_price * $rows->free_ticket) : $rows->foreign_price * $rows->sold_seat ;
					$tmptotal      =$rows->foreign_price * ($rows->sold_seat- $rows->free_ticket);
					$percent_total +=$tmptotal - ($commission * ($rows->sold_seat- $rows->free_ticket));
					$percent_total  = $percent_total - $rows->discount;
				}
				$list['local_person'] = $local_person;
				$list['foreign_person'] = $foreign_price;
				$list['local_price'] = $rows->price;
				$list['foreign_price'] = $rows->foreign_price;
				$list['sold_seat'] = $rows->sold_seat;
				$list['free_ticket'] = $rows->free_ticket;
				$list['discount'] = $rows->discount;
				$list['total_amount'] = $total_amount;
				$list['percent_total'] = $percent_total;
				$lists[] = $list;
			}
		}
		//Grouping from Lists
		$stack = array();
		foreach ($lists as $rows) {
			// if($search['agent_rp'])
				$check = $this->ifExistAgent($rows, $stack);
			/*else
				$check = $this->ifExist($rows, $stack);*/
			if($check != -1){
				$stack[$check]['local_person'] += $rows['local_person'];
				$stack[$check]['foreign_person'] += $rows['foreign_person'];
				$stack[$check]['sold_seat'] += $rows['sold_seat'];
				$stack[$check]['free_ticket'] += $rows['free_ticket'];
				$stack[$check]['discount'] += $rows['discount'];
				$stack[$check]['total_amount'] += $rows['total_amount'];
				$stack[$check]['percent_total'] += $rows['percent_total'];
			}else{
				array_push($stack, $rows);
			}
		}

		

		$search['operator_id']=$operator_id;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;
		$search['start_date']=$start_date;
		$search['end_date']=$end_date;
		$search['agentgroup_id']=Input::get('agentgroup')? Input::get('agentgroup') : 0;
		$search['agent_id']=$agent_id;


		if($search['agentgroup_id'] && $search['agentgroup_id'] !='All')
			$agent=Agent::whereagentgroup_id($search['agentgroup_id'])->get();
		else
			$agent=Agent::whereoperator_id($operator_id)->get();

		$search['agent']=$agent;
		
		// sorting result
		$response=$this->msort($stack,array("time_unit"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
		// grouping
		$tripandorderdategroup = array();
		foreach ($response AS $arr) {
		  $tripandorderdategroup[$arr['agent_name']][] = $arr;
		}
		
		ksort($tripandorderdategroup);
		return Response::json($tripandorderdategroup);
		// return Response::json($search);
		// return View::make('busreport.operatortripticketsolddaterange', array('response'=>$tripandorderdategroup, 'search'=>$search));
	}

	public function getAgentGroupList(){
		$params=Input::get('param');
		$params=json_decode(MCrypt::decrypt($params));
		$agentgroupcodes=$params->agentgroup_codeno;
		$agentgroups=array();
		if($agentgroupcodes){
			$agentgroups=AgentGroup::wherein('code_no',$agentgroupcodes)->get(array('id','name'));
		}
		return Response::json($agentgroups);
	}


	public function getAgentsByOperatorRp(){
    	$operator_id=Input::get('operator_id');
    	$code_no=Input::get('code_no');
    	$group_branches=Input::get('group_branches');

    	if(!$operator_id){
    		$response['message']="operator_id is required.";
    		return Response::json($response);
    	}

    	$agentgroup_id=null;
    	if($group_branches==0 && $code_no){
    		$agentgroup_id=AgentGroup::wherecode_no($code_no)->pluck('id');
    	}
    	if($group_branches==1 && $code_no){
    		$agentgroup_id=Agent::wherecode_no($code_no)->pluck('agentgroup_id');
    	}
    	if($agentgroup_id){
    		if($group_branches==1){
	    		$agents=Agent::whereagentgroup_id($agentgroup_id)->wherecode_no($code_no)->get(array('id','name'));
    		}else{
	    		$agents=Agent::whereagentgroup_id($agentgroup_id)->get(array('id','name'));
    		}
    	}else{
    		// dd($agentgroup_id);
	    	$agent_ids= SaleOrder::whereoperator_id($operator_id)->groupBy('agent_id')->lists('agent_id');
	    	$agents=array();
	    	// $agents=null;
	    	if($agent_ids){
	    		$agents=Agent::wherein('id', $agent_ids)->get(array('id','name'));
	    	}	
    	}
    	
    	$response['operator_id']=$operator_id;
    	$response['agents']=$agents->toarray();
    	if($code_no){
    		$ticketnorange =$this->checkagentticketno($agentgroup_id);
    		$response['checkticketnorange']=$ticketnorange;
    	}
    	return Response::json($response);
    }
	public function checkagentticketno($agentgroup_id){
		$agent_ids=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
		$maxticketno=null;
		if($agent_ids)
			$maxticketno=SaleItem::where('ticket_no','!=',0)->wherein('agent_id',$agent_ids)->orderBy('id','desc')->pluck('ticket_no');
		
		// Ticket No range
			if($maxticketno){
				$ticketnorange =AgentTicketNo::whereagentgroup_id($agentgroup_id)
											->where('st_ticket_no','<=',$maxticketno)
											->where('ed_ticket_no','>=',$maxticketno)
											->first(array('st_ticket_no','ed_ticket_no'));
				if($ticketnorange){
					$ticketnorange =$ticketnorange->toarray();
				}else{
					$ticketno_range =AgentTicketNo::whereagentgroup_id($agentgroup_id)
											->first(array('st_ticket_no','ed_ticket_no'));
					if($ticketno_range){
						$ticketnorange =$ticketno_range->toarray();
					}else{
						$ticketnorange=array();	
						$ticketnorange['st_ticket_no']='';	
						$ticketnorange['ed_ticket_no']='';	
					}
				}
			}else{
				$ticketno_range =AgentTicketNo::whereagentgroup_id($agentgroup_id)
											->first(array('st_ticket_no','ed_ticket_no'));
				if($ticketno_range){
					$ticketnorange =$ticketno_range->toarray();
				}else{
					$ticketnorange=array();	
					$ticketnorange['st_ticket_no']='';	
					$ticketnorange['ed_ticket_no']='';	
				}
			}
		//end Ticket No range


		if($maxticketno){
			$maxticketno= substr($maxticketno, 0, 6);
			$maxticketno=sprintf('%06s',++$maxticketno);
			$ed_ticket_no =AgentTicketNo::whereagentgroup_id($agentgroup_id)
										->where('st_ticket_no','<=',$maxticketno)
										->where('ed_ticket_no','>=',$maxticketno)
										// ->orderBy('id','desc')
										->pluck('ed_ticket_no');
			if(!$ed_ticket_no){
				$st_ticket_no =AgentTicketNo::whereagentgroup_id($agentgroup_id)->pluck('st_ticket_no');
				if($st_ticket_no){
					$maxticketno =$st_ticket_no;
				}else{
					$maxticketno="-";
				}
			}
		    
		}else{
			$st_ticket_no =AgentTicketNo::whereagentgroup_id($agentgroup_id)->pluck('st_ticket_no');
			if($st_ticket_no)
				$maxticketno=$st_ticket_no;
			else{
				$maxticketno="-";
			}
		}
		$response=array();
		$response['maxticketno']=$maxticketno;
		$response['ticketnorange']=$ticketnorange;
		return $response;
    }

}
