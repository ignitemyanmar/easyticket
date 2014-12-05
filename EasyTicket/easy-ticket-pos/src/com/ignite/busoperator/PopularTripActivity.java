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
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.AdapterView.OnItemSelectedListener;
import com.ignite.busoperator.adapter.AgentAdapter;
import com.ignite.busoperator.adapter.PopularAgentListViewAdapter;
import com.ignite.busoperator.adapter.PopularTripListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.Agent;
import com.ignite.busoperator.model.AgentsbyOperator;
import com.ignite.busoperator.model.OAuth2Error;
import com.ignite.busoperator.model.PopularTrip;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PopularTripActivity extends BaseSherlockActivity {
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
	protected AgentsbyOperator agent_by_operator;
	private ArrayList<PopularTrip> PopularTrips;
	private PopularTripListViewAdapter popularTripsAdapter;
	private SKConnectionDetector detector;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		getSupportActionBar().setTitle("အ ေရာင္း ရဆံုး ခရီးစဥ္ စာရင္းမ်ား");
		
		setContentView(R.layout.activity_popular_trip);
		
		lySearch = (LinearLayout)findViewById(R.id.lySearch);
		lyDetails = (RelativeLayout)findViewById(R.id.lydetails);
		lst_agent = (ListView) findViewById(R.id.lst_trip);
		
		txt_name = (TextView) findViewById(R.id.txt_trip);
		txt_total_seat = (TextView) findViewById(R.id.txt_total_seat);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		txt_grand_total =(TextView)findViewById(R.id.txt_grand_total);
		btn_from_date = (Button)findViewById(R.id.btn_from_date);
		btn_to_date = (Button)findViewById(R.id.btn_to_date);
		sp_agent = (Spinner)findViewById(R.id.sp_agent);
		
		sp_agent.setOnItemSelectedListener(agentClickListener);
		
		btn_search = (Button) findViewById(R.id.btn_search);
		btn_search.setOnClickListener(clickListener);
		btn_from_date.setOnClickListener(clickListener);
		btn_to_date.setOnClickListener(clickListener);
		
		btn_from_date.setText(getDate()[0]);
		btn_to_date.setText(getDate()[1]);
		
		lySearch.getLayoutParams().width = (DeviceUtil.getInstance(this).getWidth()/3);
		lyDetails.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5);
		
		txt_name.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5) / 4;
		txt_total_seat.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5) / 4;
		txt_total_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()/1.5) / 4;
		
		PopularTrips = new ArrayList<PopularTrip>();
		popularTripsAdapter = new PopularTripListViewAdapter(this, PopularTrips);
		popularTripsAdapter.setOnViewDetailListener(callbacks);
		lst_agent.setAdapter(popularTripsAdapter);
		
		detector = new SKConnectionDetector(this);
		
		
		agent_by_operator = new AgentsbyOperator();
		agent_by_operator.getAgents().add(
				new Agent("", "Select All...", null, null, null, null, null));
		
		selectedFromDate = getDate()[0];
		selectedToDate   = getDate()[1];
		
		if(detector.isConnectingToInternet()){
			getAgent();
			getPopularTrip();
		}else{
			detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
			detector.showErrorMessage();
		}
	}
	
	private PopularTripListViewAdapter.Callbacks callbacks = new PopularTripListViewAdapter.Callbacks() {
		
		public void onViewDetial(int positon) {
			// TODO Auto-generated method stub
			Bundle bundle = new Bundle();
			bundle.putString("agent_id", selectedAgentId);
			bundle.putString("from_date", selectedFromDate);
			bundle.putString("to_date", selectedToDate);
			bundle.putString("from_id", PopularTrips.get(positon).getFrom().toString());
			bundle.putString("to_id",PopularTrips.get(positon).getTo().toString());
			startActivity(new Intent(getApplicationContext(), PopularTriptimeActivity.class).putExtras(bundle));
		}
	};
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_from_date)
			{
				final SKCalender skCalender = new SKCalender(PopularTripActivity.this);

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
				final SKCalender skCalender = new SKCalender(PopularTripActivity.this);

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
					getPopularTrip();
				}else{
					detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
					detector.showErrorMessage();
				}
			}
		}
	};
	private ProgressDialog dialog;
	protected String selectedAgentId;
	protected String selectedAgentName;
	
	private void getPopularTrip() {
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		dialog.show();

		NetworkEngine.getInstance().getPopularTrip(
				AppLoginUser.getAccessToken(), AppLoginUser.getUserID(),
				selectedFromDate, selectedToDate, selectedAgentId,new Callback<List<PopularTrip>>() {
					
					public void success(List<PopularTrip> arg0, Response arg1) {
						// TODO Auto-generated method stub
						PopularTrips.clear();
						PopularTrips.addAll(arg0);
						popularTripsAdapter.notifyDataSetChanged();
						Integer totalAmount = 0;
						for(PopularTrip trip: arg0){
							totalAmount += trip.getTotalAmount();
						}
						txt_grand_total.setText(totalAmount.toString());
						dialog.dismiss();
					}
					
					public void failure(RetrofitError arg0) {
						// TODO Auto-generated method stub
						dialog.dismiss();
					}
				});
	}
	
	private void getAgent() {

		NetworkEngine.getInstance().getAgentbyOperator(AppLoginUser.getAccessToken(), AppLoginUser.getUserID(),
				new Callback<AgentsbyOperator>() {

					public void success(AgentsbyOperator arg0, Response arg1) {
						// TODO Auto-generated method stub
						agent_by_operator.getAgents().addAll(arg0.getAgents());
						sp_agent.setAdapter(new AgentAdapter(
								PopularTripActivity.this, agent_by_operator
										.getAgents()));
					}

					public void failure(RetrofitError arg0) {
						// TODO Auto-generated method stub
						OAuth2Error error = (OAuth2Error) arg0
								.getBodyAs(OAuth2Error.class);
						Log.i("", "Hello Error Response Code : "
								+ arg0.getResponse().getStatus());
						Log.i("", "Hello Error : " + error.getError());
						Log.i("",
								"Hello Error Desc : "
										+ error.getError_description());
					}
				});
	}
	
	private OnItemSelectedListener agentClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedAgentId = "";
			selectedAgentName = "";
			if(arg2 > 0){
				selectedAgentId = agent_by_operator.getAgents().get(arg2).getId();
				selectedAgentName = agent_by_operator.getAgents().get(arg2).getName();
			}
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub

		}
	};
	
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
