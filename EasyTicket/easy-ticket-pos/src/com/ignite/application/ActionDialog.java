package com.ignite.application;

import com.ignite.busoperator.R;

import android.app.Dialog;
import android.content.Context;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;

public class ActionDialog extends Dialog {

	private Button btn_cancel;
	private Button btn_delete;
	private Callback mCallback;
	private Button btn_change;

	public ActionDialog(Context context) {
		super(context);
		// TODO Auto-generated constructor stub
		setContentView(R.layout.dialog_delete_changeanagent);
		btn_delete = (Button) findViewById(R.id.btn_delete);
		btn_cancel = (Button) findViewById(R.id.btn_cancel);
		btn_change = (Button) findViewById(R.id.btn_change_agent);
		
		btn_cancel.setOnClickListener(clickListener);
		btn_change.setOnClickListener(clickListener);
		btn_delete.setOnClickListener(clickListener);
		
		setTitle("Please Choose One");

	}
	private View.OnClickListener clickListener = new View.OnClickListener() {
		
		public void onClick(View v) {
			// TODO Auto-generated method stub
			if(v == btn_cancel){
				if(mCallback != null){
					dismiss();
					mCallback.onCancel();
				}
			}
			if(v == btn_change){
				if(mCallback != null){
					dismiss();
					mCallback.onChange();
				}
			}
			if(v == btn_delete){
				if(mCallback != null){
					dismiss();
					mCallback.onDelete();
				}
			}
		}
	};
	
	public void setCallbackListener(Callback mCallback){
		this.mCallback = mCallback;
	}
	
	public interface Callback{
		void onDelete();
		void onChange();
		void onCancel();
	}

}
