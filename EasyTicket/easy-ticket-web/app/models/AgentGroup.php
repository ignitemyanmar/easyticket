<?php
	Class AgentGroup extends Eloquent 
	{ 
		protected $table = 'tbl_agentgroup';
		protected $fillable = array('id','name','operator_id','created_at','updated_at');
	}
?>
