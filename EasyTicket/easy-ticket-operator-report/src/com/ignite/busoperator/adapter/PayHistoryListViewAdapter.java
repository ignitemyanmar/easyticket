package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.application.DeviceUtil;
import com.ignite.busoperator.model.PayHistory;

import android.app.Activity;
import android.content.SharedPreferences;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

public class PayHistoryListViewAdapter extends BaseAdapter {
	private LayoutInflater mInflater;
	private List<PayHistory> listItem;
	private Activity aty;
	private Callback mCallback;
	public PayHistoryListViewAdapter(Activity aty, List<PayHistory> _list){
		mInflater = LayoutInflater.from(aty);
		listItem = _list;
		this.aty = aty;
	}
	public int getCount() {
		// TODO Auto-generated method stub
		return listItem.size();
	}

	public PayHistory getItem(int position) {
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
        	convertView = mInflater.inflate(R.layout.list_item_pay_history, null);
        	holder.deposit_pay_date = (TextView) convertView.findViewById(R.id.txt_deposit_pay_date);
        	holder.deposit = (TextView) convertView.findViewById(R.id.txt_deposit);
        	holder.pay_amount = (TextView) convertView.findViewById(R.id.txt_pay_amount);
        	holder.ticket_amount = (TextView) convertView.findViewById(R.id.txt_ticket_amount);
        	holder.credit_pay_date = (TextView) convertView.findViewById(R.id.txt_credit_pay_date);
        	holder.remaining_deposit = (TextView) convertView.findViewById(R.id.txt_remaining_deposit);
        	convertView.setTag(holder);
		}else{
			holder = (ViewHolder) convertView.getTag();
		}
		
		holder.deposit_pay_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		//holder.deposit.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.pay_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.ticket_amount.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.credit_pay_date.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		holder.remaining_deposit.getLayoutParams().width = (int) (DeviceUtil.getInstance(aty).getWidth()) / 5;
		
		holder.deposit_pay_date.setText(getItem(position).getDepositDate().toString());
		holder.deposit.setText(getItem(position).getDeposit().toString());
		holder.pay_amount.setText(getItem(position).getPayment().toString());
		holder.ticket_amount.setText(getItem(position).getTotalTicketAmt().toString());
		holder.credit_pay_date.setText(getItem(position).getPayDate().toString());
		holder.remaining_deposit.setText(getItem(position).getBalance().toString());
		
		return convertView;
	}
	
	public void setOnCallbackListener(Callback callback){
		this.mCallback = callback;
	}
	
	public interface Callback{
		void setDelete(int position);
	}
	
	static class ViewHolder {
		TextView deposit_pay_date, deposit, pay_amount, ticket_amount, credit_pay_date, remaining_deposit;
	}
}
