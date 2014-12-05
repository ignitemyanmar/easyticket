package com.ignite.application;

import java.util.List;

import com.ignite.busoperator.AgentListActivity;
import com.ignite.busoperator.R;
import com.ignite.busoperator.model.Trip;
import com.ignite.busoperator.model.Trip;

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
import android.widget.RadioGroup;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.RadioButton;
import android.widget.RadioGroup.OnCheckedChangeListener;

public class CommissionDialog extends Dialog {

	private Button btn_cancel;
	private Button btn_save;
	private Callback mCallback;
	private EditText edt_commission;
	
	private Context ctx;
	private RadioButton rdo_percentage;
	private RadioButton rdo_fix_amount;
	protected String commissionType = "2";
	private RadioGroup rdo_gp_type;
	private AutoCompleteTextView ato_edt_trip;

	public CommissionDialog(Context context) {
		super(context);
		// TODO Auto-generated constructor stub
		ctx = context;
		setContentView(R.layout.dialog_commission);
		btn_save = (Button) findViewById(R.id.btn_change_agent);
		btn_cancel = (Button) findViewById(R.id.btn_cancel);
		edt_commission = (EditText) findViewById(R.id.edt_commission);
		ato_edt_trip =  (AutoCompleteTextView) findViewById(R.id.ato_edt_trip);
		
		rdo_gp_type = (RadioGroup) findViewById(R.id.radioGroup1);
		rdo_percentage = (RadioButton) findViewById(R.id.rdo_percentage);
		rdo_fix_amount = (RadioButton) findViewById(R.id.rdo_fix_amount);
		
		rdo_gp_type.setOnCheckedChangeListener(changeListener);
		
		btn_cancel.setOnClickListener(clickListener);
		btn_save.setOnClickListener(clickListener);
		setTitle("Commission ေၾကးသြင္းရန္");
	}
	public void setTrips(List<Trip> trips){
		Log.i("","Hello Trips: "+ trips.toString());
		setupTrip(ato_edt_trip, trips);
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
					if(edt_commission.getText().length() != 0){
						dismiss();
						mCallback.onSave(TripID, commissionType, edt_commission.getText().toString());
					}else if(TripID == null){
						ato_edt_trip.setError("Please Choose Trip.");
					}else if(edt_commission.getText().length() == 0){
						edt_commission.setError("Please Enter Commission Amount.");
					}
					
				}
			}
		}
	};
	protected String TripID;
	
	private void setupTrip(AutoCompleteTextView textView, List<Trip> trips){
		ArrayAdapter<Trip> agentListAdapter = new ArrayAdapter<Trip>(ctx, android.R.layout.simple_dropdown_item_1line, trips);
		textView.setAdapter(agentListAdapter);
		textView.setOnItemClickListener(new OnItemClickListener() {

			public void onItemClick(AdapterView<?> arg0, View arg1,
					int arg2, long arg3) {
				// TODO Auto-generated method stub
				Log.i("", "Hello Selected Agent ID = "+ ((Trip)arg0.getAdapter().getItem(arg2)).getId());
	           	TripID = ((Trip)arg0.getAdapter().getItem(arg2)).getId().toString();
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
	
	private OnCheckedChangeListener changeListener = new OnCheckedChangeListener() {
		
		public void onCheckedChanged(RadioGroup group, int checkedId) {
			// TODO Auto-generated method stub
			if(checkedId == rdo_percentage.getId()){
				commissionType = "2";
			}
		
			if(checkedId == rdo_fix_amount.getId()){
				commissionType = "1";
			}
		}
	};
	
	
	public void setCallbackListener(Callback mCallback){
		this.mCallback = mCallback;
	}
	
	public interface Callback{
		void onSave(String tripId, String commissionType, String commissionAmount);
		void onCancel();
	}

}
