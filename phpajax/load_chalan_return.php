<?php 
	include_once('../common/conn.php');
	
	$barcd = $_GET['barcd'];

	$qry='select `poid`,`unitprice` from poitem WHERE barcode="'.$barcd.'"';
	
	$resultitmdt = $conn->query($qry); 
	if($resultitmdt->num_rows > 0){
		while($rowitmdt = $resultitmdt->fetch_assoc()){
                  $itmprice= $rowitmdt["unitprice"];
                  $chalanno= $rowitmdt["poid"];
              }
	}		
		
	
$data = array(
	'itmprice'		=>	$itmprice,
	'chalanno'	=>	$chalanno,
	//'query'			=>	$qry,
	//'request'			=>	'<pre style="font-size:14px;">'.print_r($_REQUEST, true).'</pre>',
);	


echo json_encode($data);

?>