<?php
	Class OperatorGroup extends Eloquent 
	{ 
		protected $table = 'tbl_operatorgroup';
		protected $fillable = array('id','operator_id','user_id','color','created_at','updated_at');

		public function user(){
			return $this->belongsTo('User', 'user_id');
		}
	}
?>