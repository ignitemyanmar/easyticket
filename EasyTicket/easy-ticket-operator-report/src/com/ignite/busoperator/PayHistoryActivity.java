package com.ignite.busoperator;

import java.util.List;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.widget.ListView;
import android.widget.TextView;

import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.busoperator.adapter.PayHistoryListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.PayHistory;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PayHistoryActivity extends BaseSherlockActivity {
	private TextView txt_deposit_pay_date;
	private TextView txt_deposit;
	private TextView txt_ticket_amount;
	private TextView txt_credit_pay_date;
	private TextView txt_remaining_deposit;
	private ListView lst_pay_history;
	private ProgressDialog dialog;
	private String selectedOperatorId;
	private String selectedAgentId;
	protected List<PayHistory> payHistroyList;
	private TextView txt_pay_amount;
	private String selectedAgentName;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_pay_history);
		
		Bundle bundle = getIntent().getExtras();
		selectedAgentId = bundle.getString("agent_id");
		selectedAgentName = bundle.getString("agent_name");
		
		
		getSupportActionBar().setTitle(selectedAgentName);
		
		txt_deposit_pay_date = (TextView) findViewById(R.id.txt_deposit_pay_date);
    	//txt_deposit = (TextView) findViewById(R.id.txt_deposit);
    	txt_pay_amount = (TextView) findViewById(R.id.txt_pay_amount);
    	txt_ticket_amount = (TextView) findViewById(R.id.txt_ticket_amount);
    	txt_credit_pay_date = (TextView) findViewById(R.id.txt_credit_pay_date);
    	txt_remaining_deposit = (TextView) findViewById(R.id.txt_remaining_deposit);
		lst_pay_history = (ListView) findViewById(R.id.lst_pay_history);
		
		txt_deposit_pay_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 5;
		//txt_deposit.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 5;
		txt_pay_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 5;
		txt_ticket_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 5;
		txt_credit_pay_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 5;
		txt_remaining_deposit.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 5;
		
		SKConnectionDetector skDetector = SKConnectionDetector.getInstance(this);
		skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
		if(skDetector.isConnectingToInternet()){
			getPaymentHistory();
		}else{
			skDetector.showErrorMessage();
		}
	}
	
	private PayHistoryListViewAdapter.Callback historyListViewAdapter = new PayHistoryListViewAdapter.Callback() {
		
		public void setDelete(final int position) {
			// TODO Auto-generated method stub
			AlertDialog.Builder alertDialog = new AlertDialog.Builder(PayHistoryActivity.this);
			alertDialog.setMessage("Are you sure to delete?");
		
			alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
				
				public void onClick(DialogInterface dialog, int which) {
					// TODO Auto-generated method stub
					if(SKConnectionDetector.getInstance(PayHistoryActivity.this).isConnectingToInternet()){
						SharedPreferences pref = getApplicationContext()
								.getSharedPreferences("User", Activity.MODE_PRIVATE);
						String accessToken = pref.getString("access_token", null);
						NetworkEngine.getInstance().deletePayHistory( accessToken, payHistroyList.get(position).getOperatorId().toString(),payHistroyList.get(position).getAgentId().toString(),payHistroyList.get(position).getId().toString(), new Callback<JSONObject>() {
							
							public void success(JSONObject arg0, Response arg1) {
								// TODO Auto-generated method stub
								Log.i("","Hello Response: "+ arg0.toString());
								payHistroyList.remove(position);
								payHistoryAdapter.notifyDataSetChanged();
							}
							
							public void failure(RetrofitError arg0) {
								// TODO Auto-generated method stub
							}
						});
					}				
					dialog.cancel();
				}
			});
			
			alertDialog.setNegativeButton("NO",
					new DialogInterface.OnClickListener() {
						public void onClick(DialogInterface dialog,	int which) {
							dialog.cancel();
							return;
						}
					});
			
			alertDialog.show();
		}
	};
	protected PayHistoryListViewAdapter payHistoryAdapter;
	
	private void getPaymentHistory(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
        dialog.show();
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		NetworkEngine.getInstance().getPaymentHistory(accessToken, selectedOperatorId, selectedAgentId, new Callback<List<PayHistory>>() {
			
			public void success(List<PayHistory> arg0, Response arg1) {
				// TODO Auto-generated method stub
				payHistroyList = arg0;
				payHistoryAdapter = new PayHistoryListViewAdapter(PayHistoryActivity.this, payHistroyList);
				lst_pay_history.setAdapter(payHistoryAdapter);
				payHistoryAdapter.setOnCallbackListener(historyListViewAdapter);
				dialog.dismiss();
				if(arg0.size() == 0){
					SKToastMessage.showMessage(PayHistoryActivity.this, "သင္ ရွာေသာ အခ်က္ အလက္ မ်ား  မရိွေသးပါ...", SKToastMessage.INFO);
				}
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
	}
}
