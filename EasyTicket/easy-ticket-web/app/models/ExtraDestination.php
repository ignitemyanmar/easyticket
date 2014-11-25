<?php
	Class ExtraDestination extends Eloquent 
	{ 
		protected $table = 'tbl_extra_destination';
		public $timestamps = false;

		public function city(){
			return $this->belongsTo('City', 'city_id');
		}
	}
?>
