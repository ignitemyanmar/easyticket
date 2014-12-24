<?php
	Class Operator extends Eloquent 
	{ 
		protected $table = 'tbl_operator';
		protected $fillable = array('id','name','address','phone','user_id','created_at','updated_at');
	}
?>
