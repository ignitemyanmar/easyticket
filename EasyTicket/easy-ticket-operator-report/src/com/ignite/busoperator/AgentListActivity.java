package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.inputmethod.InputMethodManager;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;

import com.actionbarsherlock.app.SherlockActivity;
import com.actionbarsherlock.view.Menu;
import com.actionbarsherlock.view.MenuItem;
import com.actionbarsherlock.view.MenuItem.OnActionExpandListener;
import com.actionbarsherlock.widget.SearchView;
import com.actionbarsherlock.widget.SearchView.OnQueryTextListener;
import com.google.gson.Gson;
import com.ignite.busoperator.adapter.AgentListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.Agents;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class AgentListActivity extends SherlockActivity {
	private ListView lst_agent;
	public static List<Agents> agentList;
	private AgentListViewAdapter agentAdapter;
	private TextView txt_name;
	private TextView txt_deposit_amount;
	private SKConnectionDetector skDetector;
	private TextView txt_credit_amount;
	private TextView txt_percent_amount;
	private TextView txt_percented_amount;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
	    
		setContentView(R.layout.activity_agent);
		txt_name = (TextView) findViewById(R.id.txt_name);
    	txt_deposit_amount = (TextView) findViewById(R.id.txt_deposit_amount);
    	txt_credit_amount = (TextView) findViewById(R.id.txt_credit);
    	txt_percent_amount = (TextView) findViewById(R.id.txt_percent_amount);
    	txt_percented_amount = (TextView) findViewById(R.id.txt_percented_amount);
		lst_agent = (ListView) findViewById(R.id.lst_agent);
		
		txt_name.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_deposit_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_credit_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_percent_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		txt_percented_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(this).getWidth()) / 6;
		
		agentList = new ArrayList<Agents>();
		
	}
	
	@Override
	protected void onResume() {
		// TODO Auto-generated method stub
		super.onResume();
		
		skDetector = SKConnectionDetector.getInstance(this);
		skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
		if(skDetector.isConnectingToInternet()){
			getAgentList();
		}else{
			skDetector.showErrorMessage();
		}
	}
	
	private AgentListViewAdapter.Callback depositCallback = new AgentListViewAdapter.Callback() {
		
		public void setDepositAmout(int position) {
			// TODO Auto-generated method stub
			startActivity(new Intent(getApplicationContext(), AgentDepositActivity.class).putExtra("agent", new Gson().toJson(agentList.get(position))));
		}

		public void setViewCredit(int postion) {
			// TODO Auto-generated method stub
			Bundle bundle = new Bundle();
			bundle.putString("agent_id", agentList.get(postion).getId().toString());
			bundle.putString("deposit", agentList.get(postion).getDepositBalance().toString());
			startActivity(new Intent(getApplicationContext(), CreditListActivity.class).putExtras(bundle));
		}

		public void setPaymentHistory(int positon) {
			// TODO Auto-generated method stub
			Bundle bundle = new Bundle();
			bundle.putString("agent_id", agentList.get(positon).getId().toString());
			startActivity(new Intent(getApplicationContext(), PayHistoryActivity.class).putExtras(bundle));
		}

		public void setView(int position) {
			// TODO Auto-generated method stub
			startActivity(new Intent(getApplicationContext(), AgentDetailActivity.class).putExtra("agent", new Gson().toJson(agentList.get(position))));
		}
	};
	private ProgressDialog dialog;
	private String selectedOperatorId;
	private EditText editsearch;
	
	private void getAgentList(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
        dialog.show();
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);
		NetworkEngine.getInstance().getAgentList(accessToken, selectedOperatorId, new Callback<List<Agents>>() {
			
			public void success(List<Agents> arg0, Response arg1) {
				// TODO Auto-generated method stub
				agentList = arg0;
				agentAdapter = new AgentListViewAdapter(AgentListActivity.this, agentList);
				agentAdapter.setOnCallbackListener(depositCallback);
				lst_agent.setAdapter(agentAdapter);
				dialog.dismiss();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
	}
	
	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// TODO Auto-generated method stub
		getSupportMenuInflater().inflate(R.menu.activity_main, menu);

		// Locate the EditText in menu.xml
        editsearch = (EditText) menu.findItem(R.id.menu_search).getActionView();
 
        // Capture Text in EditText
        editsearch.addTextChangedListener(textWatcher);
 
        // Show the search menu item in menu.xml
        MenuItem menuSearch = menu.findItem(R.id.menu_search);
 
        menuSearch.setOnActionExpandListener(new OnActionExpandListener() {
 
            // Menu Action Collapse
            public boolean onMenuItemActionCollapse(MenuItem item) {
                // Empty EditText to remove text filtering
                editsearch.setText("");
                editsearch.clearFocus();
                InputMethodManager imm = (InputMethodManager)getSystemService(Context.INPUT_METHOD_SERVICE);
                imm.hideSoftInputFromWindow(editsearch.getWindowToken(), 0);
                return true;
            }
 
            // Menu Action Expand
            public boolean onMenuItemActionExpand(MenuItem item) {
                // Focus on EditText
                editsearch.requestFocus();
 
                // Force the keyboard to show on EditText focus
                InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
                imm.toggleSoftInput(InputMethodManager.SHOW_FORCED, 0);
                return true;
            }
        });
		return super.onCreateOptionsMenu(menu);
	}
	
	 // EditText TextWatcher
    private TextWatcher textWatcher = new TextWatcher() {
 
        public void afterTextChanged(Editable s) {
            // TODO Auto-generated method stub
            String text = editsearch.getText().toString().toLowerCase(Locale.getDefault());
            Log.i("","Hello Searching Text"+ text);
            agentAdapter.filter(text);
        }
 
        public void beforeTextChanged(CharSequence arg0, int arg1, int arg2,
                int arg3) {
            // TODO Auto-generated method stub
 
        }
 
        public void onTextChanged(CharSequence arg0, int arg1, int arg2,
                int arg3) {
            // TODO Auto-generated method stub
 
        }
 
    };
	
}
