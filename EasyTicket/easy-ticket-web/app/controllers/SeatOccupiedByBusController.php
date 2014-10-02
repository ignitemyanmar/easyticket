<?php

class SeatOccupiedByBusController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$operator_ids=array();
		$orderids=array();
		$cities=array();
		$from =$to = $departure_time =null;

		$userid=Auth::user()->id;
		$usertype=Auth::user()->type;
		$operator_id =Operator::whereuser_id($userid)->pluck('id');
		$operator_ids=$operator_id;
		
		if(!$operator_id && $usertype=='agent'){
			$agent_id=Agent::whereuser_id($userid)->pluck('id');
			$orderids=SaleOrder::whereagent_id($agent_id)->lists('id');
			$operator_ids=SaleOrder::whereagent_id($agent_id)->groupby('operator_id')->lists('operator_id');
		}elseif($operator_id){
			$orderids=SaleOrder::whereoperator_id($operator_id)->lists('id');
		}else{

		}

		// get cities
		$responsecity		= $this->getCities($operator_ids,$from, $to);
		$search['cities']=$cities;

		$responsetime=$this->getTimes($operator_ids, $from, $to);
		$search['times']=$responsetime;

		

		$response=array();
		$operators=array();
		$objoperators=Operator::wherein('id',$operator_ids)->get(array('id', 'name'));
		if($objoperators){
			foreach ($objoperators as $value) {
				$temp['id']=$value['id'];
				$temp['name']=$value['name'];
				$operators[]=$temp;
			}
		}
		$search['operators']=$operators;

		$search['operator_id']=$operator_id;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;
		return View::make('busreport.seatoccupiedbybus', array('response'=>$response,'search' => $search));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
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


	public function getCities($operator_ids){
    	$cities=array();
    	$orderids 			=SaleOrder::wherein('operator_id',$operator_ids)->lists('id');
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		if($busoccurance_ids){
    			foreach ($busoccurance_ids as $busoccuranceid) {
    				$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    				if($objbusoccurance){
    					$objfromcities				=City::whereid($objbusoccurance->from)->first();
    					$tempfrom['from']			=$objfromcities->id;
    					$tempfrom['from_city']		=$objfromcities->name;
    					$objtocities				=City::whereid($objbusoccurance->to)->first();
    					$tempto['to']				=$objtocities->id;
    					$tempto['to_city']			=$objtocities->name;
    					$from[]						=$tempfrom;
    					$to[]						=$tempto;
    				}
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
    		}
    	}
    	return $cities;
    }

    public function getTimes($operator_ids, $from_city, $to_city){
		if($operator_ids && $from_city && $to_city){
			$objtrip=BusOccurance::wherein('operator_id',$operator_ids)->wherefrom($from_city)->whereto($to_city)->groupBy('departure_time')->get();
		}elseif($operator_ids && !$from_city && !$to_city){
			$objtrip=BusOccurance::wherein('operator_id',$operator_ids)->groupBy('departure_time')->get();
		}else{
			$objtrip=BusOccurance::groupBy('departure_time')->get();
		}
		$times=array();
		if($objtrip){
			foreach ($objtrip as $row) {
				$temp['tripid']=$row->id;
				$temp['time']=$row->departure_time;
				$times[]=$temp;
			}
		}
		return $times; 
	}

}