package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class TodayBus {
	@Expose
	private String bus_id;
	@Expose
	private String classes;
	@Expose
	private String from;
	@Expose
	private String to;
	@Expose
	private String time;
	@Expose
	private Integer sold_seat;
	@Expose
	private Integer total_seat;
	@Expose
	private String price;
	@Expose
	private Integer sold_amount;
	
	public String getBus_id() {
		return bus_id;
	}

	public void setBus_id(String bus_id) {
		this.bus_id = bus_id;
	}

	public String getClasses() {
		return classes;
	}

	public void setClasses(String classes) {
		this.classes = classes;
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
	
	public String getTime() {
	return time;
	}
	
	public void setTime(String time) {
	this.time = time;
	}
	
	public Integer getSold_seat() {
	return sold_seat;
	}
	
	public void setSold_seat(Integer sold_seat) {
	this.sold_seat = sold_seat;
	}
	
	public Integer getTotal_seat() {
	return total_seat;
	}
	
	public void setTotal_seat(Integer total_seat) {
	this.total_seat = total_seat;
	}
	
	public String getPrice() {
	return price;
	}
	
	public void setPrice(String price) {
	this.price = price;
	}
	
	public Integer getSold_amount() {
	return sold_amount;
	}
	
	public void setSold_amount(Integer sold_amount) {
	this.sold_amount = sold_amount;
	}

}