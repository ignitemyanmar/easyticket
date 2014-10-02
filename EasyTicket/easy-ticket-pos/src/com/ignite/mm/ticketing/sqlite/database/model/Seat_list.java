package com.ignite.mm.ticketing.sqlite.database.model;

import com.google.gson.annotations.Expose;

public class Seat_list {
	
	@Expose
	private Integer id;
	@Expose
	private String seat_no;
	@Expose
	private Integer status;
	
	public Seat_list(Integer id, String seat_no, Integer status) {
		super();
		this.id = id;
		this.seat_no = seat_no;
		this.status = status;
	}

	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public String getSeat_no() {
	return seat_no;
	}
	
	public void setSeat_no(String seat_no) {
	this.seat_no = seat_no;
	}
	
	public Integer getStatus() {
	return status;
	}
	
	public void setStatus(Integer status) {
	this.status = status;
	}

}