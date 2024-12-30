<?php



require "../common/conn.php";
include_once('../rak_framework/fetch.php');

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


$cmbstatus = $_REQUEST["cmbstatus"];
if($cmbstatus){
	$cmbstatus_str = "and orderstatus = ".$cmbstatus;
}else{$cmbstatus_str = "";}

$total = array();
$pqry=" ";



if($action=="stock_status")

     {
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and (p.code like '%".$searchValue."%' or 
			p.name like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
        /*
        $strwithoutsearchquery="SELECT s.id, p.id pid, p.code, p.image, s.product, p.name prod, s.freeqty, s.bookqty, s.orderedqty,s.deliveredqty,s.issuedqty,
		CASE WHEN s.freeqty < 0 THEN abs(s.freeqty) ELSE 0 END backordered
        FROM stock s 
		left join item p on s.product=p.id
		left join soitemdetails sid on s.product=sid.productid
        where  1=1 ";

	
	     $strwithoutsearchquery="SELECT s.product, p.id pid, p.code, p.image, s.product, p.name prod, s.freeqty, s.bookqty, s.orderedqty,s.deliveredqty,s.issuedqty,
		(SELECT COUNT(backorderedqty) FROM soitemdetails WHERE backorderedqty>0 AND productid = s.product)  backordered
		 
        FROM item p 
		left join stock s on p.id = s.product
		left join soitemdetails sid on s.product=sid.productid
        where  1=1 ";
		*/
	
        $strwithoutsearchquery="SELECT s.id, p.id pid, p.code, p.image, s.product, p.name prod, s.freeqty, s.bookqty, s.orderedqty,s.deliveredqty,s.issuedqty,
		(SELECT COUNT(backorderedqty) FROM soitemdetails WHERE backorderedqty>0 AND productid = s.product)  backordered
		
        FROM stock s 
		left join item p on s.product=p.id
        where  1=1 ";	
	
	
	
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="product.php?res=4&msg='Update Data'&id=".$row['pid']."&mod=1";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
               $photo=$rootpath."/assets/images/products/300_300/".$row["image"];
               if (file_exists($photo)) {

        		$photo="assets/images/products/300_300/".$row["image"];

        		}else{

        			$photo="assets/images/products/300_300/placeholder.jpg";

        		}
              $sl=$sl+1;  
			
			//$backordered = ($row['freeqty'] < 0)?$row['freeqty']:0;
			$stkclass = ($row['freeqty'] < 1)?'btn-danger':'btn-info';
			$bkstkclass = ($row['backordered'] > 0)?'btn-bkordered':'btn-info';
			$ordclass = ($row['orderedqty']> 0)?'btn-success':'btn-info';
			$delivclass = ($row['deliveredqty'] > 0)?'btn-delivered':'btn-info';
			$bookedclass = ($row['bookqty'] > 0)?'btn-booked':'btn-info';
			$issuedclass = ($row['issuedqty'] > 0)?'btn-issued':'btn-info';
			
			$backordered = ($row['backordered'])?$row['backordered']:0;
            $data[] = array(
					
                    "id"=>$sl,
                    "image"=>'<img src='.$photo.' width="50">',
                    "productcode"=>$row['code'],
            		"prod"=>$row['prod'],
            		"deliveredqty"=>'<a class="btn '.$delivclass.' btn-xs" href="inv_soitemList.php?action=stkbyprdct&pid='.$row['pid'].'&status=5&res=0&mod=12">'.$row['deliveredqty'].'</a>',
					"orderedqty"=>'<a class="btn '.$ordclass.' btn-xs" href="inv_soitemList.php?action=stkbyprdct&pid='.$row['pid'].'&status=3&res=0&mod=12">'.$row['orderedqty'].'</a>',
            		"freeqty"=>'<a class="btn '.$stkclass.' btn-xs" href="#">'.$row['freeqty'].'</a>',
					"backordered"=>'<a class="btn '.$bkstkclass.' btn-xs" href="inv_soitemList.php?action=stkbyprdct&pid='.$row['pid'].'&status=11&res=0&mod=12">'.$backordered.'</a>',
            		"bookqty"=>'<a class="btn '.$bookedclass.' btn-xs" href="inv_soitemList.php?action=stkbyprdct&pid='.$row['pid'].'&status=9&res=0&mod=12">'.$row['bookqty'].'</a>',
            		"issuedqty"=>'<a class="btn '.$issuedclass.' btn-xs" href="#">'.$row['issuedqty'].'</a>'
					
            	);
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