package com.ignite.busoperator.model;

public class BranchDate {
	private String ID;
	private String Date;
	private String Totaltickets;
	private String Total;
	
	public BranchDate() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public BranchDate(String iD, String date, String totaltickets,
			String total) {
		super();
		setID(iD);
		setDate(date);
		setTotaltickets(totaltickets);
		setTotal(total);
	}

	public String getID() {
		return ID;
	}

	public void setID(String iD) {
		ID = iD;
	}

	public String getDate() {
		return Date;
	}

	public void setDate(String date) {
		Date = date;
	}

	public String getTotaltickets() {
		return Totaltickets;
	}

	public void setTotaltickets(String totaltickets) {
		Totaltickets = totaltickets;
	}

	public String getTotal() {
		return Total;
	}

	public void setTotal(String total) {
		Total = total;
	}
	
	
	


}
