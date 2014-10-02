package com.ignite.busoperator;


import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.busoperator.R;

import android.os.Bundle;
import android.content.Intent;

public class MainActivity extends SherlockActivity {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getActionBar().hide();
        setContentView(R.layout.activity_main);
        Thread t = new Thread(new Runnable() {
			
			public void run() {
				// TODO Auto-generated method stub
				try {
					Thread.sleep(1000);
				} catch (Exception e) {
					// TODO: handle exception
				}finally{
					finish();
					startActivity(new Intent(MainActivity.this,LoginActivity.class));
				}
			}
		});
        t.start();
    }

   
}
