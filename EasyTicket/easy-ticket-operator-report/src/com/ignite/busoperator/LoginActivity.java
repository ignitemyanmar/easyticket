package com.ignite.busoperator;

import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;

import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.busoperator.R;
import com.ignite.busoperator.application.BaseSherlockActivity;
import com.ignite.busoperator.application.LoginUser;
import com.ignite.busoperator.clientapi.NetworkEngine;
import com.ignite.busoperator.model.AccessToken;
import com.ignite.busoperator.model.OAuth2Error;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class LoginActivity extends SherlockActivity{

	private Button login;
	private EditText email;
	private EditText password;
	private Context ctx = this;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		getActionBar().hide();
		setContentView(R.layout.activity_login);
		
		email = (EditText)findViewById(R.id.txt_login_email);
		password = (EditText)findViewById(R.id.txt_login_password);
		login = (Button)findViewById(R.id.cmd_login);
		login.setOnClickListener(clickListener);
		
		SKConnectionDetector skDetector = SKConnectionDetector.getInstance(this);
		skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
	}
	
	private OnClickListener clickListener = new OnClickListener() {
		
		private Dialog dialog;
		private String userEmail;

		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == login)
			{
				SKConnectionDetector skDetector = SKConnectionDetector.getInstance(getApplicationContext());
				skDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
				if(skDetector.isConnectingToInternet()){
					dialog = ProgressDialog.show(ctx, ""," Please wait...", true);
	    			dialog.setCancelable(true);
	    			if(email.getText().toString().contains("@")){
	    				userEmail = email.getText().toString();
	    			}else{
	    				userEmail = email.getText().toString()+"@gmail.com";
	    			}
	    			NetworkEngine.getInstance().getAccessToken("password", "721685", "IgniteAdmin721685", userEmail, password.getText().toString(), "operator", "123456789", new Callback<AccessToken>(){

						public void success(AccessToken arg0, Response arg1) {
							// TODO Auto-generated method stub
							dialog.dismiss();
							LoginUser user = new LoginUser(LoginActivity.this);
							user.setAccessToken(arg0.getAccess_token());
							user.setTokenType(arg0.getToken_type());
							user.setExpires(arg0.getExpires());
							user.setExpiresIn(arg0.getExpires_in());
							user.setRefreshToken(arg0.getRefresh_token());
							user.setUserID(arg0.getUser().getId());
							user.setUserName(arg0.getUser().getName());
							user.setUserType(arg0.getUser().getType());
							user.setUserRole(arg0.getUser().getRole());
							user.login();
	   						Intent intent = new Intent(getApplicationContext(),	MenuActivity.class);
	   						startActivity(intent);
						}
						
						public void failure(RetrofitError arg0) {
							// TODO Auto-generated method stub
							if(arg0.getResponse().getStatus() == 400){
								SKToastMessage.showMessage(LoginActivity.this, "သင္၏ Login Email ႏွင့္ Password ဟာ မွား ေနပါသည္", SKToastMessage.ERROR);
							}
							dialog.dismiss();
							//Log.i("","Hello Error Response Code : "+arg0.getResponse().getStatus() +" Reason: "+ arg0.getResponse().getReason());
						}
	    				
	    			});
				}else{
					Intent intent = new Intent(getApplicationContext(),	MenuActivity.class);
					finish();
					startActivity(intent);
					skDetector.showErrorMessage();
				}
				
				
			}
		}
	};
}
