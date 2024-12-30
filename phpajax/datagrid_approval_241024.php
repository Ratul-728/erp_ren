<?php



require "../common/conn.php";
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


$total = array();
$pqry=" ";



if($action=="transfer_stock")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or bt.name like '%".$searchValue."%' or ts.toid like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ts.id, ts.toid, DATE_FORMAT( ts.tansferdt,'%d/%b/%Y') tansferdt, concat(emp.firstname, ' ', emp.lastname) empnm, ts.st,
                                concat(emp1.firstname, ' ', emp1.lastname) approved, DATE_FORMAT( ts.approvedt,'%d/%b/%Y') approvedt
                                FROM `transfer_stock` ts LEFT JOIN hr h ON h.id=ts.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id 
                                LEFT JOIN hr h1 ON h1.id=ts.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "ts.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {

			$seturl="update_approval_to.php?action=transfer_stock&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" href="'. $seturl.'">Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" href="'. $seturl.'">Accepted<i class="fa fa-check"></i></a>';
            }
            
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"toid"=>$row['toid'],
					"tansferdt"=> $row['tansferdt'],
                    "empnm"=>$row['empnm'],
                    "approved"=>$row['approved'],
                    "approvedt"=>$row['approvedt'],
					"action"=>$urlas,
            	);
        } 
}

if($action=="salary")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or DATE_FORMAT(STR_TO_DATE(a.month, '%m'), '%M') AS month_name like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT DATE_FORMAT(STR_TO_DATE(a.month, '%m'), '%M') AS month_name, concat(emp.firstname, ' ', emp.lastname ) empnm, 
                                a.year, DATE_FORMAT( a.approved_date,'%d/%b/%Y') approved_date, a.st, a.id,a.1st_action_st, concat(emp2.firstname, ' ', emp2.lastname ) empnm2,
                                DATE_FORMAT( a.2nd_approvaldt,'%d/%b/%Y') 2nd_approvaldt, a.2nd_action_st
                                FROM approval_salary a LEFT JOIN hr h ON h.id=a.approved_by LEFT JOIN employee emp ON emp.employeecode = h.emp_id
                                LEFT JOIN hr h2 ON h2.id=a.2nd_approval LEFT JOIN employee emp2 ON emp2.employeecode = h2.emp_id
                                where 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
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
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {

			$seturl="common/update_salary.php?res=4&msg='Update Data'&id=".$row['id'];
            if($row["st"] == 0){
                if($row["approved_by"] == ""){
                    $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
                }else{
                    $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action" href="'. $seturl.'"  >2nd Approval<i class="fa fa-check"></i></a>';
                }
            }else if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled >Accepted<i class="fa fa-check"></i></a>';
            }
            else if($row["st"] == 2){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled >Declined<i class="fa fa-check"></i></a>';
            }
            
            if($row["1st_action_st"] == ""){
                $firstaction = "Pending";
            }
            else if($row["1st_action_st"] == 1){
                $firstaction = "Accepted";
            }else if($row["1st_action_st"] == 0){
                $firstaction = "Declined";
            }
            
            if($row["2nd_action_st"] == ""){
                $secondaction = "Pending";
            }
            else if($row["2nd_action_st"] == 1){
                $secondaction = "Accepted";
            }else if($row["2nd_action_st"] == 0){
                $secondaction = "Declined";
            }
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"month"=>$row['month_name'],
					"year"=> $row['year'],
                    "empnm"=>$row['empnm'],
                    "approved_date"=>$row['approved_date'],
                    "firstaction"=>$firstaction,
                    "empnm2"=>$row['empnm2'],
                    "approved_date2"=>$row['2nd_approvaldt'],
                    "secondaction"=>$secondaction,
					"action"=>$urlas,
            	);
        } 
}

