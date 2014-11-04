package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.model.TargetLabel;

import android.app.Activity;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class TargetLabelAdapter extends BaseAdapter {

	private TextView txtTitle;
	private View viewLabel;
	private List<TargetLabel> targetLabel;
	private Activity aty;
	
	public TargetLabelAdapter(Activity aty, List<TargetLabel> targetLabel) {
		super();
		// TODO Auto-generated constructor stub
		this.aty = aty;
		this.targetLabel = targetLabel;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return targetLabel.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return targetLabel.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		 if (convertView == null) {
	        	LayoutInflater mInflater = (LayoutInflater)aty.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
	            convertView = mInflater.inflate(R.layout.spinner_item_lists, null);
	        }
		 	viewLabel = (View) convertView.findViewById(R.id.view_label);
	        txtTitle = (TextView) convertView.findViewById(R.id.txt_label);
	        viewLabel.setBackgroundColor(Color.parseColor(targetLabel.get(position).getColor()));
	        txtTitle.setText(targetLabel.get(position).getName());
	        txtTitle.setSingleLine(true);
		return convertView;
	}

	@Override
	public View getDropDownView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		if (convertView == null) {
        	LayoutInflater mInflater = (LayoutInflater)
                    aty.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.spinner_sub_item_lists, null);
        }
		viewLabel = (View) convertView.findViewById(R.id.view_label);
        txtTitle = (TextView) convertView.findViewById(R.id.txt_label); 
        viewLabel.setBackgroundColor(Color.parseColor(targetLabel.get(position).getColor()));
        txtTitle.setText(targetLabel.get(position).getName());
		return convertView;
	}

}
