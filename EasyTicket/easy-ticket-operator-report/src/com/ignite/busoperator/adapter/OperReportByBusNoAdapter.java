package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.SeatDetailsbyBusnoActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.TripsbyDate;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class OperReportByBusNoAdapter extends BaseAdapter {
	private List<TripsbyDate> OReportOne;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public OperReportByBusNoAdapter(Activity aty,List<TripsbyDate> operReportone) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		mInflater = LayoutInflater.from(aty);
		this.OReportOne=operReportone;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return OReportOne.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return OReportOne.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView = mInflater.inflate(R.layout.operbusno_item, null);
			holder = new ViewHolder();
			holder.bus_no = (TextView)convertView .findViewById(R.id.txtBusNo);
			holder.trip = (TextView)convertView .findViewById(R.id.txt_trip);
			holder.trip_date = (TextView)convertView .findViewById(R.id.txtTripDate);
			holder.time = (TextView)convertView .findViewById(R.id.txtTime);
			holder.total_tickets = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.total = (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.bus_no.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 6;
		holder.trip.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 6;
		holder.trip_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 6;
		holder.time.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 6;
		holder.total_tickets.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 6;
		holder.total.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 6;
		holder.bus_no.setText(OReportOne.get(position).getBus_no());
		holder.trip.setText(OReportOne.get(position).getFrom()+" - "+OReportOne.get(position).getTo());
		holder.trip_date.setText(OReportOne.get(position).getDeparture_date());
		holder.time.setText(OReportOne.get(position).getTime());
		holder.total_tickets.setText(OReportOne.get(position).getPurchased_total_seat()+"/"+OReportOne.get(position).getTotal_seat());
		holder.total.setText(OReportOne.get(position).getTotal_amout().toString());
		holder.viewDetails.setTag(OReportOne.get(position).getBus_id());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				SharedPreferences sharedPreferences = aty.getApplicationContext().getSharedPreferences("SearchbyOperator",Activity.MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.putString("bus_id", v.getTag().toString());
				editor.commit();
				Intent next = new Intent(aty.getApplication(), SeatDetailsbyBusnoActivity.class);
				aty.startActivity(next);
			}
		});
		return convertView;
	}

	static class ViewHolder {
		TextView  bus_no,trip, trip_date, time , total_tickets, total;
		Button viewDetails;
				
	}

}
