<?php
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
    	
    	
    	$objbusoccurance =	BusOccurance::wherefrom($from)
    									->whereto($to)
    									->wheredeparture_date($date)
    									->wheredeparture_time($time)
    									->whereclasses($class_id)
    									->wherebus_no($bus_no)->first();
    	$seat_list=array();
    	$bus_id=null;
    	if($objbusoccurance){
    		$order_ids=SaleItem::wherebusoccurance_id($objbusoccurance->id)->groupBy('order_id')->lists('order_id');
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
			// return Response::json($remark_seats);

    		$bus_id=$objbusoccurance->id;
    		$busoccurance_id=$objbusoccurance->id;
    		$objseatinfo	=SeatInfo::whereseat_plan_id($objbusoccurance->seat_plan_id)->get();
    		$seat_plan_id 	=$objseatinfo[0]->seat_plan_id;
    		if($objseatinfo){
    			$seats=array();

    			$closeseat=CloseSeatInfo::wheretrip_id($objbusoccurance->trip_id)
    										->whereseat_plan_id($objbusoccurance->seat_plan_id)
    										->where('start_date','<=',$date)
    										->where('end_date','>=',$date)
    										->pluck('seat_lists');
				$jsoncloseseat=json_decode($closeseat,true);

				$k=0;$extra_city_id=null;$extra_city_price=0;
    			foreach ($objseatinfo as $seat) {
    				$temp['id']=$seat->id;
    				$checkoccupied_seat =SaleItem::wherebusoccurance_id($bus_id)
    												->whereseat_no($seat->seat_no)
    												->first();
    				$customer=array();
					if($checkoccupied_seat){
						$temp['status']		=2;
						$objorder=SaleOrder::whereid($checkoccupied_seat->order_id)->first();
						// $checkbooking=SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('booking');
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
						// return Response::json($cubrid_save_to_glo(conn_identifier, oid, file_name)omer);
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
				$seat_list['bus_id']		=$bus_id;
				$seat_list['class_id']		=$objbusoccurance->classes;
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

		// return Response::json($seat_list);
    	return View::make('bus.chooseseat', array('response'=>$seat_list, 'related_bus'=>$buslist, 'operatorgroup'=>$operatorgroup, 'agents'=>$agents,'remarkgroup'=>$remarkgroup,'remark_seats'=>$remark_seats));
	}