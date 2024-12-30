<?php


//ini_set("display_errors",1);
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
require "../common/user_btn_access.php";


session_start();


//print_r($_REQUEST);

$con = $conn;
$empid = $_SESSION["empid"];


## Read value




$draw = $_POST['draw']; 

$row = $_POST['start'];

$rowperpage = $_POST['length']; // Rows display per page

$columnIndex = $_POST['order'][0]['column']; // Column index

$columnName = $_POST['columns'][$columnIndex]['data']; // Column name

$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

$searchValue = $_POST['search']['value']; // Search value


$action= $_GET['action'];

$orderdate = explode("/",$_GET['orderdate']);
$y = $orderdate[2];
$m = $orderdate[1];
$d = $orderdate[0];
$orderdate = $y."-".$m."-".$d;

if($_GET['orderdate']){
	//$orderdate_str = " AND  order_date = DATE_FORMAT('".$orderdate."','%m/%d/%Y')";
	//$orderdate_str = " AND  DATE_FORMAT(order_date,'%m/%d/%Y') = '".$orderdate."'";
	$orderdate_str = "and o.`orderdate` = '".$orderdate."'";
}
  


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
	$cmbstatus_str = " and doi.resourceplan = ".$cmbstatus;
}else{$cmbstatus_str = "";}

$total = array();
$pqry=" ";


extract($_REQUEST);
$dt_range_str = ($dt_f && $dt_t)?" and lastdeliverydt BETWEEN '".$dt_f."' AND '".$dt_t."' AND o.orderstatus=5":"";

