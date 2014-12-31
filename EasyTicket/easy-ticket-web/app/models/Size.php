<?php
	Class Size extends Eloquent 
	{ 
		protected $table = 'tbl_size';
		public $timestamps = false;

		public function category(){
			return $this->belongsTo('Category', 'category_id');
		}
	}
?>
