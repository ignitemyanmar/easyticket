<?php
class ApiController extends BaseController
{
	public $today = "";
    
    public function __construct() {
		$this->today = App::make('MyDate');
	}

	public function postUserRegister(){
	    $user=new User();
	    $checkuser=User::whereemail(Input::get('email'))->first();
	    if($checkuser){
	      return 'Email is already used!';
	    }
	    $name=Input::get('name');
	    $email=Input::get('email');
	    $type=Input::get('type');
	    $password=Input::get('password');
	    if(!$name || !$email || !$password || !$type){
	    	$response['message']="Required fields are name, email and password";
	    	return Response::json($response);
	    }
	    $user->name=$name;
	    $user->email=$email;
	    $user->password=Hash::make($password);
	    $user->role=0;
	    $user->type=$type;
	    $user->save();
	    
	    $user = array(
	              'email' => Input::get('email'),
	              'password' => Input::get('password')
	          );
	          
	      if (Auth::attempt($user)) {
	      	$response['message']='You have arrived';
	      	return Response::json($response);
	          // return Redirect::to('/');
	      }
	          
	      return "Sorry. You can't register.";
	}

	public function postLogin(){
		$email=Input::get('email');
		$password=Input::get('password');
		if(!$email || !$password){
			$response['message']="Required fields are email and password.";
			return Response::json($response);
		}
      	$user = array(
              'email' => $email,
              'password' => $password
          	);
          
          	if (Auth::attempt($user)) {
          		$response['message']='You have arrived';
	      		return Response::json($response);
          	}
          // authentication failure! lets go back to the login page
        $response['message']="email and password don't match.";
        return Response::json($response);
    }

	public function getCustomerlistByAgentGroup(){
    	$objcustomer=array();
    	$agentgroup_id=Input::get('agentgroup_id');
    	if(!$agentgroup_id){
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	$agent_ids =Agent::whereagentgroup_id($agentgroup_id)->lists('id');
		if($agent_ids){
    		$objcustomer=SaleOrder::wherein('agent_id',$agent_ids)->groupBy('name','phone')->where('name','!=','')->get(array('name','nrc_no','phone'));
    	}
    	return Response::json($objcustomer);
    }

    public function getCustomerlistByAgent(){
    	$agent_id=Input::get('agent_id');
    	$objcustomer=array();
    	if($agent_id){
    		$objcustomer=SaleOrder::whereagent_id($agent_id)->groupBy('name','phone')->where('name','!=','')->get(array('name','nrc_no','phone'));
    	}else{
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	return Response::json($objcustomer);
    }

    public function getCustomerlistByOperator(){
    	$operator_id=Input::get('operator_id');
    	$objcustomer=array();
    	if($operator_id){
    		$objcustomer=SaleOrder::whereoperator_id($operator_id)->groupBy('name','phone')->where('name','!=','')->get(array('name','nrc_no','phone'));
    	}else{
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	return Response::json($objcustomer);
    }


	public function getAllOperator(){
		$objoperator=Operator::all();
		$operators=array();
		if($objoperator){
			foreach ($objoperator as $operator) {
				$tmp['id']=$operator->id;
				$tmp['name']=$operator->name;
				$tmp['address']=$operator->address;
				$tmp['phone']=$operator->phone;
				
				$login_info=array();
				$user_id=$operator->user_id;
				$objuser=User::whereid($user_id)->first();
				$login_info['username']='-';
				if($objuser)
				$login_info['username']=$objuser->email;

				$login_info['scope']='operator';
				$operator_name=$operator->name ."(operator)";
				$objoauthclient=OauthClients::wherename($operator_name)->first();
				if($objoauthclient){
					$login_info['client_secret']=$objoauthclient->secret;
					$login_info['client_id']=$objoauthclient->id;
				}else{
					$login_info['client_secret']='-';
					$login_info['client_id']='-';
				}
				
				$tmp['login_info']=$login_info;
				$operators[]=$tmp;
			}
		}
		$response['operators']=$operators;
		return Response::json($response);
	}

	public function getAllAgent(){
		$input = json_decode(MCrypt::decrypt(Input::get('param')));
		$operator_id = isset($input->operator_id) ? $input->operator_id : null;
		if($operator_id){
			$objagent=Agent::whereoperator_id($operator_id)->orderBy('name', 'asc')->get();
		}else{
			$objagent=Agent::all();
		}
		$agents=array();
		if($objagent){
			foreach ($objagent as $row) {
				$tmp['id']					=$row->id;
				$tmp['agentgroup_id']		=$row->agentgroup_id;
				$tmp['agentgroup']			=AgentGroup::whereid($row->agentgroup_id)->pluck('name');
				$tmp['name']				=$row->name;
				$tmp['phone']				=$row->phone;
				$tmp['address']				=$row->address;
				$tmp['commission_id']		=$row->commission_id;
				$tmp['commissiontype']		=CommissionType::whereid($row->commission_id)->pluck('name');
				$tmp['commission']		=$row->commission;
				$agents[]			=$tmp;
			}
		}
		
		$response['agents']=$agents;
		
		return Response::json($response);
	}


	public function getAllTrip(){
		$operator_id=Input::get('operator_id');
		$group_by=Input::get('group_by');
		if($operator_id){
			if($group_by){
				$objtrips=Trip::whereoperator_id($operator_id)->groupby('to')->get();
			}else{
				$objtrips=Trip::whereoperator_id($operator_id)->get();
			}
		}else{
			$objtrips=Trip::all();
		}
		$trips=array();
		$i=0;
		if($objtrips){
			foreach ($objtrips as $trip) {
				$tmp['id']=$trip->id;
				$tmp['operator_id']=$trip->operator_id;
				$tmp['from']=$trip->from;
				$tmp['to']=$trip->to;
				$tmp['operator']=Operator::whereid($trip->operator_id)->pluck('name');
				$tmp['from_city']=City::whereid($trip->from)->pluck('name');
				$tmp['to_city']=City::whereid($trip->to)->pluck('name');
				$tmp['class_id']=$trip->class_id;
				$tmp['classes']=Classes::whereid($trip->class_id)->pluck('name');
				$tmp['available_day']=$trip->available_day;
				$tmp['time']=$trip->time;
				$tmp['price']=$trip->price;
				$tmp['foreign_price']=$trip->foreign_price;
			
				$trips[]=$tmp;
			}
		}
		
		$response['trips']=$trips;
		return Response::json($response);
	}


	public function getSeatPlan(){
		$now_date = $this->today;
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->wherebooking(0)->where('name','=','')->lists('id');
    	
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}
    	
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}

		$ticket_type_id= isset($input->ticket_type_id) ? $input->ticket_type_id : 0;
		$operator_id=$input->operator_id;
		$date=$input->date;
		$time=$input->time;
		$from=$input->from_city;
		$to=$input->to_city;
		$class_id=$input->class_id;
		$todaydate=$this->today;

		// return 'hi';
		if(!$operator_id || !$from || !$to){
			$response['message']="Please Require fields are operator_id, from_city and to_city.";
			return Response::json($response);
		}
		/*if($date < $todaydate){
			$response['message']="Departure Date should be greater than or equal today date.";
			return Response::json($response);
		}*/

		$buslist=array();

		if($date && !$time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->whereclass_id($class_id)->get();
		}
		elseif($date && $time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->whereclass_id($class_id)
									->where('time','=',$time)->get();
		}
		elseif(!$date && !$time)
		{
			$buslist=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->whereclass_id($class_id)
									->get();
		}else{
		}

		$trip_list=array();
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
				$seatplan['classes']=Classes::whereid($row->class_id)->pluck('name');
				$seatplan['departure_date']=$date;
				$seatplan['departure_time']=$row->time;
				//$seatplan['arrival_time']=$row->arrival_time;
				$seatplan['price']=$row->price;
				$seatplan['foreign_price']=$row->foreign_price;
				$seatplan['operator_id']=$row->operator_id;
				
				if($objseat){
					$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
					$seatplan['row']  			 =$objseat->row;
					$seatplan['column']  		 =$objseat->column;
				}else{
					$seatplan['seat_layout_id']='-';
					$seatplan['row']='-';
					$seatplan['column']='-';
				}
				// $seatplan['seatlist']		 =$objseat->seat_list;
				$objcloseseatinfo 				 = CloseSeatInfo::wheretrip_id($row->id)
														->whereseat_plan_id($objseat->id)
														->where('start_date', '<=',$date)
														->where('end_date', '>=',$date)
														->first();

				$seatinfo 	=array();
				if($objcloseseatinfo){
					$seat_lists = $objcloseseatinfo->seat_lists;
					$objseatinfo = json_decode($seat_lists);
					foreach ($objseatinfo as $rows) {
						$temp['id']			=$rows->id;
						$temp['seat_no']	=$rows->seat_no;
						$checkoccupied_seat =SaleItem::wheretrip_id($row->id)->wheredeparture_date($date)
												->whereseat_no($rows->seat_no)->first(array('order_id','ticket_no','name','phone','nrc_no','agent_id','discount','free_ticket','free_ticket_remark'));
						if($checkoccupied_seat){
							$temp['status']			= 2;
							$temp['free_ticket'] 	= $checkoccupied_seat->free_ticket;
							$temp['free_ticket_remark'] = $checkoccupied_seat->free_ticket_remark;
							$temp['discount'] 		= $checkoccupied_seat->discount;
							$temp['booking']		= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('booking');
							$temp['remark_type']	= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark_type');
							$temp['remark']			= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark');
							$temp['customer_info']  = $checkoccupied_seat->toarray();
							$temp['customer_info']['agent_name'] = Agent::whereid($checkoccupied_seat->agent_id)->pluck('name');
						}else{
							$temp['status']			= $rows->status;
							$temp['free_ticket'] 	= 0;
							$temp['free_ticket_remark'] = null;
							$temp['discount'] 		= 0;
							$temp['booking']		= 0;
							$temp['remark_type']	= 0;
							$temp['remark']			= null;
							$temp['customer_info']  = null;
						}
						$temp['operatorgroup_id']	= isset($rows->operatorgroup_id) ? $rows->operatorgroup_id : 0;
						$seatinfo[] 		=$temp;
					}
				}else{
					$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
					foreach ($objseatinfo as $rows) {
						$temp['id']			=$rows->id;
						$temp['seat_no']	=$rows->seat_no;
						$checkoccupied_seat =SaleItem::wheretrip_id($row->id)->wheredeparture_date($date)
												->whereseat_no($rows->seat_no)->first(array('order_id','ticket_no','name','phone','nrc_no','agent_id','discount','free_ticket','free_ticket_remark'));
						if($checkoccupied_seat){
							$temp['status']			=2;
							$temp['free_ticket'] 	= $checkoccupied_seat->free_ticket;
							$temp['free_ticket_remark'] = $checkoccupied_seat->free_ticket_remark;
							$temp['discount'] 		= $checkoccupied_seat->discount;
							$temp['booking']		= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('booking');
							$temp['remark_type']	= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark_type');
							$temp['remark']			= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark');
							$temp['customer_info']  = $checkoccupied_seat->toarray();
							if($checkoccupied_seat->phone == null)
								$temp['customer_info']['phone'] = SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('phone');
							else
								$temp['customer_info']['phone'] = $checkoccupied_seat->phone;
							$temp['customer_info']['agent_name'] = Agent::whereid($checkoccupied_seat->agent_id)->pluck('name');
						}else{
							$temp['status']			=$rows->status;
							$temp['free_ticket'] 	= 0;
							$temp['free_ticket_remark'] = null;
							$temp['discount'] 		= 0;
							$temp['booking']		= 0;
							$temp['remark_type']	= 0;
							$temp['remark']			= null;
							$temp['customer_info']  = null;
						}
						
						$temp['operatorgroup_id']	= 0;
						$seatinfo[] 		=$temp;
					}
				}

				$tmp['operator_id']=$row->operator_id;
				$tmp['operator']=Operator::whereid($row->operator_id)->pluck('name');
				
				$seatplan['seat_list'] =$seatinfo;

				$tmp['seat_plan'][]	   =$seatplan;
				$trip_list[]=$tmp;
			}
		}
		
