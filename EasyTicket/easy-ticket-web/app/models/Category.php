<?php
	Class Category extends Eloquent 
	{ 
		protected $table = 'tbl_category';
		public $timestamps = false;

		public function menu(){
			return $this->belongsTo('Menu', 'menu_id');
		}

		public function subcategory(){
			return $this->hasMany('SubCategory', 'category_id');
		}

		public function itemcategories()
	    {
	        return $this->hasManyThrough('ItemCategory', 'SubCategory','category_id','subcategory_id');
	    }
	}
?>
