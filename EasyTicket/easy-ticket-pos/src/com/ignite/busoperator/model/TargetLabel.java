package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class TargetLabel {

	@Expose
	private Integer id;
	@Expose
	private String name;
	@SerializedName("start_amount")
	@Expose
	private Integer startAmount;
	@SerializedName("end_amount")
	@Expose
	private Integer endAmount;
	@Expose
	private String color;
	
	public TargetLabel(Integer id, String name, Integer startAmount,
			Integer endAmount, String color) {
		super();
		this.id = id;
		this.name = name;
		this.startAmount = startAmount;
		this.endAmount = endAmount;
		this.color = color;
	}

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
	
	public Integer getStartAmount() {
	return startAmount;
	}
	
	public void setStartAmount(Integer startAmount) {
	this.startAmount = startAmount;
	}
	
	public Integer getEndAmount() {
	return endAmount;
	}
	
	public void setEndAmount(Integer endAmount) {
	this.endAmount = endAmount;
	}
	
	public String getColor() {
	return color;
	}
	
	public void setColor(String color) {
	this.color = color;
	}

}