else if($action=="returnorder")
{
        
        if($searchValue != ''){
        	$searchQuery = " and ( ro.ro_id like '%".$searchValue."%' or  
				 ro.order_id like '%".$searchValue."%' or  
				 org.name like '%".$searchValue."%' )";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ro.ro_id, ro.order_id,  DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate, org.name customer, ro.st, ro.id, 
                                concat(emp.firstname, ' ', emp.lastname) approved,  DATE_FORMAT(ro.approvedt, '%d/%b/%Y') approvedt 
	                            FROM `return_order` ro LEFT JOIN quotation q ON q.socode=ro.order_id LEFT JOIN organization org ON org.id=q.organization 
	                            LEFT JOIN hr h ON h.id=ro.approved_by LEFT JOIN employee emp ON emp.employeecode=h.emp_id
	                            WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "sl"){
		    $columnName = "ro.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {

			$seturl="phpajax/update_approval.php?action=returnorder&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $invViewLink = '<a data-invid="'.$row['paySt'].'" href="return_rdl.php?roid='.$row['ro_id'].'&mod=3" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
			$btns = array(
			    array('view','quotation_view.php','class="show-invoice btn btn-info btn-xs"  title="View Quotation"	data-socode="'.$row['order_id'].'" '), 
				array('view','return_rdl.php','class="show-invoice btn btn-info btn-xs"  title="View Return Order"	data-socode="'.$row['ro_id'].'" '),
			);
			
        	$sl=$sl+1;
            $data[] = array(
					"sl"=> $sl,
            		"ro_id"=>$row['ro_id'],
					"order_id"=> $row['order_id'],
					"orderdate"=> $row['orderdate'],
                    "customer"=>$row['customer'],
                    "approved"=>$row['approved'],
                    "approvedt"=>$row['approvedt'],
					"action"=> getGridBtns($btns)." | ". $urlas,
            	);
        } 
}

else if($action=="cancelorder")
{
        
        if($searchValue != ''){
        	$searchQuery = " and ( ro.co_id like '%".$searchValue."%' or  
				 ro.order_id like '%".$searchValue."%' or  
				 org.name like '%".$searchValue."%' )";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ro.id,ro.co_id, ro.order_id,  DATE_FORMAT(q.orderdate, '%d/%b/%Y') orderdate,org.id oid, org.name customer,i.name product,qd.qty orderqty,ro.qty_canceled 
                            ,( case when ro.st=1 then 'Pending' when ro.st=2 then 'Approved' else 'Decliend' end) apprvst 
                            ,ro.st st,qd.otc,
                            concat(emp.firstname, ' ', emp.lastname) requestby,
                            concat(emp1.firstname, ' ', emp1.lastname) approveby
                            FROM `cancel_order` ro 
                            left join quotation q ON q.socode=ro.order_id 
                            LEFT JOIN quotation_detail qd ON q.socode=qd.socode  and ro.productid=qd.productid
                            LEFT JOIN organization org ON org.id=q.organization
                            left join item i on ro.productid=i.id
                            LEFT JOIN hr h ON h.id = ro.makeby LEFT JOIN employee emp ON emp.employeecode = h.emp_id
                            LEFT JOIN hr h1 ON h1.id = ro.approved_by LEFT JOIN employee emp1 ON emp1.employeecode = h1.emp_id
                            WHERE 1=1"; 
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "sl"){
		    $columnName = "ro.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) { 
            $rt=$row['otc']*$row['qty_canceled'];
			$seturl="phpajax/update_approval.php?action=cancelorder&res=4&msg='Update Data'&id=".$row['id']."&mod=24&coid=".$row['co_id']."&org=".$row['oid']."&otc=".$rt;
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $invViewLink = '<a data-invid="'.$row['St'].'" href="cancel_preview.php?roid='.$row['co_id'].'&mod=24" class="show-invoice btn btn-info btn-xs" title="View" target="_blank"><i class="fa fa-eye"></i></a>';
			$btns = array(
				array('view','cancel_preview.php?roid='.$row['co_id'].'&mod=24','class="show-invoice btn btn-info btn-xs"  title="View"	data-socode="'.$row['co_id'].'" '),
			);
			
        	$sl=$sl+1;
            $data[] = array(
					"sl"=> $sl,
            		"ro_id"=>$row['co_id'],
					"order_id"=> $row['order_id'],
					"orderdate"=> $row['orderdate'],
                    "customer"=>$row['customer'], 
                    "product"=>$row['product'],
                    "orderqty"=>$row['orderqty'],
                    "cancelqty"=>$row['qty_canceled'],
                    "requestby"=>$row['requestby'],
                    "approveby"=>$row['approveby'],
                    //"approved"=>$row['approved'],
					"action"=> getGridBtns($btns)." | ". $urlas,
            	);
        } 
}


