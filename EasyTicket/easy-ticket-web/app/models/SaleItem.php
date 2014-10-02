<?php
	Class SaleItem extends Eloquent 
	{ 
		protected $table = 'tbl_saleitem';
		public $timestamps = false;

		public function order(){
			// return $this->belongsToMany('SaleOrder','SaleItem','busoccurance_id');
			return $this->hasOne('SaleOrder', 'order_id');	
			//return $this->belongsTo('SaleOrder', 'order_id');
		}
	}
?>
