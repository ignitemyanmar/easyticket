<?php
	Class Advertisement extends Eloquent 
	{ 
		protected $table = 'tbl_advertisement';
		public $timestamps = false;

		public function menu(){
			return $this->belongsTo('Menu','menu_id');
		}
	}
?>
