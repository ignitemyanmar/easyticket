<?php
	Class BusOccurance extends Eloquent 
	{ 
		protected $table = 'tbl_busoccurance';
		protected $fillable = array('id','seat_no','seat_plan_id','bus_no','from','to','classes','departure_date','departure_time','arrival_time','price','foreign_price','commission','operator_id','trip_id','status','from_to_key','remark','created_at','updated_at');

		public function saleitems(){
			return $this->hasMany('SaleItem', 'busoccurance_id');
		}


	}
?>
