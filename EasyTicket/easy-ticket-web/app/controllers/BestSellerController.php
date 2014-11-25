<?php

class BestSellerController extends \BaseController {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	

    public function trips()
	{
		$report_info 			=array();
		$operator_id  			=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
		$agent_id  				=Input::get('agent_id');
		$today =Date('Y-m-d');
		$s_date 				= Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime($today.'-30 days'));
    	$e_date 				= Input::get('end_date') ? Input::get('end_date') : $today;
    	
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
    	$lists=array();
    	if($trip_id){
    		foreach ($trip_id as $rows) {
    			$trips 			= Trip::whereid($rows->trip_id)->first();

    			if($trips){
    				$list['id'] 				= $trips->id;
    				$list['from']				= $trips->from;
    				$list['to']					= $trips->to;
	    			$list['trip'] 				= City::whereid($trips->from)->pluck('name') .' - '.City::whereid($trips->to)->pluck('name');
	    			$list['classes']			= Classes::whereid($trips->class_id)->pluck('name');
	    			$total_seat 				= SeatInfo::whereseat_plan_id($trips->seat_plan_id)->wherestatus(1)->count();
                    if(count($total_seat)>0 && $days>0){
                        $list['percentage']         = round(($rows->count / ($total_seat * $days)) * 100) ;
                        $list['sold_total_seat']    = $rows->count;
                        $list['total_seat']         = $total_seat * $days;
                        $list['total_amount']       = $rows->total;
                        $lists[]                    = $list;
                    }
	    			
    			}
    		}
    	}
		
		
		$search=array();
		    	
		$search['operator_id']=$operator_id;
		$search['agent_id']=$agent_id;
		$search['start_date']=$s_date;
		$search['end_date']=$e_date;
		$agent_ids=SaleOrder::whereoperator_id($operator_id)->where('agent_id','!=',0)->groupBy('agent_id')->lists('agent_id');
		$agent=Agent::wherein('id',$agent_ids)->get();
		$search['agent']=$agent;
		$response=$lists;
		// return Response::json($lists);
		return View::make('busreport.bestseller.trips', array('response'=>$response, 'search'=>$search));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function tripdetail()
	{
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
    	$lists=array();
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
    	}
    	// return Response::json($lists);
    	return View::make('busreport.bestseller.tripdetail', array('response'=>$lists));
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function agents()
	{
		$operator_id  			=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
    	$today =Date('Y-m-d');
		$s_date 				= Input::get('start_date') ? Input::get('start_date') : date('Y-m-d', strtotime($today.'-30 days'));
    	$e_date 				= Input::get('end_date') ? Input::get('end_date') : $today;
    	
    	
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

    	$lists = array();
    	if($agent_id){
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
    	}
    	// return Response::json($lists);
    	return View::make('busreport.bestseller.agents', array('response'=>$lists));
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
    public function times(){
        $operator_id    = OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
        // dd($operator_id);
        $agent_id       = Input::get('agent_id');
        $s_date         = Input::get('start_date');
        $e_date         = Input::get('end_date');
        $from           = Input::get('from');
        $to             = Input::get('to');
        
        $datetime1 = new DateTime($s_date);
        $datetime2 = new DateTime($e_date);
        $interval = $datetime1->diff($datetime2);
        $days = $interval->format('%a');

       /* if(!$operator_id || !$s_date || !$e_date){
            $message['status']  = 0;
            $message['message'] = "Required for any parameter.";
            return Response::json($message, 400);
        }*/
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
        $lists=array();
        if($trip_id){
            $lists = array();
            foreach ($trip_id as $rows) {
                $trips          = Trip::whereid($rows->trip_id)->first();

                if($trips){
                    $list['id']                 = $trips->id;
                    $list['trip']               = City::whereid($trips->from)->pluck('name') .' - '.City::whereid($trips->to)->pluck('name');
                    $list['time']               = $trips->time;
                    $list['classes']            = Classes::whereid($trips->class_id)->pluck('name');
                    $total_seat                 = SeatInfo::whereseat_plan_id($trips->seat_plan_id)->wherestatus(1)->count();
                    if($total_seat * $days>0)
                        $list['percentage']         = round(($rows->count / ($total_seat * $days)) * 100) ;
                    else
                        $list['percentage']         = 0;

                    $list['sold_total_seat']    = $rows->count;
                    $list['total_seat']         = $total_seat * $days;
                    $list['total_amount']       = $rows->total;
                    $lists[]                    = $list;
                }
            }
        }

        $search=array();
                
        $search['operator_id']=$operator_id;
        $search['agent_id']=$agent_id;
        $search['start_date']=$s_date;
        $search['end_date']=$e_date;
        

        $cities=array();
        $cities=ReportController::getCitiesByoperatorId($operator_id);
        
        $search['cities']=$cities;
        
        $search['operator_id']=$operator_id;
        // $search['trips']=$trips;
        $search['from']=$from;
        $search['to']=$to;
        $search['agent_id']=$agent_id;

        $agent_ids=SaleOrder::whereoperator_id($operator_id)->where('agent_id','!=',0)->groupBy('agent_id')->lists('agent_id');
        $agent=Agent::wherein('id',$agent_ids)->get();
        $search['agent']=$agent;

        // return Response::json($lists);

        return View::make('busreport.bestseller.times', array('response'=>$lists, 'search'=>$search));
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