
package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.AgentAdapter;
import com.ignite.busoperator.adapter.AgentReportbyBusIDAdapter;
import com.ignite.busoperator.adapter.OperReportByBusNoAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.AgentReport;
import com.ignite.busoperator.model.OperReportByBusNo;
import com.ignite.busoperator.model.TripsbyOperator;
import com.ignite.busoperator.model.AgentReport;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ListView;
import android.widget.TextView;

public class AgentReportByBusNo extends BaseSherlockActivity{
	private ListView lvOperOne;
	private ProgressDialog dialog;
	private String bus_occ_id;
	private TextView txt_agent;
	private TextView txt_total_ticket;
	private TextView txt_total_amount;
	private TextView txt_total_label;
	private TextView txt_total;
	private TextView txt_trip_date;
	private SKConnectionDetector skDetector;
	private String filter;
	private TextView txt_main_gate_total;
	private TextView txt_child_gate_total;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_agentreport_bybusid);
		
		SharedPreferences pref = getApplicationContext()
				.getSharedPreferences("SearchbyOperator", MODE_PRIVATE);
		
		filter = pref.getString("agent_name", "").length() != 0 ? pref.getString("agent_name", "")+" - " : "";
		filter += pref.getString("from_city_name", "").length() != 0 ? pref.getString("from_city_name", "")+" - " : "";
		filter += pref.getString("to_city_name", "").length() != 0 ? pref.getString("to_city_name", "")+" - " : "";
		filter += pref.getString("from_date", "").length() != 0 ? pref.getString("from_date", "")+" - " : "";
		filter += pref.getString("to_date", "").length() != 0 ? pref.getString("to_date", "")+" - " : "";
		filter += pref.getString("time", "").length() != 0 ? pref.getString("time", "")+" - " : "";
		getSupportActionBar().setTitle(filter);
				
		lvOperOne = (ListView) findViewById(R.id.lvOperOne);
		
		txt_agent = (TextView) findViewById(R.id.txt_agent_name);
		txt_total_ticket = (TextView) findViewById(R.id.txt_total_ticket);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amout);
		txt_total_label = (TextView) findViewById(R.id.txtTotal);
		txt_total =(TextView)findViewById(R.id.txtSum);
		txt_main_gate_total = (TextView) findViewById(R.id.txt_main_gate_total);
		txt_child_gate_total = (TextView) findViewById(R.id.txt_child_gate_total);
		
		txt_agent.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		txt_total_ticket.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		txt_total_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 4;
		
		
		
		
		bus_occ_id = pref.getString("bus_occ_id", null);
		
		skDetector = SKConnectionDetector.getInstance(this);
		if(skDetector.isConnectingToInternet()){
			getReport();
		}else{
			skDetector.showErrorMessage();
		}
	}

	private void getReport() {
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);

		NetworkEngine.getInstance().getAgentbyBusID(accessToken, bus_occ_id, new Callback<List<AgentReport>>() {
			
			private Integer totalAmout = 0;
			private Integer mainTotal  = 0;
			private Integer childTotal = 0;

			public void success(List<AgentReport> arg0, Response arg1) {
				// TODO Auto-generated method stub
				lvOperOne.setAdapter(new AgentReportbyBusIDAdapter(AgentReportByBusNo.this, arg0));
				for(AgentReport report: arg0){
					totalAmout  += report.getTotal_amount();
					if(report.getOwner() == 0){
						childTotal += report.getTotal_amount();
					}
					if(report.getOwner() == 1){
						mainTotal  += report.getTotal_amount();
					}
				}
				txt_total.setText(totalAmout+" Kyats");
				txt_main_gate_total.setText(mainTotal+" Kyats");
				txt_child_gate_total.setText(childTotal+" Kyats");
				dialog.dismiss();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
	}
	
}
