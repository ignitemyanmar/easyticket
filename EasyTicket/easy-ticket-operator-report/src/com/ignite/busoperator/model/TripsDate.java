package com.ignite.busoperator.model;

public class TripsDate {
	private String ID;
	private String Date;
	private String Totaltickets;
	private String Total;
	
	public TripsDate() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	public TripsDate(String iD, String date, String totaltickets,
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
