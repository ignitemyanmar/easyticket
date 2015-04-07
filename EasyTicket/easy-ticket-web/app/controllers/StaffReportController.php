<?php

class StaffReportController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$agopt_ids     			=$this->myGlob->agopt_ids;	
		if(!isset($this->myGlob->operator_id)){
			return Redirect::to('/easyticket-admin');
		}
		$operator_id  			=$this->myGlob->operator_id;
		$seller_id 				=Input::get('seller_id');

		$from 					=Input::get('from');
		$to 					=Input::get('to');
		$start_date 			=Input::get('start_date') ? date('Y-m-d',strtotime(Input::get('start_date'))) : $this->getDate();
		$end_date 				=Input::get('end_date') ? date('Y-m-d',strtotime(Input::get('end_date'))) : $this->getDate();
		$time 					=Input::get('departure_time');
		$search =array();
		$cities=array();
    	$cities=ReportController::getCitiesByoperatorid($operator_id);
		$search['cities']=$cities;
		$times=array();
		$times=ReportController::getTime($operator_id, 0, 0);
		$search['times']=$times;

		$search['from']=$from;
		$search['to']=$to;
		$search['start_date']=$start_date;
		$search['end_date']=$end_date;
		$search['time']=$time;

		$user_ids=array();
		if(Auth::user()->role==2){
			$user_ids[]=Auth::user()->id;
		}else{
			if(!$seller_id || $seller_id =='all'){
				$user_ids=User::wheretype('operator')->lists('id');
			}else{
				$user_ids[]=$seller_id;
			}	
		}
		

		if(!$from || $from =='all'){
			$trip_ids=Trip::whereoperator_id($operator_id)->lists('id');
		}else{
			if(!$to || $to=='all'){
				$to_id=null;
			}else{
				$to_id=$to;
			}

			if($from && $to_id){
				$trip_ids=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to_id)->lists('id');
			}
		}

		if(Auth::user()->role==2)
			$users=User::wherein('id',$user_ids)->wheretype('operator')->get(array('id','name'));
		else
			$users=User::wheretype('operator')->get(array('id','name'));

		$search['users']=$users;
		$search['seller_id']=$seller_id;
		$response= array();

		$response =SaleOrderReport::wherein('user_id',$user_ids)
									->wherein('trip_id',$trip_ids)
									->where('departure_date','>=',$start_date)
									->where('departure_date','<=',$end_date)
									->where('time','like','%'.$time.'%')
									->groupBy('trip_id','user_id') 
									->selectRaw(
											'orderdate, 
											trip_id, 
											user_id, 
											seller_name, 
											agent_id, 
											agent_name, 
											CONCAT(from_city, "=>", to_city) AS trip, 
											departure_date, 
											time, 
											class_name, 
											count(*) as sold_seat, 
											sum(free_ticket) as free_ticket, 
											price, 
											foreign_price, 
											sum(discount) as discount, 
											sum(price) as total_amount, 
											sum(commission) as commission'
									)
									->get();
		// return Response::json($response);
		return View::make('busreport.staffreport.index', array('search'=>$search, 'response'=>$response));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function detail($date)
	{
		if(!isset($this->myGlob->operator_id)){
			return Redirect::to('/easyticket-admin');
		}
		$trip_id		=Input::get('trip_id');
		$operator_id	=$this->myGlob->operator_id;
		$seller_id		=Input::get('seller_id');
		$from			=Input::get('fr');
		$to				=Input::get('to');
		$time			=Input::get('ti');
		if($date){
			$departure_date =explode(',', $date);
		}
		if($trip_id){
			$date 		=$departure_date[0];
			$start_date	=$departure_date[0];
			$end_date	=$departure_date[0];
		}else{
			$start_date	=$departure_date[0];
			$end_date	=$departure_date[1];
		}

		// dd($start_date.'--'.$end_date);
		$trip_ids=array();
		if($trip_id){
			$trip_ids[]=$trip_id;
		}else{
			if($from && $to && $time && $from != 'all'){
				$trip_ids=Trip::wherefrom($from)->whereto($to)->wheretime($time)->lists('id');
			}else{
				$trip_ids=Trip::whereoperator_id($operator_id)->lists('id');
			}
		}

		$user_ids=array();
		if(Auth::user()->role==2){
			$user_ids[]=Auth::user()->id;
		}else{
			if($seller_id && $seller_id !='all'){
				$user_ids[]=$seller_id;
			}else{
				$user_ids=User::wheretype('operator')->lists('id');
			}	
		}
		

		// dd($trip_ids);

		$response =SaleOrderReport::wherein('trip_id', $trip_ids)
									->wherein('user_id',$user_ids)
									->where('departure_date','>=',$start_date)
									->where('departure_date','<=',$end_date)
									->selectRaw(
										'id as vr_no, 
										ticket_no, 
										orderdate, 
										agent_name, 
										CONCAT(from_city, "=>", to_city) AS trip, 
										departure_date, 
										time, 
										class_name,
										price, 
										foreign_price, 
										saleitem_name as buyer_name,
										commission,
										seat_no,
										free_ticket,
										discount,
										user_id,
										seller_name'
									)
									->get();
		$search=array();
		$backurl=URL::previous();
		$search['back_url']=$backurl;
		if($seller_id && $seller_id !='all'){
			$search['seller_name']=User::whereid($seller_id)->pluck('name');
		}else{
			if(Auth::user()->role==2){
				$search['seller_name']=User::whereid(Auth::user()->id)->pluck('name');
			}else{
				$search['seller_name']="All Staff";
			}
		}

		$search['trip_date']=date('d/m/Y', strtotime($start_date));
		if($end_date !=$start_date){
			$search['trip_date'] .=' - '.date('d/m/Y', strtotime($end_date));
		}

		// return Response::json($response);
		return View::make('busreport.staffreport.detail', array('response' =>$response, 'search'=>$search));
	}

}
