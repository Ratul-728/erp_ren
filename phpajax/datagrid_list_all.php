<?php
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/misfuncs.php');
include_once("../rak_framework/listgrabber.php");
require "../common/user_btn_access.php";

session_start();
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
$coalvl=$_GET['coalvl'];
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

## Search 

 if($action=="target")
    {

        $searchQuery = " ";
        if($searchValue != '')
        {

        	$searchQuery = " and (a.`yr` like '%".$searchValue."%' or 

                a.`mnth` like '%".$searchValue."%' or  b.hrName like '%".$searchValue."%' or c.name like '%".$searchValue."%' or

                d.name like '%".$searchValue."%' or  a.`target` like '%".$searchValue."%' or  a.`achivement` like '%".$searchValue."%') ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery="SELECT a.`id`, a.`yr`, a.`mnth`, b.hrName `accmgr`, c.name `itmcatagory`, d.name `item`,FORMAT(a.`target`,2) target, FORMAT(a.`achivement`,2) achivement

        FROM `salestarget` a

        LEFT OUTER JOIN `item` d ON a.`item`=d.`id`

        LEFT JOIN `hr` b ON a.`accmgr`=b.`id`

        LEFT JOIN `itmCat` c  ON a.`itmcatagory`=c.`id` where 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="target.php?res=4&msg='Update Data'&id=".$row['id']."&acm=".$row['acm']."&yr=".$row['yr']."&mod=2";

           // $photo=$rootpath."/common/upload/contact/".$row["contactcode"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            //if (file_exists($photo)) {

        	//	$photo="common/upload/contact/".$row["contactcode"].".jpg";

        	//	}else{

        			$photo="images/blankuserimage.png";

        	//	}

            $data[] = array(

                    "photo"=>'<img src='.$photo.' width="50" height="50">',

                   	"accmgr"=>$row['accmgr'],

            		"yr"=>$row['yr'],

            		"mnth"=>$row['mnth'],

            		"itmcatagory"=>$row['itmcatagory'],

        			"item"=>$row['item'],

            		"target"=>$row['target'],

            		"achivement"=>$row['achivement'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            	);

        		

        }

    }
     else if($action=="returnorder"){

        $searchQuery = " ";
        $cmbcustomer = $_REQUEST["customer"];
        if($cmbcustomer != ''){
        	$cmbcustomer = "and org.id = ".$cmbcustomer;
        }else{$cmbcustomer = "";}

        if($searchValue != '')

        {
			$searchValue = (strstr($searchValue,","))?strToNumber(trim($searchValue)):trim($searchValue);
			
			
        	$searchQuery = " and (
				 
				 ro.ro_id like '%".$searchValue."%' or  
				 ro.order_id like '%".$searchValue."%' or  
				 org.name like '%".$searchValue."%'
				 
				 )";

        }
        ## Total number of records without filtering   #c.`id`,
	 
	   $strwithoutsearchquery="SELECT ro.ro_id, ro.order_id,  DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate, org.name customer, ro.st 
	                            FROM `return_order` ro LEFT JOIN quotation q ON q.socode=ro.order_id LEFT JOIN organization org ON org.id=q.organization 
	                            WHERE 1=1 ".$dt_range_str." $cmbcustomer ";

	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        if($columnName == 'sl')
        {
            $columnName=" ro.id ";
        }

         //$empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by i.makedt desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
	 	 $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="returnorder.php?res=4&msg='Update Data'&id=".$row['ro_id']."&mod=3";
           $st = $row["st"];
           
           $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';
           if($st == 1){
               $status = '<kbd class="due">Pending</kbd>';
           }
           else if($st == 0){
               $status = '<kbd class="pending">Declined</kbd>';
           }else if($st == 2){
               $status = '<kbd class="paid">Accepted</kbd>';
           }
		   
			$invViewLink = '<a data-invid="'.$row['paySt'].'" href="return_rdl.php?roid='.$row['ro_id'].'&mod=3" class="show-invoice btn btn-info btn-xs" title="Return Order" target="_blank"><i class="fa fa-eye"></i></a>';
			
			$invViewLink2 = '<a data-invid="'.$row['paySt'].'" href="return_checklist.php?roid='.$row['ro_id'].'&mod=3" class="show-returnchecklist btn btn-info btn-xs" title="Return Checklist" target="_blank"><i class="fa fa-list-ul"></i></a>';
			
           $i++;
           
                //QC Status
    			$qryQc = "SELECT SUM(qa_warehouse.damaged_qty) AS total_damaged_qty, SUM(qa_warehouse.defect_qty) AS total_defect_qty,SUM(qa_warehouse.pass_qty) AS total_pass_qty, qa.status
                        FROM qa LEFT JOIN qa_warehouse ON qa.id = qa_warehouse.qa_id WHERE  qa.type=6 and qa.order_id = '".$row['ro_id']."' GROUP BY qa.order_id";
                
    			$resultQc = $conn->query($qryQc); 
                if ($resultQc->num_rows > 0)
                {
                    while($rowQc = $resultQc->fetch_assoc()) 
                    {
                        $passQty = $rowQc["total_pass_qty"]; if($passQty == null) $passQty = 0;
        			    $defactQty = $rowQc["total_defect_qty"]; if($defactQty == null) $defactQty = 0;
        			    $damagedQty = $rowQc["total_damaged_qty"]; if($damagedQty == null) $damagedQty = 0;
                        
                        // if($rowQc["status"] == 1){
                        //     $status = '<kbd class="pending">QA Pending</kbd>';
                        // }else if($rowQc["status"] == 2){
                        //     $status = '<kbd class="inprogress">QA In Progress</kbd>';
                        // }if($rowQc["status"] == 1){
                        //     $status = '<kbd class="completed">QA Completed</kbd>';
                        // }
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
                                WHERE doi.delivery_type = 3 and doi.order_id= '".$row['ro_id']."' GROUP BY alt.resourceid";
             //test
            $resultDeli = $conn->query($qryDeli); 
            if ($resultDeli->num_rows > 0)
            {
                while($rowDeli = $resultDeli->fetch_assoc()) 
                {
                    $deliSt = $rowDeli["dod_totpendingqty"]." Pendings | ".$rowDeli["dod_tottransqty"]." In transit | ".$rowDeli["dod_totdeliqty"]." Delivered | ".$rowDeli["dod_totretqty"]." Returned";
                    // if($rowDeli["dod_totdeliqty"] == $rowDeli["dod_totdoqty"] ){
                    //     $status =  '<kbd class="completed">Delivered</kbd>';
                    // } else if($rowDeli["dod_totdeliqty"] > 0){
                    //     $status =  '<kbd class="inprogress">Partial Delivery</kbd>';
                    // }
                }
            }else{
                $deliSt = "0 Pendings | 0 In transit | 0 Delivered | 0 Returned";
            }
                
        
			$data[] = array(
                    "sl"=>$i,
					
					"ro_id"=>$row['ro_id'],
					"order_id"=>$row["order_id"],
				
					"orderdate"=>$row["orderdate"] ,
                    "customer"=>$row['customer'],
                    "qcstatus"=>$qcSt,
                    "delistatus"=>$deliSt,
            		"status"=>$status,
            		"action"=> "".$invViewLink."|".$invViewLink2,

            	);

        } 

    }
     else if($action=="cancelorder"){

        $searchQuery = " ";
        $cmbcustomer = $_REQUEST["customer"];
        if($cmbcustomer != ''){
        	$cmbcustomer = "and org.id = ".$cmbcustomer;
        }else{$cmbcustomer = "";}

        if($searchValue != '')

        {
			$searchValue = (strstr($searchValue,","))?strToNumber(trim($searchValue)):trim($searchValue);
			
			
        	$searchQuery = " and (
				 
				 ro.co_id like '%".$searchValue."%' or  
				 ro.order_id like '%".$searchValue."%' or  
				 org.name like '%".$searchValue."%' or
				 i.name like '%".$searchValue."%' or
				 i.barcode like '%".$searchValue."%'
				 
				 )";

        }
        ## Total number of records without filtering   #c.`id`,
	 
	   $strwithoutsearchquery="SELECT ro.co_id, ro.order_id,  DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate, org.name customer,i.barcode,
	   i.name product,qd.qty orderqty,ro.qty_canceled ,( case when ro.st=1 then 'Pending' when ro.st=2 then 'Approved' else 'Decliend' end) approvst 
FROM `cancel_order` ro 
left join quotation q ON q.socode=ro.order_id 
LEFT JOIN quotation_detail qd ON q.socode=qd.socode  and ro.productid=qd.productid
LEFT JOIN organization org ON org.id=q.organization
left join item i on ro.productid=i.id
WHERE 1=1 ".$dt_range_str." $cmbcustomer ";  
  
	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        if($columnName == 'sl')
        {
            $columnName=" ro.id ";
        }

         //$empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by i.makedt desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
	 	 $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="cancelorder.php?res=4&msg='Update Data'&id=".$row['co_id']."&mod=3";
           $st = $row["approvst"];
          
		    //$invViewLink = '<a data-invid="'.$row['paySt'].'" href="return_rdl.php?roid='.$row['ro_id'].'&mod=3" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
			
           $i++;
        
			$data[] = array(
                    "sl"=>$i,
					
					"ro_id"=>$row['co_id'],
					"order_id"=>$row["order_id"],
					"orderdate"=>$row["orderdate"] ,
                    "customer"=>$row['customer'],
            		"Product"=>$row['product'],
            		"barcode"=>$row['barcode'],
            		"orderqty"=>$row['orderqty'],
            		"qty_canceled"=>$row['qty_canceled'],
            		"status"=>$st,
            		//"action"=> "".$invViewLink,

            	);

        } 

    }
    else if($action=="hc")
    {

        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and ( emp.`employeecode` like '%".$searchValue."%' or 
                 concat( emp.`firstname`,  emp.`lastname`)  like '%".$searchValue."%' or  emp.dob like '%".$searchValue."%' or  emp.office_contact like '%".$searchValue."%' or
                 emp.office_email like '%".$searchValue."%' or  emp.nid like '%".$searchValue."%') ";
        }

        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT emp.`id`, emp.`employeecode`, CONCAT(emp.`firstname`, ' ', emp.`lastname`) AS `name`, DATE_FORMAT(emp.`dob`, '%d/%b/%Y') AS `dob`, 
                                emp.`nid`, emp.`office_contact`, emp.`office_email`, emp.`bloodgroup`, emp.`photo`, dept.name deptnm, des.name desinm ,
                                hr.is_login_blocked login_status,
                                hr.id hr_id
                                FROM `employee` emp 
                                LEFT JOIN department dept ON dept.id=emp.department 
                                LEFT JOIN designation des ON des.id=emp.designation
                                LEFT JOIN hr  ON hr.emp_id=emp.employeecode
                                WHERE emp.active_st = 'A' ";
                                
                                #LEFT JOIN ( SELECT id, hrid FROM hraction ORDER BY id DESC ) latest_hra ON hra.id = latest_hra.id 
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        while ($row = mysqli_fetch_assoc($empRecords))
        {
           $seturl="employee_hr.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";
           $setdelurl="common/delobj.php?obj=employee&ret=hcList&mod=4&id=".$row['id'];
           $blockurl="phpajax/block_user_login.php";
           $blockTItle = $row['login_status']=='0' ? 'Block User' : 'Unblock User';
           $btnIcon= $row['login_status']=='0' ? 'unlock' : 'lock';
           $btns = array(
			            array('block',$blockurl,  'btnicon="{{'.$btnIcon.'}}"  class="btn btn-info btn-xs '.$btnIcon.' gridblock login_status_'.$row['login_status'].'" data-empid="'.$row['id'].'"  data-hrid="'.$row['hr_id'].'" data-status="'.$row['login_status'].'"  title="'.$blockTItle.'" '),
                        array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				        array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			            );

            $photo=$rootpath."/common/upload/hc/".$row["photo"]."";
            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
            if (file_exists($photo))
            {
        		$photo="common/upload/hc/".$row["photo"]."";
    		}
    		else
    		{
    			$photo="images/blankuserimage.png";
        	}

            $data[] = array(
                    "id"=>$row['id'],
                    "photo"=>'<img src='.$photo.' width="50" height="50">',
                   	"employeecode"=>$row['employeecode'],
            		"name"=>$row['name'],
            		"dept"=>$row['deptnm'],
            		"desi"=>$row['desinm'],
            		"dob"=>$row['dob'],
            		"office_contact"=>$row['office_contact'],
        			"office_email"=>$row['office_email'],
            		"nid"=>$row['nid'],
        			"bloodgroup"=>$row['bloodgroup'],
                    "action"=> getGridBtns($btns), 
                    );
        } 

    }

    else if($action=="privl")

    {

        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery="SELECT a.`id`,h.resourse_id,concat(e.`firstname`,e.`lastname`) nm , m.menuNm,( case `menu_priv`  when 1 then 'Log In'   when 2 then 'Only View' when 3 then 'Upto Create' when 4 then 'Up to Update' when 5 then 'Up to Delete' else 'No Privillage' end) priv 

        FROM `hrAuth` a join `hr` h on a.hrid=h.id  join `mainMenu` m on a.menuid= m.id join employee e on h.emp_id=e.employeecode

       where 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

         $empQuery="SELECT a.`id`,h.resourse_id,concat(e.`firstname`,e.`lastname`) nm , m.menuNm,( case `menu_priv`  when 1 then 'Log In'   when 2 then 'Only View' when 3 then 'Upto Create' when 4 then 'Up to Update' when 5 then 'Up to Delete' else 'No Privillage' end) priv 

        FROM `hrAuth` a join `hr` h on a.hrid=h.id  join `mainMenu` m on a.menuid= m.id join employee e on h.emp_id=e.employeecode

       where 1=1 ";

       //.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        

        //echo $empQuery;exit;

        //exit;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="priv.php?res=4&msg='Update Data'&id=".$row['id']."&mod=5";

            $photo=$rootpath."/common/upload/hc/".$row["resourse_id"].".jpg";

            $conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (file_exists($photo)) {

        		$photo="common/upload/hc/".$row["resourse_id"].".jpg";

        		}else{

        			$photo="images/blankuserimage.png";

        		}

            $data[] = array(

                    "id"=>$row['nm'],

                    "photo"=>'<img src='.$photo.' width="50" height="50">',

            		"resourse_id"=>$row['resourse_id'],

            		"menuNm"=>$row['menuNm'],

            		"priv"=>$row['priv'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            	);

        } 

    }

    else if($action=="collec")

    {
        
        //Filter
        $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];

        $filterorg = $_GET["filterorg"];

        if($filterorg != ''){

            $filterorgqry = " and cl.`customerOrg` = ".$filterorg;

        }else{
            $filterorgqry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%'  or inv.`invoiceNo` like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,inv.`invoiceNo` invoice, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,cl.`amount` amount, cu.shnm FROM collection cl left join organization c on cl.`customerOrg`=c.id 

left join transmode tr on cl.transmode=tr.id left JOIN `currency` cu ON cu.id = cl.currencycode left join invoice inv ON cl.invoice = inv.id

 where 1=1 and cl.`trdt` BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') $filterorgqry";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $totamount = 0.0;
        
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totamount += $row["amount"];

            $seturl="collection.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            
            $setview="prnt_collection_receipt.php?rpid=".$row['id'];

            $setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>number_format($row['amount'], 2)." ".$row["shnm"],
            	
        			"inv"=>$row['invoice'],
        			
        			"view"=>'<a class="btn btn-info btn-xs viewnprint"  href="'. $setview.'">View</a>',

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);

        } 
        
        array_push($total, number_format($totamount,2));

    }
    
    else if($action=="brand")
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
         $empQuery=$strwithsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
										
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $strwithsearchquery);
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
    
    else if($action=="rpt_attendance_all")

    {

        extract($_REQUEST);
        $emp = 0;
        
        if($dt_f == '') $dt_f =date('Y-m-d', strtotime('-2 days'));
        if($dt_t == '') $dt_t =date('Y-m-d');
        // echo $dt_f. " "; echo $dt_t; die; 
        $dt_range_str = ($dt_f && $dt_t)?" and a.date BETWEEN '".$dt_f."' AND '".$dt_t."'":"";		
		//echo $dt_range_str;die;
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (e.employeecode like '%".$searchValue."%' or 

                 concat(e.firstname,' ',e.lastname)  like '%".$searchValue."%' or st.title like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select e.`employeecode`,concat(e.`firstname`,' ',e.`lastname`) nm
                                ,cl.`day` date,h.`attendance_id` hrid ,st.`title` shift,st.`starttime`,st.`exittime` 
                                ,a.`intime`,a.`outtime`
                                ,(case when a.`intime`>st.`starttime` then TIME_FORMAT(TIMEDIFF(a.`intime`,st.`starttime`), '%H:%i:%s') else 0 end)latetime
                                ,(case when  st.`exittime`>a.`outtime` then TIME_FORMAT(TIMEDIFF(st.`exittime`,a.`outtime`), '%H:%i:%s') else 0 end)earlytime
                                ,(case when a.`date` is null then (case when (SELECT count(`hrid`) hrd FROM `leave` where a.`date` between `startday` and `endday` and `hrid`=h.`id`)>0 then 'Leave' else 'Absent'  end) else (case  a.attendance_type  when 0 then 'Absent' when 1 then 'Present' when 2 then 'Delay' else 'N' end)  end)  stats ,
                                a.`early_leave` 
                                from `calander` cl,`employee` e left join `hr` h on e.`employeecode`=h.`emp_id` and h.`active_st` = 1
                                left join `attendance_test` a on a.`hrid`=h.`attendance_id` $dt_range_str
                                left join `Shifting` st on st.`id`=nvl((SELECT s.`shift` FROM `assignshifthist` s  where s.`st`=1 and s.`empid`=e.`id` and s.`effectivedt`=a.`date`),3) 
                                where  cl.day BETWEEN '$dt_f' AND '$dt_t'
                                ";
                                 

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "sl"){
            $columnName = "a.date";
        }


         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die; 

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            //if($row['date'] == '') continue;
            
            $i++;
            
            $seturl="acc_collection.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=collection&ret=acc_collectionList&mod=7&id=".$row['id'];

            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
			if($row["early_leave"] == 1){
			    $early_leave = "YES";
			}else{
			    $early_leave = "NO";
			}

            $data[] = array(

                    //"trdt"=> $row['trdt'],
					
					"sl"=> $i,//$empQuery,//
				
					"employeecode"=>$row['employeecode'],
            		
            		"nm"=>$row['nm'],

            		"date"=>$row['date'],

        			"shift"=>$row['shift'],

            		"starttime"=>$row['starttime'],

        			"exittime"=>$row['exittime'],
        			
        			"intime"=>$row['intime'],
        			
        			"outtime"=>$row['outtime'],

        			"latetime"=>$row['latetime'],

            		"earlytime"=>$row['earlytime'],

        			"worktime"=>$row['worktime'],
        			
        			"early_leave"=>$early_leave,
        			
        			"stats"=>$row['stats'],
            	);

        } 

    }
    
  else if($action=="acc_collection")

    {

extract($_REQUEST);
$dt_range_str = ($dt_f && $dt_t)?" and cl.trdt BETWEEN '".$dt_f." ".$dt_f."' AND '".$dt_t."'":"";		
		
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%'  or cl.`invoice` like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,cl.`invoice`, cl.makedt, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2) amount, cu.shnm, gl.glnm FROM collection cl 
				left join organization c on cl.`customerOrg`=c.id 
				left join transmode tr on cl.transmode=tr.id 
				left JOIN `currency` cu ON cu.id = cl.currencycode 
				left join coa gl on cl.glac = gl.glno

 			where 1=1 ".$dt_range_str;

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        
        if($columnName == "makedt"){
            $columnName = "cl.makedt";
        }


         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="acc_collection.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=collection&ret=acc_collectionList&mod=7&id=".$row['id'];
            $invPayLink = '<a href="'.$seturl.'" class="btn btn-info btn-xs" title="Pay"><i class="fa fa-dollar"></i></a>';
            $btns = array(
				// array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    //"trdt"=> $row['trdt'],
					
					"trdt"=> '<span class="rowid_'.$row['id'].'">'.date_format(date_create($row['trdt']),"d/M/Y").'</span>',
				
					"makedt"=> date_format(date_create($row['makedt']),"d/M/Y | g:i a"),
					"transmode"=>$row['transmode'],
            		

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount']." ".$row["shnm"],

        			"inv"=>$row['invoice'],
        			
        			"glac"=>$row['glnm'],

            		"action"=> $invPayLink ." | ". getGridBtns($btns),
            	);

        } 

    }
    
    else if($action=="inv_stock")
    {
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $brand = $_GET["brand"]; if($brand == '') $brand = 0;
        $bc1 = $_GET["barcode"];
        
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and (p.barcode like '%".$searchValue."%' or p.name like '%".$searchValue."%' or t.name  like '%".$searchValue."%' or s.freeqty  like '%".$searchValue."%' 
        	or s.bookqty  like '%".$searchValue."%'  or s.costprice  like '%".$searchValue."%'  or p.rate  like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,p.id pid,p.barcode code,p.image, s.product,p.name prod,t.name typ,b.title brand, s.freeqty, s.bookqty, s.costprice,p.rate 
        FROM stock s left join item p on s.product=p.id
        left join itmCat t on p.catagory=t.id
		left join brand b on p.brand=b.id
        where  (p.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' or p.barcode like '%".$bc1."%' ) and ( t.id = ".$cat." or ".$cat." = 0 ) and ( b.id = ".$brand." or ".$brand." = 0 ) ";
        
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
            $data[] = array(
                    "id"=>$sl,
                    "image"=>'<img src='.$photo.' width="50">',
                    "code"=>$row['code'],
            		"prod"=>$row['prod'],
            		"typ"=>$row['typ'],
					"brand"=>$row['brand'],
            		"freeqty"=>"<strong>".number_format($row['freeqty'],0)."</strong>",
            		"bookqty"=>number_format($row['bookqty'],0),
            		"costprice"=>number_format($row['costprice'],2),
            		"mrp"=>number_format($row['rate'],2)
					
            	);
        } 
    }
    else if($action=="min_stock")
    {
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and (p.code like '%".$searchValue."%' or p.name like '%".$searchValue."%' or t.name  like '%".$searchValue."%' or s.freeqty  like '%".$searchValue."%' 
        	or s.bookqty  like '%".$searchValue."%'  or s.costprice  like '%".$searchValue."%'  or p.rate  like '%".$searchValue."%' or p.minimumstock  like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,p.id pid,p.code,p.image, s.product,p.name prod,t.name typ, s.freeqty, s.bookqty, s.costprice,p.rate,p.minimumstock  FROM stock s left join item p on s.product=p.id
        left join itemtype t on p.catagory=t.id
        where  s.freeqty<p.minimumstock ";
        
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
			
			/*
               $photo="../common/upload/item/".$row["code"].".jpg";
               if (file_exists($photo)) {

        		$photo="common/upload/item/".$row["image"].".jpg";

        		}else{

        			$photo="common/upload/item/placeholder.jpg";

        		}
			*/
				$photo		= (strlen($row['image'])>0)?"assets/images/products/300_300/".$row["image"]:"assets/images/products/placeholder.png";			
			
			
              $sl=$sl+1;  
            $data[] = array(
                    "id"=>$sl,
                    "image"=>'<img src='.$photo.' width="50">',
                    "productcode"=>$row['code'],
            		"prod"=>$row['prod'],
            		"typ"=>$row['typ'],
            		"minqty"=>number_format($row['minimumstock'],0),
            		"freeqty"=>number_format($row['freeqty'],0),
            		"costprice"=>number_format($row['costprice'],2),
            		"mrp"=>number_format($row['rate'],2)
            	);
        } 
    }
    
    else if($action=="challan")
    {
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (p.`poid` like '%".$searchValue."%' or p.`adviceno` like '%".$searchValue."%' or pr.`name` like '%".$searchValue."%' or
                  DATE_FORMAT( p.`orderdt`,'%d/%b/%Y') like '%".$searchValue."%' or format(p.`tot_amount`,2)  like '%".$searchValue."%' or
               format(p.`invoice_amount`,2)  like '%".$searchValue."%' or DATE_FORMAT( p.`delivery_dt`,'%d/%b/%Y') like '%".$searchValue."%'  ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT distinct p.`id`,p.makedt,p.`poid`, (SELECT COUNT(poid) FROM poitem poi  WHERE poi.poid = p.poid ) noofitem , p.`adviceno`, DATE_FORMAT( p.`orderdt`,'%d/%b/%Y') `orderdt`, p.`tot_amount`, p.`invoice_amount`
        ,DATE_FORMAT( p.`delivery_dt`,'%d/%b/%Y') `delivery_dt` 
        FROM `po` p join poitem i on p.poid=i.poid 
        left join item pr on i.itemid=pr.id
        where p.status='A' ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ##.`id`,
		/*
         if($columnName == 'id')
        {
            $columnName=" p.id ";
        }
		*/
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $seturl="chalan_view.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
            $setdelurl="common/delobj.php?obj=po&ret=challanList&mod=12&id=".$row['id']."";
            $setedturl="challan.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
            $setreturl="chalanreturn.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12&po=".$row['poid'];
            $seturlbarcode="barcode/generate_barcode.php?id=".$row['id']."&chid=".$row['poid'];
			
			$bcurl='<a class="btn btn-info btn-xs" title="Barcode"  href="'. $seturlbarcode.'"  ><i class="fa fa-barcode"></i></a>';
            
            //$ed='<a class="btn btn-info btn-xs"  href="'. $setedturl.'"  >Edit</a>';
			$edturl='<a class="btn btn-info btn-xs" title="Edit"  href="'. $setedturl.'"  ><i class="fa fa-edit"></i></a>';
			
            //$rt='<a class="btn btn-info btn-xs"  href="'. $setreturl.'"  >Return</a>';
			
			$returnurl='<a class="btn btn-info btn-xs" title="Return"  href="'. $setreturl.'"  ><i class="fa fa-mail-reply"></i></a>';
			
            //$dl= '<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
            //$vw='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  >View</a>';
			$viewurl='<a class="btn btn-info btn-xs" title="View"  href="'. $seturl.'"  ><i class="fa fa-eye"></i></a>';
            
            
            $sl=$sl+1;
            $data[] = array(
                    "makedt"=>$row['makedt'],
                    "adviceno"=>$row['adviceno'],
                    "poid"=>$row['poid'],
					"noi" =>$row['noofitem'],
				
            		"orderdt"=>$row['orderdt'],
            		"tot_amount"=>number_format($row['tot_amount'],2),
        			"invoice_amount"=>number_format($row['invoice_amount'],2),
            		"delivery_dt"=>$row['delivery_dt'],
            		//"edit"=>$vw,
            		//"cedit"=>$ed,
            		//"cret"=>$rt,
            		//"bc"=>'<a class="btn btn-info btn-xs"  href="'. $seturlbarcode.'" target="_blank">BarCode</a>'
					"action_buttons"=>$viewurl." | ".$edturl." | ".$bcurl, //".$returnurl." 
            	);
        } 
    }
    else if($action=="challaned")
    {
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (p.`poid` like '%".$searchValue."%' or p.`adviceno` like '%".$searchValue."%' or
                  DATE_FORMAT( p.`orderdt`,'%d/%b/%Y') like '%".$searchValue."%' or format(p.`tot_amount`,2)  like '%".$searchValue."%' or
               format(p.`invoice_amount`,2)  like '%".$searchValue."%' or DATE_FORMAT( p.`delivery_dt`,'%d/%b/%Y') like '%".$searchValue."%' ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT  p.`id`,p.`poid`,p.`adviceno`, DATE_FORMAT( p.`orderdt`,'%d/%b/%Y') `orderdt`, p.`tot_amount`, p.`invoice_amount`
        ,DATE_FORMAT( p.`delivery_dt`,'%d/%b/%Y') `delivery_dt` FROM `po` p  where 1=1 ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ##.`id`,
         if($columnName == 'id')
        {
            $columnName=" p.id ";
            $columnSortOrder=" desc";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by  ,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $seturl="chalanedit.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            $setdelurl="common/delobj.php?obj=po&ret=chalaneditList&mod=3&id=".$row['id'];
            $sl=$sl+1;
            $data[] = array(
                     "id"=>$sl,
                    "adviceno"=>$row['adviceno'],
                    "chalanno"=>$row['poid'],
            		"orderdt"=>$row['orderdt'],
            		"tot_amount"=>number_format($row['tot_amount'],2),
        			"invoice_amount"=>number_format($row['invoice_amount'],2),
            		"delivery_dt"=>$row['delivery_dt'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            		//"bc"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Return</a>'
            	);
        } 
    }
    else if($action=="challanret")
    {
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (p.`chalanno` like '%".$searchValue."%'  or
                 DATE_FORMAT( p.`returndt`,'%d/%b/%Y') like '%".$searchValue."%' or format(totalamount,2)  like '%".$searchValue."%' ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT  p.`id`,p.`chalanno`, DATE_FORMAT( p.`returndt`,'%d/%b/%Y') `returndt`, format(p.totalamount,2) tot_amount FROM `returnpo` p  where 1=1 ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ##.`id`,
         if($columnName == 'id')
        {
            $columnName=" p.id ";
            $columnSortOrder=" desc";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $seturl="returnchalanview.php?res=4&msg='view Data'&id=".$row['id']."&mod=3";
            $sl=$sl+1;
            $data[] = array(
                     "id"=>$sl,
                    "chalanno"=>$row['chalanno'],
            		"returndt"=>$row['returndt'],
            		"tot_amount"=>$row['tot_amount'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'" >View & Print</a>'
            		//"bc"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Return</a>'
            	);
        } 
    }
    else if($action=="suppl")
    {
        $searchQuery = "";
        if($searchValue != ''){
        	$searchQuery = " and (name like '%".$searchValue."%' or `address` like '%".$searchValue."%'  or `contact` like '%".$searchValue."%' 
        	or `email` like '%".$searchValue."%' or `web` like '%".$searchValue."%' 
        	) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT `id`,LPAD(id,4,'0') cd, `name`, `address`, `contact`,email,web FROM `suplier` WHERE status = 'A'  ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
         $empQuery=$strwithsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) {
            
           $seturl="supplier.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
           $setdelurl="common/delobj.php?obj=suplier&ret=supplierList&mod=12&id=".$row['id'];
           
            $ed='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  >Edit</a>';$dl= '<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			); 
           
            $data[] = array(
                    "id"=>$sl,
            		"cd"=>$row['cd'],
            		"name"=>$row['name'],
            		"address"=>$row['address'],
            		"contact"=>$row['contact_no'],
            		"email"=>$row['email'],
            		"web"=>$row['web'],
            		"action"=>getGridBtns($btns)
            	);
            $sl++;
        } 
    }
    
    else if($action=="cusorder")
    {
     $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];
        
     if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}   
        $searchQuery = " ";
        /* if($columnName == 'order_id')
        {
            $columnName=" o.orderdate ";
            $columnSortOrder=" desc";
        }*/
        if($searchValue != '')
        {
        	$searchQuery = " and (s.socode like '%".$searchValue."%' or tp.`name` like '%".$searchValue."%' or
                  DATE_FORMAT(so.`effectivedate`,'%d/%b/%Y') like '%".$searchValue."%' or format(s.invoiceamount)  like '%".$searchValue."%' ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery1="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
        , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount`
FROM `soitem` s left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization 
left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode`
left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join orderstatus st on s.orderstatus=st.id 
WHERE  1=1 ";
        
         $strwithoutsearchquery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
        , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount`
FROM `soitem` s left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization 
left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode`
left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join orderstatus st on s.orderstatus=st.id 
WHERE  1=1 ".$pqry." and s.orderdate BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')";
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ##.`id`,
        
        if($columnName == 'id')
        {
            $columnName=" s.id ";
            $columnSortOrder=" desc";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
             $seturl="cusorderdetail.php?res=4&msg='Update Data'&id=".$row['id']."&mod=13";
                $seturlv="cus_order_view.php?res=4&msg='Update Data'&id=".$row['id']."&mod=13";
                
                 if($row['stid']==2)
                {
                    $urlas='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  >Assign Agent</a>';
                } 
                else
                {
                     $urlas='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  disabled>Assign Agent</a>';
                }
            
            
            $sl=$sl+1;
            $data[] = array(
                    "id"=>$sl,
                    "socode"=>$row['socode'],
                    "srctype"=>$row['srctype'],
            		"customer"=>$row['customer'],
            		"organization"=>$row['organization'],
        			"orderdate"=>$row['orderdate'],
            		//"hrName"=>$row['hrName'],
            		"poc"=>$row['poc'],
            		"stnm"=>$row['stnm'],
            		"amount"=>number_format($row['amount'], 2),
            		"edit"=>$urlas,
                    "view"=>'<a class="btn btn-info btn-xs"  href="'. $seturlv.'">View</a>'
            	);
        } 
    }
   else if($action=="cusorderdelvstmt")
    {
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	    $dagent = $_GET["dagnt"];
	    if($dagent !=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and o.orderdate BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";
        
        if($columnName == 'order_id')
        {
            $columnName=" s.id ";
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%d/%b/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%d/%b/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
    where 1=1 ".$pqry." $dateqry and o.orderstatus=4";
        
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
                $seturl="cus_order_view.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$empQuery,//
                    "invoiceno"=>$row['invoiceno'],
                    "order_date"=>$row['order_date'],
                    "name"=>$row['cusnm'],
            		"addrs"=>$row['cusaddr'],
            		//"email"=>$row['email'],
            		"phone"=>$row['phone'],
            		"paymd"=>$row['payment_mood'],
            		//"payst"=>$row['payst'],
            	    //"agent"=>$row['agnt'],
                    "amount"=>number_format($row['amount'],2),  
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">View & print</a>'                                    
            	);
        } 
    }
    else if($action=="orderdelverd")
    {
        
       if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($columnName == 'socode')
        {
            $columnName=" s.socode ";
            $columnSortOrder=" desc";
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%d/%b/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%d/%b/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
    where 1=1 ".$pqry." and o.orderstatus in(5,8)";
        
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
                $seturl="cus_order_view.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
                $setreturnurl="return.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$empQuery,//
                    "name"=>$row['name'],//$strwithoutsearchquery1,//
            		//"addrs"=>$row['addrs'],
            		//"email"=>$row['email'],
            		"phone"=>$row['phone'],
            		"order_date"=>$row['order_date'],
            		"status"=>$row['ost'],
                "amount"=>number_format($row['amount'],2),                    
                "paymd"=>$row['payment_mood'],
            	"payst"=>$row['payst'],
            	"agent"=>$row['agentname'],
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">View</a>',    
                "return"=>'<a class="btn btn-info btn-xs"  href="'. $setreturnurl.'">Return</a>'
            	);
        } 
    }
    
    else if($action=="return_orders")
    {
        
      // if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo	
	  
	    $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and s.makedt BETWEEN DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
        
        $searchQuery = " ";
        
        if($columnName == 'socode')
        {
            $columnName=" s.socode ";
            $columnSortOrder=" desc";
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' ororg.`name` like '%".$searchValue."%' or DATE_FORMAT(o.`orderdate`,'%d/%b/%Y')  like '%".$searchValue."%' 
        	or s.qty  like '%".$searchValue."%' or s.return_qty  like '%".$searchValue."%'  b.name  like '%".$searchValue."%' ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT o.id oid,o.socode order_id,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%d/%b/%Y %T') order_date,DATE_FORMAT(s.makedt,'%d/%b/%Y') retdt
        ,s.qty ordqty,s.return_qty,b.name return_store
    FROM  soitem o  join order_returns s on o.socode=s.socode
     left join organization org on o.organization=org.id  
     left join branch b on s.return_store=b.id
    where 1=1 ";
        //o.orderstatus in(5,8) and
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery.$date_qry;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
         $empQuery=$strwithoutsearchquery.$searchQuery.$date_qry." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="reurn_order_view.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
                //$setreturnurl="return.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$empQuery,//
                    "name"=>$row['cusnm'],//$strwithoutsearchquery1,//
            		"order_date"=>$row['order_date'],
            		"retdt"=>$row['retdt'],                 
                "ordqty"=>$row['ordqty'],
            	"return_qty"=>$row['return_qty'],
            	"return_store"=>$row['return_store'],
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">View</a>',    
                //"return"=>'<a class="btn btn-info btn-xs"  href="'. $setreturnurl.'">Return</a>'
            	);
        } 
    }
    else if($action=="orderreturn")
    {
         
       if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($columnName == 'socode')
        {
            $columnName=" s.socode ";
            $columnSortOrder=" desc";
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%d/%b/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%d/%b/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
    ,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr
    ,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent,concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno,org.email
    FROM  soitem o left join orderstatus s on o.orderstatus=s.id
     left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
    where 1=1 ".$pqry." and o.orderstatus in(7,8)";
        /*$strwithoutsearchquery="SELECT o.`id`,o.`order_id`,o.`customer_id`,o.name,concat(o.`address`,',',o.`district`,',',o.`area`) addrs,o.`email`,o.`phone`,st.name stnm,o.`orderstatus` st
        , DATE_FORMAT(o.`order_date`,'%d/%b/%Y') `order_date`,o.`amount`,o.status payst,o.payment_mood 
        ,d.name agnt FROM `orders` o left join orderstatus st on o.orderstatus=st.id left join deveryagent d on o.`deleveryagent`=d.id  where 1=1  ".$pqry." and o.orderstatus in(7,8)"; 
        */
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
                $seturl="cus_order_view.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13&ret=1";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$strwithoutsearchquery1,//
                    "name"=>$row['name'],
            		//"addrs"=>$row['addrs'],
            		//"email"=>$row['email'],
            		"phone"=>$row['phone'],
            		"order_date"=>$row['order_date'],
            		"status"=>$row['ost'],
                "amount"=>number_format($row['amount'],2),                    
                "paymd"=>$row['payment_mood'],
            //	"payst"=>$row['payst'],
            	"agent"=>$row['agentname'],
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">View</a>'                                    
            	);
        } 
    }
    else if($action=="orderlist")
    {
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];
        
        $dagent = $_POST['dagnt'];
        $ost    = $_POST['odst'];
	
	    
	    if($ost!=''){$osqry=" and o.`orderstatus` =".$ost;} else { $osqry='';}
	     
       if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($columnName == 'order_id')
        {
            $columnName=" s.id ";
            
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (
			o.`socode` like '%".$searchValue."%' or 
			da.name like '%".$searchValue."%' or 
			org.`name` like '%".$searchValue."%' or 
			concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%' or 
			org.email  like '%".$searchValue."%'  or 
			org.contactno  like '%".$searchValue."%'  or 
			DATE_FORMAT(o.`orderdate`,'%d/%b/%Y')  like '%".$searchValue."%'  or 
			o.invoiceamount  like '%".$searchValue."%' or 
			s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%d/%b/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
    where 1=1 and o.orderdate BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') ".$pqry.$osqry;
          /*
           $strwithoutsearchquery="SELECT o.`id`,o.`order_id`,o.`customer_id`,o.name,concat(o.`address`,',',o.`district`,',',o.`area`) addrs,o.`email`,o.`phone`,st.name stnm,o.`orderstatus` st
        , DATE_FORMAT(o.`order_date`,'%d/%b/%Y') `order_date`,o.`amount`,o.status payst,o.payment_mood 
        ,d.name agnt FROM `orders` o left join orderstatus st on o.orderstatus=st.id left join deveryagent d on o.`deleveryagent`=d.id  where 1=1";
       */
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
                $seturl="cus_order_view.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$strwithoutsearchquery1,//
                    "name"=>$row['name'],
            		//"addrs"=>$row['addrs'],
            		//"email"=>$row['email'],
            		"phone"=>$row['phone'],
            		"order_date"=>$row['order_date'],
            		"status"=>$row['ost'],
                "amount"=>number_format($row['amount'],2),                    
                "paymd"=>$row['payment_mood'],
            	"payst"=>$row['payst'],
            	"agent"=>$row['agentname'],
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">View</a>'                                    
            	);
        } 
    }
    else if($action=="cusorderagentfb")
    {
        
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($columnName == 'socode')
        {
            $columnName=" s.socode ";
            $columnSortOrder=" desc";
        }
        
extract($_REQUEST);
$dt_range_str = ($dt_f && $dt_t)?" and oret.makedt BETWEEN '".$dt_f."' AND '".$dt_t."' ":"";		
		
		
		
        if($searchValue != '')
        {
			$searchValue = trim($searchValue);
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%d/%b/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  )";
        }
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm, o.orderdate order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr,c.name,concat(c.street,',',a1.name,',',d1.name,',',c.zip) cusaddr,o.invoiceamount amount,0 discount_total,0 shipping_charge,'' deleveryagent, concat(DATE_FORMAT(o.orderdate,'%e%c%Y'),o.id) invoiceno, org.email,
		(SELECT SUM(qty) FROM soitemdetails od WHERE socode = order_id)  item_ordered,
		IFNULL((SELECT SUM(return_qty) FROM order_returns ro WHERE socode = order_id),0) item_returned, 
		IFNULL(oret.makedt,'NA') returned_date
    FROM  soitem o 
	left join orderstatus s on o.orderstatus=s.id
    left join organization org on o.organization=org.id
    left join district d on org.district=d.id
    left join area a on org.area=a.id
    left join contact c on o.customer=c.id
    left join district d1 on c.district=d1.id
    left join area a1 on c.area=a1.id
	left join deveryagent da on o.deliveryby=da.id
	LEFT JOIN order_returns oret ON o.socode =  oret.socode
	
    where 1=1 ".$pqry." ".$dt_range_str." and o.orderstatus in (5,7,8) AND (SELECT SUM(qty) FROM soitemdetails WHERE socode = o.socode) > 0"  ;
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $setdelverurl="deliverd.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=3";
                $setreturnurl="return.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=3";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
			$returnedDate = ($row['returned_date'] == "NA")?"NA":date_format(date_create($row['returned_date']),"d/m/Y");
			
			//$status = (($row['item_ordered'] - $row['item_returned'])>0)?"Partial Returned":"Full Returned";
			
			if($row['item_ordered'] == $row['item_returned']){$status = "<kbd class=\"orstatus_6\">Full Returned</kbd>";}
			if($row['item_returned'] == 0){$status = "<kbd class=\"orstatus_5\">Delivered</kbd>";}
			if($row['item_returned'] > 0 && $row['item_returned'] < $row['item_ordered']){$status = "<kbd class=\"orstatus_10\">Partial Returned</kbd>";}
			
            $data[] = array(
				"order_id"=> $row['order_id'],//$empQuery,//
				"name"=>$row['name'],
				//"addrs"=>$row['addrs'],
				//"email"=>$row['email'],
				"phone"=>$row['phone'],
				"item_ordered"=>$row['item_ordered'],
				"item_returned"=>$row['item_returned'],
				"returned_date"=>$returnedDate,
				"order_date"=>date_format(date_create($row['order_date']),"d/m/Y"),
				"orderstatus"=>$status,
                "amount"=>number_format($row['amount'],2),                    
                "paymd"=>$row['payment_mood'],
            	//"payst"=>$row['payst'],
            	"agent"=>$row['agentname'],
                //"edit"=>'<a class="btn btn-info btn-xs"  href="'. $setdelverurl.'">Deliverd</a>',
                "return"=>'<a class="btn btn-info btn-xs"  href="'. $setreturnurl.'">Return</a>'
            	);
        } 
    }
    else if($action=="delagent")
    {
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and (`name` like '%".$searchValue."%' or `address` like '%".$searchValue."%' or `contactno` like '%".$searchValue."%'or `email` like '%".$searchValue."%' or `narration` like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT `id`, `name`, `address`, `contactno`, `email`, `narration` FROM `deveryagent` WHERE 1=1  ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
         $empQuery=$strwithoutsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $strwithoutsearchquery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $seturl="deliveryagent.php?res=4&msg='Update Data'&id=".$row['id']."&mod=13";
           $setdelurl="common/delobj.php?obj=deveryagent&ret=delagentList&mod=13&id=".$row['id'];
           $sl=$sl+1;
            $data[] = array(
                    "sl"=>$sl,
                    "name"=>$row['name'],
            		"address"=>$row['address'],
            		"contactno"=>$row['contactno'],
        			"email"=>$row['email'],
    				"narration"=>$row['narration'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'
            	);
        } 
    }
    
    
    else if($action=="glmaster")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (`vouchno` like '%".$searchValue."%' or 

                 `refno`  like '%".$searchValue."%' or `remarks` like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `vouchno`, DATE_FORMAT(`transdt`,'%d/%b/%Y') `transdt`, `refno`,substring(`remarks`,1,50) remarks FROM `glmst` WHERE `status` in('1','A') ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == 'id')
        {
            $columnName=" id ";
            $columnSortOrder=" desc";
        }
        if($columnName == 'transdt')
        {
            $columnName=" transdt ";
            $columnSortOrder=" desc ";
        }
        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="glmaster.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=glmst&ret=glmasterList&mod=7&id=".$row['id'];

            $btns = array(
                array('view','glmaster_view.php','class="show-invoice btn btn-info btn-xs"  title="View GL Voucher"	data-code="'.$row['vouchno'].'"  '),
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "id"=>$sl,

            		"vouchno"=>$row['vouchno'],

            		"transdt"=>$row['transdt'],

            		"refno"=>$row['refno'],

        			"remarks"=>$row['remarks'],

            		"action"=> getGridBtns($btns),
            	);
            	
            $sl++;

        } 

    }
    
    else if($action=="glmapping")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.title like '%".$searchValue."%' or 

                 glnm  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT gl.`id`, b.title business , concat(c.`glnm`, '(', c.`glno`, ')') glnm FROM `glmapping` gl LEFT JOIN glbusiness b ON gl.`buisness` = b.id LEFT JOIN coa c ON gl.`mappedgl` = c.glno WHERE 1  ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="glmapping.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=glmapping&ret=glmappingList&mod=7&id=".$row['id'];

            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "id"=>$sl,

            		"buisness"=>$row['business'],

            		"mappedgl"=>$row['glnm'],

            		"action"=> getGridBtns($btns),
            	);
            	
            $sl++;

        } 

    }
    
    else if($action=="rpt_storeroom_wise_available_stock")

    {
        $brand = $_GET["brand"];
        $cat = $_GET["cat"];
        $store = $_GET["store"];

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (p.barcode like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or p.code  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select p.code,p.name,p.rate,p.barcode,p.image,s.freeqty,b.name store,r.title brand, i.name catagory
                                from chalanstock s join item p on s.product=p.id left join branch b on s.storerome=b.id
                                left join brand r on p.brand=r.id left join itmCat i on p.catagory=i.id
                                where  s.freeqty>0
                                and (p.brand=$brand or $brand = 0) and (p.catagory=$cat or $cat = 0) and (s.storerome=$store or  $store = 0)
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "id"){
            $columnName = "p.id";
        }
        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$seturl="glmapping.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $photo="assets/images/products/300_300/".$row["image"];
            $ph = "<img src = '$photo' width = '50'>";

            

            $data[] = array(

                    "id"=>$sl,

            		"photo"=>$ph,

            		"code"=>$row['code'],

            		"name"=>$row['name'],

            		"barcode"=>$row['barcode'],
            		
            		"brand"=>$row['brand'],
            		
            		"catagory"=>$row['catagory'],
            		
            		"rate"=>number_format($row['rate'], 2),
            		
            		"freeqty"=>$row['freeqty'],
            		
            		"store"=>$row['store'],

            	);
            	
            $sl++;

        } 

    }
    
    else if($action=="rpt_total_invoice")

    {
        $prod = $_GET["product"];
        $org = $_GET["org"];
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and i.invoicedt between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.invoiceno like '%".$searchValue."%' or o.orgcode  like '%".$searchValue."%' or o.name  like '%".$searchValue."%'  or p.name  like '%".$searchValue."%' 
        	                or p.barcode  like '%".$searchValue."%' or p.code  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select i.invoiceno, DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') invoicedt,o.orgcode,o.name customer,p.name product,p.code,p.barcode,d.qty,d.otc,(d.qty*d.otc) amount,d.discountrate,d.discounttot,d.vatrate,d.vat,(d.discounttot+d.vat) total_amount
                                from invoice i left join organization o on  i.organization=o.id left join soitemdetails d on i.soid=d.socode left join item p on  d.productid=p.id
                                 where (d.productid =$prod or $prod = 0 ) and (i.organization =$org or $org = 0)
                                ".$date_qry;

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "id"){
            $columnName = "i.id";
        }
        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$seturl="glmapping.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $data[] = array(

                    "id"=>$sl,

            		"invoiceno"=>$row["invoiceno"],

            		"invoicedt"=>$row['invoicedt'],

            		"orgcode"=>$row['orgcode'],

            		"customer"=>$row['customer'],
            		
            		"code"=>$row['code'],
            		
            		"product"=>$row['product'],
            		
            		"barcode"=>$row['barcode'],
            		
            		"qty"=>$row['qty'],
            		
            		"otc"=>number_format($row['otc'],2),
            		
            		"amount"=>number_format($row['amount'],2),
            		
            		"discountrate"=>$row['discountrate'],
            		
            		"discounttot"=>$row['discounttot'],
            		
            		"vatrate"=>$row['vatrate'],
            		
            		"vat"=>$row['vat'],
            		
            		"total_amount"=>$row['total_amount'],

            	);
            	
            $sl++;

        } 

    }
    
    else if($action=="customer_birthdate")

    {
        $month = $_GET["month"];
        
        if($month != ''){
            $date_qry = " and MONTH(c.dob) = ".$month;
        }
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (org.name like '%".$searchValue."%' or c.name  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT org.name orgname, c.name customernm, DATE_FORMAT(c.`dob`,'%d/%b/%Y') dob, c.phone, c.email, c.area 
                                FROM `contact` c LEFT JOIN organization org ON org.orgcode=c.organization 
                                WHERE c.dob IS NOT null 
                                ".$date_qry;
        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "id"){
            $columnName = "c.dob";
        }
        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$seturl="glmapping.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $data[] = array(

                    "id"=>$sl,
                   
            		"orgname"=>$row["orgname"],

            		"customernm"=>$row['customernm'],

            		"dob"=>$row['dob'],

            		"phone"=>$row['phone'],
            		
            		"email"=>$row['email'],
            		
            		"area"=>$row['area'],

            	);
            	
            $sl++;

        } 

    }
    
    else if($action=="rpt_available_stock_summary")

    {
        $brand = $_GET["brand"];
        $cat = $_GET["cat"];

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (p.barcode like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or p.code  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select p.code,p.name,p.rate,p.barcode,p.image,s.freeqty,r.title brand,i.name catagory
                                from stock s join item p on s.product=p.id 
                                left join brand r on p.brand=r.id left join itmCat i on p.catagory=i.id
                                where  s.freeqty>0
                                and (p.brand=$brand or $brand = 0) and (p.catagory=$cat or $cat = 0)
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "id"){
            $columnName = "p.id";
        }
        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$seturl="glmapping.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $photo="assets/images/products/300_300/".$row["image"];
            $ph = "<img src = '$photo' width = '50'>";

            

            $data[] = array(

                    "id"=>$sl,

            		"photo"=>$ph,

            		"code"=>$row['code'],

            		"name"=>$row['name'],

            		"barcode"=>$row['barcode'],
            		
            		"brand"=>$row['brand'],
            		
            		"catagory"=>$row['catagory'],
            		
            		"rate"=>number_format($row['rate'], 2),
            		
            		"freeqty"=>$row['freeqty'],

            	);
            	
            $sl++;

        } 

    }
    
    else if($action=="rpt_user_sales")

    {
        $emp = $_GET["emp"];

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.invoiceno like '%".$searchValue."%' or o.orgcode  like '%".$searchValue."%' or o.name  like '%".$searchValue."%' or h.hrName  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select i.invoiceno,DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') invoicedt,o.orgcode,o.name customer,i.amount_bdt ,i.paidamount,i.dueamount,h.hrName slperson
                                from invoice i left join organization o on  i.organization=o.id left join hr h on  i.makeby=h.id
                                where (i.makeby=$emp or $emp = 0)
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "id"){
            $columnName = "i.id";
        }
        if($columnName == "invoicedt"){
            $columnName = "i.invoicedt";
        }
        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$seturl="glmapping.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";


            $data[] = array(

                    "id"=>$sl,

            		"invoiceno"=>$row["invoiceno"],

            		"invoicedt"=>$row['invoicedt'],

            		"orgcode"=>$row['orgcode'],

            		"customer"=>$row['customer'],
            		
            		"amount_bdt"=>number_format($row['amount_bdt'], 2),
            		
            		"paidamount"=>number_format($row['paidamount'], 2),
            		
            		"dueamount"=>number_format($row['dueamount'], 2),
            		
            		"slperson"=>$row['slperson'],

            	);
            	
            $sl++;

        } 

    }

    else if($action=="rpt_inv_pay")

    {

      $fd= $_GET['dt_f'];
      $td= $_GET['dt_t'];

      if($fd!=''){ $fdquery=" and p.transdt >='".$fd."' ";}

      if($td!=''){ $tdquery=" and p.transdt <='".$td."' ";}

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.invoiceno like '%".$searchValue."%' or 

                 date_format(i.invoicedt,'%d/%b/%Y')  like '%".$searchValue."%' or i.invyr like '%".$searchValue."%' or i.invoicemonth  like '%".$searchValue."%' or

                i.soid like '%".$searchValue."%'  or o.name like '%".$searchValue."%' or date_format(p.transdt,'%d/%b/%Y') like '%".$searchValue."%' or (case when p.transmode ='W' then 'Wallet' else 'Cash' end) like '%".$searchValue."%' or p.amount like '%".$searchValue."%' or p.remarks like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select  p.id,i.invoiceno,date_format(i.invoicedt,'%d/%b/%Y') invoicedt,i.invyr,i.invoicemonth,i.invoiceamt,i.soid,o.name, date_format(p.transdt,'%d/%b/%Y') transdt

                ,(case when p.transmode ='W' then 'Wallet' else 'Cash' end) transmode  

                ,p.amount,p.remarks

from invoice i, organization o, invoicepayment p

where  i.invoiceno=p.invoicid  and i.organization=o.id ".$fdquery.$tdquery;

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="rpt_invoice_payment.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(

                    "id"=>$i,

                    "invoiceno"=>$row['invoiceno'],

            		"invoicedt"=>$row['invoicedt'],

            		"invyr"=>$row['invyr'],

            		"invoicemonth"=>date("F", strtotime($row['invoicemonth'])),

        			"invoiceamt"=>number_format($row['invoiceamt'],2),

            		"soid"=>$row['soid'],

        			"name"=>$row['name'],

        			"transdt"=>$row['transdt'],

					"transmode"=>$row['transmode'],

				    "amount"=>number_format($row['amount'],2),

				    "remarks"=>$row['remarks'],

            	);

        } 

    }

    else if($action=="rpt_expense")

    {

      $fd= $_GET['dt_f'];

      if($fd!=''){ $fdquery=" and e.trdt >='$fd'";}

      $td= $_GET['dt_t'];

      if($td!=''){ $tdquery=" and e.trdt <='$td'";}

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (date_format(e.trdt,'%d/%b/%Y')  like '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or

                e.amount like '%".$searchValue."%'  or e.narationlike '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        //$strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ";

        $strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration 
                                FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery;

        

        

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "trdt"){
            $columnName = "e.trdt";
        }


         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="rpt_expense.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(

                    "id"=>$i,

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transtype"=>$row['transtype'],

        			"amount"=>number_format($row['amount'],2),

            		"naration"=>$row['naration'],

            	);

        } 

    }
    
    
    else if($action=="rpt_expense_rdl")
    {

      $gllvl= $_GET['gllvl'];
      $glctrl= $_GET['ctrgl'];
      $sessyrf= $_GET['pyr'];
      $sessyrt=$sessyrf+1;
      $ctrlcond='';
      if($glctrl!='')
      {
       $ctrlcond=" and c.ctlgl='$glctrl' ";
      }
      
        $searchQuery = " "; 

        if($searchValue != '')
        {

        	$searchQuery = " and (date_format(e.trdt,'%d/%b/%Y')  like '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or

                e.amount like '%".$searchValue."%'  or e.narationlike '%".$searchValue."%'  ) ";
        }

        ## Total number of records without filtering   #c.`id`,

       //$strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery;
         
        $strwithoutsearchquery="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece 
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.oflag='N' and c.lvl=$gllvl  $ctrlcond
order by c.`glno`";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery;//.$searchQuery;

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $strwithoutsearchquery);
        $data = array();
        $i=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $plvl=$row['lvl']+1; 
		    $pctrl=$row['ctlgl'];
		    $pisposted=$row['isposted'];
           
            $seturl="rpt_expense_rdl.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl&pyr=$sessyrf";
            if($pisposted=="P")
            {
                $lgnm=$row['glnm'];
            }
            else
            {
                $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row['glnm'].'</a>';
            }
            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(
                    "id"=>$i,//$strwithoutsearchquery1,//
                    "glno"=>$row['glno'],
            		"glnm"=>$lgnm,
            		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
        			"tot"=>number_format($row['jun'],2),
        			"jul"=>number_format($row['jul'],2),
            		"aug"=>number_format(($row['aug']-$row['jul']),2),
            		"sep"=>number_format(($row['sep']-$row['aug']),2),
            		"oct"=>number_format(($row['oct']-$row['sep']),2),
            		"nov"=>number_format(($row['nov']-$row['oct']),2),
            		"dec"=>number_format(($row['dece']-$row['nov']),2),
            		"jan"=>number_format(($row['jan']-$row['dece']),2),
            		"feb"=>number_format(($row['feb']-$row['jan']),2),
            		"mar"=>number_format(($row['mar']-$row['feb']),2),
            		"apr"=>number_format(($row['apr']-$row['mar']),2),
        			"may"=>number_format(($row['may']-$row['apr']),2),
    				"jun"=>number_format(($row['jun']-$row['may']),2),
            	);
        } 

    }
    
    else if($action=="rpt_expense_rdl_all")
    {

      $gllvl= $_GET['gllvl'];
      $glctrl= $_GET['ctrgl'];
       $sessyrf= $_GET['pyr'];
      $sessyrt=$sessyrf+1;
      
      $ctrlcond='';
      if($glctrl!='')
      {
       $ctrlcond=" and c.ctlgl='$glctrl' ";
      }
      
        $searchQuery = " "; 

        if($searchValue != '')
        {

        	$searchQuery = " and (date_format(e.trdt,'%d/%b/%Y')  like '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or

                e.amount like '%".$searchValue."%'  or e.narationlike '%".$searchValue."%'  ) ";
        }

        ## Total number of records without filtering   #c.`id`,

       //$strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery;
         
        $strwithoutsearchquery="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=1 and c.oflag='N' 
