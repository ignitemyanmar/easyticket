package com.ignite.mm.ticketing.custom.listview.adapter;

import java.util.ArrayList;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.WriterException;
import com.ignite.barcode.GenerateBarcode;
import com.ignite.mm.ticketing.Bus_Info_Activity;
import com.ignite.mm.ticketing.PDFBusActivity;
import com.ignite.mm.ticketing.PDFMovieActivity;
import com.ignite.mm.ticketing.R;
import com.ignite.mm.ticketing.sqlite.database.model.AllBusObject;
import com.ignite.mm.ticketing.sqlite.database.model.AllMovieObject;

import android.app.Activity;
import android.graphics.Bitmap;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

public class PDFBusAdapter extends BaseAdapter {

	private TextView destination, operatorName, ticketNo, price, time , date;
	private ImageView iv;
	private TextView barcodeText;
	private LayoutInflater mInflater;
	private int bitmapWidth = 150;
	private int bitmapHeight = 50;
	private ArrayList<AllBusObject> allBusObject;
	
	public PDFBusAdapter(Activity aty,
			 ArrayList<AllBusObject> allBusObject) {
		// TODO Auto-generated constructor stub
		this.mInflater= LayoutInflater.from(aty);
		this.allBusObject=allBusObject;
		//Log.i("","AllMovie :" + allMovieObject);
	}

	public int getCount() {
		// TODO Auto-generated method stub
		return allBusObject.size();
	}

	public Object getItem(int position) {
		// TODO Auto-generated method stub
		return allBusObject.get(position);
	}

	public long getItemId(int position) {
		// TODO Auto-generated method stub
		return position;
	}

	public View getView(int position, View convertView, ViewGroup parent) {
		// TODO Auto-generated method stub
		
		convertView = mInflater.inflate(R.layout.bus_ticket_item, null);
		iv = (ImageView)convertView.findViewById(R.id.img_barcode);
		barcodeText = (TextView)convertView.findViewById(R.id.txtBarcode);
		destination=(TextView)convertView.findViewById(R.id.txtDestination);
		destination.setText(allBusObject.get(position).getTrip());
		
		operatorName =(TextView)convertView.findViewById(R.id.txtAgentName);
		operatorName.setText(allBusObject.get(position).getOperatorName());
		
		ticketNo=(TextView)convertView.findViewById(R.id.txtSeatNo);
		ticketNo.setText("Seat No	: " + allBusObject.get(position).getSeatNo());
		
		price=(TextView)convertView.findViewById(R.id.txtprice);
		price.setText(allBusObject.get(position).getPrice() + " MMK");
		
		time=(TextView)convertView.findViewById(R.id.txtTime);
		time.setText("Time	: " + allBusObject.get(position).getTime());
		
		date=(TextView)convertView.findViewById(R.id.txtDate);
		date.setText("Date : " + allBusObject.get(position).getDate());
		getBarcode();
		return convertView;
	}
	
	private void getBarcode() {
		
		// barcode data
		String barcode_data = "FA-0123456";

		// barcode image
		Bitmap bitmap = null;

		try {
			GenerateBarcode code = new GenerateBarcode();
			bitmap = code.encodeAsBitmap(barcode_data, BarcodeFormat.CODE_128,
					bitmapWidth, bitmapHeight);
			/*
			 * GenerateBarcode code = new GenerateBarcode(barcode_data,150,70);
			 * bitmap = code.generateBarCode();
			 */
			iv.setImageBitmap(bitmap);

		} catch (WriterException e) {
			e.printStackTrace();
		}

		// ly.addView(iv);

		// barcodeText.setGravity(Gravity.CENTER_HORIZONTAL);
		barcodeText.setText(barcode_data);

		// ly.addView(barcodeText);
	}

	


}
