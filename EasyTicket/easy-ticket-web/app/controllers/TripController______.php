<?php

class TripController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$operator_id=Operator::whereuser_id(Auth::user()->id)->pluck('id');
		$cities=City::all();
		$busclasses=Classes::whereoperator_id($operator_id)->get();	
		$seatplan=SeatingPlan::whereoperator_id($operator_id)->get();
		$days=array('Mon','Tue','Wed','Thur','Fri','Sat','Sun');
		$response['cities']=$cities;
		$response['days']=$days;
		$response['busclasses']=$busclasses;
		$response['seatplan']=$seatplan;
		// return Response::json($response);
		return View::make('trip.add', array('response'=>$response,'operator_id'=>$operator_id));
	}


	public function showSeatPlan($id){
    	$seatplan=SeatingPlan::find($id);
    	$temp=array();
    	if($seatplan){
			$objseatlayout=SeatingLayout::whereid($seatplan->seat_layout_id)->first();
			if($objseatlayout){
				$templayout['name']=$seatplan->name;
				$templayout['row']=$objseatlayout->row;
				$templayout['column']=$objseatlayout->column;
			}
			$seat_info=array();
			$objseatinfo=SeatInfo::whereseat_plan_id($seatplan->id)->get();
			$seat_info=array();
			if($objseatinfo){
				foreach ($objseatinfo as $seats) {
					$seattemp['seat_no']=$seats->seat_no;
					$seattemp['status']=$seats->status;
					$seat_info[]=$seattemp;
				}
			}
			$templayout['seat_list']=$seat_info;
			$temp=$templayout;
		}
		if(!$temp){
			return "False";
		}
		return View::make('trip.seatplan', array('response'=>$temp));
    	// return Response::json($temp);
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(){
		$operator_id		=Input::get('operator_id');
		$from				=Input::get('from');
		$to					=Input::get('to');
		$class_id			=Input::get('class_id');
		$day				=Input::get('day');
		$available_days		=Input::get('available_day');
		$price				=Input::get('price');
		$foreign_price		=Input::get('foreign_price');
		$time				=Input::get('time');
		$seat_plan_id		=Input::get('seat_plan_id');
		$onlyone_day		=Input::get('onlyone_day');
		// dd($onlyone_day);

		if($day=='daily'){
			$available_day='Daily';
		}if($day=='onlyone'){
			$available_day=$onlyone_day;
		}else{
			if(count($available_days)==0)
				$available_day='Daily';
			else
				$available_day=implode('-', $available_days);
		}
		
		if($from == $to){
			$response['message']="From and To should not be same.";
			return Response::json($response);
		}

		$objtrip			=new Trip();
		$checkobjtrip		=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->whereclass_id($class_id)->whereavailable_day($available_day)->wheretime($time)->first();
		if($checkobjtrip){
			$response['message']='This trip is already exit';
			return Redirect::to('trip-list');
		}

		$objtrip->operator_id 	=$operator_id;
		$objtrip->from 			=$from;
		$objtrip->to 			=$to;
		$objtrip->class_id 		=$class_id;
		$objtrip->available_day =$available_day;
		$objtrip->price 		=$price;
		$objtrip->foreign_price =$foreign_price;
		$objtrip->time 		    =$time;
		$objtrip->seat_plan_id	=$seat_plan_id;
		$objtrip->save();
		
		$trip_id=$objtrip->id;
		if($day=='onlyone'){
			return $this->postBusOccuranceOnlyOne($operator_id, $trip_id, $onlyone_day, $time);
		}
		if($objtrip->available_day=='daily' || $objtrip->available_day=='Daily'){
			return $this->postBusOccuranceDailyCreate($operator_id, $trip_id);
			// return Redirect::to('busoccurance/create/daily?access_token='.$access_token.'&trip_id='.$trip_id."&operator_id=".$operator_id);
		}else{
			$availableDays=$objtrip->available_day;
			return $this->postBusOccuranceAutoCreateCustom($operator_id, $trip_id, $availableDays);
		}
		
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
		Trip::whereid($id)->delete();
		BusOccurance::wheretrip_id($id)->delete();
		return Redirect::to('trip-list')->with('message','Successfully delete trip.');
	}

	public function triplists(){
		$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$response=Trip::whereoperator_id($operator_id)->with(array('operator','from_city','to_city','busclass','seat_plan'))->orderBy('id','desc')->get();
		
		return View::make('trip.list', array('response'=>$response));
	}

	public function postBusOccuranceOnlyOne($operator_id, $trip_id,$departure_date, $time){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "-"; 
		
		// $checkoccurances    =BusOccurance::wheretrip_id($trip_id)->where('departure_date','=',$departure_date)->first();
		//already create trip
		//First create trip 
			$objtrip 			=Trip::whereid($trip_id)->first();
			$tirp_id			=$objtrip->id;
			$seat_plan_id		=$objtrip->seat_plan_id;
			$operator_id		=$objtrip->operator_id;
			$from 				=$objtrip->from;
			$to 				=$objtrip->to;
			$class_id			=$objtrip->class_id;
			$price 				=$objtrip->price;
			$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
			$commission			=$objtrip->commission;
			$time 				=$objtrip->time;
			
			$obj_busoccurance 	=new BusOccurance();
			$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
			$obj_busoccurance->bus_no 			=$bus_no;
			$obj_busoccurance->from 			=$from;
			$obj_busoccurance->to 				=$to;
			$obj_busoccurance->classes 			=$class_id;
			$obj_busoccurance->departure_date 	=$departure_date;
			$obj_busoccurance->departure_time 	=$time;
			$obj_busoccurance->price 			=$price;
			$obj_busoccurance->foreign_price	=$foreign_price;
			$obj_busoccurance->commission		=$commission;
			$obj_busoccurance->operator_id 		=$operator_id;
			$obj_busoccurance->trip_id 			=$trip_id;
			
			$obj_busoccurance->save();
			$response="Successfully has been created for this day.";
			return Redirect::to('trip-list')->with('message',$response);;	
	}

	public function postBusOccuranceDailyCreate($operator_id, $trip_id){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "-"; 
		if(!$trip_id){
			$response['message'] ="Required parameter is missing.";
			return Response::json($response);
		}
		
		if(!$operator_id || !$bus_no){
			$response['message']="Request parameter is required.";
			return Response::json($response);
		}
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$currentMonth 		=date("m");
		$nextMonth 			=date("m", strtotime($month));
		$days_in_currentMonth=date("t");
		$days_in_nextMonth  =date("t", strtotime($month));
		

		$checkoccurances    =BusOccurance::wheretrip_id($trip_id)->where('departure_date','=',$today)->first();
		$tocreateoccuranceCMonth=array();
		$tocreateoccurance=array();
		for($j=$checkdate; $j<=$days_in_currentMonth; $j++){
			$tocreateoccuranceCMonth[]=$year.'-'.$currentMonth.'-'.sprintf("%02s", $j);
		}

		for($i=1; $i<=$days_in_nextMonth; $i++){
			$tocreateoccurance[]=$year.'-'.$nextMonth.'-'.sprintf("%02s", $i);
		}
		
		//already create trip
		if($checkoccurances){
			if($checkdate>=2){
				if($trip_id && $tocreateoccurance){
					$check_exiting=$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->first();
					if($check_exiting){
						$bus_no=$check_exiting->bus_no;
					}
					$bus_no =$bus_no !=null ? $bus_no : '-';
					$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}
					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$foreign_price 		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
					$commission			=$objtrip->commission;
					$time 				=$objtrip->time;
					$i=1;
					foreach ($tocreateoccurance as $departure_date) {
						$obj_busoccurance 	=new BusOccurance();
						$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
						$obj_busoccurance->bus_no 			=$bus_no;
						$obj_busoccurance->from 			=$from;
						$obj_busoccurance->to 				=$to;
						$obj_busoccurance->classes 			=$class_id;
						$obj_busoccurance->departure_date 	=$departure_date;
						$obj_busoccurance->departure_time 	=$time;
						$obj_busoccurance->price 			=$price;
						$obj_busoccurance->foreign_price	=$foreign_price;
						$obj_busoccurance->commission		=$commission;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
					$response['maxid']=BusOccurance::max('id');
					if($i==1){
						$response['message']="This trip has been created for this month!";
					}else{
						$response['message']="Successfully has been created for this month.";
					}
					// return Response::json($response);
				}
			}
		}else{ //First create trip 
			if($tocreateoccuranceCMonth){
				$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}else{
						$bus_no ='-';
					}

					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
					$commission			=$objtrip->commission;
					$time 				=$objtrip->time;
					$i=1;
					foreach ($tocreateoccuranceCMonth as $departure_date) {
						$obj_busoccurance 	=new BusOccurance();
						$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
						$obj_busoccurance->bus_no 			=$bus_no;
						$obj_busoccurance->from 			=$from;
						$obj_busoccurance->to 				=$to;
						$obj_busoccurance->classes 			=$class_id;
						$obj_busoccurance->departure_date 	=$departure_date;
						$obj_busoccurance->departure_time 	=$time;
						$obj_busoccurance->price 			=$price;
						$obj_busoccurance->foreign_price	=$foreign_price;
						$obj_busoccurance->commission		=$commission;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
			}
			//First create trip but today greater than 20 will create for next month.
			if($checkdate>=2){
				if($trip_id && $tocreateoccurance){
					$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}else{
						$bus_no='-';
					}
					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
					$commission			=$objtrip->commission;
					$time 				=$objtrip->time;
					$i=1;
					foreach ($tocreateoccurance as $departure_date) {
						$obj_busoccurance 	=new BusOccurance();
						$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
						$obj_busoccurance->bus_no 			=$bus_no;
						$obj_busoccurance->from 			=$from;
						$obj_busoccurance->to 				=$to;
						$obj_busoccurance->classes 			=$class_id;
						$obj_busoccurance->departure_date 	=$departure_date;
						$obj_busoccurance->departure_time 	=$time;
						$obj_busoccurance->price 			=$price;
						$obj_busoccurance->foreign_price	=$foreign_price;
						$obj_busoccurance->commission		=$commission;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
					$response['maxid']=BusOccurance::max('id');
					if($i==1){
						$response['message']="This trip has been created for this month!";
					}else{
						$response['message']="Successfully has been created for this month.";
					}
					return Redirect::to('trip-list');
					// return Response::json($response);
				}
			}
		}
		$response['message']="This trip has been created for this month.";
		// return Response::json($response);	
		return Redirect::to('trip-list');	
	}

	public function postBusOccuranceAutoCreateCustom($operator_id, $trip_id, $availableDays){
		$bus_no= BusOccurance::wheretrip_id($trip_id)->first();
		$bus_no =$bus_no !=null ? $bus_no : "-";
		$jsondays=explode('-', $availableDays);
		if(!$trip_id){
			$response['message'] ="Required parameter is missing.";
			return Response::json($response);
		}
		/** 
		 *change available days to index
		 * @return availabledayindexs
		 */
		$availabledayindexs=array();
		foreach ($jsondays as $value) {
			$index=0;
			switch ($value) {
				case 'Sat':
					$index=6;
					break;
				case 'Sun':
					$index=0;
					break;
				case 'Mon':
					$index=1;
					break;
				case 'Tue':
					$index=2;
					break;
				case 'Wed':
					$index=3;
					break;
				case 'Thurs':
					$index=4;
					break;
				case 'Fri':
					$index=5;
					break;
				
				default:
					# code...
					break;
			}
			$availabledayindexs[]=$index;
		}
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$currentMonth 		=date("m");
		$nextMonth 			=date("m", strtotime($month));
		$days_in_currentMonth=date("t");
		$days_in_nextMonth  =date("t", strtotime($month));
		
		$now = strtotime("now");
		if($checkdate >=15){
			$now = strtotime("now");
			$end_date=strtotime($year.'-'.$nextMonth.'-'.$days_in_nextMonth);
		}else{
			$end_date=strtotime($year.'-'.$currentMonth.'-'.$days_in_currentMonth);
		}

		$customdays=array();
		
		while (date("Y-m-d", $now) != date("Y-m-d", $end_date)) {
		    $day_index = date("w", $now);
		    foreach ($availabledayindexs as $value) {
		    	if ($day_index == $value) {
			    	$customdate=date('Y-m-d', $now);
			    	$customdays[]=$customdate;
			    }
		    }
		    
		    $now = strtotime(date("Y-m-d", $now) . "+1 day");
		}
		
		if(count($customdays)>0 && $trip_id){
			$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($customdays[0])->first();
			if($checkbusoccurances){
				$response['message']="This trip has been created for this month!";
				return Response::json($response);
			}

			$objtrip=Trip::whereid($trip_id)->first();
			if($objtrip){
				$bus_no 			=$bus_no;
				$trip_id			=$objtrip->id;
				$operator_id		=$objtrip->operator_id;
				$from				=$objtrip->from;
				$to					=$objtrip->to;
				$class_id			=$objtrip->class_id;
				$price 				=$objtrip->price;
				$foreign_price 		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
				$commission			=$objtrip->commission;
				$time 				=$objtrip->time;
				$seat_plan_id		=$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;

				$today=date('Y-m-d');
				$count_days=count($customdays);
				$i=1;
				$j=0;
				// return Response::json($available_day);
				foreach ($customdays as $customdaydate) {
					if($i<$count_days){
						$objbusoccurance=new BusOccurance();
						$objbusoccurance->seat_plan_id	=$seat_plan_id;
						$objbusoccurance->bus_no		=$bus_no;
						$objbusoccurance->from			=$from;
						$objbusoccurance->to			=$to;
						$objbusoccurance->classes		=$class_id;
						$objbusoccurance->departure_date=$customdaydate;
						$objbusoccurance->departure_time=$time;
						$objbusoccurance->price			=$price;
						$objbusoccurance->foreign_price	=$foreign_price;
						$objbusoccurance->commission	=$commission;
						$objbusoccurance->operator_id	=$operator_id;
						$objbusoccurance->trip_id		=$trip_id;
						$objbusoccurance->save();
						$j++;
					}
					$i++;
				}
				$response['message']="Successfully $j record created for this month.";
				return Response::json($response);
			}

		}
		$response['message']="Theres is no record to create1.";
		// return Response::json($response);
		return Redirect::to('trip-list');
	}

	public function ownseat($id){
		$operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$objtrip=Trip::whereid($id)->first();
		$seatplan=SeatingPlan::find($objtrip->seat_plan_id);
		$closeseat=CloseSeatInfo::wheretrip_id($objtrip->id)->whereseat_plan_id($objtrip->seat_plan_id)->pluck('seat_lists');
		$jsoncloseseat=json_decode($closeseat,true);
		// return Response::json($jsoncloseseat);

		$from=City::whereid($objtrip->from)->pluck('name');
		$to=City::whereid($objtrip->to)->pluck('name');
		$tripinfo['from_to']=$from.'=>'.$to;
		$tripinfo['class']=Classes::whereid($objtrip->class_id)->pluck('name');
		$tripinfo['available_day']=$objtrip->available_day;
		$tripinfo['time']=$objtrip->time;
		$tripinfo['price']=$objtrip->price;
		$tripinfo['foreign_price']=$objtrip->foreign_price;
		$tripinfo['seat_plan_id']=$objtrip->seat_plan_id;
		$tripinfo['trip_id']=$id;

		$response=array();
		$seatinfoandname=array();
    	if($seatplan){
			$objseatlayout=SeatingLayout::whereid($seatplan->seat_layout_id)->first();
			if($objseatlayout){
				$templayout['name']=$seatplan->name;
				$templayout['row']=$objseatlayout->row;
				$templayout['column']=$objseatlayout->column;
			}
			$seat_info=array();
			$objseatinfo=SeatInfo::whereseat_plan_id($seatplan->id)->get();
			$seatinfoandname=$objseatinfo;
			// return Response::json($objseatinfo);
			$seat_info=array();
			if($objseatinfo){
				foreach ($objseatinfo as $seats) {
					$seattemp['seat_no']=$seats->seat_no;
					$seattemp['status']=$seats->status;
					$seat_info[]=$seattemp;
				}
			}
			$templayout['seat_list']=$seat_info;
			$response=$templayout;
		}
		$operatorgroup=OperatorGroup::whereoperator_id($operator_id)->with(
							array('user' => function($query)
							{
							    $query->addSelect(array('id','name'));
							}))
							->get();
		// return Response::json($seatplan->seat_list);
		// return Response::json($seatinfoandname);

		if(!$jsoncloseseat){
			// $jsoncloseseat=$seatplan->seat_list;
			$jsoncloseseat=$seatinfoandname;
			$jsoncloseseat=json_decode($jsoncloseseat,true);
			$j=0;
			foreach($jsoncloseseat as $rowseatinfo){
				$jsoncloseseat[$j]['operatorgroup_id']=0;
				$j++;
			}
		}
		// return Response::json($operatorgroup);
		return View::make('trip.ownseat', array('response'=>$response,'operatorgroup'=>$operatorgroup, 'operator_id'=>$operator_id, 'tripinfo'=>$tripinfo,'jsoncloseseat'=>$jsoncloseseat));
	}

	public function postownseat(){
		$operator_id=Input::get('operator_id');
		$user_id=Auth::user()->id;
		$color=Input::get('color');
		$trip_id=Input::get('trip_id');
		$seat_plan_id=Input::get('seat_plan_id');
		$chosen_seats=Input::get('seats');
		$operatorgroup_id=Input::get('operatorgroup_id');

		$objseatinfo=SeatInfo::whereseat_plan_id($seat_plan_id)->get();
		if($objseatinfo){
			$i=0;
			foreach($objseatinfo as $seat){
				$ownseat=false;
				if($chosen_seats){
					foreach ($chosen_seats as $chooseseat) {
						if($chooseseat==$seat->seat_no){
							$ownseat=true;
						}
					}
				}
				if($ownseat)
					$objseatinfo[$i]['operatorgroup_id']=$operatorgroup_id ? $operatorgroup_id : 0;
				else
					$objseatinfo[$i]['operatorgroup_id']=0;
				$i++;
			}
		}
		// $stringseatlist = (string)($objseatinfo, true);
		// dd($stringseatlist);
		$check_exiting=CloseSeatInfo::wheretrip_id($trip_id)->whereseat_plan_id($seat_plan_id)->first();
		if(count($chosen_seats)==0){
			CloseSeatInfo::wheretrip_id($trip_id)->whereseat_plan_id($seat_plan_id)->delete();
			$message="Successfully Clear Own Seat.";
			return Redirect::to('trip-list')->with('message',$message);
		}
		if($check_exiting){
			$seat_lists=$check_exiting->seat_lists;
			$jsonexitingseats=json_decode($seat_lists, true);
			// return Response::json($chosen_seats);
			if($chosen_seats){
				$k=0;
				foreach ($jsonexitingseats as $rows) {
					$ownseat=false;
					$tocancelownseat=true;
					foreach ($chosen_seats as $chosen_seatno) {
						if($chosen_seatno==$rows['seat_no'] && $rows['operatorgroup_id']==0){
							$ownseat=true;
						}
						if($chosen_seatno ==$rows['seat_no']){
							$tocancelownseat=false;
						}
					}
					
					if($ownseat)
						$jsonexitingseats[$k]['operatorgroup_id']=$operatorgroup_id ? $operatorgroup_id : 0;
					else
						$jsonexitingseats[$k]['operatorgroup_id']=$rows['operatorgroup_id'];	

					if($tocancelownseat){
						$jsonexitingseats[$k]['operatorgroup_id']=0;
					}
					$k++;
				}
			}
			// return Response::json($jsonseats);
			$jsonexitingseats=json_encode($jsonexitingseats);
			// dd($jsonexitingseats);
			$check_exiting->seat_lists=$jsonexitingseats;
			$check_exiting->update();
		}else{

			$objcloseseat=new CloseSeatInfo();
			$objcloseseat->trip_id=$trip_id;
			$objcloseseat->seat_plan_id=$seat_plan_id;
			$objcloseseat->seat_lists=$objseatinfo;
			$objcloseseat->save();
		}
		
		$message="Successfully define Close Seats.";
		return Redirect::to('trip-list')->with('message',$message);

		// return Response::json($stringseatlist);
	}

}