else if($action=="issue_order")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' or iw.name like '%".$searchValue."%' or ts.ioid like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ts.id, ts.ioid, DATE_FORMAT( ts.iodt,'%d/%b/%Y') iodt,DATE_FORMAT( ts.deliverydt,'%d/%b/%Y') deliverydt, concat(emp.firstname, ' ', emp.lastname) empnm, ts.st,iw.name,iw.address,
                                concat(emp1.firstname, ' ', emp1.lastname) approved, DATE_FORMAT( ts.approvedt,'%d/%b/%Y') approvedt
                                FROM `issue_order` ts LEFT JOIN hr h ON h.id=ts.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                LEFT JOIN hr h1 ON h1.id=ts.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                LEFT JOIN issue_warehouse iw ON iw.id=ts.issue_warehouse
                                WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "ts.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {

			$seturl="update_approval_io.php?action=transfer_stock&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" href="'. $seturl.'">Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" href="'. $seturl.'">Accepted<i class="fa fa-check"></i></a>';
            }
            
            $seturl="phpajax/update_approval.php?action=issue_order&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $btns = array(
				array('view','issue_order_approval_rdl.php','class="show-invoice btn btn-info btn-xs"  title="View"	data-code="'.$row['ioid'].'" '),
			);
            
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"ioid"=>$row['ioid'],
					"deliverydt"=> $row['deliverydt'],
					"iodt"=> $row['iodt'],
					"name"=> $row['name'],
					"address"=> $row['address'],
                    "empnm"=>$row['empnm'],
                    "approved"=>$row['approved'],
                    "approvedt"=>$row['approvedt'],
					"action"=>getGridBtns($btns)." | ". $urlas,
            	);
        } 
}

else if($action=="do")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (q.order_id like '%".$searchValue."%' or org.name like '%".$searchValue."%' or i.name like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ad.id,ad.qa_id, org.name orgnm, concat(emp.firstname, ' ', emp.lastname) empnm, 
                                DATE_FORMAT( ad.makedt,'%d/%b/%Y') makedt, ad.st, concat(emp1.firstname, ' ', emp1.lastname) approved,
                                DATE_FORMAT( ad.approvedt,'%d/%b/%Y') approvedt
                                FROM `approval_do` ad LEFT JOIN quotation quo ON quo.socode=ad.qa_id
                                LEFT JOIN organization org ON org.id=quo.organization LEFT JOIN hr h ON h.id=ad.makeby 
                                LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                LEFT JOIN hr h1 ON h1.id=ad.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                WHERE 1=1 ";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "ad.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=do&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            

            $photo="assets/images/products/300_300/".$row["image"];
            
            $btns = array(
				array('view','do_rdl.php','class="show-invoice btn btn-info btn-xs"  title="View"	data-socode="'.$row['qa_id'].'"'),
			);
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"order_id"=>$row['qa_id'],
					//"image"=> '<img src='.$photo.' width="50" height="50">',
                    "orgnm"=>$row['orgnm'],
            		"empnm"=>$row['empnm'],
            		"makedt"=>$row['makedt'],
            		"approved"=>$row['approved'],
            		"approvedt"=>$row['approvedt'],
					"action"=> getGridBtns($btns)." | ". $urlas,
            	);
        } 
}

else if($action=="co")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (c.order_id like '%".$searchValue."%' or org.name like '%".$searchValue."%' or c.co_id like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT c.order_id, org.name orgnm, DATE_FORMAT(q.orderdate,'%e/%c/%Y') orderdate, c.approval,c.id,c.co_id, 
                                CONCAT(emp.firstname, ' ', emp.lastname) AS empnm, DATE_FORMAT(c.actiondt,'%e/%c/%Y') actiondt
                                FROM `co_approval` c LEFT JOIN quotation q ON c.order_id=q.socode LEFT JOIN organization org ON org.id=q.organization
                                LEFT JOIN hr h ON h.id=c.approved_by LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
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
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=co&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["approval"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["approval"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            

            $photo="assets/images/products/300_300/".$row["image"];
            
            $btns = array(
    		    array('view','co_view.php?code='.$row["id"],'class="show-invoice btn btn-info btn-xs"  title="View CO"	data-code="'.$row['order_id'].'"  '),
        	);
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
					"co_id"=>$row['co_id'],
            		"order_id"=>$row['order_id'],
					"orderdate"=>$row['orderdate'],
                    "orgnm"=>$row['orgnm'],
                    "empnm"=>$row['empnm'],
                    "actiondt"=>$row['actiondt'],
					"action"=> getGridBtns($btns)." | ". $urlas,
            	);
        } 
}

