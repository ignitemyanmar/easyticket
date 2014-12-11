<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	public $today = null;
	public $operator_id = 0;
	public $operatorgroup_id = 0;
	public $client_ip = "";
	public function __construct(){
		$this->Date = App::make('MyDate');
		$this->today= App::make('MyDate');
		$bookingcount=SaleOrder::wherebooking(1)->whereorderdate($this->getDate())->count('id');
		$currentroute=Route::getCurrentRoute()->getPath();
		if($currentroute=='departure-times'){
		}
        Session::put('bookingcount', $bookingcount);

        $this->myGlob=App::make('myApp');

        $this->operator_id = isset($this->myGlob->operator_id) ? $this->myGlob->operator_id : 0;
        $this->operatorgroup_id = isset($this->myGlob->operatorgroup_id) ? $this->myGlob->operatorgroup_id : 0;

        $this->client_ip = $_SERVER['REMOTE_ADDR'];
    }

    protected function getDate(){
    	return date('Y-m-d', strtotime($this->today));
    }

    protected function getDateTime(){
    	return $this->today;
    }

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function msort($array, $key, $sort_flags = SORT_REGULAR, $order) {
	    if (is_array($array) && count($array) > 0) {
	        if (!empty($key)) {
	            $mapping = array();
	            foreach ($array as $k => $v) {
	                $sort_key = '';
	                if (!is_array($key)) {
	                    $sort_key = $v[$key];
	                } else {
	                    // @TODO This should be fixed, now it will be sorted as string
	                    foreach ($key as $key_key) {
	                        $sort_key .= $v[$key_key];
	                    }
	                    $sort_flags = SORT_STRING;
	                }
	                $mapping[$k] = $sort_key;
	            }
	            switch ($order) {
		            case SORT_ASC:
		                asort($mapping, $sort_flags);
		            break;
		            case SORT_DESC:
		                arsort($mapping, $sort_flags);
		            break;
		        }
	            // asort($mapping, $sort_flags);
	            // arsort($mapping, $sort_flags);
	            $sorted = array();
	            foreach ($mapping as $k => $v) {
	                $sorted[] = $array[$k];
	            }
	            return $sorted;
	        }
	    }
	    return $array;
	}

}