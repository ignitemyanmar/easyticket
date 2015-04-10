<?php
	Class Trip extends Eloquent 
	{ 
		protected $table = 'tbl_trip';
		protected $fillable = array('id','operator_id','from','to','class_id','available_day','price','foreign_price','commission','time','departure_time','ever_close','from_close_date','to_close_date','remark','seat_plan_id','status','created_at','updated_at');

		public function operator(){
			return $this->belongsTo('Operator', 'operator_id');
		}
		
		public function busclass(){
			return $this->belongsTo('Classes', 'class_id');
		}

		public function from_city(){
			return $this->belongsTo('City', 'from');
		}

		public function to_city(){
			return $this->belongsTo('City', 'to');
		}

		public function seat_plan(){
			return $this->belongsTo('SeatingPlan', 'seat_plan_id');
		}

		public function agentcommission(){
			return $this->hasMany('AgentCommission', 'trip_id');
		}

		public function extendcity(){
			return $this->hasMany('ExtraDestination', 'trip_id');
		}

		public function closeseat(){
			return $this->hasMany('CloseSeatInfo','trip_id');
		}


	}
?>
