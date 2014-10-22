package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.SeatsbyTrip;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class SeatDetailsAdapter extends BaseAdapter {
	private List<SeatsbyTrip> seat_list;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public SeatDetailsAdapter(Activity aty,List<SeatsbyTrip> seat_details) {
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
			holder.seatno = (TextView)convertView .findViewById(R.id.txtseat_no);
			holder.buyer = (TextView)convertView .findViewById(R.id.txtbuyer);
			holder.seller = (TextView)convertView .findViewById(R.id.txtseller);
			holder.prices = (TextView)convertView .findViewById(R.id.txtPrice);
			holder.commission = (TextView)convertView .findViewById(R.id.txtCommission);
			holder.vouncherNo = (TextView)convertView .findViewById(R.id.txtvouncher_no);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.seatno.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 6;
		holder.buyer.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 6;
		holder.seller.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 6;
		holder.prices.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 6;
		holder.commission.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 6;
		holder.vouncherNo.getLayoutParams().width = DeviceUtil.getInstance(aty).getWidth() / 6;
		
		holder.seatno.setText(seat_list.get(position).getSeat_no());
		holder.buyer.setText(seat_list.get(position).getCustomer_name());
		holder.seller.setText(seat_list.get(position).getAgent_name());
		if(seat_list.get(position).getFree_ticket() == 1){
			holder.prices.setText("Free Ticket");
			holder.commission.setText("Free Ticket");
		}else{
			holder.prices.setText(seat_list.get(position).getPrice().toString());
			holder.commission.setText((seat_list.get(position).getPrice() - seat_list.get(position).getCommission())+"("+seat_list.get(position).getCommission().toString()+")");
		}
		holder.vouncherNo.setText(seat_list.get(position).getTicket_no());
		return convertView;
	}

	static class ViewHolder {
		TextView seatno , buyer, seller, prices,commission, vouncherNo;
				
	}

}
