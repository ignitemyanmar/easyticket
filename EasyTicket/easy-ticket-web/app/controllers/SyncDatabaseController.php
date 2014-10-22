<?php
class SyncDatabaseController extends BaseController
{
	public $ftp_server			= '107.170.39.245';
	public $ftp_user_name		= 'easyticket';
	public $ftp_user_pass		= 'easyticket';
	public $local_file_dir		= 'remote_file/';
	public $remote_file_dir		= '/public_html/remote_file/';

	public function exportJson(){
		$saleOrderId = SaleOrder::lists('id');
		if($saleOrderId){
					
			$saleOrders 	= SaleOrder::wherein('id', $saleOrderId)->get()->toarray();
			$saleItems		= SaleItem::wherein('order_id', $saleOrderId)->get()->toarray();

			//write the json file.
			$today_sale_order = fopen('remote_file/today_sale_order.json', 'w+');
			fwrite($today_sale_order, json_encode($saleOrders));
			fclose($today_sale_order);

			$today_sale_item = fopen('remote_file/today_sale_item.json', 'w+');
			fwrite($today_sale_item, json_encode($saleItems));
			fclose($today_sale_item);

			echo "OK, Success.";
		}
	}

	public function pushJsonToServer(){
		$file 			= $this->local_file_dir.'today_sale_item.json';
		$remote_file 	= $this->remote_file_dir.'today_sale_item.json';

		// set up basic connection
		$conn_id = ftp_connect($this->ftp_server);
		//dd($conn_id);
		// login with username and password
		$login_result = ftp_login($conn_id, $this->ftp_user_name, $this->ftp_user_pass);

		// upload a file
		if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
		 echo "successfully uploaded $file\n";
		} else {
		 echo "There was a problem while uploading $file\n";
		}

		// close the connection
		ftp_close($conn_id);
	}

	public function downloadJsonFromServer(){
		$local_file = $this->local_file_dir.'local_item.json';
		$server_file = $this->remote_file_dir.'today_sale_item.json';

		// set up basic connection
		$conn_id = ftp_connect($this->ftp_server);

		// login with username and password
		$login_result = ftp_login($conn_id, $this->ftp_user_name, $this->ftp_user_pass);

		// try to download $server_file and save to $local_file
		if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		    echo "Successfully written to $local_file\n";
		} else {
		    echo "There was a problem\n";
		}

		// close the connection
		ftp_close($conn_id);
	}

	public function readJson(){
		$jsonString = file_get_contents($this->local_file_dir."local_item.json");
		$orderItems = json_decode ($jsonString,true);
		return Response::json($orderItems);
	}
}