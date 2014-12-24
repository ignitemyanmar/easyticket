<?php
	Class City extends Eloquent 
	{ 
		protected $table = 'tbl_city';
		protected $fillable = array('id','name','created_at','updated_at');
	}
?>
