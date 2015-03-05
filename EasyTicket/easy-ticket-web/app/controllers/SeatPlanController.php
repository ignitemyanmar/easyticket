<?php
class SeatPlanController extends BaseController
{
	public function getAddSeatPlan()
	{
		$seatlayout = SeatingLayout::all();
		$tickettype = TicketType::all();
		$operator   = Operator::all();
		$operator_id=$this->myGlob->operator_id;

		return View::make('seatplan.add',array('seatlayout'=>$seatlayout,'tickettype'=>$tickettype,'operator'=>$operator, 'operator_id'=>$operator_id));
	}

	public function postAddSeatPlan()
	{
		$operator_id   =$this->myGlob->operator_id;
		$ticketype_id  = Input::get('tickettype');
		$seatlayout_id = Input::get('seat_layout');
		$row 		   = SeatingLayout::whereid($seatlayout_id)->pluck('row');
		$col 		   = SeatingLayout::whereid($seatlayout_id)->pluck('column');
		$seatlist 	   = Input::get('seats');
		
		$checkseatplan = SeatingPlan::whereticket_type_id($ticketype_id)
									->whereoperator_id($operator_id)
									->whererow($row)
									->wherecolumn($col)
									->whereseat_layout_id($seatlayout_id)->first();
		if($checkseatplan){
			$response['message']='This record is already exit';
			return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
		}	
		
		$j=0;$i=0;
		$count =count($seatlist);
		// return Response::json($seatlist);
		$seatno = array();
		if($count>0){
			foreach($seatlist as $seat) {
				if($seat=="No Seat"){
					$temp['seat_no']="xxx";
					$temp['status']="0";
					$seatno[]=$temp;
				}else{
					$temp['seat_no']=$seat;
					$temp['status']=1;
					$seatno[]=$temp;
				}
			}
		}
		$string = json_encode($seatno);

		// dd($string);
		// return Response::json($string);
		$obj_seatplan = new SeatingPlan();
		$obj_seatplan->operator_id    = $operator_id;
		$obj_seatplan->ticket_type_id = $ticketype_id;
		$obj_seatplan->seat_layout_id = $seatlayout_id;
		$obj_seatplan->row = $row;
		$obj_seatplan->column = $col;
		$obj_seatplan->seat_list = $string;
		$obj_seatplan->save();

		$seatplanid=SeatingPlan::max('id');
		foreach ($seatno as $seat) {
			$objseatinfo = new SeatInfo();
			$objseatinfo->seat_plan_id=$seatplanid;
			$objseatinfo->seat_no=$seat['seat_no'];
			$objseatinfo->status=$seat['status'];
			$objseatinfo->save();
		}
		$operatorname						=Operator::whereid($operator_id)->pluck('name');
		$total_seat 						=SeatInfo::whereseat_plan_id($seatplanid)->wherestatus(1)->count();
		$seat_plan_name						=$operatorname.'-'.$total_seat;
		$objseatplanupdate					=SeatingPlan::whereid($seatplanid)->first();
		$objseatplanupdate->name=$seat_plan_name;
		$objseatplanupdate->update();
		return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
	}

	public function postSeatPlan()
	{
		$seatlayout_id 	= Input::get('seat_layout_id');
		$seatlist 		= SeatingLayout::whereid($seatlayout_id)->first();
		$seatplan = array();
		$seatplan['ticket_type_id'] = $seatlist->ticket_type_id;
		$seatplan['seat_layout_id'] = $seatlist->id;
		$seatplan['row'] 			= $seatlist->row;
		$seatplan['column']			= $seatlist->column;
		$seatsformat 				=explode(',',$seatlist->seat_list);
		$seatplan['seat_list']		= $seatsformat;

		// return Response::json($seatplan);
		return View::make('seatplan.ajax.seatingplan',array('seatplan'=>$seatplan));
	}

