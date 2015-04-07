package com.smk.custom.view;

import android.content.Context;
import android.graphics.Typeface;
import android.util.AttributeSet;
import android.widget.TextView;

public class CustomTextView extends TextView{
	public CustomTextView(Context context){
		super(context);
		this.setLineSpacing(30f, 0f);
		/*if(!isInEditMode())
			setTypeface(Typeface.createFromAsset(context.getAssets(),"fonts/ZawgyiOne2008.ttf"));*/
	}
	public CustomTextView(Context context, AttributeSet attrs) {
		super(context, attrs);
		this.setLineSpacing(30f, 0f);
		/*if(!isInEditMode())
			setTypeface(Typeface.createFromAsset(context.getAssets(),"fonts/ZawgyiOne2008.ttf"));*/
	}
	
	

}
