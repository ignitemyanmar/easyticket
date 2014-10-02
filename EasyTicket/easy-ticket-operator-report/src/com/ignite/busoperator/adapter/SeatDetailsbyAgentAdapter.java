package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.SeatReport;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class SeatDetailsbyAgentAdapter extends BaseAdapter {
	private List<SeatReport> seat_list;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public SeatDetailsbyAgentAdapter(Activity aty,List<SeatReport> seat_details) {
		super();
		// TODO Auto-generated constructor stub
		mInflater = LayoutInflater.from(aty);
		this.seat_list=seat_details;
		this.aty = aty;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return seat_list.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return seat_list.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView = mInflater.inflate(R.layout.list_item_seat_details, null);
			holder = new ViewHolder();
			holder.seatno = (TextView)convertView .findViewById(R.id.txtseat_no);
			holder.buyer = (TextView)convertView .findViewById(R.id.txtbuyer);
			holder.prices = (TextView)convertView .findViewById(R.id.txtPrice);
			holder.ticketNo = (TextView)convertView .findViewById(R.id.txt_ticket_no);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.seatno.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.buyer.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.prices.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		holder.ticketNo.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 4;
		
		holder.seatno.setText(seat_list.get(position).getSeat_no());
		holder.buyer.setText(seat_list.get(position).getCustomer_name());
		holder.prices.setText(seat_list.get(position).getPrice());
		holder.ticketNo.setText(seat_list.get(position).getTicket_no());
		return convertView;
	}

	static class ViewHolder {
		TextView seatno , buyer, prices, ticketNo;
				
	}

}
