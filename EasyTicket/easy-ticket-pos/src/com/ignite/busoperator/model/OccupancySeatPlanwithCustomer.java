
package com.ignite.busoperator.model;

import java.util.ArrayList;
import java.util.List;
import com.google.gson.annotations.Expose;

public class OccupancySeatPlanwithCustomer {
	
	@Expose
	private String operator_id;
	@Expose
	private String operator;
	@Expose
	private List<SeatPlanWithCustomer> seat_plan = new ArrayList<SeatPlanWithCustomer>();
	
	public String getOperator_id() {
	return operator_id;
	}
	
	public void setOperator_id(String operator_id) {
	this.operator_id = operator_id;
	}
	
	public String getOperator() {
	return operator;
	}
	
	public void setOperator(String operator) {
	this.operator = operator;
	}

	public List<SeatPlanWithCustomer> getSeat_plan() {
		return seat_plan;
	}

	public void setSeat_plan(List<SeatPlanWithCustomer> seat_plan) {
		this.seat_plan = seat_plan;
	}

	@Override
	public String toString() {
		return "OccupancySeatPlanwithCustomer [operator_id=" + operator_id
				+ ", operator=" + operator + ", seat_plan=" + seat_plan + "]";
	}
}