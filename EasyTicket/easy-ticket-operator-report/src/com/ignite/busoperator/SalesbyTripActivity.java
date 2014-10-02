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
import com.ignite.busoperator.adapter.CityFromAdapter;
import com.ignite.busoperator.adapter.CityToAdapter;
import com.ignite.busoperator.adapter.OperatorAdapter;
import com.ignite.busoperator.adapter.OperatorDateListAdapter;
import com.ignite.busoperator.adapter.TimeAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.MyDevice;
import com.ignite.busoperator.clientapi.NetworkEngine;
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
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
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
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;

public class SalesbyTripActivity extends BaseSherlockActivity{
	public static Button  from_date, to_date, search;
	private Spinner city_from, city_to, sp_time;
	private String selectedOperatorId = "", selectedCityfromId = "", selectedCitytoId = "", selectedTimeid = "";
	private CitiesbyAgent city_by_agent;
	private List<TimesbyOperator>time_by_operator;
	private ListView lvOperator;
	private TextView txt_total;
	RelativeLayout lyDetails;
	private LinearLayout lySearch;
	private MyDevice myDevice;
	private TextView txt_order_date;
	private TextView txt_total_ticket;
	private TextView txt_total_amount;
	private TextView txt_total_label;
	private SKConnectionDetector skDetector;
	private String selectedFromDate = "";
	private String selectedToDate = "";
	private Button btn_all_detail;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getSupportActionBar().hide();
		setContentView(R.layout.activity_operator);
		
		lySearch = (LinearLayout)findViewById(R.id.lySearch);
		lyDetails = (RelativeLayout)findViewById(R.id.lydetails);
		lvOperator = (ListView) findViewById(R.id.lvDate);
		myDevice = new MyDevice(this);
		
