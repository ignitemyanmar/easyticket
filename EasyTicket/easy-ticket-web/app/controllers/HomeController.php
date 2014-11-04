<?php

class HomeController extends \BaseController {

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

	public function generateAutoID($prefix){

    	$autoid 			= 0;
    	$last_order_id 		= SaleOrder::orderBy('id','desc')->limit('1')->pluck('id');
    	if($last_order_id){
    		$last_order_value 	= (int) substr($last_order_id, count($prefix));
    	}else{
    		return $prefix."0000001";
    	}
    	
    	if($last_order_value >= 0 && $last_order_value <9){
    		$inc_value = ++$last_order_value;
    		$autoid = "000000".$inc_value;
    	}elseif($last_order_value >= 9 && $last_order_value <99){
    		$inc_value = ++$last_order_value;
    		$autoid = "00000".$inc_value;
    	}elseif($last_order_value >= 99 && $last_order_value <999){
    		$inc_value = ++$last_order_value;
    		$autoid = "0000".$inc_value;
    	}elseif($last_order_value >= 999 && $last_order_value <9999){
    		$inc_value = ++$last_order_value;
    		$autoid = "000".$inc_value;
    	}elseif($last_order_value >= 9999 && $last_order_value <99999){
    		$inc_value = ++$last_order_value;
    		$autoid = "00".$inc_value;
    	}elseif($last_order_value >= 99999 && $last_order_value <999999){
    		$inc_value = ++$last_order_value;
    		$autoid = "0".$inc_value;
    	}elseif($last_order_value >= 999999 && $last_order_value <9999999){
    		$inc_value = ++$last_order_value;
    		$autoid = $inc_value;
    	}
    	return $prefix.$autoid;
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=array();
    	$trip=array();
    	$operator_id=Input::get('operator_id');
    	if($operator_id !=''){
    		$objbusoccurance =BusOccurance::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}else{
    		$operator_id=Operator::wherename('elite')->pluck('id');
    		$objbusoccurance =BusOccurance::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}
    	if($objbusoccurance){
    		foreach ($objbusoccurance as $trips) {
    			$temp['from_id']=$trips['from'];
    			$temp['from']=City::whereid($trips['from'])->pluck('name');
    			$temp['to_id']=$trips['to'];
    			$temp['to']=City::whereid($trips['to'])->pluck('name');
    			$trip[]=$temp;
    		}
    	}
    	$response=$trip;	
    	// return Response::json($trip);	
		return View::make('home.triplist',array('response'=>$response));
	}

