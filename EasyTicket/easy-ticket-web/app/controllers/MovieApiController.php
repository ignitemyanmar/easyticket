<?php
class MovieApiController extends BaseController
{
    public function postShowTime(){
    	$name 			=Input::get('name');
    	$operator_id 	=Input::get('operator_id');

    	if(!$name && !$operator_id){
    		$response['status']=0;
    		$response['message']="Request parameters are required.";
    		return Response::json($response);
    	}
    	$check_exiting	=ShowTime::wherename($name)->whereoperator_id($operator_id)->first();
    	if($check_exiting){
    		$response['status']=0;
    		$response['message']="This record is already exit.";
    		return Response::json($response);
    	}

    	$objshowtime 				=new ShowTime();
    	$objshowtime->name			=$name;
    	$objshowtime->operator_id 	=$operator_id;
    	$objshowtime->save();

    	$response['status']        =1;
		$response['message']       ="Successfully created one record.";
		$arr_showtime['id'] 	   =$objshowtime->id;
		$arr_showtime['name'] 	   =$objshowtime->name;
		$arr_showtime['operator_id'] 	=$objshowtime->operator_id;
		$response['showtime'] =$arr_showtime;
		return Response::json($response);
    }

    public function getShowTime(){
    	$operator_id 	=Input::get('operator_id');
    	if(!$operator_id){
    		$response['status']		=0;
    		$response['message']	='Request parameter is required.';
    		return Response::json($response);
    	}
    	$objshowtime	=ShowTime::whereoperator_id($operator_id)->get();
    	$arr_showtime   =array();
        
        if($objshowtime){
            $i=0;
            foreach ($objshowtime as $showtime) {
                $objshowtime[$i]   =$showtime;
                $objshowtime[$i]['operator']   =Operator::whereid($showtime->operator_id)->pluck('name');
                $i++;
            }
        }
    	return Response::json($objshowtime);
    }

    public function getShowTimeInfo($id){
        $objshowtime    =ShowTime::find($id);
        if($objshowtime){
            $objshowtime['operator']=Operator::whereid($objshowtime->operator_id)->pluck('name');
        }
        return Response::json($objshowtime);
    }

    public function updateShowTime($id){
        $objshowtime  =ShowTime::find($id);
        $name   = Input::get('name');
        if(!$objshowtime){
          $response['status'] =0;
          $response['message'] ="There is no showtime, same with this id.";  
          return Response::josn($response);
        }
        $objshowtime->name =$name !=null ? $name : $objshowtime->name;
        $objshowtime->update();

        $response['status'] =1;
        $response['message'] ="Successfully update this record.";
        $showtime['id']         =$objshowtime->id;
        $showtime['name']       =$objshowtime->name;
        $showtime['operator_id']=$objshowtime->operator_id;
        $showtime['operator']   =Operator::whereid($objshowtime->operator_id)->pluck('name');
        $response['showtime']   =$showtime;
        
        return Response::json($response);
    }

    public function deleteShowTime($id){
        $deleterecord =ShowTime::whereid($id)->delete();
        if($deleterecord){
            $response['status']=1;
            $response['message']="Successfully delete this record.";
            return Response::json($response);
        }else{
            $response['status']=0;
            $response['message']="There is record to delete.";
            return Response::json($response);
        }
    }

    public function postCity(){
        $objcity    =new City();
        $name       =Input::get('name');
        $operator_id=Input::get('operator_id');
        $response   =array();
        if(!$name || !$operator_id){
            $response['status']=0;
            $response['message']='Request parameters are required.';
            return Response::json($response);
        }
        
        $checkcity=City::wherename($name)->whereoperator_id($operator_id)->first();
        if($checkcity){
            $response['status']=0;
            $response['message']='This city is already exit.';
            return Response::json($response);
        }
        $objcity->name          =$name;
        $objcity->operator_id   =$operator_id;
        $objcity->save();
        return Response::json($objcity);
    }

    public function getCities(){
        $operator_id  =Input::get('operator_id');
        if(!$operator_id){
            $response['status']=0;
            $response['message']="Request parameter is required.";
            return Response::json($response);
        }
        $objcity=City::whereoperator_id($operator_id)->get();
        $cities=array();
        if($objcity){
            foreach ($objcity as $city) {
                $tmp['id']          =$city->id;
                $tmp['name']        =$city->name;
                $tmp['operator_id'] =$city->operator_id;
                $tmp['operator']    =Operator::whereid($city->operator_id)->pluck('name');
                $cities[]=$tmp;
            }
        }
        $response['cities']=$cities;
        return Response::json($response);
    }

