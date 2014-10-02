package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class Customer {
	
	@Expose
	private Integer id;
	@Expose
	private String name;
	@Expose
	private String nrc;
	@Expose
	private String phone;
	@Expose
	private String seller;
	
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
	
	public String getNrc() {
	return nrc;
	}
	
	public void setNrc(String nrc) {
	this.nrc = nrc;
	}

	public String getPhone() {
		return phone;
	}

	public void setPhone(String phone) {
		this.phone = phone;
	}

	public String getSeller() {
		return seller;
	}

	public void setSeller(String seller) {
		this.seller = seller;
	}
}