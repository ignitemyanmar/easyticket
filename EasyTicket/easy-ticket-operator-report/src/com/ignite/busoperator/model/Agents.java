package com.ignite.busoperator.model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Agents {
	
	@Expose
	private Integer id;
	@SerializedName("agentgroup_id")
	@Expose
	private Integer agentgroupId;
	@Expose
	private String name;
	@Expose
	private String phone;
	@Expose
	private String address;
	@SerializedName("commission_id")
	@Expose
	private String commissionId;
	@Expose
	private String commission;
	@SerializedName("user_id")
	@Expose
	private String userId;
	@SerializedName("old_credit")
	@Expose
	private String oldCredit;
	@SerializedName("operator_id")
	@Expose
	private String operatorId;
	@Expose
	private String credit;
	@SerializedName("agent_commission")
	@Expose
	private String agentCommission;
	@SerializedName("to_pay_credit")
	@Expose
	private String toPayCredit;
	@SerializedName("deposit_balance")
	@Expose
	private String depositBalance;
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public Integer getAgentgroupId() {
		return agentgroupId;
	}
	public void setAgentgroupId(Integer agentgroupId) {
		this.agentgroupId = agentgroupId;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getPhone() {
		return phone;
	}
	public void setPhone(String phone) {
		this.phone = phone;
	}
	public String getAddress() {
		return address;
	}
	public void setAddress(String address) {
		this.address = address;
	}
	public String getCommissionId() {
		return commissionId;
	}
	public void setCommissionId(String commissionId) {
		this.commissionId = commissionId;
	}
	public String getCommission() {
		return commission;
	}
	public void setCommission(String commission) {
		this.commission = commission;
	}
	public String getUserId() {
		return userId;
	}
	public void setUserId(String userId) {
		this.userId = userId;
	}
	public String getOldCredit() {
		return oldCredit;
	}
	public void setOldCredit(String oldCredit) {
		this.oldCredit = oldCredit;
	}
	public String getOperatorId() {
		return operatorId;
	}
	public void setOperatorId(String operatorId) {
		this.operatorId = operatorId;
	}
	public String getCredit() {
		return credit;
	}
	public void setCredit(String credit) {
		this.credit = credit;
	}
	public String getAgentCommission() {
		return agentCommission;
	}
	public void setAgentCommission(String agentCommission) {
		this.agentCommission = agentCommission;
	}
	public String getToPayCredit() {
		return toPayCredit;
	}
	public void setToPayCredit(String toPayCredit) {
		this.toPayCredit = toPayCredit;
	}
	public String getDepositBalance() {
		return depositBalance;
	}
	public void setDepositBalance(String depositBalance) {
		this.depositBalance = depositBalance;
	}
	
	@Override
	public String toString() {
		return name;
	}
	
}