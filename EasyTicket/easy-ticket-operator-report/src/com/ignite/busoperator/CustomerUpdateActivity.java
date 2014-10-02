package com.ignite.busoperator;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import com.google.gson.JsonObject;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.smk.skalertmessage.SKToastMessage;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

public class CustomerUpdateActivity extends BaseSherlockActivity {
	private EditText customer_name;
	private EditText nrc_no;
	private String selectedBusId;
	private String selectedSeatNo;
	private String selectedCustomerName;
	private String selectedNrcNo;
	private Button btn_update;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_update_customer);
		
		Bundle bundle = getIntent().getExtras();
		selectedBusId = bundle.getString("bus_id");
		selectedSeatNo = bundle.getString("seat_no");
		selectedCustomerName = bundle.getString("customer_name");
		selectedNrcNo = bundle.getString("nrc_no");
		
		Log.i("","Hello Param: bus_id = "+selectedBusId+" seat_no = "+selectedSeatNo+" cust_name = "+selectedCustomerName+" nrc_no = "+selectedNrcNo);
		
		customer_name = (EditText) findViewById(R.id.txt_customer_name);
		nrc_no = (EditText) findViewById(R.id.txt_nrc_no);
		btn_update = (Button) findViewById(R.id.btn_update);
		
		customer_name.setText(selectedCustomerName);
		nrc_no.setText(selectedNrcNo);
		
		btn_update.setOnClickListener(clickListener);
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			selectedCustomerName = customer_name.getText().toString();
			selectedNrcNo = nrc_no.getText().toString();
			UpdateCustomer();
		}
	};
	private ProgressDialog dialog;
	
	private void UpdateCustomer(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		
		NetworkEngine.getInstance().updateCustomer(accessToken, selectedBusId, selectedSeatNo, selectedCustomerName, selectedNrcNo, new Callback<JsonObject>() {
			
			public void success(JsonObject arg0, Response arg1) {
				// TODO Auto-generated method stub
				dialog.dismiss();
				finish();
				SKToastMessage.showMessage(CustomerUpdateActivity.this, "Successfully Update Record.", SKToastMessage.SUCCESS);
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				Log.e("","Hello Error: code = "+arg0.getResponse().getStatus()+ " reason = "+ arg0.getResponse().getReason());
				dialog.dismiss();
				SKToastMessage.showMessage(CustomerUpdateActivity.this, "Error: Can't update.", SKToastMessage.ERROR);
			}
		});
	}
}
