package com.ignite.busoperator.model;

public class OperReportByBusNo {
	private String ID;
	private String Busno;
	private String Tickets;
	private String Total;
	
	public OperReportByBusNo() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public OperReportByBusNo(String iD, String busno, String tickets, String total) {
		super();
		ID = iD;
		Busno = busno;
		Tickets = tickets;
		Total = total;
	}

	public String getID() {
		return ID;
	}

	public void setID(String iD) {
		ID = iD;
	}

	public String getBusno() {
		return Busno;
	}

	public void setBusno(String busno) {
		Busno = busno;
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
