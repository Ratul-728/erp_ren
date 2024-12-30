<?php
ini_set("display_error",1);	
// Include autoloader 
require_once 'dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 


if(!isset($_REQUEST['print'])){
	echo "Error printing";
}else{
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();

$filename = $_REQUEST['print'];


// Load content from html file 
//$html = file_get_contents("pdf-content.html");
$html = file_get_contents($filename);
$dompdf->loadHtml($html); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'landscape'); 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
// Output the generated PDF (1 = download and 0 = preview) 
$dompdf->stream("codexworld", array("Attachment" => 0));

	
}

?>