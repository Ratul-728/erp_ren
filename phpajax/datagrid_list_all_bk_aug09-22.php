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


## Search 

 if($action=="target")

    {

        $searchQuery = " ";

        if($searchValue != ''){

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

    else if($action=="hc")

    {

        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (`employeecode` like '%".$searchValue."%' or 

                 concat(`firstname`, `lastname`)  like '%".$searchValue."%' or dob like '%".$searchValue."%' or office_contact like '%".$searchValue."%' or

                office_email like '%".$searchValue."%' or nid like '%".$searchValue."%') ";

        }

        

        ## Total number of records without filtering   #c.`id`,

        

        $strwithoutsearchquery="select `id`, `employeecode`, concat(`firstname`,' ',`lastname`) `name`, `dob`,`nid`,`office_contact`,`office_email`,`bloodgroup`, `photo` FROM `employee` where 1=1 ";

        

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

           $seturl="employee_hr.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=employee&ret=hcList&mod=4&id=".$row['id'];

           

            $photo=$rootpath."/common/upload/hc/".$row["photo"]."";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (file_exists($photo)) {

        		$photo="common/upload/hc/".$row["photo"]."";

        		}else{

        			$photo="images/blankuserimage.png";

        		}

            $data[] = array(

                    "id"=>$row['id'],

                    "photo"=>'<img src='.$photo.' width="50" height="50">',

                   	"employeecode"=>$row['employeecode'],

            		"name"=>$row['name'],

            		"dob"=>$row['dob'],

            		"office_contact"=>$row['office_contact'],

        			"office_email"=>$row['office_email'],

            		"nid"=>$row['nid'],

        			"bloodgroup"=>$row['bloodgroup'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

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
        	$searchQuery = " and ( b.`title` like '%".$searchValue."%' or b.`origin` like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT b.`id`, b.`title`, b.`origin`, b.`image`  FROM `brand` b WHERE 1=1  ";
        
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
           $setdelurl="common/delobj.php?obj=brand&ret=brandList&mod=12&id=".$row['id'];
            $photo="../assets/images/brand_logos/".$row["image"];
            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
            if ($row["image"]) {
        			$photo="./assets/images/brand_logos/".$row["image"];
					$delimglk="&img=/assets/images/brand_logos/".$row["image"];
        		}else{
        			$photo="images/blankbrandimage.png";
        		}
        		$sl=$sl+1;
            $data[] = array(
                    "sl"=>$sl,
                    "photo"=>'<img src='.$photo.' width="50" >',
            		"title"=>$row['title'],
            		"origin"=>$row['origin'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.$delimglk.'" >Delete</a>'
            	);
			$delimglk="";
        } 
    }
    
    else if($action=="acc_collection")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%'  or cl.`invoice` like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,cl.`invoice`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2) amount, cu.shnm, gl.glnm FROM collection cl left join organization c on cl.`customerOrg`=c.id 

left join transmode tr on cl.transmode=tr.id left JOIN `currency` cu ON cu.id = cl.currencycode left join coa gl on cl.glac = gl.glno

 where 1=1 ";

        

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

            $seturl="acc_collection.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=collection&ret=acc_collectionList&mod=7&id=".$row['id'];

            

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount']." ".$row["shnm"],

        			"inv"=>$row['invoice'],
        			
        			"glac"=>$row['glnm'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);

        } 

    }
    
    else if($action=="inv_stock")
    {
        $searchQuery = " ";
        
        if($searchValue != '')
        {
        	$searchQuery = " and (p.code like '%".$searchValue."%' or p.name like '%".$searchValue."%' or t.name  like '%".$searchValue."%' or s.freeqty  like '%".$searchValue."%' 
        	or s.bookqty  like '%".$searchValue."%'  or s.costprice  like '%".$searchValue."%'  or p.rate  like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,p.id pid,p.code,p.image, s.product,p.name prod,t.name typ, s.freeqty, s.bookqty, s.costprice,p.rate 
        FROM stock s left join item p on s.product=p.id
        left join itmCat t on p.catagory=t.id
          where  1=1 ";
        
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
               $photo="../common/upload/item/".$row["code"].".jpg";
               if (file_exists($photo)) {

        		$photo="common/upload/item/".$row["image"].".jpg";

        		}else{

        			$photo="common/upload/item/placeholder.jpg";

        		}
              $sl=$sl+1;  
            $data[] = array(
                    "id"=>$sl,
                    "image"=>'<img src='.$photo.' width="50">',
                    "productcode"=>$row['code'],
            		"prod"=>$row['prod'],
            		"typ"=>$row['typ'],
            		"freeqty"=>number_format($row['freeqty'],0),
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
               $photo="../common/upload/item/".$row["code"].".jpg";
               if (file_exists($photo)) {

        		$photo="common/upload/item/".$row["image"].".jpg";

        		}else{

        			$photo="common/upload/item/placeholder.jpg";

        		}
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
                  DATE_FORMAT( p.`orderdt`,'%e/%c/%Y') like '%".$searchValue."%' or format(p.`tot_amount`,2)  like '%".$searchValue."%' or
               format(p.`invoice_amount`,2)  like '%".$searchValue."%' or DATE_FORMAT( p.`delivery_dt`,'%e/%c/%Y') like '%".$searchValue."%'  ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT distinct p.`id`,p.`poid`,p.`adviceno`, DATE_FORMAT( p.`orderdt`,'%e/%c/%Y') `orderdt`, p.`tot_amount`, p.`invoice_amount`
        ,DATE_FORMAT( p.`delivery_dt`,'%e/%c/%Y') `delivery_dt`
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
         if($columnName == 'id')
        {
            $columnName=" p.id ";
        }
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;die;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $seturl="chalan_view.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
            $setdelurl="common/delobj.php?obj=po&ret=challanList&mod=12&id=".$row['id']."";
            $setedturl="chalanedit.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12";
            $setreturl="chalanreturn.php?res=4&msg='Update Data'&id=".$row['id']."&mod=12&po=".$row['poid'];
             $seturlbarcode="barcode/generate_barcode.php?id=".$row['id']."&chid=".$row['poid'];
            
            $ed='<a class="btn btn-info btn-xs"  href="'. $setedturl.'"  >Edit</a>';
            $rt='<a class="btn btn-info btn-xs"  href="'. $setreturl.'"  >Return</a>';
            $dl= '<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
            $vw='<a class="btn btn-info btn-xs"  href="'. $seturl.'"  >View</a>';
            
            
            $sl=$sl+1;
            $data[] = array(
                    "id"=>$sl,
                    "adviceno"=>$row['adviceno'],
                    "poid"=>$row['poid'],
            		"orderdt"=>$row['orderdt'],
            		"tot_amount"=>number_format($row['tot_amount'],2),
        			"invoice_amount"=>number_format($row['invoice_amount'],2),
            		"delivery_dt"=>$row['delivery_dt'],
            		"edit"=>$vw,
            		"cedit"=>$ed,
            		"cret"=>$rt,
            		"bc"=>'<a class="btn btn-info btn-xs"  href="'. $seturlbarcode.'" target="_blank">BarCode</a>'
            	);
        } 
    }
    else if($action=="challaned")
    {
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (p.`poid` like '%".$searchValue."%' or p.`adviceno` like '%".$searchValue."%' or
                  DATE_FORMAT( p.`orderdt`,'%e/%c/%Y') like '%".$searchValue."%' or format(p.`tot_amount`,2)  like '%".$searchValue."%' or
               format(p.`invoice_amount`,2)  like '%".$searchValue."%' or DATE_FORMAT( p.`delivery_dt`,'%e/%c/%Y') like '%".$searchValue."%' ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT  p.`id`,p.`poid`,p.`adviceno`, DATE_FORMAT( p.`orderdt`,'%e/%c/%Y') `orderdt`, p.`tot_amount`, p.`invoice_amount`
        ,DATE_FORMAT( p.`delivery_dt`,'%e/%c/%Y') `delivery_dt` FROM `po` p  where 1=1 ";
        
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
                 DATE_FORMAT( p.`returndt`,'%e/%c/%Y') like '%".$searchValue."%' or format(totalamount,2)  like '%".$searchValue."%' ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT  p.`id`,p.`chalanno`, DATE_FORMAT( p.`returndt`,'%e/%c/%Y') `returndt`, format(p.totalamount,2) tot_amount FROM `returnpo` p  where 1=1 ";
        
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
            
           
            $data[] = array(
                    "id"=>$sl,
            		"cd"=>$row['cd'],
            		"name"=>$row['name'],
            		"address"=>$row['address'],
            		"contact"=>$row['contact_no'],
            		"email"=>$row['email'],
            		"web"=>$row['web'],
            		"edit"=>$ed,
            		"del"=>$dl
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
                  DATE_FORMAT(so.`effectivedate`,'%e/%c/%Y') like '%".$searchValue."%' or format(s.invoiceamount)  like '%".$searchValue."%' ) ";
               
               //$orderby=" order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        }
        //else
        //{
        // $orderby="order by  p.id desc";
        //}
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery1="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
        , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount`
FROM `soitem` s left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization 
left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode`
left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join orderstatus st on s.orderstatus=st.id 
WHERE  1=1 ";
        
         $strwithoutsearchquery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
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
   
    else if($action=="cusorderdelv")
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
         if($columnName == 'oid')
        {
            $columnName=" o.id ";
            $columnSortOrder=" desc";
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
    where 1=1 ".$pqry." and o.orderstatus=3";
        
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
                $seturl="cusorderdelvassign.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
                
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$empQuery,//
                    "name"=>$row['name'],
            		//"addrs"=>$row['addrs'],
            		//"email"=>$row['email'],
            		"phone"=>$row['phone'],
            		"order_date"=>$row['order_date'],
            		"status"=>$row['ost'],
                "amount"=>number_format($row['amount'],2),                    
                "paymd"=>$row['payment_mood'],
            	"payst"=>$row['deladr'],
            	"agent"=>$row['agentname'],
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Proces Delivery </a>'                                    
            	);
        } 
    }
     else if($action=="cusorderdelvstmt")
    {
            //generation status combo
	//$statusStr = 'SELECT * FROM orderstatus where id in(1,2,6)';
	//echo $statusStr;
	
	    if($dagent!=''){$pqry=" and o.`deliveryby` =".$dagent;}else{$pqry='';}
	  //end generation status combo		
	
        
        $searchQuery = " ";
        
        if($columnName == 'order_id')
        {
            $columnName=" s.id ";
        }
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
    where 1=1 ".$pqry." and o.orderstatus=4";
        
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
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
        , DATE_FORMAT(o.`order_date`,'%e/%c/%Y') `order_date`,o.`amount`,o.status payst,o.payment_mood 
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
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
    where 1=1 and o.orderdate BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') ".$pqry.$osqry;
          /*
           $strwithoutsearchquery="SELECT o.`id`,o.`order_id`,o.`customer_id`,o.name,concat(o.`address`,',',o.`district`,',',o.`area`) addrs,o.`email`,o.`phone`,st.name stnm,o.`orderstatus` st
        , DATE_FORMAT(o.`order_date`,'%e/%c/%Y') `order_date`,o.`amount`,o.status payst,o.payment_mood 
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
        
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`socode` like '%".$searchValue."%' or da.name like '%".$searchValue."%' or org.`name` like '%".$searchValue."%' or concat(c.street,',',a1.name,',',d1.name,',',c.zip)  like '%".$searchValue."%'
        	or org.email  like '%".$searchValue."%'  or org.contactno  like '%".$searchValue."%'  or DATE_FORMAT(o.`orderdate`,'%e/%c/%Y')  like '%".$searchValue."%'  or o.invoiceamount  like '%".$searchValue."%' or s.name  like '%".$searchValue."%'  ";
        }
        
        $strwithoutsearchquery="SELECT  da.name as agentname,o.id oid,o.socode order_id,'Cash' payment_mood,org.`name` cusnm,DATE_FORMAT(o.orderdate,'%e/%c/%Y %T') order_date,org.contactno phone,o.orderstatus,s.name ost,concat(org.street,',',a.name,',', d.name,',',org.zip) deladr
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
    where 1=1 ".$pqry." and o.orderstatus=4";
        
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
                $setdelverurl="deliverd.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
                $setreturnurl="return.php?res=4&msg='Update Data'&id=".$row['oid']."&mod=13";
               // $setdelurl="common/delobj.php?obj=product&ret=productList&mod=1&id=".$row['id'];
                //$photo="../assets/images/product/70_75/".$row['image'];
               // $alrt="onClick=\'javascript:return confirm('are you sure you want to delete this?');\'"
                //$alrt="=onclick='javascript::return confirm(are you sure you want to delete this)'";
                
            $data[] = array(
                    "order_id"=> $row['order_id'],//$empQuery,//
                    "name"=>$row['name'],
            		//"addrs"=>$row['addrs'],
            		//"email"=>$row['email'],
            		"phone"=>$row['phone'],
            		"order_date"=>$row['order_date'],
            		"status"=>$row['ost'],
                "amount"=>number_format($row['amount'],2),                    
                "paymd"=>$row['payment_mood'],
            	//"payst"=>$row['payst'],
            	"agent"=>$row['agentname'],
                "edit"=>'<a class="btn btn-info btn-xs"  href="'. $setdelverurl.'">Deliverd</a>',
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

        $strwithoutsearchquery="SELECT `id`, `vouchno`, `transdt`, `refno`, `remarks` FROM `glmst` WHERE `status` = 'A' ";

        

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
        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="glmaster.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=glmst&ret=glmasterList&mod=7&id=".$row['id'];

            

            $data[] = array(

                    "id"=>$sl,

            		"vouchno"=>$row['vouchno'],

            		"transdt"=>$row['transdt'],

            		"refno"=>$row['refno'],

        			"remarks"=>$row['remarks'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

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

            

            $data[] = array(

                    "id"=>$sl,

            		"buisness"=>$row['business'],

            		"mappedgl"=>$row['glnm'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);
            	
            $sl++;

        } 

    }

    else if($action=="rpt_inv_pay")

    {

      $fd= $_GET['fdt'];

      if($fd!=''){ $fdquery=" and p.transdt >='".$fd."' ";}

      $td= $_GET['tdt'];

      if($td!=''){ $tdquery=" and p.transdt <='".$td."' ";}

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.invoiceno like '%".$searchValue."%' or 

                 date_format(i.invoicedt,'%d/%m/%y')  like '%".$searchValue."%' or i.invyr like '%".$searchValue."%' or i.invoicemonth  like '%".$searchValue."%' or

                i.soid like '%".$searchValue."%'  or o.name like '%".$searchValue."%' or date_format(p.transdt,'%d/%m/%y') like '%".$searchValue."%' or (case when p.transmode ='W' then 'Wallet' else 'Cash' end) like '%".$searchValue."%' or p.amount like '%".$searchValue."%' or p.remarks like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select  p.id,i.invoiceno,date_format(i.invoicedt,'%d/%m/%y') invoicedt,i.invyr,i.invoicemonth,i.invoiceamt,i.soid,o.name, date_format(p.transdt,'%d/%m/%y') transdt

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

      $fd= $_GET['fdt'];

      if($fd!=''){ $fdquery=" and e.trdt >=STR_TO_DATE('".$fd."','%d/%m/%Y')";}

      $td= $_GET['tdt'];

      if($td!=''){ $tdquery=" and e.trdt <=STR_TO_DATE('".$td."','%d/%m/%Y')";}

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (date_format(e.trdt,'%d/%m/%y')  like '%".$searchValue."%' or t.name like '%".$searchValue."%' or p.name  like '%".$searchValue."%' or

                e.amount like '%".$searchValue."%'  or e.narationlike '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        //$strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%m/%y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ";

        $strwithoutsearchquery="SELECT  e.id,date_format(e.trdt,'%d/%m/%y') trdt,t.name transmode,p.name transtype,e.amount,e.naration FROM expense e left join transmode t on e.transmode=t.id left join transtype p on e.transtype=p.id where 1=1 ".$fdquery.$tdquery;

        

        

        

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
    
    else if($action=="rpt_acc_daily_trans")

    {

      $fdt= $_GET['fdt'];

      $tdt= $_GET['tdt'];

      $fglno = $_GET["fglno"];

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`vouchno` like '%".$searchValue."%' or 

                 concat(c.`glnm`, '(', c.`glno`, ')')  like '%".$searchValue."%' or a.remarks like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

		
		
                $strwithoutsearchquery="SELECT a.`entrydate`, a.`remarks`, a.`vouchno`, concat(c.`glnm`, '(', c.`glno`, ')') glnm, a.`dr_cr`, a.`amount`  
                                        FROM `gldlt` a LEFT JOIN coa c ON a.`glac` = c.glno where a.`entrydate` BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and STR_TO_DATE('".$tdt."','%d/%m/%Y')
                                        and (a.glac = '".$fglno."' or '".$fglno."' = '0') ";

        

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
			$dtstr = "where date( `invoicedt`) between STR_TO_DATE('".$fdt."','%d/%m/%Y') and STR_TO_DATE('".$tdt."','%d/%m/%Y')";
		}else{
			$dtstr = "";
		}
				
		
		
        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select a.invoicedt,a.soid,c.name product,b.qty quantity,b.otc rate,(b.qty*b.otc) revenue,(b.cost*b.qty) cost,b.vat,b.ait,
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
    
    else if($action=="rpt_qty")

    {

      $fdt= $_GET['fdt'];

      $tdt= $_GET['tdt'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`socode` like '%".$searchValue."%' or 

                 a.name  like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select c.socode,date_format(c.`orderdate`,'%d/%m/%y') orderdate,b.productid,a.name,b.qty orderqty,b.otc orderRate,(b.qty*b.otc) sellamount
                                        ,d.freeqty availableQty,d.costprice CostRate,(case WHEN d.freeqty<b.qty then (b.qty-d.freeqty) else 0 end)requiredQty
                                        from  soitem c left join soitemdetails b on b.socode=c.socode left join item a on b.productid=a.id left join stock d on a.id=d.product
                                        where c.orderstatus in(1,4) and  c.orderdate between STR_TO_DATE('".$fdt."','%d/%m/%Y') and STR_TO_DATE('".$tdt."','%d/%m/%Y') ";

        

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

            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "socode"=>$row['socode'],

            		"orderdate"=>$row['orderdate'],

            		"name"=>$row['name'],

                    "orderqty"=>$row['orderqty'],

            		"orderRate"=>number_format($row["orderRate"],2),

        			"sellamount"=>number_format($row["sellamount"],2),
        			
        			"availableQty"=>number_format($row["availableQty"],2),
        			
        			"CostRate"=>number_format($row["CostRate"],2),
        			
        			"requiredQty"=>number_format($row["requiredQty"],2),
        			

            	);
            	

        } 
        
        array_push($total, number_format($totorderqty,2));
        array_push($total, number_format($totorderrate,2));
        array_push($total, number_format($totsellamount,2));
        array_push($total, number_format($totavailqty,2));
        array_push($total, number_format($totcostrate,2));
        array_push($total, number_format($totreqqty,2));

    }
    
    else if($action=="rpt_revenue_detail")

    {

      $fdt= $_GET['fdt'];

      $tdt= $_GET['tdt'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.`soid` like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select a.invoicedt,a.soid,sum(b.qty*b.otc) revenue,sum(b.cost*b.qty) cost,sum(b.vat)vat,sum(b.ait) ait,c.deliveryamt delivarycost,
                                        sum(COALESCE(((b.qty*b.otc)-(b.cost*b.qty)),0)) margin
                                        from invoice a left join soitem c on a.soid=c.socode left join soitemdetails b on b.socode=c.socode
                                        where  date( `invoicedt`) between STR_TO_DATE('".$fdt."','%d/%m/%Y') and STR_TO_DATE('".$tdt."','%d/%m/%Y')
                                        ";

        
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

      
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";;

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,
                                        concat(e.firstname,'',e.lastname) `hrName` , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount` 
                                        FROM `soitem` s left join `contacttype` tp on s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` 
                                        left join `organization` o on o.`orgcode`=c.organization left join `hr` h on o.`salesperson`=h.`id` 
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id` 
                                        left join employee e1 on h1.`emp_id`=e1.`employeecode` left join orderstatus st on s.orderstatus=st.id 
                                        WHERE s.orderstatus=3
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
    
    else if($action=="rpt_booked_order")

    {

      
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,
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
        $organization = $_GET["filterorg"];
        if($organization == '') $organization = 0;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,concat(e.firstname,'',e.lastname) `hrName`
                                        , concat(e1.firstname,'',e1.lastname) `poc`,st.id stid ,st.name stnm,s.invoiceamount `amount`
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join `hr` h on o.`salesperson`=h.`id`  
                                        left join employee e on h.`emp_id`=e.`employeecode` left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE  (o.id=$organization or $organization = 0)
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
        $product = $_GET["filterorg"];
        if($product == '') $product = 0;
        
        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (o.`name` like '%".$searchValue."%'  or s.`socode` like '%".$searchValue."%' ) 
        	                or `poc` like '%".$searchValue."%'";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT s.`id`, s.`socode`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`,st.name stnm,s.invoiceamount `amount`
                                        ,i.name product,d.qty,(d.otc+d.vat) unitprice,d.discounttot
                                        FROM `soitem` s left join `organization` o on s.organization=o.id left join soitemdetails d on s.socode=d.socode left join item i on d.productid=i.id
                                        left join orderstatus st on s.orderstatus=st.id 
                                        WHERE  (d.productid=$product or $product = 0)
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

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (a.VouchNo like '%".$searchValue."%' or 

                 concat(g.`glnm`, '(', d.`glac`, ')')  like '%".$searchValue."%' or a.refno like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select a.VouchNo,a.TransDt
                                        ,a.refno,a.remarks,d.sl,d.glac,g.glnm,(case d. dr_cr when 'D' then d.amount else 0 End) D_amount,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount  
                                    	from glmst a,gldlt d ,coa g
                                    	where a.VouchNo=d.VouchNo  and d.glac=g.glno
                                    	and (a.VouchNo= '".$fvouch."' or '".$fvouch."' = 0 )";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;
        
        //Total Debit/Credit
        $drtot = 0; $crtot = 0;
        $qrydrtot = "SELECT SUM(`amount`) drtot FROM `gldlt` WHERE `dr_cr` = 'D'";
        $resultdrtot = $conn->query($qrydrtot); 
        while($rowdrtot = $resultdrtot->fetch_assoc()) {
            $drtot = $rowdrtot["drtot"];
        }
        
        $qrycrtot = "SELECT SUM(`amount`) crtot FROM `gldlt` WHERE `dr_cr` = 'C'";
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

        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $i++;

            $data[] = array(

                    "id"=>$i,

                    "VouchNo"=>$row['VouchNo'],

            		"TransDt"=>$row['TransDt'],

            		"refno"=>$row['refno'],

                    "remarks"=>$row['remarks'],

            		"sl"=>$row["sl"],

        			"glac"=>$row["glac"],
        			
        			"glnm"=>$row["glnm"],
        			
        			"D_amount"=>number_format($row["D_amount"]),
        			
        			"C_amount"=>number_format($row["C_amount"]),

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
    
    else if($action=="rpt_trial_balance")

    {

      $fdt= $_GET['fdt'];


        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (c.`glno` like '%".$searchValue."%' or c.`glnm` like '%".$searchValue."%'  ) ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="select un.glac,c.glnm,c.dr_cr,sum(un.D_amount) dr,sum(un.C_amount) cr,sum(un.op)op ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p 
                                        from
	                                    (
                                        	select d.glac
                                        	,(case d.dr_cr when 'D' then d.amount else 0 End) D_amount
                                        	,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount
                                        	,0 op
                                        	from glmst a,gldlt d
                                        	where a.VouchNo=d.VouchNo
                                        	and (a.entrydate   between   date_format(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'01/%m/%y')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))	
                                        		and a.status='A'
                                        	Union all	
                                        	select glno,0 D_amount,0 C_amount,COALESCE(closingbal ,0)op
                                        	from coa_mon 
                                        	where isposted='P' and closingbal<>0 
                                        		and mn=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%m')
                                        		and yr=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%Y')
                                        ) un,coa c where un.glac=c.glno
                                        group by un.glac,c.glnm,c.dr_cr";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery="select un.glac,c.glnm,c.dr_cr,sum(un.D_amount) dr,sum(un.C_amount) cr,sum(un.op)op ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p 
                                        from
	                                    (
                                        	select d.glac
                                        	,(case d.dr_cr when 'D' then d.amount else 0 End) D_amount
                                        	,(case d.dr_cr when 'C' then d.amount else 0 End) C_amount
                                        	,0 op
                                        	from glmst a,gldlt d
                                        	where a.VouchNo=d.VouchNo
                                        	and (a.entrydate   between   date_format(STR_TO_DATE('".$fdt."','%d/%m/%Y'),'01/%m/%y')  and STR_TO_DATE('".$fdt."','%d/%m/%Y'))	
                                        		and a.status='A'
                                        	Union all	
                                        	select glno,0 D_amount,0 C_amount,COALESCE(closingbal ,0)op
                                        	from coa_mon 
                                        	where isposted='P' and closingbal<>0 
                                        		and mn=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%m')
                                        		and yr=DATE_FORMAT(STR_TO_DATE('".$fdt."','%d/%m/%Y') - INTERVAL 1 MONTH,'%Y')
                                        ) un,coa c where un.glac=c.glno ".$searchQuery."
                                        group by un.glac,c.glnm,c.dr_cr";

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,
        if($columnName == "id"){
            $columnName = "glac";
        }

         $empQuery=$strwithsearchquery." limit ".$row.",".$rowperpage;

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;
        $drtot = 0;
        $crtot = 0;
        $optot = 0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {
            $drtot += $row["dr"];
            $crtot += $row["cr"];
            $optot += $row["op"];
            
            $dr = $row["dr"]; if($dr == 0) $dr = '';
            $cr = $row["cr"]; if($cr == 0) $cr = '';
            $op = $row["op"]; if($op == 0) $op = '';
            
            $i++;

            $data[] = array(

                    "id"=>$i,

                    "glac"=>$row['glac'],

            		"glnm"=>$row['glnm'],

            		"dr_cr"=>$row['dr_cr'],

                    "dr"=>number_format($dr,2),

            		"cr"=>number_format($cr,2),
            		
            		"op"=>number_format($op,2),

        			"p"=>$row["p"],

            	);

        } 
        
        array_push($total, number_format($drtot,2));
        array_push($total, number_format($crtot,2));
        array_push($total, number_format($optot,2));

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

                 tp.name  like '%".$searchValue."%' or tr.name like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,tp.name trtp, tr.name `transmode`,cl.`transref`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` 
                                        
                                        FROM expense cl, transtype tp, costcenter cc,transmode tr

                                        where cl.transtype=tp.id and cl.costcenter=cc.id and cl.transmode=tr.id and cl.`trdt` BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') $filterorgqry";

        

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

         $empQuery=$strwithoutsearchquery.$searchQuery." order by cl.id desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="expense.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=expense&ret=expenseList&mod=3&id=".$row['id'];

            

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"trtp"=>$row['trtp'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);

        } 

    }
    
    else if($action=="acc_expense")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tp.name  like '%".$searchValue."%' or tr.name like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,tp.name trtp, tr.name `transmode`,cl.`transref`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter`, gl.glnm 
                FROM expense cl LEFT JOIN transtype tp ON cl.transtype=tp.id LEFT JOIN costcenter cc ON cl.costcenter=cc.id LEFT JOIN transmode tr ON cl.transmode=tr.id LEFT JOIN coa gl ON cl.glac = gl.glno

                where 1=1  ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        if($columnName == "trdt"){
            $columnName = "DATE(trdt)";
        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="acc_expense.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=expense&ret=acc_expenseList&mod=7&id=".$row['id'];

            

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"trtp"=>$row['trtp'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],
        			
        			"glac"=>$row['glnm'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

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

    

    else if($action=="org")

    {

        $searchQuery = " ";

        if($searchValue != ''){

        	$searchQuery = " and (o.`name` like '%".$searchValue."%' or  concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or

                 i.`name`  like '%".$searchValue."%' or op.`name` like '%".$searchValue."%' or o.`contactno` like '%".$searchValue."%' or

                o.`email` like '%".$searchValue."%' or o.`website` like '%".$searchValue."%' or o.`details` like '%".$searchValue."%' ) ";

        }

        

        ## Total number of records without filtering   #c.`id`, 

        

        $strwithoutsearchquery="SELECT o.`id`,o.`name`,i.`name` `industry`,o.`employeesize`,op.`name` `operationstatus`,o.`bsnsvalue`,o.`contactno`,o.`email`,o.`website`,o.`details`

,concat(e.firstname,'',e.lastname) accmgr

FROM organization o left join businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`

left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` 

where 1=1";

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        

        ##.`id`,

        if($columnName == 'name'){

            $columnName = 'o.id';

            $columnSortOrder = "DESC";

        }

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) {

           $seturl="organization.php?res=4&msg='Update Data'&id=".$row['id']."&mod=2";

           $setdelurl="common/delobj.php?obj=organization&ret=organizationList&mod=2&id=".$row['id'];

           

           $conthisturl="contactDetail_org.php?id=".$row['id']."&mod=2";

            //$photo=$rootpath."/common/upload/contact/".$row["contactcode"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

           // if (file_exists($photo)) {

        	//	$photo="common/upload/contact/".$row["employeecode"].".jpg";

        	//	}else{

        	//		$photo="images/blankuserimage.png";

        //		}

            $data[] = array(

                   	"name"=>'<a class=""  href="'.$conthisturl.'">'.$row["name"].'</a>',

                   	//"name"=> $columnName,

            		"industry"=>$row['industry'],

            		"operationstatus"=>$row['operationstatus'],

            		"contactno"=>$row['contactno'],

        			"email"=>$row['email'],

            		"website"=>$row['website'],

        			"accmgr"=>$row['accmgr'],

        		 	 "details"=>$row['details'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);

        } 

    }

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

            $photo=$rootpath."/common/upload/item/".$row["image"].".jpg";

            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";

            if (file_exists($photo)) {

        		$photo="common/upload/item/".$row["image"].".jpg";

        		}else{

        			$photo="common/upload/item/placeholder.jpg";

        		}

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

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

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

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` 

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

        

         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $seturl="payment.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=allpayment&ret=paymentList&mod=3&id=".$row['id'];
            
            //$setviewurl = "payment_rec.php?mod=3&rpid=".$row["id"];
			$setviewurl = "money_receipt.php?rpid=".$row["id"];

            

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],
        			
        			"view"=>'<a class="viewnprint btn btn-info btn-xs"  href="'. $setviewurl.'">View</a>',

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

            	);

        } 

    }
    
    else if($action=="acc_payment")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 

                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or

                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter`, gl.glnm

                FROM allpayment cl left join contact c on cl.customer=c.id left join costcenter cc on cl.costcenter=cc.id left join transmode tr on cl.transmode=tr.id LEFT JOIN coa gl ON cl.glac = gl.glno where 1=1 ";

        

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

            $seturl="acc_payment.php?res=4&msg='Update Data'&id=".$row['id']."&mod=7";

            $setdelurl="common/delobj.php?obj=allpayment&ret=acc_paymentList&mod=7&id=".$row['id'];

            

            $data[] = array(

                    "trdt"=>$row['trdt'],

            		"transmode"=>$row['transmode'],

            		"transref"=>$row['transref'],

            		"customer"=>$row['customer'],

        			"naration"=>$row['naration'],

            		"amount"=>$row['amount'],

        			"costcenter"=>$row['costcenter'],
        			
        			"glac"=>$row['glnm'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',

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

        $basequery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`effectivedate`,'%d/%m/%y') `orderdate`,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,format(sum(qtymrc*sd.mrc),2) mrc,concat(e.firstname,'',e.lastname) `hrName`, concat(e1.firstname,'',e1.lastname) `poc`

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
    
    else if($action=="inv_soitem")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (concat(e.firstname,'',e.lastname) like '%".$searchValue."%' or  concat(e1.firstname,'',e1.lastname) like '%".$searchValue."%' or 

                 tp.`name` like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or orst.`name` like '%".$searchValue."%' or o.`name`  like '%".$searchValue."%' or o.`orgcode`  like '%".$searchValue."%' or cr.shnm  like '%".$searchValue."%'

                 or s.`socode` like '%".$searchValue."%' or s.`orderdate` like '%".$searchValue."%' ) "; //or round(sum(sd.qty*sd.otc),2) like '%".$searchValue."%' or round(sum(qtymrc*sd.mrc),2) like '%".$searchValue."%'

        }
        
        $orgid = $_GET["orgid"]; if($orgid == '') $orgid = 0;

        ## Total number of records without filtering   #c.`id`,

        $basequery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, o.orgcode, s.`orderdate`, date_format(s.`orderdate`,'%d/%m/%Y') `orderdate_formated`
        ,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,s.orderstatus, orst.name `orderstatusname`, format(sum(qtymrc*sd.mrc),2) mrc,concat(e.firstname,'',e.lastname) `hrName`, concat(e1.firstname,'',e1.lastname) `poc`

FROM `soitem` s left join `soitemdetails` sd on sd.socode=s.socode left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization  

left join `orderstatus` orst on s.`orderstatus`=orst.`id` 
left join `hr` h on o.`salesperson`=h.`id` 
left join employee e on h.`emp_id`=e.`employeecode` 
left join `hr` h1 on s.`poc`=h1.`id`  
left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join currency cr on sd.currency=cr.id WHERE  1=1 and (s.organization = $orgid or $orgid = 0) ";

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

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

            $st=$row['orderstatus'];
            
            $seturl="inv_soitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            if($st<=1){
            $urlas='<a class="btn btn-info btn-xs" title="Edit"  href="'. $seturl.'"  ><i class="fa fa-edit"></i></a>';
            }
            else
            {
            $urlas='<a class="btn btn-info btn-xs"   disabled><i class="fa fa-edit"></i></a>';
            }
            

            $setInvurl="invoicPart.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";

            $setdelurl="common/delobj.php?obj=soitem&ret=inv_soitemList&mod=3&id=".$row['id'];
             if($st<=1){
            	//$urlasdel='<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>';
				 $urlasdel='<a class="btn btn-info btn-xs griddelbtn" title="Delete"  href="'. $setdelurl.'" ><i class="fa fa-remove"></i></a>';
            }
            else
            {
            	$urlasdel='<a class="btn btn-info btn-xs"  disabled><i class="fa fa-remove"></i></a>';
            }
  
  
            $i++;

            $data[] = array(

                   // "id"=>$i,//$row['hrName'],
					//"query"=>$strwithsearchquery,
				
            		"srctype"=>$row['srctype'],

            		"hrName"=>$row['customer'],

            		"organization"=>$row['organization'],
					
					"orgcode"=>$row['orgcode'],

        			"socode"=>$row['socode'],
				
					"orderstatus"=> '<kbd class="orstatus_'.$row['orderstatus'].'">'.$row['orderstatusname'].'</kbd>',

            		"orderdate"=>$row['orderdate_formated'],

    				"shnm"=>$row['shnm'],

            		"otc"=>$row['otc'],

            		"mrc"=>$row['mrc'],

            		"poc"=>$row['poc'],

            		"action_buttons"=>$urlas." | ".$urlasdel,

            	//	"inv"=>'<a class="btn btn-info btn-xs"  href="'. $setInvurl.'">Create Invoice</a>',

            		//"del"=>$urlasdel,
					
				

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

        $basequery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`orderdate`,'%d/%m/%y') `orderdate`
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

        $base_qry="SELECT  d.`id`,d.`name` dnm,c.`id` lid, c.`name` lnm,o.id orid ,o.`name` leadcompany ,format(d.`value`,2) VALU,s.`name` stage,ds.`name` 'status',ds.`id` dsid,DATE_FORMAT(d.`dealdate`, '%d/%m/%Y') `dealdate`, DATE_FORMAT(d.`nextfollowupdate`, '%d/%m/%Y') `fldt` ,(case d. `status` when '5' then  (select `name` from deallostreason where id=d.lostreason) else '' end ) lost_rsn,format(IFNULL((d.`value`*s.`weight`/100),0),2) forcast,concat(e.firstname,'',e.lastname)  accmger,format(sum(i.qty*i.otc),2) otc,format(sum(i.qtymrc*i.mrc),2) mrc,cr.shnm

FROM deal d  left join dealitem i on d.id=i.socode

		left join contact c on d.`lead`=c.`id`

		left join organization o on d.leadcompany=o.id

        left join dealtype s on d.`stage`=s.`id`

        left join dealstatus ds  on d.`status`=ds.`id`

        left join `hr` h on o.`salesperson`=h.`id`  

        left join employee e on h.`emp_id`=e.`employeecode`

        left join currency cr on i.currency=cr.id

        where 1=1  ";

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

         $empQuery=$base_qry.$searchQuery."  group by d.`id` order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        

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
    
    

    else if($action=="invoice")

    {

        $searchQuery = " ";

        if($searchValue != '')

        {

        	$searchQuery = " and (i.`invoiceno` like '%".$searchValue."%' or  i.`invoicemonth` like '%".$searchValue."%' or  i.`invyr` like '%".$searchValue."%' or 

                  i.`soid` like '%".$searchValue."%' or o.name like '%".$searchValue."%' or i.`invoiceamt`  like '%".$searchValue."%' or 

                  i.`paidamount`  like '%".$searchValue."%'  or i.`dueamount` like '%".$searchValue."%' or i.`duedt` like '%".$searchValue."%'  or 

                  s.`name` like '%".$searchValue."%' or s.`dclass` like '%".$searchValue."%' or p.`name` like '%".$searchValue."%' or p.`dclass` like '%".$searchValue."%') ";

        }

        

        //Filter
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

        

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT  1 sl,i.`invoiceno`, i.`invyr`, i.`invoicemonth`, i.`soid`, o.name `organization`, i.`invoiceamt` invoiceamt,format(i.amount_bdt,2) amount_bdt, 
        format(i.`paidamount`,2)paidamount, i.`dueamount` due, format(i.`dueamount`,2)dueamount, i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`dclass` `paymentSt`,
        o.balance orgbal,o.id orgid FROM `invoice` i  left join invoicestatus s  on i.invoiceSt=s.id left join invoicepaystatus p on i.paymentSt=p.id  
        left join organization o on i.organization=o.id where  i.`invyr` = '".$yeardt."' and i.invoicedt BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')  ";

        //s.`status`<>6

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

       

        ## Total number of records with filtering # c.`id`,

        

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

         

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        

        ##.`id`,

        

         $empQuery=$strwithoutsearchquery.$searchQuery.$filterorgqry.$filterstqry."   order by i.id desc,".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;

        //s.`status`<>6

        //echo $empQuery;die;

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $i=0;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           $payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."&orbal=".$row['orgbal']."&orgid=".$row['orgid']."')";

           $seturl="invoice.php?res=4&msg='Update Data'&id=".$row['invoiceno']."&mod=3";

           $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';

           $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';

           $action='<span><a href="invoice.php?invid='.$row['invoiceno'].'&mod=3" class="invoice-view" title="View" target="_blank"><i class="fa fa-eye"></i></a>

           <a href="invoice_pdf.php?invid='.$row['invoiceno'].'&mod=3" class="invoice-download" title="download" target="_blank" download><i class="fa fa-download"></i></a>

           <a data-invid="'.$row['invoiceno'].'" data-invamount="'.$row['amount_bdt'].'" data-orbal="'.$row['orgbal'].'" data-due="'.$row['due'].'" data-orgid="'.$row['orgid'].'"  href="#" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>

           <a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a>
           <!--a href="mailto:info@example.com?subject=Mail from Our Site&attachments=../images/avatar.jpg" class="invoice-view" title="Email"><i class="fa fa-envelope"></i></a--></span>';

           $i++;

            $data[] = array(

                    "sl"=>$i,

                    "invoiceno"=>$row['invoiceno'],

                    "invyr"=>$row['invyr'],

            		"invoicemonth"=>date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)),

            		"soid"=>$row['soid'],

            		"organization"=>$row['organization'],

        			"invoiceamt"=> number_format($row['invoiceamt'],0,".",","),

        			"amount_bdt"=> $row['amount_bdt'],

            		"paidamount"=>$row['paidamount'],

    				"dueamount"=>$row['dueamount'],

            		"duedt"=>$row['duedt'],

            		"invoiceSt"=>$row['name'],

            		//"invoiceSt"=>$invst,

            		"paymentSt"=>$invpaymentSt,

            		//"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'

            		"edit"=>$action

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

        	$searchQuery = " and (t.`tikcketno` like '%".$searchValue."%' or  date_format(t.`issuedate`,'%d/%m/%y') like '%".$searchValue."%' or  t.`issuedetails` like '%".$searchValue."%' or assigned like '%".$searchValue."%' or 

                  t.`sub` like '%".$searchValue."%' or o.name like '%".$searchValue."%' or t.`severity` like '%".$searchValue."%' or i.name like '%".$searchValue."%' or tp.name like '%".$searchValue."%' or sb.name like '%".$searchValue."%'

                 or  date_format(t.`probabledate`,'%d/%m/%y') like '%".$searchValue."%'  or t.`severity` like '%".$searchValue."%' or h2.hrName like '%".$searchValue."%' 

                 or st.stausnm like '%".$searchValue."%' or h2.hrName like '%".$searchValue."%' or cn.name like '%".$searchValue."%' or concat_ws(' ',emp.`firstname`,emp.`lastname`) like '%".$searchValue."%') ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT t.`id` id,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%m/%y') issuedate

                                ,date_format(t.`probabledate`,'%d/%m/%y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`, h1.hrName createby,
                        
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

        	$searchQuery = " and (t.`tikcketno` like '%".$searchValue."%' or  date_format(t.`issuedate`,'%d/%m/%y') like '%".$searchValue."%' 

        	or t.`sub` like '%".$searchValue."%' or t.`severity` like '%".$searchValue."%' 

                 or st.stausnm like '%".$searchValue."%' ) ";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT t.`id` ,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%m/%y') issuedate

        ,date_format(t.`probabledate`,'%d/%m/%y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`

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

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE `status` = 'A' ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        $empQuery=$strwithoutsearchquery.$searchQuery." and lvl = 1 "; //order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage; $fixrow = $row;

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

            $data[] = array(

                    "id"=>$sl,

            		"glno"=>$row['glno'],

            		"glnm"=>$row['glnm'],

            		"ctlgl"=>$row['ctlgl'],

        			"isposted"=>$isposted,

            		"type"=>$type,
            		
            		"lvl"=>$row['lvl'],
            		
            		"opbal"=>number_format($row['opbal'],2),
            		
            		"closingbal"=>number_format($row['closingbal'],2),

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		
            		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>'

            	);
            
            $sl++;
            
            //Level 2
            $qry1 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl1."' ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
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
    
                $data[] = array(
    
                        "id"=>$sl,
    
                		"glno"=>$row1['glno'],
    
                		"glnm"=>"&nbsp; &nbsp; &nbsp;".$row1['glnm'],
    
                		"ctlgl"=>$row1['ctlgl'],
    
            			"isposted"=>$isposted,
    
                		"type"=>$type,
                		
                		"lvl"=>$row1['lvl'],
                		
                		"opbal"=>number_format($row1['opbal'],2),
                		
                		"closingbal"=>number_format($row1['closingbal'],2),
    
                		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
                		
                		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>'
    
                );
                
                $sl++;
                
                //Level 3
                $qry2 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl2."' ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
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
        
                    $data[] = array(
        
                            "id"=>$sl,
        
                    		"glno"=>$row2['glno'],
        
                    		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;".$row2['glnm'],
        
                    		"ctlgl"=>$row2['ctlgl'],
        
                			"isposted"=>$isposted,
        
                    		"type"=>$type,
                    		
                    		"lvl"=>$row2['lvl'],
                    		
                    		"opbal"=>number_format($row2['opbal'],2),
                    		
                    		"closingbal"=>number_format($row2['closingbal'],2),
        
                    		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
                    		
                    		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>'
        
                    );
                    
                    $sl++;
                    
                    //Level 4
                    $qry3 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl3."'";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
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
            
                        $data[] = array(
            
                                "id"=>$sl,
            
                        		"glno"=>$row3['glno'],
            
                        		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;".$row3['glnm'],
            
                        		"ctlgl"=>$row3['ctlgl'],
            
                    			"isposted"=>$isposted,
            
                        		"type"=>$type,
                        		
                        		"lvl"=>$row3['lvl'],
                        		
                        		"opbal"=>number_format($row3['opbal'],2),
                        		
                        		"closingbal"=>number_format($row3['closingbal'],2),
            
                        		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
                        		
                        		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>'
            
                        );
                        
                        $sl++;
                        
                        //Level 5
                        $qry4 = "SELECT `id`, `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal` FROM `coa` WHERE glno != ctlgl and `status` = 'A' and ctlgl = '".$glLvl4."'";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
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
                
                            $data[] = array(
                
                                    "id"=>$sl,
                
                            		"glno"=>$row4['glno'],
                
                            		"glnm"=>"&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;".$row4['glnm'],
                
                            		"ctlgl"=>$row4['ctlgl'],
                
                        			"isposted"=>$isposted,
                
                            		"type"=>$type,
                            		
                            		"lvl"=>$row4['lvl'],
                            		
                            		"opbal"=>number_format($row4['opbal'],2),
                            		
                            		"closingbal"=>number_format($row4['closingbal'],2),
                
                            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
                            		
                            		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>'
                
                            );
                            
                            $sl++;
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

        	$searchQuery = " and (an.announceid like '%".$searchValue."%' or  c.name like '%".$searchValue."%' or date_format(an.announcedt,'%d/%m/%y') like '%".$searchValue."%' or 

                  an.title like '%".$searchValue."%' or o.name like '%".$searchValue."%' or an.announce like '%".$searchValue."%' or i.name like '%".$searchValue."%' or tp.name like '%".$searchValue."%' or sb.name like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT an.id, an.announceid,c.name catagory,date_format(an.announcedt,'%d/%m/%y') announcedt,an.title,an.announce,o.name organization FROM announce an left join announcecatagory c on an.catagory=c.id left join organization o on an.organization=o.id WHERE 1=1";

        

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
           
           $setdelurl="common/delobj.php?obj=branch&ret=storeList&mod=12&id=".$row4['id'];

          

            $data[] = array(

                    "id"=>$sl,

            		"name"=>$row['name'],

            		"contact_name"=>$row['contact_name'],
            		
            		"contact_number"=>$row['contact_number'],
            		
            		"address"=>$row['address'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		
            		"del"=>'<a class="btn btn-info btn-xs"  href="'. $setdelurl.'">Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['Title'],

            		"description"=>$row['Description'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['Title'],

            		"description"=>$row['Description'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

        $strwithoutsearchquery="SELECT `ID` id, `Title`, `Description` FROM `ActionType` WHERE 1=1";

        

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

           $seturl="actiontype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=ActionType&ret=actiontypeList&mod=4&id=".$row['id'];

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['Title'],

            		"description"=>$row['Description'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

        $strwithoutsearchquery="SELECT a.id id, concat(e.firstname, ' ', e.lastname) empname, act.Title acttype,a.`actiondt`, dept.name deptname, ja.Title janame, desi.name designation, jt.Title jtname, concat(emp2.firstname, ' ', emp2.lastname) reportto 

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

                    "sl"=>$sl,

                    "empname"=>$row['empname'],

            		"acttype"=>$row['acttype'],

            		"actdt"=>$row['actiondt'],

            		"dept"=>$row['deptname'],

            		"jobarea"=>$row['janame'],

            		"jobtype"=>$row['jtname'],

            		"reportto"=>$row['reportto'],

            		"desig"=>$row['designation'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

        $strwithoutsearchquery="SELECT `id`, `title`, `benifitnature`, `benifittype`, `Description` FROM `benifitype` WHERE st = 0";

        

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

            if($row["benifitnature"] == "1"){$benefitnature = "Addition";}else{$benefitnature = "Deduction";}

            if($row["benifittype"] == "1"){

                $benefittype = "Salary";

            }else{

                $benefittype = "Other";

            }

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="benifittype.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=benifitype&ret=benifittypeList&mod=4&id=".$row['id'];

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"benefitnature"=>$benefitnature,

            		"benefittype"=>$benefittype,

            		"details"=>$row['Description'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"basic"=>$row['basic'],

            		"increment"=>$row['increment'],

            		"maxgross"=>$row['maxgross'],

            		"details"=>$row['Description'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"description"=>$row['remarks'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['mtitle'],

                    "pack"=>$row['ptitle'],

            		"compansation"=>$row['compansation'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

            	);

            $sl++;

        } 

    }

    

    else if($action=="hrcompansation")

    {

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (emp.firstname like '%".$searchValue."%' or emp.lastname like '%".$searchValue."%' or c.`title` like '%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.`id`, concat(emp.firstname, ' ', emp.lastname) empname, c.title com

        FROM `hrcompansation` a 

        LEFT JOIN employee emp ON a.`hrid` = emp.id 

        LEFT JOIN compansationSetup c ON a.`compansation` = c.id 

        WHERE a.st = 0";

        

        /* $strwithoutsearchquery="SELECT a.`id`, concat(emp.firstname, ' ', emp.lastname) empname, c.title com

        , b.title btype, a.`privilagedfund`,DATE_FORMAT(a.effectivedate,'%e/%c/%Y') `effectivedate` , a.`conditions`, a.`Description`, a.`increment` 

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

            $data[] = array(

                    "sl"=>$sl,

                    "empname"=>$row['empname'],

            		"compansation"=>$row['com'],

            		/*"btype"=>$row['btype'],

            		"priamount"=>$row['privilagedfund'],

            		"incr"=>$row['increment'],

            		"edate"=>$row["effectivedate"],

            		"condition"=>$row["conditions"],

            		"details"=>$row['Description'],*/

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"description"=>$row['remarks'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

        $strwithoutsearchquery="SELECT a.`id`, b.`title`, a.`date`,a.`details` FROM `Holiday` a LEFT JOIN `holidayType` b on a.holidaytype=b.id WHERE 1=1";

        

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

           $seturl="holiday.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=Holiday&ret=holidayList&mod=4&id=".$row['id'];

            $data[] = array(

                    "sl"=>$sl,

                    "htype"=>$row['title'],
                    
                    "details"=>$row['details'],

            		"action_dt"=>$row['date'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

        $strwithoutsearchquery="SELECT `id` id, `title`,`day`, `remarks` FROM `leaveType` WHERE 1=1";

        

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

                    "day"=>$row['day'],

            		"description"=>$row['remarks'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "shifting"=>$row['title'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "st"=>$row['bt'],

                    "title"=>$row['at'],

                    "weight"=>$row['weight'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "kpivt"=>$row['bt'],

                    "title"=>$row['at'],

                    "weight"=>$row['weight'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "title"=>$row['title'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "shift"=>$row['title'],

                    "starttime"=>$row['start'],

                    "endtime"=>$row['end'],

                    "delaytime"=>$row['delaytime'],

                    "edelaytime"=>$row['extendeddelay'],

                    "latetime"=>$row['latetime'],

                    "abstime"=>$row['absent'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

        	$searchQuery = " and ( b.`firstname` like '%".$searchValue."%' or  b.`lastname`)";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT a.id, a.`date`, a.`intime`, a.`outtime`, concat(e.`firstname`, ' ', e.`lastname`) empname FROM `attendance` a 

                                LEFT JOIN `hr` b ON a.`hrid` = b.`id` LEFT JOIN `employee` e ON b.emp_id = e.employeecode where a.date between '".$sdt."' AND '".$edt."' ";

        

        $sel = mysqli_query($con,$strwithoutsearchquery);

        $totalRecords = $sel->num_rows;

        ## Total number of records with filtering # c.`id`,

        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;

  // s.`status`<>6 

        $sel = mysqli_query($con,$strwithsearchquery);

        $totalRecordwithFilter = $sel->num_rows;

        if($columnName == 'sl') $columnName = "a.id";

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

            $data[] = array(

                    "sl"=>$sl,

                    "name"=>$empname,

                    "intime"=>$intime,

                    "outtime"=>$outtime,

                    "date"=>$row['date'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

            	);

            $sl++;

        } 

    }

    

    else if($action=="rptattendence")

    {

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

        	$searchQuery = " and (DATE_FORMAT(u.dt,'%e/%c/%Y') like '%".$searchValue."%' or u.hrName like '%".$searchValue."%' or u.ofctime like'%".$searchValue."%' 

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

        $strwithoutsearchquery="select u.id id,u.dt ,DATE_FORMAT(u.dt,'%e/%c/%Y') trdt,u.hrName,u.ofctime,u.shift, u.emp_id, u.hrnm

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
                                
                                d.dt BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')
                                
                                and h.emp_id=e.employeecode 
                                
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

        //echo $empQuery;die;

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

        $searchQuery = "";

        if($searchValue != '')

        {

        	$searchQuery = " and (s.salaryyear like '%".$searchValue."%' or MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) like '%".$searchValue."%' or concat(e.firstname,e.lastname) like'%".$searchValue."%' 

        	or s.benft_1 like'%".$searchValue."%'  or s.benft_2 like'%".$searchValue."%'  or s.benft_3 like'%".$searchValue."%'  or s.benft_4 like'%".$searchValue."%'  or s.benft_5 like'%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,e.lastname) emp

,s.benft_1 basic,s.benft_2 house,s.benft_3 medical,s.benft_4 transport,s.benft_5 mobile 

FROM monthlysalary s LEFT JOIN employee e ON s.hrid=e.id

where s.salaryyear=$fdt and s.salarymonth=$tdt";





#(  STR_TO_DATE(concat('01','/',s.salarymonth,'/',s.salaryyear),'%d/%m/%Y') BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')      

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

        $empRecords = mysqli_query($con, $empQuery);

        $data = array();

        $sl = 1;

        while ($row = mysqli_fetch_assoc($empRecords)) 

        {

           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";

           $seturl="hraction.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";

           $setdelurl="common/delobj.php?obj=hraction&ret=hractionList&mod=4&id=".$row['id'];

           $tot=$row['basic']+$row['house']+$row['medical']+$row['transport']+$row['mobile'];

            $data[] = array(

                    "yr"=>$row['salaryyear'],//$empQuery,//

        			"month"=>$row['mnth'],

            		"emp"=>$row['emp'],

            		"basic"=>number_format($row['basic'],2),

            		"house"=>number_format($row['house'],2),

            		"medical"=>number_format($row['medical'],2),

            		"transport"=>number_format($row['transport'],2),

            		"mobile"=>number_format($row['mobile'],2),

            		"tot"=>number_format($tot,2)

            	);

            $sl++;

        } 

    }

   

   else if($action=="rptleave")
    
    {
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

        	$searchQuery = " and (DATE_FORMAT(l.applieddate,'%e/%c/%Y') like '%".$searchValue."%' or h1.hrName like '%".$searchValue."%' or lt.title like'%".$searchValue."%' 

        	or h.hrName like'%".$searchValue."%')";

        }

        ## Total number of records without filtering   #c.`id`,

        $strwithoutsearchquery="SELECT l.hrid,h1.hrName, h1.emp_id ,(select name from designation where id= ha.`designation`) desig ,(select name from department where ID= ha.`postingdepartment`) dept ,
                                DATE_FORMAT(l.applieddate,'%d/%c/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%c/%Y') startday,
                                DATE_FORMAT(l.endday,'%d/%c/%Y') endday,h.hrName approver ,DATE_FORMAT(l.approvedate,'%d/%c/%Y') approvedate 

                                FROM `leave` l LEFT JOIN leaveType lt ON l.leavetype=lt.id LEFT JOIN hr h on l.approver=h.id LEFT JOIN hr h1 ON  l.hrid=h1.id  
                                LEFT JOIN employee e on h1.emp_id=e.employeecode LEFT JOIN hraction ha on e.id=ha.hrid
                                
                                WHERE l.applieddate BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y') and (l.hrid = $hrid or 0= $hrid)

        ";

#and l.applieddate BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')

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
    
    else if($action=="rpt_chalan_details")
    {
        
        $fd1 = $_GET["fd1"];
        $td1 = $_GET["td1"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT p.poid,p.adviceno,DATE_FORMAT(p.orderdt,'%e/%c/%Y') orderdt,DATE_FORMAT(p.delivery_dt,'%e/%c/%Y') received_dt ,t.name cat,i.itemid,
                                pr.name product,i.qty,i.unitprice,i.amount,i.barcode,DATE_FORMAT(i.expirydt,'%e/%c/%Y') expirydt 
                                FROM po p LEFT JOIN poitem i ON p.poid=i.poid LEFT JOIN product pr ON pr.id=i.itemid LEFT JOIN itemtype t ON pr.catagory=t.id 
                                where p.delivery_dt BETWEEN STR_TO_DATE('1/05/2022','%d/%m/%Y') and STR_TO_DATE('09/05/2022','%d/%m/%Y')
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        //Total Amount
        $qrytotal = "SELECT SUM(i.amount) as totalamount 
                    FROM  po p LEFT JOIN poitem i ON p.poid=i.poid LEFT JOIN product pr ON pr.id=i.itemid LEFT JOIN itemtype t ON pr.catagory=t.id
                    where p.delivery_dt BETWEEN STR_TO_DATE('".$fd1."','%d/%m/%Y') and  STR_TO_DATE('".$td1."','%d/%m/%Y')  order by t.name,p.delivery_dt, p.poid,i.itemid";
                                
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
    
    else if($action=="rpt_expire")
    {
        $td = $_GET["td"];
        $bc1 = $_GET["bc1"];
        //$dagent = $_GET["dagent"];
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        //echo $td; die;
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode,DATE_FORMAT(s.expirydt,'%e/%c/%Y') expirydt 
FROM chalanstock s LEFT JOIN item p ON s.product = p.id 
LEFT JOIN itemtype t ON p.catagory=t.id 
LEFT JOIN branch r ON s.storerome=r.id where s.`freeqty`>0 and s.expirydt< STR_TO_DATE('".$td."','%d/%m/%Y') and (s.barcode like '%".$bc1."%' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )";
                    
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        $qrysum = "SELECT SUM(s.freeqty * s.costprice) as totcost, SUM(p.rate * s.freeqty) as totmrp 
                    FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itemtype t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id
                    where s.`freeqty`>0 and s.expirydt< STR_TO_DATE('".$td."','%d/%m/%Y') and (s.barcode like '".$bc1."' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )";
        
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
    
    else if($action=="rpt_storewise_stock")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        $branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode barcode 
                                FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                where (s.barcode='".$bc1."' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 ) and s.freeqty<>0";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        //Total Amount
        $qrytotal = "SELECT SUM(s.costprice * s.freeqty) as totalcost, SUM(s.freeqty * p.rate) as totalmrp 
                    FROM chalanstock s LEFT JOIN item p ON s.product = p.id LEFT JOIN itmCat t ON p.catagory=t.id LEFT JOIN branch r ON s.storerome=r.id  
                                 where (s.barcode='".$bc1."' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = 0 )  and s.freeqty<>0";
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
           
            $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
            $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"]; $bc=$row2["barcode"];
            $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
            $tcp=$tcp+$cp;$tmp=$tmp+$mp;
           
            $data[] = array(
                    "id"=> $sl,
            		"tn"=> $tnm,
            		"pn"=> $prod,
            		"barcode"=> $bc,
            		"str"=> $str,
            		"freeqty"=> number_format($freeqty,0),
            		"costprice"=> number_format($cup,2),
            		"totalcp"=> number_format($cp,2),
            		"mrp"=> number_format($mup,2),
            		"totalmrp"=> number_format($mp,2)
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($total_cp,2));
        array_push($total, number_format($total_mrp,2));
        //print_r($total);die;
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

                                concat(emp1.`firstname`, ' ', emp1.`lastname`) hraction, a.`effectivedt` FROM `appraisal` a 

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

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "ps"=>$row['pstype'],

                    "slr"=>$row['Sl'],

                    "title"=>$row['title'],

                    "kpival"=>$row['kpival'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            $data[] = array(

                    "sl"=>$sl,

                    "empid"=>$row['empid'],

                    "pstype"=>$row['pstype'],

                    "ps"=>$row['ps'],

            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',

            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'

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

            "total"    => $total

        );

        

        //print_r($data);die;

        

        echo json_encode($response);



?>