package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class PopularAgent {
	@Expose
	private Integer id;
	@Expose
	private String name;
	@SerializedName("total_amount")
	@Expose
	private String totalAmount;
	@Expose
	private Integer count;
	@SerializedName("purchased_total_seat")
	@Expose
	private Integer purchasedTotalSeat;
	
	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public String getName() {
	return name;
	}
	
	public void setName(String name) {
	this.name = name;
	}
	
	public String getTotalAmount() {
	return totalAmount;
	}
	
	public void setTotalAmount(String totalAmount) {
	this.totalAmount = totalAmount;
	}
	
	public Integer getCount() {
	return count;
	}
	
	public void setCount(Integer count) {
	this.count = count;
	}
	
	public Integer getPurchasedTotalSeat() {
	return purchasedTotalSeat;
	}
	
	public void setPurchasedTotalSeat(Integer purchasedTotalSeat) {
	this.purchasedTotalSeat = purchasedTotalSeat;
	}
	
	}