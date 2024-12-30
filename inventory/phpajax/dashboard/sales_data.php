<?php
require "../common/conn.php";
$con = $conn;

$act= $_GET['action'];
extract($_REQUEST);

echo $datastr;die;

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];


$strwithoutsearchquery="SELECT c.`id`,tp.`name` `contacttype`,c.`name`,o.`name` `organization`,ds.`name` `designation`,dp.name `department`,c.`phone`,c.`email`
,c.`details`,c.`photo`,c.`contactcode` 
FROM `contact` c
left join `designation` ds on c.designation=ds.id 
left join `department` dp on c.department=dp.id
left join `contacttype` tp ON c.contacttype=tp.id
left join `organization` o on  c.`organization`=o.`orgcode`
where  c.contacttype<>3 ";

$empRecords = mysqli_query($con, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
     $data[] = array(
            "photo"=>'<img src='.$photo.' width="50" height="50">',
           	"contacttype"=>$row['contacttype'],
			"name"=>'<a class="btn btn-info btn-xs"  href="'.$conthisturl.'">'.$row["name"].'</a>',
    		"organization"=>$row['organization'],
    		"department"=>$row['department'],
    		"designation"=>$row['designation'],
			"phone"=>$row['phone'],
    		"email"=>$row['email'],
    		"details"=>$row['details'],
    		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
    	);
		
}



## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
?>