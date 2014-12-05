package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class AgentCommission {
	
	@Expose
	private Integer id;
	@SerializedName("agent_id")
	@Expose
	private Integer agentId;
	@SerializedName("trip_id")
	@Expose
	private Integer tripId;
	@SerializedName("commission_id")
	@Expose
	private Integer commissionId;
	@Expose
	private Integer commission;
	@Expose
	private String time;
	@Expose
	private String trip;
	@SerializedName("commission_name")
	@Expose
	private String commissionName;
	
	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public Integer getAgentId() {
	return agentId;
	}
	
	public void setAgentId(Integer agentId) {
	this.agentId = agentId;
	}
	
	public Integer getTripId() {
	return tripId;
	}
	
	public void setTripId(Integer tripId) {
	this.tripId = tripId;
	}
	
	public Integer getCommissionId() {
	return commissionId;
	}
	
	public void setCommissionId(Integer commissionId) {
	this.commissionId = commissionId;
	}
	
	public Integer getCommission() {
	return commission;
	}
	
	public void setCommission(Integer commission) {
	this.commission = commission;
	}
	
	public String getTime() {
		return time;
	}

	public void setTime(String time) {
		this.time = time;
	}

	public String getTrip() {
	return trip;
	}
	
	public void setTrip(String trip) {
	this.trip = trip;
	}
	
	public String getCommissionName() {
	return commissionName;
	}
	
	public void setCommissionName(String commissionName) {
	this.commissionName = commissionName;
	}

}