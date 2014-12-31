<?php
	Class SearchLink extends Eloquent 
	{ 
		protected $table = 'tbl_search_link';
		public $timestamps = false;

		public function menu(){
			return $this->belongsTo('Menu', 'menu_id');
		}
	}
?>
