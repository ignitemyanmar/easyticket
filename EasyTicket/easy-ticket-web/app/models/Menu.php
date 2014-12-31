<?php
	Class Menu extends Eloquent 
	{ 
		protected $table = 'tbl_menu';
		public $timestamps = false;

		/*public function categories(){
			return $this->hasManyThrough('Subcategory','Category', 'menu_id','category_id');
		}*/

		public function categories(){
			return $this->hasMany('Category', 'menu_id');
		}

		public function subcategories()
	    {
	        return $this->hasManyThrough('SubCategory', 'Category','menu_id','category_id');
	    }

	   

	}
?>