	public function getTimeList(){
		$operator_id		=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$from_to=Input::get('trip');
		$from_to=explode(',', $from_to);
		// dd($from_to);
		$from_city			=$from_to[0];
		$to_city			=$from_to[1];
		$trip_date 			=Input::get('departure_date');

		if($operator_id && $from_city && $to_city && $trip_date){
			$objtrip=BusOccurance::whereoperator_id($operator_id)->wheredeparture_date($trip_date)->wherefrom($from_city)->whereto($to_city)->get();
		}elseif($operator_id && !$from_city && !$to_city){
			$objtrip=BusOccurance::whereoperator_id($operator_id)->groupBy('departure_time')->get();
		}else{
			$objtrip=BusOccurance::groupBy('departure_time')->get();
		}
		$times=array();
		// return Response::json($objtrip);
		if($objtrip){
			foreach ($objtrip as $row) {
				// dd($trip_id);
				$temp['tripid']				= $row->id;
				$temp['bus_class']			= Classes::whereid($row->classes)->pluck('name');
				$temp['total_seat']			= SeatInfo::whereseat_plan_id($row->seat_plan_id)->wherestatus(1)->count();
				$temp['total_sold_seat']	= SaleItem::wheretrip_id($row->trip_id)->wheredeparture_date($trip_date)->count();
				$temp['time']				= $row->departure_time;
				$times[]					= $temp;
			}
		}
		// return Response::json($times); 
		$tmp_times=$this->msort($times,array("time"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
		$e=0; $m=0;
		$evening=$morning=array();
		foreach($tmp_times as $row){
			if(substr($row['time'],6,2)=="PM"){
				$evening[$e]=$row;
				$e++;
			}else{
				$morning[$m]=$row;
				$m++;
			}
		}
		$response['morning']=$morning;
		$response['evening']=$evening;
		$response['operator_id']=$operator_id;
		$response['from']=$from_city;
		$response['to']=$to_city;
		$response['date']=$trip_date;
		// return Response::json($morning); 

		return View::make('home.departuretimelist',array('response'=>$response));
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function getchoosebusseat(){
		$operator_id 	=Input::get('operator_id');
    	$from 			=Input::get('from_city');
    	$to 			=Input::get('to_city');
    	$date 			=Input::get('date');
    	$time 			=Input::get('time');
    	$bus_no			=Input::get('bus_no');

    	$objbusoccurance =	BusOccurance::wherefrom($from)
    									->whereto($to)
    									->wheredeparture_date($date)
    									->wheredeparture_time($time)
    									->wherebus_no($bus_no)->first();
    	$seat_list=array();
    	$bus_id=null;
    	if($objbusoccurance){
    		$bus_id=$objbusoccurance->id;
    		$busoccurance_id=$objbusoccurance->id;
    		$objseatinfo	=SeatInfo::whereseat_plan_id($objbusoccurance->seat_plan_id)->get();
    		$seat_plan_id 	=$objseatinfo[0]->seat_plan_id;
    		if($objseatinfo){
    			$seats=array();

    			$closeseat=CloseSeatInfo::wheretrip_id($objbusoccurance->trip_id)->whereseat_plan_id($objbusoccurance->seat_plan_id)->pluck('seat_lists');
				$jsoncloseseat=json_decode($closeseat,true);

				$k=0;
    			foreach ($objseatinfo as $seat) {
    				$temp['id']=$seat->id;
    				$checkoccupied_seat =SaleItem::wherebusoccurance_id($bus_id)
    												->whereseat_no($seat->seat_no)
    												->first();
					if($checkoccupied_seat){
						$temp['status']		=2;
						$checkbooking=SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('booking');
						if($checkbooking==1){
							$temp['status']		=3;
						}
					}else{
						$temp['status']		=$seat->status;
					}
    				$temp['seat_no']	=$seat->seat_no;
    				// $temp['status']		=$seat->status;
    				$temp['price']		=$objbusoccurance->price;
    				if($seat->status == 0){
    					$temp['price']	='xxx';
    				}
    				if($jsoncloseseat){
    					$temp['operatorgroup_id']=$jsoncloseseat[$k]['operatorgroup_id'];
    				}else{
    					$temp['operatorgroup_id']=0;
    				}
    				$checksaleitem		=SaleItem::whereseat_no($seat->seat_no)->wherebusoccurance_id($busoccurance_id)->first();
    				$seats[]	=	$temp;
    				$k++;
    			}
    			$seat_list['operator']		=Operator::whereid($operator_id)->pluck('name');
				$seat_list['operator_id']	=$operator_id;
				$seat_list['from']			=City::whereid($from)->pluck('name');
				$seat_list['to']			=City::whereid($to)->pluck('name');
				$seat_list['from_city']		=$from;
				$seat_list['to_city']		=$to;
				$seat_list['departure_date']=$date;
				$seat_list['departure_time']=$time;
				$seat_list['bus_no']		=$bus_no;
				$seat_list['bus_id']		=$bus_id;
				$seattingplan				=SeatingPlan::whereid($seat_plan_id)->first();
				$seat_list['row']			=$seattingplan->row;
				$seat_list['column']		=$seattingplan->column;
				$seat_list['seat_list']		=$seats;
    		}


    	}else{
    		$response['message']	='There is no record.';
    		return Response::json($response);
    	}
		$buslist=array();
    	$operatorgroup=OperatorGroup::whereoperator_id($operator_id)->with(
							array('user' => function($query)
							{
							    $query->addSelect(array('id','name'));
							}))
							->get();
		$agents=Agent::whereoperator_id($operator_id)->get();
		// return Response::json($seat_list);
    	return View::make('bus.chooseseat', array('response'=>$seat_list, 'related_bus'=>$buslist, 'operatorgroup'=>$operatorgroup, 'agents'=>$agents));
	}

	public function postSale(){
		$response=array();
    	$now_date = date("Y-m-d H:i:s");
		$currentDate = strtotime($now_date);
		$futureDate = $currentDate+(60*15);//add 15 minutes for expired_time;
		$expired_date = date("Y-m-d H:i:s", $futureDate);
		$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
    	$date=Input::get('date');
    	$time=Input::get('time');
    	$from_city=Input::get('from_city');
    	$to_city=Input::get('to_city');
    	$seat_liststring=Input::get('seat_list');
    	$booking=Input::get('booking');
    	$agent_id=Input::get('agent_id');

    	if(!$operator_id || !$from_city || !$to_city || !$seat_liststring){
    		$response['message']='Required fields are operator_id, from_city, to_city and seat_lsit';
    		return Response::json($response);
    	}

    	$seat_list=json_decode($seat_liststring);
    	if(count($seat_list)<1){
    		$response['message']='Seat_list format is wrong.';
    		return Response::json($response);
    	}

    	$available_tickets=0;
    	$available_seats=array();
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->where('name','=','')->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}
    	$device_id=substr( md5(rand()), 0, 7);
    	$device_id='*'.Hash::make($device_id);
    	// $device_id=Input::get('device_id') ? 1: '';
    	$canbuy=0;
    	foreach ($seat_list as $rows) {
    		$busoccuranceid=$rows->busoccurance_id;
    		$seat_plan_id=BusOccurance::whereid($busoccuranceid)->pluck('seat_plan_id');
    		$departure_date = Busoccurance::whereid($busoccuranceid)->pluck('departure_date');
    		$objseatinfo=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows->seat_no)->first();
    		$chkstatus=SaleItem::wherebusoccurance_id($busoccuranceid)->whereseat_no($rows->seat_no)->first();
    		$canbuy=true;
    		if($chkstatus){
	    		$canbuy=false;
    		}
    		else{
    			$canbuy=true;
    			$tmp['seat_no']			=$rows->seat_no;
    			$tmp['busoccurance_id']	=$rows->busoccurance_id;
    			$available_seats[]=$tmp;
    		}
	    	$temp['seat_id']=$objseatinfo->id;
	    	$temp['seat_no']=$objseatinfo->seat_no;
    		$temp['can_buy']=$canbuy;
    		$temp['bar_code']=11111111111;
    		$tickets[]=$temp;
    	}
    	// return Response::json($available_seats);

    	$max_order_id=null;
    	$available_orderid=0;
    	if(count($available_seats) == count($seat_list)){
	    	$response['message']="Successfully your purchase or booking tickets.";
    		$can_buy=true;
    			$group_operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('id');
    			$objsaleorder=new SaleOrder();
    			// $order_id=SaleOrder::max('id');
    			// $objsaleorder->id=$order_id+1;
    			$max_order_id = $objsaleorder->id 			= $this->generateAutoID($group_operator_id);
	    		$objsaleorder->orderdate=date('Y-m-d');
	    		$objsaleorder->departure_date 	= $departure_date;
	    		$objsaleorder->operator_id=$operator_id;
	    		$objsaleorder->agent_id=$agent_id ? $agent_id : 0;
	    		$objsaleorder->booking=$booking ? $booking : 0;
	    		$objsaleorder->expired_at=$expired_date;
	    		$objsaleorder->device_id=$device_id;
	    		$objsaleorder->save();
	    		$totalamount=0;
	    		// $max_order_id=SaleOrder::max('id');
	    		// $max_order_id=$objsaleorder->id;
	    		foreach ($available_seats as $rows) {
	    			$check_exiting=SaleItem::wherebusoccurance_id($rows['busoccurance_id'])->whereseat_no($rows['seat_no'])->first();
	    			if($check_exiting){
	    				//already exit skip;
	    			}else{
	    				$available_orderid=$max_order_id;
						$objsaleitems					=new SaleItem();
		    			$objsaleitems->order_id 		=$max_order_id;
		    			$objsaleitems->busoccurance_id 	=$rows['busoccurance_id'];
		    			$busoccurance					=BusOccurance::whereid($rows['busoccurance_id'])->first();
		    			$objsaleitems->seat_no			=$rows['seat_no'];
		    			$objsaleitems->device_id		=$device_id;
		    			$objsaleitems->operator			=$operator_id;
		    			if($busoccurance){
		    				$objsaleitems->trip_id			=$busoccurance->trip_id;
		    				$objsaleitems->price			=$busoccurance->price;
		    				$objsaleitems->foreign_price	=$busoccurance->foreign_price;
		    				$objsaleitems->from				=$busoccurance->from;
		    				$objsaleitems->to				=$busoccurance->to;
		    				$objsaleitems->class_id			=$busoccurance->classes;
		    				$objsaleitems->departure_date	=$busoccurance->departure_date;
		    				$totalamount +=$busoccurance->price;
		    			}		    			
		    			$objsaleitems->save();
	    			}
	    		}
    	}else{
	    	$response['message']="Sorry! Some tickets have been taken by another customer.";
    		$can_buy=false;
    	}



    	$available_device_id=SaleOrder::whereid($available_orderid)->pluck('device_id');
    	$check_orderstatus=SaleItem::whereorder_id($available_orderid)->wheredevice_id($available_device_id)->first();
    	if($check_orderstatus){
    		$response['sale_order_no']=$available_orderid;
	    	$response['device_id']=$available_device_id;
	    	$response['can_buy']=$can_buy;
	    	$response['booking']=$booking;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}else{
    		$response['sale_order_no']=$max_order_id;
	    	$response['device_id']="-";
	    	$response['can_buy']=false;
	    	$response['booking']=0;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}
    	return Response::json($response);
    }

	public function postOrder(){
		$now_date = date("Y-m-d H:i:s");
		$currentDate = strtotime($now_date);
		$futureDate = $currentDate+(60*15);//add 15 minutes for expired_time;
		$expired_date = date("Y-m-d H:i:s", $futureDate);

		$tickets 			=Input::get('tickets');
		$operator_id		=Input::get('operator_id') ? Input::get('operator_id') : 1;
		$busoccurance_id	=Input::get('busoccurance_id');
		$agent_id			=Input::get('agent_id') ? Input::get('agent_id') : 1;
		$from				=Input::get('from');
		$to					=Input::get('to');
		$departure_date		=Input::get('departure_date');
		$departure_time		=Input::get('departure_time');

		$seat_list =array();
		// return Response::json($tickets);
		if($tickets){
			foreach ($tickets as $seat_no) {
				$temp['busoccurance_id']=$busoccurance_id;
				$temp['seat_no']=$seat_no;	
				$seat_list[]=$temp;
			}
		}
    	$available_tickets=0;
    	$available_seats=array();
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->where('nrc_no','=','')->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}
    	foreach ($seat_list as $rows) {
    		$busoccuranceid=$rows['busoccurance_id'];
    		$seat_plan_id=BusOccurance::whereid($busoccuranceid)->pluck('seat_plan_id');
    		$objseatinfo=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows['seat_no'])->first();
    		$chkstatus=SaleItem::wherebusoccurance_id($busoccuranceid)->whereseat_no($rows['seat_no'])->first();
    		$canbuy=true;
    		if($chkstatus){
	    		$canbuy=false;
    		}
    		else{
    			$canbuy=true;
    			$tmp['seat_no']			=$rows['seat_no'];
    			$tmp['busoccurance_id']	=$rows['busoccurance_id'];
    			// $tmp['ticket_no']		=$rows['ticket_no'];
    			$available_seats[]=$tmp;
    		}
	    	$temp['seat_id']=$objseatinfo->id;
	    	$temp['seat_no']=$objseatinfo->seat_no;
    		$temp['can_buy']=$canbuy;
    		$temp['bar_code']=11111111111;
    		$tickets[]=$temp;
    	}
    	$max_order_id=null;

