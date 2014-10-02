package com.ignite.mm.ticketing.sqlite.database.model;
public class ConfirmSeat {
	private Integer busoccurance_id;
	private String seat_no;
	private String name;
	private String nrc_no;
	private String ticket_no;
	
	public ConfirmSeat(Integer busoccurance_id, String seat_no, String name,
			String nrc_no, String ticket_no) {
		super();
		this.busoccurance_id = busoccurance_id;
		this.seat_no = seat_no;
		this.name = name;
		this.nrc_no = nrc_no;
		this.ticket_no = ticket_no;
	}
	
	public Integer getBusoccurance_id() {
		return busoccurance_id;
	}
	public void setBusoccurance_id(Integer busoccurance_id) {
		this.busoccurance_id = busoccurance_id;
	}
	public String getSeat_no() {
		return seat_no;
	}
	public void setSeat_no(String seat_no) {
		this.seat_no = seat_no;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getNrc_no() {
		return nrc_no;
	}
	public void setNrc_no(String nrc_no) {
		this.nrc_no = nrc_no;
	}	
	public String getTicket_no() {
		return ticket_no;
	}

	public void setTicket_no(String ticket_no) {
		this.ticket_no = ticket_no;
	}

	@Override
	public String toString() {
		return "{\"busoccurance_id\":\"" + busoccurance_id
				+ "\", \"seat_no\":\"" + seat_no + "\", \"name\":\"" + name
				+ "\", \"nrc_no\":\"" + nrc_no + "\", \"ticket_no\":\""
				+ ticket_no + "\"}";
	}
	
}
