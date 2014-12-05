package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import com.ignite.application.SaveFileDialog;
import com.ignite.application.SeatbyTripExcelUtility;
import com.ignite.application.SeatbyTripPDFUtility;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.SeatDetailsAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.SeatsbyTrip;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

public class SeatDetailsbyBusnoActivity extends BaseSherlockActivity{
	private ProgressDialog dialog;
	public static final int MESSAGE_STATE_CHANGE = 1;
	public static final int MESSAGE_READ = 2;
	public static final int MESSAGE_WRITE = 3;
	public static ListView lvSeatDetails;
	private Button Print;
	private String agent_id;
	private String operator_id;
	private String from;
	private String to;
	private String time;
	private String date;
	private String bus_id;
	private TextView txt_seat_no_label;
	private TextView txt_buyer_no_label;
	private TextView txt_seller_no_label;
	private TextView txt_price_label;
	private TextView txt_vouncher_no_label;
	private SKConnectionDetector skDetector;
	private String filter;
	private TextView txt_commission_label;
	private static List<SeatsbyTrip> seatsbyTrips;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getActionBar().setDisplayHomeAsUpEnabled(true);
		getActionBar().setIcon(R.drawable.ic_home);
		setContentView(R.layout.activity_seat_details_list);
		
		SharedPreferences pref = getApplicationContext()
				.getSharedPreferences("SearchbyOperator", MODE_PRIVATE);
		
		filter = pref.getString("agent_name", "").length() != 0 ? pref.getString("agent_name", "")+" - " : "";
		filter += pref.getString("from_city_name", "").length() != 0 ? pref.getString("from_city_name", "")+" - " : "";
		filter += pref.getString("to_city_name", "").length() != 0 ? pref.getString("to_city_name", "")+" - " : "";
		filter += pref.getString("from_date", "").length() != 0 ? pref.getString("from_date", "")+" - " : "";
		filter += pref.getString("to_date", "").length() != 0 ? pref.getString("to_date", "")+" - " : "";
		filter += pref.getString("time", "").length() != 0 ? pref.getString("time", "")+" - " : "";
		getSupportActionBar().setTitle(filter);
		
		Print = (Button)findViewById(R.id.btnPrint);
		txt_seat_no_label = (TextView) findViewById(R.id.txt_seat_no);
		txt_buyer_no_label = (TextView) findViewById(R.id.txt_buyer);
		txt_seller_no_label = (TextView) findViewById(R.id.txt_seller);
		txt_price_label = (TextView) findViewById(R.id.txt_price);
		txt_commission_label = (TextView) findViewById(R.id.txt_commission);
		txt_vouncher_no_label = (TextView) findViewById(R.id.txt_vouncher_no);
		
		txt_seat_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 6;
		txt_buyer_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 6;
		txt_seller_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 6;
		txt_price_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 6;
		txt_commission_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 6;
		txt_vouncher_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 6;
		
		lvSeatDetails = (ListView) findViewById(R.id.lvseat_details_list);
		
		agent_id = pref.getString("agent_id", null);
		operator_id = pref.getString("operator_id", null);
		from = pref.getString("from_city", null);
		to = pref.getString("to_city", null);
		time = pref.getString("time", null);
		date = pref.getString("date", null);
		bus_id = pref.getString("bus_id", null);
		
		Print.setOnClickListener(clickListener);
		
		skDetector = SKConnectionDetector.getInstance(this);
		if(skDetector.isConnectingToInternet()){
			getReport();
		}else{
			skDetector.showErrorMessage();
		}
	}

	private void getReport() {
		// TODO Auto-generated method stub
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		NetworkEngine.getInstance().getSeatbyTrip(accessToken, operator_id, agent_id, from, to, time, date, bus_id, new Callback<List<SeatsbyTrip>>() {
			
			public void success(List<SeatsbyTrip> arg0, Response arg1) {
				// TODO Auto-generated method stub
				seatsbyTrips = new ArrayList<SeatsbyTrip>();
				seatsbyTrips = arg0;
				lvSeatDetails.setAdapter(new SeatDetailsAdapter(SeatDetailsbyBusnoActivity.this, seatsbyTrips));
				dialog.cancel();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.cancel();
				Log.i("","Hello Error Response Code: "+arg0.getResponse().getStatus());
			}
		});
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == Print)
			{
				SaveFileDialog fileDialog = new SaveFileDialog(SeatDetailsbyBusnoActivity.this);
		        fileDialog.setCallbackListener(new SaveFileDialog.Callback() {
					
					public void onCancel() {
						// TODO Auto-generated method stub
					}

					public void onSave(String filename, boolean PDFChecked,
							boolean ExcelChecked) {
						// TODO Auto-generated method stub
						if(PDFChecked){
							new SeatbyTripPDFUtility(seatsbyTrips).createPdf(filename);
						}
						if(ExcelChecked){
							new SeatbyTripExcelUtility(seatsbyTrips, filename).write();
						}
						SKToastMessage.showMessage(SeatDetailsbyBusnoActivity.this, "သင္၏ Report PDF ဖုိင္ အာ External SD Card လမ္းေၾကာင္းသို႕  ထုတ္ ျပီးျပီး ျဖစ္ ပါသည္ ", SKToastMessage.SUCCESS);
					}
				});
		        fileDialog.show();
		        return;
				
			}
		}
	};
}