		return Response::json($trip_list);
	}

	public function getTimeLists(){
		/*
    	 * Delete not confirm order;
    	 */
			$expired_ids=SaleOrder::wherebooking(1)->where('booking_expired','<',$this->today)->lists('id');
	    	if($expired_ids){
	    		foreach ($expired_ids as $expired_id) {
	    			$saleOrder = SaleOrder::whereid($expired_id)->first();
	    			$saleItem = SaleItem::whereorder_id($expired_id)->get();
	    			if($saleOrder){
	    				$check = DeleteSaleOrder::whereid($expired_id)->first();
	    				if(!$check){
	    					$saleOrder->updated_at = $this->today;
	    					$saleOrder->remark = 'Booking Expired';
	    					$deletedSaleOrder = DeleteSaleOrder::create($saleOrder->toarray());
		    				
	    				}
	    				SaleOrder::whereid($expired_id)->delete();
	    			}
	    			if($saleItem){
	    				foreach ($saleItem as $rows) {
	    					$check = DeleteSaleItem::whereid($rows->id)->first();
	    					if(!$check){
	    						$deletedSaleitem = DeleteSaleItem::create($rows->toarray());
	    					}
	    					SaleItem::whereorder_id($expired_id)->delete();
	    				}
	    			}
	    		}
	    	}


	    $input = json_decode(MCrypt::decrypt(Input::get('param')));
	    if(!$input)
	    	return Response::json('Invalid Request!', 400);

		$operator_id		=isset($input->operator_id) ? $input->operator_id : 0;
		if($operator_id == 0)
			return Response::json('Invalid Operator Id!', 400);

		$from_city			=isset($input->from_city) ? $input->from_city : 0;
		$to_city			=isset($input->to_city) ? $input->to_city : 0;
		$ticket_type_id		=isset($input->ticket_type_id) ? $input->ticket_type_id : 0;
		$trip_date 			=isset($input->trip_date) ? $input->trip_date : 0;

		$day 				=Date('D',strtotime($trip_date));


		if($operator_id && $from_city && $to_city && $trip_date){
			$objtrip=Trip::whereoperator_id($operator_id)
					->wherefrom($from_city)
					->whereto($to_city)
					->where(function($query) use($trip_date, $day){
						  $query->where('available_day','LIKE',$trip_date)
								->orwhere('available_day','LIKE','Daily')
								->orwhere('available_day','LIKE','%'.$day.'%');
					})
					->orderBy('time','asc')
					->get();

		}
		$times=array();
		//return Response::json($objtrip);
		if($objtrip){
			foreach ($objtrip as $row) {
				
				$temp['tripid']				= $row->id;
				$temp['class_id'] 			= $row->class_id;
				$temp['bus_class']			= Classes::whereid($row->class_id)->pluck('name');
				$close_seatinfo = CloseSeatInfo::wheretrip_id($row->id)
													->where('start_date','<=',$trip_date)
														->where('end_date','>=',$trip_date)->first();
				if($close_seatinfo)
					$temp['total_seat']			= SeatInfo::whereseat_plan_id($close_seatinfo->seat_plan_id)->wherestatus(1)->count();
				else
					$temp['total_seat']			= SeatInfo::whereseat_plan_id($row->seat_plan_id)->wherestatus(1)->count();
				$temp['total_sold_seat']	= SaleItem::wheretrip_id($row->id)->wheredeparture_date($trip_date)->count();
				$temp['time']				= $row->time;
				// To check close trip with between date; 
				if($row->ever_close == 0){
					$close_trip = CloseTrip::wheretrip_id($row->id)
												->where('start_date', '<=',$trip_date)
												->where('end_date', '>=',$trip_date)
												->count();
					if($close_trip > 0){
						// to add false;
					}else{
						$times[] = $temp;
					}
				}else{
				// To check ever close trip;
					if(strtotime($row->from_close_date) <= strtotime($trip_date) && strtotime($row->to_close_date) >= strtotime($trip_date) ){
						// to add false;
					}else{
						$times[] = $temp;
					}
				}

				
			}
		}
		$tmp_times=$this->msort($times,array("bus_class","time"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);

		return Response::json($tmp_times); 
	}

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

	public function postSale(){
    	/*
    	 * Calulate Expired Time
    	 */
	    	$now_date = $this->getDateTime();
			$currentDate = strtotime($now_date);
			$futureDate = $currentDate+(60*5);//add 15 minutes for expired_time;
			$expired_date = date("Y-m-d H:i:s", $futureDate);

		/*
		 * Method Variables
		 */
			$available_tickets	= 0;
    		$available_seats	= array();
    		$order_auto_id		= 0;
    	
    	/*
    	 * From Parameters
    	 */
    		$input 					= json_decode(MCrypt::decrypt(Input::get('param')));
    		if(!$input)
    			return Response::json("Invalid Request!", 400);

	    	$operator_id 			= isset($input->operator_id) ? $input->operator_id : 0;
	    	$agent_id 				= isset($input->agent_id) ? $input->agent_id : 0;
	    	$name 	 				= isset($input->name) ? $input->name : 0;
	    	$phone  				= isset($input->phone) ? $input->phone : 0;
	    	$remark_type  			= isset($input->remark_type) ? $input->remark_type : 0;
	    	$remark  				= isset($input->remark) ? $input->remark : 0;
	    	$seat_liststring 		= isset($input->seat_list) ? $input->seat_list : 0;
	    	$group_operator_id 		= isset($input->group_operator_id) ? $input->group_operator_id : 0;
	    	$booking 				= isset($input->booking) ? $input->booking : 0;
	    	$device_id				= isset($input->device_id) ? $input->device_id : 0;
	    	$user_id 				= isset($input->user_id) ? $input->user_id : 0;
	    	$trip_id 				= isset($input->trip_id) ? $input->trip_id : 0;
	    	$departure_date			= isset($input->trip_date) ? $input->trip_date : null;
	  
    	
    	/*
    	 * Check Required Field.
    	 */
	    	if(!$operator_id || !$seat_liststring){
	    		$response['status'] = 0;
	    		$response['message']='Required fields are operator_id and seat_lsit';
	    		return Response::json($response);
	    	}
	    	$seat_liststring = MCrypt::decrypt($seat_liststring);
	    	$seat_list=json_decode($seat_liststring);
	    	if(count($seat_list)<1){
	    		$response['status'] = 0;
	    		$response['message']='Seat_list format is wrong.';
	    		return Response::json($response);
	    	}

	    /*
    	 * Delete Expired Order.
    	 */
	    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->wherebooking(0)->where('name','=','')->lists('id');
	    	if($expired_ids){
	    		foreach ($expired_ids as $expired_id) {
	    			$saleOrder = SaleOrder::whereid($expired_id)->first();
	    			$saleItem = SaleItem::whereorder_id($expired_id)->get();
	    			if($saleOrder){
	    				$check = DeleteSaleOrder::whereid($expired_id)->first();
	    				if(!$check){
	    					$saleOrder->remark = 'Not Confirmed';
	    					$deletedSaleOrder = DeleteSaleOrder::create($saleOrder->toarray());
		    				
	    				}
	    				SaleOrder::whereid($expired_id)->delete();
	    			}
	    			if($saleItem){
	    				foreach ($saleItem as $rows) {
	    					$check = DeleteSaleItem::whereid($rows->id)->first();
	    					if(!$check){
	    						$deletedSaleitem = DeleteSaleItem::create($rows->toarray());
	    					}
	    					SaleItem::whereorder_id($expired_id)->delete();
	    				}
	    			}
	    		}
	    	}
   		
   		
   		$canby 		= true;
   		$all_canby 	= true;
   		// dd($seat_list);
    	foreach ($seat_list as $rows) {
    		$trip = Trip::whereid($trip_id)->first();
    		$seat_plan_id   = $trip->seat_plan_id;
    		$departure_time = $trip->time;
    		$objseatinfo    = SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows->seat_no)->first();
    		
    		/*
    		 * Calculate Departure Datetime;
    		 */
    			$time 		= substr($departure_time, 0, 8);
    			$time 		= $time == '12:00 AM' ? '12:00 PM' : $time;
    			$datetime 	= $departure_date." ".$time;
    			$strdate  	= strtotime($datetime);
    			$strdate  	= $strdate - (60*10);
    			$booking_expired = date("Y-m-d H:i:s", $strdate);

    		/*
    		 * Checking Timeout Booking Transaction;
    		 */
    			if(strtotime($booking_expired) < strtotime($this->today) && $booking == 1){
    				$response['status'] = 0;
    				$response['message']='Cann\'t booking because of time out';
	    			return Response::json($response);
    			}


    		/*
			 * Checking Availabel Seat;
    		 */
	    		$chkstatus = SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->whereseat_no($rows->seat_no)->first();
	    		if($chkstatus){
		    		$canbuy=false;
		    		$all_canby = false;
	    		}
	    		else{
	    			$canbuy=true;
	    			$tmp['seat_no']			=$rows->seat_no;
	    			$available_seats[]=$tmp;
	    		}

	    	$temp['seat_id']	= $objseatinfo->id;
	    	$temp['seat_no']	= $objseatinfo->seat_no;
    		$temp['can_buy']	= $canbuy;
    		$temp['bar_code']	= 11111111111;
    		$tickets[]=$temp;
    	}

    	if(count($available_seats) == count($seat_list) && $all_canby){
    		//try {
    			$response['message']="Successfully your purchase or booking tickets.";
    			$can_buy=true;
    			$order_auto_id = $this->generateAutoID($operator_id,$group_operator_id);
    			$objsaleorder=new SaleOrder();
    			$objsaleorder->id 					= $order_auto_id;
	    		$objsaleorder->orderdate 			= $this->today;
	    		$objsaleorder->departure_date 		= $departure_date;
	    		$objsaleorder->booking_expired 		= $booking_expired;
	    		$objsaleorder->agent_id 			= $agent_id ? $agent_id : 0;
	    		$objsaleorder->agent_code 			= 0;
	    		$objsaleorder->name 	 			= $name;
	    		$objsaleorder->phone 	 			= $phone;
	    		$objsaleorder->remark_type 	 		= $remark_type;
	    		$objsaleorder->remark 	 			= $remark;
	    		$objsaleorder->operator_id 			= $operator_id;
	    		$objsaleorder->expired_at 			= $expired_date;
	    		$objsaleorder->device_id 			= $device_id;
	    		$objsaleorder->booking 				= $booking;
	    		$objsaleorder->user_id 				= $user_id ? $user_id : 0;
	    		$objsaleorder->save();
	    		
	    		foreach ($available_seats as $rows) {
	    			$check_exiting=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->whereseat_no($rows['seat_no'])->first();
	    			if(!$check_exiting){
	    				$trip 								= Trip::whereid($trip_id)->first();
	    				if($trip){
							$objsaleitems					=new SaleItem();
			    			$objsaleitems->order_id 		=$order_auto_id;
			    			$objsaleitems->name 		 	=$name;
			    			$objsaleitems->phone 		 	=$phone;
			    			$objsaleitems->class_id			=$trip->class_id;
			    			$objsaleitems->trip_id 		 	=$trip->id;
			    			$objsaleitems->from 		 	=$trip->from;
			    			$objsaleitems->to 	 		 	=$trip->to;
			    			$objsaleitems->seat_no			=$rows['seat_no'];
			    			$objsaleitems->device_id		=$device_id;
			    			$objsaleitems->operator			=$operator_id;
			    			$objsaleitems->agent_id 		= $agent_id ? $agent_id : 0;
			    			$objsaleitems->agent_code 		= 0;
			    			$objsaleitems->price			=$trip->price;
			    			$objsaleitems->foreign_price	=$trip->foreign_price;
			    			$objsaleitems->departure_date	=$departure_date;
			    			$objsaleitems->save();
	    				}
	    			}
	    		}
    		/*} catch (Exception $e) {
    			dd($e);
    			$response['message']="Something was wrong!.";
    			$all_canby=false;
    		}*/
	    		
    	}else{
	    	$response['message']="Unfortunately your purchase or booking some tickets have been taken by another customer.";
    		$all_canby=false;
    	}

    	$available_device_id=SaleOrder::whereid($order_auto_id)->pluck('device_id');
    	$check_saleitem_count=SaleItem::whereorder_id($order_auto_id)->wheredevice_id($available_device_id)->count();
    	$response['status'] = 1;
    	if($check_saleitem_count == count($available_seats)){
    		$response['sale_order_no']=$order_auto_id;
	    	$response['device_id']=$available_device_id;
	    	$response['can_buy']=$all_canby;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}else{
    		$sale_order = SaleOrder::whereid($order_auto_id)->first();
    		if($sale_order)
    			$sale_order->delete();
    		$response['sale_order_no']=$order_auto_id;
	    	$response['device_id']="-";
	    	$response['can_buy']=$all_canby;
	    	$response['tickets']=$tickets;
    		return Response::json($response);

    	}
    }

    public function generateAutoID($operator_id, $operator_gp_id){
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
    		$prefix_gp_opr = "0".$operator_gp_id;
    	}elseif($operator_gp_id > 9 && $operator_gp_id <=99){
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
    /**
     * Note: This function was worked that Not Confirm by User;
     */
    public function deleteSaleOrder($id){
    	if(!$id){
    		$response['message']='sale_order_no is null.';
    		return Response::json($response);
    	}
    	$id = MCrypt::decrypt($id);
    	SaleOrder::whereid($id)->delete();
    	SaleItem::whereorder_id($id)->delete();
    	$response['message'] = 'Have been deleted.';
    	return Response::json($response);
    }

    public function postSaleComfirm(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input)
    		return Response::json("Invalid Request!");

    	$sale_order_no	=isset($input->sale_order_no) ? $input->sale_order_no : 0;
    	$reference_no	=isset($input->reference_no) ? $input->reference_no : 0;
    	$agent_id 		=isset($input->agent_id) ? $input->agent_id : 0;
    	$buyer_name		=isset($input->buyer_name) ? $input->buyer_name : null;
    	$agent_name		=isset($input->agent_name) ? $input->agent_name : null;
    	$phone			=isset($input->phone) ? $input->phone : null;
    	$nrc_no			=isset($input->nrc_no) ? $input->nrc_no : null;
    	$tickets 		=isset($input->tickets) ? $input->tickets : null;
    	$cash_credit 	=isset($input->cash_credit) ? $input->cash_credit : null;
    	$nationality	=isset($input->nationality) ? $input->nationality : null;
    	$device_id		=isset($input->device_id) ? $input->device_id : 0;
    	$booking		=isset($input->booking) ? $input->booking : 0;
    	$extra_dest_id  =isset($input->extra_dest_id) ? $input->extra_dest_id : 0;
    	$remark_type 	=isset($input->remark_type) ? $input->remark_type : 0;
		$remark 		=isset($input->remark) ? $input->remark : null;
		$order_date 	=isset($input->order_date) ? $input->order_date : null;

    	if(!$sale_order_no || !$buyer_name  || !$phone){
    		$response['message']="Required fields are sale_order_no, buyer_name, address, phone.";
			return Response::json($response);
	   	}
	   	$tickets = MCrypt::decrypt($tickets);
    	$json_tickets=json_decode($tickets);
    	if(!$json_tickets){
    		$response['message']='Tickets format is wrong.';
    		return Response::json($response);
    	}

    	if($json_tickets){
    		$objsaleorder=SaleOrder::find($sale_order_no);
    		if(!$objsaleorder){
    			$message['status']=0;
    			$message['message']="There is no order.";
    			return Response::json($message);
    		}
    		
    		$objsaleorder->orderdate=$order_date ? $order_date : $this->today;
			$operator_id=SaleItem::whereorder_id($sale_order_no)->pluck('operator');
			if($agent_name && !$agent_id){
	    		$objagent=new Agent();
	    		$check_exiting=Agent::wherename($agent_name)->first();
	    		if(!$check_exiting){
	    			$objagent->name=$agent_name;
	    			$objagent->code_no = 'TEMP'.strtotime($this->getSysDateTime());
	    			$objagent->operator_id=$operator_id;
	    			$objagent->save();
	    			$agent_id=$objagent->id;
	    		}
	    	}
    		
    		$total_discount = 0;
    		$agent_code  	= Agent::whereid($agent_id)->pluck('code_no');
    		foreach ($json_tickets as $rows) {
    			$objsaleitems=SaleItem::whereorder_id($sale_order_no)->whereseat_no($rows->seat_no)->first();
    			$objsaleitems->order_id 		=$sale_order_no;
    			$objsaleitems->seat_no			=$rows->seat_no;
    			$objsaleitems->name				=$rows->name;
    			$objsaleitems->nrc_no			=$rows->nrc_no;
    			$objsaleitems->phone			=$phone;
    			$objsaleitems->ticket_no		=$rows->ticket_no;
    			if($rows->free_ticket == "true"){
    				$objsaleitems->free_ticket		= 1;
    				$objsaleitems->free_ticket_remark = $rows->free_ticket_remark;
    			}
    			$objsaleitems->discount 			=$rows->discount ? $rows->discount : 0;
				$objsaleitems->agent_id 			=$agent_id; 
				$objsaleitems->agent_code 			=$agent_code;
				/**
				 * Check For Extra Destination Trip;
				 */
				if($extra_dest_id != 0){
					$extra_destination 					= ExtraDestination::whereid($extra_dest_id)->first();
					$objsaleitems->extra_destination_id = $extra_dest_id;
					$objsaleitems->price 				= $extra_destination->local_price;
					$objsaleitems->foreign_price 		= $extra_destination->foreign_price;
					$objsaleitems->extra_city_id		= $extra_destination->city_id;
				} 
				/**
				 * Check For Agent Commission;
				 */	
				$objagent=AgentCommission::whereagent_id($agent_id)
    									->wheretrip_id($objsaleitems->trip_id)
    									->where('start_date','<=',$objsaleorder->orderdate)
                                        ->where('end_date','>=',$objsaleorder->orderdate)
                                        ->first();
	    		if($objagent){
	    			$commission_type = CommissionType::whereid($objagent->commission_id)->pluck('name');

	    			if($commission_type=='Fixed'){
	    				$commission=$objagent->commission;
	    			}else{
	    				$commission=($objsaleitems->price * $objagent->commission ) / 100;
	    			}
	    		}else{
	    			$commission = Trip::whereid($objsaleitems->trip_id)->pluck('commission');
	    		}		
	    		
	    		$objsaleitems->commission = $commission;
    			$objsaleitems->update();
    			$total_discount += $rows->discount ? $rows->discount : 0;
    			
    		}

    		if($nationality != null && $nationality == 'foreign'){
    			$total_amount=SaleItem::whereorder_id($sale_order_no)->sum('foreign_price');
    		}else{
    			$total_amount=SaleItem::whereorder_id($sale_order_no)->sum('price');
    		}

    		$objsaleorder->reference_no	=$reference_no;
    		$objsaleorder->agent_id 	=$agent_id;
    		$objsaleorder->agent_code 	=$agent_code;
    		$objsaleorder->name 		=$buyer_name;
    		$objsaleorder->nrc_no 		=$nrc_no;
    		$objsaleorder->phone 		=$phone;
    		if($total_amount > 0){
	    		$objsaleorder->cash_credit=$cash_credit;
    		}
    		$objsaleorder->total_amount	=$total_amount;
    		$objsaleorder->nationality 	=$nationality == null ? 'local' : $nationality;

    		$total_commission=0;
    		$trip_id= SaleItem::whereorder_id($sale_order_no)->groupBy('trip_id')->pluck('trip_id');
    		$objagent=AgentCommission::whereagent_id($agent_id)
    									->wheretrip_id($trip_id)
    									->where('start_date','<=',$objsaleorder->orderdate)
                                        ->where('end_date','>=',$objsaleorder->orderdate)
                                        ->first();
    		$total_ticket=count($json_tickets);
    		if($objagent){
    			$commission=CommissionType::whereid($objagent->commission_id)->pluck('name');

    			if($commission=='Fixed'){
    				$commission_per_ticket=$objagent->commission;
    				$total_commission=$commission_per_ticket * $total_ticket;
    			}else{
    				$commission_per_ticket=$objagent->commission;
    				$total_commission=($total_amount * $commission_per_ticket ) / 100;
    			}
    		}else{
    			$total_commission = Trip::whereid($trip_id)->pluck('commission') * $total_ticket;
    		}
    		$objsaleorder->agent_commission=$total_commission;
    		$objsaleorder->booking = $booking;
    		$objsaleorder->remark_type=$remark_type;
    		$objsaleorder->remark 	  =$remark;
    		$objsaleorder->update();

    		//Payment Transaction
    		if($booking == 0){
    			$total_amount 				= $objsaleorder->total_amount - $objsaleorder->agent_commission;
    			$total_amount 				= $total_amount - $total_discount;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->agentgroup_id 	=Agent::whereid($objsaleorder->agent_id)->pluck('agentgroup_id');
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$today 										= $this->today;
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
	    		$saleOrder->cash_credit = 2;
	    		$saleOrder->update();
    		}
    	}
    	$response['device_id']=$device_id;
		$response['status']=true;
    	$response['message']='Successfully your order ticket.';
    	return Response::json($response);
    }

    public function getCreditSale(){
    	$response=array();
    	$operator_id=Input::get('operator_id');
    	$agent_id 	=Input::get('agent_id');
    	$order_id 	=Input::get('order_id');
    	$limit=Input::get('limit') ? Input::get('limit') : 8;
    	$offset=Input::get('offset') ? Input::get('offset') : 1;
    	$offset =($offset-1) * $limit;
    	if($order_id && !$agent_id){
    		$response=SaleOrder::whereid($order_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)
					->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}
    	else{
    		if($operator_id && $agent_id){
	    		$response=SaleOrder::whereoperator_id($operator_id)->whereagent_id($agent_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}elseif($operator_id && !$agent_id){
	    		$response=SaleOrder::whereoperator_id($operator_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}elseif(!$operator_id && $agent_id){
	    		$response=SaleOrder::whereagent_id($agent_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}else{
	    		$response=SaleOrder::wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}
    	}
    	// return Response::json($response);
    	if(!$operator_id && !$agent_id){
    		$response=array();
    	}
    	if($response){
    		$i=0;
    		foreach ($response as $row) {
    			$response[$i]=$row;
    			$trip='-';
    			$price=0;
    			$amount=0;
    			$tickets=count($row->saleitems);
    			if(count($row->saleitems)>0){
    				$commissiontype='-';
    				$commission='-';
    				$topayprice=0;
    				$toreduceamountperticket=0;
    				$objtrip=Trip::whereid($row->saleitems[0]->trip_id)->first();
    				if($objtrip){
    					$trip_id=$objtrip->id;
    					$agentcommission=AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
    					if($agentcommission){
    						if($agentcommission->commission_id==2){
    							$commissiontype='fixed';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=$agentcommission->commission;
    						}else{
    							$commissiontype='percentage';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=($agentcommission->commission/100) * $objtrip->price;
    						}
    					}else{
    						if($row->agent_id > 0){
    							$commissiontype='trip';
    							$commission=$objtrip->commission;
    							$toreduceamountperticket=$objtrip->commission;
    						}
    					}
    					$from=City::whereid($objtrip->from)->pluck('name');
    					$to=City::whereid($objtrip->to)->pluck('name');
    					$trip=$from.'-'.$to;
    					if($row->nationality == 'foreign' && $objtrip->foreign_price > 0){
    						$price=$objtrip->foreign_price;
    					}else{
    						$price=$objtrip->price;
    					}
    					
    					$amount= $price * $tickets;
    				}
    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
    				$response[$i]['customer']=$objorderinfo->name;
    				$response[$i]['phone']=$objorderinfo->phone;
    			}else{
    				$response[$i]['customer']='-';
    				$response[$i]['phone']='-';
    			}
				$operator=Operator::whereid($row->operator_id)->pluck('name');
				$agent=Agent::whereid($row->agent_id)->pluck('name');
    			$response[$i]['operator']=$operator;
    			$response[$i]['agent']=$agent;
    			$response[$i]['trip']=$trip;
    			$response[$i]['total_ticket']=$tickets;
    			$response[$i]['price']=$price;
    			$response[$i]['commissiontype']=$commissiontype;
    			$response[$i]['commission']= $commission;
    			$response[$i]['to_pay_amount']= $amount - ($toreduceamountperticket * $tickets);
    			$response[$i]['amount']=$amount;
    			$i++;
    		}
    	}
    	return Response::json($response);
    }

    public function getSaleOrder(){
    	$response 		=array();
    	$input 			=json_decode(MCrypt::decrypt(Input::get('param')));
    	$operator_id 	=isset($input->operator_id) ? $input->operator_id : null;
    	$departure_date =isset($input->departure_date) ? $input->departure_date : null;
    	$from 	 		=isset($input->from) ? $input->from : null;
    	$to 	 		=isset($input->to) ? $input->to : null;
    	$time 			=isset($input->time) ? $input->time : null;
    	$book_code 		=isset($input->book_code) ? $input->book_code : null;
    	if(!$operator_id){
    		$response['status']=0;
    		$response['message']='Required Any Parameter.';
    		return Response::json($response, 400);
    	}
    	if($departure_date){
    		if($from && $to){
    			$trip_id = Trip::wherefrom($from)->whereto($to)->where('time','LIKE', $time.'%')->lists('id');
    			if($trip_id){
    				$response=SaleOrder::with(array('saleitems'))
    				->whereHas('saleitems',  function($query) use ($trip_id) {
										$query->wherein('trip_id', $trip_id);
										})
    				->wherebooking(1)
    				->wheredeparture_date($departure_date)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
    			}
    			
    		}else{
    			$response=SaleOrder::with(array('saleitems'))
    				->wherebooking(1)
    				->wheredeparture_date($departure_date)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
    		}
    		
		}else if($book_code){
			$response=SaleOrder::with(array('saleitems'))
					->whereid($book_code)
    				->wherebooking(1)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
		}else{
			$response=SaleOrder::with(array('saleitems'))
    				->wherebooking(1)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
		}

    	
    	if($response){
    		$i=0;
    		foreach ($response as $row) {
    			$response[$i]=$row;
    			$trip='-';
    			$time='-';
    			$date='-';
    			$classes='-';
    			$price=0;
    			$amount=0;
    			$tickets=count($row->saleitems);
    			if(count($row->saleitems)>0){
    				$commissiontype='-';
    				$commission='-';
    				$topayprice=0;
    				$toreduceamountperticket=0;
    				$objTrip=Trip::whereid($row->saleitems[0]->trip_id)->first();
    				if($objTrip){
    					$trip_id   = $objTrip->id;
    					$trip_date = $row->saleitems[0]->departure_date;
    					$agentcommission=AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
    					if($agentcommission){
    						if($agentcommission->commission_id==2){
    							$commissiontype='fixed';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=$agentcommission->commission;
    						}else{
    							$commissiontype='percentage';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=($agentcommission->commission/100) * $objTrip->price;
    						}
    					}else{
    						if($row->agent_id > 0){
    							$commissiontype='trip';
    							$commission=$objTrip->commission;
    							$toreduceamountperticket=$objTrip->commission;
    						}
    					}
    					$from=City::whereid($objTrip->from)->pluck('name');
    					$to=City::whereid($objTrip->to)->pluck('name');
    					$trip  		=$from.'-'.$to;
    					$time 		=$objTrip->time;
    					$date 		=$trip_date;
    					$classes 	=Classes::whereid($objTrip->class_id)->pluck('name');
    					if($row->nationality == 'foreign' && $objTrip->foreign_price > 0){
    						$price=$objTrip->foreign_price;
    					}else{
    						$price=$objTrip->price;
    					}
    					
    					$amount= $price * $tickets;
    				}
    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
    				$response[$i]['customer']=$objorderinfo->name;
    				$response[$i]['phone']=$objorderinfo->phone;
    			}else{
    				$response[$i]['customer']='-';
    				$response[$i]['phone']='-';
    			}
				$operator=Operator::whereid($row->operator_id)->pluck('name');
				$agent=Agent::whereid($row->agent_id)->pluck('name');
				$response[$i]['orderdate'] =date('d/m/Y h:i:s a',strtotime($row->orderdate.' '.date('h:i:s a',strtotime($row->created_at) + ((60*60) * 6.5))));
    			$response[$i]['operator']=$operator;
    			$response[$i]['agent']=$agent;
    			$response[$i]['trip']=$trip;
    			$response[$i]['time']=$time;
    			$response[$i]['classes']=$classes;
    			$response[$i]['date']=$date;
    			$response[$i]['total_ticket']=$tickets;
    			$response[$i]['price']=$price;
    			$response[$i]['commissiontype']=$commissiontype;
    			$response[$i]['commission']= $commission;
    			$response[$i]['to_pay_amount']= $amount - ($toreduceamountperticket * $tickets);
    			$response[$i]['amount']=$amount;
    			$i++;
    		}
    	}
    	return Response::json($response);
    }

    public function postDeleteCreditSaleOrderNo($order_id){
    	$order_id 	= MCrypt::decrypt($order_id);
    	$input 		= json_decode(MCrypt::decrypt(Input::get('param')));
    	$user_id	= isset($input->user_id) ? $input->user_id : null;
    	$objorder=SaleOrder::whereid($order_id)->first();
    	if($objorder){
    		$check = DeleteSaleOrder::whereid($order_id)->first();
			if(!$check){
				$objorder->remark = 'Booking Not Confirmed by User';
				if($user_id)
					$objorder->user_id = $user_id;
				$deletedSaleOrder = DeleteSaleOrder::create($objorder->toarray());
				
			}
			SaleOrder::whereid($order_id)->delete();
			
			$saleItem = SaleItem::whereorder_id($order_id)->get();
			
			if($saleItem){
				foreach ($saleItem as $rows) {
					$check = DeleteSaleItem::whereid($rows->id)->first();
					if(!$check){
						$deletedSaleitem = DeleteSaleItem::create($rows->toarray());
					}
					SaleItem::whereorder_id($order_id)->delete();
				}
			}

			$response['status']=1;
    		$response['message']='Successfully delete credit order.';
    		
    	}else{
    		$response['status']=0;
    		$response['message']='There is no credit order with this order no .';	 
    	}
    	return Response::json($response);
    	
    }

    public function postCancelCreditSaleTicket(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	$saleitem_id_list=isset($input->saleitem_id_list) ? $input->saleitem_id_list : null;
    	$json_ticket_ids=explode(',', $saleitem_id_list);
    	if(!$json_ticket_ids){
    		$response['status']=0;
    		$response['message']='Parameter format is wrong.';
    		return Response::json($response);
    	}
    	foreach ($json_ticket_ids as $saleitem_id) {
    		$objsaleitem=SaleItem::whereid($saleitem_id)->delete();
    	}
    	$response['status']=1;
		$response['message']='Successfully delete sale ticket for credit order.';
		return Response::json($response);
    }

    public function getCitiesByagentId(){
    	$cities=array();
		$trips = Trip::groupBy('to')->orderBy('from','asc')->get();
		foreach ($trips as $rows) {
			$objfromcities				=City::whereid($rows->from)->first();
			$tempfrom['from']			=$objfromcities->id;
			$tempfrom['from_city']		=$objfromcities->name;
			$objtocities				=City::whereid($rows->to)->first();
			$tempto['to']				=$objtocities->id;
			$tempto['to_city']			=$objtocities->name;
			$from[]						=$tempfrom;
			$to[]						=$tempto;
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

    	return Response::json($cities);
    }

    public function getCitiesByoperatorId(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
    	$operator_id = isset($input->operator_id) ? $input->operator_id : null;
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return Response::json($response);
    	}
    	$cities=array();
		$trips = Trip::whereoperator_id($operator_id)->groupBy('to')->orderBy('from','asc')->get();
		foreach ($trips as $rows) {
			$objfromcities				=City::whereid($rows->from)->first();
			$tempfrom['from']			=$objfromcities->id;
			$tempfrom['from_city']		=$objfromcities->name;
			$objtocities				=City::whereid($rows->to)->first();
			$tempto['to']				=$objtocities->id;
			$tempto['to_city']			=$objtocities->name;
			$from[]						=$tempfrom;
			$to[]						=$tempto;
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

    	return Response::json($cities);
    }
    public function getTimesByagentId(){
    	$trips = Trip::groupBy('time')->orderBy('time', 'asc')->get();
		$times = array();
		foreach ($trips as $rows) {
			$subtime 				= substr($rows->time, 0, 8);
			$time 				 	= $rows->time;
			$time 					= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
	    	$list['time_unit'] 		= strtotime(substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
			$list['time']			= $rows->time;
			$times[]				=$list;
		}
		$times = $this->msort($times, array("time_unit"), $sort_flags=SORT_REGULAR, $order=SORT_ASC);
    	return Response::json($times);
    }

    public function getTimesByOperatorId(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
    	$operator_id = isset($input->operator_id) ? $input->operator_id : null;
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return Response::json($response);
    	}

    	$trips = Trip::whereoperator_id($operator_id)->groupBy('time')->orderBy('time', 'asc')->get();
		$times = array();
		foreach ($trips as $rows) {
			$subtime 				= substr($rows->time, 0, 8);
			$time 				 	= $rows->time;
			$time 					= $subtime == '12:00 AM' ? '12:00 PM'.substr($time, 8,strlen($time) - 8) : $time;
	    	$list['time_unit'] 		= strtotime(substr($time, 0, 8)) + strlen(substr($time, 9,strlen($time) - 9));
			$list['time']			= $rows->time;
			$times[]				=$list;
		}
		$times = $this->msort($times, array("time_unit"), $sort_flags=SORT_REGULAR, $order=SORT_ASC);
    	return Response::json($times);
    }

    public function getTrip(){
    	$response=array();
    	$trip=array();
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input)
    		return Response::json('Invalid Request!', 400);
    	$operator_id= isset($input->operator_id) ? $input->operator_id : 0;
    	if($operator_id == 0)
    		return Response::json('Invalid Operator Id!', 400);
    	if($operator_id !=''){
    		$objtrip =Trip::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}else{
    		$objtrip =Trip::groupBy('from','to')->get(array('from','to'));
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
    	$response=$trip;
    	return Response::json($response);
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

    /*
     * POST API
     * @/report/customer/update
     */
    public function postCustomerInfoUpdate(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
    	$trip_id		=isset($input->trip_id) ? $input->trip_id : null;
    	$departure_date	=isset($input->date) ? $input->date : null;
    	$seat_no		=isset($input->seat_no) ? $input->seat_no : null;
    	$customer_name	=isset($input->customer_name) ? $input->customer_name : null;
    	$nrc_no			=isset($input->nrc_no) ? $input->nrc_no : null;
    	$phone			=isset($input->phone) ? $input->phone : null;
    	$ticket_no		=isset($input->ticket_no) ? $input->ticket_no : null;

    	if(!$trip_id  || !$departure_date || !$seat_no || !$customer_name){
    		$response['message']= "Request parameters are required.";
    		return Response::json($response, 400);
    	}

    	$objsaleitem =SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->whereseat_no($seat_no)->first();
    	if($objsaleitem){
    		$objsaleitem->name=$customer_name;
    		$objsaleitem->phone=$phone !=null ? $phone : $objsaleitem['phone'];
    		$objsaleitem->nrc_no=$nrc_no !=null ? $nrc_no : $objsaleitem['nrc_no'] ;
    		$objsaleitem->ticket_no=$ticket_no !=null ? $ticket_no : $objsaleitem['ticket_no'];
    		$objsaleitem->update();
    	}
    	$response['message']="Successfully Update Customere Information.";
    	return Response::json($response);
    }

    /*
     * Updated by SMK
     */
    public function getSaleList(){
    	$response=array();
    	$operator_id=Input::get('operator_id');
    	$agent_id =Input::get('agent_id');
    	$from=Input::get('from');
    	$to=Input::get('to');
    	$departure_date=Input::get('departure_date');
    	$time=Input::get('time');
    	$order_s_date=Input::get('order_s_date');
    	$order_e_date=Input::get('order_e_date');
    	$cash_credit=Input::get('credit_cash');
    	$objsalelist=array();
    	// Check validation fields
    	if($operator_id == null){
    		$response['status'] 	= 0;
    		$response['message'] 	= "Required for operator id";
    		return Response::json($response, 400);
    	}
    	    	
    	if($from && $to){
	    	$trip_ids = Trip::whereoperator_id($operator_id)
	    						->where('from','=',$from)
	    						->where('to','=',$to)
	    						->where('time','LIKE',$time.'%')
	    						->lists('id');
    		
    	}else{
    		$trip_ids = Trip::whereoperator_id($operator_id)
	    						->where('time','LIKE',$time.'%')
	    						->lists('id');
    	}

    	if($trip_ids && $order_s_date && $order_e_date && $agent_id && $cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->where('orderdate','>=',$order_s_date)
    							->where('orderdate','<=',$order_e_date)
    							->whereagent_id($agent_id)
    							->wherecash_credit($cash_credit)
    							->get();


    	}elseif($trip_ids && $order_s_date && $order_e_date && !$agent_id && $cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->where('orderdate','>=',$order_s_date)
    							->where('orderdate','<=',$order_e_date)
    							->wherecash_credit($cash_credit)
    							->get();


    	}elseif($trip_ids && $order_s_date && $order_e_date && $agent_id && !$cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->where('orderdate','>=',$order_s_date)
    							->where('orderdate','<=',$order_e_date)
    							->whereagent_id($agent_id)
    							->get();


    	}elseif($trip_ids && $order_s_date && $order_e_date && !$agent_id && !$cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->where('orderdate','>=',$order_s_date)
    							->where('orderdate','<=',$order_e_date)
    							->get();


    	}elseif($trip_ids && !$order_s_date && !$order_e_date && $agent_id && $cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->whereagent_id($agent_id)
    							->wherecash_credit($cash_credit)
    							->get();
    	}elseif($trip_ids && !$order_s_date && !$order_e_date && $agent_id && !$cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->whereagent_id($agent_id)
    							->get();
    	}elseif($trip_ids && !$order_s_date && !$order_e_date && !$agent_id && $cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->wherecash_credit($cash_credit)
    							->get();
    	}elseif($trip_ids && !$order_s_date && !$order_e_date && !$agent_id && !$cash_credit){
    		$objsalelist = SaleOrder::with(array('agent','operator','saleitems'))
    								->whereHas('saleitems',  function($query) use ($trip_ids, $departure_date) {
										$query->wherein('trip_id', $trip_ids)
												->where('departure_date','LIKE',$departure_date.'%');
										})
    							->get();

    	}


    	
    	if($objsalelist){
    		$i=0;
    		foreach ($objsalelist as $row) {
    			$temp['id']=$row->id;
    			$temp['orderdate']=$row->orderdate;
    			$temp['agent_id']=$row->agent_id;
    			$temp['operator_id']=$row->operator_id;
    			$temp['customer']=$row->name;
    			$temp['phone']=$row->phone;
    			$temp['operator']=$row->operator ? $row->operator->name : '-';
    			$temp['agent']=$row->agent ? $row->agent->name : '-';
    			
    			$trip = $departure_date = $departure_time = $class = '-';
    			$price = $total_ticket = $amount =0;
    			$agent_commission=0;
    			if(count($row->saleitems)>0){

    				$objtrip=Trip::whereid($row->saleitems[0]->trip_id)->first();
    				if($objtrip){
    					$trip_id=$objtrip->id;
    					if($row->agent_id)
    					$agent_commission=AgentCommission::wheretrip_id($trip_id)->whereagent_id($row->agent_id)->pluck('commission');
    					
    					$from=City::whereid($objtrip->from)->pluck('name');
    					$to=City::whereid($objtrip->to)->pluck('name');
    					$departure_date=$row->saleitems[0]->departure_date;
    					$departure_time=$objtrip->time;
    					$class=Classes::whereid($objtrip->class_id)->pluck('name');
    					$trip=$from.'-'.$to;
    					if($row->agent_id > 0){
    						$price = $agent_commission > 0 ? $objtrip->price - $agent_commission : $objtrip->price - $objtrip->commission;
    					}else{
    						$price = $objtrip->price;
    					}
    					$commission = $objtrip->commission;
    					$total_ticket=count($row->saleitems);
    					$amount=$price * $total_ticket;
    				}
    			}
    			$temp['trip']=$trip;
    			$temp['departure_date']=$departure_date;
    			$temp['departure_time']=$departure_time;
    			$temp['class']=$class;
    			$temp['total_ticket']=$total_ticket;
    			$temp['price']=$price;
    			$temp['commission']= $agent_commission > 0 ? $agent_commission : $commission;
    			$temp['amount']= $amount;
    			$temp['cash_credit']=$row->cash_credit;
    			$temp['saleitems']=$row->saleitems->toarray();
    			$objsalelist[$i]=$temp;
    			$i++;
    		}
    	}

    	return Response::json(count($objsalelist));
    }

	
	public function populartrip(){
    	$operator_id 	= Input::get('operator_id');
    	$agent_id 		= Input::get('agent_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	
    	$datetime1 = new DateTime($s_date);
		$datetime2 = new DateTime($e_date);
		$interval = $datetime1->diff($datetime2);
		$days = $interval->format('%a');

    	if(!$operator_id || !$s_date || !$e_date){
    		$message['status'] 	= 0;
    		$message['message']	= "Required for any parameter.";
    		return Response::json($message, 400);
    	}
    	$trip_id = array();
    	if($s_date && $e_date && !$agent_id){
    		$trip_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->selectRaw('trip_id, count(*) as count, SUM(price) as total')
    								->groupBy('to')
    								->orderBy('count','desc')
    								->get();
    	}
    	if($s_date && $e_date && $agent_id){
    		$trip_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->whereagent_id($agent_id)
    								->selectRaw('trip_id, count(*) as count, SUM(price) as total')
    								->groupBy('to')
    								->orderBy('count','desc')
    								->get();
    	}
    	if($trip_id){
    		$lists = array();
    		foreach ($trip_id as $rows) {
    			$trips 			= Trip::whereid($rows->trip_id)->first();

    			if($trips){
    				$list['id'] 				= $trips->id;
    				$list['from']				= $trips->from;
    				$list['to']					= $trips->to;
	    			$list['trip'] 				= City::whereid($trips->from)->pluck('name') .' - '.City::whereid($trips->to)->pluck('name');
	    			$list['classes']			= Classes::whereid($trips->class_id)->pluck('name');
	    			$total_seat 				= SeatInfo::whereseat_plan_id($trips->seat_plan_id)->wherestatus(1)->count();
	    			$list['percentage'] 		= round(($rows->count / ($total_seat * $days)) * 100) ;
	    			$list['sold_total_seat'] 	= $rows->count;
	    			$list['total_seat']			= $total_seat * $days;
	    			$list['total_amount']		= $rows->total;
	    			$lists[] 					= $list;
    			}
    		}
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function populartriptime(){
    	$operator_id 	= Input::get('operator_id');
    	$agent_id 		= Input::get('agent_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	$from 			= Input::get('from');
    	$to 			= Input::get('to');
    	
    	$datetime1 = new DateTime($s_date);
		$datetime2 = new DateTime($e_date);
		$interval = $datetime1->diff($datetime2);
		$days = $interval->format('%a');

    	if(!$operator_id || !$s_date || !$e_date){
    		$message['status'] 	= 0;
    		$message['message']	= "Required for any parameter.";
    		return Response::json($message, 400);
    	}
    	$trip_id = array();
    	if($s_date && $e_date && !$agent_id){
    		$trip_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->wherefrom($from)
    								->whereto($to)
    								->selectRaw('trip_id, count(*) as count, SUM(price) as total')
    								->groupBy('trip_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}
    	if($s_date && $e_date && $agent_id){
    		$trip_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->wherefrom($from)
    								->whereto($to)
    								->whereagent_id($agent_id)
    								->selectRaw('trip_id, count(*) as count, SUM(price) as total')
    								->groupBy('trip_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}
    	if($trip_id){
    		$lists = array();
    		foreach ($trip_id as $rows) {
    			$trips 			= Trip::whereid($rows->trip_id)->first();

    			if($trips){
    				$list['id'] 				= $trips->id;
	    			$list['trip'] 				= City::whereid($trips->from)->pluck('name') .' - '.City::whereid($trips->to)->pluck('name');
	    			$list['time'] 				= $trips->time;
	    			$list['classes']			= Classes::whereid($trips->class_id)->pluck('name');
	    			$total_seat 				= SeatInfo::whereseat_plan_id($trips->seat_plan_id)->wherestatus(1)->count();
	    			$list['percentage'] 		= round(($rows->count / ($total_seat * $days)) * 100) ;
	    			$list['sold_total_seat'] 	= $rows->count;
	    			$list['total_seat']			= $total_seat * $days;
	    			$list['total_amount']       = $rows->total;
	    			$lists[] 					= $list;
    			}
    		}
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function popularagent(){
    	$operator_id 	= Input::get('operator_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	
    	$datetime1 = new DateTime($s_date);
		$datetime2 = new DateTime($e_date);
		$interval = $datetime1->diff($datetime2);
		$days = $interval->format('%a');

    	if(!$operator_id || !$s_date || !$e_date){
    		$message['status'] 	= 0;
    		$message['message']	= "Required for any parameter.";
    		return Response::json($message, 400);
    	}
    	$trip_id = array();
    	if($s_date && $e_date){
    		$agent_id = SaleOrder::whereoperator_id($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->selectRaw('agent_id, count(*) as count, SUM(total_amount) as total')->groupBy('agent_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}

    	if($agent_id){
    		$lists = array();
    		foreach ($agent_id as $rows) {
    			$agent 			= Agent::whereid($rows->agent_id)->first();

    			if($agent){
    				$list['id'] 					= $agent->id;
	    			$list['name'] 					= $agent->name;
	    			$list['total_amount'] 			= $rows->total;
	    			$list['count']					= $rows->count;
	    			$list['purchased_total_seat']	= SaleItem::whereagent_id($agent->id)
	    															->where('departure_date','>=',$s_date)
	    															->where('departure_date','<=',$e_date)
	    															->count();
	    			$list['label_name']				= TargetLabel::where('start_amount',"<",$rows->total)->where('end_amount','>',$rows->total)->pluck('name');
	    			$list['label_color']			= TargetLabel::where('start_amount',"<",$rows->total)->where('end_amount','>',$rows->total)->pluck('color');
	    			$lists[] 						= $list;
    			}
    		}
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function analytisclasses(){
    	$operator_id 	= Input::get('operator_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	$agent_id 		= Input::get('agent_id');
    	
    	$datetime1 = new DateTime($s_date);
		$datetime2 = new DateTime($e_date);
		$interval = $datetime1->diff($datetime2);
		$days = $interval->format('%a');

    	if(!$operator_id || !$s_date || !$e_date){
    		$message['status'] 	= 0;
    		$message['message']	= "Required for any parameter.";
    		return Response::json($message, 400);
    	}
    	$trip_id = array();
    	if($s_date && $e_date && !$agent_id){
    		$class_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->selectRaw('class_id, count(*) as count, SUM(price) as total')
    								->groupBy('class_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}

    	if($s_date && $e_date && $agent_id){
    		$class_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->whereagent_id($agent_id)
    								->selectRaw('class_id, count(*) as count, SUM(price) as total')
    								->groupBy('class_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}

    	if($class_id){
    		$lists = array();
    		foreach ($class_id as $rows) {
    			$classes 			= Classes::whereid($rows->class_id)->first();

    			if($classes){
    				$list['id'] 					= $classes->id;
	    			$list['name'] 					= $classes->name;
	    			$list['total_amount'] 			= $rows->total;
	    			$list['count']					= $rows->count;
	    			$list['purchased_total_seat']	= SaleItem::whereclass_id($classes->id)
	    															->where('departure_date','>=',$s_date)
	    															->where('departure_date','<=',$e_date)
	    															->count();
	    			$lists[] 						= $list;
    			}
    		}
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function postCloseSeatList(){
    	$trip_id 		= Input::get('trip_id');
    	$operatorgroup_id 	= Input::get('operatorgroup_id');
    	$seat_plan_id 		= Input::get('seat_plan_id');
    	$seat_lists 		= Input::get('seat_lists');
    	if(!$trip_id || !$operatorgroup_id || !$seat_plan_id || !$seat_lists){
    		$message['status'] 	= 0;
    		$message['message']	= "Required for any parameter.";
    		return Response::json($message, 400);
    	}

    	$close_seatinfo = CloseSeatInfo::wheretrip_id($trip_id)
    								->whereoperatorgroup_id($operatorgroup_id)
    								->whereseat_plan_id($seat_plan_id)
    								->first();

    	if($close_seatinfo){
    		$close_seatinfo->seat_lists = $seat_lists;
    		$close_seatinfo->update();
    	}else{
    		$close_seatinfo 					= new CloseSeatInfo();
    		$close_seatinfo->trip_id 			= $trip_id;
    		$close_seatinfo->operatorgroup_id 	= $operatorgroup_id;
    		$close_seatinfo->seat_plan_id		= $seat_plan_id;
    		$close_seatinfo->seat_lists 		= $seat_lists;
    		$close_seatinfo->save();
    	}

    	$message['status'] 	= 1;
    	$message['message']	= "Successfully saved!.";
    	return Response::json($message);
    }

    public function getOperatorGroup(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
    	$operator_id = isset($input->operator_id) ? $input->operator_id : null;
    	if($operator_id)
    		$operatorgroup = OperatorGroup::whereoperator_id($operator_id)->get();
    	else
    		$operatorgroup = OperatorGroup::all();
    	if($operatorgroup){

    		$i = 0;
    		foreach ($operatorgroup as $rows) {
    			$operatorgroup[$i]['username'] = User::whereid($rows->user_id)->pluck('name');
    			$operatorgroup[$i]['operatorname'] = Operator::whereid($rows->operator_id)->pluck('name');
    			$i++;
    		}
    		return Response::json($operatorgroup);
    	}else{
    		return Response::json(array());
    	}

    }

    public function getExtraDestination($trip_id){
    	$trip_id = MCrypt::decrypt($trip_id);
    	if(!$trip_id){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
    	$extra_destination = ExtraDestination::wheretrip_id($trip_id)->get();
    	$i = 0;
    	foreach ($extra_destination as $rows) {
    		$extra_destination[$i]['city_name'] = City::whereid($rows->city_id)->pluck('name');
    		$i++;
    	}

    	return Response::json($extra_destination);
    }

    public function getNotiBooking(){
    	$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
    	$today = isset($input->date) ? $input->date : $this->getDate();
    	$booking_count = SaleOrder::where('departure_date','=',$today)->wherebooking(1)->count();
   		return Response::json($booking_count);
    }

    /**
	 * Delete by ticket_id ;
	 *
	 * 
	 * @return Response
	 */
	
	//Delete only each order records.
	public function ticketdelete(){
		$input = json_decode(MCrypt::decrypt(Input::get('param')));
    	if(!$input){
    		$response['message']="Invalid Request!";
			return Response::json($response);
    	}
		$trip_id 			= isset($input->trip_id) ? $input->trip_id : null;
		$departure_date 	= isset($input->departure_date) ? $input->departure_date : null;
		$seat_no 			= isset($input->seat_no) ? $input->seat_no : null;
		$user_id 			= isset($input->user_id) ? $input->user_id : null;

		$objsaleitem=SaleItem::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->whereseat_no($seat_no)->first();
		if($objsaleitem){
			$orderid=$objsaleitem->order_id;
			$objsaleorder=SaleOrder::whereid($orderid)->first();
			
			$objagent_commission=AgentCommission::whereagent_id($objsaleitem->agent_id)->wheretrip_id($objsaleitem->trip_id)->first();
			// calculate agent commission
				$commission=0;
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
				if($commission==0){
					$tripcommission=Trip::whereid($objsaleitem->trip_id)->pluck('commission');
					$commission= $tripcommission;
				}
			//end calculate agent commission

			$price 		= $objsaleitem->price -$commission;
			$deleted 	= $this->del_orderticket_history_trans($objsaleitem,$objsaleorder,$price,$user_id);

			$objsaleitem->delete();

			$saleitems=SaleItem::whereorder_id($orderid)->count();
			if($saleitems ==0){
				$objorder = SaleOrder::whereid($orderid)->first();
				$check = DeleteSaleOrder::whereid($orderid)->first();
				if(!$check){
					$objorder->remark = 'Deleted by User';
					if($user_id)
						$objorder->user_id = $user_id;
					DeleteSaleOrder::create($objorder->toarray());
					SaleOrder::whereid($orderid)->delete();
				}
			}
			$response['message']="Successfully delete ticket.";
			return Response::json($response);
			
		}
	}

	public function del_orderticket_history_trans($objsaleitem, $objsaleorder, $price, $user_id){
		if($objsaleorder){
			$id=$objsaleorder->id;
			try {
				$chksaleorder=DeleteSaleOrder::whereid($id)->first();
				if($chksaleorder){
				}else{
					//$objsaleorder->user_id 	=	$user_id;
					//$objsaleorder->remark 	=	'Direct Deleted From Seat Plan';
					DeleteSaleOrder::create($objsaleorder->toarray());
				}
				
				$total_amount 				= $price;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$objdepositpayment_trans->pay_date			= $this->getDate();
	    		$objdepositpayment_trans->order_ids			= '["'.$id.'"]';

	    		$objdepositpayment_trans->payment 			= $total_amount;
	    		$agentdeposit 								= AgentDeposit::whereagent_id($objsaleorder->agent_id)->whereoperator_id($objsaleorder->operator_id)->orderBy('id','desc')->first();
	    		if($agentdeposit){
	    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
	    			$objdepositpayment_trans->balance 		= $agentdeposit->balance + $total_amount;
	    		}else{
	    			$objdepositpayment_trans->deposit 		= 0;
	    			$objdepositpayment_trans->balance 		= 0 + $total_amount;
	    		}  		
	    		$objdepositpayment_trans->debit 			= 0;
	    		$objdepositpayment_trans->deleted_flag 		= 1;
	    		$objdepositpayment_trans->save();

			} catch (Exception $e) {
				//dd($e);
			}
		}

		if($objsaleitem){
			try {
				DeleteSaleItem::create($objsaleitem->toarray());
			} catch (Exception $e) {
				//dd($e);
			}
		}
	}

	public function getTripInfo($id){
		$objtrip=Trip::whereid($id)->first();
		$trip=array();
		if($objtrip){
			$tmp['id']=$id;
			$tmp['operator_id']=$objtrip->operator_id;
			$tmp['from']=$objtrip->from;
			$tmp['to']=$objtrip->to;
			$tmp['operator']=Operator::whereid($objtrip->operator_id)->pluck('name');
			$tmp['from_city']=City::whereid($objtrip->from)->pluck('name');
			$tmp['to_city']=City::whereid($objtrip->to)->pluck('name');
			$tmp['class_id']=$objtrip->class_id;
			$tmp['classes']=Classes::whereid($objtrip->class_id)->pluck('name');
			$tmp['available_day']=$objtrip->available_day;
			$tmp['time']=$objtrip->time;
			$tmp['price']=$objtrip->price;
			$tmp['foreign_price']=$objtrip->foreign_price;
			$tmp['seat_plan_id']=$objtrip->seat_plan_id;
			$objseat_plan 		=SeatingPlan::whereid($objtrip->seat_plan_id)->first();
			$tmp['name'] 		=$objseat_plan->name;
			$seat_layout['row'] 		=$objseat_plan->row;
			$seat_layout['column'] 		=$objseat_plan->column;
			$objseatinfo=SeatInfo::whereseat_plan_id($objtrip->seat_plan_id)->get();
			$seat_info=array();
			if($objseatinfo){
				foreach ($objseatinfo as $seats) {
					$seattemp['seat_no']=$seats->seat_no;
					$seattemp['status']=$seats->status;
					$seat_info[]=$seattemp;
				}
			}

			$seat_layout['seat_lists']=$seat_info;
			$tmp['seat_layout']=$seat_layout;

			$trip=$tmp;
		}
		$response=$trip;
		return Response::json($response);
	}


}