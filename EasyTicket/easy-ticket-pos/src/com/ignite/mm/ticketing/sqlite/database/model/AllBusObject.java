package com.ignite.mm.ticketing.sqlite.database.model;

public class AllBusObject {

	private String Trip;
	private String Date;
	private String OperatorID;
	private String OperatorName;
	private String Time;
	private String SeatID;
	private String SeatNo;
	private String Price;
	private String Status;
	public AllBusObject(String trip, String date, String operatorID,
			String operatorName, String time, String seatID, String seatNo,
			String price, String status) {
		super();
		Trip = trip;
		Date = date;
		OperatorID = operatorID;
		OperatorName = operatorName;
		Time = time;
		SeatID = seatID;
		SeatNo = seatNo;
		Price = price;
		Status = status;
	}
	public String getTrip() {
		return Trip;
	}
	public void setTrip(String trip) {
		Trip = trip;
	}
	public String getDate() {
		return Date;
	}
	public void setDate(String date) {
		Date = date;
	}
	public String getOperatorID() {
		return OperatorID;
	}
	public void setOperatorID(String operatorID) {
		OperatorID = operatorID;
	}
	public String getOperatorName() {
		return OperatorName;
	}
	public void setOperatorName(String operatorName) {
		OperatorName = operatorName;
	}
	public String getTime() {
		return Time;
	}
	public void setTime(String time) {
		Time = time;
	}
	public String getSeatID() {
		return SeatID;
	}
	public void setSeatID(String seatID) {
		SeatID = seatID;
	}
	public String getSeatNo() {
		return SeatNo;
	}
	public void setSeatNo(String seatNo) {
		SeatNo = seatNo;
	}
	public String getPrice() {
		return Price;
	}
	public void setPrice(String price) {
		Price = price;
	}
	public String getStatus() {
		return Status;
	}
	public void setStatus(String status) {
		Status = status;
	}
	@Override
	public String toString() {
		return "AllBusObject [Trip=" + Trip + ", Date=" + Date
				+ ", OperatorID=" + OperatorID + ", OperatorName="
				+ OperatorName + ", Time=" + Time + ", SeatID=" + SeatID
				+ ", SeatNo=" + SeatNo + ", Price=" + Price + ", Status="
				+ Status + "]";
	}
	
	

}
