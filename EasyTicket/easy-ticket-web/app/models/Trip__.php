<?php
	Class Trip extends Eloquent 
	{ 
		protected $table = 'tbl_trip';
		public $timestamps = false;

		public function operator(){
			return $this->belongsTo('Operator', 'operator_id');
		}

		public function agentcommission(){
			return $this->hasMany('AgentCommission', 'trip_id');
		}


	}
?>
