package com.ignite.busoperator.model;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SeatPlanWithCustomer {
	
	@SerializedName("bus_id")
	@Expose
	private Integer busId;
	@Expose
	private String from;
	@Expose
	private String to;
	@SerializedName("bus_no")
	@Expose
	private String busNo;
	@Expose
	private Integer row;
	@Expose
	private Integer column;
	@Expose
	private String classes;
	@SerializedName("seat_list")
	@Expose
	private List<SeatwithCustomer> seatList = new ArrayList<SeatwithCustomer>();
	
	public Integer getBusId() {
	return busId;
	}
	
	public void setBusId(Integer busId) {
	this.busId = busId;
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
	
	public String getBusNo() {
	return busNo;
	}
	
	public void setBusNo(String busNo) {
	this.busNo = busNo;
	}
	
	public Integer getRow() {
	return row;
	}
	
	public void setRow(Integer row) {
	this.row = row;
	}
	
	public Integer getColumn() {
	return column;
	}
	
	public void setColumn(Integer column) {
	this.column = column;
	}
	
	public String getClasses() {
	return classes;
	}
	
	public void setClasses(String classes) {
	this.classes = classes;
	}
	
	public List<SeatwithCustomer> getSeatList() {
	return seatList;
	}
	
	public void setSeatList(List<SeatwithCustomer> seatList) {
	this.seatList = seatList;
	}

}