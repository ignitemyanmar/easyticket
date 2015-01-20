<?php
class TestController extends BaseController
{
	public function autoRun(){
		$saleOrder = SaleOrder::all();
		foreach ($saleOrder as $rows) {
			$saleItem = SaleItem::whereorder_id($rows->id)->get();
			foreach ($saleItem as $row) {
				$item = SaleItem::whereid($row->id)->first();
				$item->agent_id = $rows->agent_id;
				$item->update();
			}
		}
		return Response::json('success');
	}
}