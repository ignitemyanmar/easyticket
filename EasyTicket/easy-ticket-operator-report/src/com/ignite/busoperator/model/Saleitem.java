package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Saleitem {
	
	@Expose
	private String id;
	@SerializedName("order_id")
	@Expose
	private String orderId;
	@SerializedName("ticket_no")
	@Expose
	private String ticketNo;
	@SerializedName("seat_no")
	@Expose
	private String seatNo;
	@SerializedName("nrc_no")
	@Expose
	private String nrcNo;
	@Expose
	private String name;
	@Expose
	private String phone;
	@SerializedName("busoccurance_id")
	@Expose
	private Integer busoccuranceId;
	@Expose
	private Integer operator;
	@Expose
	private Integer price;
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getOrderId() {
	return orderId;
	}
	
	public void setOrderId(String orderId) {
	this.orderId = orderId;
	}
	
	public String getTicketNo() {
	return ticketNo;
	}
	
	public void setTicketNo(String ticketNo) {
	this.ticketNo = ticketNo;
	}
	
	public String getSeatNo() {
	return seatNo;
	}
	
	public void setSeatNo(String seatNo) {
	this.seatNo = seatNo;
	}
	
	public String getNrcNo() {
	return nrcNo;
	}
	
	public void setNrcNo(String nrcNo) {
	this.nrcNo = nrcNo;
	}
	
	public String getName() {
	return name;
	}
	
	public void setName(String name) {
	this.name = name;
	}
	
	public String getPhone() {
	return phone;
	}
	
	public void setPhone(String phone) {
	this.phone = phone;
	}
	
	public Integer getBusoccuranceId() {
	return busoccuranceId;
	}
	
	public void setBusoccuranceId(Integer busoccuranceId) {
	this.busoccuranceId = busoccuranceId;
	}
	
	public Integer getOperator() {
	return operator;
	}
	
	public void setOperator(Integer operator) {
	this.operator = operator;
	}
	
	public Integer getPrice() {
	return price;
	}
	
	public void setPrice(Integer price) {
	this.price = price;
	}

}