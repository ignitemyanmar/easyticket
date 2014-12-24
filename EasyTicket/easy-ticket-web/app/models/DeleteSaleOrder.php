<?php
	Class DeleteSaleOrder extends Eloquent 
	{ 
		protected $table = 'tbl_del_saleorder';

		protected $fillable = array('id','orderdate','departure_date','departure_datetime','device_id','reference_no','agent_id','name','nrc_no','phone','operator_id','cash_credit','booking','total_amount','agent_commission','user_id','nationality','remark_type','remark','expired_at');

		public function saleitems(){
			return $this->hasMany('DeleteSaleItem', 'order_id');
		}

		public function agent(){
			return $this->belongsTo('Agent', 'agent_id');
		}

		public function operator(){
			return $this->belongsTo('Operator', 'operator_id');
		}
	}
?>
