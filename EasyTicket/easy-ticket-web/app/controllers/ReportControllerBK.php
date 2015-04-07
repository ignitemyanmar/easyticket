<?php
/* 
	Dalily Report list 
	Daily report detail

	Trip report by order date
	Agent report by order date

	Trip report by order date detail
	Agent report by order date detail

	Seatoccupied by bus detail 

*/
class ReportController extends BaseController
{
	public static function getTimes($operator_id, $from_city, $to_city){
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

	// Dalily Report list
	// '@/report/dailycarandadvancesale'
    public function getDailyReportforTrip()
    {
    	$agentgroup_id =$this->myGlob->agentgroup_id;
    	$agopt_ids =$this->myGlob->agopt_ids;
    	$agent_ids=$this->myGlob->agent_ids;
    	$operator_id=$this->myGlob->operator_id;
    	$departure_time		=Input::get('departure_time');
    	$agent_id			=Input::get('agent_id');
    	$today   			=$this->getDate();
    	$date				=Input::get('date') ? Input::get('date') : $today;
    	$date  	=str_replace('/', '-', $date);
    	$date   =date('Y-m-d', strtotime($date));
    	Session::put('search_daily_date', $date);
    	$sale_item=array();
    	
    	if($agent_ids){
    		if($agopt_ids){
    			if(Auth::user()->role==9){
    				$order_ids = SaleOrder::wherein('operator_id',$agopt_ids)
		    				->where('orderdate','=',$date)
		    				->where('name','!=','')->wherebooking(0)->lists('id');
    			}else{
    				$order_ids = SaleOrder::wherein('operator_id',$agopt_ids)
		    				->wherein('agent_id',$agent_ids)
		    				->where('orderdate','=',$date)
		    				->where('name','!=','')->wherebooking(0)->lists('id');	
    			}
		    	
    		}else{
		    	$order_ids = SaleOrder::wherein('agent_id',$agent_ids)->where('orderdate','=',$date)->where('operator_id','=',$operator_id)->where('name','!=','')->wherebooking(0)->lists('id');
    		}
    	}else{
	    	$order_ids = SaleOrder::where('orderdate','=',$date)->where('operator_id','=',$operator_id)->where('name','!=','')->wherebooking(0)->lists('id');
    	}

    	if($order_ids)
			$sale_item = SaleItem::wherein('order_id', $order_ids)
								->selectRaw('order_id, count(*) as sold_seat, trip_id, agent_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket , SUM(discount) as discount')
								->groupBy('order_id')->orderBy('departure_date','asc')->get();
		$lists = array();
		foreach ($sale_item as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$trip = Trip::whereid($rows->trip_id)->first();
			if($trip){
				$list['id'] = $rows->trip_id;
				$list['bus_id'] = $rows->busoccurance_id;
				$list['departure_date'] = $rows->departure_date;
				$list['from_id'] = $trip->from;
				$list['to_id'] = $trip->to;
				$list['from_to'] = City::whereid($trip->from)->pluck('name').' => '.City::whereid($trip->to)->pluck('name');
				$operator_name =Operator::whereid($trip->operator_id)->pluck('name');
				if($agent_ids){
					$list['from_to'] .=" ( ".$operator_name. " )";
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
		//return Response::json($lists);
		//Grouping from Lists
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
		// return Response::json($sorted_stack);
		return View::make('busreport.daily.index', array('dailyforbus'=>$sorted_stack, 'search'=>$search));	
    }

    //Daily report detail
    //'@/report/dailycarandadvancesale/detail'
    public function getDetailDailyReportforBus()
    {
    	$operator_id 	=$this->myGlob->operator_id;
    	$agentgroup_id 	=$this->myGlob->agentgroup_id;
    	$agent_ids 		=$this->myGlob->agent_ids;
    	$agopt_ids 		=$this->myGlob->agopt_ids;

    	$date 			=Input::get('date') ? Input::get('date') : date('Y-m-d');
    	Session::put('search_daily_date',$date);
    	$bus_id  		=Input::get('bus_id');
		$search=array();
		$search['trip']="";
    	$search['date']=$date;
    	if($agent_ids){
    		if($agopt_ids){
    			if(Auth::user()->role==9){
    				$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
									->with('saleitems')->whereHas('saleitems',function($query){
											$bus_id = Input::get('bus_id');
											if($bus_id){
												$query->wherebusoccurance_id($bus_id);
											}
									})->where('orderdate','=',$date)
									  // ->where('operator_id','=',$operator_id)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();
    			}else{
    				$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
    								->wherein('agent_id',$agent_ids)
									->with('saleitems')->whereHas('saleitems',function($query){
											$bus_id = Input::get('bus_id');
											if($bus_id){
												$query->wherebusoccurance_id($bus_id);
											}
									})->where('orderdate','=',$date)
									  // ->where('operator_id','=',$operator_id)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();
    			}
    			
    		}else{
    			$sale_order = SaleOrder::wherein('agent_id',$agent_ids)
									->with('saleitems')->whereHas('saleitems',function($query){
											$bus_id = Input::get('bus_id');
											if($bus_id){
												$query->wherebusoccurance_id($bus_id);
											}
									})->where('orderdate','=',$date)
									  ->where('operator_id','=',$operator_id)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();
    		}
    		
    	}else{
    		$sale_order = SaleOrder::with('saleitems')
									->whereHas('saleitems',function($query){
											$bus_id = Input::get('bus_id');
											if($bus_id){
												$query->wherebusoccurance_id($bus_id);
											}
									})->where('orderdate','=',$date)
									  ->where('operator_id','=',$operator_id)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();	
    	}
    	//return Response::json($sale_order);
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
		$count=(count($tripandclassgroup));
		$i=1;
		$search['start_trip']='';
		$search['end_trip']='';
		foreach ($tripandclassgroup as $key => $value) {
			if($i==1)
				$search['start_trip']=$key;
			if($i==$count)
				$search['end_trip']=$key;
			$i++;
		}
    	//return Response::json($tripandclassgroup);
    	return View::make('busreport.daily.detail', array('response'=>$tripandclassgroup,'bus_id'=>$bus_id,'search'=>$search));	
    }
   
	
	// Agent report by order date
	// @/report/operator/trip/dateranges
	public function getTripslistreportOperator()
	{
		$report_info 			=array();
		$operator_id  			=$this->myGlob->operator_id;
		$agent_ids     			=$this->myGlob->agent_ids;	
		$agopt_ids     			=$this->myGlob->agopt_ids;	
		$operator_ids=array();

		if($agent_ids){
			$operator_ids=$agopt_ids;
		}else{
			$operator_ids[]=$operator_id;
		}

		$agent_status=$agent_id	=Input::get('agent_id') ? Input::get('agent_id') : 0;
		$trips  				=Input::get('trips'); //for agent report or not
		$search=array();
		$search['agent_rp']		=$agent_id ? 1 : 0;
		if($agent_id=="All")
			$agent_id='';

		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date') ? Input::get('start_date') : $this->getDate();
		$end_date  				=Input::get('end_date') ? Input::get('end_date') : $this->getDate();
		$start_date				=date('Y-m-d', strtotime($start_date));
		$end_date				=date('Y-m-d', strtotime($end_date));
		$departure_time  		=Input::get('departure_time');
		$departure_time			=str_replace('-', ' ', $departure_time);

	    $operator_id 	=$operator_id ? $operator_id : $this->myGlob->operator_id;

	    if($from=='all')
	    	$from=0;

		$trip_ids=array();
		$sale_item=array();
    	$order_ids=array();

		if($from && $to)
		{
			if($departure_time){
				$trip_ids=Trip::wherein('operator_id',$operator_ids)
									->wherefrom($from)
									->whereto($to)
									->wheretime($departure_time)->lists('id');
			}else{
				$trip_ids=Trip::wherein('operator_id',$operator_ids)
									->wherefrom($from)
									->whereto($to)
									->lists('id');
			}
		}
		else{
			if($departure_time){
				$trip_ids=Trip::wherein('operator_id',$operator_ids)
									->wheretime($departure_time)->lists('id');
			}else{
				$trip_ids=Trip::wherein('operator_id',$operator_ids)
									->lists('id');
			}
		}
    	
    	//select order_ids
    	if($trip_ids){
    		if($agent_ids){
    			if($agopt_ids){
    				if(Auth::user()->role==9){
		    			$order_ids=SaleItem::wherein('operator',$agopt_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
    				}else{
		    			$order_ids=SaleItem::wherein('operator',$agopt_ids)->wherein('agent_id',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
    				}
    			}else{
	    			$order_ids=SaleItem::wherein('agent_id',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
    			}
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
				$agentgroup_id 			=Input::get('agentgroup');
				if($agentgroup_id=="All")
					$agentgroup_id=0;
				$arr_agent_id			=array();

				if($agentgroup_id && $agent_id)
				{
					$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
									->whereagent_id($agent_id)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
				}
				elseif(!$agentgroup_id && $agent_id)
				{
					$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
									->whereagent_id($agent_id)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
				}
				elseif($agentgroup_id && !$agent_id)
				{
					$arr_agent_id=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
	    			
	    			$order_ids2=array();
	    			if($arr_agent_id)
	    				$order_ids2=SaleItem::wherein('agent_id',$arr_agent_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
	    			// for unique orderids for all agent branches
	    			$order_id_list=array_intersect($order_ids, $order_ids2);
	    			// dd($order_id_list);
					if($order_id_list)
						$sale_item = SaleItem::wherein('order_id', $order_id_list)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
									// ->whereagent_id($agent_id)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
				}
			/***
			*	End For agent report by agent group and branches OR don't have branch agent
    		*********************************************************************************/
			
			/******************************************************************* 
			* for Trip report 
			*/
				else
				{
					
					$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
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
			if($search['agent_rp'])
				$check = $this->ifExistAgent($rows, $stack);
			else
				$check = $this->ifExist($rows, $stack);
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

		$search['agentgroup']="";
		$agentgroup=array();
		if($agent_ids){
			$agentgroup=AgentGroup::whereid($this->myGlob->agentgroup_id)->get();
		}else{
			$agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
		}
		if(Auth::user()->role ==9){
			$agentgroup=AgentGroup::get();
		}

		$search['agentgroup']=$agentgroup;
    	
    	$cities=array();
    	if($agopt_ids)
    		$cities=$this->getCitiesByoperatorIds($agopt_ids);
    	else
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
		if($search['agent_rp']==1){
			$tripandorderdategroup = array();
			foreach ($response AS $arr) {
			  $tripandorderdategroup[$arr['agent_name']][] = $arr;
			}
		}
		else
		{
			$tripandorderdategroup = array();
			// return Response::json($response);
			foreach ($response AS $arr) {
			  $tripandorderdategroup[$arr['from_to_class']][] = $arr;
			}
	    	// sorting
		}
		ksort($tripandorderdategroup);
		//return Response::json($tripandorderdategroup);
		return View::make('busreport.operatortripticketsolddaterange', array('response'=>$tripandorderdategroup, 'search'=>$search));
	}



	//Trip report by order date detail
	// Agent report by order date detail
	// '@/triplist/{date}/daily'
	public function getTripsSellingReportbyDaily($date)
	{
		$bus_id=Input::get('bus_id');
		$order_date=explode(',', $date);
		$from=Input::get('f');
		$to=Input::get('t');
		$departure_time=Input::get('time');
		$agent_id=Input::get('a');
		$agentrp=Input::get('agentrp');
		$start_date="";
		$end_date="";
		if($bus_id){
			$date=$order_date[0];
			$start_date=$order_date[0];
			$end_date=$order_date[0];
		}else{
			$start_date=$order_date[0];
			$end_date=$order_date[1];
		}
		
    	$operator_id 	=$this->myGlob->operator_id;
    	$agent_ids 	 	=$this->myGlob->agent_ids;
    	$agopt_ids 	 	=$this->myGlob->agopt_ids;
    	$operator_ids   =array();
    	$sale_order     =array();
    	if($agent_ids){
    		$operator_ids=$agopt_ids;
    	}else{
    		$operator_ids[]=$operator_id;
    	}
	    	Session::put('search_daily_date',$date);
	    	$bus_id  		=Input::get('bus_id');
		
			//for only one transaction
	    	if($bus_id){
	    		//for choose agent
	    		if($agent_id){
	    			if($agentrp){ //for agent report
	    				if($agent_ids){
	    					if($agopt_ids){
	    						if(Auth::user()->role==9){
	    							$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id)->whereagent_id($agent_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    						}else{
	    							$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)->wherein('agent_id',$agent_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id)->whereagent_id($agent_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();	
	    						}
	    						
	    					}else{
	    						$sale_order = SaleOrder::wherein('agent_id',$agent_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id)->whereagent_id($agent_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    					}
	    						
	    				}else{
	    					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id)->whereagent_id($agent_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    				}
	    			}else{ //for report by trip
	    				if($agent_ids){
	    					if($agopt_ids){
	    						if(Auth::user()->role==9){
	    							$sale_order =SaleOrder::wherein('operator_id',$agopt_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    						}else{
	    							$sale_order =SaleOrder::wherein('operator_id',$agopt_ids)->wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();	
	    						}
	    						
	    					}else{
	    						$sale_order =SaleOrder::wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();
	    					}

	    				}else{
	    					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();
	    				}
	    			}
	    			
	    		}else{
	    			if($agent_ids){
	    				if($agopt_ids){
	    					$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
	    								->wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id){
    											$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    				}else{
	    					$sale_order = SaleOrder::wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($bus_id){
    											$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    				}
	    				
	    			}else{
	    				$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($bus_id){
    											$query->wherebusoccurance_id($bus_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    			}
	    				
	    		}
			}

			//for All transaction
			else{
				if($from && $to && $departure_time)
				{
					$trip_ids=Trip::wherein('operator_id',$operator_ids)
										->wherefrom($from)
										->whereto($to)
										->wheretime($departure_time)->lists('id');
											
				}
				elseif($from && $to && !$departure_time)
				{
					$trip_ids=Trip::wherein('operator_id',$operator_ids)
											->wherefrom($from)
											->whereto($to)
											->lists('id');
				}
				elseif(!$from && $departure_time)
				{
					$trip_ids=Trip::wherein('operator_id',$operator_ids)
											->wheretime($departure_time)->lists('id');
				}
				else
				{
					$trip_ids=Trip::wherein('operator_id',$operator_ids)
											->lists('id');
				}

				if($trip_ids){
					if($agent_ids){
						if($agopt_ids){
							if(Auth::user()->role==9){
				    			$order_ids=SaleItem::wherein('operator',$agopt_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
							}else{
					    		$order_ids=SaleItem::wherein('operator',$agopt_ids)->wherein('agent_id',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
							}
						}else{
				    		$order_ids=SaleItem::wherein('agent_id',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
						}
					}else{
			    		$order_ids=SaleItem::wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
					}
				}

				if($order_ids){
					$order_ids=SaleOrder::wherein('id',$order_ids)->wherebooking(0)->lists('id');

					/******************************************************************************** 
					*	For agent report by agent group and branches OR don't have branch agent
		    		***/
						$agentgroup_id 			=Input::get('agentgroup');
						if($agentgroup_id=="All")
							$agentgroup_id=0;
						$arr_agent_id			=array();

						if($agentgroup_id && $agent_id)
						{
							$sale_order = SaleOrder::wherein('id',$order_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($agent_id){
												$query->whereagent_id($agent_id);
											})
	    									->get();
						}
						elseif(!$agentgroup_id && $agent_id)
						{
							$sale_order = SaleOrder::wherein('id',$order_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($agent_id){
												$query->whereagent_id($agent_id);
											})
	    									->get();
						}
						elseif($agentgroup_id && !$agent_id)
						{
							$arr_agent_id=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
			    			
			    			$order_ids2=array();
			    			if($arr_agent_id)
			    				$order_ids2=SaleItem::wherein('agent_id',$arr_agent_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
			    			// for unique orderids for all agent branches
			    			$order_id_list=array_intersect($order_ids, $order_ids2);
			    			// dd($order_id_list);
							if($order_id_list)
								$sale_order = SaleOrder::wherein('id',$order_id_list)->with('saleitems')->where('name','!=','')->get();
								/*$sale_item = SaleItem::wherein('order_id', $order_id_list)
											->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
											// ->whereagent_id($agent_id)
											->groupBy('order_id')->orderBy('departure_date','asc')->get();	*/
						}
					/***
					*	End For agent report by agent group and branches OR don't have branch agent
		    		*********************************************************************************/
					
					/******************************************************************* 
					* for Trip report 
					*/
						else{
							$sale_order = SaleOrder::wherein('id',$order_ids)->with('saleitems')->whereHas('saleitems',function($query){
												})
												->where('name','!=','')
		    									->get();	
						}
				}
				
			}
			// return Response::json($sale_order);
	    	$lists = array();
	    	$l=1;
	    	$frist_trip="";
	    	$last_trip="";
	    	if($sale_order){
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
							$total_amount =  $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission);
							$total_amount = $total_amount - $seat_row->discount;
						}
						$lists[$check]['seat_no'] = $seats_no;
						$lists[$check]['local_person'] = $local_person;
						$lists[$check]['foreign_person'] = $foreign_price;
						$lists[$check]['free_ticket'] = $free_ticket;
						$lists[$check]['discount'] = $lists[$check]['discount'] + $seat_row->discount;
						$lists[$check]['sold_seat'] += 1;
			    		$lists[$check]['total_amount'] = $total_amount;
	    			}else{
	    				$list['vr_no'] = $rows->id;
	    				$list['ticket_no'] = $seat_row->ticket_no;
			    		$list['order_date'] = $rows->orderdate;
			    		$list['departure_date'] = $rows->departure_date;
		    			$seats_no = $seat_row->seat_no.", ";
		    			$from_to   = City::whereid($seat_row->from)->pluck('name').' => '.City::whereid($seat_row->to)->pluck('name');
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
						if($seat_row->extra_city_id){
							$list['from_to'] .=' => '.City::whereid($seat_row->extra_city_id)->pluck('name');
						}
						if($agent_ids){
							$list['from_to'] .=' ( '.Operator::whereid($seat_row->operator)->pluck('name').' )';
						}
			    		$list['time'] = $time;
			    		$subtime 		= substr($time, 0, 8);
			    		//To Change AM to PM;
    					$time 			= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
			    		$list['time_unit'] = strtotime($rows->departure_date.' '.substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
			    		$list['classes'] = Classes::whereid($class_id)->pluck('name');
			    		//for group trip and class
			    		$list['from_to_class']=$from_to.'('.$list['classes'].')';
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
	    			}
	    			
	    		}		
	    		$l++;
	    	}	
	    	}
	    	
	    	// SORTING AND GROUP BY TRIP AND BUSCLASS 
		    	// group
		    	$tripandclassgroup = array();
		    	$sorted = $this->msort($lists,array("time_unit"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
		    	
		    	if($agentrp){
		    		foreach ($sorted AS $arr) {
					  $tripandclassgroup[$arr['agent_name']][] = $arr;
					}	
		    	}
		    	else{
		    		foreach ($sorted AS $arr) {
					  $tripandclassgroup[$arr['from_to']][] = $arr;
					}
		    	}
				
		    	// sorting
				ksort($tripandclassgroup);

	    	//return Response::json($tripandclassgroup);
			$search=array();
			$backurl=URL::previous();
			$search['back_url']=$backurl;
			$count=count($tripandclassgroup);
			$i=1;
			foreach ($tripandclassgroup as $key => $value) {
				if($i==1)
					$frist_trip=$key;
				if($i==$count)
					$last_trip=$key;
				$i++;
			}
			$search['from']=$from;
			$search['first_trip']=$frist_trip;
			$search['last_trip']=$last_trip;
			$search['start_date']=$start_date;
			$search['end_date']=$end_date;
			$search['agent_rp']=Input::get('agentrp');

			$search['agent_name']=Agent::whereid(Input::get('a'))->pluck('name');
			$search['agentgroup_name']=AgentGroup::whereid(Input::get('agentgroup'))->pluck('name');
			//return Response::json($search);
			if(Input::get('agentrp'))
	    		return View::make('busreport.agentreportdetail', array('response'=>$tripandclassgroup,'bus_id'=>$bus_id,'search'=>$search));	
	    	else
	    		return View::make('busreport.operatortripticketsolddaily', array('response'=>$tripandclassgroup,'bus_id'=>$bus_id,'search'=>$search));	

	}


	public function AgentBranches($id)
	{
		$response=Agent::whereagentgroup_id($id)->get(array('id','name'));
		return Response::json($response);
	}

	//Trip list by date range agent
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
		$search['agentrp']=$agent_id ? 1 : 0;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;
		return View::make('busreport.agenttripticketsolddaterange', array('response'=>$report_info, 'search'=>$search));
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
		$operator_id=$this->myGlob->operator_id;
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

	public static function getCitiesByoperator12_2($operator_id){
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

    public static function getCitiesByoperatorid($operator_id){
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return $response;
    	}
    	// =========================================
				$orderids 			=SaleOrder::whereoperator_id($operator_id)->lists('id');
		    	$cities=array();
		    	if($orderids){
			    	$trip_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('trip_id')->lists('trip_id');
		    		if($trip_ids){
		    			foreach ($trip_ids as $trip_id) {
		    				$objtrip 		=Trip::whereid($trip_id)->first();
		    				if($objtrip){
		    					$objfromcities				=City::whereid($objtrip->from)->first();
		    					$tempfrom['from']			=$objfromcities->id;
		    					$tempfrom['from_city']		=$objfromcities->name;
		    					$objtocities				=City::whereid($objtrip->to)->first();
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
			// =========================================
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
	 *@/report/dailybydeparturedate
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
			$operator_id=$this->myGlob->operator_id;
			$agopt_ids =$this->myGlob->agopt_ids;
			$search=array();
			if($agopt_ids){
				$responsecities=$this->getCitiesByoperatorIds($agopt_ids);
			}else{
				$responsecities=$this->getCitiesByoperatorIds($operator_ids);
			}
			$search['cities']=$responsecities;
			$responsetime=array();
			if($agopt_ids){
				$responsetime=$this->getTimes($agopt_ids, $from, $to);
			}else{
				$responsetime=$this->getTimes($operator_ids, $from, $to);
			}
			
			$search['times']=$responsetime;
			$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['agent_id']=$agent_id !=null ? $agent_id : 0;
			$search['from']=$from;
			$search['to']=$to;
			$search['time']=$departure_time;
			$search['date']=$this->getDate();

			return View::make('busreport.tripdate.index', array('response'=>$report_info, 'search'=>$search));
		}
		/**
		 * Reports by Car
		 * @/report/dailybydeparturedate/search
		 */
		public function getDailyReportByDepartureDatesearch(){
	    	// $operator_id 		=Input::get('operator_id');
	    	$operator_id 		=$this->myGlob->operator_id;
	    	$agentgroup_id 		=$this->myGlob->agentgroup_id;
	    	$agent_ids 			=$this->myGlob->agent_ids;
	    	$agopt_ids 			=$this->myGlob->agopt_ids;
	    	$operator_ids		=array();

	    	if($agent_ids){
	    		$operator_ids=$agopt_ids;
	    	}else{
	    		$operator_ids[]=$operator_id;
	    	}

	    	$departure_time 	=Input::get('departure_time');	
	    	$departure_date 	=Input::get('departure_date');	
	    	$from 				=Input::get('from');	
	    	$to 				=Input::get('to');	
	    	$time 				=Input::get('departure_time');
	    	$agent_id 			=Input::get('agent_id');
	    	$objbusoccuranceids=null;	
	    	$busids=null;
	    	if(!$time && $from && $to && $departure_date){
	    		$objbusoccuranceids 	=BusOccurance::wherein('operator_id',$operator_ids)->wheredeparture_date($departure_date)->wherefrom($from)->whereto($to)->lists('id');
	    	}elseif(!$from && !$to && !$time && $departure_date){
				$objbusoccuranceids 	=BusOccurance::wherein('operator_id',$operator_ids)->wheredeparture_date($departure_date)->lists('id');
	    	}elseif($time && $from && $to && $departure_date){
	    		$objbusoccuranceids 	=BusOccurance::wherein('operator_id',$operator_ids)->wherefrom($from)->wheredeparture_date($departure_date)->whereto($to)->wheredeparture_time($time)->lists('id');
	    	}else{
	    		$objbusoccuranceids 	=BusOccurance::wherein('operator_id',$operator_ids)->wherefrom($from)->whereto($to)->lists('id');
	    	}


	    	if($objbusoccuranceids !=null){
	    		if($agent_ids){
		    		$busids=SaleItem::wherein('agent_id',$agent_ids)->wherein('busoccurance_id', $objbusoccuranceids)->groupBy('busoccurance_id')->lists('busoccurance_id');
	    		}else{
		    		$busids=SaleItem::wherein('busoccurance_id', $objbusoccuranceids)->groupBy('busoccurance_id')->lists('busoccurance_id');
	    		}
	    	}

	    	// dd($busids);

	    	$response=array();
			if($busids){
				foreach ($busids as $busid) {
					$objbus=BusOccurance::whereid($busid)->first();
					$temp['id']			=$objbus->id;
					$from_city 				=City::whereid($objbus->from)->pluck('name');
					$to_city 				=City::whereid($objbus->to)->pluck('name');
					$temp['trip']		=$from_city.'-'.$to_city;
					$temp['bus_no']		=$objbus->bus_no;
					$temp['class']		=Classes::whereid($objbus->classes)->pluck('name');
					if($agent_ids){
						$temp['class'] .=' ( '.Operator::whereid($objbus->operator_id)->pluck('name')." )";
					}

					$temp['departure_date']		=$objbus->departure_date;
					$temp['departure_time']		=$objbus->departure_time;
					$price 				=$objbus->price;
					if($agent_ids){
						$temp['sold_seats']	=SaleItem::wherein('agent_id',$agent_ids)->wherebusoccurance_id($busid)->count();
						$temp['total_amount']=SaleItem::wherein('agent_id',$agent_ids)->wherebusoccurance_id($busid)->sum('price');
					}else{
						$temp['sold_seats']	=SaleItem::wherebusoccurance_id($busid)->count();
						$temp['total_amount']=SaleItem::wherebusoccurance_id($busid)->sum('price');
					}
					$temp['total_seats']=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();
					// $temp['total_amount']=$price * $temp['sold_seats'];

					$response[]			=$temp;
				}
			}
			// return Response::json($response);
			// $operator_ids=Operator::lists('id');
			// $user_id =Auth::user()->id;
			if($agent_ids)
				$operator_id=null;
			else
				$operator_id=$this->myGlob->operator_id;

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
			$search['date']=$departure_date;

			// return Response::json($response);
			return View::make('busreport.tripdate.index', array('response'=>$response, 'search'=>$search));
	    }

	    // @report/dailybydeparturedate/busid
	    public function getDailyReportbydepartdateandbusid(){
	    	$agentgroup_id =$this->myGlob->agentgroup_id;
	    	$agent_ids     =$this->myGlob->agent_ids;
	    	$busid 			=Input::get('bus_id');
	    	if($agent_ids){
	    		$orderids 		=SaleItem::wherein('agent_id',$agent_ids)->wherebusoccurance_id($busid)->groupBy('order_id')->lists('order_id');
	    	}else{
		    	$orderids 		=SaleItem::wherebusoccurance_id($busid)->groupBy('order_id')->lists('order_id');
	    	}
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
	    			$objsaleitem			=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->first();
	    			$price =0; $extra_city_id=null;	
	    			if($objsaleitem){
	    				$price=$objsaleitem->price;
	    				$extra_city_id=$objsaleitem->extra_city_id;
	    			}

	    			$agent_name				=Agent::whereid($agentid)->pluck('name');	
	    			if($i==0){
	    				$temp['bus_id']			=$busid;
	    				$temp['bus_no']			=$objbusoccurance->bus_no;
	    				$temp['class']			=Classes::whereid($objbusoccurance->classes)->pluck('name');
	    				$temp['departure_date']	=$objbusoccurance->departure_date;
	    				$temp['departure_time']	=$objbusoccurance->departure_time;
	    				$from 					=City::whereid($objbus->from)->pluck('name');
	    				$to 					=City::whereid($objbus->to)->pluck('name');
	    				$temp['trip']			=$from.' => '.$to;
	    				if($extra_city_id){
	    					$temp['trip']       .=' => '.City::whereid($extra_city_id)->pluck('name');
	    				}
	    				$temp['agent_id']		=$agentid;

	    				$temp['agent'] 			=$agent_name != null ? $agent_name : '-';
	    				$temp['sold_tickets']	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
		    			// $price 					=$objbus->price;
		    			$temp['total_amount'] 	=$price * $temp['sold_tickets'];
	    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();
	    				$checkowner=Agent::whereid($agentid)->pluck('owner');
	    				$temp['owner']			=$checkowner;
	    				$response[]=$temp;

	    				if($checkowner==1){
	    					$f_main_gate_total +=$temp['total_amount'];
	    				}else{
	    					$f_agent_gate_total +=$temp['total_amount'];
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
		    				$temp['departure_time']	=$objbusoccurance->departure_time;
		    				$from 					=City::whereid($objbus->from)->pluck('name');
		    				$to 					=City::whereid($objbus->to)->pluck('name');
		    				$temp['trip']			=$from.'-'.$to;
		    				if($extra_city_id){
		    					$temp['trip']       .=' => '.City::whereid($extra_city_id)->pluck('name');
		    				}
		    				$temp['agent_id']		=$agentid;
		    				$temp['agent']			=$agent_name != null ? $agent_name : '-';
		    				$temp['sold_tickets']	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
			    			// $price 					=$objbus->price;
			    			$temp['total_amount'] 	=$price * $temp['sold_tickets'];
		    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();	
		    				$checkowner=Agent::whereid($agentid)->pluck('owner');
		    				$temp['owner']			=$checkowner;
		    				$response[]=$temp;
		    				

		    				if($checkowner==1){
		    					$f_main_gate_total +=$temp['total_amount'];
		    				}else{
		    					$f_agent_gate_total +=$temp['total_amount'];
		    				}
		    			}else{
		    				$sold_tickets	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
			    			// $price 			=$objbus->price;
			    			$total_amount	=$price * $sold_tickets;
		    				
		    				$response[$samekey]['sold_tickets'] +=$sold_tickets;
		    				$response[$samekey]['total_amount'] +=$total_amount;
		    				$checkowner=Agent::whereid($agentid)->pluck('owner');
		    				$response[$samekey]['owner']			=$checkowner;

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

	    // @/report/dailybydeparturedate/detail
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
	    						$temp['trip']=$from.' => '.$to;
	    						if($rows->extra_city_id){
	    							$temp['trip'].=' => '.City::whereid($rows->extra_city_id)->pluck('name');
	    						}
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

	    

	    public function ifExist($key, $arr){
	    	if(is_array($arr)){
	    		$i = 0;
	    		foreach ($arr as $key_row) {
	    			if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date'] && $key_row['bus_id'] == $key['bus_id']){
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

	    



	    public function getSeatOccupancyReport(){
	    	$userid 			=Auth::user()->id;
	    	$operator_id 		=$this->myGlob->operator_id;
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
				$responsetime=$this->getTime($operator_ids, $from, $to);
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


	    // Seatoccupied by bus detail 
	    // @/report/seatoccupiedbybus/detail
	    public function getSeatOccupancydetail(){
	    	$bus_id =Input::get('bus_id');
	    	$objbusoccuranceids[]=(int) $bus_id;
	    	$response =array();
	    	if($objbusoccuranceids){
	    		$objbusoccurance =BusOccurance::whereid($bus_id)->first();
	    		$temp['operator_id']	=$objbusoccurance->operator_id;
	    		$temp['operator_name']	=Operator::whereid($objbusoccurance->operator_id)->pluck('name');
	    		
	    		$order_ids=SaleItem::wherebusoccurance_id($bus_id)->groupBy('order_id')->lists('order_id');
	    		$remark_order_id=SaleOrder::wherein('id',$order_ids)
	    								->with(array('saleitems'=>function($q){
	    									$q->addSelect(array('seat_no', 'order_id'));
	    								}))
	    								->where('remark_type','!=',0)
	    								->get(array('id','remark_type','remark','name'));
	    		
	    		$remarkgroup = array();
				foreach ($remark_order_id AS $arr) {
				  $remarkgroup[$arr['remark_type']][] = $arr->toarray();
				}
				ksort($remarkgroup);
				
	    		// return Response::json($remarkgroup);
	    		
	    		$seattingplan=array();
    			$from_city 	=City::whereid($objbusoccurance->from)->pluck('name');
    			$to_city 	=City::whereid($objbusoccurance->to)->pluck('name');
    			foreach ($objbusoccuranceids as $busid) {
    				$objbus=BusOccurance::whereid($busid)->first();
    				$tmp_seatplan['bus_id']	=$busid;
    				$tmp_seatplan['from']	=$from_city;
    				$tmp_seatplan['to']		=$to_city;
    				$extra_city_id =ExtraDestination::wheretrip_id($objbus->trip_id)->pluck('city_id');
    				if($extra_city_id){
    					$tmp_seatplan['to'] .=' => '.City::whereid($extra_city_id)->pluck('name');
    				}
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
					    		$tmp_seatlist['ticket_no']='-';
					    		$tmp_seatlist['extra_city']=null;
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
					    			$tmp_seatlist['ticket_no'] =$checkbuy->ticket_no;
					    			$customer['id']=null;
					    			$customer['saleitem_id']=$checkbuy->id;
					    			$customer['name']=$checkbuy->name != '' ? $checkbuy->name : '-';
					    			$customer['phone']=$customerphone;
					    			$customer['nrc_no']=$nrc_no;
					    			if($checkbuy->extra_city_id){
					    				$tmp_seatlist['extra_city']=City::whereid($checkbuy->extra_city_id)->pluck('name');
					    			}
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
	    	return View::make('busreport.seatoccupiedbybus.detail', array('response'=>$response, 'remarkgroup'=>$remarkgroup));
	    }

	public function postCustomerInfoUpdate(){
		$saleitem_id=Input::get('saleitem_id');
		$name=Input::get('customer_name');
		$nrc=Input::get('customer_nrc');
		$phone=Input::get('phone');
		$ticket_no=Input::get('ticket_no');
		$obj_saleitem =SaleItem::find($saleitem_id);
		$bus_id=$obj_saleitem->busoccurance_id;
		$obj_saleitem->name=$name;
		$obj_saleitem->nrc_no=$nrc;
		$obj_saleitem->phone=$phone;
		$obj_saleitem->ticket_no=$ticket_no;
		$obj_saleitem->update();
		return Redirect::to('report/seatoccupiedbybus/detail?bus_id='.$bus_id.'&'.$this->myGlob->access_token);
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


}