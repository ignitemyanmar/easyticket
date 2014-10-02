package com.ignite.busoperator.model;

	public class To {
	
	private String id;
	private String name;
	
	
	public To(String id, String name) {
		super();
		this.id = id;
		this.name = name;
	}

	@Override
	public String toString() {
		return "{\"id\":" + id + ", \"name\":" + name + "}";
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
