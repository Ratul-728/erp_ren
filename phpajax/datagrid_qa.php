<?php



require "../common/conn.php";
//require "../common/gridbtns.php";
include_once('../rak_framework/fetch.php');
include_once("../rak_framework/listgrabber.php");
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


$cmbstatus = $_REQUEST["cmbstatus"];
if($cmbstatus){
	$cmbstatus_str = "and qa.status = ".$cmbstatus;
}else{$cmbstatus_str = "";}

$cmbcustomer = $_REQUEST["customer"];

if($cmbcustomer != '' && $action=="qatransfer"){
	$cmbcustomer = "and q.product = ".$cmbcustomer;
}else if($cmbcustomer != ''){
	$cmbcustomer = "and inv.organization = ".$cmbcustomer;
}else{$cmbcustomer = "";}

$total = array();
$pqry=" ";



if($action=="qatest")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (qa.order_id like '%".$searchValue."%' or  inv.invoiceno like '%".$searchValue."%' or  org.name like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT qa.order_id,DATE_FORMAT(so.orderdate, '%d/%b/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date,org.name customer,
                    MIN(DATE_FORMAT( qw.expted_deliverey_date,'%d/%b/%Y')) AS expted_deliverey_date,
                    COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass,inv.invoiceno,
                    qaq.ordered_qty AS total_qty,
                    qaq.pass_qty AS total_pass_qty, 
                    qaq.damaged_qty AS total_damaged_qty, qaq.defect_qty AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN
                    (select  qa.`order_id`,sum(qaw.`ordered_qty`)ordered_qty,sum(qaw.`pass_qty`) pass_qty,sum(qaw.`defect_qty`)defect_qty,sum(qaw.`damaged_qty`) damaged_qty from   qa_warehouse qaw ,qa where qa.id=qaw.qa_id  and qaw.qa_type = 1 group by qa.`order_id`
                     ) qaq
                    ON qaq.`order_id` = qa.`order_id`                    
                    LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN invoice inv ON qa.order_id = inv.soid
                    LEFT JOIN quotation_warehouse qw ON qw.socode=qa.order_id LEFT JOIN organization org ON org.id=so.organization
                    WHERE qa.type=1   $cmbstatus_str $cmbcustomer";

        
        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id,so.orderdate,qa.delivery_date,org.name";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id,so.orderdate,qa.delivery_date,org.name";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "so.id";
        }
        if($columnName == "orderdate"){
            $columnName = "so.orderdate";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id,so.orderdate,qa.delivery_date,org.name order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
            $seturl="qa_detail_submit.php?res=4&msg='Update Data'&type=1&id=".$row['order_id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"order_id"=>$row['order_id'],
					
					"invoiceno"=>$row['invoiceno'],
					
					"customer"=>$row['customer'],

            		"expted_deliverey_date"=>$row['expted_deliverey_date'],

            		"delivery_date"=>$row['delivery_date'],
					
					"quantity"=>$row['noitems'],
					
					"totqty"=>$row['total_qty'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }

if($action=="qaperiodic")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.name like '%".$searchValue."%' or  b.name like '%".$searchValue."%')"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT qastatus.name st, qastatus.dclass, qa.quantity, i.name prodnm, i.barcode, b.name warehouse,qa.id,
                    qa_warehouse.ordered_qty AS total_qty,
                    qa_warehouse.pass_qty AS total_pass_qty, 
                    qa_warehouse.damaged_qty AS total_damaged_qty, qa_warehouse.defect_qty AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN item i ON qa.product_id=i.id LEFT JOIN branch b ON b.id=qa_warehouse.warehouse_id
                    WHERE qa.type=9 ";

        
        $strwithoutsearchquery=$basequery;

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery;

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "qa.id";
        }

         $empQuery=$basequery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['st'].'</kbd>';
            $seturl="qa_periodic_submit.php?res=4&msg='Update Data'&type=9&id=".$row['id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"prodnm"=>$row['prodnm'],
					
					"barcode"=>$row['barcode'],
					
					"warehouse"=>$row['warehouse'],
					
					"quantity"=>$row['quantity'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }
    
if($action=="qaco")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (qa.order_id like '%".$searchValue."%' or  inv.invoiceno like '%".$searchValue."%' or  org.name like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT qa.order_id,DATE_FORMAT(so.orderdate, '%d/%b/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date,org.name customer,
                    MIN(DATE_FORMAT( qw.expted_deliverey_date,'%d/%b/%Y')) AS expted_deliverey_date,
                    COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass,inv.invoiceno,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty, 
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN invoice inv ON qa.order_id = inv.soid
                    LEFT JOIN quotation_warehouse qw ON qw.socode=qa.order_id LEFT JOIN organization org ON org.id=so.organization
                    WHERE qa.type=8 $cmbstatus_str $cmbcustomer";

        
        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "qa.id";
        }
        if($columnName == "orderdate"){
            $columnName = "so.orderdate";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
            $seturl="qa_co_submit.php?res=4&msg='Update Data'&type=8&id=".$row['order_id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"order_id"=>$row['order_id'],
					
					"invoiceno"=>$row['invoiceno'],
					
					"customer"=>$row['customer'],

            		"expted_deliverey_date"=>$row['expted_deliverey_date'],

            		"delivery_date"=>$row['delivery_date'],
					
					"quantity"=>$row['noitems'],
					
					"totqty"=>$row['total_qty'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }
    
if($action=="qaresult")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and qa.order_id like '%".$searchValue."%' or org.name like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, so.orderdate, qa.delivery_date, 
                   (SELECT COUNT(qaa.id) FROM qa qaa WHERE qaa.order_id = qa.order_id) AS noitems,
                   #(SELECT SUM(qaw.ordered_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_order_qty,
                   (SELECT SUM(qaw.pass_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_pass_qty,
                   (SELECT SUM(qaw.defect_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_defect_qty,
                   (SELECT SUM(qaw.damaged_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_damaged_qty
                    FROM qa qa
                    JOIN qa_warehouse qw ON qa.id = qw.qa_id
                    LEFT JOIN soitem so ON so.socode = qa.order_id
                    Where 1 = 1
                    $cmbstatus_str";*/
        $basequery = "SELECT qa.order_id, org.name orgname, DATE_FORMAT(so.orderdate, '%d/%b/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date, 
                    COUNT(DISTINCT qa.id) AS noitems, inv.paymentSt,c.qa_status,
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty,qa.id qaid, qa.approval,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,DATEDIFF(qa.date_iniciated, so.orderdate) dtdiff, MAX(qa.status) st,
                    (SELECT MAX(DATE_FORMAT(quow.expted_deliverey_date, '%d/%b/%Y'))
                    FROM quotation_warehouse quow
                    WHERE qa.order_id = quow.socode) AS expdt,
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty, qastatus.name status, qastatus.dclass 
                    FROM qa LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id 
                    LEFT JOIN soitem so ON so.socode = qa.order_id LEFT JOIN invoice inv ON inv.soid = qa.order_id 
                    LEFT JOIN organization org ON org.id=inv.organization LEFT JOIN co c ON qa.order_id = c.order_id
                    WHERE (qa.type=1 or qa.type=7) $cmbstatus_str";

        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl")
        {
            $columnName = "qa.id";
        }
        else if ($columnName == "orderdate")
        { 
            $columnName = "so.orderdate";
        }
        else if ($columnName == "expdt")
        { 
            $columnName = "quow.expted_deliverey_date";
        }
        else
        {
            $columnName = "qa.id";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;
		        $status= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
			    
			    $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			    $defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			    $damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

                $st= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
    			
                $i++;
                
                $seturl="qa_detail.php?res=4&msg='Update Data'&id=".$row['order_id']."&mod=3";
                
                $btns = [];
                $btns[] = array('view', $seturl, 'class="btn btn-info btn-xs" title="View Details"');
               //  if (($row["paymentSt"] == 4 && $row["st"] > 1) || $row["approval"] == 1)
                
                if ($row["paymentSt"] != 4 && $row["paymentSt"] != 3 && $row["st"] > 1 && $row["approval"] != 1) {
                    $btns[] = array('generate', '/phpajax/send_approval_do.php?qaid='.$row["order_id"], 'class="btn btn-info btn-xs fullpay" title="Generate DO"');
                }
                else if ((($row["paymentSt"] == 4 || $row["paymentSt"] == 3) && $row["st"] > 1) || $row["approval"] == 1) {
                    $btns[] = array('generate', 'deliveryQA.php?cmbempnm=' . $row['order_id'] . '&mod=3', 'class="btn btn-info btn-xs" title="Generate DO"');
                    $btns[] = array('edit', 'deliveryCO.php?cmbempnm=' . $row['order_id'] . '&mod=3', 'class="btn btn-info btn-xs" title="Generate CO/TO"');
                    
                }

                if($row["dtdiff"] == 0) $row["dtdiff"] = 1;
                
    
                $data[] = array(
    
                       // "id"=>$i,//$row['hrName'],
    					//"query"=>$strwithsearchquery,
    				
    					"sl"=>$i,
    					
    					"order_id"=>$row['order_id'],//$empQuery,//
    					
    					"orgname"=>$row["orgname"],
    					
    					"ordered_qty"=>$row['noitems'],
    					
    					"tot_qty"=>$row['total_qty'],
    
                		"orderdate"=>$row['orderdate'],
    
                		"delivery_date"=> $row["expdt"],
    
            			"status"=>$st,
            		
            			"dateddiff"=> $row["dtdiff"],
            			
            			"deliverystatus"=> $status,
    				
    				    "action"=>getGridBtns($btns)
    
                	);
                	

        } 

    }
if($action=="qaissueresult")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and qa.order_id like '%".$searchValue."%' or org.name like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, so.orderdate, qa.delivery_date, 
                   (SELECT COUNT(qaa.id) FROM qa qaa WHERE qaa.order_id = qa.order_id) AS noitems,
                   #(SELECT SUM(qaw.ordered_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_order_qty,
                   (SELECT SUM(qaw.pass_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_pass_qty,
                   (SELECT SUM(qaw.defect_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_defect_qty,
                   (SELECT SUM(qaw.damaged_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_damaged_qty
                    FROM qa qa
                    JOIN qa_warehouse qw ON qa.id = qw.qa_id
                    LEFT JOIN soitem so ON so.socode = qa.order_id
                    Where 1 = 1
                    $cmbstatus_str";*/
        $basequery = "SELECT qa.order_id, iw.name warehouse, iw.address, DATE_FORMAT(ioo.iodt, '%d/%b/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date, COUNT(DISTINCT qa.id) AS noitems, 
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty,qa.id qaid, qa.approval,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,DATEDIFF(qa.date_iniciated, ioo.iodt) dtdiff, MAX(qa.status) st,
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty, qastatus.name status, qastatus.dclass 
                    FROM qa LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id 
                    LEFT JOIN issue_order ioo ON ioo.ioid= qa.order_id LEFT JOIN issue_warehouse iw ON iw.id=ioo.issue_warehouse
                    WHERE (qa.type=5) $cmbstatus_str";

        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl")
        {
            $columnName = "qa.id";
        }
        else if ($columnName == "orderdate")
        { 
            $columnName = "ioo.orderdate";
        }
        else
        {
            $columnName = "qa.id";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;
		        $status= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
			    
			    $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			    $defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			    $damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

                $st= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
    			
                $i++;
                
                $seturl="qa_detail.php?res=4&msg='Update Data'&id=".$row['order_id']."&mod=3";
                
                $btns = [];
                $btns[] = array('view', $seturl, 'class="btn btn-info btn-xs" title="View Details"');
                
                $btns[] = array('generate', 'deliveryIssueQA.php?cmbempnm=' . $row['order_id'] . '&mod=3', 'class="btn btn-info btn-xs" title="Generate DO"');
                

                
                
    
                $data[] = array(
    
                       // "id"=>$i,//$row['hrName'],
    					//"query"=>$strwithsearchquery,
    				
    					"sl"=>$i,
    					
    					"order_id"=>$row['order_id'],//$empQuery,//
    					
    					"warehouse"=>$row["warehouse"],
    					
    					"address"=>$row["address"],
    					
    					"ordered_qty"=>$row['noitems'],
    					
    					"tot_qty"=>$row['total_qty'],
    
                		"orderdate"=>$row['orderdate'],
    
                		"delivery_date"=> $row["expdt"],
    
            			"status"=>$st,
            		
            			"dateddiff"=> $row["dtdiff"],
            			
            			"deliverystatus"=> $status,
    				
    				    "action"=>getGridBtns($btns)
    
                	);
                	

        } 

    }

if($action=="qatransferresult")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and ts.toid like '%".$searchValue."%' or org.name like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, so.orderdate, qa.delivery_date, 
                   (SELECT COUNT(qaa.id) FROM qa qaa WHERE qaa.order_id = qa.order_id) AS noitems,
                   #(SELECT SUM(qaw.ordered_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_order_qty,
                   (SELECT SUM(qaw.pass_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_pass_qty,
                   (SELECT SUM(qaw.defect_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_defect_qty,
                   (SELECT SUM(qaw.damaged_qty) FROM qa_warehouse qaw WHERE qaw.qa_id = qa.id) AS total_damaged_qty
                    FROM qa qa
                    JOIN qa_warehouse qw ON qa.id = qw.qa_id
                    LEFT JOIN soitem so ON so.socode = qa.order_id
                    Where 1 = 1
                    $cmbstatus_str";*/
        $basequery = "SELECT ts.toid transferid, DATE_FORMAT(ts.tansferdt, '%d/%b/%Y') transferdt, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date, COUNT(DISTINCT qa.id) AS noitems, 
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty,qa.id qaid, qa.approval,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,DATEDIFF(qa.date_iniciated, ts.tansferdt) dtdiff, MAX(qa.status) st,
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty, qastatus.name status, qastatus.dclass 
                    FROM qa LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id 
                    LEFT JOIN transfer_stock ts ON ts.id=qa.order_id
                    WHERE (qa.type=4) $cmbstatus_str";

        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl")
        {
            $columnName = "qa.id";
        }
        else if ($columnName == "transferdt")
        { 
            $columnName = "ts.transferdt";
        }
        else
        {
            $columnName = "qa.id";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;
		        $status= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
			    
			    $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			    $defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			    $damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

                $st= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
    			
                $i++;
                
                $seturl="qa_detail.php?res=4&msg='Update Data'&id=".$row['transferid']."&mod=3";
                
                $btns = [];
                $btns[] = array('view', $seturl, 'class="btn btn-info btn-xs" title="View Details"');
                
                $btns[] = array('generate', 'deliveryTransferQA.php?cmbempnm=' . $row['transferid'] . '&mod=3', 'class="btn btn-info btn-xs" title="Generate DO"');
                

                
                
    
                $data[] = array(
    
                       // "id"=>$i,//$row['hrName'],
    					//"query"=>$strwithsearchquery,
    				
    					"sl"=>$i,
    					
    					"transferid"=>$row['transferid'],//$empQuery,//
    					
    					"transferdt"=>$row["transferdt"],
    					
    					"ordered_qty"=>$row['noitems'],
    					
    					"tot_qty"=>$row['total_qty'],
    
                		"orderdate"=>$row['orderdate'],
    
                		"delivery_date"=> $row["expdt"],
    
            			"status"=>$st,
            		
            			"dateddiff"=> $row["dtdiff"],
            			
            			"deliverystatus"=> $status,
    				
    				    "action"=>getGridBtns($btns)
    
                	);
                	

        } 

    }
    
if($action=="qapurchase")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and qa.order_id like '%".$searchValue."%' or  sup.name like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT qa.order_id,DATE_FORMAT(qa.delivery_date, '%d/%b/%Y') delivery_date, COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass, sup.name supplier,
                        SUM(qa_warehouse.pass_qty) AS total_pass_qty, 
                        SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty,
                        SUM(qa_warehouse.ordered_qty) AS total_qty
                        FROM qa 
                        LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id
                        LEFT JOIN qastatus qastatus ON qastatus.id = qa.status
                        LEFT JOIN suplier sup ON sup.id=qa_warehouse.warehouse_id
                        WHERE qa.type=2  $cmbstatus_str";

        
        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "qa.id";
        }
        if($columnName == "delivery_date"){
            $columnName = "qa.delivery_date";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
            $seturl="qa_detail_submit.php?res=4&msg='Update Data'&type=2&id=".$row['order_id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"order_id"=>$row['order_id'],
					
					"supplier"=>$row['supplier'],

            		"delivery_date"=>$row['delivery_date'],
					
					"quantity"=>$row['noitems'],
					
					"total_qty"=>$row['total_qty'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }
    
if($action=="qareturn")

    {
        //Need to add group orderid
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and qa.order_id like '%".$searchValue."%' or  inv.invoiceno like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT qa.order_id,DATE_FORMAT(so.orderdate, '%d/%b/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date, 
                    COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass,inv.invoiceno,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty, 
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN invoice inv ON qa.order_id = inv.soid
                    WHERE qa.type=3 $cmbstatus_str";

        
        $strwithoutsearchquery=$basequery. " GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "qa.id";
        }
        if($columnName == "orderdate"){
            $columnName = "so.orderdate";
        }
        if($columnName == "delivery_date"){
            $columnName = "qa.delivery_date";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
            $seturl="qa_detail_submit.php?res=4&msg='Update Data'&type=3&id=".$row['order_id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"order_id"=>$row['order_id'],
					
					"invoiceno"=>$row['invoiceno'],

            		"orderdate"=>$row['orderdate'],

            		"delivery_date"=>$row['delivery_date'],
					
					"quantity"=>$row['noitems'],
					
					"totqty"=>$row['total_qty'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }

if($action=="qatransfer")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (ts.toid like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT ts.toid, DATE_FORMAT( ts.tansferdt, '%d/%b/%Y') tansferdt, qs.name sts, qs.dclass,ts.id,
                      SUM(qaw.pass_qty) total_pass_qty, SUM(qaw.defect_qty) total_defect_qty, SUM(qaw.damaged_qty) total_damaged_qty
                      FROM transfer_stock ts LEFT JOIN qa q ON q.order_id=ts.id LEFT JOIN qa_warehouse qaw ON qaw.qa_id=q.id
                      LEFT JOIN qastatus qs ON qs.id=q.status
                      WHERE q.id != '' $cmbstatus_str $cmbcustomer";

        
        $strwithoutsearchquery=$basequery." GROUP BY ts.toid";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY ts.toid";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "ts.id";
        }
        

         $empQuery=$basequery.$searchQuery." GROUP BY ts.toid order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

// 		echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['sts'].'</kbd>';
            $seturl="qa_transfer_submit.php?res=4&msg='Update Data'&type=4&id=".$row['id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
            $btns = array(
				array('view','to_view.php','class="show-invoice btn btn-info btn-xs"  title="View Transfer Order"	data-code="'.$row['toid'].'"  '),
			);
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"toid"=>$row['toid'],
					
					"tansferdt"=>$row['tansferdt'],
					
					"status"=>$st,
					
					"result" => $result,
        			
        			"action" => getGridBtns($btns).' | <a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }

if($action=="qaissue")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (q.order_id like '%".$searchValue."%' or  cbr.name like '%".$searchValue."%' or  tbr.name like '%".$searchValue."%'  )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT i.name product, cbr.name cbranch, io.ioid, ch.freeqty current_stock, iod.qty transfer_stock,qastatus.name sts, 
                      qastatus.dclass, q.order_id,DATE_FORMAT(io.deliverydt, '%d/%b/%Y') deliverydt,
                      SUM(qaw.ordered_qty) AS total_qty, SUM(qaw.pass_qty) AS total_pass_qty, SUM(qaw.damaged_qty) AS total_damaged_qty, 
                      SUM(qaw.defect_qty) AS total_defect_qty, tbr.name tbranch, tbr.address, q.product_id
                      FROM `qa` q LEFT JOIN qa_warehouse qaw ON q.id=qaw.qa_id LEFT JOIN item i ON i.id=q.product_id 
                      LEFT JOIN issue_order as io ON io.ioid = q.order_id LEFT JOIN issue_order_details iod ON (iod.ioid = io.id AND q.product_id = iod.product) 
                      LEFT JOIN branch cbr ON cbr.id=iod.frombranch LEFT JOIN issue_warehouse tbr ON tbr.id=io.issue_warehouse LEFT JOIN qastatus qastatus ON qastatus.id = q.status 
                      LEFT JOIN chalanstock ch ON (ch.product=q.product_id AND ch.storerome = iod.frombranch)
                      WHERE q.type = 5 $cmbstatus_str $cmbcustomer";

        
        $strwithoutsearchquery=$basequery." GROUP BY q.order_id, q.product_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY q.order_id, q.product_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "q.id";
        }
        

         $empQuery=$basequery.$searchQuery." GROUP BY q.order_id, q.product_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['sts'].'</kbd>';
            $seturl="qa_issue_submit.php?res=4&msg='Update Data'&type=5&id=".$row['order_id']."&product=".$row["product_id"]."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"product"=>$row['product'],
					
					"ioid"=>$row['ioid'],
					
					"address"=>$row['address'],
					
					"deliverydt"=>$row['deliverydt'],
					
					"cbranch"=>$row['cbranch'],

            		"tbranch"=>$row['tbranch'],

            		"current_stock"=>$row['current_stock'],
					
					"transfer_stock"=>$row['transfer_stock'],
					
					"status"=>$st,
					
					"result" => $result,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }
if($action=="returnorder")

    {
        if($cmbcustomer != ''){
        	$cmbcustomer = "and org.id = ".$cmbcustomer;
        }else{$cmbcustomer = "";}

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (qa.order_id like '%".$searchValue."%' or  inv.invoiceno like '%".$searchValue."%' or  org.name like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,
        $basequery = "SELECT qa.order_id roid,DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate,org.name customer,ro.order_id,
                    COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty, 
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id LEFT JOIN return_order ro ON ro.ro_id = qa.order_id LEFT JOIN quotation q ON q.socode = ro.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status
                    LEFT JOIN organization org ON org.id=q.organization
                    WHERE qa.type=6 $cmbstatus_str $cmbcustomer";

        
        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "qa.id";
        }
        if($columnName == "orderdate"){
            $columnName = "so.orderdate";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
            $seturl="qa_return_submit.php?res=4&msg='Update Data'&type=1&id=".$row['roid']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"roid"=>$row['roid'],
					
					"order_id"=>$row['order_id'],

            		"orderdate"=>$row['orderdate'],

            		"customer"=>$row['customer'],
					
					"noitems"=>$row['noitems'],
					
					"total_qty"=>$row['total_qty'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

            	);

        } 

    }

//All Report

if($action=="damaged_rpt")

    {
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and d.makedt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.name like '%".$searchValue."%' or  i.barcode like '%".$searchValue."%' or  q.order_id like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,
        $basequery = "SELECT i.name pnm,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit ,qw.ordered_qty total_qty,(case when d.st=0 then 'Decline' when d.st=2 then 'Approved' else  'Pending' end) st,
                      GROUP_CONCAT(qi.image_url) damge_image,h.hrName approved_by, (case when qw.qa_type=1 then 'Sold' when qw.qa_type=2 then 'Purchase' when qw.qa_type=3 then 'Return' when qw.qa_type=4 then 'Transfer' 
                      when qw.qa_type=5 then 'Issue' when qw.qa_type=6 then 'Return' else 'na' end ) qatype,q.order_id,qw.damaged_qty damaged_qty, qw.id qaw_id
                      FROM approval_damaged d left join qa_warehouse qw on d.qaw_id=qw.id left join qa_images qi on qi.qaw_id=qw.id and qi.type='damaged' left join qa q on qw.qa_id=q.id
                      left join item i on q.product_id=i.id left join hr h on d.approved_by=h.id
                      Where 1=1 $dateqry
                     ";

        
        $strwithoutsearchquery=$basequery." group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,q.order_id,qw.defect_qty,h.hrName";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,q.order_id,qw.defect_qty,h.hrName";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "id"){
            $columnName = "d.id";
        }

         $empQuery=$basequery.$searchQuery." group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,q.order_id,qw.defect_qty,h.hrName ORDER BY ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;
            if($row["st"] == "Decline"){
                $status= '<kbd class="pending">'.$row['st'].'</kbd>';
            }
            if($row["st"] == "Approved"){
                $status= '<kbd class="paid">'.$row['st'].'</kbd>';
            }
            if($row["st"] == "Pending"){
                $status= '<kbd class="part-paid">'.$row['st'].'</kbd>';
            }
            $seturl="qa_return_submit.php?res=4&msg='Update Data'&type=1&id=".$row['roid']."&mod=15";
            
            $photo="assets/images/products/300_300/".$row["image"];
            
            $qawId  = $row['qaw_id'];
            //$debug = 1;
            //get all defect upladed images;
            	$inputDefImgData = array(

            	'TableName' => 'qa_images',
            	'OrderBy' => 'id',
            	'ASDSOrder' => 'DESC',
            	'id' => '',
            	'type' => 'damaged',
            	'image_url' => '',
            	'qaw_id' => $qawId
            	);
            	
            	listData($inputDefImgData,$imgDefData);
            	
            	if(count($imgDefData)>0){
                	$picturelist[$qawId] ='<div class="ajax-img-up"><ul class="d-flex defect-img">';
                    foreach($imgDefData as $lidata){
                        $picturelist[$qawId] .= '<li class="picbox"><a class="picture-preview"  href="../images/upload/qa_images/original/'.$lidata['image_url'].'""><img src="../images/upload/qa_images/thumb/'.$lidata['image_url'].'"></a></li>';
                    }
                    $picturelist[$qawId] .='</ul></div>';
            	}
            	$inputDefImgData = "";
            	$imgDefData = "";
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"image"=>'<img src='.$photo.' width="50">',
					
					"pnm"=>$row['pnm'],

            		"barcode"=>$row['barcode'],
            		
            		"colortext"=>$row['colortext'],

            		"length"=>$row['length'],
					
					"lengthunit"=>$row['lengthunit'],
					
					"width"=>$row['width'],
					
					"widthunit"=>$row['widthunit'],

            		"height"=>$row['height'],

            		"heightunit"=>$row['heightunit'],
					
					"total_qty"=>$row['total_qty'],
					
					"damaged_qty"=>$row['damaged_qty'],
					
					"damaged_image"=>$picturelist[$qawId],

            		"approved_by"=>$row['approved_by'],

            		"qatype"=>$row['qatype'],
					
					"order_id"=>$row['order_id'],
					
					"status"=>$status,

            	);

        } 

    }

if($action=="defect_rpt")

    {
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and d.makedt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.name like '%".$searchValue."%' or  i.barcode like '%".$searchValue."%' or  q.order_id like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,
        $basequery = "SELECT i.name pnm,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit ,qw.ordered_qty total_qty,(case when d.st=0 then 'Decline' when d.st=2 then 'Approved' else  'Pending' end) st,
                      h.hrName approved_by, (case when qw.qa_type=1 then 'Sold' when qw.qa_type=2 then 'Purchase' when qw.qa_type=3 then 'Return' when qw.qa_type=4 then 'Transfer' 
                      when qw.qa_type=5 then 'Issue' when qw.qa_type=6 then 'Return' else 'na' end ) qatype,q.order_id,qw.defect_qty, qw.id qaw_id
                      FROM approval_defect d left join qa_warehouse qw on d.qaw_id=qw.id left join qa q on qw.qa_id=q.id
                      left join item i on q.product_id=i.id left join hr h on d.approved_by=h.id
                      WHERE 1=1 $dateqry
                     ";

        
        $strwithoutsearchquery=$basequery." group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,q.order_id,qw.defect_qty,h.hrName";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,q.order_id,qw.defect_qty,h.hrName";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "id"){
            $columnName = "d.id";
        }

         $empQuery=$basequery.$searchQuery." group by i.name,i.barcode,i.image,i.colortext,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,d.qty,d.st,d.approved_by,q.order_id,qw.defect_qty,h.hrName ORDER BY ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;
            if($row["st"] == "Decline"){
                $status= '<kbd class="pending">'.$row['st'].'</kbd>';
            }
            if($row["st"] == "Approved"){
                $status= '<kbd class="paid">'.$row['st'].'</kbd>';
            }
            if($row["st"] == "Pending"){
                $status= '<kbd class="part-paid">'.$row['st'].'</kbd>';
            }
            $seturl="qa_return_submit.php?res=4&msg='Update Data'&type=1&id=".$row['roid']."&mod=15";
            
            $photo="assets/images/products/300_300/".$row["image"];
            
            $qawId  = $row['qaw_id'];
            //$debug = 1;
            //get all defect upladed images;
            	$inputDefImgData = array(

            	'TableName' => 'qa_images',
            	'OrderBy' => 'id',
            	'ASDSOrder' => 'DESC',
            	'id' => '',
            	'type' => 'defect',
            	'image_url' => '',
            	'qaw_id' => $qawId
            	);
            	
            	listData($inputDefImgData,$imgDefData);
            	
            	if(count($imgDefData)>0){
                	$picturelist[$qawId] ='<div class="ajax-img-up"><ul class="d-flex defect-img">';
                    foreach($imgDefData as $lidata){
                        $picturelist[$qawId] .= '<li class="picbox"><a class="picture-preview"  href="../images/upload/qa_images/original/'.$lidata['image_url'].'""><img src="../images/upload/qa_images/thumb/'.$lidata['image_url'].'"></a></li>';
                    }
                    $picturelist[$qawId] .='</ul></div>';
            	}
            	$inputDefImgData = "";
            	$imgDefData = "";
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"image"=>'<img src='.$photo.' width="50">',
					
					"pnm"=>$row['pnm'],

            		"barcode"=>$row['barcode'],
            		
            		"colortext"=>$row['colortext'],

            		"length"=>$row['length'],
					
					"lengthunit"=>$row['lengthunit'],
					
					"width"=>$row['width'],
					
					"widthunit"=>$row['widthunit'],

            		"height"=>$row['height'],

            		"heightunit"=>$row['heightunit'],
					
					"total_qty"=>$row['total_qty'],
					
					"defect_qty"=>$row['defect_qty'],
					
					"defect_image"=>$picturelist[$qawId],

            		"approved_by"=>$row['approved_by'],

            		"qatype"=>$row['qatype'],
					
					"order_id"=>$row['order_id'],
					
					"status"=>$status,

            	);

        } 

    }

if($action=="defect_rpt_inv")

    {
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (t.name like '%".$searchValue."%' or  p.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' or r.name like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,
        $basequery = "SELECT s.id,t.name tn,p.name pn,s.freeqty defectQty,p.rate price_incl_vat,r.name str,p.barcode barcode,p.image
                     FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                     where s.storerome=10 and  s.freeqty<>0 and ( t.id = ".$cat." or ".$cat." = 0 )
                     ";

        
        $strwithoutsearchquery=$basequery;

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery;

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$basequery.$searchQuery." Order By ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			
            $i++;
            
            $photo="assets/images/products/300_300/".$row["image"];

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"tn"=>$row["tn"],
					
					"photo"=>'<img src='.$photo.' width="50" height="50">',
					
					"pn"=>$row['pn'],

            		"barcode"=>$row['barcode'],
            		
            		"defectQty"=>$row['defectQty'],

            		"price_incl_vat"=>$row['price_incl_vat'],
					
					"str"=>$row['str'],
            	);

        } 

    }
    
if($action=="qagift")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (qa.order_id like '%".$searchValue."%' or  inv.invoiceno like '%".$searchValue."%' or  org.name like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        /*$basequery="SELECT qa.order_id, inv.invoiceno, so.orderdate, qa.delivery_date, qa.quantity, qastatus.name status, qastatus.dclass FROM `qa` qa 
                    LEFT JOIN invoice inv ON qa.order_id = inv.soid LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status WHERE  1=1 $cmbstatus_str";*/

        $basequery = "SELECT qa.order_id,DATE_FORMAT(so.orderdate, '%d/%b/%Y') orderdate, DATE_FORMAT(qa.delivery_date, '%d/%b/%Y %H:%i:%s') delivery_date,org.name customer,
                    MIN(DATE_FORMAT( qw.expted_deliverey_date,'%d/%b/%Y')) AS expted_deliverey_date,
                    COUNT(DISTINCT qa.id) AS noitems, qastatus.name status, qastatus.dclass,inv.invoiceno,
                    SUM(qa_warehouse.ordered_qty) AS total_qty,
                    SUM(qa_warehouse.pass_qty) AS total_pass_qty, 
                    SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty 
                    FROM qa 
                    LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id LEFT JOIN soitem so ON so.socode = qa.order_id 
                    LEFT JOIN qastatus qastatus ON qastatus.id = qa.status LEFT JOIN invoice inv ON qa.order_id = inv.soid
                    LEFT JOIN quotation_warehouse qw ON qw.socode=qa.order_id LEFT JOIN organization org ON org.id=so.organization
                    WHERE qa.type=7 $cmbstatus_str $cmbcustomer";

        
        $strwithoutsearchquery=$basequery." GROUP BY qa.order_id";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery. " GROUP BY qa.order_id";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "sl"){
            $columnName = "qa.id";
        }
        if($columnName == "orderdate"){
            $columnName = "so.orderdate";
        }

         $empQuery=$basequery.$searchQuery." GROUP BY qa.order_id order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st= '<kbd class="'.$row['dclass'].'">'.$row['status'].'</kbd>';
            $seturl="qa_gift_submit.php?res=4&msg='Update Data'&type=7&id=".$row['order_id']."&mod=15";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Repairable | '.$damagedQty.' Damaged';
            
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"order_id"=>$row['order_id'],
					
					"invoiceno"=>$row['invoiceno'],
					
					"customer"=>$row['customer'],

            		"expted_deliverey_date"=>$row['expted_deliverey_date'],

            		"delivery_date"=>$row['delivery_date'],
					
					"quantity"=>$row['noitems'],
					
					"totqty"=>$row['total_qty'],
					
					"result" => $result,

        			"status"=>$st,
        			
        			"action" => '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>'
				
				

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
			"request" => $_REQUEST,			   

        );     

        $cmbstatus_str = "";

        //echo $data;die;

        echo json_encode($response);



?>