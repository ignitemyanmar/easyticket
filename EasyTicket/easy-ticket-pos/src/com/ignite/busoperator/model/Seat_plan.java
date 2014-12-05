package com.ignite.busoperator.model;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;

public class Seat_plan {

@Expose
private String id;
@Expose
private String seat_no;
@Expose
private String bus_no;
@Expose
private String row;
@Expose
private String column;
@Expose
private String classes;
@Expose
private List<Seat_list> seat_list = new ArrayList<Seat_list>();
	
	public String getId() {
	return id;
	}
	
	public void setId(String id) {
	this.id = id;
	}
	
	public String getSeat_no() {
	return seat_no;
	}
	
	public void setSeat_no(String seat_no) {
	this.seat_no = seat_no;
	}
	
	public String getBus_no() {
	return bus_no;
	}
	
	public void setBus_no(String bus_no) {
	this.bus_no = bus_no;
	}
	
	public String getRow() {
	return row;
	}
	
	public void setRow(String row) {
	this.row = row;
	}
	
	public String getColumn() {
	return column;
	}
	
	public void setColumn(String column) {
	this.column = column;
	}
	
	public String getClasses() {
	return classes;
	}
	
	public void setClasses(String classes) {
	this.classes = classes;
	}
	
	public List<Seat_list> getSeat_list() {
	return seat_list;
	}
	
	public void setSeat_list(List<Seat_list> seat_list) {
	this.seat_list = seat_list;
	}

}