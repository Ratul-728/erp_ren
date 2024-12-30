<?php
session_start();

//ini_set("display_errors",1);
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
require "../common/user_btn_access.php";



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
	$cmbstatus_str = " orderstatus = ".$cmbstatus;
}else{$cmbstatus_str = "orderstatus IN(3,5,10,11)";}

$total = array();
$pqry=" ";


extract($_REQUEST);
$dt_range_str = ($dt_f && $dt_t)?" and lastdeliverydt BETWEEN '".$dt_f."' AND '".$dt_t."' AND o.orderstatus=5":"";



if($action=="cusorderdelv"){
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        /*
        if($columnName == 'socode')
        {
            $columnName=" s.socode ";
            $columnSortOrder=" desc";
        }
         if($columnName == 'oid')
        {
            $columnName=" o.id ";
            $columnSortOrder=" desc";
        }
        */
        if($searchValue != '')
        {
        	$searchQuery = " and ( org.`name` like '%".$searchValue."%' or o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%' ) ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT o.lastdeliverydt lastdeliverydt, o.makedt makedt,  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o 
	left join orderstatus s on o.orderstatus=s.id
    left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
	
    where     ".$cmbstatus_str." ".$orderdate_str. " ".$dt_range_str;
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="cusorderdelvassign.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=3";
                
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                $strDeliveryDate = ($row['orderstatus'] == 5)?"<span style=\"font-size:11px\"> Date:(".date_format(date_create($row['lastdeliverydt']),"d/m/Y").")</span>":"";
            $data[] = array(
                    "order_id"=> $row['order_id'],//$empQuery,//
                    "name"=>$row['name'],
					"makedt" =>$row['makedt'],
					"lastdeliverydt" => $row['lastdeliverydt'],
            		"phone"=>$row['phone'],
            		"order_date"=>$row['order_date'],
            		//"status"=>$row['ost'],
					"status"=> '<kbd class="orstatus_'.$row['orderstatus'].'">'.$row['ost'].'</kbd> '.$strDeliveryDate,
					"amount"=>number_format($row['amount'],2),                    
					"paymd"=>$row['payment_mood'],
					"payst"=>$row['deladr'],
					"agent"=>$row['agentname'],
					"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Proces Delivery </a>'                                    
            	);
        } 
    }


