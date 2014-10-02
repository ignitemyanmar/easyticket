<?php
class OperatorController extends BaseController
{
	public function getAddOperator()
	{	
		return View::make('operator.add');
	}

	public function postAddOperator(){
        $name           =Input::get('name');
        $email          =Input::get('email');
        $password       =Input::get('password');
        $address        =Input::get('address');
        $phone          =Input::get('phone');
        $response       =array();
        $check_exiting  =User::whereemail($email)->first();
        if($check_exiting){
            $response['message']="Email is already used.";
        }

        if(!$name || !$address || !$phone || !$email || !$password){
            $response['message']="Required fields are name, address and phone.";
        }

        $objoperator        =new Operator();
        $checkoperator      =Operator::wherename($name)->whereaddress($address)->first();
        if($checkoperator){
            $response['message']='This operator is already exit';
        }

        $user=new User();
        $user->name=$name;
        $user->email=$email;
        $user->password=Hash::make($password);
        $user->type="operator";
        $user->save();
        $user_id=$user->id;

        $objoperator->name      =$name;
        $objoperator->address   =$address;
        $objoperator->phone     =$phone;
        $objoperator->user_id       =$user_id;
        $objoperator->save();

        $objoauthclient =new OauthClients();
        $client_id=rand(9000000,6);
        $clientname=$name ."(operator)";
        $secret =Hash::make($clientname);
        $objoauthclient->id=$client_id;
        $objoauthclient->secret=$secret;
        $objoauthclient->name=$clientname;
        $objoauthclient->save();
        return Redirect::to('operatorlist');
        
    }

	public function showOperatorlist()
	{
		$obj_operator = Operator::paginate(12);
        $i=0;
        if($obj_operator){
            foreach ($obj_operator as $row) {
                $obj_operator[$i]=$row;
                    $login_info=array();
                    $user_id=$row->user_id;
                    $objuser=User::whereid($user_id)->first();
                    if($objuser){
                        $login_info['username']=$objuser->email;
                        $login_info['scope']='operator';    
                    }else{
                        $login_info['username']='-';
                        $login_info['scope']='-';  
                    }
                    
                    $operator_name=$row->name ."(operator)";
                    $objoauthclient=OauthClients::wherename($operator_name)->first();
                    if($objoauthclient){
                        $login_info['client_secret']=$objoauthclient->secret;
                        $login_info['client_id']=$objoauthclient->id;
                    }else{
                        $login_info['client_secret']='-';
                        $login_info['client_id']='-';
                    }
                    
                    $obj_operator[$i]['login_info']=$login_info;
                $i++;
            }
        }
		$response = $obj_operator;
		$totalcount = Operator::count();

		return View::make('operator.list',array('response'=>$response,'totalcount'=>$totalcount));
	}
	
    public function getEditOperator($id)
    {
    	return View::make('operator.edit')->with('operator',Operator::find($id));
    }
     
    public function postEditOperator($id)
    {
    	$operator = new Operator();
    	$affectedRows= Operator::where('id','=',$id)->update(array('name'=>Input::get('name'),
    															'phone'=>Input::get('phone'),
    															'address'=>Input::get('address')));
    	return Redirect::to('operatorlist');
    }
  
    public function getDeleteOperator($id)
    {

        $check=SaleOrder::whereoperator_id($id)->lists('id');
        if($check){
            return Redirect::to('operatorlist')->with('message',"You can't delet this operator.There is transaction with this operator.");
        }
        $objoperator=Operator::whereid($id)->first();
    	$affectedRows1 = Operator::where('id','=',$id)->delete();
        if($objoperator){
            User::whereid($objoperator->user_id)->delete();
            $operator_name=$objoperator->name;
            OauthClients::wherename($objoperator->user_id)->delete();
        }
    	return Redirect::to('operatorlist')->with('message',"One record has been delete.");
    }
    
    public function postdelOperator()
    {
    	$todeleterecorts = Input::get('recordstoDelete');
    	if(count($todeleterecorts)==0)
    	{
    		return Redirect::to("/operatorlist");
    	}
    	foreach($todeleterecorts as $recid)
    	{
    		$result = Operator::where('id','=',$recid)->delete();
    	}
    	return Redirect::to("/operatorlist");
    }
    
    public function postSearchOperator()
    {
    	$keyword = Input::get('keyword');
    	$response=$operator = Operator::where('name','LIKE','%'.$keyword.'%')
    					->orwhere('address','LIKE','%'.$keyword.'%')
    					->orderBy('id','DESC')->paginate(10);
    					
        $allOperator = Operator::all();
        $totalcount = count($allOperator);
    		return View::make('operator.list')->with('operator',$operator)->with('totalcount',$totalcount)->with('response',$response);
    }


}