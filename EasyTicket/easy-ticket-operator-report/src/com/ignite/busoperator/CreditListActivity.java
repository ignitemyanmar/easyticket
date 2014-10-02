package com.ignite.busoperator;


import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import com.actionbarsherlock.app.SherlockActivity;
import com.actionbarsherlock.view.MenuItem;
import com.ignite.application.ActionDialog;
import com.ignite.application.ChangeAgentDialog;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.CityFromAdapter;
import com.ignite.busoperator.adapter.CityToAdapter;
import com.ignite.busoperator.adapter.CreditListViewAdapter;
import com.ignite.busoperator.adapter.TimeAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.MyDevice;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.Agents;
import com.ignite.busoperator.model.CitiesbyAgent;
import com.ignite.busoperator.model.CreditList;
import com.ignite.busoperator.model.OAuth2Error;
import com.ignite.busoperator.model.TimesbyOperator;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.text.format.DateFormat;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout.LayoutParams;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.LinearLayout;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;

public class CreditListActivity extends BaseSherlockActivity{
	public static Button  from_date, to_date, search;
	private Spinner city_from, city_to, sp_time;
	private String selectedOperatorId = "" ,selectedAgentId = "" , selectedStartDate = "" , selectedEndDate = "" ,selectedCityfromId = "" , selectedCitytoId = "" , selectedTimeid = "";
	private CitiesbyAgent city_by_agent;
	private List<TimesbyOperator>time_by_operator;
	private ListView lst_credit;
	private TextView txt_total_amount;
	RelativeLayout lyDetails;
	private LinearLayout lySearch;
	private MyDevice myDevice;
	private TextView txt_order_date;
	private TextView txt_total_ticket;
	private TextView txt_amount;
	private SKConnectionDetector skDetector;
	private TextView txt_invoice_no;
	private Button btn_pay;
	private TextView txt_trip;
	private Integer totalAmout = 0;
	private String selectedDeposit;
	private boolean isClickedToSearch = false;
	private CheckBox chk_delete;
	private TextView txt_percent_amount;
	private TextView txt_percented_amount;
	protected String selectedOrderId = "";
	private List<Agents> agentList = new ArrayList<Agents>();
	private ArrayAdapter<Agents> agentListAdapter;
	private String selectedAgentName;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getActionBar().setDisplayHomeAsUpEnabled(true);
		getActionBar().setIcon(R.drawable.ic_action_search);
		getActionBar().setTitle("Search");
		setContentView(R.layout.activity_credit_list);
		
		
		Bundle bundle = getIntent().getExtras();
		selectedAgentId = bundle.getString("agent_id");
		selectedAgentName = bundle.getString("agent_name");
		selectedDeposit = bundle.getString("deposit");
		
		getSupportActionBar().setTitle(selectedAgentName);
		lySearch = (LinearLayout)findViewById(R.id.lySearch);
		lyDetails = (RelativeLayout)findViewById(R.id.lydetails);
		lst_credit = (ListView) findViewById(R.id.lst_credit);
		myDevice = new MyDevice(this);
		
		lySearch.setTranslationX(-myDevice.getWidth());
		
		txt_order_date = (TextView) findViewById(R.id.txt_date);
		txt_trip = (TextView) findViewById(R.id.txt_trip);
		txt_invoice_no = (TextView) findViewById(R.id.txt_invoice);
		txt_total_ticket = (TextView) findViewById(R.id.txt_total_ticket);
		txt_amount = (TextView) findViewById(R.id.txt_amount);
		txt_percented_amount = (TextView) findViewById(R.id.txt_percented_amount);
		txt_total_amount =(TextView)findViewById(R.id.txt_total_amount);
		chk_delete = (CheckBox) findViewById(R.id.chk_delete);
		chk_delete.setOnClickListener(clickListener);
		btn_pay = (Button) findViewById(R.id.btn_pay);
		btn_pay.setOnClickListener(clickListener);
		
