package com.ignite.mm.ticketing.clientapi;

import retrofit.RequestInterceptor;
import retrofit.RestAdapter;

public class NetworkEngine {
	static INetworkEngine instance;
	public static RequestInterceptor requestInterceptor = new RequestInterceptor() {
	  public void intercept(RequestFacade request) {
	    request.addHeader("Accept-Encoding", "gzip, deflate, sdch");
	  }
	};

	public static INetworkEngine getInstance() {
		if (instance==null) {
			RestAdapter adapter = new RestAdapter.Builder()
					.setEndpoint("http://192.168.1.241")
					//.setRequestInterceptor(requestInterceptor)
					.setLogLevel(RestAdapter.LogLevel.FULL)
					.setErrorHandler(new MyErrorHandler()).build();
			instance = adapter.create(INetworkEngine.class);
		}
		return instance;
	}
}
