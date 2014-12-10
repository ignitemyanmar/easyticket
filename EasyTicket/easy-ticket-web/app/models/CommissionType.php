<?php
	Class CommissionType extends Eloquent 
	{ 
		protected $table = 'tbl_commissiontype';
		protected $fillable = array('id','name');
		public $timestamps = false;
	}
?>
