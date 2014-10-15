package com.ignite.busoperator;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import org.achartengine.ChartFactory;
import org.achartengine.GraphicalView;
import org.achartengine.model.CategorySeries;
import org.achartengine.model.SeriesSelection;
import org.achartengine.renderer.DefaultRenderer;
import org.achartengine.renderer.SimpleSeriesRenderer;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import android.app.ProgressDialog;
import android.graphics.Color;
import android.os.Bundle;
import android.text.format.DateFormat;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.Toast;
import android.widget.AdapterView.OnItemSelectedListener;

import com.ignite.busoperator.adapter.AgentAdapter;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.Agent;
import com.ignite.busoperator.model.AgentsbyOperator;
import com.ignite.busoperator.model.OAuth2Error;
import com.ignite.busoperator.model.PopularAgent;
import com.ignite.busoperator.model.PopularClasses;
import com.smk.calender.widget.SKCalender;
import com.smk.calender.widget.SKCalender.Callbacks;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class PopularClassesActivity extends BaseSherlockActivity {
	/** Colors to be used for the pie slices. */
	private static int[] COLORS = new int[] { Color.GREEN, Color.BLUE, Color.MAGENTA, Color.CYAN };
	/** The main series that will include all the data. */
	private CategorySeries mSeries = new CategorySeries("");
	/** The main renderer for the main dataset. */
	private DefaultRenderer mRenderer = new DefaultRenderer();
	/** The chart view that displays the data. */
	private GraphicalView mChartView;
	private ProgressDialog dialog;
	private String selectedFromDate;
	private String selectedToDate;
	private String selectedAgentId;
	private LinearLayout lySearch;
	private LinearLayout lyDetails;
	private Button btn_from_date;
	private Button btn_to_date;
	private Spinner sp_agent;
	private AgentsbyOperator agent_by_operator;
	private SKConnectionDetector detector;
	private Button btn_search;
	
	@Override
	protected void onRestoreInstanceState(Bundle savedState) {
	   super.onRestoreInstanceState(savedState);
	   mSeries = (CategorySeries) savedState.getSerializable("current_series");
	   mRenderer = (DefaultRenderer) savedState.getSerializable("current_renderer");
	}

	@Override
	protected void onSaveInstanceState(Bundle outState) {
	   super.onSaveInstanceState(outState);
	   outState.putSerializable("current_series", mSeries);
	   outState.putSerializable("current_renderer", mRenderer);
	}
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		getSupportActionBar().setTitle("အ ေရာင္း ရဆံုး ကား အမ်ိဳးအစား စာရင္းမ်ား");
		setContentView(R.layout.activity_popular_classes);
		mRenderer.setZoomButtonsVisible(true);
	    mRenderer.setStartAngle(180);
	    mRenderer.setDisplayValues(true);
	    
		btn_from_date = (Button)findViewById(R.id.btn_from_date);
		btn_to_date = (Button)findViewById(R.id.btn_to_date);
		sp_agent = (Spinner)findViewById(R.id.sp_agent);
		
		btn_search = (Button) findViewById(R.id.btn_search);
		btn_search.setOnClickListener(clickListener);
		btn_from_date.setOnClickListener(clickListener);
		btn_to_date.setOnClickListener(clickListener);
		
		btn_from_date.setText(getDate()[0]);
		btn_to_date.setText(getDate()[1]);
		
		detector = new SKConnectionDetector(this);

		agent_by_operator = new AgentsbyOperator();
		agent_by_operator.getAgents().add(
				new Agent("", "Select All...", null, null, null, null, null));
		
		selectedFromDate = getDate()[0];
		selectedToDate   = getDate()[1];
		
		if(detector.isConnectingToInternet()){
			getAgent();
			getPopularClasses();
		}else{
			detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
			detector.showErrorMessage();
		}
	    
	}
	
	@Override
	protected void onResume() {
		super.onResume();
	    if (mChartView == null) {
	    	
	    } else {
	    	mChartView.repaint();
	    }
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_from_date)
			{
				final SKCalender skCalender = new SKCalender(PopularClassesActivity.this);

				  skCalender.setCallbacks(new Callbacks() {

						public void onChooseDate(String chooseDate) {
				          // TODO Auto-generated method stub
				        	
				        	Date formatedDate = null;
				        	try {
								formatedDate = new SimpleDateFormat("dd-MMM-yyyy").parse(chooseDate);
							} catch (java.text.ParseException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
				        	String selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	selectedFromDate = selectedDate;
				        	btn_from_date.setText(selectedDate);
				        	
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
								
			}
			if(v == btn_to_date)
			{
				final SKCalender skCalender = new SKCalender(PopularClassesActivity.this);

				  skCalender.setCallbacks(new Callbacks() {

				        public void onChooseDate(String chooseDate) {
				          // TODO Auto-generated method stub
				        	Date formatedDate = null;
				        	try {
								formatedDate = new SimpleDateFormat("dd-MMM-yyyy").parse(chooseDate);
							} catch (java.text.ParseException e) {
								// TODO Auto-generated catch block
								e.printStackTrace();
							}
				        	String selectedDate = DateFormat.format("yyyy-MM-dd",formatedDate).toString();
				        	selectedToDate = selectedDate;
				        	btn_to_date.setText(selectedDate);
				        	skCalender.dismiss();
				        }
				  });

				  skCalender.show();
			}
			
			if(v == btn_search){
				if(detector.isConnectingToInternet()){
					getPopularClasses();
				}else{
					detector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
					detector.showErrorMessage();
				}
			}
		}
	};
	
	protected String selectedAgentName;
	
	private void getPopularClasses(){
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
		dialog.setCancelable(true);
		dialog.show();
		NetworkEngine.getInstance().getPopularClasses(AppLoginUser.getAccessToken(), AppLoginUser.getUserID(), selectedFromDate, selectedToDate, selectedAgentId, new Callback<List<PopularClasses>>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}

			public void success(List<PopularClasses> arg0, Response arg1) {
				// TODO Auto-generated method stub
				
				lySearch = (LinearLayout)findViewById(R.id.lySearch);
				lyDetails = (LinearLayout)findViewById(R.id.chart);
				
				lySearch.getLayoutParams().width = (DeviceUtil.getInstance(PopularClassesActivity.this).getWidth()/3);
				lyDetails.getLayoutParams().width = (int) (DeviceUtil.getInstance(PopularClassesActivity.this).getWidth()/1.5);
				
				mRenderer.setLabelsTextSize(18f);
				mRenderer.setLabelsColor(Color.BLACK);
				//mRenderer.setLegendHeight(32);
				mRenderer.setLegendTextSize(16f);
				mChartView = ChartFactory.getPieChartView(PopularClassesActivity.this, mSeries, mRenderer);
		    	mRenderer.setClickEnabled(true);
		    	
		    	lyDetails.addView(mChartView, new LayoutParams(LayoutParams.MATCH_PARENT,
		          LayoutParams.MATCH_PARENT));
		    	
				Integer total_purchase_ticket = 0;
				for(PopularClasses classes: arg0){
					total_purchase_ticket += classes.getPurchasedTotalSeat();
				}
				
				for(PopularClasses classes: arg0){
					SimpleSeriesRenderer renderer = new SimpleSeriesRenderer();
					double percentage = (classes.getPurchasedTotalSeat()*100) / total_purchase_ticket;
				    mSeries.add(classes.getName(), percentage);
			        renderer.setColor(COLORS[(mSeries.getItemCount() - 1) % COLORS.length]);
			        mRenderer.addSeriesRenderer(renderer);
			        mChartView.repaint();
				}
				
				dialog.dismiss();
			}
		});
	}
	
	private void getAgent() {

		NetworkEngine.getInstance().getAgentbyOperator(AppLoginUser.getAccessToken(), AppLoginUser.getUserID(),
				new Callback<AgentsbyOperator>() {

					public void success(AgentsbyOperator arg0, Response arg1) {
						// TODO Auto-generated method stub
						agent_by_operator.getAgents().addAll(arg0.getAgents());
						sp_agent.setAdapter(new AgentAdapter(
								PopularClassesActivity.this, agent_by_operator
										.getAgents()));
					}

					public void failure(RetrofitError arg0) {
						// TODO Auto-generated method stub
						OAuth2Error error = (OAuth2Error) arg0
								.getBodyAs(OAuth2Error.class);
						Log.i("", "Hello Error Response Code : "
								+ arg0.getResponse().getStatus());
						Log.i("", "Hello Error : " + error.getError());
						Log.i("",
								"Hello Error Desc : "
										+ error.getError_description());
					}
				});
	}
	
	private OnItemSelectedListener agentClickListener = new OnItemSelectedListener() {

		public void onItemSelected(AdapterView<?> arg0, View arg1, int arg2,
				long arg3) {
			// TODO Auto-generated method stub
			selectedAgentId = "";
			selectedAgentName = "";
			if(arg2 > 0){
				selectedAgentId = agent_by_operator.getAgents().get(arg2).getId();
				selectedAgentName = agent_by_operator.getAgents().get(arg2).getName();
			}
		}

		public void onNothingSelected(AdapterView<?> arg0) {
			// TODO Auto-generated method stub

		}
	};
	
	private String[] getDate(){
		String today 	= SKCalender.getToday("yyyy-MM-dd");
		String[] todayArr 		= today.split("-");
		String startDay 		= "01";
		String endDay 			= "15";
		if(Integer.valueOf(todayArr[2]) > 15){
			startDay	= "16";
			endDay		= SKCalender.getInstance(this).getEndDayofThisMonth().toString();
		}
		String[] twoDate = {todayArr[0]+"-"+todayArr[1]+"-"+startDay,todayArr[0]+"-"+todayArr[1]+"-"+endDay};
		return twoDate;
	}
	
}
