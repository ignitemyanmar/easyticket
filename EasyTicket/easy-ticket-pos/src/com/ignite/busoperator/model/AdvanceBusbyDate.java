package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class AdvanceBusbyDate {
	
	@Expose
	private Integer purchased_total_seat;
	@Expose
	private Integer total_amout;
	@Expose
	private Integer bus_id;
	@Expose
	private String bus_no;
	@SerializedName("class")
	@Expose
	private String _class;
	@Expose
	private String from;
	@Expose
	private String to;
	@Expose
	private String departure_date;
	@Expose
	private String time;
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
	
	public Integer getBus_id() {
	return bus_id;
	}
	
	public void setBus_id(Integer bus_id) {
	this.bus_id = bus_id;
	}
	
	public String getBus_no() {
	return bus_no;
	}
	
	public void setBus_no(String bus_no) {
	this.bus_no = bus_no;
	}
	
	public String getClass_() {
	return _class;
	}
	
	public void setClass_(String _class) {
	this._class = _class;
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

}