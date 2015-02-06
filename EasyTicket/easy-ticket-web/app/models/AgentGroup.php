<?php
	Class AgentGroup extends Eloquent 
	{ 
		protected $table = 'tbl_agentgroup';
		protected $fillable = array('id','name','operator_id','created_at','updated_at');

		public function agents(){
			return $this->hasMany('Agent','agentgroup_id');
		}

		public function operator(){
			return $this->belongsTo('Operator','operator_id');
		}
	}
?>
