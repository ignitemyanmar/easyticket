<?php
	Class Agent extends Eloquent 
	{ 
		protected $table = 'tbl_agent';
		public $timestamps = false;
		protected $fillable = array('id','agentgroup_id','name','phone','address','commission_id','commission','user_id','old_credit','owner','operator_id');

		public function deposit_credit(){
			return $this->hasMany('AgentDeposit','agent_id');
				
		}
	}
?>
