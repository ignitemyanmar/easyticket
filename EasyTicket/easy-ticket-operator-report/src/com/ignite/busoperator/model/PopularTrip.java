package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class PopularTrip {
	
	@Expose
	private Integer id;
	@Expose
	private Integer from;
	@Expose
	private Integer to;
	@Expose
	private String trip;
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
	
	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public Integer getFrom() {
	return from;
	}
	
	public void setFrom(Integer from) {
	this.from = from;
	}
	
	public Integer getTo() {
	return to;
	}
	
	public void setTo(Integer to) {
	this.to = to;
	}
	
	public String getTrip() {
	return trip;
	}
	
	public void setTrip(String trip) {
	this.trip = trip;
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