else if($action=="defect")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (q.order_id like '%".$searchValue."%' or b.name like '%".$searchValue."%' or i.name like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ad.qaw_id, ad.id, q.order_id, b.name warehouse, i.name productnm, concat(emp.firstname, ' ', emp.lastname) empnm, 
                                DATE_FORMAT( ad.makedt,'%d/%b/%Y') makedt, ad.st, ad.qty, i.image, concat(emp1.firstname, ' ', emp1.lastname) approved,
                                DATE_FORMAT( ad.approvedt,'%d/%b/%Y') approvedt, ts.toid, q.type
                                FROM `approval_defect` ad 
                                LEFT JOIN qa_warehouse qaw ON qaw.id=ad.qaw_id 
                                LEFT JOIN qa q ON q.id=qaw.qa_id 
                                LEFT JOIN quotation quo ON quo.socode=q.order_id 
                                LEFT JOIN item i ON i.id=q.product_id 
                                LEFT JOIN hr h ON h.id=ad.makeby 
                                LEFT JOIN employee emp ON emp.employeecode=h.emp_id 
                                LEFT JOIN branch b ON qaw.warehouse_id=b.id
                                LEFT JOIN hr h1 ON h1.id=ad.approved_by 
                                LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                LEFT JOIN transfer_stock ts ON ts.id = q.order_id
                                WHERE 1=1 ";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "ad.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=defect&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $photo="assets/images/products/300_300/".$row["image"];
            
            $type = '';
            if($row["type"] == 4){
                $orderid = $row["toid"];
            }else{
                $orderid = $row["order_id"];
            }
            
            switch ($row["type"]) {
                case 1:
                    $type = "Sales Order";
                    break;
                case 2:
                    $type = "Purchase Order";
                    break;
                case 3:
                    $type = "Return";
                    break;
                case 4:
                    $type = "Transfer Stock";
                    break;
                case 5:
                    $type = "Issue Order";
                    break;
                case 6:
                    $type = "Return Order";
                    break;
                case 7:
                    $type = "Gift Order";
                    break;
                case 8:
                    $type = "CO Allocated TO";
                    break;
                case 9:
                    $type = "Periodic QC";
                    break;
            }

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
                        $picturelist[$qawId] .= '<li class="picbox"><a class="picture-preview" href="../images/upload/qa_images/original/'.$lidata['image_url'].'""><img src="../images/upload/qa_images/thumb/'.$lidata['image_url'].'"></a></li>';
                    }
                    $picturelist[$qawId] .='</ul></div>';
            	}
            	$inputDefImgData = "";
            	$imgDefData = "";
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"order_id"=>$orderid,
            		"event"=>$type,
            		//"image"=> '<img src='.$photo.' width="50" height="50">',
            		"image"=>  $picturelist[$qawId],
					"productnm"=> $row['productnm'],
                    "warehouse"=>$row['warehouse'],
                    "qty"=>$row['qty'],
            		"empnm"=>$row['empnm'],
            		"makedt"=>$row['makedt'],
            		"approved"=>$row['approved'],
            		"approvedt"=>$row['approvedt'],
					"action"=>$urlas,
            	);
            	
            	$picturelist = "";
        } 
}

else if($action=="damage")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (q.order_id like '%".$searchValue."%' or b.name like '%".$searchValue."%' or i.name like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ad.qaw_id, ad.id, q.order_id, b.name warehouse, i.name productnm, concat(emp.firstname, ' ', emp.lastname) empnm, 
                                DATE_FORMAT( ad.makedt,'%d/%b/%Y') makedt, ad.st, ad.qty, i.image, concat(emp1.firstname, ' ', emp1.lastname) approved,
                                DATE_FORMAT( ad.approvedt,'%d/%b/%Y') approvedt, ts.toid, q.type
                                FROM `approval_damaged` ad LEFT JOIN qa_warehouse qaw ON qaw.id=ad.qaw_id LEFT JOIN qa q ON q.id=qaw.qa_id 
                                LEFT JOIN quotation quo ON quo.socode=q.order_id LEFT JOIN item i ON i.id=q.product_id LEFT JOIN hr h ON h.id=ad.makeby 
                                LEFT JOIN employee emp ON emp.employeecode=h.emp_id LEFT JOIN branch b ON qaw.warehouse_id=b.id
                                LEFT JOIN hr h1 ON h1.id=ad.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                LEFT JOIN transfer_stock ts ON ts.id = q.order_id
                                WHERE 1=1 ";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "ad.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=damage&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $photo="assets/images/products/300_300/".$row["image"];
            
            if($row["type"] == 4){
                $orderid = $row["toid"];
            }else{
                $orderid = $row["order_id"];
            }


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


        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"order_id"=>$orderid,
					"productnm"=> $row['productnm'],
					//"image"=> '<img src='.$photo.' width="50" height="50">',
					"image"=>  $picturelist[$qawId],
                    "warehouse"=>$row['warehouse'],
                    "qty"=>$row['qty'],
            		"empnm"=>$row['empnm'],
            		"makedt"=>$row['makedt'],
            		"approved"=>$row['approved'],
            		"approvedt"=>$row['approvedt'],
					"action"=>$urlas,
            	);
        } 
}

