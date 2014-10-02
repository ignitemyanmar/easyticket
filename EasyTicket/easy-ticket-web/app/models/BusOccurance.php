<?php
	Class BusOccurance extends Eloquent 
	{ 
		protected $table = 'tbl_busoccurance';
		public $timestamps = false;

		public function saleitems(){
			return $this->hasMany('SaleItem', 'busoccurance_id');
		}


	}
?>
