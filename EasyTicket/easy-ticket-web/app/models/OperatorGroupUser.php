<?php
	Class OperatorGroupUser extends Eloquent 
	{ 
		protected $table = 'tbl_operatorgroup_user';
		public $timestamps = false;

		public function user(){
			return $this->belongsTo('User', 'user_id');
		}
	}
?>