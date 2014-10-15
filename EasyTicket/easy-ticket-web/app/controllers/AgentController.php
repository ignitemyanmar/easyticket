<?php
class AgentController extends BaseController
{
	public function getAddagent()
  	{   
	  	$agent =Agent::all();
	  	$agentgroup = AgentGroup::all();
	  	$commission = CommissionType::all();
      $user_id=Auth::user()->id;
      $operator_id=Operator::whereuser_id($user_id)->pluck('id');
	  	return View::make('agent.add',array('agent'=>$agent,'agentgroup'=>$agentgroup,'commission'=>$commission, 'operator_id'=> $operator_id));
  	}

  	/*public function postAddagentold()
  	{
    	$agentgroup_id=Input::get('agentgroup_id');
      $name         =Input::get('name');
      $phone        =Input::get('phone');
      $address      =Input::get('address');
      $commission_id=Input::get('comissiontype');
      $commission   =Input::get('commission');
      $email        =Input::get('email');
      $password     =Input::get('password');

      $check_exiting  =User::whereemail($email)->first();
      if($check_exiting){
        $response['message']="Email is already used.";
      }
      $response=array();

      $checkagent  =Agent::whereagentgroup_id($agentgroup_id)->wherename($name)->wherephone($phone)->first();
      if($checkagent){
        $response['message']='This agent is already exit.';
      }

      $user=new User();
      $user->name=$name;
      $user->email=$email;
      $user->password=Hash::make($password);
      $user->type="agent";
      $user->save();
      $user_id=$user->id;

      $objagent           =new Agent();
      $objagent->agentgroup_id    =$agentgroup_id;
      $objagent->name         =$name;
      $objagent->phone        =$phone;
      $objagent->address        =$address;
      $objagent->commission_id    =$commission_id;
      $objagent->commission       =$commission;
      $objagent->user_id        =$user_id;
      $objagent->save();

      $objoauthclient =new OauthClients();
      $client_id=rand(9000000,6);
      $clientname=$name ."(agent)";
      $secret =Hash::make($clientname);
      $objoauthclient->id=$client_id;
      $objoauthclient->secret=$secret;
      $objoauthclient->name=$clientname;
      $objoauthclient->save();
      return Redirect::to('/agentlist');
    }*/

    public function postAddagent()
    {
      $operator_id  =Input::get('operator_id');
      $name         =Input::get('name');
      $phone        =Input::get('phone');
      $address      =Input::get('address');
      $commission_id=Input::get('comissiontype');
      $commission   =Input::get('commission');
      $check_exiting=Agent::whereoperator_id($operator_id)->wherename($name)->wherephone($phone)->first();
      if($check_exiting){
        $message['status']=0;
        $message['info']="This record is already exit.";
        return Redirect::to('/agentlist')->with('message', $message);
      }
      $objagent           =new Agent();
      $objagent->agentgroup_id=0;
      $objagent->name         =$name;
      $objagent->phone        =$phone;
      $objagent->address        =$address;
      $objagent->commission_id  =$commission_id;
      $objagent->commission     =$commission;
      $objagent->user_id        =0;
      $objagent->operator_id    =$operator_id;
      $objagent->save();
      $message['status']=1;
      $message['info']="Successfully insert agent.";
      return Redirect::to('/agentlist')->with('message', $message);
    }

  	public function showAgentList()
  	{
      $user_id    =Auth::user()->id;
      $operator_id=Operator::whereuser_id($user_id)->pluck('id');
      $response   = $obj_agent = Agent::whereoperator_id($operator_id)->orderBy('id','desc')->get();
      $allagent   = Agent::all();
      $totalCount = count($allagent);
      $i=0;
       foreach ($response as $agent)
       {
          $agentgroupname = AgentGroup::where('id','=',$agent['agentgroup_id'])->pluck('name');
          $commissionname = CommissionType::where('id','=',$agent['commission_id'])->pluck('name');
        
            $response[$i]['id']             = $agent['id'];
            $response[$i]['agentgroup_id']  = $agentgroupname;
            $response[$i]['name']           = $agent['name'];
            $response[$i]['phone']          = $agent['phone'];
            $response[$i]['address']        = $agent['address'];
            $response[$i]['commission']     = $agent['commission'];
            $response[$i]['commission_id']  = $commissionname;
            $i++;
        } 
         return View::make('agent.list', array(
        'response'    =>  $response,
        'obj_agent'     =>  $obj_agent,
        'totalCount'  =>  $totalCount,
        'message'   =>  ''
        ));

  	}
    
    public function getEditAgent($id)
    {
      $obj_agent = Agent::find($id);
        if(count($obj_agent) == 0 ){
            return Redirect::to('agentlist');
        }
        $agent['id']            = $id;
        $agent['agentgroup_id'] = $obj_agent->agentgroup_id;
        $agent['name']          = $obj_agent->name;
        $agent['phone']         = $obj_agent->phone;
        $agent['address']       = $obj_agent->address;
        $agent['commission_id'] = $obj_agent->commission_id;
        $agent['commission']    = $obj_agent->commission;

        $agentgroup        = AgentGroup::all();
        $comissiontype     = CommissionType::all();
       
        $response['agent']          = $agent;
        $response['agentgroup']     = $agentgroup;
        $response['comissiontype']  = $comissiontype;
                
        return View::make('agent.edit')->with('response', $response);
    }
   
    public function postEditAgent($id)
    {
      $affectedRow = Agent::where('id','=',$id)->update(array(
                    'agentgroup_id' => Input::get('agentgroup'),
                    'name'=>Input::get('name'),
                    'phone'=>Input::get('phone'),
                    'address'=>Input::get('address'),
                    'commission_id'=>Input::get('comissiontype'),
                    'commission' =>Input::get('comission'),
                    ));
      return Redirect::to('/agentlist');
    }
    
    public function getDeleteAgent($id)
    {
      $check_exiting=SaleOrder::whereagent_id($id)->first();
      
      $message=array();
      if(!$check_exiting){
        $affectedRows1 = Agent::where('id','=',$id)->delete();
        $message['status']=0;
        $message['info']="Successfully delete.";
        return Redirect::to('/agentlist')->with('message', $message);
      }
      $message['status']=1;
      $message="This agent has transactions, so you can't delete.";

      return Redirect::to('/agentlist')->with('message', $message);
    }
    
    public function postdelAgent()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to("/agentlist");
      }
      foreach($todeleterecorts as $recid)
      {
        $result = Agent::where('id','=',$recid)->delete();
      }
      return Redirect::to("/agentlist");
    }
  
    public function postSearchAgent()
    {
      $keyword = Input::get('keyword');
      $response=$agent = Agent::where('name','LIKE','%'.$keyword.'%')->orderBy('id','DESC')->paginate(10);
      $allagent = Agent::all();
      $totalCount = count($allagent);
      return View::make('agent.list')->with('agent', $agent)->with('totalCount',$totalCount)->with('response',$response);
      // return View::make('agent.list',array('agent'=>$agent,'totalCount'=>$totalCount,'response',$response));
    }
   
}