<?php
class ApiController extends BaseController
{
	public $today = "";
    
    public function __construct() {
		$this->today = App::make('MyDate');
	}

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
			$response['status']=0;
			$response['message']="There is no record for update.";
			return Response::json($response);
		}
		$objbusclass->name=$name;
		$objbusclass->operator_id=$operator_id;
		$objbusclass->update();
		$response['status']=1;
		$response['message']="Successfully update class info.";
		$class['id'] =$objbusclass->id;
		$class['name'] =$objbusclass->name;
		$response['classinfo']=$class;

		return Response::json($response);
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
		$email			=Input::get('email');
		$password		=Input::get('password');
		$address		=Input::get('address');
		$phone			=Input::get('phone');
		$response 		=array();
		$check_exiting  =User::whereemail($email)->first();
		if($check_exiting){
			$response['message']="Email is already used.";
			return Response::json($response);
		}

		if(!$name || !$address || !$phone || !$email || !$password){
			$response['message']="Required fields are name, address and phone.";
			return Response::json($response);
		}

		$objoperator		=new Operator();
		$checkoperator 		=Operator::wherename($name)->whereaddress($address)->first();
		if($checkoperator){
			$response['message']='This operator is already exit';
			return Response::json($response);
		}

		$user=new User();
		$user->name=$name;
		$user->email=$email;
		$user->password=Hash::make($password);
		$user->type="operator";
		$user->save();
		$user_id=$user->id;

		$objoperator->name		=$name;
		$objoperator->address	=$address;
		$objoperator->phone		=$phone;
		$objoperator->user_id		=$user_id;
		$objoperator->save();

		$objoauthclient =new OauthClients();
		$client_id=rand(9000000,6);
		$clientname=$name ."(operator)";
		$secret =Hash::make($clientname);
		$objoauthclient->id=$client_id;
		$objoauthclient->secret=$secret;
		$objoauthclient->name=$clientname;
		$objoauthclient->save();
		
		$response=array();
		$operatorinfo=array();
		$login_user=array();
		$client_secret=array();
		$response['id']=$objoperator->id;
		$response['name']=$name;
		$response['phone']=$phone;
		$response['address']=$address;

		$login_user['username']=$email;
		$login_user['password']=$password;
		$login_user['scope']="operator";
		$login_user['client_secret']=$secret;
		$login_user['client_id']=$client_id;

		$response['login_user']=$login_user;

		return Response::json($response);
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
				
				$login_info=array();
				$user_id=$operator->user_id;
				$objuser=User::whereid($user_id)->first();
				$login_info['username']='-';
				if($objuser)
				$login_info['username']=$objuser->email;

				$login_info['scope']='operator';
				$operator_name=$operator->name ."(operator)";
				$objoauthclient=OauthClients::wherename($operator_name)->first();
				if($objoauthclient){
					$login_info['client_secret']=$objoauthclient->secret;
					$login_info['client_id']=$objoauthclient->id;
				}else{
					$login_info['client_secret']='-';
					$login_info['client_id']='-';
				}
				
				$tmp['login_info']=$login_info;
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
		$email 			=Input::get('email');
		$newpassword 	=Input::get('new_password');
		$objoperator 	=Operator::find($id);
		
		if($objoperator){
			$operator_name=$objoperator->name ."(operator)";
			

			$objoperator->name 		=$name;
			$objoperator->address 	=$address;
			$objoperator->phone 	=$phone;
			if($newpassword){
				$objoperator->password 	=Hash::make($newpassword);
			}
			$objoperator->update();
			
			$change_name=$name ."(operator)";
			$objoauthclient=OauthClients::wherename($operator_name)->first();
			if($objoauthclient){
				$objoauthclient->name=$change_name;
				$objoauthclient->update();
			}
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
		$objoperator=Operator::whereid($id)->first();
		if($objoperator){
			$name=$objoperator->name.'(operator)';
			User::whereid($objoperator->user_id)->delete();	
			OauthClients::wherename($name)->delete();
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
    		$response['status']=0;
    		$response['message']="There is no record to update.";
    		return Response::json($response);
    	}
    	if(!$name){
    		$response['status']=0;
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	$commissiontypes->name=$name;
    	$commissiontypes->update();

    	$response['status']=1;
    	$response['message']="Successfully update this record.";
    	$commissiontype['id']=$commissiontypes->id;
    	$commissiontype['name']=$commissiontypes->name;
    	$response['commissiontype']=$commissiontype;
    	return Response::json($response);
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
		$email			=Input::get('email');
		$password		=Input::get('password');

		$check_exiting  =User::whereemail($email)->first();
		if($check_exiting){
			$response['message']="Email is already used.";
			return Response::json($response);
		}
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

		$user=new User();
		$user->name=$name;
		$user->email=$email;
		$user->password=Hash::make($password);
		$user->type="agent";
		$user->save();
		$user_id=$user->id;

		$objagent						=new Agent();
		$objagent->agentgroup_id 		=$agentgroup_id;
		$objagent->name 				=$name;
		$objagent->phone 				=$phone;
		$objagent->address 				=$address;
		$objagent->commission_id 		=$commission_id;
		$objagent->commission 			=$commission;
		$objagent->user_id 				=$user_id;
		$objagent->save();

		$objoauthclient =new OauthClients();
		$client_id=rand(9000000,6);
		$clientname=$name ."(agent)";
		$secret =Hash::make($clientname);
		$objoauthclient->id=$client_id;
		$objoauthclient->secret=$secret;
		$objoauthclient->name=$clientname;
		$objoauthclient->save();

		$response=array();
		$login_user=array();
		$response['id']=$objagent->id;
		$response['name']=$name;
		$response['phone']=$phone;
		$response['address']=$address;

		$login_user['username']=$email;
		$login_user['password']=$password;
		$login_user['scope']="agent";
		$login_user['client_secret']=$secret;
		$login_user['client_id']=$client_id;

		$response['login_user']=$login_user;
		return Response::json($response);
	}

	public function getAllAgent(){
		$operator_id=Input::get('operator_id');
		if($operator_id){
			$objagent=Agent::whereoperator_id($operator_id)->orderBy('name', 'asc')->get();
		}else{
			$objagent=Agent::all();
		}
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
			$objagent->name 			=Input::get('name') ? Input::get('name') : $objagent->name;
			$objagent->phone 			=Input::get('phone') ? Input::get('phone') : $objagent->phone;
			$objagent->address 			=Input::get('address') ? Input::get('address') : $objagent->address;
			$objagent->commission_id 	=Input::get('commission_id') ? Input::get('commission_id') : $objagent->commission_id;
			$objagent->commission 		=Input::get('commission') ? Input::get('commission') : $objagent->commission;
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
		$objagent=Agent::whereid($id)->first();
		if($objagent){
			$name=$objagent->name.'(agent)';
			User::whereid($objagent->user_id)->delete();	
			OauthClients::wherename($name)->delete();
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
		$foreign_price		=Input::get('foregn_price');
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
		$objtrip->foreign_price =$foreign_price;
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
			$tripinfo['foreign_price']	=$objtrip->foreign_price;
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
		$bus_no 	=Input::get('bus_no') ? Input::get('bus_no') : "-"; 
		if(!$trip_id){
			$response['message'] ="Required parameter is missing.";
			return Response::json($response);
		}
		
		if(!$operator_id || !$bus_no){
			$response['message']="Request parameter is required.";
			return Response::json($response);
		}
		$today 				=$this->today;
		$year 				=date('Y');
		$checkdate 			=date('d',strtotime($today));
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
		
		//already create trip
		if($checkoccurances){
			if($checkdate>=2){
				if($trip_id && $tocreateoccurance){
					$check_exiting=$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->first();
					if($check_exiting){
						$bus_no=$check_exiting->bus_no;
					}
					$bus_no =$bus_no !=null ? $bus_no : '-';
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
					$foreign_price 		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
					$commission			=$objtrip->commission;
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
						$obj_busoccurance->foreign_price	=$foreign_price;
						$obj_busoccurance->commission		=$commission;
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
		}else{ //First create trip 
			if($tocreateoccuranceCMonth){
				$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}else{
						$bus_no ='-';
					}

					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
					$commission			=$objtrip->commission;
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
						$obj_busoccurance->foreign_price	=$foreign_price;
						$obj_busoccurance->commission		=$commission;
						$obj_busoccurance->operator_id 		=$operator_id;
						$obj_busoccurance->trip_id 			=$trip_id;
						
						$obj_busoccurance->save();
						$i++;
					}
			}
			//First create trip but today greater than 20 will create for next month.
			if($checkdate>=2){
				if($trip_id && $tocreateoccurance){
					$checkbusoccurances =BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($tocreateoccurance[0])->first();
					if($checkbusoccurances){
						$response['message']="This trip has been created for this month!";
						return Response::json($response);
					}else{
						$bus_no='-';
					}
					$objtrip 			=Trip::whereid($trip_id)->first();
					$tirp_id			=$objtrip->id;
					$seat_plan_id		=$objtrip->seat_plan_id;
					$operator_id		=$objtrip->operator_id;
					$from 				=$objtrip->from;
					$to 				=$objtrip->to;
					$class_id			=$objtrip->class_id;
					$price 				=$objtrip->price;
					$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
					$commission			=$objtrip->commission;
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
						$obj_busoccurance->foreign_price	=$foreign_price;
						$obj_busoccurance->commission		=$commission;
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
		$bus_no= BusOccurance::wheretrip_id($trip_id)->first();
		$bus_no =$bus_no !=null ? $bus_no : "-";
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
		$today 				=$this->today;
		$year 				=date('Y');
		$checkdate 			=date('d', strtotime($today));
		$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
		$currentMonth 		=date("m");
		$nextMonth 			=date("m", strtotime($month));
		$days_in_currentMonth=date("t");
		$days_in_nextMonth  =date("t", strtotime($month));
		
		$now = strtotime($this->today);
		if($checkdate >=15){
			$now = strtotime($this->today);
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
				$bus_no 			=$bus_no;
				$trip_id			=$objtrip->id;
				$operator_id		=$objtrip->operator_id;
				$from				=$objtrip->from;
				$to					=$objtrip->to;
				$class_id			=$objtrip->class_id;
				$price 				=$objtrip->price;
				$foreign_price 		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
				$commission			=$objtrip->commission;
				$time 				=$objtrip->time;
				$seat_plan_id		=$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;

				$today=$this->today;
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
						$objbusoccurance->foreign_price	=$foreign_price;
						$objbusoccurance->commission	=$commission;
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

	public static function tripautocreate($operator_id){
		if(!$operator_id){
			return false;
		}
		$today = App::make('MyDate');
		$day=date('d', strtotime($today));
		if($day <1){
			return false;
		}
		$maxbusoccurance=BusOccurance::max('id');
		$objtrips  =Trip::whereoperator_id($operator_id)->get();

		$response=array();
		if($objtrips){
			foreach ($objtrips as $trip) {
				if(strtolower($trip->available_day)=='daily'){
					$trip_id 			=$trip->id;
					$bus_no 			=BusOccurance::wheretrip_id($trip->id)->pluck('bus_no');
					$bus_no 			=$bus_no !=null ? $bus_no : "-";
					$today 				=$today;
					$year 				=date('Y');
					$nextYear			=$year + 1;
					$cMonth 			=date('m');
					$checkdate 			=date('d', strtotime($today));
					$month 				=date("Y-m-d", strtotime("+1 month", strtotime($today)));
					$nextMonth 			=date("m", strtotime($month));
					$days_in_currentMonth=cal_days_in_month(CAL_GREGORIAN,$cMonth,$year);
					$days_in_nextMonth  =cal_days_in_month(CAL_GREGORIAN,$nextMonth,$nextYear);

					$tocreateoccuranceCMonth=array();
					$tocreateoccurance=array();
					for($j=1; $j<=$days_in_currentMonth; $j++){
						$tocreateoccurance[]=$year.'-'.$cMonth.'-'.sprintf("%02s", $j);
					}
					for($i=1; $i<=$days_in_nextMonth; $i++){
						if($cMonth==12){
							$year=$nextYear;
							$nextMonth='01';
						}							
						$tocreateoccurance[]=$year.'-'.$nextMonth.'-'.sprintf("%02s", $i);
					}
					// return Response::json($tocreateoccurance);
					
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
							$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
							$commission			=$objtrip->commission;
							$time 				=$objtrip->time;
							$remark 			=$objtrip->remark;
							foreach ($tocreateoccurance as $departure_date) {
								//if close with from-to date;
								$status 			= (strtotime($departure_date) >= strtotime($objtrip->from_close_date)) && (strtotime($departure_date) <= strtotime($objtrip->to_close_date))  ? 1 : 0;
								//else if ever close;
								$status 			= $objtrip->ever_close == 1 && strtotime($departure_date) >= strtotime($objtrip->from_close_date) ? 1 : $status;

								$obj_busoccurance 	=new BusOccurance();
								$obj_busoccurance->seat_plan_id 	=$seat_plan_id;
								$obj_busoccurance->bus_no 			=$bus_no;
								$obj_busoccurance->from 			=$from;
								$obj_busoccurance->to 				=$to;
								$obj_busoccurance->classes 			=$class_id;
								$obj_busoccurance->departure_date 	=$departure_date;
								$obj_busoccurance->departure_time 	=$time;
								$obj_busoccurance->price 			=$price;
								$obj_busoccurance->foreign_price	=$foreign_price;
								$obj_busoccurance->commission		=$commission;
								$obj_busoccurance->operator_id 		=$operator_id;
								$obj_busoccurance->trip_id 			=$trip_id;
								$obj_busoccurance->status 			=$status;
								$obj_busoccurance->remark 			=$remark;
								$checkbusoccurances=BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->wheredeparture_time($time)->whereclasses($class_id)->first();
								if($checkbusoccurances){
									
								}else{
									$obj_busoccurance->save();
								}
							}	
						}
						
					}
				}elseif(substr($trip->available_day, 0,1)=='2'){
				}else{
					$trip_id 	  =$trip->id;
					$availableDays=$trip->available_day;
					$bus_no 			=BusOccurance::wheretrip_id($trip->id)->pluck('bus_no');
					$bus_no 			=$bus_no !=null ? $bus_no : "-";
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
								break;
						}
						$availabledayindexs[]=$index;
					}

					$year 				=date('Y');
					$checkdate 			=date('d', strtotime($today));
					$month 				= date("Y-m-d", strtotime("+1 month", strtotime($today)));
					$currentMonth 		=date("m");
					$nextMonth 			=date("m", strtotime($month));
					$nextYear			=$year + 1;
					$days_in_currentMonth=cal_days_in_month(CAL_GREGORIAN,$currentMonth,$year);
					$days_in_nextMonth  =cal_days_in_month(CAL_GREGORIAN,$nextMonth,$nextYear);
					
					$end_date=strtotime($year.'-'.$nextMonth.'-'.$days_in_nextMonth);
					$start_date=$year.'-'.$currentMonth.'-01';
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
								$trip_id			=$objtrip->id;
								$operator_id		=$objtrip->operator_id;
								$from				=$objtrip->from;
								$to					=$objtrip->to;
								$class_id			=$objtrip->class_id;
								$price 				=$objtrip->price;
								$foreign_price		=$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
								$commission 		=$objtrip->commission;
								$time 				=$objtrip->time;
								$seat_plan_id		=$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;
								$remark 			=$objtrip->remark;

								$today=$today;
								$count_days=count($customdays);
								// return Response::json($available_day);
								foreach ($customdays as $departure_date) {
									//if close with from-to date;
									$status 			= (strtotime($departure_date) >= strtotime($objtrip->from_close_date)) && (strtotime($departure_date) <= strtotime($objtrip->to_close_date)) ? 1 : 0;
									//else if ever close;
									$status 			= $objtrip->ever_close == 1 && strtotime($departure_date) >= strtotime($objtrip->from_close_date) ? 1 : $status;
									$objbusoccurance=new BusOccurance();
									$objbusoccurance->seat_plan_id	=$seat_plan_id;
									$objbusoccurance->bus_no		=$bus_no;
									$objbusoccurance->from			=$from;
									$objbusoccurance->to			=$to;
									$objbusoccurance->classes		=$class_id;
									$objbusoccurance->departure_date=$departure_date;
									$objbusoccurance->departure_time=$time;
									$objbusoccurance->price			=$price;
									$objbusoccurance->foreign_price	=$foreign_price;
									$objbusoccurance->commission	=$commission;
									$objbusoccurance->operator_id	=$operator_id;
									$objbusoccurance->trip_id		=$trip_id;
									$objbusoccurance->status 		=$status;
									$objbusoccurance->remark 		=$remark;
									$checkbusoccurances=BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->wheredeparture_time($time)->whereclasses($class_id)->first();
									if(!$checkbusoccurances){
										$objbusoccurance->save();
									}
								}
							}
						}
					}
				}
			}

			$currentmaxid=BusOccurance::max('id');
			if($maxbusoccurance==$currentmaxid){
				$response['message']="This trip already have been created for this month!";
			}else{
				$response['message']="Successfully has been created for this month.";
			}
		}
		// return Response::json($response);

	}

	public function putTrip($id){
		$objtrip		=Trip::find($id);
		$from			=Input::get('from');
		$to				=Input::get('to');
		$operator_id 	=Input::get('operator_id');
		$class_id 		=Input::get('class_id');
		$available_day 	=Input::get('available_day');
		$price			=Input::get('price');
		$foreign_price	=Input::get('foreign_price');
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
		$objtrip->foreign_price	=$foreign_price;
		$objtrip->time			=$time;
		$objtrip->update();

		$temp['id']				 =$objtrip->id;
		$temp['operator_id']	=$objtrip->operator_id;
		$temp['from']			=$objtrip->from;
		$temp['to']				=$objtrip->to;
		$temp['class_id']		=$objtrip->class_id;
		$temp['available_day']	=$objtrip->available_day;
		$temp['price']			=$objtrip->price;
		$temp['foreign_price']	=$objtrip->foreign_price;
		$temp['time']			=$objtrip->time;
		$response['message'] ="Successfully update this trip.";
		$response['trip_info']=$temp;
		return Response::json($response);
	}

	public function getAllTrip(){
		$operator_id=Input::get('operator_id');
		$group_by=Input::get('group_by');
		if($operator_id){
			if($group_by){
				$objtrips=Trip::whereoperator_id($operator_id)->groupby('to')->get();
			}else{
				$objtrips=Trip::whereoperator_id($operator_id)->get();
			}
		}else{
			$objtrips=Trip::all();
		}
		$trips=array();
		$i=0;
		if($objtrips){
			foreach ($objtrips as $trip) {
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
				$tmp['foreign_price']=$trip->foreign_price;
			
				$trips[]=$tmp;
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
			$tmp['foreign_price']=$objtrip->foreign_price;
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
				$temp['foreign_price']	=$trip->foreign_price;
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
				$temp['foreign_price']	=$trip->foreign_price;
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
		$foreign_price		=Input::get('foreign_price');
		$commission			=Input::get('commission');

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
		$objbusoccurance->bus_no 		=$bus_no;
		$objbusoccurance->classes 		=$classes;
		$objbusoccurance->price 		=$price;
		$objbusoccurance->foreign_price	=$foreign_price;
		$objbusoccurance->commission 	=$commission;
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
		$seat_plan_id 	=Input::get('seat_plan_id') !=null ? Input::get('seat_plan_id') : $objbusoccurance->seat_plan_id;
		$bus_no 		=Input::get('bus_no') !=null ? Input::get('bus_no') : $objbusoccurance->bus_no;
		$from 			=Input::get('from') !=null ? Input::get('from') : $objbusoccurance->from;
		$to 			=Input::get('to') !=null ? Input::get('to') : $objbusoccurance->to;
		$classes 		=Input::get('class_id') !=null ? Input::get('class_id') : $objbusoccurance->class_i;;
		$departure_date =Input::get('departure_date') !=null ? Input::get('departure_date') : $objbusoccurance->departure_date;
		$departure_time =Input::get('departure_time') !=null ? Input::get('departure_time') : $objbusoccurance->departure_time;
		$price 			=Input::get('price') !=null ? Input::get('price') : $objbusoccurance->price;
		$foreign_price	=Input::get('foreign_price') !=null ? Input::get('foreign_price') : $objbusoccurance->price;
		$commission		=Input::get('commission') !=null ? Input::get('commission') : $objbusoccurance->commission;
		$operator_id 	=Input::get('operator_id') !=null ? Input::get('operator_id') : $objbusoccurance->operator_id;
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
			$objbusoccurance->foreign_price	=$foreign_price;
			$objbusoccurance->commission	=$commission;
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
			$temp['foreign_price'] =$objbusoccurance->foreign_price;
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

		$today 				=$this->today;
		$currentYear		=date('Y');
		$currentMonth		=date('m');
		$currentDay			=date('d', strtotime($today));
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
			$foreign_price		=$objtrip->foreign_price;
			$commission			=$objtrip->commission;
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
							$obj_busoccurance->foreign_price	=$foreign_price;
							$obj_busoccurance->commission		=$commission;
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
		$now_date = $this->today;
    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->wherebooking(0)->where('name','=','')->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}

    	/*
    	 * Delete not confirm order;
    	 */
    		SaleOrder::wherebooking(1)->where('departure_datetime','<',$this->today)->delete();
    	
		$ticket_type_id=Input::get('ticket_type_id');
		$operator_id=Input::get('operator_id');
		$date=Input::get('date');
		$time=Input::get('time');
		$from=Input::get('from_city');
		$to=Input::get('to_city');
		$class_id=Input::get('class_id');
		$todaydate=$this->today;

		if(!$operator_id || !$from || !$to){
			$response['message']="PleaseRequire fields are operator_id, from_city and to_city.";
			return Response::json($response);
		}
		/*if($date < $todaydate){
			$response['message']="Departure Date should be greater than or equal today date.";
			return Response::json($response);
		}*/

		$buslist=array();

		if($date && !$time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->whereclasses($class_id)
									->where('departure_date','=',$date)
									->wherestatus(0)->get();
		}
		elseif($date && $time){
			$date=date('Y-m-d', strtotime($date));
			$buslist=BusOccurance::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->whereclasses($class_id)
									->where('departure_date','=',$date)
									->where('departure_time','=',$time)
									->wherestatus(0)
									->get();
		}
		elseif(!$date && !$time)
		{
			$buslist=BusOccurance::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->whereclasses($class_id)
									->wherestatus(0)
									->get();
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
				$seatplan['classes']=Classes::whereid($row->classes)->pluck('name');
				$seatplan['departure_date']=$row->departure_date;
				$seatplan['departure_time']=$row->departure_time;
				$seatplan['arrival_time']=$row->arrival_time;
				$seatplan['price']=$row->price;
				$seatplan['foreign_price']=$row->foreign_price;
				$seatplan['operator_id']=$row->operator_id;
				
				if($objseat){
					$seatplan['seat_layout_id']  =$objseat->seat_layout_id;
					$seatplan['row']  			 =$objseat->row;
					$seatplan['column']  		 =$objseat->column;
				}else{
					$seatplan['seat_layout_id']='-';
					$seatplan['row']='-';
					$seatplan['column']='-';
				}
				
				// $seatplan['seatlist']		 =$objseat->seat_list;

				
				$objcloseseatinfo 				 =CloseSeatInfo::wheretrip_id($row->trip_id)->whereseat_plan_id($objseat->id)->first();
				$seatinfo 	=array();
				if($objcloseseatinfo){
					$seat_lists = $objcloseseatinfo->seat_lists;
					$objseatinfo = json_decode($seat_lists);
					foreach ($objseatinfo as $rows) {
						$temp['id']			=$rows->id;
						$temp['seat_no']	=$rows->seat_no;
						$busoccurance_id 	=$row->id;
						$checkoccupied_seat =SaleItem::wherebusoccurance_id($busoccurance_id)
												->whereseat_no($rows->seat_no)->first(array('order_id','ticket_no','name','phone','nrc_no','agent_id'));
						if($checkoccupied_seat){
							$temp['status']			= 2;
							$temp['booking']		= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('booking');
							$temp['remark_type']	= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark_type');
							$temp['remark']			= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark');
							$temp['customer_info']  = $checkoccupied_seat->toarray();
							$temp['customer_info']['agent_name'] = Agent::whereid($checkoccupied_seat->agent_id)->pluck('name');
						}else{
							$temp['status']			= $rows->status;
							$temp['booking']		= 0;
							$temp['remark_type']	= 0;
							$temp['remark']			= null;
							$temp['customer_info']  = null;
						}
						$temp['operatorgroup_id']	=$rows->operatorgroup_id;
						$seatinfo[] 		=$temp;
					}
				}else{
					$objseatinfo 				 =SeatInfo::whereseat_plan_id($objseat->id)->get();
					foreach ($objseatinfo as $rows) {
						$temp['id']			=$rows->id;
						$temp['seat_no']	=$rows->seat_no;
						$busoccurance_id 	=$row->id;
						$checkoccupied_seat =SaleItem::wherebusoccurance_id($busoccurance_id)
												->whereseat_no($rows->seat_no)->first(array('order_id','ticket_no','name','phone','nrc_no','agent_id'));
						if($checkoccupied_seat){
							$temp['status']			=2;
							$temp['booking']		= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('booking');
							$temp['remark_type']	= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark_type');
							$temp['remark']			= SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('remark');
							$temp['customer_info']  = $checkoccupied_seat->toarray();
							$temp['customer_info']['phone'] = SaleOrder::whereid($checkoccupied_seat->order_id)->pluck('phone');
							$temp['customer_info']['agent_name'] = Agent::whereid($checkoccupied_seat->agent_id)->pluck('name');
						}else{
							$temp['status']			=$rows->status;
							$temp['booking']		= 0;
							$temp['remark_type']	= 0;
							$temp['remark']			= null;
							$temp['customer_info']  = null;
						}
						
						$temp['operatorgroup_id']	= 0;
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
		/*
    	 * Delete not confirm order;
    	 */
    		SaleOrder::wherebooking(1)->where('departure_datetime','<',$this->today)->delete();

		$operator_id		=Input::get('operator_id');
		$from_city			=Input::get('from_city');
		$to_city			=Input::get('to_city');
		$ticket_type_id		=Input::get('ticket_type_id');
		$trip_date 			=Input::get('trip_date');

		if($operator_id && $from_city && $to_city && $trip_date){
			$objtrip=BusOccurance::whereoperator_id($operator_id)
					->wheredeparture_date($trip_date)
					->wherefrom($from_city)
					->whereto($to_city)
					->orderBy('departure_time','asc')
					->wherestatus(0)
					->get();
		}elseif($operator_id && !$from_city && !$to_city){
			$objtrip=BusOccurance::whereoperator_id($operator_id)
					->orderBy('departure_time','asc')->get();
		}else{
			$objtrip=BusOccurance::orderBy('departure_time','asc')->get();
		}
		$times=array();
		//return Response::json($objtrip);
		if($objtrip){
			foreach ($objtrip as $row) {
				$temp['tripid']				= $row->id;
				$temp['class_id'] 			= $row->classes;
				$temp['bus_class']			= Classes::whereid($row->classes)->pluck('name');
				$temp['total_seat']			= SeatInfo::whereseat_plan_id($row->seat_plan_id)->wherestatus(1)->count();
				$temp['total_sold_seat']	= SaleItem::wheretrip_id($row->trip_id)->wheredeparture_date($trip_date)->count();
				$temp['time']				= $row->departure_time;
				$times[]					= $temp;
			}
		}
		$tmp_times=$this->msort($times,array("bus_class","time"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);

		return Response::json($tmp_times); 
	}

	function msort($array, $key, $sort_flags = SORT_REGULAR, $order) {
	    if (is_array($array) && count($array) > 0) {
	        if (!empty($key)) {
	            $mapping = array();
	            foreach ($array as $k => $v) {
	                $sort_key = '';
	                if (!is_array($key)) {
	                    $sort_key = $v[$key];
	                } else {
	                    // @TODO This should be fixed, now it will be sorted as string
	                    foreach ($key as $key_key) {
	                        $sort_key .= $v[$key_key];
	                    }
	                    $sort_flags = SORT_STRING;
	                }
	                $mapping[$k] = $sort_key;
	            }
	            switch ($order) {
		            case SORT_ASC:
		                asort($mapping, $sort_flags);
		            break;
		            case SORT_DESC:
		                arsort($mapping, $sort_flags);
		            break;
		        }
	            // asort($mapping, $sort_flags);
	            // arsort($mapping, $sort_flags);
	            $sorted = array();
	            foreach ($mapping as $k => $v) {
	                $sorted[] = $array[$k];
	            }
	            return $sorted;
	        }
	    }
	    return $array;
	}

	public function postSale(){
    	/*
    	 * Calulate Expired Time
    	 */
	    	$now_date = $this->getDateTime();
			$currentDate = strtotime($now_date);
			$futureDate = $currentDate+(60*15);//add 15 minutes for expired_time;
			$expired_date = date("Y-m-d H:i:s", $futureDate);

		/*
		 * Method Variables
		 */
			$available_tickets	= 0;
    		$available_seats	= array();
    		$order_auto_id		= 0;
    	
    	/*
    	 * From Parameters
    	 */
	    	$operator_id 			= Input::get('operator_id');
	    	$agent_id 				= Input::get('agent_id');
	    	$name 	 				= Input::get('name');
	    	$phone  				= Input::get('phone');
	    	$remark_type  			= Input::get('remark_type');
	    	$remark  				= Input::get('remark');
	    	$seat_liststring 		= Input::get('seat_list');
	    	$group_operator_id 		= Input::get('group_operator_id') ? Input::get('group_operator_id') : 0;
	    	$booking 				= Input::get('booking');
	    	$device_id				= Input::get('device_id');
	    	$user_id 				= Input::get('user_id');
	  
    	
    	/*
    	 * Check Required Field.
    	 */
	    	if(!$operator_id || !$seat_liststring){
	    		$response['status'] = 0;
	    		$response['message']='Required fields are operator_id and seat_lsit';
	    		return Response::json($response);
	    	}

	    	$seat_list=json_decode($seat_liststring);
	    	if(count($seat_list)<1){
	    		$response['status'] = 0;
	    		$response['message']='Seat_list format is wrong.';
	    		return Response::json($response);
	    	}

    	/*
    	 * Delete Expired Order.
    	 */
    	/*$expired_ids=SaleOrder::where('expired_at','<',$now_date)->wherebooking(0)->where('name','=','')->lists('id');
    	if($expired_ids){
    		foreach ($expired_ids as $expired_id) {
    			SaleOrder::whereid($expired_id)->delete();
    			SaleItem::whereorder_id($expired_id)->delete();
    		}
    	}*/
	    /*
    	 * Delete Expired Order.
    	 */
	    	$expired_ids=SaleOrder::where('expired_at','<',$now_date)->wherebooking(0)->where('name','=','')->lists('id');
	    	if($expired_ids){
	    		foreach ($expired_ids as $expired_id) {
	    			$saleOrder = SaleOrder::whereid($expired_id)->first();
	    			$saleItem = SaleItem::whereorder_id($expired_id)->get();
	    			if($saleOrder){
	    				$check = DeleteSaleOrder::whereid($expired_id)->first();
	    				if(!$check){
	    					$deletedSaleOrder = DeleteSaleOrder::create($saleOrder->toarray());
		    				if($deletedSaleOrder){
		    					SaleOrder::whereid($expired_id)->delete();
		    				}
	    				}
	    			}
	    			if($saleItem){
	    				foreach ($saleItem as $rows) {
	    					$check = DeleteSaleItem::whereid($rows->id)->first();
	    					if(!$check){
	    						$deletedSaleitem = DeleteSaleItem::create($rows->toarray());
	    						if($deletedSaleitem){
	    							SaleItem::whereorder_id($expired_id)->delete();
	    						}
	    					}
	    				}
	    			}
	    		}
	    	}
   		
   		
   		$canby 		= true;
   		$all_canby 	= true;
    	foreach ($seat_list as $rows) {

    		$busoccuranceid = $rows->busoccurance_id;
    		$seat_plan_id   = BusOccurance::whereid($busoccuranceid)->pluck('seat_plan_id');
    		$trip_id 	    = BusOccurance::whereid($busoccuranceid)->pluck('trip_id');
    		$departure_date = BusOccurance::whereid($busoccuranceid)->pluck('departure_date');
    		$departure_time = BusOccurance::whereid($busoccuranceid)->pluck('departure_time');
    		$objseatinfo    = SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows->seat_no)->first();
    		
    		/*
    		 * Calculate Departure Datetime;
    		 */
    			$time 		= substr($departure_time, 0, 8);
    			$time 		= $time == '12:00 AM' ? '12:00 PM' : $time;
    			$datetime 	= $departure_date." ".$time;
    			$strdate  	= strtotime($datetime);
    			$strdate  	= $strdate - ((60*60) * 1);
    			$departure_datetime = date("Y-m-d H:i:s", $strdate);

    		/*
    		 * Checking Timeout Booking Transaction;
    		 */
    			if(strtotime($departure_datetime) < strtotime($this->today) && $booking == 1){
    				$response['status'] = 0;
    				$response['message']='Cann\'t booking because of time out';
	    			return Response::json($response);
    			}


    		/*
			 * Checking Availabel Seat;
    		 */
	    		$chkstatus = SaleItem::wherebusoccurance_id($busoccuranceid)->whereseat_no($rows->seat_no)->first();
	    		if($chkstatus){
		    		$canbuy=false;
		    		$all_canby = false;
	    		}
	    		else{
	    			$canbuy=true;
	    			$tmp['seat_no']			=$rows->seat_no;
	    			$tmp['busoccurance_id']	=$rows->busoccurance_id;
	    			$available_seats[]=$tmp;
	    		}

	    	$temp['seat_id']	= $objseatinfo->id;
	    	$temp['seat_no']	= $objseatinfo->seat_no;
    		$temp['can_buy']	= $canbuy;
    		$temp['bar_code']	= 11111111111;
    		$tickets[]=$temp;
    	}

    	if(count($available_seats) == count($seat_list) && $all_canby){
    		try {
    			$response['message']="Successfully your purchase or booking tickets.";
    			$can_buy=true;
    			$order_auto_id = $this->generateAutoID($group_operator_id);
    			$objsaleorder=new SaleOrder();
    			$objsaleorder->id 					= $order_auto_id;
	    		$objsaleorder->orderdate 			= $this->today;
	    		$objsaleorder->departure_date 		= $departure_date;
	    		$objsaleorder->departure_datetime 	= $departure_datetime;
	    		$objsaleorder->agent_id 			= $agent_id ? $agent_id : 0;
	    		$objsaleorder->name 	 			= $name;
	    		$objsaleorder->phone 	 			= $phone;
	    		$objsaleorder->remark_type 	 		= $remark_type;
	    		$objsaleorder->remark 	 			= $remark;
	    		$objsaleorder->operator_id 			= $operator_id;
	    		$objsaleorder->expired_at 			= $expired_date;
	    		$objsaleorder->device_id 			= $device_id;
	    		$objsaleorder->booking 				= $booking;
	    		$objsaleorder->user_id 				= $user_id ? $user_id : 0;
	    		$objsaleorder->save();
	    		
	    		foreach ($available_seats as $rows) {
	    			$check_exiting=SaleItem::wherebusoccurance_id($rows['busoccurance_id'])->whereseat_no($rows['seat_no'])->first();
	    			if(!$check_exiting){
	    				$busoccuranceinfo 				= BusOccurance::whereid($rows['busoccurance_id'])->first();
	    				if($busoccuranceinfo){
							$objsaleitems					=new SaleItem();
			    			$objsaleitems->order_id 		=$order_auto_id;
			    			$objsaleitems->busoccurance_id 	=$busoccuranceinfo->id;
			    			$objsaleitems->name 		 	=$name;
			    			$objsaleitems->phone 		 	=$phone;
			    			$objsaleitems->class_id			=$busoccuranceinfo->classes;
			    			$objsaleitems->trip_id 		 	=$busoccuranceinfo->trip_id;
			    			$objsaleitems->from 		 	=$busoccuranceinfo->from;
			    			$objsaleitems->to 	 		 	=$busoccuranceinfo->to;
			    			$objsaleitems->seat_no			=$rows['seat_no'];
			    			$objsaleitems->device_id		=$device_id;
			    			$objsaleitems->operator			=$operator_id;
			    			$objsaleitems->agent_id 		= $agent_id ? $agent_id : 0;
			    			$objsaleitems->price			=$busoccuranceinfo->price;
			    			$objsaleitems->foreign_price	=$busoccuranceinfo->foreign_price;
			    			$objsaleitems->departure_date	=$departure_date;
			    			$objsaleitems->save();
	    				}
	    			}
	    		}
    		} catch (Exception $e) {
    			$response['message']="Something was wrong!.";
    			$all_canby=false;
    		}
	    		
    	}else{
	    	$response['message']="Unfortunately your purchase or booking some tickets have been taken by another customer.";
    		$all_canby=false;
    	}

    	$available_device_id=SaleOrder::whereid($order_auto_id)->pluck('device_id');
    	$check_saleitem_count=SaleItem::whereorder_id($order_auto_id)->wheredevice_id($available_device_id)->count();
    	$response['status'] = 1;
    	if($check_saleitem_count == count($available_seats)){
    		$response['sale_order_no']=$order_auto_id;
	    	$response['device_id']=$available_device_id;
	    	$response['can_buy']=$all_canby;
	    	$response['tickets']=$tickets;
    		return Response::json($response);
    	}else{
    		$sale_order = SaleOrder::whereid($order_auto_id)->first();
    		if($sale_order)
    			$sale_order->delete();
    		$response['sale_order_no']=$order_auto_id;
	    	$response['device_id']="-";
	    	$response['can_buy']=$all_canby;
	    	$response['tickets']=$tickets;
    		return Response::json($response);

    	}
    }

    public function generateAutoID($prefixs){
    	$prefix = $prefixs."_";
    	$autoid 			= 0;
    	$last_order_id 		= SaleOrder::where('id','like',$prefix.'%')->orderBy('id','desc')->limit('1')->pluck('id');
    	if($last_order_id){
    		$last_order_value 	= (int) substr($last_order_id, strlen($prefix));
    		
    	}else{
    		return $prefix."0000001";
    	}

    	
    	if($last_order_value >= 0 && $last_order_value <9){
    		$inc_value = ++$last_order_value;
    		$autoid = "000000".$inc_value;
    	}elseif($last_order_value >= 9 && $last_order_value <99){
    		$inc_value = ++$last_order_value;
    		$autoid = "00000".$inc_value;
    	}elseif($last_order_value >= 99 && $last_order_value <999){
    		$inc_value = ++$last_order_value;
    		$autoid = "0000".$inc_value;
    	}elseif($last_order_value >= 999 && $last_order_value <9999){
    		$inc_value = ++$last_order_value;
    		$autoid = "000".$inc_value;
    	}elseif($last_order_value >= 9999 && $last_order_value <99999){
    		$inc_value = ++$last_order_value;
    		$autoid = "00".$inc_value;
    	}elseif($last_order_value >= 99999 && $last_order_value <999999){
    		$inc_value = ++$last_order_value;
    		$autoid = "0".$inc_value;
    	}elseif($last_order_value >= 999999 && $last_order_value <9999999){
    		$inc_value = ++$last_order_value;
    		$autoid = $inc_value;
    	}
    	return $prefix.$autoid;
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
    	$reference_no	=Input::get('reference_no');
    	$agent_id 		=Input::get('agent_id');
    	$buyer_name		=Input::get('buyer_name');
    	$agent_id		=Input::get('agent_id');
    	$agent_name		=Input::get('agent_name');

    	$phone			=Input::get('phone');
    	$nrc_no			=Input::get('nrc_no');
    	$tickets 		=Input::get('tickets');
    	$cash_credit 	=Input::get('cash_credit');
    	$nationality	=Input::get('nationality');
    	$device_id		=Input::get('device_id');
    	$booking		=Input::get('booking') ? Input::get('booking') : 0;
    	$extra_dest_id  =Input::get('extra_dest_id') ? Input::get('extra_dest_id') : 0;
    	$remark_type 	=Input::get('remark_type');
		$remark 		=Input::get('remark');

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
    		if(!$objsaleorder){
    			$message['status']=0;
    			$message['message']="There is no order.";
    			return Response::json($message);
    		}
    		$order_date=Input::get('order_date');
    		$objsaleorder->orderdate=$order_date ? $order_date : $this->today;
			$operator_id=BusOccurance::whereid($json_tickets[0]->busoccurance_id)->pluck('operator_id');
			
			if($agent_name && !$agent_id){
	    		$objagent=new Agent();
	    		$check_exiting=Agent::wherename($agent_name)->first();
	    		if(!$check_exiting){
	    			$objagent->name=$agent_name;
	    			$objagent->operator_id=$operator_id;
	    			$objagent->save();
	    			$agent_id=$objagent->id;
	    		}
	    	}
    		/*$same=true;	
    		foreach ($json_tickets as $rows) {
    			$same_saleitems=SaleItem::whereorder_id($sale_order_no)->wheredevice_id($device_id)->whereseat_no($rows->seat_no)->wherebusoccurance_id($rows->busoccurance_id)->first();
    			if(!$same_saleitems){
    				$same=false;
    				SaleItem::whereorder_id($sale_order_no)->wheredevice_id($device_id)->delete();
    			}
    		}	
    		if($same==false){
    			$response['device_id']=$device_id;
				$response['status']=$same;
				$response['message']='Sorry. Some ticket have been taken by another customer.';
				return Response::json($response);
    		}*/

    		


    		foreach ($json_tickets as $rows) {
    			$objsaleitems=SaleItem::whereorder_id($sale_order_no)->whereseat_no($rows->seat_no)->wherebusoccurance_id($rows->busoccurance_id)->first();
    			$objsaleitems->order_id 		=$sale_order_no;
    			$objsaleitems->busoccurance_id 	=$rows->busoccurance_id;
    			$objsaleitems->seat_no			=$rows->seat_no;
    			$objsaleitems->name				=$rows->name;
    			$objsaleitems->nrc_no			=$rows->nrc_no;
    			$objsaleitems->ticket_no		=$rows->ticket_no;
    			if($rows->free_ticket == "true"){
    				$objsaleitems->free_ticket		= 1;
    				$objsaleitems->free_ticket_remark = $rows->free_ticket_remark;
    			}
				$objsaleitems->agent_id 		=$agent_id; 
				if($extra_dest_id != 0){
					$extra_destination 					= ExtraDestination::whereid($extra_dest_id)->first();
					$objsaleitems->extra_destination_id = $extra_dest_id;
					$objsaleitems->price 				= $extra_destination->local_price;
					$objsaleitems->foreign_price 		= $extra_destination->foreign_price;
					$objsaleitems->extra_city_id		= $extra_destination->city_id;
				} 			
    			$objsaleitems->update();

    			$seat_plan_id=BusOccurance::whereid($rows->busoccurance_id)->pluck('seat_plan_id');
    			$changestatus=SeatInfo::whereseat_plan_id($seat_plan_id)->whereseat_no($rows->seat_no)->first();
    			if($changestatus){
    				$changestatus->status=1;
    				$changestatus->update();
    			}
    		}

    		if($nationality != null && $nationality == 'foreign'){
    			$total_amount=SaleItem::whereorder_id($sale_order_no)->sum('foreign_price');
    		}else{
    			$total_amount=SaleItem::whereorder_id($sale_order_no)->sum('price');
    		}

    		$objsaleorder->reference_no=$reference_no;
    		$objsaleorder->agent_id=$agent_id;
    		$objsaleorder->name=$buyer_name;
    		$objsaleorder->nrc_no=$nrc_no;
    		$objsaleorder->phone=$phone;
    		if($total_amount > 0){
	    		$objsaleorder->cash_credit=$cash_credit;
    		}
    		$objsaleorder->total_amount=$total_amount;
    		$objsaleorder->nationality=$nationality == null ? 'local' : $nationality;
    		// $objagent=Agent::whereid($agent_id)->first();

    		$total_commission=0;
    		$trip_id=BusOccurance::whereid($json_tickets[0]->busoccurance_id)->pluck('trip_id');
    		$objagent=AgentCommission::whereagent_id($agent_id)->wheretrip_id($trip_id)->first();
    		$total_ticket=count($json_tickets);
    		if($objagent){
    			$commission=CommissionType::whereid($objagent->commission_id)->pluck('name');

    			if($commission=='Fixed'){
    				$commission_per_ticket=$objagent->commission;
    				$total_commission=$commission_per_ticket * $total_ticket;
    			}else{
    				$commission_per_ticket=$objagent->commission;
    				$total_commission=($total_amount * $commission_per_ticket ) / 100;
    			}
    		}else{
    			$total_commission = Trip::whereid($trip_id)->pluck('commission') * $total_ticket;
    		}
    		$objsaleorder->agent_commission=$total_commission;
    		$objsaleorder->booking = $booking;
    		$objsaleorder->remark_type=$remark_type;
    		$objsaleorder->remark 	  =$remark;
    		$objsaleorder->update();

    		//Payment Transaction
    		if($booking == 0){
    			$total_amount 				= $objsaleorder->total_amount - $objsaleorder->agent_commission;
    			$objdepositpayment_trans	= new AgentDeposit();
	    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
	    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
	    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
	    		$today 										= $this->today;
	    		$objdepositpayment_trans->pay_date			= $today;
	    		$objdepositpayment_trans->payment 			= 0;
	    		$agentdeposit 				= AgentDeposit::whereagent_id($objsaleorder->agent_id)->whereoperator_id($objsaleorder->operator_id)->orderBy('id','desc')->first();
	    		if($agentdeposit){
	    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
	    			$objdepositpayment_trans->balance 		= $agentdeposit->balance - $total_amount;
	    		}else{
	    			$objdepositpayment_trans->deposit 		= 0;
	    			$objdepositpayment_trans->balance 		= 0 - $total_amount;
	    		}  		
	    		$objdepositpayment_trans->debit 			= 0;
	    		$objdepositpayment_trans->save();

	    		$saleOrder = SaleOrder::whereid($objsaleorder->id)->first();
	    		$saleOrder->cash_credit = 1;
	    		$saleOrder->update();
    		}
    	}
    	$response['device_id']=$device_id;
		$response['status']=true;
    	$response['message']='Successfully your order ticket.';
    	return Response::json($response);
    }

    public function getCreditSale(){
    	$response=array();
    	$operator_id=Input::get('operator_id');
    	$agent_id 	=Input::get('agent_id');
    	$order_id 	=Input::get('order_id');
    	$limit=Input::get('limit') ? Input::get('limit') : 8;
    	$offset=Input::get('offset') ? Input::get('offset') : 1;
    	$offset =($offset-1) * $limit;
    	if($order_id && !$agent_id){
    		$response=SaleOrder::whereid($order_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)
					->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}
    	else{
    		if($operator_id && $agent_id){
	    		$response=SaleOrder::whereoperator_id($operator_id)->whereagent_id($agent_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}elseif($operator_id && !$agent_id){
	    		$response=SaleOrder::whereoperator_id($operator_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}elseif(!$operator_id && $agent_id){
	    		$response=SaleOrder::whereagent_id($agent_id)->wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}else{
	    		$response=SaleOrder::wherecash_credit(2)->with(array('saleitems'))->take($limit)->skip($offset)->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
	    	}
    	}
    	// return Response::json($response);
    	if(!$operator_id && !$agent_id){
    		$response=array();
    	}
    	if($response){
    		$i=0;
    		foreach ($response as $row) {
    			$response[$i]=$row;
    			$trip='-';
    			$price=0;
    			$amount=0;
    			$tickets=count($row->saleitems);
    			if(count($row->saleitems)>0){
    				$commissiontype='-';
    				$commission='-';
    				$topayprice=0;
    				$toreduceamountperticket=0;
    				$objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
    				if($objbusoccurance){
    					$trip_id=$objbusoccurance->trip_id;
    					$agentcommission=AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
    					if($agentcommission){
    						if($agentcommission->commission_id==2){
    							$commissiontype='fixed';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=$agentcommission->commission;
    						}else{
    							$commissiontype='percentage';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=($agentcommission->commission/100) * $objbusoccurance->price;
    						}
    					}else{
    						if($row->agent_id > 0){
    							$commissiontype='trip';
    							$commission=$objbusoccurance->commission;
    							$toreduceamountperticket=$objbusoccurance->commission;
    						}
    					}
    					$from=City::whereid($objbusoccurance->from)->pluck('name');
    					$to=City::whereid($objbusoccurance->to)->pluck('name');
    					$trip=$from.'-'.$to;
    					if($row->nationality == 'foreign' && $objbusoccurance->foreign_price > 0){
    						$price=$objbusoccurance->foreign_price;
    					}else{
    						$price=$objbusoccurance->price;
    					}
    					
    					$amount= $price * $tickets;
    				}
    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
    				$response[$i]['customer']=$objorderinfo->name;
    				$response[$i]['phone']=$objorderinfo->phone;
    			}else{
    				$response[$i]['customer']='-';
    				$response[$i]['phone']='-';
    			}
				$operator=Operator::whereid($row->operator_id)->pluck('name');
				$agent=Agent::whereid($row->agent_id)->pluck('name');
    			$response[$i]['operator']=$operator;
    			$response[$i]['agent']=$agent;
    			$response[$i]['trip']=$trip;
    			$response[$i]['total_ticket']=$tickets;
    			$response[$i]['price']=$price;
    			$response[$i]['commissiontype']=$commissiontype;
    			$response[$i]['commission']= $commission;
    			$response[$i]['to_pay_amount']= $amount - ($toreduceamountperticket * $tickets);
    			$response[$i]['amount']=$amount;
    			$i++;
    		}
    	}
    	return Response::json($response);
    }

    public function getSaleOrder(){
    	$response 		=array();
    	$operator_id 	=Input::get('operator_id');
    	$departure_date 	=Input::get('departure_date');
    	$from 	 		=Input::get('from');
    	$to 	 		=Input::get('to');
    	$time 			=Input::get('time');
    	if(!$operator_id){
    		$response['status']=0;
    		$response['message']='Required Any Parameter.';
    		return Response::json($response, 400);
    	}
    	if($departure_date){
    		if($from && $to){
    			$trip_id = Trip::wherefrom($from)->whereto($to)->where('time','LIKE', $time.'%')->lists('id');
    			if($trip_id){
    				$response=SaleOrder::with(array('saleitems'))
    				->whereHas('saleitems',  function($query) use ($trip_id) {
										$query->wherein('trip_id', $trip_id);
										})
    				->wherebooking(1)
    				->wheredeparture_date($departure_date)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
    			}
    			
    		}else{
    			$response=SaleOrder::with(array('saleitems'))
    				->wherebooking(1)
    				->wheredeparture_date($departure_date)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
    		}
    		
		}else{
			$response=SaleOrder::with(array('saleitems'))
    				->wherebooking(1)
    				->whereoperator_id($operator_id)
					->get(array('id', 'orderdate', 'created_at', 'agent_id', 'operator_id'));
		}

    	
    	if($response){
    		$i=0;
    		foreach ($response as $row) {
    			$response[$i]=$row;
    			$trip='-';
    			$time='-';
    			$date='-';
    			$classes='-';
    			$price=0;
    			$amount=0;
    			$tickets=count($row->saleitems);
    			if(count($row->saleitems)>0){
    				$commissiontype='-';
    				$commission='-';
    				$topayprice=0;
    				$toreduceamountperticket=0;
    				$objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
    				if($objbusoccurance){
    					$trip_id=$objbusoccurance->trip_id;
    					$agentcommission=AgentCommission::whereagent_id($row->agent_id)->wheretrip_id($trip_id)->first();
    					if($agentcommission){
    						if($agentcommission->commission_id==2){
    							$commissiontype='fixed';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=$agentcommission->commission;
    						}else{
    							$commissiontype='percentage';
    							$commission=$agentcommission->commission;
    							$toreduceamountperticket=($agentcommission->commission/100) * $objbusoccurance->price;
    						}
    					}else{
    						if($row->agent_id > 0){
    							$commissiontype='trip';
    							$commission=$objbusoccurance->commission;
    							$toreduceamountperticket=$objbusoccurance->commission;
    						}
    					}
    					$from=City::whereid($objbusoccurance->from)->pluck('name');
    					$to=City::whereid($objbusoccurance->to)->pluck('name');
    					$trip  		=$from.'-'.$to;
    					$time 		=$objbusoccurance->departure_time;
    					$date 		=$objbusoccurance->departure_date;
    					$classes 	=Classes::whereid($objbusoccurance->classes)->pluck('name');
    					if($row->nationality == 'foreign' && $objbusoccurance->foreign_price > 0){
    						$price=$objbusoccurance->foreign_price;
    					}else{
    						$price=$objbusoccurance->price;
    					}
    					
    					$amount= $price * $tickets;
    				}
    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
    				$response[$i]['customer']=$objorderinfo->name;
    				$response[$i]['phone']=$objorderinfo->phone;
    			}else{
    				$response[$i]['customer']='-';
    				$response[$i]['phone']='-';
    			}
				$operator=Operator::whereid($row->operator_id)->pluck('name');
				$agent=Agent::whereid($row->agent_id)->pluck('name');
				$response[$i]['orderdate'] =date('d/m/Y h:i:s a',strtotime($row->orderdate.' '.date('h:i:s a',strtotime($row->created_at) + ((60*60) * 6.5))));
    			$response[$i]['operator']=$operator;
    			$response[$i]['agent']=$agent;
    			$response[$i]['trip']=$trip;
    			$response[$i]['time']=$time;
    			$response[$i]['classes']=$classes;
    			$response[$i]['date']=$date;
    			$response[$i]['total_ticket']=$tickets;
    			$response[$i]['price']=$price;
    			$response[$i]['commissiontype']=$commissiontype;
    			$response[$i]['commission']= $commission;
    			$response[$i]['to_pay_amount']= $amount - ($toreduceamountperticket * $tickets);
    			$response[$i]['amount']=$amount;
    			$i++;
    		}
    	}
    	return Response::json($response);
    }

    public function postAgentCommission(){
    	
    	$existingAgentCommission = AgentCommission::whereagent_id(Input::get('agent_id'))->wheretrip_id(Input::get('trip_id'))->first();
    	if(count($existingAgentCommission) > 0){
	    	$existingAgentCommission->agent_id 		= Input::get('agent_id');
	    	$existingAgentCommission->trip_id  		= Input::get('trip_id');
	    	$existingAgentCommission->commission_id = Input::get('commission_id');
	    	$existingAgentCommission->commission 	= Input::get('commission');
	    	$existingAgentCommission->save();
	    	return Response::json($existingAgentCommission);
    	}else{
    		$agentcommission 				= new AgentCommission();
	    	$agentcommission->agent_id 		= Input::get('agent_id');
	    	$agentcommission->trip_id  		= Input::get('trip_id');
	    	$agentcommission->commission_id = Input::get('commission_id');
	    	$agentcommission->commission 	= Input::get('commission');
	    	$agentcommission->save();
	    	return Response::json($agentcommission);
    	}
    	
    }

    public function getAgentCommission(){
    	$agentcommission = AgentCommission::whereagent_id(Input::get('agent_id'))->get();
    	$i = 0;
    	foreach ($agentcommission as $rows) {
    		$trip = Trip::whereid($rows->trip_id)->first();
    		$agentcommission[$i]['time'] = $trip->time;
    		$agentcommission[$i]['trip'] = City::whereid($trip->from)->pluck('name').' - '.City::whereid($trip->to)->pluck('name');
    		$agentcommission[$i]['commission_name'] = CommissionType::whereid($rows->commission_id)->pluck('name');
    		$i++;
    	}
    	return Response::json($agentcommission);
    }

    public function postDeleteCreditSaleOrderNo($order_id){
    	$objorder=SaleOrder::whereid($order_id)->first();
    	if($objorder){
    		$objorder->delete();
    		SaleItem::whereorder_id($order_id)->delete();
    		$response['status']=1;
    		$response['message']='Successfully delete credit order.';
    	}else{
    		$response['status']=0;
    		$response['message']='There is no credit order with this order no .';	 
    	}
    	return Response::json($response);
    }

    public function postPayCreditSaleOrderNo($order_id){
    	$objorder=SaleOrder::whereid($order_id)->wherecash_credit(2)->first();
    	if($objorder){
    		$objorder->cash_credit=1;
    		$objorder->update();
    		$response['status']=1;
    		$response['message']='Successfully payment for credit order.';
    	}else{
    		$response['status']=0;
    		$response['message']='There is no credit order with this order no .';	 
    	}
    	return Response::json($response);
    }

    public function postConfirmBookingOrder($order_id){
    	$objorder=SaleOrder::whereid($order_id)->wherebooking(1)->first();
    	if($objorder){
    		$total_amount 				= $objorder->total_amount - $objorder->agent_commission;
			$objdepositpayment_trans	= new AgentDeposit();
    		$objdepositpayment_trans->agent_id 	 		= $objorder->agent_id;
    		$objdepositpayment_trans->operator_id		= $objorder->operator_id;
    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
    		$today 										= $this->today;
    		$objdepositpayment_trans->pay_date			= $today;
    		$objdepositpayment_trans->payment 			= 0;
    		$agentdeposit 				= AgentDeposit::whereagent_id($objorder->agent_id)->whereoperator_id($objorder->operator_id)->orderBy('id','desc')->first();
    		if($agentdeposit){
    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
    			$objdepositpayment_trans->balance 		= $agentdeposit->balance - $total_amount;
    		}else{
    			$objdepositpayment_trans->deposit 		= 0;
    			$objdepositpayment_trans->balance 		= 0 - $total_amount;
    		}  		
    		$objdepositpayment_trans->debit 			= 0;
    		$objdepositpayment_trans->save();

    		$objorder->cash_credit = 1;
    		$objorder->booking=0;
    		$objorder->update();

    		$response['status']=1;
    		$response['message']='Successfully payment for credit order.';
    	}else{
    		$response['status']=0;
    		$response['message']='There is no booking order with this order no .';	 
    	}
    	return Response::json($response);
    }

    public function postCancelCreditSaleTicket(){
    	$saleitem_id_list=Input::get('saleitem_id_list');
    	$json_ticket_ids=explode(',', $saleitem_id_list);
    	// return Response::json($json_ticket_ids);
    	if(!$json_ticket_ids){
    		$response['status']=0;
    		$response['message']='Parameter format is wrong.';
    		return Response::json($response);
    	}
    	foreach ($json_ticket_ids as $saleitem_id) {
    		$objsaleitem=SaleItem::whereid($saleitem_id)->delete();
    	}
    	$response['status']=1;
		$response['message']='Successfully delete sale ticket for credit order.';
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
    	$objagents=null;
    	if($agent_ids){
    		$objagents=Agent::wherein('id', $agent_ids)->get();
    	}
    	if($objagents){
    		foreach ($objagents as $agent) {
    			$temp['id']=$agent->id;
    			$temp['name']=$agent->name;
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

	/*public function getTripReportByDateRanges(){
		
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date');
		$end_date  				=Input::get('end_date');
		$departure_time  		=Input::get('departure_time');
		
		$objbusoccuranceids=array();
		// operator only
		if($operator_id && !$agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , departure_time
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order)
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('start_date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('start_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order), agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('start_date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, (order), agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','=',Input::get('start_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('start_date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), time
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('start_date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->wheredeparture_time($departure_time)
										->get();
				
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}

			
		}
		// operator from, to ,startdate,  (order), time, agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('start_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->wheredeparture_time($departure_time)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate,  (order), agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('start_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate(order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('start_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('start_date'))
																->where('orderdate','<=',Input::get('end_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator , agent_id
		elseif($operator_id && $agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)
										->get();
					
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id, from, to , $departtime 
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id, from, to ,date,edate $departtime 
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('start_date'))
																->where('orderdate','<=',Input::get('end_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		else{
			$objbusoccuranceids=array();
		}

		if($agent_id){
			if($objbusoccuranceids){
					$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
					$i=0;
					foreach ($objbusoccuranceids as $row) {
						$objbusoccuranceids[$i]=$row;
						if(count($row->saleitems)>0){
							$j=0;
							$matchitems=array();
							foreach ($row->saleitems as $srow) {
								$same=0;
								foreach ($orderids as $orderid) {
									if($srow->order_id==$orderid){
										$same=1;
									}
								}
								if($same==1){
									$matchitems[]=$srow->toarray();
								}
							}
							// return Response::json($matchitems);
							$objbusoccuranceids[$i]['agentsaleitems']=$matchitems;
						}
						$i++;
					}
				}
		}
		// return Response::json($objbusoccuranceids);
		$response=array();
		$samekeys=array();

		if($objbusoccuranceids){
			$order_ids_groupby=array();
			$order_dates=array();
			$i=0;
			if(!$agent_id){
				foreach ($objbusoccuranceids as $row) {

					// $busoccuranceid = $rows->id;
					// $sale_order = SaleOrder::with('saleitems')->whereHas('saleitems', function($query){
					// 	$query->wherebusoccurance_id($busoccuranceid);
					// })->where('orderdate','>=', Input::get('start_date'))
					// 		->where('orderdate','<=',Input::get('end_date'))->get();
					// return Response::json($sale_order);

					$from=City::whereid($row->from)->pluck('name');
					$to=City::whereid($row->to)->pluck('name');
					$order_date='-';
					$total_seat=0;
					$purchased_total_seat=0;
					$total_amout=0;
					$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
					$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
					$order_date=SaleOrder::find($row->saleitems[0]->order_id)->pluck('orderdate');
					
					$purchased_total_seat +=count($row->saleitems);
					$price=$row->saleitems[0]->price;
					$total_amout +=$purchased_total_seat * $price;

					$odid=$row->saleitems[0]->order_id;
					$temp['from']=$from;
					$temp['to']=$to;
					$temp['order_date']=SaleOrder::whereid($odid)->pluck('orderdate');
					$order_date_groupby[]=$temp['order_date'];
					$temp['total_seat']=$total_seats;
					$temp['purchased_total_seat']=$purchased_total_seat;
					$temp['total_amout']=$total_amout;
					if($i==0){
						$response[]=$temp;
					}else{
						$samekey=0;
						$same=0;
						foreach ($response as $key => $resrow) {
							if($resrow['order_date']== $temp['order_date']){
								$same=1;
								$samekey=$key;
							}
						}
						if($same>0){
							$response[$samekey]['total_seat'] +=$total_seats;
							$response[$samekey]['purchased_total_seat'] +=$purchased_total_seat;
							$response[$samekey]['total_amout'] +=$total_amout;
						}else{
							$response[]=$temp;
						}
						
					}
					$i++;
				}
			}else{
				try {
					foreach ($objbusoccuranceids as $row) {
						$from=City::whereid($row->from)->pluck('name');
						$to=City::whereid($row->to)->pluck('name');
						$order_date='-';
						$total_seat=0;
						$purchased_total_seat=0;
						$total_amout=0;
						$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
						$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
						$order_date=SaleOrder::find($row->agentsaleitems[0]['order_id'])->pluck('orderdate');
						
						$purchased_total_seat +=count($row->agentsaleitems);
						$price=$row->agentsaleitems[0]['price'];
						$total_amout +=$purchased_total_seat * $price;

						$odid=$row->agentsaleitems[0]['order_id'];
						$temp['from']=$from;
						$temp['to']=$to;
						$temp['order_date']=SaleOrder::whereid($odid)->pluck('orderdate');
						$order_date_groupby[]=$temp['order_date'];
						$temp['total_seat']=$total_seats;
						$temp['purchased_total_seat']=$purchased_total_seat;
						$temp['total_amout']=$total_amout;
						if($i==0){
							$response[]=$temp;
						}else{
							$samekey=0;
							$same=0;
							foreach ($response as $key => $resrow) {
								if($resrow['order_date']== $temp['order_date']){
									$same=1;
									$samekey=$key;
								}
							}
							if($same>0){
								$response[$samekey]['total_seat'] +=$total_seats;
								$response[$samekey]['purchased_total_seat'] +=$purchased_total_seat;
								$response[$samekey]['total_amout'] +=$total_amout;
							}else{
								$response[]=$temp;
							}
							
						}
						$i++;
					}
					
				} catch (Exception $e) {
					$response=array();
				}
				
			}
			
		}
		return Response::json($response);
	}*/

	public function getTripReportByDateRanges()
	{
		$report_info 			=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		// $trips  				=Input::get('trips'); //for agent report or not
		if($agent_id=="All")
			$agent_id='';
		$from  					=Input::get('from');
		$to  					=Input::get('to');
		$start_date  			=Input::get('start_date') ? Input::get('start_date') : $this->getDate();
		$end_date  				=Input::get('end_date') ? Input::get('end_date') : $this->getDate();
		$start_date				=date('Y-m-d', strtotime($start_date));
		$end_date				=date('Y-m-d', strtotime($end_date));
		$departure_time  		=Input::get('departure_time');
		$departure_time			=str_replace('-', ' ', $departure_time);

	    $operator_id 	=$operator_id ? $operator_id : $this->myGlob->operator_id;

	    if($from=='all')
	    	$from=0;

		$trip_ids=array();
		if($from && $to)
		{
			if($departure_time){
				$trip_ids=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->wheretime($departure_time)->lists('id');
			}else{
				$trip_ids=Trip::whereoperator_id($operator_id)
									->wherefrom($from)
									->whereto($to)
									->lists('id');
			}
		}else{
			if($departure_time){
				$trip_ids=Trip::whereoperator_id($operator_id)
									->wheretime($departure_time)->lists('id');
			}else{
				$trip_ids=Trip::whereoperator_id($operator_id)
									->lists('id');
			}
		}
    	
    	$sale_item=array();
    	$order_ids=array();

    	if($agent_id){
	    	$order_ids = SaleOrder::where('orderdate','>=',$start_date)
	    					->where('orderdate','<=',$end_date)
	    					->whereagent_id($agent_id)
	    					->where('operator_id','=',$operator_id)->lists('id');
    	}else{
    		$order_ids = SaleOrder::where('orderdate','>=',$start_date)
	    					->where('orderdate','<=',$end_date)
	    					->where('operator_id','=',$operator_id)->lists('id');
    	}
    	if($order_ids)
    		$arrtrip_id=SaleItem::wherein('order_id',$order_ids)->lists('trip_id');

    	$trip_ids=array_intersect($trip_ids, $trip_ids);

    	if($trip_ids)
			if($agent_id){
				$sale_item = SaleItem::wherein('trip_id', $trip_ids)
								->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
								->where('departure_date','>=',$start_date)
								->where('departure_date','<=',$end_date)
								->whereagent_id($agent_id)
								->groupBy('order_id')->orderBy('departure_date','asc')->get();	
			}else{
				$sale_item = SaleItem::wherein('trip_id', $trip_ids)
								->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
								->where('departure_date','>=',$start_date)
								->where('departure_date','<=',$end_date)
								->groupBy('order_id')->orderBy('departure_date','asc')->get();
			}

    	// return Response::json($sale_item);
			
		$lists = array();
		foreach ($sale_item as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$trip = Trip::whereid($rows->trip_id)->first();
			$order_date=SaleOrder::whereid($rows->order_id)->pluck('orderdate');
			$list['order_date'] = $order_date;
			// dd($rows->agent_id);
			$agent_name=Agent::whereid($rows->agent_id)->pluck('name');
			$list['agent_id']=$rows->agent_id ? $rows->agent_id : 0;
			$list['agent_name']=$agent_name ? $agent_name : "-";
			
			if($trip){
				$list['id'] = $rows->trip_id;
				$list['bus_id'] = $rows->busoccurance_id;
				$list['departure_date'] = $rows->departure_date;
				$list['from_id'] = $trip->from;
				$list['to_id'] = $trip->to;
				$list['from_to'] = City::whereid($trip->from)->pluck('name').'-'.City::whereid($trip->to)->pluck('name');
				$list['time'] = $trip->time;
				$list['class_id'] = $trip->class_id;
				$list['class_name'] = Classes::whereid($trip->class_id)->pluck('name');
				if(SaleOrder::whereid($rows->order_id)->pluck('nationality') == 'local'){
					$local_person += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->price * $rows->sold_seat) - ($rows->price * $rows->free_ticket) : $rows->price * $rows->sold_seat ;
				}else{
					$foreign_price += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->foreign_price * $rows->sold_seat) - ($rows->foreign_price * $rows->free_ticket) : $rows->foreign_price * $rows->sold_seat ;
				}
				$list['local_person'] = $local_person;
				$list['foreign_person'] = $foreign_price;
				$list['local_price'] = $rows->price;
				$list['foreign_price'] = $rows->foreign_price;
				$list['sold_seat'] = $rows->sold_seat;
				$list['free_ticket'] = $rows->free_ticket;
				$list['total_amount'] = $total_amount;
				$lists[] = $list;
			}
		}
		//Grouping from Lists
		$stack = array();
		foreach ($lists as $rows) {
			$check = $this->ifExist($rows, $stack);
			if($check != -1){
				$stack[$check]['local_person'] += $rows['local_person'];
				$stack[$check]['foreign_person'] += $rows['foreign_person'];
				$stack[$check]['sold_seat'] += $rows['sold_seat'];
				$stack[$check]['free_ticket'] += $rows['free_ticket'];
				$stack[$check]['total_amount'] += $rows['total_amount'];
			}else{
				array_push($stack, $rows);
			}
		}

		$search=array();
    	$cities=array();
    	$cities=$this->getCitiesByoperatorId($operator_id);
    	
		$search['cities']=$cities;
		
		$times=array();
		$times=$this->getTime($operator_id, $from, $to);
		/*$search['times']=$times;
		$search['operator_id']=$operator_id;
		$search['trips']=$trips;
		$search['from']=$from;
		$search['to']=$to;
		$search['time']=$departure_time;
		$search['start_date']=$start_date;
		$search['end_date']=$end_date;
		$search['agent_id']=$agent_id;

		$agent=Agent::whereoperator_id($operator_id)->get();
		$search['agent']=$agent;*/

		$response=$this->msort($stack,array("order_date","departure_date","from_to"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
		return Response::json($response);
		// return View::make('busreport.operatortripticketsolddaterange', array('response'=>$response, 'search'=>$search));
	}


    /*public function getTripsReportByDaily(){
		$response 				=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$from  					=Input::get('from_city');
		$to  					=Input::get('to_city');
		$start_date  			=Input::get('date');
		$end_date  					=Input::get('end_date');
		$departure_time  		=Input::get('time');
		$objbusoccuranceids=array();
		// operator only
		if($operator_id && !$agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , departure_time
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order)
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order), agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate,  agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','=', Input::get('date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), time
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), time
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))
																->whereagent_id(Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), time
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))
																->whereagent_id(Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, time
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate (order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate,agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator , agent_id
		elseif($operator_id && $agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id, from, to , $departtime 
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		else{
			$objbusoccuranceids=array();
		}

		if($agent_id){
			if($objbusoccuranceids){
					$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
					$i=0;
					foreach ($objbusoccuranceids as $row) {
						$objbusoccuranceids[$i]=$row;
						if(count($row->saleitems)>0){
							$j=0;
							$matchitems=array();
							foreach ($row->saleitems as $srow) {
								$same=0;
								foreach ($orderids as $orderid) {
									if($srow->order_id==$orderid){
										$same=1;
									}
								}
								if($same==1){
									$matchitems[]=$srow->toarray();
								}
							}
							// return Response::json($matchitems);
							$objbusoccuranceids[$i]['agentsaleitems']=$matchitems;
						}
						$i++;
					}
				}
		}


		// return Response::json($objbusoccuranceids);
		$response=array();
		$samekeys=array();

		if($objbusoccuranceids){
			$order_ids_groupby=array();
			$order_dates=array();
			$i=0;
			if(!$agent_id){
				foreach ($objbusoccuranceids as $row) {
					
					$order_date='-';
					$total_seat=0;
					$purchased_total_seat=0;
					$total_amout=0;
					$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
					$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
					$order_date=SaleOrder::find($row->saleitems[0]->order_id)->pluck('orderdate');
					
					$purchased_total_seat +=count($row->saleitems);
					$price=$row->saleitems[0]->price;
					$total_amout +=$purchased_total_seat * $price;

					$odid=$row->saleitems[0]->order_id;
					
					
					$temp['purchased_total_seat']=$purchased_total_seat;
					$temp['total_amout']=$total_amout;
					$temp['bus_id']=$row->id;
					$temp['bus_no']=$row->bus_no;
					$from=City::whereid($row->from)->pluck('name');
					$to=City::whereid($row->to)->pluck('name');
					$temp['from']=$from;
					$temp['to']=$to;
					$temp['departure_date']=$row->departure_date;
					$temp['time']=$row->departure_time;
					$temp['total_seat']=$total_seats;

					$response[]=$temp;
				}
			}else{
				foreach ($objbusoccuranceids as $row) {
					
					$order_date='-';
					$total_seat=0;
					$purchased_total_seat=0;
					$total_amout=0;
					$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
					$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
					$order_date=SaleOrder::find($row->agentsaleitems[0]['order_id'])->pluck('orderdate');
					
					$purchased_total_seat +=count($row->agentsaleitems);
					$price=$row->agentsaleitems[0]['price'];
					$total_amout +=$purchased_total_seat * $price;

					$odid=$row->agentsaleitems[0]['order_id'];
					
					
					$temp['purchased_total_seat']=$purchased_total_seat;
					$temp['total_amout']=$total_amout;
					$temp['bus_id']=$row->id;
					$temp['bus_no']=$row->bus_no;
					$from=City::whereid($row->from)->pluck('name');
					$to=City::whereid($row->to)->pluck('name');
					$temp['from']=$from;
					$temp['to']=$to;
					$temp['departure_date']=$row->departure_date;
					$temp['time']=$row->departure_time;
					$temp['total_seat']=$total_seats;

					$response[]=$temp;
				}
			}
		}
		return Response::json($response);
	}*/

	public function getTripsReportByDaily()
	{
		$date=Input::get('date');
		$bus_id=Input::get('bus_id');
		$order_date=explode(',', $date);
		$from=Input::get('from_city');
		$to=Input::get('to_city');
		$agent_id=Input::get('agent_id');
		$start_date="";
		$end_date="";
		if($bus_id){
			$date=$order_date[0];
			$start_date=$order_date[0];
			$end_date=$order_date[0];
		}else{
			$start_date=$order_date[0];
			if(count($order_date)==2)
				$end_date=$order_date[1];
			else
				$end_date=$order_date[0];
		}
		
    	$operator_id 	=Input::get('operator_id');
	    	Session::put('search_daily_date',$date);
	    	if($bus_id){
	    		if($agent_id){
	    			$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($bus_id , $agent_id){
    										$query->wherebusoccurance_id($bus_id)->whereagent_id($agent_id);
    									})->where('orderdate','=',$date)
    									->where('operator_id','=',$operator_id)
    									// ->where('booking','=',0)
    									// ->where('name','!=','')
    									->get();
	    		}else{
	    			$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($bus_id){
    											$query->wherebusoccurance_id($bus_id);
    									})->where('orderdate','=',$date)
    									->where('operator_id','=',$operator_id)
    									// ->where('booking','=',0)
    									// ->where('name','!=','')
    									->get();	
	    		}
	    		
			}else{
				if($from && $agent_id){
					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($from, $to, $agent_id){
											$query->wherefrom($from)->whereto($to)->whereagent_id($agent_id);
										})
										->where('orderdate','>=',$start_date)
										->where('orderdate','<=',$end_date)
    									->where('operator_id','=',$operator_id)
    									// ->where('booking','=',0)
    									// ->where('name','!=','')
    									->get();
				}elseif($from && !$agent_id){
					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($from, $to, $agent_id){
											$query->wherefrom($from)->whereto($to);
										})
										->where('orderdate','>=',$start_date)
										->where('orderdate','<=',$end_date)
    									->where('operator_id','=',$operator_id)
    									// ->where('booking','=',0)
    									// ->where('name','!=','')
    									->get();
				}elseif(!$from && $agent_id){
					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query) use ($from, $to, $agent_id){
											$query->whereagent_id($agent_id);
										})
										->where('orderdate','>=',$start_date)
										->where('orderdate','<=',$end_date)
    									->where('operator_id','=',$operator_id)
    									// ->where('booking','=',0)
    									// ->where('name','!=','')
    									->get();
				}else{
					$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query){
										})
										->where('orderdate','>=',$start_date)
										->where('orderdate','<=',$end_date)
    									->where('operator_id','=',$operator_id)
    									// ->where('booking','=',0)
    									// ->where('name','!=','')
    									->get();	
				}
				
			}
	    	
			// return Response::json($sale_order);
	    	$lists = array();
	    	$l=1;
	    	$frist_trip="";
	    	$last_trip="";
	    	foreach ($sale_order as $rows) {
	    		
	    		$seats_no = "";
	    		$from_to  = null;
	    		$time = null;
	    		$price = 0;
	    		$foreign_price = 0;
	    		$class_id = null;
	    		$agent_commission = null;
	    		$commission = 0;
	    		$local_person = 0;
	    		$foreign_person = 0;
	    		$free_ticket = 0;
	    		$total_amount = 0;

	    		
	    		foreach ($rows->saleitems as $seat_row) {
	    			$check = $this->ifExistTicket($seat_row, $lists);
	    			// Already exist ticket no.
	    			if($check != -1){
	    				$seats_no = $lists[$check]['seat_no'] .", ".$seat_row->seat_no;
	    				$free_ticket = $lists[$check]['free_ticket'] + $seat_row->free_ticket;
	    				if($rows->nationality == 'local'){
							$local_person = $lists[$check]['local_person'] + 1;
							$total_amount = $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission);
						}else{
							$foreign_person += $lists[$check]['foreign_person'] + 1;
							$total_amount +=  $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission);
						}
						$lists[$check]['seat_no'] = $seats_no;
						$lists[$check]['local_person'] = $local_person;
						$lists[$check]['foreign_person'] = $foreign_price;
						$lists[$check]['free_ticket'] = $free_ticket;
						$lists[$check]['sold_seat'] += 1;
			    		$lists[$check]['total_amount'] = $total_amount;
	    			}else{
	    				$list['vr_no'] = $rows->id;
	    				$list['ticket_no'] = $seat_row->ticket_no;
			    		$list['order_date'] = $rows->orderdate;
			    		$list['departure_date'] = $rows->departure_date;
		    			$seats_no = $seat_row->seat_no.", ";
		    			$from_to   = City::whereid($seat_row->from)->pluck('name').'-'.City::whereid($seat_row->to)->pluck('name');
		    			$time = Trip::whereid($seat_row->trip_id)->pluck('time');
		    			$price = $seat_row->price;
		    			$free_ticket = $seat_row->free_ticket;
		    			$foreign_price = $seat_row->foreign_price;
		    			$class_id = Trip::whereid($seat_row->trip_id)->pluck('class_id');
		    			$agent_commission = AgentCommission::wheretrip_id($seat_row->trip_id)->whereagent_id($rows->agent_id)->first();
		    			if($agent_commission){
		    				$commission = $agent_commission->commission;
			    		}else{
			    			$commission = Trip::whereid($seat_row->trip_id)->pluck('commission');
			    		}

			    		if($rows->nationality == 'local'){
							$local_person = 1;
							$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission;
						}else{
							$foreign_person = 1;
							$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission;
						}

						$list['from_to'] = $from_to;
						//for show header info 
						if($l==1)
							$frist_trip=$from_to;

						if($l==count($sale_order))
							$last_trip=$from_to;

			    		$list['time'] = $time;
			    		$list['classes'] = Classes::whereid($class_id)->pluck('name');
			    		//for group trip and class
			    			$list['from_to_class']=$from_to.'('.$list['classes'].')';
			    		$list['agent_name'] = Agent::whereid($rows->agent_id)->pluck('name');
			    		$list['buyer_name'] = $seat_row->name;
			    		$list['commission'] = $commission;
			    		$list['seat_no'] = substr($seats_no, 0, -2);
			    		$list['sold_seat'] = 1;
			    		$list['local_person'] = $local_person;
						$list['foreign_person'] = $foreign_price;
			    		$list['price'] = $price;
			    		$list['foreign_price'] = $foreign_price;
			    		$list['free_ticket'] = $free_ticket;
			    		$list['total_amount'] = $total_amount;
			    		$lists[] = $list;
	    			}
	    			
	    		}		
	    		$l++;
	    	}

	    	// SORTING AND GROUP BY TRIP AND BUSCLASS
		    	// group
		    	$tripandclassgroup = array();
				foreach ($lists AS $arr) {
				  $tripandclassgroup[$arr['from_to_class']][] = $arr;
				}
		    	// sorting
				ksort($tripandclassgroup);

	    	// return Response::json($tripandclassgroup);
			/*$search=array();
			$backurl=URL::previous();
			$search['back_url']=$backurl;
			$search['first_trip']=$frist_trip;
			$search['last_trip']=$last_trip;
			$search['start_date']=$start_date;
			$search['end_date']=$end_date;*/
			return Response::json($tripandclassgroup);
	    	// return View::make('busreport.operatortripticketsolddaily', array('response'=>$tripandclassgroup,'bus_id'=>$bus_id,'search'=>$search));	
	}	

    public function getSeatReportByTrip(){

		$response 		=array();
		$operator_id 	=Input::get('operator_id');
    	$agent_id 		=Input::get('agent_id');
    	$from 			=Input::get('from_city');
    	$to 			=Input::get('to_city');
    	$start_date 	=Input::get('date');
    	$end_date 	    =Input::get('end_date');
    	$departure_time	=Input::get('time');
    	$bus_no			=Input::get('bus_no');
    	$bus_id			=Input::get('bus_id');
    	$invoice_no		=Input::get('invoice_no');
		
		$objbusoccuranceids=array();
		// operator only
		if($operator_id && !$agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}

		// operator from, to , departure_time
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , date, departure_time
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))
																->lists('id');
										$query->whereoperator_id(Input::get('operator_id'))
										->wherein('order_id', $orderids)
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order)
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator, from, to , startdate, enddate (order), time
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=', Input::get('end_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)
										->wherefrom($from)
										->whereto($to)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order), agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate(order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','=', Input::get('date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator , agent_id
		elseif($operator_id && $agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id, from, to , $departtime 
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		else{
			$objbusoccuranceids=array();
		}



		// operator from, to , departure_time
		if($bus_id){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query/*->whereoperator_id(Input::get('operator_id'))*/
										->orderBy('id','desc');
										})
										->whereid($bus_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}


		if($invoice_no){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids[]=Input::get('invoice_no');
											$query->wherein('order_id',$orderids
											);
										})
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}

		if($agent_id){
			if($objbusoccuranceids){
					$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
					$i=0;
					foreach ($objbusoccuranceids as $row) {
						$objbusoccuranceids[$i]=$row;
						if(count($row->saleitems)>0){
							$j=0;
							$matchitems=array();
							foreach ($row->saleitems as $srow) {
								$same=0;
								foreach ($orderids as $orderid) {
									if($srow->order_id==$orderid){
										$same=1;
									}
								}
								if($same==1){
									$matchitems[]=$srow->toarray();
								}
							}
							// return Response::json($matchitems);
							$objbusoccuranceids[$i]['agentsaleitems']=$matchitems;
						}
						$i++;
					}
				}
		}
		// return Response::json($objbusoccuranceids);
		$response=array();
		$samekeys=array();

		if($objbusoccuranceids){
			$order_ids_groupby=array();
			$order_dates=array();
			$i=0;
			if(!$agent_id){
				foreach ($objbusoccuranceids as $row) {
					$from=City::whereid($row->from)->pluck('name');
					$to=City::whereid($row->to)->pluck('name');
					$order_date='-';
					$total_seat=0;
					$purchased_total_seat=0;
					$total_amout=0;
					$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
					$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
					$order_date=SaleOrder::find($row->saleitems[0]->order_id)->pluck('orderdate');
					
					$purchased_total_seat +=count($row->saleitems);
					$price=$row->saleitems[0]->price;
					$total_amout +=$purchased_total_seat * $price;

					$odid=$row->saleitems[0]->order_id;
					$temp['from']=$from;
					$temp['to']=$to;
					foreach ($row->saleitems as $seat_list) {
						$temp['seat_no']=$seat_list->seat_no;
						$temp['price']=$seat_list->price;
						$temp['customer_name']=$seat_list->name;
						$agent_id=SaleOrder::whereid($seat_list->order_id)->pluck('agent_id');
						$agent_name=Agent::whereid($agent_id)->pluck('name');
						$agent_name=$agent_name ? $agent_name : '-';
						$temp['agent_name']=$agent_name;
						$temp['order_id']=$seat_list->order_id;
						$temp['ticket_no']=$seat_list->ticket_no;
						$temp['free_ticket']=$seat_list->free_ticket;
						$agent_commission = AgentCommission::wheretrip_id($seat_list->trip_id)->whereagent_id($seat_list->agent_id)->pluck('commission');
						$temp['commission'] = $agent_commission ? $agent_commission : BusOccurance::whereid($seat_list->busoccurance_id)->pluck('commission');
						$response[]=$temp;
					}
				}
			}else{
			//	try {
					foreach ($objbusoccuranceids as $row) {
						$from=City::whereid($row->from)->pluck('name');
						$to=City::whereid($row->to)->pluck('name');
						$order_date='-';
						$total_seat=0;
						$purchased_total_seat=0;
						$total_amout=0;
						$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
						$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
						$order_date=SaleOrder::find($row->agentsaleitems[0]['order_id'])->pluck('orderdate');
						$purchased_total_seat +=count($row->agentsaleitems);
						$price=$row->agentsaleitems[0]['price'];
						$total_amout +=$purchased_total_seat * $price;

						$odid=$row->agentsaleitems[0]['order_id'];
						$temp['from']=$from;
						$temp['to']=$to;
						foreach ($row->agentsaleitems as $seat_list) {
							$temp['seat_no']=$seat_list['seat_no'];
							$temp['price']=$seat_list['price'];
							$temp['customer_name']=$seat_list['name'];
							$agent_id=SaleOrder::whereid($seat_list['order_id'])->pluck('agent_id');
							$agent_name=Agent::whereid($agent_id)->pluck('name');
							$agent_name=$agent_name ? $agent_name : '-';
							$temp['agent_name']=$agent_name;
							$temp['order_id']=$seat_list['order_id'];
							$temp['ticket_no']=$seat_list['ticket_no'];
							$temp['free_ticket']=$seat_list->free_ticket;
							$agent_commission = AgentCommission::wheretrip_id($seat_list->trip_id)->whereagent_id($seat_list->agent_id)->pluck('commission');
							$temp['commission'] = $agent_commission ? $agent_commission : BusOccurance::whereid($seat_list->busoccurance_id)->pluck('commission');
							$response[]=$temp;
						}
					}
			//	} catch (Exception $e) {
			//		$response=array();
			//	}
				
			}
		}
		return Response::json($response);
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
    			$from_city 	=City::whereid($from)->pluck('name');
    			$to_city 	=City::whereid($to)->pluck('name');
    			foreach ($objbusoccuranceids as $busid) {
    				$objbus=BusOccurance::whereid($busid)->first();
    				$tmp_seatplan['bus_id']	=$busid;
    				$tmp_seatplan['from']	=$from_city;
    				$tmp_seatplan['to']		=$to_city;
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
					    		$customer=null;
					    		if($checkbuy){
					    			$tmp_seatlist['status']=2;
					    			$customer['id']=000;
					    			$customer['name']		=$checkbuy->name 	!= '' ? $checkbuy->name : '-';
					    			$customer['nrc']		=$checkbuy->nrc_no 	!= '' ? $checkbuy->nrc_no : '-';
					    			$customer['phone']		=$checkbuy->phone 	!= '' ? $checkbuy->phone : SaleOrder::whereid($checkbuy->order_id)->pluck('phone');
					    			$customer['seller']		=Agent::whereid(SaleOrder::whereid($checkbuy->order_id)->pluck('agent_id'))->pluck('name');
					    		}
					    		$tmp_seatlist['customer']	=$customer;

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

    	if($from_date){
    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
    									->wherefrom($from)
    									->whereto($to)
    									->where('departure_date','=',$from_date)
    									// ->where('departure_date','<=',$to_date)
    									->wheredeparture_time($departure_time)
    									->lists('id');	
    	}else{
    		$busoccurance_ids    =BusOccurance::whereoperator_id($operator_id)
    									->wherefrom($from)
    									->whereto($to)
    									// ->where('departure_date','=',$from_date)
    									->wheredeparture_time($departure_time)
    									->limit(12)
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

    public function getSaleReportbyAgentGrouptd(){
    	$agentgroup_id=$branch_agent_id	= Input::get('agent_id');
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
    	/*if($agentgroup_id){
    		$objagentbranch =Agent::whereagentgroup_id($agentgroup_id)->get();
    		if($objagentbranch){
    			foreach ($objagentbranch as $agent) {*/
    				if($to_date){
    					$objsaleorder=SaleOrder::whereagent_id($branch_agent_id)->where('orderdate', '>=',$from_date)->where('orderdate', '<=',$to_date)->get();
    				}else{
    					$objsaleorder=SaleOrder::whereagent_id($branch_agent_id)->where('orderdate', '=',$from_date)->get();
    					// return Response::json($objsaleorder);
    				}
    				if($objsaleorder){
    					$agent =Agent::whereid($branch_agent_id)->first();
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
    			/*}
    		}
    	}
		*/
    	return Response::json($response);

    }

    public function getSaleReportbyAgentGroup(){

		// format for api
		/* {
	        "agent_name": "1876 Call Center",
	        "invoice_no": 86,
	        "purchased_total_seat": 2,
	        "total_seat": 44,
	        "total_amount": 30000,
	        "cash_price": 15000
	    }
        */
		$response 				=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$from  					=Input::get('from_city');
		$to  					=Input::get('to_city');
		$start_date  			=Input::get('from_date');
		$end_date  				=Input::get('to_date');
		$departure_time  		=Input::get('time');
		
		$objbusoccuranceids=array();
		// operator only
		if($operator_id && !$agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , departure_time
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order)
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('from_date'))
																->where('orderdate','<=',Input::get('to_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('from_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate, enddate (order), agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('from_date'))
																->where('orderdate','<=',Input::get('to_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator startdate,  agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','=', Input::get('from_date'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('from_date'))
																->where('orderdate','<=',Input::get('to_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate (order)
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('from_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to ,startdate, enddate (order), agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->where('orderdate','>=', Input::get('from_date'))
																->where('orderdate','<=',Input::get('to_date'))->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator , agent_id
		elseif($operator_id && $agent_id && !$from && !$to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , agent_id, from, to , $departtime 
		elseif($operator_id && $agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		else{
			$objbusoccuranceids=array();
		}
		// return Response::json($objbusoccuranceids);
		$response=array();
		$samekeys=array();

		if($objbusoccuranceids){
			$order_ids_groupby=array();
			$order_dates=array();
			$i=0;
			foreach ($objbusoccuranceids as $row) {
				$order_date='-';
				$total_seat=0;
				$purchased_total_seat=0;
				$total_amout=0;
				$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
				$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
				$agent_id=SaleOrder::whereid($row->saleitems[0]->order_id)->pluck('agent_id');
				$agent_name=Agent::whereid($agent_id)->pluck('name');
				$purchased_total_seat +=count($row->saleitems);
				$price=$row->saleitems[0]->price;
				$total_amout +=$purchased_total_seat * $price;
				$odid=$row->saleitems[0]->order_id;
				
				
				$temp['agent_name']=$agent_name ? $agent_name : '-';
				$temp['invoice_no']=$row->saleitems[0]->order_id;
				$temp['purchased_total_seat']=$purchased_total_seat;
				$temp['total_seat']=$total_seats;
				$temp['total_amout']=$total_amout;
				$temp['cash_price']=$price;
				$response[]=$temp;
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
    	if($operator_id && !$to_date && !$from_date){
    		$objsaleorder 		=	SaleOrder::whereoperator_id($operator_id)->get();
    	}else{
    		if($to_date){
	    		$objsaleorder 		=	SaleOrder::whereoperator_id($operator_id)
	    									->where('orderdate','>=',$from_date)
	    									->where('orderdate','<=',$to_date)
	    									->get();
			}else{
				$objsaleorder 		=	SaleOrder::whereoperator_id($operator_id)
	    									->where('orderdate','=',$from_date)
	    									->get();
			}
    	}
    	// return Response::json($objsaleorderids);

		$response=array();    	
		if($objsaleorder){
			foreach ($objsaleorder as $orders) {
				$saleitems=SaleItem::whereorder_id($orders->id)->get();
				$agent_id=SaleOrder::whereid($orders->id)->pluck('agent_id');
				$agentname='-';
				if($agent_id){
					$agentname=Agent::whereid($agent_id)->pluck('name');
				}

				if($saleitems){
					$temp['agent_name']				=$agentname;
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
    	$orderids=array();
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
    	/*
    	 * Delete not confirm order;
    	 */
    		SaleOrder::wherebooking(1)->where('departure_datetime','<',$this->today)->delete();
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
    /*
     * Daily Report
     */
    public function getDailyReportforTrip(){
    	$departure_time		=Input::get('departure_time');
    	$todaydate 			=$this->today;
    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
    	$operator_id  		=Input::get('operator_id');
    	$agent_id  			=Input::get('agent_id');

    	$order_ids = SaleOrder::where('orderdate','=',$date)->where('operator_id','=',$operator_id)->wherebooking(0)->lists('id');
		if($order_ids)
			$sale_item = SaleItem::wherein('order_id', $order_ids)
								->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket')
								->groupBy('order_id')->orderBy('departure_date','asc')->get();
		$lists = array();
		//return Response::json($sale_item);
		foreach ($sale_item as $rows) {
			$local_person = 0;
			$foreign_price = 0;
			$total_amount = 0;
			$trip = Trip::whereid($rows->trip_id)->first();
			if($trip){
				$list['id'] = $rows->trip_id;
				$list['bus_id'] = $rows->busoccurance_id;
				$list['departure_date'] = $rows->departure_date;
				$list['from_id'] = $trip->from;
				$list['to_id'] = $trip->to;
				$list['from_to'] = City::whereid($trip->from)->pluck('name').'-'.City::whereid($trip->to)->pluck('name');
				$list['time'] = $trip->time;
				$list['class_id'] = $trip->class_id;
				$list['class_name'] = Classes::whereid($trip->class_id)->pluck('name');
				if(SaleOrder::whereid($rows->order_id)->pluck('nationality') == 'local'){
					$local_person += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->price * $rows->sold_seat) - ($rows->price * $rows->free_ticket) : $rows->price * $rows->sold_seat ;
				}else{
					$foreign_price += $rows->sold_seat;
					$total_amount += $rows->free_ticket > 0 ? ($rows->foreign_price * $rows->sold_seat) - ($rows->foreign_price * $rows->free_ticket) : $rows->foreign_price * $rows->sold_seat ;
				}
				$list['local_person'] = $local_person;
				$list['foreign_person'] = $foreign_price;
				$list['local_price'] = $rows->price;
				$list['foreign_price'] = $rows->foreign_price;
				$list['sold_seat'] = $rows->sold_seat;
				$list['free_ticket'] = $rows->free_ticket;
				$list['total_amount'] = $total_amount;
				$lists[] = $list;
			}
		}
		//return Response::json($lists);
		//Grouping from Lists
		$stack = array();
		foreach ($lists as $rows) {
			$check = $this->ifExist($rows, $stack);
			if($check != -1){
				$stack[$check]['local_person'] += $rows['local_person'];
				$stack[$check]['foreign_person'] += $rows['foreign_person'];
				$stack[$check]['sold_seat'] += $rows['sold_seat'];
				$stack[$check]['free_ticket'] += $rows['free_ticket'];
				$stack[$check]['total_amount'] += $rows['total_amount'];
			}else{
				array_push($stack, $rows);
			}
		}

    	return Response::json($stack);
    	/*
	    	if($agent_id){
	    		$order_ids=SaleOrder::whereagent_id($agent_id)->lists('id');
	    		$objbusoccurance_ids=SaleItem::wherein('order_id', $order_ids)->lists('id');
	    	}
	    	if($departure_time){
	    		if($operator_id){
	    			$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
										->wheredeparture_date($date)
										->wheredeparture_time($departure_time)
										->lists('id');
	    		}
	    	}else{
	    		if($operator_id){
	    			$objbusoccurance_ids=BusOccurance::whereoperator_id($operator_id)
										->wheredeparture_date($date)
										->lists('id');	
	    		}
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
								$temp['operator'] 	=Operator::whereid($busoccuranceinfo->operator_id)->pluck('name');
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
								$temp['bus_id']		=$busoccuranceinfo->id;
								$temp['classes']	=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
								$temp['from'] 		=$from_city;
								$temp['to']			=$to_city;
								$temp['time'] 		=$busoccuranceinfo->departure_time;
								$temp['sold_seat']	=$soldticketcount;
								$temp['total_seat']	=SeatInfo::whereseat_plan_id($busoccuranceinfo->seat_plan_id)->where('status','<>',0)->count();
								$temp['price']		=$busoccuranceinfo->price;
								$temp['sold_amount']=$temp['price'] * $soldticketcount;
								$temp['operator'] 	=Operator::whereid($busoccuranceinfo->operator_id)->pluck('name');
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
								$temp['bus_id']		=$busoccuranceinfo->id;
								$temp['classes']	=Classes::whereid($busoccuranceinfo->classes)->pluck('name');
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
			}*/

    }

    public function ifExist($key, $arr){
    	if(is_array($arr)){
    		$i = 0;
    		foreach ($arr as $key_row) {
    			if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date']){
    				return $i;
    			}
    			$i++;
    		}
    	}
    	return -1;
    }

    public function getDailyReportforTripByAgent(){
    	$departure_time		=Input::get('departure_time');
    	$todaydate 			=$this->today;
    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
    	$operator_id  		=Input::get('operator_id');
    	$agent_id  			=Input::get('agent_id');
    	
		$objbusoccurance_ids=array();
		if($agent_id){
			if($departure_time){
				$orderids=SaleOrder::whereagent_id($agent_id)->whereorderdate($date)->lists('id');
				$objbusoccurance_ids=SaleItem::wherein('order_id',$orderids)->lists('busoccurance_id');
	    		$objbusoccurance_ids=BusOccurance::wherein('id', $objbusoccurance_ids)
	    							->wheredeparture_date($date)
									->wheredeparture_time($departure_time)
									->lists('id');
	    	}else{
	    		$orderids=SaleOrder::whereagent_id($agent_id)->whereorderdate($date)->lists('id');
				if($orderids){
					$objbusoccurance_ids=SaleItem::wherein('order_id',$orderids)->lists('busoccurance_id');
	    			$objbusoccurance_ids=BusOccurance::wherein('id', $objbusoccurance_ids)
	    							->wheredeparture_timee_date($date)
									->lists('id');
				}
	    	}
		}

		// return Response::json($objbusoccurance_ids);

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
    	$todaydate 			=$this->today;
    	$date				=Input::get('date') ? Input::get('date') : date('Y-m-d',strtotime($todaydate));
    	$operator_id  		=Input::get('operator_id');
    	$bus_id  			=Input::get('bus_id');

    	$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query){
    											$bus_id = Input::get('bus_id');
    											if($bus_id){
    												$query->wherebusoccurance_id($bus_id);
    											}
    									})->where('orderdate','=',$date)
    									  ->where('operator_id','=',$operator_id)
    									  ->where('booking','=',0)
    									  ->where('name','!=','')
    									  ->get();

    	//return Response::json($sale_order);

    	$lists = array();
    	foreach ($sale_order as $rows) {
    		
    		$seats_no = "";
    		$from_to  = null;
    		$time = null;
    		$price = 0;
    		$foreign_price = 0;
    		$class_id = null;
    		$agent_commission = null;
    		$commission = 0;
    		$local_person = 0;
    		$foreign_person = 0;
    		$free_ticket = 0;
    		$total_amount = 0;

    		foreach ($rows->saleitems as $seat_row) {
    			$check = $this->ifExistTicket($seat_row, $lists);
    			// Already exist ticket no.
    			if($check != -1){
    				$seats_no = $lists[$check]['seat_no'] .", ".$seat_row->seat_no;
    				$free_ticket = $lists[$check]['free_ticket'] + $seat_row->free_ticket;
    				if($rows->nationality == 'local'){
						$local_person = $lists[$check]['local_person'] + 1;
						$total_amount = $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission);
					}else{
						$foreign_person += $lists[$check]['foreign_person'] + 1;
						$total_amount +=  $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission);
					}
					$lists[$check]['seat_no'] = $seats_no;
					$lists[$check]['local_person'] = $local_person;
					$lists[$check]['foreign_person'] = $foreign_price;
					$lists[$check]['free_ticket'] = $free_ticket;
					$lists[$check]['sold_seat'] += 1;
		    		$lists[$check]['total_amount'] = $total_amount;
    			}else{
    				$list['vr_no'] = $rows->id;
    				$list['ticket_no'] = $seat_row->ticket_no;
		    		$list['order_date'] = $rows->orderdate;
		    		$list['from_to'] = 
		    		$list['departure_date'] = $rows->departure_date;
	    			$seats_no = $seat_row->seat_no.", ";
	    			$from_to   = City::whereid($seat_row->from)->pluck('name').'-'.City::whereid($seat_row->to)->pluck('name');
	    			$time = Trip::whereid($seat_row->trip_id)->pluck('time');
	    			$price = $seat_row->price;
	    			$free_ticket = $seat_row->free_ticket;
	    			$foreign_price = $seat_row->foreign_price;
	    			$class_id = Trip::whereid($seat_row->trip_id)->pluck('class_id');
	    			$agent_commission = AgentCommission::wheretrip_id($seat_row->trip_id)->whereagent_id($rows->agent_id)->first();
	    			if($agent_commission){
	    				$commission = $agent_commission->commission;
		    		}else{
		    			$commission = Trip::whereid($seat_row->trip_id)->pluck('commission');
		    		}

		    		if($rows->nationality == 'local'){
						$local_person = 1;
						$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission;
					}else{
						$foreign_person = 1;
						$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission;
					}

					$list['from_to'] = $from_to;
		    		$list['time'] = $time;
		    		$list['classes'] = Classes::whereid($class_id)->pluck('name');
		    		$list['agent_name'] = Agent::whereid($rows->agent_id)->pluck('name');
		    		$list['buyer_name'] = $seat_row->name;
		    		$list['commission'] = $commission;
		    		$list['seat_no'] = substr($seats_no, 0, -2);
		    		$list['sold_seat'] = 1;
		    		$list['local_person'] = $local_person;
					$list['foreign_person'] = $foreign_price;
		    		$list['price'] = $price;
		    		$list['foreign_price'] = $foreign_price;
		    		$list['free_ticket'] = $free_ticket;
		    		$list['total_amount'] = $total_amount;
		    		$lists[] = $list;
    			}
    			
    		}		

    		
    	}

    	return Response::json($lists);

	}

	public function ifExistTicket($key, $arr){
		if(is_array($arr)){
    		$i = 0;
    		foreach ($arr as $key_row) {
    			if($key_row['ticket_no']."-".$key_row['vr_no'] == $key['ticket_no']."-".$key['order_id']){
    				return $i;
    			}
    			$i++;
    		}
    	}
    	return -1;
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

    /*public function getDailyAdvancedTrips(){
		$report_info 			=array();
    	$date 					=Input::get('date');
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		
		if($operator_id)
			$orderids 				=SaleOrder::whereorderdate($date)->whereoperator_id($operator_id)->lists('id');

		if($agent_id)
			$orderids 				=SaleOrder::whereorderdate($date)->whereagent_id($agent_id)->lists('id');

		if($orderids){
			$busoccuranceids 	=SaleItem::wherein('order_id', $orderids)->groupBy('busoccurance_id')->lists('busoccurance_id');
		}
		
		if(isset($busoccuranceids)){
			$filterorderidsbydate=SaleItem::wherein('busoccurance_id',$busoccuranceids)->lists('order_id');
		}
		$response=array();


		if(isset($filterorderidsbydate) && count($filterorderidsbydate)>0){
			$busoccurance_ids=SaleItem::wherein('order_id', $filterorderidsbydate)->groupBy('busoccurance_id')->lists('busoccurance_id');
			if($busoccurance_ids){
				$temp['purchased_total_seat']=0;
				$temp['total_amout']=0;
				$i=0;
				foreach ($busoccurance_ids as $occuranceid) {
					$objbusoccurance=BusOccurance::whereid($occuranceid)->where('departure_date','>',$date)->first();
					if($objbusoccurance){
						$seat_plan_id					=$objbusoccurance->seat_plan_id;

						if($i==0){
							$temp['from']					=City::whereid($objbusoccurance->from)->pluck('name');
							$temp['to']						=City::whereid($objbusoccurance->to)->pluck('name');
							$temp['departure_date']			=$objbusoccurance->departure_date;
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
							$temp['from']					=City::whereid($objbusoccurance->from)->pluck('name');
							$temp['to']						=City::whereid($objbusoccurance->to)->pluck('name');
							$temp['total_seat']				=SeatInfo::whereseat_plan_id($seat_plan_id)->where('status','!=',0)->count('id');
							$temp['purchased_total_seat']	=SaleItem::wherebusoccurance_id($occuranceid)->count();
							$temp['total_amout']			=$temp['purchased_total_seat'] * $objbusoccurance->price;
							if($samedate==0){
								$temp['departure_date']			=$objbusoccurance->departure_date;
								$seat_plan_id					=$objbusoccurance->seat_plan_id;
								$response[]						=$temp;
							}else{
								$response[$samekey]['purchased_total_seat'] =$response[$samekey]['purchased_total_seat'] + $temp['purchased_total_seat'];
								$response[$samekey]['total_amout'] 			=$response[$samekey]['total_amout'] + $temp['total_amout'];
								$response[$samekey]['total_seat'] 			=$response[$samekey]['total_seat'] + $temp['total_seat'];
							}

						}
					}
					$i++;
				}
			}
		}else{
			$response=array();
			return Response::json($response);
		}
		return Response::json($response);
    }*/

    public function getDailyAdvancedTrips()
    {
	    	$departure_time		=Input::get('departure_time');
	    	$agent_id			=Input::get('agent_id');
	    	$todaydate 			=date('Y-m-d');
	    	$date				=Input::get('date') ? Input::get('date') : $todaydate;
	    	Session::put('search_daily_date', $date);
	    	$operator_id=Input::get('operator_id');

	    	$sale_item=array();
	    	
	    	$order_ids = SaleOrder::where('orderdate','=',$date)->where('operator_id','=',$operator_id)->wherebooking(0)->lists('id');
	
	    	if($order_ids)
				$sale_item = SaleItem::wherein('order_id', $order_ids)
									->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket')
									->groupBy('order_id')->orderBy('departure_date','asc')->get();
			$lists = array();
			
			foreach ($sale_item as $rows) {
				$local_person = 0;
				$foreign_price = 0;
				$total_amount = 0;
				$trip = Trip::whereid($rows->trip_id)->first();
				if($trip){
					$list['id'] = $rows->trip_id;
					$list['bus_id'] = $rows->busoccurance_id;
					$list['departure_date'] = $rows->departure_date;
					$list['from_id'] = $trip->from;
					$list['to_id'] = $trip->to;
					$list['from_to'] = City::whereid($trip->from)->pluck('name').'-'.City::whereid($trip->to)->pluck('name');
					$list['time'] = $trip->time;
					$list['class_id'] = $trip->class_id;
					$list['class_name'] = Classes::whereid($trip->class_id)->pluck('name');
					if(SaleOrder::whereid($rows->order_id)->pluck('nationality') == 'local'){
						$local_person += $rows->sold_seat;
						$total_amount += $rows->free_ticket > 0 ? ($rows->price * $rows->sold_seat) - ($rows->price * $rows->free_ticket) : $rows->price * $rows->sold_seat ;
					}else{
						$foreign_price += $rows->sold_seat;
						$total_amount += $rows->free_ticket > 0 ? ($rows->foreign_price * $rows->sold_seat) - ($rows->foreign_price * $rows->free_ticket) : $rows->foreign_price * $rows->sold_seat ;
					}
					$list['local_person'] = $local_person;
					$list['foreign_person'] = $foreign_price;
					$list['local_price'] = $rows->price;
					$list['foreign_price'] = $rows->foreign_price;
					$list['sold_seat'] = $rows->sold_seat;
					$list['free_ticket'] = $rows->free_ticket;
					$list['total_amount'] = $total_amount;
					$lists[] = $list;
				}
			}
			//Grouping from Lists
			$stack = array();
			foreach ($lists as $rows) {
				$check = $this->ifExist($rows, $stack);
				if($check != -1){
					$stack[$check]['local_person'] += $rows['local_person'];
					$stack[$check]['foreign_person'] += $rows['foreign_person'];
					$stack[$check]['sold_seat'] += $rows['sold_seat'];
					$stack[$check]['free_ticket'] += $rows['free_ticket'];
					$stack[$check]['total_amount'] += $rows['total_amount'];
				}else{
					array_push($stack, $rows);
				}
			}
			return Response::json($stack);
	    	/*$search['operator_id']=$operator_id !=null ? $operator_id : 0;
			$search['date']=$date;*/
			// return View::make('busreport.daily.index', array('dailyforbus'=>$stack, 'search'=>$search));	
	}

    /*public function getDailyAdvancedByFilterDate(){
    	$report_info 			=array();
    	$order_date 					=Input::get('order_date');
    	$departure_date 				=Input::get('departure_date');
		$operator_id  					=Input::get('operator_id');
		$agent_id  						=Input::get('agent_id');
		
		if($operator_id)
		$orderids 				=SaleOrder::whereorderdate($order_date)->whereoperator_id($operator_id)->lists('id');

		if($agent_id)
		$orderids 				=SaleOrder::whereorderdate($order_date)->whereagent_id($agent_id)->lists('id');
		
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
						$class 							=Classes::whereid($objbusoccurance->classes)->pluck('name');
						$temp['class'] 					=$class !=null ? $class : '-';
						$temp['from']					=City::whereid($objbusoccurance->from)->pluck('name');
						$temp['to']						=City::whereid($objbusoccurance->to)->pluck('name');
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
    }*/

    public function getDailyAdvancedByFilterDate()
	{
    	$operator_id 	=Input::get('operator_id');
    	$date 			=Input::get('order_date') ? Input::get('order_date') : date('Y-m-d');
    	Session::put('search_daily_date',$date);
    	$bus_id  		=Input::get('bus_id');
	
    	$sale_order = SaleOrder::with('saleitems')->whereHas('saleitems',function($query){
											$bus_id = Input::get('bus_id');
											if($bus_id){
												$query->wherebusoccurance_id($bus_id);
											}
									})->where('orderdate','=',$date)
									  ->where('operator_id','=',$operator_id)
									  ->where('booking','=',0)
									  ->where('name','!=','')
									  ->get();

    	$lists = array();
    	foreach ($sale_order as $rows) {
    		
    		$seats_no = "";
    		$from_to  = null;
    		$time = null;
    		$price = 0;
    		$foreign_price = 0;
    		$class_id = null;
    		$agent_commission = null;
    		$commission = 0;
    		$local_person = 0;
    		$foreign_person = 0;
    		$free_ticket = 0;
    		$total_amount = 0;

    		foreach ($rows->saleitems as $seat_row) {
    			$check = $this->ifExistTicket($seat_row, $lists);
    			// Already exist ticket no.
    			if($check != -1){
    				$seats_no = $lists[$check]['seat_no'] .", ".$seat_row->seat_no;
    				$free_ticket = $lists[$check]['free_ticket'] + $seat_row->free_ticket;
    				if($rows->nationality == 'local'){
						$local_person = $lists[$check]['local_person'] + 1;
						$total_amount = $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission);
					}else{
						$foreign_person += $lists[$check]['foreign_person'] + 1;
						$total_amount +=  $lists[$check]['total_amount'] + ($seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission);
					}
					$lists[$check]['seat_no'] = $seats_no;
					$lists[$check]['local_person'] = $local_person;
					$lists[$check]['foreign_person'] = $foreign_price;
					$lists[$check]['free_ticket'] = $free_ticket;
					$lists[$check]['sold_seat'] += 1;
		    		$lists[$check]['total_amount'] = $total_amount;
    			}else{
    				$list['vr_no'] = $rows->id;
    				$list['ticket_no'] = $seat_row->ticket_no;
		    		$list['order_date'] = $rows->orderdate;
		    		$list['from_to'] = 
		    		$list['departure_date'] = $rows->departure_date;
	    			$seats_no = $seat_row->seat_no.", ";
	    			$from_to   = City::whereid($seat_row->from)->pluck('name').'-'.City::whereid($seat_row->to)->pluck('name');
	    			$time = Trip::whereid($seat_row->trip_id)->pluck('time');
	    			$price = $seat_row->price;
	    			$free_ticket = $seat_row->free_ticket;
	    			$foreign_price = $seat_row->foreign_price;
	    			$class_id = Trip::whereid($seat_row->trip_id)->pluck('class_id');
	    			$agent_commission = AgentCommission::wheretrip_id($seat_row->trip_id)->whereagent_id($rows->agent_id)->first();
	    			if($agent_commission){
	    				$commission = $agent_commission->commission;
		    		}else{
		    			$commission = Trip::whereid($seat_row->trip_id)->pluck('commission');
		    		}

		    		if($rows->nationality == 'local'){
						$local_person = 1;
						$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->price - $commission;
					}else{
						$foreign_person = 1;
						$total_amount = $seat_row->free_ticket > 0 ? 0 : $seat_row->foreign_price - $commission;
					}

					$list['from_to'] = $from_to;
		    		$list['time'] = $time;
		    		$list['classes'] = Classes::whereid($class_id)->pluck('name');
		    		//for group trip and class
		    			$list['from_to_class']=$from_to.'('.$list['classes'].')';
		    		$list['agent_name'] = Agent::whereid($rows->agent_id)->pluck('name');
		    		$list['buyer_name'] = $seat_row->name;
		    		$list['commission'] = $commission;
		    		$list['seat_no'] = substr($seats_no, 0, -2);
		    		$list['sold_seat'] = 1;
		    		$list['local_person'] = $local_person;
					$list['foreign_person'] = $foreign_price;
		    		$list['price'] = $price;
		    		$list['foreign_price'] = $foreign_price;
		    		$list['free_ticket'] = $free_ticket;
		    		$list['total_amount'] = $total_amount;
		    		$lists[] = $list;
    			}
    			
    		}		

    		
    	}

    	// SORTING AND GROUP BY TRIP AND BUSCLASS
	    	// group
	    	$tripandclassgroup = array();
			foreach ($lists AS $arr) {
			  $tripandclassgroup[$arr['from_to_class']][] = $arr;
			}
	    	// sorting
			ksort($tripandclassgroup);

    	return Response::json($tripandclassgroup);
    	// return View::make('busreport.daily.detail', array('response'=>$tripandclassgroup,'bus_id'=>$bus_id));	
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
		// format for api
		/* {
	        "id": 96,
	        "trip": "Yangon-Pyin Oo Lwin",
	        "bus_no": "YGN-9898",
	        "class_id": "13",
	        "class": "Normal",
	        "departure_time": "6:00 PM",
	        "sold_seats": 2,
	        "total_seats": 44,
	        "total_amount": 30000
	    }
        */
		$response 				=array();
		$operator_id  			=Input::get('operator_id');
		$agent_id  				=Input::get('agent_id');
		$from 					=Input::get('from');	
    	$to 					=Input::get('to');
		$start_date  			=Input::get('departure_date');
		$end_date  				=Input::get('end_date');
		$departure_time  		=Input::get('time');
		
		$objbusoccuranceids=array();
		// operator only
		if($operator_id && !$agent_id && !$from && !$to && !$start_date &&!$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->whereoperator_id($operator_id)->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator, departure_date
		elseif($operator_id && !$agent_id && !$from && !$to && $start_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wheredeparture_date($start_date)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to , departure_time
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to, departdate, time
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_date($start_date)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}

		// operator from, to, departdate, agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
					$objbusoccuranceids=BusOccurance::with('saleitems')
											->whereHas('saleitems',  function($query)  {
											$query->whereoperator_id(Input::get('operator_id'))
											->orderBy('id','desc');
											})
											->whereoperator_id($operator_id)
											->wherefrom($from)
											->whereto($to)
											->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
			// return Response::json($objbusoccuranceids);
		}

		// operator from, to, departdate, end_date,  agent_id, time
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('departure_date'))
																->where('orderdate','<=',Input::get('end_date'))
																->whereagent_id('agent_id')
																->lists('id');
										$query->wherein('order_id',$orderids)
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->wheredeparture_time($departure_time)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to, departdate,  agent_id, time
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','=', Input::get('departure_date'))
																->whereagent_id('agent_id')
																->lists('id');
										$query->wherein('order_id',$orderids)
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->wheredeparture_time($departure_time)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to, departdate, end_date,  agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && $end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('orderdate','>=', Input::get('departure_date'))
																->where('orderdate','<=',Input::get('end_date'))->lists('id');
										$query->wherein('order_id',$orderids)
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}

		// operator from, to, departdate, agent_id
		elseif($operator_id && $agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::whereoperator_id(Input::get('operator_id'))->whereagent_id(Input::get('agent_id'))->lists('id');
										$query->wherein('order_id',$orderids)
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_date($start_date)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		// operator from, to, departdate
		elseif($operator_id && !$agent_id && $from && $to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_date($start_date)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		
		// operator from, to, departtime
		elseif($operator_id && !$agent_id && $from && $to && !$start_date && !$end_date && $departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
										$query->whereoperator_id(Input::get('operator_id'))
										->orderBy('id','desc');
										})
										->wherefrom($from)
										->whereto($to)
										->wheredeparture_time($departure_time)
										->whereoperator_id($operator_id)
										->get();
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		
		// operator startdate,  agentid
		elseif($operator_id && $agent_id && !$from && !$to && $start_date && !$end_date && !$departure_time){
			try {
				$objbusoccuranceids=BusOccurance::with('saleitems')
										->whereHas('saleitems',  function($query)  {
											$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
											$query->wherein('order_id',$orderids
											);
										})
										->whereoperator_id($operator_id)
										->wheredeparture_date($start_date)
										->get();	
			} catch (Exception $e) {
				$objbusoccuranceids=array();
			}
		}
		
		else{
			$objbusoccuranceids=array();
		}
		// return Response::json($objbusoccuranceids);

		if($agent_id){
			if($objbusoccuranceids){
					$orderids=SaleOrder::where('agent_id','=',Input::get('agent_id'))
																->lists('id');
					$i=0;
					foreach ($objbusoccuranceids as $row) {
						$objbusoccuranceids[$i]=$row;
						if(count($row->saleitems)>0){
							$j=0;
							$matchitems=array();
							foreach ($row->saleitems as $srow) {
								$same=0;
								foreach ($orderids as $orderid) {
									if($srow->order_id==$orderid){
										$same=1;
									}
								}
								if($same==1){
									$matchitems[]=$srow->toarray();
								}
							}
							// return Response::json($matchitems);
							$objbusoccuranceids[$i]['agentsaleitems']=$matchitems;
						}
						$i++;
					}
				}
		}

		// return Response::json($objbusoccuranceids);
		$response=array();
		$samekeys=array();

		if($objbusoccuranceids){
			$order_ids_groupby=array();
			$order_dates=array();
			$i=0;
			if(!$agent_id){
				foreach ($objbusoccuranceids as $row) {
					$order_date='-';
					$total_seat=0;
					$purchased_total_seat=0;
					$total_amout=0;
					$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
					$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
					$order_date=SaleOrder::find($row->saleitems[0]->order_id)->pluck('orderdate');
					
					$purchased_total_seat +=count($row->saleitems);
					$price=$row->saleitems[0]->price;
					$total_amout +=$purchased_total_seat * $price;

					$odid=$row->saleitems[0]->order_id;
					
					
					$temp['purchased_total_seat']=$purchased_total_seat;
					$temp['total_amout']=$total_amout;
					$temp['bus_id']=$row->id;
					$temp['class']=Classes::whereid($row->classes)->pluck('name');
					$temp['bus_no']=$row->bus_no;
					$from=City::whereid($row->from)->pluck('name');
					$to=City::whereid($row->to)->pluck('name');
					$temp['from']=$from;
					$temp['to']=$to;
					$temp['departure_date']=$row->departure_date;
					$temp['time']=$row->departure_time;
					$temp['total_seat']=$total_seats;

					$response[]=$temp;
				}
			}else{
				try {
					foreach ($objbusoccuranceids as $row) {
						$order_date='-';
						$total_seat=0;
						$purchased_total_seat=0;
						$total_amout=0;
						$seatplanid=BusOccurance::whereid($row->id)->pluck('seat_plan_id');
						$total_seats=SeatInfo::whereseat_plan_id($seatplanid)->where('status','!=',0)->count('id');
						$order_date=SaleOrder::find($row->agentsaleitems[0]['order_id'])->pluck('orderdate');
						
						$purchased_total_seat +=count($row->agentsaleitems);
						$price=$row->agentsaleitems[0]['price'];
						$total_amout +=$purchased_total_seat * $price;

						$odid=$row->agentsaleitems[0]['order_id'];
						
						
						$temp['purchased_total_seat']=$purchased_total_seat;
						$temp['total_amout']=$total_amout;
						$temp['bus_id']=$row->id;
						$temp['class']=Classes::whereid($row->classes)->pluck('name');
						$temp['bus_no']=$row->bus_no;
						$from=City::whereid($row->from)->pluck('name');
						$to=City::whereid($row->to)->pluck('name');
						$temp['from']=$from;
						$temp['to']=$to;
						$temp['departure_date']=$row->departure_date;
						$temp['time']=$row->departure_time;
						$temp['total_seat']=$total_seats;

						$response[]=$temp;
					}
				} catch (Exception $e) {
					$response=array();
				}
			
			}
			
		}
		return Response::json($response);
	}
    
    public function getDailyReportbydepartdateandbusid(){
    	$busid 			=Input::get('bus_id');
    	$orderids 		=SaleItem::wherebusoccurance_id($busid)->groupBy('order_id')->lists('order_id');
    	$objbus 	 	=BusOccurance::whereid($busid)->first();
    	$objorderagent=null;
    	$objagentids= $response=array();
    	if($orderids){
    		$objagentids =SaleOrder::wherein('id', $orderids)->lists('agent_id','id');
    	}
    	$i=0;
    	if($objagentids){
    		foreach ($objagentids as $key=>$agentid) {
    			$agent_name				=Agent::whereid($agentid)->pluck('name');	
    			$agent_owner			=Agent::whereid($agentid)->pluck('owner');	
    			if($i==0){
    				$temp['bus_id']			=$busid;
    				$from 					=City::whereid($objbus->from)->pluck('name');
    				$to 					=City::whereid($objbus->to)->pluck('name');
    				$temp['trip']			=$from.'-'.$to;
    				$temp['agent_id']		=$agentid;
    				$temp['agent'] 			=$agent_name != null ? $agent_name : '-';
    				$temp['owner']			=$agent_owner;
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
	    				$from 					=City::whereid($objbus->from)->pluck('name');
	    				$to 					=City::whereid($objbus->to)->pluck('name');
	    				$temp['trip']			=$from.'-'.$to;
	    				$temp['agent_id']		=$agentid;
	    				$temp['agent']			=$agent_name != null ? $agent_name : '-';
	    				$temp['owner']			=$agent_owner;
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
    	$response=array();

    	$objsaleitems=SaleItem::wherebusoccurance_id($bus_id)->whereagent_id($agent_id)->get();
		if($objsaleitems){
			foreach ($objsaleitems as $rows) {
				$objorderinfo 				= SaleOrder::whereid($rows->order_id)->whereagent_id($agent_id)->first();
				$temp['bus_no'] 			=$objbus->bus_no;
				$from 						=City::whereid($objbus->from)->pluck('name');
				$to 						=City::whereid($objbus->to)->pluck('name');
				$temp['trip']				=$from.'-'.$to;
				$temp['class']				=Classes::whereid($objbus->classes)->pluck('name');
				$temp['departure_date'] 	=$objbus->departure_date;
				$temp['departure_time'] 	=$objbus->departure_time;
				$temp['seat_no'] 			=$rows['seat_no'];
				$temp['ticket_no'] 			=$rows['ticket_no'];
				$temp['orderdate']			=$objorderinfo->orderdate;
    			$agent_name					=Agent::whereid($objorderinfo->agent_id)->pluck('name');
    			$temp['agent']				=$agent_name != null ? $agent_name : '-';
    			$temp['customer_name']		=$objorderinfo->name;
    			$temp['operator']			=Operator::whereid($objorderinfo->operator_id)->pluck('name');
    			$temp['price'] 	 			=$objbus->price;
    			$temp['free_ticket'] 		= $rows['free_ticket'];
    			$response[] 	 			=$temp;
			}
		}
    	return Response::json($response);
    }

    public function postCustomerInfoUpdate(){
    	$bus_id			=Input::get('bus_id');
    	$seat_no		=Input::get('seat_no');
    	$customer_name	=Input::get('customer_name');
    	$nrc_no			=Input::get('nrc_no');
    	$phone			=Input::get('phone');

    	if(!$bus_id || !$seat_no || !$customer_name){
    		$response['message']= "Request parameters are required.";
    		return Response::json($response);
    	}

    	$objsaleitem =SaleItem::wherebusoccurance_id($bus_id)->whereseat_no($seat_no)->first();
    	if($objsaleitem){
    		$objsaleitem->name=$customer_name;
    		$objsaleitem->nrc_no=$nrc_no !=null ? $nrc_no : $objsaleitem->nrc_no;
    		$objsaleitem->phone=$phone !=null ? $phone : $objsaleitem->phone;
    		$objsaleitem->update();
    	}
    	$response['message']="Successfully Update Customere Information.";
    	return Response::json($response);
    }

    public function getTripsByOperator($operator_id){
    	$limit = Input::query('limit') ? Input::query('limit') : 8;
		$offset = Input::query('offset') ? Input::query('offset') : 1;
		$offset = ($offset-1) * $limit;
		$objtriplist=BusOccurance::whereoperator_id($operator_id)->with(array('saleitems'))->take($limit)->skip($offset)
					->get(array('id', 'bus_no','classes','from','to','departure_date', 'departure_time', 'price','operator_id'));;
		$i=0;
		if($objtriplist){
			foreach ($objtriplist as $row) {
				$objtriplist[$i]['customer']='-';
				$objtriplist[$i]['phone']='-';
				if(count($row->saleitems)>0){
					$j=0;
					foreach ($row->saleitems as $saleitem) {
						$objorder=SaleOrder::whereid($saleitem['order_id'])->first();
						$orderdate=$objorder->orderdate;
						$agent=Agent::whereid($objorder->agent_id)->pluck('name');
						$row->saleitems[$j]=$saleitem;
						$row->saleitems[$j]['orderdate']=$orderdate;
						$row->saleitems[$j]['agent']=$agent;
						$j++;
					}
					$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
					$objtriplist[$i]['customer']=$objorderinfo->name;
					$objtriplist[$i]['phone']=$objorderinfo->phone;
				}
				$objtriplist[$i]=$row;
				$operator=Operator::whereid($row->operator_id)->pluck('name');
				$from=City::whereid($row->from)->pluck('name');
				$to=City::whereid($row->to)->pluck('name');
				$class=Classes::whereid($row->classes)->pluck('name');
				$objtriplist[$i]['operator']=$operator;
				$objtriplist[$i]['trip']=$from.'-'.$to;
				$objtriplist[$i]['class']=$class;

				
				$i++;
			}
		}
		return Response::json($objtriplist);
    }

    public function postAgentDeposit(){
    	$agent_id 		=Input::get('agent_id');
    	$operator_id 	=Input::get('operator_id');
    	$deposit_date	=$this->today;
    	$deposit     	=Input::get('deposit');
    	$objagentdeposit=new AgentDeposit();
    	try {
    		$check_exiting=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id','desc')->first();
    		$objagentdeposit->agent_id=$agent_id;
    		$objagentdeposit->operator_id=$operator_id;
	    	$objagentdeposit->deposit_date=$deposit_date;
	    	if(!$check_exiting){
	    		$objagentdeposit->payment=0;
	    		$objagentdeposit->deposit=$deposit;
	    		$objagentdeposit->balance=$deposit;
	    	}else{
	    			$objagentdeposit->deposit=$check_exiting->balance;
	    			$objagentdeposit->payment=$deposit;
	    			$balance =$check_exiting->balance;
	    			$objagentdeposit->balance= $deposit + $balance;	
	    	}

	    	
	    	$objagentdeposit->save();
	    	$response['status']=1;
	    	$response['message']="Successfully save one record.";
    		return Response::json($response);
    	} catch (Exception $e) {
    		$response['status']=0;
    		$response['message']=$e->errorInfo[2];
    		return Response::json($response);
    	}
    }

    public function postAgentCredit(){
    	$agent_id 		=Input::get('agent_id');
    	$operator_id 	=Input::get('operator_id');
    	$deposit_date	=$this->today;
    	$old_credit    	=Input::get('old_credit');
    	$objagent=new Agent();
    	try {
    		$objagentdeposit->agent_id=$agent_id;
    		$objagentdeposit->operator_id=$operator_id;
	    	$objagentdeposit->deposit_date=$deposit_date;
	    	$objagentdeposit->deposit=$deposit;
	    	if(!$check_exiting){
	    		$objagentdeposit->balance=$deposit;
	    	}else{
	    		if($check_exiting->balance > 0){
	    			$balance =$check_exiting->balance;
	    			$objagentdeposit->balance= $deposit + $balance;	
	    		}elseif($check_exiting->deposit > 0){
	    			$debit =$check_exiting->debit;
	    			$objagentdeposit->balance= $deposit - $debit;	
	    		}else{
	    			$objagentdeposit->balance=$deposit;
	    		}
	    	}
	    	$objagentdeposit->save();
	    	$response['status']=1;
	    	$response['message']="Successfully save one record.";
    		return Response::json($response);
    	} catch (Exception $e) {
    		$response['status']=0;
    		$response['message']=$e->errorInfo[2];
    		return Response::json($response);
    	}
    }

    public function getAgentDeposit(){
    	$agent_id=Input::get('agent_id');
    	$operator_id=Input::get('operator_id');
    	if($agent_id && $operator_id)
    		$objagentdeposit=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id','desc')->first();
    	elseif($agent_id && !$operator_id)
    		$objagentdeposit=AgentDeposit::whereagent_id($agent_id)->orderBy('id','desc')->first();
    	elseif(!$agent_id && $operator_id){
    		$agent_ids=AgentDeposit::whereoperator_id($operator_id)->lists('agent_id');
    		$objagentdeposit=AgentDeposit::wherein('agent_id',$agent_ids)->orderBy('id','desc')->get();
    		if($objagentdeposit){
    			$i=0;
    			foreach ($objagentdeposit as $row) {
    				$objagentdeposit[$i]=$row;
    				$objagentdeposit[$i]['agent']=Agent::whereid($row->agent_id)->pluck('name');
    				$i++;
    			}
    		}
    		return Response::json($objagentdeposit);
    	}else{
    		$objagentdeposit=AgentDeposit::whereoperator_id($operator_id)->orderBy('id','desc')->first();
    	}

    	$response=array();
    	if($objagentdeposit){
    		$response['agent_id']=$objagentdeposit->agent_id;
    		$response['agent']=Agent::whereid($objagentdeposit->agent_id)->pluck('name');
    		$response['deposit_balance']=$objagentdeposit->balance;
    		return Response::json($response);
    	}
    }

    public function getAgentCreditByDateRange(){
    	$limit=Input::get('limit') ? Input::get('limit') : 12;
    	$offset=Input::get('offset') ? Input::get('offset') : 1;
    	$offset=($offset-1) * $limit;
    	$start_date=Input::get('start_date');
    	$end_date=Input::get('end_date');
    	$agent_id=Input::get('agent_id');
    	$operator_id=Input::get('operator_id');
    	$from=Input::get('from');
    	$to=Input::get('to');
    	$trip=Input::get('trip');
    	if($operator_id && $agent_id && $start_date && $end_date){
    		$response =SaleOrder::where('orderdate','>=',$start_date)
    							->where('orderdate','<=',$end_date)
    							->where('operator_id','=',$operator_id)
    							->where('agent_id','=',$agent_id)
    							->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
    							->orderBy('orderdate','asc')
    							->take($limit)->skip($offset)
								->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}elseif($operator_id && $agent_id && $start_date && !$end_date){
    		$response =SaleOrder::where('orderdate','=',$start_date)
    							->where('operator_id','=',$operator_id)
    							->where('agent_id','=',$agent_id)
    							->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
    							->orderBy('orderdate','asc')
    							->take($limit)->skip($offset)
								->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}
    	elseif($operator_id && $agent_id && !$start_date && !$end_date){
    		$response =SaleOrder::where('operator_id','=',$operator_id)
    							->where('agent_id','=',$agent_id)
    							->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
    							->orderBy('orderdate','asc')
    							->take($limit)->skip($offset)
								->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}elseif($operator_id && !$agent_id && $start_date && $end_date){
    		$response =SaleOrder::where('operator_id','=',$operator_id)
    							->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
    							->orderBy('orderdate','asc')
    							->take($limit)->skip($offset)
								->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}elseif($operator_id && !$agent_id && !$start_date && $end_date){
    		$response =SaleOrder::where('operator_id','=',$operator_id)
    							->wherecash_credit(2)
    							->with(array('saleitems'=> function($query) {
																$query->wherefree_ticket(0);
							 								}))
    							->orderBy('orderdate','asc')
    							->take($limit)->skip($offset)
								->get(array('id', 'orderdate', 'agent_id', 'operator_id'));
    	}else{
    		$response=array();
    	}
    	$filter=array();
    	// for from to filter
    	if($from && $to){
    		if($response){
    			$j=0;
    			$busids=BusOccurance::wherefrom($from)->whereto($to)->lists('id');
    			foreach ($response as $row) {
    				if($busids && count($row->saleitems) >0){
    					foreach ($busids as $busid) {
    						if((int)$busid == (int)$row->saleitems[0]->busoccurance_id){
    							$response[$j]=$row;
    							$temp['id']=$row->id;
    							$temp['orderdate']=$row->orderdate;
    							$temp['agent_id']=$row->agent_id;
    							$temp['operator_id']=$row->operator_id;
    							$trip_id=BusOccurance::whereid($busid)->pluck('trip_id');
    							foreach ($row->saleitems as $arrsaleitem) {
    								$tmpsaleitem['id']=$arrsaleitem->id;
	    							$tmpsaleitem['order_id']=$arrsaleitem->order_id;
	    							$tmpsaleitem['ticket_no']=$arrsaleitem->ticket_no;
	    							$tmpsaleitem['seat_no']=$arrsaleitem->seat_no;
	    							$tmpsaleitem['nrc_no']=$arrsaleitem->nrc_no;
	    							$tmpsaleitem['name']=$arrsaleitem->name;
	    							$tmpsaleitem['phone']=$arrsaleitem->phone;
	    							$tmpsaleitem['busoccurance_id']=$arrsaleitem->busoccurance_id;
	    							$tmpsaleitem['operator']=$arrsaleitem->operator;
	    							$tmpsaleitem['price']= $arrsaleitem->price;
	    							$saleitem[]=$tmpsaleitem;
    							}
    							$temp['saleitems']=$saleitem;
    							$filter[]=$temp;
    						}
    					}
    				$j++;
    				}
    			}
    		}
    	}

    	if($filter){
    		$i=0;
    		$objbus=BusOccurance::wherefrom($from)->whereto($to)->pluck('id');
    		if($objbus){
    			foreach ($filter as $row) {
	    			$arr_response=array();
	    			$response_value['id']=$row['id'];
	    			$response_value['orderdate']=$row['orderdate'];
	    			$response_value['agent_id']=$row['agent_id'];
	    			$response_value['operator_id']=$row['operator_id'];
	    			$trip='-';
	    			$price=0;
	    			$amount=0;
	    			$agent_commission=0;
	    			$to_pay_amount=0;
	    			$tickets=count($row['saleitems']);
	    			$saleitems=array();
	    			if(count($row['saleitems'])>0){
	    				$objbusoccurance=BusOccurance::whereid($row['saleitems'][0]['busoccurance_id'])->first();
	    				if($objbusoccurance){
	    					foreach ($row['saleitems'] as $value) {
	    						$tmpsaleitem['id']=$value['id'];
								$tmpsaleitem['order_id']=$value['order_id'];
								$tmpsaleitem['ticket_no']=$value['ticket_no'];
								$tmpsaleitem['seat_no']=$value['seat_no'];
								$tmpsaleitem['nrc_no']=$value['nrc_no'];
								$tmpsaleitem['name']=$value['name'];
								$tmpsaleitem['phone']=$value['phone'];
								$tmpsaleitem['busoccurance_id']=$value['busoccurance_id'];
								$tmpsaleitem['operator']=$value['operator'];
								$tmpsaleitem['price']=$value['price'];
								$saleitems[]=$tmpsaleitem;
	    					}
	    					
	    					$from=City::whereid($objbusoccurance->from)->pluck('name');
	    					$to=City::whereid($objbusoccurance->to)->pluck('name');
	    					$trip=$from.'-'.$to;
	    					$price= $objbusoccurance->price;
	    					/*$amount= $row->total_amount;
	    					$agent_commission= $row->agent_commission;
	    					$to_pay_amount=$amount-$agent_commission;*/
	    					$amount= $objbusoccurance->price * $tickets;
	    				}
	    				$objorderinfo=SaleOrder::whereid($row['saleitems'][0]['order_id'])->first();
	    				if($objorderinfo){
	    					$response_value['customer']=$objorderinfo->name;
	    					$response_value['phone']=$objorderinfo->phone;	
	    				}
	    				
	    			}else{
	    				$response_value['customer']='-';
	    				$response_value['phone']='-';
	    			}
					$operator=Operator::whereid($row['operator_id'])->pluck('name');
					$trip_id = BusOccurance::whereid($row['saleitems'][0]['busoccurance_id'])->pluck('trip_id');
					$objagent=AgentCommission::whereagent_id($row['agent_id'])->wheretrip_id($trip_id)->first();
	    			$response_value['operator']=$operator;
	    			$response_value['agent']='-';
	    			$response_value['agent_commission']=0;
	    			if($objagent){
    					$response_value['agent']=$objagent->name;
    					$commissiontype=CommissionType::whereid($objagent->commission_id)->pluck('name');
    					$response_value['commission']=$objagent->commission;	
    					if(strtolower($commissiontype)=='fixed'){
    						$response_value['commission_type']='fixed';	
    						$response_value['agent_commission']=$objagent->commission * $tickets ;	
    					}else{
    						$response_value['commission_type']='percentage';	
    						$response_value['agent_commission']=($amount * $objagent->commission) / 100;	
    					}
    				}else{
    					$response_value['commission']= Trip::whereid($trip_id)->pluck('commission');
    					$response_value['commission_type']='trip';	
    					$response_value['agent_commission']= SaleOrder::whereid($row['id'])->pluck('agent_commission');
    				}

	    			$response_value['trip']=$trip;
	    			$response_value['total_ticket']=$tickets;
	    			$response_value['price']=$price;
	    			$response_value['amount']=$amount;
	    			$response_value['grand_total']=($amount - $response_value['agent_commission']);
	    			$response_value['saleitems']=$saleitems;
	    			$arr_response[]=$response_value;
	    			$i++;
	    		}	
    		}
	    	return Response::json($arr_response);
    	}else{
    		if($response){
	    		$i=0;
	    		$objbus=BusOccurance::wherefrom($from)->whereto($to)->pluck('id');
	    		foreach ($response as $row) {
	    			$response[$i]=$row;
	    			$trip='-';
	    			$price=0;
	    			$amount=0;
	    			$tickets=count($row->saleitems);
	    			$saleitems=array();
	    			if(count($row->saleitems)>0){
	    				$objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
	    				if($objbusoccurance){
	    					$from=City::whereid($objbusoccurance->from)->pluck('name');
	    					$to=City::whereid($objbusoccurance->to)->pluck('name');
	    					$trip=$from.'-'.$to;
	    					$price= $objbusoccurance->price;
	    					$amount= $objbusoccurance->price * $tickets;
	    				}
	    				$objorderinfo=SaleOrder::whereid($row->saleitems[0]->order_id)->first();
	    				$response[$i]['customer']=$objorderinfo->name;
	    				$response[$i]['phone']=$objorderinfo->phone;
	    			}else{
	    				$response[$i]['customer']='-';
	    				$response[$i]['phone']='-';
	    			}
					$operator = Operator::whereid($row->operator_id)->pluck('name');
					$trip_id  = BusOccurance::whereid($row['saleitems'][0]['busoccurance_id'])->pluck('trip_id');
					$objagent = AgentCommission::whereagent_id($row['agent_id'])->wheretrip_id($trip_id)->first();
	    			$response_value['operator']=$operator;
	    			$response_value['agent']='-';
	    			$response_value['agent_commission']=0;
	    			if($objagent){
    					$response_value['agent']=$objagent->name;
    					$commissiontype=CommissionType::whereid($objagent->commission_id)->pluck('name');
    					$response_value['commission']=$objagent->commission;	
    					if(strtolower($commissiontype)=='fixed'){
    						$response_value['commission_type']='fixed';	
    						$response_value['agent_commission']=$objagent->commission * $tickets ;	
    					}else{
    						$response_value['commission_type']='percentage';	
    						$response_value['agent_commission']=($amount * $objagent->commission) / 100;	
    					}
    				}else{
    					$response_value['commission']= Trip::whereid($trip_id)->pluck('commission');
    					$response_value['commission_type']='trip';	
    					$response_value['agent_commission']= SaleOrder::whereid($row['id'])->pluck('agent_commission');
    				}

	    			$response[$i]['trip']=$trip;
	    			$response[$i]['total_ticket']=$tickets;
	    			$response[$i]['price']=$price;
	    			$response[$i]['amount']=$amount;
	    			$response[$i]['commission']=$response_value['commission'];
	    			$response[$i]['grand_total']=($amount - $response_value['agent_commission']);
	    			/*$response_value['trip']=$trip;
	    			$response_value['total_ticket']=$tickets;
	    			$response_value['price']=$price;
	    			$response_value['amount']=$amount;
	    			$response_value['grand_total']=($amount - $response_value['agent_commission']);
	    			$response_value['saleitems']=$saleitems;
	    			$arr_response[]=$response_value;*/
	    			$i++;
	    		}
	    	}
	    	return Response::json($response);
    	}
    }

    public function getAgentListByoperator(){
    	$operator_id=Input::get('operator_id');
    	if(!$operator_id){
    		$response['status']=0;
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}
    	$objagentlist=Agent::whereoperator_id($operator_id)->orderBy('name')->get();
    	if($objagentlist){
    		$i=0;
    		foreach ($objagentlist as $row) {
    			$objagentlist[$i]=$row;
    			$objorders=SaleOrder::whereoperator_id($operator_id)->whereagent_id($row->id)->wherecash_credit(2)->lists('id');
    			$credit=0;
    			$grand_total=0;
    			$agent_commission=0;
    			if($objorders){
    				$nationality = SaleOrder::whereid($objorders[0])->pluck('nationality');
    				if($nationality == "local")
    					$grand_total=SaleItem::wherein('order_id',$objorders)->wherefree_ticket(0)->sum('price');
    				else
    					$grand_total=SaleItem::wherein('order_id',$objorders)->wherefree_ticket(0)->sum('foreign_price');

    				$agent_commission=SaleOrder::wherein('id',$objorders)->sum('agent_commission');
    				$credit=$grand_total-$agent_commission;
    			}
    			$objagentdeposit=AgentDeposit::whereagent_id($row->id)->orderBy('id','desc')->first();
    			$objagentlist[$i]['credit']=$credit;
    			$objagentlist[$i]['agent_commission']=$agent_commission;
    			$objagentlist[$i]['to_pay_credit']=$credit;
    			$objagentlist[$i]['deposit_balance']=0;
    			if($objagentdeposit)
    			$objagentlist[$i]['deposit_balance']=$objagentdeposit->balance;
    			$i++;
    		}
    	}
    	return Response::json($objagentlist);
    }

    public function postAgentCreditByDateRangePayment(){
    	$start_date=Input::get('start_date');
    	$end_date=Input::get('end_date');
    	$from=Input::get('from');
    	$to=Input::get('to');
    	$agent_id=Input::get('agent_id');
    	$operator_id=Input::get('operator_id');
    	$trip=Input::get('trip');
    	$payment_amount=Input::get('payment_amount') ? Input::get('payment_amount') : 0;
    	$order_ids=Input::get('order_id');
    	$response=array();
    	if(!$order_ids){
    		if($operator_id && $agent_id && $start_date && $end_date){
	    		$response =SaleOrder::where('orderdate','>=',$start_date)
	    							->where('orderdate','<=',$end_date)
	    							->where('operator_id','=',$operator_id)
	    							->where('agent_id','=',$agent_id)
	    							->wherecash_credit(2)
	    							->orderBy('orderdate','asc')
	    							->lists('id');
	    	}elseif($operator_id && $agent_id && $start_date){
	    		$response =SaleOrder::where('orderdate','=',$start_date)
	    							->where('operator_id','=',$operator_id)
	    							->where('agent_id','=',$agent_id)
	    							->wherecash_credit(2)
	    							->orderBy('orderdate','asc')
	    							->lists('id');
	    	}elseif($operator_id && $agent_id && !$start_date){
	    		$response =SaleOrder::where('operator_id','=',$operator_id)
	    							->where('agent_id','=',$agent_id)
	    							->wherecash_credit(2)
	    							->orderBy('orderdate','asc')
	    							->lists('id');
	    	}else{

	    	}
    	}
    	
    	$total_amount=0;
    	if($response){
    		if($from && $to){
    			$arr_orderid=array();
    			$objbusoccuranceids=BusOccurance::wherefrom($from)->whereto($to)->lists('id');
	    		if($objbusoccuranceids){
	    			$orderidlist=SaleItem::wherein('busoccurance_id', $objbusoccuranceids)->lists('order_id');
	    			if($orderidlist){
	    				foreach($response as $orderids) {
	    					$same=0;
	    					foreach ($orderidlist as $orderidforfromto) {
	    						if($orderids== $orderidforfromto){
	    							$same=1;
	    						}
	    					}
	    					if($same==1)
	    						$arr_orderid[]=$orderids;
			    		}	
	    			}
	    			
	    		}
	    		$response=array();
	    		$response=$arr_orderid;
		    }
 			
    	}

    	if($order_ids){
    		$order_ids=explode(',', $order_ids);
    		$response=array();
    		$response=$order_ids;
    	}
    	$grand_total = 0;
    	foreach ($response as $id) {
    		$nationality = SaleOrder::whereid($id)->pluck('nationality');
    		if($nationality == "local")
    			$grand_total += SaleItem::whereorder_id($id)->wherefree_ticket(0)->pluck('price');
    		else
    			$grand_total += SaleItem::whereorder_id($id)->wherefree_ticket(0)->pluck('foreign_price');
    	}
    	

		$agent_commission=SaleOrder::wherein('id', $response)->sum('agent_commission');
		$total_amount=$grand_total-$agent_commission;

    	if($total_amount >0){
    		$current_balance=0;
    		$balance=0;
    		$debit=0;
    		$objagentdeposit=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id', 'desc')->first();
    		if($objagentdeposit){
    			$current_balance= ($objagentdeposit->balance + $payment_amount) - $objagentdeposit->debit;
    		}else{
    			$current_balance=$payment_amount;
    		}

    		$objdepositpayment_trans=new AgentDeposit();
    		$objdepositpayment_trans->agent_id=$agent_id;
    		$objdepositpayment_trans->operator_id=$operator_id;
    		$objdepositpayment_trans->total_ticket_amt=$total_amount;
    		$today=$this->today;
    		$objdepositpayment_trans->pay_date=$today;
    		$objdepositpayment_trans->payment=$payment_amount;
    		
    		$objdepositpayment_trans->deposit=0;
    		$balance=$current_balance - $total_amount;
    		$agentdeposit=AgentDeposit::whereagent_id($agent_id)->whereoperator_id($operator_id)->orderBy('id','desc')->first();
    		if($agentdeposit){
    			$objdepositpayment_trans->deposit=$agentdeposit->balance;
    		}

    		$objdepositpayment_trans->balance=$balance;
    		$objdepositpayment_trans->debit=0;
    		$objdepositpayment_trans->save();

    		if($order_ids){
    			$response =SaleOrder::wherein('id',$order_ids)
    							->wherecash_credit(2)
    							->update(array('cash_credit'=>1));
    		}else{
    			if(!$start_date && !$end_date){
	    			$response =SaleOrder::where('operator_id','=',$operator_id)
	    							->where('agent_id','=',$agent_id)
	    							->wherecash_credit(2)
	    							->orderBy('orderdate','asc')
	    							->update(array('cash_credit'=>1));
	    		}elseif($start_date && $end_date){
	    			$response =SaleOrder::where('orderdate','>=',$start_date)
	    							->where('orderdate','<=',$end_date)
	    							->where('operator_id','=',$operator_id)
	    							->where('agent_id','=',$agent_id)
	    							->wherecash_credit(2)
	    							->orderBy('orderdate','asc')
	    							->update(array('cash_credit'=>1));
	    		}elseif($start_date && !$end_date){
	    			$response =SaleOrder::where('orderdate','=',$start_date)
	    							->where('operator_id','=',$operator_id)
	    							->where('agent_id','=',$agent_id)
	    							->wherecash_credit(2)
	    							->orderBy('orderdate','asc')
	    							->update(array('cash_credit'=>1));
	    		}else{
	    		}
    		}
    		
    		$message['status']=1;
    		$message['message']="Success transaction.";
    		return Response::json($message);
    	}else{
    		$response['status']=0;
    		$response['message']='There is no debit.';
    		return Response::json($response);
    	}
    }

    public function getAgentCreditByPayment(){
    	$agent_id=Input::get('agent_id');
    	$operator_id=Input::get('operator_id');
    	$start_date=Input::get('start_date');
    	$end_date=Input::get('end_date');
    	$offset=Input::get('offset') ? Input::get('offset') : 1;
    	$limit=Input::get('limit') ? Input::get('limit') : 25;
    	$offset=($offset-1) * $limit;
    	
		$objpayment=AgentDeposit::whereagent_id($agent_id)
							->whereoperator_id($operator_id)
							->take($limit)->skip($offset)
							->get();
	
    	if($objpayment){
    		$i=0;
    		foreach ($objpayment as $row) {
    			$objpayment[$i]=$row;
    			$objpayment[$i]['agent']=Agent::whereid($row->agent_id)->pluck('name');
    			$i++;
    		}
    	}
    	return Response::json($objpayment);
    }

    public function postupdateorderbyadmin(){
    	$operator_id =Input::get('operator_id');
    	$order_id=Input::get('order_id');
    	$agent_id=Input::get('agent_id');

    	$user_id=Operator::whereid($operator_id)->pluck('user_id');
    	$objuser=User::whereid($user_id)->first();
    	if(!$objuser){
    		$response['status']=0;
    		$response['message']="There is no user, User_id is wrong.";
    		return Response::json($response);
    	}
    	if($objuser->role!=1){
    		$response['status']=0;
    		$response['message']="User is not admin.";
    		return Response::json($response);
    	}

    	if(!$order_id || !$agent_id){
    		$response['status']=0;
    		$response['message']="Request parameters are required.";
    		return Response::json($response);
    	}

    	$objorder=SaleOrder::whereid($order_id)->first();
    	if($objorder){
    		$objorder->agent_id=$agent_id;
    		$objorder->update();
    		$response['status']=1;
    		$response['message']="Successfully update order.";
    		return Response::json($response);
    	}
    	$response['status']=0;
		$response['message']="There is no order, Order_Id is wrong.";
		return Response::json($response);
    }

    public function postdeleteorderbyadmin(){
       	$operator_id =Input::get('operator_id');
    	$order_id=Input::get('order_id');
    	$user_id=Operator::whereid($operator_id)->pluck('user_id');
    	$objuser=User::whereid($user_id)->first();
    	if(!$objuser){
    		$response['status']=0;
    		$response['message']="There is no user, User_id is wrong.";
    		return Response::json($response);
    	}
    	if($objuser->role!=1){
    		$response['status']=0;
    		$response['message']="User is not admin.";
    		return Response::json($response);
    	}

    	if(!$order_id){
    		$response['status']=0;
    		$response['message']="Request parameter is required.";
    		return Response::json($response);
    	}

    	$deleteorder=$objorder=SaleOrder::whereid($order_id)->delete();
    	if($deleteorder){
    		$response['status']=1;
    		$response['message']="Successfully delete order.";
    		return Response::json($response);
    	}
    	$response['status']=0;
		$response['message']="There is no order, Order_Id is wrong.";
		return Response::json($response);
    }
    /*
     * Updated by SMK
     */
    public function getSaleList(){
    	$response=array();
    	$operator_id=Input::get('operator_id');
    	$agent_id =Input::get('agent_id');
    	$from=Input::get('from');
    	$to=Input::get('to');
    	$departure_date=Input::get('departure_date');
    	$time=Input::get('time');
    	$order_s_date=Input::get('order_s_date');
    	$order_e_date=Input::get('order_e_date');
    	$cash_credit=Input::get('credit_cash');
    	$objsalelist=array();
    	// Check validation fields
    	if($operator_id == null){
    		$response['status'] 	= 0;
    		$response['message'] 	= "Required for operator id";
    		return Response::json($response, 400);
    	}
    	// Check for from to date time from busoccurance
    	if($from && $to && !$departure_date && !$time)
    	{
    		if($operator_id){
    			$busoccuranceid=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->lists('id');
    		}else{
    			$busoccuranceid=BusOccurance::wherefrom($from)->whereto($to)->lists('id');
    		}
    	}
    	elseif($from && $to && $departure_date && !$time)
    	{
    		if($operator_id){
	    		$busoccuranceid=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->wheredeparture_date($departure_date)->lists('id');
    		}else{
	    		$busoccuranceid=BusOccurance::wherefrom($from)->whereto($to)->wheredeparture_date($departure_date)->lists('id');
    		}
    	}elseif($from && $to && $departure_date && $time)
    	{
    		if($operator_id){
    			$busoccuranceid=BusOccurance::whereoperator_id($operator_id)->wherefrom($from)->whereto($to)->wheredeparture_date($departure_date)->wheredeparture_time($time)->lists('id');
    		}else{
    			$busoccuranceid=BusOccurance::wherefrom($from)->whereto($to)->wheredeparture_date($departure_date)->wheredeparture_time($time)->lists('id');
    		}
    	}
    	else
    	{
    		$busoccuranceid=null;
    	}
    	
    	// Check orderid_list from SaleItems
    	if($busoccuranceid != null && $operator_id)
    	{
    		$orderid_list=SaleItem::whereoperator($operator_id)->wherein('busoccurance_id',$busoccuranceid)->groupBy('order_id')->lists('order_id');
    	}elseif($busoccuranceid != null && !$operator_id)
    	{
    		$orderid_list=SaleItem::wherein('busoccurance_id',$busoccuranceid)->groupBy('order_id')->lists('order_id');
    	}else{
    		$orderid_list=SaleOrder::lists('id');
    	}
    	if($orderid_list == null){
    		return array();
    	}

    	// Check order_list from SaleOrder
    	if($operator_id && !$agent_id && !$order_s_date && !$order_e_date && !$cash_credit)
    	{
    		$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->get();
    	}
		elseif($operator_id && !$agent_id && !$order_s_date && !$order_e_date && $cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->wherecash_credit($cash_credit)
    								->get();
		}
		elseif($operator_id && $agent_id && !$order_s_date && !$order_e_date && $cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->whereagent_id($agent_id)
    								->wherecash_credit($cash_credit)
    								->get();
		}
		elseif($operator_id && $agent_id && $order_s_date && !$order_e_date && $cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->whereorderdate($order_s_date)
    								->whereagent_id($agent_id)
    								->wherecash_credit($cash_credit)
    								->get();
		}
		elseif($operator_id && $agent_id && $order_s_date && $order_e_date && !$cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->where('orderdate','>=',$order_s_date)
    								->where('orderdate','<=',$order_e_date)
    								->whereagent_id($agent_id)
    								->get();
		}
		elseif($operator_id && $agent_id && $order_s_date && $order_e_date && $cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->where('orderdate','>=',$order_s_date)
    								->where('orderdate','<=',$order_e_date)
    								->whereagent_id($agent_id)
    								->wherecash_credit($cash_credit)
    								->get();
		}
		elseif($operator_id && !$agent_id && $order_s_date && !$order_e_date && !$cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->where('orderdate','=',$order_s_date)
    								->get();	
		}
		elseif($operator_id && !$agent_id && $order_s_date && $order_e_date && !$cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->where('orderdate','>=',$order_s_date)
    								->where('orderdate','<=',$order_e_date)
    								->get();	
		}
		elseif($operator_id && !$agent_id && $order_s_date && $order_e_date && $cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->where('orderdate','>=',$order_s_date)
    								->where('orderdate','<=',$order_e_date)
    								->wherecash_credit($cash_credit)
    								->get();	
		}
		elseif($operator_id && $agent_id && !$order_s_date && !$order_e_date && !$cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->whereagent_id($agent_id)
    								->get();
		}
		elseif($operator_id && $agent_id && $order_s_date && !$order_e_date && !$cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->whereagent_id($agent_id)
    								->where('orderdate','=',$order_s_date)
    								->get();
		}
		elseif($operator_id && $agent_id && $order_s_date && $order_e_date && !$cash_credit)
		{
			$objsalelist=SaleOrder::with(array('agent','operator','saleitems'))
    								->wherein('id', $orderid_list)
    								->whereoperator_id($operator_id)
    								->whereagent_id($agent_id)
    								->where('orderdate','>=',$order_s_date)
    								->where('orderdate','<=',$order_e_date)
    								->get();
		}
    	else
    	{
    		$objsalelist=array();
    	}

    	if($objsalelist){
    		$i=0;
    		foreach ($objsalelist as $row) {
    			$temp['id']=$row->id;
    			$temp['orderdate']=$row->orderdate;
    			$temp['agent_id']=$row->agent_id;
    			$temp['operator_id']=$row->operator_id;
    			$temp['customer']=$row->name;
    			$temp['phone']=$row->phone;
    			$temp['operator']=$row->operator ? $row->operator->name : '-';
    			$temp['agent']=$row->agent ? $row->agent->name : '-';
    			
    			$trip = $departure_date = $departure_time = $class = '-';
    			$price = $total_ticket = $amount =0;
    			$agent_commission=0;
    			if(count($row->saleitems)>0){

    				$objbusoccurance=BusOccurance::whereid($row->saleitems[0]->busoccurance_id)->first();
    				if($objbusoccurance){
    					$trip_id=$objbusoccurance->trip_id;
    					if($row->agent_id)
    					$agent_commission=AgentCommission::wheretrip_id($trip_id)->whereagent_id($row->agent_id)->pluck('commission');
    					
    					$from=City::whereid($objbusoccurance->from)->pluck('name');
    					$to=City::whereid($objbusoccurance->to)->pluck('name');
    					$departure_date=$objbusoccurance->departure_date;
    					$departure_time=$objbusoccurance->departure_time;
    					$class=Classes::whereid($objbusoccurance->classes)->pluck('name');
    					$trip=$from.'-'.$to;
    					if($row->agent_id > 0){
    						$price = $agent_commission > 0 ? $objbusoccurance->price - $agent_commission : $objbusoccurance->price - $objbusoccurance->commission;
    					}else{
    						$price = $objbusoccurance->price;
    					}
    					$commission = $objbusoccurance->commission;
    					$total_ticket=count($row->saleitems);
    					$amount=$price * $total_ticket;
    				}
    			}
    			$temp['trip']=$trip;
    			$temp['departure_date']=$departure_date;
    			$temp['departure_time']=$departure_time;
    			$temp['class']=$class;
    			$temp['total_ticket']=$total_ticket;
    			$temp['price']=$price;
    			$temp['commission']= $agent_commission > 0 ? $agent_commission : $commission;
    			$temp['amount']= $amount;
    			$temp['cash_credit']=$row->cash_credit;
    			$temp['saleitems']=$row->saleitems->toarray();
    			$objsalelist[$i]=$temp;
    			$i++;
    		}
    	}

    	return Response::json($objsalelist);
    }

    public function postAgentCommissionByTrip(){
    	$agent_id 		=Input::get('agent_id');
    	$trip_id 		=Input::get('trip_id');
    	$commission_id  =Input::get('commission_id');
    	$commission  	=Input::get('commission');
    	$objagentcommission=new AgentCommission();
    	$objagentcommission->agent_id=$agent_id;
    	$objagentcommission->trip_id=$trip_id;
    	$objagentcommission->commission_id=$commission_id;
    	$objagentcommission->commission=$commission;
    	$objagentcommission->save();
    	$response['status']=1;
    	$response['message']='Successfully save one record.';
    	return Response::json($response);
    }

    public function getTriplistByOperator(){
    	$operator_id=Input::get('operator_id');
    	$trip_list=Trip::with(array('operator'))->whereoperator_id($operator_id)->get();
    	return Response::json($trip_list);
    }

    public function getAgentCommissionListByTrip(){
    	$operator_id=Input::get('operator_id');
    	$trip_id=Input::get('trip_id');
    	$commission_list=AgentCommission::with(array('commission','agent'
												    => function ($query) {
												    $query->select('id', 'name');
												}))->wheretrip_id($trip_id)->get();
    	
    	return Response::json($commission_list);
    }
	
	public function populartrip(){
    	$operator_id 	= Input::get('operator_id');
    	$agent_id 		= Input::get('agent_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	
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
    	if($trip_id){
    		$lists = array();
    		foreach ($trip_id as $rows) {
    			$trips 			= Trip::whereid($rows->trip_id)->first();

    			if($trips){
    				$list['id'] 				= $trips->id;
    				$list['from']				= $trips->from;
    				$list['to']					= $trips->to;
	    			$list['trip'] 				= City::whereid($trips->from)->pluck('name') .' - '.City::whereid($trips->to)->pluck('name');
	    			$list['classes']			= Classes::whereid($trips->class_id)->pluck('name');
	    			$total_seat 				= SeatInfo::whereseat_plan_id($trips->seat_plan_id)->wherestatus(1)->count();
	    			$list['percentage'] 		= round(($rows->count / ($total_seat * $days)) * 100) ;
	    			$list['sold_total_seat'] 	= $rows->count;
	    			$list['total_seat']			= $total_seat * $days;
	    			$list['total_amount']		= $rows->total;
	    			$lists[] 					= $list;
    			}
    		}
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function populartriptime(){
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
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function popularagent(){
    	$operator_id 	= Input::get('operator_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	
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

    	if($agent_id){
    		$lists = array();
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
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function analytisclasses(){
    	$operator_id 	= Input::get('operator_id');
    	$s_date 		= Input::get('start_date');
    	$e_date 		= Input::get('end_date');
    	$agent_id 		= Input::get('agent_id');
    	
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
    		$class_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->selectRaw('class_id, count(*) as count, SUM(price) as total')
    								->groupBy('class_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}

    	if($s_date && $e_date && $agent_id){
    		$class_id = SaleItem::whereoperator($operator_id)
    								->where('departure_date','>=',$s_date)
    								->where('departure_date','<=',$e_date)
    								->whereagent_id($agent_id)
    								->selectRaw('class_id, count(*) as count, SUM(price) as total')
    								->groupBy('class_id')
    								->orderBy('count','desc')
    								->orderBy('total','desc')
    								->get();
    	}

    	if($class_id){
    		$lists = array();
    		foreach ($class_id as $rows) {
    			$classes 			= Classes::whereid($rows->class_id)->first();

    			if($classes){
    				$list['id'] 					= $classes->id;
	    			$list['name'] 					= $classes->name;
	    			$list['total_amount'] 			= $rows->total;
	    			$list['count']					= $rows->count;
	    			$list['purchased_total_seat']	= SaleItem::whereclass_id($classes->id)
	    															->where('departure_date','>=',$s_date)
	    															->where('departure_date','<=',$e_date)
	    															->count();
	    			$lists[] 						= $list;
    			}
    		}
    		return Response::json($lists);
    	}else{
    		return Response::json(array());
    	}
    }

    public function postCloseSeatList(){
    	$trip_id 		= Input::get('trip_id');
    	$operatorgroup_id 	= Input::get('operatorgroup_id');
    	$seat_plan_id 		= Input::get('seat_plan_id');
    	$seat_lists 		= Input::get('seat_lists');
    	if(!$trip_id || !$operatorgroup_id || !$seat_plan_id || !$seat_lists){
    		$message['status'] 	= 0;
    		$message['message']	= "Required for any parameter.";
    		return Response::json($message, 400);
    	}

    	$close_seatinfo = CloseSeatInfo::wheretrip_id($trip_id)
    								->whereoperatorgroup_id($operatorgroup_id)
    								->whereseat_plan_id($seat_plan_id)
    								->first();

    	if($close_seatinfo){
    		$close_seatinfo->seat_lists = $seat_lists;
    		$close_seatinfo->update();
    	}else{
    		$close_seatinfo 					= new CloseSeatInfo();
    		$close_seatinfo->trip_id 			= $trip_id;
    		$close_seatinfo->operatorgroup_id 	= $operatorgroup_id;
    		$close_seatinfo->seat_plan_id		= $seat_plan_id;
    		$close_seatinfo->seat_lists 		= $seat_lists;
    		$close_seatinfo->save();
    	}

    	$message['status'] 	= 1;
    	$message['message']	= "Successfully saved!.";
    	return Response::json($message);
    }

    public function getOperatorGroup(){
    	$operator_id = Input::get('operator_id');
    	if($operator_id)
    		$operatorgroup = OperatorGroup::whereoperator_id($operator_id)->get();
    	else
    		$operatorgroup = OperatorGroup::all();
    	if($operatorgroup){

    		$i = 0;
    		foreach ($operatorgroup as $rows) {
    			$operatorgroup[$i]['username'] = User::whereid($rows->user_id)->pluck('name');
    			$operatorgroup[$i]['operatorname'] = Operator::whereid($rows->operator_id)->pluck('name');
    			$i++;
    		}
    		return Response::json($operatorgroup);
    	}else{
    		return Response::json(array());
    	}

    }

    public function getSeatListbyTrip($id){
    	$seatinfo = SeatInfo::whereseat_plan_id($id)->get();
    	return Response::json($seatinfo);
    }

    public function getTargetLabel(){
    	$target = TargetLabel::all();
    	return Response::json($target);
    }

    public function getExtraDestination($occuranceid){
    	$trip_id = BusOccurance::whereid($occuranceid)->pluck('trip_id'); 
    	$extra_destination = ExtraDestination::wheretrip_id($trip_id)->get();
    	$i = 0;
    	foreach ($extra_destination as $rows) {
    		$extra_destination[$i]['city_name'] = City::whereid($rows->city_id)->pluck('name');
    		$i++;
    	}

    	return Response::json($extra_destination);
    }

    public function getNotiBooking(){
    	$today = Input::get('date') ? Input::get('date') : $this->getDate();
    	$booking_count = SaleOrder::where('departure_date','=',$today)->wherebooking(1)->count();
   		return Response::json($booking_count);
    }

}