package com.ignite.application;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.List;

import android.os.Environment;
import android.util.Log;

import com.ignite.busoperator.model.SeatsbyTrip;
import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Element;
import com.itextpdf.text.ExceptionConverter;
import com.itextpdf.text.Font;
import com.itextpdf.text.Font.FontFamily;
import com.itextpdf.text.Image;
import com.itextpdf.text.PageSize;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.Phrase;
import com.itextpdf.text.Rectangle;
import com.itextpdf.text.pdf.ColumnText;
import com.itextpdf.text.pdf.PdfPCell;
import com.itextpdf.text.pdf.PdfPTable;
import com.itextpdf.text.pdf.PdfPageEventHelper;
import com.itextpdf.text.pdf.PdfTemplate;
import com.itextpdf.text.pdf.PdfWriter;

public class SeatbyTripPDFUtility {
	/** The resulting PDF file. */
    public static final String RESULT = Environment.getExternalStorageDirectory() +"/Easy_Ticket/";
	private List<SeatsbyTrip> seatbyBus;
    
    public SeatbyTripPDFUtility(List<SeatsbyTrip> seatbyBus){
    	this.seatbyBus = seatbyBus;
    }
    
    /**
     * Inner class to add a table as header.
     */
    class TableHeader extends PdfPageEventHelper {
        /** The header text. */
        String header;
        /** The template with the total number of pages. */
        PdfTemplate total;
 
        /**
         * Allows us to change the content of the header.
         * @param header The new header String
         */
        public void setHeader(String header) {
            this.header = header;
        }
 
        /**
         * Creates the PdfTemplate that will hold the total number of pages.
         * @see com.itextpdf.text.pdf.PdfPageEventHelper#onOpenDocument(
         *      com.itextpdf.text.pdf.PdfWriter, com.itextpdf.text.Document)
         */
        public void onOpenDocument(PdfWriter writer, Document document) {
            total = writer.getDirectContent().createTemplate(30, 16);
        }
 
        /**
         * Adds a header to every page
         * @see com.itextpdf.text.pdf.PdfPageEventHelper#onEndPage(
         *      com.itextpdf.text.pdf.PdfWriter, com.itextpdf.text.Document)
         */
        public void onEndPage(PdfWriter writer, Document document) {
            PdfPTable table = new PdfPTable(3);
            try {
                table.setWidths(new int[]{24, 24, 2});
                table.setTotalWidth(527);
                table.setLockedWidth(true);
                table.getDefaultCell().setFixedHeight(20);
                table.getDefaultCell().setBorder(Rectangle.BOTTOM);
                table.addCell(header);
                table.getDefaultCell().setHorizontalAlignment(Element.ALIGN_RIGHT);
                table.addCell(String.format("Page %d of", writer.getPageNumber()));
                PdfPCell cell = new PdfPCell(Image.getInstance(total));
                cell.setBorder(Rectangle.BOTTOM);
                table.addCell(cell);
                table.writeSelectedRows(0, -1, 34, 803, writer.getDirectContent());
            }
            catch(DocumentException de) {
                throw new ExceptionConverter(de);
            }
        }
 
        /**
         * Fills out the total number of pages before the document is closed.
         * @see com.itextpdf.text.pdf.PdfPageEventHelper#onCloseDocument(
         *      com.itextpdf.text.pdf.PdfWriter, com.itextpdf.text.Document)
         */
        public void onCloseDocument(PdfWriter writer, Document document) {
            ColumnText.showTextAligned(total, Element.ALIGN_LEFT,
                    new Phrase(String.valueOf(writer.getPageNumber() - 1)),
                    2, 2, 0);
        }
    }
 
    /**
     * Creates a PDF document.
     * @param filename the path to the new PDF document
     * @throws    DocumentException 
     * @throws    IOException
     * @throws    SQLException
     */
    public void createPdf(String filename) {
    	
    	IfExistFileDir(RESULT);
		String fileName = filename == null ? getToday()+"_DailyReport_SeatbyBus.pdf" : filename+".pdf";
		IfExistPDF(fileName);
        
        try {
        	// Create a database connection
            // step 1
            Document document = new Document(PageSize.A4, 36, 36, 54, 36);
            // step 2
            PdfWriter writer = PdfWriter.getInstance(document, new FileOutputStream(RESULT	+ fileName));
            TableHeader event = new TableHeader();
            event.setHeader("Seat by Trip");
            writer.setPageEvent(event);
            // step 3
            document.open();
            // step 4
			//document.add(new Paragraph("Trip: "+seatbyBus.get(0).get.toString()));
			//document.add(new Paragraph("Departure Date: "+seatbyBus.get(0).getDeparture_date().toString()));
			//document.add(new Paragraph("Departuer Time: "+seatbyBus.get(0).getDeparture_time().toString()));
			//document.add(new Paragraph("Order Date: "+seatbyBus.get(0).getOrder_date().toString()));
			document.add(new Paragraph("  "));
			
			PdfPTable table = new PdfPTable(6);
			table.setTotalWidth(527);
			table.setLockedWidth(true);
            table.getDefaultCell().setFixedHeight(20);
            table.getDefaultCell().setBorder(Rectangle.BOX);
			
	        
			table.addCell("Seat No.");
			table.addCell("Customer");
			table.addCell("Seller Name");
			table.addCell("Price");
			table.addCell("Commission");
			table.addCell("Ticket No.");
				        
	        for(SeatsbyTrip seatBus: seatbyBus){
	        	table.addCell(seatBus.getSeat_no());
	            table.addCell(seatBus.getCustomer_name());
	            table.addCell(seatBus.getAgent_name());
	            table.addCell(seatBus.getPrice().toString());
	            table.addCell(seatBus.getTicket_no());
	        }
	        document.add(table);
	        document.close();
	        
		} catch (DocumentException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} catch (IOException e1){
			e1.printStackTrace();
		}
        
    }
    
    public Boolean IfExistFileDir(String Dir){
		File fileDir = new File(Dir);
		if(!fileDir.exists()){
			fileDir.mkdirs();
			return false;
		}
		return true;
	}
	public Boolean IfExistPDF(String pathName) {
		boolean ret = true;

		File file = new File(RESULT + pathName);
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
