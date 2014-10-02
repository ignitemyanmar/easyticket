package com.ignite.mm.ticketing.application;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;

import com.actionbarsherlock.app.SherlockActivity;
import com.actionbarsherlock.view.Menu;
import com.actionbarsherlock.view.MenuItem;
import com.ignite.mm.ticketing.R;

public class BaseSherlockActivity extends SherlockActivity {
	
	public LoginUser AppLoginUser;
	
	public static final String FINISH_ALL_ACTIVITIES_ACTIVITY_ACTION = "com.ignite.FINISH_ALL_ACTIVITIES_ACTIVITY_ACTION";
	private BaseActivityReceiver baseActivityReceiver = new BaseActivityReceiver();
	public static final IntentFilter INTENT_FILTER = createIntentFilter();
	
	private static IntentFilter createIntentFilter(){
		IntentFilter filter = new IntentFilter();
		filter.addAction(FINISH_ALL_ACTIVITIES_ACTIVITY_ACTION);
		return filter;
	}
	
	protected void registerBaseActivityReceiver() {
		registerReceiver(baseActivityReceiver, INTENT_FILTER);
	}
	
	protected void unRegisterBaseActivityReceiver() {
		unregisterReceiver(baseActivityReceiver);
	}
	
	public class BaseActivityReceiver extends BroadcastReceiver{
		@Override
		public void onReceive(Context context, Intent intent) {
			if(intent.getAction().equals(FINISH_ALL_ACTIVITIES_ACTIVITY_ACTION)){
				finish();
			}
		}
	} 
	
	protected void closeAllActivities(){
		sendBroadcast(new Intent(FINISH_ALL_ACTIVITIES_ACTIVITY_ACTION));
	}
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		registerBaseActivityReceiver();
		AppLoginUser = new LoginUser(this);
	}

	@Override
	protected void onResume() {
		// TODO Auto-generated method stub
		if(AppLoginUser.isExpires()){
			closeAllActivities();
		}
		super.onResume();
	}
	
	@Override
    public boolean onCreateOptionsMenu(Menu menu) {
		getSupportMenuInflater().inflate(R.menu.activity_main, menu);
        return true;
    }
	public boolean onOptionsItemSelected(MenuItem item) {

	    switch(item.getItemId()) {
	        case R.id.menu_logout:
	        	AppLoginUser.logout();
	        	closeAllActivities();
	        	return true;
   	   	}
		return false;  
	 }
	
}
