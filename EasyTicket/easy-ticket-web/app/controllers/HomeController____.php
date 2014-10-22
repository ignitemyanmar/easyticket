<?php

class HomeController extends BaseController {

	public function showWelcome()
	{
		$objholiday=new Holiday();
        $objholiday->operator_id=11;
        $objholiday->month="07";
        $objholiday->holiday="2014-07-01";
        $objholiday->save();
		return View::make('hello');
	}

	public function index(){
		$objoperator=Operator::all();
		$operators=array();
		if($objoperator){
			foreach ($objoperator as $operator) {
				$tmp['id']=$operator->id;
				$tmp['name']=$operator->name;
				$tmp['address']=$operator->address;
				$tmp['phone']=$operator->phone;
				$operators[]=$tmp;
			}
		}
		$response['operators']=$operators;

		$operator_id=Operator::where('name','like','%Elite%')->pluck('id');
		$city_ids=$to_city_ids=array();
		if($operator_id)
		{
			$city_ids=Trip::whereoperator_id($operator_id)->groupBy('from')->lists('from');
			$to_city_ids=Trip::whereoperator_id($operator_id)->wherein('from',$city_ids)->groupBy('to')->lists('to');
		}

		$objcity= $objtocity=array();
		if($city_ids)
		$objcity=City::wherein('id',$city_ids)->get(array('id','name'));
		$response['from_cities']=$objcity;
		if($to_city_ids)
		$objtocity=City::wherein('id',$to_city_ids)->get(array('id','name'));
		$response['to_cities']=$objtocity;

		$objoperator=Operator::all();
		$operators=array();
		if($objoperator){
			foreach ($objoperator as $operator) {
				$tmp['id']=$operator->id;
				$tmp['name']=$operator->name;
				$tmp['address']=$operator->address;
				$tmp['phone']=$operator->phone;
				$operators[]=$tmp;
			}
		}
		$response['operators']=$operators;

		
		$date=date('Y-m-d');

		$buslist=BusOccurance::wherefrom(1)->whereto(6)->where('departure_date','=',$date)->get();
		$busoccurancelist=array();
		if($buslist){
			foreach ($buslist as $row) {
				$tmp=array();
				
				$seatplan 	=array();
				$objseat 		=SeatingPlan::whereid($row->seat_plan_id)->first();
				$seatplan['id']=$row->id;
				$seatplan['from']=$row->from;
				$seatplan['to']=$row->to;
				$from=$row->from;
				$to=$row->to;
				if($from && $to){
					$from=City::whereid($from)->pluck('name');
					$to  =City::whereid($to)->pluck('name');
					$tmp['trip']=$from.'-'.$to;
				}
				$seatplan['bus_no']=$row->bus_no;
				$seatplan['seat_plan_id']=$row->seat_plan_id;
				// $tmp['available_day']=$row->available_day;
				$seatplan['classes']=$row->classes;
				$seatplan['departure_date']=$row->departure_date;
				$seatplan['departure_time']=$row->departure_time;
				$seatplan['arrival_time']=$row->arrival_time;
				$seatplan['price']=$row->price;
				$seatplan['operator_id']=$row->operator_id;
				

				$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
				$seatplan['row']  			 =$objseat->row;
				$seatplan['column']  		 =$objseat->column;
				// $seatplan['seatlist']		 =$objseat->seat_list;

				$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
				$seatinfo 	=array();

				$tmp['operator_id']=$row->operator_id;
				$tmp['operator']=Operator::whereid($row->operator_id)->pluck('name');
				
				// $seatplan['seat_list'] =$seatinfo;

				$tmp['seat_plan'][]	   =$seatplan;
				$busoccurancelist[]=$tmp;
			}
		}
		
		// return Response::json($busoccurancelist);
		return View::make('bus.search', array('response' => $response, 'buslist'=>$busoccurancelist));

		// return View::make('bus.index', array('response'=> $response));
	}

