package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class Seat_list {
	
	@Expose
	private String id;
	@Expose
	private String seat_no;
	@Expose
	private String status;
	@Expose
	private String price;
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getSeat_no() {
	return seat_no;
	}
	
	public void setSeat_no(String seat_no) {
	this.seat_no = seat_no;
	}
	
	public String getStatus() {
	return status;
	}
	
	public void setStatus(String status) {
	this.status = status;
	}
	
	public String getPrice() {
	return price;
	}
	
	public void setPrice(String price) {
	this.price = price;
	}

}