package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class User {
	
	@Expose
	private String id;
	@Expose
	private String name;
	
	@Expose
	private String type;
	
	@Expose
	private Integer role;
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getName() {
	return name;
	}
	
	public void setName(String name) {
	this.name = name;
	}

	public String getType() {
		return type;
	}

	public void setType(String type) {
		this.type = type;
	}

	public Integer getRole() {
		return role;
	}

	public void setRole(Integer role) {
		this.role = role;
	}
	
}