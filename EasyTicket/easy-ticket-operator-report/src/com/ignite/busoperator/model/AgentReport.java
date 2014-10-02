package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class AgentReport {
	
	@Expose
	private String bus_id;
	@Expose
	private String agent_id;
	@Expose
	private String agent;
	@Expose
	private Integer sold_tickets;
	@Expose
	private Integer total_amount;
	@Expose
	private Integer total_seats;
	
	public AgentReport(String bus_id, String agent_id, String agent,
			Integer sold_tickets, Integer total_amount, Integer total_seats) {
		super();
		this.bus_id = bus_id;
		this.agent_id = agent_id;
		this.agent = agent;
		this.sold_tickets = sold_tickets;
		this.total_amount = total_amount;
		this.total_seats = total_seats;
	}

	public String getBus_id() {
	return bus_id;
	}
	
	public void setBus_id(String bus_id) {
	this.bus_id = bus_id;
	}
	
	public String getAgent_id() {
	return agent_id;
	}
	
	public void setAgent_id(String agent_id) {
	this.agent_id = agent_id;
	}
	
	public String getAgent() {
	return agent;
	}
	
	public void setAgent(String agent) {
	this.agent = agent;
	}
	
	public Integer getSold_tickets() {
	return sold_tickets;
	}
	
	public void setSold_tickets(Integer sold_tickets) {
	this.sold_tickets = sold_tickets;
	}
	
	public Integer getTotal_amount() {
	return total_amount;
	}
	
	public void setTotal_amount(Integer total_amount) {
	this.total_amount = total_amount;
	}
	
	public Integer getTotal_seats() {
	return total_seats;
	}
	
	public void setTotal_seats(Integer total_seats) {
	this.total_seats = total_seats;
	}

}