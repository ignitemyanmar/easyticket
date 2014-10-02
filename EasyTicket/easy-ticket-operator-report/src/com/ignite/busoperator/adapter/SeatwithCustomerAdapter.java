package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.model.SeatwithCustomer;

import android.app.Activity;
import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.RelativeLayout;
import android.widget.TextView;

public class SeatwithCustomerAdapter extends BaseAdapter{
	 private final Context _context;
	    private final List<SeatwithCustomer> list;
	  	    
	    public SeatwithCustomerAdapter(Activity atx, List<SeatwithCustomer> seat_list)
	    {
	        super();
	        this._context = atx;
	        this.list = seat_list;
	       
	    }
		public int getCount() {
			// TODO Auto-generated method stub
			return  list.size();
		}
		
		public Object getItem(int position) {
			// TODO Auto-generated method stub
			return list.get(position);
		}
		public long getItemId(int position) {
			// TODO Auto-generated method stub
			return position;
		}
		public View getView(int position, View convertView, ViewGroup parent) {
			// TODO Auto-generated method stub
			ViewHolder holder;
			if (convertView == null)
	        {
	            convertView =LayoutInflater.from(_context).inflate(R.layout.list_item_seat_with_customer, null);
	            holder = new ViewHolder();
	            holder.SeatLayout = (RelativeLayout) convertView.findViewById(R.id.layout_seat);
	            holder.CustomerName = (TextView) convertView.findViewById(R.id.txt_customer_name);
	            holder.Phone = (TextView) convertView.findViewById(R.id.txt_phone);
	            holder.Seller = (TextView) convertView.findViewById(R.id.txt_seller);
	            convertView.setTag(holder);
			} else {
				holder = (ViewHolder) convertView.getTag();
			}
			
			
			if(list.get(position).getStatus() == 2){
            	holder.SeatLayout.setBackgroundColor(Color.RED);
            	holder.CustomerName.setText(list.get(position).getCustomer().getName());
            	holder.Phone.setText(list.get(position).getCustomer().getPhone());
            	holder.Seller.setText(list.get(position).getCustomer().getSeller());
            }
            
            
            if(list.get(position).getStatus() == 1){
            	holder.SeatLayout.setBackgroundColor(Color.BLUE);
            }
            
            if(list.get(position).getStatus() == 0){
            	holder.CustomerName.setVisibility(View.INVISIBLE);
            	holder.Phone.setVisibility(View.INVISIBLE);
            	holder.Seller.setVisibility(View.INVISIBLE);
            	holder.SeatLayout.setVisibility(View.INVISIBLE);
            }
    
	        return convertView;
		}
		
		 static class ViewHolder {
			 	RelativeLayout SeatLayout;
				TextView CustomerName, Phone, Seller;
			}
}
