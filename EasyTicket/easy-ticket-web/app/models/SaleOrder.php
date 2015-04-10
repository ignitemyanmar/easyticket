<?php
	Class SaleOrder extends Eloquent 
	{ 
		protected $table = 'tbl_saleorder';

		protected $fillable = array('id','orderdate','departure_date','booking_expired','device_id','reference_no','agent_id','agent_code','name','nrc_no','phone','operator_id','cash_credit','booking','total_amount','agent_commission','user_id','nationality','remark_type','remark','created_at','updated_at','expired_at');

		public function saleitems(){
			return $this->hasMany('SaleItem', 'order_id');
		}

		public function agent(){
			return $this->belongsTo('Agent', 'agent_id');
		}

		public function operator(){
			return $this->belongsTo('Operator', 'operator_id');
		}
	}
?>