order by c.`glno`";
//$gllvl  $ctrlcond
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery;//.$searchQuery;

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $strwithoutsearchquery);
        $data = array();
        $i=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $plvl=$row['lvl']+1; 
		    $pctrl=$row['ctlgl']; 
		    $pisposted=$row['isposted'];
           
            $seturl="rpt_expense_rdl_detail.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
            if($pisposted=="P")
            {
                $lgnm=$row['glnm'];
            }
            else
            {
                $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row['glnm'].'</a>';
            }
            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(
                    "id"=>$i,//$strwithoutsearchquery,// 
                    "glno"=>$row['glno'],
            		"glnm"=>$lgnm,
            		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
        			"tot"=>number_format($row['jun'],2),
        			"jul"=>number_format($row['jul'],2),
            		"aug"=>number_format(($row['aug']-$row['jul']),2),
            		"sep"=>number_format(($row['sep']-$row['aug']),2),
            		"oct"=>number_format(($row['oct']-$row['sep']),2),
            		"nov"=>number_format(($row['nov']-$row['oct']),2),
            		"dec"=>number_format(($row['dece']-$row['nov']),2),
            		"jan"=>number_format(($row['jan']-$row['dece']),2),
            		"feb"=>number_format(($row['feb']-$row['jan']),2),
            		"mar"=>number_format(($row['mar']-$row['feb']),2),
            		"apr"=>number_format(($row['apr']-$row['mar']),2),
        			"may"=>number_format(($row['may']-$row['apr']),2),
    				"jun"=>number_format(($row['jun']-$row['may']),2),
            	);
            	
            	
                
                //Level 2
                $qry1 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=2 and c.ctlgl=$pctrl  and c.oflag='N' 
order by c.`glno`";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                $result1 = mysqli_query($con, $qry1);
                while ($row1 = mysqli_fetch_assoc($result1)) 
                {
                    $plvl=$row1['lvl']+1; 
        		    $pctrl1=$row1['ctlgl'];
        		    $pisposted=$row1['isposted'];
                   
                    $seturl="rpt_expense_rdl.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                    if($pisposted=="P")
                    {
                        $lgnm=$row1['glnm'];
                    }
                    else
                    {
                        $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row1['glnm'].'</a>';
                    }
                    //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
        
                    $i++;
        
                    $data[] = array(
                            "id"=>$i,//$strwithoutsearchquery1,//
                            "glno"=>$row1['glno'],
                    		"glnm"=>"&nbsp; &nbsp; &nbsp;".$lgnm,
                    		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                			"tot"=>number_format($row1['jun'],2),
                			"jul"=>number_format($row1['jul'],2),
                    		"aug"=>number_format(($row1['aug']-$row1['jul']),2),
                    		"sep"=>number_format(($row1['sep']-$row1['aug']),2),
                    		"oct"=>number_format(($row1['oct']-$row1['sep']),2),
                    		"nov"=>number_format(($row1['nov']-$row1['oct']),2),
                    		"dec"=>number_format(($row1['dece']-$row1['nov']),2),
                    		"jan"=>number_format(($row1['jan']-$row1['dece']),2),
                    		"feb"=>number_format(($row1['feb']-$row1['jan']),2),
                    		"mar"=>number_format(($row1['mar']-$row1['feb']),2),
                    		"apr"=>number_format(($row1['apr']-$row1['mar']),2),
                			"may"=>number_format(($row1['may']-$row1['apr']),2),
            				"jun"=>number_format(($row1['jun']-$row1['may']),2),
                    	);
            	
                   
                    
                    //Level 3
                    $qry2 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=3 and c.ctlgl=$pctrl1  and c.oflag='N' 
order by c.`glno` ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                    $result2 = mysqli_query($con, $qry2);
                    while ($row2 = mysqli_fetch_assoc($result2)) 
                    {
                        $plvl=$row2['lvl']+1; 
            		    $pctrl2=$row2['ctlgl'];
            		    $pisposted=$row2['isposted'];
                       
                        $seturl="rpt_expense_rdl.php?res=4&msg='Update Data'&id=".$row2['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                        if($pisposted=="P")
                        {
                            $lgnm=$row2['glnm'];
                        }
                        else
                        {
                            $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row2['glnm'].'</a>';
                        }
                        //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
            
                        $i++;
            
                        $data[] = array(
                                "id"=>$i,//$strwithoutsearchquery1,//
                                "glno"=>$row2['glno'],
                        		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;".$lgnm,
                        		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                    			"tot"=>number_format($row2['jun'],2),
                    			"jul"=>number_format($row2['jul'],2),
                        		"aug"=>number_format(($row2['aug']-$row2['jul']),2),
                        		"sep"=>number_format(($row2['sep']-$row2['aug']),2),
                        		"oct"=>number_format(($row2['oct']-$row2['sep']),2), 
                        		"nov"=>number_format(($row2['nov']-$row2['oct']),2),
                        		"dec"=>number_format(($row2['dece']-$row2['nov']),2),
                        		"jan"=>number_format(($row2['jan']-$row2['dece']),2),
                        		"feb"=>number_format(($row2['feb']-$row2['jan']),2),
                        		"mar"=>number_format(($row2['mar']-$row2['feb']),2),
                        		"apr"=>number_format(($row2['apr']-$row2['mar']),2),
                    			"may"=>number_format(($row2['may']-$row2['apr']),2),
                				"jun"=>number_format(($row2['jun']-$row2['may']),2),
                        	);
                	
                       
                        
                        //Level 4
                        $qry3 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=4 and c.ctlgl=$pctrl2 and c.oflag='N' 
order by c.`glno`";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                        $result3 = mysqli_query($con, $qry3);
                        while ($row3 = mysqli_fetch_assoc($result3)) 
                        {
                            $plvl=$row3['lvl']+1; 
                		    $pctrl3=$row3['ctlgl'];
                		    $pisposted=$row3['isposted'];
                           
                            $seturl="rpt_expense_rdl.php?res=4&msg='Update Data'&id=".$row3['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                            if($pisposted=="P")
                            {
                                $lgnm=$row3['glnm'];
                            }
                            else
                            {
                                $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row3['glnm'].'</a>';
                            }
                            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
                
                            $i++;
                
                            $data[] = array(
                                    "id"=>$i,//$strwithoutsearchquery1,//
                                    "glno"=>$row3['glno'],
                            		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;".$lgnm,
                            		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                        			"tot"=>number_format($row3['jun'],2),
                        			"jul"=>number_format($row3['jul'],2),
                            		"aug"=>number_format(($row3['aug']-$row3['jul']),2),
                            		"sep"=>number_format(($row3['sep']-$row3['aug']),2),
                            		"oct"=>number_format(($row3['oct']-$row3['sep']),2),
                            		"nov"=>number_format(($row3['nov']-$row3['oct']),2),
                            		"dec"=>number_format(($row3['dece']-$row3['nov']),2),
                            		"jan"=>number_format(($row3['jan']-$row3['dece']),2),
                            		"feb"=>number_format(($row3['feb']-$row3['jan']),2),
                            		"mar"=>number_format(($row3['mar']-$row3['feb']),2),
                            		"apr"=>number_format(($row3['apr']-$row3['mar']),2),
                        			"may"=>number_format(($row3['may']-$row3['apr']),2),
                    				"jun"=>number_format(($row3['jun']-$row3['may']),2),
                            	);
                            
                            //Level 5
                            $qry4 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=5 and c.ctlgl=$pctrl3 and c.oflag='N' 
order by c.`glno`";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                            $result4 = mysqli_query($con, $qry4);
                            while ($row4 = mysqli_fetch_assoc($result4)) 
                            {
                                $plvl=$row4['lvl']+1; 
                    		    $pctrl4=$row4['ctlgl'];
                    		    $pisposted=$row4['isposted'];
                               
                                $seturl="rpt_expense_rdl.php?res=4&msg='Update Data'&id=".$row4['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                                if($pisposted=="P")
                                {
                                    $lgnm=$row4['glnm'];
                                }
                                else
                                {
                                    $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row4['glnm'].'</a>';
                                }
                                //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
                    
                                $i++;
                    
                                $data[] = array(
                                        "id"=>$i,//$strwithoutsearchquery1,//
                                        "glno"=>$row4['glno'],
                                		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;".$lgnm,
                                		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                            			"tot"=>number_format($row4['jun'],2),
                            			"jul"=>number_format($row4['jul'],2),
                                		"aug"=>number_format(($row4['aug']-$row4['jul']),2),
                                		"sep"=>number_format(($row4['sep']-$row4['aug']),2),
                                		"oct"=>number_format(($row4['oct']-$row4['sep']),2),
                                		"nov"=>number_format(($row4['nov']-$row4['oct']),2),
                                		"dec"=>number_format(($row4['dece']-$row4['nov']),2),
                                		"jan"=>number_format(($row4['jan']-$row4['dece']),2),
                                		"feb"=>number_format(($row4['feb']-$row4['jan']),2),
                                		"mar"=>number_format(($row4['mar']-$row4['feb']),2),
                                		"apr"=>number_format(($row4['apr']-$row4['mar']),2),
                            			"may"=>number_format(($row4['may']-$row4['apr']),2),
                        				"jun"=>number_format(($row4['jun']-$row4['may']),2),
                                	);
                                
                            }
                        }
                    }
                }
                	
        } 

    }
    else if($action=="rpt_expense_rdl_fin")
    {

      $gllvl= $_GET['gllvl'];
      $glctrl= $_GET['ctrgl'];
      $sessyrf= $_GET['pyr'];
      $sessyrt=$sessyrf+1;
      $ctrlcond='';
      if($glctrl!='')
      {
       $ctrlcond=" and c.ctlgl='$glctrl' ";
      }
      
        $searchQuery = " "; 

        if($searchValue != '')
        {

        	$searchQuery = " and (date_format(e.trdt,'%d/%b/%Y')  like '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or

                e.amount like '%".$searchValue."%'  or e.narationlike '%".$searchValue."%'  ) ";
        }

        ## Total number of records without filtering   #c.`id`,

       //$strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery;
         
        $strwithoutsearchquery="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece 
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=$gllvl  $ctrlcond
order by c.`glno`";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery;//.$searchQuery;

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $strwithoutsearchquery);
        $data = array();
        $i=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $plvl=$row['lvl']+1; 
		    $pctrl=$row['ctlgl'];
		    $pisposted=$row['isposted'];
           
            $seturl="rpt_expense_rdl_fin.php?res=4&msg='Update Data'&id=".$row['id']."&mod=17&gllvl=$plvl&ctrgl=$pctrl&pyr=$sessyrf";
            if($pisposted=="P")
            {
                $lgnm=$row['glnm'];
            }
            else
            {
                $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row['glnm'].'</a>';
            }
            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(
                    "id"=>$i,//$strwithoutsearchquery1,//
                    "glno"=>$row['glno'],
            		"glnm"=>$lgnm,
            		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
        			"tot"=>number_format($row['jun'],2),
        			"jul"=>number_format($row['jul'],2),
            		"aug"=>number_format(($row['aug']-$row['jul']),2),
            		"sep"=>number_format(($row['sep']-$row['aug']),2),
            		"oct"=>number_format(($row['oct']-$row['sep']),2),
            		"nov"=>number_format(($row['nov']-$row['oct']),2),
            		"dec"=>number_format(($row['dece']-$row['nov']),2),
            		"jan"=>number_format(($row['jan']-$row['dece']),2),
            		"feb"=>number_format(($row['feb']-$row['jan']),2),
            		"mar"=>number_format(($row['mar']-$row['feb']),2),
            		"apr"=>number_format(($row['apr']-$row['mar']),2),
        			"may"=>number_format(($row['may']-$row['apr']),2),
    				"jun"=>number_format(($row['jun']-$row['may']),2),
            	);
        } 

    }
    else if($action=="rpt_expense_rdl_all_fin")
    {

      $gllvl= $_GET['gllvl'];
      $glctrl= $_GET['ctrgl'];
       $sessyrf= $_GET['pyr'];
      $sessyrt=$sessyrf+1;
      
      $ctrlcond='';
      if($glctrl!='')
      {
       $ctrlcond=" and c.ctlgl='$glctrl' ";
      }
      
        $searchQuery = " "; 

        if($searchValue != '')
        {

        	$searchQuery = " and (date_format(e.trdt,'%d/%b/%Y')  like '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or

                e.amount like '%".$searchValue."%'  or e.narationlike '%".$searchValue."%'  ) ";
        }

        ## Total number of records without filtering   #c.`id`,

       //$strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%b/%Y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery;
         
        $strwithoutsearchquery="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=1
