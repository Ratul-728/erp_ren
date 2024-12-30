<?php

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

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (c.name like '%".$searchValue."%' or 
        tp.`name` like '%".$searchValue."%' or  o.`name` like '%".$searchValue."%' or ds.`name` like '%".$searchValue."%' or
        dp.name like '%".$searchValue."%' or  c.`phone` like '%".$searchValue."%' or c.`email` like '%".$searchValue."%' or 
        c.`details` like'%".$searchValue."%' ) ";
}

## Total number of records without filtering   #c.`id`,

$strwithoutsearchquery="SELECT c.`id`,tp.`name` `contacttype`,c.`name`,o.`name` `organization`,ds.`name` `designation`,dp.name `department`,c.`phone`,c.`email`
,c.`details`,c.`photo`,c.`contactcode` 
FROM `contact` c
left join `designation` ds on c.designation=ds.id 
left join `department` dp on c.department=dp.id
left join `contacttype` tp ON c.contacttype=tp.id
left join `organization` o on  c.`organization`=o.`orgcode`
where  c.contacttype<>3 ";

$sel = mysqli_query($con,$strwithoutsearchquery);
$totalRecords = $sel->num_rows;

## Total number of records with filtering # c.`id`,
$strwithsearchquery="SELECT c.`id`,tp.`name` `contacttype`,c.`name`,o.`name` `organization`,ds.`name` `designation`,dp.name `department`,c.`phone`,c.`email`
,c.`details`,c.`photo`,c.`contactcode`,c.hidden hidden
FROM `contact` c
left join `designation` ds on c.designation=ds.id 
left join `department` dp on c.department=dp.id
left join `contacttype` tp ON c.contacttype=tp.id
left join `organization` o on  c.`organization`=o.`orgcode`
where  c.contacttype<>3   ".$searchQuery;
 
$sel = mysqli_query($con,$strwithsearchquery);
$totalRecordwithFilter = $sel->num_rows;



##.`id`,

 $empQuery="SELECT c.`id`,tp.`name` `contacttype`,c.`name`,o.`name` `organization`,ds.`name` `designation`,dp.name `department`,c.`phone`,c.`email`
,c.`details`,c.`photo`,c.`contactcode`,c.hidden hidden 
FROM `contact` c
left join `designation` ds on c.designation=ds.id 
left join `department` dp on c.department=dp.id
left join `contacttype` tp ON c.contacttype=tp.id
left join `organization` o on  c.`organization`=o.`orgcode`
where  c.contacttype<>3   ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;



$empRecords = mysqli_query($con, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
   $seturl="contact.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";
   $setdelurl="common/delobj.php?obj=contact&ret=contactList&mod=2&id=".$row['id'];
   
    $photo=$rootpath."/common/upload/contact/".$row["contactcode"].".jpg";
    $conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
    if (file_exists($photo)) {
		$photo="common/upload/contact/".$row["contactcode"].".jpg";
		}else{
			$photo="images/blankuserimage.png";
		}
    $data[] = array(
            "photo"=>'<img src='.$photo.' width="50" height="50">',
           	"contacttype"=>$row['contacttype'],
			"name"=>'<a href="'.$conthisturl.'">'.$row["name"].'</a>',
    		"organization"=>$row['organization'],
    		"department"=>$row['department'],
    		"designation"=>$row['designation'],
			"phone"=>$row['phone'],
    		"email"=>$row['email'],
    		"hidden"=>$row['hidden'],
    		"details"=>$row['details'],
    		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
    		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'
    	);
		
}

//$data[] = array('dt'=>$empQuery);
//$seturl="contact.php?res=4&msg='Update Data'&id=".$uid."&mod=2";
## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);

?>