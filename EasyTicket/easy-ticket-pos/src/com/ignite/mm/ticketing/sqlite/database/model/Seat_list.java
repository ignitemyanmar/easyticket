package com.ignite.mm.ticketing.sqlite.database.model;

import com.google.gson.annotations.Expose;

public class Seat_list {
	
	@Expose
	private Integer id;
	@Expose
	private String seat_no;
	@Expose
	private Integer status;
	@Expose
	private Integer booking;
	@Expose
	private Integer operatorgroup_id;
	
	private CustomerInfo customerInfo;
	
	public Seat_list(Integer id, String seat_no, Integer status) {
		super();
		this.id = id;
		this.seat_no = seat_no;
		this.status = status;
	}

	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public String getSeat_no() {
	return seat_no;
	}
	
	public void setSeat_no(String seat_no) {
	this.seat_no = seat_no;
	}
	
	public Integer getStatus() {
	return status;
	}
	
	public void setStatus(Integer status) {
	this.status = status;
	}
	
	public Integer getBooking() {
		return booking;
	}

	public void setBooking(Integer booking) {
		this.booking = booking;
	}

	public Integer getOperatorgroup_id() {
		return operatorgroup_id;
	}

	public void setOperatorgroup_id(Integer operatorgroup_id) {
		this.operatorgroup_id = operatorgroup_id;
	}

	public CustomerInfo getCustomerInfo() {
		return customerInfo;
	}

	public void setCustomerInfo(CustomerInfo customerInfo) {
		this.customerInfo = customerInfo;
	}
	
}