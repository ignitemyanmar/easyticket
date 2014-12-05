package com.ignite.busoperator.model;

public class SeatDetails {
	private String ID;
	private String Seat_no;
	private String Buyer_name;
	private String Seller_name;
	private String Price;
	private String Vouncher_no;
	
	public SeatDetails() {
		super();
		// TODO Auto-generated constructor stub
	}

	public SeatDetails(String iD, String seatno, String buyer_name,
			String seller_name, String price, String vouncher_no) {
		super();
		ID = iD;
		Seat_no = seatno;
		Buyer_name = buyer_name;
		Seller_name = seller_name;
		Price = price;
		Vouncher_no = vouncher_no;
	}

	public String getID() {
		return ID;
	}

	public void setID(String iD) {
		ID = iD;
	}

	public String getSeat_no() {
		return Seat_no;
	}

	public void setSeat_no(String seatno) {
		Seat_no = seatno;
	}

	public String getBuyer_name() {
		return Buyer_name;
	}

	public void setBuyer_name(String buyer_name) {
		Buyer_name = buyer_name;
	}

	public String getSeller_name() {
		return Seller_name;
	}

	public void setSeller_name(String seller_name) {
		Seller_name = seller_name;
	}

	public String getPrice() {
		return Price;
	}

	public void setPrice(String price) {
		Price = price;
	}

	public String getVouncher_no() {
		return Vouncher_no;
	}

	public void setVouncher_no(String vouncher_no) {
		Vouncher_no = vouncher_no;
	}
	
	
}
