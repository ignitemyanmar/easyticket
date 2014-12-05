package com.ignite.busoperator.model;

public class SaleTicket {
	private String OrderID;
	private String TicketNo;
	private String CustomerName;
	private String SeatNo;
	private String Time;
	private String TripDate;
	private String Trip;
	private Integer Price;
	private Integer Commission;
	private Integer Qty;
	private Integer TotalAmount;
	public SaleTicket(String orderID, String ticketNo, String customerName,
			String seatNo, String time, String tripDate, String trip,
			Integer price, Integer commission, Integer qty, Integer totalAmount) {
		super();
		OrderID = orderID;
		TicketNo = ticketNo;
		CustomerName = customerName;
		SeatNo = seatNo;
		Time = time;
		TripDate = tripDate;
		Trip = trip;
		Price = price;
		Commission = commission;
		Qty = qty;
		TotalAmount = totalAmount;
	}
	public String getOrderID() {
		return OrderID;
	}
	public void setOrderID(String orderID) {
		OrderID = orderID;
	}
	public String getTicketNo() {
		return TicketNo;
	}
	public void setTicketNo(String ticketNo) {
		TicketNo = ticketNo;
	}
	public String getCustomerName() {
		return CustomerName;
	}
	public void setCustomerName(String customerName) {
		CustomerName = customerName;
	}
	public String getSeatNo() {
		return SeatNo;
	}
	public void setSeatNo(String seatNo) {
		SeatNo = seatNo;
	}
	public String getTime() {
		return Time;
	}
	public void setTime(String time) {
		Time = time;
	}
	public String getTripDate() {
		return TripDate;
	}
	public void setTripDate(String tripDate) {
		TripDate = tripDate;
	}
	public String getTrip() {
		return Trip;
	}
	public void setTrip(String trip) {
		Trip = trip;
	}
	public Integer getPrice() {
		return Price;
	}
	public void setPrice(Integer price) {
		Price = price;
	}
	public Integer getCommission() {
		return Commission;
	}
	public void setCommission(Integer commission) {
		Commission = commission;
	}
	public Integer getQty() {
		return Qty;
	}
	public void setQty(Integer qty) {
		Qty = qty;
	}
	public Integer getTotalAmount() {
		return TotalAmount;
	}
	public void setTotalAmount(Integer totalAmount) {
		TotalAmount = totalAmount;
	}
			
}
