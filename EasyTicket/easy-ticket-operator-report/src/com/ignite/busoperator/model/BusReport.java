package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class BusReport {
	@SerializedName("purchased_total_seat")
	@Expose
	private Integer purchasedTotalSeat;
	@SerializedName("total_amout")
	@Expose
	private Integer totalAmout;
	@SerializedName("bus_id")
	@Expose
	private Integer busId;
	@SerializedName("bus_no")
	@Expose
	private String busNo;
	@Expose
	private String from;
	@Expose
	private String to;
	@SerializedName("class")
	@Expose
	private String Classes;
	@SerializedName("departure_date")
	@Expose
	private String departureDate;
	@Expose
	private String time;
	@SerializedName("total_seat")
	@Expose
	private Integer totalSeat;

	public Integer getPurchasedTotalSeat() {
	return purchasedTotalSeat;
	}

	public void setPurchasedTotalSeat(Integer purchasedTotalSeat) {
	this.purchasedTotalSeat = purchasedTotalSeat;
	}

	public Integer getTotalAmout() {
	return totalAmout;
	}

	public void setTotalAmout(Integer totalAmout) {
	this.totalAmout = totalAmout;
	}

	public Integer getBusId() {
	return busId;
	}

	public void setBusId(Integer busId) {
	this.busId = busId;
	}

	public String getBusNo() {
	return busNo;
	}

	public void setBusNo(String busNo) {
	this.busNo = busNo;
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
	
	public String getClasses() {
		return Classes;
	}

	public void setClass(String class1) {
		Classes = class1;
	}

	public String getDepartureDate() {
	return departureDate;
	}

	public void setDepartureDate(String departureDate) {
	this.departureDate = departureDate;
	}

	public String getTime() {
	return time;
	}

	public void setTime(String time) {
	this.time = time;
	}

	public Integer getTotalSeat() {
	return totalSeat;
	}

	public void setTotalSeat(Integer totalSeat) {
	this.totalSeat = totalSeat;
	}
}
