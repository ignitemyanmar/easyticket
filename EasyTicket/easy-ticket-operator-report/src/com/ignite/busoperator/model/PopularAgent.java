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
	@SerializedName("label_name")
	@Expose
	private String labelName;
	@SerializedName("label_color")
	@Expose
	private String labelColor;
	
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

	public String getLabelName() {
		return labelName;
	}

	public void setLabelName(String labelName) {
		this.labelName = labelName;
	}

	public String getLabelColor() {
		return labelColor;
	}

	public void setLabelColor(String labelColor) {
		this.labelColor = labelColor;
	}
}