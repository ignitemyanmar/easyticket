<?php
	Class ItemCategory extends Eloquent 
	{ 
		protected $table = 'tbl_itemcategory';
		public $timestamps = false;

		public function subcategory(){
			return $this->belongsTo('Subcategory', 'subcategory_id');
		}
	}
?>
