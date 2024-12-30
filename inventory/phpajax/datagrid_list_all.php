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
        
        $strwithoutsearchquery="select `id`, `employeecode`, concat_ws(' ',`firstname`,`lastname`) `name`, `dob`,`nid`,`office_contact`,`office_email`,`bloodgroup`, `photo` FROM `employee` where 1=1 ";
        
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
           
            $photo=$rootpath."/common/upload/hc/".$row["employeecode"].".jpg";
            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
            if (file_exists($photo)) {
        		$photo="common/upload/hc/".$row["employeecode"].".jpg";
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
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 
                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or
                cl.`naration` like '%".$searchValue."%'  or cl.`invoice` like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,cl.`invoice`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2) amount FROM collection cl left join organization c on cl.`customerOrg`=c.id 
left join transmode tr on cl.transmode=tr.id
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
            $seturl="collection.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            $setdelurl="common/delobj.php?obj=collection&ret=collectionList&mod=3&id=".$row['id'];
            
            $data[] = array(
                    "trdt"=>$row['trdt'],
            		"transmode"=>$row['transmode'],
            		"transref"=>$row['transref'],
            		"customer"=>$row['customer'],
        			"naration"=>$row['naration'],
            		"amount"=>$row['amount'],
        			"inv"=>$row['invoice'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>',
            	);
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
        			"invoiceamt"=>$row['invoiceamt'],
            		"soid"=>$row['soid'],
        			"name"=>$row['name'],
        			"transdt"=>$row['transdt'],
					"transmode"=>$row['transmode'],
				    "amount"=>$row['amount'],
				    "remarks"=>$row['remarks'],
            	);
        } 
    }
    else if($action=="expense")
    {
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 
                 tp.name  like '%".$searchValue."%' or tr.name like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or
                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`,tp.name trtp, tr.name `transmode`,cl.`transref`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` FROM expense cl, transtype tp, costcenter cc,transmode tr
 where cl.transtype=tp.id and cl.costcenter=cc.id and cl.transmode=tr.id ";
        
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
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        
        
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
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and (i.`code` like '%".$searchValue."%' or 
                 i.`name`  like '%".$searchValue."%' or c.`name` like '%".$searchValue."%' or i.`size` like '%".$searchValue."%' or
                p.`name` like '%".$searchValue."%' or ic.`name` like '%".$searchValue."%' or i.`description` like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT i.`id`, i.`code`, i.`name` itnm,c.`name` bt, i.`size` ct, p.`name` lt, ic.`name` ItemCat, i.`dimension`,i.`wight`, i.`image`, i.`description` 
FROM `item` i,`color` c,`pattern` p,`itmCat` ic
where i.`color`=c.`id` and i.`pattern`= p.`id` and i.`catagory`=ic.`id` ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        
        ##.`id`,
        
         $empQuery=$strwithoutsearchquery.$searchQuery." order by i.`code` limit ".$row.",".$rowperpage;
        
        
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) {
           $seturl="rawitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=1";
           $setdelurl="common/delobj.php?obj=item&ret=rawitemList&mod=1&id=".$row['id'];
            $photo=$rootpath."/common/upload/item/".$row["code"].".jpg";
            //$conthisturl="contactDetail.php?id=".$row['id']."&mod=2";
            if (file_exists($photo)) {
        		$photo="common/upload/item/".$row["code"].".jpg";
        		}else{
        			$photo="images/blankuserimage.png";
        		}
            $data[] = array(
                    "photo"=>'<img src='.$photo.' width="50" height="50">',
                   	"code"=>$row['code'],
            		"itnm"=>$row['itnm'],
            		"bt"=>$row['bt'],
            		"ct"=>$row['ct'],
        			"lt"=>$row['lt'],
            		"ItemCat"=>$row['ItemCat'],
        			"description"=>$row['description'],
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
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (cl.`trdt` like '%".$searchValue."%' or 
                 tr.name  like '%".$searchValue."%' or cl.`transref` like '%".$searchValue."%' or c.name  like '%".$searchValue."%' or
                cl.`naration` like '%".$searchValue."%' or cc.name like '%".$searchValue."%' or cl.`amount` like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
                $strwithoutsearchquery="SELECT cl.`id`, cl.`trdt`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,format(cl.`amount`,2)amount, cc.name `costcenter` 
                FROM allpayment cl left join contact c on cl.customer=c.id left join costcenter cc on cl.costcenter=cc.id left join transmode tr on cl.transmode=tr.id where 1=1 ";
        
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
            
            $data[] = array(
                    "trdt"=>$row['trdt'],
            		"transmode"=>$row['transmode'],
            		"transref"=>$row['transref'],
            		"customer"=>$row['customer'],
        			"naration"=>$row['naration'],
            		"amount"=>$row['amount'],
        			"costcenter"=>$row['costcenter'],
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
        $strwithoutsearchquery="SELECT  p.`id`,p.`poid`,s.`name` , p.`orderdt`, p.`tot_amount`, format(p.`invoice_amount`,2)invoice_amount,p.`delivery_dt` FROM `po` p,`suplier` s  WHERE p.supid=s.id ";
        
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
            $seturl="po.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            $data[] = array(
                    "poid"=>$row['poid'],
            		"name"=>$row['name'],
            		"orderdt"=>$row['orderdt'],
            		"tot_amount"=>$row['tot_amount'],
        			"invoice_amount"=>$row['invoice_amount'],
            		"delivery_dt"=>$row['delivery_dt'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
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
        ## Total number of records without filtering   #c.`id`,
        $basequery="SELECT s.`id`, s.`socode`,tp.`name` `srctype`,c.`name` `customer`,o.`name` organization, date_format(s.`effectivedate`,'%d/%m/%y') `orderdate`,cr.shnm,format(sum(sd.qty*sd.otc),2) otc,format(sum(qtymrc*sd.mrc),2) mrc,concat(e.firstname,'',e.lastname) `hrName`, concat(e1.firstname,'',e1.lastname) `poc`
FROM `soitem` s left join `soitemdetails` sd on sd.socode=s.socode left join `contacttype` tp on  s.`srctype`=tp.`id` left join`contact` c on s.`customer`=c.`id` left join `organization` o on o.`orgcode`=c.organization  
left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode` 
left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join currency cr on sd.currency=cr.id WHERE  1=1 ";
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
        
         $empQuery=$basequery.$searchQuery." group by s.`id`, s.`socode`,tp.`name`,c.`name`,o.`name`,h.`hrName`,cr.shnm  order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //s.`status`<>6
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $i=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $seturl="soitem.php?res=4&msg='Update Data'&id=".$row['id']."&mod=1";
            $setInvurl="invoicPart.php?res=4&msg='Update Data'&id=".$row['id']."&mod=1";
            $setdelurl="common/delobj.php?obj=soitem&ret=soitemList&mod=1&id=".$row['id'];
            $i++;
            $data[] = array(
                    "hrName"=>$i,//$row['hrName'],
            		"srctype"=>$row['srctype'],
            		"customer"=>$row['customer'],
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
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT  1 sl,i.`invoiceno`, i.`invyr`, i.`invoicemonth`, i.`soid`, o.name `organization`, i.`invoiceamt` invoiceamt, format(i.`paidamount`,2)paidamount, i.`dueamount` due, format(i.`dueamount`,2)dueamount, i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`dclass` `paymentSt`,o.balance orgbal,o.id orgid FROM `invoice` i  left join invoicestatus s  on i.invoiceSt=s.id left join invoicepaystatus p on i.paymentSt=p.id  left join organization o on i.organization=o.id where 1=1  ";
        //s.`status`<>6
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
       
        ## Total number of records with filtering # c.`id`,
        
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
  // s.`status`<>6 
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ##.`id`,
        
         $empQuery=$strwithoutsearchquery.$searchQuery."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //s.`status`<>6
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $i=0;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
           $payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."&orbal=".$row['orgbal']."&orgid=".$row['orgid']."')";
           $seturl="invoice.php?res=4&msg='Update Data'&id=".$row['invoiceno']."&mod=3";
           $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';
           $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';
           $action='<span><a href="invoice.php?invid='.$row['invoiceno'].' &mod=3" class="invoice-view" title="View"><i class="fa fa-search"></i></a>
           <a href="invoice_pdf.php?invid='.$row['invoiceno'].' &mod=3" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>
           <a data-invid="'.$row['invoiceno'].'" data-invamount="'.$row['invoiceamt'].'" data-orbal="'.$row['orgbal'].'" data-due="'.$row['due'].'" data-orgid="'.$row['orgid'].'"  href="#" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>
           <a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a></span>';
           $i++;
            $data[] = array(
                    "sl"=>$i,
                    "invoiceno"=>$row['invoiceno'],
                    "invyr"=>$row['invyr'],
            		"invoicemonth"=>date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)),
            		"soid"=>$row['soid'],
            		"organization"=>$row['organization'],
        			"invoiceamt"=> number_format($row['invoiceamt'],0,".",","),
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
                        <div class="fas fa-bars dropdown-toggle bar" id="dropdownMenuButton" data-toggle="dropdown"
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
            if($row['probabledate'] == ''){
                $cdate = "Still Active";
            }else{
                $cdate = $row['probabledate'];
            }        
                    
           
            $data[] = array(
                    "humbar"=>$humbar,
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
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'
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
        	$searchQuery = " and (empname like '%".$searchValue."%' or `acttype` like '%".$searchValue."%' or `deptname` like '%".$searchValue."%' or `janame` like '%".$searchValue."%' or `designation` like '%".$searchValue."%' or `jtname` like '%".$searchValue."%') or `reportto` like '%".$searchValue."%'";
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
        	$searchQuery = " and (a.title like '%".$searchValue."%' or a.`Description` like '%".$searchValue."%' or c.`title` like '%".$searchValue."%' or b.`title` like '%".$searchValue."%' or a.`befitamount` like '%".$searchValue."%')";
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
        	$searchQuery = " and (b.title like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT a.`id`, b.`title`, a.`date` FROM `Holiday` a LEFT JOIN `holidayType` b on a.holidaytype=b.id WHERE 1=1";
        
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
        $searchQuery = "";
        if($searchValue != '')
        {
        	$searchQuery = " and (DATE_FORMAT(u.dt,'%e/%c/%Y') like '%".$searchValue."%' or u.hrName like '%".$searchValue."%' or u.ofctime like'%".$searchValue."%' 
        	or u.shift like'%".$searchValue."%'  or u.entrytm like'%".$searchValue."%'  or u.exittime like'%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="select u.id id,u.dt ,DATE_FORMAT(u.dt,'%e/%c/%Y') trdt,u.hrName,u.ofctime,u.shift
,(select name from designation where id= ha.`designation`) desig
,(select name from department  where ID= ha.`postingdepartment`) dept
,(case when entrytm is null then (case when u.lv is null then (case when u.holiday is null then 'Absent' else u.holiday end)  else u.lv end)  else 'Present' end ) sttus
,u.entrytm,u.exittime,TIMEDIFF(IFNULL(exittime,entrytm),entrytm) durtn from
(
select d.dt,h.id,h.hrName,h.emp_id,e.id eid
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
  // s.`status`<>6 
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        if($columnName == 'sl') $columnName = "u.id";
        if($columnName == 'dt') $columnName = "u.dt";
        //echo $strwithoutsearchquery;die;
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";
           $seturl="hraction.php?res=4&msg='Update Data'&id=".$row['id']."&mod=4";
           $setdelurl="common/delobj.php?obj=hraction&ret=hractionList&mod=4&id=".$row['id'];
            $data[] = array(
                    "dt"=>$row['trdt'],//$empQuery,//
        			"hrName"=>$row['hrName'],
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
FROM monthlysalary s,employee e
where s.hrid=e.id and s.salaryyear=$fdt and s.salarymonth=$tdt";


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
        $searchQuery = "";
        if($searchValue != '')
        {
        	$searchQuery = " and (DATE_FORMAT(l.applieddate,'%e/%c/%Y') like '%".$searchValue."%' or h1.hrName like '%".$searchValue."%' or lt.title like'%".$searchValue."%' 
        	or h.hrName like'%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT l.hrid,h1.hrName
,(select name from designation where id= ha.`designation`) desig
,(select name from department  where ID= ha.`postingdepartment`) dept
,DATE_FORMAT(l.applieddate,'%d/%c/%Y') applydt, lt.title,DATEDIFF(l.endday,l.startday)+1 days,DATE_FORMAT(l.startday,'%d/%c/%Y') startday,DATE_FORMAT(l.endday,'%d/%c/%Y') endday,h.hrName approver
,DATE_FORMAT(l.approvedate,'%d/%c/%Y') approvedate
FROM  `leave` l, leaveType lt,hr h ,hr h1,hraction ha ,employee e
where l.leavetype=lt.id 
and l.approver=h.id
and l.hrid=h1.id
and h1.emp_id=e.employeecode
and e.id=ha.hrid
and l.applieddate BETWEEN STR_TO_DATE('".$fdt."','%d/%m/%Y') and  STR_TO_DATE('".$tdt."','%d/%m/%Y')
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
        	$searchQuery = " and (b.title like '%".$searchValue."%' or a.title like '%".$searchValue."%' or a.weight like '%".$searchValue."%')";
        }
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT a.`id` id, concat(emp.`firstname`, ' ', emp.`lastname`) empid, p.`title` pstype, ps.`title` ps, a.`Sl`, k.`title` kpi, 
                                    ownp.`title` ownpoint, lmp.`title` lmpoint, mp.`title` mpoint, revp.`title` revpoint, hrp.`title` hrpoint 
                                FROM `hrPSsetup` a 
                                LEFT JOIN `employee` emp ON emp.`id` = a.`hrid` 
                                LEFT JOIN `psType` p ON p.`id` = a.`psType` 
                                left JOIN `performanceStandared` ps ON ps.`id` = a.`PS` 
                                LEFT JOIN `KPI` k ON k.`id` = a.`kpi` 
                                LEFT JOIN `kpivalueType` ownp ON ownp.`id` = a.`ownpoint` 
                                LEFT JOIN `kpivalueType` lmp ON lmp.`id` = a.`linemanagerpoint` 
                                LEFT JOIN `kpivalueType` mp ON mp.`id` = a.`managerpoint` 
                                LEFT JOIN `kpivalueType` revp ON revp.`id` = a.`reviewpoint`
                                LEFT JOIN `kpivalueType` hrp ON hrp.`id` = a.`hrdpoint` WHERE a.st = 1";
        
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
                    "slr"=>$row['Sl'],
                    "kpi"=>$row['kpi'],
                    "ownpoint"=>$row['ownpoint'],
                    "lmpoint"=>$row['lmpoint'],
                    "mpoint"=>$row['mpoint'],
                    "revpoint"=>$row['revpoint'],
                    "hrpoint"=>$row['hrpoint'],
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
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        
        echo json_encode($response);

?>