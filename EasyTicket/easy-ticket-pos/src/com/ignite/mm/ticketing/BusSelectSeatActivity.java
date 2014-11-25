package com.ignite.mm.ticketing;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.GridView;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;

import com.actionbarsherlock.app.ActionBar;
import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.mm.ticketing.application.BaseSherlockActivity;
import com.ignite.mm.ticketing.application.DeviceUtil;
import com.ignite.mm.ticketing.clientapi.NetworkEngine;
import com.ignite.mm.ticketing.connection.detector.ConnectionDetector;
import com.ignite.mm.ticketing.custom.listview.adapter.BusClassAdapter;
import com.ignite.mm.ticketing.custom.listview.adapter.BusSeatAdapter;
import com.ignite.mm.ticketing.custom.listview.adapter.GroupUserListAdapter;
import com.ignite.mm.ticketing.http.connection.HttpConnection;
import com.ignite.mm.ticketing.sqlite.database.model.Agent;
import com.ignite.mm.ticketing.sqlite.database.model.AgentList;
import com.ignite.mm.ticketing.sqlite.database.model.BusSeat;
import com.ignite.mm.ticketing.sqlite.database.model.OperatorGroupUser;
import com.ignite.mm.ticketing.sqlite.database.model.ReturnComfrim;
import com.ignite.mm.ticketing.sqlite.database.model.Seat;
import com.ignite.mm.ticketing.sqlite.database.model.Seat_list;
import com.ignite.mm.ticketing.sqlite.database.model.Seat_plan;
import com.ignite.mm.ticketing.sqlite.database.model.SelectSeat;
import com.smk.custom.view.CustomTextView;
import com.smk.skalertmessage.SKToastMessage;

public class BusSelectSeatActivity extends BaseSherlockActivity{
	
