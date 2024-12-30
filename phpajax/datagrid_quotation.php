<?php

require "../common/conn.php";
include_once('../rak_framework/fetch.php');
require "../common/user_btn_access.php";
session_start();


//print_r($_REQUEST);
// echo '<pre>'; print_r($_SESSION); echo '<pre>';
$currSection = $_REQUEST['currSection'];
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



if($action=="quotation")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (
        	
        	    orst.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or o.`orgcode`  like '%".$searchValue."%' 

                 or s.`socode` like '%".$searchValue."%' or s.`orderdate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;

        ## Total number of records without filtering   #c.`id`,
/*
        $basequery="SELECT 
        s.`id`,orst.sl, 
        s.makedt makedt, 
        s.`socode`,
        tp.`name` `srctype`,
        c.`name` `customer`,
        o.`name` organization, 
        o.orgcode, 
        s.`orderdate`, 
        date_format(s.`orderdate`,'%d/%b/%Y') `orderdate_formated`,
        cr.shnm,
        format(sum(sd.qty*sd.otc),2) otc,
        s.orderstatus, 
        orst.name `quotationstatusname`,
        s.invoiceamount  invoiceamount, 
        format(sum(qtymrc*sd.mrc),2) mrc,
        concat(e.firstname,'  ',e.lastname) `hrName`, 
        concat(e1.firstname,'  ',e1.lastname) `poc`, 
        MIN(DATE_FORMAT( ti.expted_deliverey_date,'%d/%b/%Y')) AS expted_deliverey_date,
        inv.id iid, 
        inv.paymentSt,
        (case when s.srctype=2 then (select name from project where id=s.project) else 'Retail' end) saletp

        FROM `quotation` s left join `quotation_detail` sd on sd.socode=s.socode
        left join `quotation_warehouse` ti on ti.socode=s.socode
        left join `contacttype` tp on  s.`srctype`=tp.`id` 
        left join `contact` c on s.`customer`=c.`id` 
        left join `organization` o on o.`orgcode`=c.organization  
        left join `invoice` inv on inv.`soid`=s.socode  
        left join `quotation_status` orst on s.`orderstatus`=orst.`id` 
        left join `hr` h on o.`salesperson`=h.`id` 
        left join employee e on h.`emp_id`=e.`employeecode` 
        left join `hr` h1 on s.`poc`=h1.`id`  
        left join employee e1 on h1.`emp_id`=e1.`employeecode`
        left join currency cr on sd.currency=cr.id 
        WHERE  1=1 and (s.organization = $orgid or $orgid = 0) $cmbstatus_str";*/
        
        $type = $_GET["type"];
        if($type != ""){
            $typeqry = " and s.socode LIKE 'GIFT%'";
        }else{
            $typeqry = " and s.socode LIKE 'QT%'";
        }
        
        $basequery="SELECT s.`id`, s.makedt makedt, s.`socode`, o.`name` organization, o.orgcode, s.`orderdate`, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate_formated`, s.orderstatus, orst.name `quotationstatusname`, inv.approval ,
                    (select MIN(DATE_FORMAT( ti.expted_deliverey_date,'%d/%b/%Y')) from quotation_warehouse ti where ti.socode=s.socode) AS expted_deliverey_date, inv.id iid, inv.paymentSt, (case when s.srctype=2 then (select name from project where id=s.srctype) else 'Retail' end) saletp 
                    FROM `quotation` s 
                    left join `organization` o on o.id=s.organization 
                    left join `invoice` inv on inv.`soid`=s.socode
                    left join `quotation_status` orst on s.`orderstatus`=orst.`id` 
                     WHERE  1=1 and (s.organization = $orgid or $orgid = 0) $cmbstatus_str $typeqry";


        $strwithoutsearchquery=$basequery;
        //." group by s.`id`, s.orderdate,s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm,s.orderstatus";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery;
        //." group by s.`id`, s.orderdate, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus";
        $groupby = " GROUP BY s.`id`, s.makedt, s.`socode`, o.`name`, o.orgcode, s.`orderdate`, s.orderstatus, orst.name, saletp";
        
 
         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        
        
        if($columnName == "id"){
            $columnName = "s.id";
        }
        else if ($columnName == "orderdate")
        { 
            $columnName = "s.orderdate";
        }
      /*  else if ($columnName == "makedt")
        { 
            $columnName = "ti.expted_deliverey_date";
        }*/
        else
        {
            $columnName=$columnName;
        }
        

         $empQuery=$basequery.$searchQuery.$groupby ."   order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
         //." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus  order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
         //$empQuery=$basequery.$searchQuery."   order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        //orst.sl asc ,s.orderstatus asc, 
        //s.`status`<>6
        /*##########*/
        
    
        /*##########*/
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

// 		echo $empQuery; die;
        $dynamicNumber = $row["iid"];
        $dynamicNumberString = (string)$dynamicNumber;
        $resultString = str_pad($dynamicNumberString, 6, '0', STR_PAD_LEFT);
		if($row['paymentstid'] == 4){
			    $inv = "INV-".$resultString;
			}else if($row['paymentstid'] == 5){
			    $inv = "PI-".$resultString;
			}else{
			    $inv= '-';
			}

	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;

            $st=$row['orderstatus'];
			
			$order_creator_id  = fetchByID('quotation','id',$row['id'],'makeby');
            
            $seturl="quotationEntry.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";
			
			
			
			//booked order can be editted by its creator and admin.
			
			
            //if(($st == 9 && $order_creator_id == $_SESSION['user']) || $st == 1 || $_SESSION['user'] == 1){
			if((( $st == 1 || $order_creator_id == $_SESSION['user']) || $_SESSION['currPriv'] > 3) &&  $st != 2 ){
				
            	$urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
            }
            else
            {
            $urlas='<a class="btn btn-info btn-xs"   disabled><i class="fa fa-edit"></i></a>';
            }
            

            $setInvurl="invoicPart.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";

            $setdelurl="common/delobj.php?obj=quotation&ret=quotationList&mod=2&id=".$row['id'];
             
			
			//booked order can be deleted by its creator and admin. .
            if((( $st == 1 || $order_creator_id == $_SESSION['user']) || $_SESSION['currPriv'] > 4) && $st != 2){
            	//$urlasdel='<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
				 $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            }
            else
            {
            	$urlasdel='<a class="btn btn-info btn-xs"  disabled><i class="fa fa-remove"></i></a>';
            }
  
            
			$invViewLink = '<a data-socode="'.$row['socode'].'" href="quotation_view.php" class="show-invoice btn btn-info btn-xs" title="View Quotation" target="_blank"><i class="fa fa-eye"></i></a>';			
			
            $i++;
			
			//generate button array
			$btns = array(); //array('delete','quotation_view.php','attrs'),
			if($type != ""){
                $btns[0] = 	array('view','gift_rdl.php','class="show-invoice btn btn-info btn-xs"  title="View"	data-socode="'.$row['socode'].'"');
            }else{
                $btns[0] = 	array('view','quotation_view.php','class="show-invoice btn btn-info btn-xs"  title="View Quotation"	data-socode="'.$row['socode'].'" data-st="'.$row["quotationstatusname"].'"  data-stcode="'.$row["orderstatus"].'"');
            }
            
			if($row["orderstatus"] == 1) {
                $btns[1] = array('edit', 'quotationEntry.php?res=4&msg=Update Data&id=' . $row['id'] . '&mod=3&mode=update', 'class="btn btn-info btn-xs" title="Edit"');
                $btns[2] = 	array('delete','common/delobj.php?obj=quotation&ret=quotationList&mod=2&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" ');
            } else {
                $btns[1] = array('edit', '', 'class="btn btn-info btn-xs" disabled title="Edit"');
                $btns[2] = 	array('delete','','class="btn btn-info btn-xs griddelbtn" disabled title="Delete" ');
            }
            
			
			//QC Status
			$qryQc = "SELECT SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty,SUM(qa_warehouse.pass_qty) AS total_pass_qty
                    FROM qa LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id WHERE  qa.type=1 and qa.order_id = '".$row['socode']."' GROUP BY qa.order_id";
            
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
                                WHERE doi.order_id= '".$row['socode']."' GROUP BY alt.resourceid";
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
            
            $approval_req = '';
            $dynamicNumber = $row["iid"];
            $dynamicNumberString = (string)$dynamicNumber;
            $resultString = str_pad($dynamicNumberString, 6, '0', STR_PAD_LEFT);
    		if($row['paymentSt'] == 4){
			    $inv = "INV-".$resultString;
			}else if($row['paymentSt'] == 5){
			    $inv = "PI-".$resultString;
			}else{
			    $inv= '-';
			    if($row["orderstatus"] == 2){
    			    $appurl = "phpajax/send_approval_qc.php?invid=".$row["iid"];
    			    $approval_req = '| <a class="btn btn-info btn-xs approval" title="Action"  href="'. $appurl .'"  ><i class="fa fa-check"></i></a>';
			    }
			}
			
			if($row["approval"] == 4){
                $orderst = '<kbd class="pending">Declined</kbd>';
            }else{
                $orderst = '<kbd class="orstatus_'.$row['orderstatus'].'">'.$row['quotationstatusname'].'</kbd>';
            }
			
            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$row['id'],
					"makedt"=>$row['makedt'],
					
					//"expted_deliverey_date"=>$row['expted_deliverey_date'],
					"expted_deliverey_date" => '<div class="deldatewrap"><span>'.$row['expted_deliverey_date'] .'</span><a class="pop-deliverydate" data-socode="'.$row['socode'].'" href="change_delivery_date.php"><i class="fa fa-edit"></i></a></div>',
					
				//	"srctype"=>$row['srctype'],

            	//	"hrName"=>$row['customer'],

            		"organization"=>$row['organization'],
					
					"orgcode"=>$row['orgcode'],

        			"socode"=>$row['socode'],//$empQuery,//
        			
        			"saletp"=>$row['saletp'], 
        			"invoice"=>$inv,
				
					"orderstatus"=> $orderst,
					
					"qcstatus"=> $qcSt,
					
					"delistatus"=> $deliSt,

            		"orderdate"=>$row['orderdate_formated'],

    			//	"shnm"=>$row['shnm'],

            	//	"otc"=>number_format($row['invoiceamount'],2), //order amount

            	//	"mrc"=>$row['mrc'],

            	//	"poc"=>$row['poc'],

            		//"action_buttons"=> $invViewLink ." | ".$urlas." | ".$urlasdel,
                    "action_buttons"=> getGridBtns($btns).$approval_req, 
                
                   
                

            	//	"inv"=>'<a class="btn btn-info btn-xs"  href="'. $setInvurl.'">Create Invoice</a>',

            		//"del"=>$urlasdel,
					
				

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