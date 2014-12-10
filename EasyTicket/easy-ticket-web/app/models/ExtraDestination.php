<?php
	Class ExtraDestination extends Eloquent 
	{ 
		protected $table = 'tbl_extra_destination';
		public $timestamps = false;
		protected $fillable = array('id','trip_id','city_id','local_price','foreign_price');

		public function city(){
			return $this->belongsTo('City', 'city_id');
		}
	}
?>
