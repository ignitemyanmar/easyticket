<?php
	Class Agent extends Eloquent 
	{ 
		protected $table = 'tbl_agent';
		protected $fillable = array('id','code_no','agentgroup_id','name','phone','address','commission_id','commission','user_id','old_credit','owner','operator_id','created_at','updated_at');

		public function deposit_credit(){
			return $this->hasMany('AgentDeposit','agent_id');
				
		}
	}
?>
