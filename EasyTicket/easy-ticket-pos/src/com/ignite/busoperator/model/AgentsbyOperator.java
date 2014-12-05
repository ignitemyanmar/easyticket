package com.ignite.busoperator.model;
	
	import java.util.ArrayList;
	import java.util.List;
	import com.google.gson.annotations.Expose;
	import com.google.gson.annotations.SerializedName;
	
	public class AgentsbyOperator {
	
	@SerializedName("operator_id")
	@Expose
	private String operatorId;
	@Expose
	private List<Agent> agents = new ArrayList<Agent>();
	
	public String getOperatorId() {
	return operatorId;
	}
	
	public void setOperatorId(String operatorId) {
	this.operatorId = operatorId;
	}
	
	public List<Agent> getAgents() {
	return agents;
	}
	
	public void setAgents(List<Agent> agents) {
	this.agents = agents;
	}

}