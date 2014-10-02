package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class AdvanceBus {
	
	private Integer purchased_total_seat;
	@Expose
	private Integer total_amout;
	@Expose
	private String from;
	@Expose
	private String to;
	@Expose
	private String departure_date;
	@Expose
	private Integer total_seat;

	public Integer getPurchased_total_seat() {
	return purchased_total_seat;
	}
	
	public void setPurchased_total_seat(Integer purchased_total_seat) {
	this.purchased_total_seat = purchased_total_seat;
	}
	
	public Integer getTotal_amout() {
	return total_amout;
	}
	
	public void setTotal_amout(Integer total_amout) {
	this.total_amout = total_amout;
	}
	
	public String getFrom() {
	return from;
	}
	
	public void setFrom(String from) {
	this.from = from;
	}
	
	public String getTo() {
	return to;
	}
	
	public void setTo(String to) {
	this.to = to;
	}
	
	public String getDeparture_date() {
	return departure_date;
	}
	
	public void setDeparture_date(String departure_date) {
	this.departure_date = departure_date;
	}
	
	public Integer getTotal_seat() {
	return total_seat;
	}
	
	public void setTotal_seat(Integer total_seat) {
	this.total_seat = total_seat;
	}

}