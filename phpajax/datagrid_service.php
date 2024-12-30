<?php



require "../common/conn.php";
//require "../common/gridbtns.php";
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


$fdt= $_GET['dt_f'];
$tdt= $_GET['dt_t'];

$st = $_GET["filterst"];

$total = array();
$pqry=" ";



if($action=="serviceitem")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and name like '%".$searchValue."%' or  code like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery = "Select id,code, name, vat, tax FROM serviceitem WHERE 1 = 1";

        
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

    
         $empQuery=$basequery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			//booked order can be edited by who created this order.;
			
			$setdelurl="common/delobj.php?obj=serviceitem&ret=service_itemList&mod=22&id=".$row['id'];
            $setInvurl="service_item.php?res=4&msg='Update Data'&id=".$row['id']."&mod=22";
            //$urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $setInvurl.'"  ><i class="fa fa-edit"></i></a>';
            //$urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            
            //generate button array
			$btns = array(
				array('edit',$setInvurl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"code"=>$row['code'],
					
					"name"=>$row['name'],

            		"vat"=>$row['vat'],

            		"tax"=>$row['tax'],
            		
            		"action"=>getGridBtns($btns),

            	);

        } 

    }
    
if($action=="serviceorder")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and so.code like '%".$searchValue."%' or  org.name like '%".$searchValue."%' "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery = "SELECT so.code,DATE_FORMAT(so.orderdate,'%d/%b/%Y') orderdate,so.totalamount,so.totalvat,so.totaltax,so.transport,so.service_charge, org.name, so.id 
                    FROM `service_order` so LEFT JOIN organization org ON org.id=so.customer WHERE 1 = 1";

        
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
        
        if($columnName == 'id'){
            $columnName = "so.id";
        }
        if($columnName == 'orderdate'){
            $columnName = "so.orderdate";
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
			
			//$setdelurl="common/delobj.php?obj=serviceitem&ret=service_itemList&mod=22&id=".$row['id'];
            $setInvurl="service_order.php?res=4&msg='Update Data'&id=".$row['id']."&mod=22";
            $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $setInvurl.'"  ><i class="fa fa-edit"></i></a>';
            //$urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            
            //$invViewLink = '<a data-code="'.$row['code'].'" href="serviceorder_view.php" class="show-invoice btn btn-info btn-xs" title="View Servie Order" target="_blank"><i class="fa fa-eye"></i></a>';
			
			//generate button arrayView Quotation
			$btns = array(
				array('view','serviceorder_view.php','class="show-invoice btn btn-info btn-xs"  title="View Servie Order"	data-code="'.$row['code'].'"  '),
				array('edit',$setInvurl,'class="btn btn-info btn-xs"  title="Edit"	  '),
			);
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"id"=>$i,
					
					"code"=>$row['code'],
					
					"orderdate"=>$row['orderdate'],

            		"name"=>$row['name'],

            		"totalamount"=>$row['totalamount'],
            		
            		"totalvat"=>$row['totalvat'],
            		
            		"transport"=>$row['transport'],
            		
            		"service_charge"=>$row['service_charge'],
            		
            		"action"=>getGridBtns($btns), 

            	);

        } 

    }
    
