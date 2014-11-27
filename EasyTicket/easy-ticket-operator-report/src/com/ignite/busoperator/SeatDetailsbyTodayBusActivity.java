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
	private SKConnectionDetector skDetector;
	private String selectedBusID;
	private List<SeatbyBus> seatsbyTrips;
	private TextView txt_date;
	private TextView txt_trip;
	private TextView txt_time;
	private TextView txt_classes;
	private TextView txt_agent;
	private TextView txt_seat_no;
	private TextView txt_qty;
	private TextView txt_price;
	private TextView txt_commission;
	private TextView txt_total_amount;
	private TextView txt_header_trip;
	private TextView txt_header_datetime;
	private String selectedFromTo;
	private String selectedDateTime;
	private TextView txt_grand_total_amount;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getSupportActionBar().setTitle("ေန႕စဥ္ အ ေရာင္း စာရင္း မ်ား");
		setContentView(R.layout.activity_today_seat_list);
		Print = (Button)findViewById(R.id.btnPrint);
		txt_header_trip = (TextView) findViewById(R.id.txt_header_trip);
		txt_header_datetime = (TextView) findViewById(R.id.txt_header_datetime);
		
		txt_date = (TextView) findViewById(R.id.txt_date);
		txt_trip = (TextView) findViewById(R.id.txt_trip);
		txt_time = (TextView) findViewById(R.id.txt_time);
		txt_classes = (TextView) findViewById(R.id.txt_classes);
		txt_agent = (TextView) findViewById(R.id.txt_agent);
		txt_seat_no = (TextView) findViewById(R.id.txt_seat_no);
		txt_qty = (TextView) findViewById(R.id.txt_qty);
		txt_price = (TextView) findViewById(R.id.txt_price);
		txt_commission = (TextView) findViewById(R.id.txt_commission);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		
		txt_grand_total_amount = (TextView) findViewById(R.id.txt_grand_total_amount);
		
		txt_date.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_trip.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_time.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_classes.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_agent.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_seat_no.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_qty.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_price.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_commission.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		txt_total_amount.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 10;
		
		lvSeatDetails = (ListView) findViewById(R.id.lvseat_details_list);
		
		Bundle bundle = getIntent().getExtras();
		selectedBusID = bundle.getString("bus_id");
		selectedFromTo = bundle.getString("from_to");
		selectedDateTime = bundle.getString("date_time");
		
		txt_header_trip.setText("ခရီးစဥ္ : "+selectedFromTo);
		txt_header_datetime.setText("ေန႕ရက္/အခ်ိန္ : "+selectedDateTime);
		
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
		NetworkEngine.getInstance().getSeatbyBusID(AppLoginUser.getAccessToken(),AppLoginUser.getUserID(),selectedBusID, new Callback<List<SeatbyBus>>() {
			
			public void success(List<SeatbyBus> arg0, Response arg1) {
				// TODO Auto-generated method stub
				seatsbyTrips = new ArrayList<SeatbyBus>();
				seatsbyTrips = arg0;
				lvSeatDetails.setAdapter(new SeatDetailsbyBusIDListAdapter(SeatDetailsbyTodayBusActivity.this, seatsbyTrips));
				dialog.cancel();
				Integer grand_total = 0;
				for(SeatbyBus seatbyBus: seatsbyTrips){
					grand_total += seatbyBus.getTotalAmount();
				}
				txt_grand_total_amount.setText(String.format("%,d", grand_total)+" Kyats");
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				if(arg0.getResponse() != null){
					Log.e("","Hello Error: "+ arg0.getMessage());
				}
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
