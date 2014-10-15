package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.ProgressDialog;
import android.os.Bundle;
import android.widget.ListView;
import android.widget.TextView;
import com.ignite.busoperator.adapter.PopularTriptimeListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.AgentsbyOperator;
import com.ignite.busoperator.model.PopularTriptime;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PopularTriptimeActivity extends BaseSherlockActivity {
	private ListView lst_trip;
	private TextView txt_time;
	private TextView txt_total_seat;
	private TextView txt_total_amount;
	private TextView txt_grand_total;
	private String selectedFromDate;
	private String selectedToDate;
	private String selectedFromId;
	private String selectedToId;
	protected AgentsbyOperator agent_by_operator;
	private ArrayList<PopularTriptime> PopularTriptimes;
	private PopularTriptimeListViewAdapter PopularTriptimesAdapter;
	private SKConnectionDetector detector;
	private TextView txt_classes;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		getSupportActionBar().hide();
		
		setContentView(R.layout.activity_popular_triptime);
		
		lst_trip = (ListView) findViewById(R.id.lst_trip);
		
		txt_time = (TextView) findViewById(R.id.txt_time);
		txt_classes = (TextView) findViewById(R.id.txt_classes);
		txt_total_seat = (TextView) findViewById(R.id.txt_total_seat);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		txt_grand_total =(TextView)findViewById(R.id.txt_grand_total);
				
		txt_time.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		txt_classes.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		txt_total_seat.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		txt_total_amount.getLayoutParams().width = (int) DeviceUtil.getInstance(this).getWidth() / 4;
		
		Bundle bundle = getIntent().getExtras();
		if(bundle != null){
			selectedAgentId = bundle.getString("agent_id");
			selectedFromDate = bundle.getString("from_date");
			selectedToDate = bundle.getString("to_date");
			selectedFromId = bundle.getString("from_id");
			selectedToId = bundle.getString("to_id");
		}
		
		PopularTriptimes = new ArrayList<PopularTriptime>();
		PopularTriptimesAdapter = new PopularTriptimeListViewAdapter(this, PopularTriptimes);
		lst_trip.setAdapter(PopularTriptimesAdapter);
		
		detector = new SKConnectionDetector(this);
		
		if(detector.isConnectingToInternet()){
			getPopularTriptime();
		}else{
			detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
			detector.showErrorMessage();
		}
	}
	
	private ProgressDialog dialog;
	protected String selectedAgentId;
	protected String selectedAgentName;
	
	private void getPopularTriptime() {
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		dialog.show();

		NetworkEngine.getInstance().getPopularTriptime(
				AppLoginUser.getAccessToken(), AppLoginUser.getUserID(),
				selectedFromDate, selectedToDate, selectedAgentId,
				selectedFromId,selectedToId,new Callback<List<PopularTriptime>>() {
					
					public void success(List<PopularTriptime> arg0, Response arg1) {
						// TODO Auto-generated method stub
						PopularTriptimes.clear();
						PopularTriptimes.addAll(arg0);
						PopularTriptimesAdapter.notifyDataSetChanged();
						Integer totalAmount = 0;
						for(PopularTriptime trip: arg0){
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
