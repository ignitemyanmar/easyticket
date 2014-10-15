package com.ignite.busoperator;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.FontsTypeface;
import com.smk.skconnectiondetector.SKConnectionDetector;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

public class MenuActivity extends BaseSherlockActivity{
	private Button btn_operator, btn_branch, btn_popular_agent;
	private Button btn_report_car;
	private Button btn_daily_report;
	private Button btn_seat_plan;
	private Button btn_credit;
	private Button btn_popular_trip;
	private Button btn_popular_classes;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getActionBar().hide();
		setContentView(R.layout.activity_menu);
		
		btn_daily_report = (Button) findViewById(R.id.btn_daily_report);
		btn_report_car = (Button) findViewById(R.id.btn_report_car);
		btn_branch = (Button)findViewById(R.id.btnBranch);
		btn_operator = (Button)findViewById(R.id.btnOperator);
		btn_popular_agent = (Button)findViewById(R.id.btn_popular_agent);
		btn_popular_trip = (Button)findViewById(R.id.btn_popular_trip);
		btn_popular_classes = (Button)findViewById(R.id.btn_popular_classes);
		btn_seat_plan = (Button)findViewById(R.id.btn_seat_plan);
		btn_credit = (Button)findViewById(R.id.btn_credit);
		
		btn_daily_report.setOnClickListener(clickListener);
		btn_report_car.setOnClickListener(clickListener);
		btn_branch.setOnClickListener(clickListener);
		btn_popular_trip.setOnClickListener(clickListener);
		btn_operator.setOnClickListener(clickListener);
		btn_popular_agent.setOnClickListener(clickListener);
		btn_popular_classes.setOnClickListener(clickListener);
		btn_seat_plan.setOnClickListener(clickListener);
		btn_credit.setOnClickListener(clickListener);
		
		SKConnectionDetector skDetector = SKConnectionDetector.getInstance(this);
		skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
		if(!skDetector.isConnectingToInternet())
			skDetector.showErrorMessage();
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_daily_report){
				startActivity(new Intent(MenuActivity.this,SalesbyDailyActivity.class));
			}
			if(v == btn_report_car){
				startActivity(new Intent(MenuActivity.this,SalesbyCarActivity.class));
			}
			if(v == btn_branch)
			{
				startActivity(new Intent(MenuActivity.this,SalesbyAgentActivity.class));
			}
			if(v == btn_popular_trip)
			{
				startActivity(new Intent(MenuActivity.this,PopularTripActivity.class));
			}
			if(v == btn_operator)
			{
				startActivity(new Intent(MenuActivity.this,SalesbyTripActivity.class));
			}
			if(v == btn_popular_agent)
			{
				startActivity(new Intent(MenuActivity.this,PopularAgentActivity.class));
			}
			if(v == btn_popular_classes)
			{
				startActivity(new Intent(MenuActivity.this,PopularClassesActivity.class));
			}
			if(v == btn_seat_plan){
				startActivity(new Intent(MenuActivity.this, SalesbyOccupencySeatPlanActivity.class));
			}
			if(v == btn_credit){
				startActivity(new Intent(MenuActivity.this, AgentListActivity.class));
			}
		}
	};
}
