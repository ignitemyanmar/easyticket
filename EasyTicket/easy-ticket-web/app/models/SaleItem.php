<?php
	Class SaleItem extends Eloquent 
	{ 
		protected $table = 'tbl_saleitem';
		public $timestamps = false;
		protected $fillable = array('order_id','device_id','ticket_no','seat_no','nrc_no','name','phone','busoccurance_id','trip_id','from','to','extra_destination_id','extra_city_id','operator','agent_id','agent_code','price','free_ticket','free_ticket_remark','discount','class_id','foreign_price','departure_date');

		public function order(){
			// return $this->belongsToMany('SaleOrder','SaleItem','busoccurance_id');
			return $this->hasOne('SaleOrder', 'order_id');	
			//return $this->belongsTo('SaleOrder', 'order_id');
		}
	}
?>
