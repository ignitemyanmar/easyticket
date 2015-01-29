<?php
class AgentGroupController extends BaseController
{
    public function showAgentgroupList()
    {
      $agentgroup_id =$this->myGlob->agentgroup_id;
      if($agentgroup_id)
        $objagentgroup = AgentGroup::whereid($agentgroup_id)->orderBy('id','desc')->get();
      else
        $objagentgroup = AgentGroup::orderBy('id','desc')->get();

      // return Response::json($objagentgroup);
      $response = $objagentgroup;
      $totalcount = AgentGroup::count();
      return View::make('agentgroup.list',array('response'=>$response,'totalcount'=>$totalcount));
    }

    public function getAddagentgroup()
    {   
      return View::make('agentgroup.add');
    }

    public function postAddagentgroup()
    {
      $name = Input::get('agentgroup');
      $checkexisting=AgentGroup::wherename($name)->first();
      if($checkexisting){
        $message['status']=0;
        $message['info']="This record is already exit.";
        return Redirect::to('/agentgrouplist')->with('message', $message);
      }

      $objagentgroup = new AgentGroup();
      $objagentgroup->name=$name;
      $result = $objagentgroup->save();
      $message['status']=1;
      $message['info']="Successfully save one record.";
      return Redirect::to('/agentgrouplist')->with('message', $message);
    }

    public function AgentGroupChildList($id)
    {
      $response=Agent::whereagentgroup_id($id)->get();
      $groupname=AgentGroup::whereid($id)->pluck('name');
      return View::make('agentgroup.childlist', array('response'=> $response, 'groupname'=>$groupname));
    }

    public function getEditAgentgroup($id)
    {
      return View::make('agentgroup.edit')->with('agentgroup',AgentGroup::find($id));
    }

    public function getAgentGroupActions($id){
      $response=array();
      $response=AgentGroup::whereid($id)->first();
      $operator_id=$this->myGlob->operator_id;
      $trips=Trip::whereoperator_id($operator_id)->with(array('from_city','to_city','busclass'))->get();

      return View::make('agentgroup.groupactions', array('response'=>$response,'trips'=>$trips));;
      // return View::make('agentgroup.gropuactions');
    }

    public function postEditAgentgroup($id)
    {
      AgentGroup::where('id','=',$id)->update(array('name'=>Input::get('name')));
      $message['status']=1;
      $message['info']="Successfully update one record.";
      return Redirect::to('agentgrouplist')->with('message', $message);
    }

    public function getDeleteAgentgroup($id)
    {
      $affectedRows1 = AgentGroup::where('id','=',$id)->delete();
      return Redirect::to('agentgrouplist');
    }

    public function postdelAgentgroup()
    {
      $todeleterecorts = Input::get('recordstoDelete');
      if(count($todeleterecorts)==0)
      {
        return Redirect::to("/agentgrouplist");
      }
      foreach($todeleterecorts as $recid)
      {
        $result = AgentGroup::where('id','=',$recid)->delete();
      }
      return Redirect::to("/agentgrouplist");
    }
    
    public function postSearchAgentgroup()
    {
      $keyword = Input::get('keyword');
      $response=$agentgroup = AgentGroup::where('name','LIKE','%'.$keyword.'%');
              
        $allagentgroup = AgentGroup::all();
        $totalcount = count($allagentgroup);
        return View::make('agentgroup.list')->with('agentgroup',$agentgroup)->with('totalcount',$totalcount)->with('response',$response);
    }

    public function destroy($id){
      AgentGroup::whereid($id)->delete();
      $message['status']=0;
      $message['info']="Successfully delete one record.";
       return Redirect::to("/agentgrouplist")->with('message', $message);
    }
    
}
