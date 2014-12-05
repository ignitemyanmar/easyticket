package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class Agent {
	
	@Expose
	private String id;
	@Expose
	private String name;
	@Expose
	private String address;
	@Expose
	private String phone;
	@Expose
	private String commission_id;
	@Expose
	private String commissiontype;
	@Expose
	private String commission;
	
	
	
	public Agent(String id, String name, String address, String phone,
			String commission_id, String commissiontype, String commission) {
		super();
		this.id = id;
		this.name = name;
		this.address = address;
		this.phone = phone;
		this.commission_id = commission_id;
		this.commissiontype = commissiontype;
		this.commission = commission;
	}

	public String getAddress() {
	return address;
	}
	
	public String getCommission() {
	return commission;
	}
	
	public String getCommission_id() {
	return commission_id;
	}
	
	public String getCommissiontype() {
	return commissiontype;
	}
	
	public String getId() {
	return id;
	}
	
	public String getName() {
	return name;
	}
	
	public String getPhone() {
	return phone;
	}
	
	public void setAddress(String address) {
	this.address = address;
	}
	
	public void setCommission(String commission) {
	this.commission = commission;
	}
	
	public void setCommission_id(String commission_id) {
	this.commission_id = commission_id;
	}
	
	public void setCommissiontype(String commissiontype) {
	this.commissiontype = commissiontype;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public void setName(String name) {
	this.name = name;
	}
	
	public void setPhone(String phone) {
	this.phone = phone;
	}
}