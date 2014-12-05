package com.ignite.busoperator;


import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import com.actionbarsherlock.app.SherlockActivity;
import com.actionbarsherlock.view.MenuItem;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.BusReportListAdapter;
import com.ignite.busoperator.adapter.CityFromAdapter;
import com.ignite.busoperator.adapter.CityToAdapter;
import com.ignite.busoperator.adapter.OperatorAdapter;
import com.ignite.busoperator.adapter.OperatorDateListAdapter;
import com.ignite.busoperator.adapter.TimeAdapter;
import com.ignite.busoperator.adapter.TodayBusbyTimeListAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.MyDevice;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.TodayBusbyTime;
import com.ignite.busoperator.model.CitiesbyAgent;
import com.ignite.busoperator.model.From;
import com.ignite.busoperator.model.OAuth2Error;
import com.ignite.busoperator.model.Operator;
import com.ignite.busoperator.model.OperatorsbyAgent;
import com.ignite.busoperator.model.TimesbyOperator;
import com.ignite.busoperator.model.To;
import com.ignite.busoperator.model.TripsbyOperator;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.format.DateFormat;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;

public class TodayBusActivity extends BaseSherlockActivity{
	public static Button  from_date, search;
	private String selectedOperatorId ;
	private ListView lst_today_bus;
	private TextView txt_total;
	private MyDevice myDevice;
	private TextView txt_total_amount;
	private TextView txt_total_label;
	private SKConnectionDetector skDetector;
	private TextView txt_bus_no;
	private TextView txt_seat;
	private TextView txt_trip;
	private ListView lst_feature_bus;
	private TextView txt_class;
	private String selectedTime;
	private String selectedDate;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getSupportActionBar().hide();
		setContentView(R.layout.activity_report_daily_bybus);
		
		myDevice = new MyDevice(this);
		
		lst_today_bus = (ListView) findViewById(R.id.lst_today_bus);
		
		txt_bus_no = (TextView) findViewById(R.id.txt_bus_no);
		txt_class = (TextView) findViewById(R.id.txt_class);
		txt_seat = (TextView) findViewById(R.id.txt_seat);
		txt_total = (TextView) findViewById(R.id.txt_total);
		txt_total_label = (TextView) findViewById(R.id.txt_label);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		
		if (myDevice.getHeight() > myDevice.getWidth()){
		} else {
			
			txt_bus_no.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_class.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_seat.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_total.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_total_label.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_total_amount.getLayoutParams().width = (int) myDevice.getWidth() / 2;
		}
		
		Bundle bundle = getIntent().getExtras();
		selectedTime = bundle.getString("time");
		selectedDate = bundle.getString("date");
		
		skDetector = SKConnectionDetector.getInstance(getApplicationContext());
		
		if(skDetector.isConnectingToInternet()){
			getTodayBus();
		}else{
			skDetector.showErrorMessage();
		}
	}
	
	
	private ProgressDialog dialog;
	private List<TodayBusbyTime> todayBus;
	
	
	private void getTodayBus(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
				
		NetworkEngine.getInstance().getTodayBusbyTime(accessToken, selectedOperatorId, selectedDate,selectedTime, new Callback<List<TodayBusbyTime>>() {

			private Integer totalAmout = 0;

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}

			public void success(List<TodayBusbyTime> arg0, Response arg1) {
				// TODO Auto-generated method stub
				todayBus = arg0;
				lst_today_bus.setAdapter(new TodayBusbyTimeListAdapter(TodayBusActivity.this, todayBus));
				for(TodayBusbyTime report: arg0){
					totalAmout  += report.getSold_amount();
				}
				txt_total_amount.setText(totalAmout+" Kyats");
				dialog.dismiss();
			}
		});
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// TODO Auto-generated method stub
		
		return false;
	}
	
}