order by c.`glno`";
//$gllvl  $ctrlcond
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery;//.$searchQuery;

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $strwithoutsearchquery);
        $data = array();
        $i=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $plvl=$row['lvl']+1; 
		    $pctrl=$row['ctlgl']; 
		    $pisposted=$row['isposted'];
           
            $seturl="rpt_expense_rdl_detail_fin.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
            if($pisposted=="P")
            {
                $lgnm=$row['glnm'];
            }
            else
            {
                $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row['glnm'].'</a>';
            }
            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(
                    "id"=>$i,//$strwithoutsearchquery,// 
                    "glno"=>$row['glno'],
            		"glnm"=>$lgnm,
            		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
        			"tot"=>number_format($row['jun'],2),
        			"jul"=>number_format($row['jul'],2),
            		"aug"=>number_format(($row['aug']-$row['jul']),2),
            		"sep"=>number_format(($row['sep']-$row['aug']),2),
            		"oct"=>number_format(($row['oct']-$row['sep']),2),
            		"nov"=>number_format(($row['nov']-$row['oct']),2),
            		"dec"=>number_format(($row['dece']-$row['nov']),2),
            		"jan"=>number_format(($row['jan']-$row['dece']),2),
            		"feb"=>number_format(($row['feb']-$row['jan']),2),
            		"mar"=>number_format(($row['mar']-$row['feb']),2),
            		"apr"=>number_format(($row['apr']-$row['mar']),2),
        			"may"=>number_format(($row['may']-$row['apr']),2),
    				"jun"=>number_format(($row['jun']-$row['may']),2),
            	);
            	
            	
                
                //Level 2
                $qry1 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=2 and c.ctlgl=$pctrl 
order by c.`glno`";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                $result1 = mysqli_query($con, $qry1);
                while ($row1 = mysqli_fetch_assoc($result1)) 
                {
                    $plvl=$row1['lvl']+1; 
        		    $pctrl1=$row1['ctlgl'];
        		    $pisposted=$row1['isposted'];
                   
                    $seturl="rpt_expense_rdl_detail_fin.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                    if($pisposted=="P")
                    {
                        $lgnm=$row1['glnm'];
                    }
                    else
                    {
                        $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row1['glnm'].'</a>';
                    }
                    //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
        
                    $i++;
        
                    $data[] = array(
                            "id"=>$i,//$strwithoutsearchquery1,//
                            "glno"=>$row1['glno'],
                    		"glnm"=>"&nbsp; &nbsp; &nbsp;".$lgnm,
                    		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                			"tot"=>number_format($row1['jun'],2),
                			"jul"=>number_format($row1['jul'],2),
                    		"aug"=>number_format(($row1['aug']-$row1['jul']),2),
                    		"sep"=>number_format(($row1['sep']-$row1['aug']),2),
                    		"oct"=>number_format(($row1['oct']-$row1['sep']),2),
                    		"nov"=>number_format(($row1['nov']-$row1['oct']),2),
                    		"dec"=>number_format(($row1['dece']-$row1['nov']),2),
                    		"jan"=>number_format(($row1['jan']-$row1['dece']),2),
                    		"feb"=>number_format(($row1['feb']-$row1['jan']),2),
                    		"mar"=>number_format(($row1['mar']-$row1['feb']),2),
                    		"apr"=>number_format(($row1['apr']-$row1['mar']),2),
                			"may"=>number_format(($row1['may']-$row1['apr']),2),
            				"jun"=>number_format(($row1['jun']-$row1['may']),2),
                    	);
            	
                   
                    
                    //Level 3
                    $qry2 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=3 and c.ctlgl=$pctrl1 
order by c.`glno` ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                    $result2 = mysqli_query($con, $qry2);
                    while ($row2 = mysqli_fetch_assoc($result2)) 
                    {
                        $plvl=$row2['lvl']+1; 
            		    $pctrl2=$row2['ctlgl'];
            		    $pisposted=$row2['isposted'];
                       
                        $seturl="rpt_expense_rdl_detail_fin.php?res=4&msg='Update Data'&id=".$row2['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                        if($pisposted=="P")
                        {
                            $lgnm=$row2['glnm'];
                        }
                        else
                        {
                            $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row2['glnm'].'</a>';
                        }
                        //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
            
                        $i++;
            
                        $data[] = array(
                                "id"=>$i,//$strwithoutsearchquery1,//
                                "glno"=>$row2['glno'],
                        		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;".$lgnm,
                        		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                    			"tot"=>number_format($row2['jun'],2),
                    			"jul"=>number_format($row2['jul'],2),
                        		"aug"=>number_format(($row2['aug']-$row2['jul']),2),
                        		"sep"=>number_format(($row2['sep']-$row2['aug']),2),
                        		"oct"=>number_format(($row2['oct']-$row2['sep']),2), 
                        		"nov"=>number_format(($row2['nov']-$row2['oct']),2),
                        		"dec"=>number_format(($row2['dece']-$row2['nov']),2),
                        		"jan"=>number_format(($row2['jan']-$row2['dece']),2),
                        		"feb"=>number_format(($row2['feb']-$row2['jan']),2),
                        		"mar"=>number_format(($row2['mar']-$row2['feb']),2),
                        		"apr"=>number_format(($row2['apr']-$row2['mar']),2),
                    			"may"=>number_format(($row2['may']-$row2['apr']),2),
                				"jun"=>number_format(($row2['jun']-$row2['may']),2),
                        	);
                	
                       
                        
                        //Level 4
                        $qry3 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=4 and c.ctlgl=$pctrl2  
order by c.`glno`";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                        $result3 = mysqli_query($con, $qry3);
                        while ($row3 = mysqli_fetch_assoc($result3)) 
                        {
                            $plvl=$row3['lvl']+1; 
                		    $pctrl3=$row3['ctlgl'];
                		    $pisposted=$row3['isposted'];
                           
                            $seturl="rpt_expense_rdl_detail_fin.php?res=4&msg='Update Data'&id=".$row3['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                            if($pisposted=="P")
                            {
                                $lgnm=$row3['glnm'];
                            }
                            else
                            {
                                $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row3['glnm'].'</a>';
                            }
                            //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
                
                            $i++;
                
                            $data[] = array(
                                    "id"=>$i,//$strwithoutsearchquery1,//
                                    "glno"=>$row3['glno'],
                            		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;".$lgnm,
                            		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                        			"tot"=>number_format($row3['jun'],2),
                        			"jul"=>number_format($row3['jul'],2),
                            		"aug"=>number_format(($row3['aug']-$row3['jul']),2),
                            		"sep"=>number_format(($row3['sep']-$row3['aug']),2),
                            		"oct"=>number_format(($row3['oct']-$row3['sep']),2),
                            		"nov"=>number_format(($row3['nov']-$row3['oct']),2),
                            		"dec"=>number_format(($row3['dece']-$row3['nov']),2),
                            		"jan"=>number_format(($row3['jan']-$row3['dece']),2),
                            		"feb"=>number_format(($row3['feb']-$row3['jan']),2),
                            		"mar"=>number_format(($row3['mar']-$row3['feb']),2),
                            		"apr"=>number_format(($row3['apr']-$row3['mar']),2),
                        			"may"=>number_format(($row3['may']-$row3['apr']),2),
                    				"jun"=>number_format(($row3['jun']-$row3['may']),2),
                            	);
                            
                            //Level 5
                            $qry4 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='7'),0)jul
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='8'),0)aug
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='9'),0)sep
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='10'),0)oct
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='11'),0)nov
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrf' and  m.mn='12'),0)dece
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='1'),0)jan
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='2'),0)feb
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='3'),0)mar
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='4'),0)apr
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='5'),0)may
,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='$sessyrt' and  m.mn='6'),0)jun
FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=5 and c.ctlgl=$pctrl3
order by c.`glno`";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                            $result4 = mysqli_query($con, $qry4);
                            while ($row4 = mysqli_fetch_assoc($result4)) 
                            {
                                $plvl=$row4['lvl']+1; 
                    		    $pctrl4=$row4['ctlgl'];
                    		    $pisposted=$row4['isposted'];
                               
                                $seturl="rpt_expense_rdl_detail_fin.php?res=4&msg='Update Data'&id=".$row4['id']."&mod=7&gllvl=$plvl&ctrgl=$pctrl";
                                if($pisposted=="P")
                                {
                                    $lgnm=$row4['glnm'];
                                }
                                else
                                {
                                    $lgnm='<a class="btn btn-info btn-xs"  href="'. $seturl.'">'.$row4['glnm'].'</a>';
                                }
                                //$setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
                    
                                $i++;
                    
                                $data[] = array(
                                        "id"=>$i,//$strwithoutsearchquery1,//
                                        "glno"=>$row4['glno'],
                                		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;".$lgnm,
                                		//"tot"=>number_format($row['apr']+$row['jul']+$row['aug']+$row['sep']+$row['oct']+$row['nov']+$row['dece']+$row['jan']+$row['feb']+$row['mar']+$row['apr']+$row['may']+$row['jun'],2),
                            			"tot"=>number_format($row4['jun'],2),
                            			"jul"=>number_format($row4['jul'],2),
                                		"aug"=>number_format(($row4['aug']-$row4['jul']),2),
                                		"sep"=>number_format(($row4['sep']-$row4['aug']),2),
                                		"oct"=>number_format(($row4['oct']-$row4['sep']),2),
                                		"nov"=>number_format(($row4['nov']-$row4['oct']),2),
                                		"dec"=>number_format(($row4['dece']-$row4['nov']),2),
                                		"jan"=>number_format(($row4['jan']-$row4['dece']),2),
                                		"feb"=>number_format(($row4['feb']-$row4['jan']),2),
                                		"mar"=>number_format(($row4['mar']-$row4['feb']),2),
                                		"apr"=>number_format(($row4['apr']-$row4['mar']),2),
                            			"may"=>number_format(($row4['may']-$row4['apr']),2),
                        				"jun"=>number_format(($row4['jun']-$row4['may']),2),
                                	);
                                
                            }
                        }
                    }
                }
                	
        } 

    }

    
    
    else if($action=="rpt_acc_daily_trans")

    {

      $fdt= $_GET['dt_f'];

      $tdt= $_GET['dt_t'];
      
      if($fdt != ''){
            $date_qry = " and m.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
        }else{
            $date_qry = "";
        }

      $fglno = $_GET["fglno"]; if($fglno == '') $fglno = 0;

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`vouchno` like '%".$searchValue."%' or 

                 concat(c.`glnm`, '(', c.`glno`, ')')  like '%".$searchValue."%' or a.remarks like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

		
		
                $strwithoutsearchquery="SELECT a.id,DATE_FORMAT( m.`transdt`,'%d/%b/%Y') `entrydate`, a.`remarks`, a.`vouchno`, concat(c.`glnm`, '(', c.`glno`, ')') glnm, a.`dr_cr`, a.`amount`  
                                        ,h.hrName makeusr,h1.hrName checkusr,h2.hrName apprvusr,m.remarks narr
FROM glmst m join `gldlt` a on m.vouchno=a.vouchno  LEFT JOIN coa c ON a.`glac` = c.glno 
left join hr h on m.entryby=h.id left join hr h1 on m.checkby=h1.id left join hr h2 on m.approvedby=h2.id 
                                        where m.isfinancial in('0','A') and (a.glac = '".$fglno."' or '".$fglno."' = '0') $date_qry ";
                                      
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "a.id";
            // $columnSortOrder="desc";
        }
        if($columnName == "entrydate"){
            $columnName = "m.transdt";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName."  ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);
        
   

        $data = array();

        $i=0;
        $drtot = 0;
        $crtot = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            if($row["dr_cr"] == 'C'){
                $dr = $row["amount"];
                $cr = '';
                $drtot += $dr;
            }else{
                $cr = $row["amount"];
                $dr = '';
                $crtot += $cr;
            }
            
            if($dr == 0) $dr = '';
            if($cr == 0) $cr = '';

            $i++;

            $data[] = array(

                    "id"=>$i,

                    "entrydate"=>$row['entrydate'],

            		"remarks"=>$row['remarks'],

            		"vouchno"=>$row['vouchno'],

                    "glnm"=>$row['glnm'],

            		"dr"=>number_format($dr,2),

        			"cr"=>number_format($cr,2),
        			"narr"=>$row['narr'],
        			"maker"=>$row['makeusr'],
        			"checker"=>$row['checkusr'],
        			"apprvr"=>$row['apprvusr'],

            	);
            	

        } 
        
        array_push($total, number_format($drtot,2));
        array_push($total, number_format($crtot,2));

    }
    else if($action=="rpt_product")

    {

      $fdt= $_GET['fdt'];

      $tdt= $_GET['tdt'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`soid` like '%".$searchValue."%' or 

                 c.name  like '%".$searchValue."%' ) ";

        }

		if($fdt && $tdt){
			$dtstr = "where date( `invoicedt`) between STR_TO_DATE('".$fdt."','%Y-%m-%d') and STR_TO_DATE('".$tdt."','%Y-%m-%d')";
		}else{
			$dtstr = "";
		}
				
		
		
        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select DATE_FORMAT(a.`invoicedt`,'%d/%b/%Y') invoicedt,a.soid,c.name product,b.qty quantity,b.otc rate,(b.qty*b.otc) revenue,(b.cost*b.qty) cost,b.vat,b.ait,
                                        COALESCE(((b.qty*b.otc)-(b.cost*b.qty)),0) margin
                                        from invoice a left join soitemdetails b on a.soid=b.socode left JOIN item c on b.productid=c.id ".$dtstr;

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "a.id";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totrev = 0;
        $totcost = 0;
        $totvat = 0;
        $totait = 0;
        $totmargin = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totrev += $row["revenue"];
            $totcost += $row["cost"];
            $totvat += $row["vat"];
            $totait += $row["ait"];
            $totmargin += $row["margin"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "invoicedt"=>$row['invoicedt'],

            		"soid"=>$row['soid'],

            		"product"=>$row['product'],

                    "quantity"=>$row['quantity'],

            		"rate"=>number_format($row["rate"],2),

        			"revenue"=>number_format($row["revenue"],2),
        			
        			"cost"=>number_format($row["cost"],2),
        			
        			"vat"=>number_format($row["vat"],2),
        			
        			"ait"=>number_format($row["ait"],2),
        			
        			"margin"=>number_format($row["margin"],2),

            	);
            	

        } 
        
        array_push($total, number_format($totrev,2));
        array_push($total, number_format($totcost,2));
        array_push($total, number_format($totvat,2));
        array_push($total, number_format($totait,2));
        array_push($total, number_format($totmargin,2));

    }
    
    else if($action=="rpt_qty"){

		$fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and c.orderdate between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
		
		
		
$oid = $_REQUEST["oid"];
$pid = $_REQUEST["pid"];
$oid_str = "";
$pid_str = "";
		
if($oid){$oid_str = "and c.socode = '".$oid."'";}else{$oid_str = "";}
if($pid){$pid_str = "and a.id = ".$pid;}else{$pid_str = "";}	
		
      $fdt= $_GET['fdt'];

      $tdt= $_GET['tdt'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (
				c.`socode` like '%".$searchValue."%' or 
                a.name  like '%".$searchValue."%' or
				a.`code` like '%".$searchValue."%' 
				) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select c.socode,date_format(c.`orderdate`,'%d/%b/%Y') orderdate,b.productid,a.id pid,a.name product,a.code,a.id pid, a.image,a.barcode barcode, o.name customer,o.contactno,b.qty orderqty
                 ,d.freeqty availableQty,(case WHEN d.freeqty<=0 then b.qty else (b.qty-d.freeqty) end)requiredQty
                from  soitem c 
				left join soitemdetails b on b.socode=c.socode 
				left join item a on b.productid=a.id 
				left join stock d on a.id=d.product
                left join organization o on o.id=c.organization
                where c.orderstatus in(2,3,4,11) and b.backorderedqty>0  ".$oid_str." ".$pid_str.$date_qry;

	//left join chalanstock cstk on a.id=cstk.product        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "photo"){
            $columnName = "c.id";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totorderqty = 0;
        $totorderrate = 0;
        $totsellamount = 0;
        $totavailqty = 0;
        $totcostrate = 0;
        $totreqqty = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totorderqty += $row["orderqty"];
            $totorderrate += $row["orderRate"];
            $totsellamount += $row["sellamount"];
            $totavailqty += $row["availableQty"];
            $totcostrate += $row["CostRate"];
            $totreqqty += $row["requiredQty"];
            
            $photo=$rootpath."/assets/images/products/300_300/".$row["image"];
                if (file_exists($photo)) {
        		    $photo="assets/images/products/300_300/".$row["image"];
        		}else{
        			$photo="assets/images/products/300_300/placeholder.png";
        		}
            
            $i++;

            $data[] = array(

                    "id"=>$i,
                    "socode"=>$row['socode'],
					"barcode"=>$row['barcode'],
            		"orderdate"=>$row['orderdate'],
            		"name"=>$row['customer'],
                    "contactno"=>$row['contactno'],
                    "photo"=>'<img src='.$photo.' width="50" height="50">',
                    "product"=>$row['product']." (".$row['code'].")",
                    "orderqty"=>number_format($row['orderqty'],0),
            		"availableQty"=>number_format($row["availableQty"],0),
        			"requiredQty"=>number_format($row["requiredQty"],0)

            	);
            	
        $pjoto="";
        } 
        
        array_push($total, number_format($totorderqty,2));
        array_push($total, number_format($totavailqty,2));
        array_push($total, number_format($totreqqty,2));

    }
    
    else if($action=="rpt_revenue_detail")

    {

      $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and date( `invoicedt`) between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`soid` like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select DATE_FORMAT(a.invoicedt, '%d/%b/%Y') invoicedt,a.soid,sum(b.qty*b.otc) revenue,sum(b.cost*b.qty) cost,sum(b.vat)vat,sum(b.ait) ait,c.deliveryamt delivarycost,
                                        sum(COALESCE(((b.qty*b.otc)-(b.cost*b.qty)),0)) margin
                                        from invoice a left join soitem c on a.soid=c.socode left join soitemdetails b on b.socode=c.socode
                                        where  1=1
                                        ".$date_qry;

        
                $qryforrec = $strwithoutsearchquery." group by a.invoicedt,a.soid,c.deliveryamt";
        $sel = mysqli_query($con,$qryforrec);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery." group by a.invoicedt,a.soid,c.deliveryamt ".$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "a.id";
        }
        if($columnName == "invoicedt"){
            $columnName = "a.invoicedt";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." group by a.invoicedt,a.soid,c.deliveryamt "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totrev = 0;
        $totcost = 0;
        $totvat = 0;
        $totait = 0;
        $totmargin = 0;
        $totdelicost = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totrev += $row["revenue"];
            $totcost += $row["cost"];
            $totvat += $row["vat"];
            $totait += $row["ait"];
            $totmargin += $row["margin"];
            $totdelicost += $row["delivarycost"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "invoicedt"=>$row['invoicedt'],

            		"soid"=>$row['soid'],

            		"rate"=>number_format($row["rate"],2),

        			"revenue"=>number_format($row["revenue"],2),
        			
        			"cost"=>number_format($row["cost"],2),
        			
        			"vat"=>number_format($row["vat"],2),
        			
        			"ait"=>number_format($row["ait"],2),
        			
        			"deliverycost"=>number_format($row["delivarycost"],2),
        			
        			"margin"=>number_format($row["margin"],2),

            	);
            	

        } 
        
        array_push($total, number_format($totrev,2));
        array_push($total, number_format($totcost,2));
        array_push($total, number_format($totvat,2));
        array_push($total, number_format($totait,2));
        array_push($total, number_format($totdelicost,2));
        array_push($total, number_format($totmargin,2));

    }
    
    else if($action=="rpt_sold_stock")

    {
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and s.orderdate between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
      
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";;

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`,
                                        concat(e.firstname,'',e.lastname) `hrName` , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount` 
                                        FROM `soitem` s left join `contacttype` tp on s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` 
                                        left join `organization` o on o.`orgcode`=c.organization left join `hr` h on o.`salesperson`=h.`id` 
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id` 
                                        left join employee e1 on h1.`emp_id`=e1.`employeecode` left join orderstatus st on s.orderstatus=st.id 
                                        WHERE s.orderstatus=3
                                        ".$date_qry;

        
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "s.id";
        }
        if($columnName == "orderdate"){
            $columnName = "s.orderdate";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totamount = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totamount += $row["amount"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "socode"=>$row['socode'],

            		"srctype"=>$row['srctype'],

            		"customer"=>$row["customer"],

        			"organization"=>$row["organization"],
        			
        			"orderdate"=>$row["orderdate"],
        			
        			"hrName"=>$row["hrName"],
        			
        			"poc"=>$row["poc"],
        			
        			"stnm"=>$row["stnm"],
        			
        			"amount"=>number_format($row["amount"],2),

            	);
            	

        } 
        
        array_push($total, number_format($totamount,2));
        

    }
    
    else if($action=="rpt_booked_order")

    {

      
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`,
                                        concat(e.firstname,'',e.lastname) `hrName` , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount` 
                                        FROM `soitem` s left join `contacttype` tp on s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` 
                                        left join `organization` o on o.`orgcode`=c.organization left join `hr` h on o.`salesperson`=h.`id` 
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id` 
                                        left join employee e1 on h1.`emp_id`=e1.`employeecode` left join orderstatus st on s.orderstatus=st.id 
                                        WHERE s.orderstatus=9
                                        ";

        
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totamount = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totamount += $row["amount"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "socode"=>$row['socode'],

            		"srctype"=>$row['srctype'],

            		"customer"=>$row["customer"],

        			"organization"=>$row["organization"],
        			
        			"orderdate"=>$row["orderdate"],
        			
        			"hrName"=>$row["hrName"],
        			
        			"poc"=>$row["poc"],
        			
        			"stnm"=>$row["stnm"],
        			
        			"amount"=>number_format($row["amount"],2),

            	);
            	

        } 
        
        array_push($total, number_format($totamount,2));
        

    }
    
    else if($action=="rpt_customer_wise_salse")

    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and s.`orderdate` between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
        
        $organization = $_GET["filterorg"];
        if($organization == '') $organization = 0;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
                                        , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount`
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join `hr` h on o.`salesperson`=h.`id`  
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE  (o.id=$organization or $organization = 0)
                                        ";

        
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery.$date_qry;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "s.id";
        }
        if($columnName == "orderdate"){
            $columnName = "s.orderdate";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery.$date_qry." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totamount = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totamount += $row["amount"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "socode"=>$row['socode'],

            		"organization"=>$row['organization'],

            		"orderdate"=>$row["orderdate"],

        			"hrName"=>$row["hrName"],
        			
        			"poc"=>$row["poc"],
        			
        			"stnm"=>$row["stnm"],
        			
        			"amount"=>number_format($row["amount"],2),

            	);
            	

        } 
        
        array_push($total, number_format($totamount,2));
        

    }
    
    else if($action=="rpt_product_wise_salse")

    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and s.orderdate between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
        
        $product = $_GET["filterorg"];
        if($product == '') $product = 0;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`,st.name stnm,s.invoiceamount `amount`
                                        ,i.name product,d.qty,(d.otc+d.vat) unitprice,d.discounttot
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join soitemdetails d on s.socode=d.socode left join item i on d.productid=i.id
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE  (d.productid=$product or $product = 0)
                                        ";

        
        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery.$date_qry;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$strwithoutsearchquery.$date_qry.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totamount = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            $totamount += $row["amount"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "socode"=>$row['socode'],

            		"organization"=>$row['organization'],

            		"orderdate"=>$row["orderdate"],
        			
        			"stnm"=>$row["stnm"],
        			
        			"amount"=>number_format($row["amount"],2),
        			
        			"product"=>$row["product"],
        			
        			"qty"=>$row["qty"],
        			
        			"unitprice"=>$row["unitprice"],
        			
        			"discounttot"=>$row["discounttot"],

            	);
            	

        } 
        
        array_push($total, number_format($totamount,2));
        

    }
    
    else if($action=="rpt_issued_stock")

    {

      
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (t.`name` like '%".$searchValue."%' or p.`name` like '%".$searchValue."%'";;

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode 
                                        FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id 
                                        LEFT JOIN branch r ON s.storerome=r.id  
                                        where  r.id = 6 and s.freeqty<>0
                                        ";

        
        $sel = mysqli_query($con,$qryforrec);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $totamount = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
            //$totamount += $row["amount"];

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "tn"=>$row['tn'],

            		"pn"=>$row['pn'],

            		"freeqty"=>$row["freeqty"],

        			"costprice"=>$row["costprice"],
        			
        			"mrp"=>$row["mrp"],
        			
        			"str"=>$row["str"],
        			
        			"barcode"=>$row["barcode"],

            	);
            	

        } 
        
        array_push($total, number_format($totamount,2));
        

    }
    
    else if($action=="rpt_gl_vouch")
    {

      $fvouch = $_GET["fvouch"];
      $fdt= $_GET['dt_f'];
      $tdt= $_GET['dt_t'];
      
      if ($fdt == '') {$fdt = date("1/m/Y");}
      if ($tdt == '') {$tdt = date("d/m/Y");}
        
        $searchQuery = " ";

        if($searchValue != '') 

        {

        	$searchQuery = " and (a.VouchNo like '%".$searchValue."%'   or org.name like '%".$searchValue."%' or 

                 concat(g.`glnm`, '(', d.`glac`, ')')  like '%".$searchValue."%' or a.refno like '%".$searchValue."%' ) ";

        } 

        ## Total number of records without filtering   #c.`id`, 

                $strwithoutsearchquery1="select a.VouchNo,DATE_FORMAT( a.TransDt,'%d/%b/%Y') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,org.name customer,
                                        (case d. dr_cr when 'D' then d.amount else 0 End) D_amount,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount  
                                        from glmst a  left join gldlt d on a.VouchNo=d.VouchNo left join coa g on d.glac=g.glno
                                        LEFT JOIN invoice inv ON (inv.invoiceno=a.refno or inv.soid = a.refno) LEFT JOIN organization org ON org.id = inv.organization
                                    	";  
                                    	
                 $strwithoutsearchquery="select a.VouchNo,DATE_FORMAT( a.TransDt,'%d/%b/%Y') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,org.name customer,
                                        (case d. dr_cr when 'D' then d.amount else 0 End) D_amount,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount  
                                        from glmst a  left join gldlt d on a.VouchNo=d.VouchNo left join coa g on d.glac=g.glno
                                        LEFT JOIN invoice inv ON (inv.invoiceno=a.refno or inv.soid = a.refno) LEFT JOIN organization org ON org.id = inv.organization
                                    	where  a.VouchNo = '$fvouch' or ('$fvouch' = '0'  and
                                    	(a.TransDt  between  STR_TO_DATE('$fdt','%d/%m/%Y')  and STR_TO_DATE('$tdt','%d/%m/%Y')))";                    	

        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        if($columnName == "id"){
            $columnName = "a.id";
        }
        if($columnName == "TransDt"){
            $columnName = "a.TransDt";
            $columnSortOrder="asc";
        }

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $i++;

            $data[] = array( 

                    "id"=>$i,//$strwithoutsearchquery1,//

                    "VouchNo"=>$row['VouchNo'],

            		"TransDt"=>$row['TransDt'],

            		"refno"=>$row['refno'],
            		
            		"customer"=>$row['customer'],

                    "remarks"=>$row['remarks'],

            		"sl"=>$row["sl"],

        			"glac"=>$row["glac"],
        			
        			"glnm"=>$row["glnm"],
        			
        			"D_amount"=>number_format($row["D_amount"],2),
        			
        			"C_amount"=>number_format($row["C_amount"],2),

            	);

        } 

    }
    
  else if($action=="rpt_gl_ledger")
    {

      $fvouch = $_GET["fvouch"];
      $glnature= fetchByID('coa','glno',$fvouch,'dr_cr');
      
      $fdt= $_GET['fdt'];
      $tdt= $_GET['tdt'];
      if ($fdt == '') {$fdt = date("1/m/Y");}
      if ($tdt == '') {$tdt = date("d/m/Y");}
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.VouchNo like '%".$searchValue."%' or 

                 concat(g.`glnm`, '(', d.`glac`, ')')  like '%".$searchValue."%' or a.refno like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,
        $opbal=0;
                $opbalqry="select (COALESCE(o.opbal,0)+COALESCE(a.amt,0)-COALESCE(b.amt,0)) op
from
(select opbal from coa_mon where  glno='$fvouch' 
and yr=year(STR_TO_DATE('".$fdt."','%d/%m/%Y')) and mn=month(STR_TO_DATE('".$fdt."','%d/%m/%Y'))
)o
 ,
(select sum(d.amount) amt from glmst m, gldlt d 
where m.vouchno=d.vouchno  and d.dr_cr='D' and  d.glac='$fvouch' and m.isfinancial in('0','A')
and ( m.transdt between DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'%Y-%m-01')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))
)a
,(select sum(d.amount) amt from glmst m, gldlt d 
where m.vouchno=d.vouchno  and d.dr_cr='C' and  d.glac='$fvouch' and m.isfinancial in('0','A')
and (m.transdt between DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'%Y-%m-01')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))
)b";

 $resultopbal = $conn->query($opbalqry); 
        while($rowopbal = $resultopbal->fetch_assoc()) {
            $opbal = $rowopbal["op"];
        }
       
        
        if($glnature=='D')
        {
        if($opbal>0)
        {$d_bal=$opbal;$c_bal=0;}
        else {$d_bal=0;$c_bal=$opbal;}
        }
        else
        {
        if($opbal>0)
        {$d_bal=0;$c_bal=$opbal;}
        else {$d_bal=$opbal;$c_bal=0;}
        }

                $strwithoutsearchquery="select '' VouchNo,DATE_FORMAT(STR_TO_DATE('".$fdt."', '%d/%m/%Y'), '%d/%b/%Y') AS TransDt,'' refno,'Opening Balance' remarks,'' sl,'' glac,'' glnm,$d_bal D_amount, $c_bal C_amount
                   union all
                   select a.VouchNo,DATE_FORMAT( a.TransDt,'%d/%b/%Y') TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,COALESCE((case d. dr_cr when 'D' then d.amount else 0 End),0) D_amount,COALESCE((case d.dr_cr when 'C' then d.amount else 0 End),0) C_amount  
                                 from glmst a  left join gldlt d on a.VouchNo=d.VouchNo and a.isfinancial in('0','A')
                                 left join coa g on d.glac=g.glno
                                 where (d.glac='$fvouch'  )
                                 and (a.TransDt  between  STR_TO_DATE('".$fdt."','%d/%m/%Y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y'))";
                                 
                $strwithoutsearchquery1="select a.VouchNo,a.TransDt ,a.refno,a.remarks,d.sl,d.glac,g.glnm,COALESCE((case d. dr_cr when 'D' then d.amount else 0 End),0) D_amount,COALESCE((case d.dr_cr when 'C' then d.amount else 0 End),0) C_amount  
                                        from glmst a  left join gldlt d on a.VouchNo=d.VouchNo left join coa g on d.glac=g.glno and a.isfinancial in('0','A')
                                 where 1=1  ";
#STR_TO_DATE('$fdt','%d/%m/%Y')  and STR_TO_DATE('$tdt','%d/%m/%Y') --and a.VouchNo in(select VouchNo from gldlt where glac='$fvouch' 
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,
 
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
         
        //Total Debit/Credit
        $drtot = 0; $crtot = 0;
        $qrydrtot = "SELECT SUM(d.`amount`) drtot from glmst a  left join gldlt d on a.VouchNo=d.VouchNo WHERE d.`dr_cr` = 'D' and 
                                    	(a.TransDt  between  STR_TO_DATE('".$fdt."','%d/%m/%Y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y'))
                                    	 limit ".$row.",".$rowperpage;
        //echo $qrydrtot;die;
        $resultdrtot = $conn->query($qrydrtot); 
        while($rowdrtot = $resultdrtot->fetch_assoc()) {
            $drtot = $rowdrtot["drtot"];
        }
        
        $qrycrtot = "SELECT SUM(d.`amount`) crtot from glmst a  left join gldlt d on a.VouchNo=d.VouchNo WHERE d.`dr_cr` = 'C' and 
                                    	(a.TransDt  between  STR_TO_DATE('".$fdt."','%d/%m/%Y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y')) 
                                    	 limit ".$row.",".$rowperpage;
        $resultcrtot = $conn->query($qrycrtot); 
        while($rowcrtot = $resultcrtot->fetch_assoc()) {
            $crtot = $rowcrtot["crtot"];
        }
        
        array_push($total, number_format($drtot,2));
        array_push($total, number_format($crtot,2));

        ##.`id`,
        if($columnName == "id"){
            $columnName = "a.id";
        }
