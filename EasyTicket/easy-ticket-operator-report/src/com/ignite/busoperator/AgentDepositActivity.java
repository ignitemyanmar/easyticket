package com.ignite.busoperator;

import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.actionbarsherlock.app.SherlockActivity;
import com.google.gson.Gson;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.Agents;
import com.smk.skalertmessage.SKToastMessage;

public class AgentDepositActivity extends BaseSherlockActivity{
	private TextView txt_name;
	private EditText edt_deposit;
	private Button btn_cancel;
	private Button btn_save;
	private Agents agent;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_agent_deposit);
		Bundle bundle = getIntent().getExtras();
		if(bundle != null){
			String agentString = bundle.getString("agent");
			agent = new Gson().fromJson(agentString, Agents.class);
		}
		txt_name = (TextView) findViewById(R.id.txt_name);
		edt_deposit = (EditText) findViewById(R.id.edt_deposit);
		btn_cancel = (Button) findViewById(R.id.btn_cancel);
		btn_save = (Button) findViewById(R.id.btn_save);
		
		txt_name.setText(agent.getName().toString());
		
		btn_cancel.setOnClickListener(clickListener);
		btn_save.setOnClickListener(clickListener);
		
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_cancel){
				finish();
			}
			
			if(v == btn_save){
				if(edt_deposit.getText().length() > 0){
					postDeposit();
				}else{
					edt_deposit.setError("Please Enter Deposit.");
				}
			}
		}
	};
	private ProgressDialog dialog;

	protected void postDeposit() {
		// TODO Auto-generated method stub
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
        dialog.show();
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String selectedOperatorId = pref.getString("user_id", null);
		NetworkEngine.getInstance().postDeposit(accessToken, selectedOperatorId, agent.getId().toString(), edt_deposit.getText().toString(), new Callback<JSONObject>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
				SKToastMessage.showMessage(AgentDepositActivity.this, "Can't save record.", SKToastMessage.ERROR);
			}

			public void success(JSONObject arg0, Response arg1) {
				// TODO Auto-generated method stub
				dialog.dismiss();
				finish();
				SKToastMessage.showMessage(AgentDepositActivity.this, "Successfully save record.", SKToastMessage.SUCCESS);
			}
		});
	}
}
