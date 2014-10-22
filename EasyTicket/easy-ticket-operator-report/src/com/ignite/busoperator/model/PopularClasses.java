package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class PopularClasses {
	
	@Expose
	private String id;
	@Expose
	private String name;
	@SerializedName("total_amount")
	@Expose
	private Integer totalAmount;
	@Expose
	private Integer count;
	@SerializedName("purchased_total_seat")
	@Expose
	private Integer purchasedTotalSeat;
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getName() {
	return name;
	}
	
	public void setName(String name) {
	this.name = name;
	}
	
	public Integer getTotalAmount() {
	return totalAmount;
	}
	
	public void setTotalAmount(Integer totalAmount) {
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