package com.ignite.busoperator.adapter;

import java.util.List;

import com.ignite.busoperator.R;
import com.ignite.busoperator.model.Seat_list;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.TextView;

public class SeatAdapter extends BaseAdapter{
	 private final Context _context;
	    private final List<Seat_list> list;
	  	    
	    public SeatAdapter(Activity atx, List<Seat_list> seat_list)
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
	            convertView =LayoutInflater.from(_context).inflate(R.layout.list_item_seat, null);
	            holder = new ViewHolder();
	            holder.seat = (CheckBox) convertView.findViewById(R.id.chk_seat);
	            holder.seatNo = (TextView) convertView.findViewById(R.id.txt_seat_no);
	            holder.cover = (View) convertView.findViewById(R.id.v_cover);
	            convertView.setTag(holder);
			} else {
				holder = (ViewHolder) convertView.getTag();
			}
			
			
			if(list.get(position).getStatus().equals("2")){
            	holder.seat.setEnabled(false);
            	holder.seatNo.setText(list.get(position).getSeat_no());
            }
            
            if(list.get(position).getStatus().equals("3")){
            	holder.seat.setChecked(true);
            	holder.seatNo.setText(list.get(position).getSeat_no());
            }
            
            if(list.get(position).getStatus().equals("1")){
            	holder.seatNo.setText(list.get(position).getSeat_no());
            }
            
            if(list.get(position).getStatus().equals("0")){
            	holder.seat.setVisibility(View.INVISIBLE);
            	holder.seatNo.setVisibility(View.INVISIBLE);
            }
    
	        return convertView;
		}
		
		 static class ViewHolder {
				CheckBox seat;
				View cover;
				TextView seatNo;
			}
}
