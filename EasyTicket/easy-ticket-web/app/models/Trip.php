<?php
	Class Trip extends Eloquent 
	{ 
		protected $table = 'tbl_trip';
		protected $fillable = array('id','operator_id','from','to','class_id','available_day','price','foreign_price','commission','time','seat_plan_id');
		public $timestamps = false;

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
			return $this->hasOne('ExtraDestination', 'trip_id');
		}


	}
?>
