<?php
	Class Order extends Eloquent 
	{ 
		protected $table = 'tbl_order';
		public $timestamps = true;

		public function orderdetail(){
			return $this->hasMany('OrderDetail', 'order_id');
		}

	}
?>
