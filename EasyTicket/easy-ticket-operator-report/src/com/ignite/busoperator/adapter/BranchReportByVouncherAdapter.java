package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.SeatDetailsbyBusnoActivity;
import com.ignite.busoperator.model.BranchReportByVouncher;

import android.app.Activity;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class BranchReportByVouncherAdapter extends BaseAdapter {
	private List<BranchReportByVouncher> branch_vouncher;
	private LayoutInflater mInflater;
	private Activity aty;
	
	public BranchReportByVouncherAdapter(Activity aty,List<BranchReportByVouncher> reportVouncher) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		mInflater = LayoutInflater.from(aty);
		this.branch_vouncher= reportVouncher;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return branch_vouncher.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return branch_vouncher.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView = mInflater.inflate(R.layout.branch_vouncher_item, null);
			holder = new ViewHolder();
			holder.vouncher_no = (TextView)convertView .findViewById(R.id.txtVouncherNo);
			holder.tickets = (TextView)convertView .findViewById(R.id.txtTicket);
			holder.total = (TextView)convertView .findViewById(R.id.txtTotal);
			holder.viewDetails = (Button)convertView.findViewById(R.id.btnDetails);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.vouncher_no.setText(branch_vouncher.get(position).getVouncherno());
		holder.tickets.setText(branch_vouncher.get(position).getTickets());
		holder.total.setText(branch_vouncher.get(position).getTotal());
		holder.viewDetails.setOnClickListener(new OnClickListener() {

			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent next = new Intent(aty.getApplication(), SeatDetailsbyBusnoActivity.class);
				aty.startActivity(next);
			}
		});
		return convertView;
	}

	static class ViewHolder {
		TextView vouncher_no , tickets, total;
		Button viewDetails;
				
	}

}
