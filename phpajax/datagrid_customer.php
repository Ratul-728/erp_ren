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


$cmbstatus = $_REQUEST["industry"];
if($cmbstatus){
	$cmbstatus_str = "and industry = ".$cmbstatus;
}else{$cmbstatus_str = "";}

$total = array();
$pqry=" ";



if($action=="org"){

        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (o.`name` like '%".$searchValue."%' or  concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  
        	i.`name`  like '%".$searchValue."%' or op.`name` like '%".$searchValue."%' or o.`contactno` like '%".$searchValue."%' or  
        	o.`email` like '%".$searchValue."%' or o.`website` like '%".$searchValue."%' or o.`note` like '%".$searchValue."%' or 
        	o.street like '%".$searchValue."%' or o.orgcode like '%".$searchValue."%')";

        }

        

        ## Total number of records without filtering   #c.`id`, 

        

        $strwithoutsearchquery="SELECT o.makedt makedt, o.note note, o.orgcode customerid, o.street address, o.`id`,o.`name`,i.`name` `industry`,
                                o.`employeesize`,op.`name` `operationstatus`,o.`bsnsvalue`,o.`contactno`,o.`email`,o.`website`,o.`details` ,o.type,
                                concat(e.firstname,'',e.lastname) accmgr   
                                FROM organization o 
                                LEFT JOIN businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`   left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` 
                                WHERE 1=1 $cmbstatus_str";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

        ##.`id`,

//        if($columnName == 'name'){
//
//            $columnName = 'o.id';
//
//            $columnSortOrder = "DESC";
//
//        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="organization.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";

           $setdelurl="common/delobj.php?obj=organization&ret=organizationList&mod=2&id=".$row['id'];
           
			$btns = array(
				array('edit','organization.php?res=4&msg=Update Data&id='.$row['id'].'&mod=2','class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete','common/delobj.php?obj=organization&ret=organizationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

           $conthisturl="contactDetail_org.php?id=".$row['id']."&mod=2";
           
           $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
           $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';

            //$photo=$rootpath."/common/upload/contact/".$row["contactcode"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

           // if (file_exists($photo)) {

        	//	$photo="common/upload/contact/".$row["employeecode"].".jpg";

        	//	}else{

        	//		$photo="images/blankuserimage.png";

        //		}
        
            if($row["type"] == 2){
                $type = "Individual";
            }else if ($row["type"] == 1){
                $type = "Organization";
            }

            $data[] = array(


                
                    
                   	"customerid"=>$row['customerid'],
                    //"name"=>'<a class=""  href="'.$seturl.'">'.$row["name"].'</a>',
					
					"name"=>'<a class=""  href="'.$conthisturl.'">'.$row["name"].'</a>',
					
					"type"=>$type,
                    
            		"industry"=>$row['industry'],

            		"operationstatus"=>$row['operationstatus'],

            		"contactno"=>$row['contactno'],

        			"email"=>$row['email'],

            		"website"=>$row['website'],

        			"accmgr"=>$row['accmgr'],

        		 	"address"=>$row['address'],
        		 	
        		 	"makedt"=>$row['makedt'],

            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		//"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',
            		
            		"action_buttons"=>getGridBtns($btns),

            	);

        } 

    }
if($action=="org_rdl"){

        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (o.`name` like '%".$searchValue."%' or  concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  
        	i.`name`  like '%".$searchValue."%' or op.`name` like '%".$searchValue."%' or o.`contactno` like '%".$searchValue."%' or  
        	o.`email` like '%".$searchValue."%' or o.`website` like '%".$searchValue."%' or o.`note` like '%".$searchValue."%' or 
        	o.street like '%".$searchValue."%' or o.orgcode like '%".$searchValue."%')";

        }

        

        ## Total number of records without filtering   #c.`id`, 

        

        $strwithoutsearchquery="SELECT o.makedt makedt, o.note note, o.orgcode customerid, o.street address, o.`id`,o.`name`,i.`name` `industry`,
                                o.`employeesize`,op.`name` `operationstatus`,o.`bsnsvalue`,o.`contactno`,o.`email`,o.`website`,o.`details` ,o.type,
                                concat(e.firstname,'',e.lastname) accmgr   
                                FROM organization o 
                                LEFT JOIN businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`   left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` 
                                WHERE 1=1 $cmbstatus_str";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

        ##.`id`,

//        if($columnName == 'name'){
//
//            $columnName = 'o.id';
//
//            $columnSortOrder = "DESC";
//
//        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="organization_rdl.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

           $setdelurl="common/delobj.php?obj=organization&ret=organizationList&mod=3&id=".$row['id'];
           
			$btns = array(
				array('edit','organization_rdl.php?res=4&msg=Update Data&id='.$row['id'].'&mod=3','class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete','common/delobj.php?obj=organization&ret=organizationList&mod=3&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

           $conthisturl="contactDetail_org.php?id=".$row['id']."&mod=2";
           
           $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
           $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';

            //$photo=$rootpath."/common/upload/contact/".$row["contactcode"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

           // if (file_exists($photo)) {

        	//	$photo="common/upload/contact/".$row["employeecode"].".jpg";

        	//	}else{

        	//		$photo="images/blankuserimage.png";

        //		}
        
            if($row["type"] == 2){
                $type = "Individual";
            }else if ($row["type"] == 1){
                $type = "Organization";
            }

            $data[] = array(


                
                    
                   	"customerid"=>$row['customerid'],
                    //"name"=>'<a class=""  href="'.$seturl.'">'.$row["name"].'</a>',
					
					"name"=>'<a class=""  href="'.$conthisturl.'">'.$row["name"].'</a>',
					
					"type"=>$type,
                    
            		"industry"=>$row['industry'],

            		"operationstatus"=>$row['operationstatus'],

            		"contactno"=>$row['contactno'],

        			"email"=>$row['email'],

            		"website"=>$row['website'],

        			"accmgr"=>$row['accmgr'],

        		 	"address"=>$row['address'],
        		 	
        		 	"makedt"=>$row['makedt'],

            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		//"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',
            		
            		"action_buttons"=>getGridBtns($btns),

            	);

        } 

    }    
    // if($action=="org"){
    
		//print_r($data);die;


           $response = array(

            "draw" => intval($draw),

            "iTotalRecords" => $totalRecords,

            "iTotalDisplayRecords" => $totalRecordwithFilter,

            "aaData" => $data,

            "total"    => $total,
			"query" => $empQuery,
			"request" => $_REQUEST,			   

        );     

        $cmbstatus_str = "";

        //echo $data;die;

        echo json_encode($response);



?>