	public function searchTrip(){
		$objoperator=Operator::all();
		$operators=array();
		if($objoperator){
			foreach ($objoperator as $operator) {
				$tmp['id']=$operator->id;
				$tmp['name']=$operator->name;
				$tmp['address']=$operator->address;
				$tmp['phone']=$operator->phone;
				$operators[]=$tmp;
			}
		}
		$response['operators']=$operators;

		$operator_id=Operator::where('name','like','%Elite%')->pluck('id');
		$city_ids=$to_city_ids=array();
		if($operator_id)
		{
			$city_ids=Trip::whereoperator_id($operator_id)->groupBy('from')->lists('from');
			$to_city_ids=Trip::whereoperator_id($operator_id)->wherein('from',$city_ids)->groupBy('to')->lists('to');
		}

		$objcity= $objtocity=array();
		if($city_ids)
		$objcity=City::wherein('id',$city_ids)->get(array('id','name'));
		$response['from_cities']=$objcity;
		if($to_city_ids)
		$objtocity=City::wherein('id',$to_city_ids)->get(array('id','name'));
		$response['to_cities']=$objtocity;

		$objoperator=Operator::all();
		$operators=array();
		if($objoperator){
			foreach ($objoperator as $operator) {
				$tmp['id']=$operator->id;
				$tmp['name']=$operator->name;
				$tmp['address']=$operator->address;
				$tmp['phone']=$operator->phone;
				$operators[]=$tmp;
			}
		}
		$response['operators']=$operators;


		$operator_id=Input::get('operator');
		$todaydate=date('Y-m-d');
		$choosedate=Input::get('departure_date');
		$date=date('Y-m-d', strtotime($choosedate));
		$from=Input::get('from');
		$to=Input::get('to');
		$time=null;
		$today =date('Y-m-d');
		$date= $date < $today ? $today : $date; 
		
		$buslist=array();

		if($date && !$time && $operator_id){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->where('departure_date','=',$date)->get();
		}
		elseif($date && $time && $operator_id){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->where('departure_date','=',$date)->where('departure_time','=',$time)->get();
		}
		elseif(!$date && !$time && $operator_id)
		{
			$buslist=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->get();
		}elseif($date && !$time && !$operator_id){
			$buslist=BusOccurance::wherefrom($from)->whereto($to)->where('departure_date','=',$date)->get();
		}else{

		}
		
		// $buslist=BusOccurance::wherefrom($from)->whereto($to)->where('departure_date','=',$date)->get();
		$busoccurancelist=array();
		if($buslist){
			foreach ($buslist as $row) {
				$tmp=array();
				
				$seatplan 	=array();
				$objseat 		=SeatingPlan::whereid($row->seat_plan_id)->first();
				$seatplan['id']=$row->id;
				$seatplan['from']=$row->from;
				$seatplan['to']=$row->to;
				$from=$row->from;
				$to=$row->to;
				if($from && $to){
					$from=City::whereid($from)->pluck('name');
					$to  =City::whereid($to)->pluck('name');
					$tmp['trip']=$from.'-'.$to;
				}
				$seatplan['bus_no']=$row->bus_no;
				$seatplan['seat_plan_id']=$row->seat_plan_id;
				// $tmp['available_day']=$row->available_day;
				$seatplan['classes']=$row->classes;
				$seatplan['departure_date']=$row->departure_date;
				$seatplan['departure_time']=$row->departure_time;
				$seatplan['arrival_time']=$row->arrival_time;
				$seatplan['price']=$row->price;
				$seatplan['operator_id']=$row->operator_id;
				

				$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
				$seatplan['row']  			 =$objseat->row;
				$seatplan['column']  		 =$objseat->column;
				// $seatplan['seatlist']		 =$objseat->seat_list;

				$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
				$seatinfo 	=array();

				$tmp['operator_id']=$row->operator_id;
				$tmp['operator']=Operator::whereid($row->operator_id)->pluck('name');
				
				// $seatplan['seat_list'] =$seatinfo;

				$tmp['seat_plan'][]	   =$seatplan;
				$busoccurancelist[]=$tmp;
			}
		}
		
		// return Response::json($busoccurancelist);
		return View::make('bus.search', array('response' => $response, 'buslist'=>$busoccurancelist));
	}

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
    			foreach ($objseatinfo as $seat) {
    				$temp['id']=$seat->id;
    				$checkoccupied_seat =SaleItem::wherebusoccurance_id($bus_id)->whereseat_no($seat->seat_no)->first();
					if($checkoccupied_seat){
						$temp['status']		=2;
					}else{
						$temp['status']		=$seat->status;
					}
    				$temp['seat_no']	=$seat->seat_no;
    				// $temp['status']		=$seat->status;
    				$temp['price']		=$objbusoccurance->price;
    				if($seat->status == 0){
    					$temp['price']	='xxx';
    				}
    				$checksaleitem		=SaleItem::whereseat_no($seat->seat_no)->wherebusoccurance_id($busoccurance_id)->first();
    				$seats[]	=	$temp;
    			}
    			$seat_list['operator']		=Operator::whereid($operator_id)->pluck('name');
				$seat_list['operator_id']	=$operator_id;
				$seat_list['from']			=City::whereid($from)->pluck('name');
				$seat_list['to']			=City::whereid($to)->pluck('name');
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

