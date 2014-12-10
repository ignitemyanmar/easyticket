<?php
	Class SeatingPlan extends Eloquent 
	{ 
		protected $table = 'tbl_seatingplan';
		protected $fillable = array('id','name','ticket_type_id','operator_id','seat_layout_id','row','column','seat_list');
		public $timestamps = false;
	}
?>
