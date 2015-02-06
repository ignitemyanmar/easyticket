<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',

));

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	switch ($code)
	{
        case 401:
            return Redirect::to('404');
	    case 403:
	        return Response::view('errors.403', array(), 403);

	    case 404:
            return '<p style="border:1px solid #ccc; color:#888; padding:29px; width:70%; margin:0 auto;">  '.substr($exception, 0, 310).'</p>';
	        return Redirect::to('404');

	    case 500:
	    	return '<p style="border:1px solid #ccc; color:#888; padding:29px; width:70%; margin:0 auto;">  '.substr($exception, 0, 310).'</p>';
	        return Redirect::to('500');

	    default:
	        //return Response::view('errors.index', array(), $code);
	}
});

App::before(function($request)
{
    // Singleton (global) object

    //date time for global
    App::singleton('MyDate', function(){
        $today = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")) + ((60*60) * 6.5));
        return $today;
    });
    $myToday = App::make('MyDate');
    View::share('today', $myToday);

    /******************************************
    */
        // Operator_id, Agent_id for Global
            App::singleton('myApp', function(){
                $app = new stdClass;
                $access_token=null;
                $v_access_token=null;
                $currentroute=$url=Request::url(); 
                $currentroute=substr($currentroute,15);
                $segment=Request::segment(1);
                $agentgroup_id=null;
                $agent_ids=array();
                $objoperators=array();
                $app->agopt_ids=array();
                $operator_ids=array();
                $app->access_token=null; // for url parameter
                $app->v_access_token=null;// for hidden form parameter
                if(Auth::check()) {
                    $access_token="access_token=".Auth::user()->access_token;
                    $v_access_token=Auth::user()->access_token;
                    $app->access_token=$access_token;
                    $app->v_access_token=$v_access_token;
                    if(Auth::user()->role==9 || Auth::user()->role==3){ //for agent and Administrator
                        // if(Auth::user()->role==9){
                            $agent_operator_id=Input::get('agopt_id');
                            if($agent_operator_id){
                                Session::put('agopt_id',$agent_operator_id);
                                Session::put('G_operator_id', $agent_operator_id);
                            }else{
                                if(Session::has('agopt_id')){
                                    $agent_operator_id=Session::get('agopt_id');
                                    Session::put('G_operator_id', $agent_operator_id);
                                }
                            }
                            if($agent_operator_id && $agent_operator_id !='all'){
                                $objoperators=Operator::whereid($agent_operator_id)->get();
                                Session::put('G_operator_id', $agent_operator_id);
                            }


                            if(Auth::user()->role==3){
                                $agentgroup_id=AgentGroup::whereuser_id(Auth::user()->id)->pluck('id');
                                $agentgroup_ids=AgentGroup::whereuser_id(Auth::user()->id)->lists('id');
                            }else{
                                $agentgroup_id=null;
                                $agentgroup_ids=AgentGroup::lists('id');
                            }
                            $app->agentgroup_id     =$agentgroup_id;
                            $app->agentgroup_ids    =$agentgroup_ids;
                            
                            if($agentgroup_ids){
                                $agent_ids              =Agent::wherein('agentgroup_id',$agentgroup_ids)->lists('id');
                            }

                            $app->agent_ids         =$agent_ids;
                            if($agent_ids){
                                $operator_ids=SaleOrder::wherein('agent_id',$agent_ids)->groupBy('operator_id')->lists('operator_id');
                            }

                            if($operator_ids){
                                $app->agopt_ids =$operator_ids;
                            }
                            if($agent_operator_id=='all'){
                                $operator_ids=SaleOrder::groupBy('operator_id')->lists('operator_id');
                                $app->agopt_ids =$operator_ids;
                            }
                            $operator_name=Operator::whereid($agent_operator_id)->pluck('name');
                            $app->operator_name=$operator_name ? $operator_name : "All Operator";
                            
                            if($agent_operator_id && $agent_operator_id !='all'){
                                $agopt_ids[] =$agent_operator_id;
                                $app->agopt_ids=$agopt_ids;
                            }
                            $app->operator_id=$agent_operator_id;
                            $app->objoperators=Operator::get();
                            $operatorgroup_id=OperatorGroupUser::whereuser_id(Auth::user()->id)->pluck('operatorgroup_id');
                            $app->operatorgroup_id=$operatorgroup_id ? $operatorgroup_id : 0;
                        // }

                        
                    }else{
                        $operator_id=Operator::whereuser_id(Auth::user()->id)->pluck('id');
                        if(!$operator_id){
                            $operator_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('operator_id');
                        }
                        
                        $operatorgroup_id=OperatorGroup::whereuser_id(Auth::user()->id)->pluck('id');
                        
                        if(!$operator_id){
                            $operator_id=OperatorGroupUser::whereuser_id(Auth::user()->id)->pluck('operator_id');
                            $operatorgroup_id=OperatorGroupUser::whereuser_id(Auth::user()->id)->pluck('operatorgroup_id');
                        }

                        $app->agentgroup_id     =$agentgroup_id;
                        $app->agent_ids         =$agent_ids;
                        $app->operator_id       =$operator_id;
                        $app->operator_name=Operator::whereid($operator_id)->pluck('name');
                        $app->operator_name=strtolower($app->operator_name);
                        $app->operatorgroup_id=$operatorgroup_id;
                        $app->objoperators=$objoperators;
                    }
                }
                // dd($operator_id);
                return $app;
            });
        // Call and Use for Controller and can use all Controllers  ...... eg.  $this->myGlob->operator_id;
        // $this->myGlob=App::make('myApp');

        // Assign for views and can use all views   ....... eg. $myApp->operator_id;
            $app = App::make('myApp');
            View::share('myApp', $app);

    /*******************************************/

});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/


Event::listen('cron.collectJobs', function() {
    Cron::add('example2', '*/2 * * * *', function() {
        // Do some crazy things successfully every two minute
        $objholiday=new Holiday();
	        $objholiday->operator_id=3222;
	        $objholiday->month="07";
	        $objholiday->holiday="2014-07-04";
	        $objholiday->save();
	        // Do some crazy things successfully every two minute
        return null;
    });
	$report = Cron::run();
});

Event::listen('cron.jobError', function($name, $return, $runtime, $rundate){
    Log::error('Job with the name ' . $name . ' returned an error.');
});


require app_path().'/filters.php';
