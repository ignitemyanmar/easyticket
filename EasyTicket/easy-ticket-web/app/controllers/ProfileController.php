<?php

class ProfileController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($id)
	{
		$me = User::find($id);
		$order = Order::with('orderdetail')->whereuser_id($id)->get();
		$i = 0;
		foreach ($order as $rows) {
			$shipping = Shipping::whereid($rows->shipping_id)->whereuser_id($id)->first();
			$shipping['township'] = Township::whereid($shipping->township_id)->pluck('name');
			$shipping['city'] = City::whereid($shipping->city_id)->pluck('name');
			$order[$i]['shipping'] = $shipping;
			$i++;
		}
		$wishitem = WishItem::whereuser_id($id)->get();
		$i = 0;
		foreach ($wishitem as $rows) {
			$item = Item::with(array('itemdetail','itemthumbimages'))->whereid($rows->item_id)->first();
			$wishitem[$i]['item'] = $item;
			$i++;
		}
		$i = 0;
		$payment = Payment::whereuser_id($id)->get();
		foreach ($payment as $rows) {
			$payment[$i]['ref_id'] = Order::whereid($rows->order_id)->pluck('ref_id');
			$i++;
		}
		//return Response::json($me);
		return View::make('profile.index', array('me' => $me, 'order' => $order, 'wish_items' => $wishitem, 'payment' => $payment));
	}

	public function removewishitem($user_id, $item_id){
		$wishitem = WishItem::whereuser_id($user_id)->whereitem_id($item_id)->first();

		if(count($wishitem) > 0){
			$wishitem->delete();
		}
		return Redirect::to(URL::previous());
	}

	public function edit($id){
		$input = Input::all();
		/*$user = array(
              'email' => Input::get('email'),
              'password' => Input::get('current_password')
        );
          
        if (Auth::attempt($user)) {*/
        	$user = User::find($id);
        	$user->name = $input['name'];
        	$user->address = $input['address'];
        	$user->phone = $input['phone'];
        	if(isset($input['file_name']) && $input['file_name']){
        		$user->image = $input['file_name'];
        	}
        	if($input['new_password']){
        		$user->password = $input['new_password'];
        	}
        	$user->update();
        	return Redirect::to(URL::previous());
        /*}else{
        	return Redirect::to(URL::previous());
        }*/
	}
}