<?php



require "../common/conn.php";

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
extract($_REQUEST);
$branch_str = ($branch && ($branch !='undefined'))?"and r.id=".$branch:"";

    if($action=="stock-transfer")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
			$searchValue = trim($searchValue);
        	$searchQuery = " and (
			s.barcode like  '%".$searchValue."%' or 
			p.name like '%".$searchValue."%' or 
			p.code like '%".$searchValue."%' or 
			r.name like '%".$searchValue."%' or  
			t.name  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,t.name tn, p.image,p.code, p.name pn,p.id pid,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode, s.storerome storerome 
                                FROM chalanstock s 
								LEFT JOIN item p ON s.product = p.id 
								LEFT JOIN itmCat t ON p.catagory=t.id 
								LEFT JOIN branch r ON s.storerome=r.id  
                                WHERE 1=1  ".$branch_str." and s.freeqty<>0 and p.id<>'' and s.storerome <>''";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        //Total Amount
        $qrytotal = "SELECT SUM(s.costprice * s.freeqty) as totalcost, SUM(s.freeqty * p.rate) as totalmrp 
                    FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                 WHERE (s.barcode='".$bc1."' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )  and s.freeqty<>0";
        //echo $qrytotal;die;
        $total_re = mysqli_query($con, $qrytotal);
        while ($row1 = mysqli_fetch_assoc($total_re)){
            $total_cp = $row1["totalcost"];
            $total_mrp = $row1["totalmrp"];
        }
        
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
			
			$pid = $row2["pid"];
           	$stkid=$row2["stkid"];
            $tnm=$row2["tn"]; 
			$prod=$row2["pn"];
			$str=$row2["str"]; 
			$storerome = $row2["storerome"]; 
            $freeqty=$row2["freeqty"]; 
			$cup=$row2["costprice"]; 
			$mup=$row2["mrp"]; 
			$bc=$row2["barcode"];
            $cp=$freeqty*$cup;
			$mp=$freeqty*$mup; 
            $tcp=$tcp+$cp;
			$tmp=$tmp+$mp;
           	
			
			$photo		= (strlen($row2['image'])>0)?"assets/images/products/300_300/".$row2["image"]:"assets/images/products/placeholder.png";
			
			$seturl="stock_transfer_form.php?pid=".$pid."&code=".$bc."&curstore=".$storerome."&curqty=".$freeqty;
			
            $data[] = array(
            		"tn"=> $tnm,
            		"pn"=> $prod,
					"code"=>  $row2["code"],
					"image"=> '<img src="'.$photo.'" width="50">',
            		"barcode"=> $bc,
            		"str"=> $str,
            		"freeqty"=> number_format($freeqty,0),
					"transfer"=>'<a class="btn-sck-trnfr btn btn-info btn-xs"  href="'.$seturl.'">Transfer Stock</a>'
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($total_cp,2));
        array_push($total, number_format($total_mrp,2));
        //print_r($total);die;
    }



        //$data[] = array('dt'=>$empQuery);

        $seturl="stock_transfer_form.php?".$row["id"];
		//$setviewurl = "money_receipt.php?rpid=".$row["id"];

        ## Response

        //$total = 98;

        $response = array(

            "draw" => intval($draw),

            "iTotalRecords" => $totalRecords,

            "iTotalDisplayRecords" => $totalRecordwithFilter,

            "aaData" => $data,

            "total"    => $total,
			
			 "query"    => $empQuery

        );

        

        //print_r($data);die;

        

        echo json_encode($response);



?>