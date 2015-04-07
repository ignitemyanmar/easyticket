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
      
      $http = new Uri('http://192.168.1.101');
      $params = array(
          'client_id'     => '721689',
          'client_secret' => 'onlineSale@EasyTickeTadmiM',
          'grant_type'    => 'password',
          'scope'         => 'sale,booking',
          'state'         => '123456789',
          'username'      => MCrypt::encrypt($username),
          'password'      => MCrypt::encrypt($password)
      );
      $params = MCrypt::encrypt(json_encode($params));
      $response = $http->post('/oauth/access_token', array('param' => $params) );
      $auth = json_decode($response);
      if($http->status == 200){
        $user = array(
                'email' => $username,
                'password' => $password
              );
        if(Auth::attempt($user)){
          if(Auth::check()){
            if(Auth::user()->role >= 2){
              if(Auth::user()->type == "operator"){
                  return "/all-trips?access_token=".Auth::user()->access_token;
              }
              if(Auth::user()->type == "admin" || Auth::user()->type == "agent"){
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
      
      $http = new Uri('http://192.168.1.101');
      $params = array(
          'client_id'     => '721685',
          'client_secret' => 'IgniteAdmin721685',
          'grant_type'    => 'password',
          'scope'         => 'admin, sale, booking',
          'state'         => '123456789',
          'username'      => MCrypt::encrypt($username),
          'password'      => MCrypt::encrypt($password)
      );
      $params = MCrypt::encrypt(json_encode($params));
      $response = $http->post('/oauth/access_token', array('param' => $params) );
      $auth = json_decode($response);
      if($http->status == 200){
        $user = array(
                'email' => $username,
                'password' => $password
              );
        if(Auth::attempt($user)){
          if(Auth::check() && Auth::user()->role >= 2){
              $objuser=User::find(Auth::user()->id);
              $objuser->access_token=$auth->access_token;
              $objuser->update();

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