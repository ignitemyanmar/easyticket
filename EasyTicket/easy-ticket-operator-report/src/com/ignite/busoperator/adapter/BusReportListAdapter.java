package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.AgentReportByBusNo;
import com.ignite.busoperator.OperatorReportByBusNo;
import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.BusReport;

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

public class BusReportListAdapter extends BaseAdapter {
	private List<BusReport> busReports;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public BusReportListAdapter(Activity aty,List<BusReport> operdate) {
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
			convertView = mInflater.inflate(R.layout.list_item_busreport, null);
			holder = new ViewHolder();
			
			holder.bus_class = (TextView)convertView .findViewById(R.id.txtClass);
			holder.trip_time = (TextView)convertView .findViewById(R.id.txtTime);
			holder.departure_date = (TextView)convertView .findViewById(R.id.txt_departure_date);
			holder.total_tickets = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.total = (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.bus_class.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 6;
		holder.trip_time.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 6;
		holder.departure_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 6;
		holder.total_tickets.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 6;
		holder.total.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 6;
		
		holder.bus_class.setText(busReports.get(position).getClasses());
		holder.trip_time.setText(busReports.get(position).getTime());
		holder.departure_date.setText(busReports.get(position).getDepartureDate());
		holder.total_tickets.setText(busReports.get(position).getPurchasedTotalSeat().toString());
		holder.total.setText(busReports.get(position).getTotalAmout().toString());
		holder.viewDetails.setTag(busReports.get(position).getBusId());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				SharedPreferences sharedPreferences = aty.getApplicationContext().getSharedPreferences("SearchbyOperator",Activity.MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.putString("bus_occ_id", v.getTag().toString());
				editor.commit();
				Intent next = new Intent(aty.getApplication(), AgentReportByBusNo.class);
				aty.startActivity(next);
			}
		});
		
		return convertView;
	}

	static class ViewHolder {
		TextView  departure_date, bus_class, trip_time, total_tickets, total;
		Button viewDetails;
				
	}

}
