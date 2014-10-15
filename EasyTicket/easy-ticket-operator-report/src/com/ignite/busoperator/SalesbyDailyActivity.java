package com.ignite.busoperator;


import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.List;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import com.actionbarsherlock.app.SherlockActivity;
import com.actionbarsherlock.view.MenuItem;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.AdvanceBusListAdapter;
import com.ignite.busoperator.adapter.BusReportListAdapter;
import com.ignite.busoperator.adapter.CityFromAdapter;
import com.ignite.busoperator.adapter.CityToAdapter;
import com.ignite.busoperator.adapter.OperatorAdapter;
import com.ignite.busoperator.adapter.OperatorDateListAdapter;
import com.ignite.busoperator.adapter.TimeAdapter;
import com.ignite.busoperator.adapter.TodayBusListAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.MyDevice;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.AdvanceBus;
import com.ignite.busoperator.model.TodayBus;
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
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TextView;

public class SalesbyDailyActivity extends BaseSherlockActivity{
	public static Button  from_date, search;
	private String selectedOperatorId ;
	private ListView lst_today_bus;
	private TextView txt_total;
	private MyDevice myDevice;
	private TextView txt_total_amount;
	private TextView txt_total_label;
	private SKConnectionDetector skDetector;
	private TextView txt_time;
	private TextView txt_seat;
	private TextView txt_trip;
	private ListView lst_feature_bus;
	private Button btn_date;
	public static String selectedDate;
	private Integer totalAmout = 0;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getSupportActionBar().setTitle("ေန႕စဥ္ အ ေရာင္း စာရင္း မ်ား");
		setContentView(R.layout.activity_report_daily_bydate);
		
		myDevice = new MyDevice(this);
		
		lst_today_bus = (ListView) findViewById(R.id.lst_today_bus);
		lst_feature_bus = (ListView) findViewById(R.id.lst_feature_bus);
		
		btn_date = (Button) findViewById(R.id.btn_date);
		btn_date.setOnClickListener(clickListener);
		txt_time = (TextView) findViewById(R.id.txt_time);
		txt_seat = (TextView) findViewById(R.id.txt_seat);
		txt_trip = (TextView) findViewById(R.id.txt_trip);
		txt_total = (TextView) findViewById(R.id.txt_total);
		txt_total_label = (TextView) findViewById(R.id.txt_label);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		
		if (myDevice.getHeight() > myDevice.getWidth()){
		} else {
			
			txt_time.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_seat.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_trip.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_total.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_total_label.getLayoutParams().width = (int) myDevice.getWidth() / 5;
			txt_total_amount.getLayoutParams().width = (int) myDevice.getWidth() / 2;
		}
		
		skDetector = SKConnectionDetector.getInstance(getApplicationContext());
		selectedDate = getToday();
		
		if(skDetector.isConnectingToInternet()){
			dialog = ProgressDialog.show(this, "", " Please wait...", true);
	        dialog.setCancelable(true);
	        dialog.show();
			getTodayBus();
			getAdvanceBus();
		}else{
			skDetector.showErrorMessage();
		}		
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			final SKCalender skCalender = new SKCalender(SalesbyDailyActivity.this);

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
			        	
			        	
			        	if(skDetector.isConnectingToInternet()){
			    			getTodayBus();
			    			getAdvanceBus();
			    		}else{
			    			skDetector.showErrorMessage();
			    		}
			        	
			        	selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
			        	
			        	skCalender.dismiss();
			        }
			  });

			  skCalender.show();
		}
	};
	
	private String getToday(){
		Calendar c = Calendar.getInstance();
		System.out.println("Current time => " + c.getTime());

		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		String formattedDate = df.format(c.getTime());
		Log.i("","Hello Today: "+formattedDate);
		return formattedDate;
	}
	
	
	private ProgressDialog dialog;
	private List<TodayBus> todayBus;
	protected List<AdvanceBus> advanceBus;
	
	private void getTodayBus(){
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		Log.i("","Hello OperatorID= "+selectedOperatorId+" Date= "+ selectedDate);
		NetworkEngine.getInstance().getTodayBus(accessToken, selectedOperatorId, selectedDate, new Callback<List<TodayBus>>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
			}

			public void success(List<TodayBus> arg0, Response arg1) {
				// TODO Auto-generated method stub
				todayBus = arg0;
				lst_today_bus.setAdapter(new TodayBusListAdapter(SalesbyDailyActivity.this, todayBus));
				setListViewHeightBasedOnChildren(lst_today_bus);
				for(TodayBus report: arg0){
					totalAmout  += report.getSold_amount();
				}
				txt_total_amount.setText(totalAmout+" Kyats");
			}
		});
	}
	
	private void getAdvanceBus(){
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
				
		NetworkEngine.getInstance().getAdvanceBus(accessToken, selectedOperatorId, selectedDate, new Callback<List<AdvanceBus>>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}

			public void success(List<AdvanceBus> arg0, Response arg1) {
				// TODO Auto-generated method stub
				advanceBus = arg0;
				lst_feature_bus.setAdapter(new AdvanceBusListAdapter(SalesbyDailyActivity.this, advanceBus));
				setListViewHeightBasedOnChildren(lst_feature_bus);
				
				for(AdvanceBus report: arg0){
					totalAmout  += report.getTotal_amout();
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
	public static void setListViewHeightBasedOnChildren(ListView listView) {
        ListAdapter listAdapter = listView.getAdapter(); 
        if (listAdapter == null) {
            // pre-condition
            return;
        }

        int totalHeight = 0;
        for (int i = 0; i < listAdapter.getCount(); i++) {
            View listItem = listAdapter.getView(i, null, listView);
            listItem.measure(0, 0);
            totalHeight += listItem.getMeasuredHeight();
        }

        ViewGroup.LayoutParams params = listView.getLayoutParams();
        params.height = totalHeight + (listView.getDividerHeight() * (listAdapter.getCount() - 1));
        listView.setLayoutParams(params);
        listView.requestLayout();
    }
}
