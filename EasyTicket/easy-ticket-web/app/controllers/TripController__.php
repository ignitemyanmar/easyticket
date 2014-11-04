<?php
class TripController___ extends BaseController
{
  public function getAddtrip()
  	{   
	  	$trip =Trip::all();
	  	$city = City::all();
      $class = Classes::all();
      $operator = Operator::all();
      $seatplan = SeatingPlan::all();
	  	return View::make('trip.add',array('trip'=>$trip,'city'=>$city,'class'=>$class,'operator'=>$operator,'seatplan'=>$seatplan));
  	}

  	public function postAddtrip()
  	{

  		$operator 			=Input::get('operator');
	    $from 			    =Input::get('from');
	    $to 		        =Input::get('to');
	    $class 	        =Input::get('class');
      $available_day 	=Input::get('day');
      $time           =Input::get('time');
      $price          =Input::get('price');
      $seatplan       =Input::get('seatplan');
     	$Trip = new Trip();
     	$Trip->operator_id 		  =$operator;
     	$Trip->from 			      =$from;
     	$Trip->to 		          =$to;
     	$Trip->class_id 		    =$class;
     	$Trip->available_day 	  =$available_day;
     	$Trip->time  	          =$time;
      $Trip->price            =$price;
      $Trip->seat_plan_id     =$seatplan;
     	
      $result=$Trip->save();
      return Redirect::to('/triplist');
  	}

  	// public function showlist()
  	// {
   //    $trip     =Trip::all();
   //    $response = $trip;
      
   //    $city     = City::all();
   //    $response = $city;
      
   //    $class    = Classes::all();
   //    $response = $class;
      
   //    $operator = Operator::all();
   //    $response = $operator;
      
   //    $seatplan = SeatingPlan::all();
   //    $response = $seatplan;

	  //   $obj_Trip = Trip::paginate(12);
	  //   $response = $obj_Trip;
	  //   $totalcount = Trip::count();
	  //   return View::make('trip.list',array('response'=>$response,'totalcount'=>$totalcount));
  	// }

    public function showTriplist()
    {
      $response   = $obj_trip = Trip::orderBy('id','desc')->paginate(12);
      $alltrip    = Trip::all();
      $totalCount = count($alltrip); 

      $i=0;
       foreach ($response as $trip)
       {
          $operatorname = Operator::where('id','=',$trip['operator_id'])->pluck('name');
          $from = City::where('id','=',$trip['from'])->pluck('name');
          $to = City::where('id','=',$trip['to'])->pluck('name');
          $classname = Classes::where('id','=',$trip['class_id'])->pluck('name');
          $seatplanname = SeatingPlan::where('id','=',$trip['seat_plan_id'])->pluck('name');
        
            $response[$i]['id']             = $trip['id'];
            $response[$i]['operator_id']    = $operatorname;
            $response[$i]['from']           = $from;
            $response[$i]['to']             = $to;
            $response[$i]['class_id']       = $classname;
            $response[$i]['available_day']  = $trip['available_day'];
            $response[$i]['price']          = $trip['price'];
            $response[$i]['time']           = $trip['time'];
            $response[$i]['seat_plan_id']   = $seatplanname;
            $i++;
        }
        
        return View::make('trip.list', array(
        'response'    =>  $response,
        'obj_trip'     =>  $obj_trip,
        'totalCount'  =>  $totalCount,
        'message'   =>  ''
        ));
    }

    public function getEditTrip($id)
    {
           $obj_trip = Trip::find($id);
        if(count($obj_trip) == 0 ){
            return Redirect::to('triplist');
        }
        $trip['id']             = $id;
        $trip['operator_id']    = $obj_trip->operator_id;
        $trip['from']           = $obj_trip->from;
        $trip['to']             = $obj_trip->to;
        $trip['class_id']       = $obj_trip->class_id;
        $trip['available_day']  = $obj_trip->available_day;
        $trip['price']          = $obj_trip->price;
        $trip['time']           = $obj_trip->time;
        $trip['seat_plan_id']   = $obj_trip->seat_plan_id;

        
        $operator = Operator::all();
        $city     = City::all();
        $class    = Classes::all();
        $seatplan = SeatingPlan::all();
       
        $response['trip']     = $trip;
        $response['operator'] = $operator;
        $response['city']     = $city;
        $response['class']    = $class;
        $response['seatplan'] = $seatplan;
                
        return View::make('trip.edit')->with('response', $response);
    }

    public function postEditTrip($id)
    {
      $affectedRow = Trip::where('id','=',$id)->update(array(
                    'operator_id' => Input::get('operator'),
                    'from'=>Input::get('from'),
                    'to'=>Input::get('to'),
                    'class_id'=>Input::get('class'),
                    'available_day'=>Input::get('day'),
                    'price' =>Input::get('price'),
                    'time'=>Input::get('time'),
                    'seat_plan_id'=>Input::get('seatplan')
                    ));
      return Redirect::to('/triplist');
    }

    public function getDeleteTrip($id)
    {
      $affectedRows1 = Trip::where('id','=',$id)->delete();
      return Redirect::to('/triplist');
    }

    public function postdelTrip()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to("/triplist");
      }
      foreach($todeleterecorts as $recid)
      {
        $result = Trip::where('id','=',$recid)->delete();
      }
      return Redirect::to("/triplist");
    }

    public function postSearchTrip()
    {
      $keyword = Input::get('keyword');
      $response=$trip = Trip::where('price','LIKE','%'.$keyword.'%')->orderBy('id','DESC')->paginate(10);
      $alltrip = Trip::all();
      $totalCount = count($alltrip);
      return View::make('trip.list')->with('trip', $trip)->with('totalCount',$totalCount)->with('response',$response);
    }
}
