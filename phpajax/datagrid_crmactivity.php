<?php
//include 'config.php';
require "../common/conn.php";
$con = $conn;

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$fdt = $_GET["dt_f"];
$tdt = $_GET["dt_t"];


if($fdt != ''){
    $dateqry = " and d.`comndt` BETWEEN DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d')";
}

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (h.hrName like '%".$searchValue."%' or 
        t.`name` like '%".$searchValue."%' or 
        c.`name` like'%".$searchValue."%' ) ";
}

## Total number of records without filtering

$strwithoutsearchquery="SELECT DATE_FORMAT(d.`comndt`,'%d/%b/%Y %h:%i:%s %p') dt,h.hrName ,t.`name` acttp ,c.`name` cus,d.note,d.place alowance,d.status,d.value ,org.name ornm
FROM `comncdetails` d,`contact` c,`hr` h ,`comnctype` t,organization org
where d.`contactid`=c.`id` and d.`makeby`=h.`id` and d.`comntp`=t.`id` and c.`contacttype`=1 and c.organization=org.orgcode $dateqry ";

$sel = mysqli_query($con,$strwithoutsearchquery);
$totalRecords = $sel->num_rows;
//$records = mysqli_fetch_assoc($sel);
//$totalRecords = $records['allcount'];

## Total number of records with filtering
$strwithsearchquery="SELECT DATE_FORMAT(d.`comndt`,'%d/%b/%Y %h:%i:%s %p') dt,h.hrName ,t.`name` acttp ,c.`name` cus,d.note,d.place alowance,d.status,d.value ,org.name ornm
FROM `comncdetails` d,`contact` c,`hr` h ,`comnctype` t,organization org
where d.`contactid`=c.`id` and d.`makeby`=h.`id` and d.`comntp`=t.`id` and c.`contacttype`=1 and c.organization=org.orgcode $dateqry ".$searchQuery;
 
$sel = mysqli_query($con,$strwithsearchquery);
$totalRecordwithFilter = $sel->num_rows;

//$records = mysqli_fetch_assoc($sel);
//$totalRecordwithFilter = $records['allcount'];

## Fetch records
//$empQuery = "select * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;


 $empQuery="SELECT DATE_FORMAT(d.`comndt`,'%d/%b/%Y') dt,h.hrName ,t.`name` acttp ,c.`name` cus,d.note,d.place alowance,d.status,d.value ,org.name ornm
FROM `comncdetails` d,`contact` c,`hr` h ,`comnctype` t,organization org
where d.`contactid`=c.`id` and d.`makeby`=h.`id` and d.`comntp`=t.`id` and c.`contacttype`=1 and c.organization=org.orgcode $dateqry ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

//echo  $empQuery;


/*
                                    <td><?php echo $row["dt"]?></td>
                                    <td><?php echo $row["hrName"];?></td>
                                    <td><?php echo $row["acttp"];?></td>
                                    <td><?php echo $row["cus"];?></td>

*/
if($columnName == "dt") $columnName = "d.comndt";

$empRecords = mysqli_query($con, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
   
    $data[] = array(
    		"dt"=>$row['dt'],
			"hrName"=>$row['hrName'],
    		"acttp"=>$row['acttp'],
    		"cus"=>$row['cus'],
    		"note"=>$row['note'],
    		"alowance"=>$row['alowance'],
    		"value"=>$row['value'],
    		"ornm"=>$row['ornm']
    	
    	);
		
}

//$data[] = array('dt'=>$empQuery);

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);