else if($action=="qc")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (q.order_id like '%".$searchValue."%' or org.name like '%".$searchValue."%' or i.name like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ac.id,i.`invoiceno`, DATE_FORMAT(i.`invoicedt`, '%d/%b/%Y') invoicedt, concat(emp.firstname, ' ', emp.lastname) empnm, 
                                o.name `organization`, format(i.amount_bdt,2) amount_bdt, o.balance orgbal,o.id orgid,i.`soid`, i.paymentSt, i.id iid,
                                DATE_FORMAT( ac.makedt,'%d/%b/%Y') makedt, ac.st, concat(emp1.firstname, ' ', emp1.lastname) approved,
                                DATE_FORMAT( ac.approvedt,'%d/%b/%Y') approvedt
                                FROM `approval_qc` ac LEFT JOIN `invoice` i ON ac.invid=i.id
                                LEFT JOIN organization o on i.organization=o.id
                                LEFT JOIN hr h ON h.id=ac.makeby 
                                LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                LEFT JOIN hr h1 ON h1.id=ac.approved_by 
                                LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "ac.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=qc&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $btns = array(
				
				array('view','quotation_view.php','class="show-invoice btn btn-info btn-xs"  title="View"	data-socode="'.$row['soid'].'"  '),
			);
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"soid"=>$row['soid'],
					"invoicedt"=> $row['invoicedt'],
                    "organization"=>$row['organization'],
            		"orgbal"=>$row['orgbal'],
            		"amount_bdt"=>$row['amount_bdt'],
            		"empnm"=>$row['empnm'],
            		"makedt"=>$row['makedt'],
            		"approved"=>$row['approved'],
            		"approvedt"=>$row['approvedt'],
					"action"=> getGridBtns($btns)." | ". $urlas,
            	);
        } 
}
else if($action=="withdrawal")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (org.name like '%".$searchValue."%' or aw.note like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT aw.id, org.name orgnm,org.orgcode, aw.amount, aw.note, concat(emp.firstname, ' ', emp.lastname) empnm,DATE_FORMAT( aw.makedt,'%d/%b/%Y') makedt,
                                aw.st, org.balance, concat(emp1.firstname, ' ', emp1.lastname) approved, DATE_FORMAT( aw.approvedt,'%d/%b/%Y') approvedt
                                FROM `approval_withdrawal` aw LEFT JOIN organization org ON org.id=aw.orgid LEFT JOIN hr h ON h.id=aw.makeby 
                                LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                LEFT JOIN hr h1 ON h1.id=aw.approved_by LEFT JOIN employee emp1 ON emp1.employeecode=h1.emp_id
                                WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "aw.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=withdrawal&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
					"orgcode"=>$row['orgcode'],
            		"orgnm"=>$row['orgnm'],
					"amount"=> $row['amount'],
					"balance"=> $row['balance'],
                    "note"=>$row['note'],
            		"empnm"=>$row['empnm'],
            		"makedt"=>$row['makedt'],
            		"approved"=>$row['approved'],
            		"approvedt"=>$row['approvedt'],
					"action"=>$urlas,
            	);
        } 
}

else if($action=="future")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  concat(e1.firstname,'  ',e1.lastname) like '%".$searchValue."%' or 

                 tp.`name` like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or orst.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or o.`orgcode`  like '%".$searchValue."%' or cr.shnm  like '%".$searchValue."%'

                 or s.`socode` like '%".$searchValue."%' or s.`orderdate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;
        $cmbstatus = $_REQUEST["cmbstatus"];
        if($cmbstatus){
        	$cmbstatus_str = "and orderstatus = ".$cmbstatus;
        }else{$cmbstatus_str = "";}


        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT s.`id`,orst.sl, s.makedt makedt, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, o.orgcode, s.`orderdate`, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate_formated`
        ,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,s.orderstatus, orst.name `quotationstatusname`,s.invoiceamount  invoiceamount, format(sum(qtymrc*sd.mrc),2) mrc,
        concat(e.firstname,'  ',e.lastname) `hrName`, concat(e1.firstname,'  ',e1.lastname) `poc`, MIN(DATE_FORMAT( ti.expted_deliverey_date,'%d/%b/%Y')) AS expted_deliverey_date,
        inv.id iid, inv.paymentSt,(case when s.srctype=2 then (select name from project where id=s.project) else 'Retail' end) saletp,  concat(emp.firstname, ' ', emp.lastname) approved,
        date_format(inv.`approvedt`,'%d/%b/%Y') `approvedt`
        FROM `quotation` s left join `quotation_detail` sd on sd.socode=s.socode
        left join `quotation_warehouse` ti on ti.socode=s.socode
        left join `contacttype` tp on  s.`srctype`=tp.`id` 
        left join`contact` c on s.`customer`=c.`id` 
        left join `organization` o on o.`orgcode`=c.organization  
        left join `invoice` inv on inv.`soid`=s.socode  
        left join `quotation_status` orst on s.`orderstatus`=orst.`id` 
        left join `hr` h on o.`salesperson`=h.`id` 
        left join employee e on h.`emp_id`=e.`employeecode` 
        left join `hr` h1 on s.`poc`=h1.`id`  
        left join employee e1 on h1.`emp_id`=e1.`employeecode`
        left join currency cr on sd.currency=cr.id
        LEFT JOIN hr h2 ON h2.id=inv.approved_by LEFT JOIN employee emp ON emp.employeecode=h2.emp_id
        WHERE  (inv.backorder = 3 or inv.backorder = 5) and (s.organization = $orgid or $orgid = 0) $cmbstatus_str";

        $strwithoutsearchquery=$basequery." group by s.`id`, s.orderdate,s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm,s.orderstatus";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery." group by s.`id`, s.orderdate, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        
        
        if($columnName == "socode"){
            $columnName = "s.socode";
        }
        else if ($columnName == "orderdate")
        { 
            $columnName = "s.orderdate";
        }
        else if ($columnName == "makedt")
        { 
            $columnName = "ti.expted_deliverey_date";
        }
        else
        {
            $columnName=$columnName;
        }
        

         $empQuery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus  order by s.orderstatus asc,  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
