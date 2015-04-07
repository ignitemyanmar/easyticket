<?php
	Class CloseTrip extends Eloquent 
	{ 
		protected $table = 'tbl_close_trip';
		protected $fillable = array('id','trip_id','start_date','end_date','remark','created_at','updated_at');
	}
?>
