<?php
class SyncDatabaseController extends BaseController
{
	public $ftp_server			= '128.199.81.168';
	public $ftp_user_name		= 'public';
	public $ftp_user_pass		= 'elite@ft';
	public $local_file_dir		= 'remote_file/';
	public $remote_file_dir		= 'remote_file/';
	public $domain				= 'http://mdm.easyticket.com.mm';

	public function index(){
		if($this->operator_id == 0){
			Auth::logout();
            return Redirect::to('/403');
		}
		return View::make('sync.index');
	}

	/**
	* Upload Trip from Clent.
	*/
 	public function pushTripJsonToServer($sync_id){
 		$syncDatetime = $this->getSysDateTime();
 		$zipFile  = 'client-'.$this->operatorgroup_id.'-trip.zip';
		$fileName = 'client-'.$this->operatorgroup_id.'-trip.json';
		$fromFile = $this->getFile($zipFile);
		$toFile	  = $this->remote_file_dir.$zipFile;
		$startDate	  = $this->getSysDateTime();
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$startDate 	  = $this->getSysDate();
		}
		if($this->exportTrip($this->operatorgroup_id,$fileName,$startDate) == "true"){
			chmod($this->getFile($zipFile), 0777);
			if($this->upload($fromFile, $toFile, $sync_id)){
				$response = array();
				$response['message'] = 'Importing your uploaded data.';
				$this->saveFile($sync_id,$response);
				$curl = curl_init( $this->domain."/writetripjson/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				$this->saveFile($sync_id,$response);
				if($response){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $syncDatetime;
						$sync->last_sync_date 	 = $syncDatetime;
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->save();
					}
					$this->deleteFile($sync_id);
					return Response::json($response);
				}else{
					$response['status_code']  = 0; // 0 is error.
					$response['message'] = "Can't import data!.";
					return Response::json($response);
				}
			}else{
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't upload data, please check connection.";
				return Response::json($response);
			}
		}else{
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "There is no updated data yet!.";
			return Response::json($response);
		}
		
	}

	/**
	* Upload Sale Order from Clent.
	*/
 	public function pushJsonToServer($sync_id){
 		$syncDatetime = $this->getSysDateTime();
 		$zipFile  = 'client-'.$this->operatorgroup_id.'-today-sale-order.zip';
		$fileName = 'client-'.$this->operatorgroup_id.'-today-sale-order.json';
		$fromFile = $this->getFile($zipFile);
		$toFile	  = $this->remote_file_dir.$zipFile;
		$startDate	  = $this->getSysDateTime();
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$startDate = $this->getSysDate();
		}
		$check = SaleOrder::whereagent_code('0')->orwhere('agent_code','=',null)->orwhere('agent_code','LIKE','%TEMP%')->count();
		if($check > 0){
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "Please define some agent code.";
			return Response::json($response);
		}
		if($this->exportSaleOrderJson($this->operatorgroup_id,$fileName,$startDate) == "true"){
			chmod($this->getFile($zipFile), 0777);
			if($this->upload($fromFile, $toFile, $sync_id)){
				$response = array();
				$response['message'] = 'Importing your uploaded data.';
				$this->saveFile($sync_id,$response);
				$curl = curl_init( $this->domain."/writetodatabase/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				$this->saveFile($sync_id,$response);
				if($response){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->save();
					}
					//$this->pushPaymentJsonToServer();
					$this->deleteFile($sync_id);
					return Response::json($response);
				}else{
					$response['status_code']  = 0; // 0 is error.
					$response['message'] = "Can't import data!.";
					return Response::json($response);
				}
			}else{
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't upload data, please check connection.";
				return Response::json($response);
			}
		}else{
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "Please Define Agent Code [OR] There is no updated data yet!.";
			return Response::json($response);
		}
		
	}

	/**
	* Upload Sale Order from Clent.
	*/
 	public function pushPaymentJsonToServer($sync_id){
 		$syncDatetime = $this->getSysDateTime();
 		$zipFile = 'client-'.$this->operatorgroup_id.'-today-payment.zip';
		$fileName = 'client-'.$this->operatorgroup_id.'-today-payment.json';
		$fromFile = $this->getFile($zipFile);
		$toFile	  = $this->remote_file_dir.$zipFile;
		$startDate	  = $this->getSysDateTime();
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$startDate = $this->getSysDate();
		}
		if($this->exportPaymentJson($this->operatorgroup_id,$fileName,$startDate)  == "true"){
			chmod($this->getFile($zipFile), 0777);
			if($this->upload($fromFile, $toFile, $sync_id)){
				$response = array();
				$response['message'] = 'Importing your uploaded data.';
				$this->saveFile($sync_id,$response);
				$curl = curl_init( $this->domain."/writepaymentjson/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				$this->saveFile($sync_id,$response);
				if($response){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->save();
					}
					$this->deleteFile($sync_id);
					return Response::json($response);
				}else{
					$response['status_code']  = 0; // 0 is error.
					$response['message'] = "Can't import data!.";
					return Response::json($response);
				}
			}else{
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't upload data, please check connection.";
				return Response::json($response);
			}
		}else{
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "There is no updated data yet!.";
			return Response::json($response);
		}
		
	}

	/**
	* Upload Deleted Sale Order from Clent.
	*/
 	public function pushDeleteSaleOrderJsonToServer($sync_id){
 		$syncDatetime = $this->getSysDateTime();
 		$zipFile = 'client-'.$this->operatorgroup_id.'-today-delsale-order.zip';
		$fileName = 'client-'.$this->operatorgroup_id.'-today-delsale-order.json';
		$fromFile = $this->getFile($zipFile);
		$toFile	  = $this->remote_file_dir.$zipFile;
		$startDate	  = $this->getSysDateTime();
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$startDate = $this->getSysDate();
		}
		if($this->exportDeleteSaleOrderJson($this->operatorgroup_id,$fileName,$startDate) == "true"){
			chmod($this->getFile($zipFile), 0777);
			if($this->upload($fromFile, $toFile, $sync_id)){
				$response = array();
				$response['message'] = 'Importing your uploaded data.';
				$this->saveFile($sync_id,$response);
				$curl = curl_init( $this->domain."/writedelsaleorderjson/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				$this->saveFile($sync_id,$response);
				if($response){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $syncDatetime;
						$sync->last_sync_date 		= $syncDatetime;
						$sync->save();
					}
					//$this->pushPaymentJsonToServer();
					$this->deleteFile($sync_id);
					return Response::json($response);
				}else{
					$response['status_code']  = 0; // 0 is error.
					$response['message'] = "Can't import data!.";
					return Response::json($response);
				}
			}else{
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't upload data, please check connection.";
				return Response::json($response);
			}
		}else{
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "There is no updated data yet!.";
			return Response::json($response);
		}
		
	}

	/**
	 * To Import Data
	 * @/writetripjson/{fname}
	 */
	public function writeTripJsonToDatabase($fileName){
		$importTrip = $this->importTrip($fileName,'');
		return $importTrip;
	}

	/**
	 * To Import Data
	 * @/writetodatabase/{fname}
	 */
	public function writeJsonToDatabase($fileName){
		$importSaleOrder = $this->importSaleOrderJson($fileName,'');
		return $importSaleOrder;
	}

	/**
	 * To Import Data
	 * @/writepaymentjson/{fname}
	 */
	public function writePaymentJsonToDatabase($fileName){
		$importPayment = $this->importPaymentJson($fileName,'');
		return $importPayment;
	}

	/**
	 * To Import Data
	 * @/writedelsaleorderjson/{fname}
	 */
	public function writeDelSaleOrderJsonToDatabase($fileName){
		$importDeletedSaleOrder = $this->importDeleteSaleOrderJson($fileName,'');
		return $importDeletedSaleOrder;
	}
	

	/**
	 * To Sync All from Server
	 */

	public function downloadAllJsonfromServer(){
		$this->downloadUserJsonfromServer();
		$this->downloadOperatorJsonfromServer();
		$this->downloadOperatorGroupJsonfromServer();
		$this->downloadOperatorGroupUserJsonfromServer();
		$this->downloadCityJsonfromServer();
		$this->downloadClassesJsonfromServer();
		$this->downloadSeatingPlanJsonfromServer();
		$this->downloadSeatInfoJsonfromServer();
		$this->downloadTripJsonfromServer();
		$this->downloadDeleteTripJsonfromServer();
		$this->downloadExtraDestinationJsonfromServer();
		$this->downloadCloseSeatInfoJsonfromServer();
		$this->downloadAgentJsonfromServer();
		$this->downloadAgentGroupJsonfromServer();
		$this->downloadAgentCommissionJsonfromServer();
		$this->downloadCommissionTypeJsonfromServer();
		$this->downloadSaleOrderJsonfromServer();
		$this->downloadDeleteSaleOrderJsonfromServer();
		$this->downloadPaymentJsonfromServer();

		$response['status_code']  = 1;
		$response['message'] = "Successfully snyc!.";
		return Response::json($response);

	}
	
	/**
	 * To Sync Trip from Server
	 */
	public function downloadTripJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-trip.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = Trip::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exporttripjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );

		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importTrip($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync DeleteTrip from Server
	 */
	public function downloadDeleteTripJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-del-trip.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = DeleteTrip::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportdeletetripjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );

		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importDeleteTrip($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync Seating Plan from Server
	 */
	public function downloadSeatingPlanJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-seating-plan.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = SeatingPlan::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportseatingplanjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importSeatingPlan($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}		
	/**
	 * To Sync Agent from Server
	 */
	public function downloadAgentJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-agent.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = Agent::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportagentjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importAgent($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync AgentGroup from Server
	 */
	public function downloadAgentGroupJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-agentgroup.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = AgentGroup::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportagentgroupjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importAgentGroup($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync City from Server
	 */
	public function downloadCityJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-city.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = City::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportcityjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importCity($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync Extra Destination from Server
	 */
	public function downloadExtraDestinationJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-extradestination.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = ExtraDestination::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportextradestinationjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importExtraDestination($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync Classes from Server
	 */
	public function downloadClassesJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-bus-classes.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = Classes::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportclassesjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importClasses($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync AgentCommission from Server
	 */
	public function downloadAgentCommissionJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-agentcommission.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = AgentCommission::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportagentcommissionjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importAgentCommission($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync CloseSeatInfo from Server
	 */
	public function downloadCloseSeatInfoJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-closeseatinfo.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = CloseSeatInfo::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportcloseseatinfojson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importCloseSeatInfo($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync CommissionType from Server
	 */
	public function downloadCommissionTypeJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-commissiontype.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = CommissionType::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportcommissiontypejson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importCommissionType($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync OperatorGroup from Server
	 */
	public function downloadOperatorGroupJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-operatorgroup.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = OperatorGroup::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}	
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportoperatorgroupjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importOperatorGroup($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync OperatorGroupUser from Server
	 */
	public function downloadOperatorGroupUserJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-operatorgroupuser.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = OperatorGroupUser::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}	
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportoperatorgroupuserjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importOperatorGroupUser($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync User from Server
	 */
	public function downloadUserJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-user.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = User::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportuserjson/".$this->operator_id."/".$fileName."/".$syncDatetime);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importUser($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync Operator from Server
	 */
	public function downloadOperatorJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-operator.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = Operator::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime) ;
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportoperatorjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importOperator($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync SeatInfo from Server
	 */
	public function downloadSeatInfoJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-seatinfo.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = SeatInfo::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportseatinfojson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importSeatInfo($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync SaleOrder from Server
	 */
	public function downloadSaleOrderJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-today-sale-order.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = SaleOrder::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}	
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportsaleorderjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importSaleOrderJson($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync DeleteSaleOrder from Server
	 */
	public function downloadDeleteSaleOrderJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-today-delsale-order.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = DeleteSaleOrder::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}	
			
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportdeletesaleorderjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importDeleteSaleOrderJson($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}else{
					$response = array();
					$response['status_code']  = 0; // 0 is error.
					$response['message'] = $importData;
					return Response::json($response);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	/**
	 * To Sync Payment from Server
	 */
	public function downloadPaymentJsonfromServer($sync_id){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-today-payment.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$syncDatetime 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$datetime = AgentDeposit::orderBy('created_at','desc')->limit(1)->pluck('created_at');
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}	
		
		$response['message'] = "Exporting from Server...";
		$this->saveFile($sync_id, $response);
		$curl = curl_init( $this->domain."/exportpaymentjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile), $sync_id)){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile($sync_id, $response);
				$importData = $this->importPaymentJson($fileName, $sync_id);
				$this->deleteFile($sync_id);
				if($importData){
					$sync = Sync::wherename($fileName)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $fileName;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$resp = array();
			$resp['status_code']  = 0; // 0 is error.
			$resp['message'] = $response; //"Can't export data from server!.";
			return Response::json($resp);
		}
	}
	
	/**
	 * To Export Trip Data.
	 * @'/exporttripjson/{id}/{fname}/{date}'
	 */
	public function exportTrip($operator_id,$fileName,$startDate){
		$trips = null;
		if($startDate == 0){
			$trips = Trip::where('operator_id','=',$operator_id)
					       ->get()->toarray();
		}else{
			$trips = Trip::where('operator_id','=',$operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($trips){
			$this->saveFile($fileName, $trips);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Trip Data.
	 */
	public function importTrip($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$trips = $this->readJson($fileName);
			if($trips){
				$duplicateTrip 	= array();
				$successTrip 	= array();
				$errorTrip 		= array();
				$i = 0;
				foreach ($trips as $rows) {
					$trip = Trip::whereid($rows['id'])->first();
					if(!$trip){
						$trip = Trip::create($rows);
						if($trip){
							array_push($successTrip, $trip->toarray());
						}else{
							array_push($errorTrip, $rows->toarray());
						}
					}else{
						Trip::whereid($rows['id'])->update($rows);
						array_push($duplicateTrip, $trip->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($trips) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateTrip']= $duplicateTrip;
				$response['errorTrip']	 = $errorTrip;
				$response['successTrip']  = $successTrip;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export DeleteTrip Data.
	 * @'/exportdeletetripjson/{id}/{fname}/{date}'
	 */
	public function exportDeleteTrip($operator_id,$fileName,$startDate){
		$DeleteTrips = null;
		if($startDate == 0){
			$DeleteTrips = DeleteTrip::where('operator_id','=',$operator_id)->get()->toarray();
		}else{
			$DeleteTrips = DeleteTrip::where('operator_id','=',$operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($DeleteTrips){
			$this->saveFile($fileName, $DeleteTrips);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import DeleteTrip Data.
	 */
	public function importDeleteTrip($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$DeleteTrips = $this->readJson($fileName);
			if($DeleteTrips){
				$duplicateDeleteTrip 	= array();
				$successDeleteTrip 	= array();
				$errorDeleteTrip 		= array();
				$i = 0;
				foreach ($DeleteTrips as $rows) {
					if(Trip::whereid($rows['id'])->first())
						Trip::whereid($rows['id'])->delete();

					$DeleteTrip = DeleteTrip::whereid($rows['id'])->first();
					if(!$DeleteTrip){
						$DeleteTrip = DeleteTrip::create($rows);
						if($DeleteTrip){
							array_push($successDeleteTrip, $DeleteTrip->toarray());
						}else{
							array_push($errorDeleteTrip, $rows->toarray());
						}
					}else{
						DeleteTrip::whereid($rows['id'])->update($rows);
						array_push($duplicateDeleteTrip, $DeleteTrip->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($DeleteTrips) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateDeleteTrip']= $duplicateDeleteTrip;
				$response['errorDeleteTrip']	 = $errorDeleteTrip;
				$response['successDeleteTrip']  = $successDeleteTrip;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export SeatingPlan Data.
	 * @/exportseatingplanjson/{id}/{fname}/{date}
	 */
	public function exportSeatingPlan($operator_id,$fileName,$startDate){
		$seatingPlans = null;
		if($startDate == 0){
			$seatingPlans = SeatingPlan::whereoperator_id($operator_id)->get()->toarray();
		}else{
			$seatingPlans = SeatingPlan::whereoperator_id($operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($seatingPlans){
			$this->saveFile($fileName, $seatingPlans);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import SeatingPlan Data.
	 */
	public function importSeatingPlan($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$seatingPlans = $this->readJson($fileName);
			if($seatingPlans){
				$duplicateSeatingPlan 	= array();
				$successSeatingPlan 	= array();
				$errorSeatingPlan 		= array();
				$i = 0;
				foreach ($seatingPlans as $rows) {
					$seatingPlan = SeatingPlan::whereid($rows['id'])->first();
					if(!$seatingPlan){
						$seatingPlan = SeatingPlan::create($rows);
						if($seatingPlan){
							array_push($successSeatingPlan, $seatingPlan->toarray());
						}else{
							array_push($errorSeatingPlan, $rows->toarray());
						}
					}else{
						SeatingPlan::whereid($rows['id'])->update($rows);
						array_push($duplicateSeatingPlan, $seatingPlan->toarray());
					}

					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($seatingPlans) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateSeatingPlan']= $duplicateSeatingPlan;
				$response['errorSeatingPlan']	 = $errorSeatingPlan;
				$response['successSeatingPlan']  = $successSeatingPlan;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export Agent Data.
	 * @/exportagentjson/{id}/{fname}/{date}
	 */
	public function exportAgent($operator_id,$fileName,$startDate){
		$Agents = null;
		if($startDate == 0)
			$Agents = Agent::whereoperator_id($operator_id)->get()->toarray();
		else
			$Agents = Agent::whereoperator_id($operator_id)->where('created_at','>=',$startDate)->orwhere('updated_at','>=',$startDate)->get()->toarray();
		
		if($Agents){
			$this->saveFile($fileName, $Agents);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Agent Data.
	 */
	public function importAgent($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$Agents = $this->readJson($fileName);
			if($Agents){
				$duplicateAgent 	= array();
				$successAgent 		= array();
				$errorAgent 		= array();
				$i = 0;
				foreach ($Agents as $rows) {
					$Agent = Agent::whereid($rows['id'])->first();
					if(!$Agent){
						$Agent = Agent::create($rows);
						if($Agent){
							array_push($successAgent, $Agent->toarray());
						}else{
							array_push($errorAgent, $rows->toarray());
						}
					}else{
						Agent::whereid($rows['id'])->update($rows);
						array_push($duplicateAgent, $Agent->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($Agents) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateAgent']= $duplicateAgent;
				$response['errorAgent']	 = $errorAgent;
				$response['successAgent']  = $successAgent;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export AgentGroup Data.
	 * @/exportagentgroupjson/{id}/{fname}/{date}
	 */
	public function exportAgentGroup($operator_id,$fileName,$startDate){
		$AgentGroup = null;
		if($startDate == 0){
			$AgentGroups = AgentGroup::whereoperator_id($operator_id)->get()->toarray();
		}else{
			$AgentGroups = AgentGroup::whereoperator_id($operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($AgentGroups){
			$this->saveFile($fileName, $AgentGroups);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import AgentGroup Data.
	 */
	public function importAgentGroup($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$AgentGroups = $this->readJson($fileName);
			if($AgentGroups){
				$duplicateAgentGroup 	= array();
				$successAgentGroup		= array();
				$errorAgentGroup 		= array();
				$i = 0;
				foreach ($AgentGroups as $rows) {
					$AgentGroup = AgentGroup::whereid($rows['id'])->first();
					if(!$AgentGroup){
						$AgentGroup = AgentGroup::create($rows);
						if($AgentGroup){
							array_push($successAgentGroup, $AgentGroup->toarray());
						}else{
							array_push($errorAgentGroup, $rows->toarray());
						}
					}else{
						AgentGroup::whereid($rows['id'])->update($rows);
						array_push($duplicateAgentGroup, $AgentGroup->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($AgentGroups) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateAgentGroup']= $duplicateAgentGroup;
				$response['errorAgentGroup']	 = $errorAgentGroup;
				$response['successAgentGroup']  = $successAgentGroup;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}

	/**
	 * To Export City Data.
	 * @/exportcityjson/{id}/{fname}/{date}
	 */
	public function exportCity($operator_id,$fileName,$startDate){
		$Citys = null;
		if($startDate == 0){
			$Citys = City::all()->toarray();
		}else{
			$Citys = City::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($Citys){
			$this->saveFile($fileName, $Citys);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import City Data.
	 */
	public function importCity($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$Citys = $this->readJson($fileName);
			if($Citys){
				$duplicateCity 	= array();
				$successCity 	= array();
				$errorCity 		= array();
				$i = 0;
				foreach ($Citys as $rows) {
					$City = City::whereid($rows['id'])->first();
					if(!$City){
						$City = City::create($rows);
						if($City){
							array_push($successCity, $City->toarray());
						}else{
							array_push($errorCity, $rows->toarray());
						}
					}else{
						City::whereid($rows['id'])->update($rows);
						array_push($duplicateCity, $City->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($Citys) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateCity']= $duplicateCity;
				$response['errorCity']	 = $errorCity;
				$response['successCity']  = $successCity;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export ExtraDestination Data.
	 * @/exportextradestinationjson/{id}/{fname}/{date}
	 */
	public function exportExtraDestination($operator_id,$fileName,$startDate){
		$ExtraDestinations = null;
		if($startDate == 0){
			$ExtraDestinations = ExtraDestination::all()->toarray();
		}else{
			$ExtraDestinations = ExtraDestination::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($ExtraDestinations){
			$this->saveFile($fileName, $ExtraDestinations);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import ExtraDestination Data.
	 */
	public function importExtraDestination($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$ExtraDestinations = $this->readJson($fileName);
			if($ExtraDestinations){
				$duplicateExtraDestination 	= array();
				$successExtraDestination 	= array();
				$errorExtraDestination 		= array();
				$i = 0;
				foreach ($ExtraDestinations as $rows) {
					$ExtraDestination = ExtraDestination::whereid($rows['id'])->first();
					if(!$ExtraDestination){
						$ExtraDestination = ExtraDestination::create($rows);
						if($ExtraDestination){
							array_push($successExtraDestination, $ExtraDestination->toarray());
						}else{
							array_push($errorExtraDestination, $rows->toarray());
						}
					}else{
						ExtraDestination::whereid($rows['id'])->update($rows);
						array_push($duplicateExtraDestination, $ExtraDestination->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($ExtraDestinations) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateExtraDestination']= $duplicateExtraDestination;
				$response['errorExtraDestination']	 = $errorExtraDestination;
				$response['successExtraDestination']  = $successExtraDestination;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export Classes Data.
	 * @/exportclassesjson/{id}/{fname}/{date}
	 */
	public function exportClasses($operator_id,$fileName,$startDate){
		$Classess = null;
		if($startDate == 0){
			$Classess = Classes::whereoperator_id($operator_id)->get()->toarray();
		}else{
			$Classess = Classes::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($Classess){
			$this->saveFile($fileName, $Classess);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Classes Data.
	 */
	public function importClasses($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$Classess = $this->readJson($fileName);
			if($Classess){
				$duplicateClasses 	= array();
				$successClasses 	= array();
				$errorClasses 		= array();
				$i = 0;
				foreach ($Classess as $rows) {
					$Classes = Classes::whereid($rows['id'])->first();
					if(!$Classes){
						$Classes = Classes::create($rows);
						if($Classes){
							array_push($successClasses, $Classes->toarray());
						}else{
							array_push($errorClasses, $rows->toarray());
						}
					}else{
						Classes::whereid($rows['id'])->update($rows);
						array_push($duplicateClasses, $Classes->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($Classess) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateClasses']= $duplicateClasses;
				$response['errorClasses']	 = $errorClasses;
				$response['successClasses']  = $successClasses;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export AgentCommission Data.
	 * @/exportagentcommissionjson/{id}/{fname}/{date}
	 */
	public function exportAgentCommission($operator_id,$fileName,$startDate){
		$AgentCommissions = null;
		if($startDate == 0){
			$AgentCommissions = AgentCommission::all()->toarray();
		}else{
			$AgentCommissions = AgentCommission::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($AgentCommissions){
			$this->saveFile($fileName, $AgentCommissions);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import AgentCommission Data.
	 */
	public function importAgentCommission($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$AgentCommissions = $this->readJson($fileName);
			if($AgentCommissions){
				$duplicateAgentCommission 	= array();
				$successAgentCommission 	= array();
				$errorAgentCommission 		= array();
				$i = 0;
				foreach ($AgentCommissions as $rows) {
					$AgentCommission = AgentCommission::whereid($rows['id'])->first();
					if(!$AgentCommission){
						$AgentCommission = AgentCommission::create($rows);
						if($AgentCommission){
							array_push($successAgentCommission, $AgentCommission->toarray());
						}else{
							array_push($errorAgentCommission, $rows->toarray());
						}
					}else{
						AgentCommission::whereid($rows['id'])->update($rows);
						array_push($duplicateAgentCommission, $AgentCommission->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($AgentCommissions) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
					
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateAgentCommission']= $duplicateAgentCommission;
				$response['errorAgentCommission']	 = $errorAgentCommission;
				$response['successAgentCommission']  = $successAgentCommission;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file');
		}
	}
	/**
	 * To Export CloseSeatInfo Data.
	 * @/exportcloseseatinfojson/{id}/{fname}/{date}
	 */
	public function exportCloseSeatInfo($operator_id,$fileName,$startDate){
		$CloseSeatInfos = null;
		if($startDate == 0){
			$CloseSeatInfos = CloseSeatInfo::all()->toarray();
		}else{
			$CloseSeatInfos = CloseSeatInfo::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}
		if($CloseSeatInfos){
			$this->saveFile($fileName, $CloseSeatInfos);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import CloseSeatInfo Data.
	 */
	public function importCloseSeatInfo($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$CloseSeatInfos = $this->readJson($fileName);
			if($CloseSeatInfos){
				$duplicateCloseSeatInfo 	= array();
				$successCloseSeatInfo 	= array();
				$errorCloseSeatInfo 		= array();
				$i = 0;
				foreach ($CloseSeatInfos as $rows) {
					$CloseSeatInfo = CloseSeatInfo::whereid($rows['id'])->first();
					if(!$CloseSeatInfo){
						$CloseSeatInfo = CloseSeatInfo::create($rows);
						if($CloseSeatInfo){
							array_push($successCloseSeatInfo, $CloseSeatInfo->toarray());
						}else{
							array_push($errorCloseSeatInfo, $rows->toarray());
						}
					}else{
						CloseSeatInfo::whereid($rows['id'])->update($rows);
						array_push($duplicateCloseSeatInfo, $CloseSeatInfo->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($CloseSeatInfos) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateCloseSeatInfo']= $duplicateCloseSeatInfo;
				$response['errorCloseSeatInfo']	 = $errorCloseSeatInfo;
				$response['successCloseSeatInfo']  = $successCloseSeatInfo;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file');
		}
	}
	/**
	 * To Export CommissionType Data.
	 * @/exportcommissiontypejson/{id}/{fname}/{date}
	 */
	public function exportCommissionType($operator_id,$fileName,$startDate){
		$CommissionTypes = null;
		if($startDate == 0){
			$CommissionTypes = CommissionType::all()->toarray();

		}else{
			$CommissionTypes = CommissionType::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
			
		}
		if($CommissionTypes){
			$this->saveFile($fileName, $CommissionTypes);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import CommissionType Data.
	 */
	public function importCommissionType($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$CommissionTypes = $this->readJson($fileName);
			if($CommissionTypes){
				$duplicateCommissionType 	= array();
				$successCommissionType 	= array();
				$errorCommissionType 		= array();
				$i = 0;
				foreach ($CommissionTypes as $rows) {
					$CommissionType = CommissionType::whereid($rows['id'])->first();
					if(!$CommissionType){
						$CommissionType = CommissionType::create($rows);
						if($CommissionType){
							array_push($successCommissionType, $CommissionType->toarray());
						}else{
							array_push($errorCommissionType, $rows->toarray());
						}
					}else{
						CommissionType::whereid($rows['id'])->update($rows);
						array_push($duplicateCommissionType, $CommissionType->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($CommissionTypes) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateCommissionType']= $duplicateCommissionType;
				$response['errorCommissionType']	 = $errorCommissionType;
				$response['successCommissionType']  = $successCommissionType;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export OperatorGroup Data.
	 * @/exportoperatorgroupjson/{id}/{fname}/{date}
	 */
	public function exportOperatorGroup($operator_id,$fileName,$startDate){
		$OperatorGroups = null;
		if($startDate == 0){
			$OperatorGroups = OperatorGroup::whereoperator_id($operator_id)->get()->toarray();
			
		}else{
			$OperatorGroups = OperatorGroup::whereoperator_id($operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();

		}
		if($OperatorGroups){
			$this->saveFile($fileName, $OperatorGroups);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import OperatorGroup Data.
	 */
	public function importOperatorGroup($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$OperatorGroups = $this->readJson($fileName);
			if($OperatorGroups){
				$duplicateOperatorGroup 	= array();
				$successOperatorGroup 	= array();
				$errorOperatorGroup 		= array();
				$i = 0;
				foreach ($OperatorGroups as $rows) {
					$OperatorGroup = OperatorGroup::whereid($rows['id'])->first();
					if(!$OperatorGroup){
						$OperatorGroup = OperatorGroup::create($rows);
						if($OperatorGroup){
							array_push($successOperatorGroup, $OperatorGroup->toarray());
						}else{
							array_push($errorOperatorGroup, $rows->toarray());
						}
					}else{
						OperatorGroup::whereid($rows['id'])->update($rows);
						array_push($duplicateOperatorGroup, $OperatorGroup->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($OperatorGroups) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateOperatorGroup']= $duplicateOperatorGroup;
				$response['errorOperatorGroup']	 = $errorOperatorGroup;
				$response['successOperatorGroup']  = $successOperatorGroup;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export OperatorGroupUser Data.
	 * @/exportoperatorgroupuserjson/{id}/{fname}/{date}
	 */
	public function exportOperatorGroupUser($operator_id,$fileName,$startDate){
		$OperatorGroupUsers = null;
		if($startDate == 0){
			$OperatorGroupUsers = OperatorGroupUser::whereoperator_id($operator_id)->get()->toarray();

		}else{
			$OperatorGroupUsers = OperatorGroupUser::whereoperator_id($operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
			
		}
		if($OperatorGroupUsers){
			$this->saveFile($fileName, $OperatorGroupUsers);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import OperatorGroupUser Data.
	 */
	public function importOperatorGroupUser($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$OperatorGroupUsers = $this->readJson($fileName);
			if($OperatorGroupUsers){
				$duplicateOperatorGroupUser 	= array();
				$successOperatorGroupUser 	= array();
				$errorOperatorGroupUser 		= array();
				$i = 0;
				foreach ($OperatorGroupUsers as $rows) {
					$OperatorGroupUser = OperatorGroupUser::whereid($rows['id'])->first();
					if(!$OperatorGroupUser){
						$OperatorGroupUser = OperatorGroupUser::create($rows);
						if($OperatorGroupUser){
							array_push($successOperatorGroupUser, $OperatorGroupUser->toarray());
						}else{
							array_push($errorOperatorGroupUser, $rows->toarray());
						}
					}else{
						OperatorGroupUser::whereid($rows['id'])->update($rows);
						array_push($duplicateOperatorGroupUser, $OperatorGroupUser->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($OperatorGroupUsers) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateOperatorGroupUser']= $duplicateOperatorGroupUser;
				$response['errorOperatorGroupUser']	 = $errorOperatorGroupUser;
				$response['successOperatorGroupUser']  = $successOperatorGroupUser;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file');
		}
	}
	/**
	 * To Export User Data.
	 * @/exportuserjson/{id}/{fname}/{date}
	 */
	public function exportUser($operator_id,$fileName,$startDate){
		$Users = null;
		if($startDate == 0)
			$Users = User::all()->toarray();
		else
			$Users = User::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		
		if($Users){
			$this->saveFile($fileName, $Users);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import User Data.
	 */
	public function importUser($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$Users = $this->readJson($fileName);
			if($Users){
				$duplicateUser 	= array();
				$successUser 	= array();
				$errorUser 		= array();
				$i = 0;
				foreach ($Users as $rows) {
					$User = User::whereid($rows['id'])->first();
					if(!$User){
						$User = User::create($rows);
						if($User){
							array_push($successUser, $User->toarray());
						}else{
							array_push($errorUser, $rows->toarray());
						}
					}else{
						User::whereid($rows['id'])->update($rows);
						array_push($duplicateUser, $User->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($Users) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateUser']= $duplicateUser;
				$response['errorUser']	 = $errorUser;
				$response['successUser']  = $successUser;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export Operator Data.
	 * @/exportoperatorjson/{id}/{fname}/{date}
	 */
	public function exportOperator($operator_id,$fileName,$startDate){
		$Operators = null;
		if($startDate == 0)
			$Operators = Operator::all()->toarray();
		else
			$Operators = Operator::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		
		if($Operators){
			$this->saveFile($fileName, $Operators);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Operator Data.
	 */
	public function importOperator($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$Operators = $this->readJson($fileName);
			if($Operators){
				$duplicateOperator 	= array();
				$successOperator 	= array();
				$errorOperator 		= array();
				$i = 0;
				foreach ($Operators as $rows) {
					$Operator = Operator::whereid($rows['id'])->first();
					if(!$Operator){
						$Operator = Operator::create($rows);
						if($Operator){
							array_push($successOperator, $Operator->toarray());
						}else{
							array_push($errorOperator, $rows->toarray());
						}
					}else{
						Operator::whereid($rows['id'])->update($rows);
						array_push($duplicateOperator, $Operator->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($Operators) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateOperator']= $duplicateOperator;
				$response['errorOperator']	 = $errorOperator;
				$response['successOperator']  = $successOperator;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Export SeatInfo Data.
	 * @/exportseatinfojson/{id}/{fname}/{date}
	 */
	public function exportSeatInfo($operator_id,$fileName,$startDate){
		$SeatInfo = null;
		if($startDate ==0)
			$SeatInfos = SeatInfo::all()->toarray();
		else
			$SeatInfos = SeatInfo::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		
		if($SeatInfos){
			$this->saveFile($fileName, $SeatInfos);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import SeatInfo Data.
	 */
	public function importSeatInfo($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$SeatInfos = $this->readJson($fileName);
			if($SeatInfos){
				$duplicateSeatInfo 	= array();
				$successSeatInfo 	= array();
				$errorSeatInfo 		= array();
				$i = 0;
				foreach ($SeatInfos as $rows) {
					$SeatInfo = SeatInfo::whereid($rows['id'])->first();
					if(!$SeatInfo){
						$SeatInfo = SeatInfo::create($rows);
						if($SeatInfo){
							array_push($successSeatInfo, $SeatInfo->toarray());
						}else{
							array_push($errorSeatInfo, $rows->toarray());
						}
					}else{
						SeatInfo::whereid($rows['id'])->update($rows);
						array_push($duplicateSeatInfo, $SeatInfo->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($SeatInfos) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateSeatInfo']= $duplicateSeatInfo;
				$response['errorSeatInfo']	 = $errorSeatInfo;
				$response['successSeatInfo']  = $successSeatInfo;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	
	/**
	 * To Export Sale Transaction Data
	 * @/exportsaleorderjson/{id}/{fname}/{date}
	 */
	public function exportSaleOrderJson($operator_id,$fileName,$startDate){

		$saleOrders = null;


		if($startDate == 0){
			$saleOrders 	= SaleOrder::with('saleitems')->get();
		}
		else{

			$saleOrders 	= SaleOrder::with('saleitems')->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		}

		if($saleOrders){
			$this->saveFile($fileName, $saleOrders);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
		
	}
	/**
	 * To Import Trip Transaction Data.
	 */
	public function importTripJson($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
		
			$trips = $this->readJson($fileName);
			$duplicateTrips 	= array();
			$errorTrips 		= array();
			$successTrips 		= array();
			$i = 0;
			if($trips){
				foreach ($trips as $rows) {
					$trip = Trip::whereid($rows['id'])->first();
					if(!$trip){
						$trip = Trip::create($rows->toarray());
						if($trip){
							array_push($successTrips, $saleOrder->toarray());
						}else{
							array_push($errorTrips, $rows->toarray());
						}
						
					}else{
						
						$trip = Trip::whereid($rows['id'])->where('updated_at','<',$rows['updated_at'])->first();
						if($trip){
							$trip = Trip::update($rows->toarray());
							if($trip){
								array_push($successTrips, $saleOrder);
								
							}else{
								array_push($errorTrips, $rows);
							}
							
						}
						array_push($duplicateTrips, $saleOrder);
					}

					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($saleOrders) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);					
				}
				$response['status_code']  = 1; // 1 is success.
				$response['message'] = "Successfully your data was saved.";
				$response["duplicateTrips"] = $duplicateTrips;
				$response["errorTrips"] = $errorTrips;
				$response["successTrips"] = $successTrips;
				return $response;
			}else{
				$response['status_code']  = 0;
				$response['message'] = "Empty value from your uploaded file.";
				return $response;
			}

		}else{
			dd('Cann\'t open zip file.');
		}
	}
	/**
	 * To Import Sale Transaction Data.
	 */
	public function importSaleOrderJson($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
		
			$saleOrders = $this->readJson($fileName);
			$duplicateSaleOrders 	= array();
			$duplicateSaleItems 	= array();
			$duplicateSeat 			= array();
			$errorSaleOrders 		= array();
			$errorSaleItems	 		= array();
			$successSaleOrders 		= array();
			$successSaleItems	 	= array();
			$i = 0;
			if($saleOrders){
				foreach ($saleOrders as $rows) {
					$saleOrder = SaleOrder::whereid($rows['id'])->first();
					if(!$saleOrder){
						$saleOrder = SaleOrder::create($rows);
						if($saleOrder){
							array_push($successSaleOrders, $saleOrder->toarray());
							foreach ($rows['saleitems'] as $row) {
								$saleItem = SaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->first();
								if(!$saleItem){
									unset($row['id']);
									//Check for duplicate seat
									$checkExistingSeat = SaleItem::wheretrip_id($row['trip_id'])->wheredeparture_date($row['departure_date'])->whereseat_no($row['seat_no'])->first();
									if(!$checkExistingSeat){
										$saleItem = SaleItem::create($row);
										if($saleItem){
											array_push($successSaleItems, $saleItem->toarray());
										}else{
											array_push($errorSaleItems, $row->toarray());
										}
									}else{
										// If seat is duplicate
										$orderIds = $checkExistingSeat->order_id.','.$row['order_id'];
										$checkExistingSeat->order_id = $orderIds;
										$duplicateSeat = DeleteSaleItem::create($checkExistingSeat->toarray());
										if($duplicateSeat){
											SaleItem::wheretrip_id($row['trip_id'])->wheredeparture_date($row['departure_date'])->whereseat_no($row['seat_no'])->delete();
											$saleItem = SaleItem::create($row);
											if($saleItem){
												array_push($successSaleItems, $saleItem->toarray());
											}else{
												array_push($errorSaleItems, $row->toarray());
											}
										}
										array_push($duplicateSeat, $row->toarray());
									}
								}else{
									array_push($duplicateSaleItems, $saleItem->toarray());
								}
							}
						}else{
							array_push($errorSaleOrders, $rows->toarray());
						}
						
					}else{
						$delSaleOrder = SaleOrder::whereid($rows['id'])->where('updated_at','<',$rows['updated_at'])->first();
						if($delSaleOrder){
							// Check for already exist in deleted table.
							$checkDelSaleOrder = DeleteSaleOrder::whereid($delSaleOrder->id)->first();
							if(!$checkDelSaleOrder){
								$delSaleOrder->remark = 'Duplicate Sale Order';
								$deletedSaleOrder = DeleteSaleOrder::create($delSaleOrder->toarray());
							}
							SaleOrder::whereid($rows['id'])->delete();
							$saleOrder = SaleOrder::create($rows);
							if($saleOrder){
								array_push($successSaleOrders, $saleOrder);
								foreach ($rows['saleitems'] as $row) {
									unset($row['id']);
									$saleItem = SaleItem::whereorder_id($row['order_id'])->wheretrip_id($row['trip_id'])->wheredeparture_date($row['departure_date'])->whereseat_no($row['seat_no'])->first();
									if(!$saleItem){
										$saleItem = SaleItem::create($row);
										if($saleItem){
											array_push($successSaleItems, $saleItem);
										}else{
											array_push($errorSaleItems, $row);
										}
									}else{
										$duplicateSeat = $saleItem->toarray();
										unset($duplicateSeat['id']);
										DeleteSaleItem::create($duplicateSeat);
										array_push($duplicateSaleItems, $saleItem);
									}
								}
							}else{
								array_push($errorSaleOrders, $rows);
							}
							
						}
						array_push($duplicateSaleOrders, $saleOrder);
					}

					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($saleOrders) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);					
				}
				$response['status_code']  = 1; // 1 is success.
				$response['message'] = "Successfully your data was saved.";
				$response["duplicateSaleOrders"] = $duplicateSaleOrders;
				$response["duplicateSaleItems"] = $duplicateSaleItems;
				$response["duplicateSeat"] 	= $duplicateSeat;
				$response["errorSaleOrders"] = $errorSaleOrders;
				$response["errorSaleItems"] = $errorSaleItems;
				$response["successSaleOrders"] = $successSaleOrders;
				$response["successSaleItems"] =$successSaleItems;
				return $response;
			}else{
				$response['status_code']  = 0;
				$response['message'] = "Empty value from your uploaded file.";
				return $response;
			}

		}else{
			dd('Cann\'t open zip file.');
		}
	}
	/**
	 * To Export Payment Transaction Data
	 * @/exportpaymentjson/{id}/{fname}/{date}
	 */
	public function exportPaymentJson($operator_id,$fileName,$startDate){
		$agentDeposits = null;
		if($startDate == 0)
			$agentDeposits 	= AgentDeposit::all()->toarray();
		else
			$agentDeposits 	= AgentDeposit::where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		
		if($agentDeposits){
			$this->saveFile($fileName, $agentDeposits);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
		
	}
	/**
	 * To Import Payment Transaction Data.
	 */
	public function importPaymentJson($fileName, $sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$agentDeposits = $this->readJson($fileName);
			$duplicateAgentDeposits 	= array();
			$errorAgentDeposits	 		= array();
			$successAgentDeposits	 	= array();
			if($agentDeposits){
				$i = 0;
				foreach ($agentDeposits as $rows) {
					$agentDeposit = AgentDeposit::whereagent_id($rows['agent_id'])
								->whereoperator_id($rows['operator_id'])
								->wheredeposit_date($rows['deposit_date'])
								->wheredeposit($rows['deposit'])
								->wheretotal_ticket_amt($rows['total_ticket_amt'])
								->wherepayment($rows['payment'])
								->wherepay_date($rows['pay_date'])
								->whereorder_ids($rows['order_ids'])
								->wherebalance($rows['balance'])
								->wheredebit($rows['debit'])
								->wherecreated_at($rows['created_at'])
								->whereupdated_at($rows['updated_at'])
								->first();
					if(!$agentDeposit){
						$agentDeposit = AgentDeposit::create($rows);
						if($agentDeposit){
							array_push($successAgentDeposits, $agentDeposit->toarray());
						}else{
							array_push($errorAgentDeposits, $rows->toarray());
						}
						
					}else{
						array_push($duplicateAgentDeposits, $agentDeposit->toarray());
					}

					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($agentDeposits) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);	
				}
				$response['status_code']  = 1; // 1 is success.
				$response['message'] = "Successfully your data was saved.";
				$response["duplicateAgentDeposits"] = count($duplicateAgentDeposits);
				$response["errorAgentDeposits"] = count($errorAgentDeposits);
				$response["successAgentDeposits"] = count($successAgentDeposits);
				return $response;
			}else{
				$response['status_code']  = 0;
				$response['message'] = "Empty value from your uploaded file.";
				return $response;
			}
		}else{
			dd('Cann\'t open zip file.');
		}
	}

	/**
	 * To Export DeleteSale Transaction Data
	 * @/exportdeletesaleorderjson/{id}/{fname}/{date}
	 */
	public function exportDeleteSaleOrderJson($operator_id,$fileName,$startDate){
		$deleteSaleOrders = null;
		if($startDate == 0)
			$deleteSaleOrders 	= DeleteSaleOrder::with('saleitems')->get()->toarray();
		else
			$deleteSaleOrders 	= DeleteSaleOrder::with('saleitems')->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)->get()->toarray();
		
		if($deleteSaleOrders){
			$this->saveFile($fileName, $deleteSaleOrders);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
		
	}
	/**
	 * To Import DeleteSale Transaction Data.
	 */
	public function importDeleteSaleOrderJson($fileName,$sync_id){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$DeleteSaleOrders = $this->readJson($fileName);
			$duplicateDeleteSaleOrders 	= array();
			$duplicateDeleteSaleItems 	= array();
			$errorDeleteSaleOrders 		= array();
			$errorDeleteSaleItems	 	= array();
			$successDeleteSaleOrders 	= array();
			$successDeleteSaleItems	 	= array();
			if($DeleteSaleOrders){
				$i = 0;
				foreach ($DeleteSaleOrders as $rows) {

					$deleteSaleOrder = DeleteSaleOrder::whereid($rows['id'])->wherecreated_at($rows['created_at'])->first();

					

					if(!$deleteSaleOrder){
						$delSaleOrder = DeleteSaleOrder::create($rows);
						if($delSaleOrder){
							array_push($successDeleteSaleOrders, $delSaleOrder->toarray());
							$saleitem_count = SaleItem::whereorder_id($row['order_id'])->count();
							foreach ($rows['saleitems'] as $row) {
								$deleteSaleItem = DeleteSaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->first();
								if(!$deleteSaleItem){
									$deleteSaleItem = DeleteSaleItem::create($row);
									if($deleteSaleItem){
										SaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->delete();
										array_push($successDeleteSaleItems, $deleteSaleItem->toarray());
									}else{
										array_push($errorDeleteSaleItems, $row->toarray());
									}
								}else{
									array_push($duplicateDeleteSaleItems, $deleteSaleItem->toarray());
								}
							}
							// Check to delete sale order when not delete to all sale item.
							if(count($successDeleteSaleItems) >= $saleitem_count)
								SaleOrder::whereid($rows['id'])->wherecreated_at($rows['created_at'])->delete();

						}else{
							array_push($errorDeleteSaleOrders, $rows->toarray());
						}
						
					}else{
						$deleteSaleorder_item = $rows;
						unset($deleteSaleorder_item['device_id']);
						unset($deleteSaleorder_item['saleitems']);
						$DeleteSaleOrder = DeleteSaleOrder::whereid($rows['id'])->where('updated_at','<',$rows['updated_at'])->update($deleteSaleorder_item);
						if($DeleteSaleOrder){
							array_push($successDeleteSaleOrders, $DeleteSaleOrder);
							foreach ($rows['saleitems'] as $row) {
								unset($row['device_id']);
								$deleteSaleItem = DeleteSaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->first();
								if(!$deleteSaleItem){
									$deleteSaleItem = DeleteSaleItem::create($row);
									if($deleteSaleItem){
										array_push($successDeleteSaleItems, $deleteSaleItem);
									}else{
										array_push($errorDeleteSaleItems, $row);
									}
								}else{
									DeleteSaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->update($row);
									array_push($duplicateDeleteSaleItems, $deleteSaleItem);
								}
							}
						}else{
							array_push($errorDeleteSaleOrders, $rows);
						}
						array_push($duplicateDeleteSaleOrders, $DeleteSaleOrder);
					}

					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($DeleteSaleOrders) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile($sync_id, $progress);	
				}
				$response['status_code']  = 1; // 1 is success.
				$response['message'] = "Successfully your data was saved.";
				$response["duplicateDeleteSaleOrders"] = $duplicateDeleteSaleOrders;
				$response["duplicateDeleteSaleItems"] = $duplicateDeleteSaleItems;
				$response["errorDeleteSaleOrders"] = $errorDeleteSaleOrders;
				$response["errorDeleteSaleItems"] = $errorDeleteSaleItems;
				$response["successDeleteSaleOrders"] = $successDeleteSaleOrders;
				$response["successDeleteSaleItems"] = $successDeleteSaleItems;
				return $response;
			}else{
				$response['status_code']  = 0;
				$response['message'] = "Empty value from your uploaded file.";
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
		}
	}
	/**
	 * To Save File as JSON File.
	 */
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
	/**
	 * To Delete File
	 */
	public function deleteFile($fileName){
		$fileDir = "remote_file/".$fileName;
		if (file_exists($fileDir)) {
        	unlink($fileDir);
    	}
	}
	/*
	 * To Create Zipfile
	 */
	public function createZip($zipFile, $fileName, $addFile){
		$zip = new ZipArchive();
		$zip->open($zipFile, ZipArchive::CREATE);
		$zip->addFile($addFile, $fileName);
		$zip->close();
		chmod($zipFile,0777);
	}
	/**
	 * To Get File Dir.
	 */
	public function getFile($fileName){
		$file = $this->local_file_dir.$fileName;
		return $file;
	}
	/**
	 * To Connect FTP Server.
	 */
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
				dd("No Internet Connection.");
				//return false;
			}
		} catch (Exception $e) {
			dd($e);
		}
	}
	/**
	 * To Download Data from Server.
	 */
	public function download($fromFile, $toFile, $sync_id){
		
		// First -> attend
		$response['message'] = 'Connecting to Server...';
		
		$this->saveFile($sync_id, $response);

		$primary_connection = $this->connect();
		$secondary_connection = $this->connect();

		try {
			
			$mode = FTP_BINARY;
			ftp_pasv($primary_connection,TRUE);
			ftp_pasv($secondary_connection,TRUE);

			$download_status=ftp_nb_get($primary_connection, $toFile, $fromFile, $mode);
			if($download_status == FTP_FAILED){
				dd('Can\'t download data, Please check connection.');
			}
			if($download_status == FTP_MOREDATA){
				// Second -> connected
				$response['message'] = 'Connected to Server...';
				$this->saveFile($sync_id, $response);
			}

			$filesize=ftp_size($secondary_connection,$fromFile);
			$response['file_url']		= $toFile;
			$response['total_size'] 	= $filesize;
			$this->saveFile($sync_id, $response);
			while($download_status == FTP_MOREDATA){
			    $download_status = ftp_nb_continue($primary_connection);
			}

		} catch (Exception $e) {
			dd($e);
		}
		$response['message'] = 'Successfully your download file';
		$this->saveFile($sync_id,$response);
		ftp_close($primary_connection);
		ftp_close($secondary_connection);
		//$this->deleteFile($sync_id);
		return true;
	}
	/**
	 * To Upload Data to Server.
	 */
	public function upload($fromFile, $toFile, $sync_id){

		$destination_file = $toFile;
		$source_file = $fromFile;

		// First -> attend
		$response['message'] = 'Connecting to Server...';
		$response['file_url'] 		= $source_file;
		$response['total_size'] 	= ceil(filesize($source_file)/1024);
		$response['uploaded_size'] 	= 0;

		$this->saveFile($sync_id, $response);

		$primary_connection = $this->connect();
		$secondary_connection = $this->connect();

		try {
			
			$mode = FTP_BINARY;
			ftp_pasv($primary_connection,TRUE);
			ftp_pasv($secondary_connection,TRUE);
			
			$files_on_server = ftp_nlist($primary_connection, $this->remote_file_dir);
			if (in_array($destination_file, $files_on_server)) 
	        {
				$delected = ftp_delete($primary_connection, $destination_file);
	        }

			$upload_status=ftp_nb_put($primary_connection, $destination_file, $source_file, $mode);

			if($upload_status == FTP_FAILED){
				dd('Can\'t upload data, Please check connection.');
			}
			if($upload_status == FTP_MOREDATA){
				// Second -> connected
				$response['message'] = 'Connected to Server...';
				$this->saveFile($sync_id, $response);
			}else{
				$response['message'] = 'Please try againt!.';
				$this->saveFile($sync_id, $response);
			}

			define('ALPHA', 0.2); // Weight factor of new calculations, between 0 and 1
			$filesize=filesize($source_file);
			$transferred = 0;
			$rate = 0;
			$time = microtime(true);

			$start_time=$time;
			while($upload_status == FTP_MOREDATA){
			    $upload_status = ftp_nb_continue($primary_connection);

			    $sizeNow=ftp_size($secondary_connection,$destination_file);
			    $sizeNowkB=$sizeNow/1024;
			    $timeNow = microtime(true);

			    $currentRate = ($sizeNow - $transferred) / ($timeNow - $time);
			    $currentkBRate = $currentRate / 1024;

			    $rate = ALPHA * $currentRate + (1 - ALPHA) * $rate;
			    $time = $timeNow;
			    $transferred = $sizeNow;

			    $response['message'] 		= 'Uploading to server...';
			    $response['uploaded_size'] 	= ceil($sizeNowkB);
			    $response['progress'] 		= ceil($sizeNowkB / ($filesize/1024) * 100);
			    $response['speed'] 			= ceil($currentkBRate);
			    $response['agv_speed'] 		= ceil($rate/1024);
			    $elapsed_time 				= ceil($timeNow - $start_time);
			    $response['elapsed_time'] 	= ceil($elapsed_time);
			    if($rate!=0){
			        $eta = $filesize/$rate - $elapsed_time;
			    }else{
			        $eta=0.0;
			    }
			    if($eta<=0){
			        $eta=0.0;
			    };
			    $response['elapsed_time_left'] = ceil($eta);
			    $this->saveFile($sync_id,$response);

			}
		} catch (Exception $e) {
			dd($e);
		}
		$response['message'] = 'Successfully your uploaded file';
		$this->saveFile($sync_id,$response);
		ftp_chmod($primary_connection, 0777, $destination_file);
		ftp_close($primary_connection);
		ftp_close($secondary_connection);
		//$this->deleteFile($sync_id);
		return true;
	}

	public function getUploadProgress($sync_id){
		print json_encode($this->readJson($sync_id));
	}

	public function getDownloadedProgress($sync_id){
		$progress = $this->readJson($sync_id);
		$response['message'] = isset($progress['message']) ? $progress['message'] : 'Downloading from server...';
		if(isset($progress['total_size'])){
			$fileSize = $progress['total_size'];
			$source_file = $progress['file_url'];
			if(!$source_file)
				$sizeNow=$progress['file_lenght'];
			else
				$sizeNow=filesize($source_file);
			$response['file_url'] = $source_file;
			$response['total_size'] = ceil($fileSize / 1024);
			$response['downloaded'] = ceil($sizeNow / 1024);
			$response['progress']	= ceil(($sizeNow/1024) / ($fileSize/1024) * 100);
		    print json_encode($response);
		}else{
			print json_encode($progress);
		}
	}
	
	/**
	 * To Read from Json File.
	 */
	public function readJson($fileName){
		$file = "remote_file/".$fileName;
		if(file_exists($file)){
			$jsonString = file_get_contents($file);
			$jsonArr = json_decode($jsonString,true);
			return $jsonArr;
		}else{
			return array();
		}
		
	}

}