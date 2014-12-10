<?php
	Class SeatInfo extends Eloquent 
	{ 
		protected $table = 'tbl_seatinfo';
		protected $fillable = array('id','seat_plan_id','seat_no','status');
		public $timestamps = false;
	}
?>
