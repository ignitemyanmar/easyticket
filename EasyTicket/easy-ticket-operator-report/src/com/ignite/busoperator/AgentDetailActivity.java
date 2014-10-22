package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.ignite.application.CommissionDialog;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.Agents;
import com.ignite.busoperator.model.Trip;
import com.ignite.busoperator.model.Trips;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class AgentDetailActivity extends BaseSherlockActivity {
	private Button btn_init_credit;
	private Button btn_deposit;
	private Button btn_pay_credit;
	private Button btn_pay_history;
	private String agentString;
	private Agents agent;
	private Button btn_commission;
	private ProgressDialog dialog;
	protected List<Trip> tripsList = new ArrayList<Trip>();
	private Button btn_commission_list;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_agent_detail);
		
		Bundle bundle = getIntent().getExtras();
		agentString = bundle.getString("agent");
		
		agent = new Gson().fromJson(agentString, Agents.class);
		
		getSupportActionBar().setTitle(agent.getName().toString());
		
		btn_init_credit = (Button) findViewById(R.id.btn_init_credit);
		btn_commission = (Button) findViewById(R.id.btn_commission);
		btn_commission_list = (Button) findViewById(R.id.btn_commission_list);
		btn_deposit = (Button) findViewById(R.id.btn_deposit_amount);
		btn_pay_credit = (Button) findViewById(R.id.btn_pay_credit);
		btn_pay_history = (Button) findViewById(R.id.btn_pay_history);
		
		btn_init_credit.setOnClickListener(clickListener);
		btn_commission.setOnClickListener(clickListener);
		btn_commission_list.setOnClickListener(clickListener);
		btn_deposit.setOnClickListener(clickListener);
		btn_pay_credit.setOnClickListener(clickListener);
		btn_pay_history.setOnClickListener(clickListener);
		
		if(SKConnectionDetector.getInstance(this).isConnectingToInternet()){
			getTrip();
		}
		
	}
	
	private void getTrip(){
		NetworkEngine.getInstance().getTrip(AppLoginUser.getAccessToken(), AppLoginUser.getUserID(),"trip", new Callback<Trips>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}

			public void success(Trips arg0, Response arg1) {
				// TODO Auto-generated method stub
				tripsList  = arg0.getTrips();
			}
		});
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_init_credit){
				startActivity(new Intent(getApplicationContext(), AgentInitCreditActivity.class).putExtra("agent", new Gson().toJson(agent)));
			}
			
			if(v == btn_deposit){
				startActivity(new Intent(getApplicationContext(), AgentDepositActivity.class).putExtra("agent", new Gson().toJson(agent)));
			}
			
			if(v == btn_pay_credit){
				Bundle bundle = new Bundle();
				bundle.putString("agent_id", agent.getId().toString());
				bundle.putString("agent_name", agent.getName().toString());
				bundle.putString("deposit", agent.getDepositBalance().toString());
				startActivity(new Intent(getApplicationContext(), CreditListActivity.class).putExtras(bundle));
			}
			
			if(v == btn_pay_history){
				Bundle bundle = new Bundle();
				bundle.putString("agent_id", agent.getId().toString());
				bundle.putString("agent_name", agent.getName().toString());
				startActivity(new Intent(getApplicationContext(), PayHistoryActivity.class).putExtras(bundle));
			}
			
			if(v == btn_commission_list){
				Bundle bundle = new Bundle();
				bundle.putString("agent_id", agent.getId().toString());
				startActivity(new Intent(getApplicationContext(), AgentCommissionActivity.class).putExtras(bundle));
			}
			
			if(v == btn_commission){
				CommissionDialog commissionDialog = new CommissionDialog(AgentDetailActivity.this);
				commissionDialog.setTrips(tripsList);
				commissionDialog.setCallbackListener(new CommissionDialog.Callback() {
					
					public void onSave(String tripId, String commissionType, String commissionAmount) {
						// TODO Auto-generated method stub
						dialog = ProgressDialog.show(AgentDetailActivity.this, "", " Please wait...", true);
				        dialog.setCancelable(true);
				        Log.i("","Hello Param: "+tripId+", "+commissionType+", "+commissionAmount);
						NetworkEngine.getInstance().postAgentCommission(AppLoginUser.getAccessToken(),agent.getId().toString().toString(), tripId,commissionType, commissionAmount, new Callback<JsonObject>() {
							
							public void success(JsonObject arg0, Response arg1) {
								// TODO Auto-generated method stub
								dialog.dismiss();
								finish();
								SKToastMessage.showMessage(AgentDetailActivity.this, "Successfully save record.", SKToastMessage.SUCCESS);
							}
							
							public void failure(RetrofitError arg0) {
								// TODO Auto-generated method stub
								Log.i("","Hello "+ arg0.getCause());
								SKToastMessage.showMessage(AgentDetailActivity.this, "Can't save record.", SKToastMessage.ERROR);
								dialog.dismiss();
							}
						});
					}
					
					public void onCancel() {
						// TODO Auto-generated method stub
						
					}
				});
				commissionDialog.show();
			}
		}
	};
}