//.$searchQuery
        $empQuery=$strwithoutsearchquery." order by TransDt,VouchNo asc  limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
 
        $i=0;$op=0;
        if($glnature=='D'){$cl=$op;}
        else{$cl=$op*(-1);}

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
 
           if($glnature=='D')
            {
                $cl=$cl+$row["D_amount"]-$row["C_amount"];
            }
           else
            {
                $cl=$cl-$row["D_amount"]+$row["C_amount"];
            }
            $i++;

            $data[] = array(

                    "id"=>$i, 

                    "VouchNo"=>$row['VouchNo'],//$opbalqry,//$empQuery,//$strwithoutsearchquery1,//$empQuery,//

            		"TransDt"=>$row['TransDt'],

            		"refno"=>$row['refno'], 

                    "remarks"=>$row['remarks'],

            		"sl"=>$row["sl"],

        			"glac"=>$row["glac"],
        			
        			"glnm"=>$row["glnm"],
        			
        			"D_amount"=>number_format($row["D_amount"]),
        			
        			"C_amount"=>number_format($row["C_amount"]),
                    "lb"=>number_format($cl),
            	);

        } 

    }
    
    else if($action=="rpt_profit_loss")
    {

      $fdt= $_GET['fdt'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (d.`glac` like '%".$searchValue."%' or g.`glnm` like '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select d.glac ,g.glnm ,g.dr_cr, 
                                        (case d.dr_cr when 'D' then sum(d.amount) else 0 end) Debit_amt,
                                        (case d.dr_cr when 'C' then sum(d.amount) else 0 end) credit_amt 
                                        ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from  glmst a,gldlt d,coa g
                                    	where  a.VouchNo=d.VouchNo and d.glac=g.glno And substring(d.glac,1,1) in('3','4') 
                                    	and month(a.transdt) =month(STR_TO_DATE('".$fdt."','%d/%m/%Y')) and a.status='A'
                                	    group by d.glac,g.glnm,g.dr_cr ,d.dr_cr order by d.glac";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "glac";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $drtot = 0;
        $crtot = 0;
        
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $drtot += $row["Debit_amt"];
            $crtot += $row["credit_amt"];
            
            $dr = $row["Debit_amt"]; if($dr == 0) $dr = '';
            $cr = $row["credit_amt"]; if($cr == 0) $cr = '';
            
            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "glac"=>$row['glac'],

            		"glnm"=>$row['glnm'],

            		"dr_cr"=>$row['dr_cr'],

                    "Debit_amt"=>number_format($dr,2),

            		"credit_amt"=>number_format($cr,2),

        			"p"=>$row["p"],

            	);
            	
            		
        }
        
        array_push($total, number_format($drtot,2));
        array_push($total, number_format($crtot,2));

    }
    
    else if($action=="rpt_collection")

    {

      $fdt= $_GET['dt_f'];
      $tdt= $_GET['dt_t'];
      if($fdt != ""){
          $date_qry = "AND c.trdt BETWEEN STR_TO_DATE('$fdt','%d/%m/%Y') AND STR_TO_DATE('$tdt','%d/%m/%Y')";
      }else{
          $date_qry = "";
      }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.orgcode like '%".$searchValue."%' or o.name like '%".$searchValue."%' or m.name like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,	and a.status='A'

        $strwithoutsearchquery="select o.orgcode,o.name orgname,c.trdt,c.amount,m.name modeofpayment
                                from collection c left join organization o on c.customerOrg=o.id left join transmode m on c.transmode=m.id
                                where 1=1 $date_qry ";
        

        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "c.id";
        }

         $empQuery=$strwithsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;;//." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "orgcode"=>$row['orgcode'],
                    
                    "orgname"=>$row['orgname'],

            		"amount"=>$row['amount'],
            		
            		"modeofpayment"=>$row['modeofpayment'],

            	);

        } 
        

    }
    
    else if($action=="rpt_sales")

    {

      $fdt= $_GET['dt_f'];
      $tdt= $_GET['dt_t'];
      if($fdt != ""){
          $date_qry = "AND q.orderdate BETWEEN STR_TO_DATE('$fdt','%d/%m/%Y') AND STR_TO_DATE('$tdt','%d/%m/%Y')";
      }else{
          $date_qry = "";
      }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.orgcode like '%".$searchValue."%' or o.name like '%".$searchValue."%' or q.socode like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,	and a.status='A'

        $strwithoutsearchquery="select DATE_FORMAT( q.orderdate,'%d/%b/%Y') orderdate, q.socode,o.orgcode,o.name orgname,sum(qd.discounttot) salesamount
                                from quotation q join quotation_detail qd on q.socode=qd.socode left join organization o on q.organization=o.id
                                where 1=1 $date_qry ";
        

        $sel = mysqli_query($con,$strwithoutsearchquery." group by q.orderdate,q.socode,o.orgcode,o.name");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery." group by q.orderdate,q.socode,o.orgcode,o.name";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "q.id";
        }
        if($columnName == "orderdate"){
            $columnName = "q.orderdate";
        }

         $empQuery=$strwithsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;;//." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $i++;

            $data[] = array(

                    "id"=>$i,
                    
                    "socode"=>$row['socode'],
                    
                    "orderdate"=>$row['orderdate'],

                    "orgcode"=>$row['orgcode'],
                    
                    "orgname"=>$row['orgname'],
            		
            		"salesamount"=>number_format($row['salesamount'],2),

            	);

        } 
        

    }
    
    else if($action=="rpt_acc_expense")

    {

      $fdt= $_GET['dt_f'];
      $tdt= $_GET['dt_t'];
      if($fdt != ""){
          $date_qry = "AND e.trdt BETWEEN STR_TO_DATE('$fdt','%d/%m/%Y') AND STR_TO_DATE('$tdt','%d/%m/%Y')";
      }else{
          $date_qry = "";
      }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (t.name like '%".$searchValue."%' or m.name like '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,	and a.status='A'

        $strwithoutsearchquery="select t.name exp_ntre,e.amount,m.name modeofpayment
                            from expense e left join transtype t on e.transtype=t.id left join transmode m on e.transmode=m.id
                            where 1=1 $date_qry ";
        

        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "e.id";
        }

         $empQuery=$strwithsearchquery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;;//." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "exp_ntre"=>$row['exp_ntre'],

            		"amount"=>$row['amount'],
            		
            		"modeofpayment"=>$row['modeofpayment'],

            	);

        } 
        
       
       // array_push($total, number_format($drtot,2));
        //array_push($total, number_format($crtot,2));
        //array_push($total, number_format($optot,2));
         //array_push($total, number_format($optot,2));
        

    }
    
    else if($action=="rpt_trial_balance")

    {

      $fdt1= $_GET['dt_f'];
      $tdt1= $_GET['dt_t'];
       

$date1 = DateTime::createFromFormat('d/m/Y', $fdt1);
$fdt = $date1->format('Y-m-d');
$date2 = DateTime::createFromFormat('d/m/Y', $tdt1);
$tdt = $date2->format('Y-m-d');

if($fdt == '')
{
    $fdt = date("Y-m-d");
}
if($tdt == '')
{
    $tdt = date("Y-m-d");
}
$fyr= $date1->format('Y');
$tyr= $date2->format('Y');
$fmn=$date1->format('n');
$tmn=$date2->format('n');



        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`glno` like '%".$searchValue."%' or c.`glnm` like '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,	and a.status='A'

                $strwithoutsearchquery="select un.glac,c.glnm,c.dr_cr,COALESCE(sum(un.D_amount),0) dr,COALESCE(sum(un.C_amount),0) cr,COALESCE(sum(un.op),0)op ,'$fdt' p 
                                        from
	                                    (
                                        	select d.glac
                                        	,(case d.dr_cr when 'D' then d.amount else 0 End) D_amount
                                        	,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount
                                        	,0 op
                                        	from glmst a,gldlt d
                                        	where a.VouchNo=d.VouchNo and a.isfinancial in('0','A')
                                        	and (a.transdt   between  '$fdt'  and '$tdt')	
                                        	
                                        	Union all	
                                        	select glno,0 D_amount,0 C_amount,COALESCE(opbal ,0)op
                                        	from coa_mon 
                                        	where isposted='P' and opbal<>0 
                                        		and mn=$fmn 
                                        		and yr='$fyr'
                                        ) un,coa c where un.glac=c.glno and c.oflag='N'
                                        group by un.glac,c.glnm,c.dr_cr";
//	and mn=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%m')
//	and yr=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%Y')
        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;
                            
        

        ## Total number of records with filtering # c.`id`,and a.status='A'

        

        $strwithsearchquery="select un.glac,c.glnm,c.dr_cr,COALESCE(sum(un.D_amount),0) dr,COALESCE(sum(un.C_amount),0) cr,COALESCE(sum(un.op),0) op ,'$fdt' p 
                                        from
	                                    (
                                        	select d.glac
                                        	,COALESCE((case d.dr_cr when 'D' then d.amount else 0 End),0) D_amount
                                        	,COALESCE((case d.dr_cr when 'C' then d.amount else 0 End),0) C_amount
                                        	,0 op
                                        	from glmst a,gldlt d
                                        	where a.VouchNo=d.VouchNo and a.isfinancial in('0','A')
                                        	   	and (a.transdt   between   '$fdt'  and '$tdt')	
                                        		
                                        	Union all	
                                        	select glno,0 D_amount,0 C_amount,COALESCE(opbal ,0) op
                                        	from coa_mon 
                                        	where isposted='P' and opbal<>0 
                                        		and mn=$fmn
                                        		and yr='$fyr'
                                        ) un,coa c where un.glac=c.glno and c.oflag='N' ".$searchQuery."
                                        group by un.glac,c.glnm,c.dr_cr";

         // yr=year(STR_TO_DATE('".$fdt."','%d/%m/%Y')) and mn=month(STR_TO_DATE('".$fdt."','%d/%m/%Y'))

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "glac";
        }

         $empQuery=$strwithsearchquery;//." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $drtot = 0;
        $crtot = 0;
        $optot = 0;
        $cltot = 0;
        $cl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $drtot += $row["dr"];
            $crtot += $row["cr"];
            $optot += $row["op"];
            
            $drcr = $row["dr_cr"];
            $dr = $row["dr"]; //if($dr == 0) $dr = '';
            $cr = $row["cr"]; //if($cr == 0) $cr = '';
            $op = $row["op"]; //if($op == 0) $op = '';
            if($drcr=='D')
            {
                $cl = $row["op"]+$row["dr"]-$row["cr"]; //if($op == 0) $op = '';
            }
            else
            {
                $op=$op*(-1);
                $cl = $op+$row["dr"]-$row["cr"]; //if($op == 0) $op = '';
                //$cl = $row["op"]-$row["dr"]+$row["cr"]; //if($op == 0) $op = '';
            }
            
            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "glac"=>$row['glac'],

            		"glnm"=>$row['glnm'],

            		"op"=>number_format($op,2),

                    "dr"=>number_format($dr,2),

            		"cr"=>number_format($cr,2),
            		
            		"cl"=>number_format($cl,2),

        			//"p"=>$row["p"],

            	);

        } 
        
       
       // array_push($total, number_format($drtot,2));
        //array_push($total, number_format($crtot,2));
        //array_push($total, number_format($optot,2));
         //array_push($total, number_format($optot,2));
        

    }
    else if($action=="rpt_trial_balance_fin")

    {

      $fdt= $_GET['dt_f'];
      $tdt= $_GET['dt_t'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`glno` like '%".$searchValue."%' or c.`glnm` like '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,	and a.status='A'

                $strwithoutsearchquery="select un.glac,c.glnm,c.dr_cr,COALESCE(sum(un.D_amount),0) dr,COALESCE(sum(un.C_amount),0) cr,COALESCE(sum(un.op),0)op ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p 
                                        from
	                                    (
                                        	select d.glac
                                        	,(case d.dr_cr when 'D' then d.amount else 0 End) D_amount
                                        	,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount
                                        	,0 op
                                        	from glmst a,gldlt d
                                        	where a.VouchNo=d.VouchNo and a.isfinancial in('0','Y')
                                        	and (a.transdt   between   date_format(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'01/%m/%y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y'))	
                                        	
                                        	Union all	
                                        	select glno,0 D_amount,0 C_amount,(COALESCE(opbal ,0)+COALESCE(op_bal_fin,0)) op
                                        	from coa_mon 
                                        	where isposted='P' and (COALESCE(opbal ,0)+COALESCE(op_bal_fin,0))<>0 
                                        		and mn='7' 
                                        		and yr='2023'
                                        ) un,coa c where un.glac=c.glno
                                        group by un.glac,c.glnm,c.dr_cr";
//	and mn=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%m')
//	and yr=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%Y')
        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,and a.status='A'

        

       
        
        $strwithsearchquery="select un.glac,c.glnm,c.dr_cr,COALESCE(sum(un.D_amount),0) dr,COALESCE(sum(un.C_amount),0) cr,COALESCE(sum(un.op),0) op ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p 
                                        from
	                                    (
                                        	select d.glac
                                        	,COALESCE((case d.dr_cr when 'D' then d.amount else 0 End),0) D_amount
                                        	,COALESCE((case d.dr_cr when 'C' then d.amount else 0 End),0) C_amount
                                        	,0 op
                                        	from glmst a,gldlt d 
                                        	where a.VouchNo=d.VouchNo and a.isfinancial in('0','Y')
                                        	   	and (a.transdt   between   date_format(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'01/%m/%y')  and STR_TO_DATE('".$tdt."','%d/%m/%Y'))
                                        		
                                        	Union all	
                                        	select glno,0 D_amount,0 C_amount,(COALESCE(opbal ,0)+COALESCE(op_bal_fin,0))  op
                                        	from coa_mon 
                                        	where isposted='P' and (COALESCE(opbal ,0)+COALESCE(op_bal_fin,0))<>0 
                                        			and mn=month(STR_TO_DATE('".$fdt."','%d/%m/%Y'))
                                        		and yr=year(STR_TO_DATE('".$fdt."','%d/%m/%Y'))
                                        ) un,coa c where un.glac=c.glno ".$searchQuery."
                                        group by un.glac,c.glnm,c.dr_cr";

         // yr=year(STR_TO_DATE('".$fdt."','%d/%m/%Y')) and mn=month(STR_TO_DATE('".$fdt."','%d/%m/%Y'))

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "glac";
        }

         $empQuery=$strwithsearchquery;//." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $drtot = 0;
        $crtot = 0;
        $optot = 0;
        $cltot = 0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $drtot += $row["dr"];
            $crtot += $row["cr"];
            $optot += $row["op"];
            
            $dr = $row["dr"]; //if($dr == 0) $dr = '';
            $cr = $row["cr"]; //if($cr == 0) $cr = '';
            $op = $row["op"]; //if($op == 0) $op = '';
            $cl = $row["op"]+$row["dr"]-$row["cr"]; //if($op == 0) $op = '';
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "glac"=>$row['glac'],

            		"glnm"=>$row['glnm'],

            		"op"=>number_format($op,2),

                    "dr"=>number_format($dr,2),

            		"cr"=>number_format($cr,2),
            		
            		"cl"=>number_format($cl,2),

        			//"p"=>$row["p"],

            	);

        } 
        
       
      //  array_push($total, number_format($optot,2));
        //array_push($total, number_format($crtot,2));
        //array_push($total, number_format($optot,2));
         //array_push($total, number_format($optot,2));
        

    }
    else if($action=="rpt_balance_sheet")

    {

      $fdt= $_GET['fdt'];
      $pyr=$_GET['fyr'];
      $pmn=$_GET['fmn'];

        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (d.`glac` like '%".$searchValue."%' or g.`glnm` like '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,
        
        //Sub Query
        $assetqry = "select @asset=COALESCE(closingBal,0) asset from coa_mon where glno='100000000' and mn='$pmn' and yr='$pyr'";
        $assetresult = mysqli_query($con, $assetqry);
        while ($assetrow = mysqli_fetch_assoc($assetresult)){
            $asset = $assetrow["asset"];
        }
        
        $liabilityqry = "select @liability =COALESCE(closingBal,0) liability from coa_mon where glno='200000000' and mn='$pmn' and yr='$pyr'";
        $liabilityresult = mysqli_query($con, $liabilityqry);
        while ($liabilityrow = mysqli_fetch_assoc($liabilityresult)){
            $liability = $liabilityrow["liability"];
        }

        $strwithoutsearchquery="select
                                (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                            	from coa_mon a 
                            	where substring(a.glno,1,1) in('1','2') 
                               and mn='$pmn' and yr='$pyr' and a.status='A'
                            	order by a.glno";
//and a.lvl>1 
        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "glac";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;
        //print $empQuery;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "asslib"=>$row['asslib'],

            	//	"assLib_amount"=>$row['assLib_amount'],

            		"lvl"=>$row['lvl'],

                    "glno"=>$row['glno'],

            		"glnm"=>$row["glnm"],
            		
            		"closingBal"=>number_format($row["closingBal"]),

        			"p"=>$row["p"],

            	);

        } 

    }

    else if($action=="expense")

    {
        //Filter
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and cl.`trdt` between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }

        $filterorg = $_GET["filterorg"];

        if($filterorg != ''){

            $filterorgqry = " and cl.`customerOrg` = ".$filterorg;

        }else{
            $filterorgqry = "";
        }
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tp.name  like '%".$searchValue."%' or tr.name like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`,  DATE_FORMAT( cl.`trdt`,'%d/%b/%Y') `trdt`,tp.name trtp, tr.name `transmode`,cl.`transref`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` 
                                        
                                        FROM expense cl, transtype tp, costcenter cc,transmode tr

                                        where cl.transtype=tp.id and cl.costcenter=cc.id and cl.transmode=tr.id $date_qry $filterorgqry";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

         if($columnName == 'id')
        {
            $columnName=" cl.id ";
            $columnSortOrder=" desc";
        }
         if($columnName == 'trdt')
        {
            $columnName=" cl.trdt ";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="expense.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=expense&ret=expenseList&mod=3&id=".$row['id'];

            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"trtp"=>$row['trtp'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],

                    "action"=> getGridBtns($btns),
            	);

        } 

    }
    
     else if($action=="acc_expense")

    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and cl.`trdt` between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tp.name  like '%".$searchValue."%' or tr.name like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, DATE_FORMAT(cl.`trdt`, '%d/%b/%Y') trdt,tp.name trtp, tr.name `transmode`,cl.`transref`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter`, gl.glnm 
                FROM expense cl LEFT JOIN transtype tp ON cl.transtype=tp.id LEFT JOIN costcenter cc ON cl.costcenter=cc.id LEFT JOIN transmode tr ON cl.transmode=tr.id LEFT JOIN coa gl ON cl.glac = gl.glno

                where 1=1  ".$date_qry;

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "trdt"){
            $columnName = "cl.trdt";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="acc_expense.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=expense&ret=acc_expenseList&mod=7&id=".$row['id'];

            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"trtp"=>$row['trtp'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],
        			
        			"glac"=>$row['glnm'],

            		"action"=> getGridBtns($btns),
            	);

        } 

    }

    else if($action=="lead")

    {

        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (c.`contactcode` like '%".$searchValue."%' or 

                 tp.`name`  like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or o.`name` like '%".$searchValue."%' or

                ds.`name` like '%".$searchValue."%' or dp.name like '%".$searchValue."%' or c.`phone` like '%".$searchValue."%' or c.`email` like '%".$searchValue."%') ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery="SELECT c.`id`,c.`contactcode`,tp.`name` `contacttype`,c.`name`,o.`name` `organization`,ds.`name` `designation`,dp.name `department`,c.`phone`,c.`email`,c.`details` 

        FROM `contact` c,`designation` ds,`department` dp,`leadstatus` tp,`organization` o

        where c.lead_state=tp.id and c.designation=ds.id and c.department=dp.id  and c.contacttype=3 and c.`organization`=o.`id`";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by c.id desc ,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="lead.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";

            $photo=$rootpath."/common/upload/contact/".$row["contactcode"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (file_exists($photo)) {

        		$photo="common/upload/contact/".$row["employeecode"].".jpg";

        		}else{

        			$photo="images/blankuserimage.png";

        		}

            $data[] = array(

                    "photo"=>'<img src='.$photo.' width="50" height="50">',

                   	"name"=>$row['name'],

            		"status"=>$row['contacttype'],

            		"organization"=>$row['organization'],

            		"designation"=>$row['designation'],

        			"department"=>$row['department'],

            		"phone"=>$row['phone'],

        			"email"=>$row['email'],

        			"details"=>$row['details'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            	);

        } 

    }

    
    // org removed; see in datagrid_customer.php
    
    else if($action=="item")
    {

        $brnd = $_GET["brnd"]; if($brnd == '') $brnd = 0;
        $cat = $_GET["icat"]; if($cat == '') $cat = 0;
        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (i.`code` like '%".$searchValue."%' or 

                 i.`name`  like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or i.`size` like '%".$searchValue."%' or

                p.`name` like '%".$searchValue."%' or ic.`name` like '%".$searchValue."%' or i.`description` like '%".$searchValue."%' or b.title like '%".$searchValue."%' ) ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery0="SELECT i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, b.title brand,i.`dimension`,i.`wight`, i.`image`, i.`description`,i.rate, i.cost, i.vat, i.ait
FROM `item` i LEFT JOIN `color` c ON i.`color`=c.`id` LEFT JOIN `pattern` p ON i.`pattern`= p.`id` LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` LEFT JOIN brand b on i.brand=b.id
 ";

        $strwithoutsearchquery="SELECT i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, b.title brand,i.`dimension`,i.`wight`, i.`image`, i.`description`,i.rate, i.cost, i.vat, i.ait
FROM `item` i LEFT JOIN `color` c ON i.`color`=c.`id` LEFT JOIN `pattern` p ON i.`pattern`= p.`id` LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` LEFT JOIN brand b on i.brand=b.id
where  (ic.`id`=$cat or $cat='0') and (i.brand =$brnd or $brnd=0) ";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

        ##.`id`,
        
        if($columnName == 'photo'){
            $columnName = "i.id";
        }
        

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

                

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="rawitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";

           $setdelurl="common/delobj.php?obj=item&ret=rawitemList&mod=12&id=".$row['id'];
           $seturlbarcode="barcode/generate_barcode.php?id=".$row['id']."&chid=0";

            $photo=$rootpath."/common/upload/item/".$row["image"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (file_exists($photo)) {

        		$photo="common/upload/item/".$row["image"].".jpg";

        		}else{

        			$photo="common/upload/item/placeholder.jpg";

        		}
            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
            $data[] = array(

                    "photo"=>'<img src='.$photo.' width="50" height="50">',

                   	"code"=>$row['code'],//$strwithoutsearchquery1,//

            		"itnm"=>$row['itnm'],

            		"rate"=>number_format($row['rate'], 2),

            		"cost"=>number_format($row['cost'], 2),
            		
            		"vat"=>number_format($row['vat'], 0),
            		
            		"ait"=>number_format($row['ait'],0),

        			"lt"=>$row['lt'],

            		"ItemCat"=>$row['ItemCat'],
            		"brand"=>$row['brand'],

        			//"description"=>$row['description'],

            		"action"=> getGridBtns($btns),
	               "bc"=>'<a class="btn btn-info btn-xs"  href="'. $seturlbarcode.'" target="_blank">BarCode</a>',
            		
            	);

        } 

    }

    else if($action=="mo")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (p.`mocode` like '%".$searchValue."%' or 

                 p.`deliverydt`  like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT  p.`id`,p.`mocode`,p.`deliverydt` FROM `mo` p where 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="mo.php?res=4&msg='Update Data'&id=".$row['id']."&mod=1";

            $data[] = array(

                    "mocode"=>$row['mocode'],

            		"deliverydt"=>$row['deliverydt'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            	);

        } 

    }

    else if($action=="payment")

    {
        
        //Filter
        $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];

        $filterorg = $_GET["filterorg"];

        if($filterorg != ''){

            $filterorgqry = " and cl.customer = ".$filterorg;

        }else{
            $filterorgqry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, DATE_FORMAT( cl.`trdt`,'%d/%b/%Y') `trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` ,cl.st

                FROM allpayment cl left join organization c on cl.customer=c.id left join costcenter cc on cl.costcenter=cc.id left join transmode tr on cl.transmode=tr.id 
                
                where 1=1 and cl.`trdt` BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') $filterorgqry
                
                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "trdt"){
            $columnName = "cl.trdt";
        }

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="payment.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=allpayment&ret=paymentList&mod=3&id=".$row['id'];
            
            //$setviewurl = "payment_rec.php?mod=3&rpid=".$row["id"];
			$setviewurl = "money_receipt.php?rpid=".$row["id"];

            $btns = array(
				//sample
				//array('delete','quotation_view.php','attrs'),
				array('view',$setviewurl,'class="btn btn-info btn-xs"  title="View" '),
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
			if($row["st"] == 1){
			    $st = '<kbd class="inprogress">Pending</kbd>';
			    
			}else if($row["st"] == 2){
			    $st = '<kbd class="completed">Completed</kbd>';
	
			}else{
			    $st = '<kbd class="pending">Declined</kbd>';
			}

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],
        			
        			"st"=>$st,
        			
        			"action"=> getGridBtns($btns),
            	);

        } 

    }

    
    else if($action=="action_payment")

    {
        
        //Filter
        $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];

        $filterorg = $_GET["filterorg"];

        if($filterorg != ''){

            $filterorgqry = " and cl.customer = ".$filterorg;

        }else{
            $filterorgqry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and ( DATE_FORMAT( cl.`trdt`,'%d/%b/%Y')  like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, DATE_FORMAT( cl.`trdt`,'%d/%b/%Y') `trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter`,cl.st,cl.customer orgid

                FROM allpayment cl left join organization c on cl.customer=c.id left join costcenter cc on cl.costcenter=cc.id left join transmode tr on cl.transmode=tr.id 
                
                where 1=1 and cl.`trdt` BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y') $filterorgqry
                
                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            if($row["st"] == 1){
			    $st = '<kbd class="inprogress">Pending</kbd>';
			    
			    $actbtn = '<a class="btn btn-info btn-xs"  href="./common/update_payment.php?res=2&id='.$row["id"].'&amount='.$row["amount"].'&orgid='.$row["orgid"].'">Accept</a>';
                $delbtn ='<a class="btn btn-info btn-xs"  href="./common/update_payment.php?res=0&id='.$row["id"].'">Decline</a>';
			}else if($row["st"] == 2){
			    $st = '<kbd class="completed">Completed</kbd>';
	
			    $actbtn = "<a class='btn btn-info btn-xs' style='background-color:#808080'> Already Accepted</a>";
                $delbtn ="<a class='btn btn-info btn-xs' style='background-color:#808080'>Already Accepted</a>";
			}else{
			    $st = '<kbd class="pending">Declined</kbd>';
			    
			    $actbtn = "<a class='btn btn-info btn-xs' style='background-color:#808080'> Already Declined</a>";
                $delbtn ="<a class='btn btn-info btn-xs' style='background-color:#808080'>Already Declined</a>";
			}
             
            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],
        			
        			"st"=>$st,
        			
        			"accept"=> $actbtn,
        			
        			"decline"=> $delbtn,
            	);

        } 

    }
    
    else if($action=="action_return_payment")

    {
        
        //Filter
        $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];

        $filterorg = $_GET["filterorg"];

        if($filterorg != ''){

            $filterorgqry = " and cl.customer = ".$filterorg;

        }else{
            $filterorgqry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (rp.narration like '%".$searchValue."%' or 

                 trm.name  like '%".$searchValue."%' or rp.quotation like '%".$searchValue."%' or org.name  like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT rp.id,rp.narration,rp.amount, DATE_FORMAT(rp.`trdt`, '%d/%b/%Y') trdt,trm.name transmode, rp.transref,  
                rp.quotation, rp.st, org.name customer, org.id orgid
                
                FROM `return_payment` rp LEFT JOIN organization org ON org.id=rp.customer LEFT JOIN transmode trm ON trm.id =rp.transmode
                
                where 1=1 and rp.`trdt` BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') $filterorgqry
                
                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == 'sl') $columnName = "rp.id";

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $sl++;

            $seturl="return_payment.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=return_payment&ret=return_paymentList&mod=7&id=".$row['id'];

            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
            
			if($row["st"] == 1){
			    $st = '<kbd class="inprogress">Pending</kbd>';
			    
			    $actbtn = '<a class="btn btn-info btn-xs"  href="./common/update_return_payment.php?res=2&id='.$row["id"].'&amount='.$row["amount"].'&orgid='.$row["orgid"].'">Accept</a>';
                $delbtn ='<a class="btn btn-info btn-xs"  href="./common/update_return_payment.php?res=0&id='.$row["id"].'">Decline</a>';
			}else if($row["st"] == 2){
			    $st = '<kbd class="completed">Completed</kbd>';
	
			    $actbtn = "<a class='btn btn-info btn-xs' style='background-color:#808080'> Already Accepted</a>";
                $delbtn ="<a class='btn btn-info btn-xs' style='background-color:#808080'>Already Accepted</a>";
			}else{
			    $st = '<kbd class="pending">Declined</kbd>';
			    
			    $actbtn = "<a class='btn btn-info btn-xs' style='background-color:#808080'> Already Declined</a>";
                $delbtn ="<a class='btn btn-info btn-xs' style='background-color:#808080'>Already Declined</a>";
			}

            $data[] = array(
                
                    "sl"=>$sl,
                    
                    "narration"=>$row['narration'],
                
                    "customer"=>$row['customer'],
                    
                    "quotation"=>$row['quotation'],
                    
                    "amount"=>$row['amount'],

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],
            		
            		"st"=>$st,
        			
        			"accept"=> $actbtn,
        			
        			"decline"=> $delbtn,
            	);

        } 

    }
    
    else if($action=="acc_payment")

    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and cl.`trdt` between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`,  DATE_FORMAT( cl.`trdt`,'%d/%b/%Y') `trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, 
                                        cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter`, gl.glnm, t.value tds, v.value vds

                                        FROM allpayment cl left join suplier c on cl.customer=c.id left join costcenter cc on cl.costcenter=cc.id left join transmode tr on cl.transmode=tr.id 
                                        
                                        LEFT JOIN coa gl ON cl.glac = gl.glno  LEFT JOIN tds t ON cl.tds=t.id LEFT JOIN tds v ON v.id=cl.vds
                                        
                                        where 1=1 ".$date_qry;

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "trdt"){
            $columnName = "cl.trdt";
        }


         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $vds = ($row["amount"] * $row["vds"]) / 100;
            $tds = ($row["amount"] * $row["tds"]) / 100;
            $amount = $row["amount"] + $tds + $vds;
            $seturl="acc_payment.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=allpayment&ret=acc_paymentList&mod=7&id=".$row['id'];

            $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
			$slip = '<a href="supplier_slip.php?trid='.$row['id'].'" class="btn btn-info btn-xs slip-print dataTable2"><i class="fa fa-print"></i></a>';

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$amount,

        			"costcenter"=>$row['costcenter'],
        			
        			"glac"=>$row['glnm'],

            		"action"=> getGridBtns($btns). "| ". $slip,
            	);

        } 

    }

    else if($action=="po")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (p.`poid` like '%".$searchValue."%' or 

                 s.`name` like '%".$searchValue."%' or p.`orderdt` like '%".$searchValue."%' or p.`tot_amount`  like '%".$searchValue."%' or

                p.`invoice_amount` like '%".$searchValue."%' or p.`delivery_dt` like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT  p.`id`,p.`poid`,s.`name` , p.`orderdt`, p.`tot_amount`, format(p.`invoice_amount`,2)invoice_amount,p.`delivery_dt` FROM `po` p,`suplier` s  WHERE p.supid=s.id and p.status='A'";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

        if($columnName == "edit"){

            $columnName = "poid";

        }

        

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="po.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $actbtn =   '<div class="btn-group">

                        <a class="dropdown-toggle dropdown_icon" data-toggle="dropdown">

                        <i class="fa fa-ellipsis-h"></i> </a>

                            <ul class="dropdown-menu">

                                <li>

                                    <a href="'.$seturl.'" >

                                        <i class="fa fa-edit"></i>Edit

                                    </a>

                                </li>

                                <li>

                                    <a href="'.$seturl.'">

                                        <i class="fa fa-trash"></i>Delete

                                    </a>

                                </li>  

                                                

                            </ul>

                        </div>';

           

            $data[] = array(

                    "poid"=>$row['poid'],

            		"name"=>'<span class=" profile-img"> <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRCWz2PNQ8o4EKjY6lY6JcT7sLIWeiS3Hc0_Vsm2Ot820vgbCO_GWVAB4Z_XDsWiyWDuf0&usqp=CAU"></span>'.$row['name'],

            		"orderdt"=>$row['orderdt'],

            		"tot_amount"=>$row['tot_amount'],

        			"invoice_amount"=>$row['invoice_amount'],

            		"delivery_dt"=>$row['delivery_dt'],

            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            		"edit"=>$actbtn

            	);

        } 

    }

     else if($action=="soitem")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  concat(e1.firstname,'',e1.lastname) like '%".$searchValue."%' or 

                 tp.`name` like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or cr.shnm  like '%".$searchValue."%'

                 or s.`socode` like '%".$searchValue."%' or s.`effectivedate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;

        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`effectivedate`,'%d/%b/%Y') `orderdate`,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,format(sum(qtymrc*sd.mrc),2) mrc,concat(e.firstname,'',e.lastname) `hrName`, concat(e1.firstname,'',e1.lastname) `poc`

FROM `soitem` s left join `soitemdetails` sd on sd.socode=s.socode left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization  

left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode` 

left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`

left join currency cr on sd.currency=cr.id WHERE  1=1 and (s.organization = $orgid or $orgid = 0) ";

        $strwithoutsearchquery=$basequery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm  order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="soitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setInvurl="invoicPart.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=soitem&ret=soitemList&mod=3&id=".$row['id'];

            $i++;

            $data[] = array(

                    "id"=>$i,//$row['hrName'],

            		"srctype"=>$row['srctype'],

            		"hrName"=>$row['customer'],

            		"organization"=>$row['organization'],

        			"socode"=>$row['socode'],

            		"orderdate"=>$row['orderdate'],

    				"shnm"=>$row['shnm'],

            		"otc"=>$row['otc'],

            		"mrc"=>$row['mrc'],

            		"poc"=>$row['poc'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"inv"=>'<a class="btn btn-info btn-xs"  href="'. $setInvurl.'">Create Invoice</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);

        } 

    }
    
    else if($action=="inv_stock(don't know why this has duplicate value)")
    {
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and (p.productcode like '%".$searchValue."%' or p.name like '%".$searchValue."%' or t.name  like '%".$searchValue."%' or s.freeqty  like '%".$searchValue."%' 
        	 or s.costprice  like '%".$searchValue."%'  or p.mrp  like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $baseqry=" SELECT p.id,p.productcode,p.image, s.product,p.name prod,t.name typ, sum(s.freeqty) freeqty, p.cost costprice,p.discount,p.mrp, p.code FROM chalanstock s , product p,itemtype t  
        where s.product=p.id and p.catagory=t.id ";
        $strwithoutsearchquery= $baseqry." group by p.id ,p.productcode,p.image, s.product,p.name ,t.name ,p.mrp ,p.cost,p.discount having sum(s.freeqty)<>0 ";
       
        
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$baseqry.$searchQuery." group by p.id ,p.productcode,p.image, s.product,p.name ,t.name ,p.mrp ,p.cost,p.discount having sum(s.freeqty)<>0";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
         $empQuery=$baseqry.$searchQuery." group by p.id ,p.productcode,p.image, s.product,p.name ,t.name ,p.mrp ,p.cost,p.discount having sum(s.freeqty)<>0 order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {       
                $seturl="product.php?res=4&msg='Update Data'&id=".$row['pid']."&mod=3";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
               $photo="/common/upload/item/".$row["code"].".jpg";
                if (file_exists($photo)) {
        		//$photo="../assets/images/product/70_75/".$row['image'];
        		}else{
        			$photo="images/blankuserimage.png";
        		}
                //$photo="images/blankuserimage.png";
                //die;
                if($row["freeqty"] < 1){
                    $prdnm = "<p style='color:#00abe3;'> ".$row["prod"]."</p>";
                }else{
                    $prdnm = $row["prod"];
                }
              $sl=$sl+1;  
            $data[] = array(
                    "id"=>$sl,//$empQuery,//
                    "image"=>'<img src='.$photo.' width="50" height="50">',
                    "productcode"=>$row['productcode'],
            		"prod"=>$prdnm,
            		"typ"=>$row['typ'],
            		"freeqty"=>number_format($row['freeqty'],0),
            		"costprice"=>number_format($row['costprice'],2),
            		"mrp"=>number_format($row['mrp'],2),
            		"discount"=>number_format($row['discount'],2)
            	);
        } 
    }
    else if($action=="inv_soitem_ed")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  concat(e1.firstname,'',e1.lastname) like '%".$searchValue."%' or 

                 tp.`name` like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or cr.shnm  like '%".$searchValue."%'

                 or s.`socode` like '%".$searchValue."%' or s.`orderdate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;

        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate`
        ,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,s.orderstatus
        ,format(sum(qtymrc*sd.mrc),2) mrc,concat(e.firstname,'',e.lastname) `hrName`, concat(e1.firstname,'',e1.lastname) `poc`

FROM `soitem` s left join `soitemdetails` sd on sd.socode=s.socode left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization  

left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode` 

