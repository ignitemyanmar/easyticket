package com.ignite.busoperator.clientapi;

import retrofit.RestAdapter;

public class NetworkEngine {
	static INetworkEngine instance;
	public static INetworkEngine getInstance() {
		if (instance==null) {
			RestAdapter adapter = new RestAdapter.Builder().setEndpoint("http://192.168.1.101").setErrorHandler(new MyErrorHandler()).build();
			instance = adapter.create(INetworkEngine.class);
		}
		return instance;
	}
}
