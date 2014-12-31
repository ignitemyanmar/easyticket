<?php

class CategoryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$objcategory =Category::orderBy('id', 'desc')->get();
		if($objcategory){
			$i=0;
			foreach ($objcategory as $row) {
				$objcategory[$i]=$row;
				$objmenu=Menu::whereid($row->menu_id)->first();
				$objcategory[$i]['menu']=$objmenu->name.' ('.$objmenu->name_mm.')';
			$i++;	
			}
		}
		return View::make('category.list', array('response'=>$objcategory));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$objcategory =Menu::orderBy('name')->get();
		return View::make('category.add', array('response'=>$objcategory));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$menu=Input::get('menu');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$itemcode_prefix=Input::get('itemcode_prefix');
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/category/create');
		}
		$check_exiting =Category::wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			$response ="This record is already exit.";
			return Redirect::to('/category')->with('message', $response);
		}

		$objcategory= new Category();
		$objcategory->menu_id=$menu;
		$objcategory->name=$name;
		$objcategory->name_mm=$name_mm;
		$objcategory->search_key_mm=$search_key_mm;
		$objcategory->itemcode_prefix=$itemcode_prefix;
		$objcategory->alias=strtolower(str_replace(' ', '-', $name));
		$objcategory->name_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objcategory->image=$image;
		$objcategory->save();
		$response ="Successfully save one record.";
		return Redirect::to('/category')->with('message', $response);
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
		$objcategory=Category::find($id);
		$objmenu=Menu::orderBy('name')->get();
		$arr_menu=array();
		if($objmenu){
			foreach ($objmenu as $row) {
				$temp['id']=$row->id;
				$temp['name']=$row->name;
				$temp['name_mm']=$row->name_mm;
				$arr_menu[]=$temp;
			}
		}
		$objcategory['menu']=$arr_menu;
		return View::make('category.edit', array('response'=>$objcategory));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$menu=Input::get('menu');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$oldimage=Input::get('hdimage');
		$itemcode_prefix=Input::get('itemcode_prefix');
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/category/create');
		}
		$check_exiting =Category::wheremenu_id($menu)->wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response ="This record is already exit.";
			return Redirect::to('/category')->with('message', $response);
		}

		$objcategory= Category::find($id);
		$objcategory->menu_id=$menu;
		$objcategory->name=$name;
		$objcategory->name_mm=$name_mm;
		$objcategory->search_key_mm=$search_key_mm;
		$objcategory->itemcode_prefix=$itemcode_prefix;
		$objcategory->alias=strtolower(str_replace(' ', '-', $name));
		$objcategory->name_mm=strtolower(str_replace(' ', '-', $name_mm));
		if($image)
		$objcategory->image=$image;
		else
		$objcategory->image=$oldimage;
		$objcategory->update();
		$response ="Successfully update this record.";
		return Redirect::to('/category')->with('message', $response);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=SubCategory::wherecategory_id($id)->first();
		$check_exiting1=Size::wherecategory_id($id)->first();
		$check_exiting2=Item::wherecategory_id($id)->first();
		if($check_exiting || $check_exiting1 || $check_exiting2){
			$message['status']=0;
			$message['info']="You can't delete this record. Has links.";
		}

		Category::whereid($id)->delete();
		$response="Successfully delete one record.";
		return Redirect::to('/category')->with('message', $response);
	}

}