left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`

left join currency cr on sd.currency=cr.id WHERE  1=1 and (s.organization = $orgid or $orgid = 0) ";

        $strwithoutsearchquery=$basequery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm,s.orderstatus";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus  order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           // $st=$row['orderstatus'];
            
           // $seturl="inv_soitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
           // if($st<=1){
            $urlas='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  >Edit</a>';
           // }
           // else
          //  {
          //  $urlas='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  disabled>Edit</a>';
          //  }
            

            $setInvurl="invoicPart.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=soitem&ret=inv_soitemList&mod=3&id=".$row['id'];
             if($st<=1){
            $urlasdel='<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
            }
            else
            {
            $urlasdel='<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'"  disabled>Delete</a>';
            }
  
  
            $i++;

            $data[] = array(

                    "id"=>$i,//$row['hrName'],

            		"srctype"=>$row['srctype'],

            		"hrName"=>$row['customer'],

            		"organization"=>$row['organization'],

        			"socode"=>$row['socode'],

            		"orderdate"=>$row['orderdate'],

    				"shnm"=>$row['shnm'],

            		"otc"=>$row['otc'],

            		"mrc"=>$row['mrc'],

            		"poc"=>$row['poc'],

            		"edit"=>$urlas,

            	//	"inv"=>'<a class="btn btn-info btn-xs"  href="'. $setInvurl.'">Create Invoice</a>',

            		"del"=>$urlasdel,

            	);

        } 

    }
    else if($action=="deal")

    {

        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and d.dealdate between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }

        //generation status combo

	$statusStr = 'SELECT * FROM dealstatus';

	//echo $statusStr;

	

	

		$statusResult = $conn->query($statusStr);

		if ($statusResult->num_rows > 0){

			while($statusRow = $statusResult->fetch_assoc()){

				$thisClass = str_replace(" ","_",$statusRow['name']);

				$statusCombo .= '<li class="col-xs-6"><a href="javascript:void(0)" data-statusid="'.$statusRow['id'].'" class="'.strtolower($thisClass).'">'.$statusRow['name'].'</a></li>';

				

			 }

		}

  //end generation status combo		

		

	//generation stage combo

	    $stagesStr = 'SELECT * FROM dealtype order by sl';



		$stageResult = $conn->query($stagesStr);

		if ($stageResult->num_rows > 0){

			while($stageRow = $stageResult->fetch_assoc()){

				$thisClass = str_replace(" ","_",$stageRow['name']);

				$stageCombo .= '<li class="col-xs-6"><a href="javascript:void(0)" data-stageid="'.$stageRow['id'].'" class="'.strtolower($thisClass).'">'.$stageRow['name'].'</a></li>';

				

			 }

		}

	//end generation stage combo

        

        

        

        

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (d.`name` like '%".$searchValue."%' or concat(e.firstname,' ',e.lastname) like '%".$searchValue."%' or

                 c.`name` like '%".$searchValue."%' or o.`name` like '%".$searchValue."%' or d.`value`  like '%".$searchValue."%' or  cr.shnm  like '%".$searchValue."%' or

                d.`dealdate` like '%".$searchValue."%' or round(IFNULL((d.`value`*s.`weight`/100),0),2) like '%".$searchValue."%') ";

        } 

        ## Total number of records without filtering   #c.`id`,

        $base_qry="SELECT  d.`id`,d.`name` dnm,c.`id` lid, c.`name` lnm,o.id orid ,o.`name` leadcompany ,format(d.`value`,2) VALU,s.`name` stage,ds.`name` 'status',ds.`id` dsid,DATE_FORMAT(d.`dealdate`, '%d/%b/%Y') `dealdate`, DATE_FORMAT(d.`nextfollowupdate`, '%d/%b/%Y') `fldt` ,(case d. `status` when '5' then  (select `name` from deallostreason where id=d.lostreason) else '' end ) lost_rsn,format(IFNULL((d.`value`*s.`weight`/100),0),2) forcast,concat(e.firstname,'',e.lastname)  accmger,format(sum(i.qty*i.otc),2) otc,format(sum(i.qtymrc*i.mrc),2) mrc,cr.shnm

FROM deal d  left join dealitem i on d.id=i.socode

		left join contact c on d.`lead`=c.`id`

		left join organization o on d.leadcompany=o.id

        left join dealtype s on d.`stage`=s.`id`

        left join dealstatus ds  on d.`status`=ds.`id`

        left join `hr` h on o.`salesperson`=h.`id`  

        left join employee e on h.`emp_id`=e.`employeecode`

        left join currency cr on i.currency=cr.id

        where 1=1  ".$date_qry;

        $strwithoutsearchquery=$base_qry." group by d.`id`";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$base_qry.$searchQuery." group by d.`id`" ;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "dnm"){

            $columnName = "makedate";

            $columnSortOrder = "desc";

        }
        
        if($columnName == "dealdate") $columnName = "d.dealdate";
        if($columnName == "fldt") $columnName = "d.nextfollowupdate";

         $empQuery=$base_qry.$searchQuery."  group by d.`id` order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="deal.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";

            $setdelurl="common/delobj.php?obj=deal&ret=dealList&mod=2&id=".$row['id'];

            

             $conthisturl="contactDetail.php?id=".$row['lid']."&mod=2";

             $conthisturl1="contactDetail_org.php?id=".$row['orid']."&mod=2";

            $data[] = array(

                    "dnm"=>$row['dnm'],

                    //"dnm"=>$columnName,

            		"lnm"=>'<a class=""  href="'.$conthisturl.'">'.$row["lnm"].'</a>',

            		"leadcompany"=>'<a class=""  href="'.$conthisturl1.'">'.$row["leadcompany"].'</a>',

                    "value"=>$row['otc']+$row['mrc'],

            		"stage"=>'<td>

					

										

                                      <div class="">

                                        <a class="bit-btn dropdown-toggle" id="menu2" type="button" data-toggle="dropdown" data-id="'.$row['id'].'">

                                            <span>

                                                '.$row["stage"].'

                                                <span class="caret"></span>

                                            </span>

                                        </a>

                                        <div class="dropdown-menu dropdown-menu-mega">

                                            <ul class="row">

                                              '.$stageCombo.'

                                            </ul>                                    

                                        </div>

                                      </div>                                         

                                    <input type="hidden"  class="stage '.strtolower(str_replace(" ","_",$row["stage"])).' dropdown">    

                                    </td>',

				

            		"status"=>'<td>

					

										

                                      <div class="">

                                        <a class="bit-btn dropdown-toggle" id="menu2" type="button" data-toggle="dropdown" data-id="'.$row['id'].'">

                                            <span>

                                                '.$row["status"].'

                                                <span class="caret"></span>

                                            </span>

                                        </a>

                                        <div class="dropdown-menu dropdown-menu-mega">

                                            <ul class="row">

                                              '.$statusCombo.'

                                            </ul>                                    

                                        </div>

                                      </div>                                         

                                    <input type="hidden" class="status '.strtolower(str_replace(" ","_",$row["status"])).' dropdown">    

                                    </td>',				

                                    

            		//"status"=>$row['status'],

                	"shnm"=>$row['shnm'],

                    "otc"=>$row['otc'],

                    "mrc"=>$row['mrc'],

        			"dealdate"=>$row['dealdate'],

        			"fldt"=>$row['fldt'],

        			"accmger"=>$row['accmger'],

				    "lost_rsn"=>$row['lost_rsn'],

					"forcast"=>$row['forcast'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

            	);

        } 

		

		

    }
    
    

    else if($action=="issue")

    {

        $stagesStr = 'SELECT * FROM dealtype order by sl';



		$stageResult = $conn->query($stagesStr);

		if ($stageResult->num_rows > 0){

			while($stageRow = $stageResult->fetch_assoc()){

				$thisClass = str_replace(" ","_",$stageRow['name']);

				$stageCombo .= '<li class="col-xs-6"><a href="javascript:void(0)" data-stageid="'.$stageRow['id'].'" class="'.strtolower($thisClass).'">'.$stageRow['name'].'</a></li>';

				

			 }

		}

		

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (t.`tikcketno` like '%".$searchValue."%' or  date_format(t.`issuedate`,'%d/%b/%Y') like '%".$searchValue."%' or  t.`issuedetails` like '%".$searchValue."%' or assigned like '%".$searchValue."%' or 

                  t.`sub` like '%".$searchValue."%' or o.name like '%".$searchValue."%' or t.`severity` like '%".$searchValue."%' or i.name like '%".$searchValue."%' or tp.name like '%".$searchValue."%' or sb.name like '%".$searchValue."%'

                 or  date_format(t.`probabledate`,'%d/%b/%Y') like '%".$searchValue."%'  or t.`severity` like '%".$searchValue."%' or h2.hrName like '%".$searchValue."%' 

                 or st.stausnm like '%".$searchValue."%' or h2.hrName like '%".$searchValue."%' or cn.name like '%".$searchValue."%' or concat_ws(' ',emp.`firstname`,emp.`lastname`) like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT t.`id` id,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%b/%Y') issuedate

                                ,date_format(t.`probabledate`,'%d/%b/%Y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`, h1.hrName createby,
                        
                                p.name `severity`,concat_ws(' ',emp.`firstname`,emp.`lastname`) assigned,st.stausnm `status`,h2.hrName `reporter`,cn.name `channel`,h3.hrName `accountmanager` 
                        
                                FROM issueticket t left join organization o on t.organization=o.id 
                                
                                left join hr h1 on t.makeby=h1.id
                                
                                left join item i on t.product=i.id 
                                
                                left join issuetype tp on t.issuetype =tp.id 
                                
                                left join issuesubtype sb on t.issuesubtype=sb.id
                                
                                left join hr h2 on t.reporter=h2.id 
                                
                                left join hr h3 on t.accountmanager=h3.id 
                                
                                left join employee emp on t.`assigned`=emp.id
                                
                                left join issuestatus st on t.status=st.id 
                                
                                left join issuechannel cn on t.channel=cn.id 
                                
                                left join issuepriority p on t.severity=p.id where 1=1 ";



        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        //echo $strwithsearchquery;die;

        

        if($columnName == 'humbar'){

            $columnName = "t.`id`";

            $columnSortOrder = "DESC";

        }
        if($columnName == 'issuedate'){

            $columnName = "t.id";

        }

        $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $setdelurl="common/delobj.php?obj=issueticket&ret=issueadminList&mod=6&id=".$row['id'];

           $seturl="issueadmin.php?res=4&msg='Update Data'&id=".$row['id']."&mod=6";

          // $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';

          // $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';

          // $action='<span><a href="invoice.php?invid='.$row['invoiceno'].' &mod=2" class="invoice-view" title="View"><i class="fa fa-search"></i></a>

           //<a href="invoice_pdf.php?invid='.$row['invoiceno'].' &mod=2" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>

           //<a href="'.$payur.'" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>

           //<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a></span>';

           $humbar = '<div class="dropdown dropright">

                        <div class="fa fa-bars dropdown-toggle bar" id="dropdownMenuButton" data-toggle="dropdown"

                            aria-haspopup="Dropright" aria-expanded="false">

                

                        </div>

                        <div class="dropdown-menu postitem-status" aria-labelledby="dropdownMenuButton" name="action" id="action">

                                <a class="dropdown-item" hr ef="javscript:void(0)" onclick = "action(1,'.$row["id"].')">Pending</a><br>

                                <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(2,'.$row["id"].')">Resolved</a><br>
                                
                                <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(6,'.$row["id"].')">Testing</a><br>

                                <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(3,'.$row["id"].')">Copy</a><br>

                                <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(4,'.$row["id"].')">Edit</a><br>

                                <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(5,'.$row["id"].')">Delete</a>

                

                

                        </div>

                

                    </div>';
            $actbtn =   '<div class="btn-group">

                        <a class="dropdown-toggle dropdown_icon" data-toggle="dropdown">

                        <i class="fa fa-ellipsis-h"></i> </a>

                            <ul class="dropdown-menu">
                            
                                <li>

                                    <a class="dropdown-item" hr ef="javscript:void(0)" onclick = "action(1,'.$row["id"].')">

                                        <i class="fa fa-spinner"></i>Pending

                                    </a>

                                </li>
                                
                                <li>

                                    <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(6,'.$row["id"].')">

                                        <i class="fa fa-flag-checkered"></i>Testing

                                    </a>

                                </li>
                                
                                <li>

                                    <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(2,'.$row["id"].')">

                                        <i class="fa fa-check-circle-o"></i>Resolved

                                    </a>

                                </li>
                                
                                
                                <li>

                                    <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(3,'.$row["id"].')">

                                        <i class="fa fa-copy"></i>Copy

                                    </a>

                                </li>

                                <li>

                                    <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(4,'.$row["id"].')">

                                        <i class="fa fa-edit"></i>Edit

                                    </a>

                                </li>

                                <li>

                                    <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(5,'.$row["id"].')">

                                        <i class="fa fa-trash"></i>Delete

                                    </a>

                                </li>  

                                                

                            </ul>

                        </div>';

            if($row['probabledate'] == ''){

                $cdate = "Still Active";

            }else{

                $cdate = $row['probabledate'];

            }        

                    

           

            $data[] = array(

                    "humbar"=>$actbtn,

                    //"tikcketno"=>$columnName,

                    "tikcketno"=>$row['tikcketno'],

            		"organization"=>$row['organization'],

            		"sub"=>$row['sub'],

            		"issuedate"=>$row['issuedate'],

        			"probabledate"=>$cdate,

            		"product"=>$row['product'],

    				"issuetype"=>$row['issuetype'],

    				"issuesubtype"=>$row['issuesubtype'],

            		"severity"=>$row['severity'],

            		"assigned"=>$row['assigned'],

            		"status"=>$row['status'],

            		"createby"=>$row["createby"],

            		"createdt"=>$row["issuedate"],

        			"reporter"=>$row['reporter'],

            		"channel"=>$row['channel'],

    				"accountmanager"=>$row['accountmanager'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'" target = "_blank">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete('.$row["id"].');return false;"  href="'. $setdelurl.'" >Delete</a>'

            	);

        } 

    }

    else if($action=="issuecus")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (t.`tikcketno` like '%".$searchValue."%' or  date_format(t.`issuedate`,'%d/%b/%Y') like '%".$searchValue."%' 

        	or t.`sub` like '%".$searchValue."%' or t.`severity` like '%".$searchValue."%' 

                 or st.stausnm like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT t.`id` ,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%b/%Y') issuedate

        ,date_format(t.`probabledate`,'%d/%b/%Y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`

,p.name `severity`, t.`assigned`,'New' stg,st.stausnm `status`,h2.hrName `reporter`,cn.name `channel`,h3.hrName `accountmanager` 

FROM issueticket t left join organization o on t.organization=o.id

left join item i on t.product=i.id 

left join issuetype tp on t.issuetype =tp.id 

left join issuesubtype sb on t.issuesubtype=sb.id

left join hr h2 on t.reporter=h2.id 

left join hr h3 on t.accountmanager=h3.id

left join issuestatus st on t.status=st.id 

left join issuechannel cn on t.channel=cn.id 

left join issuepriority p on t.severity=p.id, contact orc where orc.organization = o.orgcode and orc.id = ".$_SESSION["customer"];





        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

         $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="issuecustomer.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

           $setdelurl="common/delobj.php?obj=issueticket&ret=issuecustomerList&mod=7&id=".$row['id'];

          // $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';

          // $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';

          // $action='<span><a href="invoice.php?invid='.$row['invoiceno'].' &mod=2" class="invoice-view" title="View"><i class="fa fa-search"></i></a>

           //<a href="invoice_pdf.php?invid='.$row['invoiceno'].' &mod=2" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>

           //<a href="'.$payur.'" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>

           //<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a></span>';

           

            $data[] = array(

                    "tikcketno"=>$row['tikcketno'],

            		"issuedate"=>$row['issuedate'],

            		"sub"=>$row['sub'],

            		"severity"=>$row['severity'],

        			"stg"=>$row['stg'],

            		"status"=>$row['status'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

            	);

        } 

    }
    
 else if($action=="coa")
    {
        
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (glno like '%".$searchValue."%' or  glnm like '%".$searchValue."%' )";
        }
        
        if($coalvl >0)
        {
        	$searchQuery = $searchQuery ." and (lvl =$coalvl)";
        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE `status` in( 'A','1')  and `oflag`='N' ";
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        //$empQuery=$strwithsearchquery;
        //$empRecords = mysqli_query($con, $empQuery);

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
       if($searchValue != '' or $coalvl >0)
        {
            $empRecords = mysqli_query($con, $strwithsearchquery);
            $data = array();
            $sl = 1;
            while ($row = mysqli_fetch_assoc($empRecords)) 
            {
                $glLvl1 = $row["lvl"];
                
               if($row["dr_cr"] == 'D'){
                   $type = "Debit";
               }else{
                   $type = "Credit";
               }
               
               if($row["isposted"] == 'P'){
                   $isposted = "YES";
               }else{
                   $isposted = "NO";
               }
               
               $seturl="coa.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";
    
              $setdelurl="common/delobj.php?obj=coa&ret=coaList&mod=7&id=".$row['id'];
              
              $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			if($glLvl1==2){$glm="<span class=\"lvl-2\">".$row['glnm']."</span>";}
			if($glLvl1==3){$glm="<span class=\"lvl-3\">".$row['glnm']."</span>";}
            if($glLvl1==4){$glm="<span class=\"lvl-4\">".$row['glnm']."</span>";}
            if($glLvl1==5){$glm="<span class=\"lvl-5\">".$row['glnm']."</span>";}
    
                $data[] = array(
    
                        "id"=>$sl,//$searchQuery,//
    
                		"glno"=>$row['glno'],//$strwithsearchquery,//
    
                		"glnm"=>$glm,//$row['glnm'],
    
                		"ctlgl"=>$row['ctlgl'],
    
            			"isposted"=>$isposted,
    
                		"type"=>$type,
                		
                		"lvl"=>$row['lvl'],
                		
                		"opbal"=>number_format($row['opbal'],2),
                		
                		"closingbal"=>number_format($row['closingbal'],2),
    
                		"action"=> getGridBtns($btns)
    
                	);
                
                $sl++;
            }
        }
        else
        {
            $empQuery=$strwithoutsearchquery." and lvl = 1 "; //order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage; $fixrow = $row;
            $empRecords = mysqli_query($con, $empQuery);
            $data = array();
            $sl = 1;
            while ($row = mysqli_fetch_assoc($empRecords)) 
            {
                $glLvl1 = $row["glno"];
                
               if($row["dr_cr"] == 'D'){
                   $type = "Debit";
               }else{
                   $type = "Credit";
               }
               
               if($row["isposted"] == 'P'){
                   $isposted = "YES";
               }else{
                   $isposted = "NO";
               }
               
               $seturl="coa.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";
    
              $setdelurl="common/delobj.php?obj=coa&ret=coaList&mod=7&id=".$row['id'];
              
              $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
                $data[] = array(
                    
                        "rowclass"=> 'l-1',
    
                        "id"=>$sl, //$coalvl,//
    
                		"glno"=>$row['glno'],//$totalRecordwithFilter
    
                		"glnm"=>"<span class=\"lvl-1\">".$row['glnm']."</span>",
    
                		"ctlgl"=>$row['ctlgl'],
    
            			"isposted"=>$isposted,
    
                		"type"=>$type,
                		
                		"lvl"=>$row['lvl'],
                		
                		"opbal"=>number_format($row['opbal'],2),
                		
                		"closingbal"=>number_format($row['closingbal'],2),
    
                		"action"=> getGridBtns($btns),
                	);
                
                $sl++;
                
                //Level 2
                $qry1 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl1."'   and `oflag`='N'";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                $result1 = mysqli_query($con, $qry1);
                while ($row1 = mysqli_fetch_assoc($result1)) 
                {
                    $glLvl2 = $row1["glno"];
                    
                    if($row1["dr_cr"] == 'D'){
                       $type = "Debit";
                   }else{
                       $type = "Credit";
                   }
                   
                   if($row1["isposted"] == 'P'){
                       $isposted = "YES";
                   }else{
                       $isposted = "NO";
                   }
                   
                   $seturl="coa.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=7";
        
                  $setdelurl="common/delobj.php?obj=coa&ret=coaList&mod=7&id=".$row1['id'];
                  
                  $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
        
                    $data[] = array(
                        
                            "rowclass"=> 'l-2',
        
                            "id"=>$sl,
        
                    		"glno"=>$row1['glno'],
        
                    		"glnm"=>"<span class=\"lvl-2\">".$row1['glnm']."</span>", //&nbsp; &nbsp; &nbsp;
        
                    		"ctlgl"=>$row1['ctlgl'],
        
                			"isposted"=>$isposted,
        
                    		"type"=>$type,
                    		
                    		"lvl"=>$row1['lvl'],
                    		
                    		"opbal"=>number_format($row1['opbal'],2),
                    		
                    		"closingbal"=>number_format($row1['closingbal'],2),
        
                    		"action"=> getGridBtns($btns),
        
                    );
                    
                    $sl++;
                    
                    //Level 3
                    $qry2 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl2."' and `oflag`='N' ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                    $result2 = mysqli_query($con, $qry2);
                    while ($row2 = mysqli_fetch_assoc($result2)) 
                    {
                        $glLvl3 = $row2["glno"];
                        
                        if($row2["dr_cr"] == 'D'){
                           $type = "Debit";
                       }else{
                           $type = "Credit";
                       }
                       
                       if($row2["isposted"] == 'P'){
                           $isposted = "YES";
                       }else{
                           $isposted = "NO";
                       }
                       
                       $seturl="coa.php?res=4&msg='Update Data'&id=".$row2['id']."&mod=7";
            
                      $setdelurl="common/delobj.php?obj=coa&ret=coaList&mod=7&id=".$row2['id'];
                      
                      $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
            
                        $data[] = array(
                            
                                "rowclass"=> 'l-3',
            
                                "id"=>$sl,
            
                        		"glno"=>$row2['glno'],
            
                        		"glnm"=>"<span class=\"lvl-3\">".$row2['glnm']."</span>", //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            
                        		"ctlgl"=>$row2['ctlgl'],
            
                    			"isposted"=>$isposted,
            
                        		"type"=>$type,
                        		
                        		"lvl"=>$row2['lvl'],
                        		
                        		"opbal"=>number_format($row2['opbal'],2),
                        		
                        		"closingbal"=>number_format($row2['closingbal'],2),
                        		
                        		"action"=> getGridBtns($btns),
            
                        );
                        
                        $sl++;
                        
                        //Level 4
                        $qry3 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl3."' and `oflag`='N' ";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                        $result3 = mysqli_query($con, $qry3);
                        while ($row3 = mysqli_fetch_assoc($result3)) 
                        {
                            $glLvl4 = $row3["glno"];
                            
                            if($row3["dr_cr"] == 'D'){
                               $type = "Debit";
                           }else{
                               $type = "Credit";
                           }
                           
                           if($row3["isposted"] == 'P'){
                               $isposted = "YES";
                           }else{
                               $isposted = "NO";
                           }
                           
                           $seturl="coa.php?res=4&msg='Update Data'&id=".$row3['id']."&mod=7";
                
                           $setdelurl="common/delobj.php?obj=coa&ret=coaList&mod=7&id=".$row3['id'];
                           
                           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
                
                            $data[] = array(
                                    
                                    "rowclass"=> 'l-4',
                
                                    "id"=>$sl,
                
                            		"glno"=>$row3['glno'],
                
                            		"glnm"=>"<span class=\"lvl-4\">".$row3['glnm']."</span>", //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                
                            		"ctlgl"=>$row3['ctlgl'],
                
                        			"isposted"=>$isposted,
                
                            		"type"=>$type,
                            		
                            		"lvl"=>$row3['lvl'],
                            		
                            		"opbal"=>number_format($row3['opbal'],2),
                            		
                            		"closingbal"=>number_format($row3['closingbal'],2),
                
                            		"action"=> getGridBtns($btns),
                
                            );
                            
                            $sl++;
                            
                            //Level 5
                            $qry4 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl4."' and `oflag`='N' ";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                            $result4 = mysqli_query($con, $qry4);
                            while ($row4 = mysqli_fetch_assoc($result4)) 
                            {
                                $glLvl5 = $row4["glno"];
                                
                                if($row4["dr_cr"] == 'D'){
                                   $type = "Debit";
                               }else{
                                   $type = "Credit";
                               }
                               
                               if($row4["isposted"] == 'P'){
                                   $isposted = "YES";
                               }else{
                                   $isposted = "NO";
                               }
                               
                               $seturl="coa.php?res=4&msg='Update Data'&id=".$row4['id']."&mod=7";
                    
                              $setdelurl="common/delobj.php?obj=coa&ret=coaList&mod=7&id=".$row4['id'];
                              
                              $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
                    
                                $data[] = array(
                                    
                                        "rowclass"=> 'l-5',
                    
                                        "id"=>$sl,
                    
                                		"glno"=>$row4['glno'],
                    
                                		"glnm"=>"<span class=\"lvl-5\">".$row4['glnm']."</span>", //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    
                                		"ctlgl"=>$row4['ctlgl'],
                    
                            			"isposted"=>$isposted,
                    
                                		"type"=>$type,
                                		
                                		"lvl"=>$row4['lvl'],
                                		
                                		"opbal"=>number_format($row4['opbal'],2),
                                		
                                		"closingbal"=>number_format($row4['closingbal'],2),
                    
                                		"action"=> getGridBtns($btns),
                    
                                );
                                
                                $sl++;
                            }
                        }
                    }
                }
                	
    
            } 
        
            
        }

    }
    else if($action=="coafinance")
    {
        
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (glno like '%".$searchValue."%' or  glnm like '%".$searchValue."%' )";
        }
        
        if($coalvl >0)
        {
        	$searchQuery = $searchQuery ." and (lvl =$coalvl)";
        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE `status` = 'A' ";
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        //$empQuery=$strwithsearchquery;
        //$empRecords = mysqli_query($con, $empQuery); 

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
       if($searchValue != '' or $coalvl >0)
        {
            $empRecords = mysqli_query($con, $strwithsearchquery);
            $data = array();
            $sl = 1;
            while ($row = mysqli_fetch_assoc($empRecords)) 
            {
                $glLvl1 = $row["glno"];
                
               if($row["dr_cr"] == 'D'){
                   $type = "Debit";
               }else{
                   $type = "Credit";
               }
               
               if($row["isposted"] == 'P'){
                   $isposted = "YES";
               }else{
                   $isposted = "NO";
               }
               
               if($row["oflag"] == 'Y'){
                   $isfinanced = "YES";
               }else{
                   $isfinanced = "NO";
               }
               
               $seturl="coafinance.php?res=4&msg='Update Data'&id=".$row['id']."&mod=17";
    
              $setdelurl="common/delobj.php?obj=coa&ret=coafinanceList&mod=17&id=".$row['id'];
              
              $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
    
                $data[] = array(
    
                        "id"=>$sl,//$searchQuery,//
    
                		"glno"=>$row['glno'],//$strwithsearchquery,//
    
                		"glnm"=>$row['glnm'],
    
                		"ctlgl"=>$row['ctlgl'],
    
            			"isposted"=>$isposted,
            			"isfinanced"=>$isfinanced,
    
                		"type"=>$type,
                		
                		"lvl"=>$row['lvl'],
                		
                		"opbal"=>number_format($row['opbal'],2),
                		
                		"closingbal"=>number_format($row['closingbal'],2),
    
                		"action"=> getGridBtns($btns)
    
                	);
                
                $sl++;
            }
        }
        else 
        {
            $empQuery=$strwithoutsearchquery." and lvl = 1 "; //order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage; $fixrow = $row;
            $empRecords = mysqli_query($con, $empQuery);
            $data = array();
            $sl = 1;
            while ($row = mysqli_fetch_assoc($empRecords)) 
            {
                $glLvl1 = $row["glno"];
                
               if($row["dr_cr"] == 'D'){
                   $type = "Debit";
               }else{
                   $type = "Credit";
               }
               
               if($row["isposted"] == 'P'){
                   $isposted = "YES";
               }else{
                   $isposted = "NO";
               }
              
               if($row["oflag"] == 'Y'){
                   $isfinanced = "YES";
               }else{
                   $isfinanced = "NO";
               }
               
               $seturl="coafinance.php?res=4&msg='Update Data'&id=".$row['id']."&mod=17";
    
              $setdelurl="common/delobj.php?obj=coa&ret=coafinanceList&mod=17&id=".$row['id'];
              
              $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
                $data[] = array(
                    
                        "rowclass"=> 'l-1',
    
                        "id"=>$sl, //$coalvl,//
    
                		"glno"=>$row['glno'],//$totalRecordwithFilter
    
                		"glnm"=>"<span class=\"lvl-1\">".$row['glnm']."</span>",
    
                		"ctlgl"=>$row['ctlgl'],
    
            			"isposted"=>$isposted,
            			"isfinanced"=>$isfinanced,
    
                		"type"=>$type,
                		
                		"lvl"=>$row['lvl'],
                		
                		"opbal"=>number_format($row['opbal'],2),
                		
                		"closingbal"=>number_format($row['closingbal'],2),
    
                		"action"=> getGridBtns($btns),
                	);
                
                $sl++;
                
                //Level 2
                $qry1 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl1."' ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                $result1 = mysqli_query($con, $qry1);
                while ($row1 = mysqli_fetch_assoc($result1)) 
                {
                    $glLvl2 = $row1["glno"];
                    
                    if($row1["dr_cr"] == 'D'){
                       $type = "Debit";
                   }else{
                       $type = "Credit";
                   }
                   
                   if($row1["isposted"] == 'P'){
                       $isposted = "YES";
                   }else{
                       $isposted = "NO";
                   }
                   if($row1["oflag"] == 'Y'){
                   $isfinanced = "YES";
                   }else{
                       $isfinanced = "NO";
                   }
                   $seturl="coafinance.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=17";
        
                  $setdelurl="common/delobj.php?obj=coa&ret=coafinanceList&mod=17&id=".$row1['id'];
                  
                  $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
        
                    $data[] = array(
                        
                            "rowclass"=> 'l-2',
        
                            "id"=>$sl,
        
                    		"glno"=>$row1['glno'],
        
                    		"glnm"=>"<span class=\"lvl-2\">".$row1['glnm']."</span>", //&nbsp; &nbsp; &nbsp;
        
                    		"ctlgl"=>$row1['ctlgl'],
        
                			"isposted"=>$isposted,
                			"isfinanced"=>$isfinanced,
        
                    		"type"=>$type,
                    		
                    		"lvl"=>$row1['lvl'],
                    		
                    		"opbal"=>number_format($row1['opbal'],2),
                    		
                    		"closingbal"=>number_format($row1['closingbal'],2),
        
                    		"action"=> getGridBtns($btns),
        
                    );
                    
                    $sl++;
                    
                    //Level 3
                    $qry2 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl2."' ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                    $result2 = mysqli_query($con, $qry2);
                    while ($row2 = mysqli_fetch_assoc($result2)) 
                    {
                        $glLvl3 = $row2["glno"];
                        
                        if($row2["dr_cr"] == 'D'){
                           $type = "Debit";
                       }else{
                           $type = "Credit";
                       }
                       
                       if($row2["isposted"] == 'P'){
                           $isposted = "YES";
                       }else{
                           $isposted = "NO";
                       }
                       
                       if($row2["oflag"] == 'Y'){
                       $isfinanced = "YES";
                       }else{
                           $isfinanced = "NO";
                       }
                       $seturl="coafinance.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=17";
            
                      $setdelurl="common/delobj.php?obj=coa&ret=coafinanceList&mod=17&id=".$row1['id'];
                      
                      $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
            
                        $data[] = array(
                            
                                "rowclass"=> 'l-3',
            
                                "id"=>$sl,
            
                        		"glno"=>$row2['glno'],
            
                        		"glnm"=>"<span class=\"lvl-3\">".$row2['glnm']."</span>", //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            
                        		"ctlgl"=>$row2['ctlgl'],
            
                    			"isposted"=>$isposted,
                    			"isfinanced"=>$isfinanced,
            
                        		"type"=>$type,
                        		
                        		"lvl"=>$row2['lvl'],
                        		
                        		"opbal"=>number_format($row2['opbal'],2),
                        		
                        		"closingbal"=>number_format($row2['closingbal'],2),
                        		
                        		"action"=> getGridBtns($btns),
            
                        );
                        
                        $sl++;
                        
                        //Level 4
                        $qry3 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl3."'";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                        $result3 = mysqli_query($con, $qry3);
                        while ($row3 = mysqli_fetch_assoc($result3)) 
                        {
                            $glLvl4 = $row3["glno"];
                            
                            if($row3["dr_cr"] == 'D'){
                               $type = "Debit";
                           }else{
                               $type = "Credit";
                           }
                           
                           if($row3["isposted"] == 'P'){
                               $isposted = "YES";
                           }else{
                               $isposted = "NO";
                           }
                           
                           if($row3["oflag"] == 'Y'){
                           $isfinanced = "YES";
                           }else{
                               $isfinanced = "NO";
                           }
                           $seturl="coafinance.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=17";
                
                          $setdelurl="common/delobj.php?obj=coa&ret=coafinanceList&mod=17&id=".$row1['id'];
                      
                           
                           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
                
                            $data[] = array(
                                    
                                    "rowclass"=> 'l-4',
                
                                    "id"=>$sl,
                
                            		"glno"=>$row3['glno'],
                
                            		"glnm"=>"<span class=\"lvl-4\">".$row3['glnm']."</span>", //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                
                            		"ctlgl"=>$row3['ctlgl'],
                
                        			"isposted"=>$isposted,
                        			"isfinanced"=>$isfinanced,
                
                            		"type"=>$type,
                            		
                            		"lvl"=>$row3['lvl'],
                            		
                            		"opbal"=>number_format($row3['opbal'],2),
                            		
                            		"closingbal"=>number_format($row3['closingbal'],2),
                
                            		"action"=> getGridBtns($btns),
                
                            );
                            
                            $sl++;
                            
                            //Level 5
                            $qry4 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`,`oflag`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl4."'";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                            $result4 = mysqli_query($con, $qry4);
                            while ($row4 = mysqli_fetch_assoc($result4)) 
                            {
                                $glLvl5 = $row4["glno"];
                                
                                if($row4["dr_cr"] == 'D'){
                                   $type = "Debit";
                               }else{
                                   $type = "Credit";
                               }
                               
                               if($row4["isposted"] == 'P'){
                                   $isposted = "YES";
                               }else{
                                   $isposted = "NO";
                               }
                               
                               if($row4["oflag"] == 'Y'){
                               $isfinanced = "YES";
                               }else{
                                   $isfinanced = "NO";
                               }
                               $seturl="coafinance.php?res=4&msg='Update Data'&id=".$row1['id']."&mod=17";
                    
                              $setdelurl="common/delobj.php?obj=coa&ret=coafinanceList&mod=17&id=".$row1['id'];
                      
                              
                              $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
                    
                                $data[] = array(
                                    
                                        "rowclass"=> 'l-5',
                    
                                        "id"=>$sl,
                    
                                		"glno"=>$row4['glno'],
                    
                                		"glnm"=>"<span class=\"lvl-5\">".$row4['glnm']."</span>", //&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                    
                                		"ctlgl"=>$row4['ctlgl'],
                    
                            			"isposted"=>$isposted,
                            			"isfinanced"=>$isfinanced,
                    
                                		"type"=>$type,
                                		
                                		"lvl"=>$row4['lvl'],
                                		
                                		"opbal"=>number_format($row4['opbal'],2),
                                		
                                		"closingbal"=>number_format($row4['closingbal'],2),
                    
                                		"action"=> getGridBtns($btns),
                    
                                );
                                
                                $sl++;
                            }
                        }
                    }
                }
                	
    
            } 
        
            
        }

    }
    
    else if($action=="announce")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (an.announceid like '%".$searchValue."%' or  c.name like '%".$searchValue."%' or date_format(an.announcedt,'%d/%b/%Y') like '%".$searchValue."%' or 

                  an.title like '%".$searchValue."%' or o.name like '%".$searchValue."%' or an.announce like '%".$searchValue."%' or i.name like '%".$searchValue."%' or tp.name like '%".$searchValue."%' or sb.name like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT an.id, an.announceid,c.name catagory,date_format(an.announcedt,'%d/%b/%Y') announcedt,an.title,an.announce,o.name organization FROM announce an left join announcecatagory c on an.catagory=c.id left join organization o on an.organization=o.id WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

         $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="announcement.php?res=4&msg='Update Data'&id=".$row['id']."&mod=6";

          

            $data[] = array(

                    "announceid"=>$row['announceid'],

            		"catagory"=>$row['catagory'],

            		"announcedt"=>$row['announcedt'],

            		"title"=>$row['title'],

        			"announce"=>$row['announce'],

            		"organization"=>$row['organization'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            	);

        } 

    }

    else if($action=="sms")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.name like '%".$searchValue."%' or  s.`contact` like '%".$searchValue."%' or s.`msg` like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select s.id,o.name `organization`, s.`contact`, s.`msg` from announcesms s left join organization o on s.organization=o.id WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

         $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="sms.php?res=4&msg='Update Data'&id=".$row['id']."&mod=6";

          

            $data[] = array(

                    "organization"=>$row['organization'],

            		"contact"=>$row['contact'],

            		"msg"=>$row['msg'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            	);

        } 

    }
    else if($action=="store")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (name like '%".$searchValue."%' or  contact_name like '%".$searchValue."%' or `contact_number` like '%".$searchValue."%' or `address` like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT id,`name`,`contact_name`,`contact_number`,`address` FROM `branch` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

         $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="store.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
           
           $setdelurl="common/delobj.php?obj=branch&ret=storeList&mod=12&id=".$row['id'];

          $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "id"=>$sl,

            		"name"=>$row['name'],

            		"contact_name"=>$row['contact_name'],
            		
            		"contact_number"=>$row['contact_number'],
            		
            		"address"=>$row['address'],

            		"action"=> getGridBtns($btns), 

            	);
            $sl++;
        } 

    }
    
    else if($action=="assignshift")

    {
        
        $shift = $_GET["assign"];
        
        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and ( concat(emp.`firstname`,' ' ,emp.`lastname`) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, s.title shift, concat(emp.`firstname`,' ' ,emp.`lastname`) empname,DATE_FORMAT(a.`effectivedt`,'%d/%b/%Y') `effectivedt` , emp.employeecode,emp.id empid
                                FROM `assignshift` a LEFT JOIN Shifting s ON a.`shift` = s.id LEFT JOIN employee emp ON a.`empid` = emp.id 
                                WHERE 1=1 and (a.effectivedt between STR_TO_DATE('".$fd."','%d/%m/%Y') and  STR_TO_DATE('".$td."','%d/%m/%Y')) and (a.shift = $shift or $shift = 0)";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

         $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="assignshift.php?res=4&msg='Update Data'&id=".$row["empid"]."&mod=4";
           
           $setdelurl="common/delobj.php?obj=branch&ret=storeList&mod=12&id=".$row4['id'];

          $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
			);

            $data[] = array(

                    "id"=>$sl,

            		"employeecode"=>$row['employeecode'],

            		"empname"=>$row['empname'],
            		
            		"shift"=>$row['shift'],
            		
            		"effectivedt"=>$row['effectivedt'],

            		"action"=> getGridBtns($btns),

            	);
            $sl++;
        } 

    }

    

    else if($action=="jobarea")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (Title like '%".$searchValue."%' or  `Description` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `ID` id, `Title`, `Description` FROM `JobArea` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ID";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="jobarea.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=JobArea&ret=jobareaList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['Title'],

            		"description"=>$row['Description'],

                    "action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }
    
    else if($action=="department")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (name like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT  dept.id, dept.`name`, concat(emp.firstname, ' ', emp.lastname) empname FROM `department` dept left join employee emp on emp.id=dept.head WHERE dept.st=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "dept.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="department.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=department&ret=departmentList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "name"=>$row['name'],
                    
                    "empname"=>$row['empname'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }
    
    else if($action=="designation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (name like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT  id, `name` FROM `designation` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="designation.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=designation&ret=designationList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "name"=>$row['name'],
                    
                    "action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="jobtype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (Title like '%".$searchValue."%' or  `Description` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `ID` id, `Title`, `Description` FROM `JobType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ID";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="jobtype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=JobType&ret=jobtypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['Title'],

            		"description"=>$row['Description'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="actiontype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (Title like '%".$searchValue."%' or  `Description` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `ID` id, `Title`, `Description`, active FROM `ActionType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ID";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            if($row["active"] == 1){
                $active = "Active";
            }else{
                $active = "Inactive";
            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="actiontype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=ActionType&ret=actiontypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['Title'],

            		"description"=>$row['Description'],
            		
            		"active"=>$active,

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }
    
    else if($action=="requisition")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`requision_no` like '%".$searchValue."%' or  b.`name` like '%".$searchValue."% 'or  concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, DATE_FORMAT( a.date,'%d/%b/%Y') date, a.`requision_no`, b.name, concat(emp.firstname, ' ', emp.lastname) empname,a.status 
                                FROM `requision` a LEFT JOIN branch b ON a.`branch` = b.id LEFT JOIN employee emp ON a.`requision_by` = emp.id 
                                WHERE 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'date') $columnName = "a.date";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            if($row["status"]==1){
                $st = "Initiated";
            }else if($row["status"]==2){
                $st = "Accepted";
            }else if($row["status"]==3){
                $st = "Partially Accepted";
            }else{
                $st = "Declined";
            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="requisition.php?res=4&msg='Update Data'&id=".$row['id']."&mod=14";
           $setdelurl="common/delobj.php?obj=requision&ret=requisitionList&mod=14&id=".$row['id']; 
           
           if($row["status"] == 1){
                $delmsg = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delete</a>";
                //generate button array
    			$btns = array(
    			    array('edit','requisition.php?res=4&msg=Update Data&id='.$row['id'].'&mod=14','class="btn btn-info btn-xs"  title="Edit"	  '),
    				array('delete','common/delobj.php?obj=requision&ret=requisitionList&mod=14&id='.$row['id'],'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
    			);
           }else{
               $delmsg = "<a class='btn btn-info btn-xs'>Can't Delete</a>";
               //generate button array
    			$btns = array(
    			    array('edit','requisition.php?res=4&msg=Update Data&id='.$row['id'].'&mod=14','class="btn btn-info btn-xs"  title="Edit"	  '),
    			    array('delete','javascript:void(0)','class="btn btn-info btn-xs griddelbtn" disabled title=" Can not be Deleted" '),
    			
    			);
           }
           

            $data[] = array(

                    "sl"=>$sl,

                    "date"=>$row['date'],

            		"requision_no"=>$row['requision_no'],
            		
            		"name"=>$row["name"],
            		
            		"empname"=>$row["empname"],
            		
            		"st"=>$st,

            		"action"=>getGridBtns($btns)

            	);

            $sl++;

        } 

    }
    
    else if($action=="rfq")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (rfq.rfq like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT rfq.id, rfq.rfq, DATE_FORMAT( rfq.date,'%d/%b/%Y') date,concat(emp.firstname, ' ', emp.lastname) empname, rfq.`st` 
                                FROM `rfq` rfq LEFT JOIN employee emp ON emp.id = rfq.rfq_by WHERE 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "rfq.id";
        if($columnName == 'date') $columnName = "rfq.date";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            if($row["st"]==0){
                $st = "Initiated";
            }else if($row["st"]==1){
                $st = "Approved";
            }else if($row["st"]==3){
                $st = "Asked";
            }else if($row["st"]==4){
                $st = "Recieved";
            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           //$seturl="requisition.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";
           //$setdelurl="common/delobj.php?obj=rfq&ret=rfqList&mod=2&id=".$row['id'];
           $seturl="rfq.php?res=4&msg='Update Data'&id=".$row['id']."&mod=14";
           $setdelurl="";
           
           $setdelurl="common/delobj.php?obj=rfq&ret=rfqList&mod=14&id=".$row['id'];
           $setViewUrl="";
           $setVendorUrl="rfq_vendor.php?res=4&msg='Vendor Data'&id=".$row['id']."&mod=14";
           $setSendUrl="";
           
           if($row["st"] == 0){
                $delmsg = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delete</a>";
           }else{
               $delmsg = "<a class='btn btn-info btn-xs'>Can't Delete</a>";
           }
           

            $data[] = array(

                    "sl"=>$sl,
                    
                    "rfq"=>$row['rfq'],

                    "date"=>$row['date'],

            		"empname"=>$row['empname'],
            		
            		"st"=>$st,
            		
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>$delmsg,
            		
            		//"print"=>'<a class="btn btn-info btn-xs"  href="'. $setPrintUrl.'">Print</a>',
            		
            		"view"=>'<a class="btn btn-info btn-xs"  href="'. $setViewUrl.'">View</a>',
            		
            		"vendor"=>'<a class="btn btn-info btn-xs"  href="'. $setVendorUrl.'">Assign Vendor</a>',
            		
            		"send"=>'<a class="btn btn-info btn-xs"  href="'. $setSendUrl.'">Email</a>',

            	);

            $sl++;

        } 

    }
    else if($action=="pr_qoutation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.rfq like '%".$searchValue."%' or org.name like '%".$searchValue."% '  or i.name like '%".$searchValue."% ')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, a.quotation, b.rfq, org.name vendor, i.name product, a.`date`,a.`order_qty`,a.`offered_qty`,a.`quated_price`, a.`item_spec`, a.st

                                FROM `rfq_vendor` a LEFT JOIN rfq_details b ON a.rfq = b.id LEFT JOIN organization org ON a.`vendor_id` = org.id 
                                LEFT JOIN item i ON a.`product` = i.id WHERE 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $actbtn = "";
            
            if($row["st"]==0){
                $st = "Initiated";
                $actbtn = "<select name = 'act'> <option value = 1> Accpet </option>  <option value = 0> Decline </option> </select>";
            }else if($row["st"]==1){
                $st = "Approved";
            }else if($row["st"]==2){
                $st = "Delivered";
            }else if($row["st"]==3){
                $st = "Declined";
            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="./common/quotation_action.php?res=1&id=".$row['id']."&mod=14";
           $setdelurl="./common/quotation_action.php?res=0&id=".$row['id']."&mod=14";
           
           
           /*if($row["status"] == 1){
                $delmsg = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delete</a>";
           }else{
               $delmsg = "<a class='btn btn-info btn-xs'>Can't Delete</a>";
           }*/
           

            $data[] = array(

                    "sl"=>$sl,
                    
                    "quotation"=>$row['quotation'],
                    
                    "rfq"=>$row['rfq'],
                    
                    "vendor"=>$row['vendor'],
                    
                    "product"=>$row['product'],

                    "date"=>$row['date'],

            		"order_qty"=>$row['order_qty'],
            		
            		"offered_qty"=>$row['offered_qty'],
            		
            		"item_spec"=>$row['item_spec'],
            		
            		"quated_price"=>$row['quated_price'],
            		
            		"st"=>$st,
            		
            		"actbtn"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Accept</a>',

            		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Declined</a>',
            		
            	);

            $sl++;

        } 

    }
    
    else if($action=="approved_quotation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.rfq like '%".$searchValue."%' or org.name like '%".$searchValue."% '  or i.name like '%".$searchValue."% ')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, a.quotation, b.rfq, org.name vendor, i.name product, a.`date`,a.`order_qty`,a.`offered_qty`,a.`quated_price`, a.`item_spec`, a.st

                                FROM `rfq_vendor` a LEFT JOIN rfq_details b ON a.rfq = b.id LEFT JOIN organization org ON a.`vendor_id` = org.id 
                                LEFT JOIN item i ON a.`product` = i.id WHERE a.st = 1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $actbtn = "";
            
            if($row["st"]==0){
                $st = "Initiated";
                $actbtn = "<select name = 'act'> <option value = 1> Accpet </option>  <option value = 0> Decline </option> </select>";
            }else if($row["st"]==1){
                $st = "Approved";
            }else if($row["st"]==2){
                $st = "Delivered";
            }else if($row["st"]==3){
                $st = "Declined";
            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="./quotation_recommendation.php?res=4&id=".$row['id']."&mod=14";
           //$setdelurl="./common/quotation_action.php?res=0&id=".$row['id']."&mod=14";
           
           
           /*if($row["status"] == 1){
                $delmsg = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delete</a>";
           }else{
               $delmsg = "<a class='btn btn-info btn-xs'>Can't Delete</a>";
           }*/
           

            $data[] = array(

                    "sl"=>$sl,
                    
                    "quotation"=> $row["quotation"],
                    
                    "rfq"=>$row['rfq'],
                    
                    "vendor"=>$row['vendor'],
                    
                    "product"=>$row['product'],

                    "date"=>$row['date'],

            		"order_qty"=>$row['order_qty'],
            		
            		"offered_qty"=>$row['offered_qty'],
            		
            		//"item_spec"=>$row['item_spec'],
            		
            		"quated_price"=>$row['quated_price'],
            		
            		"st"=>$st,
            		
            		"actbtn"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Recommendation</a>',

            		//"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Declined</a>',
            		
            	);

            $sl++;

        } 

    }
    
    else if($action=="recommendation_quot")

    {
        $rfq = $_GET["rfq"];
        $product = $_GET["product"];
        $emp = $_GET["emp"];

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`quotation` like '%".$searchValue."%' or org.name like '%".$searchValue."% '  or r.rfq like '%".$searchValue."% ' or i.name like '%".$searchValue."% ')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT ra.id, a.`quotation`, r.rfq, org.name, i.name product, a.`order_qty`, a.`offered_qty`, a.`item_spec`, a.`quated_price`, ra.recommendation, concat(emp.firstname, ' ', emp.lastname) emp, ra.st,
                                DATE_FORMAT( ra.approvedate,'%d/%b/%Y') approvedate, concat(emp1.firstname, ' ', emp1.lastname) emp1
        
                                FROM rfq_authorisation ra LEFT JOIN `rfq_vendor` a ON ra.rfq_vendor = a.id LEFT JOIN rfq_details r ON a.`rfq` = r.id LEFT JOIN  organization org ON org.id = a.`vendor_id` 
                                LEFT JOIN rfq rf ON rf.rfq=r.rfq LEFT JOIN employee emp ON ra.`recommender`= emp.id LEFT JOIN item i ON i.id = r.product LEFT JOIN employee emp1 ON ra.`approveby`= emp1.id
                                
                                WHERE 1=1 and (rf.id = $rfq or $rfq = 0) and (i.id = $product or $product = 0) and (emp.id = $emp or $emp = 0) ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ra.id";
        if($columnName == 'actdate') $columnName = "ra.approvedate";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $actbtn = "";
            
            if($row["st"]==0){
                $st = "Initiated";
                
                $seturl="./common/update_recom.php?res=1&id=".$row['id']."&mod=14";
                $setdelurl="./common/update_recom.php?res=2&id=".$row['id']."&mod=14";
                
                $actbtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$seturl >Accept</a>";
                $delbtn ="<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Decline</a>";
                
            }else{
                
                if($row["st"]==1){
                    $st = "Approved";
                }else if($row["st"]==3){
                    $st = "Delivered";
                }else if($row["st"]==2){
                    $st = "Declined";
                }
                
                $actbtn = "<a class='btn btn-info btn-xs' style='background-color:#808080'> Already Applied</a>";
                $delbtn ="<a class='btn btn-info btn-xs' style='background-color:#808080'>Already Applied</a>";
                
            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           //$seturl="./quotation_recommendation.php?res=4&id=".$row['id']."&mod=14";
           //$setdelurl="./common/quotation_action.php?res=0&id=".$row['id']."&mod=14";
           
           
           /*if($row["status"] == 1){
                $delmsg = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delete</a>";
           }else{
               $delmsg = "<a class='btn btn-info btn-xs'>Can't Delete</a>";
           }*/
           

            $data[] = array(

                    "sl"=>$sl,
                    
                    "quotation"=> $row["quotation"],
                    
                    "rfq"=>$row['rfq'],
                    
                    "vendor"=>$row['name'],
                    
                    "product"=>$row['product'],

                    "date"=>$row['date'],

            		"order_qty"=>$row['order_qty'],
            		
            		"offered_qty"=>$row['offered_qty'],
            		
            		"item_spec"=>$row['item_spec'],
            		
            		"quated_price"=>$row['quated_price'],
            		
            		"recommendation"=>$row['recommendation'],
            		
            		"emp" => $row["emp"],
            		
            		"actby" => $row["emp1"],
            		
            		"actdate" => $row["approvedate"],
            		
            		"st"=>$st,
            		
            		"actbtn"=>$actbtn,

            		"delbtn"=>$delbtn,
            		
            	);

            $sl++;

        } 

    }
    
    else if($action=="approved_recommendation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`quotation` like '%".$searchValue."%' or org.name like '%".$searchValue."% '  or r.rfq like '%".$searchValue."% ' or i.name like '%".$searchValue."% ')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT ra.id, a.`quotation`, r.rfq, org.name, i.name product, a.`order_qty`, a.`offered_qty`, a.`item_spec`, a.`quated_price`, ra.recommendation, concat(emp.firstname, ' ', emp.lastname) emp, ra.st
        
                                FROM rfq_authorisation ra LEFT JOIN `rfq_vendor` a ON ra.rfq_vendor = a.id LEFT JOIN rfq_details r ON a.`rfq` = r.id LEFT JOIN  organization org ON org.id = a.`vendor_id` LEFT JOIN rfq rf ON rf.rfq=r.rfq LEFT JOIN employee emp ON ra.`recommender`= emp.id
                                LEFT JOIN item i ON i.id = r.product
                                
                                WHERE ra.st=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ra.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $actbtn = "";
            
            
            $seturl="./raise_po.php?res=1&id=".$row['id']."&mod=14";
                
            $actbtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$seturl >Raise PO</a>";
                

            $data[] = array(

                    "sl"=>$sl,
                    
                    "quotation"=> $row["quotation"],
                    
                    "rfq"=>$row['rfq'],
                    
                    "vendor"=>$row['name'],
                    
                    "product"=>$row['product'],

                    "date"=>$row['date'],

            		"order_qty"=>$row['order_qty'],
            		
            		"offered_qty"=>$row['offered_qty'],
            		
            		"item_spec"=>$row['item_spec'],
            		
            		"quated_price"=>$row['quated_price'],
            		
            		"recommendation"=>$row['recommendation'],
            		
            		"emp" => $row["emp"],
            		
            		"actbtn"=>$actbtn,
            		
            	);

            $sl++;

        } 

    }
    
    if($action=="to")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and toid like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery = "SELECT id , `toid`, `makeby`, `makedt`, `st`, DATE_FORMAT( tansferdt,'%d/%b/%Y') tansferdt FROM `transfer_stock` WHERE 1 = 1";

        
        $strwithoutsearchquery=$basequery;

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery;

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

    
         $empQuery=$basequery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			if($row["st"] == 1){
			    $status= '<kbd class="inprogress">Pending</kbd>';
			}
			if($row["st"] == 0){
			    $status= '<kbd class="pending">Declined</kbd>';
			}
			if($row["st"] == 2){
			    $status= '<kbd class="completed">Accepted</kbd>';
			}
            $setInvurl="to.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $setInvurl.'"  ><i class="fa fa-edit"></i></a>';
            
            //generate button arrayView Quotation
			$btns = array(
				array('view','to_view.php','class="show-invoice btn btn-info btn-xs"  title="View Transfer Order"	data-code="'.$row['toid'].'"  '),
				array('edit',$setInvurl,'class="btn btn-info btn-xs"  title="Edit"	  '),
			);
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"toid"=>$row['toid'],
					
					"tansferdt"=>$row['tansferdt'],

            		"status"=>$status,
            		
            		"action"=>getGridBtns($btns), 

            	);

        } 

    }
    
    if($action=="io")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and io.ioid like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery = "SELECT io.`id`, io.`ioid`,DATE_FORMAT( io.iodt,'%d/%b/%Y') `iodt`,DATE_FORMAT( io.deliverydt,'%d/%b/%Y') `deliverydt`,
                    iw.`name`, iw.`address`, io.st, io.note 
                    FROM `issue_order` io LEFT JOIN issue_warehouse iw ON iw.id=io.issue_warehouse WHERE 1 = 1";

        
        $strwithoutsearchquery=$basequery;

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery;

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

    
         $empQuery=$basequery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			if($row["st"] == 1){
			    $status= '<kbd class="inprogress">Pending</kbd>';
			}
			if($row["st"] == 0){
			    $status= '<kbd class="pending">Declined</kbd>';
			}
			if($row["st"] == 2){
			    $status= '<kbd class="completed">Accepted</kbd>';
			}
            $setInvurl="io.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
            $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $setInvurl.'"  ><i class="fa fa-edit"></i></a>';
            
            //generate button arrayView Quotation
			$btns = array(
				array('view','io_view.php','class="show-invoice btn btn-info btn-xs"  title="View Issue Order"	data-code="'.$row['ioid'].'"  '),
				array('edit',$setInvurl,'class="btn btn-info btn-xs"  title="Edit"	  '),
			);
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"ioid"=>$row['ioid'],
					
					"iodt"=>$row['iodt'],
					
					"deliverydt"=>$row['deliverydt'],
					
					"name"=>$row['name'],
					
					"address"=>$row['address'],
					
					"note"=>$row['note'],

            		"status"=>$status,
            		
            		"action"=>getGridBtns($btns), 

            	);

        } 

    }
    
    else if($action=="periodic_qc")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.name like '%".$searchValue."%' or i.barcode like '%".$searchValue."%' b.name like '%".$searchValue."%' )"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery = "SELECT i.name prodnm, i.barcode, b.name warehouse, ch.freeqty,DATE_FORMAT(ch.qadt,'%d/%b/%Y') qadt, ch.id, i.image 
                    FROM `chalanstock` ch LEFT JOIN item i ON i.id=ch.product LEFT JOIN branch b ON b.id=ch.storerome  
                    WHERE (ch.qadt IS null OR ch.qadt < DATE_SUB(CURDATE(), INTERVAL 3 MONTH)) AND ch.storerome <> 0 AND ch.freeqty > 0 ";

        
        $strwithoutsearchquery=$basequery;

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery;

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

    
         $empQuery=$basequery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6
        
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			
            $sentqc="./common/addperiodic_qc.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
            $urlas='<a class="btn btn-info btn-xs" title="Sent To QC"  href="'. $sentqc.'"  >Sent To QC</a>';
            
            if (strlen($row["image"])>0) {

        		$photo="assets/images/products/300_300/".$row["image"];

        		}else{

        			$photo="assets/images/products/placeholder.png";

        		}
