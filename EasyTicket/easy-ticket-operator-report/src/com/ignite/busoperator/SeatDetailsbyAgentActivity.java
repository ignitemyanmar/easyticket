package com.ignite.busoperator;

import java.io.File;
import java.io.FileOutputStream;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import com.ignite.application.SaveFileDialog;
import com.ignite.application.SeatbyAgentExcelUtility;
import com.ignite.application.SeatbyAgentPDFUtility;
import com.ignite.application.SeatbyTripPDFUtility;
import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.SeatDetailsbyAgentAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.SeatReport;
import com.itextpdf.text.Document;
import com.itextpdf.text.PageSize;
import com.itextpdf.text.pdf.PdfPTable;
import com.itextpdf.text.pdf.PdfWriter;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;
import android.app.Activity;
import android.app.ProgressDialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Environment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ListView;
import android.widget.TextView;

public class SeatDetailsbyAgentActivity extends BaseSherlockActivity{
	private ProgressDialog dialog;
	public static final int MESSAGE_READ = 2;
	public static final int MESSAGE_WRITE = 3;
	
	
	public static ListView lvSeatDetails;
	private Button Print;
	private String agent_id;
	private String bus_id;
	private TextView txt_seat_no_label;
	private TextView txt_buyer_no_label;
	private TextView txt_price_label;
	private TextView txt_vouncher_no_label;
	private SKConnectionDetector skDetector;
	private List<SeatReport> seatReports;
	private String filter;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getActionBar().setDisplayHomeAsUpEnabled(true);
		getActionBar().setIcon(R.drawable.ic_home);
		setContentView(R.layout.activity_agent_seat_list);
		
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
		View title_view = LayoutInflater.from(this).inflate(R.layout.title_by_seat_detail,null,false);
		txt_seat_no_label = (TextView) title_view.findViewById(R.id.txt_seat_no);
		txt_buyer_no_label = (TextView) title_view.findViewById(R.id.txt_buyer);
		txt_price_label = (TextView) title_view.findViewById(R.id.txt_price);
		txt_vouncher_no_label = (TextView) title_view.findViewById(R.id.txt_ticket_no);
		
		txt_seat_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 4;
		txt_buyer_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 4;
		txt_price_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 4;
		txt_vouncher_no_label.getLayoutParams().width = DeviceUtil.getInstance(this).getWidth() / 4;
		
		lvSeatDetails = (ListView) findViewById(R.id.lvseat_details_list);
		lvSeatDetails.addHeaderView(title_view);
		
		
		agent_id = pref.getString("agent_id", null);
		bus_id = pref.getString("bus_occ_id", null);
		
		Log.i("","hello agent_id = "+ agent_id+" bus_id = "+bus_id);
		
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
		NetworkEngine.getInstance().getSeatReport(accessToken, bus_id, agent_id, new Callback<List<SeatReport>>() {
			
			public void success(List<SeatReport> arg0, Response arg1) {
				// TODO Auto-generated method stub
				seatReports = new ArrayList<SeatReport>();
				seatReports = arg0;
				lvSeatDetails.setAdapter(new SeatDetailsbyAgentAdapter(SeatDetailsbyAgentActivity.this, seatReports));
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
				SaveFileDialog fileDialog = new SaveFileDialog(SeatDetailsbyAgentActivity.this);
		        fileDialog.setCallbackListener(new SaveFileDialog.Callback() {
					
					public void onCancel() {
						// TODO Auto-generated method stub
					}

					public void onSave(String filename, boolean PDFChecked,
							boolean ExcelChecked) {
						// TODO Auto-generated method stub
						if(PDFChecked){
							new SeatbyAgentPDFUtility(seatReports).createPdf(filename);
						}
						if(ExcelChecked){
							new SeatbyAgentExcelUtility(seatReports, filename).write();
						}
						SKToastMessage.showMessage(SeatDetailsbyAgentActivity.this, "သင္၏ Report PDF ဖုိင္ အာ External SD Card လမ္းေၾကာင္းသို႕  ထုတ္ ျပီးျပီး ျဖစ္ ပါသည္ ", SKToastMessage.SUCCESS);
					}
				});
		        fileDialog.show();
		        return;
				
			}
		}
	};

}