		city_from = (Spinner)findViewById(R.id.spn_TripsFrom);
		city_to = (Spinner)findViewById(R.id.spn_TripsTo);
		sp_time = (Spinner)findViewById(R.id.spn_time);
		from_date = (Button)findViewById(R.id.btnDateFrom);
		to_date = (Button)findViewById(R.id.btnDateTo);
		search = (Button)findViewById(R.id.btnSearch);
		
		txt_order_date.getLayoutParams().width = (int) (myDevice.getWidth()) / 7;
		txt_trip.getLayoutParams().width = (int) (myDevice.getWidth()) / 7;
		txt_invoice_no.getLayoutParams().width = (int) (myDevice.getWidth()) / 7;
		txt_total_ticket.getLayoutParams().width = (int) (myDevice.getWidth()) / 7;
		txt_amount.getLayoutParams().width = (int) (myDevice.getWidth()) / 7;
		txt_percented_amount.getLayoutParams().width = (int) (myDevice.getWidth()) / 7;
	
		
		skDetector = SKConnectionDetector.getInstance(getApplicationContext());
		
		if(skDetector.isConnectingToInternet()){
			getCity();
			getTimeData();
			
			city_from.setOnItemSelectedListener(cityFromClickListener);
			city_to.setOnItemSelectedListener(cityToClickListener);
			sp_time.setOnItemSelectedListener(timeClickListener);
			
		}else{
			skDetector.showErrorMessage();
		}
		