//             //generate button arrayView Quotation
// 			$btns = array(
// 				array('view','io_view.php','class="show-invoice btn btn-info btn-xs"  title="View Issue Order"	data-code="'.$row['ioid'].'"  '),
// 				array('edit',$setInvurl,'class="btn btn-info btn-xs"  title="Edit"	  '),
// 			);
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"photo"=>'<img src='.$photo.' width="50" height="50">',  
					
					"prodnm"=>$row['prodnm'],
					
					"barcode"=>$row['barcode'],
					
					"warehouse"=>$row['warehouse'],
					
					"freeqty"=>$row['freeqty'],
					
					"qadt"=>$row['qadt'],
            		
            		"action"=> $urlas, 

            	);

        } 
 
    }
    
    else if($action=="raise_po")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`poid` like '%".$searchValue."%' or org.name like '%".$searchValue."% '  or a.delivery_address like '%".$searchValue."% ' or a.note like '%".$searchValue."% ')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, a.`poid`,DATE_FORMAT( a.podate,'%d/%b/%Y') `podate`, a.`delivery_address`, a.`note`, a.`st`, org.name, i.name product
                                FROM `rfqpo` a LEFT JOIN organization org ON org.id = a.`vendor` LEFT JOIN rfqpo_details rd ON a.poid = rd.pono 
                                LEFT JOIN rfq_authorisation ra ON rd.rfq_auth = ra.id LEFT JOIN rfq_vendor rv ON ra.rfq_vendor = rv.id LEFT JOIN item i ON i.id = rv.product
                                WHERE 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'podate') $columnName = "a.podate";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $actbtn = "";
            $invoice = "";
            
            if($row["st"] == 0){
                $st = "Initiated";
            }else if($row["st"] == 1){
                $invoice="./rfq_invoice.php?res=1&id=".$row['id']."&mod=14";
                $st = "Fully Delivered";
            }else if($row["st"] == 2){
                $invoice="./rfq_invoice.php?res=1&id=".$row['id']."&mod=14";
                $st = "Partially Delivery";
            }else{
                $st = "Delivery Cancelled";
            }
            
            
            $seturl="./raise_po.php?res=1&id=".$row['id']."&mod=14";
            $setdelurl="./rfqpo_delivery.php?res=1&id=".$row['id']."&mod=14";
                
            $actbtn = '<a data-invid="'.$row['id'].'" href="invoice_rfq.php?invid='.$row['id'].'&mod=3" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
            $deliverybtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Quality Check</a>";
            $invoicebtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$invoice >Create Invoice</a>";
                

            $data[] = array(

                    "sl"=>$sl,
                    
                    "pono"=> $row["poid"],
                    
                    "podate"=>$row['podate'],
                    
                    "product"=>$row['product'],
                    
                    "vendor"=>$row['name'],
                    
                    "delivery"=>$row['delivery_address'],

                    "note"=>$row['note'],
            		
            		"st" => $st,
            		
            		"view"=>$actbtn,
            		
            		"deliverybtn"=>$deliverybtn,
            		
            		"invoice"=>$invoicebtn,
            		
            	);

            $sl++;

        } 

    }
    else if($action=="rfq_invoice")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`poid` like '%".$searchValue."%' or org.name like '%".$searchValue."% '  or a.delivery_address like '%".$searchValue."% ' or a.note like '%".$searchValue."% ')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, a.`poid`,DATE_FORMAT( a.podate,'%d/%b/%Y') `podate`, a.`st`, org.name,  ri.invoiceno, ri.note
                                FROM rfq_invoice ri LEFT JOIN `rfqpo` a ON ri.rfqpo=a.id LEFT JOIN organization org ON org.id = a.`vendor` 
                                WHERE 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'date') $columnName = "a.podate";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $actbtn = "";
            $invoice = "";
            
            if($row["st"] == 0){
                $st = "Initiated";
            }else if($row["st"] == 1){
                $invoice="./rfq_invoice.php?res=1&id=".$row['id']."&mod=14";
                $st = "Fully Delivered";
            }else if($row["st"] == 2){
                $invoice="./rfq_invoice.php?res=1&id=".$row['id']."&mod=14";
                $st = "Partially Delivery";
            }else{
                $st = "Delivery Cancelled";
            }
            
            $qrytot = "SELECT SUM(`invoice_amount`) amt FROM `rfq_invoice_details` WHERE `invoiceno` = '".$row["invoiceno"]."'";// echo $qrytot;die;
            $res = mysqli_query($con, $qrytot);
            while($rowtot = mysqli_fetch_assoc($res)){
                $totamt = $rowtot["amt"];
            }
            
            
            $seturl="./raise_po.php?res=1&id=".$row['id']."&mod=14";
            $setdelurl="./rfqpo_delivery.php?res=1&id=".$row['id']."&mod=14";
                
            //$actbtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$seturl >view</a>";
            $deliverybtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delivery</a>";
            $invoicebtn = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$invoice >Create Invoice</a>";
                

            $data[] = array(

                    "sl"=>$sl,
                    
                    "invoiceno"=> $row["invoiceno"],
                    
                    "date"=>$row['podate'],
                    
                    "vendor"=>$row['name'],
                    
                    "amt"=>$totamt,

                    "note"=>$row['note'],
                    
                    "st"=>"Initiated",
            		
            		"view"=>$actbtn,
            	);

            $sl++;

        } 

    }
    
    else if($action=="proc_qoutation_old")

    {
        $totalRecords = 0;
        $data = array();
        $sl = 1;
                
        $qryVendor = "SELECT a.id, a.`rfq`, a.`vendor` FROM `rfq_details` a LEFT JOIN rfq b ON a.rfq = b.rfq WHERE a.vendor is NOT NULL and b.st = 1 order by id desc";
        $resultVendor = mysqli_query($con, $qryVendor);
        while ($rowVendor = mysqli_fetch_assoc($resultVendor)){
            $rfq = $rowVendor["id"];
            $vendorList = substr($rowVendor["vendor"], 0, -1);
            $vendorArray = array();
            $vendorArray = explode(",", $vendorList);
            //echo count($vendorArray);die;
            
            for($i = 0; $i < count($vendorArray); $i++){
                $searchQuery = "";

                if($searchValue != '')
        
                {
        
                	$searchQuery = " and (a..rfq like '%".$searchValue."%' or  concat(emp.firstname, ' ', emp.firstname) like '%".$searchValue."% ')";
        
                }
        
                ## Total number of records without filtering   #c.`id`,
        
                $strwithoutsearchquery="SELECT a.`rfq`, org.name vendornm, b.date, b.st, concat(emp.firstname, ' ', emp.lastname) empname 
                                        FROM `rfq_details` a LEFT JOIN rfq b ON a.rfq = b.rfq LEFT JOIN employee emp ON b.rfq_by = emp.id, organization org 
                                        WHERE a.id = '$rfq' and org.id = '".$vendorArray[$i]."'";
        
                
        
                // $sel = mysqli_query($con,$strwithoutsearchquery);
        
                // $totalRecords = $sel->num_rows;
                $totalRecords++;
        
                ## Total number of records with filtering # c.`id`,
        
                $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
          // s.`status`<>6 
        
                $sel = mysqli_query($con,$strwithsearchquery);
        
                $totalRecordwithFilter = $sel->num_rows;
        
                if($columnName == 'sl') $columnName = "a.id";
        
                $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder;
                //echo $empQuery;die;
                $empRecords = mysqli_query($con, $empQuery);
        
                while ($row = mysqli_fetch_assoc($empRecords)) 
        
                {
                    if($row["st"]==0){
                        $st = "Initiated";
                    }else if($row["st"]==1){
                        $st = "Approved";
                    }else if($row["st"]==3){
                        $st = "Asked";
                    }else if($row["st"]==4){
                        $st = "Recieved";
                    }
        
                   //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";
        
                   $seturl="proc_qoutation.php?res=4&msg='Update Data'&id=".$vendorArray[$i]."&rfqid=".$rfq."&mod=14";
                   //$setdelurl="common/delobj.php?obj=rfq&ret=rfqList&mod=2&id=".$row['id'];
                   
                   
                   /*if($row["status"] == 1){
                        $delmsg = "<a class='btn btn-info btn-xs' onclick='javascript:confirmationDelete($(this));return false;'  href=$setdelurl >Delete</a>";
                   }else{
                       $delmsg = "<a class='btn btn-info btn-xs'>Can't Delete</a>";
                   }*/
                   
        
                    $data[] = array(
        
                            "sl"=>$sl,
                            
                            "vendor"=>$row['vendornm'],
                            
                            "rfq"=>$row['rfq'],
        
                            "date"=>$row['date'],
        
                    		"empname"=>$row['empname'],
                    		
                    		"st"=>$st,
                    		
                    		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Action</a>',
        
                    		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>',
                    	);
        
                    $sl++;
        
                } 

            }
        }
        
    }
    
    else if($action=="proc_qoutation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`rfq` like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) empname like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, a.`rfq`, DATE_FORMAT( a.date,'%d/%b/%Y') `date`, concat(emp.firstname, ' ', emp.lastname) empname, DATE_FORMAT( a.validity_date,'%d/%b/%Y') `validity_date` 
                                FROM `rfq` a LEFT JOIN employee emp ON a.`rfq_by` = emp.id 
                                WHERE a.`st` = 1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'date') $columnName = "a.date";
        if($columnName == 'validity_date') $columnName = "a.validity_date";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="proc_qoutation.php?res=4&msg='Update Data'&id=".$row["id"]."&mod=14";
           //$setdelurl="common/delobj.php?obj=requision&ret=requisitionList&mod=14&id=".$row['id']; 
           

            $data[] = array(

                    "sl"=>$sl,
                    
                    "rfq"=>$row['rfq'],

                    "date"=>$row['date'],
            		
            		"empname"=>$row["empname"],
            		
            		"validity_date"=>$row["validity_date"],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Action</a>',

            		//"del"=>$delmsg

            	);

            $sl++;

        } 

    }
    
    else if($action=="pending_requisition")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`requision_no` like '%".$searchValue."%' or  b.`name` like '%".$searchValue."% 'or  concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, DATE_FORMAT( a.date,'%d/%b/%Y') date, a.`requision_no`, b.name, concat(emp.firstname, ' ', emp.lastname) empname,a.status 
                                FROM `requision` a LEFT JOIN branch b ON a.`branch` = b.id LEFT JOIN employee emp ON a.`requision_by` = emp.id 
                                WHERE a.status=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'date') $columnName = "a.date";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            
           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="pending_requisition.php?res=4&msg='Update Data'&id=".$row['id']."&mod=14";
           $setdelurl="common/delobj.php?obj=requision&ret=requisitionList&mod=14&id=".$row['id']; 
           

            $data[] = array(

                    "sl"=>$sl,

                    "date"=>$row['date'],

            		"requision_no"=>$row['requision_no'],
            		
            		"name"=>$row["name"],
            		
            		"empname"=>$row["empname"],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Action</a>',

            		//"del"=>$delmsg

            	);

            $sl++;

        } 

    }

    else if($action=="itemcat")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (name like '%".$searchValue."%' or  `description` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT catid, makedt,  id, name  FROM itmCat WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="itemcat.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";

        
			$setdelurl="common/delobj.php?obj=itmCat&ret=itemcatList&mod=12&id=".$row['id'];
			
			
			$btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "makedt"=>$makedt,
 					"catid"=>$row['catid'],
                    "title"=>$row['name'],

            		//"description"=>$row['Description'],

            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		//"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'
				
					"action_buttons"=>getGridBtns($btns),

            	);

            $sl++;

        } 

    }
    
    else if($action=="issue_warehouse")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (name like '%".$searchValue."%' or  `address` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT *  FROM issue_warehouse WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="issue_warehouse.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";

        
			$setdelurl="common/delobj.php?obj=issue_warehouse&ret=issue_warehouseList&mod=12&id=".$row['id'];
			
			
			$btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
    
            $data[] = array(

                    "id"=>$sl,
 					"name"=>$row['name'],
                    "address"=>$row['address'],
					"action_buttons"=>getGridBtns($btns),

            	);

            $sl++;

        } 

    }

    else if($action=="hraction")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname, ' ', e.lastname) like '%".$searchValue."%' or dept.name like '%".$searchValue."%' or act.Title like '%".$searchValue."%' or ja.Title like '%".$searchValue."%'
        	                or desi.name like '%".$searchValue."%' or jt.Title like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id id, a.type, concat(e.firstname, ' ', e.lastname) empname, act.Title acttype,DATE_FORMAT(a.`actiondt`,'%d/%b/%Y') `actiondt`, dept.name deptname, 
                                
                                ja.Title janame, desi.name designation, jt.Title jtname, concat(emp2.firstname, ' ', emp2.lastname) reportto, e.employeecode 

        FROM `hraction` a LEFT JOIN employee e ON a.`hrid` = e.id 

        LEFT JOIN ActionType act ON a.`actiontype` = act.ID 

        LEFT JOIN department dept ON a.`postingdepartment` = dept.id 

        LEFT JOIN JobArea ja ON a.`jobarea` = ja.ID 

        LEFT JOIN designation desi ON a.`designation` = desi.id 

        LEFT JOIN JobType jt ON a.`jobtype` = jt.ID 

        LEFT JOIN employee emp2 ON a.`reportto` = emp2.id WHERE a.st = 1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'actdt') $columnName = "a.actiondt";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";
			
			if($row["type"] == 1){
			    $type = "Posting";
			} else if($row["type"] == 2){
			    $type = "Punishment";
			}else{
			    $type = "Appreciation";
			}
			
			$seturl="hraction_v2.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4&type=".$row["type"];

           $setdelurl="common/delobj.php?obj=hraction&ret=hractionList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "empname"=>$row['empname'],
                    
                    "empcode"=>$row['employeecode'],
                    
                    "type" => $type,

            		"acttype"=>$row['acttype'],

            		"actdt"=>$row['actiondt'],

            		"dept"=>$row['deptname'],

            		"jobarea"=>$row['janame'],

            		"jobtype"=>$row['jtname'],

            		"reportto"=>$row['reportto'],

            		"desig"=>$row['designation'],

            		"action"=> getGridBtns($btns), 
            	);

            $sl++;

        } 

    }

    else if($action=="benefittype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%' or `Description` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT b.`id`, b.`title`, b.`benifitnature`, p.title `benefittype`, b.`Description` FROM `benifitype` b LEFT JOIN pakage p ON b.benifittype = p.id
                                WHERE b.st = 0";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "b.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            if($row["benifitnature"] == "1"){$benefitnature = "Addition";}else{$benefitnature = "Deduction";}
            $benefittype = $row["benefittype"];
            
           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="benifittype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=benifitype&ret=benifittypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"benefitnature"=>$benefitnature,

            		"benefittype"=>$benefittype,

            		"details"=>$row['Description'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="compansationSetup")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%' or `Description` like '%".$searchValue."%' or `basic` like '%".$searchValue."%' or `increment` like '%".$searchValue."%' or `maxgross` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `title`, `basic`, `increment`, `maxgross`, `Description` FROM `compansationSetup` WHERE st = 0";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="compansationSetup.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=compansationSetup&ret=compansationSetupList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"basic"=>$row['basic'],

            		"increment"=>$row['increment'],

            		"maxgross"=>$row['maxgross'],

            		"details"=>$row['Description'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

     else if($action=="pakage")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%' or  `remarks` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT  id, `title`, `remarks` FROM `pakage` WHERE 1=1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ID";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="pakage.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=pakage&ret=pakageList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"description"=>$row['remarks'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="packageSetup")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.title like '%".$searchValue."%' or p.`title` like '%".$searchValue."%' or c.`title` like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id`, a.`Title` mtitle, p.`title` ptitle, c.`title` compansation from pakageSetup a

        left join pakage p on p.id=a.pakage LEFT JOIN compansationSetup c ON a.`scale`= c.id    WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="packageSetup.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=pakageSetup&ret=packageSetupList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['mtitle'],

                    "pack"=>$row['ptitle'],

            		"compansation"=>$row['compansation'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="hrcompansation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (emp.firstname like '%".$searchValue."%' or emp.lastname like '%".$searchValue."%' or c.`Title` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id`, concat(emp.firstname, ' ', emp.lastname) empname, c.Title com, a.increment, a.privilagedfund,  DATE_FORMAT(a.effectivedate, '%d/%b/%Y') effectivedate

        FROM `hrcompansation` a 

        LEFT JOIN employee emp ON a.`hrid` = emp.id 

        LEFT JOIN compansationSetup c ON a.`compansation` = c.id 

        WHERE a.st = 1";

        

        /* $strwithoutsearchquery="SELECT a.`id`, concat(emp.firstname, ' ', emp.lastname) empname, c.title com

        , b.title btype, a.`privilagedfund`,DATE_FORMAT(a.effectivedate,'%d/%b/%Y') `effectivedate` , a.`conditions`, a.`Description`, a.`increment` 

        FROM `hrcompansation` a 

        LEFT JOIN employee emp ON a.`hrid` = emp.id 

        LEFT JOIN compansationSetup c ON a.`compansation` = c.id 

        LEFT JOIN pakage b ON a.`pakage`=b.id WHERE a.st = 0";

        */

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        //echo $empQuery;die;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="hrcompansation.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=hrcompansation&ret=hrcompansationList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "empname"=>$row['empname'],

            		"compansation"=>$row['com'],
            		
            		"privilagedfund"=>$row['privilagedfund'],
            		
            		"increment"=>$row['increment'],

            		"effectivedate"=>$row["effectivedate"],

            		/*"btype"=>$row['btype'],

            		"condition"=>$row["conditions"],

            		"details"=>$row['Description'],*/

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }
    
    else if($action=="gross")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (emp.firstname like '%".$searchValue."%' or emp.lastname like '%".$searchValue."%' or emp.employeecode like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT concat(emp.firstname, ' ', emp.lastname) empname,emp.employeecode, gs.gross, DATE_FORMAT(gs.effectivedate, '%d/%b/%Y') effectivedate,emp.id 
                                FROM `employee` emp LEFT JOIN `gross_salary` gs ON emp.id=gs.empid
                                WHERE 1 = 1";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "emp.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        //echo $empQuery;die;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           $seturl="gross.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "empname"=>$row['empname'],
                    
                    "employeecode"=>$row['employeecode'],

            		"gross"=>$row['gross'],
            		
            		"effectivedate"=>$row['effectivedate'],
            		
            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }
    
    else if($action=="gross_history")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (emp.firstname like '%".$searchValue."%' or emp.lastname like '%".$searchValue."%' or emp.employeecode like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT concat(emp.firstname, ' ', emp.lastname) empname, emp.employeecode, gs.gross, DATE_FORMAT(gs.effectivedate, '%d/%b/%Y') effectivedate,gs.id 
                                FROM  `gross_salary_history` gs  LEFT JOIN `employee` emp ON emp.id=gs.empid
                                WHERE 1 = 1";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "gs.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        //echo $empQuery;die;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
           $seturl="gross_hist.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";
           $setdelurl="common/delobj.php?obj=gross_salary_history&ret=gross_histList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "empname"=>$row['empname'],
                    
                    "employeecode"=>$row['employeecode'],

            		"gross"=>$row['gross'],
            		
            		"effectivedate"=>$row['effectivedate'],
            		
            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="holidaytype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%' or  `remarks` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id` id, `title`, `remarks` FROM `holidayType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="holidaytype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=holidayType&ret=holidaytypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"description"=>$row['remarks'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="holiday")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.title like '%".$searchValue."%'  or a.details like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id`, b.`title`, DATE_FORMAT(a.`date`, '%d/%b/%Y') `date`,a.`details` FROM `Holiday` a LEFT JOIN `holidayType` b on a.holidaytype=b.id WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'action_dt') $columnName = "a.date";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="holiday.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=Holiday&ret=holidayList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "htype"=>$row['title'],
                    
                    "details"=>$row['details'],

            		"action_dt"=>$row['date'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="leavetype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%' or  `remarks` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id` id, `title`,`day`, `remarks`, day_contractual FROM `leaveType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="leavetype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=leaveType&ret=leavetypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

                    "day"=>$row['day'],
                    
                    "day_contractual" => $row['day_contractual'], 

            		"description"=>$row['remarks'],

            		"action"=>getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="shifting")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id` id, `title` FROM `Shifting` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="shifting.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=Shifting&ret=shiftingList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "shifting"=>$row['title'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="pstype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `title` FROM `psType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="pstype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=psType&ret=pstypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="ps")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.title like '%".$searchValue."%' or a.title like '%".$searchValue."%' or a.weight like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, b.title bt, a.`title` at, a.`weight` FROM `performanceStandared` a LEFT JOIN psType b ON a.`standardtype` = b.id WHERE 1 = 1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="ps.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=performanceStandared&ret=psList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "st"=>$row['bt'],

                    "title"=>$row['at'],

                    "weight"=>$row['weight'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="kpivaluetype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `title` FROM `kpivalueType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="kpivaluetype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=kpivalueType&ret=kpivaluetypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="kpivalue")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.title like '%".$searchValue."%' or a.title like '%".$searchValue."%' or a.weight like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id id, b.title bt, a.title at, a.weight FROM `kpivalue`a LEFT JOIN `kpivalueType` b ON a.`kpivalueType` = b.`id` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="kpivalue.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=kpivalue&ret=kpivalueList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "kpivt"=>$row['bt'],

                    "title"=>$row['at'],

                    "weight"=>$row['weight'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="appraisaltype")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `title` FROM `appraisalType` WHERE 1=1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="appraisaltype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=appraisalType&ret=appraisaltypeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="officetime")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (title like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id` id, b.`title`, a.`start`, a.`end`, a.`delaytime`, a.`extendeddelay`, a.`latetime`, a.`absent` 

                                FROM `OfficeTime` a LEFT JOIN `Shifting` b ON a.`shift` = b.`id` WHERE a.`st` = 1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="officetime.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=OfficeTime&ret=officetimeList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "shift"=>$row['title'],

                    "starttime"=>$row['start'],

                    "endtime"=>$row['end'],

                    "delaytime"=>$row['delaytime'],

                    "edelaytime"=>$row['extendeddelay'],

                    "latetime"=>$row['latetime'],

                    "abstime"=>$row['absent'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="attendance")

    {

        $sdt = $_GET["sdt"];

        $edt = $_GET["edt"];

        

        $sdt = str_replace('/', '-', $sdt);

        $edt = str_replace('/', '-', $edt);

        

        

        $sdt = date("Y-m-d", strtotime($sdt) );

        $edt = date("Y-m-d", strtotime($edt) );

        

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( concat(e.`firstname`, ' ', e.`lastname`) like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id,DATE_FORMAT(a.`date`,'%d/%b/%Y') `date`, a.`intime`, a.`outtime`, concat(e.`firstname`, ' ', e.`lastname`) empname FROM `attendance_test` a 

                                LEFT JOIN `hr` b ON a.`hrid` = b.`attendance_id` LEFT JOIN `employee` e ON b.emp_id = e.employeecode where a.date between '".$sdt."' AND '".$edt."' ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'date') $columnName = "a.date";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $empname = $row['empname'];

            if($empname == ''){

                $empname = "Administration";

            }

            $intime = $row['intime'];

            $intime = date("g:i a", strtotime($intime));

            $outtime = $row['outtime'];

            $outtime = date("g:i a", strtotime($outtime));

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="att.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=attendance&ret=attList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "name"=>$empname,

                    "intime"=>$intime,

                    "outtime"=>$outtime,

                    "date"=>$row['date'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    else if($action=="probation_report")

    {

        $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.effective_to) = ".$month;

        $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.effective_to) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' or d.name like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT CONCAT(emp.firstname, ' ', emp.lastname) AS empnm, emp.employeecode, d.name deptnm,
                                DATE_FORMAT( h.actiondt,'%e/%c/%Y') actiondt, DATE_FORMAT( h.effective_to,'%e/%c/%Y') effective_to
                                FROM hraction h LEFT JOIN ActionType act ON act.ID = h.actiontype LEFT JOIN JobType jt ON jt.ID = h.jobtype 
                                LEFT JOIN employee emp ON emp.id = h.hrid LEFT JOIN department d ON d.id=emp.department
                                WHERE h.actiontype = 1 AND jt.ID = 1 $monthqry $yearqry
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "h.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY h.hrid order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "empnm"=>$row["empnm"],

                    "employeecode"=>$row["employeecode"],

                    "deptnm"=>$row["deptnm"],

                    "actiondt"=>$row['actiondt'],

            		"effective_to"=>$row["effective_to"],

            	);

            $sl++;

        } 

    }
    
    else if($action=="contract_report")

    {

        $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.effective_to) = ".$month;

        $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.effective_to) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' or d.name like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT CONCAT(emp.firstname, ' ', emp.lastname) AS empnm, emp.employeecode, d.name deptnm,
                                DATE_FORMAT( h.actiondt,'%e/%c/%Y') actiondt, DATE_FORMAT( h.effective_to,'%e/%c/%Y') effective_to
                                FROM hraction h LEFT JOIN ActionType act ON act.ID = h.actiontype LEFT JOIN JobType jt ON jt.ID = h.jobtype 
                                LEFT JOIN employee emp ON emp.id = h.hrid LEFT JOIN department d ON d.id=emp.department
                                WHERE jt.ID = 5 $monthqry $yearqry
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "h.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "empnm"=>$row["empnm"],

                    "employeecode"=>$row["employeecode"],

                    "deptnm"=>$row["deptnm"],

                    "actiondt"=>$row['actiondt'],

            		"effective_to"=>$row["effective_to"],

            	);

            $sl++;

        } 

    }
    
    else if($action=="disciplinary_report")

    {

        $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.actiondt) = ".$month;

        $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.actiondt) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' or dept.name like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT concat(emp.firstname, ' ', emp.lastname) empnm, emp.employeecode, dept.name deptnm, h.remarks, 
                                DATE_FORMAT( h.actiondt,'%e/%c/%Y') actiondt, DATE_FORMAT( h.effective_to,'%e/%c/%Y') effective_to
                                FROM `hraction` h LEFT JOIN employee emp ON emp.id=h.hrid LEFT JOIN department dept ON emp.department=dept.id 
                                WHERE h.type = 2 $monthqry $yearqry
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "h.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "empnm"=>$row["empnm"],

                    "employeecode"=>$row["employeecode"],

                    "deptnm"=>$row["deptnm"],
                    
                    "remarks"=>$row["remarks"],

                    "actiondt"=>$row['actiondt'],

            		"effective_to"=>$row["effective_to"],

            	);

            $sl++;

        } 

    }
    
    else if($action=="appreciation_report")

    {

        $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.actiondt) = ".$month;

        $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.actiondt) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' or dept.name like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT concat(emp.firstname, ' ', emp.lastname) empnm, emp.employeecode, dept.name deptnm, h.remarks, 
                                DATE_FORMAT( h.actiondt,'%e/%c/%Y') actiondt, DATE_FORMAT( h.effective_to,'%e/%c/%Y') effective_to
                                FROM `hraction` h LEFT JOIN employee emp ON emp.id=h.hrid LEFT JOIN department dept ON emp.department=dept.id 
                                WHERE h.type = 3 $monthqry $yearqry
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "h.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "empnm"=>$row["empnm"],

                    "employeecode"=>$row["employeecode"],

                    "deptnm"=>$row["deptnm"],

                    "actiondt"=>$row['actiondt'],

            	);

            $sl++;

        } 

    }
    
    else if($action=="ap_pun_report")

    {

        $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.actiondt) = ".$month;

        $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.actiondt) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' or dept.name like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT CONCAT(emp.firstname, ' ', emp.lastname) AS empnm, emp.employeecode, h.id,dept.name deptnm, COUNT(CASE WHEN h.type = 2 THEN 1 END) AS punishment_count, 
                                COUNT(CASE WHEN h.type = 3 THEN 1 END) AS appreciation_count 
                                FROM hraction h LEFT JOIN employee emp ON emp.id = h.hrid LEFT JOIN department dept ON emp.department = dept.id 
                                WHERE 1=1 $monthqry $yearqry
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "h.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." GROUP BY emp.id, emp.firstname, emp.lastname, emp.employeecode order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "empnm"=>$row["empnm"],

                    "employeecode"=>$row["employeecode"],

                    "deptnm"=>$row["deptnm"],
                    
                    "appreciation_count"=>$row['appreciation_count'],

                    "punishment_count"=>$row['punishment_count'],

            	);

            $sl++;

        } 

    }
    
    else if($action=="dept_employee_report")

    {

        $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.actiondt) = ".$month;

        $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.actiondt) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or  emp.employeecode like '%".$searchValue."%' or dept.name like '%".$searchValue."%'
        	                or des.name like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT concat(emp.firstname, ' ', emp.lastname) empnm, emp.employeecode, h.actiondt, dept.name deptnm, des.name desnm, emp.office_contact, 
                                emp.bloodgroup, emp.presentaddress
                                FROM `employee` emp LEFT JOIN hraction h ON (h.hrid=emp.id AND h.actiontype=1) LEFT JOIN department dept ON dept.id = emp.department 
                                LEFT JOIN designation des ON des.id=emp.designation 
                                WHERE 1=1 $monthqry $yearqry
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "h.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "empnm"=>$row["empnm"],

                    "employeecode"=>$row["employeecode"],

                    "deptnm"=>$row["deptnm"],
                    
                    "desnm"=>$row["desnm"],
                    
                    "actiondt"=>$row['actiondt'],

                    "office_contact"=>$row['office_contact'],
                    
                    "presentaddress"=>$row["presentaddress"],
                    
                    "bloodgroup"=>$row['bloodgroup']

            	);

            $sl++;

        } 

    }
    
    else if($action=="promotion_history")

    {

        // $month = $_GET["month"]; if($month != 0) $monthqry = "AND MONTH(h.actiondt) = ".$month;

        // $year = $_GET["year"];   if($year != 0) $yearqry = "AND YEAR(h.actiondt) = ".$year;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( CONCAT(e.firstname, ' ', e.lastname) like '%".$searchValue."%' or  e.employeecode like '%".$searchValue."%' )";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT  
                                ha.hrid,e.employeecode, concat(e.firstname,e.lastname) emp
                                ,
                                (
                                SELECT d1.name  FROM hractionHist hh join designation d1 on hh.designation=d1.id where ha.hrid=hh.hrid and  hh.actiondt=
                                    (select max(hh1.actiondt) from hractionHist hh1 where hh.hrid=hh1.hrid and 					hh.actiontype=hh1.actiontype) 
                                 and hh.actiontype=ha.actiontype order by d1.name limit 1,1 
                                ) prevDesgnm
                                ,ha.actiondt promotionDate,ha.designation,d.name deisgNm
                                ,g.gross newgross
                                ,
                                ((SELECT gross FROM gross_salary_history gh where gh.empid=g.empid and gh.effectivedate=
                                	(select max(gh1.effectivedate) from gross_salary_history gh1 where 							gh.empid=gh1.empid)
                                )-g.gross) increment
                                FROM employee e left join hr on e.employeecode=hr.emp_id left join hraction ha on hr.id=ha.hrid left join designation d on ha.designation=d.id
                                left join gross_salary g on ha.hrid=g.empid
                                where ha.actiontype=4
                                ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "ha.actiondt";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        // echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $data[] = array(

                    "sl"=>$sl,

                    "emp"=>$row["emp"],

                    "employeecode"=>$row["employeecode"],

                    "prevDesgnm"=>$row["prevDesgnm"],
                    
                    "promotionDate"=>$row["promotionDate"],
                    
                    "deisgNm"=>$row['deisgNm'],

                    "increment"=>$row['increment'],
                    
                    "newgross"=>$row["newgross"],

            	);

            $sl++;

        } 

    }

    else if($action=="rptattendence")

    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt == ''){
            $fdt = date("d/m/Y");
            $tdt = date("1/m/Y");
        }
        
        $date_qry = " and d.dt between DATE_FORMAT('$fdt', '%d/%m/%Y') and DATE_FORMAT('$tdt', '%d/%m/%Y') ";

        if($emp_id != 0){
            $qryhrid = "SELECT h.id FROM employee emp LEFT JOIN hr h ON emp.employeecode = h.emp_id WHERE emp.id = ".$emp_id;
            $resulthrid = $conn->query($qryhrid);
            //echo $qryhrid;die;
            while($rowhrid = $resulthrid->fetch_assoc()){
                $hrid = $rowhrid["id"];
            }
        }else{
            $hrid = 0;
        }

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (DATE_FORMAT(u.dt,'%d/%b/%Y') like '%".$searchValue."%' or u.hrName like '%".$searchValue."%' or u.ofctime like'%".$searchValue."%' 

        	or u.shift like'%".$searchValue."%'  or u.entrytm like'%".$searchValue."%'  or u.exittime like'%".$searchValue."%')";

        }

        

        

        $fdeptqry = '';

        $fdept = $_GET["fdept"];

        if($fdept != ''){

            $fdeptqry = " and ha.postingdepartment=".$fdept;

        }

        

        $fstqry = '';

        $fst = $_GET["fst"];

        

        if($fst != ''){

            $fstqry = " and (case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else u.lv end)  else 'Present' end ) ='$fst'";

        }

        

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select u.id id,u.dt ,DATE_FORMAT(u.dt,'%d/%b/%Y') trdt,u.hrName,u.ofctime,u.shift, u.emp_id, u.hrnm

                                ,(select name from designation where id= ha.`designation`) desig
                                
                                ,(select name from department  where ID= ha.`postingdepartment`) dept
                                
                                ,(case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else u.lv end)  else 'Present' end ) sttus
                                
                                ,u.entrytm,u.exittime,TIMEDIFF(IFNULL(exittime,entrytm),entrytm) durtn from
                                
                                (
                                
                                select d.dt,h.id,h.hrName,h.emp_id,e.id eid, concat (e.firstname, ' ', e.lastname) hrnm
                                
                                ,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
                                
                                ,(select (case when max(outtime) is null then max(intime) when max(intime)>max(outtime) then  max(intime) else max(outtime) end)  from attendance where hrid=h.id and date=d.dt) exittime
                                
                                ,(select title from Shifting where id=(select shift from assignshifthist where empid=e.id 
                                
                                and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))shift
                                
                                ,(select concat(`start`,' to ',`end`)  from OfficeTime where shift=(select shift from assignshifthist where empid=e.id 
                                
                                and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))ofctime
                                
                                ,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id 
                                
                                and d.dt BETWEEN l.startday and l.endday) lv
                                
                                ,(SELECT ht.title FROM  Holiday h,holidayType ht where h.holidaytype=ht.id and h.`date`=d.dt) holiday
                                
                                from loggday d,hr h ,employee e
                                
                                where 
                                
                                h.emp_id=e.employeecode $date_qry
                                
                                ) u,hraction ha where u.eid=ha.hrid ";