if($action=="pendingdelivery"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( doi.do_id like '%".$searchValue."%' or doi.order_id like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT 
                                doi.do_id, doi.type,
                                date_format(doi.do_date,'%d/%b/%Y') do_date, 
                                doi.start_time,
                                doi.start_time,
                                doi.end_time,
                                doi.order_id,
                                ds.name stnm, 
                                ds.dclass,
                                resourceplan,
                                concat(emp.firstname, ' ', emp.lastname) supervisor, 
                                GROUP_CONCAT(DISTINCT lt.name) team,
                                SUM(dod.qty) AS totqty,
                                SUM(dod.do_qty) AS totdoqty
                                FROM `delivery_order` doi 
                                LEFT JOIN delivery_order_detail dod ON dod.do_id=doi.id 
                                LEFT JOIN deliverystatus ds ON ds.id=doi.resourceplan 
                                LEFT JOIN resourceplan r ON doi.do_id=r.doid 
                                LEFT JOIN employee emp ON emp.id=r.supervisor
                                LEFT JOIN assign_logistic_team alt ON alt.resourceid=r.id 
                                LEFT JOIN logistic_team lt ON lt.id=alt.logisticteamid
                                WHERE 1 = 1 $cmbstatus_str";
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY doi.do_id");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "r.id";
            // $columnName = "doi.do_date";
        }
        if($columnName == 'do_date'){
            $columnName = "doi.do_date";
        }
        
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       $i++;
                $seturl="/scm_resource_plan.php?mod=16&do=".$row['do_id'];
                $rp = '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
                $deliSt = '-';
                $resourceplan = $row["resourceplan"];
                if($row["type"] == 0){
                    $view = '<a class="btn btn-info btn-xs" disabled> Canceled DO</a>';
                }
                else if($resourceplan == 1){
                    $view = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">Resource Plan </a>';
                    
                }else if ($resourceplan == 2){
                    $iseditable = "";
                    //Delivery Status
        			$qryDeli = "SELECT rp.doid, rp.delivery_start, rp.delivery_end, CONCAT(emp.firstname, ' ', emp.lastname) AS empnm,
                                            rp.acknowledgement, GROUP_CONCAT(DISTINCT lt.name) AS team, ds.name AS stnm, ds.dclass,
                                            agg.dod_totdoqty, agg.dod_totpendingqty, agg.dod_tottransqty, agg.dod_totdeliqty, agg.dod_totretqty
                                        FROM
                                            `resourceplan` rp
                                            LEFT JOIN `delivery_order` doi ON rp.doid = doi.do_id
                                            LEFT JOIN employee emp ON rp.supervisor = emp.id
                                            LEFT JOIN assign_logistic_team alt ON alt.resourceid = rp.id
                                            LEFT JOIN logistic_team lt ON lt.id = alt.logisticteamid
                                            LEFT JOIN deliverystatus ds ON ds.id = doi.resourceplan
                                            LEFT JOIN (
                                                SELECT
                                                    do_id,
                                                    SUM(do_qty) AS dod_totdoqty,
                                                    SUM(pending_qty) AS dod_totpendingqty,
                                                    SUM(intransit_qty) AS dod_tottransqty,
                                                    SUM(delivered_qty) AS dod_totdeliqty,
                                                    SUM(due_return_qty + returned_qty) AS dod_totretqty
                                                FROM
                                                    delivery_order_detail
                                                GROUP BY
                                                    do_id
                                            ) AS agg ON doi.id = agg.do_id
                                        WHERE doi.order_id= '".$row['order_id']."' GROUP BY alt.resourceid";
                     //test
                    $resultDeli = $conn->query($qryDeli);
                    if ($resultDeli->num_rows > 0)
                    {
                        while($rowDeli = $resultDeli->fetch_assoc()) 
                        {
                            if($rowDeli["dod_totdeliqty"] == $row['totdoqty']){
                                $rp = '<kbd class="orstatus_8">Delivered</kbd>';
                                $iseditable = "disabled";
                            }else if($rowDeli["dod_totdeliqty"] > 0){
                                $rp = '<kbd class="pending">Partial Delivered</kbd>';
                                $iseditable = "disabled";
                            }
                            $deliSt = $rowDeli["dod_totpendingqty"]." Pendings | ".$rowDeli["dod_tottransqty"]." In transit | ".$rowDeli["dod_totdeliqty"]." Delivered | ".$rowDeli["dod_totretqty"]." Returned";
                        }
                    }else{
                        $deliSt = "0 Pendings | 0 In transit | 0 Delivered | 0 Returned";
                    }
                    
                    $view = '<a class="btn btn-info btn-xs '.$iseditable.'"  href="'.$seturl.'&type=2"> Edit </a>';
                }
            
                $supervisor = $row["supervisor"];
                if($supervisor == '') $supervisor = '-';
                
                $btns = array(
        			array('view','delivery_order_scm_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
        		);
               
            $data[] = array(
                    "sl"=> $i,
                    "do_id"=> $row['do_id'],
                    "order_id"=> $row['order_id'],
                    "do_qty"=>$row['totdoqty'],
                    "do_date"=>$row['do_date'],
					"start_time" =>$row['start_time'],
					"end_time" => $row['end_time'],
            		"supervisor"=>$supervisor,
            		"delivery_team"=>$row["team"],   
            		"status"=>$deliSt,
					"resource_plan"=>$rp,
					"view"=> getGridBtns($btns)." | ".$view                                   
            	);
        } 
    }
    
