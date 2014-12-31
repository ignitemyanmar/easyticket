<?php

class GroupSaleController extends \BaseController {

	public function ezDate($d) { 
        $ts = time() - strtotime(str_replace("-","/",$d)); 
        
        if($ts>31536000) $val = round($ts/31536000,0);//.' year'; 
        else if($ts>2419200) $val = round($ts/2419200,0);//.' month'; 
        else if($ts>604800) $val = round($ts/604800,0);//.' week'; 
        else if($ts>86400) $val = round($ts/86400,0);//.' day'; 
        else if($ts>3600) $val = round($ts/3600,0);//.' hour'; 
        else if($ts>60) $val = round($ts/60,0);//.' minute'; 
        // else $val = $ts.' second'; 
        else $val = $ts; 
        
        // if($val>1) $val .= 's'; 
        return $val; 
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$response=array();
		$advertisement=Advertisement::whereposition("Group Sale")->orderBy('id','desc')->limit('5')->get();
		$response['advertisement']=$advertisement;
		$groupitems = GroupItem::where('end_date','>=', Date('Y-m-d'))->orderBy('id','desc')->get();
		$i=0;
		$groupsale = array();
		foreach ($groupitems as $gpi_rows) {
			$item =Item::with(array('itemdetail'))->whereid($gpi_rows->item_id)->first();
			if($item != null){
				$item['number_person'] = $gpi_rows->number_person;
				$item['percentage'] = $gpi_rows->percentage;
				$item['user_id'] = $gpi_rows->user_id;
				$item['user_name'] = User::whereid($gpi_rows->user_id)->pluck('name');
				$returnseconds=$this->ezDate($gpi_rows->end_date);
			    if($returnseconds < 0){
			       $item['end_date'] =substr($returnseconds,1);
			    }else{
			    	$item['end_date'] = 0;
			    }
			    $group_person = GroupPerson::wheregroup_id($gpi_rows->id)->get();
			    $item['left_person'] = $gpi_rows->number_person - count($group_person);
			    $j = 0;
			    foreach ($group_person as $rows) {
			    	$group_person[$j]['name'] = User::whereid($rows->user_id)->pluck('name');
			    	$group_person[$j]['image'] = User::whereid($rows->user_id)->pluck('image');
			    	$j++;
			    }
			    $item['group_person'] = $group_person;
				$group_sale[$i] = $item;
				if(count($item->itemdetail)>0){
					$group_sale[$i]->price = ($item->itemdetail[0]->price - ($item->itemdetail[0]->price * $group_item->percentage)/100) ;
					$group_sale[$i]->oldprice = ($item->itemdetail[0]->oldprice - ($item->itemdetail[0]->oldprice * $group_item->percentage)/100);
				}else{
					$group_sale[$i]->price=0;
					$group_sale[$i]->oldprice=0;
				}
				$i++;
			}
		}

		$response['group_sale']=$group_sale;
		// return Response::json($response);
		return View::make('groupsale.index', array('response'=>$response));
	}

	public function setgroupitem($id){
		$input = Input::all();
		if($input){
			$group_item = GroupItem::whereuser_id(Auth::user()->id)->whereitem_id($id)->where('end_date','>=',date('Y-m-d H:i:s'))->first();
			if(count($group_item) == 0){

				$group_item 				= new GroupItem();
				$group_item->item_id 		= $id;
				$group_item->user_id 		= Auth::user()->id;
				$percentage = $input['number_person'];
				$group_item_price = GroupItemPrice::whereitem_id($id)->wherepercentage($percentage)->first();
				if($group_item_price){

					$group_item->number_person 	= $group_item_price->number_person;
					$group_item->end_date 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s', strtotime(Date('Y-m-d H:i:s'))) . ' + '.$group_item_price->duration));
				
				}else{
					if($percentage  == 3){
						$group_item->number_person 	= 10;
						$group_item->end_date 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s', strtotime(Date('Y-m-d H:i:s'))) . ' + 1 week'));
					}
					if($percentage  == 7){
						$group_item->number_person 	= 30;
						$group_item->end_date 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s', strtotime(Date('Y-m-d H:i:s'))) . ' + 2 week'));
					}
				}
				
				$group_item->percentage		= $input['number_person'];
				$group_item->save();

				GroupSaleMailController::index($id,$group_item->number_person,$group_item->end_date,$group_item->$percentage);

			}

			return Redirect::to(URL::previous());
		}
	}

