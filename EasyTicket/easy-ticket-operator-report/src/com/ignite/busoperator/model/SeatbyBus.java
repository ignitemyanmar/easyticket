package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SeatbyBus {
	
	@Expose
	private String bus_id;
	@SerializedName("Trip")
	@Expose
	private String trip;
	@Expose
	private String sale_id;
	@Expose
	private String order_id;
	@Expose
	private String order_date;
	@Expose
	private String seat_no;
	@Expose
	private String ticket_no;
	@Expose
	private Integer free_ticket;
	@Expose
	private String name;
	@Expose
	private String agent;
	@Expose
	private Integer price;
	@Expose
	private Integer commission;
	@Expose
	private String departure_date;
	@Expose
	private String departure_time;

	public String getBus_id() {
	return bus_id;
	}
	
	public void setBus_id(String bus_id) {
	this.bus_id = bus_id;
	}
	
	public String getTrip() {
	return trip;
	}
	
	public void setTrip(String trip) {
	this.trip = trip;
	}
	
	public String getSale_id() {
	return sale_id;
	}
	
	public void setSale_id(String sale_id) {
	this.sale_id = sale_id;
	}
	
	public String getOrder_id() {
	return order_id;
	}
	
	public void setOrder_id(String order_id) {
	this.order_id = order_id;
	}
	
	public String getOrder_date() {
	return order_date;
	}
	
	public void setOrder_date(String order_date) {
	this.order_date = order_date;
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
	
	public Integer getFree_ticket() {
		return free_ticket;
	}

	public void setFree_ticket(Integer free_ticket) {
		this.free_ticket = free_ticket;
	}

	public String getName() {
	return name;
	}
	
	public void setName(String name) {
	this.name = name;
	}
	
	public String getAgent() {
	return agent;
	}
	
	public void setAgent(String agent) {
	this.agent = agent;
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

}