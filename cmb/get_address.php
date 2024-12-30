<?php
//ini_set('display_errors',1);

//echo 'DDDD'.$_REQUEST["orgid"];
//die;
require "../common/conn.php";




//echo $_REQUEST["orgid"];

if($_REQUEST["orgid"]){


$id = $_REQUEST["orgid"];


   
    $qry = '
	SELECT o.name, ds.name district,cn.name country, a.name area,  street,zip, o.contactno,o.address
		FROM `organization`o 
		LEFT JOIN district ds ON o.district = ds.id
		LEFT JOIN country cn ON o.country = cn.id
		LEFT JOIN area a ON o.area = a.id
		WHERE o.id=	'.$id;
    
    $result = $conn->query($qry);
    while($row = $result->fetch_assoc()){
            $address = "Contact Number: ".$row["contactno"]."<br>";
        	$address .= ($row["address"])?$row["address"]."<br>":"";
        	$address .= ($row["street"])?$row["street"]."<br>":"";
		    $address .= ($row["area"])?$row["area"].", ":"";
			$address .= ($row["district"])?$row["district"]:"";
			$address .= ($row["zip"])?"-".$row["zip"].", ":"";
			$address .= ($row["country"])?$row["country"]:"";
			
		
	}
	
	//$address = str_replace( "\n", '<br />', $address ); 
	$address = str_replace( "<br>", "\n", $address ); 
	echo $address;
	
}

?>