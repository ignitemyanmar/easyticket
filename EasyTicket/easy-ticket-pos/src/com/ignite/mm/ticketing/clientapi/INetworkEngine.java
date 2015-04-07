package com.ignite.mm.ticketing.clientapi;

import java.util.List;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.client.Response;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.Header;
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
	void getAccessToken(@Field("param") String param, Callback<Response> callback);
	
	@GET("/seatplan")
	void getItems(@Query("param") String param, Callback<Response> callback);

	@GET("/city")
	void getAllCity(@Query("access_token") String access_token, Callback<Response> callback);
	
	@GET("/trips")
	void getTrips(@Query("param") String param, Callback<Response> callback);
	
	@GET("/time")
	void getAllTime(@Query("param") String param, Callback<Response> callback);
			 
	@GET("/operator")
	void getAllOperator(@Query("access_token")String access_token, Callback<Response> callback);
	
	@FormUrlEncoded
	@POST("/sale/{id}/delete")
	void deleteSaleOrder( @Field("param")String param, @Path("id") String id,Callback<Response> callback);
	
	@GET("/agent")
	void getAllAgent(@Query("param")String param, Callback<Response> callback);
	
	@GET("/sale/order")
	void getBookingOrder(@Query("param") String param, Callback<Response> callback);
	
	@FormUrlEncoded
	@POST("/sale/credit/delete/{id}")
	void deleteAllOrder(@Field("param")String param, @Path("id") String id,Callback<Response> callback);

	@FormUrlEncoded
	@POST("/sale/credit/cancelticket")
	void deleteOrderItem(@Field("param")String param, Callback<Response> callback);

	@FormUrlEncoded
	@POST("/report/customer/update")
	void editSeatInfo(@Field("param")String param, Callback<Response> callback);
	
	@FormUrlEncoded
	@POST("/ticket_delete")
	void deleteTicket(@Field("param")String param, Callback<Response> callback);
	
	@GET("/operatorgroup")
	void getOperatorGroupUser(@Query("param") String param, Callback<Response> callback);
	
	@GET("/extra_destination/{id}")
	void getExtraDestination(@Query("param")String param ,@Path("id")String id, Callback<Response> callback);

	@GET("/booking/notify")
	void getNotiBooking(@Query("param")String param, Callback<Response> callback);
	
	@GET("/citiesbyoperator")
	void getCitybyOperator(@Query("param") String param, Callback<Response> callback);
	
	@GET("/timesbyoperator")
	void getTimebyOperator(@Query("param") String param, Callback<Response> callback);
	

}