if($action=="invoice"){

        $searchQuery = " ";

        if($searchValue != '')

        {
			$searchValue = (strstr($searchValue,","))?strToNumber(trim($searchValue)):trim($searchValue);
			
//			$makedt = 'STR_TO_DATE('.$searchValue.',"%d/%b/%Y")';
			
        	$searchQuery = " and (
				 
				 o.balance like '%".$searchValue."%' or  
				 i.`invoice` like '%".$searchValue."%' or  
                 i.`serviceorder` like '%".$searchValue."%' or 
				 o.name like '%".$searchValue."%' or 
				 i.`invoiceamt`  like '%".$searchValue."%' or 
                 i.`paidamt`  like '%".$searchValue."%'  or 
				 i.`dueamt` like '%".$searchValue."%' or
				 p.`name` like '%".$searchValue."%' or 
				 p.`dclass` like '%".$searchValue."%'
				 
				 )";

        }

        $strwithoutsearchquery="SELECT  1 sl,i.`invoice`,DATE_FORMAT( i.makedt,'%d/%b/%Y') makedt,i.id iid, i.`invyr`, i.`invoiceamt`, DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') `invoicedt`, o.id cid, o.name `organization`, 
                                format(i.`paidamt`,2)paidamount, i.`dueamt` due, p.`name` paySt,p.`id` paymentstid,p.`dclass`, o.balance orgbal,o.id orgid,i.serviceorder ,p.id pstid
                        		FROM `service_invoice` i  
                        		LEFT JOIN invoicepaystatus p on i.paymnetst=p.id
                                LEFT JOIN service_order so ON so.code=i.serviceorder
                                LEFT JOIN organization o on so.customer=o.id
                        		
                        	 	WHERE  i.type=1  ";
                        	 	//.$filterorg_str."  ".$filterst_str ." ".$dt_range_str." ";
                        	 	
                        	 	
          $strwithoutsearchquery1="SELECT  1 sl,i.`invoice`,DATE_FORMAT( i.makedt,'%d/%b/%Y') makedt,i.id iid, i.`invyr`, i.`invoiceamt`, DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') `invoicedt`, o.id cid, o.name `organization`, 
                                format(i.`paidamt`,2)paidamount, i.`dueamt` due, p.`name` paySt,p.`id` paymentstid,p.`dclass`, o.balance orgbal,o.id orgid,i.serviceorder ,p.id pstid
                        		FROM `service_invoice` i  
                        		LEFT JOIN invoicepaystatus p on i.paymnetst=p.id
                                LEFT JOIN service_order so ON so.code=i.serviceorder
                                LEFT JOIN organization o on so.customer=o.id
                        		
                        	 	WHERE  i.type=1  $filterorg_str $filterst_str  $dt_range_str $searchQuery ";

	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        
        if($columnName == 'invoicedt'){
            $columnName = "i.invoicedt";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."&orbal=".$row['orgbal']."&orgid=".$row['orgid']."')";

           //$seturl="invoice.php?res=4&msg='Update Data'&id=".$row['invoiceno']."&mod=3";

           //$invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';

           $invpaymentSt='<kbd class="'.$row['dclass'].'">'.$row['paySt'].'</kbd>';
			$invpaymentStid=$row['pstid'];
		   
			
			$invViewLink = '<a data-invid="'.$row['invoice'].'" href="service_invoice_rdl.php?invid='.$row['invoice'].'&mod=22" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
			$invDLLink = '<a href="service_invoice_pdf.php?invid='.$row['invoice'].'&mod=22" class="btn btn-info btn-xs" title="Download PDF" target="_blank" download><i class="fa fa-download"></i></a>';
            $paymentLink = 'service_make_payment_popup.php?invoiceno='.$row['invoice'].'&cid='.$row['orgid'];
           $downloadLink = 'service_invoice_pdf.php?invid='.$row['invoice'].'&mod=22';
           
           	if($row['paymentstid'] == 4 or $row['paymentstid'] == 3)
           	{
			
           $btns = array(
				array('view','service_invoice_rdl.php?invid='.$row['invoice'].'&mod=22','class="show-invoice btn btn-info btn-xs"  title="View Servie Order"  data-invid="'.$row['invoice'].'"  '),
				array('download',$downloadLink ,'class="btn btn-info btn-xs"  title="Download PDF" target="_blank"	  '),
				array('payment',$paymentLink,'class="mkpayment btn btn-info btn-xs"  title="Make Payment" disabled data-invid="'.$row['invoice'].'" data-cid="'.$row['orgid'].'"    '),
			);
           	}
           	else
           	{
           	  $btns = array(
				array('view','service_invoice_rdl.php?invid='.$row['invoice'].'&mod=22','class="show-invoice btn btn-info btn-xs"  title="View Servie Order"  data-invid="'.$row['invoice'].'"  '),
				array('download',$downloadLink ,'class="btn btn-info btn-xs"  title="Download PDF" target="_blank"	  '),
				array('payment',$paymentLink,'class="mkpayment btn btn-info btn-xs"  title="Make Payment"  data-invid="'.$row['invoice'].'" data-cid="'.$row['orgid'].'"    '),
			);  
           	}
           
           $dtl="organazionwallet.php?orgid=".$row['cid']."&mod=7";
           
           	// $invPayLink = '<a data-invid="'.$row['invoice'].'" data-cid="'.$row['orgid'].'"  href="service_make_payment_popup.php?invoiceno='.$row['invoice'].'&cid='.$row['orgid'].'" class="mkpayment  btn btn-info btn-xs" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
           $invPaySlip = '<a href="transaction_list.php?transref='.$row['invoice'].'&mod=22" class="btn btn-info btn-xs paysliplist" title="Money Receipt" data-invid="'.$row['invoice'].'" ><i class="fa fa-file-text-o"></i></a>';
		
           $i++;

			$data[] = array(
                    "sl"=>$i,
					
					"invoice"=>'<span class="rowid_'.$row['invoice'].'">'.$row['invoice'].'</span>',
					"serviceorder"=>$row['serviceorder'],//$strwithoutsearchquery1,// 
					"invoicedt"=>$row['invoicedt'],
            		"organization"=>'<a href="'. $dtl.'" target="_blank">'.$row['organization'].'</a>',
        			"invoiceamt"=> number_format($row['invoiceamt'],2,".",","),
            		"paidamount"=>$row['paidamount'],
    				"dueamt"=>$row['due'],
					"walletbalance" => number_format($row['orgbal'],2,".",","),
            		"paymentSt"=>$invpaymentSt,
            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            		//"edit"=> "".$invViewLink." | ".$invDLLink." | ".$invPayLink."",
            		"action"=>getGridBtns($btns) . " | ".$invPaySlip, 

            	);

        } 

    }
    
