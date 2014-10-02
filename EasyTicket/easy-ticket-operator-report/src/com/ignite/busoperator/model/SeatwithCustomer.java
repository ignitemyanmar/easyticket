package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SeatwithCustomer {
	
	@Expose
	private String id;
	@SerializedName("seat_no")
	@Expose
	private String seatNo;
	@Expose
	private String price;
	@Expose
	private Integer status;
	@Expose
	private Customer customer;
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getSeatNo() {
	return seatNo;
	}
	
	public void setSeatNo(String seatNo) {
	this.seatNo = seatNo;
	}
	
	public String getPrice() {
	return price;
	}
	
	public void setPrice(String price) {
	this.price = price;
	}
	
	public Integer getStatus() {
	return status;
	}
	
	public void setStatus(Integer status) {
	this.status = status;
	}
	
	public Customer getCustomer() {
	return customer;
	}
	
	public void setCustomer(Customer customer) {
	this.customer = customer;
	}

}