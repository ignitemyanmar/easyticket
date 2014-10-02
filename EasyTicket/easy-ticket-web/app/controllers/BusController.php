<?php

class BusController extends BaseController {

	public function index()
	{
		$response=array();
    	$trip=array();
    	$operator_id=Input::get('operator_id');
    	if($operator_id !=''){
    		$objbusoccurance =BusOccurance::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}else{
    		$objbusoccurance =BusOccurance::groupBy('from','to')->get(array('from','to'));
    	}
    	if($objbusoccurance){
    		foreach ($objbusoccurance as $trips) {
    			$temp['from_id']=$trips['from'];
    			$temp['from']=City::whereid($trips['from'])->pluck('name');
    			$temp['to_id']=$trips['to'];
    			$temp['to']=City::whereid($trips['to'])->pluck('name');
    			$trip[]=$temp;
    		}
    	}
    	$response=$trip;
    	$objoperator=array();
    	$objoperator=Operator::all();
		return View::make('bus.trips', array('response'=> $response, 'operators'=>$objoperator));
    	// return View::make('')
	}

	/* functions
		
	*/
	public function getTrip(){
    	$response=array();
    	$trip=array();
    	$operator_id=Input::get('operator_id');
    	if($operator_id !=''){
    		$objbusoccurance =BusOccurance::whereoperator_id($operator_id)->groupBy('from','to')->get(array('from','to'));
    	}else{
    		$objbusoccurance =BusOccurance::groupBy('from','to')->get(array('from','to'));
    	}
    	if($objbusoccurance){
    		foreach ($objbusoccurance as $trips) {
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
}