		lySearch.setOnClickListener(clickListener);
		search.setOnClickListener(clickListener);
		from_date.setOnClickListener(clickListener);
		to_date.setOnClickListener(clickListener);
	}
	
	@Override
	protected void onResume() {
		// TODO Auto-generated method stub
		super.onResume();
		if(skDetector.isConnectingToInternet()){
			getCreditList();			
		}else{
			skDetector.showErrorMessage();
		}
	}
	
	private CreditListViewAdapter.Callback childCheckedCallback = new CreditListViewAdapter.Callback() {
		
		public void onChildItemCheckedChange(int position, boolean isChecked) {
			// TODO Auto-generated method stub
			if(chk_delete.isChecked()){
				if(!isChecked){
					chk_delete.setChecked(false);
				}
			}else{
				if(isChecked){
					for(int i=0; i<creditList.size();i++){
						View childView = lst_credit.getChildAt(i);
						CheckBox chk_child_delete = (CheckBox) childView.findViewById(R.id.chk_delete);
						if(chk_child_delete.isChecked()){
							chk_delete.setChecked(true);
						}else{
							chk_delete.setChecked(false);
							break;
						}
					}
				}
			}
			selectedOrderId = "";
			totalAmout = 0;
			for(int i=0; i<creditList.size();i++){
				View childView = lst_credit.getChildAt(i);
				CheckBox chk_child_delete = (CheckBox) childView.findViewById(R.id.chk_delete);
				if(chk_child_delete.isChecked()){
					selectedOrderId  += creditList.get(i).getId().toString()+",";
					totalAmout   += creditList.get(i).getGrandTotal();
				}
				txt_total_amount.setText(totalAmout+" Kyats");
			}
			
			if(selectedOrderId.length() == 0){
				btn_pay.setEnabled(false);
			}else{
				btn_pay.setEnabled(true);
			}
			
		}

		public void onClickActionButton(final int position) {
			// TODO Auto-generated method stub
			ActionDialog actionDialog = new ActionDialog(CreditListActivity.this);
			actionDialog.setCallbackListener(new ActionDialog.Callback() {
				
				public void onDelete() {
					// TODO Auto-generated method stub
					dialog = ProgressDialog.show(CreditListActivity.this, "", " Please wait...", true);
			        dialog.setCancelable(true);
					SharedPreferences pref = CreditListActivity.this.getSharedPreferences("User", Activity.MODE_PRIVATE);
					String accessToken = pref.getString("access_token", null);
					selectedOperatorId = pref.getString("user_id", null);
					NetworkEngine.getInstance().deleteOrder(accessToken, selectedOperatorId, creditList.get(position).getId().toString(), new Callback<JSONObject>() {

						public void success(JSONObject arg0, Response arg1) {
							// TODO Auto-generated method stub
							dialog.dismiss();
							finish();
							SKToastMessage.showMessage(CreditListActivity.this, "Successfully save record.", SKToastMessage.SUCCESS);
						}
						
						public void failure(RetrofitError arg0) {
							// TODO Auto-generated method stub
							SKToastMessage.showMessage(CreditListActivity.this, "Can't save record.", SKToastMessage.ERROR);
							dialog.dismiss();
						}
					});
					
				}
				
				public void onChange() {
					// TODO Auto-generated method stub
					ChangeAgentDialog changeAgentDialog = new ChangeAgentDialog(CreditListActivity.this);
					changeAgentDialog.setCallbackListener(new ChangeAgentDialog.Callback() {
						
						public void onSave(String agentId) {
							// TODO Auto-generated method stub
							dialog = ProgressDialog.show(CreditListActivity.this, "", " Please wait...", true);
					        dialog.setCancelable(true);
							SharedPreferences pref = CreditListActivity.this.getSharedPreferences("User", Activity.MODE_PRIVATE);
							String accessToken = pref.getString("access_token", null);
							selectedOperatorId = pref.getString("user_id", null);
							Log.i("","Hello OpId = "+selectedOperatorId +" AgId = "+agentId+ "OdId = "+ creditList.get(position).getId().toString());
							NetworkEngine.getInstance().changeAgent(accessToken, selectedOperatorId, agentId, creditList.get(position).getId().toString(), new Callback<JSONObject>() {

								public void success(JSONObject arg0, Response arg1) {
									// TODO Auto-generated method stub
									dialog.dismiss();
									finish();
									SKToastMessage.showMessage(CreditListActivity.this, "Successfully save record.", SKToastMessage.SUCCESS);
								}
								
								public void failure(RetrofitError arg0) {
									// TODO Auto-generated method stub
									SKToastMessage.showMessage(CreditListActivity.this, "Can't save record.", SKToastMessage.ERROR);
									dialog.dismiss();
								}
							});
						}
						
						public void onCancel() {
							// TODO Auto-generated method stub
							
						}
					});
					changeAgentDialog.show();
				}
				
				public void onCancel() {
					// TODO Auto-generated method stub
					
				}
			});
			
			actionDialog.show();
		}
	};
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == lySearch){
				if(isOpen){
					isOpen = false;
					lySearch.setTranslationX(- myDevice.getWidth());
				}
			}
			if(v == search)
			{
				isClickedToSearch = true;
				if(skDetector.isConnectingToInternet()){
					getCreditList();	
				}else{
					skDetector.showErrorMessage();
				}
				
				if(isOpen){
					isOpen = false;
					lySearch.setTranslationX(- myDevice.getWidth());
				}
			}
			if(v == chk_delete){
				for(int i=0;i<creditList.size();i++){
					View childView = lst_credit.getChildAt(i);
					CheckBox chk_child_delete = (CheckBox) childView.findViewById(R.id.chk_delete);
					if(((CheckBox)v).isChecked()){
						chk_child_delete.setChecked(true);
					}else{
						chk_child_delete.setChecked(false);
					}
				}
			}
			if(v == from_date)
			{
				final SKCalender skCalender = new SKCalender(CreditListActivity.this);

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
				        	selectedStartDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	from_date.setText(selectedStartDate);
				        	
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
								
			}
			if(v == to_date)
			{
				final SKCalender skCalender = new SKCalender(CreditListActivity.this);

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
				        	selectedEndDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	to_date.setText(selectedEndDate);
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
			}
			
			if(v == btn_pay){
				if(!isClickedToSearch){
					selectedCityfromId = "";
					selectedCitytoId = "";
				}
				Bundle bundle = new Bundle();
				bundle.putString("operator_id", selectedOperatorId);
				bundle.putString("agent_id", selectedAgentId);
				bundle.putString("from_city",selectedCityfromId);
				bundle.putString("to_city",selectedCitytoId);
				bundle.putString("from_date", selectedStartDate);
				bundle.putString("to_date", selectedEndDate);
				bundle.putString("time", selectedTimeid);
				bundle.putString("deposit", selectedDeposit);
				bundle.putString("total_amount", totalAmout.toString());
				bundle.putString("order_id", selectedOrderId);
				startActivity(new Intent(getApplicationContext(), PayCreditActivity.class).putExtras(bundle));
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
	private List<CreditList> creditList;
	protected CreditListViewAdapter creditAdapter;
	private boolean isOpen = false;
	
	private void getCity() {
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String operator_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getCitybyOperator(accessToken, operator_id, new Callback<CitiesbyAgent>() {
		
			public void success(CitiesbyAgent arg0, Response arg1) {
				// TODO Auto-generated method stub
				city_by_agent = arg0;
				city_from.setAdapter(new CityFromAdapter(CreditListActivity.this,city_by_agent.getFrom()));
				city_to.setAdapter(new CityToAdapter(CreditListActivity.this, city_by_agent.getTo()));
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
	
	private void getCreditList(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		Log.i("","Sale Seat :" + selectedCityfromId + "," + selectedCitytoId + "," + selectedTimeid + "," + selectedStartDate+ "," + selectedEndDate);
				
		NetworkEngine.getInstance().getCreditList(accessToken, selectedOperatorId, selectedAgentId, selectedStartDate, selectedEndDate, selectedCityfromId, selectedCitytoId, selectedTimeid, new Callback<List<CreditList>>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}

			public void success(List<CreditList> arg0, Response arg1) {
				// TODO Auto-generated method stub
				totalAmout = 0;
				btn_pay.setEnabled(true);
				creditList = arg0;
				lst_credit.removeAllViewsInLayout();
				creditAdapter = new CreditListViewAdapter(CreditListActivity.this, creditList);
				lst_credit.setAdapter(creditAdapter);
				setListViewHeightBasedOnChildren(lst_credit);
				creditAdapter.setCallbacks(childCheckedCallback);
				for(CreditList creditList: arg0){
					totalAmout   += creditList.getGrandTotal();
					selectedOrderId  += creditList.getId().toString()+",";
				}
				txt_total_amount.setText(totalAmout+" Kyats");
				dialog.dismiss();
				if(arg0.size() == 0){
					btn_pay.setEnabled(false);
					SKToastMessage.showMessage(CreditListActivity.this, "သင္ ရွာေသာ အခ်က္ အလက္ မ်ား  မရိွေသးပါ...", SKToastMessage.INFO);
				}
			}
		});
		
	}
	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		// TODO Auto-generated method stub
		switch (item.getItemId()) {
		case android.R.id.home:
			if(isOpen){
				isOpen = false;
				lySearch.setTranslationX(- myDevice.getWidth());
			}else{
				isOpen = true;
				lySearch.setTranslationX(0);
			}
			
			return true;
		}
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
				sp_time.setAdapter(new TimeAdapter(CreditListActivity.this, time_by_operator));
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
	
	private static void setListViewHeightBasedOnChildren(ListView listView) {
		ListAdapter listAdapter = listView.getAdapter();
		if (listAdapter == null && listView.getCount() == 0) {
			// pre-condition
			return;
		}

		int totalHeight = 0;
		for (int i = 0; i < listAdapter.getCount(); i++) {
			View listItem = listAdapter.getView(i, null, listView);
			listItem.measure(0, 0);
			totalHeight += listItem.getMeasuredHeight();
		}

		LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
		params.height = totalHeight
				+ (listView.getDividerHeight() * (listAdapter.getCount() - 1));
		listView.setLayoutParams(params);
		listView.requestLayout();
	}
}
