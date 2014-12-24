<?php
	Class CommissionType extends Eloquent 
	{ 
		protected $table = 'tbl_commissiontype';
		protected $fillable = array('id','name','created_at','updated_at');
		public $timestamps = false;
	}
?>
