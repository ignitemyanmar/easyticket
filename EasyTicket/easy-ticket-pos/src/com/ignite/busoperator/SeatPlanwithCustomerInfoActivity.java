package com.ignite.busoperator;


import java.util.ArrayList;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import com.actionbarsherlock.app.SherlockActivity;
import com.actionbarsherlock.view.MenuItem;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.SeatwithCustomerAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.OccupancySeatPlanwithCustomer;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.GridView;
import android.widget.ListAdapter;

public class SeatPlanwithCustomerInfoActivity extends BaseSherlockActivity{
	private String selectedOperatorId , selectedCityfromId , selectedCitytoId , selectedTimeid;
	private GridView seat_plan;
	private SKConnectionDetector skDetector;
	private String selectedDate;
	private String selectedBusId;
	private ProgressDialog dialog;
	private OccupancySeatPlanwithCustomer occupancySeatPlan;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);

		setContentView(R.layout.activity_occupancy_seat_with_customer);
		
		seat_plan = (GridView) findViewById(R.id.grid_seat_plan);
		seat_plan.setOnItemClickListener(itemClickListener);
						
		skDetector = SKConnectionDetector.getInstance(getApplicationContext());
		
		Bundle bundle = getIntent().getExtras();
		selectedBusId = bundle.getString("bus_id");
		
		SharedPreferences pref = this.getSharedPreferences("SearchbyOperator", Activity.MODE_PRIVATE);
		selectedOperatorId = pref.getString("operator_id", null);
		selectedCityfromId = pref.getString("from_city", null);
		selectedCitytoId = pref.getString("to_city", null);
		selectedDate = pref.getString("from_date", null);
		selectedTimeid = pref.getString("time", null);
		
	}
	
	@Override
	protected void onResume() {
		// TODO Auto-generated method stub
		if(skDetector.isConnectingToInternet()){
			getOccupancySeatPlan();
		}else{
			skDetector.showErrorMessage();
			fadeData();
		}
		super.onResume();
	}

	private void fadeData(){
			
	}
	
	private void getOccupancySeatPlan(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		
		Log.i("","Hello Param: OP_ID = "+selectedOperatorId+" FID = "+ selectedCityfromId +" TID = "+ selectedCitytoId+" Date = "+ selectedDate+ "Time = "+ selectedTimeid +" BID = "+selectedBusId);
		NetworkEngine.getInstance().getOccupancySeatPlanwithCustomer(accessToken, selectedOperatorId, selectedCityfromId, selectedCitytoId, selectedDate, selectedTimeid,selectedBusId, new Callback<OccupancySeatPlanwithCustomer>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
				Log.e("", "Hello Error: "+arg0.getResponse().getStatus());
			}

			public void success(OccupancySeatPlanwithCustomer arg0, Response arg1) {
				// TODO Auto-generated method stub
				occupancySeatPlan = arg0;
				Log.i("","Hello: "+ occupancySeatPlan.toString());
				seat_plan.setNumColumns(occupancySeatPlan.getSeat_plan().get(0).getColumn());
				seat_plan.getLayoutParams().width = 150 * occupancySeatPlan.getSeat_plan().get(0).getColumn();
				seat_plan.setAdapter(new SeatwithCustomerAdapter(SeatPlanwithCustomerInfoActivity.this, occupancySeatPlan.getSeat_plan().get(0).getSeatList()));
				setGridViewHeightBasedOnChildren(seat_plan , Integer.valueOf(occupancySeatPlan.getSeat_plan().get(0).getColumn()));
				dialog.dismiss();
			}
		});
	}
	
	private OnItemClickListener itemClickListener = new OnItemClickListener() {

		public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			if(occupancySeatPlan.getSeat_plan().get(0).getSeatList().get(arg2).getCustomer() != null){
				Bundle bundle = new Bundle();
				bundle.putString("bus_id", occupancySeatPlan.getSeat_plan().get(0).getBusId().toString());
				bundle.putString("seat_no", occupancySeatPlan.getSeat_plan().get(0).getSeatList().get(arg2).getSeatNo().toString());
				bundle.putString("customer_name", occupancySeatPlan.getSeat_plan().get(0).getSeatList().get(arg2).getCustomer().getName().toString());
				bundle.putString("nrc_no", occupancySeatPlan.getSeat_plan().get(0).getSeatList().get(arg2).getCustomer().getNrc().toString());
				
				startActivity(new Intent(getApplicationContext(), CustomerUpdateActivity.class).putExtras(bundle));
			}			
		}
	};
	
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
