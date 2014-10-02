
package com.ignite.busoperator;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.graphics.Paint;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.EditText;
import android.widget.TextView;

import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PayCreditActivity extends BaseSherlockActivity {
	private String selectedOperatorId;
	private String selectedAgentId;
	private String selectedCityfromId;
	private String selectedCitytoId;
	private String selectedStartDate;
	private String selectedEndDate;
	private String selectedTimeid;
	private String totalAmout;
	private TextView txt_credit_total;
	private TextView txt_deposit_amount;
	private EditText edt_pay_amount;
	private Button btn_cancel;
	private Button btn_save;
	private String selectedDeposit;
	private SKConnectionDetector skDetector;
	private CheckBox chk_pay;
	private TextView txt_remaining_amount;
	private String selectedOrderId;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_pay_credit);
		
		Bundle bundle = getIntent().getExtras();
		selectedOperatorId = bundle.getString("operator_id");
		selectedAgentId = bundle.getString("agent_id");
		selectedCityfromId = bundle.getString("from_city");
		selectedCitytoId = bundle.getString("to_city");
		selectedStartDate = bundle.getString("from_date");
		selectedEndDate = bundle.getString("to_date");
		selectedTimeid = bundle.getString("time");
		selectedDeposit = bundle.getString("deposit");
		selectedOrderId = bundle.getString("order_id");
		totalAmout = bundle.getString("total_amount");
		
		txt_credit_total = (TextView) findViewById(R.id.txt_credit_total);
		txt_deposit_amount = (TextView) findViewById(R.id.txt_deposit_amount);
		edt_pay_amount = (EditText) findViewById(R.id.edt_pay_amount);
		edt_pay_amount.addTextChangedListener(textWatcher);
		txt_remaining_amount = (TextView) findViewById(R.id.txt_remaining_amount);
		chk_pay = (CheckBox) findViewById(R.id.chk_pay);
		chk_pay.setOnCheckedChangeListener(changeListener);
		
		btn_cancel = (Button) findViewById(R.id.btn_cancel);
		btn_save = (Button) findViewById(R.id.btn_save);
		if(Integer.valueOf(selectedDeposit) == 0){
			chk_pay.setEnabled(false);
		}
		/*if(Integer.valueOf(selectedDeposit) < Integer.valueOf(totalAmout)){
			chk_pay.setEnabled(false);
			SKToastMessage.showMessage(this, "ေပးရန္ ပမာဏ မေလာက္ပါသျဖင့္ ၾကိဳတင္ေငြ ထပ္သြင္းပါ", SKToastMessage.ERROR);
			btn_save.setEnabled(false);
		}else{
			Integer remainAmount = Integer.valueOf(selectedDeposit) - Integer.valueOf(totalAmout);
			txt_remaining_amount.setText(remainAmount+" Ks");
			btn_save.setEnabled(true);
		}*/
		
		txt_credit_total.setText(totalAmout + " Ks");
		txt_deposit_amount.setText(selectedDeposit + " Ks");
		
		btn_cancel.setOnClickListener(clickListener);
		btn_save.setOnClickListener(clickListener);
		
		skDetector = SKConnectionDetector.getInstance(this);
		skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
		
	}
	
	private OnCheckedChangeListener changeListener = new OnCheckedChangeListener() {
		
		public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
			// TODO Auto-generated method stub
			if(isChecked){
				Integer remainAmount = Integer.valueOf(selectedDeposit) - Integer.valueOf(totalAmout);
				txt_deposit_amount.setText(remainAmount.toString()+" Ks");
				edt_pay_amount.setText("");
				edt_pay_amount.setEnabled(false);
				txt_credit_total.setPaintFlags(Paint.STRIKE_THRU_TEXT_FLAG);
			}else{
				txt_deposit_amount.setText(selectedDeposit+" Ks");
				edt_pay_amount.setEnabled(true);
				if ((txt_credit_total.getPaintFlags() & Paint.STRIKE_THRU_TEXT_FLAG) > 0){
					txt_credit_total.setPaintFlags( txt_credit_total.getPaintFlags() & (~ Paint.STRIKE_THRU_TEXT_FLAG));
				}
			}
		}
	};
	private TextWatcher textWatcher = new TextWatcher() {
		
		public void onTextChanged(CharSequence s, int start, int before, int count) {
			// TODO Auto-generated method stub
			if(edt_pay_amount.getText().toString().equals(totalAmout)){
				txt_credit_total.setPaintFlags(Paint.STRIKE_THRU_TEXT_FLAG);
			}else{
				txt_credit_total.setText(totalAmout+" Ks");
				if ((txt_credit_total.getPaintFlags() & Paint.STRIKE_THRU_TEXT_FLAG) > 0){
					txt_credit_total.setPaintFlags( txt_credit_total.getPaintFlags() & (~ Paint.STRIKE_THRU_TEXT_FLAG));
				}
			}
		}
		
		public void beforeTextChanged(CharSequence s, int start, int count,
				int after) {
			// TODO Auto-generated method stub
			
		}
		
		public void afterTextChanged(Editable s) {
			// TODO Auto-generated method stub
			
		}
	};
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_cancel){
				finish();
			}
			
			if(v == btn_save){
				if(skDetector.isConnectingToInternet()){
					
					if(checkField()){
						postPayment();
					}else{
						edt_pay_amount.setError("Please Enter Payment Amount.");
					}
					
				}else{
					skDetector.showErrorMessage();
				}
			}
			
		}
	};
	
	private boolean checkField(){
		boolean checked = true;
		if(edt_pay_amount.getText().length() == 0 && Integer.valueOf(selectedDeposit) == 0 ){
			checked = false;
		}
		return checked;
	}
	private ProgressDialog dialog;
	
	private void postPayment(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
        dialog.show();
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		
		Log.i("","Sale Seat : op =" + selectedOperatorId+", agentId = " + selectedAgentId + ", startDate = " + selectedStartDate + ", endDate = " + selectedEndDate + ", fromId=" + selectedCityfromId+ ", toId=" + selectedCitytoId+", orderId ="+ selectedOrderId);
		NetworkEngine.getInstance().postPayment(accessToken, selectedOperatorId, selectedAgentId, selectedOrderId, edt_pay_amount.getText().toString(), new Callback<JSONObject>() {
			
			public void success(JSONObject arg0, Response arg1) {
				// TODO Auto-generated method stub
				dialog.dismiss();
				finish();
				SKToastMessage.showMessage(PayCreditActivity.this, "Successfully save record.", SKToastMessage.SUCCESS);
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				SKToastMessage.showMessage(PayCreditActivity.this, "Can't save record.", SKToastMessage.ERROR);
				dialog.dismiss();
			}
		});
	}
	
}
