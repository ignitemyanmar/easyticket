<?php
class ItemPopupController extends \BaseController {
	public function storeshoppopup()
	{
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('image');
		$check_exiting =Shop::wherename($name)->whereuser_id(Auth::user()->id)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objshop= new Shop();
		$objshop->user_id=Auth::user()->id;
		$objshop->name=$name;
		$objshop->name_mm=$name_mm;
		$objshop->search_key_mm=$search_key_mm;
		$objshop->image=$image;
		$objshop->save();
        $shop=Shop::orderBy('name', 'asc')->whereuser_id(Auth::user()->id)->get();
		return Response::json($shop);
	}

	public function storebrandpopup()
	{
		// $cbomenuid=Input::get('cbomenuid');
		$menu_id=Input::get('menu_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('image');
		$check_exiting =Brand::wherename($name)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objbrand= new Brand();
		$objbrand->menu_id=$menu_id;
		$objbrand->name=$name;
		$objbrand->name_mm=$name_mm;
		$objbrand->search_key_mm=$search_key_mm;
		$objbrand->image=$image;
		$objbrand->save();
        $brand=Brand::wheremenu_id($menu_id)->orderBy('name', 'asc')->get();
		return Response::json($brand);
	}

	public function brandlistbymenu($menuid){
		$brand=Brand::wheremenu_id($menuid)->orderBy('name', 'asc')->get();
		return View::make('item.ajax.brand', array('response'=>$brand));
		// return Response::json($brand);
	}

	public function storecategorypopup()
	{
		$menu_id=Input::get('cbomenuid');
		// $menu_id=Input::get('menu_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('image');
		$check_exiting =Category::wheremenu_id($menu_id)->wherename($name)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objcategory= new Category();
		$objcategory->menu_id=$menu_id;
		$objcategory->name=$name;
		$objcategory->name_mm=$name_mm;
		$objcategory->search_key_mm=$search_key_mm;
		$objcategory->image=$image;
		$objcategory->save();
        $category=Category::wheremenu_id($menu_id)->orderBy('name', 'asc')->get();
		return Response::json($category);
	}

	public function storesubcategorypopup()
	{
		$category_id=Input::get('cbocategoryid');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('image');
		$check_exiting =SubCategory::wherecategory_id($category_id)->wherename($name)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objsubcategory= new SubCategory();
		$objsubcategory->category_id=$category_id;
		$objsubcategory->name=$name;
		$objsubcategory->name_mm=$name_mm;
		$objsubcategory->search_key_mm=$search_key_mm;
		$objsubcategory->image=$image;
		$objsubcategory->save();
        $subcategory=SubCategory::wherecategory_id($category_id)->orderBy('name', 'asc')->get();
		return Response::json($subcategory);
	}

	public function storesizepopup()
	{
		$category_id=Input::get('cbocategoryid');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$check_exiting =ItemSize::wherecategory_id($category_id)->wherename($name)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objsize= new ItemSize();
		$objsize->category_id=$category_id;
		$objsize->name=$name;
		$objsize->name_mm=$name_mm;
		$objsize->save();
        $size=ItemSize::wherecategory_id($category_id)->orderBy('name', 'asc')->get();
		return Response::json($size);
	}

	public function storecolorpopup()
	{
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$check_exiting =Color::wherename($name)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objcolor= new Color();
		$objcolor->name=$name;
		$objcolor->name_mm=$name_mm;
		$objcolor->save();
        $color=Color::orderBy('name', 'asc')->get();
		return Response::json($color);
	}

	public function storeitemcategorypopup()
	{
		$subcategory_id=Input::get('subcategory_id');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('image');
		$check_exiting =ItemCategory::wheresubcategory_id($subcategory_id)->wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objitemcategory= new ItemCategory();
		$objitemcategory->subcategory_id=$subcategory_id;
		$objitemcategory->name=$name;
		$objitemcategory->name_mm=$name_mm;
		$objitemcategory->search_key_mm=$search_key_mm;
		$objitemcategory->image=$image;
		$objitemcategory->save();
        $itemcategory=ItemCategory::wheresubcategory_id($subcategory_id)->orderBy('name', 'asc')->get();
		return Response::json($itemcategory);
	}

	public function storeqtyrangepopup()
	{
		$start_qty=Input::get('start_qty');
		$end_qty=Input::get('end_qty');
		$check_exiting =QtyrangeforPrice::wherestartqty($start_qty)->whereendqty($end_qty)->first();
		if($check_exiting){
			return 'This record is already exit';
		}
		$objqtyrange= new QtyrangeforPrice();
		$objqtyrange->startqty=$start_qty;
		$objqtyrange->endqty=$end_qty;
		$objqtyrange->save();
        $qtyrange=QtyrangeforPrice::orderBy('id', 'desc')->get();
		return Response::json($qtyrange);
	}

}

