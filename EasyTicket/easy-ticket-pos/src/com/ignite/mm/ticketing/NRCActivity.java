package com.ignite.mm.ticketing;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.text.InputType;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.LinearLayout.LayoutParams;
import android.widget.RadioButton;
import android.widget.Spinner;
import android.widget.TextView;
import com.actionbarsherlock.app.ActionBar;
import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.mm.ticketing.application.BaseSherlockActivity;
import com.ignite.mm.ticketing.application.DeviceUtil;
import com.ignite.mm.ticketing.clientapi.NetworkEngine;
import com.ignite.mm.ticketing.custom.listview.adapter.AgentListAdapter;
import com.ignite.mm.ticketing.http.connection.HttpConnection;
import com.ignite.mm.ticketing.sqlite.database.model.Agent;
import com.ignite.mm.ticketing.sqlite.database.model.AgentList;
import com.ignite.mm.ticketing.sqlite.database.model.ConfirmSeat;
import com.smk.custom.view.CustomTextView;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class NRCActivity extends BaseSherlockActivity {

	private ActionBar actionBar;
	private TextView actionBarTitle;
	private ImageButton actionBarBack;
	private Button btnsubmit;
	private EditText edt_buyer;
	private AutoCompleteTextView edt_nrc_no;
	private EditText edt_phone;
	private ProgressDialog dialog;
	private String SaleOrderNo;
	private String Trip;
	private String Date;
	private String OperatorID;
	private String OperatorName;
	private String Time;
	private String SelectedSeatIndex;
	private String[] selectedSeat;
	private LinearLayout layout_ticket_no_container;
	private String AgentID = "0";
	private List<Agent> agents;
	private TextView txt_agent;
	private RadioButton rdo_cash_down;
	private RadioButton rdo_credit;
	private AutoCompleteTextView auto_txt_agent;
	private List<Agent> agentList;
	private ArrayAdapter<Agent> agentListAdapter;
	private EditText edt_ref_invoice_no;
	private RadioButton rdo_local;
	private List<String> nrcFormat;
	private ArrayAdapter<String> nrcListAdapter;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.nrc_activity);

		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.action_bar);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.action_bar_title);
		actionBarBack = (ImageButton) actionBar.getCustomView().findViewById(
				R.id.action_bar_back);
		actionBarBack.setOnClickListener(clickListener);
		actionBarTitle.setText("BUS");
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);

		Bundle bundle = getIntent().getExtras();
		Trip = bundle.getString("trip");
		Date = bundle.getString("trip_date");
		OperatorID = bundle.getString("operator_id");
		OperatorName = bundle.getString("operator_name");
		Time = bundle.getString("trip_time");
		SelectedSeatIndex = bundle.getString("selected_seat");
		SaleOrderNo = bundle.getString("sale_order_no");
		
		edt_buyer = (EditText) findViewById(R.id.edt_buyer);
		edt_nrc_no = (AutoCompleteTextView) findViewById(R.id.edt_nrc_no);
		edt_phone = (EditText) findViewById(R.id.edt_phone);
		txt_agent = (CustomTextView) findViewById(R.id.txt_seller);
		edt_ref_invoice_no = (EditText) findViewById(R.id.edt_ref_invoice_no);
		rdo_cash_down = (RadioButton) findViewById(R.id.rdo_cash_down);
		rdo_credit = (RadioButton) findViewById(R.id.rdo_credit);
		rdo_local = (RadioButton) findViewById(R.id.rdo_local);
		nrcFormat = new ArrayList<String>();
		nrcFormat.add("1/MaAhaPa(N) 000000");
		nrcFormat.add("2/MaAhaPa(N) 000000");
		nrcFormat.add("3/MaAhaPa(N) 000000");
		nrcFormat.add("4/MaAhaPa(N) 000000");
		nrcFormat.add("5/MaAhaPa(N) 000000");
		nrcFormat.add("6/MaAhaPa(N) 000000");
		nrcFormat.add("7/MaAhaPa(N) 000000");
		nrcFormat.add("8/MaAhaPa(N) 000000");
		nrcFormat.add("9/MaAhaPa(N) 000000");
		nrcFormat.add("10/MaAhaPa(N) 000000");
		nrcFormat.add("11/MaAhaPa(N) 000000");
		nrcFormat.add("12/MaAhaPa(N) 000000");
		nrcFormat.add("13/MaAhaPa(N) 000000");
		nrcFormat.add("14/MaAhaPa(N) 000000");
		
		nrcListAdapter = new ArrayAdapter<String>(this, android.R.layout.simple_dropdown_item_1line, nrcFormat);
		edt_nrc_no.setAdapter(nrcListAdapter);
		
		auto_txt_agent = (AutoCompleteTextView) findViewById(R.id.txt_agent);
	    agentList = new ArrayList<Agent>();
		
		layout_ticket_no_container = (LinearLayout) findViewById(R.id.ticket_no_container);
		
		btnsubmit = (Button) findViewById(R.id.btnSubmit);
		SKConnectionDetector skDetector = new SKConnectionDetector(this);
		skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
		if(skDetector.isConnectingToInternet()){
			SharedPreferences pref = this.getApplicationContext().getSharedPreferences("User", Activity.MODE_PRIVATE);
			String user_type = pref.getString("user_type", null);
			if(user_type.equals("operator")){
				getAgent();
			}else{
				txt_agent.setVisibility(View.GONE);
				auto_txt_agent.setVisibility(View.GONE);
			}
		}else{
			skDetector.showErrorMessage();
		}
		btnsubmit.setOnClickListener(clickListener);
		
		LayoutParams lps = new LinearLayout.LayoutParams(LayoutParams.MATCH_PARENT, LayoutParams.WRAP_CONTENT);
		lps.setMargins(0, 10, 0, 0);
		selectedSeat = SelectedSeatIndex.split(",");
		for (int i = 0; i < selectedSeat.length; i++) {
			CustomTextView label = new CustomTextView(this);
			label.setText("လက္မွတ္ နံပါတ္ "+(i+1));
			label.setTextSize(18f);
			layout_ticket_no_container.addView(label,lps);
			EditText ticket_no = new EditText(this);
			ticket_no.setInputType(InputType.TYPE_CLASS_TEXT);
			//ticket_no.setKeyListener(DigitsKeyListener.getInstance());
			ticket_no.setId(i+1);
			ticket_no.setSingleLine(true);
			layout_ticket_no_container.addView(ticket_no,lps);
		}
		
	}

	private void comfirmOrder() {
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		List<ConfirmSeat> seats = new ArrayList<ConfirmSeat>();
		
		for (int i = 0; i < selectedSeat.length; i++) {
			EditText ticket_no = (EditText)findViewById(i+1);
			seats.add(
					new ConfirmSeat(BusSelectSeatActivity.BusSeats.get(0)
							.getSeat_plan().get(0).getId(),
							BusSelectSeatActivity.BusSeats.get(0)
									.getSeat_plan().get(0).getSeat_list()
									.get(Integer.valueOf(selectedSeat[i]))
									.getSeat_no().toString(), edt_buyer
							.getText().toString(), edt_nrc_no.getText()
							.toString(),ticket_no.getText().toString()));
		}
		
		SharedPreferences pref_old_sale = this.getApplicationContext().getSharedPreferences("old_sale", Activity.MODE_PRIVATE);
		String working_date = pref_old_sale.getString("working_date", null);
				
		SharedPreferences pref = this.getApplicationContext().getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String user_id = pref.getString("user_id", null);
		String user_type = pref.getString("user_type", null);
		if(user_type.equals("agent")){
			AgentID = user_id;
		}
		
		List<NameValuePair> params = new ArrayList<NameValuePair>();
		params.add(new BasicNameValuePair("buyer_name", edt_buyer.getText().toString()));
		params.add(new BasicNameValuePair("phone", edt_phone.getText().toString()));
		params.add(new BasicNameValuePair("nrc_no", edt_nrc_no.getText().toString()));
		params.add(new BasicNameValuePair("agent_name", auto_txt_agent.getText().toString()));
		params.add(new BasicNameValuePair("agent_id", AgentID));
		params.add(new BasicNameValuePair("sale_order_no", SaleOrderNo));
		params.add(new BasicNameValuePair("tickets", seats.toString()));
		params.add(new BasicNameValuePair("cash_credit", rdo_cash_down.isChecked() == true ? "1" : "2"));
		params.add(new BasicNameValuePair("nationality", rdo_local.isChecked() == true ? "local" : "foreign"));
		params.add(new BasicNameValuePair("reference_no", edt_ref_invoice_no.getText().toString()));
		params.add(new BasicNameValuePair("order_date",working_date));
		params.add(new BasicNameValuePair("device_id",DeviceUtil.getInstance(this).getID()));
		params.add(new BasicNameValuePair("access_token", accessToken));
		Log.i("","Hello Params :"+ params.toString());
		final Handler handler = new Handler() {

			public void handleMessage(Message msg) {

				String jsonData = msg.getData().getString("data");
				try {
					Log.i("","Hello Response :"+ jsonData);
					JSONObject jsonObj = new JSONObject(jsonData);
					if(!jsonObj.getBoolean("status") && jsonObj.getString("device_id").equals(DeviceUtil.getInstance(NRCActivity.this).getID())){
						SKToastMessage.showMessage(NRCActivity.this, "သင္ မွာယူေသာ လက္ မွတ္ မ်ားမွာ စကၠန့္ပိုင္ အတြင္း တစ္ ျခားသူ ယူ သြားေသာေၾကာင့္ သင္မွာေသာလက္မွတ္မ်ား မရႏိုင္ေတာ့ပါ။ ေက်းဇူးျပဳၿပီး တစ္ျခားလက္ မွတ္ မ်ား ျပန္ေရႊးေပးပါ။။", SKToastMessage.ERROR);
						dialog.dismiss();
					}else{
						SKToastMessage.showMessage(NRCActivity.this, "လက္မွတ္ ျဖတ္ျပီးျပီး ျဖစ္ပါသည္။", SKToastMessage.SUCCESS);
						closeAllActivities();
						startActivity(new Intent(NRCActivity.this, BusTripsCityActivity.class));
						dialog.dismiss();
					}
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
		};
		HttpConnection lt = new HttpConnection(handler, "POST",
				"http://192.168.1.125/sale/comfirm", params);
		lt.execute();
	}
	
	public boolean checkFields()
    {
    	if(edt_buyer.getText().toString().length() == 0){
    		edt_buyer.setError("Enter The Buyer Name.");
    		return false;
    	}
    	
    	if(edt_phone.getText().toString().length() == 0 && edt_phone.getText().toString().length() < 8)
    	{
    		edt_phone.setError("Enter The Phone.");
			return false;
		}
    	
    	return true;
	   
	
   }
	private OnClickListener clickListener = new OnClickListener() {

		public void onClick(View v) {
			if (v == actionBarBack) {
				onBackPressed();
			}

			if (v == btnsubmit) {
				if(checkFields()){
					if(SKConnectionDetector.getInstance(NRCActivity.this).isConnectingToInternet()){
						comfirmOrder();
					}else{
						// FADE DATA
						SharedPreferences sharedPreferences = getApplication()
								.getSharedPreferences("CheckOutInfo", MODE_PRIVATE);
						SharedPreferences.Editor editor = sharedPreferences.edit();

						editor.clear();
						editor.commit();
						editor.putString("buyer_name", edt_buyer.getText().toString());
						editor.putString("trip", Trip);
						editor.putString("trip_date", Date);
						editor.putString("operator_id", OperatorID);
						editor.putString("operator_name", OperatorName);
						editor.putString("time", Time);
						editor.putString("selected_seat", SelectedSeatIndex);

						editor.commit();

						finish();
						startActivity(new Intent(NRCActivity.this, Bus_Info_Activity.class));
					}
				}
			}
		}
	};
	
	private void getAgent(){
		SharedPreferences pref = this.getApplicationContext().getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String user_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getAllAgent(accessToken,user_id, new Callback<AgentList>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}

			public void success(AgentList arg0, Response arg1) {
				// TODO Auto-generated method stub
				agentList = arg0.getAgents();
				agentListAdapter = new ArrayAdapter<Agent>(NRCActivity.this, android.R.layout.simple_dropdown_item_1line, agentList);
			    auto_txt_agent.setAdapter(agentListAdapter);
			    auto_txt_agent.setOnItemClickListener(new OnItemClickListener() {

					public void onItemClick(AdapterView<?> arg0, View arg1,
							int arg2, long arg3) {
						// TODO Auto-generated method stub
						Log.i("", "Hello Selected Agent ID = "+ ((Agent)arg0.getAdapter().getItem(arg2)).getId());
			           	AgentID = ((Agent)arg0.getAdapter().getItem(arg2)).getId();
					}
				});
			}
		});
	}
	
	@Override
	public void onBackPressed() {
		// TODO Auto-generated method stub
		AlertDialog.Builder alertDialog = new AlertDialog.Builder(this);
		alertDialog.setMessage("Are you sure to exit?");
	
		alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
			
			public void onClick(DialogInterface dialog, int which) {
				// TODO Auto-generated method stub
				if(SKConnectionDetector.getInstance(NRCActivity.this).isConnectingToInternet()){
					SharedPreferences pref = getApplicationContext()
							.getSharedPreferences("User", Activity.MODE_PRIVATE);
					String accessToken = pref.getString("access_token", null);
					Log.i("","Hello OrderNo: "+ SaleOrderNo);
					NetworkEngine.getInstance().deleteSaleOrder( accessToken, SaleOrderNo, new Callback<JSONObject>() {
						
						public void success(JSONObject arg0, Response arg1) {
							// TODO Auto-generated method stub
							Log.i("","Hello Response: "+ arg0.toString());
						}
						
						public void failure(RetrofitError arg0) {
							// TODO Auto-generated method stub
						}
					});
				}				
				finish();
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
		
		//super.onBackPressed();
		
	}
}
