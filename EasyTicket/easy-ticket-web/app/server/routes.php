<?php
Route::get('tripautocreate',       'ApiController@tripautocreate');
Route::get('/', 								function(){
	return Redirect::to('/bus');
});

Route::get('/bus', 								'HomeController@index');
Route::get('triplist',							'HomeController@searchTrip');
Route::get('bus_seat_choose',					'HomeController@getchoosebusseat');
Route::post('ticket/order',						'HomeController@postOrder');
Route::get('cartview/{id}',						'HomeController@getcart');
Route::resource('checkout', 					'HomeController@checkout');

Route::get('operators/agent/{id}',				'ReportController@getOperatorsbyAgent');
Route::get('agents/operator/{id}',				'ReportController@getAgentsbyOperator');
Route::get('triplist/{id}/agent',				'ReportController@getTripslistreportOperator');

//operator report
Route::get('report/operator/trip/dateranges',	'ReportController@getTripslistreportOperator');
Route::get('triplist/{date}/daily',				'ReportController@getTripsSellingReportbyDaily');
Route::get('triplist/{busid}/busid',			'ReportController@getTripsSellingReportbyBusid');

//agent report
Route::get('report/agent/trip/dateranges',		'ReportController@getTripslistdaterangeAgent');
Route::get('agent/triplist/{date}/daily',		'ReportController@getTripsSellingReportbyDailyAgent');
Route::get('triplist/{busid}/busid',			'ReportController@getTripsSellingReportbyBusid');

//seatoccupiedbybus
Route::get('seatoccupiedbybus',					'ReportController@getSeatOccupancyReportbybus');
Route::get('report/seatoccupiedbytrip',			'ReportController@getSeatOccupancyBytrip');

Route::get('triplist/agentreport/{id}/dateragne','ReportController@getTripslistreport');



	Route::post('oauth/access_token', function()
	{
	    $response = AuthorizationServer::performAccessTokenFlow();
	 
		if($response->getStatusCode() === 200) {
			$user = array(
	              'email' => Input::get('username'),
	              'password' => Input::get('password')
	          	);
	          
	      	if (Auth::attempt($user)) {
	          	$response = json_decode($response->getContent());
	          	if(Auth::user()->type == "operator"){
	          		$operator = Operator::whereuser_id(Auth::user()->id)->first();
					$response->user['id'] = $operator->id;
					$response->user['name'] = $operator->name;
					$response->user['type'] = Auth::user()->type;
					return Response::json($response);
	          	}
	          	if(Auth::user()->type == "admin"){
	          		$admin = User::whereid(Auth::user()->id)->first();
					$response->user['id'] = $admin->id;
					$response->user['name'] = $admin->name;
					$response->user['type'] = Auth::user()->type;
					return Response::json($response);
	          	}
	          	if(Auth::user()->type == "agent"){
	          		$agent = Agent::whereuser_id(Auth::user()->id)->first();
					$response->user['id'] = $agent->id;
					$response->user['name'] = $agent->name;
					$response->user['type'] = Auth::user()->type;
					return Response::json($response);
	          	}
	      	}	 
		}
		return $response;
	});

	Route::group(array('before' => 'auth'), function()
	{

		Route::get('easyticket-admin', 				array('as' => 'easyticket-admin', function () {
	        return View::make('admin.login');
	    }));

	});

	Route::filter('guest',              'UserController@filterguest');
	Route::filter('auth',               'UserController@filterauth');
	Route::get('register',              'UserController@adduser1');
	Route::get('user',              'UserController@adduser1');
	Route::get('easyticket-admin',      array('as'=>'easyticket-admin','uses'=>'UserController@getLogin'))->before('guest');
	Route::post('administration',       'UserController@postLogin');
	Route::get('users-logout',          array('as'=>'logout','uses'=>'UserController@getLogout'))->before('auth');   
	Route::post('users-register', 		'UserController@postUserRegister');


