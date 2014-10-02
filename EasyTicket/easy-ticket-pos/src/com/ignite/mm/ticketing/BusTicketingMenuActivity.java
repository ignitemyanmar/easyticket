package com.ignite.mm.ticketing;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.text.format.DateFormat;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.actionbarsherlock.app.ActionBar;
import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.mm.ticketing.application.BaseSherlockActivity;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;

public class BusTicketingMenuActivity extends BaseSherlockActivity {
	private LinearLayout btn_sale_ticket;
	private LinearLayout btn_credit_list;
	private ActionBar actionBar;
	private TextView actionBarTitle;
	private ImageButton actionBarBack;
	private LinearLayout btn_cancel_order;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.action_bar);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.action_bar_title);
		actionBarBack = (ImageButton) actionBar.getCustomView().findViewById(
				R.id.action_bar_back);
		actionBarBack.setOnClickListener(clickListener);
		actionBarTitle.setText("Easy Ticket");
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
		
		setContentView(R.layout.activity_busticketing_menu);
		
		btn_sale_ticket = (LinearLayout) findViewById(R.id.btn_sale_ticket);
		btn_credit_list = (LinearLayout) findViewById(R.id.btn_credit_list);
		btn_cancel_order = (LinearLayout) findViewById(R.id.btn_cancel_order);
		
		btn_sale_ticket.setOnClickListener(clickListener);
		btn_credit_list.setOnClickListener(clickListener);
		btn_cancel_order.setOnClickListener(clickListener);
		
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_sale_ticket){
				SharedPreferences sharedPreferences = getSharedPreferences("old_sale",MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.clear();
				editor.commit();
				editor.putString("working_date", "");
				editor.commit();
				Intent intent = new Intent(getApplicationContext(),	BusTripsCityActivity.class);
				startActivity(intent);
			}
			
			if(v == btn_credit_list){
				Intent intent = new Intent(getApplicationContext(),	BusTicketingCreditListActivity.class);
				startActivity(intent);
			}
			
			if(v == btn_cancel_order){
				final SKCalender skCalender = new SKCalender(BusTicketingMenuActivity.this);
				
				  skCalender.setCallbacks(new Callbacks() {

						public void onChooseDate(String chooseDate) {
				        	// TODO Auto-generated method stub
				        	Date formatedDate = null;
							try {
								formatedDate = new SimpleDateFormat("dd-MMM-yyyy").parse(chooseDate);
							} catch (ParseException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
				        	String selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	SharedPreferences sharedPreferences = getSharedPreferences("old_sale",MODE_PRIVATE);
							SharedPreferences.Editor editor = sharedPreferences.edit();
							editor.clear();
							editor.commit();
							editor.putString("working_date", selectedDate);
							editor.commit();
				        	skCalender.dismiss();
				        	startActivity(new Intent(getApplicationContext(), BusTripsCityActivity.class));			        	
				        	
				        }
				  });

				skCalender.show();
			}
			
			if(v == actionBarBack){
				finish();
			}
		}
	};
}
