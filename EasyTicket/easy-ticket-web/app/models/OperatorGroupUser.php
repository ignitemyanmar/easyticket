<?php
	Class OperatorGroupUser extends Eloquent 
	{ 
		protected $table = 'tbl_operatorgroup_user';
		protected $fillable = array('id','operator_id','operatorgroup_id','user_id','created_at','updated_at');

		public function user(){
			return $this->belongsTo('User', 'user_id');
		}
	}
?>