Route::group(array('before' => 'oauth'), function()
{
    Route::get('/hello', function()
    {
        return View::make('hello');
    });

    Route::post('ticket_types',                     'ApiController@postTicketType');
	Route::get('ticket_types',                      'ApiController@getTicketType');
	Route::get('ticket_types/{id}',                 'ApiController@getTicketTypeById');
	Route::post('ticket_types/update/{id}',				'ApiController@putTicketType');
	Route::post('ticket_types/delete/{id}',              'ApiController@deleteTicketType');

	Route::post('city',                           	'ApiController@postCity');
	Route::get('city',                  	  	  	'ApiController@getAllCity');
	Route::get('city/{id}',                  	  	'ApiController@getCityInfo');
	Route::post('city/{id}',						'ApiController@putupdateCity');
	Route::post('city/delete/{id}',					'ApiController@deleteCity');

	Route::post('operator',                       	'ApiController@postOperator');
	Route::get('operator',                  	  	'ApiController@getAllOperator');
	Route::get('operator/{id}',                   	'ApiController@getOperatorInfo');
	Route::post('operator/update/{id}',                   	'ApiController@putupdateOperator');
	Route::post('operator/delete/{id}',             'ApiController@deleteOperator');

	Route::post('agentgroup',                     	'ApiController@postAgentgroup');
	Route::get('agentgroup',              		  	'ApiController@getAgentgroup');
	Route::get('agentgroup/{id}',             		'ApiController@getAgentGroupinfo');
	Route::post('agentgroup/update/{id}',           'ApiController@updateAgentGroupinfo');
	Route::post('agentgroup/delete/{id}',           'ApiController@deleteAgentGroupinfo');
	
	Route::post('agent',                          	'ApiController@postAgent');
	Route::get('agent',							  	'ApiController@getAllAgent');
	Route::get('agent/{id}',					  	'ApiController@getAgentInfo');
	Route::post('agent/update/{id}',					  	'ApiController@updateAgentInfo');
	Route::post('agent/delete/{id}',					  	'ApiController@deleteAgent');
	// Route::get('agentbranchbygroup',       		  	'ApiController@getAgentbranchesbygroup');
	// Route::get('agentsbygroup/{id}',              	'ApiController@getAgentgroupbyid');
	Route::get('customer/agentgroup',				'ApiController@getCustomerlistByAgentGroup');
	Route::get('customer/agent',					'ApiController@getCustomerlistByAgent');
	Route::get('customer/operator',					'ApiController@getCustomerlistByOperator');

	Route::post('occurance/bus',                  	'ApiController@postBusOccurance');
	Route::get('busoccurance/{id}',         		'ApiController@getBusOccuranceInfo');
	Route::post('busoccurance/update/{id}',         'ApiController@updateBusOccurance');
	Route::post('busoccurance/delete/{id}',         'ApiController@deleteBusOccurance');

	Route::post('holidays', 							'ApiController@postHolidays');
	Route::get('holidays/operator', 					'ApiController@getHolidaysbyOperator');

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
	Route::get('time',						  	  'ApiController@getTime');
	Route::post('sale',						  	  'ApiController@postSale');
	Route::post('sale/{id}/delete',				  'ApiController@deleteSaleOrder');
	Route::post('sale/comfirm',					  'ApiController@postSaleComfirm');
	
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

	Route::get('report/operator/trip/date',       'ApiController@getTripsReportByDaily');
	Route::get('report/operator/trip',            'ApiController@getTripReportByDateRanges');
	Route::get('report/operator/seat/trip',       'ApiController@getSeatReportByTrip');
	Route::get('report/operator/invoice/trip',    'ApiController@getSaleReportbyOperatorInvoice');
	Route::get('report/operator/seat/tripbybus',  'ApiController@getSeatOccupiedReportByBus');
	Route::get('report/operator/seatplan',        'ApiController@getSeatOccupancyReport');
	
	Route::get('report/bus/daily',             	  		'ApiController@getDailyReportforTrip');
	Route::get('report/bus/daily/time',           		'ApiController@getDailyReportforTrip');
	Route::get('report/bus/daily/busid',          		'ApiController@getDetailDailyReportforBus');
	Route::get('report/bus/daily/seatoccupied',   		'ApiController@getSeatOccupancyReportbyBusid');
	Route::get('report/soldtrips/advance/daily',  		'ApiController@getDailyAdvancedTrips');
	Route::get('report/soldtrips/advance/daily/date',  	'ApiController@getDailyAdvancedByFilterDate');
	
	Route::get('report/tripdate/operator/daily',             'ApiController@getDailyReportbydeparturedate');
	Route::get('report/tripdate/operator/busid',             'ApiController@getDailyReportbydepartdateandbusid');
	Route::get('report/tripdate/operator/detail',            'ApiController@getDailyReportbydepartdatedetail');

	Route::get('tripreportbydaily',               'ApiController@getTripsReportByDailyMod');
	Route::get('citiesbyagent',       			  'ApiController@getCitiesByagentId');
	Route::get('citiesbyoperator',       		  'ApiController@getCitiesByoperatorId');
	Route::get('timesbyagent',       			  'ApiController@getTimesByagentId');
	Route::get('timesbyoperator',       		  'ApiController@getTimesByOperatorId');

});

Route::post('user-login',          			  		'ApiController@postLogin');
Route::post('user-register',          		  		'ApiController@postUserRegister');

Route::get('/404', function()
{
    return View::make('error.404');
});
Route::get('/500', function()
{
    return View::make('error.500');
});
