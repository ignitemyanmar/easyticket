<?php
class ApiController extends BaseController
{
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

	public function postTicketType(){
		$ticket_type =Input::get('name');
		if(!$ticket_type){
			$response['message']='The request is missing a required parameter.';
			return Response::json($response);
		}
		$checkduplicate =TicketType::wherename($ticket_type)->first();
		if($checkduplicate){
			$response['message'] 	='This record is already exit.';
			return Response::json($response);
		}

		$objtickttype 			=new TicketType();
		$objtickttype->name 	=$ticket_type;
		$objtickttype->save();
		$returnid	=TicketType::max('id');
		$response['message']	="Successfully save one record.";
		$response['id']	=$returnid;
		return Response::json($response);
	}

	public function getTicketType(){
		$objtickettypes		=TicketType::all();
		$tickettypes		=array();
		if($objtickettypes){
			foreach ($objtickettypes as $tickettype) {
				$temp['id']		=$tickettype->id;
				$temp['name']	=$tickettype->name;
				$tickettypes[]	=$temp;
			}
		}
		$response['ticket_types']=$tickettypes;
		return Response::json($response);
	}

	public function getTicketTypeById($id){
		$objtickttype 	=TicketType::find($id);
		$temp=array();
		if($objtickttype){
			$temp['id']		=$objtickttype->id;
			$temp['name']	=$objtickttype->name;
		}
		$tickettypeinfo 		=$temp;
		$response['ticket_type']=$tickettypeinfo;
		return Response::json($response);		
	}

	public function putTicketType($id){
		$name 			=Input::get('name');
		$objtickttype 	=TicketType::find($id);
		if($name){
			$objtickttype->name = $name;
			$objtickttype->update();
		}
		$response['message'] = 'Successfully update record.';
		$tickettypeinfo=TicketType::whereid($id)->first();
		$temp['id']=$id;
		$temp['name']=$tickettypeinfo->name;
		$response['ticket_type']=$temp;
		return Response::json($response);
	}

	public function deleteTicketType($id){
		TicketType::whereid($id)->delete();
		$response['message']='Successfully delete this record.';
		return Response::json($response);
	}


	public function postCity(){
		$objcity 	=new City();
		$name		=Input::get('name');
		$response 	=array();
		if($name=='' || is_null($name)){
			$response['message']='Please passing name parameter.';
			return Response::json($response);
		}
		
		$objcity->name=$name;
		$checkcity=City::wherename($name)->first();
		if($checkcity){
			$response['message']='This city is already exit.';
			return Response::json($response);
		}

		$objcity->save();
		$ret_objcity=City::wherename($name)->first();
		return Response::json($ret_objcity);
	}

