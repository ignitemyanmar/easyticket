<?php
	Route::get('oldaggroup', function(){
		$date=date('Y-m-d');
		$response=Agent::where('updated_at','>=',$date)->lists('agentgroup_id');
		// return Response::json($response);
		AgentGroup::whereNotIn('id',$response)->delete();
		return Response::json($response);
	});
	//agent create for multi first time
	Route::get('agentins', function(){
		$agents=array(
			'ကုိေက်ာ္လြင္ (လွဳိင္)',
			'ကုိေက်ာ္လြင္ (ေျမနီကုန္း)',
		);
		$i=0;
		$j=0;
		$duplicate='<br>';
		foreach ($agents as $row) {
			$check=Agent::wherename($row)->first();
			if($check){
				$j++;
				$duplicate .=$row.'<br>';
			}else{
				$objagent=new Agent();
				$objagent->name=$row;
				$objagent->save();
				$i++;
			}
		}
		dd($i .' records has been insert. ' . $j .' records are duplicate. ' . $duplicate);
	});

	//to auto insert agent commission table 
	Route::get('saves', function(){
		$i=0;
		$objorders=SaleOrder::orderBy('id','asc')->get();
		if($objorders){
			foreach ($objorders as $rows) {
				$objsaleorder=SaleOrder::find($rows->id);
				$objsaleitems=SaleItem::whereorder_id($rows->id)->first();
				if($objsaleorder && $objsaleitems){
					$objagent_commission=AgentCommission::whereagent_id($objsaleitems->agent_id)->wheretrip_id($objsaleitems->trip_id)->first();
					$commission=0;
					if($objagent_commission){
						if($objagent_commission->commission_id==1){
							$commission=$objagent_commission->commission;
						}else{
							if($nationality=='local'){
			    				$commission +=($objsaleitems->price * $objagent_commission->commission) / 100;
			    			}else{
			    				$commission +=($objsaleitems->foreign_price * $objagent_commission->commission) / 100;
			    			}
						}
					}
					$tickets=SaleItem::whereorder_id($rows->id)->count('id');
					$commission=$commission * $tickets;

					if($commission==0){
						$tripcommission=Trip::whereid($objsaleitems->trip_id)->pluck('commission');
						$commission= $tripcommission *  $tickets;
					}
					$totalamount=0;

					if($rows->nationality=='local'){
						$totalamount +=$objsaleitems->price * $tickets;
					}else{
						$totalamount +=$objsaleitems->foreign_price * $tickets;	
					}
				}

				//Payment Transaction
	    		if($rows->booking == 0){
	    			$total_amount 				= $objsaleorder->total_amount - $objsaleorder->agent_commission;
	    			$check=AgentDeposit::whereorder_ids('["'.$objsaleorder->id.'"]')->first();
	    			
	    			if(!$check){
		    			$objdepositpayment_trans	= new AgentDeposit();
		    			$agentgroup_id=Agent::whereid($objsaleorder->agent_id)->pluck('agentgroup_id');
			    		$objdepositpayment_trans->agentgroup_id 	=$agentgroup_id;
			    		$objdepositpayment_trans->agent_id 	 		= $objsaleorder->agent_id;
			    		$objdepositpayment_trans->operator_id		= $objsaleorder->operator_id;
			    		$objdepositpayment_trans->total_ticket_amt	= $total_amount;
			    		$today 										= $objsaleorder->orderdate;
			    		$objdepositpayment_trans->pay_date			= $today;
			    		$objdepositpayment_trans->order_ids			= '["'.$objsaleorder->id.'"]';

			    		$objdepositpayment_trans->payment 			= 0;
			    		$agentdeposit 				= AgentDeposit::whereagent_id($objsaleorder->agent_id)->whereoperator_id($objsaleorder->operator_id)->orderBy('id','desc')->first();
			    		if($agentdeposit){
			    			$objdepositpayment_trans->deposit 		= $agentdeposit->balance;
			    			$objdepositpayment_trans->balance 		= $agentdeposit->balance - $total_amount;
			    		}else{
			    			$objdepositpayment_trans->deposit 		= 0;
			    			$objdepositpayment_trans->balance 		= 0 - $total_amount;
			    		}  		
			    		$objdepositpayment_trans->debit 			= 0;
			    		$objdepositpayment_trans->save();
			    	}else{
			    		$i++;
			    	}
	    		}	
			}
			dd('Already having records are ' . $i);
		}else{
			dd('There is no records.');
		}
	});

	//agent group for has group agent
	Route::get('hasgroupagent', function(){
		$agent=Input::get('agent');
		$objagentsids=Agent::where('name','like',$agent.'%')->lists('id');
		// return Response::json($objagentsids);
		if($objagentsids){
			$objagentgroup=new AgentGroup();
			$objagentgroup->operator_id=10;
			$objagentgroup->name=$agent;
			$objagentgroup->save();
				Agent::wherein('id',$objagentsids)->update(array('agentgroup_id'=>$objagentgroup->id));
		}
		dd(count($objagentsids). ' records has been created.');

	});

	//for non group agents
	Route::get('agntgrp', function(){
		$objagent_ids=Agent::whereagentgroup_id(0)->lists('id');
		$k=0;$i=0;
		if($objagent_ids){
			foreach ($objagent_ids as $id) {
				$agentgroup=new AgentGroup();
				$objagent=Agent::whereid($id)->first();
				$check=AgentGroup::wherename($objagent->name)->first();
				if($check){
					$k++;
				}else{
					$agentgroup->name 		=$objagent->name;
					$agentgroup->operator_id=$objagent->operator_id ? $objagent->operator_id : 0;
					$agentgroup->save();
					$objagent->agentgroup_id=$agentgroup->id;
					$objagent->update();
					$i++;
				}
				
			}
		}
		dd('finished! create records is '.$i.' duplicate records is '.$k);
	});
	
	// Route::get('tripautocreate',       				'ApiController@tripautocreate');

	Route::get('operators/agent/{id}',				'ReportController@getOperatorsbyAgent');
	Route::get('agents/operator/{id}',				'ReportController@getAgentsbyOperator');
	Route::get('triplist/{id}/agent',				'ReportController@getTripslistreportOperator');
	Route::post('saleticket',						'ApiController@postSale');

	Route::filter('guest',              'UserController@filterguest');
	Route::filter('auth',               'UserController@filterauth');
	Route::get('register',              'UserController@adduser1');
	Route::get('user',              	'UserController@adduser1');
	
	Route::post('users-register', 		'UserController@postUserRegister');

	Route::post('user-login',          		'ApiController@postLogin');
	Route::post('user-register',          	'ApiController@postUserRegister');
	Route::get('backup_saleorder', 			'SyncDatabaseController@backUpAll');
	Route::post('import_saleorder', 		'SyncDatabaseController@importBackup');
	Route::post('oauth/access_token', function()
	{
	    $response = AuthorizationServer::performAccessTokenFlow();
		if($response->getStatusCode() === 200) {
			$response = json_decode($response->getContent());
			$input = json_decode(MCrypt::decrypt(Input::get('param')));
			$user = User::whereemail(MCrypt::decrypt($input->username))->first();
            $user->access_token = $response->access_token;
            $user->ip_address   = $_SERVER['REMOTE_ADDR'];
            $user->update();
			$user = array(
	              'email' => MCrypt::decrypt($input->username),
	              'password' => MCrypt::decrypt($input->password)
	          	);
	      	if(Auth::attempt($user)) {
	          	if(Auth::user()->type == "operator"){
	          		$operatorgroup = OperatorGroup::whereuser_id(Auth::user()->id)->first();
					if($operatorgroup){
						$operator_id=$operatorgroup->operator_id;
						$response->user['id'] 				= $operator_id;
						$response->user['operatorgroup_id'] = $operatorgroup->id;
						$response->user['user_id'] 			= Auth::user()->id;
						$response->user['name'] 			= Auth::user()->name;
						$response->user['type'] 			= Auth::user()->type;
						$response->user['role'] 			= Auth::user()->role;
						return Response::json($response);
					}else{
						$groupUser = OperatorGroupUser::whereuser_id(Auth::user()->id)->first();
						$response->user['id'] 				= isset($groupUser->operator_id) ? $groupUser->operator_id : Operator::whereuser_id(Auth::user()->id)->pluck('id');
						$response->user['operatorgroup_id'] = isset($groupUser->operatorgroup_id) ? $groupUser->operatorgroup_id : 0;
						$response->user['user_id'] 			= Auth::user()->id;
						$response->user['name'] 			= Auth::user()->name;
						$response->user['type'] 			= Auth::user()->type;
						$response->user['role'] 			= Auth::user()->role;
						return Response::json($response);
					}
	          	}
	          	if(Auth::user()->type == "admin"){
	          		$admin = User::whereid(Auth::user()->id)->first();
					$response->user['id'] 				= $admin->id;
					$response->user['operatorgroup_id'] = 0;
					$response->user['name'] 			= $admin->name;
					$response->user['type'] 			= Auth::user()->type;
					$response->user['role'] 			= Auth::user()->role;
					return Response::json($response);
	          	}
	          	if(Auth::user()->type == "agent"){
	          		$agent = AgentGroup::whereuser_id(Auth::user()->id)->first();
					if($agent){
						$response->user['id'] 				= $agent->user_id;
						$response->user['operatorgroup_id'] = 0;
						$response->user['name'] 			= $agent->name;
						$response->user['type'] 			= Auth::user()->type;	
						$response->user['role'] 			= Auth::user()->role;
						return Response::json($response);
					}else{
						$message['error']="invalid_client.";
						$message['error_description']="Client authentication failed.";
						return Response::json($message);
					}
					
	          	}
	      	}	 
			return $response;
		}else{
			return $response;
		}
	});

	// Admin User Login / Logout Routes;
		Route::get('easyticket-admin',      'UserController@getLogin');
		Route::post('administration',       'UserController@postLogin');
		Route::get('users-logout',          array('as'=>'logout','uses'=>'UserController@getLogout'))->before('auth');   
	// Admin Routes ---
		Route::group(array('before' => 'oauth:admin'), function(){
			Route::get('/staff/salereport', 				'StaffReportController@index');
			Route::get('staff/salebytrip/{date}',			'StaffReportController@detail');
			
			//Sync
			Route::get('/client/sync', 						'SyncDatabaseController@index');

			//operator report
			Route::get('report/operator/trip/dateranges',	'ReportController@getTripslistreportOperator');
			Route::get('triplist/{date}/daily',				'ReportController@getTripsSellingReportbyDaily');

		/**
		 * New / Update Existing by SMK
		 */
			Route::get('report/operator/trip/daterangesbycode',   'ReportController@getTripslistreportOperatorCode');
			Route::get('triplist/{date}/agentdetailreportbycode', 'ReportController@getTripsSellingReportbyDailyCode');

			Route::get('report/bytrip',						'ReportController@getReportbyTrip');
			Route::get('report/{date}/tripdetail',			'ReportController@getReportbyTripDetail');

			//agent report
			Route::get('report/agent/trip/dateranges',		'ReportController@getTripslistdaterangeAgent');
			Route::get('agent/triplist/{date}/daily',		'ReportController@getTripsSellingReportbyDailyAgent');
			Route::get('triplist/{busid}/busid',			'ReportController@getTripsSellingReportbyBusid');
			Route::get('triplist/agentreport/{id}/dateragne',	'ReportController@getTripslistreport');
			
			//Trip Date Reports
			Route::get('report/dailybydeparturedate',			'ReportController@getDailyReportByDepartureDate');
			Route::get('report/dailybydeparturedate/search',	'ReportController@getDailyReportByDepartureDatesearch');
			Route::get('report/dailybydeparturedate/busid',		'ReportController@getDailyReportbydepartdateandbusid');
			Route::get('report/dailybydeparturedate/detail',	'ReportController@getDailyReportbydepartdatedetail');

			Route::get('report/dailycarandadvancesale',			'ReportController@getDailyReportforTrip');
			Route::get('report/dailycarandadvancesale/search',	'ReportController@getDailyReportforTrip');
			Route::get('report/dailycarandadvancesale/time',	'ReportController@getDailyReportforTripFilterbyTime');
			Route::get('report/dailycarandadvancesale/date',	'ReportController@getDailyAdvancedByFilterDate');
			Route::get('report/dailycarandadvancesale/detail',	'ReportController@getDetailDailyReportforBus');

			Route::get('report/seatoccupiedbybus',				'ReportController@getSeatOccupancyReport');
			Route::get('report/seatoccupiedbybus/search',		'ReportController@getSeatOccupancyReport');
			Route::get('report/seatoccupiedbybus/detail',		'ReportController@getSeatOccupancydetail');

			Route::post('report/customers/update',				'ReportController@postCustomerInfoUpdate');
			
			Route::get('report/bestseller/trip',				'BestSellerController@trips');
			Route::get('report/bestseller/tripdetail',			'BestSellerController@tripdetail');

			Route::get('report/bestseller/time',				'BestSellerController@times');
			Route::get('report/bestseller/timedetail',			'BestSellerController@timedetail');

			Route::get('report/bestseller/agents',				'BestSellerController@agents');
			Route::get('report/bestseller/agentdetail',			'BestSellerController@agentdetail');
			
			Route::get('report/booking',						'BookingController@getBookingList');
			Route::get('report/booking/{id}',					'BookingController@getBookingDeleteOrComfirm');
			Route::get('report/booking/delete/{id}',			'BookingController@getBookingDelete');
			Route::get('report/booking/comfirm/{id}',			'BookingController@getBookingComfirm');


			Route::get('report/agentscredit',						'CreditController@index');
			Route::get('report/agentscredits/search',				'CreditController@index');
			Route::get('report/agentcreditlist/group/{id}',			'CreditController@second');
			Route::get('report/agentcreditlist/paymentdetail/{id}',	'CreditController@detail');
			
			Route::get('report/agentcredit/{id}',				'AgentCreditController@getActionForm');
			Route::get('report/agentcreditsales/{id}',			'AgentCreditController@getAgentCreditSaleList');

			Route::get('report/agentpaymentslist/{id}',			'AgentCreditController@getAgentPaymentSaleList');
			
			Route::post('report/agentdeposit',					'AgentCreditController@postAgentDeposit');
			Route::post('report/agentoldcredit',				'AgentCreditController@postAgentOldCredit');

			Route::post('report/agentcommission',				'AgentCreditController@postAgentCommission');
			Route::get('report/agentcommission/{id}',			'AgentCreditController@getAgentCommission');
			
			Route::post('report/agentcreditspayment',			'AgentCreditController@postCreditPayment');
			Route::get('report/paymenttransaction/{id}',		'AgentCreditController@getPaymentTransaction');

			Route::get('report/seatoccupiedbytrip',				'ReportController@getSeatOccupancyBytrip');

			Route::get('agents/create',			'AgentController@getAddagent');
			Route::post('addagent',				'AgentController@postAddagent');
			Route::get('agentlist',				'AgentController@showAgentList');
			Route::get('agent-update/{id}',  	'AgentController@getEditAgent');
			Route::post('updateagent/{id}',  	'AgentController@postEditAgent');
			Route::post('delagent',          	'AgentController@postdelBusClass');
			Route::get('deleteagent/{id}',   	'AgentController@getDeleteAgent');
			Route::post('searchagent',       	'AgentController@postSearchAgent');

			Route::get('agent-salelist/{id}',	'AgentController@agentSaleList');
			Route::post('order/changeagent',	'AgentController@postagentchange');


			Route::get('agentgroup/create',			'AgentGroupController@getAddagentgroup');
			Route::get('agentgroup-actions/{id}',	'AgentGroupController@getAgentGroupActions');
			
			Route::post('addagentgroup',			'AgentGroupController@postAddagentgroup');
			Route::get('agentgrouplist',	    	'AgentGroupController@showAgentgroupList');
			Route::get('agentgroupchildlist/{id}',	'AgentGroupController@AgentGroupChildList');
			Route::get('deleteagentgroup',	    	'AgentGroupController@destroy');
			
			Route::get('agentbranches/{id}',		'ReportController@AgentBranches');

			Route::get('agentgroup-update/{id}',  	'AgentGroupController@getEditAgentgroup');
			Route::post('updateagentgroup/{id}',  	'AgentGroupController@postEditAgentgroup');
			Route::post('delagentgroup',          	'AgentGroupController@postdelAgentgroup');
			Route::get('deleteagentgroup/{id}',   	'AgentGroupController@getDeleteAgentgroup');
			Route::post('searchagentgroup',       	'AgentGroupController@postSearchAgentgroup');

			Route::get('trip/create', 			'TripController@getAddtrip');
			Route::get('trip/extend/{id}',      'TripController@getExtendTrip');
			Route::post('trip/extend/{id}',     'TripController@postExtendTrip');

			Route::get('trip/editextend/{id}',  'TripController@getEditExtendTrip');
			Route::post('trip/editextend/{id}', 'TripController@postEditExtendTrip');

			Route::get('trip/deleteextend/{id}',  'TripController@getDeleteExtendTrip');

			Route::post('addtrip',				'TripController@postAddtrip');
			// Route::get('triplist',				'TripController@showTriplist');
			Route::get('trip-update/{id}', 		'TripController@getEditTrip');
			Route::post('updatetrip/{id}', 		'TripController@postEditTrip');
			Route::post('deltrip',          	'TripController@postdelTrip');
			Route::get('deletetrip/{id}',   	'TripController@getDeleteTrip');
			Route::post('searchtrip',      		'TripController@postSearchTrip');

			Route::get('busclass/create',		'BusClassController@getAddBusClasses');
			Route::post('addbusclass',			'BusClassController@postAddBusClasses');
			Route::get('busclasslist',			'BusClassController@showBusClassList');	
			Route::get('busclass-update/{id}',  'BusClassController@getEditBusClass');
			Route::post('updatebusclass/{id}',  'BusClassController@postEditBusClass');
			Route::post('delbusclass',          'BusClassController@postdelBusClass');
			Route::get('deletebusclass/{id}',   'BusClassController@getDeleteBusClass');
			Route::post('searchbusclass',       'BusClassController@postSearchBusClass');

			Route::get('carnoassign/{id}',      			'BusnoAssignController@index');
			Route::get('carnoassign/daterange/search',    	'BusnoAssignController@search');


			Route::get('operators/create',		'OperatorController@getAddOperator');
			Route::post('addoperator',			'OperatorController@postAddOperator');
			Route::get('operatorlist',			'OperatorController@showOperatorlist');	
			Route::get('operator-update/{id}',  'OperatorController@getEditOperator');
			Route::post('updateoperator/{id}',  'OperatorController@postEditOperator');
			Route::post('deloperator',          'OperatorController@postdelOperator');
			Route::get('deleteoperator/{id}',   'OperatorController@getDeleteOperator');
			Route::post('searchoperator',       'OperatorController@postSearchOperator');

			Route::get('seatlayout/create',			'SeatLayoutController@getAddSeatLayout');
			Route::post('addseatlayout',			'SeatLayoutController@postAddSeatLayout');
			Route::get('seatlayoutlist',			'SeatLayoutController@showSeatLayoutList');
			Route::get('seatlayout-update/{id}',  	'SeatLayoutController@getEditSeatLayout');
			Route::post('updateseatlayout/{id}',  	'SeatLayoutController@postEditSeatLayout');
			Route::post('delseatlayout',          	'SeatLayoutController@postdelSeatLayout');
			Route::get('deleteseatlayout/{id}',   	'SeatLayoutController@getDeleteSeatLayout');
			Route::post('searchseatlayout',       	'SeatLayoutController@postSearchSeatLayout');
			Route::get('seatlayoutframe',			'SeatLayoutController@postSeatLayout');

			//    16.10.2014
			Route::get('trip/create',				'TripController@create');
			Route::get('trip-list',					'TripController@triplists');
			Route::post('trip-create',				'TripController@store');
			Route::get('trip/seatplan/{id}',		'TripController@showSeatPlan');
			Route::get('deletetrip/{id}',			'TripController@destroy');
			Route::post('changeprice',				'TripController@chageprice');
			

			Route::get('define-ownseat/{id}',				'TripController@ownseat');
			Route::get('define-ownseat-drange/{id}',		'TripController@ownseatdaterange');
			Route::get('ownseatbytrip/{id}',				'TripController@ownseatbytrip');
			Route::post('define-ownseat',					'TripController@postownseat');
			Route::get('ownseat/delete/{id}', 				'TripController@destroyownseat');

			Route::get('changeseatplan/{id}',				'SeatPlanController@getchangeseatplan');
			Route::post('changetripseatplan/{id}',			'SeatPlanController@postchangeseatplan');



			Route::get('closetrip/{id}',			'TripController@closeTrip');
			Route::post('closetrip',				'TripController@saveCloseTrip');
			Route::get('closedtrip/{id}',			'TripController@getCloseTrip');
			Route::get('closedtrip/{id}/delete',	'TripController@deleteCloseTrip');
			Route::get('everclose/{id}/remove',		'TripController@removeEverClose');
			

			Route::get('orderlist',					'OrderController@orderlist');
			Route::get('order-delete/{id}',			'OrderController@destroy');
			
			Route::get('order-tickets/{id}',		'OrderController@ticketlist');
			Route::get('order-tickets/delete/{id}',	'OrderController@ticketdelete');

			Route::get('commissiontypecreate',		'CommissionTypeController@getAddcommissiontype');
			Route::post('addcommissiontype',		'CommissionTypeController@postAddCommissiontype');
			Route::get('commissiontypelist',	    'CommissionTypeController@showCommissiontypeList');
			Route::get('commissiontype-update/{id}','CommissionTypeController@getEditCommissiontype');
			Route::post('updatecommissiontype/{id}','CommissionTypeController@postEditCommissiontype');
			Route::post('delcommissiontype',        'CommissionTypeController@postdelCommissiontype');
			Route::get('deletecommissiontype/{id}', 'CommissionTypeController@getDeleteCommissiontype');
			Route::post('searchcommissiontype',     'CommissionTypeController@postSearchCommissiontype');

			Route::get('city/create',		'CityController@getAddCity');
			Route::post('addcity',			'CityController@postAddCity');
			Route::get('citylist',	    	'CityController@showCityList');
			Route::get('city-update/{id}',	'CityController@getEditCity');
			Route::post('updatecity/{id}',	'CityController@postEditCity');
			Route::post('delcity',        	'CityController@postdelCity');
			Route::get('deletecity/{id}', 	'CityController@getDeleteCity');
			Route::post('searchcity',     	'CityController@postSearchCity');

			Route::get('tickettypes/create',			'TicketTypeController@getAddTicketType');
			Route::post('addtickettype',			'TicketTypeController@postAddTicketType');
			Route::get('tickettypelist',	    	'TicketTypeController@showTicketTypeList');
			Route::get('tickettype-update/{id}',	'TicketTypeController@getEditTicketType');
			Route::post('updatetickettype/{id}',	'TicketTypeController@postEditTicketType');
			Route::post('deltickettype',        	'TicketTypeController@postdelTicketType');
			Route::get('deletetickettype/{id}', 	'TicketTypeController@getDeleteTicketType');
			Route::post('searchtickettype',     	'TicketTypeController@postSearchTicketType');

			Route::get('usercreate',		'UserController@getAddUser');
			Route::post('adduser',			'UserController@postAddUser');
			Route::get('userlist',	    	'UserController@showTicketTypeList');
			Route::get('user-update/{id}',	'UserController@getEditTicketType');
			Route::post('updateuser/{id}',	'UserController@postEditTicketType');
			Route::post('deluser',        	'UserController@postdelTicketType');
			Route::get('deleteuser/{id}', 	'UserController@getDeleteTicketType');
			Route::post('searchuser',     	'UserController@postSearchTicketType');

			Route::get('seatplans/create',			'SeatPlanController@getAddSeatPlan');
			Route::get('makeseatplan',				'SeatPlanController@postSeatPlan');
			Route::post('addseatplan',     			'SeatPlanController@postAddSeatPlan');
			Route::get('seatplanlist',     			'SeatPlanController@showSeatPlanList');
			Route::get('seatplan-update/{id}',  	'SeatPlanController@getEditSeatPlan');
			Route::post('updateseatplan/{id}',  	'SeatPlanController@postEditSeatPlan');
			Route::post('delseatplan',          	'SeatPlanController@postdelSeatPlan');
			Route::get('deleteseatplan/{id}',   	'SeatPlanController@getDeleteSeatPlan');
			Route::post('searchseatplan',       	'SeatPlanController@postSearchSeatPlan');
			Route::get('seatplandetail/{id}/seat_plan_id',		'SeatPlanController@getSeatPlanDetail');

			Route::get('seatplan/update/{planid}',     		'SeatPlanController@getEdit');

			Route::get('permission',				'PermissionController@index');
			Route::get('permission-create',			'PermissionController@create');
			Route::post('premission-create',		'PermissionController@store');
			Route::get('permission-edit/{id}',		'PermissionController@edit');
			Route::post('permission-update/{id}',	'PermissionController@update');
			Route::get('permission-delete/{id}',	'PermissionController@destroy');

			Route::get('user-list',					'UserController@index');
			Route::get('user-create',				'UserController@create');
			Route::post('user-create',				'UserController@store');
			Route::get('user-edit/{id}',			'UserController@edit');
			Route::post('user-update/{id}',			'UserController@update');
			Route::get('user-delete/{id}',			'UserController@destroy');
		});
	// Frond end unsecure routes;
		Route::get('/',      		   'UserController@getFLogin');
		Route::post('/userlogin',       'UserController@postFrontLogin');
		Route::get('/userlogout',       'UserController@getFrontLogout');
	// Frond end secure routes;
		Route::group(array('before' => 'oauth:sale,booking'), function()
		{
			Route::get('/all-trips', 						'HomeController@index');
			Route::get('/alloperator', 						'HomeController@chooseoperator');

			Route::get('/departure-times', 					'HomeController@getTimeList');
			//14
			Route::get('bus_seat_choose',					'HomeController@getchoosebusseat');
			Route::post('saletickets',						'HomeController@postSale');
			Route::post('sales/{id}/delete',				'HomeController@deleteSaleOrder');
			Route::post('ticket/order',						'HomeController@postOrder');
			Route::get('cartview/{id}',						'HomeController@getcart');
			Route::resource('checkout', 					'HomeController@checkout');
			
			Route::get('bookinglist/{id}',					'BookingController@getBookingListByBus');
			
			Route::get('todaybookings',						'BookingController@getTodayBookingList');

			Route::get('notconfirm-order-delete/{id}','OrderController@deleteNotConfirmOrder');

		});
	// API Routes ------
		Route::group(array('before' => 'oauth:sale,booking'), function()
		{
		    Route::post('ticket_types',                     'ApiController@postTicketType');
			Route::get('ticket_types',                      'ApiController@getTicketType');
			Route::get('ticket_types/{id}',                 'ApiController@getTicketTypeById');
			Route::post('ticket_types/update/{id}',			'ApiController@putTicketType');
			Route::post('ticket_types/delete/{id}',         'ApiController@deleteTicketType');

			Route::post('city',                           	'ApiController@postCity');
			Route::get('city',                  	  	  	'ApiController@getAllCity');
			Route::get('city/{id}',                  	  	'ApiController@getCityInfo');
			Route::post('city/{id}',						'ApiController@putupdateCity');
			Route::post('city/delete/{id}',					'ApiController@deleteCity');

		    Route::get('operator/triplist',					'ApiController@getTriplistByOperator');
		    Route::get('operator/trip/agentcommission',		'ApiController@getAgentCommissionListByTrip');
		    Route::post('operator/agentcommission',			'ApiController@postAgentCommission');
		    Route::get('operator/agentcommission',			'ApiController@getAgentCommission');

			Route::post('operator',                       	'ApiController@postOperator');
			Route::get('operator',                  	  	'ApiController@getAllOperator');
			Route::get('operator/{id}',                   	'ApiController@getOperatorInfo');
			Route::post('operator/update/{id}',             'ApiController@putupdateOperator');
			Route::post('operator/delete/{id}',             'ApiController@deleteOperator');

			Route::post('agentgroup',                     	'ApiController@postAgentgroup');
			Route::get('agentgroup',              		  	'ApiController@getAgentgroup');
			Route::get('agentgroup/{id}',             		'ApiController@getAgentGroupinfo');
			Route::post('agentgroup/update/{id}',           'ApiController@updateAgentGroupinfo');
			Route::post('agentgroup/delete/{id}',           'ApiController@deleteAgentGroupinfo');
			
			Route::post('agent',                          	'ApiController@postAgent');
			Route::get('agent',							  	'ApiController@getAllAgent');
			Route::get('agent/{id}',					  	'ApiController@getAgentInfo');
			Route::post('agent/update/{id}',				'ApiController@updateAgentInfo');
			Route::post('agent/delete/{id}',				'ApiController@deleteAgent');

			Route::get('customer/agentgroup',				'ApiController@getCustomerlistByAgentGroup');
			Route::get('customer/agent',					'ApiController@getCustomerlistByAgent');
			Route::get('customer/operator',					'ApiController@getCustomerlistByOperator');

			Route::post('occurance/bus',                  	'ApiController@postBusOccurance');
			Route::get('busoccurance/{id}',         		'ApiController@getBusOccuranceInfo');
			Route::post('busoccurance/update/{id}',         'ApiController@updateBusOccurance');
			Route::post('busoccurance/delete/{id}',         'ApiController@deleteBusOccurance');

			Route::post('holidays', 						'ApiController@postHolidays');
			Route::get('holidays/operator', 				'ApiController@getHolidaysbyOperator');

			Route::resource('busoccurance/create/daily',    'ApiController@postBusOccuranceAutoCreate');
			Route::resource('busoccurance/create/custom',  'ApiController@postBusOccuranceAutoCreateCustom');
			Route::resource('busoccurance/create/montofri', 'ApiController@postBusOccuranceMonToFri');
			
			Route::get('busoccurancelist/schedule/{type}',  'ApiController@getBusOccuranceSchedule');
			Route::post('seatlayout',                  	  	'ApiController@postSeatLayout');
			Route::post('seatplan',                  	  	'ApiController@postSeatplan');

			Route::post('trip',                       	  'ApiController@postTrip');
			Route::post('trip/{id}',                      'ApiController@putTrip');
			Route::post('trips',                       	  'ApiController@postTrip');
			Route::get('trip',                  	  	  'ApiController@getAllTrip');
			Route::get('trip/{id}',                  	  'ApiController@getTripInfo');
			Route::get('tripsbyfrom-to',                  'ApiController@getTripInfobyfromto');
			
			Route::get('triplists',             		  'ApiController@getTripListsbyFromTo');
			Route::post('triplists/holiday',              'ApiController@postTripHolidays');
			Route::post('triplists/holiday/update',       'ApiController@postTripHolidaysupdate');
			Route::post('triplists/holiday/delete',       'ApiController@postTripHolidaysdelete');
			Route::get('triplists/holiday',               'ApiController@getTripHolidays');
			Route::get('triplists/holiday/trip',          'ApiController@getTripHolidaysbyTrip');
			
			Route::get('trips',                       	  'ApiController@getTrip');
			Route::get('seatplan/tripcreate/{opertorid}', 'ApiController@getSeatPlanforTripcreate');
			
			Route::get('busoccurancelist',			  	  'ApiController@getBusOccuranceList');
			
			Route::post('busclasses',					  'ApiController@postClasses');
			Route::get('busclasses',			  		  'ApiController@getClasses');
			Route::get('busclasses/{id}',			  	  'ApiController@getClassesinfo');
			Route::post('busclasses/update/{id}',		  'ApiController@getClassesUpdate');
			Route::post('busclasses/delete/{id}',		  'ApiController@getClassesdelete');

			Route::get('seatlayouts',					  'ApiController@getSeatLayouts');
			Route::get('seatplansbyoperator',			  'ApiController@getSeatplans');
			Route::get('seatplan',						  'ApiController@getSeatPlan');
			Route::get('time',						  	  'ApiController@getTimeLists');
			Route::post('sale',						  	  'ApiController@postSale');
			Route::post('sale/{id}/delete',				  'ApiController@deleteSaleOrder');
			Route::post('sale/comfirm',					  'ApiController@postSaleComfirm');
			
			Route::get('sale/credit',					  'ApiController@getCreditSale');
			Route::post('sale/credit/pay/{id}',		  	  'ApiController@postPayCreditSaleOrderNo');
			Route::post('sale/credit/delete/{id}',		  'ApiController@postDeleteCreditSaleOrderNo');
			Route::post('sale/credit/cancelticket',		  'ApiController@postCancelCreditSaleTicket');

			Route::get('sale/order',					  'ApiController@getSaleOrder');
			Route::post('sale/order/confirm/{id}',		  'ApiController@postConfirmBookingOrder');

			Route::get('report/operator/salelist',		  'ApiController@getSaleList');
			
			Route::post('updateorder', 					  'ApiController@postupdateorderbyadmin');
			Route::post('deleteorder', 					  'ApiController@postdeleteorderbyadmin');

			Route::post('agent/commissionbytrip',		  'ApiController@postAgentCommissionByTrip');
			

			Route::get('agentlist/operatorid',		  	  'ApiController@getAgentListByoperator');
			Route::post('agent/deposit',		  		  'ApiController@postAgentDeposit');
			Route::post('agent/credit',		  		  	  'ApiController@postAgentCredit');
			Route::get('agents/deposit',		  	      'ApiController@getAgentDeposit');
			Route::get('agents/credits',		  		  'ApiController@getAgentCreditByDateRange');
			Route::post('agents/credits/payment',		  'ApiController@postAgentCreditByDateRangePayment');
			Route::get('agents/credits/payment',		  'ApiController@getAgentCreditByPayment');
			
			Route::post('commissiontype',				  'ApiController@postCommissiontype');
			Route::get('commissiontype',				  'ApiController@getCommissiontype');
			Route::get('commissiontype/{id}',			  'ApiController@getCommissiontypeInfo');
			Route::post('commissiontype/update/{id}',	  'ApiController@getCommissiontypeUpdate');
			Route::post('commissiontype/delete/{id}',	  'ApiController@getCommissiontypeDelete');

			Route::get('report/agent/childs',             'ApiController@getAgentgroupbyid');
			Route::get('report/operator/agents', 		  'ApiController@getAgentsByOperatorRp');
			Route::get('report/agent/operators', 		  'ApiController@getOperatorsByAgentRp');
			Route::get('report/agent/invoice/trip',       'ApiController@getSaleReportbyAgentGroup');
			Route::get('report/agent/trip/date',       	  'ApiController@getTripsReportByDaily');
			Route::get('report/agent/trip',            	  'ApiController@getTripReportByDateRanges');
			Route::get('report/agent/seat/trip',       	  'ApiController@getSeatReportByTrip');

			Route::get('report/operator/trip/date',      		'ApiController@getTripsReportByDaily');
			Route::get('report/operator/trip',            		'ApiController@getTripReportByDateRanges');
			Route::get('report/operator/seat/trip',       		'ApiController@getSeatReportByTrip');
			Route::get('report/operator/invoice/trip',    		'ApiController@getSaleReportbyOperatorInvoice');
			Route::get('report/operator/seat/tripbybus',  		'ApiController@getSeatOccupiedReportByBus');
			Route::get('report/operator/seatplan',        		'ApiController@getSeatOccupancyReport');
			
			Route::get('report/trips/operator/{id}',      		'ApiController@getTripsByOperator');

			Route::post('report/customer/update',          		'ApiController@postCustomerInfoUpdate');
			
			Route::get('report/bus/daily',             	  		'ApiController@getDailyReportforTrip');
			Route::get('report/bus/daily/time',           		'ApiController@getDailyReportforTrip');
			Route::get('report/bus/daily/busid',          		'ApiController@getDetailDailyReportforBus');
			Route::get('report/bus/daily/seatoccupied',   		'ApiController@getSeatOccupancyReportbyBusid');
			Route::get('report/soldtrips/advance/daily',  		'ApiController@getDailyAdvancedTrips');
			Route::get('report/soldtrips/advance/daily/date',  	'ApiController@getDailyAdvancedByFilterDate');
			
			Route::get('report/bus/daily/agent',             	'ApiController@getDailyReportforTripByAgent');

			Route::get('report/tripdate/operator/daily',        'ApiController@getDailyReportbydeparturedate');
			Route::get('report/tripdate/operator/busid',        'ApiController@getDailyReportbydepartdateandbusid');
			Route::get('report/tripdate/operator/detail',       'ApiController@getDailyReportbydepartdatedetail');

			Route::get('tripreportbydaily',               		'ApiController@getTripsReportByDailyMod');
			Route::get('citiesbyagent',       			  		'ApiController@getCitiesByagentId');
			Route::get('citiesbyoperator',       		  		'ApiController@getCitiesByoperatorId');
			Route::get('timesbyagent',       			  		'ApiController@getTimesByagentId');
			Route::get('timesbyoperator',       		  		'ApiController@getTimesByOperatorId');


			Route::post('showtimes',						'MovieApiController@postShowTime');
			Route::get('showtimes',							'MovieApiController@getShowTime');
			Route::get('showtimes/{id}',					'MovieApiController@getShowTimeInfo');
			Route::post('showtimes/update/{id}',			'MovieApiController@updateShowTime');
			Route::post('showtimes/delete/{id}',			'MovieApiController@deleteShowTime');

			Route::post('cities',							'MovieApiController@postCity');
			Route::get('cities',							'MovieApiController@getCities');
			Route::get('cities/{id}',						'MovieApiController@getCityInfo');
			Route::post('cities/update/{id}',				'MovieApiController@updateCity');
			Route::post('cities/delete/{id}',				'MovieApiController@deleteCity');

			Route::post('cinema',							'MovieApiController@postCinema');
			Route::get('cinema',							'MovieApiController@getCinemas');
			Route::get('cinema/{id}',						'MovieApiController@getCinemaInfo');
			Route::post('cinema/update/{id}',				'MovieApiController@updateCinema');
			
			Route::post('movies',							'MovieApiController@postMovie');
			Route::get('movies',							'MovieApiController@getMovies');
			Route::get('movies/{id}',						'MovieApiController@getMovieInfo');
			Route::post('movies/update/{id}',				'MovieApiController@updateMovie');

			Route::post('ticketclass',						'MovieApiController@postTicketClass');
			Route::get('ticketclass',						'MovieApiController@getticketclass');
			Route::get('ticketclass/{id}',					'MovieApiController@getTicketClassInfo');
			Route::post('ticketclass/update/{id}',			'MovieApiController@updateTicketClass');

			Route::post('subcinema',							'MovieApiController@postSubCinema');
			Route::get('subcinema',								'MovieApiController@getSubCinema');
			Route::get('subcinema/{id}',						'MovieApiController@getSubCinemaInfo');
			Route::post('subcinema/update/{id}',				'MovieApiController@updateSubCinema');

			//Popularity
			Route::get('report/popular/trip'					,'ApiController@populartrip');
			Route::get('report/popular/triptime'				,'ApiController@populartriptime');
			Route::get('report/popular/agent'					,'ApiController@popularagent');

			//Analytis
			Route::get('report/analytis/classes'				,'ApiController@analytisclasses');

			Route::get('operatorgroup',							'ApiController@getOperatorGroup');
			Route::get('seatlistbytrip/{id}',					'ApiController@getSeatListbyTrip');

			Route::post('closeseatlist',						'ApiController@postCloseSeatList');

			Route::get('targetlabel',							'ApiController@getTargetLabel');
			Route::get('extra_destination/{trip_id}',			'ApiController@getExtraDestination');

			Route::get('booking/notify', 						'ApiController@getNotiBooking');

			Route::post('ticket_delete', 						'ApiController@ticketdelete');



			/* Client Report Api Routes
			*
			*/
				Route::get('dailysales', 						'ReportApiController@getDailyReport');
				Route::get('dailysaledetail', 					'ReportApiController@getDailyReportDetail');
				Route::get('reportbyagent', 					'ReportApiController@getReportByAgent');
				Route::get('agentgroups', 						'ReportApiController@getAgentGroupList');
				Route::get('report/operator/agents', 		  	'ReportApiController@getAgentsByOperatorRp');


		});
	// Sync Route ------
		Route::get('downloadtripjson/{sync_id}', 							'SyncDatabaseController@downloadTripJsonfromServer');
		Route::get('downloaddeletetripjson/{sync_id}', 						'SyncDatabaseController@downloadDeleteTripJsonfromServer');
		Route::get('downloadseatingplanjson/{sync_id}', 					'SyncDatabaseController@downloadSeatingPlanJsonfromServer');
		Route::get('downloadagentjson/{sync_id}', 							'SyncDatabaseController@downloadAgentJsonfromServer');
		Route::get('downloadagentgroupjson/{sync_id}', 						'SyncDatabaseController@downloadAgentGroupJsonfromServer');
		Route::get('downloadcityjson/{sync_id}', 							'SyncDatabaseController@downloadCityJsonfromServer');
		Route::get('downloadextradestinationjson/{sync_id}',				'SyncDatabaseController@downloadExtraDestinationJsonfromServer');
		Route::get('downloadclassesjson/{sync_id}',							'SyncDatabaseController@downloadClassesJsonfromServer');
		Route::get('downloadagentcommissionjson/{sync_id}',					'SyncDatabaseController@downloadAgentCommissionJsonfromServer');
		Route::get('downloadcloseseatinfojson/{sync_id}',					'SyncDatabaseController@downloadCloseSeatInfoJsonfromServer');
		Route::get('downloadcomissiontypejson/{sync_id}', 					'SyncDatabaseController@downloadCommissionTypeJsonfromServer');
		Route::get('downloadoperatorgroupjson/{sync_id}',					'SyncDatabaseController@downloadOperatorGroupJsonfromServer');
		Route::get('downloadoperatorgroupuserjson/{sync_id}',				'SyncDatabaseController@downloadOperatorGroupUserJsonfromServer');
		Route::get('downloaduserjson/{sync_id}',							'SyncDatabaseController@downloadUserJsonfromServer');
		Route::get('downloadoperatorjson/{sync_id}',						'SyncDatabaseController@downloadOperatorJsonfromServer');
		Route::get('downloadseatinfojson/{sync_id}',						'SyncDatabaseController@downloadSeatInfoJsonfromServer');
		Route::get('downloadsaleorderjson/{sync_id}',						'SyncDatabaseController@downloadSaleOrderJsonfromServer');
		Route::get('downloaddeletesaleorderjson/{sync_id}',					'SyncDatabaseController@downloadDeleteSaleOrderJsonfromServer');

		Route::get('exporttripjson/{id}/{fname}/{date}', 					'SyncDatabaseController@exportTrip');
		Route::get('exportdeletetripjson/{id}/{fname}/{date}',				'SyncDatabaseController@exportDeleteTrip');
		Route::get('exportseatingplanjson/{id}/{fname}/{date}', 			'SyncDatabaseController@exportSeatingPlan');
		Route::get('exportagentjson/{id}/{fname}/{date}', 					'SyncDatabaseController@exportAgent');
		Route::get('exportagengrouptjson/{id}/{fname}/{date}', 				'SyncDatabaseController@exportAgentGroup');
		Route::get('exportcityjson/{id}/{fname}/{date}',					'SyncDatabaseController@exportCity');
		Route::get('exportextradestinationjson/{id}/{fname}/{date}',		'SyncDatabaseController@exportExtraDestination');
		Route::get('exportclassesjson/{id}/{fname}/{date}', 				'SyncDatabaseController@exportClasses');
		Route::get('exportagentcommissionjson/{id}/{fname}/{date}', 		'SyncDatabaseController@exportAgentCommission');
		Route::get('exportcloseseatinfojson/{id}/{fname}/{date}', 			'SyncDatabaseController@exportCloseSeatInfo');
		Route::get('exportcommissiontypejson/{id}/{fname}/{date}', 			'SyncDatabaseController@exportCommissionType');
		Route::get('exportoperatorgroupjson/{id}/{fname}/{date}', 			'SyncDatabaseController@exportOperatorGroup');
		Route::get('exportoperatorgroupuserjson/{id}/{fname}/{date}', 		'SyncDatabaseController@exportOperatorGroupUser');
		Route::get('exportuserjson/{id}/{fname}/{date}', 					'SyncDatabaseController@exportUser');
		Route::get('exportoperatorjson/{id}/{fname}/{date}', 				'SyncDatabaseController@exportOperator');
		Route::get('exportseatinfojson/{id}/{fname}/{date}', 				'SyncDatabaseController@exportSeatInfo');
		Route::get('exportsaleorderjson/{id}/{fname}/{date}', 				'SyncDatabaseController@exportSaleOrderJson');
		Route::get('exportdeletesaleorderjson/{id}/{fname}/{date}', 		'SyncDatabaseController@exportDeleteSaleOrderJson');
		Route::get('exportpaymentjson/{id}/{fname}/{date}', 				'SyncDatabaseController@exportPaymentJson');
	

		Route::get('writetodatabase/{fname}',						'SyncDatabaseController@writeJsonToDatabase');
		Route::get('writetripjson/{fname}',							'SyncDatabaseController@writeTripJsonToDatabase');
		Route::get('writeextradestinationjson/{fname}',				'SyncDatabaseController@writeExtraDestinationJsonToDatabase');
		Route::get('writepaymentjson/{fname}',						'SyncDatabaseController@writePaymentJsonToDatabase');
		Route::get('writedelsaleorderjson/{fname}',					'SyncDatabaseController@writeDelSaleOrderJsonToDatabase');
		
		Route::get('uploadjson/{sync_id}', 								'SyncDatabaseController@pushJsonToServer');
		Route::get('uploadtripjson/{sync_id}', 							'SyncDatabaseController@pushTripJsonToServer');
		Route::get('uploadextradestinationjson/{sync_id}',				'SyncDatabaseController@pushExtraDestinationJsonToServer');
		Route::get('uploadpaymentjson/{sync_id}', 						'SyncDatabaseController@pushPaymentJsonToServer');
		Route::get('uploaddelsaleorderjson/{sync_id}', 					'SyncDatabaseController@pushDeleteSaleOrderJsonToServer');
		
		Route::get('downloadjson', 								'SyncDatabaseController@downloadAllJsonfromServer');


		Route::get('test/{fname}', 								'SyncDatabaseController@importDeleteSaleOrderJson');
		Route::get('testupload', 								'SyncDatabaseController@uploadtest');
		Route::get('getupprogress/{sync_id}',					'SyncDatabaseController@getUploadProgress');
		Route::get('getdownprogress/{sync_id}',					'SyncDatabaseController@getDownloadedProgress');


	// Other Routes;
		Route::get('generateautoid/{opr_id}/{gopr_id}', 		'HomeController@generateAutoID');
		Route::get('autorun',									'TestController@autoRun');

		Route::get('db_backup', function(){
			$manager = App::make('BigName\BackupManager\Manager');
			$manager->makeBackup()->run('mysql', 'local', date('Y-m-d').'_bkg_easyticket_db.sql', 'gzip');
			return Response::json('Database backup is success.');
		});

		Route::get('404', function()
		{
		    return View::make('error.404');
		    //return Redirect::to('/');
		});
		Route::get('500', function()
		{
		    return View::make('error.500');
		    //return Redirect::to('/');
		});

		Route::get('401', function()
		{
		    return View::make('error.401');
		});

		Route::get('403', function()
		{
		    return View::make('error.403');
		});

		Route::get('front_403', function()
		{
		    return View::make('error.front_403');
		});

		Route::get('rsa-generate', function(){
			$rsa = new Crypt_RSA();
			//$rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_OPENSSH);
			$k = $rsa->createKey();
			echo $k['publickey'];
			echo "\n";
			echo $k['privatekey'];
		});

		Route::get('code', function(){
			$date = date('Y-m-d H:i:s');
			
			$value = (strtotime($date) / 3600);

			$long = (int) $value;
			return $long;

			dd(date('Y-m-d H:i:s', strtotime($date) ));

		});

		Route::get('decrypt/{en}', function($en){
			
			//dd(strtoupper(Str::random(32)));
			
			$encrypted = MCrypt::encrypt('ေစာ');
			//dd($encrypted);
			#Decrypt
			$decrypted = MCrypt::decrypt($en);

			if(is_string($decrypted))
				dd($decrypted);
		});

		Route::get('/report_xls', function() {
				$flag = false;
				$data = Agent::orderBy('name','asc')->get();
				$list = array();
				foreach ($data as $key => $value) {
					if(!$flag) {
				    	// display field/column names as first row
						$list[] = array_keys($value->toarray());
				    	$flag = true;
				    }
					
					$list[]  = $value->toarray();
				}
				// generate file (constructor parameters are optional)
				$xls = new Excel('UTF-8', false, 'Workflow Management');
				$xls->addArray($list);
				$xls->generateXML('Output_Report_WFM');
			  exit;
		});

		Route::get('/update_commission', function(){
			$saleitem = SaleItem::wherecommission(0)->get();
			foreach ($saleitem as $key => $value) {
				$agent_commission = AgentCommission::whereagent_id($value->agent_id)->wheretrip_id($value->trip_id)->first();
				if($agent_commission){
					$saleitem[$key]->commission = $agent_commission->commission;
					$saleitem[$key]->update();
				}else{
					$saleitem[$key]->commission = Trip::whereid($value->trip_id)->pluck('commission');
					$saleitem[$key]->update();
				}
			}
			return 'success';
		});

		Route::get('/delete_notexist_trip_id', function(){
			$trip_id = Trip::lists('id');
			$deletedSaleItem = DeleteSaleItem::whereNotIn('trip_id',$trip_id)->delete();
			$extra_destination = ExtraDestination::whereNotIn('trip_id',$trip_id)->delete();
			$saleitem = SaleItem::whereNotIn('trip_id',$trip_id)->delete();
			echo $deletedSaleItem.'<br>';
			echo $extra_destination.'<br>';
			echo $saleitem.'<br>';
		});

		Route::get('/update_tripid', function(){
			$trip = Trip::all();
			foreach ($trip as $key => $value) {
				$ail_day = $value->available_day;
				$day = '';
				if($ail_day == 'Daily'){
					$day = 'DLY';
				}else if($ail_day == 'Sat' || $ail_day == 'Sun' || $ail_day == 'Mon' || $ail_day == 'Tue' || $ail_day == 'Web' || $ail_day == 'Thu' || $ail_day == 'Fri'){
					$day = 'WEK';
				}else{
					$day = 'DAT';
				}
				$number = strlen(substr($value->time, 9));
				if($number > 0){
					$value->time = substr($value->time, 0, 9). $number;
					$newId = $value->operator_id.$value->from.$value->to.$value->class_id.$day.(str_replace(' ','',str_replace(':', '', $value->time)));
					$trip[$key]->id = $newId;
					$trip[$key]->update();
					echo $newId.'<br>';
				}
				
			}
		});

		Route::get('/update_departuretime', function() {
			$trip = Trip::all();
			foreach ($trip as $key => $value) {
				$subtime 		= substr($value->time, 0, 8);
				$date_time = date('Y-m-d').' '.str_replace(' ', ':00 ', $subtime);
				echo $date_time .'<br>';
				$trip[$key]->departure_time = date('Y-m-d H:i:s', strtotime($date_time));
				echo $trip[$key]->departure_time .'<br>';
				$trip[$key]->update();
			}
		});

		Route::get('/update_agent_code', function(){
			$agent = Agent::all();
			foreach ($agent as $key => $value) {
				$agent[$key]->code_no = $value->id;
				$agent[$key]->update();
				echo $agent[$key]->code_no .'<br>';
			}
		});
		Route::get('/update_agent_code_saleorder', function(){
			$saleOrder = SaleOrder::where('agent_id','!=',0)->whereagent_code("")->get();
			foreach ($saleOrder as $key => $value) {
				$saleOrder[$key]->agent_code = $value->agent_id;
				$saleOrder[$key]->update();
			}
			echo 'Success';
		});
		Route::get('/update_agent_code_saleitem', function(){
			$saleItem = SaleItem::where('agent_id','!=',0)->whereagent_code("")->get();
			foreach ($saleItem as $key => $value) {
				$saleItem[$key]->agent_code = $value->agent_id;
				$saleItem[$key]->update();
			}
			echo 'Success';
		});

		Route::get('/changerome/{number}', function($integer){
			$table = array('M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1); 
		    $return = ''; 
		    while($integer > 0) 
		    { 
		        foreach($table as $rom=>$arb) 
		        { 
		            if($integer >= $arb) 
		            { 
		                $integer -= $arb; 
		                $return .= $rom; 
		                break; 
		            } 
		        } 
		    } 

		    echo $return;
		});

		Route::get('/changenumber/{rome}', function($roman){
			$romans = array('M' => 1000,'CM' => 900,'D' => 500,'CD' => 400,'C' => 100,'XC' => 90,'L' => 50,'XL' => 40,'X' => 10,'IX' => 9,'V' => 5,'IV' => 4,'I' => 1);
			$result = 0;

			foreach ($romans as $key => $value) {
			    while (strpos($roman, $key) === 0) {
			        $result += $value;
			        $roman = substr($roman, strlen($key));
			    }
			}
			echo $result;
		});
	