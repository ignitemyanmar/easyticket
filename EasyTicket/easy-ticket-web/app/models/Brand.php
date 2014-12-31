<?php
	Class Brand extends Eloquent 
	{ 
		protected $table = 'tbl_brand';
		public $timestamps = false;

		public function menu(){
			return $this->belongsTo('Menu', 'menu_id');
		}
	}
?>
