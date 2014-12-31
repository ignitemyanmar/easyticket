<?php
class SyncDatabaseController extends BaseController
{
	public $ftp_server			= '107.170.39.245';
	public $ftp_user_name		= 'easyticket';
	public $ftp_user_pass		= 'easyticket';
	public $local_file_dir		= 'remote_file/';
	public $remote_file_dir		= 'public_html/easyticket/public/remote_file/';
	public $domain				= 'http://easyticket.com.mm';

	public function index(){
		return View::make('sync.index');
	}

	/**
	* Upload Sale Order from Clent.
	*/
 	public function pushJsonToServer(){
 		$zipFile = 'client-'.$this->operatorgroup_id.'-today-sale-order.zip';
		$fileName = 'client-'.$this->operatorgroup_id.'-today-sale-order.json';
		$fromFile = $this->getFile($zipFile);
		$toFile	  = $this->remote_file_dir.$zipFile;
		$startDate	  = $this->getSysDateTime();
		$sync 		  = Sync::wherename($zipFile)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$startDate = 0;
		}
		
		if($this->exportSaleOrderJson($this->operatorgroup_id,$fileName,$startDate) == "true"){
			$this->createZip($this->getFile($zipFile), $fileName, $this->getFile($fileName));
			if($this->upload($fromFile, $toFile)){
				$response = array();
				$response['message'] = 'Importing your uploaded data.';
				$this->saveFile('progress',$response);
				$curl = curl_init( $this->domain."/writetodatabase/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				$this->saveFile('progress',json_decode($response,true));
				if($response){
					$sync = Sync::wherename($zipFile)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $zipFile;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					//$this->pushPaymentJsonToServer();
					$this->deleteFile('progress');
					return Response::json(json_decode($response));
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
 	public function pushPaymentJsonToServer(){
 		$zipFile = 'client-'.$this->operatorgroup_id.'-today-payment.zip';
		$fileName = 'client-'.$this->operatorgroup_id.'-today-payment.json';
		$fromFile = $this->getFile($zipFile);
		$toFile	  = $this->remote_file_dir.$zipFile;
		$startDate	  = $this->getSysDateTime();
		$sync 		  = Sync::wherename($zipFile)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
		}else{
			$startDate = 0;
		}
		if($this->exportPaymentJson($this->operatorgroup_id,$fileName,$startDate)  == "true"){
			$this->createZip($this->getFile($zipFile), $fileName, $this->getFile($fileName));
			if($this->upload($fromFile, $toFile)){
				$response = array();
				$response['message'] = 'Importing your uploaded data.';
				$this->saveFile('progress',$response);
				$curl = curl_init( $this->domain."/writepaymentjson/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				$this->saveFile('progress',json_decode($response,true));
				if($response){
					$sync = Sync::wherename($zipFile)->first();
					if($sync){
						$sync->last_updated_date = $this->getSysDateTime();
						$sync->last_sync_date = $this->getSysDateTime();
						$sync->update();
					}else{
						$sync 						= new Sync();
						$sync->name 				= $zipFile;
						$sync->last_updated_date 	= $this->getSysDateTime();
						$sync->last_sync_date 		= $this->getSysDateTime();
						$sync->save();
					}
					$this->deleteFile('progress');
					return Response::json(json_decode($response));
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
	 * @/writetodatabase/{fname}
	 */
	public function writeJsonToDatabase($fileName){
		$importSaleOrder = $this->importSaleOrderJson($fileName);
		return $importSaleOrder;
	}

	/**
	 * To Import Data
	 * @/writepaymentjson/{fname}
	 */
	public function writePaymentJsonToDatabase($fileName){
		$importPayment = $this->importPaymentJson($fileName);
		return $importPayment;
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
		$this->downloadBusJsonfromServer();
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
	 * To Sync Bus from Server
	 */
	public function downloadBusJsonfromServer(){

		$fileName = 'client-'.$this->operatorgroup_id.'-bus-occurance.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$syncDatetime = 0;
		$datetime = BusOccurance::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportbusjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importBusOccurance($fileName);
				$this->deleteFile('progress');
				if($importData){
					return Response::json($importData);
				}
			}else{
				$response = array();
				$response['status_code']  = 0; // 0 is error.
				$response['message'] = "Can't download from server!.";
				return Response::json($response);
			}
		}else{
			$response = array();
			$response['status_code']  = 0; // 0 is error.
			$response['message'] = "Can't export data from server!.";
			return Response::json($response);
		}
	}
	/**
	 * To Sync Trip from Server
	 */
	public function downloadTripJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-trip.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$syncDatetime = 0;
		$datetime = Trip::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}

		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exporttripjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );

		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importTrip($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadDeleteTripJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-del-trip.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$syncDatetime = 0;
		$datetime = DeleteTrip::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportdeletetripjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );

		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importDeleteTrip($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadSeatingPlanJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-seating-plan.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = SeatingPlan::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportseatingplanjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importSeatingPlan($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadAgentJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-agent.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = Agent::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportagentjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importAgent($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadAgentGroupJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-agentgroup.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = AgentGroup::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportagentgroupjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importAgentGroup($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadCityJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-city.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = City::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportcityjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importCity($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadExtraDestinationJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-extradestination.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = ExtraDestination::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportextradestinationjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importExtraDestination($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadClassesJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-bus-classes.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = Classes::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportclassesjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importClasses($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadAgentCommissionJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-agentcommission.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = AgentCommission::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportagentcommissionjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importAgentCommission($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadCloseSeatInfoJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-closeseatinfo.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = CloseSeatInfo::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportcloseseatinfojson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importCloseSeatInfo($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadCommissionTypeJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-commissiontype.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = CommissionType::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportcommissiontypejson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importCommissionType($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadOperatorGroupJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-operatorgroup.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = OperatorGroup::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}	
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportoperatorgroupjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importOperatorGroup($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadOperatorGroupUserJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-operatorgroupuser.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = OperatorGroupUser::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}	
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportoperatorgroupuserjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importOperatorGroupUser($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadUserJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-user.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = User::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportuserjson/".$this->operator_id."/".$fileName."/".$syncDatetime);
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importUser($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadOperatorJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-operator.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = Operator::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime) ;
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportoperatorjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importOperator($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadSeatInfoJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-seatinfo.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = SeatInfo::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportseatinfojson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importSeatInfo($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadSaleOrderJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-today-sale-order.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = SaleOrder::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}	
		$syncDatetime = 0; #To Remove It	
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportsaleorderjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importSaleOrderJson($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadDeleteSaleOrderJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-today-delsale-order.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = DeleteSaleOrder::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}	
		$syncDatetime = 0; #To Remove It	
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportdeletesaleorderjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importDeleteSaleOrderJson($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	public function downloadPaymentJsonfromServer(){
		
		$fileName = 'client-'.$this->operatorgroup_id.'-today-payment.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;
		$syncDatetime = 0;
		$datetime = AgentDeposit::orderBy('created_at','desc')->limit(1)->pluck('created_at');
		if($datetime){
			$syncDatetime = str_replace(' ', '%20', $datetime);
		}else{
			$syncDatetime = 0;
		}	
		$syncDatetime = 0; #To Remove It
		$response['message'] = "Exporting from Server...";
		$this->saveFile('progress', $response);
		$curl = curl_init( $this->domain."/exportpaymentjson/".$this->operator_id."/".$fileName."/".$syncDatetime );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download(str_replace('.json', '.zip', $fromFile), str_replace('.json', '.zip', $toFile))){
				$response = array();
				$response['message'] = "Importing your downloaded data...";
				$this->saveFile('progress', $response);
				$importData = $this->importPaymentJson($fileName);
				$this->deleteFile('progress');
				if($importData){
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
	 * To Export BusOccourence Data
	 * @/exportbusjson/{id}/{fname}/{date}
	 */
	public function exportBusOccurance($operator_id,$fileName,$startDate){
		$busOccurance = null;
		if($startDate == 0){
			$busOccurance = BusOccurance::whereoperator_id($operator_id)
										->get()->toarray();
		}else{
			$busOccurance = BusOccurance::whereoperator_id($operator_id)->where('created_at','>',$startDate)->orwhere('updated_at','>',$startDate)
											->get()->toarray();
		}

		if($busOccurance){
			$this->saveFile($fileName, $busOccurance);
			$this->createZip($this->getFile(str_replace('.json', '.zip', $fileName)), $fileName, $this->getFile($fileName));
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import BusOccourence Data
	 */
	public function importBusOccurance($fileName){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
			$busOccurance = $this->readJson($fileName);
			if($busOccurance){
				$duplicateBus 	= array();
				$successBus 	= array();
				$errorBus 		= array();
				$i = 0;
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
						BusOccurance::whereid($rows['id'])->update($rows);
						array_push($duplicateBus, $busOccuran->toarray());
					}
					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($busOccurance) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile('progress', $progress);
				}
				$response['status_code'] = 1; //1 is success;
				$response['message']	 = 'Successfully your import data.';
				$response['duplicateBus']= $duplicateBus;
				$response['errorBus']	 = $errorBus;
				$response['successBus']  = $successBus;
				return $response;
			}
		}else{
			dd('Cann\'t unzip your file.');
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
	public function importTrip($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importDeleteTrip($fileName){
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

					if(BusOccurance::wheretrip_id($rows['id'])->first())
						BusOccurance::wheretrip_id($rows['id'])->delete();

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
					$this->saveFile('progress', $progress);
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
	public function importSeatingPlan($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importAgent($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importAgentGroup($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importCity($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importExtraDestination($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importClasses($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importAgentCommission($fileName){
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
					$this->saveFile('progress', $progress);
					
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
	public function importCloseSeatInfo($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importCommissionType($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importOperatorGroup($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importOperatorGroupUser($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importUser($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importOperator($fileName){
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
					$this->saveFile('progress', $progress);
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
	public function importSeatInfo($fileName){
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
					$this->saveFile('progress', $progress);
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
			$saleOrders 	= SaleOrder::with('saleitems')->get()->toarray();
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
	 * To Import Sale Transaction Data.
	 */
	public function importSaleOrderJson($fileName){
		$zip = new ZipArchive;
		$res = $zip->open($this->getFile(str_replace('.json', '.zip', $fileName)));
		if ($res) {
			$zip->extractTo($this->local_file_dir);
			$zip->close();
		
			$saleOrders = $this->readJson($fileName);
			$duplicateSaleOrders 	= array();
			$duplicateSaleItems 	= array();
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
						$saleorder_item = $rows;
						unset($saleorder_item['device_id']);
						unset($saleorder_item['saleitems']);
						$saleOrder = SaleOrder::whereid($rows['id'])->where('updated_at','<',$rows['updated_at'])->update($saleorder_item);
						if($saleOrder){
							array_push($successSaleOrders, $saleOrder);
							foreach ($rows['saleitems'] as $row) {
								unset($row['id']);
								unset($row['device_id']);
								$saleItem = SaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->first();
								if(!$saleItem){
									$saleItem = SaleItem::create($row);
									if($saleItem){
										array_push($successSaleItems, $saleItem);
									}else{
										array_push($errorSaleItems, $row);
									}
								}else{
									SaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->update($row);
									array_push($duplicateSaleItems, $saleItem);
								}
							}
						}else{
							array_push($errorSaleOrders, $rows);
						}
						array_push($duplicateSaleOrders, $saleOrder);
					}

					$progress['message'] = "Importing [$fileName] to Database.";
					$progress['file_url'] = "";
					$progress['total_size'] = count($saleOrders) * 1024;
					$progress['file_lenght'] = $i++ * 1024;
					$this->saveFile('progress', $progress);					
				}
				$response['status_code']  = 1; // 1 is success.
				$response['message'] = "Successfully your data was saved.";
				$response["duplicateSaleOrders"] = count($duplicateSaleOrders);
				$response["duplicateSaleItems"] = count($duplicateSaleItems);
				$response["errorSaleOrders"] = count($errorSaleOrders);
				$response["errorSaleItems"] = count($errorSaleItems);
				$response["successSaleOrders"] = count($successSaleOrders);
				$response["successSaleItems"] =count($successSaleItems);
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
	public function importPaymentJson($fileName){
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
					$this->saveFile('progress', $progress);	
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
	public function importDeleteSaleOrderJson($fileName){
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
					$deleteSaleOrder = DeleteSaleOrder::whereid($rows['id'])->first();

					if(SaleOrder::whereid($rows['id'])->delete())
						SaleOrder::whereid($rows['id'])->delete();

					if(!$deleteSaleOrder){
						$delSaleOrder = DeleteSaleOrder::create($rows);
						if($delSaleOrder){
							array_push($successDeleteSaleOrders, $delSaleOrder->toarray());
							foreach ($rows['saleitems'] as $row) {
								$deleteSaleItem = DeleteSaleItem::whereorder_id($row['order_id'])->whereseat_no($row['seat_no'])->first();
								if(!$deleteSaleItem){
									$deleteSaleItem = DeleteSaleItem::create($row);
									if($deleteSaleItem){
										array_push($successDeleteSaleItems, $deleteSaleItem->toarray());
									}else{
										array_push($errorDeleteSaleItems, $row->toarray());
									}
								}else{
									array_push($duplicateDeleteSaleItems, $deleteSaleItem->toarray());
								}
							}
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
					$this->saveFile('progress', $progress);	
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
			
		}else{
			dd('Please Check File Name or Array List');
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
				return false;
			}
		} catch (Exception $e) {
			dd($e);
		}
	}
	/**
	 * To Download Data from Server.
	 */
	public function download($fromFile, $toFile){
		
		// First -> attend
		$response['status'] = 'Connecting to Server...';
		
		$this->saveFile('progress', $response);

		$primary_connection = $this->connect();
		$secondary_connection = $this->connect();

		try {
			
			$mode = FTP_BINARY;
			ftp_pasv($primary_connection,TRUE);
			ftp_pasv($secondary_connection,TRUE);

			$upload_status=ftp_nb_get($primary_connection, $toFile, $fromFile, $mode);
			if($upload_status == FTP_FAILED){
				dd('Can\'t upload data, Please check connection.');
			}
			if($upload_status == FTP_MOREDATA){
				// Second -> connected
				$response['status'] = 'Connected to Server...';
				$this->saveFile('progress', $response);
			}

			$filesize=ftp_size($secondary_connection,$fromFile);
			$response['file_url']		= $toFile;
			$response['total_size'] 	= $filesize;
			$this->saveFile('progress', $response);
			
			while($upload_status == FTP_MOREDATA){
			    $upload_status = ftp_nb_continue($primary_connection);
			}

		} catch (Exception $e) {
			dd($e);
		}
		$response['message'] = 'Successfully your download file';
		$this->saveFile('progress',$response);
		ftp_close($primary_connection);
		ftp_close($secondary_connection);
		$this->deleteFile('progress');
		return true;
	}
	/**
	 * To Upload Data to Server.
	 */
	public function upload($fromFile, $toFile){

		$destination_file = $toFile;
		$source_file = $fromFile;

		// First -> attend
		$response['message'] = 'Connecting to Server...';
		$response['file_url'] 		= $source_file;
		$response['total_size'] 	= ceil(filesize($source_file)/1024);
		$response['uploaded_size'] 	= 0;

		$this->saveFile('progress', $response);

		$primary_connection = $this->connect();
		$secondary_connection = $this->connect();

		try {
			
			$mode = FTP_BINARY;
			ftp_pasv($primary_connection,TRUE);
			ftp_pasv($secondary_connection,TRUE);

			$upload_status=ftp_nb_put($primary_connection, $destination_file, $source_file, $mode);
			if($upload_status == FTP_FAILED){
				dd('Can\'t upload data, Please check connection.');
			}
			if($upload_status == FTP_MOREDATA){
				// Second -> connected
				$response['message'] = 'Connected to Server...';
				$this->saveFile('progress', $response);
			}else{
				$response['message'] = 'Please try againt!.';
				$this->saveFile('progress', $response);
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
			    $this->saveFile('progress',$response);

			}
		} catch (Exception $e) {
			dd($e);
		}
		$response['message'] = 'Successfully your uploaded file';
		$this->saveFile('progress',$response);
		ftp_chmod($primary_connection, 0777, $destination_file);
		ftp_close($primary_connection);
		ftp_close($secondary_connection);
		//$this->deleteFile('progress');
		return true;
	}

	public function downloadtest(){
		
		$destination_file = $this->remote_file_dir.'ezticket.zip';
		$source_file = $this->local_file_dir.'ezticket.zip';

		// First -> attend
		$response['status'] = 'Connecting to Server...';
		
		$this->saveFile('progress', $response);

		$primary_connection = $this->connect();
		$secondary_connection = $this->connect();

		try {
			
			$mode = FTP_BINARY;
			ftp_pasv($primary_connection,TRUE);
			ftp_pasv($secondary_connection,TRUE);

			$upload_status=ftp_nb_get($primary_connection, $source_file, $destination_file, $mode);
			if($upload_status == FTP_FAILED){
				dd('Can\'t upload data, Please check connection.');
			}
			if($upload_status == FTP_MOREDATA){
				// Second -> connected
				$response['status'] = 'Connected to Server...';
				$this->saveFile('progress', $response);
			}

			$filesize=ftp_size($secondary_connection,$destination_file);
			$response['file_url']		= $source_file;
			$response['total_size'] 	= $filesize;
			$this->saveFile('progress', $response);
			
			while($upload_status == FTP_MOREDATA){
			    $upload_status = ftp_nb_continue($primary_connection);
			}

		} catch (Exception $e) {
			dd($e);
		}
		$response['message'] = 'Successfully your uploaded file';
		$this->saveFile('progress',$response);
		ftp_close($primary_connection);
		ftp_close($secondary_connection);
		$this->deleteFile('progress');
	}

	public function getUploadProgress(){
		print json_encode($this->readJson('progress'));
	}

	public function getDownloadedProgress(){
		$progress = $this->readJson('progress');
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