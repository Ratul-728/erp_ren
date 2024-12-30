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
extract($_REQUEST);
$dt_range_str = ($dt_f && $dt_t)?" and s.orderdate BETWEEN '".$dt_f."' AND '".$dt_t."'":"";
$pid_str = ($pid)?" and sd.productid=".$pid:"";


if($action=="inv_soitem"){

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  concat(e1.firstname,'  ',e1.lastname) like '%".$searchValue."%' or 

                 tp.`name` like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or orst.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or o.`orgcode`  like '%".$searchValue."%' or cr.shnm  like '%".$searchValue."%'

                 or s.`socode` like '%".$searchValue."%' or s.`orderdate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;

        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT s.`id`, s.makedt makedt, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, o.orgcode, s.`orderdate`, date_format(s.`orderdate`,'%d/%m/%Y') `orderdate_formated`
        ,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,s.orderstatus, orst.name `orderstatusname`, s.invoiceamount  invoiceamount, format(sum(qtymrc*sd.mrc),2) mrc,concat(e.firstname,'  ',e.lastname) `hrName`, concat(e1.firstname,'  ',e1.lastname) `poc`

FROM `soitem` s 
left join `soitemdetails` sd on sd.socode=s.socode 
left join `contacttype` tp on  s.`srctype`=tp.`id` 
left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization  

left join `orderstatus` orst on s.`orderstatus`=orst.`id` 
left join `hr` h on o.`salesperson`=h.`id` 
left join employee e on h.`emp_id`=e.`employeecode` 
left join `hr` h1 on s.`poc`=h1.`id`  
left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join currency cr on sd.currency=cr.id WHERE  1=1 ".$pid_str." ".$dt_range_str." and (s.organization = $orgid or $orgid = 0) $cmbstatus_str";

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

        if($columnName == "id"){
            $columnName = "s.id";
        }

         $empQuery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm ,s.orderstatus  order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

		//echo $strwithoutsearchquery;die;
	
	

	
        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
			//booked order can be edited by who created this order.;

            $st=$row['orderstatus'];
			
			$order_creator_id  = fetchByID('soitem','id',$row['id'],'makeby');
            
            $seturl="inv_soitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
			$socode=$row['socode'];
			
			
			//booked order can be editted by its creator and admin. .
            //if(($st == 9 && $order_creator_id == $_SESSION['user']) || $st == 1 || $_SESSION['user'] == 1){
			
			
			
			if(( $st == 1 || $order_creator_id == $_SESSION['user']) || $_SESSION['currPriv'] > 3){
				
            	$urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
            }
            else
            {
            $urlas='<a class="btn btn-info btn-xs"   disabled><i class="fa fa-edit"></i></a>';
            }
            

            $setInvurl="invoicPart.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/cancelorder.php?st=$st&ret=inv_soitemList&mod=3&id=".$row['id']."so=".$socode;
             
			
			//booked order can be deleted by its creator and admin. .
            //if(($st == 9 && $order_creator_id == $_SESSION['user']) || $_SESSION['user'] == 1){
			if(( $st <= 4||$st==9||$st==11) && (($order_creator_id == $_SESSION['user']) || $_SESSION['currPriv'] > 4)){
            	//$urlasdel='<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
				 $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Cancel"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            }
            else
            {
            	$urlasdel='<a class="btn btn-info btn-xs"  disabled><i class="fa fa-remove"></i></a>';
            }
  	
			$invViewLink = '<a data-socode="'.$row['socode'].'" href="order_view.php" class="show-invoice btn btn-info btn-xs" title="View Order"><i class="fa fa-eye"></i></a>';			
  
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
			    	"id"=>$row['id'],
			    	
					"makedt"=>$row['makedt'],
					
					"srctype"=>$row['srctype'],

            		"hrName"=>$row['customer'],

            		"organization"=>$row['organization'],
					
					"orgcode"=>$row['orgcode'],

        			"socode"=>$row['socode'],
				
					"orderstatus"=> '<kbd class="orstatus_'.$row['orderstatus'].'">'.$row['orderstatusname'].'</kbd>',

            		"orderdate"=>$row['orderdate_formated'],

    				"shnm"=>$row['shnm'],

            		"otc"=>number_format($row['invoiceamount'],2), //order amount

            		"mrc"=>$row['mrc'],

            		"poc"=>$row['poc'],

            		"action_buttons"=> $invViewLink." | ".$urlas." | ".$urlasdel,

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
			 "request" => $columnSortOrder,

        );     

        $cmbstatus_str = "";

        //echo $data;die;

        echo json_encode($response);



?>