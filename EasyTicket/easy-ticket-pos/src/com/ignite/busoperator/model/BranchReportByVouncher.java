package com.ignite.busoperator.model;

public class BranchReportByVouncher {
	private String ID;
	private String Vouncherno;
	private String Tickets;
	private String Total;
	
	public BranchReportByVouncher() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public BranchReportByVouncher(String iD, String vouncher_no, String tickets, String total) {
		super();
		ID = iD;
		Vouncherno = vouncher_no;
		Tickets = tickets;
		Total = total;
	}

	public String getID() {
		return ID;
	}

	public void setID(String iD) {
		ID = iD;
	}
	
	public String getVouncherno() {
		return Vouncherno;
	}

	public void setVouncherno(String vouncherno) {
		Vouncherno = vouncherno;
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
