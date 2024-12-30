<?php

require "../common/conn.php";
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



if($action=="item"){

        $brnd = $_GET["brnd"]; if($brnd == '') $brnd = 0;
        $cat = $_GET["icat"]; if($cat == '') $cat = 0;
        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (i.`code` like '%".$searchValue."%' or i.`barcode` like '%".$searchValue."%' or 

                 i.`name`  like '%".$searchValue."%' or i.colortext like '%".$searchValue."%' or i.`size` like '%".$searchValue."%' or

                p.`name` like '%".$searchValue."%' or ic.`name` like '%".$searchValue."%' or i.`description` like '%".$searchValue."%' or b.title like '%".$searchValue."%' ) ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery0="SELECT i.make_dt, i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, b.title brand
        ,i.`dimension`,i.`wight`, i.`image`, i.`description`,i.rate, i.cost, i.vat, i.ait,i.parts
        ,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,i.note,i.forstock,i.backorderqty,i.finishedst
FROM `item` i LEFT JOIN `color` c ON i.`color`=c.`id` LEFT JOIN `pattern` p ON i.`pattern`= p.`id` LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` LEFT JOIN brand b on i.brand=b.id
 ";

        $strwithoutsearchquery="SELECT i.make_dt, i.`id`, i.`code`, i.`name` itnm,i.colortext color,c.code clorcd, i.`size` ct, p.`name` lt, ic.`name` ItemCat, b.title brand,i.`dimension`,i.`wight`, i.`image`, i.`description`,
                            i.rate, i.cost, i.vat, i.ait,i.parts, i.barcode,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,i.note,i.forstock,i.backorderqty,i.finishedst
,i.approvedst
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

           //$setdelurl="common/delobj.php?obj=item&ret=rawitemList&mod=12&id=".$row['id'];
		   $setdelurl="rawitemList.php?action=delete&mod=12&id=".$row['id'];
			
           $seturlbarcode="barcode/generate_barcode.php?id=".$row['id']."&chid=0";
			
			
			
			//booked order can be editted by its creator and admin. .
            if( $_SESSION['currPriv'] > 3){
				
            	$urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
            }
            else
            {
            $urlas='<a class="btn btn-info btn-xs"   disabled><i class="fa fa-edit"></i></a>';
            }
			
			//booked order can be deleted by its creator and admin. .
            //if( $_SESSION['user'] == 1){
			if( $_SESSION['currPriv'] > 4){

				 $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            }
            else
            {
            	$urlasdel='<a class="btn btn-info btn-xs"  disabled><i class="fa fa-remove"></i></a>';
            }			
			
			
			$urlbarcode='<a class="btn btn-info btn-xs" title="Barcode" target="_blank" href="'. $seturlbarcode.'" ><i class="fa fa-barcode"></i></a>';
			

			

            $photo=$rootpath."/assets/images/products/300_300/".$row["image"];

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (strlen($row["image"])>0) {

        		$photo="assets/images/products/300_300/".$row["image"];

        		}else{

        			$photo="assets/images/products/placeholder.png";

        		}
        		
        		$btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			); 
            $price=$row['rate']+$row['rate']*$row['vat']*.01;
            $lenth=$row['length'].$row['lengthunit'];
            $width=$row['width'].$row['widthunit'];
            $height=$row['height'].$row['heightunit'];
            $isfinshed=$row['finishedst']; if ($isfinshed==1){$finished="Yes";}else {$finished="Not Yet";}
            $isapproved=$row['approvedst'];if ($isapproved==1){$approved="Yes";}else {$approved="Not Yet";}
            $colorcd=$row['clorcd'];
            $color='<span style="color:'.$colorcd.'">..</span>';
            $data[] = array(
				
					"pid"=>$row['id'],
				
					"make_dt"=>$row['make_dt'],
				
                    "photo"=>'<img src='.$photo.' width="50" height="50">',
                    
                    "color"=>$row['color'],
                   	"code"=>$row['code'],//$strwithoutsearchquery1,//

            		"itnm"=>$row['itnm'],
        			"lt"=>$row['lt'],
            		"ItemCat"=>$row['ItemCat'],
            		"brand"=>$row['brand'],
            		"barcode"=>$row['barcode'],
            		"lenth"=>$lenth,
            		"width"=>$width,
            		"height"=>$height,
            		"parts"=>$row['parts'],
                    "finished"=>$finished,
                    "approved"=>$approved,
        			"rate"=>number_format($price,2),

            	  //"action_buttons"=>$urlas." | ".$urlbarcode."  | ".$urlasdel,
            	  "action_buttons"=> getGridBtns($btns)."| ".$urlbarcode,

            	);

        } 

    }
else if($action=="itemAprv"){

        $brnd = $_GET["brnd"]; if($brnd == '') $brnd = 0;
        $cat = $_GET["icat"]; if($cat == '') $cat = 0;
        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (i.`code` like '%".$searchValue."%' or 

                 i.`name`  like '%".$searchValue."%' or i.`colortext` like '%".$searchValue."%' or i.`size` like '%".$searchValue."%' or

                p.`name` like '%".$searchValue."%' or ic.`name` like '%".$searchValue."%' or i.`description` like '%".$searchValue."%' or b.title like '%".$searchValue."%' ) ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery0="SELECT i.make_dt, i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, b.title brand
        ,i.`dimension`,i.`wight`, i.`image`, i.`description`,i.rate, i.cost, i.vat, i.ait,i.parts,i.cal_ldp
        ,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,i.note,i.forstock,i.backorderqty,i.finishedst
FROM `item` i LEFT JOIN `color` c ON i.`color`=c.`id` LEFT JOIN `pattern` p ON i.`pattern`= p.`id` LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` LEFT JOIN brand b on i.brand=b.id
 ";

        $strwithoutsearchquery="SELECT i.make_dt, i.`id`, i.`code`, i.`name` itnm,i.colortext color,c.code clorcd, i.`size` ct, p.`name` lt, ic.`name` ItemCat, b.title brand,i.`dimension`,i.`wight`, i.`image`, i.`description`,
                            i.rate, i.cost, i.vat, i.ait,i.parts, i.barcode,i.length,i.lengthunit,i.width,i.widthunit,i.height,i.heightunit,i.note,i.forstock,i.backorderqty,i.finishedst
,i.approvedst, i.cal_ldp
                            FROM `item` i LEFT JOIN `color` c ON i.`color`=c.`id` LEFT JOIN `pattern` p ON i.`pattern`= p.`id` LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` LEFT JOIN brand b on i.brand=b.id
                            where i.approvedst=0 and (ic.`id`=$cat or $cat='0') and (i.brand =$brnd or $brnd=0) ";

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

           $seturl="rawitemAprv.php?res=4&msg='Update Data'&id=".$row['id']."&mod=24";

           //$setdelurl="common/delobj.php?obj=item&ret=rawitemList&mod=12&id=".$row['id'];
		   $setdelurl="rawitemAprvList.php?action=delete&mod=24&id=".$row['id'];
			
           $seturlbarcode="barcode/generate_barcode.php?id=".$row['id']."&chid=0";
			
			
			
			//booked order can be editted by its creator and admin. .
            if( $_SESSION['currPriv'] > 3){
				
            	$urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
            }
            else
            {
            $urlas='<a class="btn btn-info btn-xs"   disabled><i class="fa fa-edit"></i></a>';
            }
			
			//booked order can be deleted by its creator and admin. .
            //if( $_SESSION['user'] == 1){
			if( $_SESSION['currPriv'] > 4){

				 $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            }
            else
            {
            	$urlasdel='<a class="btn btn-info btn-xs"  disabled><i class="fa fa-remove"></i></a>';
            }			
			
			
			$urlbarcode='<a class="btn btn-info btn-xs" title="Barcode" target="_blank" href="'. $seturlbarcode.'" ><i class="fa fa-barcode"></i></a>';
			

			

            $photo=$rootpath."/assets/images/products/300_300/".$row["image"];

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (strlen($row["image"])>0) {

        		$photo="assets/images/products/300_300/".$row["image"];

        		}else{

        			$photo="assets/images/products/placeholder.png";

        		}
        		
        		$btns = array(
				array('edit',$seturl,'class="btn btn-info btn-xs"  title="Edit"	  '),
				array('delete',$setdelurl,'class="btn btn-info btn-xs griddelbtn" title="Delete" '),
			);
            $price=$row['rate']+$row['rate']*$row['vat']*.01;
            $lenth=$row['length'].$row['lengthunit'];
            $width=$row['width'].$row['widthunit'];
            $height=$row['height'].$row['heightunit'];
            $isfinshed=$row['finishedst']; if ($isfinshed==1){$finished="Yes";}else {$finished="Not Yet";}
            $isapproved=$row['approvedst'];if ($isapproved==1){$approved="Yes";}else {$approved="Not Yet";}
            $colorcd=$row['clorcd'];
            $color='<span style="color:'.$colorcd.'">..</span>';
            $data[] = array(
				
					"pid"=>$row['id'],
				
					"make_dt"=>$row['make_dt'],
				
                    "photo"=>'<img src='.$photo.' width="50" height="50">',
                    
                    "color"=>$row['color'],
                   	"code"=>$row['code'],//$strwithoutsearchquery1,//
                    "cal_ldp"=>$row['cal_ldp'],
            		"itnm"=>$row['itnm'],
        			"lt"=>$row['lt'],
            		"ItemCat"=>$row['ItemCat'],
            		"brand"=>$row['brand'],
            		"barcode"=>$row['barcode'],
            		"lenth"=>$lenth,
            		"width"=>$width,
            		"height"=>$height,
            		"parts"=>$row['parts'],
                    "finished"=>$finished,
                    "approved"=>$approved,
        			"rate"=>number_format($price,2),

            	  //"action_buttons"=>$urlas." | ".$urlbarcode."  | ".$urlasdel,
            	  "action_buttons"=> getGridBtns($btns)."| ".$urlbarcode,

            	);

        } 

    }    
else{}    
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