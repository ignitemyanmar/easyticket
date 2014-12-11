<?php
class SyncDatabaseController extends BaseController
{
	public $ftp_server			= '107.170.39.245';
	public $ftp_user_name		= 'easyticket';
	public $ftp_user_pass		= 'easyticket';
	public $local_file_dir		= "remote_file/";
	public $remote_file_dir		= 'public_html/easyticket/public/remote_file/';
	public $domain				= 'http://easyticket.com.mm';

	public function index(){
		return View::make('sync.index');
	}

	/**
	* Upload Sale Order from Clent.
	*/
 	public function pushJsonToServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-today-sale-order.json';
		$fromFile = $this->getFile($fileName);
		$toFile	  = $this->remote_file_dir.$fileName;
		if($this->exportSaleOrderJson($fileName)){
			if($this->upload($fromFile, $toFile)){
				$curl = curl_init( $this->domain."/writetodatabase/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				if($response){
					$this->pushPaymentJsonToServer();
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
	/**
	* Upload Sale Order from Clent.
	*/
 	public function pushPaymentJsonToServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-today-payment.json';
		$fromFile = $this->getFile($fileName);
		$toFile	  = $this->remote_file_dir.$fileName;
		if($this->exportPaymentJson($fileName)){
			if($this->upload($fromFile, $toFile)){
				$curl = curl_init( $this->domain."/writepaymentjson/".$fileName );
				curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec( $curl );
				dd($response);
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
		$this->downloadExtraDestinationJsonfromServer();
		$this->downloadCloseSeatInfoJsonfromServer();
		$this->downloadBusJsonfromServer();
		$this->downloadAgentJsonfromServer();
		$this->downloadAgentCommissionJsonfromServer();
		$this->downloadCommissionTypeJsonfromServer();
		$this->downloadSaleOrderJsonfromServer();

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

		$curl = curl_init( $this->domain."/exportbusjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importBusOccurance($fileName);
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
		
		$curl = curl_init( $this->domain."/exporttripjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importTrip($fileName);
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
	 * To Sync Seating Plan from Server
	 */
	public function downloadSeatingPlanJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-seating-plan.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportseatingplanjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );

		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importSeatingPlan($fileName);
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
	 * To Sync Agent from Server
	 */
	public function downloadAgentJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-agent.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportagentjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importAgent($fileName);
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
	 * To Sync City from Server
	 */
	public function downloadCityJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-city.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportcityjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importCity($fileName);
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
	 * To Sync Extra Destination from Server
	 */
	public function downloadExtraDestinationJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-extradestination.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportextradestinationjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importExtraDestination($fileName);
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
	 * To Sync Classes from Server
	 */
	public function downloadClassesJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-bus-classes.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportclassesjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importClasses($fileName);
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
	 * To Sync AgentCommission from Server
	 */
	public function downloadAgentCommissionJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-agentcommission.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportagentcommissionjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importAgentCommission($fileName);
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
	 * To Sync CloseSeatInfo from Server
	 */
	public function downloadCloseSeatInfoJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-closeseatinfo.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportcloseseatinfojson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importCloseSeatInfo($fileName);
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
	 * To Sync CommissionType from Server
	 */
	public function downloadCommissionTypeJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-commissiontype.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportcommissiontypejson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importCommissionType($fileName);
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
	 * To Sync OperatorGroup from Server
	 */
	public function downloadOperatorGroupJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-operatorgroup.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportoperatorgroupjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importOperatorGroup($fileName);
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
	 * To Sync OperatorGroupUser from Server
	 */
	public function downloadOperatorGroupUserJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-operatorgroupuser.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportoperatorgroupuserjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importOperatorGroupUser($fileName);
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
	 * To Sync User from Server
	 */
	public function downloadUserJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-user.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportuserjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importUser($fileName);
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
	 * To Sync Operator from Server
	 */
	public function downloadOperatorJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-operator.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportoperatorjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importOperator($fileName);
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
	 * To Sync SeatInfo from Server
	 */
	public function downloadSeatInfoJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-seatinfo.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportseatinfojson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importSeatInfo($fileName);
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
	 * To Sync SaleOrder from Server
	 */
	public function downloadSaleOrderJsonfromServer(){
		$fileName = 'client-'.$this->operatorgroup_id.'-today-sale-order.json';
		$toFile = $this->getFile($fileName);
		$fromFile = $this->remote_file_dir.$fileName;

		$curl = curl_init( $this->domain."/exportsaleorderjson/".$this->operator_id."/".$fileName );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $curl );
		if($response == "true"){
			if($this->download($fromFile, $toFile)){
				$importData = $this->importSaleOrderJson($fileName);
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
	 * To Export BusOccourence Data
	 * @/exportbusjson/{id}/{fname}
	 */
	public function exportBusOccurance($operator_id,$fileName){
		$startDate	  = $this->getDate();
		$endDate 	  = date('Y-m-d',strtotime($this->getDate() . ' + 30 day'));
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			if($sync->last_updated_date == $this->getDate())
				return "false"; // Already Syn for Today.
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
			$sync->last_updated_date = $this->getDate();
			$sync->last_sync_date = $endDate;
			$sync->update();
		}else{
			$sync 						= new Sync();
			$sync->name 				= $fileName;
			$sync->last_updated_date 	= $this->getDate();
			$sync->last_sync_date 		= $endDate;
			$sync->save();
		}
		 
		$busOccurance = BusOccurance::where('departure_date','>=',$startDate)
										->where('departure_date','<=',$endDate)
										->get()->toarray();
		if($busOccurance){
			$this->saveFile($fileName, $busOccurance);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import BusOccourence Data
	 */
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
	/**
	 * To Export Trip Data.
	 * @'/exporttripjson/{id}/{fname}'
	 */
	public function exportTrip($operator_id,$fileName){
		$trips = Trip::where('operator_id','=',$operator_id)->get()->toarray();
		if($trips){
			$this->saveFile($fileName, $trips);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Trip Data.
	 */
	public function importTrip($fileName){
		$trips = $this->readJson($fileName);
		if($trips){
			$duplicateTrip 	= array();
			$successTrip 	= array();
			$errorTrip 		= array();
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
					array_push($duplicateTrip, $trip->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateTrip']= $duplicateTrip;
			$response['errorTrip']	 = $errorTrip;
			$response['successTrip']  = $successTrip;
			return $response;
		}
	}
	/**
	 * To Export SeatingPlan Data.
	 * @/exportseatingplanjson/{id}/{fname}
	 */
	public function exportSeatingPlan($operator_id,$fileName){
		$seatingPlans = SeatingPlan::whereoperator_id($operator_id)->get()->toarray();
		if($seatingPlans){
			$this->saveFile($fileName, $seatingPlans);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import SeatingPlan Data.
	 */
	public function importSeatingPlan($fileName){
		$seatingPlans = $this->readJson($fileName);
		if($seatingPlans){
			$duplicateSeatingPlan 	= array();
			$successSeatingPlan 	= array();
			$errorSeatingPlan 		= array();
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
					array_push($duplicateSeatingPlan, $seatingPlan->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateSeatingPlan']= $duplicateSeatingPlan;
			$response['errorSeatingPlan']	 = $errorSeatingPlan;
			$response['successSeatingPlan']  = $successSeatingPlan;
			return $response;
		}
	}
	/**
	 * To Export Agent Data.
	 * @/exportagentjson/{id}/{fname}
	 */
	public function exportAgent($operator_id,$fileName){
		$Agents = Agent::whereoperator_id($operator_id)->get()->toarray();
		if($Agents){
			$this->saveFile($fileName, $Agents);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Agent Data.
	 */
	public function importAgent($fileName){
		$Agents = $this->readJson($fileName);
		if($Agents){
			$duplicateAgent 	= array();
			$successAgent 		= array();
			$errorAgent 		= array();
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
					array_push($duplicateAgent, $Agent->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateAgent']= $duplicateAgent;
			$response['errorAgent']	 = $errorAgent;
			$response['successAgent']  = $successAgent;
			return $response;
		}
	}
	/**
	 * To Export City Data.
	 * @/exportcityjson/{id}/{fname}
	 */
	public function exportCity($operator_id,$fileName){
		$Citys = City::all()->toarray();
		if($Citys){
			$this->saveFile($fileName, $Citys);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import City Data.
	 */
	public function importCity($fileName){
		$Citys = $this->readJson($fileName);
		if($Citys){
			$duplicateCity 	= array();
			$successCity 	= array();
			$errorCity 		= array();
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
					array_push($duplicateCity, $City->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateCity']= $duplicateCity;
			$response['errorCity']	 = $errorCity;
			$response['successCity']  = $successCity;
			return $response;
		}
	}
	/**
	 * To Export ExtraDestination Data.
	 * @/exportextradestinationjson/{id}/{fname}
	 */
	public function exportExtraDestination($operator_id,$fileName){
		$ExtraDestinations = ExtraDestination::all()->toarray();
		if($ExtraDestinations){
			$this->saveFile($fileName, $ExtraDestinations);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import ExtraDestination Data.
	 */
	public function importExtraDestination($fileName){
		$ExtraDestinations = $this->readJson($fileName);
		if($ExtraDestinations){
			$duplicateExtraDestination 	= array();
			$successExtraDestination 	= array();
			$errorExtraDestination 		= array();
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
					array_push($duplicateExtraDestination, $ExtraDestination->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateExtraDestination']= $duplicateExtraDestination;
			$response['errorExtraDestination']	 = $errorExtraDestination;
			$response['successExtraDestination']  = $successExtraDestination;
			return $response;
		}
	}
	/**
	 * To Export Classes Data.
	 * @/exportclassesjson/{id}/{fname}
	 */
	public function exportClasses($operator_id,$fileName){
		$Classess = Classes::whereoperator_id($operator_id)->get()->toarray();
		if($Classess){
			$this->saveFile($fileName, $Classess);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Classes Data.
	 */
	public function importClasses($fileName){
		$Classess = $this->readJson($fileName);
		if($Classess){
			$duplicateClasses 	= array();
			$successClasses 	= array();
			$errorClasses 		= array();
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
					array_push($duplicateClasses, $Classes->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateClasses']= $duplicateClasses;
			$response['errorClasses']	 = $errorClasses;
			$response['successClasses']  = $successClasses;
			return $response;
		}
	}
	/**
	 * To Export AgentCommission Data.
	 * @/exportagentcommissionjson/{id}/{fname}
	 */
	public function exportAgentCommission($operator_id,$fileName){
		$AgentCommissions = AgentCommission::all()->toarray();
		if($AgentCommissions){
			$this->saveFile($fileName, $AgentCommissions);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import AgentCommission Data.
	 */
	public function importAgentCommission($fileName){
		$AgentCommissions = $this->readJson($fileName);
		if($AgentCommissions){
			$duplicateAgentCommission 	= array();
			$successAgentCommission 	= array();
			$errorAgentCommission 		= array();
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
					array_push($duplicateAgentCommission, $AgentCommission->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateAgentCommission']= $duplicateAgentCommission;
			$response['errorAgentCommission']	 = $errorAgentCommission;
			$response['successAgentCommission']  = $successAgentCommission;
			return $response;
		}
	}
	/**
	 * To Export CloseSeatInfo Data.
	 * @/exportcloseseatinfojson/{id}/{fname}
	 */
	public function exportCloseSeatInfo($operator_id,$fileName){
		$CloseSeatInfos = CloseSeatInfo::all()->toarray();
		if($CloseSeatInfos){
			$this->saveFile($fileName, $CloseSeatInfos);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import CloseSeatInfo Data.
	 */
	public function importCloseSeatInfo($fileName){
		$CloseSeatInfos = $this->readJson($fileName);
		if($CloseSeatInfos){
			$duplicateCloseSeatInfo 	= array();
			$successCloseSeatInfo 	= array();
			$errorCloseSeatInfo 		= array();
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
					array_push($duplicateCloseSeatInfo, $CloseSeatInfo->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateCloseSeatInfo']= $duplicateCloseSeatInfo;
			$response['errorCloseSeatInfo']	 = $errorCloseSeatInfo;
			$response['successCloseSeatInfo']  = $successCloseSeatInfo;
			return $response;
		}
	}
	/**
	 * To Export CommissionType Data.
	 * @/exportcommissiontypejson/{id}/{fname}
	 */
	public function exportCommissionType($operator_id,$fileName){
		$CommissionTypes = CommissionType::all()->toarray();
		if($CommissionTypes){
			$this->saveFile($fileName, $CommissionTypes);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import CommissionType Data.
	 */
	public function importCommissionType($fileName){
		$CommissionTypes = $this->readJson($fileName);
		if($CommissionTypes){
			$duplicateCommissionType 	= array();
			$successCommissionType 	= array();
			$errorCommissionType 		= array();
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
					array_push($duplicateCommissionType, $CommissionType->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateCommissionType']= $duplicateCommissionType;
			$response['errorCommissionType']	 = $errorCommissionType;
			$response['successCommissionType']  = $successCommissionType;
			return $response;
		}
	}
	/**
	 * To Export OperatorGroup Data.
	 * @/exportoperatorgroupjson/{id}/{fname}
	 */
	public function exportOperatorGroup($operator_id,$fileName){
		$OperatorGroups = OperatorGroup::whereoperator_id($operator_id)->get()->toarray();
		if($OperatorGroups){
			$this->saveFile($fileName, $OperatorGroups);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import OperatorGroup Data.
	 */
	public function importOperatorGroup($fileName){
		$OperatorGroups = $this->readJson($fileName);
		if($OperatorGroups){
			$duplicateOperatorGroup 	= array();
			$successOperatorGroup 	= array();
			$errorOperatorGroup 		= array();
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
					array_push($duplicateOperatorGroup, $OperatorGroup->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateOperatorGroup']= $duplicateOperatorGroup;
			$response['errorOperatorGroup']	 = $errorOperatorGroup;
			$response['successOperatorGroup']  = $successOperatorGroup;
			return $response;
		}
	}
	/**
	 * To Export OperatorGroupUser Data.
	 * @/exportoperatorgroupuserjson/{id}/{fname}
	 */
	public function exportOperatorGroupUser($operator_id,$fileName){
		$OperatorGroupUsers = OperatorGroupUser::whereoperator_id($operator_id)->get()->toarray();
		if($OperatorGroupUsers){
			$this->saveFile($fileName, $OperatorGroupUsers);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import OperatorGroupUser Data.
	 */
	public function importOperatorGroupUser($fileName){
		$OperatorGroupUsers = $this->readJson($fileName);
		if($OperatorGroupUsers){
			$duplicateOperatorGroupUser 	= array();
			$successOperatorGroupUser 	= array();
			$errorOperatorGroupUser 		= array();
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
					array_push($duplicateOperatorGroupUser, $OperatorGroupUser->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateOperatorGroupUser']= $duplicateOperatorGroupUser;
			$response['errorOperatorGroupUser']	 = $errorOperatorGroupUser;
			$response['successOperatorGroupUser']  = $successOperatorGroupUser;
			return $response;
		}
	}
	/**
	 * To Export User Data.
	 * @/exportuserjson/{id}/{fname}
	 */
	public function exportUser($operator_id,$fileName){
		$Users = User::all()->toarray();
		if($Users){
			$this->saveFile($fileName, $Users);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import User Data.
	 */
	public function importUser($fileName){
		$Users = $this->readJson($fileName);
		if($Users){
			$duplicateUser 	= array();
			$successUser 	= array();
			$errorUser 		= array();
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
					array_push($duplicateUser, $User->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateUser']= $duplicateUser;
			$response['errorUser']	 = $errorUser;
			$response['successUser']  = $successUser;
			return $response;
		}
	}
	/**
	 * To Export Operator Data.
	 * @/exportoperatorjson/{id}/{fname}
	 */
	public function exportOperator($operator_id,$fileName){
		$Operators = Operator::all()->toarray();
		if($Operators){
			$this->saveFile($fileName, $Operators);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import Operator Data.
	 */
	public function importOperator($fileName){
		$Operators = $this->readJson($fileName);
		if($Operators){
			$duplicateOperator 	= array();
			$successOperator 	= array();
			$errorOperator 		= array();
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
					array_push($duplicateOperator, $Operator->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateOperator']= $duplicateOperator;
			$response['errorOperator']	 = $errorOperator;
			$response['successOperator']  = $successOperator;
			return $response;
		}
	}
	/**
	 * To Export SeatInfo Data.
	 * @/exportseatinfojson/{id}/{fname}
	 */
	public function exportSeatInfo($operator_id,$fileName){
		$SeatInfos = SeatInfo::all()->toarray();
		if($SeatInfos){
			$this->saveFile($fileName, $SeatInfos);
			return "true";
		}else{
			return "false";
		}
	}
	/**
	 * To Import SeatInfo Data.
	 */
	public function importSeatInfo($fileName){
		$SeatInfos = $this->readJson($fileName);
		if($SeatInfos){
			$duplicateSeatInfo 	= array();
			$successSeatInfo 	= array();
			$errorSeatInfo 		= array();
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
					array_push($duplicateSeatInfo, $SeatInfo->toarray());
				}
			}
			$response['status_code'] = 1; //1 is success;
			$response['message']	 = 'Successfully your import data.';
			$response['duplicateSeatInfo']= $duplicateSeatInfo;
			$response['errorSeatInfo']	 = $errorSeatInfo;
			$response['successSeatInfo']  = $successSeatInfo;
			return $response;
		}
	}
	/**
	 * To Export Sale Transaction Data
	 * @/exportsaleorderjson/{id}/{fname}
	 */
	public function exportSaleOrderJson($fileName){
		$startDate	  = $this->getDate();
		$sync 		  = Sync::wherename($fileName)->first(); // To Check/Update Latest Sync Date.
		if($sync){
			$startDate 	  = $sync->last_sync_date; // To Get Data that Not Sync.
			$sync->last_updated_date = $this->getDate();
			$sync->last_sync_date = $this->getDate();
			$sync->update();
		}else{
			$sync 						= new Sync();
			$sync->name 				= $fileName;
			$sync->last_updated_date 	= $this->getDate();
			$sync->last_sync_date 		= $this->getDate();
			$sync->save();
		}
		$saleOrders 	= SaleOrder::with('saleitems')->where('created_at','>=', $startDate)->get()->toarray();
		if($saleOrders){
			$this->saveFile($fileName, $saleOrders);
			return "true";
		}else{
			return "false";
		}
		
	}
	/**
	 * To Import Sale Transaction Data.
	 */
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
	/**
	 * To Export Payment Transaction Data
	 */
	public function exportPaymentJson($fileName){
		$agentDeposits 	= AgentDeposit::where('created_at','Like','%'.$this->getDate().'%')->get()->toarray();
		if($agentDeposits){
			$this->saveFile($fileName, $agentDeposits);
			return "true";
		}else{
			return "false";
		}
		
	}
	/**
	 * To Import Payment Transaction Data.
	 */
	public function importPaymentJson($fileName){
		$agentDeposits = $this->readJson($fileName);
		$duplicateAgentDeposits 	= array();
		$errorAgentDeposits	 		= array();
		$successAgentDeposits	 	= array();
		if($agentDeposits){
			foreach ($agentDeposits as $rows) {
				$agentDeposit = AgentDeposit::whereagent_id($rows['agent_id'])
							->whereagent_id($rows['agent_id'])
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
			}
			$response['status_code']  = 1; // 1 is success.
			$response['message'] = "Successfully your data was saved.";
			$response["duplicateAgentDeposits"] = $duplicateAgentDeposits;
			$response["errorAgentDeposits"] = $errorAgentDeposits;
			$response["successAgentDeposits"] = $successAgentDeposits;
			return $response;
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
			return $e;
		}
		
		
	}
	/**
	 * To Upload Data to Server.
	 */
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
	/**
	 * To Download Data from Server.
	 */
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
	/**
	 * To Read from Json File.
	 */
	public function readJson($fileName){
		$jsonString = file_get_contents("remote_file/".$fileName);
		$jsonArr = json_decode($jsonString,true);
		return $jsonArr;
	}
}