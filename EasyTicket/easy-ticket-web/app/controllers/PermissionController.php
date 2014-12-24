<?php

class PermissionController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$userrole=array('2','4','8');//2 is staffs, 4 is supervisors, 8 is Manager
		$response=array();
		$response=UserPermission::wherein('role',$userrole)->get();
		if($response){
			foreach ($response as $key => $permission) {
				if($permission->role==2){
					$role="Staff";
				}elseif($permission->role==4){
					$role="Supervisors";
				}else{
					$role="Manager";
				}
				$response[$key]['header']=$role. "<div style='float:right'><a href='permission-edit/".$permission->role."'   class='btn blue-stripe delete'>ျပင္ရန္</a></div>";
			}
		}
		return View::make('permission.list', array('response'=>$response));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$response['role']=array("2"=>"Staff","4"=>"Supervisors", "8"=>"Manager");
		$response['menu']=array(
								"ေန႔စဥ္ အေရာင္းစာရင္း",
								"ကားခ်ဳပ္အေရာင္းစာရင္း",
								"ခရီးစဥ္အလုိက္ အေရာင္းစာရင္း",
								"အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္း",
								"ၾကိဳတင္မွာယူထားေသာ စာရင္းမ်ား",
								"အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား",
								"အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား",
								"အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား",
								"အေရာင္းကုိယ္စားလွယ္မ်ား",
								"ေရာင္းျပီးလက္မွတ္မ်ား ဖ်က္ရန္",
								"ျမိဳ႕မ်ား",
								"ကားအမ်ိဳးအစားမ်ား",
								"ခုံအေနအထားမ်ား",
								"ခရီးစဥ္မ်ား",
								"ခုံနံပါတ္ အစီအစဥ္",
								"လုပ္ပုိင္ခြင့္မ်ား",
								"User List"
								);
		return View::make('permission.add', array('response'=>$response));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$role=Input::get('role');
		$premissions=Input::get('permission');

		if(count($premissions)>0){
			foreach ($premissions as $key => $permission) {
				$objuserpermission=new UserPermission();
				$objuserpermission->role=$role;
				$objuserpermission->menu=$permission;
				$objuserpermission->save();
			}
			$message['status']=1;
			$message['info']="Successfully save.";
			return Redirect::to('permission')->with('message',$message);
		}
			
		return Redirect::to('permission');	
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($role)
	{
		$response['role']=array("2"=>"Staff","4"=>"Supervisors", "8"=>"Manager");
		$response['menu']=array(
								"ေန႔စဥ္ အေရာင္းစာရင္း",
								"ကားခ်ဳပ္အေရာင္းစာရင္း",
								"ခရီးစဥ္အလုိက္ အေရာင္းစာရင္း",
								"အေရာင္းကုိယ္စားလွယ္ႏွင့္ အေရာင္းစာရင္း",
								"ၾကိဳတင္မွာယူထားေသာ စာရင္းမ်ား",
								"အေရာင္းရဆုံး ခရီးစဥ္ စာရင္းမ်ား",
								"အေရာင္းကုိယ္စားလွယ္ ႏွင့္ အေၾကြးစာရင္းမ်ား",
								"အေရာင္းကုိယ္စားလွယ္ အုပ္စုမ်ား",
								"အေရာင္းကုိယ္စားလွယ္မ်ား",
								"ေရာင္းျပီးလက္မွတ္မ်ား ဖ်က္ရန္",
								"ျမိဳ႕မ်ား",
								"ကားအမ်ိဳးအစားမ်ား",
								"ခုံအေနအထားမ်ား",
								"ခရီးစဥ္မ်ား",
								"ခုံနံပါတ္ အစီအစဥ္",
								"လုပ္ပုိင္ခြင့္မ်ား",
								"User List"
								);

		$permissions=UserPermission::whererole($role)->get();
		return View::make('permission.edit', array('response'=>$response,'permissions'=>$permissions,'role'=>$role));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($role)
	{
		UserPermission::whererole($role)->delete();
		$role=Input::get('role');
		$premissions=Input::get('permission');

		if(count($premissions)>0){
			foreach ($premissions as $key => $permission) {
				$objuserpermission=new UserPermission();
				$objuserpermission->role=$role;
				$objuserpermission->menu=$permission;
				$objuserpermission->save();
			}
			$message['status']=1;
			$message['info']=" Successfully save.";
			return Redirect::to('permission')->with('message',$message);
		}
			
		return Redirect::to('permission');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		UserPermission::whereid($id)->delete();
		$message['status']=1;
		$message['info']="Successfully delete one record.";
		return Redirect::to('permission')->with('message',$message);
	}

	

}