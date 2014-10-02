package com.ignite.mm.ticketing;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;

import com.actionbarsherlock.app.ActionBar;
import com.actionbarsherlock.app.SherlockActivity;
import com.google.gson.Gson;
import com.ignite.mm.ticketing.application.BaseSherlockActivity;
import com.ignite.mm.ticketing.clientapi.NetworkEngine;
import com.ignite.mm.ticketing.custom.listview.adapter.CreditListViewAdapter;
import com.ignite.mm.ticketing.sqlite.database.model.CreditOrder;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class BusTicketingCreditListActivity extends BaseSherlockActivity {
	private ListView lst_credit;
	private List<CreditOrder> credit_list;
	private ActionBar actionBar;
	private TextView actionBarTitle;
	private ImageButton actionBarBack;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.action_bar);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.action_bar_title);
		actionBarBack = (ImageButton) actionBar.getCustomView().findViewById(
				R.id.action_bar_back);
		actionBarBack.setOnClickListener(clickListener);
		actionBarTitle.setText("Easy Ticket");
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
		
		setContentView(R.layout.activity_busticketing_credit);
		lst_credit = (ListView) findViewById(R.id.lst_credit);
		
		credit_list = new ArrayList<CreditOrder>();
	
		lst_credit.setOnItemClickListener(itemClickListener);
		
	}
	
	@Override
	protected void onResume() {
		// TODO Auto-generated method stub
		super.onResume();
		SKConnectionDetector connectionDetector = SKConnectionDetector.getInstance(this);
		if(connectionDetector.isConnectingToInternet()){
			getCreditList();
		}else{
			connectionDetector.showErrorMessage();
		}
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == actionBarBack){
				finish();
			}
		}
	};
	
	
	private OnItemClickListener itemClickListener = new OnItemClickListener() {

		public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			Intent intent = new Intent(getApplicationContext(), PayDeleteActivity.class);
			intent.putExtra("credit_order", new Gson().toJson(credit_list.get(arg2)));
			startActivity(intent);
			
		}
	};
	private String operatorId;
	private String agentId;
	private ProgressDialog dialog;
	
	private void getCreditList(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String userType = pref.getString("user_type", null);
		String userId = pref.getString("user_id", null);
		if(userType.equals("operator")){
			operatorId = userId;
			agentId = "0";
		}else{
			operatorId = "0";
			agentId = userId;
		}
		
		NetworkEngine.getInstance().getCreditOrder(accessToken, operatorId, agentId, new Callback<List<CreditOrder>>() {
			
			public void success(List<CreditOrder> arg0, Response arg1) {
				// TODO Auto-generated method stub
				credit_list = arg0;
				lst_credit.setAdapter(new CreditListViewAdapter(BusTicketingCreditListActivity.this, credit_list));
				dialog.dismiss();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
		
	}
	
}