	public function getAllCity(){
		$objcity=City::all();
		$cities=array();
		if($objcity){
			foreach ($objcity as $city) {
				$tmp['id']=$city->id;
				$tmp['name']=$city->name;
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

	public function putupdateCity($id){
		$name 		=Input::get('name');
		$objcity 	=City::find($id);
		if($name && $objcity){
			$objcity->name=$name;
			$objcity->update();
		}
		$response['message']='This record have been update.';
		$objcityinfo =City::find($id);
		$temp['id']=$objcityinfo->id;
		$temp['name']=$objcityinfo->name;
		$response['cityinfo']=$temp;
		return Response::json($response);
	}

	public function deleteCity($id){
		$check_has_trips=BusOccurance::wherefrom($id)->orwhere('to','=',$id)->get();
		if(count($check_has_trips)>0){
			$response['message']="You can't delet this city for trips have with this city.";
			return Response::json($response);
		}
		City::whereid($id)->delete();
		$response['message']="Successfully delete this city.";
		return Response::json($response);
	}

	public function postClasses(){
		$name=Input::get('name');
		$operator_id=Input::get('operator_id');
		if(!$name || !$operator_id){
			$response['message']='Required fields are name and operator_id.';
			return Response::json($response);
		}
		$checkclass=Classes::whereoperator_id($operator_id)->wherename($name)->first();
		if($checkclass){
			$response['message']='This record is alreay exit.';
			return Response::json($response);
		}
		$objclasses=new Classes();
		$objclasses->name 		=$name;
		$objclasses->operator_id=$operator_id;
		$objclasses->save();

		$response['message']	='Successfully save one record.';
		$objclasses=Classes::whereoperator_id($operator_id)->orderBy('id','desc')->get();
		if($objclasses){
			foreach ($objclasses as $rows) {
				$temp['id']=$rows->id;
				$temp['name']=$rows->name;
				$temp['operator_id']=$rows->operator_id;
				$classes[]=$temp;
			}
		}
		$response['classes']=$classes;
		return Response::json($response);
	}

	public function getClasses(){
		$operator_id=Input::get('operator_id');
		if(!$operator_id){
			$response['message']='Required field is operator_id.';
			return Response::json($response);
		}
		$classes=array();
		$objclasses=Classes::whereoperator_id($operator_id)->get();
		if($objclasses){
			foreach ($objclasses as $rows) {
				$temp['id']=$rows->id;
				$temp['name']=$rows->name;
				$temp['operator_id']=$rows->operator_id;
				$classes[]=$temp;
			}
		}
		$response['classes']=$classes;
		return Response::json($response);
	}

	public function getClassesinfo($id){
		$objbusclass=array();
		if($id){
			$objbusclass=Classes::whereid($id)->first();
			if(!$objbusclass){
				$response['message']="There is no record for this class.";
				return Response::json($response);
			}
		}
		return Response::json($objbusclass);
	}

	public function getClassesUpdate($id){
		$name 		=Input::get('name');
		$operator_id=Input::get('operator_id');
		$objbusclass=Classes::find($id);
		if(!$objbusclass){
			$response['message']="There is no record for update.";
			return Response::json($response);
		}
		$objbusclass->name=$name;
		$objbusclass->operator_id=$operator_id;
		$objbusclass->update();
		return Response::json($objbusclass);
	}

	public function getClassesdelete($id){
		$checktrip=Trip::whereclass_id($id)->lists('id');
		if(count($checktrip)>0){
			$response['message']="You can't delete this class for have trips with this class.";
			return Response::json($response);
		}
		Classes::find($id)->delete();
		$response['message']="Have been deleted.";
		return Response::json($response);
	}

	public function postOperator(){
		$name			=Input::get('name');
		$address		=Input::get('address');
		$phone			=Input::get('phone');
		$response 		=array();

		if(!$name || !$address || !$phone){
			$response['message']="Required fields are name, address and phone.";
			return Response::json($response);
		}

		$objoperator		=new Operator();
		$checkoperator 		=Operator::wherename($name)->whereaddress($address)->first();
		if($checkoperator){
			$response['message']='This operator is already exit';
			return Response::json($response);
		}

		$objoperator->name		=$name;
		$objoperator->address	=$address;
		$objoperator->phone		=$phone;
		$objoperator->save();

		$ret_objoperator=Operator::wherename($name)->whereaddress($address)->first();
		return Response::json($ret_objoperator);
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
				$operators[]=$tmp;
			}
		}
		$response['operators']=$operators;
		return Response::json($response);
	}

	public function getOperatorInfo($id){
		$objoperator=Operator::whereid($id)->first();
		$operator=array();
		if($objoperator){
			$tmp['id']=$id;
			$tmp['name']=$objoperator->name;
			$tmp['address']=$objoperator->address;
			$tmp['phone']=$objoperator->phone;
			$operator=$tmp;
		}
		$response=$operator;
		return Response::json($response);
	}

	public function putupdateOperator($id){
		$name 			=Input::get('name');
		$address 		=Input::get('address');
		$phone 			=Input::get('phone');
		$objoperator 	=Operator::find($id);
		if($objoperator){
			$objoperator->name 		=$name;
			$objoperator->address 	=$address;
			$objoperator->phone 	=$phone;
			$objoperator->update();
		}
		$response['message']="Successfully update operator information.";
		$temp 	=array();
		$operatorinfo 		=Operator::find($id);
		if($operatorinfo){
			$temp['id']			=$id;
			$temp['name']		=$name;
			$temp['address']	=$address;
			$temp['phone']		=$phone;
		}
		$response['operator'] 	=$temp;
		return Response::json($response);
	}

	public function deleteOperator($id){
		$check_has_trips 	=BusOccurance::whereoperator_id($id)->get();
		$check_has_order 	=SaleOrder::whereoperator_id($id)->get();
		if(count($check_has_trips)>0  || count($check_has_order)>0){
			$response['message'] 	="You can't delete this operator for trips have with this operator.";
			return Response::json($response);
		}
		Operator::whereid($id)->delete();
		$response['message'] ="Successfully delete operator.";
		return Response::json($response);
	}

	public function postCommissiontype(){
    	$name=Input::get('commissiontype');
    	if(!$name){
    		$response['message']='Required fields is commissiontype.';
    		return Response::json($response);
    	}
    	$checkexit=CommissionType::wherename($name)->first();
    	if($checkexit){
    		$response['message']='This record is already exit.';
    		return Response::json($response);
    	}
    	$objcommissiontype=new CommissionType();
    	$objcommissiontype->name=$name;
    	$objcommissiontype->save();

    	$response['message']='Successfully save one record.';
    	$commissiontypelist=CommissionType::orderBy('id', 'desc')->get();
    	$commissiontypes=array();
    	if($commissiontypelist){
    		foreach ($commissiontypelist as $row) {
    			$temp['id']				=$row->id;
    			$temp['commissiontype']=$row->name;
    			$commissiontypes[]=$temp;
    		}
    	}
    	$response['commissiontype_list']=$commissiontypes;
    	return Response::json($response);
    }

    public function getCommissiontype(){
    	$commissiontypelist=CommissionType::orderBy('id', 'desc')->get();
    	$commissiontypes=array();
    	if($commissiontypelist){
    		foreach ($commissiontypelist as $row) {
    			$temp['id']				=$row->id;
    			$temp['commissiontype']=$row->name;
    			$commissiontypes[]=$temp;
    		}
    	}
    	$response['commissiontype_list']=$commissiontypes;
    	return Response::json($response);
    }

    public function getCommissiontypeInfo($id){
    	$commissiontypes=array();
    	$commissiontypes=CommissionType::find($id);
    	return Response::json($commissiontypes);
    }

    public function getCommissiontypeUpdate($id){
    	$name 			=Input::get('name');
    	$commissiontypes=CommissionType::find($id);
    	if(!$commissiontypes){
    		$response['message']="There is no record to update.";
    		return Response::json($response);
    	}
    	if(!$name){
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	$commissiontypes->name=$name;
    	$commissiontypes->update();
    	return Response::json($commissiontypes);
    }

    public function getCommissiontypeDelete($id){
    	$checkcommission=Agent::wherecommission_id($id)->lists('id');
    	if(count($checkcommission)>0){
    		$response['message']="You cann't delete this record.";
    		return Response::json($response);
    	}
    	CommissionType::find($id)->delete();
    	$response['message']="Have been deleted.";
    	return Response::json($response);
    }

    public function postAgentgroup(){
    	$name=Input::get('name');
    	if(!$name){
    		$response['message']='Required fields is name.';
    		return Response::json($response);
    	}
    	$checkexit=AgentGroup::wherename($name)->first();
    	if($checkexit){
    		$response['message']='This record is already exit.';
    		return Response::json($response);
    	}

    	$objagentgroup=new AgentGroup();
    	$objagentgroup->name=$name;
    	$objagentgroup->save();
    	$response['message']='Successfully save one record.';
    	$agentgroup=AgentGroup::wherename($name)->first();
    	$temp['id']=$agentgroup->id;
    	$temp['name']=$agentgroup->name;
    	$response['agentgroup_info']=$temp;
    	return Response::json($response);
    }

    public function getAgentgroup(){
    	$agentgroup=array();
    	$agents=AgentGroup::all();
    	if($agents){
    		foreach ($agents as $rows) {
    			$temp['id']=$rows->id;
    			$temp['name']=$rows->name;
    			$agentgroup[]= $temp;
    		}

    	}
    	return Response::json($agentgroup);
    }

	public function getAgentGroupinfo($id){
    	if(!$id){
    		$response['message'] ="Required fields is missing.";
    		return Response::json($response);
    	}
    	$objagentinfo=Agent::whereagentgroup_id($id)->get();
    	$agents=array();
    	if($objagentinfo){
    		foreach ($objagentinfo as $row) {
    			$temp['id']=$row->id;
    			$temp['name']=$row->name;
	    		$temp['address']=$row->address;
	    		$temp['phone']=$row->phone;
	    		$temp['commission_id']=$row->commission_id;
	    		$temp['commissiontype']=CommissionType::whereid($row->commission_id)->pluck('name');
	    		$temp['commission']=$row->commission;
    			$agents[]=$temp;
    		}
    	}
    	$response['agentgroup']=AgentGroup::whereid($id)->pluck('name');
    	$response['agents']=$agents;
    	return Response::json($response);
    }

    public function getAgentgroupbyid(){
    	$id 	=Input::get('agent_id');
    	if(!$id){
    		$response['message'] ="Required fields is missing.";
    		return Response::json($response);
    	}
    	$objagentinfo=Agent::whereagentgroup_id($id)->get();
    	$agents=array();
    	if($objagentinfo){
    		foreach ($objagentinfo as $row) {
    			$temp['id']=$row->id;
    			$temp['name']=$row->name;
	    		$temp['address']=$row->address;
	    		$temp['phone']=$row->phone;
	    		$temp['commission_id']=$row->commission_id;
	    		$temp['commissiontype']=CommissionType::whereid($row->commission_id)->pluck('name');
	    		$temp['commission']=$row->commission;
    			$agents[]=$temp;
    		}
    	}
    	$response['agentgroup']=AgentGroup::whereid($id)->pluck('name');
    	$response['agents']=$agents;
    	return Response::json($response);
    }

    public function updateAgentGroupinfo($id){
    	$objagentgroup		=	AgentGroup::find($id);
    	if($objagentgroup){
    		$objagentgroup->name=Input::get('name');
    		$objagentgroup->update();
    		$response['message']="Successfully update this record.";
    		$temp['id']			=$objagentgroup->id;
    		$temp['name']		=$objagentgroup->name;
    		$response['agent_group']=$temp;
    		return Response::json($response);
    	}else{
    		$response['message'] ="There is no record for this agent.";
    		return Response::json($response);
    	}
    }

    public function deleteAgentGroupinfo($id){
    	$agent_branche_ids =Agent::whereagentgroup_id($id)->lists('id');
    	if(count($agent_branche_ids)>0){
    		$check_has_order 	=SaleOrder::wherein('agent_id', $agent_branche_ids)->lists('id');
    		if(count($check_has_order)>0){
    			$response['message'] 	="You can't delete this agent_group for have order transactions.";
    			return Response::json($response);
    		}
    	}
    	AgentGroup::whereid($id)->delete();
    	Agent::whereagentgroup_id($id)->delete();
    	$response['message'] ="Successfully delete this agent Group.";
    	return Response::json($response);
    }

	public function postAgent(){
		$agentgroup_id		=Input::get('agentgroup_id');
		$name				=Input::get('name');
		$phone				=Input::get('phone');
		$address			=Input::get('address');
		$commission_id		=Input::get('commission_id');
		$commission			=Input::get('commission');
		$response=array();
		if(!$agentgroup_id || !$name || !$phone || !$address || !$commission_id || !$commission){
			$response['message']='Required fields are agentgroup_id, name, phone, address, commission_id and commission.';
			return Response::json($response);
		}

		$checkagent  =Agent::whereagentgroup_id($agentgroup_id)->wherename($name)->wherephone($phone)->first();
		if($checkagent){
			$response['message']='This agent is already exit.';
			return Response::json($response);
		}
		$objagent						=new Agent();
		$objagent->agentgroup_id 		=$agentgroup_id;
		$objagent->name 				=$name;
		$objagent->phone 				=$phone;
		$objagent->address 				=$address;
		$objagent->commission_id 		=$commission_id;
		$objagent->commission 			=$commission;
		$objagent->save();

		$agentinfo=Agent::wherename($name)->wherephone($phone)->first();
		$tmp['id']				=$agentinfo->id;
		$tmp['agentgroup_id']	=$agentinfo->agentgroup_id;
		$tmp['agentgroup']		=AgentGroup::whereid($agentinfo->agentgroup_id)->pluck('name');
		$tmp['name']			=$agentinfo->name;
		$tmp['phone']			=$agentinfo->phone;
		$tmp['address'] 		=$agentinfo->address;
		$tmp['commission_id']	=$agentinfo->commission_id;
		$tmp['commissiontype']	=CommissionType::whereid($agentinfo->commission_id)->pluck('name');
		$tmp['commission']		=$agentinfo->commission;
		$response['agent']=$tmp;
		return Response::json($response);
	}

	public function getAllAgent(){
		$objagent=Agent::all();
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

	public function getAgentInfo($id){
		$objagent	=Agent::whereid($id)->first();
		$agent=array();
		if($objagent){
			$agent['id']=$objagent->id;
			$agent['agentgroup_id']			=$objagent->agentgroup_id;
			$agent['agentgroup']			=AgentGroup::whereid($objagent->agentgroup_id)->pluck('name');
			$agent['name']					=$objagent->name;
			$agent['phone']					=$objagent->phone;
			$agent['address']				=$objagent->address;
			$agent['commission_id']			=$objagent->commission_id;
			$agent['commissiontype']		=CommissionType::whereid($objagent->commission_id)->pluck('name');
			$agent['commission']			=$objagent->commission;
		}
		$response=$agent;
		return Response::json($response);
	}

	public function updateAgentInfo($id){
		$objagent 	=Agent::find($id);
		if($objagent){
			$objagent->name 			=Input::get('name');
			$objagent->phone 			=Input::get('phone');
			$objagent->address 			=Input::get('address');
			$objagent->commission_id 	=Input::get('commission_id');
			$objagent->commission 		=Input::get('commission');
			$objagent->update();
			
			$response['message'] 			="Successfully update this record.";
			$temp['id']						=$id;
			$temp['group_id']				=$objagent->agentgroup_id;
			$temp['group']					=AgentGroup::whereid($objagent->agentgroup_id)->pluck('name');
			$temp['name']					=$objagent->name;
			$temp['phone']					=$objagent->phone;
			$temp['address']				=$objagent->address;
			$temp['commission_id']			=$objagent->commission_id;
			$temp['commissiontype']			=CommissionType::whereid($objagent->commission_id)->pluck('name');
			$temp['commission']				=$objagent->commission;
			$response['agent']		=$temp;
			return Response::json($response);
		}else{
			$response['message'] 	="This operator doesn't exit.";
			return Response::json($response);
		}
	}

	public function deleteAgent($id){
		$check_has_order =SaleOrder::whereagent_id($id)->get();
		if(count($check_has_order)>0){
			$response['message'] 	="You can't delete this agent for have order transactions.";
			return Response::json($response);
		}
		Agent::whereid($id)->delete();
		$response['message'] ="Successfully delete this record.";
		return Response::json($response);
	}

	public function postTrip(){
		$operator_id		=Input::get('operator_id');
		$from				=Input::get('from');
		$to					=Input::get('to');
		$class_id			=Input::get('class_id');
		$available_day		=Input::get('available_day');
		$price				=Input::get('price');
		$time				=Input::get('time');
		$seat_plan_id		=Input::get('seat_plan_id');

		if(!$operator_id || !$from || !$to || !$time || !$class_id || !$available_day || !$price){
			$response['message']="Required fields are operator_id, from, to, class_id, available_day, time and price.";
			return Response::json($response);
		}
		if($from == $to){
			$response['message']="From and To should not be same.";
			return Response::json($response);
		}

		$objtrip			=new Trip();
		$checkobjtrip		=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->whereclass_id($class_id)->whereavailable_day($available_day)->wheretime($time)->first();
		if($checkobjtrip){
			$response['message']='This trip is already exit';
			return Response::json($response);
		}

		$objtrip->operator_id 	=$operator_id;
		$objtrip->from 			=$from;
		$objtrip->to 			=$to;
		$objtrip->class_id 		=$class_id;
		$objtrip->available_day =$available_day;
		$objtrip->price 		=$price;
		$objtrip->time 		    =$time;
		$objtrip->seat_plan_id	=$seat_plan_id;
		$objtrip->save();

		if($objtrip){
			$tripinfo['id']				=$objtrip->id;
			$tripinfo['from']			=$objtrip->from;
			$tripinfo['to']				=$objtrip->to;
			$tripinfo['class_id']		=$objtrip->class_id;
			$tripinfo['classes']		=Classes::whereid($objtrip->class_id)->pluck('name');
			$tripinfo['operator']		=Operator::whereid($objtrip->operator_id)->pluck('name');
			$tripinfo['from_city']		=City::whereid($objtrip->from)->pluck('name');
			$tripinfo['to_city']		=City::whereid($objtrip->to)->pluck('name');
			$tripinfo['time']			=$objtrip->time;
			$tripinfo['price']			=$objtrip->price;
		}

		$response['message']='Successfully save one record.';
		$response['trip_info']=$tripinfo;
		$access_token=Input::get('access_token');
		$trip_id=$objtrip->id;
		if($objtrip->available_day=='daily' || $objtrip->available_day=='Daily'){
			return $this->postBusOccuranceDailyCreate($operator_id, $trip_id);
			// return Redirect::to('busoccurance/create/daily?access_token='.$access_token.'&trip_id='.$trip_id."&operator_id=".$operator_id);
		}else{
			$availableDays=$objtrip->available_day;
			return $this->postBusOccuranceAutoCreateCustom($operator_id, $trip_id, $availableDays);
		}
		return Response::json($response);
	}

	public function postBusOccuranceDailyCreate($operator_id, $trip_id){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "YGN-9898"; 
		if(!$trip_id){
			$response['message'] ="Required parameter is missing.";
			return Response::json($response);
		}
		
		if(!$operator_id || !$bus_no){
			$response['message']="Request parameter is required.";
			return Response::json($response);
		}
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$currentMonth 		=date("m");
		$nextMonth 			=date("m", strtotime($month));
		$days_in_currentMonth=date("t");
		$days_in_nextMonth  =date("t", strtotime($month));
		

		$checkoccurances    =BusOccurance::wheretrip_id($trip_id)->where('departure_date','=',$today)->first();
		$tocreateoccuranceCMonth=array();
		$tocreateoccurance=array();
		for($j=$checkdate; $j<=$days_in_currentMonth; $j++){
			$tocreateoccuranceCMonth[]=$year.'-'.$currentMonth.'-'.sprintf("%02s", $j);
		}

		for($i=1; $i<=$days_in_nextMonth; $i++){
			$tocreateoccurance[]=$year.'-'.$nextMonth.'-'.sprintf("%02s", $i);
		}
		

		if($checkoccurances){
			if($checkdate>=20){
				if($trip_id && $tocreateoccurance){
					$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}
					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$time 				=$objtrip->time;
					$i=1;
					foreach ($tocreateoccurance as $departure_date) {
						$obj_busoccurance 	=new BusOccurance();
						$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
						$obj_busoccurance->bus_no 			=$bus_no;
						$obj_busoccurance->from 			=$from;
						$obj_busoccurance->to 				=$to;
						$obj_busoccurance->classes 			=$class_id;
						$obj_busoccurance->departure_date 	=$departure_date;
						$obj_busoccurance->departure_time 	=$time;
						$obj_busoccurance->price 			=$price;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
					$response['maxid']=BusOccurance::max('id');
					if($i==1){
						$response['message']="This trip has been created for this month!";
					}else{
						$response['message']="Successfully has been created for this month.";
					}
					return Response::json($response);
				}
			}
		}else{
			if($tocreateoccuranceCMonth){
				$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}
					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$time 				=$objtrip->time;
					$i=1;
					foreach ($tocreateoccuranceCMonth as $departure_date) {
						$obj_busoccurance 	=new BusOccurance();
						$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
						$obj_busoccurance->bus_no 			=$bus_no;
						$obj_busoccurance->from 			=$from;
						$obj_busoccurance->to 				=$to;
						$obj_busoccurance->classes 			=$class_id;
						$obj_busoccurance->departure_date 	=$departure_date;
						$obj_busoccurance->departure_time 	=$time;
						$obj_busoccurance->price 			=$price;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
			}
			if($checkdate>=20){
				if($trip_id && $tocreateoccurance){
					$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}
					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$time 				=$objtrip->time;
					$i=1;
					foreach ($tocreateoccurance as $departure_date) {
						$obj_busoccurance 	=new BusOccurance();
						$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
						$obj_busoccurance->bus_no 			=$bus_no;
						$obj_busoccurance->from 			=$from;
						$obj_busoccurance->to 				=$to;
						$obj_busoccurance->classes 			=$class_id;
						$obj_busoccurance->departure_date 	=$departure_date;
						$obj_busoccurance->departure_time 	=$time;
						$obj_busoccurance->price 			=$price;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
					$response['maxid']=BusOccurance::max('id');
					if($i==1){
						$response['message']="This trip has been created for this month!";
					}else{
						$response['message']="Successfully has been created for this month.";
					}
					return Response::json($response);
				}
			}
			
		}

		$response['message']="This trip has been created for this month.";
		return Response::json($response);		
	}

	public function postBusOccuranceAutoCreateCustom($operator_id, $trip_id, $availableDays){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "YGN-9898"; 
		$jsondays=explode('-', $availableDays);
		if(!$trip_id){
			$response['message'] ="Required parameter is missing.";
			return Response::json($response);
		}
		/** 
		 *change available days to index
		 * @return availabledayindexs
		 */
		$availabledayindexs=array();
		foreach ($jsondays as $value) {
			$index=0;
			switch ($value) {
				case 'Sat':
					$index=6;
					break;
				case 'Sun':
					$index=0;
					break;
				case 'Mon':
					$index=1;
					break;
				case 'Tue':
					$index=2;
					break;
				case 'Wed':
					$index=3;
					break;
				case 'Thurs':
					$index=4;
					break;
				case 'Fri':
					$index=5;
					break;
				
				default:
					# code...
					break;
			}
			$availabledayindexs[]=$index;
		}
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$currentMonth 		=date("m");
		$nextMonth 			=date("m", strtotime($month));
		$days_in_currentMonth=date("t");
		$days_in_nextMonth  =date("t", strtotime($month));
		
		$now = strtotime("now");
		if($checkdate >=15){
			$now = strtotime("now");
			$end_date=strtotime($year.'-'.$nextMonth.'-'.$days_in_nextMonth);
		}else{
			$end_date=strtotime($year.'-'.$currentMonth.'-'.$days_in_currentMonth);
		}

		$customdays=array();
		
		while (date("Y-m-d", $now) != date("Y-m-d", $end_date)) {
		    $day_index = date("w", $now);
		    foreach ($availabledayindexs as $value) {
		    	if ($day_index == $value) {
			    	$customdate=date('Y-m-d', $now);
			    	$customdays[]=$customdate;
			    }
		    }
		    
		    $now = strtotime(date("Y-m-d", $now) . "+1 day");
		}
		
		if(count($customdays)>0 && $trip_id){
			$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($customdays[0])->first();
			if($checkbusoccurances){
				$response['message']="This trip has been created for this month!";
				return Response::json($response);
			}

			$objtrip=Trip::whereid($trip_id)->first();
			if($objtrip){
				$trip_id	=$objtrip->id;
				$operator_id=$objtrip->operator_id;
				$from		=$objtrip->from;
				$to			=$objtrip->to;
				$class_id	=$objtrip->class_id;
				$price 		=$objtrip->price;
				$time 		=$objtrip->time;
				$seat_plan_id=$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;

				$today=date('Y-m-d');
				$count_days=count($customdays);
				$i=1;
				$j=0;
				// return Response::json($available_day);
				foreach ($customdays as $customdaydate) {
					if($i<$count_days){
						$objbusoccurance=new BusOccurance();
						$objbusoccurance->seat_plan_id	=$seat_plan_id;
						$objbusoccurance->bus_no		=$bus_no;
						$objbusoccurance->from			=$from;
						$objbusoccurance->to			=$to;
						$objbusoccurance->classes		=$class_id;
						$objbusoccurance->departure_date=$customdaydate;
						$objbusoccurance->departure_time=$time;
						$objbusoccurance->price			=$price;
						$objbusoccurance->operator_id	=$operator_id;
						$objbusoccurance->trip_id		=$trip_id;
						$objbusoccurance->save();
						$j++;
					}
					$i++;
				}
				$response['message']="Successfully $j record created for this month.";
				return Response::json($response);
			}

		}
		$response['message']="Theres is no record to create1.";
		return Response::json($response);
	}

	public function tripautocreate(){
		if(!Auth::check()){
			$response['message']="You are not allow permission.";
			return Response::json($response);
		}
		$userid=Auth::user()->id;
		$operator_id=Operator::whereuser_id($userid)->pluck('id');
		
		$maxbusoccurance=BusOccurance::max('id');
		$objtrips  =Trip::whereoperator_id($operator_id)->get();
		$response=array();
		if($objtrips){
			foreach ($objtrips as $trip) {
				if(strtolower($trip->available_day)=='daily'){
					$this->cronBusOccuranceDailyCreate($operator_id, $trip->id);
				}else{
					$availableDays=$trip->available_day;
					$this->cronBusOccuranceAutoCreateCustom($operator_id, $trip->id, $trip->available_day);
				}
			}

			$currentmaxid=BusOccurance::max('id');
			if($maxbusoccurance==$currentmaxid){
				$response['message']="This trip already have been created for this month!";
			}else{
				$response['message']="Successfully has been created for this month.";
			}
		}
		return Response::json($response);

	}

	public function cronBusOccuranceDailyCreate($operator_id, $trip_id){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "YGN-9898"; 
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				=date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$nextMonth 			=date("m", strtotime($month));
		$days_in_nextMonth  =date("t", strtotime($month));
		
		$tocreateoccuranceCMonth=array();
		$tocreateoccurance=array();
		for($i=1; $i<=$days_in_nextMonth; $i++){
			$tocreateoccurance[]=$year.'-'.$nextMonth.'-'.sprintf("%02s", $i);
		}
		
		if($trip_id && $tocreateoccurance){
			$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
			if($checkbusoccurances){
			}else{
				$objtrip 			=Trip::whereid($trip_id)->first();
				$tirp_id			=$objtrip->id;
				$seat_plan_id		=$objtrip->seat_plan_id;
				$operator_id		=$objtrip->operator_id;
				$from 				=$objtrip->from;
				$to 				=$objtrip->to;
				$class_id			=$objtrip->class_id;
				$price 				=$objtrip->price;
				$time 				=$objtrip->time;
				$i=1;
				foreach ($tocreateoccurance as $departure_date) {
					$obj_busoccurance 	=new BusOccurance();
					$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
					$obj_busoccurance->bus_no 			=$bus_no;
					$obj_busoccurance->from 			=$from;
					$obj_busoccurance->to 				=$to;
					$obj_busoccurance->classes 			=$class_id;
					$obj_busoccurance->departure_date 	=$departure_date;
					$obj_busoccurance->departure_time 	=$time;
					$obj_busoccurance->price 			=$price;
					$obj_busoccurance->operator_id 		=$operator_id;
					$obj_busoccurance->trip_id 			=$trip_id;
					
					$obj_busoccurance->save();
					$i++;
				}	
			}
			
			
		}

	}

	public function cronBusOccuranceAutoCreateCustom($operator_id, $trip_id, $availableDays){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "YGN-9898"; 
		$jsondays=explode('-', $availableDays);
		/** 
		 *change available days to index
		 * @return availabledayindexs
		 */
		$availabledayindexs=array();
		foreach ($jsondays as $value) {
			$index=0;
			switch ($value) {
				case 'Sat':
					$index=6;
					break;
				case 'Sun':
					$index=0;
					break;
				case 'Mon':
					$index=1;
					break;
				case 'Tue':
					$index=2;
					break;
				case 'Wed':
					$index=3;
					break;
				case 'Thurs':
					$index=4;
					break;
				case 'Fri':
					$index=5;
					break;
				
				default:
					# code...
					break;
			}
			$availabledayindexs[]=$index;
		}
		$today 				=date('Y-m-d');
		$year 				=date('Y');
		$checkdate 			=date('d');
		$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$currentMonth 		=date("m");
		$nextMonth 			=date("m", strtotime($month));
		$days_in_currentMonth=date("t");
		$days_in_nextMonth  =date("t", strtotime($month));
		
			$end_date=strtotime($year.'-'.$nextMonth.'-'.$days_in_nextMonth);
			$start_date=$year.'-'.$nextMonth.'-01';
			$now = strtotime($start_date);
		$customdays=array();
		while (date("Y-m-d", $now) != date("Y-m-d", $end_date)) {
		    $day_index = date("w", $now);
		    foreach ($availabledayindexs as $value) {
		    	if ($day_index == $value) {
			    	$customdate=date('Y-m-d', $now);
			    	$customdays[]=$customdate;
			    }
		    }
		    
		    $now = strtotime(date("Y-m-d", $now) . "+1 day");
		}
		
		if(count($customdays)>0 && $trip_id){
			$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($customdays[0])->first();
			if($checkbusoccurances){
			}else{
				$objtrip=Trip::whereid($trip_id)->first();
				if($objtrip){
					$trip_id	=$objtrip->id;
					$operator_id=$objtrip->operator_id;
					$from		=$objtrip->from;
					$to			=$objtrip->to;
					$class_id	=$objtrip->class_id;
					$price 		=$objtrip->price;
					$time 		=$objtrip->time;
					$seat_plan_id=$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;

					$today=date('Y-m-d');
					$count_days=count($customdays);
					$i=1;
					$j=0;
					// return Response::json($available_day);
					foreach ($customdays as $customdaydate) {
						if($i<$count_days){
							$objbusoccurance=new BusOccurance();
							$objbusoccurance->seat_plan_id	=$seat_plan_id;
							$objbusoccurance->bus_no		=$bus_no;
							$objbusoccurance->from			=$from;
							$objbusoccurance->to			=$to;
							$objbusoccurance->classes		=$class_id;
							$objbusoccurance->departure_date=$customdaydate;
							$objbusoccurance->departure_time=$time;
							$objbusoccurance->price			=$price;
							$objbusoccurance->operator_id	=$operator_id;
							$objbusoccurance->trip_id		=$trip_id;
							$objbusoccurance->save();
							$j++;
						}
						$i++;
					}
				}
			}
		}
	}
	


	public function putTrip($id){
		$objtrip		=Trip::find($id);
		$from			=Input::get('from');
		$to				=Input::get('to');
		$operator_id 	=Input::get('operator_id');
		$class_id 		=Input::get('class_id');
		$available_day 	=Input::get('available_day');
		$price			=Input::get('price');
		$time 			=Input::get('time');

		if(!$from || !$to || !$operator_id || !$class_id || !$price || !$available_day || !$time){
			$response['message']='Required fields are operator_id, from, to, class_id, available_day, price, time.';
			return Response::json($response);
		}
		$checktrip=Trip::where('id','!=',$id)->whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->whereclass_id($class_id)->first();
		if($checktrip){
			$response['message']='Same with exiting record.';
		}
		$objtrip->operator_id	=$operator_id;
		$objtrip->from			=$from;
		$objtrip->to			=$to;
		$objtrip->class_id		=$class_id;
		$objtrip->available_day	=$available_day;
		$objtrip->price			=$price;
		$objtrip->time			=$time;
		$objtrip->update();

		$temp['id']				 	=$objtrip->id;
		$temp['operator_id']	=$objtrip->operator_id;
		$temp['from']			=$objtrip->from;
		$temp['to']				=$objtrip->to;
		$temp['class_id']		=$objtrip->class_id;
		$temp['available_day']	=$objtrip->available_day;
		$temp['price']			=$objtrip->price;
		$temp['time']			=$objtrip->time;
		$response['message'] ="Successfully update this trip.";
		$response['trip_info']=$temp;
		return Response::json($response);
	}

	public function getAllTrip(){
		$operator_id=Input::get('operator_id');
		if($operator_id){
			$objtrips=Trip::whereoperator_id($operator_id)->get();
		}else{
			$objtrips=Trip::all();
		}
		$trips=array();
		$i=0;
		if($objtrips){
			foreach ($objtrips as $trip) {
				if($i==0){
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
				
					$trips[]=$tmp;
				}else{
					$sametrip=0;

					$tmp['id']=$trip->id;
					$tmp['operator_id']=$trip->operator_id;
					$tmp['from']=$trip->from;
					$tmp['to']=$trip->to;
					foreach($trips as $row){
						if($row['from'] == $trip->from && $row['to'] == $trip->to){
							$sametrip +=1;
						}
					}

					$tmp['operator']=Operator::whereid($trip->operator_id)->pluck('name');
					$tmp['from_city']=City::whereid($trip->from)->pluck('name');
					$tmp['to_city']=City::whereid($trip->to)->pluck('name');
					$tmp['class_id']=$trip->class_id;
					$tmp['classes']=Classes::whereid($trip->class_id)->pluck('name');
					$tmp['available_day']=$trip->available_day;
					$tmp['time']=$trip->time;
					$tmp['price']=$trip->price;
					if($sametrip ==0){
						$trips[]=$tmp;
					}
				}
				$i++;
			}
		}
		
		$response['trips']=$trips;
		return Response::json($response);
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

	public  function getTripInfobyfromto()
	{
		$operator_id=Input::get('operator_id');
		$from=Input::get('from');
		$to  =Input::get('to');
		$limit = Input::query('limit') ? Input::query('limit') : 8;
		$offset = Input::query('offset') ? Input::query('offset') : 1;
		$offset = ($offset-1) * $limit;
		if(!$from || !$to){
			$response['message']='Please passing from and to parameters.';
			return Response::json($response);
		}

		if($operator_id){
			$objtriplist=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->take($limit)->skip($offset)->get();
		}else{
			$objtriplist=Trip::wherefrom($from)->whereto($to)->take($limit)->skip($offset)->get();
		}
		$ret_trips=array();
		$trips=array();
		if(count($objtriplist)>0){
			foreach ($objtriplist as $trip) {
				$operator_id			=$trip->operator_id;
				$from					=$trip->from;
				$to						=$trip->to;
				$temp['id']				=$trip->id;
				$temp['class_id']		=$trip->class_id;
				$temp['classes']		=Classes::whereid($trip->class_id)->pluck('name');
				$temp['available_day']	=$trip->available_day;
				$temp['time']			=$trip->time;
				$temp['price']			=$trip->price;
				$temp['seat_plan_id']	=$trip->seat_plan_id;
				$objseat_plan 			=SeatingPlan::whereid($trip->seat_plan_id)->first();
				$temp['name'] 			=$objseat_plan->name;
				$layout['row'] 			=$objseat_plan->row;
				$layout['column'] 		=$objseat_plan->column;
				$objseatinfo=SeatInfo::whereseat_plan_id($trip->seat_plan_id)->get();
				$seat_info=array();
				if($objseatinfo){
					foreach ($objseatinfo as $seats) {
						$seattemp['seat_no']=$seats->seat_no;
						$seattemp['status']=$seats->status;
						$seat_info[]=$seattemp;
					}
				}
				$layout['seat_lists']=$seat_info;
				$temp['seat_layout'] =$layout;
				$trips[]				=$temp;
			}
			$ret_trips['operator_id']=$operator_id;
			$ret_trips['operator']=Operator::whereid($operator_id)->pluck('name');
			$ret_trips['from']=City::whereid($from)->pluck('name');
			$ret_trips['to']=City::whereid($to)->pluck('name');
			$ret_trips['trips']=$trips;
		}
		return Response::json($ret_trips);
	}

	/**
	 * Trip list for define holidays.
	 */
	public function getTripListsbyFromTo()
	{
		$operator_id=Input::get('operator_id');
		$from=Input::get('from');
		$to  =Input::get('to');
		$limit = Input::query('limit') ? Input::query('limit') : 8;
		$offset = Input::query('offset') ? Input::query('offset') : 1;
		$offset = ($offset-1) * $limit;
		if(!$from || !$to){
			$response['message']='Please passing from and to parameters.';
			return Response::json($response);
		}

		if($operator_id){
			$objtriplist=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->take($limit)->skip($offset)->get();
		}else{
			$objtriplist=Trip::wherefrom($from)->whereto($to)->take($limit)->skip($offset)->get();
		}
		$ret_trips=array();
		$trips=array();
		if(count($objtriplist)>0){
			foreach ($objtriplist as $trip) {
				$operator_id			=$trip->operator_id;
				$from					=$trip->from;
				$to						=$trip->to;
				$temp['id']				=$trip->id;
				$temp['class_id']		=$trip->class_id;
				$temp['classes']		=Classes::whereid($trip->class_id)->pluck('name');
				$temp['available_day']	=$trip->available_day;
				$temp['time']			=$trip->time;
				$temp['price']			=$trip->price;
				$temp['seat_plan_id']	=$trip->seat_plan_id;
				$objseat_plan 			=SeatingPlan::whereid($trip->seat_plan_id)->first();
				$temp['name'] 			=$objseat_plan->name;
				$trips[]				=$temp;
			}
			$ret_trips['operator_id']=$operator_id;
			$ret_trips['operator']=Operator::whereid($operator_id)->pluck('name');
			$ret_trips['from']=City::whereid($from)->pluck('name');
			$ret_trips['to']=City::whereid($to)->pluck('name');
			$ret_trips['trips']=$trips;
		}else{
			$ret_trips='There is no occurance for this trip yet.';
		}
		return Response::json($ret_trips);
	}

	public function postTripHolidays(){
		$trip_id 	=Input::get('trip_id');
		$start_date	=Input::get('start_date');
		$end_date	=Input::get('end_date');
		if(!$trip_id || !$start_date || !$end_date){
			$response['message']="Request parameters are required.";
			return Response::json($response);
		}
		$fromtokey=BusOccurance::wheretrip_id($trip_id)->where('from_to_key','!=',0)->groupBy('from_to_key')->orderBy('from_to_key','desc')->pluck('from_to_key');
		$holidaykey=1;
		if($fromtokey){
			$holidaykey=$fromtokey +1;
		}
		$checkbusoccurance =BusOccurance::wheretrip_id($trip_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->get();
		$i=0;
		if($checkbusoccurance){
			foreach ($checkbusoccurance as $objbusoccurance) {
				$objupdateholiday=BusOccurance::whereid($objbusoccurance->id)->first();
				$objupdateholiday->status=4;
				$objupdateholiday->from_to_key=$holidaykey;
				$objupdateholiday->remark="Holiday for this day.";
				$objupdateholiday->update();
				$i++;
			}
			$response['message']="Successfully define holidays for busoccurance. Total record is $i.";
		}else{
			$response['message']="There is no record for define holiday.";
		}
		return Response::json($response);
	}

	public function postTripHolidaysupdate(){
		$trip_id 			=Input::get('trip_id');
		$holiday_id 		=Input::get('holiday_id');
		$start_date			=Input::get('start_date');
		$end_date			=Input::get('end_date');

		if(!$trip_id || !$start_date || !$end_date || !$holiday_id){
			$response['message']="Request parameters are required.";
			return Response::json($response);
		}

		$objbusoccurance=BusOccurance::wheretrip_id($trip_id)->wherefrom_to_key($holiday_id)->wherestatus(4)
							->update(array(
								'status' => 0,
								'from_to_key' => 0,
								'remark' => ''
								));
		$checkbusoccurance =BusOccurance::wheretrip_id($trip_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->wherestatus(0)
							->get();
		$total=count($checkbusoccurance);
		
		$fromtokey=BusOccurance::wheretrip_id($trip_id)->where('from_to_key','!=',0)->groupBy('from_to_key')->orderBy('from_to_key','desc')->pluck('from_to_key');
		$holidaykey=1;
		if($fromtokey){
			$holidaykey=$fromtokey +1;
		}

		if($total>0){
			foreach ($checkbusoccurance as $bus) {
				$objbusoccurances=BusOccurance::whereid($bus->id)->first();
				$objbusoccurances->status=4;
				$objbusoccurances->from_to_key=$holidaykey;
				$objbusoccurances->remark="holiday for this day.";
				$objbusoccurances->update();
			}
			$response['message']="Successfully define holidays for busoccurance. Total record is $total.";
		}else{
			$response['message']="Have been define holiday.";
		}
		return Response::json($response);
	}

	public function postTripHolidaysdelete(){
		$trip_id 		=Input::get('trip_id');
		$holiday_id 	=Input::get('holiday_id');
		if(!$trip_id || !$holiday_id){
			$response['message']="Request parameters are required.";
			return Response::json($response);
		}

		BusOccurance::wheretrip_id($trip_id)->wherefrom_to_key($holiday_id)
						->update(array(
							'status' =>0,
							'from_to_key' =>0,
							'remark' =>''
						));
		$response['message']='Successfully delete holiday for trip.';
		return Response::json($response);
	}
	
	public function getTripHolidaysbyTrip(){
		$limit 			=Input::get('limit') !=null ? Input::get('limit') : 12;
		$offset 		=Input::get('offset') !=null ? Input::get('offset') : 1;
		$offset 		=($offset-1) * $limit;
		$trip_id 		=Input::get('trip_id');
		if(!$trip_id){
			$response['message']="Request parameters are required.";
			return Response::json($response);
		}

		$objtrip= Trip::whereid($trip_id)->first();
		$from='-';
		$to='-';
		$time='-';
		$class='-';
		if($objtrip){
			$from=City::whereid($objtrip->from)->pluck('name');
			$to=City::whereid($objtrip->to)->pluck('name');
			$class=Classes::whereid($objtrip->class_id)->pluck('name');
			$time=$objtrip->time;
		}

		$holidaytriplist_arr =array();
		$holidaytriplist =BusOccurance::wheretrip_id($trip_id)->groupBy('from_to_key')->orderBy('departure_date','asc')->wherestatus(4)->take($limit)->skip($offset)->get();
		if($holidaytriplist){
			$temp_array['trip_id']=$trip_id;
			$temp_array['from_to']=$from.'-'.$to;
			$temp_array['time']=$time;
			$temp_array['class']=$class;
			$holidays=array();
			foreach ($holidaytriplist as $key => $value) {
				$temp['from_date']=$value->departure_date;
				$to_date=$value->departure_date;
				$to_date=BusOccurance::wheretrip_id($trip_id)->wherefrom_to_key($value->from_to_key)->orderBy('departure_date','desc')->pluck('departure_date');
				$temp['to_date']=$to_date;
				$temp['holiday_id']=$value->from_to_key;
				$holidays[]=$temp;
			}
			$temp_array['holidays']=$holidays;

			$holidaytriplist_arr=$temp_array;
		}
		
		return Response::json($holidaytriplist_arr);
	}

	public function getTripHolidays(){
		$limit 			=Input::get('limit') !=null ? Input::get('limit') : 12;
		$offset 		=Input::get('offset') !=null ? Input::get('offset') : 1;
		$offset 		=($offset-1) * $limit;
		$operator_id 	=Input::get('operator_id');
		$from			=Input::get('from');
		$to				=Input::get('to');
		$time			=Input::get('time');
		$start_date		=Input::get('start_date');
		$end_date		=Input::get('end_date');

		$holidaytriplist =array();
		if($time && $start_date && $end_date){
			$holidaytriplist=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->wheretime($time)->where('departure_date','>=','start_date')->where('departure_date','<=','end_date')->take($limit)->skip($offset)->get();
		}elseif($time && !$start_date && !$end_date){
			$holidaytriplist=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->wheretime($time)->take($limit)->skip($offset)->get();
		}else{
			$holidaytriplist=Trip::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->take($limit)->skip($offset)->get();
		}
		$i=0;
		if($holidaytriplist){
			foreach ($holidaytriplist as $trip) {
				$holidaytriplist[$i]=$trip;
				$holidaytriplist[$i]['holidays']=BusOccurance::wheretrip_id($trip->id)->wherestatus(4)->count();
				$i++;
			}
		}
		return Response::json($holidaytriplist);
	}


	public function getTripReportByDateRange(){
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date');
		$end_date  				=Input::get('end_date');
		$departure_time  		=Input::get('departure_time');
		
		if(!$operator_id || !$from || !$to || !$start_date || !$end_date || !$departure_time){
			$response['message']='Required fields are operator_id, from, to, start_date, end_date and departure_time';
			return Response::json($response);
		}

		$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->where('departure_date','>=',$start_date)
								->where('departure_date','<=',$end_date)
								->wheredeparture_time($departure_time)->lists('id');
		if($busoccurance_ids){
			$orderids=SaleItem::wherein('busoccurance_id',$busoccurance_ids)->groupBy('order_id')->lists('order_id');
			$orderdates=array();
			if($orderids){
				$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->groupBy('orderdate')
								->lists('orderdate');
			}

			if(count($orderdates) >0){
				foreach ($orderdates as $order_date) {
					$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->lists('id');
					$occuranceids_bydaily	=SaleItem::wherein('order_id', $orderids_bydaily)->groupBy('busoccurance_id')->lists('busoccurance_id');
					// return Response::json($occuranceids_bydaily);
					$amount=0;
					$seat_total=0;
					if($occuranceids_bydaily){
						foreach ($occuranceids_bydaily as $occuranceid) {
							$tickets=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$orderids_bydaily)->count('id');
							$seatplanid=BusOccurance::whereid($occuranceid)->pluck('seat_plan_id');
							if($seatplanid){
								$seat_counts=SeatInfo::whereseat_plan_id($seatplanid)->count('id');
								$seat_total +=$seat_counts;
							}
							$price=BusOccurance::whereid($occuranceid)->pluck('price');
							$amount +=$tickets * $price;
						}
					}
					$soldtickets 			=SaleItem::wherein('order_id', $orderids_bydaily)->count('id');
					$temp['date']		=$order_date;
					$temp['purchased_total_seat']	=$soldtickets;
					$temp['total_seat']	=$seat_total;
					$temp['total_amout']			=$amount;
					$report_info[]=$temp;
				}
			}
		}
		return Response::json($report_info);
	}


	public function postBusOccurance(){
		$operator_id		=Input::get('operator_id');
		$seat_no			=Input::get('seat_no');
		$seat_plan_id		=Input::get('seat_plan_id');
		$bus_no				=Input::get('bus_no');
		$departure_date		=Input::get('departure_date');
		$departure_time		=Input::get('departure_time');
		$arrival_time		=Input::get('arrival_time');
		$from				=Input::get('from');
		$to					=Input::get('to');
		$classes			=Input::get('classes');
		$price				=Input::get('price');

		if(!$seat_plan_id || !$seat_no ||  !$from || !$to || !$departure_date || !$departure_time ||  !$arrival_time || !$operator_id || !$price || !$classes){
			$response['message']="Required fields are operator_id, seat_no, seat_plan_id, from, to, departure_date, departure_time, arrival_time, price and classes.";
			return Response::json($response);
		}

		$checkbusoccurance =BusOccurance::whereseat_plan_id($seat_plan_id)
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_date($departure_date)
										->wheredeparture_time($departure_time)
										->whereclasses($classes)
										->first();
		if($checkbusoccurance){
			$response['message']='This record is already exit';
			return Response::json($response);
		}
		$objbusoccurance=new BusOccurance();
		$objbusoccurance->operator_id 	=$operator_id;
		$objbusoccurance->seat_no 			=$seat_no;
		$objbusoccurance->from 			=$from;
		$objbusoccurance->to 			=$to;
		$objbusoccurance->departure_date =$departure_date;
		$objbusoccurance->departure_time =$departure_time;
		$objbusoccurance->arrival_time 	 =$arrival_time;
		$objbusoccurance->seat_plan_id	 =$seat_plan_id;
		$objbusoccurance->bus_no 	=$bus_no;
		$objbusoccurance->classes 	=$classes;
		$objbusoccurance->price 	=$price;
		$objbusoccurance->save();

		$response['message']="Successfully save your occurrent.";
		$response['occurrent_id']=BusOccurance::max('id');
		return Response::json($response);
	}

	public function getBusOccuranceInfo($id){
		$objbusoccurance =BusOccurance::whereid($id)->first();
		if($objbusoccurance){
			$objbusoccurance['seat_plan_name']=SeatingPlan::whereid($objbusoccurance->seat_plan_id)->pluck('name');
			$objbusoccurance['from_city']=City::whereid($objbusoccurance->from)->pluck('name');
			$objbusoccurance['to_city']=City::whereid($objbusoccurance->to)->pluck('name');
			$objbusoccurance['class']=Classes::whereid($objbusoccurance->classes)->pluck('name');
			$objbusoccurance['operator']=Operator::whereid($id)->pluck('name');
		}
		return Response::json($objbusoccurance);
	}

	public function updateBusOccurance($id){
		$objbusoccurance=BusOccurance::whereid($id)->first();
		$seat_no 		=Input::get('seat_no') !=null ? Input::get('seat_no') : $objbusoccurance->seat_no;
		$seat_plan_id 	=Input::get('seat_plan_id') !=null ? Input::get('seat_plan_id') : $objbusoccurance->seat_plan_id;;
		$bus_no 		=Input::get('bus_no') !=null ? Input::get('bus_no') : $objbusoccurance->bus_no;;
		$from 			=Input::get('from') !=null ? Input::get('from') : $objbusoccurance->from;;
		$to 			=Input::get('to') !=null ? Input::get('to') : $objbusoccurance->to;;
		$classes 		=Input::get('class_id') !=null ? Input::get('class_id') : $objbusoccurance->class_id;;
		$departure_date =Input::get('departure_date') !=null ? Input::get('departure_date') : $objbusoccurance->departure_date;;
		$departure_time =Input::get('departure_time') !=null ? Input::get('departure_time') : $objbusoccurance->departure_time;;
		$price 			=Input::get('price') !=null ? Input::get('price') : $objbusoccurance->price;;
		$operator_id 	=Input::get('operator_id') !=null ? Input::get('operator_id') : $objbusoccurance->operator_id;;
		if(!$bus_no || !$classes || !$departure_date || !$departure_time || !$price || !$operator_id){
			$response['message']='Request parameter are required.';
			return Response::json($response);
		}
		if($objbusoccurance){
			$objbusoccurance->seat_no 		=$seat_no;
			$objbusoccurance->seat_plan_id	=$seat_plan_id;
			$objbusoccurance->bus_no		=$bus_no;
			$objbusoccurance->from			=$from;
			$objbusoccurance->to			=$to;
			$objbusoccurance->classes		=$classes;
			$objbusoccurance->departure_date=$departure_date;
			$objbusoccurance->departure_time=$departure_time;
			$objbusoccurance->price			=$price;
			$objbusoccurance->operator_id 	=$operator_id;
			$objbusoccurance->update();
			$response['message']="Successfully update this busoccurance.";
		}else{
			$response['message']="Can't update this busoccuranceids. Try agrain.";
		}
		return Response::json($response);
	}

	public function deleteBusOccurance($id){
		$checkorder =SaleItem::wherebusoccurance_id($id)->first();
		if($checkorder){
			$response['message'] ="Order tickets have for this bus.";
		}
		$response['message']='-';
		$objbusoccurance=BusOccurance::find($id);
		$temp=array();
		if($objbusoccurance){
			$objbusoccurance->status= 3;
			$objbusoccurance->remark='Deleted';
			$objbusoccurance->update();
			$temp['id'] =$objbusoccurance->id;
			$temp['seat_no'] =$objbusoccurance->seat_no;
			$temp['seat_plan_id'] =$objbusoccurance->seat_plan_id;
			$temp['bus_no'] =$objbusoccurance->bus_no;
			$temp['from'] =$objbusoccurance->from;
			$temp['to'] =$objbusoccurance->to;
			$temp['classes'] =$objbusoccurance->classes;
			$temp['departure_date'] =$objbusoccurance->departure_date;
			$temp['departure_time'] =$objbusoccurance->departure_time;
			$temp['price'] =$objbusoccurance->price;
			$temp['operator_id'] =$objbusoccurance->operator_id;
			$temp['trip_id'] =$objbusoccurance->trip_id;
			$temp['status'] =$objbusoccurance->status;
			$temp['remark'] =$objbusoccurance->remark;
		}
		$response['busoccuranceidinfo']=$temp;

		// $response['busoccuranceidinfo']=$objbusoccurance;
		return Response::json($response);
	}

	public function postBusOccuranceAutoCreate(){
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "YGN-9898"; 
		$trip_id	=Input::get('trip_id');
		$operator_id=Input::get('operator_id');
		if(!$trip_id){
			$response['message'] ="Required parameter is missing.";
			return Response::json($response);
		}
		
		if(!$operator_id || !$bus_no){
			$response['message']="Request parameter is required.";
			return Response::json($response);
		}

		$today 				=date('Y-m-d');
		$currentYear		=date('Y');
		$currentMonth		=date('m');
		$currentDay			=date('d');
		$start_date 		=$currentDay;
		$time 				= strtotime($currentYear."-".$currentMonth."-".$start_date);
		$final 				= date("Y-m-d", strtotime("+1 month", $time));
		$nextMonth 			= date("m", strtotime("+1 month", $time));
		$currentMandY 		= date("Y-m-");
		$currentMdays 		= date("t");
		$nextMdays		 	= date("t",strtotime("+1 month", $time));
		$nextMandY 			= date("Y-m-",strtotime("+1 month", $time));
		$holdays_forthisM	=array();
		$currentmonthHolidays=array();
		if($operator_id){
			$currentmonthHolidays =Holiday::whereoperator_id($operator_id)->where('month','=',$currentMonth)->get(array('month','holiday'));
		}

		if(count($currentmonthHolidays)>0){
				foreach ($currentmonthHolidays as $keys=> $hdays) {
					$hdays =$hdays['holiday'];
					$day=substr($hdays, 8,2);
					$holdays_forthisM[]=$day;
				}
		}

		$available_day=array();
		for($j=$currentDay; $j<=$currentMdays; $j++){
			$hasholiday=0;
			if($holdays_forthisM){
				foreach($holdays_forthisM as $hday){
					if($hday == $j){
						$hasholiday=1;
					}
				}
				
			}
			if($hasholiday ==0){
				$twodigits=sprintf("%02s", $j);
				$available_day[]=$twodigits;
			}
			
		}

		$nextmonthHolidays=array();
		if($operator_id){
			$nextmonthHolidays =Holiday::whereoperator_id($operator_id)->where('month','=',$nextMonth)->get(array('month','holiday'));
		}
		if(count($nextmonthHolidays)>0){
				foreach ($nextmonthHolidays as $keys=> $hdays) {
					$hdays =$hdays['holiday'];
					$day=substr($hdays, 8,2);
					$holdays_fornextM[]=$day;
				}
		}

		for($i=1; $i<=$start_date; $i++){
			$hasholiday=0;
			if($holdays_fornextM){
				foreach($holdays_fornextM as $hday){
					if($hday == $i){
						$hasholiday=1;
					}
				}
			}
			if($hasholiday==0){
				$twodigits=sprintf("%02s", $i);
				$available_day[]=$twodigits;
			}
		}
		// return Response::json($available_day);
		$tripid 		=Input::get('trip_id');
		$i=0;
		// return Response::json($available_day);
		if($tripid && $available_day){
			$objtrip=Trip::whereid($tripid)->first();
			$tirp_id			=$objtrip->id;
			$seat_plan_id		=$objtrip->seat_plan_id;
			$operator_id		=$objtrip->operator_id;
			$from 				=$objtrip->from;
			$to 				=$objtrip->to;
			$class_id			=$objtrip->class_id;
			$price 				=$objtrip->price;
			$time 				=$objtrip->time;
			$busoccurance_ids   =BusOccurance::wheretrip_id($trip_id)->wheredeparture_time($time)->wherefrom($from)->whereto($to)->where('departure_date','>',$today)->count('id');
				
				if($busoccurance_ids < 5){
					foreach ($available_day as $day) {
						$departure_date 	=$currentMandY.$day;
						$checkbusoccurance  =BusOccurance::wheretrip_id($trip_id)->wherefrom($from)->whereto($to)->where('departure_date','>=',$departure_date)->wheredeparture_time($time)->whereoperator_id($operator_id)->get();
						if(count($checkbusoccurance)<1){
							$obj_busoccurance 	=new BusOccurance();
							$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
							$obj_busoccurance->bus_no 			=$bus_no;
							$obj_busoccurance->from 			=$from;
							$obj_busoccurance->to 				=$to;
							$obj_busoccurance->classes 			=$class_id;
							$obj_busoccurance->departure_date 	=$departure_date;
							$obj_busoccurance->departure_time 	=$time;
							$obj_busoccurance->price 			=$price;
							$obj_busoccurance->operator_id 		=$operator_id;
							$obj_busoccurance->trip_id 			=$trip_id;
							
							$obj_busoccurance->save();
							$i++;
						}
					}
					$response['maxid']=BusOccurance::max('id');
					if($i==0){
						$response['message']="This trip has been created for this month!";
					}else{
						$response['message']="Successfully $i records has been created for this month.";
					}
					return Response::json($response);
				}
		}	

		$response['message']="This trip has been created for this month.";
		return Response::json($response);		
	}

	

	
	public function getBusOccuranceSchedule($type){
		$objtripids=Trip::whereavailable_day($type)->lists('id');
		// dd($objtripids);
		if(count($objtripids)>0){
			$objbusoccurance=BusOccurance::wherein('trip_id',$objtripids)->get();
			return Response::json($objbusoccurance);
		}
		
	}

	public function getBusOccuranceList(){
		$objbusoccurance=BusOccurance::all();
		$busoccurancelist=array();
		if($objbusoccurance){
			foreach ($objbusoccurance as $row) {
				$tmp['id']=$row->id;
				$tmp['from']=$row->from;
				$tmp['to']=$row->to;
				$from=$row->from;
				$to=$row->to;
				if($from && $to){
					$from=City::whereid($from)->pluck('name');
					$to  =City::whereid($to)->pluck('name');
					$tmp['trip']=$from.'-'.$to;
				}
				$tmp['bus_no']=$row->bus_no;
				$tmp['seat_plan_id']=$row->seat_plan_id;
				// $tmp['available_day']=$row->available_day;
				$tmp['classes']=$row->classes;
				$tmp['departure_time']=$row->departure_time;
				$tmp['arrival_time']=$row->arrival_time;
				$tmp['price']=$row->price;
				$tmp['operator_id']=$row->operator_id;
				$tmp['operator']=Operator::whereid($row->operator_id)->pluck('name');
				
				$objseat 		=SeatingPlan::whereid($row->seat_plan_id)->first();
				if($objseat){
					$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
					$seatplan['row']  			 =$objseat->row;
					$seatplan['column']  		 =$objseat->column;
					$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
				}else{
					$seatplan['seat_layout_id']  =null;
					$seatplan['row']  			 =null;
					$seatplan['column']  		 =null;	
				}
				
				// $seatplan['seatlist']		 =$objseat->seat_list;

				$seatinfo 	=array();
				if($objseatinfo){
					foreach ($objseatinfo as $rows) {
						$temp['id']			=$rows->id;
						$temp['seat_no']	=$rows->seat_no;
						$temp['status']		=$rows->status;
						$seatinfo[] 		=$temp;
					}
					
				}
				$seatplan['seat_info']  =$seatinfo;
				$tmp['seat_plan']		=$seatplan;
				

				$busoccurancelist[]=$tmp;
			}
		}
		$response['busoccurancelist']=$busoccurancelist;
		return Response::json($response);
	}

	public function postSeatLayout(){
		$ticket_type_id=Input::get('ticket_type_id');
		$row=Input::get('row');
		$column=Input::get('column');
		$seat_list=Input::get('seat_list');

		if(!$ticket_type_id || !$row || !$column || !$seat_list){
			$response['message']='Required fields are ticket_type_id, row, column and seat_list.';
			return Response::json($response);
		}

		$checkseatplan=SeatingLayout::whereticket_type_id($ticket_type_id)
									->whererow($row)
									->wherecolumn($column)
									->whereseat_list($seat_list)->first();
		if($checkseatplan){
			$response['message']='This record is already exit';
			return Response::json($response);
		}		

		$objseatlayout					=new SeatingLayout();
		$objseatlayout->ticket_type_id	=$ticket_type_id;
		$objseatlayout->row	=$row;
		$objseatlayout->column	=$column;
		$objseatlayout->seat_list	=$seat_list;
		$objseatlayout->save();

		$response['message']='Successfully saved';
		$response['seat_layout_id']=SeatingLayout::max('id');
		return Response::json($response);
	}

	public function getSeatLayouts(){
		$objseatlayouts=SeatingLayout::all();
		$layouts=array();
		if($objseatlayouts){
			foreach ($objseatlayouts as $seatlayout) {
				$temp['id']=$seatlayout->id;
				$temp['row']=$seatlayout->row;
				$temp['column']=$seatlayout->column;
				$temp['seat_list']=$seatlayout->seat_list;
				$layouts[]=$temp;
			}
		}
		$response['seat_layouts']=$layouts;
		return Response::json($response);
	}

	public function getSeatplans(){
		$operator_id=Input::get('operator_id');
		if(!$operator_id){
			$response['message']="operator_id is required.";
			return Response::json($response);
		}
		$objseatplans=SeatingPlan::whereoperator_id($operator_id)->get();
		$seat_plans=array();
		if($objseatplans){
			foreach ($objseatplans as $rows) {
				$operator_id=$operator_id;
				$temp['id']=$rows->id;
				$temp['seat_layout_id']=$rows->seat_layout_id;
				$objseatlayout=SeatingLayout::whereid($rows->seat_layout_id)->first();
				if($objseatlayout){
					$temp['seat_list']=$objseatlayout->seat_list;
					$temp['row']=$objseatlayout->row;
					$temp['column']=$objseatlayout->column;
				}
				$objseatinfo=SeatInfo::whereseat_plan_id($rows->id)->get();
				$seat_info=array();
				if($objseatinfo){
					foreach ($objseatinfo as $seats) {
						$seattemp['seat_no']=$seats->seat_no;
						$seattemp['status']=$seats->status;
						$seat_info[]=$seattemp;
					}
				}
				$temp['seat_lists']=$seat_info;
				$seat_plans[]=$temp;
			}
		}
		$response['operator_id']=$operator_id;
		$response['operator']	=Operator::whereid($operator_id)->pluck('name');
		$response['seat_plans']=$seat_plans;
		return Response::json($response);
	}

	public function postSeatplan(){
		$ticket_type_id			=Input::get('ticket_type_id');
		$operator_id			=Input::get('operator_id');
		$row					=Input::get('row');
		$column					=Input::get('column');
		$seat_layout_id			=Input::get('seat_layout_id');
		$seat_liststring		=Input::get('seat_list');
		$seat_lsit 				=json_decode($seat_liststring);

		if(!$ticket_type_id || !$operator_id || !$row || !$column || !$seat_layout_id){
			$response['message']='Require fields are ticket_type_id, operator_id, row, column, seat_layout_id, seat_list';
			return Response::json($response);
		}

		if(!$seat_lsit){
			$response['message']= 'Seat list fromat is wrong.';
			return Response::json($response);
		}

		$checkseatplan=SeatingPlan::whereticket_type_id($ticket_type_id)
									->whereoperator_id($operator_id)
									->whererow($row)
									->wherecolumn($column)
									->whereseat_layout_id($seat_layout_id)->first();
		if($checkseatplan){
			$response['message']='This record is already exit';
			return Response::json($response);
		}						

		$objseatingplan						= new SeatingPlan();
		$objseatingplan->ticket_type_id 	=$ticket_type_id;
		$objseatingplan->operator_id 		=$operator_id;
		$objseatingplan->row 				=$row;
		$objseatingplan->column 			=$column;
		$objseatingplan->seat_layout_id 	=$seat_layout_id;
		$objseatingplan->seat_list 			=$seat_liststring;
		$objseatingplan->save();

		$seatplanid=SeatingPlan::max('id');
		foreach ($seat_lsit as $seat) {
			$objseatinfo=new SeatInfo();
			$objseatinfo->seat_plan_id=$seatplanid;
			$objseatinfo->seat_no=$seat->seat_no;
			$objseatinfo->status=$seat->status;
			$objseatinfo->save();
		}
		$operatorname						=Operator::whereid($operator_id)->pluck('name');
		$total_seat 						=SeatInfo::whereseat_plan_id($seatplanid)->wherestatus(1)->count();
		$seat_plan_name						=$operatorname.'-'.$total_seat;
		$objseatplanupdate					=SeatingPlan::whereid($seatplanid)->first();
		$objseatplanupdate->name=$seat_plan_name;
		$objseatplanupdate->update();

		$response['message']="Successfully save";
		return Response::json($response);
	}

	public function getSeatPlan(){
		$now_date = date("Y-m-d H:i:s");
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->where('name','=','')->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}
    	
		$ticket_type_id=Input::get('ticket_type_id');
		$operator_id=Input::get('operator_id');
		$date=Input::get('date');
		$time=Input::get('time');
		$from=Input::get('from_city');
		$to=Input::get('to_city');
		$todaydate=date('Y-m-d');

		if(!$operator_id || !$from || !$to){
			$response['message']="PleaseRequire fields are operator_id, from_city and to_city.";
			return Response::json($response);
		}
		if($date < $todaydate){
			$response['message']="Departure Date should be greater than or equal today date.";
			return Response::json($response);
		}

		$buslist=array();

		if($date && !$time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->where('departure_date','=',$date)->get();
		}
		elseif($date && $time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->where('departure_date','=',$date)->where('departure_time','=',$time)->get();
		}
		elseif(!$date && !$time)
		{
			$buslist=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->get();
		}else{
		}

		$busoccurancelist=array();
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
				$seatplan['classes']=$row->classes;
				$seatplan['departure_date']=$row->departure_date;
				$seatplan['departure_time']=$row->departure_time;
				$seatplan['arrival_time']=$row->arrival_time;
				$seatplan['price']=$row->price;
				$seatplan['operator_id']=$row->operator_id;
				

				$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
				$seatplan['row']  			 =$objseat->row;
				$seatplan['column']  		 =$objseat->column;
				// $seatplan['seatlist']		 =$objseat->seat_list;

				$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
				$seatinfo 	=array();
				if($objseatinfo){
					foreach ($objseatinfo as $rows) {
						$temp['id']			=$rows->id;
						$temp['seat_no']	=$rows->seat_no;
						$busoccurance_id 	=$row->id;
						$checkoccupied_seat =SaleItem::wherebusoccurance_id($busoccurance_id)->whereseat_no($rows->seat_no)->first();
						if($checkoccupied_seat){
							$temp['status']		=2;
						}else{
							$temp['status']		=$rows->status;
						}
						$seatinfo[] 		=$temp;
					}
				}

				$tmp['operator_id']=$row->operator_id;
				$tmp['operator']=Operator::whereid($row->operator_id)->pluck('name');
				
				$seatplan['seat_list'] =$seatinfo;

				$tmp['seat_plan'][]	   =$seatplan;
				$busoccurancelist[]=$tmp;
			}
		}
		$response=$busoccurancelist;

		return Response::json($response);
	}

	public function getTime(){
		$operator_id=Input::get('operator_id');
		$from_city=Input::get('from_city');
		$to_city=Input::get('to_city');
		$ticket_type_id=Input::get('ticket_type_id');
		if($operator_id && $from_city && $to_city){
			$objtrip=BusOccurance::whereoperator_id($operator_id)->wherefrom($from_city)->whereto($to_city)->groupBy('departure_time')->get();
		}elseif($operator_id && !$from_city && !$to_city){
			$objtrip=BusOccurance::whereoperator_id($operator_id)->groupBy('departure_time')->get();
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
		return Response::json($times); 
	}


    public function postSale(){
    	$now_date = date("Y-m-d H:i:s");
		$currentDate = strtotime($now_date);
		$futureDate = $currentDate+(60*15);//add 15 minutes for expired_time;
		$expired_date = date("Y-m-d H:i:s", $futureDate);

    	$operator_id=Input::get('operator_id');
    	$agent_id=Input::get('agent_id');
    	$date=Input::get('date');
    	$time=Input::get('time');
    	$from_city=Input::get('from_city');
    	$to_city=Input::get('to_city');
    	$seat_liststring=Input::get('seat_list');
    	$order_type=Input::get('order_type');

    	if(!$operator_id || !$from_city || !$to_city || !$seat_liststring){
    		$response['message']='Required fields are operator_id, from_city, to_city and seat_lsit';
    		return Response::json($response);
    	}

    	$seat_list=json_decode($seat_liststring);
    	if(count($seat_list)<1){
    		$response['message']='Seat_list format is wrong.';
    		return Response::json($response);
    	}

    	$available_tickets=0;
    	$available_seats=array();
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->where('name','=','')->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}
    	foreach ($seat_list as $rows) {
    		$busoccuranceid=$rows->busoccurance_id;
    		$seat_plan_id=BusOccurance::whereid($busoccuranceid)->pluck('seat_plan_id');
    		$objseatinfo=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows->seat_no)->first();
    		$chkstatus=SaleItem::wherebusoccurance_id($busoccuranceid)->whereseat_no($rows->seat_no)->first();
    		$canbuy=true;
    		if($chkstatus){
	    		$canbuy=false;
    		}
    		else{
    			$canbuy=true;
    			$tmp['seat_no']			=$rows->seat_no;
    			$tmp['busoccurance_id']	=$rows->busoccurance_id;
    			$available_seats[]=$tmp;
    		}
	    	$temp['seat_id']=$objseatinfo->id;
	    	$temp['seat_no']=$objseatinfo->seat_no;
    		$temp['can_buy']=$canbuy;
    		$temp['bar_code']=11111111111;
    		$tickets[]=$temp;
    	}
    	$max_order_id=null;

    	if(count($available_seats) == count($seat_list)){
	    	$response['message']="Successfully your purchase or booking tickets.";
    		$can_buy=true;
	    		$objsaleorder=new SaleOrder();
	    		$objsaleorder->orderdate=date('Y-m-d');
	    		$objsaleorder->agent_id=$agent_id ? $agent_id : 0;
	    		$objsaleorder->operator_id=$operator_id;
	    		$objsaleorder->expired_at=$expired_date;
	    		$objsaleorder->save();
	    		$max_order_id=SaleOrder::max('id');
	    		
	    		foreach ($available_seats as $rows) {
	    			$objsaleitems					=new SaleItem();
	    			$objsaleitems->order_id 		=$max_order_id;
	    			$objsaleitems->busoccurance_id 	=$rows['busoccurance_id'];
	    			$objsaleitems->seat_no			=$rows['seat_no'];
	    			$objsaleitems->operator			=$operator_id;
	    			$objsaleitems->save();
	    		}
    	}else{
	    	$response['message']="Unfortunately your purchase or booking some tickets have been taken by another customer.";
    		$can_buy=false;
    	}
    	$response['sale_order_no']=$max_order_id;
    	$response['can_buy']=$can_buy;
    	$response['tickets']=$tickets;
    	return Response::json($response);
    }

    public function deleteSaleOrder($id){
    	$order_no=Input::get('sale');
    	if(!$id){
    		$response['message']='sale_order_no is null.';
    		return Response::json($response);
    	}
    	SaleOrder::whereid($id)->delete();
    	SaleItem::whereorder_id($id)->delete();
    	$response['message'] = 'Have been deleted.';
    	return Response::json($response);
    }

    public function postSaleComfirm(){
    	$sale_order_no	=Input::get('sale_order_no');
    	$agent_id 		=Input::get('agent_id');
    	$buyer_name		=Input::get('buyer_name');
    	$agent_id		=Input::get('agent_id');
    	$phone			=Input::get('phone');
    	$nrc_no			=Input::get('nrc_no');
    	$tickets 		=Input::get('tickets');
    	if(!$sale_order_no || !$buyer_name  || !$phone){
    		$response['message']="Required fields are sale_order_no, buyer_name, address, phone.";
			return Response::json($response);
	   	}
    	$json_tickets=json_decode($tickets);
    	if(!$json_tickets){
    		$response['message']='Tickets format is wrong.';
    		return Response::json($response);
    	}

    	if($json_tickets){
    		$objsaleorder=SaleOrder::find($sale_order_no);
    		$objsaleorder->orderdate=date('Y-m-d');
    		$objsaleorder->agent_id=$agent_id;
    		$objsaleorder->name=$buyer_name;
    		$objsaleorder->nrc_no=$nrc_no;
    		$objsaleorder->phone=$phone;
    		$objsaleorder->update();
    		foreach ($json_tickets as $rows) {
    			$objsaleitems=SaleItem::whereorder_id($sale_order_no)->whereseat_no($rows->seat_no)->wherebusoccurance_id($rows->busoccurance_id)->first();
    			$objsaleitems->order_id 		=$sale_order_no;
    			$objsaleitems->busoccurance_id 	=$rows->busoccurance_id;
    			$objsaleitems->seat_no			=$rows->seat_no;
    			$objsaleitems->name				=$rows->name;
    			$objsaleitems->nrc_no			=$rows->nrc_no;
    			$objsaleitems->ticket_no		=$rows->ticket_no;
    			$objsaleitems->update();

    			$seat_plan_id=BusOccurance::whereid($rows->busoccurance_id)->pluck('seat_plan_id');
    			$changestatus=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows->seat_no)->first();
    			if($changestatus){
    				$changestatus->status=1;
    				$changestatus->update();
    			}
    		}
    	}
    	$response['message']='Successfully your order ticket.';
    	return Response::json($response);
    }

