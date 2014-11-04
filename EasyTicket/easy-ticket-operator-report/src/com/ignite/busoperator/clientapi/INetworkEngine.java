package com.ignite.busoperator.clientapi;


import java.util.List;

import org.json.JSONObject;

import com.google.gson.JsonObject;
import com.ignite.busoperator.model.AccessToken;
import com.ignite.busoperator.model.AdvanceBus;
import com.ignite.busoperator.model.AdvanceBusbyDate;
import com.ignite.busoperator.model.AgentCommission;
import com.ignite.busoperator.model.AgentReport;
import com.ignite.busoperator.model.Agents;
import com.ignite.busoperator.model.AgentsbyOperator;
import com.ignite.busoperator.model.BusReport;
import com.ignite.busoperator.model.CitiesbyAgent;
import com.ignite.busoperator.model.CreditList;
import com.ignite.busoperator.model.InvoicebyDate;
import com.ignite.busoperator.model.OccupancySeatPlan;
import com.ignite.busoperator.model.OccupancySeatPlanwithCustomer;
import com.ignite.busoperator.model.OperatorsbyAgent;
import com.ignite.busoperator.model.PayHistory;
import com.ignite.busoperator.model.PopularAgent;
import com.ignite.busoperator.model.PopularClasses;
import com.ignite.busoperator.model.PopularTrip;
import com.ignite.busoperator.model.PopularTriptime;
import com.ignite.busoperator.model.SaleDetail;
import com.ignite.busoperator.model.SeatReport;
import com.ignite.busoperator.model.SeatbyBus;
import com.ignite.busoperator.model.SeatsbyTrip;
import com.ignite.busoperator.model.TargetLabel;
import com.ignite.busoperator.model.TimesbyOperator;
import com.ignite.busoperator.model.TodayBus;
import com.ignite.busoperator.model.TodayBusbyTime;
import com.ignite.busoperator.model.Trips;
import com.ignite.busoperator.model.TripsbyOperator;
import com.ignite.busoperator.model.TripsbyDate;

import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;
import retrofit.http.Query;

public interface INetworkEngine {
	
	@GET("/citiesbyoperator")
	void getCitybyOperator(@Query("access_token") String access_token,
			@Query("operator_id") String operator_id,
			Callback<CitiesbyAgent> callback);
	
	@GET("/timesbyoperator")
	void getTimebyOperator(@Query("access_token") String access_token, @Query ("operator_id") String operator_id, Callback<List<TimesbyOperator>> callback);
			 
	@GET("/report/agent/operators")
	void getAllOperators(@Query("access_token")String access_token, @Query ("agent_id") String operator_id, Callback<OperatorsbyAgent> callback);
	
	@POST("/oauth/access_token")
	void getAccessToken(@Query("grant_type") String grant_type,
			@Query("client_id") String client_id,
			@Query("client_secret") String client_secret,
			@Query("username") String username,
			@Query("password") String password, 
			@Query("scope") String scope,
			@Query("state") String state,
			Callback<AccessToken> callback);
	
	@GET("/report/operator/trip")
	void getTripsbyOperator(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id,
			@Query("from") String from,
			@Query("to") String to,
			@Query("departure_time") String departure_time, 
			@Query("start_date") String start_date,
			@Query("end_date") String end_date,
			Callback<List<TripsbyOperator>> callback);
	
	@GET("/report/operator/trip/date")
	void getTripsbyDate(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id,
			@Query("from_city") String from,
			@Query("to_city") String to,
			@Query("time") String time, 
			@Query("date") String date,
			Callback<List<TripsbyDate>> callback);
	
	@GET("/report/operator/seat/trip")
	void getSeatbyTrip(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id,
			@Query("from_city") String from,
			@Query("to_city") String to,
			@Query("time") String time, 
			@Query("date") String date,
			@Query("bus_id") String bus_id,
			Callback<List<SeatsbyTrip>> callback);
	
