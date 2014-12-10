<?php
	Class OperatorGroupUser extends Eloquent 
	{ 
		protected $table = 'tbl_operatorgroup_user';
		protected $fillable = array('id','operator_id','operatorgroup_id','user_id');
		public $timestamps = false;

		public function user(){
			return $this->belongsTo('User', 'user_id');
		}
	}
?>