    public function getAgentsByOperatorRp(){
    	$operator_id=Input::get('operator_id');
    	if(!$operator_id){
    		$response['message']="operator_id is required.";
    		return Response::json($response);
    	}

    	$agent_ids= SaleOrder::whereoperator_id($operator_id)->groupBy('agent_id')->lists('agent_id');
    	$agents=array();
    	$objagents=Agent::wherein('id', $agent_ids)->get();
    	if($objagents){
    		foreach ($objagents as $agent) {
    			$temp['id']=$agent->agentgroup_id;
    			$temp['name']=AgentGroup::whereid($agent->agentgroup_id)->pluck('name');
    			$agents[]=$temp;
    		}
    	}
    	$unique_agent = array();
		foreach ($agents as $val) {
		    $unique_agent[$val['id']] = $val;    
		}
		$agent_list = array_values($unique_agent);

    	$response['operator_id']=$operator_id;
    	$response['agents']=$agent_list;
    	return Response::json($response);
    }

    public function getOperatorsByAgentRp(){
    	$agent_id=Input::get('agent_id');
    	if(!$agent_id){
    		$response['message']="agent_id is required.";
    		return Response::json($response);
    	}

    	$operator_ids= SaleOrder::whereagent_id($agent_id)->groupBy('operator_id')->lists('operator_id');
    	$operators=array();
    	$objoperator=Operator::wherein('id', $operator_ids)->get();
    	if($objoperator){
    		foreach ($objoperator as $operator) {
    			$temp['id']=$operator->id;
    			$temp['name']=$operator->name;
    			$operators[]=$temp;
    		}
    	}
    	$response['agent_id']=$agent_id;
    	$response['operators']=$operators;
    	return Response::json($response);
    }

