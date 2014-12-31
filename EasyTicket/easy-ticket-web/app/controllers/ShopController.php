<?php

class ShopController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$user_id=Auth::user()->id;
		$objshops=Shop::whereuser_id($user_id)->get();
		if(Auth::user()->role==8){
			$objshops=Shop::all();
		}

		$i=0;
		foreach($objshops as $row){
			$objshops[$i]['owner']=User::whereid($row->user_id)->pluck('name');
			$i++;
		}

		return View::make('shop.list', array('response'=> $objshops ));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$users=User::whererole(4)->get();
		$shopuser_id=Shop::groupBy('user_id')->lists('user_id');
		$response['user']=$users;
		$response['shopuser']=$shopuser_id;
		return View::make('shop.add',array('response'=>$response));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// $user_id=Auth::user()->id;
		$user_id=Input::get('user_id');
		$role=User::whereid($user_id)->pluck('role');
		$role=Input::get('role');
		if($role !=8){
			$shopcount=Shop::whereuser_id($user_id)->count();
			if($shopcount>=1){
				$response['info'] ="You have been created one shop. You can create only one shop.";
				return Redirect::to('/shop')->with('message', $response);
			}

		}
		

		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$phone=Input::get('phone');
		$address=Input::get('address');
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/shop/create');
		}
		$check_exiting =Shop::wherename($name)->first();
		if($check_exiting){
			$response['status'] =0;
			$response['info'] ="This Record is already exit.";
			return Redirect::to('/shop')->with('message', $response);
		}
		// $user_id=Auth::user()->id;
		$objshop= new Shop();
		$objshop->name=$name;
		$objshop->name_mm=$name_mm;
		$objshop->search_key_mm=$search_key_mm;
		$objshop->alias=strtolower(str_replace(' ', '-', $name));
		$objshop->alias_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objshop->phone=$phone;
		$objshop->address=$address;
		$objshop->image=$image;
		$objshop->user_id=$user_id;
		$objshop->save();
		$response['status']=1;
		$response['info'] ="Successfully insert one record.";
		return Redirect::to('/shop')->with('message', $response);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$response=array();
		$shopitem=Item::with('itemdetail')->whereshop_id($id)->orderBy('id','desc')->paginate(10);;
		$i=0;
		if($shopitem){
			foreach ($shopitem as $row) {
				$shopitem[$i]=$row;
				if(count($row->itemdetail)>0){
					$shopitem[$i]->price=$row->itemdetail[0]->price;
					$shopitem[$i]->oldprice=$row->itemdetail[0]->oldprice;
				}else{
					$shopitem[$i]->price=0;
					$shopitem[$i]->oldprice=0;
				}
				$i++;
			}								
		}
		$response['shopitem']=$shopitem;

		$shop = Shop::find($id);

		$shop_category = Item::whereshop_id($id)
							->selectRaw('category_id, count(*) as count')
							->groupBy('category_id')->get();
		$list = array();
		foreach ($shop_category as $rows) {
			$category 			= Category::with('subcategory')->whereid($rows->category_id)->first();
			$i = 0;
			$category['count'] 	= $rows->count;
			foreach ($category->subcategory as $sub_rows) {
				$subcategory_count = Item::whereshop_id($id)
										   ->wherecategory_id($rows->category_id)
										   ->wheresubcategory_id($sub_rows->id)->count();
				$category->subcategory[$i]['count'] = $subcategory_count;
				$i++;						   
			}
			$list[] 			= $category;
		}
		
		return View::make('shop.index', array('shop'=>$shop,'response'=>$response, 'category' => $list));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$users=User::whererole(4)->get();
		$shopuser_id=Shop::groupBy('user_id')->lists('user_id');
		$responses['user']=$users;
		$responses['shopuser']=$shopuser_id;

		$response=Shop::find($id);
		return View::make('shop.edit', array('response'=>$response, 'responses'=>$responses));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$name=Input::get('name');
		$name_mm=Input::get('name_mm');
		$search_key_mm=Input::get('search_key_mm');
		$oldimage=Input::get('hdimage');
		$phone=Input::get('phone');
		$address=Input::get('address');
		$image=Input::get('gallery1');
		if(!$name && !$name_mm){
			return Redirect::to('/shop/create');
		}
		$check_exiting =Shop::wherename($name)->wherename_mm($name_mm)->where('id','!=',$id)->first();
		if($check_exiting){
			$message['status']=1;
			$message['info'] ="This Record is already exit.";
			return Redirect::to('/shop')->with('message', $message);
		}
		$objshop= Shop::find($id);
		$objshop->name=$name;
		$objshop->name_mm=$name_mm;
		$objshop->alias=strtolower(str_replace(' ', '-', $name));
		$objshop->alias_mm=strtolower(str_replace(' ', '-', $name_mm));
		$objshop->search_key_mm=$search_key_mm;
		$objshop->phone=$phone;
		$objshop->address=$address;
		if($image)
			$objshop->image=$image;
		else
			$objshop->image=$oldimage;
		$objshop->update();
		$message['info']="Successfully update.";
		$message['status']=1;
		return Redirect::to('/shop')->with('message', $message);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=Item::whereshop_id($id)->first();
		if($check_exiting){
			$message['status']=0;
			$message['info']="You can't delete this record. Has links.";
		}

		Shop::find($id)->delete();
		$message['status']=1;
		$message['info']="Successfully delete one record.";
		return Redirect::to('/shop')->with('message', $message);
	}

	public function search($id, $category_id, $subcategory_id){
		$response=array();
		$shopitem=Item::with('itemdetail')->whereshop_id($id)->wherecategory_id($category_id)->wheresubcategory_id($subcategory_id)->orderBy('id','desc')->paginate(10);
		$i=0;
		if($shopitem){
			foreach ($shopitem as $row) {
				$shopitem[$i]=$row;
				if(count($row->itemdetail)>0){
					$shopitem[$i]->price=$row->itemdetail[0]->price;
					$shopitem[$i]->oldprice=$row->itemdetail[0]->oldprice;
				}else{
					$shopitem[$i]->price=0;
					$shopitem[$i]->oldprice=0;
				}
				$i++;
			}								
		}
		$response['shopitem']=$shopitem;

		$shop = Shop::find($id);

		$shop_category = Item::whereshop_id($id)
							->selectRaw('category_id, count(*) as count')
							->groupBy('category_id')->get();
		$list = array();
		foreach ($shop_category as $rows) {
			$category 			= Category::with('subcategory')->whereid($rows->category_id)->first();
			$i = 0;
			$category['count'] 	= $rows->count;
			foreach ($category->subcategory as $sub_rows) {
				$subcategory_count = Item::whereshop_id($id)
										   ->wherecategory_id($rows->category_id)
										   ->wheresubcategory_id($sub_rows->id)->count();
				$category->subcategory[$i]['count'] = $subcategory_count;
				$i++;						   
			}
			$list[] 			= $category;
		}
		
		return View::make('shop.index', array('shop'=>$shop,'response'=>$response, 'category' => $list));
	}


}
