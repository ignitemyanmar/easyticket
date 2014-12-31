<?php

class DetailController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		$today = strtotime(date("Y-m-d H:i:s")) + ((60*60) * 6.5);
		$today_morning_start = strtotime(date('Y-m-d',$today)."06:00:00");
		$today_morning_end = strtotime(date('Y-m-d',$today)."09:00:00");
		$today_evening_start = strtotime(date('Y-m-d',$today)."21:00:00");
		$today_evening_end = strtotime(date('Y-m-d',$today)."23:00:00");

		$this->calculatecount($id);
		$item 			= Item::with(array('itemdetail','compareprice','itemthumbimages','itempricebyqty'))->whereid($id)->first();
		$similar_items 	= Item::with(array('itemdetail','itemthumbimages'))->wheremenu_id($item->menu_id)->wherecategory_id($item->category_id)->wheresubcategory_id($item->subcategory_id)->limit(2)->get();
		$shop_items 	= Item::with(array('itemdetail','itemthumbimages'))->whereshop_id($item->shop_id)->limit(4)->get();
		$relative_items = Item::with(array('itemdetail','itemthumbimages'))->wheremenu_id($item->menu_id)->wherecategory_id($item->category_id)->wheresubcategory_id($item->subcategory_id)->limit(5)->get();
		$item['shop']	= Shop::find($item->shop_id);

		//Check Time Sale;
		if($item->timesale > 0){
			if($today >= $today_morning_start && $today <= $today_morning_end){
			}else{
				$item->timesale = 0;
			}
			if($today >= $today_evening_start && $today <= $today_evening_end){
			}else{
				$item->timesale = 0;
			}
		}

		$i = 0;
		foreach ($item->itempricebyqty as $rows) {
			$item['itempricebyqty'][$i]['startqty'] = QtyrangeforPrice::whereid($rows->priceqtyrange_id)->pluck('startqty');
			$item['itempricebyqty'][$i]['endqty'] 	= QtyrangeforPrice::whereid($rows->priceqtyrange_id)->pluck('endqty');
			$i++;
		}
		$i = 0;
		$item_qty = 0;
		foreach ($item->itemdetail as $itemdetail) {
			$item_qty += $itemdetail->qty;
			$item['itemdetail'][$i]['remaining_qty'] 	= $itemdetail->qty;
			$item['itemdetail'][$i]['sold_qty']			= OrderDetail::whereitemdt_id($itemdetail->id)->count('quantity');
			$colors		 								= Color::whereid($itemdetail->color_id)->first();
			$size_of_color 								= ItemDetail::whereitem_id($id)->wherecolor_id($itemdetail->color_id)->get();
			$j = 0;
			foreach ($size_of_color as $size) {
				$colorsize[$j] = Size::whereid($size->size_id)->first()/*->toarray()*/;
				$colorsize[$j]['price'] = $size->price;
				$j++;
			}
			$colors['size']								= $colorsize;
			$item['itemdetail'][$i]["color"] 			= $colors/*->toarray()*/;
			$sizes							 			= Size::whereid($itemdetail->size_id)->first();
			$color_of_size								= ItemDetail::whereitem_id($id)->wheresize_id($itemdetail->size_id)->get();
			$j = 0;
			foreach ($color_of_size as $color) {
				$sizecolor[$j] = Color::whereid($color->color_id)->first()/*->toarray()*/;
				$sizecolor[$j]['price'] = $color->price;
				$j++;
			}
			$sizes['color']								= $sizecolor;
			$item['itemdetail'][$i]["size"] 			= $sizes/*->toarray()*/;
			$i++;
			
		}
		$item['item_qty'] = $item_qty;
		$link_men[0] = Menu::whereid($item->menu_id)->first();
		$link_men[1] = Category::whereid($item->category_id)->first();
		$link_men[2] = SubCategory::whereid($item->subcategory_id)->first();

		$group_sale  = array();
		$group_person = array();
		$group_item = GroupItem::whereitem_id($item->id)->where('end_date','>', date('Y-m-d'))->first();
		if(count($group_item) > 0){
			$group_person = GroupPerson::wheregroup_id($group_item->id)->get();
			if(count($group_person) < $group_item->number_person){
				$i = 0;
				$group_item['is_enter'] = false;
				foreach ($group_person as $rows) {
					if(Auth::check())
					{
						if($rows->user_id == Auth::user()->id){
							$group_item['is_enter'] = true;
						}
					}
					$group_person[$i]['user_name'] = User::whereid($rows->user_id)->pluck('name');
					$i++;
				
				}
				$item['group_item'] = $group_item;
			}
		}
		$review = Review::whereitem_id($id)->get();
		$i = 0;
		foreach ($review as $rows) {
			$review[$i]['username'] = User::whereid($rows->user_id)->pluck('name');
			$i++;
		}

		$group_item_price = GroupItemPrice::whereitem_id($id)->orderBy('number_person','asc')->get();
		$item_dt_id 	  = ItemDetail::whereitem_id($id)->lists('id');
		$order_item_count = 0;
		if($item_dt_id){
			$order_item_count = OrderDetail::wherein('itemdt_id',$item_dt_id)->sum('quantity');
		}
		
		$item['sold_qty'] = $order_item_count;
		//return Response::json($item);
		return View::make('detail.index',array(
			'link_menu'			=> $link_men,
			'item'				=> $item,
			'group_person'		=> $group_person,
			'similar_items'		=>$similar_items, 
			'shop_items'		=>$shop_items, 
			'relative_items'	=>$relative_items,
			'review'			=>$review,
			'group_item_price'	=>$group_item_price));
	}

	public function calculatecount($id){
		$item = Item::find($id);
		$item ->view_counts = $item->view_counts + 1;
		$item ->save();
	}

	public function ratereview($id){
		$shop_id = Item::whereid($id)->pluck('shop_id');
		$item_rating = Input::get('rateit_item');
		$shop_rating = Input::get('rateit_shop');
		$delivery_rating = Input::get('rateit_delivery');
		$comment 		 = Input::get('review');
		$review = Review::whereitem_id($id)->whereuser_id(Auth::user()->id)->first();
		if(!$review){
			$review 					= new Review();
			$review->user_id 			= Auth::user()->id;
			$review->item_id 			= $id;
			$review->shop_id 			= $shop_id;
			$review->item_rating 		= $item_rating;
			$review->delivery_rating 	= $delivery_rating;
			$review->shop_rating 		= $shop_rating;
			$review->comment 			= $comment;
			$review->save();
		}else{
			$review->user_id 			= Auth::user()->id;
			$review->item_id 			= $id;
			$review->shop_id 			= $shop_id;
			$review->item_rating 		= $item_rating;
			$review->delivery_rating 	= $delivery_rating;
			$review->shop_rating 		= $shop_rating;
			$review->comment 			= $comment;
			$review->save();
		}
		return Redirect::to(URL::previous());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
