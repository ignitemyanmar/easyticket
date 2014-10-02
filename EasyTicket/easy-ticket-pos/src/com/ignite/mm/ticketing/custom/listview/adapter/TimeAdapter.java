package com.ignite.mm.ticketing.custom.listview.adapter;

import java.util.List;

import com.ignite.mm.ticketing.R;
import com.ignite.mm.ticketing.sqlite.database.model.Time;

import android.app.Activity;
import android.graphics.Color;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class TimeAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<Time> listItem;
	public TimeAdapter(Activity aty, List<Time> _list){
		mInflater = LayoutInflater.from(aty);
		listItem = _list;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public Time getItem(int position) {
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
        	convertView = mInflater.inflate(R.layout.list_item_time, null);
        	holder.Time = (TextView)convertView.findViewById(R.id.txt_time);
        	holder.Classes = (TextView)convertView.findViewById(R.id.txt_class);
        	holder.TotalSeat = (TextView)convertView.findViewById(R.id.txt_total_seat);
        	holder.Line = (View)convertView.findViewById(R.id.v_indicator);
        	
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		holder.Classes.setText(getItem(position).getBus_class());
		/*String timeStr = getItem(position).getTime().replaceAll(" ", "").toLowerCase();
		String timeTypeStr = timeStr.substring(timeStr.length() - 2, timeStr.length());
		if(timeTypeStr.equals("am")){
			holder.Time.setTextColor(Color.parseColor("#FF9408"));
			holder.Line.setBackgroundColor(Color.parseColor("#FF9408"));
		}else{
			holder.Time.setTextColor(Color.parseColor("#32bf3b"));
			holder.Line.setBackgroundColor(Color.parseColor("#32bf3b"));
		}*/
		holder.Time.setText(getItem(position).getTime());
		holder.TotalSeat.setText(getItem(position).getTotal_sold_seat().toString() +"/"+ getItem(position).getTotal_seat().toString());
		if((getItem(position).getTotal_seat() - getItem(position).getTotal_sold_seat()) <= 5){
			holder.Time.setBackgroundResource(R.drawable.ovel_time_red);
			holder.Line.setBackgroundColor(Color.parseColor("#F01D09"));
		}else{
			holder.Time.setBackgroundResource(R.drawable.ovel_time_green);
			holder.Line.setBackgroundColor(Color.parseColor("#349A10"));
		}
		
		return convertView;
	}
	static class ViewHolder {
		TextView Time;
		TextView Classes;
		TextView TotalSeat;
		View Line;
	}
}
