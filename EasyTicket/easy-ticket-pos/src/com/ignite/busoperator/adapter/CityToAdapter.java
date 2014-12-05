package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.model.To;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class CityToAdapter extends BaseAdapter {

	private TextView txtTitle;
	private List<To> to;
	private Activity aty;
	
	public CityToAdapter(Activity aty, List<To> to) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		this.to = to;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return to.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return to.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		 if (convertView == null) {
	        	LayoutInflater mInflater = (LayoutInflater)aty.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
	            convertView = mInflater.inflate(R.layout.spiner_item_list, null);
	        }
	        txtTitle = (TextView) convertView.findViewById(R.id.txtTitle);
	        txtTitle.setText(to.get(position).getName());
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
        txtTitle.setText(to.get(position).getName());
		return convertView;
	}

}
