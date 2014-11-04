package com.ignite.busoperator.adapter;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;

import com.ignite.busoperator.OperatorReportByBusNo;
import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.TripsbyOperator;

import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.text.format.DateFormat;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class OperatorDateListAdapter extends BaseAdapter {
	private List<TripsbyOperator> OperDate;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public OperatorDateListAdapter(Activity aty,List<TripsbyOperator> operdate) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		mInflater = LayoutInflater.from(aty);
		this.OperDate=operdate;
		
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return OperDate.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return OperDate.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}
	public static String changeDate(String date){
		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		Date StartDate = null;
		try {
			StartDate = df.parse(date);
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return DateFormat.format("dd/MM/yyyy",StartDate).toString();
	}
	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView = mInflater.inflate(R.layout.operdate_item, null);
			holder = new ViewHolder();
			holder.order_date = (TextView)convertView .findViewById(R.id.txtOrderDate);
			holder.total_tickets = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.total = (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		holder.order_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 4;
		holder.total_tickets.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 4;
		holder.total.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth() / 1.5) / 4;
		holder.order_date.setText(changeDate(OperDate.get(position).getOrder_date()));
		holder.total_tickets.setText(OperDate.get(position).getPurchased_total_seat().toString());
		holder.total.setText(OperDate.get(position).getTotal_amout().toString());
		holder.viewDetails.setTag(OperDate.get(position).getOrder_date());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				SharedPreferences sharedPreferences = aty.getApplicationContext().getSharedPreferences("SearchbyOperator",Activity.MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.putString("date", v.getTag().toString());
				editor.commit();
				Intent next = new Intent(aty.getApplication(), OperatorReportByBusNo.class);
				aty.startActivity(next);
			}
		});
		
		return convertView;
	}

	static class ViewHolder {
		TextView  order_date, total_tickets, total;
		Button viewDetails;
				
	}

}