if($action=="deliveryqa"){
        
	   // if($cmbstatus == 1){
    //     	$cmbstatus_str = " and totqty > totdoqty ";
    //     }
    //     else if($cmbstatus == 2){
    //     	$cmbstatus_str = " and totqty > totdoqty ";
    //     }
    //     else{
    //     	$cmbstatus_str = "";
    //     }
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( doi.do_id like '%".$searchValue."%' or doi.order_id like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT doi.do_id, date_format(doi.do_date,'%d/%b/%Y') do_date, doi.start_time,doi.start_time,doi.end_time,doi.order_id,doi.resourceplan, doi.type,
                                SUM(dod.pending_qty) AS totpqty,
                                SUM(dod.do_qty) AS totdoqty,
                                SUM(dod.delivered_qty) AS totdeliveredqty
                                FROM `delivery_order` doi LEFT JOIN delivery_order_detail dod ON dod.do_id=doi.id LEFT JOIN qa on qa.order_id = doi.order_id 
                                WHERE qa.type != 5";
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY doi.do_id");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "dod.id";
        }
        if($columnName == 'do_date'){
            $columnName = "doi.do_date";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $cancelDO = false;
                $seturl="";
                if($row["type"] == 0){
                    $type =  '<kbd class="pending">Cancel Delivery Order</kbd>';
                }
                else if($row["type"] == 2){
                    $type =  '<kbd class="completed">Full Delivery(Self)</kbd>';
                }
                else if($row["resourceplan"] == 1 || $row["totdeliveredqty"] == 0){
                    $type =  '<kbd class="pending">Pending</kbd>';
                    $cancelDO = true;
                }
                else if($row["totdeliveredqty"] < $row["totdoqty"] && $row["totdeliveredqty"] > 0){
                    $type =  '<kbd class="inprogress">Partial Delivery</kbd>';
                }else{
                    $type = '<kbd class="completed">Full Delivery</kbd>';
                }
                $i++;
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
                                WHERE rp.doid= '".$row['do_id']."' GROUP BY alt.resourceid";
            $resultDeli = $conn->query($qryDeli);
            if ($resultDeli->num_rows > 0)
            {
                while($rowDeli = $resultDeli->fetch_assoc()) 
                {
                    $deliSt = $rowDeli["dod_totpendingqty"]." Pendings | ".$rowDeli["dod_tottransqty"]." In transit | ".$rowDeli["dod_totdeliqty"]." Delivered | ".$rowDeli["dod_totretqty"]." Returned";

                }
            }else{
                $deliSt = "Not assigned yet";
            }
            
            if($row["type"] == 0){
                $deliSt = "Canceled this delivery order";
            }

			//generate button array
			if($cancelDO) {
    			$btns = array(
    				array('view','delivery_order_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
    				array('delete','phpajax/cancelDo.php?doid='.$row['do_id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
			}else{
			    $btns = array(
    				array('view','delivery_order_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
    				//array('delete','','class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
			}
			
			
            $data[] = array(
                    "sl"=> $i,
                    "do_id"=> $row['do_id'],
                    "order_id"=> $row['order_id'],
                    "do_date"=>'<div class="deldatewrap"><span>'.$row['do_date'] .'</span><a class="pop-deliverydate" data-socode="'.$row['do_id'].'" href="change_delivery_date_do.php"><i class="fa fa-edit"></i></a></div>',
					"start_time" =>$row['start_time'],
					"end_time" => $row['end_time'],
            		"type"=>$type,
            		"delist"=>$deliSt,
            		"qty"=>$row['totqty'],                    
					"do_qty"=>$row['totdoqty'],
					"action_buttons"=> getGridBtns($btns),
					//"view"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Proces Delivery </a>'                                    
            	);
        } 
    }

if($action=="deliveryissueqa"){
        
	   // if($cmbstatus == 1){
    //     	$cmbstatus_str = " and totqty > totdoqty ";
    //     }
    //     else if($cmbstatus == 2){
    //     	$cmbstatus_str = " and totqty > totdoqty ";
    //     }
    //     else{
    //     	$cmbstatus_str = "";
    //     }
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( doi.do_id like '%".$searchValue."%' or doi.order_id like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT doi.do_id, date_format(doi.do_date,'%d/%b/%Y') do_date, doi.start_time,doi.start_time,doi.end_time,doi.order_id,
                                SUM(dod.qty) AS totqty,
                                SUM(dod.do_qty) AS totdoqty,
                                SUM(dod.pending_qty) AS totpending_qty
                                FROM `delivery_order` doi LEFT JOIN delivery_order_detail dod ON dod.do_id=doi.id LEFT JOIN qa on qa.order_id = doi.order_id 
                                WHERE qa.type = 5";
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY doi.do_id");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "dod.id";
        }
        if($columnName == 'do_date'){
            $columnName = "doi.do_date";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="";
                
                if($row["totpending_qty"] < 1){
                    $type = '<kbd class="completed">Full Delivery</kbd>';
                }else if ($row["totpending_qty"] == $row["totdoqty"] ) {
                    $type =  '<kbd class="inprogress">Not Delivered</kbd>';
                }else{
                    $type =  '<kbd class="inprogress">Partial Delivery</kbd>';
                }
                $i++;
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
                                WHERE rp.doid= '".$row['do_id']."' GROUP BY alt.resourceid";
            $resultDeli = $conn->query($qryDeli); 
            if ($resultDeli->num_rows > 0)
            {
                while($rowDeli = $resultDeli->fetch_assoc()) 
                {
                    $deliSt = $rowDeli["dod_totpendingqty"]." Pendings | ".$rowDeli["dod_tottransqty"]." In transit | ".$rowDeli["dod_totdeliqty"]." Delivered | ".$rowDeli["dod_totretqty"]." Returned";
                }
            }else{
                $deliSt = "Not assigned yet";
            }

			//generate button array
			$btns = array(
				array('view','delivery_issue_order_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
				//array('edit','quotationEntry.php?res=4&msg=Update Data&id='.$row['id'].'&mod=3','class="btn btn-info btn-xs"  title="Edit"	  '),
				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);			
			
			
            $data[] = array(
                    "sl"=> $i,
                    "do_id"=> $row['do_id'],
                    "order_id"=> $row['order_id'],
                    "do_date"=>$row['do_date'],
					"start_time" =>$row['start_time'],
					"end_time" => $row['end_time'],
            		"type"=>$type,
            		"delist"=>$deliSt,
            		"qty"=>$row['totqty'],                    
					"do_qty"=>$row['totdoqty'],
					"action_buttons"=> getGridBtns($btns),
					//"view"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Proces Delivery </a>'                                    
            	);
        } 
    }

if($action=="deliverytransferqa"){
        
	   // if($cmbstatus == 1){
    //     	$cmbstatus_str = " and totqty > totdoqty ";
    //     }
    //     else if($cmbstatus == 2){
    //     	$cmbstatus_str = " and totqty > totdoqty ";
    //     }
    //     else{
    //     	$cmbstatus_str = "";
    //     }
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( doi.do_id like '%".$searchValue."%' or doi.order_id like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT doi.do_id, date_format(doi.do_date,'%d/%b/%Y') do_date, doi.start_time,doi.start_time,doi.end_time,doi.order_id,
                                SUM(dod.qty) AS totqty,
                                SUM(dod.do_qty) AS totdoqty,
                                SUM(dod.pending_qty) AS totpending_qty
                                FROM `delivery_order` doi LEFT JOIN delivery_order_detail dod ON dod.do_id=doi.id LEFT JOIN qa on qa.order_id = doi.order_id 
                                WHERE qa.type = 4";
        
        $sel = mysqli_query($con,$strwithoutsearchquery." GROUP BY doi.do_id");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "dod.id";
        }
        if($columnName == 'do_date'){
            $columnName = "doi.do_date";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY doi.do_id order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="";
                
                if($row["totpending_qty"] < 1){
                    $type = '<kbd class="completed">Full Delivery</kbd>';
                }else if ($row["totpending_qty"] == $row["totdoqty"] ) {
                    $type =  '<kbd class="inprogress">Not Delivered</kbd>';
                }else{
                    $type =  '<kbd class="inprogress">Partial Delivery</kbd>';
                }
                $i++;
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
                                WHERE rp.doid= '".$row['do_id']."' GROUP BY alt.resourceid";
            $resultDeli = $conn->query($qryDeli); 
            if ($resultDeli->num_rows > 0)
            {
                while($rowDeli = $resultDeli->fetch_assoc()) 
                {
                    $deliSt = $rowDeli["dod_totpendingqty"]." Pendings | ".$rowDeli["dod_tottransqty"]." In transit | ".$rowDeli["dod_totdeliqty"]." Delivered | ".$rowDeli["dod_totretqty"]." Returned";
                }
            }else{
                $deliSt = "Not assigned yet";
            }

			//generate button array
			$btns = array(
				array('view','delivery_issue_order_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-st="'.$row['totdoqty'].'"  '),
				//array('edit','quotationEntry.php?res=4&msg=Update Data&id='.$row['id'].'&mod=3','class="btn btn-info btn-xs"  title="Edit"	  '),
				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);			
			
			
            $data[] = array(
                    "sl"=> $i,
                    "do_id"=> $row['do_id'],
                    "order_id"=> $row['order_id'],
                    "do_date"=>$row['do_date'],
					"start_time" =>$row['start_time'],
					"end_time" => $row['end_time'],
            		"type"=>$type,
            		"delist"=>$deliSt,
            		"qty"=>$row['totqty'],                    
					"do_qty"=>$row['totdoqty'],
					"action_buttons"=> getGridBtns($btns),
					//"view"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Proces Delivery </a>'                                    
            	);
        } 
    }


if($action=="return_delivery"){
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( i.name like '%".$searchValue."%' or  i.code like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT d.do_id, d.order_id,inv.invoiceno, b.name warehouse,br.name fromwarehouse, COUNT(DISTINCT dod.item) AS total_items, 
                                SUM(dod.do_qty) AS total_do_qty, SUM(dod.due_return_qty + dod.returned_qty) AS total_return_qty 
                                FROM delivery_order_detail dod LEFT JOIN delivery_order d ON d.id=dod.do_id LEFT JOIN invoice inv ON inv.soid=d.order_id 
                                LEFT JOIN branch b ON b.id=6 LEFT JOIN qa_warehouse qaw ON dod.qa_id = qaw.id LEFT JOIN branch br ON br.id = qaw.warehouse_id 
                                WHERE dod.due_return_qty > 0";
        
        $sel = mysqli_query($con,$strwithoutsearchquery."  GROUP BY dod.do_id");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery."  GROUP BY dod.do_id";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = "dod.id";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY dod.do_id order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="delivery_return.php?mod=16&csid=".$row["do_id"];
                
                $i++;
                
                //generate button array
    			$btns = array(
    				array('view','delivery_return_scm_slip.php','class="show-invoice btn btn-info btn-xs"  title="View DO"	data-doid="'.$row['do_id'].'" data-invid="'.$row['invoiceno'].'" data-st="'.$row['totdoqty'].'"  '),
    				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
    				//array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
                
               
            $data[] = array(
                    "id"=> $i,
                    "do_id"=> $row['do_id'],
                    "order_id"=>$row['order_id'],
                    "invoiceno"=> $row["invoiceno"],
                    "total_items"=> $row['total_items'],
                    "total_do_qty"=>$row['total_do_qty'],
					"total_return_qty" =>$row['total_return_qty'],
					"fromwarehouse" => $row["fromwarehouse"],
					"warehouse" => $row['warehouse'],
					
					"action"=>getGridBtns($btns)                                   
            	);
        } 
    }


if($action=="deliveryco"){
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and ( ca.order_id like '%".$searchValue."%' or org.name like '%".$searchValue."%' or ca.co_id like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ca.co_id, ca.order_id, org.name orgnm, DATE_FORMAT(q.orderdate,'%e/%c/%Y') orderdate, c.st,c.qa_status,ca.approval, ca.id, c.delivery_status
                                FROM co_approval ca LEFT JOIN `co` c ON c.order_id = ca.order_id  LEFT JOIN quotation q ON ca.order_id=q.socode LEFT JOIN organization org ON org.id=q.organization
                                WHERE 1=1";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'sl'){
            $columnName = "ca.id";
        }
        if($columnName == 'orderdate'){
            $columnName = "q.orderdate";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery); //echo $empQuery;die;
        $data = array();
        $i = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="";
                
                //Check for approval
                if($row["approval"] == 1){
                    $type =  '<kbd class="inprogress">Waiting for Approval</kbd>';
                }else if($row["approval"] == 0){
                    $type =  '<kbd class="pending">Declined</kbd>';
                }else if($row["approval"] == 2){
                    //Check for delvery
                    if($row["delivery_status"] == 0){
                        $type =  '<kbd class="inprogress">Waiting for Transfer Stock</kbd>';
                    }
                    else if($row["delivery_status"] == 1){
                        //Check for qc
                        if($row["qa_status"] == 0){
                            $type =  '<kbd class="inprogress">Holding For QC</kbd>';
                        }
                        else if($row["st"] == 1){
                            $type =  '<kbd class="inprogress">Ready for Delivery</kbd>';
                        }
                        else if($row["st"] == 2){
                            $type =  '<kbd class="inprogress">Partial Delivered</kbd>';
                        }
                        else if($row["st"] == 3){
                            $type =  '<kbd class="completed">Delivered</kbd>';
                        }
                    }
                }
                $i++;
                
                $sendto = "";
                if($row["delivery_status"] == 0 && $row["approval"] == 2){
                    $seturl="deliveryTransferCO.php?res=4&msg='Update Data'&coid=".$row['co_id']."&mod=3";
                    $sendto = ' | <a class="btn btn-info btn-xs"  href="'. $seturl.'">Request for Transfer Stock</a>';
                    $btns = array(
    			        array('view','co_view.php?code='.$row["id"],'class="show-invoice btn btn-info btn-xs"  title="View CO"	data-code="'.$row['order_id'].'"  '),
        			);
                }
                else if($row["qa_status"] == 0 && $row["approval"] == 2 && $row["delivery_status"] == 1){
                    $seturl="cotoqa.php?res=4&msg='Update Data'&orderid=".$row['order_id']."&mod=3";
                    $sendto = ' | <a class="btn btn-info btn-xs sendtoqc"  href="'. $seturl.'">Sent to QC</a>';
                    $btns = array(
    			        array('view','co_view.php?code='.$row["id"],'class="show-invoice btn btn-info btn-xs"  title="View CO"	data-code="'.$row['order_id'].'"  '),
        			);
                }else if($row["approval"] == 2 && $row["delivery_status"] == 1 && $row["qa_status"] == 1){
                    $btns = array(
    			        array('view','co_view.php?code='.$row["id"],'class="show-invoice btn btn-info btn-xs"  title="View CO"	data-code="'.$row['order_id'].'"  '),
        				array('generate', 'deliveryCOform.php?cmbempnm=' . $row['order_id'] . '&mod=3', 'class="btn btn-info btn-xs" title="Generate DO"')
        			);
                }
                else{
                    $btns = array(
    			        array('view','co_view.php?code='.$row["id"],'class="show-invoice btn btn-info btn-xs"  title="View CO"	data-code="'.$row['order_id'].'"  '),
        			);
                }
                
                //QC Status
    			$qryQc = "SELECT SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty,SUM(qa_warehouse.pass_qty) AS total_pass_qty
                        FROM qa LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id WHERE  qa.type=8 and qa.order_id = '".$row['order_id']."' GROUP BY qa.order_id";
                
    			$resultQc = $conn->query($qryQc); 
                if ($resultQc->num_rows > 0)
                {
                    while($rowQc = $resultQc->fetch_assoc()) 
                    {
                        $passQty = $rowQc["total_pass_qty"]; if($passQty == null) $passQty = 0;
        			    $defactQty = $rowQc["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
        			    $damagedQty = $rowQc["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;
    
                    }
                }else{
                    $passQty = 0; $defactQty = 0; $damagedQty = 0;
                }
                
                $qcSt= $passQty.' Passed | '.$defactQty.' Defact | '.$damagedQty.' Damaged';
                
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
                                WHERE doi.delivery_type = 2 and doi.order_id= '".$row['co_id']."' GROUP BY alt.resourceid";
             //test
            $resultDeli = $conn->query($qryDeli); 
            if ($resultDeli->num_rows > 0)
            {
                while($rowDeli = $resultDeli->fetch_assoc()) 
                {
                    $deliSt = $rowDeli["dod_totpendingqty"]." Pendings | ".$rowDeli["dod_tottransqty"]." In transit | ".$rowDeli["dod_totdeliqty"]." Delivered | ".$rowDeli["dod_totretqty"]." Returned";
                }
            }else{
                $deliSt = "0 Pendings | 0 In transit | 0 Delivered | 0 Returned";
            }
            
			
			
            $data[] = array(
                    "sl"=> $i,
                    "co_id"=> $row['co_id'],
                    "socode"=> $row['order_id'],
                    "orgnm"=> $row['orgnm'],
                    "orderdate" =>$row['orderdate'],
                    "qcstatus" =>$qcSt,
                    "delistatus" =>$deliSt,
            		"type"=>$type,
					"action_buttons"=> getGridBtns($btns).$sendto,                                    
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