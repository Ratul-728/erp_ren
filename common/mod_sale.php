<?php
include_once("conn.php");

function getWarehousesByPID($pid){
	global $conn;
	
	$query ="SELECT 
			cs.storerome whid,
			cs.freeqty qty,
			br.name whname
			FROM `chalanstock` cs
			LEFT JOIN branch br ON cs.storerome = br.id 
			 WHERE product=$pid AND cs.freeqty>0 AND br.name <> 'GRS'";
	 
	
	//echo $query; die;
	$result = $conn->query($query);
	
    if ($result->num_rows > 0) {
        $dbCols = 0;
        while($row = $result->fetch_assoc()) {
            $array_warehouse[$dbCols]["id"]=$row["whid"];
            $array_warehouse[$dbCols]["name"]=$row["whname"];
			$array_warehouse[$dbCols]["quantity"]=$row["qty"];
			
			$dbCols++;
        }
	}
	
	return $array_warehouse;
}

?>