	@GET("/report/agent/seat/trip")
	void getSeatbyInvoice(@Query("access_token")String access_token,
			@Query("agent_id") String agent_id,
			@Query("from_city") String from,
			@Query("to_city") String to,
			@Query("time") String time, 
			@Query("date") String date,
			@Query("invoice_no") String invoice_no,
			Callback<List<SeatsbyTrip>> callback);
	
	@GET("/report/operator/agents")
	void getAgentbyOperator(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			Callback<AgentsbyOperator> callback);
	
	@GET("/report/operator/trip")
	void getTripsAll(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("from") String from_city,
			@Query("to") String to_city,
			@Query("start_date") String start_date,
			@Query("end_date") String end_date,
			@Query("departure_time") String time, 
			Callback<List<TripsbyOperator>> callback);
	
	@GET("/report/operator/invoice/trip")
	void getInvoicebyDate(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("from_city") String from_city,
			@Query("to_city") String to_city,
			@Query("time") String time,
			@Query("from_date") String from_date,
			Callback<List<InvoicebyDate>> callback);
	
	@GET("/report/tripdate/operator/daily")
	void getBusReport(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("from") String from_city,
			@Query("to") String to_city,
			@Query("time") String time,
			@Query("departure_date") String departure_date,
			Callback<List<BusReport>> callback);
	
	@GET("/report/tripdate/operator/busid")
	void getAgentbyBusID(@Query("access_token")String access_token,
			@Query("bus_id") String bus_id,
			Callback<List<AgentReport>> callback);
	
	@GET("/report/tripdate/operator/detail")
	void getSeatReport(@Query("access_token")String access_token,
			@Query("bus_id") String bus_id,
			@Query("agent_id") String agent_id,
			Callback<List<SeatReport>> callback);
	
	@GET("/report/bus/daily")
	void getTodayBus(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("date") String date,
			Callback<List<TodayBus>> callback);
	
	@GET("/report/bus/daily/time")
	void getTodayBusbyTime(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("date") String date,
			@Query("departure_time") String departure_time,
			Callback<List<TodayBusbyTime>> callback);
	
	@GET("/report/bus/daily/busid")
	void getSeatbyBusID(@Query("access_token")String access_token,
			@Query("bus_id") String bus_id,
			Callback<List<SeatbyBus>> callback);
	
	
	@GET("/report/soldtrips/advance/daily")
	void getAdvanceBus(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("date") String date,
			Callback<List<AdvanceBus>> callback);
	
	@GET("/report/soldtrips/advance/daily/date")
	void getAdvanceBusbyDate(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("order_date") String order_date,
			@Query("departure_date") String departure_date,
			Callback<List<AdvanceBusbyDate>> callback);
	
	@GET("/report/operator/seatplan")
	void getOccupancySeatPlan(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("from_city") String from_city,
			@Query("to_city") String to_city,
			@Query("from_date") String from_date,
			@Query("time") String time,
			Callback<OccupancySeatPlan> callback);
	
	@GET("/report/operator/seat/tripbybus")
	void getOccupancySeatPlanwithCustomer(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("from_city") String from_city,
			@Query("to_city") String to_city,
			@Query("date") String date,
			@Query("time") String time,
			@Query("bus_id") String bus_id,
			Callback<OccupancySeatPlanwithCustomer> callback);
	
	@FormUrlEncoded
	@POST("/report/customer/update")
	void updateCustomer(@Field("access_token")String access_token,
			@Field("bus_id") String bus_id,
			@Field("seat_no") String seat_no,
			@Field("customer_name") String customer_name,
			@Field("nrc_no") String nrc_no,
			Callback<JsonObject> callback);
	
	@GET("/agentlist/operatorid")
	void getAgentList(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id, Callback<List<Agents>> callback);
	
	@FormUrlEncoded
	@POST("/agent/deposit")
	void postDeposit(@Field("access_token")String access_token,
			@Field("operator_id") String operator_id,
			@Field("agent_id") String agent_id,
			@Field("deposit") String deposit, Callback<JSONObject> callback);
	
