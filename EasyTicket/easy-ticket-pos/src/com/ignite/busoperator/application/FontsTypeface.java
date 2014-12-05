package com.ignite.busoperator.application;
import android.content.Context;
import android.graphics.Typeface;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

public class FontsTypeface {
	private Context ctx;
	public String Aurora = "aurora";
	public String Zawgyi_One = "zawgyi_one";
	public String Helvetica = "helvetica";
	public String Ayarwagaung = "ayar_wagaung";
	public String Myanmar3 = "myanmar3";
	
	public FontsTypeface(Context ctx) {
		this.ctx = ctx;
	}
	public void setTypeface(Button tv, String style){
		Typeface tf = null;
		if(style.toLowerCase().equals("aurora")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/aurora-bold-condensed-bt.ttf");
		}
		if(style.toLowerCase().equals("zawgyi_one")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/ZawgyiOne2008.ttf");
		}
		if(style.toLowerCase().equals("helvetica")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/helvetica-neue1.ttf");
		}
		if(style.toLowerCase().equals("ayar_wagaung")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/MTM-Wagaung.ttf");
		}
		if(style.toLowerCase().equals("myanmar3")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/Myanmar3.ttf");
		}
		
		tv.setTypeface(tf);
		
	}
	public void setTypeface(TextView tv, String style){
		Typeface tf = null;
		if(style.toLowerCase().equals("aurora")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/aurora-bold-condensed-bt.ttf");
		}
		if(style.toLowerCase().equals("zawgyi_one")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/ZawgyiOne2008.ttf");
		}
		if(style.toLowerCase().equals("helvetica")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/helvetica-neue1.ttf");
		}	
		if(style.toLowerCase().equals("ayar_wagaung")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/MTM-Wagaung.ttf");
		}
		if(style.toLowerCase().equals("myanmar3")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/Myanmar3.ttf");
		}
		tv.setTypeface(tf);
		
	}
	public void setTypeface(EditText tv, String style){
		Typeface tf = null;
		if(style.toLowerCase().equals("aurora")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/aurora-bold-condensed-bt.ttf");
		}
		if(style.toLowerCase().equals("zawgyi_one")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/ZawgyiOne2008.ttf");
		}
		if(style.toLowerCase().equals("helvetica")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/helvetica-neue1.ttf");
		}	
		if(style.toLowerCase().equals("ayar_wagaung")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/MTM-Wagaung.ttf");
		}
		if(style.toLowerCase().equals("myanmar3")){
			tf = Typeface.createFromAsset(ctx.getAssets(),"fonts/Myanmar3.ttf");
		}
		tv.setTypeface(tf);
		
	}
}
