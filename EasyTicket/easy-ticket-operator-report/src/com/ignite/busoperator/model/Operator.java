package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class Operator {

	@Expose
	private String id;
	@Expose
	private String name;
	
	public Operator(String id, String name) {
		super();
		this.id = id;
		this.name = name;
	}

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

}