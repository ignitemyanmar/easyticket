package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class TodayBusbyTime {
	
	@Expose
	private String bus_id;
	@Expose
	private String bus_no;
	@Expose
	private String from;
	@Expose
	private String to;
	@SerializedName("class")
	@Expose
	private String _class;
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
	
	public TodayBusbyTime(String bus_id, String bus_no, String from, String to,
			String _class, String time, Integer sold_seat, Integer total_seat,
			String price, Integer sold_amount) {
		super();
		this.bus_id = bus_id;
		this.bus_no = bus_no;
		this.from = from;
		this.to = to;
		this._class = _class;
		this.time = time;
		this.sold_seat = sold_seat;
		this.total_seat = total_seat;
		this.price = price;
		this.sold_amount = sold_amount;
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
	
	public String getClass_() {
	return _class;
	}
	
	public void setClass_(String _class) {
	this._class = _class;
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