		txt_order_date = (TextView) findViewById(R.id.txt_order_date);
		txt_total_ticket = (TextView) findViewById(R.id.txt_total_ticket);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amout);
		txt_total_label = (TextView) findViewById(R.id.txtTotal);
		txt_total =(TextView)findViewById(R.id.txtSum);
		city_from = (Spinner)findViewById(R.id.spn_TripsFrom);
		city_to = (Spinner)findViewById(R.id.spn_TripsTo);
		sp_time = (Spinner)findViewById(R.id.spn_time);
		from_date = (Button)findViewById(R.id.btnDateFrom);
		to_date = (Button)findViewById(R.id.btnDateTo);
		search = (Button)findViewById(R.id.btnSearch);
		btn_all_detail = (Button) findViewById(R.id.btn_all_detail);
		btn_all_detail.setVisibility(View.VISIBLE);
		btn_all_detail.setOnClickListener(clickListener);
		
		// lanscape
		lySearch.getLayoutParams().width = (myDevice.getWidth()/3);
		lyDetails.getLayoutParams().width = (int) (myDevice.getWidth()/1.5);
		
		txt_order_date.getLayoutParams().width = (int) (myDevice.getWidth()/1.5) / 4;
		txt_total_ticket.getLayoutParams().width = (int) (myDevice.getWidth()/1.5) / 4;
		txt_total_amount.getLayoutParams().width = (int) (myDevice.getWidth()/1.5) / 4;
		txt_total_label.getLayoutParams().width = (int) ((int) (myDevice.getWidth()/1.5) / 2.5);
		
		city_by_agent = new CitiesbyAgent();
		city_by_agent.getFrom().add(new From("", "Select All..."));
		city_by_agent.getTo().add(new To("", "Select All..."));
		time_by_operator = new ArrayList<TimesbyOperator>();
		time_by_operator.add(new TimesbyOperator("Select All..."));
		
		skDetector = SKConnectionDetector.getInstance(getApplicationContext());
		
		if(skDetector.isConnectingToInternet()){
			getCity();
			getTimeData();
			getTripsbyOperator();
			city_from.setOnItemSelectedListener(cityFromClickListener);
			city_to.setOnItemSelectedListener(cityToClickListener);
			sp_time.setOnItemSelectedListener(timeClickListener);
		}else{
			skDetector.showErrorMessage();
		}
		
		search.setOnClickListener(clickListener);
		from_date.setOnClickListener(clickListener);
		to_date.setOnClickListener(clickListener);
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == search)
			{
				if(skDetector.isConnectingToInternet()){
					getTripsbyOperator();	
				}else{
					skDetector.showErrorMessage();
					lvOperator.setAdapter(new OperatorDateListAdapter(SalesbyTripActivity.this, tripsbyOperators));
				}
			}
			if(v == from_date)
			{
				final SKCalender skCalender = new SKCalender(SalesbyTripActivity.this);

				  skCalender.setCallbacks(new Callbacks() {

				        public void onChooseDate(String chooseDate) {
				          // TODO Auto-generated method stub
				        	
				        	Date formatedDate = null;
				        	try {
								formatedDate = new SimpleDateFormat("dd-MMM-yyyy").parse(chooseDate);
							} catch (java.text.ParseException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
				        	String selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	selectedFromDate = selectedDate;
				        	from_date.setText(selectedDate);
				        	
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
								
			}
			if(v == to_date)
			{
				final SKCalender skCalender = new SKCalender(SalesbyTripActivity.this);

				  skCalender.setCallbacks(new Callbacks() {

				        public void onChooseDate(String chooseDate) {
				          // TODO Auto-generated method stub
				        	Date formatedDate = null;
				        	try {
								formatedDate = new SimpleDateFormat("dd-MMM-yyyy").parse(chooseDate);
							} catch (java.text.ParseException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
				        	String selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	selectedToDate = selectedDate;
				        	to_date.setText(selectedDate);
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
			}
			
			if(v == btn_all_detail){
				SharedPreferences sharedPreferences = getSharedPreferences("Filter",MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.clear();
				editor.commit();
				editor.putString("operator_id", selectedOperatorId);
				editor.putString("from_city", selectedCityfromId);
				editor.putString("from_city_name", selectedCityfromName);
				editor.putString("to_city", selectedCitytoId);
				editor.putString("to_city_name", selectedCitytoName);
				editor.putString("from_date", selectedFromDate);
				editor.putString("to_date", selectedToDate);
				editor.putString("time", selectedTimeid);
				editor.putString("date", "");
				editor.commit();
				startActivity(new Intent(getApplicationContext(), ReportsActivity.class));
			}
			
		}
	};
	
	private OnItemSelectedListener cityFromClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedCityfromId = "";
			selectedCityfromName = "";
			if(arg2 > 0){
				selectedCityfromId = city_by_agent.getFrom().get(arg2).getId();
				selectedCityfromName = city_by_agent.getFrom().get(arg2).getName();
			}
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
			
		}
	};
	
	private OnItemSelectedListener cityToClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedCitytoId = "";
			selectedCitytoName = "";
			if(arg2 > 0){
				selectedCitytoId = city_by_agent.getTo().get(arg2).getId();
				selectedCitytoName = city_by_agent.getTo().get(arg2).getName();
			}
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
			
		}
	};

	private OnItemSelectedListener timeClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedTimeid = "";
			if(arg2 != 0){
				selectedTimeid = time_by_operator.get(arg2).getTime();
			}
			
			
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
		}
	};
	
	private ProgressDialog dialog;
	
	private List<TripsbyOperator> tripsbyOperators;
	private String selectedCityfromName;
	private String selectedCitytoName;
	
	private void getCity() {
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String operator_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getCitybyOperator(accessToken, operator_id, new Callback<CitiesbyAgent>() {
		
			public void success(CitiesbyAgent arg0, Response arg1) {
				// TODO Auto-generated method stub
				city_by_agent.getFrom().addAll(arg0.getFrom());
				city_by_agent.getTo().addAll(arg0.getTo());
				city_from.setAdapter(new CityFromAdapter(SalesbyTripActivity.this,city_by_agent.getFrom()));
				city_to.setAdapter(new CityToAdapter(SalesbyTripActivity.this, city_by_agent.getTo()));
			}

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				OAuth2Error error = (OAuth2Error) arg0.getBodyAs(OAuth2Error.class);
				Log.i("","Hello Error Response Code : "+arg0.getResponse().getStatus());
				Log.i("","Hello Error : "+error.getError());
				Log.i("","Hello Error Desc : "+error.getError_description());
			}
			
		});
	}
	
	private void getTripsbyOperator() {
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User",
				Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		
		SharedPreferences sharedPreferences = getApplicationContext()
				.getSharedPreferences("SearchbyOperator", MODE_PRIVATE);
		SharedPreferences.Editor editor = sharedPreferences.edit();
		editor.clear();
		editor.commit();
		editor.putString("operator_id", selectedOperatorId);
		editor.putString("from_city", selectedCityfromId);
		editor.putString("from_city_name", selectedCityfromName);
		editor.putString("to_city", selectedCitytoId);
		editor.putString("to_city_name", selectedCitytoName);
		editor.putString("from_date", selectedFromDate);
		editor.putString("to_date", selectedToDate);
		editor.putString("time", selectedTimeid);
		editor.putString("date", "");
		editor.commit();

		NetworkEngine.getInstance().getTripsbyOperator(accessToken,
				selectedOperatorId, "", selectedCityfromId, selectedCitytoId,
				selectedTimeid, selectedFromDate,
				selectedToDate,
				new Callback<List<TripsbyOperator>>() {

					private Integer totalAmout = 0;

					public void failure(RetrofitError arg0) {
						// TODO Auto-generated method stub
						dialog.dismiss();
					}

					public void success(List<TripsbyOperator> arg0,Response arg1) {
						// TODO Auto-generated method stub
						tripsbyOperators = arg0;
						lvOperator.setAdapter(new OperatorDateListAdapter(
								SalesbyTripActivity.this, tripsbyOperators));
						for (TripsbyOperator tripsbyOperator : arg0) {
							totalAmout += tripsbyOperator.getTotal_amout();
						}
						txt_total.setText(totalAmout + " Kyats");
						dialog.dismiss();
						if (arg0.size() == 0) {
							SKToastMessage.showMessage(
									SalesbyTripActivity.this,
									"သင္ ရွာေသာ အခ်က္ အလက္ မ်ား  မရိွေသးပါ...",
									SKToastMessage.INFO);
						}
					}
				});
	}
	
	private void getTimeData() {
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String operator_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getTimebyOperator(accessToken, operator_id , new Callback<List<TimesbyOperator>>() {

			public void success(List<TimesbyOperator> arg0, Response arg1) {
				// TODO Auto-generated method stub
				time_by_operator.addAll(arg0);
				sp_time.setAdapter(new TimeAdapter(SalesbyTripActivity.this, time_by_operator));
			}
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				OAuth2Error error = (OAuth2Error) arg0.getBodyAs(OAuth2Error.class);
				Log.i("","Hello Error Response Code : "+arg0.getResponse().getStatus());
				Log.i("","Hello Error : "+error.getError());
				Log.i("","Hello Error Desc : "+error.getError_description());
			}
		});
	}
}
