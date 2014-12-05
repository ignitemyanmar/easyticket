package com.ignite.busoperator;

import java.util.ArrayList;
import java.util.List;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
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

import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.application.DetailReportExcelUtility;
import com.ignite.application.DetailReportPDFUtility;
import com.ignite.application.SaveFileDialog;
import com.ignite.application.SeatbyAgentExcelUtility;
import com.ignite.application.SeatbyAgentPDFUtility;
import com.ignite.busoperator.adapter.ReportDetailListViewAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.CreditList;
import com.ignite.busoperator.model.SaleDetail;
import com.ignite.busoperator.model.SaleTicket;
import com.ignite.busoperator.model.Saleitem;
import com.ignite.busoperator.model.TripsbyOperator;
import com.smk.skalertmessage.SKToastMessage;

public class ReportsActivity extends BaseSherlockActivity {
	private TextView txt_invoice_no;
	private TextView txt_customer;
	private TextView txt_seat_no;
	private TextView txt_trip_date;
	private TextView txt_trip;
	private TextView txt_price;
	private TextView txt_amount;
	private TextView txt_time;
	private TextView txt_qty;
	private ListView lst_report;
	private ProgressDialog dialog;
	private String selectedOperatorId;
	private String selectedAgentId;
	private String selectedCityfromId;
	private String selectedCitytoId;
	private String selectedTimeid;
	private String selectedFromDate;
	private String selectedToDate;
	private TextView txt_total_amount;
	private String filter;
	private Button Print;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_detail_report);
		
		SharedPreferences pref = getSharedPreferences("Filter",MODE_PRIVATE);
		filter = pref.getString("agent_name", "").length() != 0 ? pref.getString("agent_name", "")+" - " : "";
		filter += pref.getString("from_city_name", "").length() != 0 ? pref.getString("from_city_name", "")+" - " : "";
		filter += pref.getString("to_city_name", "").length() != 0 ? pref.getString("to_city_name", "")+" - " : "";
		filter += pref.getString("from_date", "").length() != 0 ? pref.getString("from_date", "")+" - " : "";
		filter += pref.getString("to_date", "").length() != 0 ? pref.getString("to_date", "")+" - " : "";
		filter += pref.getString("time", "").length() != 0 ? pref.getString("time", "")+" - " : "";
		getSupportActionBar().setTitle(filter);

		txt_invoice_no = (TextView) findViewById(R.id.txt_invoice_no);
		txt_customer = (TextView) findViewById(R.id.txt_customer);
		txt_seat_no = (TextView) findViewById(R.id.txt_seat_no);
		txt_time = (TextView) findViewById(R.id.txt_time);
		txt_trip_date = (TextView) findViewById(R.id.txt_trip_date);
		txt_trip = (TextView) findViewById(R.id.txt_trip);
		txt_price = (TextView) findViewById(R.id.txt_price);
		txt_qty = (TextView) findViewById(R.id.txt_qty);
		txt_amount = (TextView) findViewById(R.id.txt_amount);
		txt_total_amount = (TextView) findViewById(R.id.txt_total_amount);
		lst_report = (ListView) findViewById(R.id.lst_reports);
		Print = (Button)findViewById(R.id.btnPrint);
		Print.setOnClickListener(clickListener);

		txt_invoice_no.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_customer.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_seat_no.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_time.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_trip_date.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_trip.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_price.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_qty.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		txt_amount.getLayoutParams().width = DeviceUtil.getInstance(this)
				.getWidth() / 9;
		getReportDetail();
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			SaveFileDialog fileDialog = new SaveFileDialog(ReportsActivity.this);
	        fileDialog.setCallbackListener(new SaveFileDialog.Callback() {
				
				public void onCancel() {
					// TODO Auto-generated method stub
				}

				public void onSave(String filename, boolean PDFChecked,
						boolean ExcelChecked) {
					// TODO Auto-generated method stub
					if(PDFChecked){
						new DetailReportPDFUtility(saleDetail).createPdf(filename);
					}
					if(ExcelChecked){
						new DetailReportExcelUtility(saleDetail, filename).write();
					}
					SKToastMessage.showMessage(ReportsActivity.this, "သင္၏ Report PDF ဖုိင္ အာ External SD Card လမ္းေၾကာင္းသို႕  ထုတ္ ျပီးျပီး ျဖစ္ ပါသည္ ", SKToastMessage.SUCCESS);
				}
			});
	        fileDialog.show();
		}
	};
	protected List<SaleDetail> saleDetail;

	private void getReportDetail() {
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		SharedPreferences pref = this.getSharedPreferences("User",
				Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		selectedOperatorId = pref.getString("user_id", null);

		SharedPreferences prefFilter = this.getSharedPreferences("Filter",
				Activity.MODE_PRIVATE);
		selectedAgentId = prefFilter.getString("agent_id", "");
		selectedCityfromId = prefFilter.getString("from_city", "");
		selectedCitytoId = prefFilter.getString("to_city", "");
		selectedTimeid = prefFilter.getString("time", "");
		selectedFromDate = prefFilter.getString("from_date", "");
		selectedToDate = prefFilter.getString("to_date", "");
		Log.i("", "Sale Seat : " + selectedAgentId + "," + selectedCityfromId
				+ "," + selectedCitytoId + "," + selectedTimeid + ","
				+ selectedFromDate + "," + selectedToDate);
		NetworkEngine.getInstance().getReportDetail(accessToken,
				selectedOperatorId, selectedAgentId, selectedCityfromId,
				selectedCitytoId, selectedTimeid, selectedFromDate,
				selectedToDate, "", new Callback<List<SaleDetail>>() {

					private Integer totalAmout = 0;

					public void success(List<SaleDetail> arg0, Response arg1) {
						// TODO Auto-generated method stub
						saleDetail = arg0;
						
						for (SaleDetail saleDetail : arg0) {
							totalAmout += saleDetail.getAmount();
						}
						txt_total_amount.setText(totalAmout + " Kyats");
						List<SaleTicket> saleTickets = new ArrayList<SaleTicket>();
						for (int i = 0; i < arg0.size(); i++) {
							
							for (Saleitem saleitem : arg0.get(i).getSaleitems()) {
								Integer found_position = checkTicketNo(saleTickets, saleitem);
								if(found_position != null){
									saleTickets.get(found_position).setSeatNo(saleTickets.get(found_position).getSeatNo()+", "+saleitem.getSeatNo());
									saleTickets.get(found_position).setQty(saleTickets.get(found_position).getQty() + 1);
									saleTickets.get(found_position).setTotalAmount(saleTickets.get(found_position).getTotalAmount() + arg0.get(i).getPrice());
								}else{
									saleTickets.add(new SaleTicket(saleitem.getOrderId(),saleitem
											.getTicketNo(), saleitem.getName(),
											saleitem.getSeatNo(), arg0.get(i)
													.getDepartureTime(), arg0
													.get(i).getDepartureDate(),
											arg0.get(i).getTrip(),
											arg0.get(i).getPrice(),arg0.get(i).getCommission(), 1,
											arg0.get(i).getPrice()));
								}
							}
						}
						
						lst_report.setAdapter(new ReportDetailListViewAdapter(
								ReportsActivity.this, saleTickets));

						dialog.dismiss();
						if (arg0.size() == 0) {
							SKToastMessage.showMessage(ReportsActivity.this,
									"သင္ ရွာေသာ အခ်က္ အလက္ မ်ား  မရိွေသးပါ...",
									SKToastMessage.INFO);
						}
					}

					public void failure(RetrofitError arg0) {
						// TODO Auto-generated method stub

					}
				});

	}
	
	private Integer checkTicketNo(List<SaleTicket> list, Saleitem saleitem){
		Integer found = null;
		for(int i=0; i<list.size();i++){
			if(list.get(i).getTicketNo().equals(saleitem.getTicketNo())){
				found = i;
				break;
			}
		}
		
		return found;
	}
}

