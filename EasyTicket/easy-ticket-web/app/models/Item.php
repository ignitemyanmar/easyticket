<?php
	Class Item extends Eloquent 
	{ 
		protected $table = 'tbl_item';

		public function itemdetail(){
			return $this->hasMany('ItemDetail', 'item_id');
		}

		public function itemthumbimages(){
			return $this->hasMany('ItemImages', 'item_id');
		}

		public function compareprice(){
			return $this->hasMany('ComparePrice', 'item_id');
		}
		
		public function itempricebyqty(){
			return $this->hasMany('ItempriceByQty','item_id');
		}

		public function itemimages(){
			return $this->hasMany('ItemImages', 'item_id');
		}

		public function itempricebyqtyrange(){
			 return $this->belongsToMany('QtyrangeforPrice', 'tbl_itempricesbyqty', 'priceqtyrange_id','item_id')->withPivot('price');
		}

		public function pricebyqtyranges(){
			 return $this->hasMany('ItempriceByQty', 'item_id');
		}

		public function shop(){
			return $this->belongsTo('Shop','shop_id');
		}

		public function menu(){
			return $this->belongsTo('Menu','menu_id');
		}

		public function category(){
			return $this->belongsTo('Category','category_id');
		}

		public function subcategory(){
			return $this->belongsTo('SubCategory','subcategory_id');
		}

		public function itemcategory(){
			return $this->belongsTo('ItemCategory','itemcategory_id');
		}

	}
?>
