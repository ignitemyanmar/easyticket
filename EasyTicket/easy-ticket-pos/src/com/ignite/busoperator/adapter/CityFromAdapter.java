package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.model.From;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class CityFromAdapter extends BaseAdapter {
	
	private TextView txtTitle;
	private List<From> from;
	private Activity aty;
	
	public CityFromAdapter( Activity aty ,List<From> from) {
		super();
		this.from = from;
		this.aty = aty;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return from.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return from.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		if (convertView == null) {
        	LayoutInflater mInflater = (LayoutInflater)aty.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.spiner_item_list, null);
        }
        txtTitle = (TextView) convertView.findViewById(R.id.txtTitle);
        txtTitle.setText(from.get(position).getName());
        txtTitle.setSingleLine(true);
        return convertView;
	}

	@Override
	public View getDropDownView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		if (convertView == null) {
        	LayoutInflater mInflater = (LayoutInflater)
                    aty.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.spiner_sub_item_list, null);
        }
        txtTitle = (TextView) convertView.findViewById(R.id.txtTitle);        
        txtTitle.setText(from.get(position).getName());
		return convertView;
	}

}
