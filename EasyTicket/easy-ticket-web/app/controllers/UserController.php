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

	public function getLogin()
    {
      return View::make('admin.login');
    }

    public function postLogin(){
      $user = array(
              'email' => Input::get('username'),
              'password' => Input::get('password')
          );
          
          	if (Auth::attempt($user)) {
              $id=Auth::user()->id;
          		$type=Auth::user()->type;
              if($type=="operator"){
                $operator_id=Operator::whereuser_id($id)->pluck('id');
                return "report/dailycarandadvancesale?operator_id=".$operator_id;
              }elseif($type=="agent"){
                $agent_id=Agent::whereuser_id($id)->pluck('id');
                return "operators/agent/".$agent_id;
              }else{
                
              }
          	}else{
          		return 'Invalid email and password!';
          	}

    	    // authentication failure! lets go back to the login page
	        return Redirect::to('easyticket-admin')
              ->with('flash_error', 'Your username/password combination was incorrect.')
              ->withInput();
    }

    public function getLogout(){
      Auth::logout();

          return Redirect::route('easyticket-admin')
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

}