#d.dt between '2021-06-25' and '2021-07-06'        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

        //$strwithsearchquery .= $fdeptqry;

        

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "u.dt";

        if($columnName == 'dt') $columnName = "u.dt";

        

        $empQuery=$strwithoutsearchquery.$searchQuery.$fdeptqry.$fstqry." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        echo $empQuery;die;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="hraction.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=hraction&ret=hractionList&mod=4&id=".$row['id'];
           
           $ddch = date("Y-d-m", strtotime($row["trdt"]) );
           
           $timestamp = strtotime($ddch);

            $day = date('D', $timestamp);

            $data[] = array(

                    "sl"=>$sl,

                    "dt"=>$row['trdt'],//$empQuery,//
                    
                    "day"=>$day,
                    
                    "emp_id"=>$row['emp_id'],

        			"hrName"=>$row['hrnm'],

            		"desig"=>$row['desig'],

            		"dept"=>$row['dept'],

            		"shift"=>$row['shift'],

            		"ofctime"=>$row['ofctime'],

            		"sttus"=>$row['sttus'],

            		"entrytm"=>$row['entrytm'],

            		"exittime"=>$row['exittime'],

            		"durtn"=>$row['durtn']

            	);

            $sl++;

        } 

    }

    

     else if($action=="rptsalarysheet")

    {
        
        $dept = $_GET["dept"];
        $desi = $_GET["desi"];
        $yr = $_GET["fd"];
        $mn = $_GET["td"];

        $searchQuery = "";
        

        if($searchValue != '')

        {

        	$searchQuery = " and (s.salaryyear like '%".$searchValue."%' or MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) like '%".$searchValue."%' or concat(e.firstname,e.lastname) like'%".$searchValue."%' 

        	or s.benft_1 like'%".$searchValue."%'  or s.benft_2 like'%".$searchValue."%'  or s.benft_3 like'%".$searchValue."%'  or s.benft_4 like'%".$searchValue."%'  or s.benft_5 like'%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT e.id,s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,' ',e.lastname) emp, e.employeecode empcode
                            ,s.benft_1 ,s.benft_2 ,s.benft_3 ,s.benft_4 ,s.benft_5 
                            ,s.benft_6 ,s.benft_7 ,s.benft_8 ,s.benft_9 ,s.benft_10,s.benft_11,s.privilage,s.total,s.notes
                            ,s.advance,s.loans,s.others
                            , dept.name deptname, desi.name desiname, s.approvest, s.id sid
                            FROM monthlysalary s  JOIN hr h  ON s.hrid=h.id and h.active_st=1  join employee e on h.emp_id=e.employeecode 
                            LEFT JOIN department dept ON dept.id = e.department 
                            LEFT JOIN designation desi ON desi.id = e.designation
                            where s.salaryyear='$yr' and s.salarymonth='$mn' and ($dept = e.department or $dept = 0) and ($desi = e.designation or $desi = 0)";

        
        
        $strwithoutsearchquery_test="SELECT s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,' ',e.lastname) emp, e.employeecode empcode
                            ,s.benft_1 ,s.benft_2 ,s.benft_3 ,s.benft_4 ,s.benft_5 
                            ,s.benft_6 ,s.benft_7 ,s.benft_8 ,s.benft_9 ,s.benft_10,s.benft_11,s.privilage
                            , dept.name deptname, desi.name desiname
                            FROM monthlysalary s left JOIN employee e ON s.hrid=e.id left JOIN hraction hra ON hra.hrid = s.hrid 
                            LEFT JOIN department dept ON dept.id = hra.postingdepartment 
                            LEFT JOIN designation desi ON desi.id = hra.designation
                            ";


#(  STR_TO_DATE(concat('01','/',s.salarymonth,'/',s.salaryyear),'%d/%b/%Y') BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y')      

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "u.id";

        if($columnName == 'yr') $columnName = "s.salaryyear";

        

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";
          $paySlipurl="rpt_paySlip.php?eid=".$row['id']."&yr=".$row['salaryyear']."&mn=$mn&mod=4";
          
          $btns = array(
				array('view',$paySlipurl,'class="btn btn-info btn-xs"  title="Pay Slip"'),
			);

			if($row["approvest"] == 0){
			    $seturl="edit_salary.php?res=4&msg='Update Data'&id=".$row['sid']."&mod=4";
			    
			    array_push($btns, array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"'));
			}


           $tot=$row['total'];//$row['benft_1']+$row['benft_2']+$row['benft_3']+$row['benft_4']-$row['benft_11'];
            $notes=$row['notes'];
            $data[] = array(

                    "yr"=>$row['salaryyear'],//$empQuery,//

        			"month"=>$row['mnth'],
        			
        			"empcode"=>$row['empcode'],

            		"emp"=>$row['emp'],
            		
            		"deptname"=>$row['deptname'],
            		
            		"desiname"=>$row['desiname'],

            		"benft_1"=>number_format($row['benft_1'],2),

            		"benft_2"=>number_format($row['benft_2'],2),

            		"benft_3"=>number_format($row['benft_3'],2),

            		"benft_4"=>number_format($row['benft_4'],2),

            		"benft_5"=>number_format($row['benft_5'],2),
            		"benft_6"=>number_format($row['benft_6'],2),
            		"benft_7"=>number_format($row['benft_7'],2),
            		"benft_8"=>number_format($row['benft_8'],2),
            		"benft_9"=>number_format($row['benft_9'],2),
            		"benft_10"=>number_format($row['benft_10'],2),
                    "benft_11"=>number_format($row['benft_11'],2),
                    "adv"=>number_format($row['advance'],2),
            		"loan"=>number_format($row['loans'],2),
                    "others"=>number_format($row['others'],2),
                    "privilage"=>number_format($row['privilage'],2),
            		"tot"=>number_format($tot,2),
                    "notes"=>$notes,
                    "action"=> getGridBtns($btns)
            	);

            $sl++;

        } 

    }

   

   else if($action=="rptleave")
    
    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and l.applieddate between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
        if($emp_id != 0){
            $qryhrid = "SELECT h.id FROM employee emp LEFT JOIN hr h ON emp.employeecode = h.emp_id WHERE emp.id = ".$emp_id;
            $resulthrid = $conn->query($qryhrid);
            //echo $qryhrid;die;
            while($rowhrid = $resulthrid->fetch_assoc()){
                $hrid = $rowhrid["id"];
            }
        }else{
            $hrid = 0;
        }

        $searchQuery = "";

        if($searchValue != '') 

        {

        	$searchQuery = " and (DATE_FORMAT(l.applieddate,'%d/%b/%Y') like '%".$searchValue."%' or h1.hrName like '%".$searchValue."%' or lt.title like'%".$searchValue."%' 

        	or h.hrName like'%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT l.hrid,h1.hrName, h1.emp_id ,(select name from designation where id= e.designation) desig ,(select name from department where id= e.department) dept ,
                                DATE_FORMAT(l.applieddate,'%d/%b/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%b/%Y') startday,
                                DATE_FORMAT(l.endday,'%d/%b/%Y') endday,h.hrName approver ,DATE_FORMAT(l.approvedate,'%d/%b/%Y') approvedate 
                                FROM `leave` l LEFT JOIN leaveType lt ON l.leavetype=lt.id LEFT JOIN hr h on l.approver=h.id LEFT JOIN hr h1 ON  l.hrid=h1.id  
                                LEFT JOIN employee e on h1.emp_id=e.employeecode 
                                WHERE (e.id = $emp_id or 0= $emp_id) $date_qry ";

#and l.applieddate BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y')

#d.dt between '2021-06-25' and '2021-07-06'        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "u.id";

        if($columnName == 'applydt') $columnName = "l.applieddate";
        if($columnName == 'startday') $columnName = "l.startday";
        if($columnName == 'endday') $columnName = "l.endday";
        if($columnName == 'approvedate') $columnName = "l.approvedate";

        

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="hraction.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=hraction&ret=hractionList&mod=4&id=".$row['id'];

            $data[] = array(

                    "applydt"=>$row['applydt'],//$empQuery,//

                    "hrid"=>$row['emp_id'],

        			"hrName"=>$row['hrName'],

            		"desig"=>$row['desig'],

            		"dept"=>$row['dept'],

            		"title"=>$row['title'],

            		"days"=>$row['days'],

            		"startday"=>$row['startday'],

            		"endday"=>$row['endday'],

            		"approver"=>$row['approver'],

            		"approvedate"=>$row['approvedate']

            	);

            $sl++;

        } 

    }
    
    else if($action=="leavehr")
    
    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and l.applieddate between DATE_FORMAT('$fdt', '%d/%b/%Y') and DATE_FORMAT('$tdt', '%d/%b/%Y') ";
        }else{
            $date_qry = "";
        }
        if($emp_id != 0){
            $qryhrid = "SELECT h.id FROM employee emp LEFT JOIN hr h ON emp.employeecode = h.emp_id WHERE emp.id = ".$emp_id;
            $resulthrid = $conn->query($qryhrid);
            //echo $qryhrid;die;
            while($rowhrid = $resulthrid->fetch_assoc()){
                $hrid = $rowhrid["id"];
            }
        }else{
            $hrid = 0;
        }

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (DATE_FORMAT(l.applieddate,'%d/%b/%Y') like '%".$searchValue."%' or h1.hrName like '%".$searchValue."%' or lt.title like'%".$searchValue."%' 

        	or h.hrName like'%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT l.id, l.hrid,h1.hrName, h1.emp_id ,des.name desig ,dept.name dept ,
                                DATE_FORMAT(l.applieddate,'%d/%b/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%b/%Y') startday,
                                DATE_FORMAT(l.endday,'%d/%b/%Y') endday,h.hrName approver ,DATE_FORMAT(l.approvedate,'%d/%b/%Y') approvedate,
                                l.reliveraction, l.relivercomments,DATE_FORMAT(l.releveddate,'%d/%b/%Y') releveddate, l.approveraction, l.approvercoments,
                                concat(reliemp.firstname, ' ', reliemp.lastname) relinm, l.st actst

                                FROM `leave` l LEFT JOIN leaveType lt ON l.leavetype=lt.id LEFT JOIN hr h on l.approver=h.id LEFT JOIN hr h1 ON  l.hrid=h1.id  
                                LEFT JOIN employee e on h1.emp_id=e.employeecode LEFT JOIN department dept ON dept.id=e.department LEFT JOIN designation des ON des.id=e.designation 
                                LEFT JOIN hr relihr ON  l.reliver=relihr.id LEFT JOIN employee reliemp ON reliemp.employeecode=relihr.emp_id
                                
                                WHERE (l.hrid = $hrid or 0= $hrid) $date_qry

        ";

#and l.applieddate BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y')

#d.dt between '2021-06-25' and '2021-07-06'        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "u.id";

        if($columnName == 'applydt') $columnName = "l.applieddate";
        if($columnName == 'startday') $columnName = "l.startday";
        if($columnName == 'endday') $columnName = "l.endday";
        if($columnName == 'approvedate') $columnName = "l.approvedate";

        

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";
           if($row["reliveraction"] == ''){
               $reliveraction = "Still pending";
           }else{
               $reliveraction = "Approved";
           }
           
           if($row["approveraction"] == ''){
               $approveraction = "Still pending";
           }else{
               $approveraction = "Approved";
           }
           
           if($row["actst"] == 1 || $row["actst"] == 2 || $row["actst"] == 3){
               $seturl="action_leave_hr.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";
               $actbtn = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">Action</a>';
           }
           else if($row["actst"] == 0){
               $actbtn = 'Declined';
           }else if($row["actst"] == 4){
               $actbtn = 'Accepted';
           }
           
           $leaveid = $row["id"];
        
                $inputDefImgData = array(

            	'TableName' => 'leave_documents',
            	'OrderBy' => 'id',
            	'ASDSOrder' => 'DESC',
            	'id' => '',
            	'image' => '',
            	'leaveid' => $leaveid
            	);
            	
            	listData($inputDefImgData,$imgDefData);
            	
            	if(count($imgDefData)>0){
                	$picturelist[$leaveid] ='<div class="ajax-img-up"><ul class="d-flex defect-img">';
                    foreach($imgDefData as $lidata){
                        $picturelist[$leaveid] .= '<li class="picbox"><a class="picture-preview"  href="../common/upload/leave_documents/'.$lidata['image'].'""><img src="../common/upload/leave_documents/default_thumb.jpg"></a></li>';
                    }
                    $picturelist[$leaveid] .='</ul></div>';
            	}
            	$inputDefImgData = "";
            	$imgDefData = "";

            $data[] = array(

                    "applydt"=>$row['applydt'],//$empQuery,//

                    "hrid"=>$row['emp_id'],

        			"hrName"=>$row['hrName'],

            		"desig"=>$row['desig'],

            		"dept"=>$row['dept'],

            		"title"=>$row['title'],

            		"days"=>$row['days'],

            		"startday"=>$row['startday'],

            		"endday"=>$row['endday'],
            		
            		"image"=>  $picturelist[$leaveid],
            		
            		"relinm"=>$row['relinm'],
            		
            		"reliveraction"=>$reliveraction,
            		
            		"relivercomments"=>$row['relivercomments'],
            		
            		"releveddate"=>$row['releveddate'],
            		
            		"approver"=>$row['approver'],
            		
            		"approveraction"=>$approveraction,
            		
            		"approvercoments"=>$row['approvercoments'],
            		
            		"approvedate"=>$row['approvedate'],

            		"action"=> $actbtn

            	);

            $sl++;

        } 

    }
    
    else if($action=="rpt_chalan_details")
    {
        
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and p.delivery_dt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.poid like  '%".$searchValue."%' or t.name  like '%".$searchValue."%' or pr.name like '%".$searchValue."%' 
        	                or i.barcode  like '%".$searchValue."%' or p.adviceno  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT p.poid,p.adviceno,DATE_FORMAT(p.orderdt,'%d/%b/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%d/%b/%Y') received_dt ,t.name cat,i.itemid,
                                pr.name product,i.qty,i.unitprice,i.amount,i.barcode,DATE_FORMAT(i.expirydt,'%d/%b/%Y') expirydt 
                                FROM po p LEFT JOIN poitem i ON p.poid=i.poid LEFT JOIN product pr ON pr.id=i.itemid LEFT JOIN itemtype t ON pr.catagory=t.id 
                                where 1=1 $dateqry
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        //Total Amount
        $qrytotal = "SELECT SUM(i.amount) as totalamount 
                    FROM  po p LEFT JOIN poitem i ON p.poid=i.poid LEFT JOIN product pr ON pr.id=i.itemid LEFT JOIN itemtype t ON pr.catagory=t.id
                    where 1=1 $dateqry  order by t.name,p.delivery_dt, p.poid,i.itemid";
                                
        $total_re = mysqli_query($con, $qrytotal);
        while ($row1 = mysqli_fetch_assoc($total_re)){
            $total_amount = $row1["totalamount"];
        }
        
        //echo $qrytotal;die;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'p.id';
        }
        if($columnName == 'orderdt'){
            $columnName = 'p.orderdt';
        }
        if($columnName == 'received_dt'){
            $columnName = 'p.delivery_dt';
        }
        if($columnName == 'expirydt'){
            $columnName = 'i.expirydt';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $gt = 0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $order_id=$row2["poid"];$adviceno=$row2["adviceno"]; $orderdt=$row2["orderdt"]; $received_dt=$row2["received_dt"];  
            $cat=$row2["cat"]; $product=$row2["product"]; $qty=$row2["qty"];$barcode=$row2["barcode"];$expdt=$row2["expirydt"];
            $unitprice=$row2["unitprice"];$amount=$row2["amount"]; $gt=$gt+$amount;
           
            $data[] = array(
                    "id"=> $sl,
                    "cat"=> $cat,
            		"orderdt"=> $orderdt,
            		"poid"=> $order_id,
            		"adviceno"=> $adviceno,
            		"received_dt"=> $received_dt,
            		"product"=> $product,
            		"barcode"=> $barcode,
            		"qty"=> number_format($qty,0),
            		"unitprice"=> number_format($unitprice,2),
            		"amount"=> number_format($amount,2),
            		"expirydt"=> $expdt
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($total_amount,2));
        
    }
    
    else if($action=="rpt_purchase")
    {
        
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and l.makedt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( l.`voucher_no` like  '%".$searchValue."%' or l.`pi_no`  like '%".$searchValue."%' or l.`supplier` like '%".$searchValue."%' 
        	                or l.`lc_tt_no`  like '%".$searchValue."%' or pr.barcode  like '%".$searchValue."%' or b.`name`  like '%".$searchValue."%'
        	                or h.hrName  like '%".$searchValue."%' or l.`gnr_no`  like '%".$searchValue."%' or pr.name  like '%".$searchValue."%'
        	                or bn.name  like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT l.id puid,l.`voucher_no`, DATE_FORMAT(l.`voucher_date`,'%d/%b/%Y') `voucher_date`,l.`pi_no`, DATE_FORMAT(l.`pi_date`,'%d/%b/%Y') `pi_date`,
                                sup.name `supplier`,l.`lc_tt_no`, DATE_FORMAT(l.`lc_tt_date`,'%d/%b/%Y') `lc_tt_date` ,i.`com_invoice_val_usd` ,
                                l.`exchange_rate` ,i.`com_invoice_val_bdt`,i.`freight_charges`,i.`global_taxes`,i.`cd`,i.`rd`,i.`sd`,i.`vat`,i.tot_landed_cost, l.`at`,l.`ait`,b.`name` received_location ,
                                h.hrName received_by,l.`gnr_no`, DATE_FORMAT(l.`gnr_date`,'%d/%b/%Y') `gnr_date` ,pr.name prod,pr.description,pr.barcode,i.`qty`,i.`tot_value`, bn.name banknm,DATE_FORMAT(l.`bank_dt`,'%d/%b/%Y') `bank_dt`,l.`payment_amount`,
                                l.`remark`, pr.image 
                                from purchase_landing l left join suplier sup ON sup.id=l.warehouse left join purchase_landing_item i on l.id=i.pu_id left join item pr on i.productId=pr.id
                                left join branch b on l.warehouse=b.id left join bank bn on l.`bank_name`=bn.id left join hr h on l.`received_by`=h.id
                                where 1=1 $dateqry
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'l.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $photo="assets/images/products/300_300/".$row["image"];
          
            
            $data[] = array(
                    "id"=> $sl,
                    "voucher_no"=> $row["voucher_no"],
            		"voucher_date"=> $row["voucher_date"],
            		"pi_no"=> $row["pi_no"],
            		"pi_date"=> $row["pi_date"],
            		"supplier"=> $row["supplier"],
            		"lc_tt_no"=> $row["lc_tt_no"],
            		"lc_tt_date"=> $row["lc_tt_date"],
            		"com_invoice_val_usd"=> $row["com_invoice_val_usd"],
            		"exchange_rate"=> $row["exchange_rate"],
            		"com_invoice_val_bdt"=> $row["com_invoice_val_bdt"],
            		"freight_charges"=> $row["freight_charges"],
            		"global_taxes"=> $row["global_taxes"],
            		"cd"=> $row["cd"],
            		"rd"=> $row["rd"],
            		"sd"=> $row["sd"],
            		"vat"=> $row["vat"],
            		"tlc"=> $row["tot_landed_cost"],
            		"at"=> $row["at"],
            		"ait"=> $row["ait"],
            		"received_location"=> $row["received_location"],
            		"received_by"=> $row["received_by"],
            		"gnr_no"=> $row["gnr_no"],
            		"gnr_date"=> $row["gnr_date"],
            		"image"=>'<img src='.$photo.' width="50" height="50">',
            		"prod"=> $row["prod"],
            		"description"=> $row["description"],
            		"barcode"=> $row["barcode"],
            		"qty"=> $row["qty"],
            		"tot_value"=> $row["tot_value"],
            		"banknm"=> $row["banknm"],
            		"bank_dt"=> $row["bank_dt"],
            		"payment_amount"=> $row["payment_amount"],
            		"remark"=> $row["remark"]
            		
            	);
            $sl++;
        } 
        
    }
    
    else if($action=="rpt_group_wise_allocation")
    {
        
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and l.makedt BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( c.name like  '%".$searchValue."%' or i.code  like '%".$searchValue."%' or i.name like '%".$searchValue."%' 
        	                or b.name ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select c.name catagory,i.code productCode,i.name Product,i.description ProductDescription
                                ,b.name Warehouse,s.orderedqty allocatedQty, i.image
                                from item i left join itmCat c on i.catagory=c.id
                                left join chalanstock s on s.product=i.id left join branch b on s.storerome=b.id
                                where s.orderedqty>0
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'i.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $photo="assets/images/products/300_300/".$row["image"];
          
            
            $data[] = array(
                    "id"=> $sl,
                    "image"=>'<img src='.$photo.' width="50" height="50">',
                    "Product"=> $row["Product"],
            		"productCode"=> $row["productCode"],
            		"catagory"=> $row["catagory"],
            		"ProductDescription"=> $row["ProductDescription"],
            		"Warehouse"=> $row["Warehouse"],
            		"allocatedQty"=> $row["allocatedQty"],
            		
            	);
            $sl++;
        } 
        
    }
    
    else if($action=="rpt_revenue_new")
    {
        
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and q.orderdate BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( q.socode like  '%".$searchValue."%' or o.name  like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery1="SELECT  DATE_FORMAT(q.orderdate,'%d/%b/%Y') AS date, q.socode order_id,o.name customer,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) amount,(qd.vat/qd.qty*agg.dod_totdeliqty) vat,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) adjustment_amount,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) delivery_amount,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) ,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) discounted_total,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) revenue,qd.cost,(((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) -(qd.cost*agg.dod_totdeliqty)) margin
FROM quotation q  join quotation_detail qd on q.socode=qd.socode  JOIN delivery_order d ON d.order_id = q.socode
join organization o on q.organization= o.id
 JOIN (
  SELECT
  do_id,item,  SUM(do_qty) AS dod_totdoqty,  SUM(pending_qty) AS dod_totpendingqty,  SUM(intransit_qty) AS dod_tottransqty,  SUM(delivered_qty) AS dod_totdeliqty,
  SUM(due_return_qty + returned_qty) AS dod_totretqty
  FROM    delivery_order_detail  GROUP BY    do_id,item
) AS agg ON d.id = agg.do_id and agg.item=qd.productid
WHERE q.orderstatus in( '7','8') 
                                ";
                                
          $strwithoutsearchquery="SELECT  DATE_FORMAT(q.orderdate,'%d/%b/%Y') AS date, q.socode order_id,o.name customer,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) amount,(qd.vat/qd.qty*agg.dod_totdeliqty) vat,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) adjustment_amount,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) delivery_amount,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) ,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) discounted_total,((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) revenue,qd.cost,(((qd.discounttot/qd.qty)*(agg.dod_totdeliqty)) -(qd.cost*agg.dod_totdeliqty)) margin
FROM quotation q  join quotation_detail qd on q.socode=qd.socode  JOIN delivery_order d ON d.order_id = q.socode
join organization o on q.organization= o.id
 JOIN (
  SELECT
  do_id,item,  SUM(do_qty) AS dod_totdoqty,  SUM(pending_qty) AS dod_totpendingqty,  SUM(intransit_qty) AS dod_tottransqty,  SUM(delivered_qty) AS dod_totdeliqty,
  SUM(due_return_qty + returned_qty) AS dod_totretqty
  FROM    delivery_order_detail  GROUP BY    do_id,item
) AS agg ON d.id = agg.do_id and agg.item=qd.productid
WHERE q.orderstatus in( '7','8') $dateqry
                                ";
          
          /*
          "select DATE_FORMAT(qt.orderdate,'%d/%b/%Y') AS date, qt.socode order_id, o.name AS customer,  FORMAT(SUM((qd.discounttot+qd.discount_amount)/qd.qty), 2) AS amount, FORMAT(SUM(qd.vat), 2) AS vat,FORMAT(SUM(qd.discounttot/qd.qty), 2) AS adjustment_amount,FORMAT((SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`), 2) delivery_amount
                       ,FORMAT(SUM(qd.discounttot), 2) AS discounted_total,FORMAT(SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`, 2) revenue,FORMAT(SUM(qd.cost), 2) AS cost
                       , FORMAT((SUM(qd.discounttot/qd.qty)*dd.`delivered_qty`) - SUM(qd.cost), 2) AS margin
                              from delivery_order_detail dd ,qa q,quotation_detail qd,quotation qt,organization o where dd.qa_id=q.id  and q.order_id=qd.socode and dd.item=qd.productid and qd.socode=qt.socode 
                              and qt.organization = o.id  and dd.st=2 AND q.type=1 $dateqry "  ; */                 
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id' || $columnName == 'date'){ 
            $columnName = 'q.orderdate';
        }
        
        $empQuery1=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empQuery=$str.$searchQuery."  order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery1);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            
            $data[] = array(
                    "id"=> $sl,//$empQuery1,//
                    "date"=> $row["date"],  
            		"order_id"=> $row["order_id"],
            		"customer"=> $row["customer"],
            		"amount"=> number_format($row['amount'],2),
            		"vat"=> number_format($row['vat'],2),
            		"adjustment_amount"=> number_format($row['adjustment_amount'],2),
            		"delivery_amount"=> number_format($row['delivery_amount'],2),
            		"discounted_total"=> number_format($row['discounted_total'],2),
            		"revenue"=> number_format($row['revenue'],2),
            // 		"cost"=> $row["cost"],
            // 		"margin"=> $row["margin"],
            		
            	);
            $sl++;
        } 
        
    }
    
    else if($action=="rpt_expire")
    {
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and  s.expirydt< STR_TO_DATE('".$tdt."','%d/%b/%Y')";
        }else{
            $date_qry = "";
        }
        $bc1 = $_GET["barcode"];
        //$dagent = $_GET["dagent"];
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        //echo $td; die;
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( t.name like  '%".$searchValue."%' or p.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode,DATE_FORMAT(s.expirydt,'%d/%b/%Y') expirydt 
FROM chalanstock s LEFT JOIN item p ON s.product = p.id 
LEFT JOIN itemtype t ON p.catagory=t.id 
LEFT JOIN branch r ON s.storerome=r.id where s.`freeqty`>0 $date_qry and (s.barcode like '%".$bc1."%' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )";
                    
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        $qrysum = "SELECT SUM(s.freeqty * s.costprice) as totcost, SUM(p.rate * s.freeqty) as totmrp 
                    FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itemtype t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id
                    where s.`freeqty`>0 $date_qry and (s.barcode like '".$bc1."' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )";
        
        $resultsum = mysqli_query($con, $qrysum);
        while ($rowsum = mysqli_fetch_assoc($resultsum)) {
            array_push($total, $rowsum["totcost"]);
            array_push($total, $rowsum["totmrp"]);
        }
        
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
        }
        if($columnName == 'expirydt'){
            $columnName = 's.expirydt';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit;
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $cp=$row['freeqty']*$row['costprice'];
           $mp=$row['freeqty']*$row['mrp'];
           
            $data[] = array(
                    "id"=>$sl,
                    "tn"=>$row["tn"],
            		"pn"=>$row['pn'],
            		"barcode"=>$row['barcode'],
            		"str"=>$row['str'],
            		"expirydt"=>$row['expirydt'],
            		"freeqty"=>number_format($row['freeqty'],0),
            		"costprice"=>number_format($row['costprice'],2),
            		"costpr"=>number_format($cp,2),
            		"mrp"=>number_format($row['mrp'],2),
            		"mrptotal"=>number_format($mp,2),
            		
            		
            	);
            $sl++;
        } 
    }
     else if($action=="rpt_aging")
    {
        $fd1 = $_GET["dt_f"];
        $td1 = $_GET["dt_t"];
        
        if($fd1 == ''){
            $dateqry = "";
        }else{
            $dateqry = " and p.voucher_date BETWEEN STR_TO_DATE('$fd1','%Y-%m-%d') and STR_TO_DATE('$td1','%Y-%m-%d')";
        }
        
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( t.name like  '%".$searchValue."%' or p.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT i.image,t.name tn,i.id,i.name pn,s.freeqty,s.costprice,i.rate mrp,r.name str,s.barcode,DATE_FORMAT(max(p.voucher_date),'%d/%b/%Y') purchagedt
,DATEDIFF(sysdate(),max(p.voucher_date)) nosdays 
FROM 
purchase_landing p ,purchase_landing_item pi,
chalanstock s LEFT JOIN item i ON s.product = i.id 
LEFT JOIN itmCat t ON i.catagory=t.id 
LEFT JOIN branch r ON s.storerome=r.id
where   
p.id=pi.pu_id and pi.productId=i.id and
s.`freeqty`>0  $dateqry and (s.barcode like '%".$bc1."%' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 ) and ( t.id = ".$cat." or ".$cat." = 0 )";
                    
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id'; 
        }
        if($columnName == 'voucher_date'){
            $columnName = 'p.voucher_date';
        } 
        
        $empQuery=$strwithoutsearchquery.$searchQuery." GROUP by t.name ,i.name ,s.freeqty,s.costprice,i.rate ,r.name ,s.barcode  order by $columnName $columnSortOrder";
        //." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit;
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $cp=$row['freeqty']*$row['costprice'];
           $mp=$row['freeqty']*$row['mrp'];
           $photo="assets/images/products/300_300/".$row["image"];
            $data[] = array(
                    "id"=>$sl,//$empQuery,//
                    "tn"=>$row["tn"],
                    "photo"=>'<img src='.$photo.' width="50" height="50">',
            		"pn"=>$row['pn'],
            		"barcode"=>$row['barcode'],
            		"str"=>$row['str'],
            		"makedt"=>$row['purchagedt'],
            		"freeqty"=>number_format($row['freeqty'],0),
            		"costprice"=>number_format($row['costprice'],2),
            		"costpr"=>number_format($cp,2),
            		"nosdays"=>$row['nosdays'],
            		"mrp"=>number_format($row['mrp'],2),
            		"mrptotal"=>number_format($mp,2),
            		
            		
            	);
            $sl++;
        } 
    }
   
    
    else if($action=="rpt_storewise_stock")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $brand = $_GET["brand"]; if($brand == '') $brand = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.name like  '%".$searchValue."%' or t.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' or p.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,p.barcode barcode, s.storerome, p.image, b.title brand
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id 
                                LEFT JOIN brand b ON b.id=p.brand
                                where (s.barcode='".$bc1."' or p.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' or p.barcode like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and ( b.id = ".$brand." or ".$brand." = 0 ) and s.freeqty<>0";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"]; $br=$row2["brand"];  
            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];
            $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
            $tcp=$tcp+$cp;$tmp=$tmp+$mp;
            
            if($row2["storerome"] == 7){
                $storetype = "Future Stock";
            }
            else if($row2["storerome"] == 8){
                $storetype = "Back Stock";
            }else{
                $storetype = "In Stock";
            }
            $photo="assets/images/products/300_300/".$row2["image"];
          
            $data[] = array(
                    "id"=> $sl,
            		"tn"=> $tnm,
            		"brand"=> $br,
            		"image"=>'<img src='.$photo.' width="50" height="50">',
            		"pn"=> $prod,
            		"barcode"=> $bc,
            		"storetype"=> $storetype,
            		"str"=> $str,
            		"freeqty"=> number_format($freeqty,0),
            		"costprice"=> number_format($cup,2),
            		"totalcp"=> number_format($cp,2),
            		"mrp"=> number_format($mup,2),
            		"totalmrp"=> number_format($mp,2),
					"query"=> $empRecords,
					
            		
            	);
            $sl++;
        } 
        
    }
     else if($action=="rpt_issueloc_stock") 
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $brand = $_GET["brand"]; if($brand == '') $brand = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.name like  '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery1="select i.id,t.name catnm,b.title brand,p.image,p.name prod,p.barcode,'Issue' tp,r.name issueloc,id.qty,p.rate 
                            from issue_order i join issue_order_details id on i.id=id.ioid  left join item p on p.id=id.product LEFT JOIN itmCat t ON p.catagory=t.id
                            LEFT JOIN issue_warehouse r ON i.issue_warehouse=r.id  LEFT JOIN brand b ON b.id=p.brand
                                where  id.qty<>0";
        $strwithoutsearchquery2="select i.id,t.name catnm,b.title brand,p.image,p.name prod,p.barcode,'Issue' tp,r.name issueloc,id.qty,p.rate 
                            from issue_order i join issue_order_details id on i.id=id.ioid  left join item p on p.id=id.product LEFT JOIN itmCat t ON p.catagory=t.id
                            LEFT JOIN issue_warehouse r ON i.issue_warehouse=r.id  LEFT JOIN brand b ON b.id=p.brand
                                where (p.barcode='".$bc1."'  or '".$bc1."'='' or p.name like '%".$bc1."%' or p.barcode like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and ( b.id = ".$brand." or ".$brand." = 0 ) and id.qty<>0";
        
        $strwithoutsearchquery="select i.id,t.name catnm,b.title brand,p.image,p.name prod,p.barcode,'Issue' tp,r.name issueloc,id.qty,p.rate, DATE_FORMAT( i.makedt,'%d/%b/%Y') requestdt,
                                concat(emp.firstname, ' ', emp.lastname) requestby,concat(emp1.firstname, ' ', emp1.lastname) approvedby,DATE_FORMAT( i.approvedt,'%d/%b/%Y') approvedt
                            from issue_order i join issue_order_details id on i.id=id.ioid  left join item p on p.id=id.product LEFT JOIN itmCat t ON p.catagory=t.id
                            LEFT JOIN issue_warehouse r ON i.issue_warehouse=r.id  LEFT JOIN brand b ON b.id=p.brand LEFT JOIN hr h ON h.id=i.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                LEFT JOIN hr h1 ON h1.id=i.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                where (p.barcode='".$bc1."'  or '".$bc1."'='' or p.name like '%".$bc1."%' or p.barcode like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and ( b.id = ".$brand." or ".$brand." = 0 ) and id.qty<>0";     
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'i.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $tnm=$row2["catnm"]; $prod=$row2["prod"];$str=$row2["issueloc"]; $br=$row2["brand"];  
            $freeqty=$row2["qty"]; $cup=0; $mup=$row2["rate"]; $bc=$row2["barcode"];
            //$cp=$freeqty*$cup;
            $mp=$freeqty*$mup; 
            //$tcp=$tcp+$cp;
            $tmp=$tmp+$mp;
            
           
                $storetype = "Issue Stock";
            
            $photo="assets/images/products/300_300/".$row2["image"];
          
            $data[] = array(
                    "id"=> $sl,
            		"tn"=> $tnm,//$strwithsearchquery,
            		"brand"=> $br,
            		"image"=>'<img src='.$photo.' width="50" height="50">',
            		"pn"=> $prod,
            		"barcode"=> $bc,
            		"storetype"=> $storetype,
            		"str"=> $str,
            		"freeqty"=> number_format($freeqty,0),
            		//"costprice"=> number_format($cup,2),
            		//"totalcp"=> number_format($cp,2),
            		"mrp"=> number_format($mup,2),
            		"totalmrp"=> number_format($mp,2),
            		"requestby"=> $row2["requestby"],
            		"requestdt"=> $row2["requestdt"],
            		"approvedby"=> $row2["approvedby"],
            		"approvedt"=> $row2["approvedt"],
					"query"=> $empRecords,
					
            		
            	);
            $sl++;
        } 
        
    }
    else if($action=="rpt_future_stock")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.name like  '%".$searchValue."%' or t.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode, s.storerome,p.image
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                where (s.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and s.storerome=7 and s.freeqty<>0 ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];
            $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
            $tcp=$tcp+$cp;$tmp=$tmp+$mp;
            
            if($row2["storerome"] == 7){
                $storetype = "Future Stock";
            }
            else if($row2["storerome"] == 8){
                $storetype = "Back Stock";
            }else{
                $storetype = "In Stock";
            }
           $photo="assets/images/products/300_300/".$row2["image"];
            $data[] = array(
                    "id"=> $sl,
            		"tn"=> $tnm,
            		"photo"=>'<img src='.$photo.' width="50" height="50">',
            		"pn"=> $prod,
            		"barcode"=> $bc,
            		"storetype"=> $storetype,
            		"str"=> $str,
            		"freeqty"=> number_format($freeqty,0),
            		"costprice"=> number_format($cup,2),
            		"totalcp"=> number_format($cp,2),
            		"mrp"=> number_format($mup,2),
            		"totalmrp"=> number_format($mp,2),
					"query"=> $empRecords,
					
            		
            	);
            $sl++;
        } 
    }
    else if($action=="rpt_backorder_stock")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.name like  '%".$searchValue."%' or t.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode, s.storerome,p.image
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                where (s.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' ) and ( r.id = ".$branch." or ".$branch." = 0 )
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and s.storerome=8 and s.freeqty<>0 ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];
            $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
            $tcp=$tcp+$cp;$tmp=$tmp+$mp;
            
            if($row2["storerome"] == 7){
                $storetype = "Future Stock";
            }
            else if($row2["storerome"] == 8){
                $storetype = "Back Stock";
            }else{
                $storetype = "In Stock";
            }
           $photo="assets/images/products/300_300/".$row2["image"];
            $data[] = array(
                    "id"=> $sl,
            		"tn"=> $tnm,
            		"photo"=>'<img src='.$photo.' width="50" height="50">',
            		"pn"=> $prod,
            		"barcode"=> $bc,
            		"storetype"=> $storetype,
            		"str"=> $str,
            		"freeqty"=> number_format($freeqty,0),
            		"costprice"=> number_format($cup,2),
            		"totalcp"=> number_format($cp,2),
            		"mrp"=> number_format($mup,2),
            		"totalmrp"=> number_format($mp,2),
					"query"=> $empRecords,
					
            		
            	);
            $sl++;
        } 
        
    } 
    else if($action=="backorder_shift")
    {
        
        //$store = $_GET["store"]; if($store == '') $store = 0;
        //$branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.name like  '%".$searchValue."%' or t.name like '%".$searchValue."%' or s.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,t.name tn,s.product,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,p.barcode barcode, s.storerome
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                where (s.barcode='".$bc1."' or '".$bc1."'='' or p.name like '%".$bc1."%' ) 
                                and ( t.id = ".$cat." or ".$cat." = 0 ) and s.storerome=8 and s.freeqty<>0 ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        //echo $qrytotal;die;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];$itm=$row2["product"];
            $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
            $tcp=$tcp+$cp;$tmp=$tmp+$mp;
            
            //$seturl="backorder_shift.php?res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            
            $seturl = "javascript:void(0);";
            //$seturl="update_shfit_stock.php?action=backorder&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
           
			$btns = array(
                array('stock-transfer',$seturl,'class="btn btn-info btn-xs backorder-shift" data-barcode="'.$row2["barcode"].'" data-id="'.$row2["id"].'" data-freeqty="'.$row2["freeqty"].'"  title="Shift Backorder"'),
			
			);
            
            if($row2["storerome"] == 7){
                $storetype = "Future Stock";
            }
            else if($row2["storerome"] == 8){
                $storetype = "Back Stock";
            }else{
                $storetype = "In Stock";
            }
           
            $data[] = array(
                    "id"=> $sl,
                    //"id"=> $row2["id"],
            		"tn"=> $tnm,
            	//	"itm"=>$itm,
            		"pn"=> $prod,
            		"barcode"=> $bc,
            		"storetype"=> $storetype,
            		"str"=> $str,
            		"freeqty"=> '<input type="hidden" readonly name="hboqty"  class="form-control hboqty  center-block" value="'.number_format($freeqty,0).'"><input type="text" readonly name="boqty" style="width:60px;text-align:center;" class="form-control boqty  center-block" value="'.number_format($freeqty,0).'">',
            		"foqty"=> '<input type="text" name="foqty"  style="width:60px;text-align:center;" class="form-control foqty center-block numonly">',
            		"costprice"=> number_format($cup,2),
            		"totalcp"=> number_format($cp,2),
            		"mrp"=> number_format($mup,2),
            		"totalmrp"=> number_format($mp,2),
					"action_buttons"=>getGridBtns($btns),
					
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($total_cp,2));
        array_push($total, number_format($total_mrp,2));
        //print_r($total);die;
    } 
    else if($action=="allocated")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        //$branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( q.socode like  '%".$searchValue."%' or o.name  like '%".$searchValue."%' or cat.name like '%".$searchValue."%' 
        	                    or i.name like '%".$searchValue."%' or i.barcode like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT i.barcode, i.image,q.id,q.socode,q.srctype,q.project,(case when q.srctype=2 then q.project else 'Retail' end ) 'type', cat.name cat
                                ,o.name customer,q.organization cusid,a.product_id ,i.name,DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate,(SELECT MAX(DATE_FORMAT(quow.expted_deliverey_date, '%d/%b/%Y'))
                                FROM quotation_warehouse quow
                                WHERE a.order_id = quow.socode) AS deliverydt,q.orderstatus,qs.name orderst
                                ,a.product_id,(a.quantity -COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0)) quantity
                                ,a.status ,qas.name qastatus,qw.qa_type,qw.warehouse_id,qw.ordered_qty,COALESCE(qw.pass_qty,0) pass_qty
                                ,COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0) deliverdqty
                                FROM quotation q left join qa a on q.socode=a.order_id left join quotation_status qs on q.orderstatus=qs.id left join qastatus qas on a.status=qas.id
                                left join qa_warehouse qw on a.id=qw.qa_id left join organization o on q.organization=o.id 
                                left join branch b on qw.warehouse_id=b.id left join item i on a.product_id=i.id LEFT JOIN itmCat cat ON cat.id=i.catagory
                                WHERE (i.barcode like '%".$bc1."%' or '".$bc1."'='') 
                                and ( cat.id = ".$cat." or ".$cat." = 0 ) and q.orderstatus in(4,5,7) and qw.ordered_qty>COALESCE((SELECT sum(delivered_qty) FROM delivery_order_detail where qa_id=a.id),0) ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'q.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $photo="assets/images/products/300_300/".$row["image"];
            $data[] = array(
                    "id"=> $sl,
            		"socode"=> $row["socode"],
            		"type"=> $row["type"],
            		"customer"=> $row["customer"],
            		"cat"=> $row["cat"],
            		"photo"=>'<img src='.$photo.' width="50" height="50">',
            		"product"=> $row["name"],
            		"barcode"=> $row["barcode"],
            		"orderdate"=> $row["orderdate"],
            		"deliverydt"=> $row["deliverydt"],
            		"orderst"=> $row["orderst"],
            		"quantity"=> $row["quantity"],
            		"qastatus"=> $row["qastatus"],
            		"ordered_qty"=> $row["ordered_qty"],
					"pass_qty"=> $row["pass_qty"],
					"deliverdqty"=> $row["deliverdqty"],
            		
            	);
            $sl++;
        } 
    }

    else if($action=="appraisal")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and ( b.`firstname` like '%".$searchValue."%' or  b.`lastname`)";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id` id, a.`year`, b.`title` atype, concat(emp.`firstname`, ' ', emp.`lastname`) hrid, a.`managerrecomandation`, a.`hrdrecommendation`, a.`mdrecomendation`, 

                                concat(emp1.`firstname`, ' ', emp1.`lastname`) hraction,DATE_FORMAT(a.`effectivedt`,'%d/%b/%Y') `effectivedt` FROM `appraisal` a 

                                LEFT JOIN `appraisalType` b ON a.`appraisalType` = b.`id` LEFT JOIN `employee` emp ON a.`hrid` = emp.`id` 

                                LEFT JOIN `employee` emp1 ON a.`hraction` = emp1.`id` 

                                WHERE a.`st` = 1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";
        if($columnName == 'effectivedt') $columnName = "a.effectivedt";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $intime = $row['intime'];

            $intime = date("g:i a", strtotime($intime));

            $outtime = $row['outtime'];

            $outtime = date("g:i a", strtotime($outtime));

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="appraisal.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=appraisal&ret=appraisalList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "year"=>$row['year'],

                    "atype"=>$row['atype'],

                    "hrid"=>$row['hrid'],

                    "mnr"=>$row['managerrecomandation'],

                    "hrr"=>$row['hrdrecommendation'],

                    "mdr"=>$row['mdrecomendation'],

                    "hraction"=>$row['hraction'],

                    "effectivedt"=>$row['effectivedt'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="kpi")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (b.title like '%".$searchValue."%' or a.title like '%".$searchValue."%' or a.weight like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id` id, ps.`title` pstype, a.`Sl`, a.`title`, k.`title` kpival FROM `KPI` a 

                                LEFT JOIN `performanceStandared` ps ON ps.`id` = a.`PS` LEFT JOIN `kpivalueType`k ON a.`kpivalueType` = k.`id` WHERE a.st = 1 ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="kpi.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=KPI&ret=kpiList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "ps"=>$row['pstype'],

                    "slr"=>$row['Sl'],

                    "title"=>$row['title'],

                    "kpival"=>$row['kpival'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }

    

    else if($action=="hrpssetup")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(emp.`firstname`, ' ', emp.`lastname`) like '%".$searchValue."%' or p.title like '%".$searchValue."%' or ps.title like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id` id, concat(emp.`firstname`, ' ', emp.`lastname`) empid, p.`title` pstype, ps.`title` ps

                                FROM `hrPSsetup` a 

                                LEFT JOIN `employee` emp ON emp.`id` = a.`hrid` 

                                LEFT JOIN `psType` p ON p.`id` = a.`psType` 

                                left JOIN `performanceStandared` ps ON ps.`id` = a.`PS` 

                                WHERE a.st = 1";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="hrpssetup.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=hrPSsetup&ret=hrpssetupList&mod=4&id=".$row['id'];
           
           $btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);

            $data[] = array(

                    "sl"=>$sl,

                    "empid"=>$row['empid'],

                    "pstype"=>$row['pstype'],

                    "ps"=>$row['ps'],

            		"action"=> getGridBtns($btns),
            	);

            $sl++;

        } 

    }
 
    

    else

    {

    

    }

        //$data[] = array('dt'=>$empQuery);

        //$seturl="contact.php?res=4&msg='Update Data'&id=".$uid."&mod=2";

        ## Response

        //$total = 98;

        $response = array(

            "draw" => intval($draw),

            "iTotalRecords" => $totalRecords,

            "iTotalDisplayRecords" => $totalRecordwithFilter,

            "aaData" => $data,

            "total"    => $total,
			"query"    => $empQuery,

        );

        

        //print_r($data);die;

        

        echo json_encode($response);



?>