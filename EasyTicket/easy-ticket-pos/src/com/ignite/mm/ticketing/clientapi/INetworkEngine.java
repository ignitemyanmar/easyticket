package com.ignite.mm.ticketing.clientapi;

import java.util.List;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;
import retrofit.http.Query;

import com.google.gson.JsonObject;
import com.ignite.mm.ticketing.sqlite.database.model.AccessToken;
import com.ignite.mm.ticketing.sqlite.database.model.AgentList;
import com.ignite.mm.ticketing.sqlite.database.model.BusSeat;
import com.ignite.mm.ticketing.sqlite.database.model.Cities;
import com.ignite.mm.ticketing.sqlite.database.model.CityList;
import com.ignite.mm.ticketing.sqlite.database.model.CreditOrder;
import com.ignite.mm.ticketing.sqlite.database.model.ExtraCity;
import com.ignite.mm.ticketing.sqlite.database.model.OperatorGroupUser;
import com.ignite.mm.ticketing.sqlite.database.model.Operators;
import com.ignite.mm.ticketing.sqlite.database.model.ReturnComfrim;
import com.ignite.mm.ticketing.sqlite.database.model.Time;
import com.ignite.mm.ticketing.sqlite.database.model.TimesbyOperator;
import com.ignite.mm.ticketing.sqlite.database.model.TripsCollection;

public interface INetworkEngine {
	
	@FormUrlEncoded
	@POST("/oauth/access_token")
	void getAccessToken(@Field("grant_type") String grant_type,
			@Field("client_id") String client_id,
			@Field("client_secret") String client_secret,
			@Field("username") String username,
			@Field("password") String password, 
			@Field("scope") String scope,
			@Field("state") String state, Callback<AccessToken> callback);
	
	@GET("/seatplan")
	void getItems(@Query("access_token") String access_token,
			@Query("operator_id") String operator_id,
			@Query("from_city") String from_city,
			@Query("to_city") String to_city, 
			@Query("class_id") String class_id,
			@Query("date") String date,
			@Query("time") String time, Callback<List<BusSeat>> callback);
	
	@FormUrlEncoded
	@POST("/sale")
	void postSaleOrder(@Field("access_token") String access_token,
			@Field("operator_id") String operator_id,
			@Field("agent_id") String agent_id,
			@Field("from_city") String from_city,
			@Field("to_city") String to_city, 
			@Field("seat_list") String seat_list, Callback<ReturnComfrim> callback);
	
	@GET("/city")
	void getAllCity(@Query("access_token") String access_token, Callback<CityList> callback);
	
	@GET("/trips")
	void getTrips(@Query("access_token") String access_token, @Query("operator_id") String operator_id, Callback<List<TripsCollection>> callback);
	
	@GET("/time")
	void getAllTime(@Query("access_token") String access_token,@Query("operator_id") String operator_id,@Query("from_city") String from_city,@Query("to_city") String to_city,@Query("trip_date") String trip_date, Callback<List<Time>> callback);
			 
	@GET("/operator")
	void getAllOperators(@Query("access_token")String access_token, Callback<Operators> callback);
	
	@FormUrlEncoded
	@POST("/sale/{id}/delete")
	void deleteSaleOrder( @Field("access_token")String access_token, @Path("id") String id,Callback<JSONObject> callback);
	
	@GET("/agent")
	void getAllAgent(@Query("access_token")String access_token, @Query("operator_id")String operator_id, Callback<AgentList> callback);
	
	@GET("/sale/order")
	void getBookingOrder(@Query("access_token")String access_token, @Query("operator_id") String operator_id, @Query("departure_date") String departure_date, @Query("from") String from, @Query("to") String to, @Query("time") String time,@Query("book_code") String book_code, Callback<List<CreditOrder>> callback);
	
	@FormUrlEncoded
	@POST("/sale/credit/delete/{id}")
	void deleteAllOrder(@Field("access_token")String access_token, @Path("id") String id,Callback<JSONObject> callback);

	@FormUrlEncoded
	@POST("/sale/order/confirm/{id}")
	void confirmBooking(@Field("access_token")String access_token, @Path("id") String id,Callback<JSONObject> callback);
	
	@FormUrlEncoded
	@POST("/sale/credit/cancelticket")
	void deleteOrderItem(@Field("access_token")String access_token, @Field("saleitem_id_list") String sale_item,Callback<JSONObject> callback);

	@GET("/operatorgroup")
	void getOperatorGroupUser(@Query("access_token")String access_token,@Query("operator_id")String operator_id, Callback<List<OperatorGroupUser>> callback);
	
	@GET("/extra_destination/{id}")
	void getExtraDestination(@Query("access_token")String access_token ,@Path("id")String id, Callback<List<ExtraCity>> callback);

	@GET("/booking/notify")
	void getNotiBooking(@Query("access_token")String access_token, @Query("date")String date, Callback<Integer> callback);
	
	@GET("/citiesbyoperator")
	void getCitybyOperator(@Query("access_token") String access_token, @Query("operator_id") String operator_id,Callback<Cities> callback);
	
	@GET("/timesbyoperator")
	void getTimebyOperator(@Query("access_token") String access_token, @Query ("operator_id") String operator_id, Callback<List<TimesbyOperator>> callback);
	
	@FormUrlEncoded
	@POST("/report/customer/update")
	void editSeatInfo(@Field("access_token")String access_token, @Field("bus_id") String bus_id, @Field("seat_no") String seat_no, @Field("customer_name") String customer_name, @Field("phone") String phone, @Field("nrc_no") String nrc_no, @Field("ticket_no") String ticket_no, Callback<JsonObject> callback);
	
	@FormUrlEncoded
	@POST("/ticket_delete")
	void deleteTicket(@Field("access_token")String access_token, @Field("bus_id") String bus_id, @Field("seat_no") String seat_no, @Field("user_id") String user_id, Callback<JsonObject> callback);

}