//orst.sl asc ,
        //s.`status`<>6
        
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

	
        while($row = mysqli_fetch_assoc($empRecords)){
		
            $i++;
			
			//generate button array
			$seturl="phpajax/update_approval.php?action=future&res=4&msg='Update Data'&id=".$row['iid']."&mod=24";
            
            $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            
			$btns = array(
				array('view','quotation_view.php','class="show-invoice btn btn-info btn-xs"  title="View Quotation"	data-socode="'.$row['socode'].'" data-st="'.$row["quotationstatusname"].'"  '),
			);

            $data[] = array(
					
					"expted_deliverey_date"=>$row['expted_deliverey_date'],

            		"organization"=>$row['organization'],

        			"socode"=>$row['socode'],//$empQuery,//
        			
        			"saletp"=>$row['saletp'], 

            		"orderdate"=>$row['orderdate_formated'],
            		
            		"approved"=>$row['approved'],
            		
            		"approvedt"=>$row['approvedt'],

                    "action_buttons"=> getGridBtns($btns)." | ". $urlas,

            	);

        } 

    }
else if($action=="back")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  concat(e1.firstname,'  ',e1.lastname) like '%".$searchValue."%' or 

                 tp.`name` like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or orst.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or o.`orgcode`  like '%".$searchValue."%' or cr.shnm  like '%".$searchValue."%'

                 or s.`socode` like '%".$searchValue."%' or s.`orderdate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;
        $cmbstatus = $_REQUEST["cmbstatus"];
        if($cmbstatus){
        	$cmbstatus_str = "and orderstatus = ".$cmbstatus;
        }else{$cmbstatus_str = "";}


        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT s.`id`,orst.sl, s.makedt makedt, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, o.orgcode, s.`orderdate`, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate_formated`
        ,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,s.orderstatus, orst.name `quotationstatusname`,s.invoiceamount  invoiceamount, format(sum(qtymrc*sd.mrc),2) mrc,
        concat(e.firstname,'  ',e.lastname) `hrName`, concat(e1.firstname,'  ',e1.lastname) `poc`, MIN(DATE_FORMAT( ti.expted_deliverey_date,'%d/%b/%Y')) AS expted_deliverey_date,
        inv.id iid, inv.paymentSt,(case when s.srctype=2 then (select name from project where id=s.project) else 'Retail' end) saletp,  concat(emp.firstname, ' ', emp.lastname) approved, 
        date_format(inv.`approvedt`,'%d/%b/%Y') `approvedt`
        FROM `quotation` s left join `quotation_detail` sd on sd.socode=s.socode
        left join `quotation_warehouse` ti on ti.socode=s.socode
        left join `contacttype` tp on  s.`srctype`=tp.`id` 
        left join`contact` c on s.`customer`=c.`id` 
        left join `organization` o on o.`orgcode`=c.organization  
        left join `invoice` inv on inv.`soid`=s.socode  
        left join `quotation_status` orst on s.`orderstatus`=orst.`id` 
        left join `hr` h on o.`salesperson`=h.`id` 
        left join employee e on h.`emp_id`=e.`employeecode` 
        left join `hr` h1 on s.`poc`=h1.`id`  
        left join employee e1 on h1.`emp_id`=e1.`employeecode`
        left join currency cr on sd.currency=cr.id
        LEFT JOIN hr h2 ON h2.id=inv.approved_by LEFT JOIN employee emp ON emp.employeecode=h2.emp_id
        WHERE  (inv.backorder = 4 or inv.backorder = 5) and (s.organization = $orgid or $orgid = 0) $cmbstatus_str";

        $strwithoutsearchquery=$basequery." group by s.`id`, s.orderdate,s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm,s.orderstatus";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$basequery.$searchQuery." group by s.`id`, s.orderdate, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus";

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        
        
        if($columnName == "socode"){
            $columnName = "s.socode";
        }
        else if ($columnName == "orderdate")
        { 
            $columnName = "s.orderdate";
        }
        else if ($columnName == "makedt")
        { 
            $columnName = "ti.expted_deliverey_date";
        }
        else
        {
            $columnName=$columnName;
        }
        

         $empQuery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus  order by s.orderstatus asc,  ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
