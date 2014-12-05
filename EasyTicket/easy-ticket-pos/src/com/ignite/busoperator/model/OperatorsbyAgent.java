package com.ignite.busoperator.model;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;

public class OperatorsbyAgent {

	@Expose
	private String agent_id;
	@Expose
	private List<Operator> operators = new ArrayList<Operator>();
	
	public String getAgent_id() {
	return agent_id;
	}
	
	public void setAgent_id(String agent_id) {
	this.agent_id = agent_id;
	}
	
	public List<Operator> getOperators() {
	return operators;
	}
	
	public void setOperators(List<Operator> operators) {
	this.operators = operators;
	}

	@Override
	public String toString() {
		return "OperatorsbyAgent [agent_id=" + agent_id + ", operators="
				+ operators + "]";
	}

}