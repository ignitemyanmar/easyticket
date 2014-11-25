<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public function __construct(){
		$this->Date = App::make('MyDate');
		
		$today=date('Y-m-d');
		$bookingcount=SaleOrder::wherebooking(1)->whereorderdate($today)->count('id');
		$currentroute=Route::getCurrentRoute()->getPath();
		if($currentroute=='departure-times'){
		}
        Session::put('bookingcount', $bookingcount);

        $this->myGlob=App::make('myApp');
    }

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}