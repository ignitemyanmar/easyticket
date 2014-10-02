package com.ignite.mm.ticketing.custom.listview.adapter;

import java.util.List;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.TextView;

import com.ignite.mm.ticketing.R;
import com.ignite.mm.ticketing.BusSelectSeatActivity;
import com.ignite.mm.ticketing.sqlite.database.model.Seat_list;

public class BusSeatAdapter extends BaseAdapter{
	 private final Context _context;
	    private final List<Seat_list> list;
	  	    
	    public BusSeatAdapter(Activity atx, List<Seat_list> seat_list)
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
			
			
			if(list.get(position).getStatus() == 2){
            	holder.seat.setEnabled(false);
            	holder.seatNo.setText(list.get(position).getSeat_no());
            }
            
            if(list.get(position).getStatus() == 3){
            	holder.seat.setChecked(true);
            	holder.seatNo.setText(list.get(position).getSeat_no());
            }
            
            if(list.get(position).getStatus() == 1){
            	holder.seatNo.setText(list.get(position).getSeat_no());
            	holder.seat.setEnabled(true);
            	holder.seat.setTag(position);
            	holder.seat.setOnCheckedChangeListener(new OnCheckedChangeListener() {
					
					public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
						if(isChecked){
							//If checked the seat.
							String[] seleted = BusSelectSeatActivity.SelectedSeat.split(",");
							if(!BusSelectSeatActivity.SelectedSeat.isEmpty()){
								boolean isExisted = false;
								for (int i = 0; i < seleted.length; i++) {
									if(seleted[i].equals(buttonView.getTag().toString())){
										isExisted = true;
									}
								}
								
								if(!isExisted){
									BusSelectSeatActivity.SelectedSeat += buttonView.getTag()+",";
								}
							}else{
								BusSelectSeatActivity.SelectedSeat += buttonView.getTag()+",";
							}
							
							
						}else{
							//If unchecked the seat.
							String[] seleted = BusSelectSeatActivity.SelectedSeat.split(",");
							if(!BusSelectSeatActivity.SelectedSeat.isEmpty()){
								BusSelectSeatActivity.SelectedSeat = "";
								for (int i = 0; i < seleted.length; i++) {
									if(!seleted[i].equals(buttonView.getTag().toString())){
										BusSelectSeatActivity.SelectedSeat += seleted[i]+",";
									}
								}
								
							}
						}
						
					}
				});
            }
            
            if(list.get(position).getStatus() == 0){
            	holder.seat.setVisibility(View.INVISIBLE);
            	holder.seatNo.setVisibility(View.INVISIBLE);
            }
            
           /* if(list.get(position).getStatus().equals("5")){
            	holder.seat.setChecked(false);
            	holder.cover.setVisibility(View.VISIBLE);
        		holder.cover.setClickable(false);
            }*/
    
	        return convertView;
		}
		
		 static class ViewHolder {
				CheckBox seat;
				View cover;
				TextView seatNo;
			}
}
