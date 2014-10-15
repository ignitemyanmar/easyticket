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
import com.ignite.busoperator.adapter.SeatAdapter;
import com.ignite.busoperator.adapter.TimeAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.MyDevice;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.BusReport;
import com.ignite.busoperator.model.CitiesbyAgent;
import com.ignite.busoperator.model.From;
import com.ignite.busoperator.model.OAuth2Error;
import com.ignite.busoperator.model.OccupancySeatPlan;
import com.ignite.busoperator.model.Operator;
import com.ignite.busoperator.model.OperatorsbyAgent;
import com.ignite.busoperator.model.TimesbyOperator;
import com.ignite.busoperator.model.To;
import com.ignite.busoperator.model.TripsbyOperator;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.R.layout;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.text.format.DateFormat;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.Button;
import android.widget.GridView;
import android.widget.LinearLayout;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;

public class SalesbyOccupencySeatPlanActivity extends BaseSherlockActivity{
	public static Button  from_date, search;
	private Spinner city_from, city_to, sp_time;
	private String selectedOperatorId , selectedCityfromId , selectedCitytoId , selectedTimeid;
	private CitiesbyAgent city_by_agent;
	private List<TimesbyOperator>time_by_operator;
	private GridView seat_plan;
	private SKConnectionDetector skDetector;
	private String selectedDate;
	private LinearLayout layout_car_no_list;
	private Button btn_view_detail;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getSupportActionBar().setTitle("ထိုင္ ခံု ႏွင့္ လူစာရင္းမ်ား");
		setContentView(R.layout.activity_occupancy_seat);
		
		seat_plan = (GridView) findViewById(R.id.grid_seat_plan);
		city_from = (Spinner)findViewById(R.id.spn_TripsFrom);
		city_to = (Spinner)findViewById(R.id.spn_TripsTo);
		sp_time = (Spinner)findViewById(R.id.spn_time);
		from_date = (Button)findViewById(R.id.btnDateFrom);
		search = (Button)findViewById(R.id.btnSearch);
				
		layout_car_no_list = (LinearLayout) findViewById(R.id.layout_car_no_list);
		btn_view_detail = (Button) findViewById(R.id.btn_view_detail);
		skDetector = SKConnectionDetector.getInstance(getApplicationContext());
		
		if(skDetector.isConnectingToInternet()){
			getCity();
			getTimeData();
			
			city_from.setOnItemSelectedListener(cityFromClickListener);
			city_to.setOnItemSelectedListener(cityToClickListener);
			sp_time.setOnItemSelectedListener(timeClickListener);
			
		}else{
			skDetector.showErrorMessage();
			fadeData();
		}
		
