package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class PopularTriptime {
	
	@Expose
	private String id;
	@Expose
	private String trip;
	@Expose
	private String time;
	@Expose
	private String classes;
	@Expose
	private Integer percentage;
	@SerializedName("sold_total_seat")
	@Expose
	private Integer soldTotalSeat;
	@SerializedName("total_seat")
	@Expose
	private Integer totalSeat;
	@SerializedName("total_amount")
	@Expose
	private Integer totalAmount;
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getTrip() {
	return trip;
	}
	
	public void setTrip(String trip) {
	this.trip = trip;
	}
	
	public String getTime() {
	return time;
	}
	
	public void setTime(String time) {
	this.time = time;
	}
	
	public String getClasses() {
	return classes;
	}
	
	public void setClasses(String classes) {
	this.classes = classes;
	}
	
	public Integer getPercentage() {
	return percentage;
	}
	
	public void setPercentage(Integer percentage) {
	this.percentage = percentage;
	}
	
	public Integer getSoldTotalSeat() {
	return soldTotalSeat;
	}
	
	public void setSoldTotalSeat(Integer soldTotalSeat) {
	this.soldTotalSeat = soldTotalSeat;
	}
	
	public Integer getTotalSeat() {
	return totalSeat;
	}
	
	public void setTotalSeat(Integer totalSeat) {
	this.totalSeat = totalSeat;
	}
	
	public Integer getTotalAmount() {
	return totalAmount;
	}
	
	public void setTotalAmount(Integer totalAmount) {
	this.totalAmount = totalAmount;
	}

}