<?php
	Class AgentCommission extends Eloquent 
	{ 
		protected $table = 'tbl_agent_commission';
		public $timestamps = false;

		public function agent(){
			return $this->belongsTo('Agent','agent_id');
		}
		public function trip(){
			return $this->belongsTo('Trip','trip_id');
		}
		public function commission(){
			return $this->belongsTo('CommissionType','commission_id');
		}
		public function commissiontype(){
			return $this->belongsTo('CommissionType','commission_id');
		}
	}
?>
