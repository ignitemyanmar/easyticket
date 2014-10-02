package com.ignite.busoperator.model;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SaleDetail {
	
	@Expose
	private Integer id;
	@Expose
	private String orderdate;
	@SerializedName("agent_id")
	@Expose
	private Integer agentId;
	@SerializedName("operator_id")
	@Expose
	private Integer operatorId;
	@Expose
	private String customer;
	@Expose
	private String phone;
	@Expose
	private String operator;
	@Expose
	private String agent;
	@Expose
	private String trip;
	@SerializedName("departure_date")
	@Expose
	private String departureDate;
	@SerializedName("departure_time")
	@Expose
	private String departureTime;
	@SerializedName("class")
	@Expose
	private String _class;
	@SerializedName("total_ticket")
	@Expose
	private Integer totalTicket;
	@Expose
	private Integer price;
	@Expose
	private Integer commission;
	@Expose
	private Integer amount;
	@Expose
	private List<Saleitem> saleitems = new ArrayList<Saleitem>();
	
	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public String getOrderdate() {
	return orderdate;
	}
	
	public void setOrderdate(String orderdate) {
	this.orderdate = orderdate;
	}
	
	public Integer getAgentId() {
	return agentId;
	}
	
	public void setAgentId(Integer agentId) {
	this.agentId = agentId;
	}
	
	public Integer getOperatorId() {
	return operatorId;
	}
	
	public void setOperatorId(Integer operatorId) {
	this.operatorId = operatorId;
	}
	
	public String getCustomer() {
	return customer;
	}
	
	public void setCustomer(String customer) {
	this.customer = customer;
	}
	
	public String getPhone() {
	return phone;
	}
	
	public void setPhone(String phone) {
	this.phone = phone;
	}
	
	public String getOperator() {
	return operator;
	}
	
	public void setOperator(String operator) {
	this.operator = operator;
	}
	
	public String getAgent() {
	return agent;
	}
	
	public void setAgent(String agent) {
	this.agent = agent;
	}
	
	public String getTrip() {
	return trip;
	}
	
	public void setTrip(String trip) {
	this.trip = trip;
	}
	
	public String getDepartureDate() {
	return departureDate;
	}
	
	public void setDepartureDate(String departureDate) {
	this.departureDate = departureDate;
	}
	
	public String getDepartureTime() {
	return departureTime;
	}
	
	public void setDepartureTime(String departureTime) {
	this.departureTime = departureTime;
	}
	
	public String getClass_() {
	return _class;
	}
	
	public void setClass_(String _class) {
	this._class = _class;
	}
	
	public Integer getTotalTicket() {
	return totalTicket;
	}
	
	public void setTotalTicket(Integer totalTicket) {
	this.totalTicket = totalTicket;
	}
	
	public Integer getPrice() {
	return price;
	}
	
	public void setPrice(Integer price) {
	this.price = price;
	}

	public Integer getCommission() {
		return commission;
	}

	public void setCommission(Integer commission) {
		this.commission = commission;
	}

	public Integer getAmount() {
	return amount;
	}
	
	public void setAmount(Integer amount) {
	this.amount = amount;
	}
	
	public List<Saleitem> getSaleitems() {
	return saleitems;
	}
	
	public void setSaleitems(List<Saleitem> saleitems) {
	this.saleitems = saleitems;
	}

}