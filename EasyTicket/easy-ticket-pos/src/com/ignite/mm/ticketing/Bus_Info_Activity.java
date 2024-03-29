package com.ignite.mm.ticketing;

import java.io.BufferedOutputStream;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Set;
import android.app.ProgressDialog;
import android.bluetooth.BluetoothDevice;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.Bitmap.CompressFormat;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.MeasureSpec;
import android.view.View.OnClickListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ImageButton;
import android.widget.LinearLayout;
import android.widget.ListAdapter;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.actionbarsherlock.app.ActionBar;
import com.actionbarsherlock.app.SherlockActivity;
import com.example.bluetoothprinter.BlueToothService;
import com.example.bluetoothprinter.BlueToothService.OnReceiveDataHandleEvent;
import com.ignite.mm.ticketing.application.BluetoothDeviceDialog;
import com.ignite.mm.ticketing.custom.listview.adapter.PDFBusAdapter;
import com.ignite.mm.ticketing.sqlite.database.model.AllBusObject;
import com.smk.custom.view.CustomTextView;

public class Bus_Info_Activity extends SherlockActivity{
	private TextView actionBarTitle, destination, tripDate, agentName, dep_time,amount;
	private ActionBar actionBar;
	private ImageButton actionBarBack;
	
	private String Trip, Date, OperatorID, OperatorName, Time;
	private String SelectedSeatIndex = "";
	private String SelectedSeatNo = "";
	private String SelectedPrice = "";
	public static int no_of_ticket;
	
	private int verson = 52;
	private BlueToothService mBTService = null;
	private ListView mDeviceList;
	private ArrayAdapter<String> mNewDevicesArrayAdapter = null;
	private Set<BluetoothDevice> devices;
	public static final int MESSAGE_STATE_CHANGE = 1;
	public static final int MESSAGE_READ = 2;
	public static final int MESSAGE_WRITE = 3;
	
	private BluetoothDeviceDialog mBluetoothDeviceDialog;
	private ProgressDialog dialog;
	private String Buyer;
	
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		
		setContentView(R.layout.activity_bus_ticket_list);
		
		SharedPreferences sharedPreferences = getApplicationContext().getSharedPreferences("CheckOutInfo",MODE_PRIVATE);
		Buyer  = sharedPreferences.getString("buyer_name", null);
		Trip = sharedPreferences.getString("trip", null);
		Date = sharedPreferences.getString("trip_date", null);
		OperatorID = sharedPreferences.getString("operator_id", null);
		OperatorName = sharedPreferences.getString("operator_name", null);
		Time = sharedPreferences.getString("time", null);
		SelectedSeatIndex = sharedPreferences.getString("selected_seat", null);
		
		SharedPreferences.Editor editor = sharedPreferences.edit();
		editor.putString("loaded", "true");
		editor.commit();
		
		actionBar = getSupportActionBar();
		actionBar.setCustomView(R.layout.action_bar);
		actionBarTitle = (TextView) actionBar.getCustomView().findViewById(
				R.id.action_bar_title);
		actionBarBack = (ImageButton) actionBar.getCustomView().findViewById(
				R.id.action_bar_back);
		actionBarTitle.setText("BUS");

		actionBarBack.setOnClickListener(clickListener);
		actionBar.setDisplayOptions(ActionBar.DISPLAY_SHOW_CUSTOM);
		
