<?php



require "../common/conn.php";
include_once('../rak_framework/fetch.php');

session_start();


//print_r($_REQUEST);

$con = $conn;



## Read value
$treatfrom = $_GET['treatfrom']; 
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

extract($_REQUEST);

$filterorg_str = ($filterorg && ($filterorg !='undefined'))?"and o.id=".$filterorg:"";

$filterst_str = ($filterst && ($filterst !='undefined'))?"and i.paymentSt=".$filterst:"";

$dt_range_str = ($dt_f && $dt_t)?" and i.invoicedt BETWEEN '".$dt_f."' AND '".$dt_t."'":"";


if($cmbstatus){
	$cmbstatus_str = "and orderstatus = ".$cmbstatus;
}else{$cmbstatus_str = "";}





$total = array();
$pqry=" ";

if($action=="invoice"){

        $searchQuery = " ";
        $cmbcustomer = $_REQUEST["customer"];
        if($cmbcustomer != ''){
        	$cmbcustomer = "and i.organization = ".$cmbcustomer;
        }else{$cmbcustomer = "";}

        if($searchValue != '')

        {
			$searchValue = (strstr($searchValue,","))?strToNumber(trim($searchValue)):trim($searchValue);
			
//			$makedt = 'STR_TO_DATE('.$searchValue.',"%d/%b/%Y")';
			
        	$searchQuery = " and (
				 
				 o.balance like '%".$searchValue."%' or  
				 i.`invoiceno` like '%".$searchValue."%' or  
				 i.`invoicemonth` like '%".$searchValue."%' or  
				 i.`invyr` like '%".$searchValue."%' or 
                 i.`soid` like '%".$searchValue."%' or 
				 o.name like '%".$searchValue."%' or
				 o.orgcode like '%".$searchValue."%' or
				 i.`invoiceamt`  like '%".$searchValue."%' or 
                 i.`paidamount`  like '%".$searchValue."%'  or 
				 i.`dueamount` like '%".$searchValue."%' or
				 i.`duedt` like '%".$searchValue."%'  or 
                 s.`name` like '%".$searchValue."%' or 
				 s.`dclass` like '%".$searchValue."%' or 
				 p.`name` like '%".$searchValue."%' or 
				 p.`dclass` like '%".$searchValue."%'
				 
				 )";

        }

        

        //Filter
	 
	 /*
        $fdt = $_GET["fdt"];
        $tdt = $_GET["tdt"];
        $yeardt = $_GET["yeardt"]; if($yeardt == '') $yeardt = date('Y');

        $filterorg = $_GET["filterorg"];

        if($filterorg != ''){

            $filterorgqry = " and i.`organization` = ".$filterorg;

        }

        

        $filterst = $_GET["filterst"];

        if($filterst != ''){

            $filterstqry = " and i.`paymentSt` = ".$filterst;

        }

        */

        ## Total number of records without filtering   #c.`id`,
	 
	 //WHERE  1=1  and i.`invyr` = '".$yeardt."' and i.invoicedt BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y')  ";
	 
        $strwithoutsearchquery="
		SELECT  1 sl,
		i.`invoiceno`, 
		DATE_FORMAT(i.makedt, '%d/%b/%Y') makedt,
		i.id iid, 
		i.`invyr`, 
		i.`invoicemonth`,
		i.`invoicedt`, 
		i.`soid`, 
		o.id cid, 
		o.name `organization`, 
		i.`invoiceamt` invoiceamt,
		(SELECT sum(`discounttot`) FROM `invoicedetails`   where invoiceno=i.`invoiceno`) discounttot,
		format(i.amount_bdt,2) amount_bdt,   
		format(i.`paidamount`,2)paidamount_tot, 
		i.`paidamount`,
		i.`dueamount` due, 
		format(i.`dueamount`,2)dueamount, 
		i.`duedt`, 
		i.return_amount,
		s.`name`,
		s.`dclass` `invoiceSt`,
		p.`name` paySt,
		p.`id` paymentstid,
		p.`dclass` `paymentSt`,
        o.balance orgbal,o.id orgid 
		FROM `invoice` i  
		LEFT JOIN invoicestatus s  on i.invoiceSt=s.id 
		LEFT JOIN invoicepaystatus p on i.paymentSt=p.id  
        LEFT JOIN organization o on i.organization=o.id 
		
	 	WHERE (i.approval != 3 and i.approval != 4) and (i.backorder = 0 or i.backorder = 1)  and substr(soid,1,2)!='GI'  ".$filterorg_str."  ".$filterst_str ." ".$dt_range_str." $cmbcustomer ";

	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        if($columnName == 'makedt')
        {
            $columnName=" i.makedt ";
        }

         //$empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by i.makedt desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
	 	 $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;$disctotl=0;$paybletot=0;$duetot=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."&orbal=".$row['orgbal']."&orgid=".$row['orgid']."')";

           $seturl="invoice.php?res=4&msg='Update Data'&id=".$row['invoiceno']."&mod=3";

           $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';

           $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';
           
            $dynamicNumber = $row["iid"];
            $dynamicNumberString = (string)$dynamicNumber;
            $resultString = str_pad($dynamicNumberString, 6, '0', STR_PAD_LEFT);
            
            $approval_req = '';
			if($row['paymentstid'] == 4){
			    
			    $inv = "INV-".$resultString;
			}
			else if($row['paymentstid'] == 3){
			    
			    $inv = "INV-".$resultString; 
			}
			else if($row['paymentstid'] == 5){
			    $inv = "PI-".$resultString;
			}else{
			    $inv= '';
			 //   $appurl = "phpajax/send_approval_qc.php?invid=".$row["iid"];
			 //   $approval_req = '| <a class="btn btn-info btn-xs approval" title="Action"  href="'. $appurl .'"  ><i class="fa fa-check"></i></a>';
			}
		   
			
			$invViewLink = '<a data-invid="'.$row['paySt'].'" href="invoice_rdl.php?invid='.$row['invoiceno'].'&mod=3" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
			$invDLLink = '<a href="transaction_list.php?transref='.$row['invoiceno'].'&mod=3" class="btn btn-info btn-xs paysliplist" title="Money Receipt" data-invid="'.$row['paySt'].'" ><i class="fa fa-file-text-o"></i></a>';
			$invPayLink = '<a data-invid="'.$inv.'" data-cid="'.$row['orgid'].'"  href="make_payment_popup.php?invoiceno='.$row['invoiceno'].'&cid='.$row['orgid'].'" class="mkpayment  btn btn-info btn-xs" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
           
			if($row['paymentstid'] == 4 ||$row['paymentstid'] == 3){
				$invPayLink = '<a href="javascript:void(0)" disabled class="btn btn-info btn-xs" style="" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
			}

           $i++;

					$invoicedt=date_create($row['invoicedt']);
					$invoicedt =  date_format($invoicedt,"d/M/Y");
					
			$dtl="organazionwallet.php?orgid=".$row['orgid']."&mod=7";
		    $invno1 = ($inv)?$inv:"-";
		    $disctotl=$row['due'];
		    $paybletot=$row['paidamount'];
		    $return_amount = $row["return_amount"];
            $duetot=($disctotl);
			$data[] = array( 
                    "sl"=>$i,
					
					"makedt"=>$row['makedt'],
					"invoiceno"=>'<span class="rowid_'.$row['invoiceno'].'">'.$invno1.'</span>',
				
					"invoicedt"=>$invoicedt ,
                    "invyr"=>$row['invyr'],
            		"invoicemonth"=>date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)),
            		"soid"=>$row['soid'],
            		"organization"=>'<a href="'. $dtl.'" target="_blank">'.$row['organization'].'</a>',
        			"invoiceamt"=> number_format($row["invoiceamt"],2,".",","),
        			"return_amount"=> number_format($row['return_amount'], 2),
        			"amount_bdt"=> number_format($row['amount_bdt'], 2),
            		"paidamount"=>number_format($row['paidamount'], 2),//number_format($paybletot,2,".",","), //$row['paidamount'],
    				"dueamount"=>number_format($duetot,2,".",","), //$dueamt,
            		"duedt"=>$row['duedt'],
            		"invoiceSt"=>$row['name'],
            	 
            		//"invoiceSt"=>$invst,
					"walletbalance" => number_format($row['orgbal'],2,".",","),
            		"paymentSt"=>$invpaymentSt,
            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            		"edit"=> "".$invViewLink." | ".$invPayLink." | ".$invDLLink."".$approval_req,

            	);

        } 

    }

 if($action=="financialcustomization"){

        $searchQuery = " ";

        if($searchValue != '')

        {
			$searchValue = (strstr($searchValue,","))?strToNumber(trim($searchValue)):trim($searchValue);
			
//			$makedt = 'STR_TO_DATE('.$searchValue.',"%d/%b/%Y")';
			
        	$searchQuery = " and (
				 
				 o.balance like '%".$searchValue."%' or  
				 i.`invoiceno` like '%".$searchValue."%' or  
				 i.`invoicemonth` like '%".$searchValue."%' or  
				 i.`invyr` like '%".$searchValue."%' or 
                 i.`soid` like '%".$searchValue."%' or 
				 o.name like '%".$searchValue."%' or 
				 i.`invoiceamt`  like '%".$searchValue."%' or 
                 i.`paidamount`  like '%".$searchValue."%'  or 
				 i.`dueamount` like '%".$searchValue."%' or
				 i.`duedt` like '%".$searchValue."%'  or 
                 s.`name` like '%".$searchValue."%' or 
				 s.`dclass` like '%".$searchValue."%' or 
				 p.`name` like '%".$searchValue."%' or 
				 p.`dclass` like '%".$searchValue."%'
				 
				 )";

        }

        

       

        ## Total number of records without filtering   #c.`id`,
	 
	 //WHERE  1=1  and i.`invyr` = '".$yeardt."' and i.invoicedt BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y')  ";
	 
        $strwithoutsearchquery="
		SELECT  1 sl,i.`invoiceno`,DATE_FORMAT(i.makedt, '%d/%b/%Y') makedt,i.id iid, i.`invyr`, i.`invoicemonth`,i.`invoicedt`, i.`soid`, o.id cid, o.name `organization`, 
		i.`invoiceamt` invoiceamt,format(i.amount_bdt,2) amount_bdt,   format(i.`paidamount`+paid_reservedamt,2)paidamount, i.`dueamount` due, format(i.`dueamount`+i.due_reservedamt,2)dueamount,
		i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`id` paymentstid,p.`dclass` `paymentSt`,
        o.balance orgbal,o.id orgid, i.reserved_amount, i.due_reservedamt 
		FROM `invoice` i  
		LEFT JOIN invoicestatus s  on i.invoiceSt=s.id 
		LEFT JOIN invoicepaystatus p on i.paymentSt=p.id  
        LEFT JOIN organization o on i.organization=o.id 
		
	 	WHERE  (i.backorder = 0 or i.backorder = 1) ".$dt_range_str." ";

	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        if($columnName == 'makedt')
        {
            $columnName=" i.makedt ";
        }


         //$empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by i.makedt desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
	 	 $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."&orbal=".$row['orgbal']."&orgid=".$row['orgid']."')";

           $seturl="financialCustomization.php?res=4&msg='Update Data'&id=".$row['invoiceno']."&mod=17";

           $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';
           
		   $invEditLink = '<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
		   $invViewLink = '<a data-invid="'.$row['invoiceno'].'" href="invoice_finrdl.php?invid='.$row['invoiceno'].'&mod=17" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
		   $invPayLink = '<a data-invid="'.$row['invoiceno'].'" data-cid="'.$row['orgid'].'"  href="finance_make_payment_popup.php?invoiceno='.$row['invoiceno'].'&cid='.$row['orgid'].'" class="mkpayment  btn btn-info btn-xs" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
           $invPaySlip = '<a href="transaction_list.php?transref='.$row['invoiceno'].'&mod=3" class="btn btn-info btn-xs paysliplist" title="Money Receipt" data-invid="'.$row['invoiceno'].'" ><i class="fa fa-file-text-o"></i></a>';
		    //	$invDLLink = '<a href="transaction_list.php?transref='.$row['invoiceno'].'&mod=3" class="btn btn-info btn-xs paysliplist" title="Money Receipt" data-invid="'.$row['paySt'].'" ><i class="fa fa-file-text-o"></i></a>';
		
			if($row['paymentstid'] == 4 && $row["dueamount"] <= 0){
				$invPayLink = '<a href="javascript:void(0)" disabled class="btn btn-info btn-xs" style="" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
			}
			
           $i++;

					$invoicedt=date_create($row['invoicedt']);
					$invoicedt =  date_format($invoicedt,"d/M/Y");
					
					$invno1 = ($row['invoiceno'])?$row['invoiceno']:"-";
			$data[] = array(
                    "sl"=>$i,
					
					"makedt"=>$row['makedt'],
					"invoiceno"=>'<span class="rowid_'.$row['invoiceno'].'">'.$invno1.'</span>',
				
					"invoicedt"=>$invoicedt ,
                    "invyr"=>$row['invyr'],
            		"invoicemonth"=>date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)),
            		"soid"=>$row['soid'],
            		"organization"=>$row['organization'],
        			"invoiceamt"=> number_format($row['invoiceamt']+$row['reserved_amount'],2,".",","),
        			"amount_bdt"=> $row['amount_bdt']+$row['reserved_amount'],
            		"paidamount"=>$row['paidamount'],
    				"dueamount"=>$row['dueamount'],
            		"duedt"=>$row['duedt'],
            		"invoiceSt"=>$row['name'],
            		//"invoiceSt"=>$invst,
					"adjustment" => number_format($row['reserved_amount'],2,".",","),
            		"paymentSt"=>$invpaymentSt,
            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            	//	"edit"=> $invViewLink." | ".$invEditLink." | ".$invPayLink." | ".$invPaySlip,
            		"edit"=> $invViewLink." | ".$invPaySlip,

            	);

        } 

    }
    
 if($action=="ordershifting"){

        $searchQuery = " ";

        if($searchValue != '')

        {
			$searchValue = (strstr($searchValue,","))?strToNumber(trim($searchValue)):trim($searchValue);
			
//			$makedt = 'STR_TO_DATE('.$searchValue.',"%d/%b/%Y")';
			
        	$searchQuery = " and (
				 
				 o.balance like '%".$searchValue."%' or  
				 i.`invoiceno` like '%".$searchValue."%' or  
				 i.`invoicemonth` like '%".$searchValue."%' or  
				 i.`invyr` like '%".$searchValue."%' or 
                 i.`soid` like '%".$searchValue."%' or 
				 o.name like '%".$searchValue."%' or 
				 i.`invoiceamt`  like '%".$searchValue."%' or 
                 i.`paidamount`  like '%".$searchValue."%'  or 
				 i.`dueamount` like '%".$searchValue."%' or
				 i.`duedt` like '%".$searchValue."%'  or 
                 s.`name` like '%".$searchValue."%' or 
				 s.`dclass` like '%".$searchValue."%' or 
				 p.`name` like '%".$searchValue."%' or 
				 p.`dclass` like '%".$searchValue."%'
				 
				 )";

        }

        

       

        ## Total number of records without filtering   #c.`id`,
	 
	 //WHERE  1=1  and i.`invyr` = '".$yeardt."' and i.invoicedt BETWEEN STR_TO_DATE('".$fdt."','%d/%b/%Y') and  STR_TO_DATE('".$tdt."','%d/%b/%Y')  ";
	 
        $strwithoutsearchquery="
		SELECT  1 sl,i.`invoiceno`,DATE_FORMAT(i.makedt, '%d/%b/%Y') makedt,i.id iid, i.`invyr`, i.`invoicemonth`,i.`invoicedt`, i.`soid`, o.id cid, o.name `organization`, 
		i.`invoiceamt` invoiceamt,format(i.amount_bdt,2) amount_bdt,   format(i.`paidamount`+paid_reservedamt,2)paidamount, i.`dueamount` due, format(i.`dueamount`+i.due_reservedamt,2)dueamount,
		i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`id` paymentstid,p.`dclass` `paymentSt`,
        o.balance orgbal,o.id orgid, i.reserved_amount, i.due_reservedamt 
		FROM `invoice` i  
		LEFT JOIN invoicestatus s  on i.invoiceSt=s.id 
		LEFT JOIN invoicepaystatus p on i.paymentSt=p.id  
        LEFT JOIN organization o on i.organization=o.id 
	 	WHERE 
	 	(select qty from quotation_warehouse where warehouse in(8,7) and  socode=i.soid) >0 and
	 	(i.backorder = 0 or i.backorder = 1) ".$dt_range_str." ";

	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        if($columnName == 'makedt')
        {
            $columnName=" i.makedt ";
        }


         //$empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by i.makedt desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
	 	 $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."&orbal=".$row['orgbal']."&orgid=".$row['orgid']."')";

           $seturl="financialCustomization.php?res=4&msg='Update Data'&id=".$row['invoiceno']."&mod=17";

           $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';
           
		   $invEditLink = '<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
		   $invViewLink = '<a data-invid="'.$row['invoiceno'].'" href="invoice_finrdl.php?invid='.$row['invoiceno'].'&mod=17" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
		   $invPayLink = '<a data-invid="'.$row['invoiceno'].'" data-cid="'.$row['orgid'].'"  href="finance_make_payment_popup.php?invoiceno='.$row['invoiceno'].'&cid='.$row['orgid'].'" class="mkpayment  btn btn-info btn-xs" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
           $invPaySlip = '<a href="transaction_list.php?transref='.$row['invoiceno'].'&mod=3" class="btn btn-info btn-xs paysliplist" title="Money Receipt" data-invid="'.$row['invoiceno'].'" ><i class="fa fa-file-text-o"></i></a>';
			if($row['paymentstid'] == 4){
				$invPayLink = '<a href="javascript:void(0)" disabled class="btn btn-info btn-xs" style="" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
			}
			
           $i++;

					$invoicedt=date_create($row['invoicedt']);
					$invoicedt =  date_format($invoicedt,"d/M/Y");
					
					$invno1 = ($row['invoiceno'])?$row['invoiceno']:"-";
			$data[] = array(
                    "sl"=>$i,
					
					"makedt"=>$row['makedt'],
					"invoiceno"=>'<span class="rowid_'.$row['invoiceno'].'">'.$invno1.'</span>',
				
					"invoicedt"=>$invoicedt ,
                    "invyr"=>$row['invyr'],
            		"invoicemonth"=>date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)),
            		"soid"=>$row['soid'],
            		"organization"=>$row['organization'],
        			"invoiceamt"=> number_format($row['invoiceamt']+$row['reserved_amount'],2,".",","),
        			"amount_bdt"=> $row['amount_bdt']+$row['reserved_amount'],
            		"paidamount"=>$row['paidamount'],
    				"dueamount"=>$row['dueamount'],
            		"duedt"=>$row['duedt'],
            		"invoiceSt"=>$row['name'],
            		//"invoiceSt"=>$invst,
					"adjustment" => number_format($row['reserved_amount'],2,".",","),
            		"paymentSt"=>$invpaymentSt,
            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            		"edit"=> $invViewLink." | ".$invEditLink." | ".$invPayLink." | ".$invPaySlip,

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