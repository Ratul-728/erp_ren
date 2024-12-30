<?php

require "../common/conn.php";
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
 
$fromdt='01/01/2015';
$todt = '31/12/2020';

## Search 
 if($action=="order")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (  d.`name` like'%".$searchValue."%' 
        	or concat(em.firstname,' ',em.lastname) like'%".$searchValue."%'  or c.`name` like'%".$searchValue."%'  or  f.`name` like'%".$searchValue."%'  or  c.`size` like'%".$searchValue."%'
        	or  g.`name` like'%".$searchValue."%'  or org.`name` like'%".$searchValue."%'  or  a.`socode` like'%".$searchValue."%'  or  a.`effectivedate` like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn, concat(e1.firstname,'',e1.lastname) `poc`  FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
where (a.`effectivedate` between STR_TO_DATE('".$fromdt."', '%d/%m/%Y') and STR_TO_DATE('".$todt."', '%d/%m/%Y')) and  (a.terminationDate>sysdate() or a.terminationDate is null) ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecords = $records['allcount'];
        
        ## Total number of records with filtering
        $strwithsearchquery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn , concat(e1.firstname,'',e1.lastname) `poc` FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
where  (a.`effectivedate` between STR_TO_DATE('".$fromdt."', '%d/%m/%Y') and STR_TO_DATE('".$todt."', '%d/%m/%Y')) and (a.terminationDate>sysdate() or a.terminationDate is null) ".$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecordwithFilter = $records['allcount'];
        
        ## Fetch records
        //$empQuery = "select * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        
         $empQuery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn , concat(e1.firstname,'',e1.lastname) `poc` FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
where (a.`effectivedate` between STR_TO_DATE('".$fromdt."', '%d/%m/%Y') and STR_TO_DATE('".$todt."', '%d/%m/%Y')) and  (a.terminationDate>sysdate() or a.terminationDate is null)  ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        //echo  $empQuery;
        
        
        /*
                                            <td><?php echo $row["dt"]?></td>
                                            <td><?php echo $row["hrName"];?></td>
                                            <td><?php echo $row["acttp"];?></td>
                                            <td><?php echo $row["cus"];?></td>
        
        */
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
          
            $data[] = array(
        			"contType"=>$row['contType'],
            		"cus_nm"=>$row['cus_nm'],
            		"hrName"=>$row['hrName'],
            		"itmnm"=>$row['itmnm'],
            		"itm_cat"=>$row['itm_cat'],
            		"size"=>$row['size'],
        			"pattern"=>$row['pattern'],
            		"orgn"=>$row['orgn'],
            		"socode"=>$row['socode'],
            		"poc"=>$row['poc'],
            		"orderdate"=>$row['orderdate'],
            		"pmrc"=>$row['mrc'],
        			"otcvalue"=>$row['otc'],
        			"stage"=>$row['stage'],
        			"prob"=>$row['prob'],
            		"stat"=>$row['stat']
            	);
        		
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