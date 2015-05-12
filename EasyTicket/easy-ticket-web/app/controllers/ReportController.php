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
			if(is_array($operator_id))
				$objtrip=Trip::wherein('operator_id',$operator_id)->wherefrom($from_city)->whereto($to_city)->groupBy('time')->get();
			else
				$objtrip=Trip::whereoperator_id($operator_id)->wherefrom($from_city)->whereto($to_city)->groupBy('time')->get();
		}elseif($operator_id && !$from_city && !$to_city){
			if(is_array($operator_id))
				$objtrip=Trip::wherein('operator_id',$operator_id)->groupBy('time')->get();
			else
				$objtrip=Trip::whereoperator_id($operator_id)->groupBy('time')->get();
		}else{
			$objtrip=Trip::groupBy('time')->get();
		}
		$times=array();
		if($objtrip){
			foreach ($objtrip as $row) {
				$temp['tripid']=$row->id;
				$temp['time']=$row->time;
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
    	
    	// return Response::json($agentgroup_id.'--'.$operator_id);

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
								->selectRaw('order_id, count(*) as sold_seat, trip_id, agent_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket , SUM(discount) as discount')
								->groupBy('order_id')->orderBy('departure_date','asc')->get();
		$lists = array();
		foreach ($sale_item as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$trip = Trip::whereid($rows->trip_id)->first();
			if($trip){
				$list['id'] = $rows->trip_id;
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
		//return Response::json($sorted_stack);
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
    	$departure_date =Input::get('departure_date');
    	Session::put('search_daily_date',$date);

    	$trip_id  		=Input::get('trip_id');

		$search=array();
		$search['trip']="";
    	$search['date']=$date;
    	if($agent_ids){
    		if($agopt_ids){
    			if(Auth::user()->role==9){
    				$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
									->with('saleitems')->whereHas('saleitems',function($query) use($trip_id,$departure_date){
											if($trip_id){
												$query->wheretrip_id($trip_id)->where('departure_date','=',$departure_date);
											}
									})->where('orderdate','=',$date)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();
    			}else{
    				$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
    								->wherein('agent_id',$agent_ids)
									->with('saleitems')->whereHas('saleitems',function($query) use($trip_id,$departure_date){
											if($trip_id){
												$query->wheretrip_id($trip_id)->where('departure_date','=',$departure_date);
											}
									})->where('orderdate','=',$date)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();
    			}
    			
    		}else{
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
    		
    	}else{
    		$sale_order = SaleOrder::with('saleitems')
									->whereHas('saleitems',function($query) use($trip_id,$departure_date){
											if($trip_id){
												$query->wheretrip_id($trip_id)->where('departure_date','=',$departure_date);
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
    	// return Response::json($tripandclassgroup);
    	return View::make('busreport.daily.detail', array('response'=>$tripandclassgroup,'trip_id'=>$trip_id,'search'=>$search));	
    }
   
	// Changes by Agent Code
	// Agent report by order date
	// @/report/operator/trip/daterangesbycode
	public function getTripslistreportOperatorCode()
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

		$agent_status 			= $agent_id	= Input::get('agent_id') ? Input::get('agent_id') : 0;
		$trips  				= Input::get('trips'); //for agent report or not
		$search=array();
		$search['agent_rp']		= $agent_id ? 1 : 0;
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
		    			$order_ids=SaleItem::wherein('operator',$agopt_ids)->wherein('agent_code',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
    				}
    			}else{
	    			$order_ids=SaleItem::wherein('agent_code',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
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
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_code, operator')
									->whereagent_code($agent_id)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
				}
				elseif(!$agentgroup_id && $agent_id)
				{
					$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_code, operator')
									->whereagent_code($agent_id)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
				}
				elseif($agentgroup_id && !$agent_id)
				{
					$arr_agent_id=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
	    			
	    			$order_ids2=array();
	    			if($arr_agent_id)
	    				$order_ids2=SaleItem::wherein('agent_code',$arr_agent_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
	    			// for unique orderids for all agent branches
	    			$order_id_list=array_intersect($order_ids, $order_ids2);
	    			// dd($order_id_list);
					if($order_id_list)
						$sale_item = SaleItem::wherein('order_id', $order_id_list)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_code, operator')
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
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_code, operator')
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
			$agent_name=Agent::wherecode_no($rows->agent_code)->pluck('name');
			$list['agent_id']=$rows->agent_code ? $rows->agent_code : 0;
			$list['agent_name']=$agent_name ? $agent_name : "-";
			
			if($trip){
				$list['id'] = $rows->trip_id;
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
				
				$owner = Agent::wherecode_no($rows->agent_code)->pluck('owner');
				if($owner == 0){
		    		$commission =$this->calculatecommission($rows->trip_id, $rows->agent_code, $order_date);
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
		return View::make('busreport.agentreportbycode', array('response'=>$tripandorderdategroup, 'search'=>$search));
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
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
									->whereagent_id($agent_id)
									->groupBy('order_id')->orderBy('departure_date','asc')->get();	
				}
				elseif(!$agentgroup_id && $agent_id)
				{
					$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
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
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, SUM(free_ticket) as free_ticket,SUM(discount) as discount, agent_id, operator')
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

	// Changes by Agent Code
	// Trip report by order date detail
	// Agent report by order date detail
	// '@/triplist/{date}/daily'
	public function getTripsSellingReportbyDailyCode($date)
	{
		$trip_id=Input::get('trip_id');
		$order_date=explode(',', $date);
		$from=Input::get('f');
		$to=Input::get('t');
		$departure_time=Input::get('time');
		$agent_id=Input::get('a');
		$agentrp=Input::get('agentrp');
		$start_date="";
		$end_date="";
		if($trip_id){
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
		
			//for only one transaction
	    	if($trip_id){
	    		//for choose agent
	    		if($agent_id){
	    			if($agentrp){ //for agent report
	    				if($agent_ids){
	    					if($agopt_ids){
	    						if(Auth::user()->role==9){
	    							$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_code($agent_id);
    									})
    									->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    						}else{
	    							$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)->wherein('agent_code',$agent_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_code($agent_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();	
	    						}
	    						
	    					}else{
	    						$sale_order = SaleOrder::wherein('agent_code',$agent_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_code($agent_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    					}
	    						
	    				}else{
	    					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_code($agent_id);
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
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    						}else{
	    							$sale_order =SaleOrder::wherein('operator_id',$agopt_ids)->wherein('agent_code',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();	
	    						}
	    						
	    					}else{
	    						$sale_order =SaleOrder::wherein('agent_code',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();
	    					}

	    				}else{
	    					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date);
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
	    								->wherein('agent_code',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id,$date){
    											$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    				}else{
	    					$sale_order = SaleOrder::wherein('agent_code',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id,$date){
    											$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    				}
	    				
	    			}else{
	    				$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($trip_id,$date){
    											$query->wheretrip_id($trip_id)->wheredeparture_date($date);
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
					    		$order_ids=SaleItem::wherein('operator',$agopt_ids)->wherein('agent_code',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
							}
						}else{
				    		$order_ids=SaleItem::wherein('agent_code',$agent_ids)->wherein('trip_id',$trip_ids)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
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
												$query->whereagent_code($agent_id);
											})
	    									->get();
						}
						elseif(!$agentgroup_id && $agent_id)
						{
							$sale_order = SaleOrder::wherein('id',$order_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($agent_id){
												$query->whereagent_code($agent_id);
											})
	    									->get();
						}
						elseif($agentgroup_id && !$agent_id)
						{
							$arr_agent_id=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
			    			
			    			$order_ids2=array();
			    			if($arr_agent_id)
			    				$order_ids2=SaleItem::wherein('agent_code',$arr_agent_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
			    			// for unique orderids for all agent branches
			    			$order_id_list=array_intersect($order_ids, $order_ids2);
			    			// dd($order_id_list);
							if($order_id_list)
								$sale_order = SaleOrder::wherein('id',$order_id_list)->with('saleitems')->where('name','!=','')->get();
								/*$sale_item = SaleItem::wherein('order_id', $order_id_list)
											->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
											// ->whereagent_code($agent_id)
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
		    			$owner = Agent::wherecode_no($rows->agent_code)->pluck('owner');
		    			if($owner == 0){
		    				$commission =$this->calculatecommission($seat_row->trip_id, $rows->agent_code, $rows->orderdate);
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
			    		$list['agent_name'] = Agent::wherecode_no($rows->agent_code)->pluck('name');
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

			$search['agent_name']=Agent::wherecode_no(Input::get('a'))->pluck('name');
			$search['agentgroup_name']=AgentGroup::whereid(Input::get('agentgroup'))->pluck('name');
			//return Response::json($search);
			if(Input::get('agentrp'))
	    		return View::make('busreport.agentreportdetail', array('response'=>$tripandclassgroup,'trip_id'=>$trip_id,'search'=>$search));	
	    	else
	    		return View::make('busreport.operatortripticketsolddaily', array('response'=>$tripandclassgroup,'trip_id'=>$trip_id,'search'=>$search));	

	}

	// Trip report by order date detail
	// Agent report by order date detail
	// '@/triplist/{date}/daily'
	public function getTripsSellingReportbyDaily($date)
	{
		$trip_id=Input::get('trip_id');
		$order_date=explode(',', $date);
		$from=Input::get('f');
		$to=Input::get('t');
		$departure_time=Input::get('time');
		$agent_id=Input::get('a');
		$agentrp=Input::get('agentrp');
		$start_date="";
		$end_date="";
		if($trip_id){
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
		
			//for only one transaction
	    	if($trip_id){
	    		//for choose agent
	    		if($agent_id){
	    			if($agentrp){ //for agent report
	    				if($agent_ids){
	    					if($agopt_ids){
	    						if(Auth::user()->role==9){
	    							$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_id($agent_id);
    									})
    									->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    						}else{
	    							$sale_order = SaleOrder::wherein('operator_id',$agopt_ids)->wherein('agent_id',$agent_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_id($agent_id);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();	
	    						}
	    						
	    					}else{
	    						$sale_order = SaleOrder::wherein('agent_id',$agent_ids)->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_id($agent_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    					}
	    						
	    				}else{
	    					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date)->whereagent_id($agent_id);
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
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    						}else{
	    							$sale_order =SaleOrder::wherein('operator_id',$agopt_ids)->wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();	
	    						}
	    						
	    					}else{
	    						$sale_order =SaleOrder::wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();
	    					}

	    				}else{
	    					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($trip_id, $date , $agent_id){
    										$query->wheretrip_id($trip_id)->wheredeparture_date($date);
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
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id,$date){
    											$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->wherebooking(0)
    									->get();
	    				}else{
	    					$sale_order = SaleOrder::wherein('agent_id',$agent_ids)
	    								->with('saleitems')->whereHas('saleitems',function($query) use ($trip_id,$date){
    											$query->wheretrip_id($trip_id)->wheredeparture_date($date);
    									})->where('departure_date','=',$date)
    									->where('operator_id','=',$operator_id)
    									->wherebooking(0)
    									->get();	
	    				}
	    				
	    			}else{
	    				$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($trip_id,$date){
    											$query->wheretrip_id($trip_id)->wheredeparture_date($date);
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
		    	// Calculate Agent Total	
					foreach ($tripandclassgroup as $key => $value) {
						$total_amount = 0;
						foreach ($value as $key2 => $value2) {
							$total_amount += $value2['total_amount'];
						}
						foreach ($value as $key2 => $value2) {
							$tripandclassgroup[$key][$key2]['subtotal_amount'] = $total_amount;
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
	    		return View::make('busreport.agentreportdetail', array('response'=>$tripandclassgroup,'trip_id'=>$trip_id,'search'=>$search));	
	    	else
	    		return View::make('busreport.operatortripticketsolddaily', array('response'=>$tripandclassgroup,'trip_id'=>$trip_id,'search'=>$search));	

	}

	public function Pagination($page,$lastpage)
	{
		$lpm1 = $lastpage - 1;	
		$prevs= $page - 1;							
		$nexts = $page + 1;	
		$adjacents = 3;
		$pagination = "";
		$url = URL::full();
		$pattern     = '/__PgNo__=[^&]+(.*)/';
		$replacement = "$1";
		$url = preg_replace($pattern, $replacement, $url);

		if($lastpage > 1)
		{	
			//$pagination .= "<div class='pagination'>";
			if ($page > 1) 
				$pagination.= "<li><a href='$url&__PgNo__=$prevs'>«</a></li>";
			else
				$pagination.= "<li class='disabled'><a href >«</a></li>";	
		
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<li class='active'><a href=''>$counter</a></li>";
					else
						$pagination.= "<li><a href='$url&__PgNo__=$counter'>$counter</a></li>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))
			{
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class='active'><a href=''>$counter</a></li>";
						else
							$pagination.= "<li><a href='$url&__PgNo__=$counter'>$counter</a></li>";					
					}
					$pagination.= "<li><a href=''>...</a></li>";
					$pagination.= "<li><a href='$url&__PgNo__=$lpm1'>$lpm1</a></li>";
					$pagination.= "<li><a href='$url&__PgNo__=$lastpage'>$lastpage</a></li>";		
				}
				
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a href='$url&__PgNo__=1'>1</a></li>";
					$pagination.= "<li><a href='$url&__PgNo__=2'>2</a></li>";
					$pagination.= "<li><a href=''>...</a><li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li class='active'><a href=''>$counter</a></li>";
						else
							$pagination.= "<li><a href='$url&__PgNo__=$counter'>$counter</a></li>";					
					}
					$pagination.= "<li><a href=''>...</a><li>";
					$pagination.= "<li><a href='$url&__PgNo__=$lpm1'>$lpm1</a></li>";
					$pagination.= "<li><a href='$url&__PgNo__=$lastpage'>$lastpage</a></li>";		
				}
				else
				{
					$pagination.= "<li><a href='$url&__PgNo__=1'>1</a></li>";
					$pagination.= "<li><a href='$url&__PgNo__=2'>2</a></li>";
					$pagination.= "<li><a href=''>...</a></li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li  class='active'><a href=''>$counter</a></li>";
						else
							$pagination.= "<li><a href='$url&__PgNo__=$counter'>$counter</a></li>";					
					}
				}
			}
		
			if ($page < $counter - 1) 
				$pagination.= "<li><a href='$url&__PgNo__=$nexts'>» </a></li>";
			else
				$pagination.= "<li class='disabled'><a href=''>» </a></li>";
			//$pagination.= "</div>\n";		
		}
		return $pagination; 
	}
	//****************** Developer SMK ******************
	// Grouping Report by Trip
	// '@/report/bytrip'
	public function getReportbyTrip(){
		$print 					=Input::get('print');
		$operator_id 			=Input::get('operator_id');
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date') ? Input::get('start_date') : date('Y-m-', strtotime($this->getDate())).'01';
		$end_date  				=Input::get('end_date') ? Input::get('end_date') : date('Y-m-t', strtotime($this->getDate()));
		$start_date				=date('Y-m-d', strtotime($start_date));
		$end_date				=date('Y-m-d', strtotime($end_date));
		$departure_time  		=Input::get('departure_time');
		$departure_time			=str_replace('-', ' ', $departure_time);

		if($print != 1){
			$offset					= Input::get('__PgNo__') ? Input::get('__PgNo__') : 1;
			$limit 					= Input::get('limit') ? Input::get('limit') : 10;
			$start 					= ($offset-1) * $limit;
		}else{
			$start = 0;
			$limit 	= 999999;
		}
		

		$sale_order = array();
		if($from != 'all' && $to != 'all'){
			$total_records = TripsReport:: whereoperator_id($operator_id)->wherefrom($from)->whereto($to)
										->where('departure_date','>=',$start_date)
										->where('departure_date','<=',$end_date)
										->where('time','LIKE',$departure_time.'%')
										->orderBy('booking_expired','asc')->count();
			$sale_order = TripsReport:: whereoperator_id($operator_id)->wherefrom($from)->whereto($to)
										->where('departure_date','>=',$start_date)
										->where('departure_date','<=',$end_date)
										->where('time','LIKE',$departure_time.'%')
										->orderBy('booking_expired','asc')
										->offset($start)->limit($limit)
										->get();
		}else{
			$total_records = TripsReport:: whereoperator_id($operator_id)
										->where('departure_date','>=',$start_date)
										->where('departure_date','<=',$end_date)
										->where('time','LIKE',$departure_time.'%')
										->orderBy('booking_expired','asc')->count();
			$sale_order = TripsReport:: whereoperator_id($operator_id)
										->where('departure_date','>=',$start_date)
										->where('departure_date','<=',$end_date)
										->where('time','LIKE',$departure_time.'%')
										->orderBy('booking_expired','asc')
										->offset($start)->limit($limit)
										->get();
		}

		$lists = array();
		foreach ($sale_order as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$commission=0;
			$percent_total=0;
			$list['id'] = $rows->trip_id;
			$list['order_date'] = $rows->order_date;
			$list['departure_date'] = $rows->departure_date;
			$list['from_id'] = $rows->from;
			$list['to_id'] = $rows->to;
			$list['from_to'] = $rows->from_city.' => '.$rows->to_city;
			if($rows->extra_city_id){
				$list['from_to'] .=' => '.City::whereid($extra_city_id)->pluck('name');
			}
						
			$list['time'] = $rows->time;
    		//To Change AM to PM;
    		$time 			= $rows->time;
			$subtime 		= substr($time, 0, 8);
			$time 			= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
    		$list['time_unit'] = strtotime($rows->departure_date.' '.substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
			$list['class_id'] = $rows->class_id;
			$list['class_name'] = $rows->class_name;
			$list['from_to_class']=$list['from_to']. "(".$list['class_name'].")";
			$list['local_price'] = $rows->price;
			$list['foreign_price'] = $rows->foreign_price;
			$list['sold_seat'] = $rows->ticket_qty;
			$list['free_ticket'] = $rows->free_ticket_total;
			$list['discount'] = $rows->discount_amount;
			$list['total_amount'] = $rows->price_subtotal + (($rows->foreign_price_subtotal - $rows->price_subtotal) > 0 ? ($rows->foreign_price_subtotal - $rows->price_subtotal) : 0 ) ;
			$list['commission_amount'] = $rows->commission_subtotal;
			$lists[] = $list;
		}

	// Calculate Pagination
		$lastpage = ceil($total_records/$limit);
		$paginater = $this->Pagination($offset,$lastpage);


    	$cities=array();
    	$cities=$this->getCitiesByoperatorId($operator_id);
		$search['cities']=$cities;
		$times=array();
		$times=$this->getTime($operator_id, $from, $to);
		$search['times']=$times;
		$search['operator_id']=$operator_id;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;
		$search['start_date']=$start_date;
		$search['end_date']=$end_date;
		$search['paginater']=$paginater;


		// sorting result
		$response=$this->msort($lists,array("time_unit"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
		// To print
		if($print == true){
			$rows = array();
			$row['from_to'] = "ခရီးစဥ္";
			$row['departure_date'] = "ထြက္ခြာမည့္ေန ့ရက္";
			$row['time'] = "အခ်ိန္";
			$row['class_name'] = "ကားအမ်ိဴးအစား";
			$row['sold_seat'] = "ခုံအေရ အတြက္";
			$row['free_ticket'] = "အခမဲ႕ လက္မွတ္ ";
			$row['discount'] = "Discount ";
			$row['local_price'] = "ေစ်းႏုန္း";
			$row['total_amount'] = "စုုစုုေပါင္း";
			$row['commission_amount'] = "% ႏုတ္ျပီး စုုစုုေပါင္း";				
			$rows[]  = $row;
			foreach ($lists as $key => $value) {
				$row['from_to'] = $value['from_to'];
				$row['departure_date'] = date('d/m/Y',strtotime($value['departure_date']));
				$row['time'] = $value['time'];
				$row['class_name'] = $value['class_name'];
				$row['sold_seat'] = $value['sold_seat'];
				$row['free_ticket'] = $value['free_ticket'];
				$row['discount'] = $value['discount'];
				$row['local_price'] = $value['local_price'];
				$row['total_amount'] = $value['total_amount'];
				$row['commission_amount'] = $value['total_amount'] - $value['commission_amount'];				
				$rows[]  = $row;
			  
			}
			// generate file (constructor parameters are optional)
			$xls = new Excel('UTF-8', false, 'Workflow Management');
			$xls->addArray($rows);
			$xls->generateXML($start_date.'-'.$end_date.'-Reports');
		  	exit;
		}
		$tripandorderdategroup = array();
		foreach ($response AS $arr) {
		  $tripandorderdategroup[$arr['from_to_class']][] = $arr;
		}
		ksort($tripandorderdategroup);
		//return Response::json($tripandorderdategroup);
		return View::make('busreport.trip_report.trip_report', array('response'=>$tripandorderdategroup, 'search'=>$search));
	}

	// Trip report by order date detail
	// Agent report by order date detail
	// '@/report/{date}/tripdetail'
	public function getReportbyTripDetail($date){
		$print 		= Input::get('print');
		$date 		= explode(',', $date);
		$start_date = $date[0];
		$end_date 	= count($date) > 1 ? $date[1] : $date[0];
		$operator_id= Input::get('operator_id');
		$from 		= Input::get('f');
		$to 		= Input::get('t');
		$time 		= Input::get('time');

		if($print != true){
			$offset					= Input::get('__PgNo__') ? Input::get('__PgNo__') : 1;
			$limit 					= Input::get('limit') ? Input::get('limit') : 50;
			$offset 				= ($offset-1) * $limit;
		}else{
			$offset = 0;
			$limit 	= 999999;
		}

		if($from != 'all' && $to != 'all'){
			$total_records = SaleOrderReport::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->where('time','Like',$time.'%')
											->where('departure_date','>=',$start_date)
											->where('departure_date','<=',$end_date)
											->orderBy('booking_expired', 'asc')
											->count();
			$sale_order = SaleOrderReport::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->where('time','Like',$time.'%')
											->where('departure_date','>=',$start_date)
											->where('departure_date','<=',$end_date)
											->orderBy('booking_expired', 'asc')
											->offset($offset)->limit($limit)
											->get();
		}else{
			$total_records = SaleOrderReport::whereoperator_id($operator_id)
											->where('departure_date','>=',$start_date)
											->where('departure_date','<=',$end_date)
											->where('time','Like',$time.'%')
											->orderBy('booking_expired', 'asc')
											->count();
			$sale_order = SaleOrderReport::whereoperator_id($operator_id)
											->where('departure_date','>=',$start_date)
											->where('departure_date','<=',$end_date)
											->where('time','Like',$time.'%')
											->orderBy('booking_expired', 'asc')
											->offset($offset)->limit($limit)
											->get();
		}

	    $lists = array();
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

	    		
				$list['vr_no'] = $rows->id;
				$list['ticket_no'] = $rows->ticket_no;
	    		$list['order_date'] = $rows->orderdate;
	    		$list['departure_date'] = $rows->departure_date;
    			$seats_no = $rows->seat_no.", ";
    			$from_to   = $rows->from_city.' => '.$rows->to_city;
    			$time = $rows->time;
    			$price = $rows->price;
    			$free_ticket = $rows->free_ticket;
    			$foreign_price = $rows->foreign_price;
    			$class_id = $rows->class_id;
    			$owner = Agent::whereid($rows->agent_id)->pluck('owner');
    			if($owner == 0){
    				$commission =$this->calculatecommission($rows->trip_id, $rows->agent_id, $rows->orderdate);
    			}else{
    				$commission = 0;
    			}

	    		if($rows->nationality == 'local'){
					$local_person = 1;
					$total_amount = $rows->free_ticket > 0 ? 0 : $rows->price - $commission;
					$total_amount = $total_amount - $rows->discount;
				}else{
					$foreign_person = 1;
					$total_amount = $rows->free_ticket > 0 ? 0 : $rows->foreign_price - $commission;
					$total_amount = $total_amount - $rows->discount;
				}


				$list['from_to'] = $from_to;
				if($rows->extra_city_id){
					$list['from_to'] .=' => '.City::whereid($rows->extra_city_id)->pluck('name');
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
	    		$list['buyer_name'] = $rows->name;
	    		$list['commission'] = $commission;
	    		$list['seat_no'] = substr($seats_no, 0, -2);
	    		$list['sold_seat'] = 1;
	    		$list['local_person'] = $local_person;
				$list['foreign_person'] = $foreign_price;
	    		$list['price'] = $price;
	    		$list['foreign_price'] = $foreign_price;
	    		$list['free_ticket'] = $free_ticket;
	    		$list['discount'] = $rows->discount;
	    		$list['total_amount'] = $total_amount;
	    		$lists[] = $list;
	    			
	    	}	
	    }

    	// SORTING AND GROUP BY TRIP AND BUSCLASS 
	    	// group
	    	$tripandclassgroup = array();
	    	$sorted = $this->msort($lists,array("time_unit"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);

	    	// To print
	    	if($print == true){
				$rows = array();
				$row['ticket_no'] = "လက္မွတ္ No";
               	$row['seat_no'] = "ခုံနံပါတ္ ";
               	$row['from_to'] = "ခရီးစဥ္";
               	$row['departure_date'] = "ထြက္ခြာ မည့္ ေန့ရက္ / အခ်ိန္";
               	$row['classes'] = "ကားအမ်ုိဳး အစား";
               	$row['agent_name'] = "အေရာင္း ကုိယ္စားလွယ္";
               	$row['buyer_name'] = "ဝယ္သူ";
               	$row['order_date'] = "ဝယ္ယူ သည့္ေန႔";
               	$row['sold_seat'] = "ခုံအေရ အတြက္";
               	$row['free_ticket'] = "အခမဲ႕ လက္မွတ္ ";
               	$row['discount'] = "Discount ";
               	$row['price'] = "ေစ်းႏုန္း";
               	$row['price_1'] = "ရွင္းႏႈန္း";
               	$row['total_amount'] = "စုုစုုေပါင္း";		
				$rows[]  = $row;
				foreach ($lists as $key => $result) {
					$row['ticket_no'] = $result['ticket_no'];
					$row['seat_no'] = $result['seat_no'];
					$row['from_to'] = $result['from_to'];
					$row['departure_date'] = date('d/m/Y',strtotime($result['departure_date'])) ."(".$result['time'].")";
					$row['classes'] = $result['classes'];
					$row['agent_name'] = $result['agent_name'];
					$row['buyer_name'] = $result['buyer_name'];
					$row['order_date'] = date('d/m/Y',strtotime($result['order_date']));
					$row['sold_seat'] = $result['sold_seat'];
					$row['free_ticket'] = $result['free_ticket'];
					$row['discount'] = $result['discount'];
					if($result['foreign_person'] > 0)
						$row['price'] = $result['foreign_price'];
					else
						$row['price'] = $result['price'];
					if($result['foreign_person'] > 0){
						$price_1 = $result['foreign_person']-$result['commission'];
						$row['price_1'] =$price_1 ." (".$result['commission'].")";
					}
					else{
						$price_1 = $result['price']-$result['commission'];
						$row['price_1'] = $price_1 ." (".$result['commission'].")";
					}
					$row['total_amount'] = $result['total_amount'];				
					$rows[]  = $row;
				}
				// generate file (constructor parameters are optional)
				$xls = new Excel('UTF-8', false, 'Workflow Management');
				$xls->addArray($rows);
				$xls->generateXML($start_date.'-'.$end_date.'-Reports');
			  	exit;
			}
	    	
	    	foreach ($sorted AS $arr) {
				$tripandclassgroup[$arr['from_to']][] = $arr;
			}
	    	// sorting
			ksort($tripandclassgroup);

		// Calculate Pagination
			$lastpage = ceil($total_records/$limit);
			$paginater = $this->Pagination($offset,$lastpage);

	    	//return Response::json($tripandclassgroup);
			$search=array();
			$backurl=URL::previous();
			$search['from'] = $from;
			$search['back_url']=$backurl;
			$search['start_date']=$start_date;
			$search['end_date']=$end_date;
			$search['paginater']=$paginater;

			return View::make('busreport.trip_report.trip_detail_report', array('response'=>$tripandclassgroup,'search'=>$search));	

	}

	public function AgentBranches($id)
	{
		if($id == 'All')
			$response=Agent::get(array('id','name','code_no'));
		else
			$response=Agent::whereagentgroup_id($id)->get(array('id','name','code_no'));
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

    public static function getCitiesByoperatorIds($operator_id){
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return $response;
    	}
    	$orderids 			=SaleOrder::wherein('operator_id',$operator_id)->lists('id');
    	$cities=array();
    	if(is_array($operator_id))
	    	$trip_ids 	=SaleItem::wherein('operator', $operator_id)->groupBy('trip_id')->lists('trip_id');
	    else
	    	$trip_ids 	=SaleItem::whereoperator($operator_id)->groupBy('trip_id')->lists('trip_id');
	    
    	if($trip_ids){
			foreach ($trip_ids as $id) {
				$trip 		=Trip::whereid($id)->first();
				if($trip){
					$objfromcities				=City::whereid($trip->from)->first();
					$tempfrom['from']			=$objfromcities->id;
					$tempfrom['from_city']		=$objfromcities->name;
					$objtocities				=City::whereid($trip->to)->first();
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
		}else{
    		$response['message']='There is no records.';
    		return $response;
    	}

    	return $cities;
    }
    

    /** 
	 *Daily and daily advance sale reports
	 *@/report/dailybydeparturedate
	 */
	 public function getDailyReportByDepartureDate(){
			$operator_id  			=Input::get('operator_id');
			$agent_id  				=Input::get('agent_id');
			$from  					=Input::get('from');
			$to  					=Input::get('to');
			$start_date  			=Input::get('start_date');
			$end_date  				=Input::get('end_date');
			$departure_time  		=Input::get('departure_time');
			$departure_time			=str_replace('-', ' ', $departure_time);
			$report_info = array();
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
	    	$trip_ids=null;	
	    	
	    	if(!$time && $from && $to){
	    		$trip_ids 	=Trip::wherein('operator_id',$operator_ids)->wherefrom($from)->whereto($to)->lists('id');
	    	}elseif($time && $from && $to){
	    		$trip_ids 	=Trip::wherein('operator_id',$operator_ids)->wherefrom($from)->whereto($to)->wheretime($time)->lists('id');
	    	}else{
	    		$trip_ids 	=Trip::wherein('operator_id',$operator_ids)->lists('id');
	    	}

	    	/**
	    	 * Filter by Departure Date;
	    	 */
	    	if($trip_ids !=null){
	    		if($agent_ids){
		    		$trip_ids=SaleItem::wherein('agent_id',$agent_ids)->wherein('trip_id', $trip_ids)->where('departure_date','LIKE',$departure_date.'%')->groupBy('trip_id')->lists('trip_id');
	    		}else{
		    		$trip_ids=SaleItem::wherein('trip_id', $trip_ids)->groupBy('trip_id')->where('departure_date','LIKE',$departure_date.'%')->lists('trip_id');
	    		}
	    	}

	    	// dd($busids);

	    	$response=array();
			if($trip_ids){
				foreach ($trip_ids as $id) {
					$trip=Trip::whereid($id)->first();
					$temp['id']			=$id;
					$from_city 			=City::whereid($trip->from)->pluck('name');
					$to_city 			=City::whereid($trip->to)->pluck('name');
					$temp['trip']		=$from_city.'-'.$to_city;
					$temp['class']		=Classes::whereid($trip->class_id)->pluck('name');
					if($agent_ids){
						$temp['class'] .=' ( '.Operator::whereid($trip->operator_id)->pluck('name')." )";
					}

					$temp['departure_date']		=$departure_date;
					$temp['departure_time']		=$trip->time;
					$price 						=$trip->price;
					$temp['price']				=$price;
					$temp['foreign_price'] 		=$trip->foreign_price;
					if($agent_ids){
						$temp['sold_seats']	=SaleItem::wherein('agent_id',$agent_ids)->wheretrip_id($id)->where('departure_date','LIKE',$departure_date.'%')->count();
						$temp['total_amount']=SaleItem::wherein('agent_id',$agent_ids)->wheretrip_id($id)->where('departure_date','LIKE',$departure_date.'%')->sum('price');
					}else{
						$temp['sold_seats']	=SaleItem::wheretrip_id($id)->where('departure_date','LIKE',$departure_date.'%')->count();
						$temp['total_amount']=SaleItem::wheretrip_id($id)->where('departure_date','LIKE',$departure_date.'%')->sum('price');
					}

					$temp['total_seats']=SeatInfo::whereseat_plan_id($trip->seat_plan_id)->where('status','!=',0)->count();
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
	    	$trip_id 			=Input::get('trip_id');
	    	$departure_date 	=Input::get('departure_date');
	    	if($agent_ids){
	    		$orderids 		=SaleItem::wherein('agent_id',$agent_ids)->where('name','!=','')->wheretrip_id($trip_id)->wheredeparture_date($departure_date)->groupBy('order_id')->lists('order_id');
	    	}else{
		    	$orderids 		=SaleItem::where('name','!=','')->wheretrip_id($trip_id)->wheredeparture_date($departure_date)->groupBy('order_id')->lists('order_id');
	    	}
	    	$objtrip 	 		=Trip::whereid($trip_id)->first();
	    	$objorderagent=null;
	    	
	    	$objagentids= $response=array();
	    	if($orderids){
	    		$objagentids =SaleOrder::wherein('id', $orderids)->where('name','!=','')->get(array('departure_date','agent_id','id'));
	    	}

	    	$i=0;
	    	$main_gate_total=0;
	    	$f_main_gate_total=0;
	    	$agent_gate_total=0;
	    	$f_agent_gate_total=0;
	    	$grand_total=0;
	    	if($objagentids){
	    		$response = array();
	    		foreach ($objagentids as $row) {
	    			$objsaleitem			=SaleItem::whereorder_id($row->id)->first();
	    			$price =0; $extra_city_id =null;	
	    			if($objsaleitem){
	    				$price=$objsaleitem->price;
	    				$extra_city_id=$objsaleitem->extra_city_id;
	    			}
	    			$checkowner=Agent::whereid($row->agent_id)->pluck('owner');
	    			$agent_name				=Agent::whereid($row->agent_id)->pluck('name');
	    			$check_existed = $this->isExistedAgentId($response, $row->agent_id);
	    			if($check_existed == -1){
	    				$temp['id']				=$trip_id;
	    				$temp['class']			=Classes::whereid($objtrip->class_id)->pluck('name');
	    				$temp['departure_time']	=$objtrip->time;
	    				$temp['departure_date']	=$departure_date;
	    				$from 					=City::whereid($objtrip->from)->pluck('name');
	    				$to 					=City::whereid($objtrip->to)->pluck('name');
	    				$temp['trip']			=$from.'-'.$to;
	    				if($extra_city_id){
	    					$temp['trip']       .=' => '.City::whereid($extra_city_id)->pluck('name');
	    				}
	    				$temp['agent_id']		=$row->agent_id;
	    				$temp['agent']			=$agent_name != null ? $agent_name : '-';
	    				$temp['price'] 			=$objtrip->price;
	    				$temp['sold_tickets']	=SaleItem::whereorder_id($row->id)->count();
	    				$temp['free_ticket']	=SaleItem::whereorder_id($row->id)->sum('free_ticket');
		    			$temp['discount'] 		=SaleItem::whereorder_id($row->id)->sum('discount');
	    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objtrip->seat_plan_id)->where('status','!=',0)->count();	
	    				
	    				$temp['owner']			=$checkowner;
	    				if($checkowner == 1){
	    					$temp['commission']   = 0;  
	    					$temp['total_amount'] = ((($price * $temp['sold_tickets']) - ($price * $temp['free_ticket'])) - $temp['discount']);
		    				$f_main_gate_total += $temp['total_amount'];
	    				}else{
	    					$agent_commission = AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
	    					if($agent_commission){
	    						$temp['commission']   = $agent_commission->commission;  
	    						$temp['total_amount'] = ((($price * $temp['sold_tickets']) - ($agent_commission->commission * ($temp['sold_tickets'] - $temp['free_ticket']))) - $temp['discount']);
	    					}
		    				else{
		    					$temp['commission']   = $objtrip->commission;
		    					$temp['total_amount'] = ((($price * $temp['sold_tickets']) - ($objtrip->commission * ($temp['sold_tickets'] - $temp['free_ticket']))) - $temp['discount']);
		    				}
		    				$f_agent_gate_total +=$temp['total_amount'];
	    				}
	    				array_push($response, $temp);
	    			}else{
	    				$discount = SaleItem::whereorder_id($row->id)->sum('discount');
	    				$free_ticket = SaleItem::whereorder_id($row->id)->sum('free_ticket');
	    				$sold_tickets	=SaleItem::whereorder_id($row->id)->count();
	    				$agent_commission = AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
    						    				
	    				if($checkowner == 1){
	    					$total_amount = ((($price * $sold_tickets) - ($price * $free_ticket)) - $discount);
		    				$f_main_gate_total += $total_amount;
	    				}else{
	    					$agent_commission = AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
	    					if($agent_commission){
	    						$total_amount = ((($price * $sold_tickets) - ($agent_commission->commission * ($sold_tickets - $free_ticket))) - $discount);
	    					}
		    				else{
		    					$total_amount = ((($price * $sold_tickets) - ($objtrip->commission * ($sold_tickets - $free_ticket))) - $discount);
		    				}
		    				$f_agent_gate_total += $total_amount;
	    				}
	    				$response[$check_existed]['free_ticket'] +=$free_ticket;
	    				$response[$check_existed]['sold_tickets'] +=$sold_tickets;
	    				$response[$check_existed]['total_amount'] +=$total_amount;
	    				$response[$check_existed]['discount'] +=$discount;
	    				
	    			}
	
	    		}	
	    	}
	    	$total_sold['main_gate_total']	=$f_main_gate_total;
	    	$total_sold['agent_gate_total']	=$f_agent_gate_total;
	    	$total_sold['grand_total']		=$f_main_gate_total + $f_agent_gate_total;
	    	// return Response::json($response);
	    	return View::make('busreport.tripdate.filterbybusid', array('response'=>$response,'total_sold'=>$total_sold));
	    }

	    public function isExistedAgentId($arr, $agent_id){
	    	if(is_array($arr)){
	    		foreach ($arr as $key => $value) {
	    			if($value['agent_id'] == $agent_id){
	    				return $key;
	    			}
	    		}
	    	}
	    	return -1;
	    }

	    // @/report/dailybydeparturedate/detail
	    public function getDailyReportbydepartdatedetail(){
	    	$agent_id 		=Input::get('agent_id');
	    	$trip_id 		=Input::get('trip_id');
	    	$departure_date =Input::get('departure_date');
	    	$trip=Trip::whereid($trip_id)->first();
	    	$objorderids=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->groupBy('order_id')->lists('order_id');
	    	$response=array();
	    	if($objorderids){
	    		foreach ($objorderids as $key => $id) {
	    			$objorderinfo=SaleOrder::whereid($id)->whereagent_id($agent_id)->first();
	    			if($objorderinfo){
	    				$objsaleitems=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->whereorder_id($id)->get();
	    				if($objsaleitems){
	    					foreach ($objsaleitems as $rows) {
	    						$temp['trip_id']	=$trip->id;
	    						$from 				=City::whereid($trip->from)->pluck('name');
	    						$to 				=City::whereid($trip->to)->pluck('name');
	    						$temp['trip']=$from.' => '.$to;
	    						if($rows->extra_city_id){
	    							$temp['trip'].=' => '.City::whereid($rows->extra_city_id)->pluck('name');
	    						}
	    						$temp['class'] 				=Classes::whereid($trip->class_id)->pluck('name');
	    						$temp['departure_date'] 	=$departure_date;
	    						$temp['departure_time'] 	=$trip->time;
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
	    			if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date']){
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
	    	$trip_id =Input::get('trip_id');
	    	$departure_date = Input::get('departure_date');
	    	$trip_ids[]= $trip_id;
	    	$response =array();
	    	if($trip_id){
	    		$trip =Trip::whereid($trip_id)->first();
	    		$temp['operator_id']	=$trip->operator_id;
	    		$temp['operator_name']	=Operator::whereid($trip->operator_id)->pluck('name');
	    		
	    		$order_ids=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->groupBy('order_id')->lists('order_id');
	    		$remark_order_id=SaleOrder::wherein('id',$order_ids)
	    								->with(array('saleitems'=>function($q){
	    									$q->addSelect(array('seat_no', 'order_id'));
	    								}))
	    								->wheredeparture_date($departure_date)
	    								->where('remark_type','!=',0)
	    								->get(array('id','remark_type','remark','name'));
	    		
	    		$remarkgroup = array();
				foreach ($remark_order_id AS $arr) {
				  $remarkgroup[$arr['remark_type']][] = $arr->toarray();
				}
				ksort($remarkgroup);
				
	    		// return Response::json($remarkgroup);
	    		
	    		$seattingplan=array();
    			$from_city 	=City::whereid($trip->from)->pluck('name');
    			$to_city 	=City::whereid($trip->to)->pluck('name');
    			foreach ($trip_ids as $id) {
    				$objtrip=Trip::whereid($id)->first();
    				$tmp_seatplan['id']		=$id;
    				$tmp_seatplan['from']	=$from_city;
    				$tmp_seatplan['to']		=$to_city;
    				$extra_city_id =ExtraDestination::wheretrip_id($objtrip->id)->pluck('city_id');
    				if($extra_city_id){
    					$tmp_seatplan['to'] .=' => '.City::whereid($extra_city_id)->pluck('name');
    				}
		    		$tmp_seatplan['bus_no']=$objtrip->bus_no;
		    		$objseatplan=SeatingPlan::whereid($objtrip->seat_plan_id)->first();
		    		$tmp_seatplan['row']=$objseatplan->row;
		    		$tmp_seatplan['column']=$objseatplan->column;
		    		$tmp_seatplan['classes']=$objtrip->class_id;
		    		$tmp_seatplan['class']=Classes::whereid($objtrip->class_id)->pluck('name');
		    		$tmp_seatplan['date']=$departure_date;
		    		$tmp_seatplan['time']=$objtrip->time;
		    		$tmp_seatplan['total_seats']=SeatInfo::whereseat_plan_id($objtrip->seat_plan_id)->where('status','!=',0)->count('id');
		    		$tmp_seatplan['total_sold_seats']=SaleItem::wheretrip_id($objtrip->id)->wheredeparture_date($departure_date)->count('id');
		    		$tmp_seatplan['total_amount']=$objtrip->price * $tmp_seatplan['total_sold_seats'];

		    		$seatlist=array();
		    			$objseatinfo=SeatInfo::whereseat_plan_id($objtrip->seat_plan_id)->get();
		    			if($objseatinfo){
		    				foreach ($objseatinfo as $seats) {
		    					$tmp_seatlist['id']=$seats['id'];
		    					$tmp_seatlist['seat_no']=$seats['seat_no'];
					    		$tmp_seatlist['price']=$objtrip->price;
		    					if($seats['status']==0){
		    						$tmp_seatlist['seat_no']='xxx';
					    			$tmp_seatlist['price']='xxx';
		    					}
					    		
					    		$tmp_seatlist['status']=$seats['status'];
					    		$checkbuy	=SaleItem::wheretrip_id($objtrip->id)->wheredeparture_date($departure_date)->whereseat_no($seats['seat_no'])->first();

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