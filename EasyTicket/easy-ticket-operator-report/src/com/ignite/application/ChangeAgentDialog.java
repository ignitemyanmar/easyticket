package com.ignite.application;

import java.util.List;

import com.ignite.busoperator.AgentListActivity;
import com.ignite.busoperator.R;
import com.ignite.busoperator.model.Agents;

import android.app.Dialog;
import android.content.Context;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.AdapterView.OnItemClickListener;

public class ChangeAgentDialog extends Dialog {

	private Button btn_cancel;
	private Button btn_save;
	private Callback mCallback;
	private AutoCompleteTextView edt_change;
	
	private Context ctx;

	public ChangeAgentDialog(Context context) {
		super(context);
		// TODO Auto-generated constructor stub
		ctx = context;
		setContentView(R.layout.dialog_change_anagent);
		btn_save = (Button) findViewById(R.id.btn_change_agent);
		btn_cancel = (Button) findViewById(R.id.btn_cancel);
		edt_change = (AutoCompleteTextView) findViewById(R.id.edt_agent);
		
		btn_cancel.setOnClickListener(clickListener);
		btn_save.setOnClickListener(clickListener);
		setupAgents(edt_change);
		setTitle("ကားဂိတ္ အမည္ ခ်ိန္း ရန္");
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
			if(v == btn_save){
				if(mCallback != null){
					if(AgentID.length() != 0){
						dismiss();
						mCallback.onSave(AgentID);
					}else{
						edt_change.setError("Please Choose Agents.");
					}
					
				}
			}
		}
	};
	protected String AgentID = "";
	
	public void setCallbackListener(Callback mCallback){
		this.mCallback = mCallback;
	}
	
	private void setupAgents(AutoCompleteTextView textView){
		List<Agents> agentList = AgentListActivity.agentList;
		ArrayAdapter<Agents> agentListAdapter = new ArrayAdapter<Agents>(ctx, android.R.layout.simple_dropdown_item_1line, agentList);
		textView.setAdapter(agentListAdapter);
		textView.setOnItemClickListener(new OnItemClickListener() {

			public void onItemClick(AdapterView<?> arg0, View arg1,
					int arg2, long arg3) {
				// TODO Auto-generated method stub
				Log.i("", "Hello Selected Agent ID = "+ ((Agents)arg0.getAdapter().getItem(arg2)).getId());
	           	AgentID = ((Agents)arg0.getAdapter().getItem(arg2)).getId().toString();
			}
		});
		
		textView.addTextChangedListener(new TextWatcher() {
			
			public void onTextChanged(CharSequence s, int start, int before, int count) {
				// TODO Auto-generated method stub
				Log.i("","Hello Text Change: "+ s);
			}
			
			public void beforeTextChanged(CharSequence s, int start, int count,
					int after) {
				// TODO Auto-generated method stub
				
			}
			
			public void afterTextChanged(Editable s) {
				// TODO Auto-generated method stub
				
			}
		});
	}
	
	public interface Callback{
		void onSave(String agentId);
		void onCancel();
	}

}
