package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.AppUtil;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.SeatbyBus;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class SeatDetailsbyBusIDListAdapter extends BaseAdapter {
	private List<SeatbyBus> seat_list;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public SeatDetailsbyBusIDListAdapter(Activity aty,List<SeatbyBus> seat_details) {
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
			convertView = mInflater.inflate(R.layout.activity_seat_details_item, null);
			holder = new ViewHolder();
			holder.date = (TextView)convertView .findViewById(R.id.txtDepartureDate);
			holder.trip = (TextView)convertView .findViewById(R.id.txtTrip);
			holder.time = (TextView)convertView .findViewById(R.id.txtTime);
			holder.classes = (TextView)convertView .findViewById(R.id.txtClasses);
			holder.agent = (TextView)convertView .findViewById(R.id.txtAgent);
			holder.seatno = (TextView)convertView .findViewById(R.id.txtSeatNo);
			holder.qty = (TextView)convertView .findViewById(R.id.txtQty);
			holder.price = (TextView)convertView .findViewById(R.id.txtPrice);
			holder.commission = (TextView)convertView .findViewById(R.id.txtCommission);
			holder.total_amount = (TextView)convertView .findViewById(R.id.txtTotalAmount);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.date.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.trip.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.time.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.classes.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.agent.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.seatno.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.qty.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.price.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.commission.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		holder.total_amount.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 10;
		
		holder.date.setText(AppUtil.changeDate(seat_list.get(position).getDepartureDate()));
		holder.trip.setText(seat_list.get(position).getFromTo());
		holder.time.setText(seat_list.get(position).getTime());
		holder.classes.setText(seat_list.get(position).getClasses());
		holder.agent.setText(seat_list.get(position).getAgentName());
		holder.seatno.setText(seat_list.get(position).getSeatNo());
		holder.qty.setText(seat_list.get(position).getSoldSeat().toString());
		holder.price.setText(seat_list.get(position).getPrice().toString());
		holder.commission.setText((seat_list.get(position).getPrice() - seat_list.get(position).getCommission()) +"("+seat_list.get(position).getCommission().toString()+")");
		holder.total_amount.setText(String.format("%,d", seat_list.get(position).getTotalAmount()));
		
		return convertView;
	}

	static class ViewHolder {
		TextView date, trip, time, classes, agent, seatno, qty, price, commission, total_amount;
				
	}

}
