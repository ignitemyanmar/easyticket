package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.PopularTrip;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class PopularTripbyAgentListViewAdapter extends BaseAdapter {
	private List<PopularTrip> trip_list;
	private LayoutInflater mInflater;
	private Activity aty;
	private Callbacks mCallbacks;
	
	public PopularTripbyAgentListViewAdapter(Activity aty,List<PopularTrip> seat_details) {
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
			convertView 		= mInflater.inflate(R.layout.list_item_popular_trip, null);
			holder 				= new ViewHolder();
			holder.name			= (TextView)convertView .findViewById(R.id.txt_trip);
			holder.total_ticket = (TextView)convertView .findViewById(R.id.txt_total_ticket);
			holder.total_amount = (TextView)convertView .findViewById(R.id.txt_total_amount);
			holder.detail 		= (Button)convertView .findViewById(R.id.btn_detail);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.name.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.total_ticket.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.total_amount.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.detail.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		
		holder.name.setText(trip_list.get(position).getTrip());
		holder.total_ticket.setText(trip_list.get(position).getSoldTotalSeat().toString());
		holder.total_amount.setText(trip_list.get(position).getTotalAmount().toString());
		holder.detail.setTag(position);
		holder.detail.setOnClickListener(new View.OnClickListener() {
			
			public void onClick(View v) {
				// TODO Auto-generated method stub
				int positon = (Integer) v.getTag();
				if(mCallbacks != null){
					mCallbacks.onViewDetial(positon);
				}
			}
		});
		
		return convertView;
	}
	
	public void setOnViewDetailListener(Callbacks callbacks){
		this.mCallbacks = callbacks;
	}
	
	public interface Callbacks{
		void onViewDetial(int positon);
	}

	static class ViewHolder {
		TextView name , total_ticket, total_amount;
		Button detail;
				
	}

}