    	// for related bus
    	//
    	//
		$buslist=array();
		if($date && !$time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)->where('id','!=',$bus_id)->wherefrom($from)->whereto($to)->where('departure_date','=',$date)->get();
		}
		
		$busoccurancelist=array();
		if($buslist){
			foreach ($buslist as $row) {
				$tmp=array();
				
				$seatplan 	=array();
				$objseat 		=SeatingPlan::whereid($row->seat_plan_id)->first();
				$seatplan['id']=$row->id;
				$seatplan['from']=$row->from;
				$seatplan['to']=$row->to;
				$from=$row->from;
				$to=$row->to;
				if($from && $to){
					$from=City::whereid($from)->pluck('name');
					$to  =City::whereid($to)->pluck('name');
					$tmp['trip']=$from.'-'.$to;
				}
				$seatplan['bus_no']=$row->bus_no;
				$seatplan['seat_plan_id']=$row->seat_plan_id;
				// $tmp['available_day']=$row->available_day;
				$seatplan['classes']=$row->classes;
				$seatplan['departure_date']=$row->departure_date;
				$seatplan['departure_time']=$row->departure_time;
				$seatplan['arrival_time']=$row->arrival_time;
				$seatplan['price']=$row->price;
				$seatplan['operator_id']=$row->operator_id;
				

				$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
				$seatplan['row']  			 =$objseat->row;
				$seatplan['column']  		 =$objseat->column;
				// $seatplan['seatlist']		 =$objseat->seat_list;

				$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
				$seatinfo 	=array();

				$tmp['operator_id']=$row->operator_id;
				$tmp['operator']=Operator::whereid($row->operator_id)->pluck('name');
				
				// $seatplan['seat_list'] =$seatinfo;

				$tmp['seat_plan'][]	   =$seatplan;
				$busoccurancelist[]=$tmp;
			}
		}
    		// return Response::json($seat_list);
    	return View::make('bus.chooseseat', array('response'=>$seat_list, 'related_bus'=>$buslist));
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
	    	// if($available_seats){
	    		$objsaleorder=new SaleOrder();
	    		$objsaleorder->orderdate=date('Y-m-d');
	    		$objsaleorder->agent_id=$agent_id;
	    		$objsaleorder->operator_id=$operator_id;
	    		$objsaleorder->expired_at=$expired_date;
	    		$objsaleorder->save();
	    		$max_order_id=SaleOrder::max('id');
	    		
	    		foreach ($available_seats as $rows) {
	    			$objsaleitems=new SaleItem();
	    			$objsaleitems->order_id=$max_order_id;
	    			$objsaleitems->busoccurance_id=$rows['busoccurance_id'];
	    			$objsaleitems->seat_no=$rows['seat_no'];
	    			$objsaleitems->save();
	    		}
	    	// }
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
		$objsaleitems=SaleItem::whereorder_id($id)->get();
		$tickets=array();
		$operator_id=null;
		if($objsaleitems){
			foreach ($objsaleitems as $ticket) {
				$temp['id']				=$ticket['id'];
				$temp['sale_order_no']	=$ticket['order_id'];
				$temp['agent_id']		=$ticket['agent_id'];
				$temp['seat_no']		=$ticket['seat_no'];
				$temp['busoccurance_id']=$ticket['busoccurance_id'];
				$operator_id=$temp['operator_id']	=$ticket['operator'];
				$temp['operator']		=Operator::whereid($ticket['operator'])->pluck('name');
				$temp['price']			=BusOccurance::whereid($ticket['busoccurance_id'])->pluck('price');
				$objbusoccurance		=BusOccurance::whereid($ticket['busoccurance_id'])->first();
				$from					=City::whereid($objbusoccurance->from)->pluck('name');
				$to						=City::whereid($objbusoccurance->to)->pluck('name');
				$temp['bus_no']			=$objbusoccurance->bus_no;
				$temp['time']			=$objbusoccurance->departure_time;
				$temp['from_to']		=$from.'-'.$to;
				$tickets[]				=$temp;
			}
		}
		$agents=Agent::whereoperator_id($operator_id)->get();
		// dd($operator_id);
		// return Response::json($agents);
		return View::make('bus.cartview', array('response'=> $tickets, 'agents'=>$agents));
	}

	public function checkout(){
		$agent_id 		=Input::get('agent_id');
		$sale_order_no 	=Input::get('sale_order_no');
		$buyer_name 	=Input::get('buyer_name');
		$address 		=Input::get('address');
		$phone 			=Input::get('phone');
		$nrc_no 		=Input::get('nrc');
		$seat_no 		=Input::get('seat_no');
		$busoccurance_id=Input::get('busoccurance_id');
		$ticket_no		=Input::get('ticket_no');
    		$objsaleorder=SaleOrder::find($sale_order_no);
    		if(!$objsaleorder){return Redirect::to('bus');}
    		$objsaleorder->orderdate=date('Y-m-d');
    		$objsaleorder->agent_id=$agent_id;
    		$objsaleorder->name=$buyer_name;
    		$objsaleorder->nrc_no=$nrc_no;
    		$objsaleorder->phone=$phone;
    		$objsaleorder->update();
    		$i=0;
    		foreach ($seat_no as $seatno) {
    			$objsaleitems=SaleItem::whereorder_id($sale_order_no)->whereseat_no($seatno)->wherebusoccurance_id($busoccurance_id[$i])->first();
    			$objsaleitems->order_id 		=$sale_order_no;
    			$objsaleitems->busoccurance_id 	=$busoccurance_id[$i];
    			$objsaleitems->seat_no			=$seatno;
    			$objsaleitems->name				=$buyer_name;
    			$objsaleitems->nrc_no			=$nrc_no;
    			$objsaleitems->ticket_no		=$ticket_no[$i];
    			$objsaleitems->update();
    			$i++;
    		}
    	$message="Success.";
    	return Redirect::to('bus')->with('message',$message);
	}

	public function postSale(){
    	$now_date = date("Y-m-d H:i:s");
		$currentDate = strtotime($now_date);
		$futureDate = $currentDate+(60*15);//add 15 minutes for expired_time;
		$expired_date = date("Y-m-d H:i:s", $futureDate);

    	$operator_id=Input::get('operator_id');
    	$agent_id=Input::get('agent_id');
    	$date=Input::get('date');
    	$time=Input::get('time');
    	$from_city=Input::get('from_city');
    	$to_city=Input::get('to_city');
    	$seat_liststring=Input::get('seat_list');
    	$order_type=Input::get('order_type');

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
    			$objsaleorder=new SaleOrder();
	    		$objsaleorder->orderdate=date('Y-m-d');
	    		$objsaleorder->agent_id=$agent_id ? $agent_id : 0;
	    		$objsaleorder->operator_id=$operator_id;
	    		$objsaleorder->expired_at=$expired_date;
	    		$objsaleorder->device_id=$device_id;
	    		$objsaleorder->save();
	    		$max_order_id=SaleOrder::max('id');
	    		foreach ($available_seats as $rows) {
	    			$check_exiting=SaleItem::wherebusoccurance_id($rows['busoccurance_id'])->whereseat_no($rows['seat_no'])->first();
	    			if($check_exiting){
	    				//already exit skip;
	    			}else{
	    				$available_orderid=$max_order_id;
						$objsaleitems					=new SaleItem();
		    			$objsaleitems->order_id 		=$max_order_id;
		    			$objsaleitems->busoccurance_id 	=$rows['busoccurance_id'];
		    			$price 							=BusOccurance::whereid($rows['busoccurance_id'])->pluck('price');
		    			$objsaleitems->seat_no			=$rows['seat_no'];
		    			$objsaleitems->device_id		=$device_id;
		    			$objsaleitems->operator			=$operator_id;
		    			$objsaleitems->price			=$price;
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
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}else{
    		$response['sale_order_no']=$max_order_id;
	    	$response['device_id']="-";
	    	$response['can_buy']=false;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}
    	return Response::json($response);
    }

    public function deleteSaleOrder($id){
    	// $order_no=Input::get('sale');
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

}