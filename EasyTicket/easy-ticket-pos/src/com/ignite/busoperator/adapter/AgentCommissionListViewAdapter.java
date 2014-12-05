package com.ignite.busoperator.adapter;

import java.util.ArrayList;
import java.util.List;
import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.AgentCommission;
import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class AgentCommissionListViewAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<AgentCommission> listItem;
	private Activity aty;
	private ArrayList<AgentCommission> arraylist;
	public AgentCommissionListViewAdapter(Activity aty, List<AgentCommission> _list){
		mInflater = LayoutInflater.from(aty);
		this.listItem = _list;
		this.arraylist = new ArrayList<AgentCommission>();
		this.arraylist.addAll(_list);
		this.aty = aty;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public AgentCommission getItem(int position) {
		return listItem.get(position);
	}

	public long getItemId(int arg0) {
		// TODO Auto-generated method stub
		return arg0;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		ViewHolder holder = null;
		if (convertView == null) {
			holder = new ViewHolder();
        	convertView = mInflater.inflate(R.layout.list_item_agent_commission, null);
        	holder.txt_trip = (TextView) convertView.findViewById(R.id.txt_trip);
        	holder.txt_time = (TextView) convertView.findViewById(R.id.txt_time);
        	holder.txt_commission_name = (TextView) convertView.findViewById(R.id.txt_commission_name);
        	holder.txt_commission = (TextView) convertView.findViewById(R.id.txt_commission);
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.txt_trip.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		holder.txt_time.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		holder.txt_commission_name.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		holder.txt_commission.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 4;
		
		holder.txt_trip.setText(getItem(position).getTrip());
		holder.txt_time.setText(getItem(position).getTime());
		holder.txt_commission_name.setText(getItem(position).getCommissionName());
		holder.txt_commission.setText(getItem(position).getCommission().toString());
				
		return convertView;
	}
	
	static class ViewHolder {
		TextView txt_trip, txt_time, txt_commission_name, txt_commission;
		
	}
}
