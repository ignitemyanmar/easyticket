<?php
class UserController extends BaseController
{
  public function postToTakeAccessToken(){
    return AuthorizationServer::performAccessTokenFlow();
  }

  public function getUserRegister(){
      return View::make('admin.register');
  }

  public function postUserRegister(){
      $user=new User();
      $checkuser=User::whereemail(Input::get('email'))->first();
      if($checkuser){
        return 'Email is already used!';
      }
      $user->name=Input::get('username');
      $user->email=Input::get('email');
      $user->password=Hash::make(Input::get('password'));
      $user->role=0;
      $user->save();

      $user = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );
            
          if (Auth::attempt($user)) {
            $id=Auth::user()->id;
            $access_token="ljhj2gnjKvy3HDy0NB04NYDbdCRyKzSprpqRKF8x";
            Session::put('access_token', $access_token);
            return "agents/operator/1";
          }
            
      return "Sorry. You can't register.";

  } 

  public function getFLogin()
  {
    if(Auth::check()){
      return Redirect::to("/all-trips?access_token=".Auth::user()->access_token);
    }
    return View::make('login.login');
  }

  public function postFrontLogin(){
      $username = Input::get('username');
      $password = Input::get('password');
      
      $curl = curl_init( "http://easyticket.dev/oauth/access_token" );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
          'client_id'     => '721689',
          'client_secret' => 'onlineSale@EasyTickeTadmiM',
          'grant_type'    => 'password',
          'scope'         => 'sale,booking',
          'state'         => '123456789',
          'username'      => $username,
          'password'      => $password
      ) );
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
      $auth = curl_exec( $curl );
      $auth = json_decode($auth);
      $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);
      if($http_status == 200){
        $user = array(
                'email' => $username,
                'password' => $password
              );
        if(Auth::attempt($user)){
          /*if(Auth::check()){
            if(Auth::user()->role==9 || Auth::user()->role==3){
              $operator_ids=Operator::lists('id');
              if($operator_ids){
                foreach ($operator_ids as $operator_id) {
                  $this->tripautocreate($operator_id);
                }
              }
              return '/alloperator?access_token='.Auth::user()->access_token;
            }else{
              $operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
                if(!$operator_id){
                    $operator_id=OperatorGroupUser::whereuser_id(Auth::user()->id)->pluck('operator_id');
                }
              $this->tripautocreate($operator_id);
              return "/all-trips?access_token=".Auth::user()->access_token;
            }
          }*/

          if(Auth::check()){
            if(Auth::user()->role >= 2){
              if(Auth::user()->type == "operator"){
                  $operator_id = Operator::whereuser_id(Auth::user()->id)->pluck('id');
                  if($operator_id)
                    $this->tripautocreate($operator_id);

                  return "/all-trips?access_token=".Auth::user()->access_token;
              }
              if(Auth::user()->type == "admin" || Auth::user()->type == "agent"){
                  $operator_ids=Operator::lists('id');
                  if($operator_ids){
                    foreach ($operator_ids as $operator_id) {
                      $this->tripautocreate($operator_id);
                    }
                  }
                  return '/alloperator?access_token='.Auth::user()->access_token;
              }
            }else{
              Auth::logout();
              return '/front_403';
            }
          }
        }
      }else{
        return '/';
      }
  }

  public function getFrontLogout(){
    Auth::logout();
        return Redirect::to('/')
            ->with('flash_notice', 'You are successfully logged out.');
  }


  public function getLogin()
  {
    if(Auth::check()){
        if(Auth::user()->type == "operator"){
          $operator_id=Operator::whereuser_id(Auth::user()->id)->pluck('id');
          return Redirect::to("report/dailycarandadvancesale?access_token=".Auth::user()->access_token."&operator_id=".$operator_id);
        }elseif(Auth::user()->type == "agent"){
          $agent_id=Agent::whereuser_id(Auth::user()->id)->pluck('id');
          return Redirect::to("report/dailycarandadvancesale?access_token=".Auth::user()->access_token."&operator_id=all");
        }else{
          
        }
    }
    return View::make('admin.login');
  }

  public function postLogin(){
      $username = Input::get('username');
      $password = Input::get('password');

      $curl = curl_init( "http://easyticket.dev/oauth/access_token" );
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, array(
          'client_id'     => '721685',
          'client_secret' => 'IgniteAdmin721685',
          'grant_type'    => 'password',
          'scope'         => 'admin, sale, booking',
          'state'         => '123456789',
          'username'      => $username,
          'password'      => $password
      ) );
      curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
      $auth = curl_exec( $curl );
      $auth = json_decode($auth);
      $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);
      if($http_status == 200){
        $user = array(
                'email' => $username,
                'password' => $password
              );
        if(Auth::attempt($user)){
          if(Auth::check() && Auth::user()->role >= 2){
              if(Auth::user()->type == "operator"){
                $operator_id=Operator::whereuser_id(Auth::user()->id)->pluck('id');
                return "report/dailycarandadvancesale?access_token=".Auth::user()->access_token."&operator_id=".$operator_id;
              }elseif(Auth::user()->type == "agent"){
                return "report/dailycarandadvancesale?access_token=".Auth::user()->access_token."&operator_id=all";
              }else{
                return "report/dailycarandadvancesale?access_token=".Auth::user()->access_token."&operator_id=all";
              }
          }else{
            Auth::logout();
            return "/403";
          }
        }
      }else{
        return "easyticket-admin";
      }
  }

  public function getLogout(){
    Auth::logout();
        return Redirect::to('/easyticket-admin')
            ->with('flash_notice', 'You are successfully logged out.');
  }

  public function filterauth(){
      if (Auth::guest())
                    return Redirect::to('easyticket-admin')
                            ->with('flash_error', 'You must be logged in to view this page!');

  }

  public function filterguest(){
    if (Auth::check()) 
                return Redirect::to('/')->with('flash_notice', 'You are already logged in!');
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
          $trip_id      =$trip->id;
          $bus_no       =BusOccurance::wheretrip_id($trip->id)->pluck('bus_no');
          $bus_no       =$bus_no !=null ? $bus_no : "-";
          $today        =$today;
          $year         =date('Y');
          $nextYear     =$year + 1;
          $cMonth       =date('m');
          $checkdate      =date('d', strtotime($today));
          $month        =date("Y-m-d", strtotime("+1 month", strtotime($today)));
          $nextMonth      =date("m", strtotime($month));
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
              $objtrip      =Trip::whereid($trip_id)->first();
              $tirp_id      =$objtrip->id;
              $seat_plan_id   =$objtrip->seat_plan_id;
              $operator_id    =$objtrip->operator_id;
              $from         =$objtrip->from;
              $to         =$objtrip->to;
              $class_id     =$objtrip->class_id;
              $price        =$objtrip->price;
              $foreign_price    =$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
              $commission     =$objtrip->commission;
              $time         =$objtrip->time;
              $i=1;
              foreach ($tocreateoccurance as $departure_date) {
                $obj_busoccurance   =new BusOccurance();
                $obj_busoccurance->seat_plan_id   =$seat_plan_id;
                $obj_busoccurance->bus_no       =$bus_no;
                $obj_busoccurance->from       =$from;
                $obj_busoccurance->to         =$to;
                $obj_busoccurance->classes      =$class_id;
                $obj_busoccurance->departure_date   =$departure_date;
                $obj_busoccurance->departure_time   =$time;
                $obj_busoccurance->price      =$price;
                $obj_busoccurance->foreign_price  =$foreign_price;
                $obj_busoccurance->commission   =$commission;
                $obj_busoccurance->operator_id    =$operator_id;
                $obj_busoccurance->trip_id      =$trip_id;
                $checkbusoccurances=BusOccurance::wheretrip_id($trip_id)->wheredeparture_date($departure_date)->wheredeparture_time($time)->whereclasses($class_id)->first();
                if($checkbusoccurances){
                  
                }else{
                  $obj_busoccurance->save();
                }
                $i++;
              } 
            }
            
          }
        }elseif(substr($trip->available_day, 0,1)=='2'){
        }else{
          $trip_id    =$trip->id;
          $availableDays=$trip->available_day;
          $bus_no       =BusOccurance::wheretrip_id($trip->id)->pluck('bus_no');
          $bus_no       =$bus_no !=null ? $bus_no : "-";
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
          $today        =$today;
          $year         =date('Y');
          $checkdate      =date('d', strtotime($today));
          $month        = date("Y-m-d", strtotime("+1 month", strtotime($today)));
          $currentMonth     =date("m");
          $nextMonth      =date("m", strtotime($month));
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
                $trip_id      =$objtrip->id;
                $operator_id    =$objtrip->operator_id;
                $from       =$objtrip->from;
                $to         =$objtrip->to;
                $class_id     =$objtrip->class_id;
                $price        =$objtrip->price;
                $foreign_price    =$objtrip->foreign_price == 0 ? $objtrip->price : $objtrip->foreign_price;
                $commission     =$objtrip->commission;
                $time         =$objtrip->time;
                $seat_plan_id   =$objtrip->seat_plan_id ? $objtrip->seat_plan_id : 1;

                $today=$today;
                $count_days=count($customdays);
                $i=1;
                $j=0;
                // return Response::json($available_day);
                foreach ($customdays as $customdaydate) {
                  if($i<$count_days){
                    $objbusoccurance=new BusOccurance();
                    $objbusoccurance->seat_plan_id  =$seat_plan_id;
                    $objbusoccurance->bus_no    =$bus_no;
                    $objbusoccurance->from      =$from;
                    $objbusoccurance->to      =$to;
                    $objbusoccurance->classes   =$class_id;
                    $objbusoccurance->departure_date=$customdaydate;
                    $objbusoccurance->departure_time=$time;
                    $objbusoccurance->price     =$price;
                    $objbusoccurance->foreign_price =$foreign_price;
                    $objbusoccurance->commission  =$commission;
                    $objbusoccurance->operator_id =$operator_id;
                    $objbusoccurance->trip_id   =$trip_id;
                    $objbusoccurance->save();
                    $j++;
                  }
                  $i++;
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

  }

  
  /**
   * User List
   *
   * @return response
   */
  public function index(){
    $agopt_ids=$this->myGlob->agopt_ids;
    $user_ids=array();
    $user_id =$user_id_group= array();
    if(Auth::user()->role==9){
      $user_id=OperatorGroup::lists('user_id');
      $user_id_group=OperatorGroupUser::lists('user_id');
    }else{
      $user_id=OperatorGroup::whereoperator_id($this->myGlob->operator_id)->lists('user_id');
      $user_id_group=OperatorGroupUser::whereoperator_id($this->myGlob->operator_id)->lists('user_id');
    }
    $user_ids=array_unique(array_merge($user_id, $user_id_group));
    $response=array();

    if($user_ids){
      $response=User::wherein('id', $user_ids)->get(); 
      if($response){
        foreach ($response as $key => $rows) {
          if($rows->role==8){
            $role="Manager / Operator";
          }elseif($rows->role==4){
            $role="Supervisor";
          }elseif($rows->role==3){
            $role="Agent";
          }else{
            $role="Staff";
          }
          
          $operatorgroup_id=OperatorGroupUser::whereuser_id($rows->id)->pluck('operatorgroup_id');
          if($operatorgroup_id){
            $groupuser_id=OperatorGroup::whereid($operatorgroup_id)->pluck('user_id');
            $operator_groupname=User::whereid($groupuser_id)->pluck('name');
          }else{
            $operator_groupname="-";
          }

          $response[$key]['operator_groupname']=$operator_groupname;
          $operator_id=Operator::whereuser_id($rows->id)->pluck('id');
          $response[$key]['operator_id']=$operator_id ? $operator_id : '0';
          $response[$key]['header']=$role;
        }
      } 
    }
    // return Response::json($response);

    return View::make('user.list', array('response'=>$response));
  }

  /**
   * Create New User
   *
   * @return response
   */
  public function create(){
    $response=array();
    $response['role']=array('2'=>"Staff", '3'=>"Agent", '4'=>"Supervisor", '8'=>"Manager");
    $agopt_ids=$this->myGlob->agopt_ids;
    if($agopt_ids){
      $operator_group=OperatorGroup::wherein('operator_id',$agopt_ids)
                                  ->with(array(
                                    'user'=>function($q){ $q->addSelect(array('id','name'));}
                                  ))->get(array('id','user_id'));
    }else{
      $operator_group=OperatorGroup::whereoperator_id($this->myGlob->operator_id)
                                  ->with(array(
                                    'user'=>function($q){ $q->addSelect(array('id','name'));}
                                  ))->get(array('id','user_id'));
    }
    $response['operator_group']=$operator_group;
    $agentgroups=array();
    if(Auth::user()->role==9 || Auth::user()->role==8){
      $agentgroups=AgentGroup::all();
    }
    $response['agentgroup']=$agentgroups;
    return View::make('user.add', array('response'=>$response));
  }

  /**
   * Store User Info
   *
   * @return response
   */
  public function store(){
    $name         =Input::get('name');
    $email        =Input::get('email');
    $password     =Input::get('password');
    $role         =Input::get('role');
    $type         =Input::get('type');
    $agentgroup_id=Input::get('agentgroup_id');
    if($role==3){
      $type ="Agent";
    }
    $group_user   =Input::get('group_user');
    $groupuser_id =Input::get('groupuser_id');

    $checkexisting=User::whereemail($email)->first();
    if($checkexisting){
      $message['status']=0;
      $message['info']="Email is already used.";
      return Redirect::to('user-list?'.$this->myGlob->access_token)->with('message',$message);
    }
    $objuser            =new User();
    $objuser->name      =$name;
    $objuser->email     =$email;
    $objuser->password  =Hash::make($password);
    $objuser->role      =$role;
    $objuser->type      =$type;
    $objuser->save();

    $user_id            =$objuser->id;
    if($group_user=="group"){
      $objoperatorgroup     =new OperatorGroup();
      $objoperatorgroup->operator_id  =$this->myGlob->operator_id ? $this->myGlob->operator_id : 0;
      $objoperatorgroup->user_id  =$user_id;
      $objoperatorgroup->save();
    }else{
      $objgroupuser                   =new OperatorGroupUser();
      $objgroupuser->operator_id      =$this->myGlob->operator_id;
      $objgroupuser->operatorgroup_id =$groupuser_id;
      $objgroupuser->user_id          =$user_id;
      $objgroupuser->save();
    }
    if($agentgroup_id){
      AgentGroup::whereid($agentgroup_id)->update(array('user_id'=>$user_id));
    }

    $message['status']=1;
    $message['info']="Successfully save one user.";
    return Redirect::to('user-list?access_token='.Auth::user()->access_token)->with('message', $message);
  }

  /**
   * Edit User Info
   *
   * @return response
   */
  public function edit($id){
    $response=array();
    $response['role']=array('2'=>"Staff", '3'=>"Agent", '4'=>"Supervisor", '8'=>"Manager");

    $agopt_ids =$this->myGlob->agopt_ids;
    if($agopt_ids){
      $operator_group=OperatorGroup::wherein('operator_id',$agopt_ids)
                                  ->with(array(
                                    'user'=>function($q){ $q->addSelect(array('id','name'));}
                                  ))->get(array('id','user_id'));
    }else{
      $operator_group=OperatorGroup::whereoperator_id($this->myGlob->operator_id)
                                  ->with(array(
                                    'user'=>function($q){ $q->addSelect(array('id','name'));}
                                  ))->get(array('id','user_id'));
    }
    $response['operator_group']=$operator_group;
    
    $user_info=User::find($id);
    $checkgroup=OperatorGroup::whereuser_id($id)->first();
    if($checkgroup){
      $user_info['group']="group";
      $user_info['undergroup_id']=0;
    }else{
      $user_info['group']="undergroup";
      $checkgroupuser=OperatorGroupUser::whereuser_id($id)->first();
      
      $user_info['undergroup_id']=0;
      if($checkgroupuser)
        $user_info['undergroup_id']=$checkgroupuser->operatorgroup_id;
    }

    $agentgroups=array();
    if(Auth::user()->role==9 || Auth::user()->role==8){
      $agentgroups=AgentGroup::all();
    }
    $response['agentgroup']=$agentgroups;



    return View::make('user.edit', array('response'=>$response, 'user_info'=>$user_info));
  }

  /**
   * Store User Info
   *
   * @return response
   */
  public function update($id){
    $name         =Input::get('name');
    $email        =Input::get('email');
    $password     =Input::get('password');
    $role         =Input::get('role');
    $type         =Input::get('type');
    if($role==3){
      $type ="Agent";
    }
    $group_user   =Input::get('group_user');
    $groupuser_id =Input::get('groupuser_id');

    $checkexisting=User::where('id','!=',$id)->whereemail($email)->first();
    if($checkexisting){
      $message['status']=0;
      $message['info']="Email is already used.";
      return Redirect::to('user-list?'.$this->myGlob->access_token)->with('message',$message);
    }
    $objuser            =User::find($id);
    $objuser->name      =$name;
    $objuser->email     =$email;
    if($password)
      $objuser->password  =Hash::make($password);
    $objuser->role      =$role;
    $objuser->type      =$type;
    $objuser->save();

    $user_id            =$id;
    if($group_user=="group"){
      OperatorGroupUser::whereuser_id($id)->delete();
      $objoperatorgroup     =new OperatorGroup();
      $objoperatorgroup->operator_id  =$this->myGlob->operator_id;
      $objoperatorgroup->user_id  =$user_id;
      $objoperatorgroup->save();
    }else{
      OperatorGroup::whereuser_id($id)->delete();
      $checkgroupuser=OperatorGroupUser::whereuser_id($id)->first();
      if($checkgroupuser){
        $checkgroupuser->operatorgroup_id =$groupuser_id;
        $checkgroupuser->user_id          =$user_id;
        $checkgroupuser->update();
      }else{
        $objgroupuser                   =new OperatorGroupUser();
        $objgroupuser->operator_id      =$this->myGlob->operator_id;
        $objgroupuser->operatorgroup_id =$groupuser_id;
        $objgroupuser->user_id          =$user_id;
        $objgroupuser->save();
      }
      
    }

    $message['status']=1;
    $message['info']="Successfully save one user.";
    return Redirect::to('user-list?'.$this->myGlob->access_token)->with('message', $message);
  }
  

  /**
   * Delete User info
   *
   * @return response
   */
  public function destroy($id){
    User::whereid($id)->delete();
    $message['status']=1;
    $message['info']="Successfully delete one record.";
    return Redirect::to('user-list?'.$this->myGlob->access_token)->with('message', $message);
  } 

}