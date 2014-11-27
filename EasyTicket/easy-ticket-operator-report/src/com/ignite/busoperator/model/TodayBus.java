package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class TodayBus {
	
	@SerializedName("bus_id")
	@Expose
	private Integer busId;
	@SerializedName("departure_date")
	@Expose
	private String departureDate;
	@SerializedName("from_id")
	@Expose
	private Integer fromId;
	@SerializedName("to_id")
	@Expose
	private Integer toId;
	@SerializedName("from_to")
	@Expose
	private String fromTo;
	@Expose
	private String time;
	@SerializedName("class_id")
	@Expose
	private Integer classId;
	@SerializedName("class_name")
	@Expose
	private String className;
	@SerializedName("local_price")
	@Expose
	private Integer localPrice;
	@SerializedName("foreign_price")
	@Expose
	private Integer foreignPrice;
	@SerializedName("sold_seat")
	@Expose
	private Integer soldSeat;
	@SerializedName("total_amount")
	@Expose
	private Integer totalAmount;
	
	/**
	* 
	* @return
	* The busId
	*/
	public Integer getBusId() {
	return busId;
	}
	
	/**
	* 
	* @param busId
	* The bus_id
	*/
	public void setBusId(Integer busId) {
	this.busId = busId;
	}
	
	/**
	* 
	* @return
	* The departureDate
	*/
	public String getDepartureDate() {
	return departureDate;
	}
	
	/**
	* 
	* @param departureDate
	* The departure_date
	*/
	public void setDepartureDate(String departureDate) {
	this.departureDate = departureDate;
	}
	
	/**
	* 
	* @return
	* The fromId
	*/
	public Integer getFromId() {
	return fromId;
	}
	
	/**
	* 
	* @param fromId
	* The from_id
	*/
	public void setFromId(Integer fromId) {
	this.fromId = fromId;
	}
	
	/**
	* 
	* @return
	* The toId
	*/
	public Integer getToId() {
	return toId;
	}
	
	/**
	* 
	* @param toId
	* The to_id
	*/
	public void setToId(Integer toId) {
	this.toId = toId;
	}
	
	/**
	* 
	* @return
	* The fromTo
	*/
	public String getFromTo() {
	return fromTo;
	}
	
	/**
	* 
	* @param fromTo
	* The from_to
	*/
	public void setFromTo(String fromTo) {
	this.fromTo = fromTo;
	}
	
	/**
	* 
	* @return
	* The time
	*/
	public String getTime() {
	return time;
	}
	
	/**
	* 
	* @param time
	* The time
	*/
	public void setTime(String time) {
	this.time = time;
	}
	
	/**
	* 
	* @return
	* The classId
	*/
	public Integer getClassId() {
	return classId;
	}
	
	/**
	* 
	* @param classId
	* The class_id
	*/
	public void setClassId(Integer classId) {
	this.classId = classId;
	}
	
	/**
	* 
	* @return
	* The className
	*/
	public String getClassName() {
	return className;
	}
	
	/**
	* 
	* @param className
	* The class_name
	*/
	public void setClassName(String className) {
	this.className = className;
	}
	
	/**
	* 
	* @return
	* The localPrice
	*/
	public Integer getLocalPrice() {
	return localPrice;
	}
	
	/**
	* 
	* @param localPrice
	* The local_price
	*/
	public void setLocalPrice(Integer localPrice) {
	this.localPrice = localPrice;
	}
	
	/**
	* 
	* @return
	* The foreignPrice
	*/
	public Integer getForeignPrice() {
	return foreignPrice;
	}
	
	/**
	* 
	* @param foreignPrice
	* The foreign_price
	*/
	public void setForeignPrice(Integer foreignPrice) {
	this.foreignPrice = foreignPrice;
	}
	
	/**
	* 
	* @return
	* The soldSeat
	*/
	public Integer getSoldSeat() {
	return soldSeat;
	}
	
	/**
	* 
	* @param soldSeat
	* The sold_seat
	*/
	public void setSoldSeat(Integer soldSeat) {
	this.soldSeat = soldSeat;
	}
	
	/**
	* 
	* @return
	* The totalAmount
	*/
	public Integer getTotalAmount() {
	return totalAmount;
	}
	
	/**
	* 
	* @param totalAmount
	* The total_amount
	*/
	public void setTotalAmount(Integer totalAmount) {
	this.totalAmount = totalAmount;
	}

}