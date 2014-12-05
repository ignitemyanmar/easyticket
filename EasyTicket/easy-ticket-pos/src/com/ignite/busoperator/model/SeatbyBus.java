package com.ignite.busoperator.model;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SeatbyBus {
	
	@SerializedName("vr_no")
	@Expose
	private String vrNo;
	@SerializedName("order_date")
	@Expose
	private String orderDate;
	@SerializedName("departure_date")
	@Expose
	private String departureDate;
	@SerializedName("from_to")
	@Expose
	private String fromTo;
	@Expose
	private String time;
	@Expose
	private String classes;
	@SerializedName("agent_name")
	@Expose
	private String agentName;
	@Expose
	private Integer commission;
	@SerializedName("seat_no")
	@Expose
	private String seatNo;
	@SerializedName("sold_seat")
	@Expose
	private Integer soldSeat;
	@Expose
	private Integer price;
	@SerializedName("total_amount")
	@Expose
	private Integer totalAmount;
	
	/**
	* 
	* @return
	* The vrNo
	*/
	public String getVrNo() {
	return vrNo;
	}
	
	/**
	* 
	* @param vrNo
	* The vr_no
	*/
	public void setVrNo(String vrNo) {
	this.vrNo = vrNo;
	}
	
	/**
	* 
	* @return
	* The orderDate
	*/
	public String getOrderDate() {
	return orderDate;
	}
	
	/**
	* 
	* @param orderDate
	* The order_date
	*/
	public void setOrderDate(String orderDate) {
	this.orderDate = orderDate;
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
	* The classes
	*/
	public String getClasses() {
	return classes;
	}
	
	/**
	* 
	* @param classes
	* The classes
	*/
	public void setClasses(String classes) {
	this.classes = classes;
	}
	
	/**
	* 
	* @return
	* The agentName
	*/
	public String getAgentName() {
	return agentName;
	}
	
	/**
	* 
	* @param agentName
	* The agent_name
	*/
	public void setAgentName(String agentName) {
	this.agentName = agentName;
	}
	
	/**
	* 
	* @return
	* The commission
	*/
	public Integer getCommission() {
	return commission;
	}
	
	/**
	* 
	* @param commission
	* The commission
	*/
	public void setCommission(Integer commission) {
	this.commission = commission;
	}
	
	/**
	* 
	* @return
	* The seatNo
	*/
	public String getSeatNo() {
	return seatNo;
	}
	
	/**
	* 
	* @param seatNo
	* The seat_no
	*/
	public void setSeatNo(String seatNo) {
	this.seatNo = seatNo;
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
	* The price
	*/
	public Integer getPrice() {
	return price;
	}
	
	/**
	* 
	* @param price
	* The price
	*/
	public void setPrice(Integer price) {
	this.price = price;
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