<?php
	Class Classes extends Eloquent 
	{ 
		protected $table = 'tbl_classes';
		protected $fillable = array('id','name','operator_id','created_at','updated_at');
	}
?>
