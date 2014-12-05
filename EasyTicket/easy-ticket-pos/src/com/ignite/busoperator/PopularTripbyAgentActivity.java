package com.ignite.busoperator;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.ListView;
import android.widget.TextView;
import com.ignite.busoperator.adapter.PopularTripbyAgentListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.PopularTrip;
import com.smk.calender.widget.SKCalender;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PopularTripbyAgentActivity extends BaseSherlockActivity {
	private ListView lst_agent;
	private TextView txt_name;
	private TextView txt_total_seat;
	private TextView txt_total_amount;
	private TextView txt_grand_total;
	private String selectedFromDate;
	private String selectedToDate;
	private ArrayList<PopularTrip> PopularTrips;
	private PopularTripbyAgentListViewAdapter popularTripsAdapter;
	private SKConnectionDetector detector;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		getSupportActionBar().setTitle("အ ေရာင္း ရဆံုး ခရီးစဥ္ စာရင္းမ်ား");
		
		setContentView(R.layout.activity_popular_trip_by_agent);
		
		lst_agent = (ListView) findViewById(R.id.lst_trip);
		
		txt_name = (TextView) findViewById(R.id.txt_trip);
		txt_total_seat = (TextView) findViewById(R.id.txt_total_seat);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		txt_grand_total =(TextView)findViewById(R.id.txt_grand_total);
		
		
		txt_name.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		txt_total_seat.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		txt_total_amount.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		
		PopularTrips = new ArrayList<PopularTrip>();
		popularTripsAdapter = new PopularTripbyAgentListViewAdapter(this, PopularTrips);
		popularTripsAdapter.setOnViewDetailListener(callbacks);
		lst_agent.setAdapter(popularTripsAdapter);
		
		detector = new SKConnectionDetector(this);
		
		Bundle bundle = getIntent().getExtras();
		if(bundle != null){
			selectedAgentId = bundle.getString("agent_id");
			selectedFromDate = bundle.getString("from_date");
			selectedToDate = bundle.getString("to_date");
		}
		
		if(detector.isConnectingToInternet()){
			getPopularTrip();
		}else{
			detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
			detector.showErrorMessage();
		}
	}
	
	private PopularTripbyAgentListViewAdapter.Callbacks callbacks = new PopularTripbyAgentListViewAdapter.Callbacks() {
		
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
}
