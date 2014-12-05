<?php
class SyncDatabaseController extends BaseController
{
	public $ftp_server			= '107.170.39.245';
	public $ftp_user_name		= 'easyticket';
	public $ftp_user_pass		= 'easyticket';
	public $local_file_dir		= "remote_file/";
	public $remote_file_dir		= 'public_html/easyticket/public/remote_file/';

	public function index(){
		return View::make('sync.index');
	}

	/**
	* Upload from Clent.
	*/
 	public function pushJsonToServer(){
		$fileName = 'today_sale_'.$this->getDate().'.json';
		$fromFile = $this->getFile($fileName);
		$toFile	  = $this->remote_file_dir.$fileName;
		if($this->exportSaleOrderJson($fileName)){
			if($this->upload($fromFile, $toFile)){
				$curl = curl_init( "http://easyticket.com.mm/writetodatabase" );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				if($response){
					return Response::json(json_decode($response));
				}else{
					$response['status_code']  = 0; // 0 is error.
					$response['message'] = "Can't import data!.";
					return Response::json($response);
				}
			}
		}else{
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "Can't export data!.";
			return Response::json($response);
		}
		
	}

	public function writeJsonToDatabase(){
		$fileName = 'today_sale_'.$this->getDate().'.json';
		$importSaleOrder = $this->importSaleOrderJson($fileName);
		return $importSaleOrder;
	}

	public function exportSaleOrderJson($fileName){
		$saleOrders 	= SaleOrder::with('saleitems')->where('created_at','Like','%'.$this->getDate().'%')->get()->toarray();
		if($saleOrders){
			$this->saveFile($fileName, $saleOrders);
			return true;
		}else{
			return false;
		}
		
	}

	public function downloadJsonfromServer(){
		$fileName = 'bus_occurance_'.$this->getDate().'.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( "http://easyticket.com.mm/exportbusjson/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importBusOccurance($fileName);
				if($importData){
					return Response::json($importData);
				}
			}else{
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "Can't export data from server!.";
			return Response::json($response);
		}
	}

	public function exportBusOccurance($fileName){
		$end_days_in_month=cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
		$busOccurance = BusOccurance::where('departure_date','>=',date('Y-m',strtotime($this->getDate())).'-01')
										->where('departure_date','<=',date('Y-m',strtotime($this->getDate())).'-'.$end_days_in_month)->get()->toarray();
		if($busOccurance){
			$this->saveFile($fileName, $busOccurance);
			return true;
		}else{
			return false;
		}
	}

	public function importBusOccurance($fileName){
		$busOccurance = $this->readJson($fileName);
		if($busOccurance){
			$duplicateBus 	= array();
			$successBus 	= array();
			$errorBus 		= array();
			foreach ($busOccurance as $rows) {
				$busOccuran = BusOccurance::whereid($rows['id'])->first();
				if(!$busOccuran){
					$busOccuran = BusOccurance::create($rows);
					if($busOccuran){
						array_push($successBus, $busOccuran->toarray());
					}else{
						array_push($errorBus, $rows->toarray());
					}
				}else{
					array_push($duplicateBus, $busOccuran->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateBus']= $duplicateBus;
			$response['errorBus']	 = $errorBus;
			$response['successBus']  = $successBus;
			return $response;
		}
	}

	public function importSaleOrderJson($fileName){
		$saleOrders = $this->readJson($fileName);
		$duplicateSaleOrders 	= array();
		$duplicateSaleItems 	= array();
		$errorSaleOrders 		= array();
		$errorSaleItems	 		= array();
		$successSaleOrders 		= array();
		$successSaleItems	 	= array();
		if($saleOrders){
			foreach ($saleOrders as $rows) {
				$saleOrder = SaleOrder::whereid($rows['id'])->first();
				if(!$saleOrder){
					$newSaleOrder = $rows;
					$saleOrder = SaleOrder::create($newSaleOrder);
					if($saleOrder){
						array_push($successSaleOrders, $saleOrder->toarray());
						foreach ($rows['saleitems'] as $row) {
							$saleItem = SaleItem::whereid($row['id'])->whereorder_id($row['order_id'])->first();
							if(!$saleItem){
								$saleItem = SaleItem::create($row);
								if($saleItem){
									array_push($successSaleItems, $saleItem->toarray());
								}else{
									array_push($errorSaleItems, $row->toarray());
								}
							}else{
								array_push($duplicateSaleItems, $saleItem->toarray());
							}
						}
					}else{
						array_push($errorSaleOrders, $rows->toarray());
					}
					
				}else{
					array_push($duplicateSaleOrders, $saleOrder->toarray());
				}
			}
			$response['status_code']  = 1; // 1 is success.
			$response['message'] = "Successfully your data was saved.";
			$response["duplicateSaleOrders"] = $duplicateSaleOrders;
			$response["duplicateSaleItems"] = $duplicateSaleItems;
			$response["errorSaleOrders"] = $errorSaleOrders;
			$response["errorSaleItems"] = $errorSaleItems;
			$response["successSaleOrders"] = $successSaleOrders;
			$response["successSaleItems"] = $successSaleItems;

			return $response;
		}
	}

	public function saveFile($fileName,$arrList){
		if($fileName && is_array($arrList)){
			try {
				$fileDir = "remote_file/".$fileName;
				$fileData = fopen($fileDir, 'w+');
				fwrite($fileData, json_encode($arrList));
				fclose($fileData);
				chmod($fileDir,0777);
			} catch (Exception $e) {
				return $e;
			}
			
		}
	}

	public function getFile($fileName){
		$file = $this->local_file_dir.$fileName;
		return $file;
	}

	public function connect(){
		// set up basic connection
		try {
			$conn_id = ftp_connect($this->ftp_server);
			if($conn_id){
				// login with username and password
				$login_result = ftp_login($conn_id, $this->ftp_user_name, $this->ftp_user_pass);
				if($login_result){
					return $conn_id;
				}
			}else{
				return false;
			}
		} catch (Exception $e) {
			return $e;
		}
		
		
	}

	public function upload($fromFile, $toFile){
		try {
			$conn_id = $this->connect();
			if($conn_id){
				// upload a file
				if (ftp_put($conn_id, $toFile, $fromFile, FTP_ASCII)) {
				 	return true;
				} else {
				 	return false;
				}
			}
			// close the connection
			ftp_close($conn_id);
		} catch (Exception $e) {
			return $e;
		}
		
	}

	public function download($fromFile, $toFile){
		try {
			$conn_id = $this->connect();
			if($conn_id){
				// try to download $server_file and save to $local_file
				if (ftp_get($conn_id, $toFile, $fromFile, FTP_BINARY)) {
				    return true;
				} else {
				    return false;
				}
			}
			// close the connection
			ftp_close($conn_id);
		} catch (Exception $e) {
			return $e;
		}
	}

	public function readJson($fileName){
		$jsonString = file_get_contents("remote_file/".$fileName);
		$jsonArr = json_decode ($jsonString,true);
		return $jsonArr;
	}
}