package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class PayHistory {
	
	@Expose
	private Integer id;
	@SerializedName("agent_id")
	@Expose
	private Integer agentId;
	@SerializedName("operator_id")
	@Expose
	private Integer operatorId;
	@SerializedName("deposit_date")
	@Expose
	private String depositDate;
	@Expose
	private Integer deposit;
	@SerializedName("total_ticket_amt")
	@Expose
	private Integer totalTicketAmt;
	@Expose
	private Integer payment;
	@SerializedName("pay_date")
	@Expose
	private String payDate;
	@Expose
	private Integer balance;
	@Expose
	private Integer debit;
	@Expose
	private String agent;
	
	public Integer getId() {
	return id;
	}
	
	public void setId(Integer id) {
	this.id = id;
	}
	
	public Integer getAgentId() {
	return agentId;
	}
	
	public void setAgentId(Integer agentId) {
	this.agentId = agentId;
	}
	
	public Integer getOperatorId() {
	return operatorId;
	}
	
	public void setOperatorId(Integer operatorId) {
	this.operatorId = operatorId;
	}
	
	public String getDepositDate() {
	return depositDate;
	}
	
	public void setDepositDate(String depositDate) {
	this.depositDate = depositDate;
	}
	
	public Integer getDeposit() {
	return deposit;
	}
	
	public void setDeposit(Integer deposit) {
	this.deposit = deposit;
	}
	
	public Integer getTotalTicketAmt() {
	return totalTicketAmt;
	}
	
	public void setTotalTicketAmt(Integer totalTicketAmt) {
	this.totalTicketAmt = totalTicketAmt;
	}
	
	public Integer getPayment() {
	return payment;
	}
	
	public void setPayment(Integer payment) {
	this.payment = payment;
	}
	
	public String getPayDate() {
	return payDate;
	}
	
	public void setPayDate(String payDate) {
	this.payDate = payDate;
	}
	
	public Integer getBalance() {
	return balance;
	}
	
	public void setBalance(Integer balance) {
	this.balance = balance;
	}
	
	public Integer getDebit() {
	return debit;
	}
	
	public void setDebit(Integer debit) {
	this.debit = debit;
	}
	
	public String getAgent() {
	return agent;
	}
	
	public void setAgent(String agent) {
	this.agent = agent;
	}

}