//orst.sl asc ,
        //s.`status`<>6
        
        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

	
        while($row = mysqli_fetch_assoc($empRecords)){
		
            $i++;
			
			//generate button array
			$seturl="phpajax/update_approval.php?action=back&res=4&msg='Update Data'&id=".$row['iid']."&mod=24";
            
            $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            
			$btns = array(
				array('view','quotation_view.php','class="show-invoice btn btn-info btn-xs"  title="View Quotation"	data-socode="'.$row['socode'].'" data-st="'.$row["quotationstatusname"].'"  '),
			);

            $data[] = array(
					
					"expted_deliverey_date"=>$row['expted_deliverey_date'],

            		"organization"=>$row['organization'],

        			"socode"=>$row['socode'],//$empQuery,//
        			
        			"saletp"=>$row['saletp'], 

            		"orderdate"=>$row['orderdate_formated'],
            		
            		"approved"=>$row['approved'],
            		
            		"approvedt"=>$row['approvedt'],

                    "action_buttons"=> getGridBtns($btns)." | ". $urlas,

            	);

        } 

    }

else if($action=="check")
{
        
        if($searchValue != ''){
        	$searchQuery = " and ( ac.invoice like '%".$searchValue."%' or b.name like '%".$searchValue."%' or org.name like '%".$searchValue."%' or  
				 ac.checkno like '%".$searchValue."%' or  concat(emp2.firstname, ' ', emp2.lastname) like '%".$searchValue."%' or
				 concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%' )";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT ac.id, ac.invoice, ac.checkno, DATE_FORMAT( ac.checkdt,'%d/%b/%Y') checkdt, b.name bank, ac.amount, ac.note, ac.image, org.name orgnm, concat(emp.firstname, ' ', emp.lastname) requestby, 
                                concat(emp2.firstname, ' ', emp2.lastname) approvedby, ac.st, inv.soid, inv.paymentSt paymentstid, inv.id iid, 
                                DATE_FORMAT( ac.approvedt,'%d/%b/%Y') approvedt
                                FROM `approval_check` ac LEFT JOIN bank b ON b.id=ac.bank LEFT JOIN invoice inv ON inv.invoiceno=ac.invoice LEFT JOIN organization org ON org.id=inv.organization 
                                LEFT JOIN hr h ON h.id=ac.makeby LEFT JOIN employee emp ON emp.employeecode=h.emp_id LEFT JOIN hr h2 ON h2.id=ac.approved 
                                LEFT JOIN employee emp2 ON emp2.employeecode=h2.emp_id
                                WHERE 1=1";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "sl"){
		    $columnName = "ac.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {

			$seturl="phpajax/update_approval.php?action=check&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["st"] == 1){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action" href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["st"] == 0){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
        
            if($row["image"] != ""){
                $image = "/images/upload/checks/".$row["image"];
                $photo = '<img src='.$image.' width="50" height="50">';
                
                $imagePrev = '<div class="ajax-img-up"><ul class="d-flex defect-img">
                                <li class="picbox"><a class="picture-preview" href="'.$image.'">'.$photo.'</a></li>
                            </ul></div>
                ';
            }else{
                $imagePrev = "";
            }
            
            $dynamicNumber = $row["iid"];
            $dynamicNumberString = (string)$dynamicNumber;
            $resultString = str_pad($dynamicNumberString, 6, '0', STR_PAD_LEFT);
            if($row['paymentstid'] == 4){
			    
			    $inv = "INV-".$resultString;
			}else if($row['paymentstid'] == 5){
			    $inv = "PI-".$resultString;
			}else{
			    $inv= '';
			}
			
        	$sl=$sl+1;
            $data[] = array(
					"sl"=> $sl,
					"quotation"=>$row['soid'],
            		"invoice"=>$inv,
					"checkno"=> $row['checkno'],
					"image"=> $imagePrev,
                    "checkdt"=>$row['checkdt'],
                    "bank"=>$row['bank'],
					"orgnm"=> $row['orgnm'],
					"amount"=> number_format($row['amount'],2),
                    "note"=>$row['note'],
                    "requestby"=>$row['requestby'],
                    "approvedby"=>$row['approvedby'],
                    "approvedt"=>$row['approvedt'],
					"action"=> $urlas,
            	);
        } 
}

