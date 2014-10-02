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
	/*switch ($code)
	{
	    case 403:
	        return Response::view('errors.403', array(), 403);

	    case 404:
	        return Redirect::to('404');

	    case 500:
	    	return '<p style="border:1px solid #ccc; color:#888; padding:29px; width:70%; margin:0 auto;">  '.substr($exception, 0, 290).'</p>';
	        return Redirect::to('500');

	    default:
	        return Response::view('errors.index', array(), $code);
	}*/
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
