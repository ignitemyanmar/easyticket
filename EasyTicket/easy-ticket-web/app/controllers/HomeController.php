<?php

class HomeController extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	// For Administrator Account to choose operator
	public function chooseoperator(){
		$response=Operator::get();
		// return Response::json($operators);
		return View::make('home.operatorlist', array('response'=>$response));
	}


	public function index()
	{
		$response=array();
    	$trip=array();
    	$operator_id=$this->myGlob->operator_id;
    	if($operator_id=='all' || !$operator_id){
    		return Redirect::to('alloperator?'.$this->myGlob->access_token);
    	}
    	if($operator_id !=''){
    		$objtrip =Trip::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}else{
    		$operator_id=$this->myGlob->operator_id;
    		$objtrip =Trip::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}
    	if($objtrip){
    		foreach ($objtrip as $trips) {
    			$temp['from_id']=$trips['from'];
    			$temp['from']=City::whereid($trips['from'])->pluck('name');
    			$temp['to_id']=$trips['to'];
    			$temp['to']=City::whereid($trips['to'])->pluck('name');
    			$trip[]=$temp;
    		}
    	}
    	$response=array();
    	$go=array();
    	$return=array();
    	if($trip){
    		foreach($trip as $row)
	    	{
	    		if($row['from']=="Yangon"){
	    			$go[]=$row;
	    		}else{
	    			$return[]=$row;
	    		}
	    	}
    	}
    	$response['go']=$go;
    	$response['return']=$return;
    	// return Response::json($response);
		return View::make('home.triplist',array('response'=>$response));
	}

	public function getTimeList(){
		$operator_id		=$this->myGlob->operator_id;
		
		$from_to=Input::get('trip');
		$from_to=explode(',', $from_to);
		$from_city			=$from_to[0];
		$to_city			=$from_to[1];
		$trip_date 			=Input::get('departure_date');


		if($operator_id && $from_city && $to_city && $trip_date){
			$objtrip=Trip::whereoperator_id($operator_id)
						// ->wheredeparture_date($trip_date)
						->wherefrom($from_city)
						->whereto($to_city)
						->orderBy('time','asc')
						->orderBy('class_id','asc')
						->wherestatus(0)
						->get();
		}elseif($operator_id && !$from_city && !$to_city){
			$objtrip=Trip::whereoperator_id($operator_id)->wherestatus(0)
							->groupBy('time')->get();
		}else{
			$objtrip=Trip::groupBy('time')->wherestatus(0)->get();
		}

		$times=array();
		if($objtrip){
			foreach ($objtrip as $row) {
				$temp['tripid']				= $row->id;
				$temp['class_id']			= $row->class_id;
				$temp['bus_class']			= Classes::whereid($row->class_id)->pluck('name');
				
				$close_seatinfo = CloseSeatInfo::wheretrip_id($row->id)
													->where('start_date','<=',$trip_date)
														->where('end_date','>=',$trip_date)->first();
				if($close_seatinfo)
					$temp['total_seat']	= SeatInfo::whereseat_plan_id($close_seatinfo->seat_plan_id)->wherestatus(1)->count();
				else
					$temp['total_seat']			= SeatInfo::whereseat_plan_id($row->seat_plan_id)->wherestatus(1)->count();
				// dd($trip_date);
				
					$temp['total_sold_seat']	= SaleItem::wheretrip_id($row->id)->wheredeparture_date($trip_date)->count();
				$temp['time']				= $row->time;
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
		
		$eveningtrip = array();
		foreach ($evening AS $arr) {
		  $eveningtrip[$arr['bus_class']][] = $arr;
		}
		ksort($eveningtrip);

		$morningtrip = array();
		foreach ($morning AS $arr) {
		  $morningtrip[$arr['bus_class']][] = $arr;
		}
		ksort($morningtrip);

		$response['morning']=$morningtrip;
		$response['evening']=$eveningtrip;
		$response['operator_id']=$operator_id;
		$response['from']=$from_city;
		$response['to']=$to_city;
		$response['date']=$trip_date;
		
		// return Response::json($response); 
		return View::make('home.departuretimelist',array('response'=>$response));
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */

	public function getchoosebusseat(){
		$now_date = $this->getDateTime();

    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->where('name','=','')->where('booking','=',0)->lists('id');
		if($expired_ids){
			SaleOrder::wherein('id',$expired_ids)->delete();
    	}
    	
    	
		$operator_id 	=Input::get('operator_id');
    	$from 			=Input::get('from_city');
    	$to 			=Input::get('to_city');
    	$date 			=Input::get('date');
    	$time 			=Input::get('time');
    	$class_id		=Input::get('class_id');
    	$bus_no			=Input::get('bus_no');

    	$objtrip  		=Trip::wherefrom($from)->whereto($to)->wheretime($time)->whereclass_id($class_id)->first();
    	
  		$seat_list=array();
    	if($objtrip){
    		$order_ids=SaleItem::wheretrip_id($objtrip->id)->wheredeparture_date($date)->groupBy('order_id')->lists('order_id');
	    		$remark_order_id=array();
	    		if($order_ids){
	    			$remark_order_id=SaleOrder::wherein('id',$order_ids)
	    								->with(array('saleitems'=>function($q){
	    									$q->addSelect(array('seat_no', 'order_id'));
	    								}))
	    								->where('remark_type','!=',0)
	    								->get(array('id','remark_type','remark','name'));	
	    		}

	    		$remarkgroup = array();
	    		if($remark_order_id){
					foreach ($remark_order_id AS $arr) {
					  $remarkgroup[$arr['remark_type']][] = $arr->toarray();
					}
				}
				ksort($remarkgroup);
			$remark_seats=array();
			if($remark_order_id){
				foreach ($remark_order_id as $rmordergroup) {
					if(count($rmordergroup->saleitems)>0){
						foreach ($rmordergroup->saleitems as $tickets) {
							$remark_seats[]=$tickets->seat_no;
						}
					}	
				}
			}
			/*// 
			if($objseat){
				$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
				$seatplan['row']  			 =$objseat->row;
				$seatplan['column']  		 =$objseat->column;
			}else{
				$seatplan['seat_layout_id']='-';
				$seatplan['row']='-';
				$seatplan['column']='-';
			}
			// */

			$closeseat=CloseSeatInfo::wheretrip_id($objtrip->id)
    										->whereseat_plan_id($objtrip->seat_plan_id)
    										->where('start_date','<=',$date)
    										->where('end_date','>=',$date)
    										->pluck('seat_lists');
    										
    		$objcloseseat=CloseSeatInfo::wheretrip_id($objtrip->id)
    										->where('start_date','<=',$date)
    										->where('end_date','>=',$date)
    										->first();
    		if($objcloseseat){
    			$objseatinfo	=SeatInfo::whereseat_plan_id($objcloseseat->seat_plan_id)->get();
    		}else{
    			$objseatinfo	=SeatInfo::whereseat_plan_id($objtrip->seat_plan_id)->get();
    		}
    		$seat_plan_id 	=$objseatinfo[0]->seat_plan_id;
    		
    		if($objseatinfo){
    			$seats=array();

    			/*$closeseat=CloseSeatInfo::wheretrip_id($objtrip->id)
    										->whereseat_plan_id($objtrip->seat_plan_id)
    										->where('start_date','<=',$date)
    										->where('end_date','>=',$date)
    										->pluck('seat_lists');*/
				$jsoncloseseat=json_decode($closeseat,true);

				$k=0;$extra_city_id=null;$extra_city_price=0;
    			foreach ($objseatinfo as $seat) {
    				$temp['id']=$seat->id;
    				$checkoccupied_seat =SaleItem::wheretrip_id($objtrip->id)->wheredeparture_date($date)
    												->whereseat_no($seat->seat_no)
    												->first();
    				$customer=array();
					if($checkoccupied_seat){
						$temp['status']		=2;
						$objorder=SaleOrder::whereid($checkoccupied_seat->order_id)->first();
						if($objorder->booking==1){
							$temp['status']		=3;
						}
						$agentname='-';
						if($objorder->agent_id !=0){
							$agentname=Agent::whereid($objorder->agent_id)->pluck('name');
						}

						$customer=SaleItem::whereorder_id($checkoccupied_seat->order_id)->whereseat_no($seat->seat_no)->first(array('name','phone','nrc_no','ticket_no','extra_city_id','extra_destination_id'));
						if($customer->extra_city_id){
							$extra_city_id=$customer->extra_city_id;
							$customerinfo['extra_city']=City::whereid($customer->extra_city_id)->pluck('name');
						}else{
							$customerinfo['extra_city']=null;
						}
						$customerinfo['name']	=$customer->name ? $customer->name : $objorder->name;
						$customerinfo['phone']	=$objorder->phone;
						$customerinfo['nrc']	=$customer->nrc_no ? $customer->nrc_no : $objorder->nrc_no;
						$customerinfo['ticket_no']	=$customer->ticket_no ? $customer->ticket_no : "-";
						$temp['customer']	=$customerinfo;
						$temp['agent']		=$agentname;
					}else{
						$temp['status']		=$seat->status;
						$temp['customer']=$customer;
						$temp['agent']		='-';

					}
    				$temp['seat_no']	=$seat->seat_no;
    				$temp['price']		=$objtrip->price;
    				if($seat->status == 0){
    					$temp['price']	='xxx';
    				}
    				if($jsoncloseseat){
    					$temp['operatorgroup_id']=$jsoncloseseat[$k]['operatorgroup_id'];
    				}else{
    					$temp['operatorgroup_id']=0;
    				}
    				$checksaleitem		=SaleItem::whereseat_no($seat->seat_no)->wheretrip_id($objtrip->id)->first();
    				$seats[]	=	$temp;
    				$k++;
    			}
    			$seat_list['operator']		=Operator::whereid($operator_id)->pluck('name');
				$seat_list['operator_id']	=$operator_id;
				$seat_list['from']			=City::whereid($from)->pluck('name');
				$seat_list['to']			=City::whereid($to)->pluck('name');
				$seat_list['from_to']		=$seat_list['from'].' => '. $seat_list['to'];
				$seat_list['extra_city']    =null;
				if($extra_city_id){
					$seat_list['extra_city']=City::whereid($extra_city_id)->pluck('name');
				}
				$seat_list['from_city']		=$from;
				$seat_list['to_city']		=$to;
				$seat_list['departure_date']=$date;
				$seat_list['departure_time']=$time;
				$seat_list['bus_no']		=$bus_no;
				$seat_list['trip_id']		=$objtrip->id;
				$seat_list['class_id']		=$objtrip->class_id;
				$seattingplan				=SeatingPlan::whereid($seat_plan_id)->first();
				$seat_list['row']			=$seattingplan->row;
				$seat_list['column']		=$seattingplan->column;
				$seat_list['seat_list']		=$seats;
    		}


    	}else{
    		return Redirect::to('/404');
    	}
		$buslist=array();
    	$operatorgroup=OperatorGroup::whereoperator_id($operator_id)->with(
							array('user' => function($query)
							{
							    $query->addSelect(array('id','name'));
							}))
							->get();

		
		$agopt_ids = $this->myGlob->agopt_ids;
		$agentgroup_id = $this->myGlob->agentgroup_id;
		if($agopt_ids){
			if(Auth::user()->role==9 || Auth::user()->role==3){
				if($agentgroup_id){
					$agents=Agent::whereagentgroup_id($agentgroup_id)->get();
				}else{
					$agents=Agent::get();
				}
			}else{
				$agents=Agent::wherein('operator_id',$agopt_ids)->get();
			}
		}else{
			$agents=Agent::whereoperator_id($operator_id)->get();
		}

		// return Response::json($remarkgroup);
    	return View::make('bus.chooseseat', array('response'=>$seat_list, 'related_bus'=>$buslist, 'operatorgroup'=>$operatorgroup, 'agents'=>$agents,'remarkgroup'=>$remarkgroup,'remark_seats'=>$remark_seats));
	}

	public function postSale(){
		$response=array();
    	$now_date = $this->getDateTime();
		$currentDate = strtotime($now_date);
		$futureDate = $currentDate+(60*15);//add 15 minutes for expired_time;
		$expired_date = date("Y-m-d H:i:s", $futureDate);
		$operator_id=$this->myGlob->operator_id;
    	$trip_id=Input::get('trip_id');
    	$date=Input::get('date');
    	$time=Input::get('time');
    	$from_city=Input::get('from_city');
    	$to_city=Input::get('to_city');
    	$seat_liststring=Input::get('seat_list');
    	$booking=Input::get('booking');
    	$agent_id=Input::get('agent_id');
    	$customer_name=Input::get('customer_name') ? Input::get('customer_name') : "";
    	$phone_no=Input::get('phone_no') ? Input::get('phone_no') : "";

    	$seat_list=json_decode($seat_liststring);
    	if(count($seat_list)<1){
    		$response['message']='Seat_list format is wrong.';
    		return Response::json($response);
    	}
    	/***
    	 * Checking for Encryption
    	 */
    	foreach ($seat_list as $seat) {
    		$seat_no 		= MCrypt::decrypt($seat->seat_no);
    		$seat_plan_id	= Trip::whereid($trip_id)->pluck('seat_plan_id');
    		if($seat_plan_id){
    			$objseatinfo= SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($seat_no)->get();
    			if(!$objseatinfo){
    				$response['message']='Invalid Seat No.';
    				return Response::json($response);
    			}
    		}else{
    			$response['message']='Invalid Bus Id.';
    			return Response::json($response);
    		}
    	}

    	$available_tickets=0;
    	$available_seats=array();
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)
    							->where('name','=','')
    							->where('booking','=',0)->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}
    	$device_id=substr( md5(rand()), 0, 7);
    	$device_id='*'.Hash::make($device_id);
    	$booking_expired ='';
    	$canbuy=0;
    	
    	$objtrip        =Trip::whereid($trip_id)->first();
    	foreach ($seat_list as $rows) {
    		$seat_plan_id   =$objtrip->seat_plan_id;
    		$departure_date =$date;
    		$departure_time =$objtrip->time;
    		/*
    		 * Calculate Departure Datetime;
    		 */
    			$datetime = $departure_date." ".substr($departure_time, 0, 8);
    			$strdate  = strtotime($datetime);
    			$strdate  = $strdate - (60*10);
    			$booking_expired = date("Y-m-d H:i:s", $strdate);

    		$objseatinfo=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no(MCrypt::decrypt($rows->seat_no))->first();
    		$chkstatus=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($date)
    							->whereseat_no(MCrypt::decrypt($rows->seat_no))->first();
    		$canbuy=true;
    		if($chkstatus){
	    		$canbuy=false;
    		}
    		else{
    			$tmp['seat_no']			= MCrypt::decrypt($rows->seat_no);
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
    	if(count($available_seats) == count($seat_list) && $canbuy){
    		try {
    			$response['message']="Successfully your purchase or booking tickets.";
    		    $can_buy=true;
    			$group_operator_id=$this->myGlob->operatorgroup_id;
    			$operator_id=$this->myGlob->operator_id;
    			$objsaleorder=new SaleOrder();
    			$max_order_id =$objsaleorder->id= $this->generateAutoID($operator_id,$group_operator_id);
	    		$objsaleorder->orderdate 		=$this->Date;
	    		$objsaleorder->departure_date 	= $departure_date;
	    		$objsaleorder->booking_expired 	= $booking_expired;
	    		$objsaleorder->name 		 	= $customer_name;
	    		$objsaleorder->phone 	 		= $phone_no;
	    		$objsaleorder->operator_id 		=$operator_id;
	    		$objsaleorder->agent_id 		=$agent_id ? $agent_id : 0;
	    		$objsaleorder->agent_code 		= 0;
	    		$objsaleorder->booking 			=$booking ? $booking : 0;
	    		$objsaleorder->expired_at 		=$expired_date;
	    		$objsaleorder->device_id 		=$device_id;
	    		$objsaleorder->user_id 			=Auth::user()->id;
	    		$objsaleorder->save();
	    		$totalamount=0;
	    		foreach ($available_seats as $rows) {
	    			$check_exiting=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($date)
	    									->whereseat_no($rows['seat_no'])->first();
	    			if($check_exiting){
	    				$response['message']="Sorry! Some tickets have been taken by another customer.";
    					$can_buy=false;
	    			}else{
	    				$available_orderid=$max_order_id;
						$objsaleitems					=new SaleItem();
		    			$objsaleitems->order_id 		=$max_order_id;
		    			// $busoccurance					=BusOccurance::whereid($rows['busoccurance_id'])->first();
		    			// $objtrip						=BusOccurance::whereid($rows['busoccurance_id'])->first();
		    			$objsaleitems->seat_no			=$rows['seat_no'];
		    			$objsaleitems->device_id		=$device_id;
		    			$objsaleitems->name				=$customer_name;
		    			$objsaleitems->phone			=$phone_no;
		    			$objsaleitems->operator			=$operator_id;
		    			$objsaleitems->agent_id 		= $agent_id ? $agent_id : 0;
		    			$objsaleitems->agent_code 		= 0;
		    			if($objtrip){
		    				$objsaleitems->trip_id			=$objtrip->id;
		    				$objsaleitems->price			=$objtrip->price;
		    				$objsaleitems->foreign_price	=$objtrip->foreign_price;
		    				$objsaleitems->from				=$objtrip->from;
		    				$objsaleitems->to				=$objtrip->to;
		    				$objsaleitems->class_id			=$objtrip->class_id;
		    				$objsaleitems->departure_date	=$date;
		    				$totalamount 					+=$objtrip->price;
		    			}		    			
		    			$objsaleitems->save();
	    			}
	    		}
    		} catch (Exception $e) {
    			$response['message']="Sorry! Something was worng.".$e;
    			$response['can_buy']= false;
    			$response['sale_order_no'] = $available_orderid;
	    		return Response::json($response);
    		}
	    	    
    	}else{
	    	$response['message']="Sorry! Some tickets have been taken by another customer.";
    		$can_buy=false;
    	}

    	$available_device_id=SaleOrder::whereid($available_orderid)->pluck('device_id');
    	$saleitem_count=SaleItem::whereorder_id($available_orderid)->wheredevice_id($available_device_id)->count();
    	if(count($available_seats) == $saleitem_count){
    		$response['sale_order_no']= MCrypt::encrypt($available_orderid);
	    	$response['device_id']=$available_device_id;
	    	$response['can_buy']=$can_buy;
	    	$response['booking']=$booking;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}else{
    		$sale_order = SaleOrder::whereid($available_orderid)->first();
    		if($sale_order)
    			$sale_order->delete();
    		$response['sale_order_no']=$max_order_id;
	    	$response['device_id']="-";
	    	$response['can_buy']=false;
	    	$response['booking']=0;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}
    	return Response::json($response);
    }

	

	public function getcart($id){
		$id = MCrypt::decrypt($id);
		$agopt_ids =$this->myGlob->agopt_ids;
		$objorder=SaleOrder::whereid($id)->first();
		$objsaleitems=SaleItem::whereorder_id($id)->get();
		$tickets=array();
		$operator_id=$this->myGlob->operator_id;
		$agentgroup_id=$this->myGlob->agentgroup_id;
		if($agopt_ids){
			if(Auth::user()->role == 9 || Auth::user()->role == 3 ){
				if($agentgroup_id){
					$agents=Agent::whereagentgroup_id($agentgroup_id)->get();
				}else{
					$agents=Agent::get();
				}
			}else{
				$agents=Agent::wherein('operator_id',$agopt_ids)->get();
			}
		}else{
			$agents=Agent::whereoperator_id($operator_id)->get();
		}
		$objexendcity=array();
		$customer_name="";
		$phone_no="";
		if($objsaleitems){
			foreach ($objsaleitems as $ticket) {
				$temp['id']				=$ticket['id'];
				$temp['sale_order_no']	=$ticket['order_id'];
				$temp['agent_id']		=$ticket['agent_id'];
				$temp['seat_no']		=$ticket['seat_no'];
				$customer_name			=$ticket['name'];
				$phone_no				=$ticket['phone'];
				$temp['trip_id']		=$ticket['trip_id'];
				$operator_id=$temp['operator_id']=$ticket['operator'];
				$temp['operator']		=Operator::whereid($ticket['operator'])->pluck('name');
				
				$objtrip 				=Trip::whereid($ticket->trip_id)->first();
				$trip_id=$objtrip->id;
				$objexendcity=ExtraDestination::wheretrip_id($trip_id)->with('city')->get();
				$temp['price']			=0;
				$temp['foreign_price']	=0;
				$temp['departure_date']	=$ticket->departure_date;
				$temp['price']			=$ticket->price;
				$temp['foreign_price']	=$objtrip->foreign_price;
				$from					=City::whereid($objtrip->from)->pluck('name');
				$to						=City::whereid($objtrip->to)->pluck('name');
				$temp['bus_no']			=0;
				$temp['time']			=$objtrip->time;
				$temp['from_to']		=$from.'-'.$to;
				$tickets[]				=$temp;
			}                                                                                                                                        
		}
		$customer=array();
		$customer['customer_name']=$customer_name;
		$customer['phone_no']=$phone_no;
		// return Response::json($objorder);
		return View::make('bus.cartview', array('response'=> $tickets, 'objorder'=>$objorder, 'agents'=>$agents, 'objexendcity'=>$objexendcity,'customer'=>$customer));
	}

	
	public function checkout(){
		$agent_id 		=Input::get('agent_id');
		$sale_order_no 	=Input::get('sale_order_no');
		$trip_id 		=Input::get('trip_id');
		$departure_date =Input::get('departure_date');
		$buyer_name 	=Input::get('buyer_name');
		$address 		=Input::get('address');
		$nationality 	=Input::get('nationality');
		$phone 			=Input::get('phone');
		$nrc_no 		=Input::get('nrc');
		$booking 		=0;
		$cash_credit 	=2;	//credit
		$today			=$this->Date;
		$oldsale 		=Input::get('oldsale');
		$solddate 		=Input::get('solddate');
		$extra_dest_id  =Input::get('extra_dest_id') ? Input::get('extra_dest_id') : 0;
		$remark_type 	=Input::get('remark_type');
		$remark 		=Input::get('remark');
		$sale_order_no  = MCrypt::decrypt($sale_order_no);

		$orderdate      =$today;
		if($oldsale){
			$orderdate       =$solddate ? $solddate : $today;
		}

		$seat_no 		=Input::get('seat_no');
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
    		$objsaleorder->agent_code=Agent::whereid($agent_id)->pluck('code_no');
    		$objsaleorder->name=$buyer_name;
    		$objsaleorder->nrc_no=$nrc_no;
    		$objsaleorder->phone=$phone;
    		$objsaleorder->nationality=$nationality;
    		$objsaleorder->booking=$booking;
    		$objsaleorder->cash_credit=$cash_credit;
    		
    		$i=0;
    		$totalamount=0;
    		$commission=0;
    		foreach ($seat_no as $seatno) {
    			$seatno = MCrypt::decrypt($seatno);

    			$objsaleitems=SaleItem::whereorder_id($sale_order_no)
    							->whereseat_no($seatno)
    							->wheretrip_id($trip_id)
    							->wheredeparture_date($departure_date)
    							->first();
    			if($objsaleorder && $objsaleitems){
    				if($i==0){
	    				$objagent_commission=AgentCommission::whereagent_id($agent_id)
	    													->wheretrip_id($objsaleitems->trip_id)
	    													->where('start_date','<=',$orderdate)
	                                                        ->where('end_date','>=',$orderdate)
	    													->first();
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

	    			if($extra_dest_id != 0){
						$extra_destination 					= ExtraDestination::whereid($extra_dest_id)->first();
						$objsaleitems->extra_destination_id = $extra_dest_id;
						$objsaleitems->price 				= $extra_destination->local_price;
						$objsaleitems->foreign_price 		= $extra_destination->foreign_price;
						$objsaleitems->extra_city_id		= $extra_destination->city_id;
					}
					$objsaleitems->order_id 		=$sale_order_no;
	    			$objsaleitems->agent_id 		=$agent_id;
	    			$objsaleitems->agent_code 		=Agent::whereid($agent_id)->pluck('code_no');
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
    			}else{
    				return 'error';
    			}
    			$i++;
    		}
			
    		$objsaleorder->total_amount=$totalamount;
    		$objsaleorder->agent_commission=$commission;
    		$objsaleorder->remark_type=$remark_type;
    		$objsaleorder->remark 	  =$remark;
    		$objsaleorder->update();	
    		

    		//Payment Transaction
    		if($booking == 0){
    			$total_amount 				= $objsaleorder->total_amount - $objsaleorder->agent_commission;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->agentgroup_id 	=Agent::whereid($objsaleorder->agent_id)->pluck('agentgroup_id');
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$today 										= date("Y-m-d");
	    		$objdepositpayment_trans->pay_date			= $today;
	    		$objdepositpayment_trans->order_ids			= '["'.$objsaleorder->id.'"]';

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
	    		if($saleOrder){
	    			$saleOrder->cash_credit = 2;
	    			$saleOrder->update();
	    		}
	    		
    		}

    	$message="Success.";
    	return Redirect::to('/all-trips?access_token='.Auth::user()->access_token)->with('message',$message);
	}

	public function deleteSaleOrder($id){
    	if(!$id){
    		$response['message']='sale_order_no is null.';
    		return Response::json($response);
    	}
    	$order_id=SaleItem::whereid($id)->pluck('order_id');
    	SaleItem::whereid($id)->delete();
    	$tickets=SaleItem::whereorder_id($order_id)->count();
    	if($tickets==0){
    		SaleOrder::whereid($order_id)->delete();
    	}
    	$response= "Have been deleted.";
    	return Response::json($response);
    }

	public function generateAutoID($operator_id, $operator_gp_id){
    	$prefix_opr    	= sprintf('%04s',$operator_id);
    	$prefix_gp_opr 	= sprintf('%02s',$operator_gp_id);
    	$prefix  		= $prefix_opr.$prefix_gp_opr;
    	// Get Last ID Value;
    	$last_order_id 		= SaleOrder::where('id','like',$prefix.'%')->orderBy('id','desc')->limit('1')->pluck('id');
    	if($last_order_id){
    		$last_order_value 	= (int) substr($last_order_id, strlen($prefix));
    	}else{
    		return $prefix."00000001";
    	}

    	return $prefix.sprintf('%08s', ++$last_order_value);
    }

    public function generateAutoIDFFormat($operator_id, $operator_gp_id){
    	$prefix_opr = 0;
    	$prefix_gp_opr = 0;
    	// Generate Operator ID;
    	if($operator_id >= 0 && $operator_id <=9){
    		$prefix_opr = "000".$operator_id;
    	}elseif($operator_id > 9 && $operator_id <=99){
    		$prefix_opr = "00".$operator_id;
    	}elseif($operator_id > 99 && $operator_id <=999){
    		$prefix_opr = "0".$operator_id;
    	}elseif($operator_id > 999 && $operator_id <=9999){
    		$prefix_opr = $operator_id;
    	}
    	// Generate Operator Group ID;
    	if($operator_gp_id >= 0 && $operator_gp_id <=9){
    		$prefix_gp_opr = "000".$operator_gp_id;
    	}elseif($operator_gp_id > 9 && $operator_gp_id <=99){
    		$prefix_gp_opr = "00".$operator_gp_id;
    	}elseif($operator_gp_id > 99 && $operator_gp_id <=999){
    		$prefix_gp_opr = "0".$operator_gp_id;
    	}elseif($operator_gp_id > 999 && $operator_gp_id <=9999){
    		$prefix_gp_opr = $operator_gp_id;
    	}
    	$prefix = $prefix_opr.$prefix_gp_opr;
    	$autoid 			= 0;
    	// Get Last ID Value;
    	$last_order_id 		= SaleOrder::where('id','like',$prefix.'%')->orderBy('id','desc')->limit('1')->pluck('id');
    	if($last_order_id){
    		$last_order_value 	= (int) substr($last_order_id, strlen($prefix));
    	}else{
    		return $prefix."00000001";
    	}

		//Auto Digit 8    	
    	if($last_order_value >= 0 && $last_order_value <9){
    		$inc_value = ++$last_order_value;
    		$autoid = "0000000".$inc_value;
    	}elseif($last_order_value >= 9 && $last_order_value <99){
    		$inc_value = ++$last_order_value;
    		$autoid = "000000".$inc_value;
    	}elseif($last_order_value >= 99 && $last_order_value <999){
    		$inc_value = ++$last_order_value;
    		$autoid = "00000".$inc_value;
    	}elseif($last_order_value >= 999 && $last_order_value <9999){
    		$inc_value = ++$last_order_value;
    		$autoid = "0000".$inc_value;
    	}elseif($last_order_value >= 9999 && $last_order_value <99999){
    		$inc_value = ++$last_order_value;
    		$autoid = "000".$inc_value;
    	}elseif($last_order_value >= 99999 && $last_order_value <999999){
    		$inc_value = ++$last_order_value;
    		$autoid = "00".$inc_value;
    	}elseif($last_order_value >= 999999 && $last_order_value <9999999){
    		$inc_value = ++$last_order_value;
    		$autoid = "0".$inc_value;
    	}elseif($last_order_value >= 9999999 && $last_order_value <99999999){
    		$inc_value = ++$last_order_value;
    		$autoid = $inc_value;
    	}
    	return $prefix.$autoid;
    }

}