<?php

class QtyrangeforPriceController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$objlist=QtyrangeforPrice::orderBy('id','Desc')->paginate(12);
		return View::make('qtyrangeforprice.list', array('response'=> $objlist));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('qtyrangeforprice.add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$startqty=Input::get('startqty');
		$endqty=Input::get('endqty');
		$check_exiting=QtyrangeforPrice::wherestartqty($startqty)->whereendqty($endqty)->first();
		if($check_exiting){
			$message['status']=0;
			$message['info']="This record is already exit.";
			return Redirect::to('qtyrangeforprice')->with('message', $message);
		}
		$objquantityrnage=new QtyrangeforPrice();
		$objquantityrnage->startqty=$startqty;
		$objquantityrnage->endqty=$endqty;
		$objquantityrnage->save();
		$message['status']=1;
		$message['info']="Successfully save one record.";
		return Redirect::to('qtyrangeforprice')->with('message', $message);

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
		$response=QtyrangeforPrice::find($id);
		return View::make('qtyrangeforprice.edit', array('response'=> $response));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$startqty=Input::get('startqty');
		$endqty=Input::get('endqty');
		$check_exiting=QtyrangeforPrice::wherestartqty($startqty)->whereendqty($endqty)->where('id','!=',$id)->first();
		if($check_exiting){
			$message['status']=0;
			$message['info']="This record is already exit.";
			return Redirect::to('qtyrangeforprice')->with('message', $message);
		}
		$objquantityrnage=QtyrangeforPrice::find($id);
		$objquantityrnage->startqty=$startqty;
		$objquantityrnage->endqty=$endqty;
		$objquantityrnage->update();
		$message['status']=1;
		$message['info']="Successfully update one record.";
		return Redirect::to('qtyrangeforprice')->with('message', $message);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$check_exiting=ItempriceByQty::wherepriceqtyrange_id($id)->first();
		if($check_exiting){
			$message['status']=0;
			$message['info']="This record can't be delete for have transactions.";
			return Redirect::to('qtyrangeforprice')->with('message', $message);
		}
		QtyrangeforPrice::whereid($id)->delete();
		$message['status']=1;
		$message['info']="Successfully delete one record.";
		return Redirect::to('qtyrangeforprice')->with('message', $message);
	}

}