<?php
	Class ItemDetail extends Eloquent 
	{ 
		protected $table = 'tbl_itemdetail';
		public $timestamps = false;

		public function size(){
			return $this->belongsTo('Size', 'size_id');
		}

		public function color(){
			return $this->belongsTo('Color', 'color_id');
		}
	}
?>