		search.setOnClickListener(clickListener);
		from_date.setOnClickListener(clickListener);
		btn_view_detail.setOnClickListener(clickListener);
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == search)
			{
				if(skDetector.isConnectingToInternet()){
					getOccupancySeatPlan();	
				}else{
					skDetector.showErrorMessage();
				}
			}
			if(v == from_date)
			{
				final SKCalender skCalender = new SKCalender(SalesbyOccupencySeatPlanActivity.this);

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
				        	selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	from_date.setText(selectedDate);
				        	
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
								
			}
			if(v == btn_view_detail){
				Bundle bundle = new Bundle();
				bundle.putString("bus_id", selectedBusId);
				startActivity(new Intent(getApplicationContext(), SeatPlanwithCustomerInfoActivity.class).putExtras(bundle));
			}
			
		}
	};
	
	private OnItemSelectedListener cityFromClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedCityfromId = null;
			selectedCityfromId = city_by_agent.getFrom().get(arg2).getId();
			
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
			
		}
	};
	
	private OnItemSelectedListener cityToClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedCitytoId = null;
			selectedCitytoId = city_by_agent.getTo().get(arg2).getId();
			
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
			
		}
	};

	private OnItemSelectedListener timeClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedTimeid = null;
			selectedTimeid = time_by_operator.get(arg2).getTime();
			
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
		}
	};
	private ProgressDialog dialog;
	private OccupancySeatPlan occupancySeatPlan;
	protected String selectedBusId;
	
	private void fadeData(){
		
		List<From> from = new ArrayList<From>();
		from.add(new From("1", "Yangon") );
		
		List<To> to = new ArrayList<To>();
		to.add(new To("1", "Mandalay") );
		to.add(new To("1", "Naypyitaw") );
		to.add(new To("1", "Pying Oo Lwing") );
		
		city_from.setAdapter(new CityFromAdapter(SalesbyOccupencySeatPlanActivity.this,from));
		city_to.setAdapter(new CityToAdapter(SalesbyOccupencySeatPlanActivity.this, to));
		
		List<TimesbyOperator> times_by_agent = new ArrayList<TimesbyOperator>();
		times_by_agent.add(new TimesbyOperator("10:00 AM"));
		times_by_agent.add(new TimesbyOperator("11:00 AM"));
		times_by_agent.add(new TimesbyOperator("12:00 AM"));
		times_by_agent.add(new TimesbyOperator("1:00 PM"));
		times_by_agent.add(new TimesbyOperator("2:00 PM"));
		sp_time.setAdapter(new TimeAdapter(SalesbyOccupencySeatPlanActivity.this, times_by_agent));
		
	}
	
	private void getCity() {
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String operator_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getCitybyOperator(accessToken, operator_id, new Callback<CitiesbyAgent>() {
		
			public void success(CitiesbyAgent arg0, Response arg1) {
				// TODO Auto-generated method stub
				city_by_agent = arg0;
				city_from.setAdapter(new CityFromAdapter(SalesbyOccupencySeatPlanActivity.this,city_by_agent.getFrom()));
				city_to.setAdapter(new CityToAdapter(SalesbyOccupencySeatPlanActivity.this, city_by_agent.getTo()));
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
	
	private void getOccupancySeatPlan(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		
		SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences("SearchbyOperator",MODE_PRIVATE);
		SharedPreferences.Editor editor = sharedPreferences.edit();
		editor.putString("operator_id", selectedOperatorId);	   						
		editor.putString("from_city",selectedCityfromId);
		editor.putString("to_city",selectedCitytoId);
		editor.putString("from_date", from_date.getText().toString());
		editor.putString("time", selectedTimeid);
		editor.putString("date", "");
		editor.commit();
		
		NetworkEngine.getInstance().getOccupancySeatPlan(accessToken, selectedOperatorId, selectedCityfromId, selectedCitytoId, selectedDate, selectedTimeid, new Callback<OccupancySeatPlan>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}

			public void success(OccupancySeatPlan arg0, Response arg1) {
				// TODO Auto-generated method stub
				if(arg0.getSeat_plan().size() == 0){
					dialog.dismiss();
					SKToastMessage.showMessage(SalesbyOccupencySeatPlanActivity.this, "သင္ ရွာေသာ အခ်က္ အလက္ မ်ား  မရိွေသးပါ...", SKToastMessage.INFO);
					return;
				}
				occupancySeatPlan = arg0;
				seat_plan.setNumColumns(Integer.parseInt(occupancySeatPlan.getSeat_plan().get(0).getColumn()));
				seat_plan.getLayoutParams().width = 75 * Integer.parseInt(occupancySeatPlan.getSeat_plan().get(0).getColumn());
				seat_plan.setAdapter(new SeatAdapter(SalesbyOccupencySeatPlanActivity.this, occupancySeatPlan.getSeat_plan().get(0).getSeat_list()));
				setGridViewHeightBasedOnChildren(seat_plan , Integer.valueOf(occupancySeatPlan.getSeat_plan().get(0).getColumn()));
				selectedBusId = occupancySeatPlan.getSeat_plan().get(0).getId();
				for(int i = 0; i < occupancySeatPlan.getSeat_plan().size(); i++){
					Button btn_car_no = new Button(SalesbyOccupencySeatPlanActivity.this);
					btn_car_no.setText(occupancySeatPlan.getSeat_plan().get(i).getBus_no().toString());
					btn_car_no.setBackgroundColor(Color.parseColor("#072549"));
					btn_car_no.setTextColor(Color.WHITE);
					btn_car_no.setTag(i);
					btn_car_no.setOnClickListener(new View.OnClickListener() {
						
						public void onClick(View v) {
							// TODO Auto-generated method stub
							int position = Integer.parseInt(v.getTag().toString());
							Log.i("","Hello position : "+ position);
							seat_plan.setNumColumns(Integer.parseInt(occupancySeatPlan.getSeat_plan().get(position).getColumn()));
							seat_plan.getLayoutParams().width = 75 * Integer.parseInt(occupancySeatPlan.getSeat_plan().get(position).getColumn());
							seat_plan.setAdapter(new SeatAdapter(SalesbyOccupencySeatPlanActivity.this, occupancySeatPlan.getSeat_plan().get(position).getSeat_list()));
							setGridViewHeightBasedOnChildren(seat_plan , Integer.valueOf(occupancySeatPlan.getSeat_plan().get(position).getColumn()));
							selectedBusId = occupancySeatPlan.getSeat_plan().get(position).getId();
						}
					});
					layout_car_no_list.addView(btn_car_no);
					
				}
				dialog.dismiss();
				
			}
		});
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// TODO Auto-generated method stub
		
		return false;
	}
	
	private void getTimeData() {
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String operator_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getTimebyOperator(accessToken, operator_id , new Callback<List<TimesbyOperator>>() {

			public void success(List<TimesbyOperator> arg0, Response arg1) {
				// TODO Auto-generated method stub
				Log.i("","Time :" + arg0.toString());
				time_by_operator = arg0;
				sp_time.setAdapter(new TimeAdapter(SalesbyOccupencySeatPlanActivity.this, time_by_operator));
				/*mLoadingView.setVisibility(View.GONE);
				mLoadingView.startAnimation(topOutAnimaiton());*/
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
	public void setGridViewHeightBasedOnChildren(GridView gridView, int columns) {
		ListAdapter listAdapter = gridView.getAdapter();
		if (listAdapter == null) {
			// pre-condition
			return;
		}

		int totalHeight = 0;
		int items = listAdapter.getCount();
		int rows = 0;

		View listItem = listAdapter.getView(0, null, gridView);
		listItem.measure(0, 0);
		totalHeight = listItem.getMeasuredHeight();

		float x = 1;
		if (items > columns) {
			x = items / columns;
			rows = (int) (x + 1);
			totalHeight *= rows;
		}

		ViewGroup.LayoutParams params = gridView.getLayoutParams();
		params.height = totalHeight;
		gridView.setLayoutParams(params);

	}
}
