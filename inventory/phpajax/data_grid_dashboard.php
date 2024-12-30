<?php
//include 'config.php';
require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
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
    if($action=="1")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.yr like '%".$searchValue."%'  or 
                DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering
          $baseqrry="SELECT   s.`yr`,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn
    ,format(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)+s.`otc`) Else 0 end )),2) n
    ,format(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end )),2) exs
    ,format((sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)+s.`otc`) Else 0 end ))+sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end ))),2) tot
    FROM  `rpt_sales_so` s  ,`reportmanth` r  
    WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))
    and s.yr>=YEAR(CURDATE()) ";
    
            $strwithoutsearchquery=$baseqrry."group by s.`yr`, s.`mnth`  "; 
            $sel = mysqli_query($con,$strwithoutsearchquery);
            $totalRecords = $sel->num_rows;
            
        ## Total number of records with filtering
        $strwithsearchquery=$baseqrry.$searchQuery."group by s.`yr`, s.`mnth`  ";
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ## Fetch records
         $empQuery=$baseqrry.$searchQuery."group by s.`yr`, s.`mnth` "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $data[] = array(
            		"yr"=>$row['yr'],
        			"mnth"=>$row['mnth'],
            		"n"=>$row['n'],
            		"exs"=>$row['exs'],
            		"total"=>$row['tot']
            	);
        }
    }
    else  if($action=="2")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.`hrName` like '%".$searchValue."%'  ) ";
        }
        
        ## Total number of records without filtering
          $baseqrry="SELECT  s.`hrName`
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc 
,format((sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )+sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)),2) tot
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE())  ";
    
            $strwithoutsearchquery=$baseqrry." group by s.`hrName`  "; 
            $sel = mysqli_query($con,$strwithoutsearchquery);
            $totalRecords = $sel->num_rows;
            
        ## Total number of records with filtering
        $strwithsearchquery=$baseqrry.$searchQuery." group by s.`hrName` ";
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ## Fetch records
        $empQuery=$baseqrry.$searchQuery." group by s.`hrName` "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $data[] = array(
            		"hrName"=>$row['hrName'],
        			"otcvalue"=>$row['otcvalue'],
            		"pmrc"=>$row['pmrc'],
            		"tot"=>$row['tot']
            	);
        }
    }
    else  if($action=="3")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.`itm_cat` like '%".$searchValue."%'  ) ";
        }
        
        ## Total number of records without filtering
          $baseqrry="SELECT  s.`itm_cat`
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc
,format((sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )+sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)),2) tot
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE())  ";
    
            $strwithoutsearchquery=$baseqrry." group by s.`itm_cat`  "; 
            $sel = mysqli_query($con,$strwithoutsearchquery);
            $totalRecords = $sel->num_rows;
            
        ## Total number of records with filtering
        $strwithsearchquery=$baseqrry.$searchQuery." group by s.`itm_cat` ";
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ## Fetch records
        $empQuery=$baseqrry.$searchQuery." group by s.`itm_cat` "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $data[] = array(
            		"itm_cat"=>$row['itm_cat'],
        			"otcvalue"=>$row['otcvalue'],
            		"pmrc"=>$row['pmrc'],
            		"tot"=>$row['tot']
            	);
        }
    }
    else  if($action=="4")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.`size` like '%".$searchValue."%'  ) ";
        }
        
        ## Total number of records without filtering
          $baseqrry="SELECT  s.`size`
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,round(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE())  ";
    
            $strwithoutsearchquery=$baseqrry." group by s.`size`  "; 
            $sel = mysqli_query($con,$strwithoutsearchquery);
            $totalRecords = $sel->num_rows;
            
        ## Total number of records with filtering
        $strwithsearchquery=$baseqrry.$searchQuery." group by s.`size` ";
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ## Fetch records
        $empQuery=$baseqrry.$searchQuery." group by s.`size` "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $data[] = array(
            		"size"=>$row['size'],
        			"otcvalue"=>$row['otcvalue'],
            		"pmrc"=>$row['pmrc'],
            		"tot"=>$row['tot']
            	);
        }
    }
    else  if($action=="5")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.yr like '%".$searchValue."%'  or 
                DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') like '%".$searchValue."%'  ) ";
        }
        
        ## Total number of records without filtering
          $baseqrry="SELECT  s.yr,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn,round(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)) Else 0 end )),2) nmrc
,round(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end )),2) emrc
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2) otcvalue
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))
and s.yr>=YEAR(CURDATE())  ";
    
            $strwithoutsearchquery=$baseqrry." group by s.yr,s.mnth  "; 
            $sel = mysqli_query($con,$strwithoutsearchquery);
            $totalRecords = $sel->num_rows;
            
        ## Total number of records with filtering
        $strwithsearchquery=$baseqrry.$searchQuery." group by s.yr,s.mnth ";
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ## Fetch records
        $empQuery=$baseqrry.$searchQuery." group by s.yr,s.mnth "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $data[] = array(
            		"yr"=>$row['yr'],
            		"mn"=>$row['mn'],
        			"otcvalue"=>number_format($row['otcvalue'],2),
            		"nmrc"=>number_format($row['nmrc'],2),
            		"emrc"=>number_format($row['emrc'],2),
            		"tot"=>number_format(($row['otcvalue']+$row['nmrc']+$row['emrc']),2)
            	);
        }
    }
    else  if($action=="6")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.`itmnm` like '%".$searchValue."%'  ) ";
        }
        
        ## Total number of records without filtering
          $baseqrry="SELECT  s.`itmnm`
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2) otcvalue
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc 
,format((sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )+sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)),2) tot
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE())  ";
    
            $strwithoutsearchquery=$baseqrry." group by s.`itmnm`  "; 
            $sel = mysqli_query($con,$strwithoutsearchquery);
            $totalRecords = $sel->num_rows;
            
        ## Total number of records with filtering
        $strwithsearchquery=$baseqrry.$searchQuery." group by s.`itmnm` ";
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        ## Fetch records
        $empQuery=$baseqrry.$searchQuery." group by s.`itmnm` "." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit();
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
            $data[] = array(
            		"itmnm"=>$row['itmnm'],
        			"otcvalue"=>$row['otcvalue'],
            		"pmrc"=>$row['pmrc'],
            		"tot"=>$row['tot']
            	);
        }
    }
    else
    {
    
    }
    
    
    
    //$data[] = array('dt'=>$empQuery);
    
    ## Response
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );
    
    echo json_encode($response);
}