if($action=="maintenance")

    {
        if($fdt == ''){
            $dateqry = "";
        }else{
            $dateqry = " and m.date BETWEEN STR_TO_DATE('$fdt','%Y-%m-%d') and STR_TO_DATE('$tdt','%Y-%m-%d')";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and m.code like '%".$searchValue."%' or  o.name like '%".$searchValue."%' or  m.do_number like '%".$searchValue."%' ";
        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery = "SELECT m.id, m.code,m.do_number,DATE_FORMAT(m.date,'%d/%b/%Y') date, m.inspection, mt.name reason,m.fee,m.tds,m.vds,m.total, o.name orgname
                      FROM `maintenance` m LEFT JOIN maintenance_type mt ON m.reason=mt.id LEFT JOIN delivery_order d ON m.do_number=d.do_id LEFT JOIN quotation q ON q.socode=d.order_id 
                      LEFT JOIN organization o on q.organization=o.id
                      WHERE 1=1 $dateqry";
        
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
        
        if($columnName == 'id'){
            $columnName = "m.id";
        }
        if($columnName == 'date'){
            $columnName = "m.date";
        }

    
         $empQuery=$basequery.$searchQuery." order by  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $empQuery; die;
	
		//get privilege of this user: 2=view, 3=create, 4=update, 5=delete;


	
        while($row = mysqli_fetch_assoc($empRecords)){
			
            $setInvurl="service_order.php?res=4&msg='Update Data'&id=".$row['id']."&mod=22";
            $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $setInvurl.'"  ><i class="fa fa-edit"></i></a>';
            
            $invViewLink = '<a data-code="'.$row['code'].'" href="serviceorder_view.php" class="show-invoice btn btn-info btn-xs" title="View Servie Order" target="_blank"><i class="fa fa-eye"></i></a>';
			
			//generate button arrayView Quotation
			$btns = array(
				array('view','maintenanceorder_view.php','class="show-invoice btn btn-info btn-xs"  title="View Maintenance Order"	data-code="'.$row['code'].'"  '),
				array('edit',$setInvurl,'class="btn btn-info btn-xs"  title="Edit"	  '),
			);
            $i++;
            
            if($row["inspection"] == 0) $inspection = 'No';
            else $inspection = 'YES';

            $data[] = array(
				
					"id"=>$i,
					
					"code"=>$row['code'],
					
					"do_number"=>$row['do_number'],

            		"date"=>$row['date'],
            		
            		"reason"=>$row['reason'],

            		"inspection"=>$inspection,
            		
            		"orgname"=>$row['orgname'],
            		
            		"fee"=>$row['fee'],
            		
            		"tds"=>$row['tds'],
            		
            		"vds"=>$row['vds'],
            		
            		"total"=>$row['total'],
            		
            		"action"=>getGridBtns($btns), 

            	);

        } 

    }
    
if($action=="maintenance_invoice"){
    
        if($fdt == ''){
            $dateqry = "";
        }else{
            $dateqry = " and i.`invoicedt` BETWEEN STR_TO_DATE('$fdt','%Y-%m-%d') and STR_TO_DATE('$tdt','%Y-%m-%d')";
        }
        
        if($st == ''){
            $filterst = "";
        }else{
            $filterst = " and i.paymnetst='$st'";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {
			
        	$searchQuery = " and (
				 
				 o.balance like '%".$searchValue."%' or  
				 i.`invoice` like '%".$searchValue."%' or  
                 i.`serviceorder` like '%".$searchValue."%' or 
				 o.name like '%".$searchValue."%' or 
				 i.`invoiceamt`  like '%".$searchValue."%' or 
                 i.`paidamt`  like '%".$searchValue."%'  or 
				 i.`dueamt` like '%".$searchValue."%' or
				 p.`name` like '%".$searchValue."%' or 
				 p.`dclass` like '%".$searchValue."%'
				 
				 )";

        }

        $strwithoutsearchquery="SELECT  1 sl,i.`invoice`,DATE_FORMAT( i.makedt,'%d/%b/%Y') makedt,i.id iid, i.`invyr`, i.`invoiceamt`, DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') `invoicedt`, o.id cid, o.name `organization`, 
                                format(i.`paidamt`,2)paidamount, i.`dueamt` due, p.`name` paySt,p.`id` paymentstid,p.`dclass`, o.balance orgbal,o.id orgid,i.serviceorder 
                        		FROM `service_invoice` i  
                        		LEFT JOIN invoicepaystatus p on i.paymnetst=p.id
                                LEFT JOIN maintenance m ON m.code=i.serviceorder
                                LEFT JOIN delivery_order d ON m.do_number=d.do_id
                                LEFT JOIN quotation q ON q.socode=d.order_id
                                LEFT JOIN organization o on q.organization=o.id
                        		
                        	 	WHERE  i.type=2 ".$filterst ." ".$dateqry." ";

	 
	
        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;

        ##.`id`,
        
        if($columnName == 'invoicedt'){
            $columnName = "i.invoicedt";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

	 
	  
        //s.`status`<>6
        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();
        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $invpaymentSt='<kbd class="'.$row['dclass'].'">'.$row['paySt'].'</kbd>';
			
		   
			
			$invViewLink = '<a data-invid="'.$row['invoice'].'" href="maintenance_invoice_rdl.php?invid='.$row['invoice'].'&mod=22" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
			$invDLLink = '<a href="maintenance_invoice_pdf.php?invid='.$row['invoice'].'&mod=22" class="btn btn-info btn-xs" title="Download PDF" target="_blank" download><i class="fa fa-download"></i></a>';
			$invPayLink = '<a data-invid="'.$row['invoice'].'" data-cid="'.$row['orgid'].'"  href="service_make_payment_popup.php?invoiceno='.$row['invoice'].'&cid='.$row['orgid'].'" class="mkpayment  btn btn-info btn-xs" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
           
           $downloadLink = 'maintenance_invoice_pdf.php?invid='.$row['invoice'].'&mod=22';
           $paymentLink = 'service_make_payment_popup.php?invoiceno='.$row['invoice'].'&cid='.$row['orgid'];
           $btns = array(
				array('view','service_invoice_rdl.php?invid='.$row['invoice'].'&mod=22','class="show-invoice btn btn-info btn-xs"  title="View Servie Order"  data-invid="'.$row['invoice'].'"  '),
				array('download',$downloadLink ,'class="btn btn-info btn-xs"  title="Download PDF" target="_blank"	  '),
				array('payment',$paymentLink,'class="mkpayment btn btn-info btn-xs"  title="Make Payment"	 data-invid="'.$row['invoice'].'" data-cid="'.$row['orgid'].'"    '),
			);
			
           
           
			if($row['paymentstid'] == 4){
				$invPayLink = '<a href="javascript:void(0)" disabled class="btn btn-info btn-xs" style="" title="Pay Invoice"><i class="fa fa-dollar"></i></a>';
			}
			
			$invPaySlip = '<a href="transaction_list.php?transref='.$row['invoice'].'&mod=22" class="btn btn-info btn-xs paysliplist" title="Money Receipt" data-invid="'.$row['invoice'].'" ><i class="fa fa-file-text-o"></i></a>';
		

           $i++;

			$data[] = array(
                    "sl"=>$i,
					
					"invoice"=>'<span class="rowid_'.$row['invoice'].'">'.$row['invoice'].'</span>',
					"serviceorder"=>$row['serviceorder'],
					"invoicedt"=>$row['invoicedt'],
            		"organization"=>$row['organization'],
        			"invoiceamt"=> number_format($row['invoiceamt'],2,".",","),
            		"paidamount"=>$row['paidamount'],
    				"dueamt"=>$row['due'],
					"walletbalance" => number_format($row['orgbal'],2,".",","),
            		"paymentSt"=>$invpaymentSt,
            		//"edit"=> "".$invViewLink." | ".$invDLLink." | ".$invPayLink."",
            		"action"=>getGridBtns($btns) . " | ".$invPaySlip, 

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

        
        //echo $data;die;

        echo json_encode($response);



?>