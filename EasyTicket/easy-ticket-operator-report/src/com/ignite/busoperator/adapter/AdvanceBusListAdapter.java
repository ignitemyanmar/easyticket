package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.AdvanceBusActivity;
import com.ignite.busoperator.AgentReportByBusNo;
import com.ignite.busoperator.OperatorReportByBusNo;
import com.ignite.busoperator.R;
import com.ignite.busoperator.SalesbyDailyActivity;
import com.ignite.busoperator.TodayBusActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.AdvanceBus;

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

public class AdvanceBusListAdapter extends BaseAdapter {
	private List<AdvanceBus> busReports;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public AdvanceBusListAdapter(Activity aty,List<AdvanceBus> operdate) {
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
			holder.time = (TextView)convertView .findViewById(R.id.txtTime);
			holder.trip = (TextView)convertView .findViewById(R.id.txtTrip);
			holder.seat = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.total= (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		holder.time.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		holder.trip.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		holder.seat.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		holder.total.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		
		holder.time.setText(busReports.get(position).getDeparture_date());
		holder.trip.setText(busReports.get(position).getFrom() +"-"+ busReports.get(position).getTo());
		holder.seat.setText(busReports.get(position).getPurchased_total_seat().toString());
		holder.total.setText(busReports.get(position).getTotal_amout().toString());
		holder.viewDetails.setTag(busReports.get(position).getDeparture_date());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				Bundle bundle = new Bundle();
				bundle.putString("departure_date", v.getTag().toString());
				bundle.putString("date",SalesbyDailyActivity.selectedDate);
				Intent next = new Intent(aty.getApplication(), AdvanceBusActivity.class).putExtras(bundle);
				aty.startActivity(next);
			}
		});
		
		return convertView;
	}

	static class ViewHolder {
		TextView  time, trip, seat, total;
		Button viewDetails;
				
	}

}