	public function entertogroup(){
		$group_id = Input::get('group_id');
		$item_id  = Input::get('item_id');
		$color_id = Input::get('color');
		$size_id  = Input::get('size');
		$qty 	  = Input::get('qty');
		
		$group_person = GroupPerson::whereuser_id(Auth::user()->id)->wheregroup_id($group_id)->first();
		if(count($group_person) > 0){
			$group_person->delete();
			$response['status'] = 2;
       		$response['message'] = 'Removed!';
       		$response['redirect_uri']	= URL::previous();
			return Response::json($response);
		}else{
			$group_person = new GroupPerson();
			$group_person->group_id = $group_id;
			$group_person->user_id  = Auth::user()->id;
			$group_person->item_id  = $item_id;
			$group_person->color_id = $color_id != null ? $color_id : 0;
			$group_person->size_id  = $size_id != null ? $size_id : 0;
			$group_person->qty 		= $qty;
			$group_person->save();
			$response['status']  		= 1;
       		$response['message'] 		= 'Thank you!';
       		$response['redirect_uri']	= URL::previous();
			return Response::json($response);
		}
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
		$input = Input::all();
		for ($i=0; $i < count($input['number_person']); $i++) { 
			$group_item_price = GroupItemPrice::whereitem_id($input['item_id'])->wherenumber_person($input['number_person'][$i])->first();
			if($group_item_price){
				$group_item_price->percentage 	= $input['percentage'][$i];
				$group_item_price->duration 	= $input['number_duration'][$i].' '.$input['label_duration'][$i];
				$group_item_price->update();
			}else{
				$group_item_price = new GroupItemPrice();
				$group_item_price->item_id 			= $input['item_id'];
				$group_item_price->number_person 	= $input['number_person'][$i];
				$group_item_price->percentage 		= $input['percentage'][$i];
				$group_item_price->duration 		= $input['number_duration'][$i].' '.$input['label_duration'][$i];
				$group_item_price->save();
			}
			
		}
		$message = "Successfully save your records.";
		return Redirect::to('/item')->with('message',$message);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$group_item_price = GroupItemPrice::whereitem_id($id)->get();
		return View::make('groupsale.add',array('item_id'=>$id,'group_item_price'=>$group_item_price));
	}

	public function getRemoveGroupUser($group_id, $user_id)
	{
		GroupPerson::wheregroup_id($group_id)->whereuser_id($user_id)->delete();
		$response['status'] = 1;
        $response['redirect_uri'] = URL::previous();
        return Response::json($response);
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

	public function getgroupsaleorders(){
		$response=array();
		$group_ids=GroupPerson::groupBy('group_id')->lists('group_id');
		$groupsaleleaders=GroupItem::wherein('id', $group_ids)
									->with(array(
											'user'=>function($q){
												$q->addSelect(array('id','name','email','phone'))->orderBy('id','desc');
											},
											'iteminfo'=>function($q){
												$q->addSelect(array('id','name','image'))->orderBy('id','desc');
											}
										))
									->orderBy('user_id', 'asc')->get();
		if($groupsaleleaders){
			$i=0;
			foreach ($groupsaleleaders as $row) {
				$groupsaleleaders[$i]['currentusercount']=GroupPerson::wheregroup_id($row->id)->count();
				$i++;
			}
		}
		// return Response::json($groupsaleleaders);
		return View::make('groupsale.groupsales', array('response'=>$groupsaleleaders));
	}


}
