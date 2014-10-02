package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.CreditList;
import android.app.Activity;
import android.content.SharedPreferences;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.TextView;
import android.widget.CompoundButton.OnCheckedChangeListener;

public class CreditListViewAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<CreditList> listItem;
	private Activity aty;
	private Callback mCallbacks;
	public CreditListViewAdapter(Activity aty, List<CreditList> _list){
		mInflater = LayoutInflater.from(aty);
		listItem = _list;
		this.aty = aty;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public CreditList getItem(int position) {
		return listItem.get(position);
	}

	public long getItemId(int arg0) {
		// TODO Auto-generated method stub
		return arg0;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		
		ViewHolder holder = null;
		if (convertView == null) {
			holder = new ViewHolder();
        	convertView = mInflater.inflate(R.layout.list_item_credit, null);
        	holder.txt_order_date = (TextView) convertView.findViewById(R.id.txt_order_date);
        	holder.txt_trip = (TextView) convertView.findViewById(R.id.txt_trip);
        	holder.txt_invoice_no = (TextView) convertView.findViewById(R.id.txt_invoice_no);
        	holder.txt_total_ticket = (TextView) convertView.findViewById(R.id.txt_total_ticket);
        	holder.txt_total_amount = (TextView) convertView.findViewById(R.id.txt_total_amount);
        	holder.txt_percented_amount = (TextView) convertView.findViewById(R.id.txt_percented_amount);
        	holder.chk_delete = (CheckBox) convertView.findViewById(R.id.chk_delete);
        	holder.btn_action = (Button) convertView.findViewById(R.id.btn_action);
        	
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.txt_order_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 7;
		holder.txt_trip.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 7;
		holder.txt_invoice_no.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 7;
		holder.txt_total_ticket.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 7;
		holder.txt_total_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 7;
		holder.txt_percented_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 7;
		
		holder.txt_order_date.setText(getItem(position).getOrderdate().toString());
		holder.txt_trip.setText(getItem(position).getTrip().toString());
		holder.txt_invoice_no.setText(getItem(position).getId().toString());
		holder.txt_total_ticket.setText(getItem(position).getTotalTicket().toString());
		Integer commissionPrice = getItem(position).getPrice() - getItem(position).getCommission();
		holder.txt_total_amount.setText(commissionPrice.toString()+"("+getItem(position).getCommission().toString()+")");
		holder.txt_percented_amount.setText(getItem(position).getGrandTotal().toString());
		
		holder.chk_delete.setTag(position);
		holder.chk_delete.setOnCheckedChangeListener(new OnCheckedChangeListener() {
			
			public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
				// TODO Auto-generated method stub
				int pos = (Integer) buttonView.getTag();
				if(mCallbacks != null){
					mCallbacks.onChildItemCheckedChange(pos, isChecked);
				}
			}
		});
		
		SharedPreferences pref = aty.getSharedPreferences("User", Activity.MODE_PRIVATE);
		int role = pref.getInt("user_role", 0);
		if(role == 0){
			holder.btn_action.setVisibility(View.GONE);
		}else{
			holder.btn_action.setVisibility(View.VISIBLE);
		}
		holder.btn_action.setTag(position);
		holder.btn_action.setOnClickListener(new OnClickListener() {
			
			public void onClick(View v) {
				// TODO Auto-generated method stub
				int pos = (Integer) v.getTag();
				if(mCallbacks != null){
					mCallbacks.onClickActionButton(pos);
				}
			}
		});
		return convertView;
	}
	
	public void setCallbacks(Callback listener) {
        mCallbacks = listener;
    }
	
	public interface Callback{
		public void onChildItemCheckedChange(int position,boolean isChecked);
		public void onClickActionButton(int position);
	}
	
	static class ViewHolder {
		TextView txt_order_date,txt_trip, txt_invoice_no, txt_total_ticket, txt_total_amount, txt_percented_amount;
		CheckBox chk_delete;
		Button btn_action;
	}
}
