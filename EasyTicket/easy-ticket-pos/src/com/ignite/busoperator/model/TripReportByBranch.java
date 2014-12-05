package com.ignite.busoperator.model;

public class TripReportByBranch {
	private String ID;
	private String BranchName;
	private String Tickets;
	private String Total;
	
	public TripReportByBranch() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public TripReportByBranch(String iD, String branch_name, String tickets, String total) {
		super();
		ID = iD;
		BranchName = branch_name;
		Tickets = tickets;
		Total = total;
	}

	public String getID() {
		return ID;
	}

	public void setID(String iD) {
		ID = iD;
	}
	
	public String getBranchName() {
		return BranchName;
	}

	public void setBranchName(String branchName) {
		BranchName = branchName;
	}

	public String getTickets() {
		return Tickets;
	}

	public void setTickets(String tickets) {
		Tickets = tickets;
	}

	public String getTotal() {
		return Total;
	}

	public void setTotal(String total) {
		Total = total;
	}
}
