<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/*Route::get('/',                           		'HomeController@showWelcome');



Route::post('oauth/access_token', 				'UserController@postToTakeAccessToken');
*/

Route::get('/', 								function(){
	return Redirect::to('/bus');
});

Route::get('/bus', 								'HomeController@index');
Route::get('triplist',							'HomeController@searchTrip');
Route::get('bus_seat_choose',					'HomeController@getchoosebusseat');
Route::post('ticket/order',						'HomeController@postOrder');


Route::get('operators/agent/{id}',				'ReportController@getOperatorsbyAgent');
Route::get('agents/operator/{id}',				'ReportController@getAgentsbyOperator');
Route::get('triplist/{id}/agent',				'ReportController@getTripslistreport');
Route::get('triplist/{id}/daterange',			'ReportController@getTripslistreport');
Route::get('triplist/{date}/daily',				'ReportController@getTripsSellingReportbyDaily');



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
      	}	 
	}
	return $response;
	
});

Route::group(array('before' => 'oauth'), function()
{
    Route::get('/hello', function()
    {
        return View::make('hello');
    });

    Route::post('ticket_types',                     'ApiController@postTicketType');
	Route::get('ticket_types',                      'ApiController@getTicketType');
	Route::get('ticket_types/{id}',                 'ApiController@getTicketTypeById');
	Route::post('ticket_types/{id}',				'ApiController@putTicketType');
	Route::delete('ticket_types/{id}',              'ApiController@deleteTicketType');

	Route::post('city',                           	'ApiController@postCity');
	Route::get('city',                  	  	  	'ApiController@getAllCity');
	Route::get('city/{id}',                  	  	'ApiController@getCityInfo');
	Route::post('city/{id}',						'ApiController@putupdateCity');
	Route::delete('city/{id}',						'ApiController@deleteCity');

	Route::post('operator',                       	'ApiController@postOperator');
	Route::get('operator',                  	  	'ApiController@getAllOperator');
	Route::get('operator/{id}',                   	'ApiController@getOperatorInfo');
	Route::post('operator/{id}',                   	'ApiController@putupdateOperator');
	Route::delete('operator/{id}',                  'ApiController@deleteOperator');

	Route::post('agentgroup',                     	'ApiController@postAgentgroup');
	Route::get('agentgroup',              		  	'ApiController@getAgentgroup');
	Route::get('agentgroup/{id}',             		'ApiController@getAgentGroupinfo');
	Route::post('agentgroup/{id}',             		'ApiController@updateAgentGroupinfo');
	Route::delete('agentgroup/{id}',             	'ApiController@deleteAgentGroupinfo');
	
	Route::post('agent',                          	'ApiController@postAgent');
	Route::get('agent',							  	'ApiController@getAllAgent');
	Route::get('agent/{id}',					  	'ApiController@getAgentInfo');
	Route::post('agent/{id}',					  	'ApiController@updateAgentInfo');
	Route::delete('agent/{id}',					  	'ApiController@deleteAgent');
	// Route::get('agentbranchbygroup',       		  	'ApiController@getAgentbranchesbygroup');

	// Route::get('agentsbygroup/{id}',              	'ApiController@getAgentgroupbyid');
	
	Route::post('occurance/bus',                  	'ApiController@postBusOccurance');
	Route::resource('busoccurance/create/daily',        'ApiController@postBusOccuranceAutoCreate');
	Route::resource('busoccurance/create/weekend',      'ApiController@postBusOccuranceAutoCreateWeekEnd');
	Route::resource('busoccurance/create/montofri',     'ApiController@postBusOccuranceMonToFri');
	
	Route::get('busoccurancelist/schedule/{type}',  'ApiController@getBusOccuranceSchedule');
	Route::post('seatlayout',                  	  	'ApiController@postSeatLayout');
	Route::post('seatplan',                  	  	'ApiController@postSeatplan');

	

	Route::post('trip',                       	  'ApiController@postTrip');
	Route::post('trip/{id}',                       'ApiController@putTrip');
	Route::post('trips',                       	  'ApiController@postTrip');
	Route::get('trip',                  	  	  'ApiController@getAllTrip');
	Route::get('trip/{id}',                  	  'ApiController@getTripInfo');
	Route::get('tripsbyfrom-to',                  'ApiController@getTripInfobyfromto');

	Route::get('trips',                       	  'ApiController@getTrip');

	Route::get('seatplan/tripcreate/{opertorid}', 'ApiController@getSeatPlanforTripcreate');

	
	Route::get('busoccurancelist',			  	  'ApiController@getBusOccuranceList');
	

	Route::get('seatlayouts',					  'ApiController@getSeatLayouts');
	Route::get('seatplansbyoperator',			  'ApiController@getSeatplans');
	Route::post('busclasses',					  'ApiController@postClasses');
	Route::get('busclasses',			  		  'ApiController@getClasses');
	Route::get('seatplan',						  'ApiController@getSeatPlan');
	Route::get('time',						  	  'ApiController@getTime');
	Route::post('sale',						  	  'ApiController@postSale');
	Route::post('sale/{id}/delete',				  'ApiController@deleteSaleOrder');
	Route::post('sale/comfirm',					  'ApiController@postSaleComfirm');
	Route::post('commissiontype',				  'ApiController@postCommissiontype');
	Route::get('commissiontype',				  'ApiController@getCommissiontype');


	Route::get('report/agent/childs',             	'ApiController@getAgentgroupbyid');
	Route::get('report/operator/agents', 		  'ApiController@getAgentsByOperatorRp');
	Route::get('report/agent/operators', 		  'ApiController@getOperatorsByAgentRp');
	Route::get('report/agent/invoice/trip',       'ApiController@getSaleReportbyAgentGroup');
	Route::get('report/agent/trip/date',       	  'ApiController@getTripsReportByDaily');
	Route::get('report/agent/trip',            	  'ApiController@getTripReportByDateRanges');
	Route::get('report/agent/seat/trip',       	  'ApiController@getSeatReportByTrip');

	Route::get('report/operator/trip/date',       'ApiController@getTripsReportByDaily');
	Route::get('report/operator/trip',            'ApiController@getTripReportByDateRanges');
	Route::get('report/operator/seat/trip',       'ApiController@getSeatReportByTrip');
	Route::get('report/operator/seat/tripbybus',  'ApiController@getSeatOccupiedReportByBus');
	Route::get('report/operator/seatplan',        'ApiController@getSeatOccupancyReport');
	
	Route::get('report/bus/daily',             	  'ApiController@getDailyReportforTrip');
	Route::get('report/bus/daily/time',           'ApiController@getDailyReportforTrip');
	Route::get('report/bus/daily/busid',          'ApiController@getDetailDailyReportforBus');
	Route::get('report/bus/daily/seatoccupied',   'ApiController@getSeatOccupancyReportbyBusid');
	Route::get('report/soldtrips/advance/daily',  		'ApiController@getDailyAdvancedTrips');
	Route::get('report/soldtrips/advance/daily/date',  	'ApiController@getDailyAdvancedByFilterDate');
	
	
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
