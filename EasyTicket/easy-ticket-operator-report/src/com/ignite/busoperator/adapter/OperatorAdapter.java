package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.model.Operator;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

public class OperatorAdapter extends BaseAdapter {

	private TextView txtTitle;
	private Activity aty;
	private List<Operator>Operator_list;
	
	public OperatorAdapter(Activity aty, List<Operator>oper_list) {
		super();
		// TODO Auto-generated constructor stub
		this.Operator_list = oper_list;
		this.aty = aty;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return Operator_list.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return Operator_list.get(position);
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
	        txtTitle.setText(Operator_list.get(position).getName());
	        txtTitle.setSingleLine(true);
		return convertView;
	}
	
	public View getDropDownView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		if (convertView == null) {
        	LayoutInflater mInflater = (LayoutInflater)
                    aty.getSystemService(Activity.LAYOUT_INFLATER_SERVICE);
            convertView = mInflater.inflate(R.layout.spiner_sub_item_list, null);
        }
        txtTitle = (TextView) convertView.findViewById(R.id.txtTitle);        
        txtTitle.setText(Operator_list.get(position).getName());
		return convertView;
	}


}
