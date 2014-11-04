package com.ignite.busoperator;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.text.format.DateFormat;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;

import com.actionbarsherlock.app.ActionBar;
import com.ignite.busoperator.adapter.PopularAgentListViewAdapter;
import com.ignite.busoperator.adapter.TargetLabelAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.PopularAgent;
import com.ignite.busoperator.model.TargetLabel;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PopularAgentActivity extends BaseSherlockActivity {
	private LinearLayout lySearch;
	private RelativeLayout lyDetails;
	private ListView lst_agent;
	private TextView txt_name;
	private TextView txt_total_seat;
	private TextView txt_total_amount;
	private TextView txt_grand_total;
	private TextView btn_from_date;
	private TextView btn_to_date;
	private Spinner sp_agent;
	private String selectedFromDate;
	private String selectedToDate;
	private Button btn_search;
	private ArrayList<PopularAgent> PopularAgents;
	private PopularAgentListViewAdapter populoarAgentAdapter;
	private SKConnectionDetector detector;
	private ActionBar actionBar;
	private TextView actionBarTitle;
	private Spinner actionbarSpinnerLabel;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.actionbar_with_spinner);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.txt_title);
		actionBarTitle.setText("အ ေရာင္း ရဆံုး အ ေရာင္း ကိုယ္စားလွယ္ စာရင္းမ်ား");
		actionbarSpinnerLabel = (Spinner) actionBar.getCustomView().findViewById(R.id.sp_label);
		actionbarSpinnerLabel.setVisibility(View.INVISIBLE);
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
				
		setContentView(R.layout.activity_popular_agent);
		
		lySearch = (LinearLayout)findViewById(R.id.lySearch);
		lyDetails = (RelativeLayout)findViewById(R.id.lydetails);
		lst_agent = (ListView) findViewById(R.id.lst_agent);
		
		txt_name = (TextView) findViewById(R.id.txt_name);
		txt_total_seat = (TextView) findViewById(R.id.txt_total_seat);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		txt_grand_total =(TextView)findViewById(R.id.txt_grand_total);
		btn_from_date = (Button)findViewById(R.id.btn_from_date);
		btn_to_date = (Button)findViewById(R.id.btn_to_date);
		sp_agent = (Spinner)findViewById(R.id.sp_agent);
		
		btn_from_date.setText(getDate()[0]);
		btn_to_date.setText(getDate()[1]);
		
		btn_search = (Button) findViewById(R.id.btn_search);
		btn_search.setOnClickListener(clickListener);
		btn_from_date.setOnClickListener(clickListener);
		btn_to_date.setOnClickListener(clickListener);
		
		lySearch.getLayoutParams().width = (DeviceUtil.getInstance(this).getWidth()/3);
		lyDetails.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5);
		
		txt_name.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5) / 5;
		txt_total_seat.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5) / 5;
		txt_total_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5) / 5;
		
		PopularAgents = new ArrayList<PopularAgent>();
		populoarAgentAdapter = new PopularAgentListViewAdapter(this, PopularAgents);
		populoarAgentAdapter.setOnViewDetailListener(callbacks);
		lst_agent.setAdapter(populoarAgentAdapter);
		
		detector = new SKConnectionDetector(this);
		selectedFromDate = getDate()[0];
		selectedToDate   = getDate()[1];
		if(detector.isConnectingToInternet()){
			getPopularAgent();
			getTargetLabel();
		}else{
			detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
			detector.showErrorMessage();
		}
		
	}
	
	private PopularAgentListViewAdapter.Callbacks callbacks = new PopularAgentListViewAdapter.Callbacks() {
		
		public void onViewDetial(int positon) {
			// TODO Auto-generated method stub
			Bundle bundle = new Bundle();
			bundle.putString("agent_id", PopularAgents.get(positon).getId().toString());
			bundle.putString("from_date", selectedFromDate);
			bundle.putString("to_date", selectedToDate);
			startActivity(new Intent(getApplicationContext(), PopularTripbyAgentActivity.class).putExtras(bundle));
		}
	};
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_from_date)
			{
				final SKCalender skCalender = new SKCalender(PopularAgentActivity.this);

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
				        	btn_from_date.setText(selectedDate);
				        	
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
								
			}
			if(v == btn_to_date)
			{
				final SKCalender skCalender = new SKCalender(PopularAgentActivity.this);

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
				        	btn_to_date.setText(selectedDate);
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
			}
			
			if(v == btn_search){
				if(detector.isConnectingToInternet()){
					getPopularAgent();
				}else{
					detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
					detector.showErrorMessage();
				}
			}
		}
	};
	private ProgressDialog dialog;
	protected List<TargetLabel> targetLabels;
	
	private void getTargetLabel(){
		NetworkEngine.getInstance().getTargetLabel(AppLoginUser.getAccessToken(), new Callback<List<TargetLabel>>() {
			
			public void success(List<TargetLabel> arg0, Response arg1) {
				// TODO Auto-generated method stub
				targetLabels = new ArrayList<TargetLabel>();
				targetLabels.add(new TargetLabel(0, "Select Target Level", 0, 0, "#000000"));
				targetLabels.addAll(arg0);
				actionbarSpinnerLabel.setAdapter(new TargetLabelAdapter(PopularAgentActivity.this, targetLabels));
				//actionbarSpinnerLabel.setOnItemSelectedListener(itemSelectedListener);
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}
		});
	}
	
	private OnItemSelectedListener itemSelectedListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			if(arg2 == 0){
				//populoarAgentAdapter.filter("");
			}else{
				Log.i("","Hello : "+ targetLabels.get(arg2).getName());
				populoarAgentAdapter.filter(targetLabels.get(arg2).getName());
			}
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub
			
		}
	};
	
	private void getPopularAgent(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		dialog.show();
		NetworkEngine.getInstance().getPopularAgent(AppLoginUser.getAccessToken(), AppLoginUser.getUserID(), selectedFromDate, selectedToDate, new Callback<List<PopularAgent>>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}

			public void success(List<PopularAgent> arg0, Response arg1) {
				// TODO Auto-generated method stub
				PopularAgents.addAll(arg0);
				populoarAgentAdapter.notifyDataSetChanged();
				dialog.dismiss();
			}
		});
	}
	
	private String[] getDate(){
		String today 	= SKCalender.getToday("yyyy-MM-dd");
		String[] todayArr 		= today.split("-");
		String startDay 		= "01";
		String endDay 			= "15";
		if(Integer.valueOf(todayArr[2]) > 15){
			startDay	= "16";
			endDay		= SKCalender.getInstance(this).getEndDayofThisMonth().toString();
		}
		String[] twoDate = {todayArr[0]+"-"+todayArr[1]+"-"+startDay,todayArr[0]+"-"+todayArr[1]+"-"+endDay};
		return twoDate;
	}
}
