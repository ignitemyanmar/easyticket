<?php
	Class CloseSeatInfo extends Eloquent 
	{ 
		protected $table = 'tbl_close_seatinfo';
		protected $fillable = array('id','trip_id','operatorgroup_id','seat_plan_id','seat_lists');
		public $timestamps = false;
	}
?>
