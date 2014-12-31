<?php

class ItemListController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($menu_id)
	{	
		$search='';
		$sorting = Input::get('sort');
		if($sorting == null){
			$sorting = 'comprehensive';
		}
		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->paginate(25);
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->where('view_counts','!=','0')
							->orderby('view_counts','desc')
							->paginate(25);
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->orderby('id','desc')
							->paginate(25);
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->where('discount','!=','0')
							->orderby('discount','desc')
							->paginate(25);
		}
		
		$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->limit(3)->get();

		$link_men[0] = Menu::whereid($menu_id)->first();
		//return Response::json($items);
		return View::make('itemlist.index', array('link_menu'=> $link_men,'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting,'search'=>$search));
	}

	public function listbybrand($brand_id){
		$search='';
		$sorting = Input::get('sort');
		if($sorting == null){
			$sorting = 'comprehensive';
		}
		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($brand_id)
							->paginate(25);
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($brand_id)
							->where('view_counts','!=','0')
							->orderby('view_counts','desc')
							->paginate(25);
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($brand_id)
							->orderby('id','desc')
							->paginate(25);
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($brand_id)
							->where('discount','!=','0')
							->orderby('discount','desc')
							->paginate(25);
		}
		
		$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($brand_id)
							->limit(3)->get();
		$menu_id=Brand::whereid($brand_id)->pluck('menu_id');
		$link_men[0] = Menu::whereid($menu_id)->first();
		$link_men[1] = Brand::whereid($brand_id)->first();
		//return Response::json($items);
		return View::make('itemlist.index', array('link_menu'=> $link_men,'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting, 'search'=>$search));
	}

	public function indexbycategoy($menu_id, $category_id)
	{
		$search='';
		$sorting = Input::get('sort');
		if($sorting == null){
			$sorting = 'comprehensive';
		}
		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->paginate(25);
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->where('view_counts','!=','0')
							->orderby('view_counts','desc')
							->paginate(25);
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->orderby('id','desc')
							->paginate(25);
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->where('discount','!=','0')
							->orderby('discount','desc')
							->paginate(25);
		}
		
		$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->limit(3)->get();

		$link_men[0] = Menu::whereid($menu_id)->first();
		$link_men[1] = Category::whereid($category_id)->first();
		//return Response::json($items);
		return View::make('itemlist.index', array('link_menu'=> $link_men,'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting, 'search'=>$search));
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function indexbysubcategoy($menu_id, $category_id, $subcategory_id)
	{
		$search='';
		$sorting = Input::get('sort');
		if($sorting == null){
			$sorting = 'comprehensive';
		}
		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->wheresubcategory_id($subcategory_id)
							->paginate(25);
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->wheresubcategory_id($subcategory_id)
							->where('view_counts','!=','0')
							->orderby('view_counts','desc')
							->get();
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->wheresubcategory_id($subcategory_id)
							->orderby('id','desc')
							->paginate(25);
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->wheresubcategory_id($subcategory_id)
							->where('discount','!=','0')
							->orderby('discount','desc')
							->paginate(25);
		}
		
		$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->wheresubcategory_id($subcategory_id)->limit(3)->get();
		
		$link_men[0] = Menu::whereid($menu_id)->first();
		$link_men[1] = Category::whereid($category_id)->first();
		$link_men[2] = SubCategory::whereid($subcategory_id)->first();
		//return Response::json($items);
		return View::make('itemlist.index', array('link_menu'=> $link_men,'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting, 'search'=>$search));
	}

	public function indexbybrand($menu_id, $id)
	{	
		$search='';
		$sorting = Input::get('sort');
		if($sorting == null){
			$sorting = 'comprehensive';
		}
		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($id)
							->wheremenu_id($menu_id)
							->paginate(25);
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($id)
							->wheremenu_id($menu_id)
							->where('view_counts','!=','0')
							->orderby('view_counts','desc')
							->paginate(25);
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($id)
							->wheremenu_id($menu_id)
							->orderby('id','desc')
							->paginate(25);
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($id)
							->wheremenu_id($menu_id)
							->where('discount','!=','0')
							->orderby('discount','desc')
							->paginate(25);
		}
		
		$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wherebrand_id($id)
							->limit(3)->get();

		//$link_men[0] = Menu::whereid($id)->first();
		//return Response::json($items);
		return View::make('itemlist.index', array('link_menu'=> array(),'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting,'search'=>$search));
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

	public function searcholdddddd()
	{
		
		$search=Input::get('search') ? Input::get('search') : '';

        if(Session::has('sorting')){
            $sorting = Session::get('sorting');
        }else{
			$sorting=Input::get('sorting') ? Input::get('sorting') : 'comprehensive';
            Session::put('sorting',$sorting);
        }

		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->paginate(25);
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->where('view_counts','!=','0')
							->orderby('view_counts','desc')
							->paginate(25);
							
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orderby('id','desc')
							->paginate(25);
			
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orderby('id','desc')
							->where('discount','!=','0')
							->orderby('discount','desc')
							->paginate(25);
		}else{

		}

		if($search==''){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory'))
							->paginate(25);
		}

		

		if(count($items)>0){
			$menu_id=$items[0]->menu_id;
			$category_id=$items[0]->category_id;	

			$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->limit(3)->get();

			$link_men[0] = Menu::whereid($menu_id)->first();
			$link_men[1] =Category::whereid($category_id)->first();
		}else{
			$hot_items=array();
			$link_men=array();
		}
			
		
		// return Response::json($link_men);
		return View::make('itemlist.search', array('link_menu'=> $link_men,'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting,'search'=>$search));
	}

	public function search()
	{
		/*
        if(Session::has('searchkeyword')){
            $search = Session::get('searchkeyword');
        }else{
			$search=Input::get('search') ? Input::get('search') : '';
            Session::put('searchkeyword',$search);
        }*/
		$search=Input::get('search') ? Input::get('search') : '';

        if(Session::has('sorting')){
            $sorting = Session::get('sorting');
        }else{
			$sorting=Input::get('sorting') ? Input::get('sorting') : 'comprehensive';
            Session::put('sorting',$sorting);
        }

        $items=$category_ids=$subcategory_ids=$menu_ids=array();
		$menu_ids=Menu::where('name','like', '%'.$search.'%')->orwhere('name_mm','like', '%'.$search.'%')->orwhere('search_key_mm','like', '%'.$search.'%')->pluck('id');
		$category_ids=Category::where('name','like', '%'.$search.'%')->orwhere('name_mm','like', '%'.$search.'%')->orwhere('search_key_mm','like', '%'.$search.'%')->pluck('id');
		$subcategory_ids=SubCategory::where('name','like', '%'.$search.'%')->orwhere('name_mm','like', '%'.$search.'%')->orwhere('search_key_mm','like', '%'.$search.'%')->pluck('id');
		$shop_ids=Shop::where('name','like', '%'.$search.'%')->orwhere('name_mm','like', '%'.$search.'%')->orwhere('search_key_mm','like', '%'.$search.'%')->pluck('id');

		if($sorting == 'comprehensive'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orwhere('search_key_mm','like','%'.$search.'%')
							->orwhere('menu_id','=',$menu_ids)
							->orwhere('category_id','=',$category_ids)
							->orwhere('subcategory_id','=',$subcategory_ids)
							->orwhere('shop_id','=',$shop_ids)
							->paginate(25);

			/*$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orwhere('search_key_mm','like','%'.$search.'%')
							->paginate(25);*/
		}elseif($sorting == 'popularity'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							/*->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})*/
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orwhere('search_key_mm','like','%'.$search.'%')
							->where('view_counts','!=','0')
							->orwhere('menu_id','=',$menu_ids)
							->orwhere('category_id','=',$category_ids)
							->orwhere('subcategory_id','=',$subcategory_ids)						
							->orderby('view_counts','desc')
							->orwhere('shop_id','=',$shop_ids)
							->paginate(25);
							
		}elseif($sorting == 'new'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							/*->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})*/
							->orwhere('menu_id','=',$menu_ids)
							->orwhere('category_id','=',$category_ids)
							->orwhere('subcategory_id','=',$subcategory_ids)
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orwhere('search_key_mm','like','%'.$search.'%')
							->orderby('id','desc')
							->orwhere('shop_id','=',$shop_ids)
							->paginate(25);
			
		}elseif($sorting == 'discounts'){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory','itemcategory'))
							/*->whereHas('menu',function($query) use ($search){
									$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('category',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})
							->whereHas('subcategory',function($query) use ($search){
								$query->where('name','like', '%'.$search.'%')
									  ->orwhere('name_mm','like', '%'.$search.'%');
							})*/
							->orwhere('menu_id','=',$menu_ids)
							->orwhere('category_id','=',$category_ids)
							->orwhere('subcategory_id','=',$subcategory_ids)
							->orwhere('name','like','%'.$search.'%')
							->orwhere('name_mm','like','%'.$search.'%')
							->orwhere('search_key_mm','like','%'.$search.'%')
							->orderby('id','desc')
							->where('discount','!=','0')
							->orderby('discount','desc')
							->orwhere('shop_id','=',$shop_ids)
							->paginate(25);
		}else{

		}

		if($search==''){
			$items = Item::with(array('itemdetail','itemthumbimages','menu','category','subcategory'))
							->paginate(25);
		}

		

		if(count($items)>0){
			$menu_id=$items[0]->menu_id;
			$category_id=$items[0]->category_id;	

			$hot_items = Item::with(array('itemdetail','itemthumbimages'))
							->wheremenu_id($menu_id)
							->wherecategory_id($category_id)
							->limit(3)->get();

			$link_men[0] = Menu::whereid($menu_id)->first();
			$link_men[1] =Category::whereid($category_id)->first();
		}else{
			$hot_items=array();
			$link_men=array();
		}
			
		return View::make('itemlist.search', array('link_menu'=> $link_men,'items'=> $items, 'hot_items'=> $hot_items, 'sorting'=> $sorting,'search'=>$search));
	}



}
