package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import com.ignite.application.SaveFileDialog;
import com.ignite.application.SeatbyAgentExcelUtility;
import com.ignite.application.SeatbyBusExcelUtility;
import com.ignite.application.SeatbyBusPDFUtility;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.SeatDetailsbyBusIDListAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.SeatbyBus;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

public class SeatDetailsbyTodayBusActivity extends BaseSherlockActivity{
	private ProgressDialog dialog;
	public static final int MESSAGE_STATE_CHANGE = 1;
	public static final int MESSAGE_READ = 2;
	public static final int MESSAGE_WRITE = 3;
	public static ListView lvSeatDetails;
	private Button Print;
	private TextView txt_seat_no_label;
	private TextView txt_buyer_no_label;
	private TextView txt_seller_no_label;
	private TextView txt_price_label;
	private TextView txt_vouncher_no_label;
	private SKConnectionDetector skDetector;
	private String selectedBusID;
	private List<SeatbyBus> seatsbyTrips;
	private TextView txt_commission_label;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getActionBar().setDisplayHomeAsUpEnabled(true);
		getActionBar().setIcon(R.drawable.ic_home);
		setContentView(R.layout.activity_today_seat_list);
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
		
		Bundle bundle = getIntent().getExtras();
		selectedBusID = bundle.getString("bus_id");
		
		//bluetoothPrint();
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
		NetworkEngine.getInstance().getSeatbyBusID(accessToken, selectedBusID, new Callback<List<SeatbyBus>>() {
			
			public void success(List<SeatbyBus> arg0, Response arg1) {
				// TODO Auto-generated method stub
				seatsbyTrips = new ArrayList<SeatbyBus>();
				seatsbyTrips = arg0;
				lvSeatDetails.setAdapter(new SeatDetailsbyBusIDListAdapter(SeatDetailsbyTodayBusActivity.this, seatsbyTrips));
				dialog.cancel();
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.cancel();
			}
		});
	}
	

	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == Print)
			{
		        SaveFileDialog fileDialog = new SaveFileDialog(SeatDetailsbyTodayBusActivity.this);
		        fileDialog.setCallbackListener(new SaveFileDialog.Callback() {					
					public void onCancel() {
						// TODO Auto-generated method stub
					}

					public void onSave(String filename, boolean PDFChecked,
							boolean ExcelChecked) {
						// TODO Auto-generated method stub
						// TODO Auto-generated method stub
						if(PDFChecked){
							new SeatbyBusPDFUtility(seatsbyTrips).createPdf(filename);
						}
						if(ExcelChecked){
							new SeatbyBusExcelUtility(seatsbyTrips, filename).write();
						}
						SKToastMessage.showMessage(SeatDetailsbyTodayBusActivity.this, "သင္၏ Report PDF ဖုိင္ အာ External SD Card လမ္းေၾကာင္းသို႕  ထုတ္ ျပီးျပီး ျဖစ္ ပါသည္ ", SKToastMessage.SUCCESS);
					}
				});
		        fileDialog.show();
				return;
				
			}
		}
	};
}
