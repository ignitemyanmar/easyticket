package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.AgentReportByBusNo;
import com.ignite.busoperator.OperatorReportByBusNo;
import com.ignite.busoperator.R;
import com.ignite.busoperator.SalesbyDailyActivity;
import com.ignite.busoperator.SeatDetailsbyTodayBusActivity;
import com.ignite.busoperator.TodayBusActivity;
import com.ignite.busoperator.application.AppUtil;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.TodayBus;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class TodayBusListAdapter extends BaseAdapter {
	private List<TodayBus> busReports;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public TodayBusListAdapter(Activity aty,List<TodayBus> operdate) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		mInflater = LayoutInflater.from(aty);
		this.busReports=operdate;
		
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return busReports.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return busReports.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView = mInflater.inflate(R.layout.list_item_today_sale, null);
			holder = new ViewHolder();
			holder.date = (TextView)convertView .findViewById(R.id.txtDepartureDate);
			holder.time = (TextView)convertView .findViewById(R.id.txtTime);
			holder.trip = (TextView)convertView .findViewById(R.id.txtTrip);
			holder.classes = (TextView)convertView .findViewById(R.id.txtClasses);
			holder.seat = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.price = (TextView)convertView .findViewById(R.id.txtPrice);
			holder.total= (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		holder.date.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		holder.time.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		holder.trip.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		holder.classes.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		holder.seat.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		holder.price.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		holder.total.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 8;
		
		holder.date.setText(AppUtil.changeDate(busReports.get(position).getDepartureDate()));
		holder.time.setText(busReports.get(position).getTime());
		holder.trip.setText(busReports.get(position).getFromTo());
		holder.classes.setText(busReports.get(position).getClassName());
		holder.seat.setText(busReports.get(position).getSoldSeat().toString());
		holder.price.setText(busReports.get(position).getLocalPrice().toString());
		holder.total.setText(String.format("%,d",busReports.get(position).getTotalAmount()));
		holder.viewDetails.setTag(busReports.get(position));
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				TodayBus todayBus = (TodayBus) v.getTag();
				Bundle bundle = new Bundle();
				bundle.putString("bus_id", todayBus.getBusId().toString());
				bundle.putString("from_to", todayBus.getFromTo()+"("+todayBus.getClassName()+")");
				bundle.putString("date_time", todayBus.getDepartureDate()+" "+todayBus.getTime());
				Intent next = new Intent(aty.getApplication(), SeatDetailsbyTodayBusActivity.class).putExtras(bundle);
				aty.startActivity(next);
			}
		});
		
		return convertView;
	}

	static class ViewHolder {
		TextView  date,time, trip,classes, seat,price, total;
		Button viewDetails;
				
	}

}