if($action=="deliverystatus"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( rp.doid like '%".$searchValue."%' or inv.invoiceno like '%".$searchValue."%' or org.name like '%".$searchValue."%' )";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT rp.doid, DATE_FORMAT( rp.delivery_start,'%d/%b/%Y %H %i %s') delivery_start, DATE_FORMAT( rp.delivery_end,'%d/%b/%Y %H %i %s') delivery_end, CONCAT(emp.firstname, ' ', emp.lastname) AS empnm,
                                    rp.acknowledgement, GROUP_CONCAT(DISTINCT lt.name) AS team, ds.name AS stnm, ds.dclass,inv.invoiceno, org.name customer,doi.do_id,
                                    agg.dod_totdoqty, agg.dod_totpendingqty, agg.dod_tottransqty, agg.dod_totdeliqty, agg.dod_totretqty, doi.type
                                FROM
                                    `resourceplan` rp
                                    LEFT JOIN `delivery_order` doi ON rp.doid = doi.do_id
                                    LEFT JOIN `qa` qa ON qa.order_id = doi.order_id
                                    LEFT JOIN employee emp ON rp.supervisor = emp.id
                                    LEFT JOIN assign_logistic_team alt ON alt.resourceid = rp.id
                                    LEFT JOIN logistic_team lt ON lt.id = alt.logisticteamid
                                    LEFT JOIN deliverystatus ds ON ds.id = doi.resourceplan
                                    LEFT JOIN quotation q ON q.socode=doi.order_id
                                    LEFT JOIN invoice inv ON inv.soid=q.socode
                                    LEFT JOIN organization org ON org.id=q.organization
                                    LEFT JOIN (
                                        SELECT
                                            do_id,
                                            SUM(do_qty) AS dod_totdoqty,
                                            SUM(pending_qty) AS dod_totpendingqty,
                                            SUM(intransit_qty) AS dod_tottransqty,
                                            SUM(delivered_qty) AS dod_totdeliqty,
                                            SUM(due_return_qty + returned_qty) AS dod_totretqty
                                        FROM
                                            delivery_order_detail
                                        GROUP BY
                                            do_id
                                    ) AS agg ON doi.id = agg.do_id
                                WHERE
                                    qa.type != 5 and doi.delivery_type = 1 and rp.supervisor = ".$empid;
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY alt.resourceid");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "rp.id";
        }
        if($columnName == 'delivery_date'){
            $columnName = "rp.delivery_start";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       $i++;
                $seturl="scm_delivery_status_detail.php?mod=16&do=".$row['doid'];
                
                $view = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>';
                
                $acknowledgement = $row["acknowledgement"];
                if($acknowledgement == 0){
                    $ackst = "No";
                }else{
                    $ackst = "Yes";
                }
                if($row["type"] == 0){
                    //generate button array
        			$btns = array(
        				array('view','delivery_order_scm_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
        			);
                    $rp = '<kbd class="pending">Canceled DO</kbd>';
                    
                }else{
                    //generate button array
        			$btns = array(
        				array('view','delivery_order_scm_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
        				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
        			);
                    $rp = '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
                }
                
                $st = $row["dod_totpendingqty"]." Pendings | ".$row["dod_tottransqty"]." In transit | ".$row["dod_totdeliqty"]." Delivered | ".$row["dod_totretqty"]." Returned";
                $invViewLink2 = '<a href="resourceplan_view.php?doid='.$row['doid'].'&mod=3" class="show-returnchecklist btn btn-info btn-xs" title="Resource Plan" target="_blank"><i class="fa fa-list-ul"></i></a>';
                
                $data[] = array(
                    "sl"=> $i,
                    "doid"=> $row['doid'],
                    "invoiceno"=> $row['invoiceno'],
                    "customer"=> $row['customer'],
                    "do_qty"=>$row['dod_totdoqty'],
                    "delivery_date"=>$row['delivery_start'],
					"delivery_start" =>$row['delivery_start'],
					"delivery_end" => $row['delivery_end'],
            		"supervisor"=>$row['empnm'],
            		"acknowledgement"=>$ackst,                    
					"delivery_team"=>$row["team"],
					"status"=>$st,
					"resource_plan"=>$rp,
					
					"view"=> $invViewLink2 ." | ". getGridBtns($btns)                                   
            	);
        } 
    }
   
if($action=="issuedeliverystatus"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( rp.doid like '%".$searchValue."%' or inv.invoiceno like '%".$searchValue."%' or org.name like '%".$searchValue."%' )";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT rp.doid, DATE_FORMAT( rp.delivery_start,'%d/%b/%Y %H %i %s') delivery_start, DATE_FORMAT( rp.delivery_end,'%d/%b/%Y %H %i %s') delivery_end, CONCAT(emp.firstname, ' ', emp.lastname) AS empnm,
                                    rp.acknowledgement, GROUP_CONCAT(DISTINCT lt.name) AS team, ds.name AS stnm, ds.dclass,doi.do_id,
                                    agg.dod_totdoqty, agg.dod_totpendingqty, agg.dod_tottransqty, agg.dod_totdeliqty, agg.dod_totretqty, 
                                    iw.name warehouse, iw.address
                                FROM
                                    `resourceplan` rp
                                    LEFT JOIN `delivery_order` doi ON rp.doid = doi.do_id
                                    LEFT JOIN `qa` qa ON qa.order_id = doi.order_id
                                    LEFT JOIN employee emp ON rp.supervisor = emp.id
                                    LEFT JOIN assign_logistic_team alt ON alt.resourceid = rp.id
                                    LEFT JOIN logistic_team lt ON lt.id = alt.logisticteamid
                                    LEFT JOIN deliverystatus ds ON ds.id = doi.resourceplan
                                    LEFT JOIN issue_order ioo ON ioo.ioid=doi.order_id
                                    LEFT JOIN issue_warehouse iw ON iw.id=ioo.issue_warehouse
                                    LEFT JOIN (
                                        SELECT
                                            do_id,
                                            SUM(do_qty) AS dod_totdoqty,
                                            SUM(pending_qty) AS dod_totpendingqty,
                                            SUM(intransit_qty) AS dod_tottransqty,
                                            SUM(delivered_qty) AS dod_totdeliqty,
                                            SUM(due_return_qty + returned_qty) AS dod_totretqty
                                        FROM
                                            delivery_order_detail
                                        GROUP BY
                                            do_id
                                    ) AS agg ON doi.id = agg.do_id
                                WHERE
                                    qa.type = 5 and rp.supervisor = ".$empid;
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY alt.resourceid");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "rp.id";
        }
        if($columnName == 'delivery_date'){
            $columnName = "rp.delivery_start";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       $i++;
                $seturl="scm_issue_delivery_status_detail.php?mod=16&do=".$row['doid'];
                
                $view = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>';
                
                //generate button array
    			$btns = array(
    				array('view','issue_delivery_order_scm_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
    				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
    				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
                
                $acknowledgement = $row["acknowledgement"];
                if($acknowledgement == 0){
                    $ackst = "No";
                }else{
                    $ackst = "Yes";
                }
                $rp = '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
                
                $st = $row["dod_totpendingqty"]." Pendings | ".$row["dod_tottransqty"]." In transit | ".$row["dod_totdeliqty"]." Delivered | ".$row["dod_totretqty"]." Returned";
                
                $data[] = array(
                    "sl"=> $i,
                    "doid"=> $row['doid'],
                    "warehouse"=> $row['warehouse'],
                    "address"=> $row['address'],
                    "do_qty"=>$row['dod_totdoqty'],
                    "delivery_date"=>$row['delivery_start'],
					"delivery_start" =>$row['delivery_start'],
					"delivery_end" => $row['delivery_end'],
            		"supervisor"=>$row['empnm'],
            		"acknowledgement"=>$ackst,                    
					"delivery_team"=>$row["team"],
					"status"=>$st,
					"resource_plan"=>$rp,
					
					"view"=> getGridBtns($btns)                                    
            	);
        } 
    }
    
if($action=="codeliverystatus"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( rp.doid like '%".$searchValue."%' or inv.invoiceno like '%".$searchValue."%' or org.name like '%".$searchValue."%' )";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT rp.doid, DATE_FORMAT( rp.delivery_start,'%d/%b/%Y %H %i %s') delivery_start, DATE_FORMAT( rp.delivery_end,'%d/%b/%Y %H %i %s') delivery_end, CONCAT(emp.firstname, ' ', emp.lastname) AS empnm,
                                    rp.acknowledgement, GROUP_CONCAT(DISTINCT lt.name) AS team, ds.name AS stnm, ds.dclass,doi.do_id,c.co_id,
                                    agg.dod_totdoqty, agg.dod_totpendingqty, agg.dod_tottransqty, agg.dod_totdeliqty, agg.dod_totretqty, c.order_id,org.name orgnm, DATE_FORMAT(q.orderdate,'%e/%c/%Y') orderdate
                                FROM
                                    `resourceplan` rp
                                    LEFT JOIN `delivery_order` doi ON rp.doid = doi.do_id
                                    LEFT JOIN `qa` qa ON qa.order_id = doi.order_id
                                    LEFT JOIN employee emp ON rp.supervisor = emp.id
                                    LEFT JOIN assign_logistic_team alt ON alt.resourceid = rp.id
                                    LEFT JOIN logistic_team lt ON lt.id = alt.logisticteamid
                                    LEFT JOIN deliverystatus ds ON ds.id = doi.resourceplan
                                    LEFT JOIN co c ON c.co_id=doi.order_id
                                    LEFT JOIN quotation q ON c.order_id=q.socode
                                    LEFT JOIN organization org ON org.id=q.organization
                                    LEFT JOIN (
                                        SELECT
                                            do_id,
                                            SUM(do_qty) AS dod_totdoqty,
                                            SUM(pending_qty) AS dod_totpendingqty,
                                            SUM(intransit_qty) AS dod_tottransqty,
                                            SUM(delivered_qty) AS dod_totdeliqty,
                                            SUM(due_return_qty + returned_qty) AS dod_totretqty
                                        FROM
                                            delivery_order_detail
                                        GROUP BY
                                            do_id
                                    ) AS agg ON doi.id = agg.do_id
                                WHERE
                                    doi.delivery_type = 2 and rp.supervisor = ".$empid;
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY alt.resourceid");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "rp.id";
        }
        if($columnName == 'delivery_date'){
            $columnName = "rp.delivery_start";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       $i++;
                $seturl="scm_co_delivery_status_detail.php?mod=16&do=".$row['doid'];
                
                $view = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>';
                
                //generate button array
    			$btns = array(
    				array('view','co_view_scm.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-coid="'.$row['co_id'].'"  '),
    				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
    				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
                
                $acknowledgement = $row["acknowledgement"];
                if($acknowledgement == 0){
                    $ackst = "No";
                }else{
                    $ackst = "Yes";
                }
                $rp = '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
                
                $st = $row["dod_totpendingqty"]." Pendings | ".$row["dod_tottransqty"]." In transit | ".$row["dod_totdeliqty"]." Delivered | ".$row["dod_totretqty"]." Returned";
                
                $data[] = array(
                    "sl"=> $i,
                    "doid"=> $row['doid'],
                    "co_id"=> $row['co_id'],
                    "order_id"=> $row['order_id'],
                    "orgnm"=> $row['orgnm'],
                    "orderdate"=> $row['orderdate'],
                    "do_qty"=>$row['dod_totdoqty'],
                    "delivery_date"=>$row['delivery_start'],
					"delivery_start" =>$row['delivery_start'],
					"delivery_end" => $row['delivery_end'],
            		"supervisor"=>$row['empnm'],
            		"acknowledgement"=>$ackst,                    
					"delivery_team"=>$row["team"],
					"status"=>$st,
					"resource_plan"=>$rp,
					
					"view"=> getGridBtns($btns)                                    
            	);
        } 
    }

if($action=="transferdeliverystatus"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( rp.doid like '%".$searchValue."%' or inv.invoiceno like '%".$searchValue."%' or org.name like '%".$searchValue."%' )";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT rp.doid, DATE_FORMAT( rp.delivery_start,'%d/%b/%Y %H %i %s') delivery_start, DATE_FORMAT( rp.delivery_end,'%d/%b/%Y %H %i %s') delivery_end, CONCAT(emp.firstname, ' ', emp.lastname) AS empnm,
                                    rp.acknowledgement, GROUP_CONCAT(DISTINCT lt.name) AS team, ds.name AS stnm, ds.dclass,doi.do_id,
                                    agg.dod_totdoqty, agg.dod_totpendingqty, agg.dod_tottransqty, agg.dod_totdeliqty, agg.dod_totretqty,ts.toid
                                FROM
                                    `resourceplan` rp
                                    LEFT JOIN `delivery_order` doi ON rp.doid = doi.do_id
                                    LEFT JOIN transfer_stock ts ON ts.toid=doi.order_id
                                    LEFT JOIN `qa` qa ON qa.order_id = ts.id
                                    LEFT JOIN employee emp ON rp.supervisor = emp.id
                                    LEFT JOIN assign_logistic_team alt ON alt.resourceid = rp.id
                                    LEFT JOIN logistic_team lt ON lt.id = alt.logisticteamid
                                    LEFT JOIN deliverystatus ds ON ds.id = doi.resourceplan
                                    LEFT JOIN (
                                        SELECT
                                            do_id,
                                            SUM(do_qty) AS dod_totdoqty,
                                            SUM(pending_qty) AS dod_totpendingqty,
                                            SUM(intransit_qty) AS dod_tottransqty,
                                            SUM(delivered_qty) AS dod_totdeliqty,
                                            SUM(due_return_qty + returned_qty) AS dod_totretqty
                                        FROM
                                            delivery_order_detail
                                        GROUP BY
                                            do_id
                                    ) AS agg ON doi.id = agg.do_id
                                WHERE
                                    qa.type = 4 and rp.supervisor = ".$empid;
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY alt.resourceid");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "rp.id";
        }
        if($columnName == 'delivery_date'){
            $columnName = "rp.delivery_start";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        // echo $empQuery;die;
        
        $empRecords = mysqli_query($con, $empQuery); 
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       $i++;
                $seturl="scm_transfer_delivery_status_detail.php?mod=16&do=".$row['doid'];
                
                $view = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>';
                
                //generate button array
    			$btns = array(
    				array('view','transfer_delivery_order_scm_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
    				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
    				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
                
                $acknowledgement = $row["acknowledgement"];
                if($acknowledgement == 0){
                    $ackst = "No";
                }else{
                    $ackst = "Yes";
                }
                $rp = '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
                
                $st = $row["dod_totpendingqty"]." Pendings | ".$row["dod_tottransqty"]." In transit | ".$row["dod_totdeliqty"]." Delivered | ".$row["dod_totretqty"]." Returned";
                
                $data[] = array(
                    "sl"=> $i,
                    "doid"=> $row['doid'],
                    "order_id"=> $row['toid'],
                    "delivery_date"=>$row['delivery_start'],
					"delivery_start" =>$row['delivery_start'],
					"delivery_end" => $row['delivery_end'],
            		"supervisor"=>$row['empnm'],
            		"acknowledgement"=>$ackst,                    
					"delivery_team"=>$row["team"],
					"status"=>$st,
					"resource_plan"=>$rp,
					
					"view"=> getGridBtns($btns)                                    
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
            $seturl="scm_return_submit.php?res=4&msg='Update Data'&type=1&id=".$row['roid']."&mod=16";
            
            $passQty = $row["total_pass_qty"]; if($passQty == null) $passQty = 0;
			$defactQty = $row["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
			$damagedQty = $row["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;

            $result= $passQty.' Passed | '.$defactQty.' Defact | '.$damagedQty.' Damaged';
            
            $viewurl = "return_rdl.php?roid=".$row['roid']."&mod=16";
			$btns = [];
			$btns[] = array('view', $viewurl, 'class="btn btn-info btn-xs show-invoice" title="View Details"'); 
			$btns[] = array('generate', 'deliveryReturn.php?returnid=' . $row['roid'] . '&mod=16', 'class="btn btn-info btn-xs" title="Generate DO"');
			
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
        			
        			"action" => getGridBtns($btns)
				
				

            	);

        } 

    }

if($action=="returndelivery"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( rp.doid like '%".$searchValue."%' or inv.invoiceno like '%".$searchValue."%' or org.name like '%".$searchValue."%' )";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT rp.doid, DATE_FORMAT( rp.delivery_start,'%d/%b/%Y %H %i %s') delivery_start, DATE_FORMAT( rp.delivery_end,'%d/%b/%Y %H %i %s') delivery_end, CONCAT(emp.firstname, ' ', emp.lastname) AS empnm,
                                    rp.acknowledgement, GROUP_CONCAT(DISTINCT lt.name) AS team, ds.name AS stnm, ds.dclass,doi.do_id,r.ro_id,
                                    agg.dod_totdoqty, agg.dod_totpendingqty, agg.dod_tottransqty, agg.dod_totdeliqty, agg.dod_totretqty, r.order_id,org.name orgnm, DATE_FORMAT(q.orderdate,'%e/%c/%Y') orderdate
                                FROM
                                    `resourceplan` rp
                                    LEFT JOIN `delivery_order` doi ON rp.doid = doi.do_id
                                    LEFT JOIN `qa` qa ON qa.order_id = doi.order_id
                                    LEFT JOIN employee emp ON rp.supervisor = emp.id
                                    LEFT JOIN assign_logistic_team alt ON alt.resourceid = rp.id
                                    LEFT JOIN logistic_team lt ON lt.id = alt.logisticteamid
                                    LEFT JOIN deliverystatus ds ON ds.id = doi.resourceplan
                                    LEFT JOIN return_order r ON r.ro_id=doi.order_id
                                    LEFT JOIN quotation q ON r.order_id=q.socode
                                    LEFT JOIN organization org ON org.id=q.organization
                                    LEFT JOIN (
                                        SELECT
                                            do_id,
                                            SUM(do_qty) AS dod_totdoqty,
                                            SUM(pending_qty) AS dod_totpendingqty,
                                            SUM(intransit_qty) AS dod_tottransqty,
                                            SUM(delivered_qty) AS dod_totdeliqty,
                                            SUM(due_return_qty + returned_qty) AS dod_totretqty
                                        FROM
                                            delivery_order_detail
                                        GROUP BY
                                            do_id
                                    ) AS agg ON doi.id = agg.do_id
                                WHERE
                                    doi.delivery_type = 3 and rp.supervisor = ".$empid;
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY alt.resourceid");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "rp.id";
        }
        if($columnName == 'delivery_date'){
            $columnName = "rp.delivery_start";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY alt.resourceid order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       $i++;
                $seturl="scm_ro_delivery_status_detail.php?mod=16&do=".$row['doid'];
                
                $view = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">View Details</a>';
                $setchecklisturl = "scm_return_action.php?res=4&msg='Update Data'&type=1&id=".$row['doid']."&mod=16";
                
                //generate button array
    			$btns = array(
    				array('view','ro_view_scm.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-roid="'.$row['ro_id'].'"  '),
    				array('edit',$setchecklisturl , 'class="btn btn-info btn-xs" title="Action"'),
    				array('generate',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
    				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
                
                $acknowledgement = $row["acknowledgement"];
                if($acknowledgement == 0){
                    $ackst = "No";
                }else{
                    $ackst = "Yes";
                }
                $rp = '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
                if($row["dod_totdeliqty"] == $row['dod_totdoqty']){
                    $rp = '<kbd class="paid">Delivered</kbd>';
                }else if($row["dod_totdeliqty"] > 0){
                    $rp = '<kbd class="due"> Partial Delivered</kbd>';
                }
                
                $st = $row["dod_totpendingqty"]." Pendings | ".$row["dod_tottransqty"]." In transit | ".$row["dod_totdeliqty"]." Delivered";
                
                $data[] = array(
                    "sl"=> $i,
                    "doid"=> $row['doid'],
                    "ro_id"=> $row['ro_id'],
                    "order_id"=> $row['order_id'],
                    "orgnm"=> $row['orgnm'],
                    "orderdate"=> $row['orderdate'],
                    "do_qty"=>$row['dod_totdoqty'],
                    "delivery_date"=>$row['delivery_start'],
					"delivery_start" =>$row['delivery_start'],
					"delivery_end" => $row['delivery_end'],
            		"supervisor"=>$row['empnm'],
            		"acknowledgement"=>$ackst,                    
					"delivery_team"=>$row["team"],
					"status"=>$st,
					"resource_plan"=>$rp,
					
					"view"=> getGridBtns($btns)                                    
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
		$orderdate_str = "";
        //echo $data;die;

        echo json_encode($response);



?>