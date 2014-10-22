package com.ignite.mm.ticketing.clientapi;

import java.util.List;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.http.Field;
import retrofit.http.FieldMap;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.POST;
import retrofit.http.Path;
import retrofit.http.Query;

import com.ignite.mm.ticketing.sqlite.database.model.AccessToken;
import com.ignite.mm.ticketing.sqlite.database.model.AgentList;
import com.ignite.mm.ticketing.sqlite.database.model.BusSeat;
import com.ignite.mm.ticketing.sqlite.database.model.CityList;
import com.ignite.mm.ticketing.sqlite.database.model.CreditOrder;
import com.ignite.mm.ticketing.sqlite.database.model.OperatorGroupUser;
import com.ignite.mm.ticketing.sqlite.database.model.Operators;
import com.ignite.mm.ticketing.sqlite.database.model.ReturnComfrim;
import com.ignite.mm.ticketing.sqlite.database.model.SelectSeat;
import com.ignite.mm.ticketing.sqlite.database.model.Time;
import com.ignite.mm.ticketing.sqlite.database.model.TripsCollection;

public interface INetworkEngine {

	@POST("/oauth/access_token")
	void getAccessToken(@Query("grant_type") String grant_type,
			@Query("client_id") String client_id,
			@Query("client_secret") String client_secret,
			@Query("username") String username,
			@Query("password") String password, 
			@Query("scope") String scope,
			@Query("state") String state, Callback<AccessToken> callback);
	
	@GET("/seatplan")
	void getItems(@Query("access_token") String access_token,
			@Query("operator_id") String operator_id,
			@Query("from_city") String from_city,
			@Query("to_city") String to_city, 
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
	
	@GET("/sale/credit")
	void getCreditOrder(@Query("access_token")String access_token, @Query("operator_id") String operator_id, @Query("agent_id") String agent_id, Callback<List<CreditOrder>> callback);
	
	@FormUrlEncoded
	@POST("/sale/credit/delete/{id}")
	void deleteAllOrder(@Field("access_token")String access_token, @Path("id") String id,Callback<JSONObject> callback);

	@FormUrlEncoded
	@POST("/sale/credit/pay/{id}")
	void payCredit(@Field("access_token")String access_token, @Path("id") String id,Callback<JSONObject> callback);
	
	@FormUrlEncoded
	@POST("/sale/credit/cancelticket")
	void deleteOrderItem(@Field("access_token")String access_token, @Field("saleitem_id_list") String sale_item,Callback<JSONObject> callback);

	@GET("/operatorgroup")
	void getOperatorGroupUser(@Query("access_token")String access_token,@Query("operator_id")String operator_id, Callback<List<OperatorGroupUser>> callback);
}
