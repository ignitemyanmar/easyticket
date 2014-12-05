package com.ignite.busoperator.adapter;

import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.Agents;

import android.app.Activity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

public class AgentListViewAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<Agents> listItem;
	private Activity aty;
	private Callback mCallback;
	private ArrayList<Agents> arraylist;
	public AgentListViewAdapter(Activity aty, List<Agents> _list){
		mInflater = LayoutInflater.from(aty);
		this.listItem = _list;
		this.arraylist = new ArrayList<Agents>();
		this.arraylist.addAll(_list);
		this.aty = aty;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public Agents getItem(int position) {
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
        	convertView = mInflater.inflate(R.layout.list_item_agent, null);
        	holder.txt_name = (TextView) convertView.findViewById(R.id.txt_name);
        	holder.txt_deposit_amount = (TextView) convertView.findViewById(R.id.txt_deposit_amount);
        	holder.txt_credit_amount = (TextView) convertView.findViewById(R.id.txt_credit);
        	holder.txt_latest_deposit = (TextView) convertView.findViewById(R.id.txt_latest_deposit);
        	holder.view_detail = (LinearLayout) convertView.findViewById(R.id.view_detail);
        	holder.btn_view = (Button) convertView.findViewById(R.id.btn_view);
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.txt_name.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.txt_deposit_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.txt_credit_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.txt_latest_deposit.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.view_detail.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		
		holder.txt_name.setText(getItem(position).getName().toString());
		if(getItem(position).getDepositBalance().toString().contains("-")){
			holder.txt_latest_deposit.setText(getItem(position).getDepositBalance().toString().replace("-", ""));
			holder.txt_deposit_amount.setText("0");
		}else{
			holder.txt_deposit_amount.setText(getItem(position).getDepositBalance().toString());
			holder.txt_latest_deposit.setText("0");
		}
		
		holder.txt_credit_amount.setText(getItem(position).getCredit().toString());
		
		holder.btn_view.setTag(position);
		holder.btn_view.setOnClickListener(new OnClickListener() {
			
			public void onClick(View v) {
				// TODO Auto-generated method stub
				int pos = (Integer) v.getTag();
				if(mCallback != null){
					mCallback.setView(pos);
				}
			}
		});
		
		return convertView;
	}
	
	public void setOnCallbackListener(Callback callback){
		this.mCallback = callback;
	}
	
	public interface Callback{
		void setDepositAmout(int position);
		void setViewCredit(int postion);
		void setPaymentHistory(int positon);
		void setView(int position);
	}
	
	 public void filter(String charText) {
	        charText = charText.toLowerCase(Locale.getDefault());
	        listItem.clear();
	        if (charText.length() == 0) {
	        	listItem.addAll(arraylist);
	        }
	        else
	        {
	            for (Agents agent : arraylist)
	            {
	                if (agent.getName().toLowerCase(Locale.getDefault()).contains(charText))
	                {
	                	listItem.add(agent);
	                }
	            }
	        }
	        notifyDataSetChanged();
	    }
	
	static class ViewHolder {
		TextView txt_name, txt_deposit_amount, txt_credit_amount, txt_latest_deposit;
		Button btn_view;
		LinearLayout view_detail;
		
	}
}
