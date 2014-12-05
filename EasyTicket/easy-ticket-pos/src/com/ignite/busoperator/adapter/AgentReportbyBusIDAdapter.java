package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.SeatDetailsbyAgentActivity;
import com.ignite.busoperator.SeatDetailsbyBusnoActivity;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.AgentReport;

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

public class AgentReportbyBusIDAdapter extends BaseAdapter {
	private List<AgentReport> agentReports;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public AgentReportbyBusIDAdapter(Activity aty,List<AgentReport> operReportone) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		mInflater = LayoutInflater.from(aty);
		this.agentReports=operReportone;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return agentReports.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return agentReports.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView = mInflater.inflate(R.layout.list_item_agentreport, null);
			holder = new ViewHolder();
			holder.agent = (TextView)convertView .findViewById(R.id.txtAgent);
			holder.total_tickets = (TextView)convertView .findViewById(R.id.txtTotalticket);
			holder.total = (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.agent.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		holder.total_tickets.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		holder.total.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		
		holder.agent.setText(agentReports.get(position).getAgent());
		holder.total_tickets.setText(agentReports.get(position).getSold_tickets().toString()+"/"+agentReports.get(position).getTotal_seats().toString());
		holder.total.setText(agentReports.get(position).getTotal_amount().toString());
		holder.viewDetails.setTag(agentReports.get(position).getAgent_id());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				SharedPreferences sharedPreferences = aty.getApplicationContext().getSharedPreferences("SearchbyOperator",Activity.MODE_PRIVATE);
				SharedPreferences.Editor editor = sharedPreferences.edit();
				editor.putString("agent_id", v.getTag().toString());
				editor.commit();
				Intent next = new Intent(aty.getApplication(), SeatDetailsbyAgentActivity.class);
				aty.startActivity(next);
			}
		});
		return convertView;
	}

	static class ViewHolder {
		TextView  agent, total_tickets, total;
		Button viewDetails;
				
	}

}
