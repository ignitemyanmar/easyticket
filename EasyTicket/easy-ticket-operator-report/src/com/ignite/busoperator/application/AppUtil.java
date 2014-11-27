package com.ignite.busoperator.application;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;

import android.text.format.DateFormat;

public class AppUtil {
	public static String changeDate(String date){
		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		Date StartDate = null;
		try {
			StartDate = df.parse(date);
		} catch (ParseException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		return DateFormat.format("dd/MM/yyyy",StartDate).toString();
	}
}
