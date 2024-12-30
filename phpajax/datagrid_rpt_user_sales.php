<?php

require "../common/conn.php";
include_once('../rak_framework/fetch.php');
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


if($action=="rpt_user_sales"){
    
    
        $emp = $_GET["emp"];
        
        
        
        
      $fdt= $_GET['dt_f'];

      $tdt= $_GET['dt_t'];
      
      if($fdt != ''){
            //$date_qry = " and i.`invoicedt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') "; 
            $date_qry = " and i.`invoicedt` between '$fdt' and '$tdt' "; 
        }else{
            $date_qry = "";
        }
        
        

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.invoiceno like '%".$searchValue."%' or o.orgcode  like '%".$searchValue."%' or o.name  like '%".$searchValue."%' or h.hrName  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="select i.invoiceno, DATE_FORMAT(i.`invoicedt`,'%d/%b/%Y') invoicedt,o.orgcode,o.name customer,i.amount_bdt ,i.paidamount,i.dueamount,h.hrName slperson
                              from invoice i join quotation q on i.soid=q.socode AND q.orderstatus IN(7,8) left join organization o on  i.organization=o.id left join hr h on  i.makeby=h.id 
                                where (i.makeby=$emp or $emp = 0)  $date_qry
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
        
        
        // Prepare the response array
        $response = array(
            "draw" => intval($_REQUEST['draw']),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data,
            "query" => $empQuery,
        );
    
        // Return the response in JSON format
        echo json_encode($response);        

    }
    
?>