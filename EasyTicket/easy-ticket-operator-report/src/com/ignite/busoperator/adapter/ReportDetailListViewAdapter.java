package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.SaleTicket;
import com.ignite.busoperator.model.SaleTicket;
import com.ignite.busoperator.model.Saleitem;

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

public class ReportDetailListViewAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<SaleTicket> listItem;
	private Activity aty;
	public ReportDetailListViewAdapter(Activity aty, List<SaleTicket> _list){
		mInflater = LayoutInflater.from(aty);
		listItem = _list;
		this.aty = aty;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public SaleTicket getItem(int position) {
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
        	convertView = mInflater.inflate(R.layout.list_item_report_detail, null);
        	holder.txt_invoice_no = (TextView) convertView.findViewById(R.id.txt_invoice_no);
    		holder.txt_customer = (TextView) convertView.findViewById(R.id.txt_customer);
    		holder.txt_seat_no = (TextView) convertView.findViewById(R.id.txt_seat_no);
    		holder.txt_time = (TextView) convertView.findViewById(R.id.txt_time);
    		holder.txt_trip_date = (TextView) convertView.findViewById(R.id.txt_trip_date);
    		holder.txt_trip = (TextView) convertView.findViewById(R.id.txt_trip);
    		holder.txt_price = (TextView) convertView.findViewById(R.id.txt_price);
    		holder.txt_qty = (TextView) convertView.findViewById(R.id.txt_qty);
    		holder.txt_amount = (TextView) convertView.findViewById(R.id.txt_amount);
        	
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.txt_invoice_no.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_customer.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_seat_no.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_time.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_trip_date.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_trip.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_price.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_qty.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		holder.txt_amount.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth()/ 9;
		
		holder.txt_invoice_no.setText(getItem(position).getTicketNo());
		holder.txt_customer.setText(getItem(position).getCustomerName());
		holder.txt_seat_no.setText(getItem(position).getSeatNo());
		holder.txt_time.setText(getItem(position).getTime());
		holder.txt_trip_date.setText(getItem(position).getTripDate());
		holder.txt_trip.setText(getItem(position).getTrip());
		holder.txt_price.setText(getItem(position).getPrice().toString()+"("+ getItem(position).getCommission()+")");
		holder.txt_qty.setText(getItem(position).getQty().toString());
		holder.txt_amount.setText(getItem(position).getTotalAmount().toString());
		
				
		return convertView;
	}
	
	static class ViewHolder {
		TextView txt_invoice_no,txt_customer, txt_seat_no,txt_time, txt_trip_date, txt_trip, txt_price,txt_qty, txt_amount;
		CheckBox chk_delete;
		Button btn_action;
	}
}