    public function getTripByAgentRp(){
    	$agent_id		=Input::get('agent_id');
    	$operator_id	=Input::get('operator_id');
    	$from_city		=Input::get('from_city');
    	$to_city		=Input::get('to_city');
    	$from_date		=Input::get('from_date');
    	$to_date		=Input::get('to_date');
    	$time			=Input::get('time');

    	if(!$agent_id || !$operator_id || !$from_city || !$to_city || !$from_date){
    		$response['message']= "Required fields are agent_id, operator_id, from_city, to_city and from_date";
    		return Response::json($response);
    	}
    }

    public function getTripReportByDateRanges(){
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date');
		$end_date  				=Input::get('end_date');
		$departure_time  		=Input::get('departure_time');
		$departure_time			=str_replace('-', ' ', $departure_time);

		
		if(!$from || !$to || !$start_date || !$end_date){
			$response['message']='Required fields are operator_id, from, to, start_date and end_date';
			return Response::json($response);
		}

		if($departure_time && $operator_id){
			$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->wheredeparture_time($departure_time)->lists('id');
		}elseif(!$departure_time && $operator_id){
			$busoccurance_ids=BusOccurance::whereoperator_id($operator_id)
								->wherefrom($from)
								->whereto($to)
								->lists('id');
		}elseif($departure_time && !$operator_id){
			$busoccurance_ids=BusOccurance::wherefrom($from)
								->wheredeparture_time($departure_time)
								->whereto($to)
								->lists('id');
		}else{
			$busoccurance_ids=BusOccurance::wherefrom($from)
								->whereto($to)
								->lists('id');
		}
		
		if($busoccurance_ids){
			$orderids=SaleItem::wherein('busoccurance_id',$busoccurance_ids)->groupBy('order_id')->lists('order_id');
			$orderdates=array();
			if($orderids){
				if(!$agent_id){
					$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->groupBy('orderdate')
								->lists('orderdate');
				}else{
					$agent_branches=Agent::whereagentgroup_id($agent_id)->lists('id');
					$orderdates=SaleOrder::wherein('id',$orderids)
								->where('orderdate','>=', $start_date)
								->where('orderdate','<=', $end_date)
								->wherein('agent_id', $agent_branches)
								->groupBy('orderdate')
								->lists('orderdate');
				}
				
			}

			if(count($orderdates) >0){
				foreach ($orderdates as $order_date) {
					if($agent_id){
						$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->wherein('agent_id',$agent_branches)->lists('id');
					}else{
						$orderids_bydaily		=SaleOrder::whereorderdate($order_date)->lists('id');
					}
					$occuranceids_bydaily		=SaleItem::wherein('order_id', $orderids_bydaily)->wherein('busoccurance_id',$busoccurance_ids)->groupBy('busoccurance_id')->lists('busoccurance_id');
					$amount=0;
					$seat_total=0;
					$tickets=0;
					if($occuranceids_bydaily){
						foreach ($occuranceids_bydaily as $occuranceid) {
							$tickets			+=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$orderids_bydaily)->count('id');
							$obj_busoccurance	=BusOccurance::whereid($occuranceid)->first();
							if($obj_busoccurance){
								$seatplanid			=$obj_busoccurance->seat_plan_id;
								$price				=$obj_busoccurance->price;
							}

							if($seatplanid){
								$seat_total	+=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
							}
							$amount    		+=$tickets * $price;
						}
						$soldtickets 					=SaleItem::wherein('order_id', $orderids_bydaily)->count('id');
						$temp['order_date']				=$order_date;
						$temp['total_seat']				=$seat_total;
						$temp['purchased_total_seat']	=$tickets;
						$temp['total_amout']			=$amount;
						$report_info[]					=$temp;
					}
					
				}
			}
		}
		return Response::json($report_info);
	}	

	public function getTripsReportByDaily(){
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$from  					=Input::get('from_city');
		$to  					=Input::get('to_city');
		$date  				    =Input::get('date');
		$departure_time  		=Input::get('time');
		$departure_time 		=str_replace('-', ' ', $departure_time);
		$agent_id  				=Input::get('agent_id');
		
		if(!$from || !$to || !$date){
			$response['message']='Required fields are operator_id, from, to, date and departure_time';
			return Response::json($response);
		}
				

		if($departure_time){
			$busoccurance_id=BusOccurance::wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->lists('id');
		}else{
			$busoccurance_id=BusOccurance::wherefrom($from)
										->whereto($to)
										->lists('id');
		}
		// return Response::json($busoccurance_id);

		if($busoccurance_id){
			$orderidsbyoccuranceid 		=SaleItem::wherein('busoccurance_id', $busoccurance_id)->groupBy('order_id')->lists('order_id');
			// $orderidsbyoccuranceid=SaleOrder::whereorderdate($date)->wherein('id',$sorder)->lists('id');
			if($orderidsbyoccuranceid && !$agent_id){
				$filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->whereorderdate($date)->lists('id');
			}elseif($orderidsbyoccuranceid){
				$agentids = Agent::whereagentgroup_id($agent_id)->lists('id');
				$filterorderidsbydate  	=SaleOrder::wherein('id', $orderidsbyoccuranceid)->wherein('agent_id', $agentids)->whereorderdate($date)->lists('id');
			}else{
				$filterorderidsbydate=array();
			}

		}
		$response=array();
		if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
			$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
			if($busoccurance_ids){
				$temp['purchased_total_seat']=0;
				$temp['total_amout']=0;
				foreach ($busoccurance_ids as $occuranceid) {
					if($departure_time){
						$objbusoccurance=BusOccurance::whereid($occuranceid)->wheredeparture_time($departure_time)->first();
					}else{
						$objbusoccurance=BusOccurance::whereid($occuranceid)->first();
					}
					$temp['bus_id']					=$objbusoccurance->id;
					$temp['bus_no']					=$objbusoccurance->bus_no;
					$temp['departure_date']			=$objbusoccurance->departure_date;
					$temp['time']					=$objbusoccurance->departure_time;
					$seat_plan_id					=$objbusoccurance->seat_plan_id;
					$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
					$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->wherein('order_id',$filterorderidsbydate)->count();
					$temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
					$response[]						=$temp;
				}
			}
		}else{
			$response['message']='There is no record.';
			return Response::json($response);
		}
		return Response::json($response);
    }

    public function getSeatReportByTrip(){
    	$operator_id 	=Input::get('operator_id');
    	$agent_id 		=Input::get('agent_id');
    	$from 			=Input::get('from_city');
    	$to 			=Input::get('to_city');
    	$date 			=Input::get('date');
    	$time 			=Input::get('time');
    	$bus_no			=Input::get('bus_no');
    	$bus_id			=Input::get('bus_id');
    	$invoice_no		=Input::get('invoice_no');
    	if(!$bus_id){
    		$response['message'] ='Required parameter is missing.';
    		return Response::json($response);
    	}
    	if($agent_id){
	    	$orederids=SaleOrder::whereorderdate($date)->whereagent_id($agent_id)->lists('id');
    	}else{
	    	$orederids=SaleOrder::whereorderdate($date)->lists('id');
    	}
    	$seat_info=array();
    	
    	if($orederids){
    		$saletickets =SaleItem::wherein('order_id', $orederids)->wherebusoccurance_id($bus_id)->get();
    		if($saletickets){
    			$objbusoccurance =BusOccurance::whereid($bus_id)->first();
    			foreach ($saletickets as $rows) {
    				$temp['seat_no']		= $rows->seat_no;
    				$temp['price']			= $objbusoccurance->price;
    				$temp['customer_name']	= $rows->name ? $rows->name : "-";
    				$objorder 				=SaleOrder::whereid($rows->order_id)->first();
    				$agent_name				='-';
    				if($agent_id !=0 && $objorder->agent_id !=0){
	    				$agent_name			= Agent::whereid($objorder->agent_id)->pluck('name');
    				}
    				if($objorder->agent_id !=0){
	    				$agent_name			= Agent::whereid($objorder->agent_id)->pluck('name');
    				}
    				$temp['agent_name'] 	=$agent_name !='' ? $agent_name : '-';
    				$temp['invoice_no']		= $objorder->id;
					$seat_info[]=$temp;
    			}
    		}
    	}
    	return Response::json($seat_info);
    }

    public function getSeatOccupiedReportByBus(){
    	$operator_id 	=Input::get('operator_id');
    	$agent_id 		=Input::get('agent_id');
    	$from 			=Input::get('from_city');
    	$to 			=Input::get('to_city');
    	$date 			=Input::get('date');
    	$time 			=Input::get('time');
    	$time 			=str_replace('-', ' ', $time);
    	$bus_id			=Input::get('bus_id');
    	$objbusoccuranceids=array();
    	if(!$bus_id){
    		$objbusoccuranceids =	BusOccurance::wherefrom($from)
    									->whereto($to)
    									->wheredeparture_date($date)
    									->wheredeparture_time($time)
    									->lists('id');	
    	}else{
    		$objbusoccuranceids[]=(int) $bus_id;
    	}
    	$response =array();
    	if($objbusoccuranceids){
    		$temp['operator_id']	=$operator_id;
    		$temp['operator_name']	=Operator::whereid($operator_id)->pluck('name');
    		
    		$seattingplan=array();
    			foreach ($objbusoccuranceids as $busid) {
    				$objbus=BusOccurance::whereid($busid)->first();
    				$tmp_seatplan['bus_id']=$busid;
		    		$tmp_seatplan['bus_no']=$objbus->bus_no;
		    		$objseatplan=SeatingPlan::whereid($objbus->seat_plan_id)->first();
		    		$tmp_seatplan['row']=$objseatplan->row;
		    		$tmp_seatplan['column']=$objseatplan->column;
		    		$tmp_seatplan['classes']=$objbus->classes;

		    		$seatlist=array();
		    			$objseatinfo=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->get();
		    			if($objseatinfo){
		    				foreach ($objseatinfo as $seats) {
		    					$tmp_seatlist['id']=$seats['id'];
		    					$tmp_seatlist['seat_no']=$seats['seat_no'];
					    		$tmp_seatlist['price']=$objbus->price;
		    					if($seats['status']==0){
		    						$tmp_seatlist['seat_no']='xxx';
					    			$tmp_seatlist['price']='xxx';
		    					}
					    		
					    		$tmp_seatlist['status']=$seats['status'];
					    		$checkbuy	=SaleItem::wherebusoccurance_id($objbus->id)->whereseat_no($seats['seat_no'])->first();
					    		$customer=array();
					    		if($checkbuy){
					    			$tmp_seatlist['status']=2;
					    			$customer['id']=000;
					    			$customer['name']=$checkbuy->name != '' ? $checkbuy->name : '-';
					    			$customer['nrc']=$checkbuy->nrc != '' ? $checkbuy->nrc : '-';
					    		}
					    		$tmp_seatlist['customer']=$customer;

					    		$seatlist[]=$tmp_seatlist;
		    				}
		    			}
			    		
		    		$tmp_seatplan['seat_list']=$seatlist;
		    		
		    		$seattingplan=$tmp_seatplan;

		    		$seatplan[]=$seattingplan;	
    			}
	    		
				$temp['seat_plan']=$seatplan;
			$response=$temp;
    	}
    	return Response::json($response);

    	$seat_info=array();
    	if($objbusoccurance){
    		$busoccurance_id=$objbusoccurance->id;
    		$objseatinfo	=SeatInfo::whereseat_plan_id($objbusoccurance->seat_plan_id)->get();
    		$seat_list=array();
    		if($objseatinfo){
    			foreach ($objseatinfo as $seat) {
    				$temp['id']=$seat->id;
    				$temp['seat_no']	=$seat->seat_no;
    				$temp['status']		=$seat->status;
    				$temp['price']		=$objbusoccurance->price;
    				if($seat->status == 0){
    					$temp['price']	='xxx';
    				}
    				$checksaleitem		=SaleItem::whereseat_no($seat->seat_no)->wherebusoccurance_id($busoccurance_id)->first();
    				if($checksaleitem){
    					$temp['customername']	=$checksaleitem->name !=null ? $checksaleitem->name : "-";
    					$temp['nrc']			=$checksaleitem->nrc_no ? $checksaleitem->nrc_no : '-';
    					$orderinfo				=SaleOrder::whereid($checksaleitem->order_id)->first();
    					if($orderinfo){
    						$temp['invoice_no']	=$orderinfo->id;
    						$agent_name 		=Agent::whereid($orderinfo->agent_id)->pluck('name');
    						$temp['agent_name']	= $agent_name !=null ? $agent_name : "-";
    					}
    				}else{
    					$temp['customername']	='-';
    					$temp['nrc']			='-';
    					$temp['invoice_no']		='-';
    					$temp['agent_name']		='-';
    				}
    				$seat_list[]	=	$temp;
    			}

    		}
    	}else{
    		$response['message']	='There is no record.';
    		return Response::json($response);
    	}
    	
    	return Response::json($seat_list);

    }

    public function getSeatOccupiedbyInvoice(){
    	$operator_id 	=Input::get('operator_id');
    	$agent_id 		=Input::get('agent_id');
    	$from 			=Input::get('from_city');
    	$to 			=Input::get('to_city');
    	$date 			=Input::get('date');
    	$time 			=Input::get('time');
    	$bus_no			=Input::get('bus_no');

    	$objbusoccurance =	BusOccurance::wherefrom($from)
    									->whereto($to)
    									// ->wheredeparture_date($date)
    									->wheredeparture_time($time)
    									->wherebus_no($bus_no)->first();
    	$seat_info=array();
    	if($objbusoccurance){
    		$busoccurance_id=$objbusoccurance->id;
    		$objseatinfo	=SeatInfo::whereseat_plan_id($objbusoccurance->seat_plan_id)->get();
    		$seat_list=array();
    		if($objseatinfo){
    			foreach ($objseatinfo as $seat) {
    				$temp['id']=$seat->id;
    				$temp['seat_no']	=$seat->seat_no;
    				$temp['status']		=$seat->status;
    				$temp['price']		=$objbusoccurance->price;
    				if($seat->status == 0){
    					$temp['price']	='xxx';
    				}
    				$checksaleitem		=SaleItem::whereseat_no($seat->seat_no)->wherebusoccurance_id($busoccurance_id)->first();
    				if($checksaleitem){
    					$temp['customername']	=$checksaleitem->name !=null ? $checksaleitem->name : "-";
    					$temp['nrc']			=$checksaleitem->nrc_no ? $checksaleitem->nrc_no : "-";
    					$orderinfo				=SaleOrder::whereid($checksaleitem->order_id)->first();
    					if($orderinfo){
    						$temp['invoice_no']	=$orderinfo->id;
    						$agent_name			=Agent::whereid($orderinfo->agent_id)->pluck('name');
    						$temp['agent_name'] =$agent_name !=null ? $temp['agent_name'] : "-";
    					}
    				}else{
    					$temp['customername']	='-';
    					$temp['nrc']			='-';
    					$temp['invoice_no']		='-';
    					$temp['agent_name']		='-';
    				}
    				$seat_list[]	=	$temp;
    			}

    		}
    	}else{
    		$response['message']	='There is no record.';
    		return Response::json($response);
    	}
    	
    	return Response::json($seat_list);

    }

    public function getSeatOccupancyReportold(){
    	$operator_id 		=Input::get('operator_id');
    	$from_date 			=Input::get('from_date');
    	$to_date 			=Input::get('to_date');
    	$departure_time 	=Input::get('time');
    	$from 				=Input::get('from_city');
    	$to 				=Input::get('to_city');

    	if(!$operator_id || !$from_date || !$from || !$to){
    		$response['message']="Fill out required fields.";
    		return Response::json($response);
    	}
    	if($to_date){
    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
    									->wherefrom($from)
    									->whereto($to)
    									// ->where('departure_date','>=',$from_date)
    									// ->where('departure_date','<=',$to_date)
    									->wheredeparture_time($departure_time)
    									->lists('id');	
    	}else{
    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
    									->wherefrom($from)
    									->whereto($to)
    									// ->where('departure_date','=',$from_date)
    									->wheredeparture_time($departure_time)
    									->lists('id');
    	}
    	
    	$seat_plan_array=array();
    	$temp=array();
    	if($busoccurance_ids){
    		$temp['operator_id']=$operator_id;
    		$temp['operator']	=Operator::whereid($operator_id)->pluck('name');
    		$temp_seat_plan=array();
    		foreach ($busoccurance_ids as $busoccuranceid) {
    			$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    			if($objbusoccurance){
    				$tmp['id'] 				=$objbusoccurance->id;
	    			$tmp['seat_no'] 		=$objbusoccurance->seat_no;
	    			$tmp['bus_no'] 			=$objbusoccurance->bus_no;
	    			$objseat_plan			=SeatingPlan::whereid($objbusoccurance->seat_plan_id)->first();
	    			
	    			if($objseat_plan){
	    				$tmp['row'] 			=0;
	    				$tmp['column'] 			=0;
	    				$tmp['row'] 			=$objseat_plan->row;
	    				$tmp['column'] 			=$objseat_plan->column;
	    				$tmp['classes'] 		=$objbusoccurance->classes;
	    				$objseatinfo			=SeatInfo::whereseat_plan_id($objseat_plan->id)->get();
	    				$seat_list_array=array();
	    				if($objseatinfo){
	    					foreach ($objseatinfo as $seats) {
	    						$seat['id'] 			=$seats->id;
	    						$seat['seat_no'] 		=$seats->seat_no;
	    						$seat['status'] 		=$seats->status;
	    						$seat['price'] 			=$objbusoccurance->price;
	    						if($seats->status==0){
	    							$seat['price'] 	="xxx";
	    						}
	    						$checkoccupied 			=SaleItem::wherebusoccurance_id($objbusoccurance->id)->whereseat_no($seats->seat_no)->first();
	    						$customer 				='-';
	    						if($checkoccupied){
	    							$seat['status'] 		=2;
	    							$customer['id'] 		=333333;
		    						$customer['name'] 		=$checkoccupied->name ? $checkoccupied->name : "-";
		    						$customer['nrc'] 		=$checkoccupied->nrc_no ? $checkoccupied->nrc_no : "-";
	    						}
	    						$seat['customer'] 		=$customer;
	    						$seat_list_array[]=$seat;
	    					}
	    				}
	    				$tmp['seat_list']		=$seat_list_array;
	    			}
	    			$temp_seat_plan[]=$tmp;
    			}
    			
    		}
    		$temp['seat_plan']=$temp_seat_plan;
    	}

    	return Response::json($temp);
    }

    public function getSeatOccupancyReport(){
    	$operator_id 		=Input::get('operator_id');
    	$from_date 			=Input::get('from_date');
    	$to_date 			=Input::get('to_date');
    	$departure_time 	=Input::get('time');
    	$from 				=Input::get('from_city');
    	$to 				=Input::get('to_city');

    	if(!$operator_id || !$from_date || !$from || !$to){
    		$response['message']="Fill out required fields.";
    		return Response::json($response);
    	}
    	if($to_date){
    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
    									->wherefrom($from)
    									->whereto($to)
    									// ->where('departure_date','>=',$from_date)
    									// ->where('departure_date','<=',$to_date)
    									->wheredeparture_time($departure_time)
    									->lists('id');	
    	}else{
    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
    									->wherefrom($from)
    									->whereto($to)
    									// ->where('departure_date','=',$from_date)
    									->wheredeparture_time($departure_time)
    									->lists('id');
    	}
    	
    	$seat_plan_array=array();
    	$temp=array();
    	if($busoccurance_ids){
    		$temp['operator_id']=$operator_id;
    		$temp['operator']	=Operator::whereid($operator_id)->pluck('name');
    		$temp_seat_plan=array();
    		foreach ($busoccurance_ids as $busoccuranceid) {
    			$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    			if($objbusoccurance){
    				$tmp['id'] 				=$objbusoccurance->id;
	    			$tmp['seat_no'] 		=$objbusoccurance->seat_no !='' ? $objbusoccurance->seat_no : '-';
	    			$tmp['bus_no'] 			=$objbusoccurance->bus_no;
	    			$objseat_plan			=SeatingPlan::whereid($objbusoccurance->seat_plan_id)->first();
	    			
	    			if($objseat_plan){
	    				$tmp['row'] 			=0;
	    				$tmp['column'] 			=0;
	    				$tmp['row'] 			=$objseat_plan->row;
	    				$tmp['column'] 			=$objseat_plan->column;
	    				$tmp['classes'] 		=$objbusoccurance->classes;
	    				$objseatinfo			=SeatInfo::whereseat_plan_id($objseat_plan->id)->get();
	    				$seat_list_array=array();
	    				if($objseatinfo){
	    					foreach ($objseatinfo as $seats) {
	    						$seat['id'] 			=$seats->id;
	    						$seat['seat_no'] 		=$seats->seat_no;
	    						$seat['status'] 		=$seats->status;
	    						$seat['price'] 			=$objbusoccurance->price;
	    						if($seats->status==0){
	    							$seat['price'] 	="xxx";
	    						}
	    						$checkoccupied 			=SaleItem::wherebusoccurance_id($objbusoccurance->id)->whereseat_no($seats->seat_no)->first();
	    						$customer=array();

	    						if($checkoccupied){
	    							$seat['status'] 		=2;
	    							$customer['id'] 		=333333;
		    						$customer['name'] 		=$checkoccupied->name ? $checkoccupied->name : "-";
		    						$customer['nrc'] 		=$checkoccupied->nrc_no ? $checkoccupied->nrc_no : "-";
	    						}
	    						if(count($customer)<1){
		    						$customer 				='-';
		    					}else{
		    						$seat['customer'] 		=$customer;
	    						}
	    						$seat_list_array[]=$seat;
	    					}
	    				}
	    				$tmp['seat_list']		=$seat_list_array;
	    			}
	    			$temp_seat_plan[]=$tmp;
    			}
    			
    		}
    		$temp['seat_plan']=$temp_seat_plan;
    	}

    	return Response::json($temp);
    }

    public function getSaleReportbyAgentGroup(){
    	$agentgroup_id=$branch_agent_id	= Input::get('agent_id');
    	$agentgroup_id		= Agent::whereid($branch_agent_id)->pluck('agentgroup_id');

    	$from_city			=Input::get('from_city');
    	$to_city			=Input::get('to_city');
    	$from_date			=Input::get('from_date');
    	$to_date			=Input::get('to_date');
    	$time				=Input::get('time');
    	$time 				=str_replace('-', ' ', $time);

    	if(!$branch_agent_id || !$from_city || !$to_city || !$from_date){
    		$response['message']='Required fields are agent_id, from_city, to_city, from_date and to_date.';
    		return Response::json($response);
    	}
    	if($to_date){
    		$objsaleorderids 		=	SaleOrder::whereagent_id($branch_agent_id)
    									->where('orderdate','>=',$from_date)
    									->where('orderdate','<=',$to_date)
    									->lists('id');
		}else{
			$objsaleorderids 		=	SaleOrder::whereagent_id($branch_agent_id)
    									->where('orderdate','=',$from_date)
    									->lists('id');
		}
		$response=array();    	
    	if($agentgroup_id){
    		$objagentbranch =Agent::whereagentgroup_id($agentgroup_id)->get();
    		if($objagentbranch){
    			foreach ($objagentbranch as $agent) {
    				if($to_date){
    					$objsaleorder=SaleOrder::whereagent_id($agent->id)->where('orderdate', '>=',$from_date)->where('orderdate', '<=',$to_date)->get();
    				}else{
    					$objsaleorder=SaleOrder::whereagent_id($agent->id)->where('orderdate', '=',$from_date)->get();
    					// return Response::json($objsaleorder);
    				}
    				if($objsaleorder){
    					foreach ($objsaleorder as $orders) {
    						
		    				$saleitems=SaleItem::whereorder_id($orders->id)->get();
		    				if($saleitems){
		    					$temp['agent_name']				=$agent->name !=null ? $agent->name : '-';
	    						$temp['invoice_no']				=$orders->id;
	    						$temp['purchased_total_seat']=0;
	    						$temp['total_seat']=0;
	    						$temp['total_amount']=0;
		    					foreach ($saleitems as $saleitem) {
		    						$objbusoccurance 				=BusOccurance::whereid($saleitem->busoccurance_id)->first();
		    						$seat_plan_id 					=$objbusoccurance->seat_plan_id;
		    						$temp['cash_price']				=$objbusoccurance->price;
		    						// $temp['busid']					=$objbusoccurance->id;
		    						$temp['purchased_total_seat']	+=1;
		    						$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
		    						$temp['total_amount']			=$temp['cash_price'] * $temp['purchased_total_seat'];
		    						
		    					}
	    						$response[]						=$temp;

		    				}
    					}
    					
    				}
    			}
    		}
    	}

    	return Response::json($response);

    }

    public function getSaleReportbyOperatorInvoice(){
    	$operator_id 		= Input::get('operator_id');
    	$from_city			=Input::get('from_city');
    	$to_city			=Input::get('to_city');
    	$from_date			=Input::get('from_date');
    	$to_date			=Input::get('to_date');
    	$time				=Input::get('time');
    	$time 				=str_replace('-', ' ', $time);

    	if(!$branch_agent_id || !$from_city || !$to_city || !$from_date){
    		$response['message']='Required fields are agent_id, from_city, to_city, from_date and to_date.';
    		return Response::json($response);
    	}
    	$orderid 			=SaleItem::whereoperator_id($operator_id)->pluck('order_id');
    	$agentgroup_id		= Agent::whereid($branch_agent_id)->pluck('agentgroup_id');

    	if($to_date){
    		$objsaleorderids 		=	SaleOrder::whereagent_id($branch_agent_id)
    									->where('orderdate','>=',$from_date)
    									->where('orderdate','<=',$to_date)
    									->lists('id');
		}else{
			$objsaleorderids 		=	SaleOrder::whereagent_id($branch_agent_id)
    									->where('orderdate','=',$from_date)
    									->lists('id');
		}
		$response=array();    	
    	if($agentgroup_id){
    		$objagentbranch =Agent::whereagentgroup_id($agentgroup_id)->get();
    		if($objagentbranch){
    			foreach ($objagentbranch as $agent) {
    				if($to_date){
    					$objsaleorder=SaleOrder::whereagent_id($agent->id)->where('orderdate', '>=',$from_date)->where('orderdate', '<=',$to_date)->get();
    				}else{
    					$objsaleorder=SaleOrder::whereagent_id($agent->id)->where('orderdate', '=',$from_date)->get();
    					// return Response::json($objsaleorder);
    				}
    				if($objsaleorder){
    					foreach ($objsaleorder as $orders) {
    						
		    				$saleitems=SaleItem::whereorder_id($orders->id)->get();
		    				if($saleitems){
		    					$temp['agent_name']				=$agent->name !=null ? $agent->name : '-';
	    						$temp['invoice_no']				=$orders->id;
	    						$temp['purchased_total_seat']=0;
	    						$temp['total_seat']=0;
	    						$temp['total_amount']=0;
		    					foreach ($saleitems as $saleitem) {
		    						$objbusoccurance 				=BusOccurance::whereid($saleitem->busoccurance_id)->first();
		    						$seat_plan_id 					=$objbusoccurance->seat_plan_id;
		    						$temp['cash_price']				=$objbusoccurance->price;
		    						// $temp['busid']					=$objbusoccurance->id;
		    						$temp['purchased_total_seat']	+=1;
		    						$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
		    						$temp['total_amount']			=$temp['cash_price'] * $temp['purchased_total_seat'];
		    						
		    					}
	    						$response[]						=$temp;

		    				}
    					}
    					
    				}
    			}
    		}
    	}

    	return Response::json($response);

    }

    public function getCitiesByagentId(){
    	$agent_id 		=Input::get('agent_id');
    	if(!$agent_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return Response::json($response);
    	}
    	$orderids 			=SaleOrder::whereagent_id($agent_id)->lists('id');
    	$cities=array();
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		// return Response::json($busoccurance_ids);
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
    	}else{
    		$response['message']='There is no records.';
    		return Response::json($response);
    	}

    	return Response::json($cities);
    }

    public function getCitiesByoperatorId(){
    	$operator_id 		=Input::get('operator_id');
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return Response::json($response);
    	}

    	$orderids 			=SaleOrder::whereoperator_id($operator_id)->lists('id');
    	$cities=array();
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		// return Response::json($busoccurance_ids);
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
    	}else{
    		$response['message']='There is no records.';
    		return Response::json($response);
    	}

    	return Response::json($cities);
    }
    public function getTimesByagentId(){
    	$agent_id 		=Input::get('agent_id');
    	if(!$agent_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return Response::json($response);
    	}

    	$orderids 			=SaleOrder::whereagent_id($agent_id)->lists('id');
    	$times=array();
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		// return Response::json($busoccurance_ids);
    		if($busoccurance_ids){
    			foreach ($busoccurance_ids as $busoccuranceid) {
    				$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    				if($objbusoccurance){
    					$temp['time']			=$objbusoccurance->departure_time;
    					$times[]				=$temp;
    				}
    			}
    		}
    	}else{
    		$response['message']='There is no records.';
    		return Response::json($response);
    	}

    	$last_times=array();
    	$unique_times=array_unique($times, SORT_REGULAR);
    	if($unique_times){
    		foreach ($unique_times as $time) {
    			$tmp	=$time;
    			$last_times[]=$tmp;
    		}
    	}
    	return Response::json($last_times);
    }

    public function getTimesByOperatorId(){
    	$operator_id 		=Input::get('operator_id');
    	if(!$operator_id){
    		$response['message']='The request is missing a required parameter.'; 
    		return Response::json($response);
    	}

    	$orderids 			=SaleOrder::whereoperator_id($operator_id)->lists('id');
    	$times=array();
    	if($orderids){
	    	$busoccurance_ids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    		// return Response::json($busoccurance_ids);
    		if($busoccurance_ids){
    			foreach ($busoccurance_ids as $busoccuranceid) {
    				$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
    				if($objbusoccurance){
    					$temp['time']			=$objbusoccurance->departure_time;
    					$times[]				=$temp;
    				}
    			}
    		}
    	}else{
    		$response['message']='There is no records.';
    		return Response::json($response);
    	}

    	$last_times=array();
    	$unique_times=array_unique($times, SORT_REGULAR);
    	if($unique_times){
    		foreach ($unique_times as $time) {
    			$tmp	=$time;
    			$last_times[]=$tmp;
    		}
    	}
    	return Response::json($last_times);
    }

    

    public function getAgentbranchesbygroup(){
    	$group_id 	=Input::get('agent_group_id');
    	if(!$group_id){
    		$response['message']= "agent_group_id is required.";
    		return Response::json($response);
    	}
    	$agentids	=Agent::whereagentgroup_id($group_id)->lists('id');
    	$agent_list=array();
    	if($agentids){
    		$objorderinfo =SaleOrder::wherein('agent_id', $agentids)->groupBy('agent_id')->lists('agent_id');
    		if($objorderinfo){
    			foreach ($objorderinfo as $id) {
    				$agentinfo 		=Agent::whereid($id)->first();
    				$temp['id']		=$agentinfo->id;
    				$temp['name']	=$agentinfo->name;
    				$agent_list[]	=$temp;
    			}
    		}
    	}
    	return Response::json($agent_list);
    }

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

    public function getSeatPlanforTripcreate($operator_id){
    	$objseatplans=SeatingPlan::whereoperator_id($operator_id)->get();
    	$seatplans=array();
    	if($objseatplans){
    		foreach ($objseatplans as $seatplan) {
    			$temp['id']=$seatplan->id;
    			$temp['name']=$seatplan->name;

    			$objseatlayout=SeatingLayout::whereid($seatplan->seat_layout_id)->first();
				if($objseatlayout){
					$templayout['row']=$objseatlayout->row;
					$templayout['column']=$objseatlayout->column;
				}
				$seat_info=array();
				$objseatinfo=SeatInfo::whereseat_plan_id($seatplan->id)->get();
				$seat_info=array();
				if($objseatinfo){
					foreach ($objseatinfo as $seats) {
						$seattemp['seat_no']=$seats->seat_no;
						$seattemp['status']=$seats->status;
						$seat_info[]=$seattemp;
					}
				}
				$templayout['seat_lists']=$seat_info;
				$temp['seat_layout']=$templayout;
				$seat_plans[]=$temp;

    			$seatplans[]=$temp;
    		}
    		
    	}
    	return Response::json($seatplans);
    }

    public function getDailyReportforTrip(){
    	$departure_time		=Input::get('departure_time');
    	$todaydate 			=date('Y-m-d');
    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
    	$operator_id  		=Input::get('operator_id');
    	if($departure_time){
    		$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
									->wheredeparture_date($date)
									->wheredeparture_time($departure_time)
									->lists('id');
    	}else{
    		$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
									->wheredeparture_date($date)
									->lists('id');
    	}
		
		$salerecord=array();
		if($departure_time){
			if($objbusoccurance_ids){
				foreach ($objbusoccurance_ids as $occurrent_id) {
						$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
						$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
						$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
						if($order_ids){
							$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
							$temp['bus_id']		=$busoccuranceinfo->id;
							$temp['bus_no']		=$busoccuranceinfo->bus_no;
							$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
							$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
							$temp['from'] 		=$from_city;
							$temp['to'] 		=$to_city;
							$temp['class']		=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
							$temp['time'] 		=$busoccuranceinfo->departure_time;
							$temp['sold_seat']	=$soldticketcount;
							$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
							$temp['price']		=$busoccuranceinfo->price;
							$temp['sold_amount']=$temp['price'] * $soldticketcount;
							$salerecord[] 		=$temp;
						}
				}
			}
			return Response::json($salerecord);	
		}else{
			$salerecord=array();
			if($objbusoccurance_ids){
				$i=0;
				foreach ($objbusoccurance_ids as $occurrent_id) {
					if($i==0){
						$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
						$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
						$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
						if($order_ids){
							$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
							$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
							$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
							$temp['from'] 		=$from_city;
							$temp['to'] 		=$to_city;
							$temp['time'] 		=$busoccuranceinfo->departure_time;
							$temp['sold_seat']	=$soldticketcount;
							$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
							$temp['price']		=$busoccuranceinfo->price;
							$temp['sold_amount']=$temp['price'] * $soldticketcount;
							$salerecord[] 		=$temp;
						}
					}else{
						$saleiteminfo 	=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->get();
						$order_ids 		=SaleItem::wherebusoccurance_id($occurrent_id)->groupBy('order_id')->lists('order_id');
						$busoccuranceinfo=BusOccurance::whereid($occurrent_id)->first();
						if($order_ids){
							$soldticketcount 	=SaleItem::wherebusoccurance_id($occurrent_id)->wherein('order_id',$order_ids)->count('id');
							$from_city 			=City::whereid($busoccuranceinfo->from)->pluck('name');
							$to_city 			=City::whereid($busoccuranceinfo->to)->pluck('name');
							$temp['from'] 		=$from_city;
							$temp['to'] 		=$to_city;
							$temp['time'] 		=$busoccuranceinfo->departure_time;
							$temp['sold_seat']	=$soldticketcount;
							$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
							$temp['price']		=$busoccuranceinfo->price;
							$temp['sold_amount']=$temp['price'] * $soldticketcount;
							$sametime=0;
							foreach ($salerecord as $key => $value) {
								if($value['time']== $temp['time']){
									$salerecord[$key]['sold_seat']=$value['sold_seat'] + $temp['sold_seat'];
									$salerecord[$key]['total_seat']=$value['total_seat'] + $temp['total_seat'];
									$salerecord[$key]['sold_amount']=$value['sold_amount'] + $temp['sold_amount'];
									$sametime +=1;
								}
							}
							if($sametime==0){
								$salerecord[] 		=$temp;
							}else{

							}
						}
						

					}
					$i++;
				}

			}
			return Response::json($salerecord);	
		}

    }


    public function getDetailDailyReportforBus(){
    	$todaydate 			=date('Y-m-d');
    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
    	$operator_id  		=Input::get('operator_id');
    	$bus_id  			=Input::get('bus_id');
		$objbusoccurance=BusOccurance::whereid($bus_id)->first();
		$salerecord=array();
		if($objbusoccurance){
			$saleiteminfo 	=SaleItem::wherebusoccurance_id($objbusoccurance->id)->get();
			$order_ids 		=SaleItem::wherebusoccurance_id($objbusoccurance->id)->lists('order_id','id');
			if($order_ids){
				foreach ($order_ids as $key => $orderid) {
					$objorderinfo 			=SaleOrder::whereid($orderid)->first();
					$temp['bus_id']			=$bus_id;
					$from 					=City::whereid($objbusoccurance->from)->pluck('name');
					$to 					=City::whereid($objbusoccurance->to)->pluck('name');
					$temp['Trip']			=$from.'-'.$to;
					$temp['sale_id']		=$key;
					$temp['order_id']		=$objorderinfo->id;
					$temp['order_date']		=$objorderinfo->orderdate;
					$temp['seat_no'] 		=SaleItem::whereid($key)->pluck('seat_no');
					$temp['name']			=$objorderinfo->name !=null ? $objorderinfo->name : '-';
					$agent_name 			=Agent::whereid($objorderinfo->agent_id)->pluck('name');
					$temp['agent'] 			=$agent_name !=null ? $agent_name : '-';
					$temp['price']			=$objbusoccurance->price;
					$temp['departure_date']	=$objbusoccurance->departure_date;
					$temp['departure_time']	=$objbusoccurance->departure_time;
					$salerecord[] 		=$temp;
				}
			}
		}
		return Response::json($salerecord);
    }

    public function getSeatOccupancyReportbyBusid(){
    	$busoccuranceid =Input::get('bus_id');
			$objbusoccurance 		=BusOccurance::whereid($busoccuranceid)->first();
			$temp['id'] 				=$busoccuranceid;
			$temp['seat_no'] 		=$objbusoccurance->seat_no;
			$temp['bus_no'] 			=$objbusoccurance->bus_no;
			$objseat_plan			=SeatingPlan::whereid($objbusoccurance->seat_plan_id)->first();
			
			if($objseat_plan){
				$tmp['row'] 			=0;
				$tmp['column'] 			=0;
				$tmp['row'] 			=$objseat_plan->row;
				$tmp['column'] 			=$objseat_plan->column;
				$tmp['classes'] 		=$objbusoccurance->classes;
				$objseatinfo			=SeatInfo::whereseat_plan_id($objseat_plan->id)->get();
				$seat_list_array=array();
				if($objseatinfo){
					foreach ($objseatinfo as $seats) {
						$seat['id'] 			=$seats->id;
						$seat['seat_no'] 		=$seats->seat_no;
						$seat['status'] 		=$seats->status;
						$seat['price'] 			=$objbusoccurance->price;
						if($seats->status==0){
							$seat['price'] 	="xxx";
						}
						$checkoccupied 			=SaleItem::wherebusoccurance_id($objbusoccurance->id)->whereseat_no($seats->seat_no)->first();
						$customer 				=null;
						if($checkoccupied){
							$seat['status'] 		=2;
							$customer['id'] 		=333333;
    						$customer['name'] 		=$checkoccupied->name !=null ? $checkoccupied->name : '-';
    						$customer['nrc'] 		=$checkoccupied->nrc_no !=null ? $checkoccupied->nrc_no : '-';
						}
						$seat['customer'] 		=$customer;
						$seat_list_array[]=$seat;
					}
				}
    			$tmp['seat_list']=$seat_list_array;
    		}
    	$temp['seat_plan']=$tmp;
	    return Response::json($temp);
    }

    
    public function getDailyAdvancedTrips(){
		$report_info 			=array();
    	$date 					=Input::get('date');
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		
		$orderids 				=SaleOrder::whereorderdate($date)->lists('id');
		if($orderids){
			$busoccuranceids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
		}
		
		if(isset($busoccuranceids)){
			$filterorderidsbydate=SaleItem::wherein('busoccurance_id',$busoccuranceids)->lists('order_id');
		}

		if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
			$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
			if($busoccurance_ids){
				$temp['purchased_total_seat']=0;
				$temp['total_amout']=0;
				$i=0;
				foreach ($busoccurance_ids as $occuranceid) {
					$objbusoccurance=BusOccurance::whereid($occuranceid)->first();
					if($i==0){
						// $temp['bus_id']					=$objbusoccurance->id;
						// $temp['bus_no']					=$objbusoccurance->bus_no;
						$temp['departure_date']			=$objbusoccurance->departure_date;
						// $temp['time']					=$objbusoccurance->departure_time;
						$seat_plan_id					=$objbusoccurance->seat_plan_id;
						$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
						$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
						$temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
						$response[]						=$temp;
					}else{
						$samedate=0;
						$samekey=0;
						foreach ($response as $key => $value) {
							if($value['departure_date'] == $objbusoccurance->departure_date){
								$samedate +=1;
								$samekey=$key;
							}

						}
						$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
						$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
						$temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
						if($samedate==0){
							// $temp['bus_id']					=$objbusoccurance->id;
							// $temp['bus_no']					=$objbusoccurance->bus_no;
							$temp['departure_date']			=$objbusoccurance->departure_date;
							// $temp['time']					=$objbusoccurance->departure_time;
							$seat_plan_id					=$objbusoccurance->seat_plan_id;
							$response[]						=$temp;
						}else{
							$response[$samekey]['purchased_total_seat'] =$response[$samekey]['purchased_total_seat'] + $temp['purchased_total_seat'];
							$response[$samekey]['total_amout'] 			=$response[$samekey]['total_amout'] + $temp['total_amout'];
							$response[$samekey]['total_seat'] 			=$response[$samekey]['total_seat'] + $temp['total_seat'];
						}
					}

					
					$i++;
					
				}
			}
		}else{
			$response['message']='There is no record.';
			return Response::json($response);
		}
		return Response::json($response);
    }

    public function getDailyAdvancedByFilterDate(){
    	$report_info 			=array();
    	$order_date 					=Input::get('order_date');
    	$departure_date 				=Input::get('departure_date');
		$operator_id  					=Input::get('operator_id');
		$agent_id  						=Input::get('agent_id');
		
		$orderids 				=SaleOrder::whereorderdate($order_date)->lists('id');
		if($orderids){
			$busoccuranceids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
		}
		$filterbusids=array();
		if(isset($busoccuranceids)){
			foreach($busoccuranceids as $busid){
				$filterid=BusOccurance::wheredeparture_date($departure_date)->whereid($busid)->pluck('id');
				if($filterid){
					$filterbusids[]=$filterid;
				}
			}
			if($filterbusids){
				$filterorderidsbydate=SaleItem::wherein('busoccurance_id',$filterbusids)->lists('order_id');
			}
		}

		if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
			$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
			if($busoccurance_ids){
				$temp['purchased_total_seat']=0;
				$temp['total_amout']=0;
				foreach ($busoccurance_ids as $occuranceid) {
					$objbusoccurance=BusOccurance::whereid($occuranceid)->first();
						$temp['bus_id']					=$objbusoccurance->id;
						$temp['bus_no']					=$objbusoccurance->bus_no;
						$temp['departure_date']			=$objbusoccurance->departure_date;
						$temp['time']					=$objbusoccurance->departure_time;
						$seat_plan_id					=$objbusoccurance->seat_plan_id;
						$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
						$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
						$temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
						$response[]						=$temp;
				}
			}
		}else{
			$response['message']='There is no record.';
			return Response::json($response);
		}
    	return Response::json($response);
    }

    public function postHolidays(){
    	$operator_id =Input::get('operator_id');
    	$holiday 	 =Input::get('holiday');
    	if(!$operator_id || !$holiday){
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	$month =substr($holiday, 5,2);
    	$checkholiday=Holiday::whereoperator_id($operator_id)->whereholiday($holiday)->first();
    	if($checkholiday){
    		$response['message']="This holiday is already created.";
    		return Response::json($response);
    	}

    	$objholiday  =new Holiday();
    	$objholiday->operator_id=$operator_id;
    	$objholiday->month 		=$month;
    	$objholiday->holiday 	=$holiday;
    	$objholiday->save();
    	$response['message']="Successfully created holiday.";
    	$temp['id']=$objholiday->id;
    	$temp['operator_id']=$operator_id;
    	$temp['month']=$month;
    	$temp['holiday']=$holiday;
    	$response['holiday']=$temp;
    	return Response::json($response);
    }

    public function getHolidaysbyOperator(){
    	$operator_id =Input::get('operator_id');
    	$month 		 =Input::get('month');
    	if($month){
	    	$objholidays=Holiday::whereoperator_id($operator_id)->where('month','=',$month)->get();
    	}else{
	    	$objholidays=Holiday::whereoperator_id($operator_id)->get();
    	}
    	$temp_response['operator_id']=$operator_id;
    	$temp_response['operator']	=Operator::whereid($operator_id)->pluck('name');
    	$holidays= array();
    	if($objholidays){
    		foreach($objholidays as $holiday){
    			$temp['id']=$holiday->id;
    			$temp['month']=$holiday->month;
    			$temp['holiday']=$holiday->holiday;
    			$holidays[]=$temp;
    		}
    	}
    	$temp_response['holidays']=$holidays;
    	return Response::json($temp_response);

    }

    public function getDailyReportbydeparturedate(){
    	$operator_id 		=Input::get('operator_id');
    	$departure_date 	=Input::get('departure_date');	
    	$from 				=Input::get('from');	
    	$to 				=Input::get('to');	
    	$time 				=Input::get('time');
    	if(!$operator_id || !$departure_date){
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	$objbusoccuranceids=null;	
    	$busids=null;
    	if(!$time && $from && $to){
    		$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wheredeparture_date($departure_date)->wherefrom($from)->whereto($to)->lists('id');
    	}elseif(!$from && !$to && !$time){
			$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wheredeparture_date($departure_date)->lists('id');
    	}else{
    		$objbusoccuranceids 	=BusOccurance::whereoperator_id($operator_id)->wheredeparture_date($departure_date)->wherefrom($from)->whereto($to)->wheredeparture_time($time)->lists('id');
    	}

    	if($objbusoccuranceids !=null){
    		$busids=SaleItem::wherein('busoccurance_id', $objbusoccuranceids)->groupBy('busoccurance_id')->lists('busoccurance_id');
    	}

    	$response=array();
		if($busids){
			foreach ($busids as $busid) {
				$objbus=BusOccurance::whereid($busid)->first();
				$temp['id']			=$objbus->id;
				$temp['bus_no']		=$objbus->bus_no;
				$temp['class']		=Classes::whereid($objbus->classes)->pluck('name');
				$temp['departure_time']		=$objbus->departure_time;
				$price 				=$objbus->price;
				$temp['sold_seats']	=SaleItem::wherebusoccurance_id($busid)->count();
				$temp['total_seats']=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();
				$temp['total_amount']=$price * $temp['sold_seats'];
				$response[]			=$temp;
			}
		}
		return Response::json($response);
    }

    public function getDailyReportbydepartdateandbusid(){
    	$busid 			=Input::get('bus_id');
    	$orderids 		=SaleItem::wherebusoccurance_id($busid)->groupBy('order_id')->lists('order_id');
    	$objbus 	 	=BusOccurance::whereid($busid)->first();
    	$objorderagent=null;
    	if($orderids){
    		$objagentids =SaleOrder::wherein('id', $orderids)->lists('agent_id','id');
    	}

    	$i=0;
    	if($objagentids){
    		foreach ($objagentids as $key=>$agentid) {
    			$agent_name				=Agent::whereid($agentid)->pluck('name');	
    			if($i==0){
    				$temp['bus_id']			=$busid;
    				$temp['agent_id']		=$agentid;
    				$temp['agent'] 			=$agent_name != null ? $agent_name : '-';
    				$temp['sold_tickets']	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
	    			$price 					=$objbus->price;
	    			$temp['total_amount'] 	=$price * $temp['sold_tickets'];
    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();
    				$response[]=$temp;
    			}else{
    				$sameagent=0;
    				$samekey=0;
    				foreach ($response as $s_key=>$value) {
	    				if($value['agent_id']==$agentid ){
	    					$sameagent +=1;
	    					$samekey=$s_key;
	    				}
	    			}
	    			if($sameagent==0){
	    				$temp['bus_id']			=$busid;
	    				$temp['agent_id']		=$agentid;
	    				$temp['agent']			=$agent_name != null ? $agent_name : '-';
	    				$temp['sold_tickets']	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
		    			$price 					=$objbus->price;
		    			$temp['total_amount'] 	=$price * $temp['sold_tickets'];
	    				$temp['total_seats']	=SeatInfo::whereseat_plan_id($objbus->seat_plan_id)->where('status','!=',0)->count();	
	    				$response[]=$temp;
	    			}else{
	    				$sold_tickets	=SaleItem::whereorder_id($key)->wherebusoccurance_id($busid)->count();
		    			$price 			=$objbus->price;
		    			$total_amount	=$price * $sold_tickets;
	    				
	    				$response[$samekey]['sold_tickets'] +=$sold_tickets;
	    				$response[$samekey]['total_amount'] +=$total_amount;
	    			}
    			}
    			$i++;
    		}	
    	}
    	return Response::json($response);
    }

    public function getDailyReportbydepartdatedetail(){
    	$agent_id 	=Input::get('agent_id');
    	$bus_id 	=Input::get('bus_id');

    	$objbus=BusOccurance::whereid($bus_id)->first();
    	$objorderids=SaleItem::wherebusoccurance_id($bus_id)->groupBy('order_id')->lists('order_id');
    	$response=array();
    	if($objorderids){
    		foreach ($objorderids as $key => $id) {
    			$objorderinfo=SaleOrder::whereid($id)->whereagent_id($agent_id)->first();
    			if($objorderinfo){
    				$objsaleitems=SaleItem::wherebusoccurance_id($bus_id)->whereorder_id($id)->get();
    				if($objsaleitems){
    					foreach ($objsaleitems as $rows) {
    						$temp['bus_no'] 	=$objbus->bus_no;
    						$from 				=City::whereid($objbus->from)->pluck('name');
    						$to 				=City::whereid($objbus->to)->pluck('name');
    						$temp['trip']=$from.'-'.$to;
    						$temp['class']=Classes::whereid($objbus->classes)->pluck('name');
    						$temp['departure_date'] 	=$objbus->departure_date;
    						$temp['departure_time'] 	=$objbus->departure_time;
    						$temp['seat_no'] 	=$rows['seat_no'];
    						$temp['ticket_no'] 	=$rows['ticket_no'];
    						$temp['orderdate']=$objorderinfo->orderdate;
			    			$agent_name=Agent::whereid($objorderinfo->agent_id)->pluck('name');
			    			$temp['agent']=$agent_name != null ? $agent_name : '-';
			    			$temp['customer_name']=$objorderinfo->name;
			    			$temp['operator']=Operator::whereid($objorderinfo->operator_id)->pluck('name');
			    			$temp['price'] 	 =$objbus->price;
			    			$response[] 	 =$temp;
    					}
    				}
    			}
    		}
    	}
    	return Response::json($response);
    }
}