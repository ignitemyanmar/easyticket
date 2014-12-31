<?php

class SubCategoryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$objsubcategory=Menu::with(array('subcategories'))
					->whereHas('subcategories', function($query) {
				 	})
				 	->orderBy('id','asc')
				    ->get();
		if($objsubcategory){
			$i=0;
			foreach ($objsubcategory as $row) {
				$objsubcategory[$i]=$row;
				$j=0;
				foreach ($row->subcategories as $nestrow) {
					$objcategory=Category::whereid($nestrow->category_id)->first();
					$objsubcategory[$i]->subcategories[$j]['category']=$objcategory->name.' ( '. $objcategory->name_mm. ' )';
					$j++;
				}
				$i++;
			}
		}
		// return Response::json($objsubcategory);
		return View::make('subcategory.list', array('response'=>$objsubcategory));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$objmenu 		=Menu::orderBy('name')->get();
		$objcategory =Category::orderBy('name')->get();
		$response['menu']=$objmenu;
		$response['category']=$objcategory;
		
		return View::make('subcategory.add', array('response'=>$response));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$category_id=Input::get('category');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$image=Input::get('gallery1');
		$itemcode_prefix=Input::get('itemcode_prefix');
		if(!$name && !$name_mm){
			return Redirect::to('/category/create');
		}
		$check_exiting =SubCategory::wherename($name)->wherename_mm($name_mm)->first();
		if($check_exiting){
			$response['status'] =0;
			return Redirect::to('/subcategory')->with('message', $response);
		}

		$objsubcategory= new SubCategory();
		$objsubcategory->category_id=$category_id;
		$objsubcategory->name=$name;
		$objsubcategory->name_mm=$name_mm;
		$objsubcategory->alias=strtolower(str_replace(' ', '-', $name));
		$objsubcategory->name_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objsubcategory->image=$image;
		// $objsubcategory->itemcode_prefix=$itemcode_prefix;
		$objsubcategory->save();
		$response['status'] =1;
		return Redirect::to('/subcategory')->with('message', $response);
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
		$objsubcategory=SubCategory::find($id);
		$objcategory=Category::orderBy('name')->get();
		$arr_category=array();
		if($objcategory){
			foreach ($objcategory as $row) {
				$temp['id']=$row->id;
				$temp['name']=$row->name;
				$temp['name_mm']=$row->name_mm;
				$arr_category[]=$temp;
			}
		}
		$objsubcategory['category']=$arr_category;
		$objsubcategory['menu']=Menu::all();
		$menu_id=Category::whereid($objsubcategory->category_id)->pluck('menu_id');
		$objsubcategory['menu_id']=$menu_id;
		// return Response::json($objsubcategory);
		return View::make('subcategory.edit', array('response'=>$objsubcategory));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$objsubcategory= SubCategory::find($id);
		$category_id=Input::get('category');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$itemcode_prefix=Input::get('itemcode_prefix');
		$image=Input::get('gallery1');
		$oldimage=Input::get('hdimage');
		if(!$name && !$name_mm){
			return Redirect::to('/subcategory/create');
		}
		$check_exiting =SubCategory::wherecategory_id($objsubcategory->category_id)->wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ='This record is already exit';
			return Redirect::to('/subcategory')->with('message', $response);
		}

		$objsubcategory->category_id=$category_id;
		$objsubcategory->name=$name;
		$objsubcategory->name_mm=$name_mm;
		$objsubcategory->search_key_mm=$search_key_mm;
		// $objsubcategory->itemcode_prefix=$itemcode_prefix;
		if($image)
		$objsubcategory->image=$image;
		else
		$objsubcategory->image=$oldimage;
		$objsubcategory->update();
		$response['status'] =0;
		$response['info'] ="Successfully update one record.";
		return Redirect::to('/subcategory')->with('message', $response);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=Item::wheresubcategory_id($id)->first();
		if($check_exiting){
			$response['status']=0;
			$response['info']="You can't delete this record for has links.";
			return Redirect::to('subcategory')->with('message',$response);
		}
		SubCategory::whereid($id)->delete();
		$response['status']=1;
		$response['info']='Successfully delete one record.';
		return Redirect::to('subcategory')->with('message',$response);
	}

}