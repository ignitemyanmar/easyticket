
package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.OperReportByBusNoAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.OperReportByBusNo;
import com.ignite.busoperator.model.TripsbyOperator;
import com.ignite.busoperator.model.TripsbyDate;
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

public class OperatorReportByBusNo extends BaseSherlockActivity{
	private ListView lvOperOne;
	private ProgressDialog dialog;
	private String agent_id;
	private String operator_id;
	private String from;
	private String to;
	private String departure_time;
	private String date;
	private TextView txt_order_busno;
	private TextView txt_trip_time;
	private TextView txt_total_ticket;
	private TextView txt_total_amount;
	private TextView txt_total_label;
	private TextView txt_total;
	private TextView txt_trip_date;
	private SKConnectionDetector skDetector;
	private TextView txt_trip;
	private String filter;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_operator_date);
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
		
		txt_order_busno = (TextView) findViewById(R.id.txt_order_busno);
		txt_trip = (TextView) findViewById(R.id.txt_trip);
		txt_trip_date = (TextView) findViewById(R.id.txt_trip_date);
		txt_trip_time = (TextView) findViewById(R.id.txt_trip_time);
		txt_total_ticket = (TextView) findViewById(R.id.txt_total_ticket);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amout);
		txt_total_label = (TextView) findViewById(R.id.txtTotal);
		txt_total =(TextView)findViewById(R.id.txtSum);
		
		txt_order_busno.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_trip.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_trip_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_trip_time.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_total_ticket.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_total_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_total_label.getLayoutParams().width = (int) ((int) (DeviceUtil.getInstance(this).getWidth()) / 2.5);
		
		
		agent_id = pref.getString("agent_id", null);
		operator_id = pref.getString("operator_id", null);
		from = pref.getString("from_city", null);
		to = pref.getString("to_city", null);
		departure_time = pref.getString("time", null);
		date = pref.getString("date", null);
		
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
		NetworkEngine.getInstance().getTripsbyDate(accessToken, operator_id, agent_id, from, to, departure_time, date, new Callback<List<TripsbyDate>>() {
			
			private Integer totalAmout = 0;

			public void success(List<TripsbyDate> arg0, Response arg1) {
				// TODO Auto-generated method stub
				lvOperOne.setAdapter(new OperReportByBusNoAdapter(OperatorReportByBusNo.this, arg0));
				for(TripsbyDate tripsbyDate: arg0){
					totalAmout  += tripsbyDate.getTotal_amout();
				}
				txt_total.setText(totalAmout+" Kyats");
				dialog.dismiss();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
	}
	
}