else if($action=="gift")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (q.socode like '%".$searchValue."%' or org.name like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT q.socode, date_format(q.`orderdate`,'%d/%b/%Y') orderdate, org.name customer, i.id invid,
                                concat(emp.firstname, ' ', emp.lastname) approved,
                                concat(emp1.firstname, ' ', emp1.lastname) request,
                                date_format(i.`approvedt`,'%d/%b/%Y') `approvedt`
                                FROM `quotation` q LEFT JOIN invoice i ON i.soid=q.socode LEFT JOIN organization org ON org.id=q.organization
                                LEFT JOIN hr h2 ON h2.id=i.approved_by LEFT JOIN employee emp ON emp.employeecode=h2.emp_id
                                LEFT JOIN hr h3 ON h3.id=q.makeby LEFT JOIN employee emp1 ON emp1.employeecode=h3.emp_id
                                WHERE i.approval=3 ";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "q.id";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=gift&res=4&msg='Update Data'&id=".$row['invid']."&mod=24";
			$urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            
            $btns = array(
				array('view','gift_rdl.php','class="show-invoice btn btn-info btn-xs"  title="View"	data-socode="'.$row['socode'].'"'),
			);
            
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"socode"=>$row['socode'],
                    "orderdate"=>$row['orderdate'],
            		"customer"=>$row['customer'],
            		"request"=>$row['request'],
            		"approved"=>$row['approved'],
            		"approvedt"=>$row['approvedt'],
					"action"=> getGridBtns($btns)." | ". $urlas,
            	);
        } 
}
else if($action=="itm_rate_chnage")
{
        
        if($searchValue != ''){
        	$searchQuery = " and (p.barcode like '%".$searchValue."%' or p.name like '%".$searchValue."%' or p.rate like '%".$searchValue."%'  or c.newrate  like '%".$searchValue."%'  or u.hrName like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT c.id,p.barcode,p.name prod,p.rate,p.image,c.newrate,c.reason,u.hrName reqby,DATE_FORMAT(c.makedt,'%e/%c/%Y') trdt ,
                                concat(emp.firstname, ' ', emp.lastname) approved_by,
                                DATE_FORMAT(c.approvedt , '%d/%b/%Y') approvedt, c.approvst 
                                FROM `approval_item_price_change` c LEFT JOIN item p ON p.id=c.product LEFT JOIN hr u ON c.makeby=u.id LEFT JOIN hr h ON h.id =c.approveby
                                LEFT JOIN employee emp ON emp.employeecode=h.emp_id
                                where 1=1 ";
		
		//$strwithoutsearchquery="SELECT id, code, title, origin, image, makedt  FROM brand";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        ##.`id`,
        if($columnName == "id"){
		    $columnName = "c.makedt";
		}
	  	
		$empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
		 
		
        //print_r($empQuery);exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
			$seturl="phpajax/update_approval.php?action=approval_itm_rate_change&res=4&msg='Update Data'&id=".$row['id']."&mod=24";
            if($row["approvst"] == 0){
                $urlas='<a class="btn btn-info btn-xs actionbtn" title="Action"  href="'. $seturl.'"  ><i class="fa fa-check"></i></a>';
            }else if($row["approvst"] == 1){
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
            }else{
                $urlas='<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
            }
            
            $photo="assets/images/products/300_300/".$row["image"];
            

           
        	$sl=$sl+1;
            $data[] = array(
					"id"=> $sl,
            		"barcode"=>$row['barcode'],//$empQuery,//
            		"image"=> '<img src='.$photo.' width="50" height="50">',
            	//	"image"=>  $picturelist[$qawId],
					"productnm"=> $row['prod'],
                    "currentrate"=>$row['rate'],
                    "newrate"=>$row['newrate'],
            		"reason"=>$row['reason'],
            		"makeby"=>$row['reqby'],
            		"makedt"=>$row['trdt'],
            		"approved_by"=>$row['approved_by'],
            		"approvedt"=>$row['approvedt'],
					"action"=>$urlas,
            	);
            	
            	$picturelist = "";
        } 
}

else
{
    
}
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