	@FormUrlEncoded
	@POST("/agent/deposit")
	void postCredit(@Field("access_token")String access_token,
			@Field("operator_id") String operator_id,
			@Field("agent_id") String agent_id,
			@Field("deposit") String deposit, Callback<JSONObject> callback);
	
	@GET("/agents/credits")
	void getCreditList(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id,
			@Query("start_date") String start_date,
			@Query("end_date") String end_date,
			@Query("from") String from,
			@Query("to") String to,
			@Query("time") String time, Callback<List<CreditList>> callback);
	
	@FormUrlEncoded
	@POST("/agents/credits/payment")
	void postPayment(@Field("access_token")String access_token,
			@Field("operator_id") String operator_id,
			@Field("agent_id") String agent_id,
			@Field("order_id") String order_id,
			@Field("payment_amount") String payment_amount , Callback<JSONObject> callback);
	
	@FormUrlEncoded
	@POST("/agent/update/{id}")
	void postCommission(@Field("access_token")String access_token,
			@Path("id") String id,
			@Field("commission_id") String commission_id,
			@Field("commission") String commission, Callback<JSONObject> callback);
	
	
	@FormUrlEncoded
	@POST("/updateorder")
	void changeAgent(@Field("access_token")String access_token,
			@Field("operator_id") String operator_id,
			@Field("agent_id") String agent_id,
			@Field("order_id") String order_id, Callback<JSONObject> callback);
	
	@FormUrlEncoded
	@POST("/deleteorder")
	void deleteOrder(@Field("access_token")String access_token,
			@Field("operator_id") String operator_id,
			@Field("order_id") String order_id, Callback<JSONObject> callback);
	
	@GET("/agents/credits/payment")
	void getPaymentHistory(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id, Callback<List<PayHistory>> callback);
	
	@GET("/agents/credits/payment")
	void deletePayHistory(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id,
			@Query("pay_id") String pay_id, Callback<JSONObject> callback);
	
	@GET("/report/operator/salelist")
	void getReportDetail(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("agent_id") String agent_id,
			@Query("from") String from,
			@Query("to") String to,
			@Query("time") String time,
			@Query("order_s_date") String order_start_date,
			@Query("order_e_date") String order_end_date,
			@Query("credit_cash") String credit_cashed, Callback<List<SaleDetail>> callback);
	
	@GET("/trip")
	void getTrip(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("group_by") String group_by, Callback<Trips> callback);
	
	@FormUrlEncoded
	@POST("/operator/agentcommission")
	void postAgentCommission(@Field("access_token")String access_token,
			@Field("agent_id") String agent_id,
			@Field("trip_id") String trip_id,
			@Field("commission_id") String commission_id,
			@Field("commission") String commission,Callback<JsonObject> callback);
	
	@GET("/operator/agentcommission")
	void getAgentCommission(@Query("access_token")String access_token,
			@Query("agent_id") String agent_id, Callback<List<AgentCommission>> callback);
	
	@GET("/report/popular/agent")
	void getPopularAgent(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("start_date") String start_date, 
			@Query("end_date") String end_date,
			Callback<List<PopularAgent>> callback);
	
	@GET("/report/popular/trip")
	void getPopularTrip(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("start_date") String start_date, 
			@Query("end_date") String end_date,
			@Query("agent_id") String agent_id, Callback<List<PopularTrip>> callback);
	
	@GET("/report/popular/triptime")
	void getPopularTriptime(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("start_date") String start_date, 
			@Query("end_date") String end_date,
			@Query("agent_id") String agent_id,
			@Query("from") String from,
			@Query("to") String to,
			Callback<List<PopularTriptime>> callback);
	
	@GET("/report/analytis/classes")
	void getPopularClasses(@Query("access_token")String access_token,
			@Query("operator_id") String operator_id,
			@Query("start_date") String start_date,
			@Query("end_date") String end_date,
			@Query("agent_id") String agent_id,
			Callback<List<PopularClasses>> callback);
	
	@GET("/targetlabel")
	void getTargetLabel(@Query("access_token")String access_token,
			Callback<List<TargetLabel>> callback);
	
}
