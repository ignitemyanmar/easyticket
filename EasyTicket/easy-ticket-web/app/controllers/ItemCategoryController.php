<?php

class ItemCategoryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$objitemcategory=Category::with(array('itemcategories'))
					->whereHas('itemcategories', function($query) {
				 	})
				    ->get();
		if($objitemcategory){
			$i=0;
			foreach ($objitemcategory as $row) {
				$objitemcategory[$i]=$row;
				$objmenu=Menu::whereid($row->menu_id)->first();
				$objitemcategory[$i]['menu']=$objmenu->name.' ( '.$objmenu->name_mm.' )';
				$j=0;
				foreach ($row->itemcategories as $nestrow) {
					$objsubcategory=SubCategory::whereid($nestrow->subcategory_id)->first();
					$objitemcategory[$i]->itemcategories[$j]['subcategory']=$objsubcategory->name.' ( '. $objsubcategory->name_mm. ' )';
					$j++;
				}
				$i++;
			}
		}
		// return Response::json($objitemcategory);
		return View::make('itemcategory.list', array('response'=>$objitemcategory));
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
		// return Response::json($response);
		return View::make('itemcategory.add', array('response'=>$response));

		/*$objitemcategory =SubCategory::orderBy('name')->get();
		return View::make('itemcategory.add', array('response'=>$objitemcategory));*/
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$subcategory_id=Input::get('subcategory');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/itemcategory/create');
		}
		$check_exiting =ItemCategory::wherename($name)->wherename_mm($name_mm)->first();
		if(count($check_exiting)>0){
			$response['status'] =0;
			return Redirect::to('/itemcategory')->with('message', $response);
		}
		$objitemcategory= new ItemCategory();
		$objitemcategory->subcategory_id=$subcategory_id;
		$objitemcategory->name=$name;
		$objitemcategory->name_mm=$name_mm;
		$objitemcategory->alias=strtolower(str_replace(' ', '-', $name));
		$objitemcategory->alias_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objitemcategory->search_key_mm=$search_key_mm;
		$objitemcategory->image=$image != null ? $image : '-';
		$objitemcategory->save();
		$response['status'] =1;
		return Redirect::to('/itemcategory')->with('message', $response);
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
		$objsubcategory =SubCategory::orderBy('name', 'asc')->get();
		$response=ItemCategory::find($id);
		$objmenu=Menu::all();
		$response['menu']=$objmenu;
		$subcategory_id=ItemCategory::whereid($id)->pluck('subcategory_id');
		$category_id=SubCategory::whereid($subcategory_id)->pluck('category_id');
		$menu_id=Category::whereid($category_id)->pluck('menu_id');
		$response['menu_id']=$menu_id;
		$response['category_id']=$category_id;
		$response['subcategory_id']=$subcategory_id;
		$response['category']=Category::whereid($category_id)->get();
		$response['subcategory']=SubCategory::whereid($subcategory_id)->get();
		// return Response::json($response);
		return View::make('itemcategory.edit', array('response'=>$response,'subcategory'=>$objsubcategory));
		
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$subcategory_id=Input::get('subcategory');
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$image=Input::get('gallery1');
		$oldimage=Input::get('hdimage');
		if(!$name && !$name_mm){
			return Redirect::to('/itemcategory/create');
		}
		$check_exiting =ItemCategory::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$response['status'] =1;
			$response['info'] ='This record is already exit';
			return Redirect::to('/itemcategory')->with('message', $response);
		}

		$objitemcategory= ItemCategory::find($id);
		$objitemcategory->subcategory_id=$subcategory_id;
		$objitemcategory->name=$name;
		$objitemcategory->name_mm=$name_mm;
		$objitemcategory->search_key_mm=$search_key_mm;
		if($image)
		$objitemcategory->image=$image;
		else
		$objitemcategory->image=$oldimage;
		$objitemcategory->update();
		$response['status'] =1;
		$response['info'] ="Successfully update one record.";
		return Redirect::to('/itemcategory')->with('message', $response);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=Item::whereitemcategory_id($id)->first();
		if($check_exiting){
			$response['status']=0;
			$response['info']="You can't delete this record for has links.";
			return Redirect::to('itemcategory')->with('message',$response);
		}
		ItemCategory::whereid($id)->delete();
		$response['status']=1;
		$response['info']='Successfully delete one record.';
		return Redirect::to('itemcategory')->with('message',$response);
	}


}