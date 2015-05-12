<?php
/*
  Add New Agent
  Store Agent
  Agent List
*/
class AgentController extends BaseController
{
	
    // Add New Agent
    public function getAddagent()
  	{   
	  	$agent =Agent::all();
      if(Auth::user()->role==3){
        $agentgroup=AgentGroup::whereuser_id(Auth::user()->id)->get();
      }else{
        $agentgroup = AgentGroup::all();
      }
	  	$commission = CommissionType::all();
      $operator_id=$this->myGlob->operator_id;
	  	return View::make('agent.add',array('agent'=>$agent,'agentgroup'=>$agentgroup,'commission'=>$commission, 'operator_id'=> $operator_id));
  	}

    // Store Agent
  	public function postAddagent()
    {
      $operator_id  =$this->myGlob->operator_id;
      if(!$operator_id){
        $operator_id=Operator::orderBy('id','desc')->pluck('id');
      }
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
        return Redirect::to('/agentlist?access_token='.Auth::user()->access_token)->with('message', $message);
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
      return Redirect::to('/agentlist?access_token='.Auth::user()->access_token)->with('message', $message);
    }

    // Agent List
  	public function showAgentList()
  	{
      $operator_id=$this->myGlob->operator_id;
      $agopt_ids=$this->myGlob->agopt_ids;
      $agent_ids=$this->myGlob->agent_ids;
      // dd($agent_ids);
      if($agent_ids){
        if($agopt_ids){
          $response   = $obj_agent = Agent::wherein('id',$agent_ids)->orderBy('id','desc')->orderBy('name','asc')->get();
        }else{
          $response   = $obj_agent = Agent::wherein('id',$agent_ids)->whereoperator_id($operator_id)->orderBy('id','desc')->orderBy('name','asc')->get();
        }
      }
      else{
        $response   = $obj_agent = Agent::whereoperator_id($operator_id)->orderBy('id','desc')->orderBy('name','asc')->get();
      }

      // return Response::json($response);
      $allagent   = Agent::all();
      $totalCount = count($allagent);
      $i=0;
       foreach ($response as $agent)
       {
          $agentgroupname = AgentGroup::where('id','=',$agent['agentgroup_id'])->pluck('name');
          $commissionname = CommissionType::where('id','=',$agent['commission_id'])->pluck('name');
          $response[$i]['id']             = $agent['id'];
          $response[$i]['agentgroup_name']= $agentgroupname ? $agentgroupname : 'None';
          $response[$i]['name']           = $agent['name'];
          $response[$i]['phone']          = $agent['phone'];
          $response[$i]['address']        = $agent['address'];
          $response[$i]['commission']     = $agent['commission'];
          $response[$i]['commission_id']  = $agent['commission_id'];
          $response[$i]['commission_type']  = $commissionname;
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
   
    /*public function postEditAgent($id)
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
      return Redirect::to('/agentlist?access_token='.Auth::user()->access_token);
    }*/

    public function postEditAgent($id)
    {
      $status =Input::get('status');// check only updae code_no or not
      if($status==1){
        $objagent=Agent::whereid($id)->first();
        $objagent->code_no=Input::get('code_no');
        $objagent->update();
        return "Successfully update.";
      } 
      
      $affectedRow = Agent::where('id','=',$id)->update(array(
                    'agentgroup_id' => Input::get('agentgroup_id'),
                    'name'=>Input::get('name'),
                    'phone'=>Input::get('phone'),
                    'address'=>Input::get('address'),
                    'commission_id'=>Input::get('comissiontype'),
                    'commission' =>Input::get('comission'),
                    'owner'      =>Input::get('owner'),
                    ));
      return Redirect::to('/agentlist?access_token='.Auth::user()->access_token);
    }
    
    public function getDeleteAgent($id)
    {
      $check_exiting=SaleOrder::whereagent_id($id)->first();
      
      $message=array();
      if(!$check_exiting){
        $affectedRows1 = Agent::where('id','=',$id)->delete();
        $message['status']="0";
        $message['info']="Successfully delete.";
        return Redirect::to('/agentlist?'.$this->myGlob->access_token)->with('message', $message);
      }
      $message['status']="1";
      $message['info']="This agent has transactions, so you can't delete.";

      return Redirect::to('/agentlist?'.$this->myGlob->access_token)->with('message', $message);
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

    //Agent Sale List by Agent_id
    public function agentSaleList($id)
    {
      $report_info      =array();
      $operator_id      =$this->myGlob->operator_id;
      $agent_ids        =$this->myGlob->agent_ids;
      $agopt_ids        =$this->myGlob->agopt_ids;
      $operator_ids     =array();
      if($agopt_ids){
        $operator_ids =$agopt_ids;
      }else{
        $operator_ids[] =$operator_id;
      }
      $agent_status=$agent_id =$id;
      $trips          =Input::get('trips'); //for agent report or not
      $search=array();
      $search['agent_rp']   =1;

      $from           =Input::get('from');
      $to             =Input::get('to');

      $start_date       =Input::get('start_date');
      $end_date         =Input::get('end_date') ? Input::get('end_date') : $this->getDate();
      if($start_date){
        $start_date =str_replace('/', '-', $start_date);
        $start_date =date('Y-m-d', strtotime($start_date));
        $end_date =str_replace('/', '-', $end_date);
        $end_date =date('Y-m-d', strtotime($end_date));
      }else{
        $start_date=$this->getDate();
        $end_date=$this->getDate();
      }

      $departure_time     =Input::get('departure_time');
      $departure_time     =str_replace('-', ' ', $departure_time);

      $operator_id  =$operator_id ? $operator_id : $this->myGlob->operator_id;

      if($from=='all')
        $from=0;

      $trip_ids=array();
      $sale_item=array();
      $order_ids=array();

      if($departure_time){
        $trip_ids=Trip::wherein('operator_id',$operator_ids)
                  ->wheretime($departure_time)->lists('id');
      }else{
        $trip_ids=Trip::wherein('operator_id',$operator_ids)
                  ->lists('id');
      }

      if($trip_ids)
        $order_ids=SaleItem::wherein('trip_id',$trip_ids)
                  ->where('departure_date','>=',$start_date)
                  ->where('departure_date','<=',$end_date)
                  ->groupBy('order_id')->lists('order_id');

      if($order_ids)
          $order_ids=SaleOrder::wherein('id',$order_ids)->wherebooking(0)->lists('id');

        if($order_ids)
        { 
          /******************************************************************************** 
          * For agent report by agent group and branches OR don't have branch agent
          ***/
          $agentgroup_id      =Input::get('agentgroup');
          if($agentgroup_id=="All")
            $agentgroup_id=0;
          $arr_agent_id     =array();

          if($agentgroup_id && $agent_id)
          {
            $sale_item = SaleItem::wherein('order_id', $order_ids)
                    ->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
                    ->whereagent_id($agent_id)
                    ->groupBy('order_id')->orderBy('departure_date','asc')->get();  
          }
          elseif(!$agentgroup_id && $agent_id)
          {
            $sale_item = SaleItem::wherein('order_id', $order_ids)
                    ->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
                    ->whereagent_id($agent_id)
                    ->groupBy('order_id')->orderBy('departure_date','asc')->get();
          }
          elseif($agentgroup_id && !$agent_id)
          {
            $arr_agent_id=Agent::whereagentgroup_id($agentgroup_id)->lists('id');
              
              $order_ids2=array();
              if($arr_agent_id)
                $order_ids2=SaleItem::wherein('agent_id',$arr_agent_id)->where('departure_date','>=',$start_date)->where('departure_date','<=',$end_date)->groupBy('order_id')->lists('order_id');
              // for unique orderids for all agent branches
              $order_id_list=array_intersect($order_ids, $order_ids2);
              // dd($order_id_list);
            if($order_id_list)
              $sale_item = SaleItem::wherein('order_id', $order_id_list)
                    ->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
                    // ->whereagent_id($agent_id)
                    ->groupBy('order_id')->orderBy('departure_date','asc')->get();  
          }
        /***
        * End For agent report by agent group and branches OR don't have branch agent
          *********************************************************************************/
        
        /******************************************************************* 
        * for Trip report 
        */
          else
          {
            $sale_item = SaleItem::wherein('order_id', $order_ids)
                    ->selectRaw('order_id, count(*) as sold_seat, trip_id, price, foreign_price, departure_date, busoccurance_id, SUM(free_ticket) as free_ticket, agent_id')
                    ->groupBy('order_id')->orderBy('departure_date','asc')->get();
          }

        }
        
      $lists = array();
      foreach ($sale_item as $rows) {
        $local_person = 0;
        $foreign_price = 0;
        $total_amount = 0;
        $commission=0;
        $percent_total=0;
        $trip = Trip::whereid($rows->trip_id)->first();
        $order_date=SaleOrder::whereid($rows->order_id)->pluck('orderdate');
        $list['order_date'] = $order_date;
        $list['order_id'] = $rows->order_id;
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
          
          $list['from_to_class']=$list['from_to']. "(".$list['class_name'].")";
          
          $nationality=SaleOrder::whereid($rows->order_id)->pluck('nationality');
          
          $agent_commission = AgentCommission::wheretrip_id($rows->trip_id)->whereagent_id($rows->agent_id)->first();
          if($agent_commission){
              $commission = $agent_commission->commission;
            }else{
              $commission = Trip::whereid($rows->trip_id)->pluck('commission');
            }

          if( $nationality== 'local'){
            $local_person += $rows->sold_seat;
            $total_amount += $rows->free_ticket > 0 ? ($rows->price * $rows->sold_seat) - ($rows->price * $rows->free_ticket) : $rows->price * $rows->sold_seat ;
            $tmptotal      =$rows->price * ($rows->sold_seat- $rows->free_ticket);
            $percent_total +=$tmptotal - ($commission * ($rows->sold_seat- $rows->free_ticket));
          }else{
            $foreign_price += $rows->sold_seat;
            $total_amount += $rows->free_ticket > 0 ? ($rows->foreign_price * $rows->sold_seat) - ($rows->foreign_price * $rows->free_ticket) : $rows->foreign_price * $rows->sold_seat ;
            $tmptotal      =$rows->foreign_price * ($rows->sold_seat- $rows->free_ticket);
            $percent_total +=$tmptotal - ($commission * ($rows->sold_seat- $rows->free_ticket));
          }
          $list['local_person'] = $local_person;
          $list['foreign_person'] = $foreign_price;
          $list['local_price'] = $rows->price;
          $list['foreign_price'] = $rows->foreign_price;
          $list['sold_seat'] = $rows->sold_seat;
          $list['free_ticket'] = $rows->free_ticket;
          $list['total_amount'] = $total_amount;
          $list['percent_total'] = $percent_total;
          $lists[] = $list;
        }
      }
      //Grouping from Lists
      $stack = array();
      foreach ($lists as $rows) {
        if($search['agent_rp'])
          $check = $this->ifExistAgent($rows, $stack);
        else
          $check = $this->ifExist($rows, $stack);
        if($check != -1){
          $stack[$check]['local_person'] += $rows['local_person'];
          $stack[$check]['foreign_person'] += $rows['foreign_person'];
          $stack[$check]['sold_seat'] += $rows['sold_seat'];
          $stack[$check]['free_ticket'] += $rows['free_ticket'];
          $stack[$check]['total_amount'] += $rows['total_amount'];
          $stack[$check]['percent_total'] += $rows['percent_total'];
        }else{
          array_push($stack, $rows);
        }
      }

      $search['agentgroup']="";
      $agentgroup=array();
      $agentgroup=AgentGroup::whereoperator_id($operator_id)->get();
      $search['agentgroup']=$agentgroup;

      $cities=array();
      $cities=$this->getCitiesByoperatorId($operator_id);
      $search['cities']=$cities;
      
      $times=array();
      $times=$this->getTime($operator_id, $from, $to);
      $search['times']=$times;

      $search['operator_id']=$operator_id;
      $search['trips']=$trips;
      $search['from']=$from;
      $search['to']=$to;
      $search['time']=$departure_time;
      $search['start_date']=$start_date;
      $search['end_date']=$end_date;
      $search['agentgroup_id']=Input::get('agentgroup')? Input::get('agentgroup') : 0;
      $search['agent_name']=Agent::whereid($id)->pluck('name');
      $search['agent_id']=$id;
      
      // sorting result
      $response=$this->msort($stack,array("departure_date","time"), $sort_flags=SORT_REGULAR,$order=SORT_ASC);
      
      // grouping
      if($search['agent_rp']==1){
        $tripandorderdategroup = array();
        foreach ($response AS $arr) {
          $tripandorderdategroup[$arr['agent_name']][] = $arr;
        }
      }
      else
      {
        $tripandorderdategroup = array();
        foreach ($response AS $arr) {
          $tripandorderdategroup[$arr['from_to_class']][] = $arr;
        }
          // sorting
      }
      ksort($tripandorderdategroup);

      $agent=Agent::whereoperator_id($operator_id)->where('id','!=',$id)->get();
      // return Response::json($tripandorderdategroup);
      return View::make('agent.agentsalelist', array('response'=>$tripandorderdategroup, 'search'=>$search,'agentlist'=>$agent));
    }
   
    
    public function postagentchange(){
      $order_id=Input::get('order_id');
      $agent_id=Input::get('agent_id');
      $backurl=URL::previous();
      $objsaleorder=SaleOrder::whereid($order_id)->first();
      if($objsaleorder){
        $objsaleorder->agent_id=$agent_id;
        $objsaleorder->update();
      }

      $objsaleitem=SaleItem::whereorder_id($order_id)->update(array('agent_id'=>$agent_id));
      $agentgroup_id=Agent::whereid($agent_id)->pluck('agentgroup_id');
      $objagentdeposittransactions=AgentDeposit::whereorder_ids('["'.$order_id.'"]')->update(array('agent_id'=>$agent_id));
     
      return Redirect::to($backurl);
    } 

    //Start General Functions
      public function ifExist($key, $arr){
        if(is_array($arr)){
          $i = 0;
          foreach ($arr as $key_row) {
            if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date'] && $key_row['bus_id'] == $key['bus_id']){
              return $i;
            }
            $i++;
          }
        }
        return -1;
      }

      public static function getCitiesByoperatorId($operator_id){
        if(!$operator_id){
          $response['message']='The request is missing a required parameter.'; 
          return $response;
        }
        // =========================================
          $orderids       =SaleOrder::whereoperator_id($operator_id)->lists('id');
            $cities=array();
            if($orderids){
              $trip_ids   =SaleItem::wherein('order_id', $orderids)->groupBy('trip_id')->lists('trip_id');
              if($trip_ids){
                foreach ($trip_ids as $trip_id) {
                  $objtrip    =Trip::whereid($trip_id)->first();
                  if($objtrip){
                    $objfromcities        =City::whereid($objtrip->from)->first();
                    $tempfrom['from']     =$objfromcities->id;
                    $tempfrom['from_city']    =$objfromcities->name;
                    $objtocities        =City::whereid($objtrip->to)->first();
                    $tempto['to']       =$objtocities->id;
                    $tempto['to_city']      =$objtocities->name;
                    $from[]           =$tempfrom;
                    $to[]           =$tempto;
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
              return $response;
            }

            return $cities;
        // =========================================
      }

      public function ifExistAgent($key, $arr){
        if(is_array($arr)){
          $i = 0;
          foreach ($arr as $key_row) {
            if($key_row['id'].'-'.$key_row['departure_date'] == $key['id'].'-'.$key['departure_date'] && $key_row['agent_id'] == $key['agent_id']){
              return $i;
            }
            $i++;
          }
        }
        return -1;
      }

      
    //End General Functions



}
