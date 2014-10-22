package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SeatReport {

	@Expose
	private String bus_no;
	@Expose
	private String trip;
	@SerializedName("class")
	@Expose
	private String _class;
	@Expose
	private String departure_date;
	@Expose
	private String departure_time;
	@Expose
	private String seat_no;
	@Expose
	private String ticket_no;
	@Expose
	private String orderdate;
	@Expose
	private String agent;
	@Expose
	private String customer_name;
	@Expose
	private String operator;
	@Expose
	private String price;
	@Expose
	private Integer free_ticket;
	
	public String getBus_no() {
	return bus_no;
	}
	
	public void setBus_no(String bus_no) {
	this.bus_no = bus_no;
	}
	
	public String getTrip() {
	return trip;
	}
	
	public void setTrip(String trip) {
	this.trip = trip;
	}
	
	public String getClass_() {
	return _class;
	}
	
	public void setClass_(String _class) {
	this._class = _class;
	}
	
	public String getDeparture_date() {
	return departure_date;
	}
	
	public void setDeparture_date(String departure_date) {
	this.departure_date = departure_date;
	}
	
	public String getDeparture_time() {
	return departure_time;
	}
	
	public void setDeparture_time(String departure_time) {
	this.departure_time = departure_time;
	}
	
	public String getSeat_no() {
	return seat_no;
	}
	
	public void setSeat_no(String seat_no) {
	this.seat_no = seat_no;
	}
	
	public String getTicket_no() {
	return ticket_no;
	}
	
	public void setTicket_no(String ticket_no) {
	this.ticket_no = ticket_no;
	}
	
	public String getOrderdate() {
	return orderdate;
	}
	
	public void setOrderdate(String orderdate) {
	this.orderdate = orderdate;
	}
	
	public String getAgent() {
	return agent;
	}
	
	public void setAgent(String agent) {
	this.agent = agent;
	}
	
	public String getCustomer_name() {
	return customer_name;
	}
	
	public void setCustomer_name(String customer_name) {
	this.customer_name = customer_name;
	}
	
	public String getOperator() {
	return operator;
	}
	
	public void setOperator(String operator) {
	this.operator = operator;
	}
	
	public String getPrice() {
	return price;
	}
	
	public void setPrice(String price) {
	this.price = price;
	}

	public Integer getFree_ticket() {
		return free_ticket;
	}

	public void setFree_ticket(Integer free_ticket) {
		this.free_ticket = free_ticket;
	}
	

}