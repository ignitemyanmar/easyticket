<?php
	Class SubCategory extends Eloquent 
	{ 
		protected $table = 'tbl_subcategory';
		public $timestamps = false;

		public function category(){
			return $this->belongsTo('Category', 'category_id');
		}

	}
?>
