package com.ignite.mm.ticketing;


import retrofit.Callback;
import retrofit.RetrofitError;
import retrofit.client.Response;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.TextView;
import com.actionbarsherlock.app.ActionBar;
import com.actionbarsherlock.app.SherlockActivity;
import com.ignite.mm.ticketing.application.LoginUser;
import com.ignite.mm.ticketing.application.SecureKey;
import com.ignite.mm.ticketing.clientapi.NetworkEngine;
import com.ignite.mm.ticketing.sqlite.database.model.AccessToken;
import com.smk.skalertmessage.SKToastMessage;
import com.smk.skconnectiondetector.SKConnectionDetector;

public class UserLogin extends SherlockActivity {

	private EditText txtEmail;
	private EditText txtPassword;
	private Context ctx = this;
	private Button[] buttons = new Button[3];
	private ProgressDialog dialog;
//	private String UserEmail, UserPassword;
	private ActionBar actionBar;
	private TextView actionBarTitle;
	private ImageButton actionBarBack;
	private SKConnectionDetector connectionDetector;
	
	public static boolean isSkip = false;
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		setContentView(R.layout.activity_login);
		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.action_bar);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.action_bar_title);
		actionBarBack = (ImageButton) actionBar.getCustomView().findViewById(
				R.id.action_bar_back);
		actionBarBack.setOnClickListener(clickListener);
		actionBarTitle.setText("User's Login");
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
		
		txtEmail = (EditText) this.findViewById(R.id.txt_login_email);
		txtPassword = (EditText) this.findViewById(R.id.txt_login_password);

		buttons[0] = (Button) findViewById(R.id.cmd_login);
		buttons[1] = (Button) findViewById(R.id.btn_skip_login);
		buttons[2] = (Button) findViewById(R.id.cmd_to_register);

		for (int i = 0; i < buttons.length; i++) {

			buttons[i].setOnClickListener(clickListener);

		}
		connectionDetector = SKConnectionDetector.getInstance(this);
		connectionDetector.setMessageStyle(SKConnectionDetector.VERTICAL_TOASH);
		if(!connectionDetector.isConnectingToInternet())
			connectionDetector.showErrorMessage();
		
	}

	private OnClickListener clickListener = new OnClickListener() {

		private String userEmail;

		public void onClick(View v) {
			if (v == actionBarBack) {
				finish();
			}
			//for Login button
			if (v == buttons[0]) {
				if(connectionDetector.isConnectingToInternet()){
					if(checkFields()){
						dialog = ProgressDialog.show(ctx, ""," Please wait...", true);
		    			dialog.setCancelable(true);
		    			if(txtEmail.getText().toString().contains("@")){
		    				userEmail = txtEmail.getText().toString();
		    			}else{
		    				userEmail = txtEmail.getText().toString()+"@gmail.com";
		    			}
						NetworkEngine.getInstance().getAccessToken(SecureKey.getGrant(), SecureKey.getId(), SecureKey.getKey(), userEmail, txtPassword.getText().toString(), SecureKey.getScope(), SecureKey.getState(), new Callback<AccessToken>() {
							
							public void success(AccessToken arg0, Response arg1) {
								// TODO Auto-generated method stub
								dialog.dismiss();
		   						
								LoginUser user = new LoginUser(UserLogin.this);
								user.setAccessToken(arg0.getAccess_token());
								user.setTokenType(arg0.getToken_type());
								user.setExpires(arg0.getExpires());
								user.setExpiresIn(arg0.getExpires_in());
								user.setRefreshToken(arg0.getRefresh_token());
								user.setUserID(arg0.getUser().getId());
								user.setUserGroupID(arg0.getUser().getOperatorgroup_id());
								user.setLoginUserID(arg0.getUser().getUser_id());
								user.setUserName(arg0.getUser().getName());
								user.setUserType(arg0.getUser().getType());
								user.login();
		   						
								if(isSkip){
									isSkip = false;
									Intent intent = new Intent(getApplicationContext(),	Bus_Info_Activity.class);
									finish();
									startActivity(intent);
								}else{
									Intent intent = new Intent(getApplicationContext(),	BusMenuActivity.class);
			   						startActivity(intent);
								}
							}
							
							public void failure(RetrofitError arg0) {
								// TODO Auto-generated method stub
								dialog.dismiss();
								if(arg0.getResponse() != null){
									if(arg0.getResponse().getStatus() == 400){
										SKToastMessage.showMessage(UserLogin.this, "သင္၏ Login Email ႏွင့္ Password ဟာ မွား ေနပါသည္", SKToastMessage.ERROR);
									}
								}
							}
						});
					}
				}else{
					connectionDetector.showErrorMessage();
					SharedPreferences sharedPreferences = ctx.getSharedPreferences("User",MODE_PRIVATE);
					SharedPreferences.Editor editor = sharedPreferences.edit();
					
					editor.clear();
					editor.commit();
					editor.putString("access_token", null);
					editor.putString("token_type", null);
					editor.putLong("expires", 0);
					editor.putLong("expires_in", 0);
					editor.putString("refresh_token", null);
					editor.putString("user_id", "1");
					editor.putString("user_name", "Elite");
					editor.putString("user_type", "operator");
					editor.commit();
					Intent intent = new Intent(getApplicationContext(),	BusTripsCityActivity.class);
					finish();
					startActivity(intent);
				}
			}
			//for skip button
			if(v == buttons[1])
			{
				SharedPreferences sharedPreferences = ctx.getSharedPreferences("User",MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				
				editor.clear();
				editor.commit();
				
				isSkip = true;
				
				editor.putString("useremail", null);
				editor.commit();
				startActivity(new Intent(ctx, MenuActivity.class));
			}
			
			// for User Register
			if (v == buttons[2]) {
				startActivity(new Intent(ctx, UserRegister.class));
			}

		}
	};
	
	public boolean checkFields() {
		if (txtEmail.getText().toString().length() == 0) {
			txtEmail.setError("Enter The User Email");
			return false;
		}
		if (txtPassword.getText().toString().length() == 0) {
			txtPassword.setError("Enter The User Password");
			return false;
		}

		return true;

	}

}