	public static List<BusSeat> Bus_Seat;
	private ListView lvClass;
	private ActionBar actionBar;
	private TextView actionBarTitle;
	private ImageButton actionBarBack;
	private GridView mSeat;
	public static String SelectedSeat;
	protected ArrayList<Seat> Seat;
	protected ProgressDialog dialog;
	private ConnectionDetector connectionDetector;
	private LinearLayout mLoadingView;
	private LinearLayout mNoConnection;
	protected ReturnComfrim returnComfirm;
	private String AgentID = "0";
	private String OperatorID;
	private String FromCity;
	private String ToCity;
	private String Time;
	private String Date;
	private TextView txt_operator;
	private TextView txt_classes;
	private TextView txt_price;
	private TextView txt_dept_date;
	private TextView txt_dept_time;
	private ListView lst_group_user;
	private Button btn_booking;
	private String From;
	private String To;
	private Button btn_now_booking;
	private AutoCompleteTextView edt_agent;
	private Integer isBooking = 0;
	private Integer NotifyBooking;
	private TextView actionBarNoti;
	private Button btn_check_out;
	private String Classes;
	public static List<BusSeat> BusSeats;
	public static List<OperatorGroupUser> groupUser = new ArrayList<OperatorGroupUser>();
	public static String CheckOut;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.bus_seat_list);

		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.action_bar);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.action_bar_title);
		actionBarBack = (ImageButton) actionBar.getCustomView().findViewById(
				R.id.action_bar_back);
		
		actionBarBack.setOnClickListener(clickListener);
		actionBarNoti = (TextView) actionBar.getCustomView().findViewById(R.id.txt_notify_booking);
		actionBarNoti.setOnClickListener(clickListener);
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
		mSeat = (GridView) findViewById(R.id.grid_seat);
		lst_group_user = (ListView) findViewById(R.id.lst_group_user);
		connectionDetector = new ConnectionDetector(this);
		
		Bundle bundle = getIntent().getExtras();	
		AgentID = bundle.getString("agent_id");
		OperatorID = bundle.getString("operator_id");
		FromCity = bundle.getString("from_city_id");
		ToCity = bundle.getString("to_city_id");
		From = bundle.getString("from_city");
		To = bundle.getString("to_city");
		Classes = bundle.getString("class_id");
		Time = bundle.getString("time");
		Date = bundle.getString("date");
		
		SharedPreferences notify = getSharedPreferences("NotifyBooking", Context.MODE_PRIVATE);
		NotifyBooking = notify.getInt("count", 0);
		if(NotifyBooking > 0){
			actionBarNoti.setVisibility(View.VISIBLE);
			actionBarNoti.setText(NotifyBooking.toString());
		}
		
		actionBarTitle.setText("Choose Seat > "+From+" - "+To);
		
		SelectedSeat = "";
		btn_booking		= (Button) findViewById(R.id.btn_booking);
		btn_booking.setOnClickListener(clickListener);
		btn_now_booking = (Button) findViewById(R.id.btn_now_booking);
		btn_now_booking.setOnClickListener(clickListener);
		btn_check_out = (Button) findViewById(R.id.btn_check_out);
		btn_check_out.setOnClickListener(clickListener);
		edt_agent	= (AutoCompleteTextView) findViewById(R.id.edt_agent);
		mLoadingView = (LinearLayout) findViewById(R.id.ly_loading);
		mNoConnection = (LinearLayout) findViewById(R.id.no_internet);
		txt_operator = (CustomTextView) findViewById(R.id.txt_operator);
		txt_classes = (CustomTextView) findViewById(R.id.txt_classes);
		txt_price = (CustomTextView) findViewById(R.id.txt_price);
		txt_dept_date = (CustomTextView) findViewById(R.id.txt_departure_date);
		txt_dept_date.setText("ထြက္ခြာမည့္ ေန႕ရက္ : "+ Date);
		txt_dept_time = (CustomTextView) findViewById(R.id.txt_departure_time);
		txt_dept_time.setText("ထြက္ခြာမည့္ အခ်ိန္ : "+ Time);
		
	}
	
	@Override
	protected void onResume() {
		// TODO Auto-generated method stub
		super.onResume();
		if(SelectedSeat.length() != 0)
			finish();
		if(connectionDetector.isConnectingToInternet())
		{ 	mLoadingView.setVisibility(View.VISIBLE);
			mLoadingView.startAnimation(topInAnimaiton());
			getOperatorGroupUser();
			getSeatPlan();
			getAgent();
		}else {
			mNoConnection.setVisibility(View.VISIBLE);
			mNoConnection.startAnimation(topInAnimaiton());
			fadeData();
		}
	}
	
	private void fadeData(){
		BusSeats = new ArrayList<BusSeat>();
		List<Seat_plan> seat_plan = new ArrayList<Seat_plan>();
		List<Seat_list> seat_list = new ArrayList<Seat_list>();
		seat_list.add(new Seat_list(1, "A01", 1));
		seat_list.add(new Seat_list(1, "A02", 1));
		seat_list.add(new Seat_list(1, "A01", 0));
		seat_list.add(new Seat_list(1, "A03", 1));
		seat_list.add(new Seat_list(1, "A04", 1));
		seat_list.add(new Seat_list(1, "A05", 1));
		seat_list.add(new Seat_list(1, "A06", 1));
		seat_list.add(new Seat_list(1, "A01", 0));
		seat_list.add(new Seat_list(1, "A07", 1));
		seat_list.add(new Seat_list(1, "A08", 1));
		seat_list.add(new Seat_list(1, "A09", 1));
		seat_list.add(new Seat_list(1, "A10", 1));
		seat_list.add(new Seat_list(1, "A01", 0));
		seat_list.add(new Seat_list(1, "A11", 1));
		seat_list.add(new Seat_list(1, "A12", 1));
		seat_list.add(new Seat_list(1, "A13", 1));
		seat_list.add(new Seat_list(1, "A14", 1));
		seat_list.add(new Seat_list(1, "A01", 0));
		seat_list.add(new Seat_list(1, "A15", 1));
		seat_list.add(new Seat_list(1, "A16", 1));
		seat_list.add(new Seat_list(1, "A17", 1));
		seat_list.add(new Seat_list(1, "A18", 1));
		seat_list.add(new Seat_list(1, "A01", 0));
		seat_list.add(new Seat_list(1, "A19", 1));
		seat_list.add(new Seat_list(1, "A20", 1));
		seat_list.add(new Seat_list(1, "A21", 1));
		seat_list.add(new Seat_list(1, "A22", 1));
		seat_list.add(new Seat_list(1, "A01", 0));
		seat_list.add(new Seat_list(1, "A23", 1));
		seat_list.add(new Seat_list(1, "A24", 1));
		seat_list.add(new Seat_list(1, "A25", 1));
		seat_list.add(new Seat_list(1, "A26", 1));
		seat_list.add(new Seat_list(1, "A07", 1));
		seat_list.add(new Seat_list(1, "A28", 1));
		seat_list.add(new Seat_list(1, "A29", 1));
		
		seat_plan.add(new Seat_plan(1, 1, 2, "YGN-A/0002", 1, "Special", "10:00 AM", "1:0 PM", 15000, 1, 1, 7, 5, seat_list ));
		BusSeats.add(new BusSeat("Yagon - Mandalay", "1", "Elite", seat_plan ));
		if(BusSeats.size() > 0){
			txt_operator.setText("ကားဂိတ္ : "+ BusSeats.get(0).getOperator());
			txt_classes.setText("ယာဥ္အမ်ိဳးအစား : "+ BusSeats.get(0).getSeat_plan().get(0).getClasses());
			txt_price.setText("ေစ်းႏႈန္း :"+ BusSeats.get(0).getSeat_plan().get(0).getPrice()+" Ks");
			mSeat.setNumColumns(BusSeats.get(0).getSeat_plan().get(0).getColumn());
			mSeat.setAdapter(new BusSeatAdapter(this, BusSeats.get(0).getSeat_plan().get(0).getSeat_list()));	
			setGridViewHeightBasedOnChildren(mSeat , Integer.valueOf(BusSeats.get(0).getSeat_plan().get(0).getColumn()));
			
			lvClass = (ListView)findViewById(R.id.lvBusClass);
			lvClass.setAdapter(new BusClassAdapter(this, BusSeats.get(0).getSeat_plan()));
			lvClass.setOnItemClickListener(itemClickListener);
		}
	}
	
	private void getOperatorGroupUser(){
		NetworkEngine.getInstance().getOperatorGroupUser(AppLoginUser.getAccessToken(), AppLoginUser.getUserID(), new Callback<List<OperatorGroupUser>>() {

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}

			public void success(List<OperatorGroupUser> arg0, Response arg1) {
				// TODO Auto-generated method stub
				groupUser = arg0;
				lst_group_user.setAdapter(new GroupUserListAdapter(BusSelectSeatActivity.this, groupUser));
				setListViewHeightBasedOnChildren(lst_group_user);
			}
		});
	}
	
	private Animation topInAnimaiton() {
		Animation anim = AnimationUtils.loadAnimation(getApplicationContext(),
				R.anim.top_in);
		anim.reset();
		return anim;

	}

	private Animation topOutAnimaiton() {
		Animation anim = AnimationUtils.loadAnimation(getApplicationContext(),
				R.anim.top_out);
		anim.reset();
		return anim;

	}
	
	private void getAgent(){
		SharedPreferences pref = this.getApplicationContext().getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String user_id = pref.getString("user_id", null);
		NetworkEngine.getInstance().getAllAgent(accessToken,user_id, new Callback<AgentList>() {

			private List<Agent> agentList;
			private ArrayAdapter<Agent> agentListAdapter;

			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}

			public void success(AgentList arg0, Response arg1) {
				// TODO Auto-generated method stub
				agentList = arg0.getAgents();
				agentListAdapter = new ArrayAdapter<Agent>(BusSelectSeatActivity.this, android.R.layout.simple_dropdown_item_1line, agentList);
				edt_agent.setAdapter(agentListAdapter);
				edt_agent.setOnItemClickListener(new OnItemClickListener() {

					public void onItemClick(AdapterView<?> arg0, View arg1,
							int arg2, long arg3) {
						// TODO Auto-generated method stub
			           	AgentID = ((Agent)arg0.getAdapter().getItem(arg2)).getId();
					}
				});
				
			}
		});
	}
	
	private void getSeatPlan() {
		SharedPreferences pref = this.getApplicationContext().getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		Log.i("","Hello : "+ OperatorID+"/"+ FromCity+"/"+ ToCity+"/"+ Classes+"/"+Date+"/"+Time);
		NetworkEngine.getInstance().getItems(accessToken,OperatorID, FromCity, ToCity, Classes,Date, Time, new Callback<List<BusSeat>>() {
			
			public void success(List<BusSeat> arg0, Response arg1) {
				// TODO Auto-generated method stub
				SelectedSeat = "";
				BusSeats = arg0;
				getData();
				mLoadingView.setVisibility(View.GONE);
				mLoadingView.startAnimation(topOutAnimaiton());
			}
			
			public void failure(RetrofitError arg0) {
				// TODO Auto-generated method stub
				
			}
		});
	}
	
	public void getServermsg()
	{
		dialog = ProgressDialog.show(this, "", " Please wait...", true);
        dialog.setCancelable(true);
        List<SelectSeat> seats = new ArrayList<SelectSeat>();
        String[] selectedSeat = SelectedSeat.split(",");
		for (int i = 0; i < selectedSeat.length; i++) {
			seats.add(new SelectSeat(BusSeats.get(0).getSeat_plan().get(0).getId(), BusSeats.get(0).getSeat_plan().get(0).getSeat_list().get(Integer.valueOf(selectedSeat[i])).getSeat_no().toString()));
		}
		
		String FromCity = BusSeats.get(0).getSeat_plan().get(0).getFrom().toString();
		String ToCity = BusSeats.get(0).getSeat_plan().get(0).getTo().toString();
		
        Log.i("","Hello From City: "+FromCity+" , To City : "+ToCity+" and Select Seat -> "+seats.toString());
        SharedPreferences pref = this.getApplicationContext().getSharedPreferences("User", Activity.MODE_PRIVATE);
		String accessToken = pref.getString("access_token", null);
		String user_id = pref.getString("user_id", null);
		String user_type = pref.getString("user_type", null);
		if(user_type.equals("agent")){
			AgentID = user_id;
		}else if(AgentID.length() == 0){
			AgentID = "0";
		}
                
        List<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new BasicNameValuePair("device_id", DeviceUtil.getInstance(this).getID()));
        params.add(new BasicNameValuePair("operator_id", OperatorID));
        params.add(new BasicNameValuePair("agent_id", AgentID));
        params.add(new BasicNameValuePair("from_city", FromCity));
        params.add(new BasicNameValuePair("to_city", ToCity));
        params.add(new BasicNameValuePair("group_operator_id", AppLoginUser.getUserGroupID()));
        params.add(new BasicNameValuePair("seat_list", seats.toString()));
        params.add(new BasicNameValuePair("booking", isBooking.toString()));
        params.add(new BasicNameValuePair("access_token", accessToken));
        Log.i("","Hello Param: " + params.toString());
		final Handler handler = new Handler() {

			public void handleMessage(Message msg) {
				
				String jsonData = msg.getData().getString("data");
				Log.i("ans:","Server Response: "+jsonData);
				try {
					JSONObject jsonObject = new JSONObject(jsonData);
					if(jsonObject.getString("status").equals("1")){
						if(jsonObject.getBoolean("can_buy") && jsonObject.getString("device_id").equals(DeviceUtil.getInstance(BusSelectSeatActivity.this).getID())){
		        			if(isBooking == 0){
		        				Intent nextScreen = new Intent(BusSelectSeatActivity.this,NRCActivity.class);
		        				JSONArray jsonArray = jsonObject.getJSONArray("tickets");
		        				String SeatLists = "";
		        				for(int i=0; i<jsonArray.length(); i++){
		        					JSONObject obj = jsonArray.getJSONObject(i);
		        					SeatLists += obj.getString("seat_no")+",";
		        				}
			    				Bundle bundle = new Bundle();
			    				bundle.putString("from_intent", "checkout");
			    				bundle.putString("selected_seat",  SeatLists);
			    				bundle.putString("sale_order_no", jsonObject.getString("sale_order_no"));
			    				bundle.putString("bus_occurence", BusSeats.get(0).getSeat_plan().get(0).getId().toString());
			    				nextScreen.putExtras(bundle);
			    				startActivity(nextScreen);
		        			}else{
		        				SKToastMessage.showMessage(BusSelectSeatActivity.this, "Booking မွာျပီးျပီး ျဖစ္ပါသည္။", SKToastMessage.SUCCESS);
		        				isBooking = 0;
		        				getSeatPlan();
		        			}
							
		    				dialog.dismiss();
		        		}else{
		        			dialog.dismiss();
		        			SKToastMessage.showMessage(BusSelectSeatActivity.this, "သင္ မွာယူေသာ လက္ မွတ္ မ်ားမွာ စကၠန့္ပိုင္ အတြင္း တစ္ ျခားသူ ယူ သြားေသာေၾကာင့္ သင္မွာေသာလက္မွတ္မ်ား မရႏိုင္ေတာ့ပါ။ ေက်းဇူးျပဳၿပီး တစ္ျခားလက္ မွတ္ မ်ား ျပန္ေရႊးေပးပါ။။", SKToastMessage.ERROR);
		        			getSeatPlan();
		        		}
					}else{
						isBooking = 0;
						dialog.dismiss();
						SKToastMessage.showMessage(BusSelectSeatActivity.this, jsonObject.getString("message"), SKToastMessage.ERROR);
					}
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
		};
		HttpConnection lt = new HttpConnection(handler,"POST", "http://192.168.1.101/sale",params);
		lt.execute();
		
	}
	
		
	private void getData() {
		if(BusSeats.size() > 0){
			txt_operator.setText("ကားဂိတ္ : "+ BusSeats.get(0).getOperator());
			txt_classes.setText("ယာဥ္အမ်ိဳးအစား : "+ BusSeats.get(0).getSeat_plan().get(0).getClasses());
			txt_price.setText("ေစ်းႏႈန္း :"+ BusSeats.get(0).getSeat_plan().get(0).getPrice()+" Ks");
			mSeat.setNumColumns(BusSeats.get(0).getSeat_plan().get(0).getColumn());
			mSeat.setAdapter(new BusSeatAdapter(this, BusSeats.get(0).getSeat_plan().get(0).getSeat_list()));	
			setGridViewHeightBasedOnChildren(mSeat , Integer.valueOf(BusSeats.get(0).getSeat_plan().get(0).getColumn()));
			
			lvClass = (ListView)findViewById(R.id.lvBusClass);
			lvClass.setAdapter(new BusClassAdapter(this, BusSeats.get(0).getSeat_plan()));
			lvClass.setOnItemClickListener(itemClickListener);
		}else{
			AlertDialog.Builder alertDialog = new AlertDialog.Builder(this);
			alertDialog.setMessage("There is no bus yet.");
			alertDialog.setCancelable(false);
			alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
				
				public void onClick(DialogInterface dialog, int which) {
					// TODO Auto-generated method stub
					finish();
				}
			});
			alertDialog.show();
		}
	}
	
	private OnItemClickListener itemClickListener = new OnItemClickListener() {

		public void onItemClick(AdapterView<?> parent, View view, int position,
				long id) {
			txt_operator.setText("ကားဂိတ္ : "+ BusSeats.get(0).getOperator());
			txt_classes.setText("ယာဥ္အမ်ိဳးအစား : "+ BusSeats.get(0).getSeat_plan().get(position).getClasses());
			txt_price.setText("ေစ်းႏႈန္း :"+ BusSeats.get(0).getSeat_plan().get(position).getPrice()+" Ks");
			mSeat.setNumColumns(BusSeats.get(0).getSeat_plan().get(position).getColumn());
			mSeat.setAdapter(new BusSeatAdapter(BusSelectSeatActivity.this, BusSeats.get(0).getSeat_plan().get(position).getSeat_list()));	
			setGridViewHeightBasedOnChildren(mSeat , Integer.valueOf(BusSeats.get(0).getSeat_plan().get(position).getColumn()));
		}
	};
	
	
	private OnClickListener clickListener = new OnClickListener() {

		public void onClick(View v) {
			if (v == actionBarBack) {
				finish();
			}
			
			if(v == actionBarNoti){
				SharedPreferences sharedPreferences = getSharedPreferences("order",MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.clear();
				editor.commit();
				editor.putString("order_date", getToday());
				editor.commit();
	        	startActivity(new Intent(getApplicationContext(),	BusTicketingOrderListActivity.class));
			}
			
			if(v == btn_booking){
				SharedPreferences sharedPreferences = getSharedPreferences("order",MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.clear();
				editor.commit();
				editor.putString("order_date", Date);
				editor.commit();
	        	startActivity(new Intent(getApplicationContext(),	BusTicketingOrderListActivity.class));
			}
			
			if(v == btn_now_booking){
				if(SelectedSeat.length() != 0){
					if(connectionDetector.isConnectingToInternet()){
						isBooking = 1;
						if(!AgentID.equals("0") && edt_agent.getText().toString().length() != 0){
							getServermsg();
						}else{
							edt_agent.setError("Please Choose Agent");
						}
						
					}else{
						connectionDetector.showErrorDialog();
					}
				}else{
					SKToastMessage.showMessage(BusSelectSeatActivity.this, "Please choose the seat.", SKToastMessage.ERROR);
				}
			}
			
			if(v == btn_check_out){
				if(SelectedSeat.length() != 0){
					if(connectionDetector.isConnectingToInternet()){
						getServermsg();
					}else{
						// FADE DATA
						Intent nextScreen = new Intent(BusSelectSeatActivity.this,NRCActivity.class);
	    				Bundle bundle = new Bundle();
	    				bundle.putString("trip",BusSeats.get(0).getTrip());
	    				bundle.putString("trip_date", Date);
	    				bundle.putString("operator_id",OperatorID);
	    				bundle.putString("operator_name", BusSeats.get(0).getOperator());
	    				bundle.putString("trip_time",Time);
	    				bundle.putString("selected_seat",  SelectedSeat);
	    				bundle.putString("sale_order_no", "Inv-1234");
	    				nextScreen.putExtras(bundle);
	    				startActivity(nextScreen);
					}
				}else{
					SKToastMessage.showMessage(BusSelectSeatActivity.this, "Please choose the seat.", SKToastMessage.ERROR);
				}
			}				
		}
	};
	
	public void showDialog(String msg){
		LayoutInflater factory = LayoutInflater.from(this);
	    final View msgDialogView = factory.inflate(R.layout.custom_msg_dialog, null);
	    final AlertDialog msgDialog = new AlertDialog.Builder(this).create();
	    msgDialog.setView(msgDialogView);
	    LinearLayout msgContainer = (LinearLayout)msgDialogView.findViewById(R.id.dialog_container);
	    try {
			JSONObject data = new JSONObject(msg);
			JSONArray arr = data.getJSONArray("Seat");
			for (int i = 0; i < arr.length(); i++) {
				JSONObject obj = arr.getJSONObject(i);
				TextView txtMsg = new TextView(this);
				txtMsg.setText(obj.getString("seatNo")+" is "+obj.getString("seatStatus"));
				msgContainer.addView(txtMsg);
			}
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	    msgDialogView.findViewById(R.id.btnOK).setOnClickListener(new OnClickListener() {

	        public void onClick(View v) {
	            //your business logic 
	            msgDialog.dismiss();
	        }
	    });
	    msgDialog.show();
	}
	
	public void setGridViewHeightBasedOnChildren(GridView gridView, int columns) {
		ListAdapter listAdapter = gridView.getAdapter();
		if (listAdapter == null) {
			// pre-condition
			return;
		}

		int totalHeight = 0;
		int items = listAdapter.getCount();
		int rows = 0;

		View listItem = listAdapter.getView(0, null, gridView);
		listItem.measure(0, 0);
		totalHeight = listItem.getMeasuredHeight();

		float x = 1;
		if (items > columns) {
			x = items / columns;
			rows = (int) (x + 1);
			totalHeight *= rows;
		}

		ViewGroup.LayoutParams params = gridView.getLayoutParams();
		params.height = totalHeight;
		gridView.setLayoutParams(params);

	}
	public static void setListViewHeightBasedOnChildren(ListView listView) {
        ListAdapter listAdapter = listView.getAdapter(); 
        if (listAdapter == null) {
            // pre-condition
            return;
        }

        int totalHeight = 0;
        for (int i = 0; i < listAdapter.getCount(); i++) {
            View listItem = listAdapter.getView(i, null, listView);
            listItem.measure(0, 0);
            totalHeight += listItem.getMeasuredHeight();
        }

        ViewGroup.LayoutParams params = listView.getLayoutParams();
        params.height = totalHeight + (listView.getDividerHeight() * (listAdapter.getCount() - 1));
        listView.setLayoutParams(params);
        listView.requestLayout();
    }
	
	@Override
	protected void onStart() {
		// TODO Auto-generated method stub
		RelativeLayout focuslayout = (RelativeLayout) findViewById(R.id.layout_seat_plan);
		focuslayout.requestFocus();
		super.onStart();
	}
}