    public function getCityInfo($id){
        $objcity=City::whereid($id)->first();
        $city=array();
        if($objcity){
            $city['id']=$id;
            $city['name']=$objcity->name;
        }
        $response=$city;
        return Response::json($response);
    }

    public function updateCity($id){
        $name       =Input::get('name');
        $objcity    =City::find($id);
        if(!$objcity){
            $response['status']=0;
            $response['message']="There is no city to update, same with this id.";
            return Response::json($response);
        }
        $objcity->name=$name !=null ? $name : $objcity->name;
        $objcity->update();
        $response['message']='This record have been update.';
        $temp['id']=$objcity->id;
        $temp['name']=$objcity->name;
        $temp['operator_id']=$objcity->operator_id;
        $temp['operator']=Operator::whereid($objcity->operator_id)->pluck('name');
        $response['cityinfo']=$temp;
        return Response::json($response);
    }

    public function deleteCity($id){
        $deletecity =   City::whereid($id)->delete();
        if($deletecity){
            $response['status']=1;
            $response['message']="Successfully delete this city.";
        }else{
            $response['status']=0;
            $response['message']="There is no record to delete with this id.";
        }
        return Response::json($response);
    }

    public function postCinema(){
        $name           =Input::get('name');
        $operator_id    =Input::get('operator_id');
        $address        =Input::get('address');
        $township_id    =Input::get('township_id');
        $city           =Input::get('city');
        $phone           =Input::get('phone');
        if(!$name || !$operator_id || !$township_id || !$address || !$city || !$phone){
            $response['status']     =0;
            $response['message']    ="Request parameters are required. ";
            return Response::json($response);
        }
        $check_exiting =Cinema::whereoperator_id($operator_id)->wherename($name)->first();
        if($check_exiting){
            $response['status']     =0;
            $response['message']    ="This cinema is already exit.";
            return Response::json($response);
        }
        $objcinema              =new Cinema();
        $objcinema->name        =$name;
        $objcinema->operator_id =$operator_id;
        $objcinema->address     =$address;
        $objcinema->township_id =$township_id;
        $objcinema->city        =$city;
        $objcinema->phone       =$phone;
        $objcinema->save();

        $response['status'] =1;
        $response['message']="Successfully create cinema.";
        $arr_cinema['id']           =$objcinema->id;
        $arr_cinema['name']         =$objcinema->name;
        $arr_cinema['operator_id']  =$objcinema->operator_id;
        $arr_cinema['operator']     =Operator::whereid($objcinema->operator_id)->pluck('name');
        $arr_cinema['address']      =$objcinema->address;
        $arr_cinema['township_id']  =$objcinema->township_id;
        $arr_cinema['township']     =City::whereid($objcinema->township_id)->pluck('name');
        $arr_cinema['city']         =$objcinema->city;
        $arr_cinema['phone']        =$objcinema->phone;
        $response['cinemainfo']=$arr_cinema;
        return Response::json($response);
    }

    public function getCinemas(){
        $operator_id =Input::get('operator_id');
        if(!$operator_id){
            $response['status']     =0;
            $response['message']    ="Request parameter is required.";
            return Response::json($response);
        }
        $objcinemas  =Cinema::whereoperator_id($operator_id)->get();
        
        $arr_cinema  =array();
        if($objcinemas){
            foreach ($objcinemas as $cinema) {
                $temp['id']           =$cinema->id;
                $temp['name']         =$cinema->name;
                $temp['operator_id']  =$cinema->operator_id;
                $temp['operator']     =Operator::whereid($cinema->operator_id)->pluck('name');
                $temp['address']      =$cinema->address;
                $temp['township_id']  =$cinema->township_id;
                $temp['township']     =City::whereid($cinema->township_id)->pluck('name');
                $temp['city']         =$cinema->city;
                $temp['phone']        =$cinema->phone;
                $arr_cinema[]         =$temp;
            }
        }
        $response['status']     =1;
        $response['cinemas']    =$arr_cinema;
        return Response::json($response);
    }

