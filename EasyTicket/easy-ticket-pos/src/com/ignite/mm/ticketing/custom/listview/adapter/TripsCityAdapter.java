package com.ignite.mm.ticketing.custom.listview.adapter;

import java.util.List;

import com.ignite.mm.ticketing.R;
import com.ignite.mm.ticketing.sqlite.database.model.TripsCollection;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class TripsCityAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<TripsCollection> listItem;
	public TripsCityAdapter(Activity aty, List<TripsCollection> _list){
		mInflater = LayoutInflater.from(aty);
		listItem = _list;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public TripsCollection getItem(int position) {
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
        	convertView = mInflater.inflate(R.layout.list_item_trips_city, null);
        	holder.TripCity = (TextView)convertView.findViewById(R.id.txt_trips_city);
        	
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		holder.TripCity.setText(getItem(position).getFrom()+" - "+getItem(position).getTo());
		return convertView;
	}
	static class ViewHolder {
		TextView TripCity;
	}
}
