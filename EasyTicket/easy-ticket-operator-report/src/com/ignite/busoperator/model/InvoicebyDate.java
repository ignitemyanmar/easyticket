package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;

public class InvoicebyDate {
	
	@Expose
	private String agent_name;
	@Expose
	private String invoice_no;
	@Expose
	private Integer purchased_total_seat;
	@Expose
	private Integer total_seat;
	@Expose
	private Integer total_amount;
	@Expose
	private String cash_price;
	
	
	
	public InvoicebyDate(String agent_name, String invoice_no,
			Integer purchased_total_seat, Integer total_seat,
			Integer total_amount, String cash_price) {
		super();
		this.agent_name = agent_name;
		this.invoice_no = invoice_no;
		this.purchased_total_seat = purchased_total_seat;
		this.total_seat = total_seat;
		this.total_amount = total_amount;
		this.cash_price = cash_price;
	}

	public String getAgent_name() {
	return agent_name;
	}
	
	public void setAgent_name(String agent_name) {
	this.agent_name = agent_name;
	}
	
	public String getInvoice_no() {
	return invoice_no;
	}
	
	public void setInvoice_no(String invoice_no) {
	this.invoice_no = invoice_no;
	}
	
	public Integer getPurchased_total_seat() {
	return purchased_total_seat;
	}
	
	public void setPurchased_total_seat(Integer purchased_total_seat) {
	this.purchased_total_seat = purchased_total_seat;
	}
	
	public Integer getTotal_seat() {
	return total_seat;
	}
	
	public void setTotal_seat(Integer total_seat) {
	this.total_seat = total_seat;
	}
	
	public Integer getTotal_amount() {
	return total_amount;
	}
	
	public void setTotal_amount(Integer total_amount) {
	this.total_amount = total_amount;
	}
	
	public String getCash_price() {
	return cash_price;
	}
	
	public void setCash_price(String cash_price) {
	this.cash_price = cash_price;
	}

}