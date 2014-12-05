package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.PopularTriptime;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class PopularTriptimeListViewAdapter extends BaseAdapter {
	private List<PopularTriptime> trip_list;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public PopularTriptimeListViewAdapter(Activity aty,List<PopularTriptime> seat_details) {
		super();
		// TODO Auto-generated constructor stub
		mInflater = LayoutInflater.from(aty);
		this.trip_list=seat_details;
		this.aty = aty;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return trip_list.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return trip_list.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView 		= mInflater.inflate(R.layout.list_item_popular_triptime, null);
			holder 				= new ViewHolder();
			holder.time			= (TextView)convertView .findViewById(R.id.txt_time);
			holder.classes		= (TextView)convertView .findViewById(R.id.txt_classes);
			holder.total_ticket = (TextView)convertView .findViewById(R.id.txt_total_ticket);
			holder.total_amount = (TextView)convertView .findViewById(R.id.txt_total_amount);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.time.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.classes.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.total_ticket.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.total_amount.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		
		holder.time.setText(trip_list.get(position).getTime());
		holder.classes.setText(trip_list.get(position).getClasses());
		holder.total_ticket.setText(trip_list.get(position).getSoldTotalSeat().toString());
		holder.total_amount.setText(trip_list.get(position).getTotalAmount().toString());
		
		return convertView;
	}
	
	static class ViewHolder {
		TextView time, classes, total_ticket, total_amount;
	}

}