	public function showSeatPlanList()
  	{
  		$operator_id=$this->myGlob->operator_id;
      	$response   	= $obj_seatplan = SeatingPlan::whereoperator_id($operator_id)->orderBy('id','desc')->get();
  		$allSeatPlan	= SeatingPlan::all();
      	$totalCount 	= count($allSeatPlan);
  		$i=0;
        foreach ($response as $seatplan)
        {
          $tickettypename = TicketType::where('id','=',$seatplan['ticket_type_id'])->pluck('name');
          $operatorname   = Operator::where('id','=',$seatplan['operator_id'])->pluck('name');
            $response[$i]['id']              = $seatplan['id'];
            $response[$i]['operator_id']     = $operatorname;
            $response[$i]['ticket_type_id']  = $tickettypename;
            $response[$i]['seat_layout_id']  = $seatplan['seat_layout_id'];
            $response[$i]['row']           	 = $seatplan['row'];
            $response[$i]['column']          = $seatplan['column'];
            $response[$i]['seatlist']        = $seatplan['seatlist'];
            $i++;
        } 

		return View::make('seatplan.list', array(
        'response'    	 =>  $response,
        'obj_seatplan'   =>  $obj_seatplan,
        'totalCount'     =>  $totalCount
        ));
  	}

  	public function getEditSeatPlan($id)
    {
      $obj_seatplan = SeatingPlan::find($id);
       if(count($obj_seatplan)==0){
        return Redirect::to('seatplanlist?'.$this->myGlob->access_token);
       }
       $seatplan['id'] 			   = $id;
       $seatplan['operator_id']    = $obj_seatplan->operator_id;
       $seatplan['ticket_type_id'] = $obj_seatplan->ticket_type_id;
       $seatplan['seat_layout_id'] = $obj_seatplan->seat_layout_id;
       $seatplan['row'] 		   = $obj_seatplan->row;
       $seatplan['column'] 		   = $obj_seatplan->column;
       $seatplan['seat_list'] 	   = $obj_seatplan->seat_list;
       
       $tickettype = TicketType::all();
       $seatlayout = SeatingLayout::all();
       $operator   = Operator::all();
       $response['seatplan']   = $seatplan;
       $response['tickettype'] = $tickettype;
       $response['seatlayout'] = $seatlayout;
       $response['operator']   = $operator;
       return View::make('seatplan.edit',array('tickettype'=>$tickettype,'response'=>$response));
    }

    public function postEditSeatPlan($id)
    {
		$operator_id   = Input::get('operator');
        $ticketype_id  = Input::get('tickettype');
		$seatlayout_id = Input::get('seat_layout');
		$row 		   = SeatingLayout::whereid($seatlayout_id)->pluck('row');
		$col 		   = SeatingLayout::whereid($seatlayout_id)->pluck('column');
		$seatlist 	   = Input::get('seats');

		$j=0;$i=0;
		$count =count($seatlist);
		$seatno = array();
		if($count>0){
			foreach($seatlist as $seat) {
				if($seat=="No Seat"){
					$temp['seat_no']="xxx";
					$temp['status']="0";
					$seatno[]=$temp;
				}else{
					$temp['seat_no']=$seat;
					$temp['status']=1;
					$seatno[]=$temp;
				}
			}
		}
		$string = json_encode($seatno);


      	$affectedRow = SeatingPlan::where('id','=',$id)->update(array(
                                  'seat_list'=>$string));

		$objseatinfo=SeatInfo::whereseat_plan_id($id)->lists('seat_no');
		$j=0;
		foreach ($seatno as $seat) {
			$toupdate=SeatInfo::whereseat_plan_id($id)->whereseat_no($objseatinfo[$j])->first();
			$toupdate->seat_no=$seat['seat_no'];
			$toupdate->update();
			$j++;
		}

      return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
    }

    public function getDeleteSeatPlan($id)
    {
    	$seatplan_id=Trip::whereseat_plan_id($id)->first();
    	if($seatplan_id){
      		return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
    	}
      	$affectedRows1 = SeatingPlan::where('id','=',$id)->delete();
      	return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
    }

