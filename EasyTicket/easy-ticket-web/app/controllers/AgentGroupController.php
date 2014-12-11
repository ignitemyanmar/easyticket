<?php
class AgentGroupController extends BaseController
{
	   public function getAddagentgroup()
  	{   
	  	// $agent =Agent::all();
	  	// $agentgroup = AgentGroup::all();
	  	return View::make('agentgroup.add');
  	}

  	public function postAddagentgroup()
  	{
      $name = Input::get('agentgroup');
      $objagentgroup = new AgentGroup();
      $objagentgroup->name=$name;
      $result = $objagentgroup->save();
      return Redirect::to('/agentgrouplist');
  	}
   

  	public function showAgentgroupList()
  	{
      $objagentgroup = AgentGroup::orderBy('id','desc')->get();
      // return Response::json($objagentgroup);
      $response = $objagentgroup;
      $totalcount = AgentGroup::count();
      return View::make('agentgroup.list',array('response'=>$response,'totalcount'=>$totalcount));
  	}

    public function AgentGroupChildList($id)
    {
      $response=Agent::whereagentgroup_id($id)->get();
      $groupname=AgentGroup::whereid($id)->pluck('name');
      // return Response::json($agentlist);
      return View::make('agentgroup.childlist', array('response'=> $response, 'groupname'=>$groupname));
    }

    public function getEditAgentgroup($id)
    {
      return View::make('agentgroup.edit')->with('agentgroup',AgentGroup::find($id));
    }

    public function postEditAgentgroup($id)
    {
      $agentgroup = new AgentGroup();
      $affectedRows= AgentGroup::where('id','=',$id)->update(array('name'=>Input::get('name')));
      return Redirect::to('agentgrouplist');
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
    
}
