package com.ignite.busoperator.adapter;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import com.ignite.busoperator.R;
import com.ignite.busoperator.adapter.PopularTripListViewAdapter.Callbacks;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.Agents;
import com.ignite.busoperator.model.PopularAgent;

import android.app.Activity;
import android.graphics.Color;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

public class PopularAgentListViewAdapter extends BaseAdapter {
	private List<PopularAgent> agent_list;
	private LayoutInflater mInflater;
	private Activity aty;
	private Callbacks mCallbacks;
	private List<PopularAgent> arraylist;
	
	public PopularAgentListViewAdapter(Activity aty,List<PopularAgent> seat_details) {
		super();
		// TODO Auto-generated constructor stub
		mInflater = LayoutInflater.from(aty);
		this.agent_list=seat_details;
		this.arraylist = new ArrayList<PopularAgent>();
		this.arraylist.addAll(seat_details);
		Log.i("","Hello Size : "+ agent_list.size());
		this.aty = aty;
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return agent_list.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return agent_list.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		ViewHolder holder;
		if (convertView == null) {
			convertView 		= mInflater.inflate(R.layout.list_item_popular_agent, null);
			holder 				= new ViewHolder();
			holder.name			= (TextView)convertView .findViewById(R.id.txt_name);
			holder.total_ticket = (TextView)convertView .findViewById(R.id.txt_total_ticket);
			holder.total_amount = (TextView)convertView .findViewById(R.id.txt_total_amount);
			holder.detail 		= (Button)convertView .findViewById(R.id.btn_detail);
			holder.layout_label = (LinearLayout)convertView .findViewById(R.id.layout_label);
			holder.view_label	= (View)convertView .findViewById(R.id.view_label);
			holder.txt_label	= (TextView) convertView .findViewById(R.id.txt_label);
			convertView.setTag(holder);
		} else {
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.name.getLayoutParams().width = (int) ((DeviceUtil.getInstance(aty).getWidth()/1.5) / 5);
		holder.total_ticket.getLayoutParams().width = (int) ((DeviceUtil.getInstance(aty).getWidth()/1.5) / 5);
		holder.total_amount.getLayoutParams().width = (int) ((DeviceUtil.getInstance(aty).getWidth()/1.5) / 5);
		holder.layout_label.getLayoutParams().width = (int) ((DeviceUtil.getInstance(aty).getWidth()/1.5) / 5);
		holder.detail.getLayoutParams().width = (int) ((DeviceUtil.getInstance(aty).getWidth()/1.5) / 5);
		
		holder.name.setText(agent_list.get(position).getName());
		holder.total_ticket.setText(agent_list.get(position).getPurchasedTotalSeat().toString());
		holder.total_amount.setText(agent_list.get(position).getTotalAmount().toString());
		holder.view_label.setBackgroundColor(Color.parseColor(agent_list.get(position).getLabelColor()));
		holder.txt_label.setText(agent_list.get(position).getLabelName());
		holder.detail.setTag(position);
		holder.detail.setOnClickListener(new View.OnClickListener() {
			
			public void onClick(View v) {
				// TODO Auto-generated method stub
				int positon = (Integer) v.getTag();
				if(mCallbacks != null){
					mCallbacks.onViewDetial(positon);
				}
			}
		});
		
		return convertView;
	}
	
	 public void filter(String charText) {
	        charText = charText.toLowerCase();
	        agent_list.clear();
	        Log.i("","Hello : "+ charText+", length : "+ charText.length());
	        if (charText.length() == 0) {
	        	Log.i("","Hello Size : "+ arraylist.size());
	        	agent_list.addAll(arraylist);
	        	
	        }
	        else
	        {
	            for (PopularAgent agent : arraylist)
	            {
	                if (agent.getLabelName().toLowerCase().contains(charText))
	                {
	                	Log.i("","Hello Found : "+ agent.getName());
	                	agent_list.add(agent);
	                }
	            }
	        }
	        notifyDataSetChanged();
	    }
	
	public void setOnViewDetailListener(Callbacks callbacks){
		this.mCallbacks = callbacks;
	}
	
	public interface Callbacks{
		void onViewDetial(int positon);
	}

	static class ViewHolder {
		TextView name , total_ticket, total_amount, txt_label;
		Button detail;
		LinearLayout layout_label;
		View view_label;
				
	}

}