    public function getCinemaInfo($id){
        $objcinema =Cinema::find($id);
        if(!$objcinema){
            $response['status']     =0;
            $response['message']    ="There is no cinema, same with this id.";
            return Response::json($response);
        }
        $arr_cinema =array();
        if($objcinema){
            $temp['id']           =$objcinema->id;
            $temp['name']         =$objcinema->name;
            $temp['operator_id']  =$objcinema->operator_id;
            $temp['operator']     =Operator::whereid($objcinema->operator_id)->pluck('name');
            $temp['address']      =$objcinema->address;
            $temp['township_id']  =$objcinema->township_id;
            $temp['township']     =City::whereid($objcinema->township_id)->pluck('name');
            $temp['city']         =$objcinema->city;
            $temp['phone']        =$objcinema->phone;
            $arr_cinema[]         =$temp;
        }
        $response['status']    =1;
        $response['cinema']    =$arr_cinema;
        return Response::json($response);
    }

    public function updateCinema($id){
        $name           =Input::get('name');
        $address        =Input::get('address');
        $township_id    =Input::get('township_id');
        $city           =Input::get('city');
        $phone           =Input::get('phone');

        $objcinema =Cinema::find($id);
        if($objcinema){
            $objcinema ->name           = $name !=null ? $name : $objcinema->name;
            $objcinema ->address        = $address !=null ? $address : $objcinema->address;
            $objcinema ->township_id    = $township_id !=null ? $township_id : $objcinema->township_id;
            $objcinema ->city           = $city !=null ? $city : $objcinema->city;
            $objcinema ->phone          = $phone !=null ? $phone : $objcinema->phone;
            $objcinema ->update();
            $response['status']     =1;
            $response['message']    ="Successfully update this record.";
            
            $temp['id']           =$objcinema->id;
            $temp['name']         =$objcinema->name;
            $temp['operator_id']  =$objcinema->operator_id;
            $temp['operator']     =Operator::whereid($objcinema->operator_id)->pluck('name');
            $temp['address']      =$objcinema->address;
            $temp['township_id']  =$objcinema->township_id;
            $temp['township']     =City::whereid($objcinema->township_id)->pluck('name');
            $temp['city']         =$objcinema->city;
            $temp['phone']        =$objcinema->phone;
            $arr_cinema[]         =$temp;
            $response['cinema']   =$arr_cinema;
            return Response::json($response);
        }else{
            $response['status']     =0;
            $response['message']    ="There is no record to update, same with this id.";
            return Response::json($response);
        }
    }

    public function postMovie(){
        $name           = Input::get('name');
        $cinema_id      = Input::get('cinema_id');
        $start_date     = Input::get('start_date');
        $end_date       = Input::get('end_date');
        $description    = Input::get('description');

        if(!$name || !$cinema_id || !$start_date || !$end_date || !$description){
            $response['status']  =0;
            $response['message']  ="Request parameters are required.";
            return Response::json($response);
        }

        $check_exiting          =Movie::wherename($name)->wherecinema_id($cinema_id)
                                ->where('start_date','>=',$startdate)
                                ->where('end_date','<=',$startdate)->first();
        $objmovie               =new Movie();
        $objmovie->name         =$name;
        $objmovie->cinema_id    =$cinema_id;
        $objmovie->start_date   =$start_date;
        $objmovie->end_date     =$end_date;
        $objmovie->description  =$description;
        $objmovie->save();

        $response['status']     =1;
        $response['message']    ="Successfully save one record.";
        $movie['id']            =$objmovie->id;
        $movie['name']          =$objmovie->name;
        $movie['cinema_id']     =$objmovie->cinema_id;
        $movie['cinema']        =Cinema::whereid($objmovie->cinema_id)->pluck('name');
        $movie['start_date']    =$objmovie->start_date;
        $movie['end_date']      =$objmovie->end_date;
        $movie['description']   =$objmovie->description;
        
        $response['movie']      =$movie;
        return Response::json($response);   
    }

    public function postTicketClass(){
        $name           =Input::get('name');
        $price          =Input::get('price');
        $operator_id    =Input::get('operator_id');

        if(!$name || !$price || !$operator_id){
            $response['status'] =0;
            $response['message']="Request parameters are required.";
            return Response::json($response);
        }

        $check_exiting =TicketClass::wherename($name)->whereoperator_id($operator_id)->whereprice($price)->first();
        if($check_exiting){
            $response['status'] =0;
            $response['message']="This record is already exit.";
            return Response::json($response);
        }

        $objticketclass                 =new TicketClass();
        $objticketclass->name           =$name;
        $objticketclass->price          =$price;
        $objticketclass->operator_id    =$operator_id;
        $objticketclass->save();

        $response['status'] =1;
        $response['message']="Successfully created one record.";
        $ticketclass['id']          =   $objticketclass->id;
        $ticketclass['name']        =   $objticketclass->name;
        $ticketclass['operator_id'] =   $objticketclass->operator_id;
        $ticketclass['operator']    =   Operator::whereid($objticketclass->operator_id)->pluck('name');
        $ticketclass['price']       =   $objticketclass->price;
        $response['ticketclass']    =$ticketclass;
        return Response::json($response);
    }

