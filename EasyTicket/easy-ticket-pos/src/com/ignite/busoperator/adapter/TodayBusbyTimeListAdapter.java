package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.AgentReportByBusNo;
import com.ignite.busoperator.R;
import com.ignite.busoperator.SeatDetailsbyTodayBusActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.TodayBusbyTime;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class TodayBusbyTimeListAdapter extends BaseAdapter {
	private List<TodayBusbyTime> busReports;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public TodayBusbyTimeListAdapter(Activity aty,List<TodayBusbyTime> operdate) {
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
			convertView = mInflater.inflate(R.layout.list_item_today_sale_bytime, null);
			holder = new ViewHolder();
			holder.bus_no = (TextView)convertView .findViewById(R.id.txtBusNo);
			holder.bus_class = (TextView)convertView .findViewById(R.id.txtClass);
			holder.seat = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.total= (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		holder.bus_no.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		holder.bus_class.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		holder.seat.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		holder.total.getLayoutParams().width = (int) DeviceUtil.getInstance(aty).getWidth() / 5;
		
		holder.bus_no.setText(busReports.get(position).getBus_no());
		holder.bus_class.setText(busReports.get(position).getClass_());
		holder.seat.setText(busReports.get(position).getSold_seat().toString());
		holder.total.setText(busReports.get(position).getSold_amount().toString());
		holder.viewDetails.setTag(busReports.get(position).getBus_id());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				Bundle bundle = new Bundle();
				bundle.putString("bus_id", v.getTag().toString());
				Intent next = new Intent(aty.getApplication(), SeatDetailsbyTodayBusActivity.class).putExtras(bundle);
				aty.startActivity(next);
			}
		});
		
		return convertView;
	}

	static class ViewHolder {
		TextView  bus_no, bus_class, seat, total;
		Button viewDetails;
				
	}

}
