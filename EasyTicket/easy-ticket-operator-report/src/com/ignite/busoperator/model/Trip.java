package com.ignite.busoperator.model;

	public class Trip {
	
	private Integer id;
	private Integer operator_id;
	private Integer from;
	private Integer to;
	private String operator;
	private String from_city;
	private String to_city;
	private Integer class_id;
	private String classes;
	private String available_day;
	private String time;
	private Integer price;
	
	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public Integer getOperator_id() {
	return operator_id;
	}
	
	public void setOperator_id(Integer operator_id) {
	this.operator_id = operator_id;
	}
	
	public Integer getFrom() {
	return from;
	}
	
	public void setFrom(Integer from) {
	this.from = from;
	}
	
	public Integer getTo() {
	return to;
	}
	
	public void setTo(Integer to) {
	this.to = to;
	}
	
	public String getOperator() {
	return operator;
	}
	
	public void setOperator(String operator) {
	this.operator = operator;
	}
	
	public String getFrom_city() {
	return from_city;
	}
	
	public void setFrom_city(String from_city) {
	this.from_city = from_city;
	}
	
	public String getTo_city() {
	return to_city;
	}
	
	public void setTo_city(String to_city) {
	this.to_city = to_city;
	}
	
	public Integer getClass_id() {
	return class_id;
	}
	
	public void setClass_id(Integer class_id) {
	this.class_id = class_id;
	}
	
	public String getClasses() {
	return classes;
	}
	
	public void setClasses(String classes) {
	this.classes = classes;
	}
	
	public String getAvailable_day() {
	return available_day;
	}
	
	public void setAvailable_day(String available_day) {
	this.available_day = available_day;
	}
	
	public String getTime() {
	return time;
	}
	
	public void setTime(String time) {
	this.time = time;
	}
	
	public Integer getPrice() {
	return price;
	}
	
	public void setPrice(Integer price) {
	this.price = price;
	}

	@Override
	public String toString() {
		return from_city+" - "+to_city;
	}

}