    public function postdelSeatPlan()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
      }
      foreach($todeleterecorts as $recid)
      {
        $result = SeatingPlan::where('id','=',$recid)->delete();
      }
      return Redirect::to('/seatplanlist?'.$this->myGlob->access_token);
    }

    public function postSearchSeatPlan()
    {
      $keyword = Input::get('keyword');
      $response=$seatplan = SeatingPlan::where('row','LIKE','%'.$keyword.'%')->orwhere('column','LIKE','%'.$keyword.'%')->
      orderBy('id','DESC')->paginate(10);
      $allseatplan = SeatingPlan::all();
      $totalCount = count($allseatplan);
      return View::make('seatplan.list')->with('seatplan', $seatplan)->with('totalCount',$totalCount)->with('response',$response);
    }

    public function showSeatPlanLists($operator_id,$name, $ticketype_id)
  	{
      	$response   	= $obj_seatplan = SeatingPlan::orderBy('id','desc')->paginate(12);
  		$allSeatPlan	= SeatingPlan::all();
      	$totalCount 	= count($allSeatPlan);
  		$i=0;
       foreach ($response as $seatplan)
       {
          $tickettypename = TicketType::where('id','=',$seatplan['ticket_type_id'])->pluck('name');
          $operatorname   = Operator::where('id','=',$seatplan['operator_id'])->pluck('name');
            $response[$i]['id']              = $seatplan['id'];
            $response[$i]['operator_id']     = $operatorname;
            $response[$i]['ticket_type_id']  = $tickettypename;
            $response[$i]['seat_layout_id']  = $seatplan['seat_layout_id'];
            $response[$i]['row']           	 = $seatplan['row'];
            $response[$i]['column']          = $seatplan['column'];
            $response[$i]['seatlist']        = $seatplan['seatlist'];
            $i++;
        } 

		return View::make('seatplan.list', array(
        'response'    	 =>  $response,
        'obj_seatplan'   =>  $obj_seatplan,
        'totalCount'     =>  $totalCount
        ));
  	}

    public function getSeatPlanDetail($seat_layout_id)
    {
    	$seatplan_id        = Input::get('seatplan_id');
    	$operator_id  		= Input::get('operator_id');
    	$ticketype_id  		= Input::get('ticket_type_id');
    	$name  				= Input::get('name');

    	$responseobj   		= $this->showSeatPlanLists($operator_id,$name, $ticketype_id);
		$response			= json_decode($responseobj, true);
    	$seatlayout_id = Input::get('seat_layout');
		$row 		   = Input::get('row');
		$col 		   = Input::get('column');
		$seatlist 	   = Input::get('seat_list');

		$seatplanlist =SeatingPlan::whereid($seatplan_id)->first();
		$parameter = array();
		$parameter['seat_layout_id'] = $seatplanlist->seat_layout_id;
		$parameter['row'] 			 = $seatplanlist->row;
		$parameter['column'] 		 = $seatplanlist->column;
		
		$allseatinfo = SeatInfo::whereseat_plan_id($seatplan_id)->get();
		
		$seat_info=array();
		if($allseatinfo){
			foreach ($allseatinfo as $seat) {
				$temp['id']=$seat->id;
				$temp['seat_plan_id']=$seat->seat_plan_id;
				$temp['seat_no']=$seat->seat_no;
				$temp['status']=$seat->status;
				$seat_info[]=$temp;
			}
		}
		
		$parameter['seat_list'] 	 = $seat_info;
		
		// return Response::json($parameter);
		return View::make('seatplan.detail',array('response'=>$response,'parameter'=>$parameter));
    }

    public function getEdit($id){
    	$seatlayout_id 	= SeatingPlan::whereid($id)->pluck('seat_layout_id');
		$seatlist 		= SeatingLayout::whereid($seatlayout_id)->first();
		$seatplan = array();
		$seatplan['id'] = $id;
		$seatplan['ticket_type_id'] = $seatlist->ticket_type_id;
		$seatplan['seat_layout_id'] = $seatlist->id;
		$seatplan['row'] 			= $seatlist->row;
		$seatplan['column']			= $seatlist->column;
		$seatsformat 				=explode(',',$seatlist->seat_list);
		$seatplan['seat_list']		= $seatsformat;
		
		$seat_no_list=array();
		$objseatinfo=SeatInfo::whereseat_plan_id($id)->get();
		if($objseatinfo){
			foreach ($objseatinfo as $row) {
				$temp['seat_plan_id']=$row->seat_plan_id;
				$temp['seat_no']=$row->seat_no;
				$temp['status']=$row->status;
				$seat_no_list[]=$temp;
			}
		}
		// return Response::json($seat_no_list);
		return View::make('seatplan.editseat',array('seatplan'=>$seatplan, 'seat_no_list'=>$seat_no_list));
    }

    public function getchangeseatplan($id)
    {
		$operator_id=$this->myGlob->operator_id;
		$busclasses=Classes::whereoperator_id($operator_id)->get();	
		$seatplan=SeatingPlan::whereoperator_id($operator_id)->get();
		$trip =Trip::whereid($id)->with('busclass','from_city','to_city','extendcity')->first();
		$response['seatplan']=$seatplan;
		$response['trip']=$trip;
		// return Response::json($response['trip']);
		return View::make('seatplan.changetripseatplan', array('response'=>$response,'operator_id'=>$operator_id));
	}

	public function postchangeseatplan($id){
		$date=Input::get('onlyone_day');
		$seat_plan_id=Input::get('seat_plan_id') ? Input::get('seat_plan_id') : 0;
		$objbusoccurance=BusOccurance::wheretrip_id($id)->wheredeparture_date($date)->first();

		$checksaleitems =array();
		$message='';
		if($objbusoccurance){
			$currentseatname=SeatInfo::whereseat_plan_id($objbusoccurance->seat_plan_id)->orderBy('id','asc')->pluck('seat_no');
			$checkseatnames=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($currentseatname)->pluck('seat_no');
			$new_seat_plan= SeatInfo::whereseat_plan_id($seat_plan_id)->get();
			$solditem = SaleItem::wherebusoccurance_id($objbusoccurance->id)->lists('seat_no');
			$check = true;
			foreach ($solditem as $value) {
				if(!$this->isExist($value, $new_seat_plan)){
					$check = false;
					break;
				}
			}
			if($checkseatnames && $check == true){
				$objbusoccurance->seat_plan_id=$seat_plan_id;
				$objbusoccurance->update();
				$message ="Successfully change seat plan.";

				$checkcloseseat=CloseSeatInfo::wheretrip_id($id)->where('start_date','<=',$date)
																->where('end_date','>=',$date)
																->pluck('seat_lists');
			    $checkcloseseat =json_decode($checkcloseseat);

			    $objseatinfo =SeatInfo::whereseat_plan_id($seat_plan_id)->get();

			    if($checkcloseseat && $objseatinfo){
			    	foreach ($objseatinfo as $key => $rows) {
			    		if($key < count($checkcloseseat)){
			    			$objseatinfo[$key]['operatorgroup_id']=$checkcloseseat[$key]->operatorgroup_id;
			    		}
			    		else{
			    			$objseatinfo[$key]['operatorgroup_id']=0;
			    		}

			    	}
			    }

			    // return Response::json($objseatinfo);
				// return Response::json($checkcloseseat);
			    $checkexistingupdated =CloseSeatInfo::wheretrip_id($id)->where('start_date','<=',$date)
																->where('end_date','>=',$date)->first();
				if($checkexistingupdated){
					$checkexistingupdated->seat_plan_id=$seat_plan_id;
					$checkexistingupdated->seat_lists=$objseatinfo;
					$checkexistingupdated->update();
				}else{
					if($checkcloseseat){
						$obj_closeseatinfo=new CloseSeatInfo();
					    $obj_closeseatinfo->trip_id=$id;
					    $operatorgroup_id=CloseSeatInfo::wheretrip_id($id)->where('start_date','<=',$date)
																		->where('end_date','>=',$date)
																		->pluck('operatorgroup_id');
					    $obj_closeseatinfo->operatorgroup_id=$operatorgroup_id ? $operatorgroup_id : 0;
					    $obj_closeseatinfo->seat_plan_id=$seat_plan_id;
					    $obj_closeseatinfo->seat_lists=$objseatinfo;
					    $obj_closeseatinfo->start_date=$date;
					    $obj_closeseatinfo->end_date=$date;
					    $obj_closeseatinfo->save();	
					}
				}
			}else{
				$message ="Can't change seat plan.";
			}
			
		}else{
			$message="This day bus stop";
		}
		// return Response::json($checksaleitems);
		return Redirect::to('trip-list?'.$this->myGlob->access_token)->with('message', $message);
	}

	public function isExist($value, $lists){
		foreach ($lists as $key => $seat) {
			if($value == $seat->seat_no){
				return true;
				break;
			}
		}
		return false;
	}
	
}