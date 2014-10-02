package com.ignite.busoperator.application;

import java.io.BufferedReader;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.ObjectInputStream;
import java.io.ObjectOutputStream;
import java.io.OutputStreamWriter;
import java.io.Serializable;

import com.ignite.busoperator.model.CitiesbyAgent;

import android.os.Environment;
import android.util.Base64;
import android.util.Base64InputStream;
import android.util.Base64OutputStream;


public class StoreJSONUtli {
	
	private String DIR_PATH = Environment.getExternalStorageDirectory()+"/EasyTicketing/";
	private static StoreJSONUtli instance;

	public StoreJSONUtli(){
		File dir = new File(DIR_PATH);
		createDir(dir);
	}
	
	public static StoreJSONUtli getInstance() {
    	if(instance == null){
    		instance = new StoreJSONUtli();
		}
		return instance;
	}
	
	public boolean fileWrite(String dataJson, String fileName){
		File myFile = new File(DIR_PATH + fileName +".json");
		try {
			myFile.createNewFile();
			FileOutputStream fOut = new FileOutputStream(myFile);
			OutputStreamWriter myOutWriter = new OutputStreamWriter(fOut);
			myOutWriter.append(dataJson);
			myOutWriter.close();
			fOut.close();
			return true;
		} catch (IOException e) {
			return false;
		}
	}
	
	public String fileRead(String fileName){
		try {
			File myFile = new File(DIR_PATH+ fileName +".json");
			FileInputStream fIn = new FileInputStream(myFile);
			BufferedReader myReader = new BufferedReader(new InputStreamReader(fIn));
			String aDataRow = "";
			String aBuffer = "";
			while ((aDataRow = myReader.readLine()) != null) {
				aBuffer += aDataRow + "\n";
			}
			myReader.close();
			return aBuffer;
		} catch (IOException e) {
			return null;
		}
	}
	
	private void createDir(File dir) {
	      if (dir.exists()) {
	          return;
	      }
	      if (!dir.mkdirs()) {
				throw new RuntimeException("Can not create dir " + dir);
	      }
	  }
	
	public String objectToString(Serializable obj) {
	    try {
	      ByteArrayOutputStream baos = new ByteArrayOutputStream();
	      ObjectOutputStream oos = new ObjectOutputStream(
	          new Base64OutputStream(baos, Base64.NO_PADDING
	              | Base64.NO_WRAP));
	      oos.writeObject(obj);
	      oos.close();
	      return baos.toString("UTF-8");
	    } catch (IOException e) {
	      e.printStackTrace();
	    }
	    return null;
	  }

	  public Object stringToObject(String str) {
	    try {
	      return new ObjectInputStream(new Base64InputStream(
	          new ByteArrayInputStream(str.getBytes()), Base64.NO_PADDING
	              | Base64.NO_WRAP)).readObject();
	    } catch (Exception e) {
	      e.printStackTrace();
	    }
	    return null;
	  }
}
