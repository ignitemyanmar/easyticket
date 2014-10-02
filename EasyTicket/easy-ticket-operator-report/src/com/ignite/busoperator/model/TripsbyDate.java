package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class TripsbyDate {
	@Expose
	private String bus_id;	
	@Expose
	private String bus_no;
	@Expose
	private String departure_date;
	@Expose
	private String time;
	@Expose
	private Integer total_seat;
	@Expose
	private Integer purchased_total_seat;
	@Expose
	private Integer total_amout;
	@Expose
	private String from;
	@Expose
	private String to;
	
	public TripsbyDate(String bus_id, String bus_no, String departure_date,
			String time, Integer total_seat, Integer purchased_total_seat,
			Integer total_amout, String from, String to) {
		super();
		this.bus_id = bus_id;
		this.bus_no = bus_no;
		this.departure_date = departure_date;
		this.time = time;
		this.total_seat = total_seat;
		this.purchased_total_seat = purchased_total_seat;
		this.total_amout = total_amout;
		this.from = from;
		this.to = to;
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

	public String getBus_id() {
		return bus_id;
	}

	public void setBus_id(String bus_id) {
		this.bus_id = bus_id;
	}

	public String getBus_no() {
		return bus_no;
	}
	
	public void setBus_no(String bus_no) {
	this.bus_no = bus_no;
	}
	
	public String getDeparture_date() {
	return departure_date;
	}
	
	public void setDeparture_date(String departure_date) {
	this.departure_date = departure_date;
	}
	
	public String getTime() {
	return time;
	}
	
	public void setTime(String time) {
	this.time = time;
	}
	
	public Integer getTotal_seat() {
	return total_seat;
	}
	
	public void setTotal_seat(Integer total_seat) {
	this.total_seat = total_seat;
	}
	
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

}