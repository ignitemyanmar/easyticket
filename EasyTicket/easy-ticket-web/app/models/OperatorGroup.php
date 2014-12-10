<?php
	Class OperatorGroup extends Eloquent 
	{ 
		protected $table = 'tbl_operatorgroup';
		protected $fillable = array('id','operator_id','user_id','color');
		public $timestamps = false;

		public function user(){
			return $this->belongsTo('User', 'user_id');
		}
	}
?>