    public function getticketclass(){
        $operator_id = Input::get('operator_id');
        if(!$operator_id){
            $response['status'] =0;
            $response['message']="Request parameter is required.";
            return Response::json($response);
        }

        $objticketclass =TicketClass::whereoperator_id($operator_id)->orderBy('price','asc')->get();
        $arr_ticketclass=array();
        if($objticketclass){
            foreach ($objticketclass as $ticketclass) {
                $temp['id']             =$ticketclass->id;
                $temp['name']           =$ticketclass->name;
                $temp['operator_id']    =$ticketclass->operator_id;
                $temp['operator']       =Operator::whereid($ticketclass->operator_id)->pluck('name');
                $temp['price']          =$ticketclass->price;
                $arr_ticketclass[]      =$temp;
            }
        }
        $response['status']     =1;
        $response['ticketclass']=$arr_ticketclass;
        return Response::json($response);
    }

    public function getTicketClassInfo($id){
        $objticketclass =TicketClass::find($id);
        $arr_ticketclass=array();
        if($objticketclass){
            $temp['id']             =$objticketclass->id;
            $temp['name']           =$objticketclass->name;
            $temp['operator_id']    =$objticketclass->operator_id;
            $temp['operator']       =Operator::whereid($objticketclass->operator_id)->pluck('name');
            $temp['price']          =$objticketclass->price;
            $arr_ticketclass        =$temp;
        }
        $response['status']     =1;
        $response['ticketclass']=$arr_ticketclass;
        return Response::json($response);
    }

    public function updateTicketClass($id){
        $name   =Input::get('name');
        $price  =Input::get('price');
        $objticketclass =TicketClass::find($id);
        if($objticketclass){
            $objticketclass->name   =$name != null ? $name : $objticketclass->name;
            $objticketclass->price  =$price != null ? $price : $objticketclass->price;
            $objticketclass->update();

            $temp['id']             =$objticketclass->id;
            $temp['name']           =$objticketclass->name;
            $temp['operator_id']    =$objticketclass->operator_id;
            $temp['operator']       =Operator::whereid($objticketclass->operator_id)->pluck('name');
            $temp['price']          =$objticketclass->price;
            $arr_ticketclass        =$temp;
            
            $response['status']     =1;
            $response['message']    ="Successfully update TicketClass info.";
            $response['ticketclass']=$arr_ticketclass;
            return Response::json($response);   
        }else{
            $response['status']     =0;
            $response['message']    ="There is no record to update, same with this id.";
            return Response::json($response);
        }
    }

    public function postSubCinema(){
        $name           =Input::get('name');
        $cinema_id      =Input::get('cinema_id');
        $seat_plan_id   =Input::get('seat_plan_id');
        if(!$name || !$cinema_id || !$seat_plan_id){
            $response['status']     =0;
            $response['message']    ="Request parameters are required.";
            return Response::json($response);
        }

        $check_exiting =SubCinema::wherename($name)->wherecinema_id($cinema_id)->first();
        if($check_exiting){
            $response['status']     =0;
            $response['message']    ="This record is already exit.";
            return Response::json($response);
        }

        $objsubcinema   =new SubCinema();
        $objsubcinema->name             =$name;
        $objsubcinema->cinema_id        =$cinema_id;
        $objsubcinema->seat_plan_id     =$seat_plan_id;
        $objsubcinema->save();

        $response['status']     =1;
        $response['message']    ="Successfully created one record.";
        $temp['id']             =$objsubcinema->id;
        $temp['name']           =$objsubcinema->name;
        $temp['cinema_id']      =$objsubcinema->cinema_id;
        $temp['cinema']         =Cinema::whereid($objsubcinema->cinema_id)->pluck('name');
        $temp['seat_plan_id']   =$objsubcinema->seat_plan_id;
        $temp['seat_plan_name'] =SeatingPlan::whereid($objsubcinema->seat_plan_id)->pluck('name');
        $response['subcinema']  =$temp;
        return Response::json($response);
    }
}