		setData();
		bluetoothPrint();
		
	}
	
	public void bluetoothPrint() {
		// TODO Auto-generated method stub
		try {
			mBluetoothDeviceDialog = new BluetoothDeviceDialog(this);
	       	mBluetoothDeviceDialog.show();
	       	mDeviceList = mBluetoothDeviceDialog.getListView();
	        mDeviceList.setOnItemClickListener(itemClickListener);
	        mBTService = new BlueToothService(this, handler);
	        mNewDevicesArrayAdapter = new ArrayAdapter<String>(this,android.R.layout.simple_list_item_1);
	        if (!mBTService.IsOpen()) {
				mBTService.OpenDevice();
				return;
			}
			if (mBTService.GetScanState() == BlueToothService.STATE_SCANING)
				return;
			
			mNewDevicesArrayAdapter.clear();
			devices = mBTService.GetBondedDevice();

			if (devices.size() > 0) {

				for (BluetoothDevice device : devices) {
					mNewDevicesArrayAdapter.add(device.getName() + "\n"
							+ device.getAddress());
				}
			}
			mDeviceList.setAdapter(mNewDevicesArrayAdapter);
			mBTService.setOnReceive(new OnReceiveDataHandleEvent() {

				public void OnReceive(BluetoothDevice device) {
					// TODO Auto-generated method stub
					if (device != null) {
						mNewDevicesArrayAdapter.add(device.getName() + "\n"
								+ device.getAddress());
					} else {
						Message msg = new Message();
						msg.what = 1;
						handler.sendMessage(msg);

					}
				}
			});
			new Thread() {
				public void run() {
					mBTService.ScanDevice();
				}
			}.start();
		} catch (NullPointerException e) {
			// TODO: handle exception
		}		
	}

	
	private OnClickListener clickListener = new OnClickListener() {

	public void onClick(View v) {
			if (v == actionBarBack) {
				finish();
			}
		}
	};
	
	private LinearLayout ticketContainer;
	private ArrayList<AllBusObject> allBusObject;
	private TextView buyerName;
	private static ListView lvBus;
	
	private void setData()
	{
		View invoice = LayoutInflater.from(this).inflate(R.layout.activity_bus_invoice,null,false);
		invoice.setBackgroundColor(Color.WHITE);
		ticketContainer = (LinearLayout)invoice.findViewById(R.id.ticket_item_container);
		destination=(CustomTextView)invoice.findViewById(R.id.txt_trip);
		buyerName=(CustomTextView)invoice.findViewById(R.id.txt_customer_name);
		tripDate=(CustomTextView)invoice.findViewById(R.id.txt_depature_date);
		agentName=(CustomTextView)invoice.findViewById(R.id.txt_agent);
		dep_time=(CustomTextView)invoice.findViewById(R.id.txt_depature_time);
		amount=(CustomTextView)invoice.findViewById(R.id.txt_grand_total);
		
		destination.setText("ခရီးစဥ္: "+ Trip);
		tripDate.setText("ေန႕စြဲ: "+Date);
		buyerName.setText(Buyer);
		agentName.setText("၀န္ေဆာင္မွဳ :"+ OperatorName);
		dep_time.setText("ထြက္ခြာမည့္အခ်ိန္: "+Time);
		
		int GrandTotal = 0;
		String[] seletedSeat = SelectedSeatIndex.split(",");
		allBusObject = new ArrayList<AllBusObject>();
		for (int i = 0; i < seletedSeat.length; i++) {
			View ticket = LayoutInflater.from(this).inflate(R.layout.list_item_ticket,null,false);
			((TextView)ticket.findViewById(R.id.txt_ticket_no)).setText(BusSelectSeatActivity.BusSeats.get(0).getSeat_plan().get(0).getSeat_list().get(Integer.valueOf(seletedSeat[i])).getSeat_no().toString());
			((TextView)ticket.findViewById(R.id.txt_ticket_price)).setText(BusSelectSeatActivity.BusSeats.get(0).getSeat_plan().get(0).getPrice().toString());
			ticketContainer.addView(ticket);
			GrandTotal += BusSelectSeatActivity.BusSeats.get(0).getSeat_plan().get(0).getPrice();
			SelectedPrice = BusSelectSeatActivity.BusSeats.get(0).getSeat_plan().get(0).getPrice()+",";
		    SelectedSeatNo = BusSelectSeatActivity.BusSeats.get(0).getSeat_plan().get(0).getSeat_list().get(Integer.valueOf(seletedSeat[i])).getSeat_no().toString()+",";
			
			allBusObject.add ( new AllBusObject( Trip, Date, OperatorID, OperatorName, Time,"", SelectedSeatNo,SelectedPrice,"2"));
		}
		lvBus=(ListView)findViewById(R.id.lvBus);
		lvBus.addHeaderView(invoice);
		lvBus.setAdapter(new PDFBusAdapter(this,allBusObject));
		amount.setText(String.valueOf("စုစုေပါင္း: "+GrandTotal + " MMK"));
	}
	
		
	public static boolean storeImage(Bitmap imageData, String path, String filename) {
		// get path to external storage (SD card)
		String photoPath = path;
		File sdIconStorageDir = new File(photoPath);

		// create storage directories, if they don't exist
		sdIconStorageDir.mkdirs();

		try {
			String filePath = sdIconStorageDir.toString() + "/" + filename;
			FileOutputStream fileOutputStream = new FileOutputStream(filePath);

			BufferedOutputStream bos = new BufferedOutputStream(
					fileOutputStream);

			// choose another format if PNG doesn't suit you
			imageData.compress(CompressFormat.PNG, 100, bos);

			bos.flush();
			bos.close();

		} catch (FileNotFoundException e) {
			Log.w("TAG", "Error saving image file: " + e.getMessage());
			return false;
		} catch (IOException e) {
			Log.w("TAG", "Error saving image file: " + e.getMessage());
			return false;
		}

		return true;
	}
	private final static String PDF_FILE_PATH = "/sdcard/external_sd/bus_ticketing/";
	public static Bitmap getWholeListViewItemsToBitmap() {

	    ListView listview    = lvBus;
	    ListAdapter adapter  = listview.getAdapter(); 
	    int itemscount       = adapter.getCount();
	    int allitemsheight   = 0;
	    List<Bitmap> bmps    = new ArrayList<Bitmap>();

	    for (int i = 0; i < itemscount; i++) {

	        View childView      = adapter.getView(i, null, listview);
	        childView.measure(MeasureSpec.makeMeasureSpec(listview.getWidth(), MeasureSpec.EXACTLY), 
	                MeasureSpec.makeMeasureSpec(0, MeasureSpec.UNSPECIFIED));

	        childView.layout(0, 0, childView.getMeasuredWidth(), childView.getMeasuredHeight());
	        childView.setDrawingCacheEnabled(true);
	        childView.buildDrawingCache();
	        bmps.add(childView.getDrawingCache());
	        allitemsheight+=childView.getMeasuredHeight();
	    }

	    Bitmap bigbitmap    = Bitmap.createBitmap(listview.getMeasuredWidth(), allitemsheight, Bitmap.Config.ARGB_8888);
	    Canvas bigcanvas    = new Canvas(bigbitmap);

	    Paint paint = new Paint();
	    int iHeight = 0;

	    for (int i = 0; i < bmps.size(); i++) {
	        Bitmap bmp = bmps.get(i);
	        bigcanvas.drawBitmap(bmp, 0, iHeight, paint);
	        iHeight+=bmp.getHeight();

	        bmp.recycle();
	        bmp=null;
	    }

	    storeImage(bigbitmap, PDF_FILE_PATH, "busticket.png");
	    return bigbitmap;
	}
	
	 private Handler handler = new Handler() {
			public void handleMessage(Message msg) {
				super.handleMessage(msg);
				switch (msg.what) {
				case MESSAGE_STATE_CHANGE:
					switch (msg.arg1) {
					case BlueToothService.STATE_CONNECTED:
						break;
					case BlueToothService.STATE_CONNECTING:
						break;
					case BlueToothService.STATE_LISTEN:
					case BlueToothService.STATE_NONE:
						break;
					case BlueToothService.SUCCESS_CONNECT:
						dialog.dismiss();
						Toast.makeText(getApplicationContext(),"Success connected", Toast.LENGTH_LONG).show();
						mDeviceList.setVisibility(View.GONE);
						break;
					case BlueToothService.FAILED_CONNECT:
						dialog.dismiss();
						Toast.makeText(getApplicationContext(),"Not success connected", Toast.LENGTH_LONG).show();
						break;
					case BlueToothService.LOSE_CONNECT:
						dialog.dismiss();
						Toast.makeText(getApplicationContext(),"Lost connection", Toast.LENGTH_LONG).show();
						break;
					}
					break;
				case MESSAGE_READ:
					break;
				case MESSAGE_WRITE:
					break;

				}
			}
		};
		private OnItemClickListener itemClickListener = new OnItemClickListener() {

			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				dialog = ProgressDialog.show(Bus_Info_Activity.this, ""," Please wait...", true);
				if (!mBTService.IsOpen()) {
					mBTService.OpenDevice();
					return;
				}
				if (mBTService.GetScanState() == BlueToothService.STATE_SCANING) {
					Message msg = new Message();
					msg.what = 2;
					handler.sendMessage(msg);
				}
				if (mBTService.getState() == BlueToothService.STATE_CONNECTING) {
					return;
				}
				String info = ((TextView) arg1).getText().toString();
				String address = info.substring(info.length() - 17);
				mBTService.DisConnected();
				mBTService.ConnectToDevice(address);
				mBluetoothDeviceDialog.dismiss();
			}
		};
		public static Bitmap resizeImage(Bitmap bitmap, int w, int h) {
			Bitmap BitmapOrg = bitmap;
			int width = BitmapOrg.getWidth();
			int height = BitmapOrg.getHeight();
			int newWidth = w;
			int newHeight = h;

			float scaleWidth = ((float) newWidth) / width;
			float scaleHeight = ((float) newHeight) / height;
			Matrix matrix = new Matrix();
			matrix.postScale(scaleWidth, scaleWidth);
			Bitmap resizedBitmap = Bitmap.createBitmap(BitmapOrg, 0, 0, width,height, matrix, true);
			return resizedBitmap;
		}		
}
