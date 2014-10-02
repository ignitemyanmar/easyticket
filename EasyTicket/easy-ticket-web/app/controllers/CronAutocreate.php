<?php
public function cronBusOccuranceDailyCreate($trip_id){
		$bus_no 			=BusOccurance::wheretrip_id($trip_id)->pluck('bus_no');
		$bus_no 			=$bus_no !=null ? $bus_no : "-";
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				=date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$nextMonth 			=date("m", strtotime($month));
		$days_in_nextMonth  =date("t", strtotime($month));
		
		$tocreateoccuranceCMonth=array();
		$tocreateoccurance=array();
		for($i=1; $i<=$days_in_nextMonth; $i++){
			$tocreateoccurance[]=$year.'-'.$nextMonth.'-'.sprintf("%02s", $i);
		}
		
		if($trip_id && $tocreateoccurance){
			$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
			if($checkbusoccurances){
			}else{
				$objtrip 			=Trip::whereid($trip_id)->first();
				$tirp_id			=$objtrip->id;
				$seat_plan_id		=$objtrip->seat_plan_id;
				$operator_id		=$objtrip->operator_id;
				$from 				=$objtrip->from;
				$to 				=$objtrip->to;
				$class_id			=$objtrip->class_id;
				$price 				=$objtrip->price;
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
					$obj_busoccurance->operator_id 		=$operator_id;
					$obj_busoccurance->trip_id 			=$trip_id;
					
					$obj_busoccurance->save();
					$i++;
				}	
			}
			
		}

	}

	public function cronBusOccuranceAutoCreateCustom($trip_id, $availableDays){
		$bus_no 			=BusOccurance::wheretrip_id($trip_id)->pluck('bus_no');
		$bus_no 			=$bus_no !=null ? $bus_no : "-";
		$jsondays=explode('-', $availableDays);
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
		
			$end_date=strtotime($year.'-'.$nextMonth.'-'.$days_in_nextMonth);
			$start_date=$year.'-'.$nextMonth.'-01';
			$now = strtotime($start_date);
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
			}else{
				$objtrip=Trip::whereid($trip_id)->first();
				if($objtrip){
					$trip_id	=$objtrip->id;
					$operator_id=$objtrip->operator_id;
					$from		=$objtrip->from;
					$to			=$objtrip->to;
					$class_id	=$objtrip->class_id;
					$price 		=$objtrip->price;
					$time 		=$objtrip->time;
					$seat_plan_id=$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;

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
							$objbusoccurance->operator_id	=$operator_id;
							$objbusoccurance->trip_id		=$trip_id;
							$objbusoccurance->save();
							$j++;
						}
						$i++;
					}
				}
			}
		}
	}
	