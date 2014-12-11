<?php
	Class AgentDeposit extends Eloquent 
	{ 
		protected $table = 'tbl_agent_deposit_trans';
		public $fillable = array('agent_id','operator_id','deposit_date','deposit','total_ticket_amt','payment','pay_date','order_ids','balance','debit','created_at','updated_at');
	}
?>
