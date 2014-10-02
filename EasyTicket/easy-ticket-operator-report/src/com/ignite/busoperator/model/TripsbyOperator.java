package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class TripsbyOperator {
	
	@Expose
	private String order_date;
	@Expose
	private Integer total_seat;
	@Expose
	private Integer purchased_total_seat;
	@Expose
	private Integer total_amout;
	
	
	
	public TripsbyOperator(String order_date, Integer total_seat,
			Integer purchased_total_seat, Integer total_amout) {
		super();
		this.order_date = order_date;
		this.total_seat = total_seat;
		this.purchased_total_seat = purchased_total_seat;
		this.total_amout = total_amout;
	}

	public String getOrder_date() {
	return order_date;
	}
	
	public void setOrder_date(String order_date) {
	this.order_date = order_date;
	}
	
	public Integer getTotal_seat() {
	return total_seat;
	}
	
	public void setTotal_seat(Integer total_seat) {
	this.total_seat = total_seat;
	}
	
	public Integer getPurchased_total_seat() {
	return purchased_total_seat;
	}
	
	public void setPurchased_total_seat(Integer purchased_total_seat) {
	this.purchased_total_seat = purchased_total_seat;
	}
	
	public Integer getTotal_amout() {
	return total_amout;
	}
	
	public void setTotal_amout(Integer total_amout) {
	this.total_amout = total_amout;
	}

}
