<?php
/**
 * TVLGIAO WPDANCE FRAMEWORK 2017.
 *
 * @author : Cao Vuong.
 * -Email  : hoangcaovuong92@gmail.com.
 */

// Reference the Dompdf namespace
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;


// $attachment_content : HTML content
if(!function_exists ('ifind_save_pdf_file')){
	function ifind_save_pdf_file($attachment_content){
		// Notication: Add to begin of this file : use Dompdf\Dompdf;
		// Instantiate and use the dompdf class
		$dompdf = new Dompdf();
		
		// Load content from html file
		$attachment_content = ifind_sanitize_html_content($attachment_content);
		$dompdf->loadHtml($attachment_content, 'UTF-8');

		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4');
		//$dompdf->setPaper('A4', 'landscape');
		$dompdf->set_option('defaultMediaType', 'all');
		$dompdf->set_option('isFontSubsettingEnabled', true);

		// Courier (Normal, Bold, Oblique, and BoldOblique variants)
		// Helvetica (Normal, Bold, Oblique, and BoldOblique variants)
		// Times (Normal, Bold, Oblique, and BoldOblique variants)
		// Symbol
		// ZapfDingbats
		$dompdf->set_option('defaultFont', 'Helvetica'); 

		// Render the HTML as PDF
		$dompdf->render();

		//File Path
		$pdfroot  = ABSPATH.'/pdf_file/';

		//Create folder if not exists
		if (!is_dir($pdfroot)) {
			mkdir($pdfroot, 0777, true);
		}

		//File name
		$pdfroot .= 'report_'.date("F j,Y_G-i-s").'.pdf';

		//Create file if not exists
		if (!is_file($pdfroot)) {}

		//Download file
		//$dompdf->stream('title.pdf');

		$pdf_string = $dompdf->output();

		//Write pdf file - return url of file if success
		return file_put_contents($pdfroot, $pdf_string) ? $pdfroot : false;
	}
}