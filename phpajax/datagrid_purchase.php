<?php



require "../common/conn.php";
//require "../common/gridbtns.php";
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
	$cmbstatus_str = "and st = ".$cmbstatus;
}else{$cmbstatus_str = "";}

$total = array();
$pqry=" ";



if($action=="purchasedata")

    {
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and pl.`gnr_date` between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
        }else{
            $date_qry = "";
        }

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and pl.poid like '%".$searchValue."%' or pl.`voucher_no` like '%".$searchValue."%' or pl.`voucher_no` like '%".$searchValue."%' or pl.pi_no like '%".$searchValue."%'
        	                or pl.lc_tt_no like '%".$searchValue."%' or pl.gnr_no like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%'"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT pl.poid, pl.`voucher_no`, DATE_FORMAT(pl.`voucher_date`,'%d/%b/%Y') voucher_date, pl.pi_no, DATE_FORMAT(pl.`pi_date`,'%d/%b/%Y') pi_date, pl.lc_tt_no,DATE_FORMAT(pl.`lc_tt_date`,'%d/%b/%Y') lc_tt_date, pl.at,pl.ait, pl.gnr_no,DATE_FORMAT(pl.`gnr_date`,'%d/%b/%Y') gnr_date,
                    concat(emp.firstname, ' ', emp.lastname) nm, b.name warehouse, pos.dclass, pl.st, pos.name stnm, pl.id, pl.payment_amount
                    ,(SELECT sum(`tot_landed_cost`) landedcost FROM purchase_landing_item i where i.`pu_id`=pl.id) landedcost
                    ,pl.containerno
                    FROM `purchase_landing` pl LEFT JOIN employee emp ON pl.received_by=emp.id LEFT JOIN suplier b ON b.id=pl.warehouse LEFT JOIN purchase_st pos ON pos.id=pl.st
                    WHERE 1=1 $date_qry $cmbstatus_str";

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
            $columnName = "pl.id";
        }
        if($columnName == "voucher_date"){
            $columnName = "pl.voucher_date";
        }
        if($columnName == "pi_date"){
            $columnName = "pl.pi_date";
        }
        if($columnName == "lc_tt_date"){
            $columnName = "pl.lc_tt_date";
        }
        if($columnName == "gnr_date"){
            $columnName = "pl.gnr_date";
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

            $st= '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
            
            $seturl="purchase_dataform.php?res=2&msg='Update Data'&mod=12&poid=".$row['poid'];
            $setdelurl="common/delobj.php?obj=purchase_landing&ret=purchase_dataformList&mod=12&id=".$row['id'];
            
            if($row['st'] == 1){
				$urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
                $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';

			}else{
			    //$urlas='<a class="btn btn-info btn-xs" title="Edit" disabled href="javascript:void(0)"  ><i class="fa fa-edit"></i></a>';
                $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
                $urlasdel='<a class="btn btn-info btn-xs" disabled title="Delete"  href="javascript:void(0)" ><i class="fa fa-remove"></i></a>';
			}
			
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"poid"=>$row['poid'],
					
					"voucher_no"=>$row['voucher_no'],

            		"voucher_date"=>$row['voucher_date'],

            		"pi_no"=>$row['pi_no'],
					
					"pi_date"=>$row['pi_date'],
					
					"lc_tt_no"=>$row['lc_tt_no'],
					
					"lc_tt_date"=>$row['lc_tt_date'],
					
					"payment_amount"=> number_format($row["payment_amount"], 2),
					"landedcost" =>  number_format($row["landedcost"], 2),
                     "containerno" =>  $row["containerno"],
            		"at"=>$row['at'],

            		"ait"=>$row['ait'],
					
					"gnr_no"=>$row['gnr_no'],
					
					"gnr_date"=>$row['gnr_date'],
					
					"warehouse"=>$row['warehouse'],

            		"nm"=>$row['nm'],
				
				    "stnm"=>$st,
				    
				    "action"=> $urlas." | ".$urlasdel

            	);

        } 

    }
    
if($action=="purchasedatainv")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and pl.poid like '%".$searchValue."%' or pl.`voucher_no` like '%".$searchValue."%' or pl.`voucher_no` like '%".$searchValue."%' or pl.pi_no like '%".$searchValue."%'
        	                or pl.lc_tt_no like '%".$searchValue."%' or pl.gnr_no like '%".$searchValue."%' or concat(emp.firstname, ' ', emp.lastname) like '%".$searchValue."%'"; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT pl.poid, pl.`voucher_no`, pl.voucher_date, pl.pi_no, pl.pi_date, pl.lc_tt_no, pl.lc_tt_date, pl.at,pl.ait, pl.gnr_no, pl.gnr_date,
                    concat(emp.firstname, ' ', emp.lastname) nm, b.name warehouse, pos.name stnm, pos.dclass, pl.st
                    FROM `purchase_landing` pl LEFT JOIN employee emp ON pl.received_by=emp.id LEFT JOIN branch b ON b.id=pl.warehouse LEFT JOIN purchase_st pos ON pos.id=pl.st
                    WHERE 1=1 $cmbstatus_str";

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
            $columnName = "pl.id";
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

            $st= '<kbd class="'.$row['dclass'].'">'.$row['stnm'].'</kbd>';
            if($row["st"] == 2 || $row["st"] == 3){
                $seturl = "purchase_data_inv.php?mod=12&po=".$row['poid'];
                $addInv = '<a class="btn btn-info btn-xs"  href="'. $seturl.'">Inventory</a>';
            }else{
                $addInv = '<a class="btn btn-info btn-xs"href="javascript:void(0)" dissabled >Inventory</a>';
            }
            
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
					"sl"=>$i,
					
					"poid"=>$row['poid'],
					
					"voucher_no"=>$row['voucher_no'],

            		"voucher_date"=>$row['voucher_date'],

            		"pi_no"=>$row['pi_no'],
					
					"pi_date"=>$row['pi_date'],
					
					"lc_tt_no"=>$row['lc_tt_no'],
					
					"lc_tt_date"=>$row['lc_tt_date'],

            		"at"=>$row['at'],

            		"ait"=>$row['ait'],
					
					"gnr_no"=>$row['gnr_no'],
					
					"gnr_date"=>$row['gnr_date'],
					
					"warehouse"=>$row['warehouse'],

            		"nm"=>$row['nm'],
            		
            		"stnm"=>$st,
            		
            		"action"=>$addInv,
				
				

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