    	if(count($available_seats) == count($seat_list)){
	    	$response['message']="Successfully your purchase or booking tickets.";
    		$can_buy=true;
    			$group_operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('id');
	    		$objsaleorder=new SaleOrder();
	    		$order_id=$objsaleorder->id = $this->generateAutoID($group_operator_id);
	    		// dd($this->generateAutoID($group_operator_id));
	    		$objsaleorder->orderdate=date('Y-m-d');
	    		$objsaleorder->departure_date=$departure_date;
	    		$objsaleorder->agent_id=$agent_id;
	    		$objsaleorder->operator_id=$operator_id;
	    		$objsaleorder->expired_at=$expired_date;
	    		$objsaleorder->save();
	    		$max_order_id=$order_id/*SaleOrder::max('id')*/;
	    		foreach ($available_seats as $rows) {
	    			$objsaleitems=new SaleItem();
	    			$objsaleitems->order_id=$max_order_id;
	    			$objsaleitems->from=$from;
	    			$objsaleitems->to=$to;
	    			$objsaleitems->departure_date=$departure_date;
	    			$objsaleitems->busoccurance_id=$rows['busoccurance_id'];
	    			$objsaleitems->seat_no=$rows['seat_no'];
	    			$objsaleitems->save();
	    		}
    	}else{
	    	$response['message']="Sorry!. Some tickets have been taken by another customer.";
    		$can_buy=false;
    	}
    	$response['sale_order_no']=$max_order_id;
    	$response['can_buy']=$can_buy;
    	$response['tickets']=$tickets;
		return Response::json($tickets);
	}

	public function getcart($id){
		$objorder=SaleOrder::whereid($id)->first();
		$objsaleitems=SaleItem::whereorder_id($id)->get();
		$tickets=array();
		$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$agents=Agent::whereoperator_id($operator_id)->get();
		if($objsaleitems){
			foreach ($objsaleitems as $ticket) {
				$temp['id']				=$ticket['id'];
				$temp['sale_order_no']	=$ticket['order_id'];
				$temp['agent_id']		=$ticket['agent_id'];
				$temp['seat_no']		=$ticket['seat_no'];
				$temp['busoccurance_id']=$ticket['busoccurance_id'];
				$operator_id=$temp['operator_id']	=$ticket['operator'];
				$temp['operator']		=Operator::whereid($ticket['operator'])->pluck('name');
				$objbus 				=BusOccurance::whereid($ticket['busoccurance_id'])->first();
				$temp['price']			=0;
				$temp['foreign_price']	=0;
				$temp['departure_date']	="";
				if($objbus){
					$temp['departure_date']	=$objbus->departure_date;
					$temp['price']			=$objbus->price;
					$temp['foreign_price']	=$objbus->foreign_price;
				}
				$objbusoccurance		=BusOccurance::whereid($ticket['busoccurance_id'])->first();
				$from					=City::whereid($objbusoccurance->from)->pluck('name');
				$to						=City::whereid($objbusoccurance->to)->pluck('name');
				$temp['bus_no']			=$objbusoccurance->bus_no;
				$temp['time']			=$objbusoccurance->departure_time;
				$temp['from_to']		=$from.'-'.$to;
				$tickets[]				=$temp;
			}
		}
		return View::make('bus.cartview', array('response'=> $tickets, 'objorder'=>$objorder, 'agents'=>$agents));
	}

	public function checkout(){
		$agent_id 		=Input::get('agent_id');
		$sale_order_no 	=Input::get('sale_order_no');
		$buyer_name 	=Input::get('buyer_name');
		$address 		=Input::get('address');
		$nationality 	=Input::get('nationality');
		$phone 			=Input::get('phone');
		$nrc_no 		=Input::get('nrc');
		$booking 		=0;
		$cash_credit 	=Input::get('cash_credit');
		$today=date('Y-m-d');
		$oldsale 		=Input::get('oldsale');
		$solddate 		=Input::get('solddate');
		$orderdate      =$today;
		if($oldsale){
			$orderdate       =$solddate ? $solddate : $today;
		}

		$seat_no 		=Input::get('seat_no');
		$busoccurance_id=Input::get('busoccurance_id');
		$ticket_no		=Input::get('ticket_no');
		$totalticket=count($ticket_no);
		$foc=array();
		for($i=1; $i<=$totalticket; $i++){
			$foc[] 			=Input::get('foc'.$i) ? Input::get('foc'.$i) : 0;
		}
    		$objsaleorder=SaleOrder::find($sale_order_no);
    		if(!$objsaleorder){return Redirect::to('/');}
    		$objsaleorder->orderdate=$orderdate;
    		$objsaleorder->agent_id=$agent_id;
    		$objsaleorder->name=$buyer_name;
    		$objsaleorder->nrc_no=$nrc_no;
    		$objsaleorder->phone=$phone;
    		$objsaleorder->nationality=$nationality;
    		$objsaleorder->booking=$booking;
    		$objsaleorder->cash_credit=$cash_credit;
    		
    		$i=0;
    		$totalamount=0;
    		$busoccuranceid=0;
    		$commission=0;
    		foreach ($seat_no as $seatno) {
    			$objsaleitems=SaleItem::whereorder_id($sale_order_no)->whereseat_no($seatno)->wherebusoccurance_id($busoccurance_id[$i])->first();
    			if($i==0){
    				$busoccuranceid=$busoccurance_id[$i];
    				$objagent_commission=AgentCommission::whereagent_id($agent_id)->wheretrip_id($objsaleitems->trip_id)->first();
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
    				$commission=$commission * count($seat_no);

    				if($commission==0){
    					$tripcommission=Trip::whereid($objsaleitems->trip_id)->pluck('commission');
    					$commission= $tripcommission * count($seat_no);
    				}
    			}

    			$objsaleitems->order_id 		=$sale_order_no;
    			$objsaleitems->agent_id 		=$agent_id;
    			$objsaleitems->busoccurance_id 	=$busoccurance_id[$i];
    			$objsaleitems->seat_no			=$seatno;
    			$objsaleitems->name				=$buyer_name;
    			$objsaleitems->nrc_no			=$nrc_no;
    			$objsaleitems->ticket_no		=$ticket_no[$i];
    			$objsaleitems->free_ticket		=$foc[$i];
    			$objsaleitems->update();
    			if($nationality=='local'){
    				$totalamount +=$objsaleitems->price;
    			}else{
    				$totalamount +=$objsaleitems->foreign_price;	
    			}
    			$i++;
    		}
    		$objsaleorder->departure_date=BusOccurance::whereid($busoccuranceid)->pluck('departure_date');
    		$objsaleorder->total_amount=$totalamount;
    		$objsaleorder->agent_commission=$commission;
    		$objsaleorder->update();

    		//Payment Transaction
    		if($booking == 0){
    			$total_amount 				= $objsaleorder->total_amount - $objsaleorder->agent_commission;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$today 										= date("Y-m-d");
	    		$objdepositpayment_trans->pay_date			= $today;
	    		$objdepositpayment_trans->payment 			= 0;
	    		$agentdeposit 				= AgentDeposit::whereagent_id($objsaleorder->agent_id)->whereoperator_id($objsaleorder->operator_id)->orderBy('id','desc')->first();
	    		if($agentdeposit){
	    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
	    			$objdepositpayment_trans->balance 		= $agentdeposit->balance - $total_amount;
	    		}else{
	    			$objdepositpayment_trans->deposit 		= 0;
	    			$objdepositpayment_trans->balance 		= 0 - $total_amount;
	    		}  		
	    		$objdepositpayment_trans->debit 			= 0;
	    		$objdepositpayment_trans->save();

	    		$saleOrder = SaleOrder::whereid($objsaleorder->id)->first();
	    		$saleOrder->cash_credit = 1;
	    		$saleOrder->update();
    		}

    	$message="Success.";
    	return Redirect::to('/')->with('message',$message);
	}

	public function deleteSaleOrder($id){
    	if(!$id){
    		$response['message']='sale_order_no is null.';
    		return Response::json($response);
    	}
    	$order_id=SaleItem::whereid($id)->pluck('order_id');
    	// SaleOrder::whereid($order_id)->delete();
    	SaleItem::whereid($id)->delete();
    	$tickets=SaleItem::whereorder_id($order_id)->count();
    	if($tickets==0){
    		SaleOrder::whereid($order_id)->delete();
    	}
    	$response= "Have been deleted.";
    	return Response::json($response);
    }

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
	public function destroy($id)
	{
		//
	}

}