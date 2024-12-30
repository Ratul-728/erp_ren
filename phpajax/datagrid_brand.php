<?php

require "../common/conn.php";
include_once('../rak_framework/fetch.php');
require "../common/user_btn_access.php";

session_start();


//print_r($_REQUEST);

$con = $conn;



## Read value

$draw = $_POST['draw']; 

$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page

$columnIndex = $_POST['order'][0]['column']; // Column index

$columnName = $_POST['columns'][$columnIndex]['data']; // Column name

$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

$searchValue = $_POST['search']['value']; // Search value


$action= $_GET['action'];

$fdt= $_GET['fd'];

$tdt= $_GET['td'];
$lvl= $_GET['lvl'];
$fd= $_GET['fdt'];
$td= $_GET['tdt'];
$dagent= $_GET['dagnt'];
$ost= $_GET['odst'];

//Employee id
$emp_id = $_GET["empid"];


$total = array();
$pqry=" ";



if($action=="brand")
    {
        
        if($searchValue != ''){
        	$searchQuery = " and ( b.`id` like '%".$searchValue."%' or b.`title` like '%".$searchValue."%' or b.`origin` like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT b.`id`,b.`code`, b.`title`, b.`origin`, b.`image`, b.makedt make_dt FROM `brand` b  WHERE 1=1 ";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        
		//$empQuery=$strwithsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
										
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $seturl="brand.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
           //$setdelurl="common/delobj.php?obj=brand&ret=brandList&mod=12&id=".$row['id'];
			$setdelurl="brandList.php?action=delete&mod=12&id=".$row['id'];
			
			$btns = array(
                array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
            	if(strlen($row["image"])>0){
					$photo="assets/images/brands/300_300/".$row["image"];
					//$delimglk="&img=".$photo;
        		}else{
        			
					$photo="assets/images/brands/blankbrandimage.png";
        		}
			
			
        		$sl=$sl+1;
            $data[] = array(
					"id"=> $row['id'],
					"code"=> $row['code'],
                    "make_dt"=>$row['make_dt'],
                    "photo"=>'<img src='.$photo.' width="50" >',
            		"title"=>$row['title'],
            		"origin"=>$row['origin'],
					"action_buttons"=>getGridBtns($btns),
            	);
			$delimglk="";
			$photo = "";
        } 
    }
		//print_r($data);die;


           $response = array(

            "draw" => intval($draw),

            "iTotalRecords" => $totalRecords,

            "iTotalDisplayRecords" => $totalRecordwithFilter,

            "aaData" => $data,

            "total"    => $total,
			"query" => $empQuery,
			 "request" => $columnSortOrder,

        );     

        $cmbstatus_str = "";

        //echo $data;die;

        echo json_encode($response);



?>