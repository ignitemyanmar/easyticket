<?php
	Class Classes extends Eloquent 
	{ 
		protected $table = 'tbl_classes';
		protected $fillable = array('id','name','operator_id');
		public $timestamps = false;
	}
?>
