package com.ignite.mm.ticketing.clientapi;

import retrofit.RestAdapter;

public class NetworkEngine {
	static INetworkEngine instance;
	public static INetworkEngine getInstance() {
		if (instance==null) {
			RestAdapter adapter = new RestAdapter.Builder().setEndpoint("http://192.168.1.125").build();
			instance = adapter.create(INetworkEngine.class);
		}
		return instance;
	}
}
