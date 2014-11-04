<?php
	Class Agent extends Eloquent 
	{ 
		protected $table = 'tbl_agent';
		public $timestamps = false;

		public function deposit_credit(){
			return $this->hasMany('AgentDeposit','agent_id');
				
		}
	}
?>
