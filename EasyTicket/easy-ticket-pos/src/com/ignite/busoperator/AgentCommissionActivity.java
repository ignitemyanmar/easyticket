package com.ignite.busoperator;

import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.ProgressDialog;
import android.os.Bundle;
import android.widget.ListView;
import android.widget.TextView;

import com.ignite.busoperator.adapter.AgentCommissionListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.AgentCommission;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class AgentCommissionActivity extends BaseSherlockActivity {
	private TextView txt_trip;
	private TextView txt_commission;
	private TextView txt_commission_name;
	private ListView lst_agent_commission;
	private ProgressDialog dialog;
	private String AgentID;
	private TextView txt_time;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_agent_trips_commission);
		Bundle bundle = getIntent().getExtras();
		AgentID = bundle.getString("agent_id");
		txt_trip = (TextView) findViewById(R.id.txt_trip);
		txt_time = (TextView) findViewById(R.id.txt_time);
		txt_commission_name = (TextView) findViewById(R.id.txt_commission_name);
		txt_commission = (TextView) findViewById(R.id.txt_commission);
		
		txt_trip.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		txt_time.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		txt_commission_name.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		txt_commission.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		
		lst_agent_commission = (ListView) findViewById(R.id.lst_agent_commission);
		
		if(SKConnectionDetector.getInstance(this).isConnectingToInternet()){
			getAgentCommission();
		}
		
	}
	
	private void getAgentCommission(){
		dialog = new ProgressDialog(this);
		dialog.setMessage("Loading...");
		dialog.show();
		
		NetworkEngine.getInstance().getAgentCommission(AppLoginUser.getAccessToken(), AgentID, new Callback<List<AgentCommission>>() {
			
			public void success(List<AgentCommission> arg0, Response arg1) {
				// TODO Auto-generated method stub
				lst_agent_commission.setAdapter(new AgentCommissionListViewAdapter(AgentCommissionActivity.this, arg0));
				dialog.dismiss();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
	}
}
