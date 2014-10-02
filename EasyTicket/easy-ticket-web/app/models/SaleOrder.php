<?php
	Class SaleOrder extends Eloquent 
	{ 
		protected $table = 'tbl_saleorder';

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
