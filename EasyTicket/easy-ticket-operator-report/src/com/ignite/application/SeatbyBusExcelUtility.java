package com.ignite.application;

import java.io.File;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;
import com.ignite.busoperator.model.SeatbyBus;
import com.itextpdf.text.Paragraph;

import android.os.Environment;
import android.util.Log;
import jxl.CellView;
import jxl.Workbook;
import jxl.WorkbookSettings;
import jxl.format.UnderlineStyle;
import jxl.write.Label;
import jxl.write.Number;
import jxl.write.WritableCellFormat;
import jxl.write.WritableFont;
import jxl.write.WritableSheet;
import jxl.write.WritableWorkbook;
import jxl.write.WriteException;
import jxl.write.biff.RowsExceededException;


public class SeatbyBusExcelUtility {

  private static final String RESULT = Environment.getExternalStorageDirectory() +"/Easy_Ticket/";
  private WritableCellFormat boldUnderline;
  private WritableCellFormat labels;
  private String inputFile;
  private List<SeatbyBus> seatbyBus;
  
  public SeatbyBusExcelUtility(List<SeatbyBus> seatbyBus, String filename) {
	  this.seatbyBus = seatbyBus;
	  IfExistFileDir(RESULT);
	  inputFile = filename == null ? RESULT+getToday()+"_DailyReport_SeatbyBus.xls" : RESULT+filename+".xls";
	  IfExistPDF(inputFile);
  }

  public void write() {
	
	File file = new File(inputFile);
	WorkbookSettings wbSettings = new WorkbookSettings();
	
	wbSettings.setLocale(new Locale("en", "EN"));
	
	WritableWorkbook workbook;
	try {
		workbook = Workbook.createWorkbook(file, wbSettings);
		workbook.createSheet("Report", 0);
		WritableSheet excelSheet = workbook.getSheet(0);
		createLabel(excelSheet);
		createContent(excelSheet);
		workbook.write();
		workbook.close();
	} catch (IOException e) {
		// TODO Auto-generated catch block
		e.printStackTrace();
	} catch (WriteException e){
		e.printStackTrace();
	}
  }

  private void createLabel(WritableSheet sheet)
      throws WriteException {
    // Lets create a labels font
    WritableFont font = new WritableFont(WritableFont.ARIAL, 10);
    // Define the cell format
    labels = new WritableCellFormat(font);
    // Lets automatically wrap the cells
    labels.setWrap(true);

    // create create a bold font with unterlines
    WritableFont headerBoldUnderline = new WritableFont(WritableFont.ARIAL, 10, WritableFont.BOLD, false,
        UnderlineStyle.SINGLE);
    boldUnderline = new WritableCellFormat(headerBoldUnderline);
    // Lets automatically wrap the cells
    boldUnderline.setWrap(true);

    CellView cv = new CellView();
    cv.setFormat(labels);
    cv.setFormat(boldUnderline);
    cv.setAutosize(true);
    
    // Report Header
    addLabel(sheet, 0, 0, "Trip: "+seatbyBus.get(0).getFromTo().toString());
    addLabel(sheet, 0, 1, "Departure Date: "+seatbyBus.get(0).getDepartureDate().toString());
    addLabel(sheet, 0, 2, "Departuer Time: "+seatbyBus.get(0).getTime().toString());
    addLabel(sheet, 0, 3, "Order Date: "+seatbyBus.get(0).getOrderDate().toString());

    // Write a few headers
    addCaption(sheet, 0, 0, "Departure Date");
    addCaption(sheet, 1, 0, "Trip");
    addCaption(sheet, 2, 0, "Departure Time");
    addCaption(sheet, 3, 0, "Bus Class");
    addCaption(sheet, 4, 0, "Agent Name");
    addCaption(sheet, 5, 0, "Seat No.");
    addCaption(sheet, 6, 0, "QTY");
    addCaption(sheet, 7, 0, "Price");
    addCaption(sheet, 8, 0, "Commission");
    addCaption(sheet, 9, 0, "Total Amount");
    
  }

  private void createContent(WritableSheet sheet) throws WriteException,
      RowsExceededException {
	  
    int i = 1;
    for(SeatbyBus seat: seatbyBus){
    	addLabel(sheet, 0, i, seat.getDepartureDate());
    	addLabel(sheet, 1, i, seat.getFromTo());
    	addLabel(sheet, 2, i, seat.getTime());
    	addLabel(sheet, 3, i, seat.getClasses());
    	addLabel(sheet, 4, i, seat.getAgentName());
    	addLabel(sheet, 5, i, seat.getSeatNo());
    	addLabel(sheet, 6, i, seat.getSoldSeat().toString());
    	addLabel(sheet, 7, i, seat.getPrice().toString());
    	addLabel(sheet, 8, i, seat.getCommission().toString());
    	addLabel(sheet, 9, i, seat.getTotalAmount().toString());
    	i++;
    }
  }

	private void addCaption(WritableSheet sheet, int column, int row, String s)
	      throws RowsExceededException, WriteException {
	   Label label;
	   label = new Label(column, row, s, boldUnderline);
	   sheet.addCell(label);
	}

  	public void addNumber(WritableSheet sheet, int column, int row,
      Integer integer) throws WriteException, RowsExceededException {
  		Number number;
    	number = new Number(column, row, integer, labels);
    	sheet.addCell(number);
  	}

	private void addLabel(WritableSheet sheet, int column, int row, String s)
	      throws WriteException, RowsExceededException {
	   Label label;
	   label = new Label(column, row, s, labels);
	   sheet.addCell(label);
	}
  
  	public Boolean IfExistFileDir(String Dir){
		File fileDir = new File(Dir);
		if(!fileDir.exists()){
			fileDir.mkdirs();
			return false;
		}
		return true;
	}
	private Boolean IfExistPDF(String pathName) {
		boolean ret = true;
	
		File file = new File(pathName);
		if (!file.exists()) {
			try {
				file.createNewFile();
			} catch (Exception e) {
				Log.e("TravellerLog :: ", "Problem creating folder");
				ret = false;
			}
		}
		return ret;
	}
	private String getToday(){
		Calendar c = Calendar.getInstance();
		System.out.println("Current time => " + c.getTime());

		SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		String formattedDate = df.format(c.getTime());
		Log.i("","Hello Today: "+formattedDate);
		return formattedDate;
	}
} 
