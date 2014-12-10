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

  	public function postAddagent()
    {
      $operator_id  =Input::get('operator_id');
      $agentgroup_id=Input::get('agentgroup_id');
      $name         =Input::get('name');
      $phone        =Input::get('phone');
      $address      =Input::get('address');
      $commission_id=Input::get('comissiontype');
      $commission   =Input::get('commission');
      $owner   =Input::get('owner') ? Input::get('owner') : 0;
      $check_exiting=Agent::whereoperator_id($operator_id)->wherename($name)->wherephone($phone)->first();
      if($check_exiting){
        $message['status']=0;
        $message['info']="This record is already exit.";
        return Redirect::to('/agentlist')->with('message', $message);
      }
      $objagent           =new Agent();
      $objagent->agentgroup_id=$agentgroup_id;
      $objagent->name         =$name;
      $objagent->phone        =$phone;
      $objagent->address        =$address;
      $objagent->commission_id  =$commission_id;
      $objagent->commission     =$commission;
      $objagent->owner          =$owner != null ? $owner : 0;
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
      $operator_id=OperatorGroup::whereuser_id($user_id)->pluck('operator_id');
      $response   = $obj_agent = Agent::whereoperator_id($operator_id)->orderBy('id','desc')->get();
      $allagent   = Agent::all();
      $totalCount = count($allagent);
      $i=0;
       foreach ($response as $agent)
       {
          $agentgroupname = AgentGroup::where('id','=',$agent['agentgroup_id'])->pluck('name');
          $commissionname = CommissionType::where('id','=',$agent['commission_id'])->pluck('name');
          $response[$i]['id']             = $agent['id'];
          $response[$i]['agentgroup_name']= $agentgroupname ? $agentgroupname : 'မရွိ';
          $response[$i]['name']           = $agent['name'];
          $response[$i]['phone']          = $agent['phone'];
          $response[$i]['address']        = $agent['address'];
          $response[$i]['commission']     = $agent['commission'];
          $response[$i]['commission_id']  = $commissionname;
          $i++;
        } 

        $agent_group = array();
        foreach($response AS $arr) {
          $agent_group[$arr['agentgroup_name']][] = $arr->toarray();
        }

        ksort($agent_group);
        // return Response::json($agent_group);
         return View::make('agent.list', array(
        'response'    =>  $agent_group,
        'obj_agent'     =>  $obj_agent,
        'totalCount'  =>  $totalCount,
        'message'   =>  ''
        ));

  	}
    
    public function getEditAgent($id)
    {
        $agentgroup=AgentGroup::all();
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
        $agent['owner']    = $obj_agent->owner;

        $comissiontype     = CommissionType::all();
       
        $response['agent']          = $agent;
        $response['comissiontype']  = $comissiontype;
                // return Response::json($response);
        return View::make('agent.edit', array('response'=> $response,'agentgroup'=>$agentgroup));
    }
   
    public function postEditAgent($id)
    {
      $affectedRow = Agent::where('id','=',$id)->update(array(
                    'agentgroup_id' => Input::get('agentgroup_id'),
                    'name'=>Input::get('name'),
                    'phone'=>Input::get('phone'),
                    'address'=>Input::get('address'),
                    'commission_id'=>Input::get('comissiontype'),
                    'commission' =>Input::get('comission'),
                    'owner'      =>Input::get('owner'),
                    ));
      return Redirect::to('/agentlist');
    }
    
    public function getDeleteAgent($id)
    {
      $check_exiting=SaleOrder::whereagent_id($id)->first();
      
      $message=array();
      if(!$check_exiting){
        $affectedRows1 = Agent::where('id','=',$id)->delete();
        $message['status']="0";
        $message['info']="Successfully delete.";
        return Redirect::to('/agentlist')->with('message', $message);
      }
      $message['status']="1";
      $message['info']="This agent has transactions, so you can't delete.";

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
