<?php
	Class City extends Eloquent 
	{ 
		protected $table = 'tbl_city';
		protected $fillable = array('id','name');
		public $timestamps = false;
	}
?>
