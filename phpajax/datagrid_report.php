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
    
    $total = array();
    
    $action= $_GET['action'];
    if($action=="tar_acv")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (t.yr like '%".$searchValue."%'  or 
                monthname(str_to_date(t.mnth,'%m')) like '%".$searchValue."%' or 
                h.`hrName` like'%".$searchValue."%' or  i.`name` like'%".$searchValue."%' or  ifnull(u.shnm,'BDT') like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
            $strwithoutsearchquery="select t.yr,monthname(str_to_date(t.mnth,'%m')) mnth,t.mnth mnt
         ,h.`hrName` accmgr,i.`id` itid,i.name itmcatagory,ifnull(u.shnm,'BDT') crnc,t.target target
,round((ifnull(u.acv,0)-ifnull(u.p_acv,0)),2)acv
from salestarget t left join (
SELECT 
o.salesperson acm,i.catagory icat,DATE_FORMAT(si.`effectivedate`, '%Y') syr,DATE_FORMAT(si.`effectivedate`, '%m') smn
,sum((ifnull(d.qty,0)*ifnull(d.otc,0))+(ifnull(d.qtymrc,0)*ifnull(d.mrc,0))) acv
,sum(ifnull((select ((ifnull(d1.qty,0)*ifnull(d1.otc,0))+(ifnull(d1.qtymrc,0)*ifnull(d1.mrc,0))) from soitemdetails d1 where d1.socode=si.oldsocode and d1.productid=d.productid),0))p_acv
,cr.shnm    
FROM soitem si join organization o on o.id=si.organization
 join soitemdetails d on si.socode=d.socode
 join item i on i.id=d.productid left join currency cr on d.currency=cr.id
  WHERE DATE_FORMAT(si.`effectivedate`, '%Y')>='2020' 
group by o.salesperson,i.catagory,syr,smn,cr.shnm

)u on t.yr=u.syr and t.mnth=CONVERT(u.smn,UNSIGNED) and t.accmgr=u.acm and t.itmcatagory=u.icat
join `hr` h  on t.accmgr=h.id
join `itmCat` i on t.itmcatagory=i.`id` 
where t.yr ='2020' ";                     
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecords = $records['allcount'];
        
        ## Total number of records with filtering
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecordwithFilter = $records['allcount'];
        
        ## Fetch records
     
         $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        //echo  $empQuery;
       
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
           $acvp=$row['acv']/$row['target']*100;
           $dtl="rpt_accmgr_acv_detail.php?accm=".$row['accmgr']."&yer=".$row['yr']."&mnt=".$row['mnt']."&itid=".$row['itid']."&mod=2";
            $data[] = array(
            		"yr"=>$row['yr'],
        			"mnth"=>$row['mnth'],
            		"accmgr"=>$row['accmgr'],
            		"itmcatagory"=>$row['itmcatagory'],
            		"crnc"=>$row['crnc'],
            		"target"=>$row['target'],
            		"acv"=>'<a class=""  href="'.$dtl.'">'.$row['acv'].'</a>',
            		"acvper"=>round($acvp,2)
            	);
        		
        }
    }
    else if($action=="sales_forcast")
    {
        $qry = "CALL psp_salse_forcast(1)";
        $result1 = $con->query($qry);
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	//$searchQuery = " and (r.dt like '%".$searchValue."%' or s.`contType` like '%".$searchValue."%' or  s.`cus_nm` like'%".$searchValue."%' 
        	//or  s.`hrName` like'%".$searchValue."%'  or s.`itmnm` like'%".$searchValue."%'  or  s.`itm_cat` like'%".$searchValue."%'  or  s.`size` like'%".$searchValue."%'
        	//or  s.`pattern` like'%".$searchValue."%'  or s.`orgn` like'%".$searchValue."%'  or  s.`socode` like'%".$searchValue."%'  or  s.`orderdate` like'%".$searchValue."%' or  `frcst` like'%".$searchValue."%') ";
        
            //$searchQuery = " and ( `frcst` like'%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT s.`socode`, s.`contType`, s.`cus_id`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`da`, s.`hrid`, s.`hrName`, s.`itmid`, s.`itmnm`, s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`
,(case when r.yr=s.yr and r.month=s.mnth then 'New' Else 'Existing' end ) stat
,(case when r.yr=s.yr and r.month=s.mnth then round((s.`mrc`*(r.dy-s.`da`+1))/r.dy,2) Else s.`mrc` end ) pmrc
,(case when r.yr=s.yr and r.month=s.mnth then round(s.`otc`,2) Else 0 end ) otcvalue
,(case when r.`dt`>sysdate() then 'Forcast' else 'Actual' end) frcst
,s.poc,s.currnm, o.`name` name, o.`contactno`, a.name buildn, b.name towern, g.`name` flatn, c.name opst, d.name ownern, d.phone ownernum, e.name tname, f.name flatname 
FROM  `rpt_sales_so` s  ,`reportmanth` r , `organization` o, area a, state b, operationstatus c, orgaContact d , district e, country f, flatnumber g
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.orgn = o.orgcode and o.`area` = a.id and o.`state` = b.id and o.`operationstatus` = c.id and o.`orgcode` = d.organization and d.conatcttype = 1 and d.designation = 1 and o.district = e.id and o.country = f.id
                and g.id = o.zip";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'frcst' && $columnSortOrder == 'desc'){
            $searchQuery .= " and r.`dt`> sysdate() ";
        }else if ($columnName == 'frcst' && $columnSortOrder == 'asc'){
            $searchQuery .= " and r.`dt`< sysdate() ";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
          
            $data[] = array(
            		"dt"=>$row['dt'],
            		"cus_nm"=>$row['cus_nm'],
            		"orgn"=>$row['orgn'],
            		"company"=>$row['opst'],
            		"buildn"=>$row['buildn'],
            		"towern"=>$row['tname'],
            		"floorn"=>$row['towern'],
            		"flatn"=>$row['flatn'],
            		"state"=>$row['flatname'],
            		"socode"=>$row['socode'],
            		"orderdate"=>$row['orderdate'],
            		"currnm"=>$row['currnm'],
            		"pmrc"=>$row['pmrc'],
        			"otcvalue"=>$row['otcvalue'],
        			"stage"=>$row['stage'],
        			"prob"=>$row['prob'],
            		"stat"=>$row['stat'],
            		"frcst"=>$row['frcst']
            	);
        		
        }
    }
    
    else if($action=="rpt_sof")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (  d.`name` like'%".$searchValue."%' 
        	or concat(em.firstname,' ',em.lastname) like'%".$searchValue."%'  or c.`name` like'%".$searchValue."%'  or  f.`name` like'%".$searchValue."%'  or  c.`size` like'%".$searchValue."%' or  cr.shnm like'%".$searchValue."%'
        	or  g.`name` like'%".$searchValue."%'  or org.`name` like'%".$searchValue."%'  or  a.`socode` like'%".$searchValue."%'  or  a.`effectivedate` like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, DATE_FORMAT( a.effectivedate,'%d/%b/%Y %H %i %s') orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn, concat(e1.firstname,'',e1.lastname) `poc`  FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`  left join currency cr on b.currency=cr.id
where  (a.terminationDate>sysdate() or a.terminationDate is null) ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecords = $records['allcount'];
        
        ## Total number of records with filtering
        $strwithsearchquery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm,DATE_FORMAT( a.effectivedate,'%d/%b/%Y %H %i %s') orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn, concat(e1.firstname,'',e1.lastname) `poc`  FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`  left join currency cr on b.currency=cr.id
where  (a.terminationDate>sysdate() or a.terminationDate is null) ".$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecordwithFilter = $records['allcount'];
        
        ## Fetch records
        //$empQuery = "select * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        if($columnName == "orderdate") $columnName = "a.effectivedate";
        
         $empQuery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, DATE_FORMAT( a.effectivedate,'%d/%b/%Y %H %i %s') orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn, concat(e1.firstname,'',e1.lastname) `poc`  FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`  left join currency cr on b.currency=cr.id
where  (a.terminationDate>sysdate() or a.terminationDate is null)  ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
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
            		"shnm"=>$row['shnm'],
            		"pmrc"=>$row['mrc'],
        			"otcvalue"=>$row['otc'],
        			"stage"=>$row['stage'],
        			"prob"=>$row['prob'],
            		"stat"=>$row['stat']
            	);
        		
        }
    }
    else if($action=="terminat_so")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.`terminationDate` like '%".$searchValue."%' or c.name like '%".$searchValue."%' or h.hrName like'%".$searchValue."%' 
        	or  i.name like'%".$searchValue."%'  or ic.name like'%".$searchValue."%'  or p.name like'%".$searchValue."%'  or  i.size like'%".$searchValue."%'
        	or o.name like'%".$searchValue."%'   or  s.`socode` like'%".$searchValue."%' or  cr.shnm like'%".$searchValue."%'  or  s.`effectivedate` like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT DATE_FORMAT(s.`terminationDate`, '%d/%b/%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d/%b/%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc 
FROM soitem s left join soitemdetails d on s.`socode`= d.`socode`
	left join terminationcause c on s.`terminationcause`=c.id
    left join organization o on s.`organization`=o.id
    left join hr h on o.`salesperson`=h.`id` 
    left join item i on d.`productid`=i.`id`
    left join itmCat ic on i.`catagory`=ic.id
    left join pattern p on i.`pattern`=p.`id`
    left join currency cr on d.currency=cr.id
WHERE    `terminationDate`<sysdate()";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecords = $records['allcount'];
        
        ## Total number of records with filtering
        $strwithsearchquery="SELECT DATE_FORMAT(s.`terminationDate`, '%d/%b/%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d/%b/%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc 
FROM soitem s left join soitemdetails d on s.`socode`= d.`socode`
	left join terminationcause c on s.`terminationcause`=c.id
    left join organization o on s.`organization`=o.id
    left join hr h on o.`salesperson`=h.`id` 
    left join item i on d.`productid`=i.`id`
    left join itmCat ic on i.`catagory`=ic.id
    left join pattern p on i.`pattern`=p.`id`
    left join currency cr on d.currency=cr.id
WHERE    `terminationDate`<sysdate() ".$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        //$records = mysqli_fetch_assoc($sel);
        //$totalRecordwithFilter = $records['allcount'];
        
        ## Fetch records
        //$empQuery = "select * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        if($columnName == "tdt") $columnName = "s.terminationDate";
        if($columnName == "efdt") $columnName = "s.effectivedate";
        
         $empQuery="SELECT DATE_FORMAT(s.`terminationDate`, '%d/%b/%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d/%b/%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc 
FROM soitem s left join soitemdetails d on s.`socode`= d.`socode`
	left join terminationcause c on s.`terminationcause`=c.id
    left join organization o on s.`organization`=o.id
    left join hr h on o.`salesperson`=h.`id` 
    left join item i on d.`productid`=i.`id`
    left join itmCat ic on i.`catagory`=ic.id
    left join pattern p on i.`pattern`=p.`id`
    left join currency cr on d.currency=cr.id
WHERE    `terminationDate`<sysdate()  ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
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
            		"tdt"=>$row['tdt'],
        			"terminationcause"=>$row['terminationcause'],
            		"hrName"=>$row['hrName'],
            		"itmnm"=>$row['itmnm'],
            		"itmcat"=>$row['itmcat'],
            		"size"=>$row['size'],
            		"comtp"=>$row['comtp'],
        			"ornm"=>$row['ornm'],
            		"socode"=>$row['socode'],
            		"efdt"=>$row['efdt'],
            		"shnm"=>$row['shnm'],
            		"mrc"=>$row['mrc'],
            		"otc"=>$row['otc']
            	);
        		
        }
    }
    else if($action=="deal_forcast")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.`name` like '%".$searchValue."%' or s.`contType` like '%".$searchValue."%' or s.`cus_nm` like'%".$searchValue."%' 
        	or  s.`orderdate` like'%".$searchValue."%'  or s.`yr` like'%".$searchValue."%'  or s.`mnth` like'%".$searchValue."%'  or  s.`dy` like'%".$searchValue."%'
        	or s.`hrName` like'%".$searchValue."%'   or  s.`itmnm` like'%".$searchValue."%' or  s.currency like'%".$searchValue."%'  or  round(s.`mrc`,2) like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT s.`name` socode, s.`contType`, s.`cus_nm`, DATE_FORMAT(s.`orderdate`,'%d/%b/%Y') `orderdate`, s.`yr`, s.`mnth`, s.`dy` da, s.`hrName`, s.`itmnm`
        , s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy, DATE_FORMAT(r.`dt`,'%d/%b/%Y') `dt`,s.`st` stat
,round(s.`mrc`,2) pmrc,round(s.`otc`,2) otcvalue,'Forcast'  frcst
,s.`scale`,round(s.`probability`,0)probability
,round(((s.`otc`+s.`mrc`)*s.probability/100),2) revenue,s.currency
FROM  `rpt_sales_deal` s  ,`reportmanth` r  
WHERE   ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        if($columnName == "dt") $columnName = "r.dt";
        if($columnName == "orderdate") $columnName = "s.orderdate";
       
        ## Total number of records with filtering
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
          
            $data[] = array(
            		"dt"=>$row['dt'],
        			"contType"=>$row['contType'],
            		"cus_nm"=>$row['cus_nm'],
            		"hrName"=>$row['hrName'],
            		"itmnm"=>$row['itmnm'],
            		"itm_cat"=>$row['itm_cat'],
            		"size"=>$row['size'],
        			"pattern"=>$row['pattern'],
            		"orgn"=>$row['orgn'],
            		"socode"=>$row['socode'],
            		"orderdate"=>$row['orderdate'],
            		"currency"=>$row['currency'],
            		"otcvalue"=>$row['otcvalue'],
            		"pmrc"=>$row['pmrc'],
            		"stage"=>$row['stage'],
            		"stat"=>$row['stat'],
            		"frcst"=>$row['frcst'],
            		"revenue"=>$row['revenue'],
            		"scale"=>$row['scale'],
            		"probability"=>$row['probability']
            	);
        		
        }
    }
    else if($action=="accmgr_acv")
    {
        $acm= $_GET['accm'];$yr= $_GET['yer'];$mn= $_GET['mnt'];$cat= $_GET['ct'];
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (s.socode like '%".$searchValue."%' or org.name like '%".$searchValue."%' or s.effectivedate like'%".$searchValue."%' 
        	or  i.name like'%".$searchValue."%'  or ct.name like'%".$searchValue."%'  or cr.shnm like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering CONVERT(u.smn,UNSIGNED)
       
        $strwithoutsearchquery="select '".$yr."' yr, monthname(str_to_date(".$mn.",'%m')) mn,'".$acm."' acm,s.socode,org.name organization,s.effectivedate,i.name item,ct.name ctnm, cr.shnm currency
                ,d.qty,d.otc,d.qtymrc,d.mrc ,((ifnull(d.qty,0)*ifnull(d.otc,0))+(ifnull(d.qtymrc,0)*ifnull(d.mrc,0))) acv
                ,(ifnull((select sum((ifnull(d1.qty,0)*ifnull(d1.otc,0))+(ifnull(d1.qtymrc,0)*ifnull(d1.mrc,0))) from soitemdetails d1 where d1.socode=s.oldsocode and d1.productid=d.productid),0))p_acv
                from soitem s left join soitemdetails d on s.socode=d.socode 
	                left join organization org  on s.organization=org.id
                    left join item i on d.productid=i.id
                    left join currency cr on d.currency=cr.id
                    left join itmCat ct on i.catagory=ct.id 
                where DATE_FORMAT(s.effectivedate,'%Y')=".$yr." 
                and  convert(DATE_FORMAT(s.effectivedate,'%m'),UNSIGNED)='".$mn."'
                and ct.id=".$cat."
                and s.organization in 
                (
                	select org.id from organization org where org.salesperson in
                	(
                 		select h.id from hr h where h.hrName ='".$acm."'   
                	)
                ) ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
       
        ## Total number of records with filtering
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
          
            $data[] = array(
            		"yr"=>$row['yr'],
        			"mnth"=>$row['mn'],
            		"accmgr"=>$row['acm'],
            		"socode"=>$row['socode'],
            		"organization"=>$row['organization'],
            		"effectivedate"=>$row['effectivedate'],
            		"item"=>$row['item'],
        			"ctnm"=>$row['ctnm'],
            		"currency"=>$row['currency'],
            		"qty"=>$row['qty'],
            		"otc"=>$row['otc'],
            		"qtymrc"=>$row['qtymrc'],
            		"mrc"=>$row['mrc'],
            		"pacv"=>$row['p_acv'],
            		"acv"=>$row['acv']-$row['p_acv']
            	);
        		
        }
    }
    else if($action=="orgwalllist")
    {
        $filterst = $_GET["filterst"];
        
        if($filterst == 1){
            $filterqry = "HAVING invoiceamt = 0 ";
        }else if($filterst == 3){
            $filterqry = " HAVING (dueAmt <= 0 and invoiceamt != 0) ";
        }else if($filterst == 2){
            $filterqry = " HAVING (dueAmt > 0 and invoiceamt != 0 )";
        }else{
            $filterqry = "";
        }
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`name` like '%".$searchValue."%' or i.`name` like '%".$searchValue."%' or o.`contactno` like'%".$searchValue."%' 
        	or  o.`email` like'%".$searchValue."%'  or o.`website` like'%".$searchValue."%'  or concat(e.firstname,'',e.lastname) like'%".$searchValue."%'
        	or  o.balance like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $basequery="SELECT o.orgcode, o.`id`,o.`name`,i.`name` `industry`,o.`contactno`,o.`email`,o.`website`
,concat(e.firstname,'',e.lastname) accmgr,o.balance,sum(COALESCE(b.invoiceamt,0)) invoiceamt
,sum(COALESCE(b.paidamount,0)) pidAmt,sum(COALESCE(b.dueamount,0)) dueAmt
FROM organization o left join businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`
left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` 
left join  invoice b on o.id=b.organization
where 1=1 ";
        
        $strwithoutsearchquery=$basequery."group by  o.`id`,o.`name`,i.`name`,o.`contactno`,o.`email`,o.`website`,e.firstname,e.lastname,o.balance";
         
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
       
        ## Total number of records with filtering
        $strwithsearchquery=$basequery.$searchQuery."group by  o.`id`,o.`name`,i.`name`,o.`contactno`,o.`email`,o.`website`,e.firstname,e.lastname,o.balance";
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        $empQuery=$basequery.$searchQuery." group by  o.`id`,o.`name`,i.`name`,o.`contactno`,o.`email`,o.`website`,e.firstname,e.lastname,o.balance".$searchQuery.$filterqry." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
          $dtl="organazionwallet.php?orgid=".$row['id']."&mod=7";
          $withdrawal = '<a data-cid="'.$row['id'].'"  href="make_withdrawal_popup.php?cid='.$row['id'].'" class="withdrawal  btn btn-info btn-xs" title="Withdrawal"><i class="fa fa-dollar"></i></a>';
           
			if($row['balance'] <= 0){
				$withdrawal = '<a href="javascript:void(0)" disabled class="btn btn-info btn-xs" style="" title="Withdrawal"><i class="fa fa-dollar"></i></a>';
			}
			
          if($row['invoiceamt']==0){$st='No Purchase Yet';} else if($row['dueAmt']<=0){$st='No Due';} else {$st='Due';}
            $data[] = array(
                    "orgcode"=>$row['orgcode'],
            		"name"=>'<a href="'. $dtl.'" target="_blank">'.$row['name'].'</a>',
            		"contactno"=>$row['contactno'],
            		"email"=>$row['email'],
            		"website"=>$row['website'],
            		"accmgr"=>$row['accmgr'],
            		"balance"=>number_format($row['balance'],2),
            		"invoiceamt"=>number_format($row['invoiceamt'],2),
            		"paidAmt"=>number_format($row['pidAmt'],2),
            		"dueAmt"=>number_format($row['dueAmt'],2),
            		"status"=>$st,
            		"withdrawal" => $withdrawal
            	);
        		
        }
    }
    else if($action=="orgwall")
    {
        $org_id= $_GET['orgid'];
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        { 
        	$searchQuery = " and (w.`transdt` like '%".$searchValue."%' or o.name like '%".$searchValue."%' or m.name like'%".$searchValue."%' 
        	or  w.`trans_ref` like'%".$searchValue."%'  or w.`amount` like'%".$searchValue."%'  or w.`remarks` like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT w.`id`,DATE_FORMAT(w.`transdt`, '%d/%b/%Y') `transdt`,o.name org,m.name `transmode`, (case w.`dr_cr` when 'd' then 'Debit' when 'C' then 'Credit' else ' ' end) drcr, w.`trans_ref`, w.`amount`, w.`remarks` ,w.balance FROM `organizationwallet` w left join organization o on w.`orgid`=o.id left join transmode m on w.`transmode`=m.id
where w.`orgid`='".$org_id."'  ";
        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
       
        ## Total number of records with filtering
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName = "transdt"){
            $columnName = " DATE_FORMAT(w.`transdt`, '%d/%b/%Y') ";
        }
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ,w.id  ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
           $seturl="wallet.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
           $slip = '<a href="orgwallet_slip.php?trid='.$row['id'].'" class="btn btn-info btn-xs slip-print"><i class="fa fa-print"></i></a> | ';
            $data[] = array(
            		"transdt"=>$row['transdt'],
        			"org"=>$row['org'],
            		"drcr"=>$row['drcr'],
            		"transmode"=>$row['transmode'],
            		"trans_ref"=>$row['trans_ref'],
            		"remarks"=>$row['remarks'],
            		"amount"=>number_format($row['amount'], 2),
            		"balance"=>number_format($row['balance'], 2),
            		"edit"=> $slip.' <a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            	);
        		
        }
    }
    else if($action=="attendence")
    {
        $fd= $_POST['filter_date_from'];
        $td= $_POST['filter_date_to'];
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        { 
        	$searchQuery = " and (DATE_FORMAT(u.dt,'%e/%c/%Y') like '%".$searchValue."%' or u.hrName like '%".$searchValue."%' or u.ofctime like'%".$searchValue."%' 
        	or u.shift like'%".$searchValue."%'  or u.entrytm like'%".$searchValue."%'  or u.exittime like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="select DATE_FORMAT(u.dt,'%e/%c/%Y') dt,u.hrName,u.ofctime,u.shift
,(select name from designation where id= ha.`designation`) desig
,(select title from PostingDepartment where ID= ha.`postingdepartment`) dept
,(case when entrytm is null then (case when u.lv is null then 'Absent' else u.lv end)  else 'Present' end ) sttus
,u.entrytm,u.exittime,TIMEDIFF(IFNULL(exittime,entrytm),entrytm) durtn from
(
select d.dt,h.id,h.hrName,h.emp_id,e.id eid
,(select min(intime) from attendance where hrid=h.id and date=d.dt) entrytm
,(select (case when max(outtime) is null then max(intime) when max(intime)>max(outtime) then  max(intime) else max(outtime) end)  from attendance where hrid=h.id and date=d.dt) exittime
,(select title from Shifting where id=(select shift from assignshifthist where empid=e.id 
and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))shift
,(select `start` from OfficeTime where id=(select shift from assignshifthist where empid=h.id 
and effectivedt =(select max(effectivedt) from assignshifthist where `empid`=e.id and `effectivedt`<=d.dt)))ofctime
,(SELECT lt.title FROM  `leave` l, leaveType lt where l.leavetype=lt.id and  hrid=h.id 
and d.dt BETWEEN l.startday and l.endday) lv    
from loggday d,hr h ,employee e
where d.dt between '2021-06-25' and '2021-07-06'

and h.emp_id=e.employeecode
) u,hraction ha where u.eid=ha.hrid "; 
//#and h.id=45 order by u.hrName,u.dt";;
//and d.dt BETWEEN STR_TO_DATE('".$fd."','%d/%b/%Y') and  STR_TO_DATE('".$td."','%d/%b/%Y')        
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
       
        ## Total number of records with filtering
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
         
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
           $seturl="wallet.php?res=4&msg='Update Data'&id=".$row['id']."&mod=3";
            $data[] = array(
            		"dt"=>$row['dt'],//$empQuery,//
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
        		
        }
    }
    else if($action=="rpt_cash_flow")
    {
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and invoicedt between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d') ";
            $date_qry2 = "where invoicedt < STR_TO_DATE('".$fdt."','%Y-%m-%d')";
            $date_qry3 = "and trdt between DATE_FORMAT('$fdt', '%Y-%m-%d') and DATE_FORMAT('$tdt', '%Y-%m-%d')";
            $date_qry4 = "where trdt < STR_TO_DATE('".$fdt."','%Y-%m-%d')";
        }else{
            $date_qry = "";
            $date_qry2 = "";
            $date_qry3 = "";
            $date_qry4 = "";
        }
        
        $bal=0;$i=0;$bf=0;$totdr=0;$totcr=0;$net=0;
        //echo $fd;die;
        $qry0="select sum(paidamount) dra from invoice $date_qry2";
        $qry1="select sum(amount) cra from expense $date_qry4";
        //echo $qry1;die;
        $result0 = $conn->query($qry0);
        $row0 = $result0->fetch_assoc();
        $d=$row0["dra"];
        //echo $d;die;
        $result1 = $conn->query($qry1);
        $row1 = $result1->fetch_assoc();
        $c=$row1["cra"];
        $bal=$d-$c;
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select date_format(trdt,'%d/%b/%Y') trdt,narr,incm dr,expns cr
                                FROM
                                (
                                    SELECT `invoicedt` trdt,`paidamount` incm,0 expns,concat(soid,'-',invoiceno) narr 
                                    FROM invoice where 1=1 $date_qry
                                    union all 
                                    select trdt  trdt,0 incm,amount expns,naration narr from expense where 1=1 $date_qry3
                                ) u
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'trdt';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $totbal = 0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $trdt=$row2["trdt"];$narr=$row2["narr"]; $dr=$row2["dr"]; $cr=$row2["cr"];  
            $bal=$bal+$dr-$cr;$i++;$totdr=$totdr+$dr;$totcr=$totcr+$cr;
            $totbal += $bal;
           
            $data[] = array(
                    "id"=> $sl,
                    "trdt"=> $trdt,
            		"narr"=> $narr,
            		"dr"=> number_format($dr,2),
            		"cr"=> number_format($cr,2),
            		"balance"=> number_format($bal,2),
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($totdr,2));
        array_push($total, number_format($totcr,2));
        array_push($total, number_format($totbal,2));
        
    }
    else if($action=="rpt_customer")
    {
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and a.invoicedt between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
        }else{
            $date_qry = "";
        }
        
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select o.name,sum(b.qty*b.otc) revenue,sum(b.cost*b.qty) cost,sum(b.vat)vat,sum(b.ait) ait,c.deliveryamt deliverycost,sum(COALESCE(((b.qty*b.otc)-(b.cost*b.qty)),0)) margin
                                from invoice a left join soitem c on a.soid=c.socode left join soitemdetails b on b.socode=c.socode join organization o on  c.organization=o.id
                                WHERE 1=1 ".$date_qry."
                                group by  o.name
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "id"){
            $columnName = "a.id";
        }
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $totrev = 0; $totcost = 0; $totvat = 0; $totait = 0; $totdeli = 0; $totmar = 0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           $totrev += $row2["revenue"];
           $totcost += $row2["cost"];
           $totvat += $row2["vat"];
           $totait += $row2["ait"];
           $totdeli += $row2["deliverycost"];
           $totmar += $row2["margin"];
            $data[] = array(
                    "id"=> $sl,
                    "name"=> $row2["name"],
            		"revenue"=> $row2["revenue"],
            		"cost"=> number_format($row2["cost"],2),
            		"vat"=> number_format($row2["vat"],2),
            		"ait"=> number_format($row2["ait"],2),
            		"deliverycost"=> number_format($row2["deliverycost"],2),
            		"margin"=> number_format($row2["margin"],2),
            		"total"=> number_format(($row2["revenue"]+ $row2["vat"]),2),
            		
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($totrev,2));
        array_push($total, number_format($totcost,2));
        array_push($total, number_format($totvat,2));
        array_push($total, number_format($totait,2));
        array_push($total, number_format($totdeli,2));
        array_push($total, number_format($totmar,2));
        
        
    } 
    
    else if($action=="ason")
    {
        
        $fdt = $_GET["dt_f"];
        //$tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = "and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<=DATE_FORMAT('$fdt', '%Y-%m-%d') )";
        }else{
            $date_qry = "";
        }
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and (i.barcode like  '%".$searchValue."%' or i.name like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select i.barcode,i.name product,i.description,h.freeqty qty,h.`costprice` rate,(h.`freeqty`*h.`costprice`) cost,b.name loc,DATE_FORMAT(h.`stockdate`, '%d/%b/%Y') stockdate from stockhist h, item i,branch b  where h.product=i.id and h.store=b.id  $date_qry ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        
        $columnName = "i.barcode, b.name";
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        // echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
            $data[] = array(
                    "id"=> $sl,
                    "product"=> $row2["product"],
            		"barcode"=> $row2["barcode"],
            		"description"=> $row2["description"],
            		"qty"=> number_format($row2["qty"],0),
            		"rate"=> number_format($row2["rate"],2),
            		"cost"=> number_format($row2["cost"],2),
            		"loc"=> $row2["loc"],
            		"stockdate"=> $row2["stockdate"]
            		
            	);
            $sl++;
        } 
        
        
    }
    
    else if($action=="rpt_customer_statement")
    {
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry1 = " and w.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
            $date_qry2 = " and o.`transdt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
        }else{
            $date_qry1 = "";
            $date_qry2 = "";
        }
        $filterorg = $_GET['filterorg']; if($filterorg == '') $filterorg = 0;
        
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select o.id, DATE_FORMAT( o.transdt,'%d/%b/%Y %H %i %s') transdt,'' transmode, '' `trans_ref`,'Opening Bal' descr,'' debit,'' credit,o.balance from organizationwallet o 
                                where (o.orgid= ".$filterorg." or ".$filterorg." = 0) $date_qry2 and id=(select max(id) from organizationwallet o1 where o1.orgid=o.orgid and `transdt`=o.transdt)
                                union all select w.id,w.`transdt`,m.name transmode,w.`trans_ref`,w.`remarks`,(case when w.dr_cr='C' then `amount` else 0 end ) cr_amt
                                ,(case when w.dr_cr='D' then `amount` else 0 end ) dr_amt,w.`balance`
                                from `organizationwallet` w left join organization o on w.orgid=o.id left join transmode m on w.`transmode`=m.id
                                where (w.orgid= ".$filterorg." or ".$filterorg." = 0) and w.dr_cr in('C','D') $date_qry1
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == "transdt"){
            $columnName = "o.transdt";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $totdebit = 0; $totcredit = 0; $totamount = 0; 
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           $totdebit += $row2["debit"];
           $totcredit += $row2["credit"];
           $totamount += $row2["balance"];
           
            $data[] = array(
                    "id"=> $sl,
                    "transdt"=> $row2["transdt"],
            		"transmode"=> $row2["transmode"],
            		"trans_ref"=> $row2["trans_ref"],
            		"descr"=> $row2["descr"],
            		"debit"=> number_format($row2["debit"],2),
            		"credit"=> number_format($row2["credit"],2),
            		"balance"=> number_format($row2["balance"],2),
            		
            		
            	);
            $sl++;
        } 
        
        
    }
    else if($action=="rpt_revenue")
    {
        
        $fd = $_GET["fd1"];
        $td = $_GET["td1"];
        
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,s.socode,o.name organization 
                                ,sum(d.qty*d.otc) otc,sum(d.qtymrc*d.mrc) mrc
                                ,(select  sum(`invoiceamt`) inv from invoice where soid=s.socode) rev
                                ,(select  sum(`paidamount`) inv from invoice where soid=s.socode) inc
                                ,(select  sum(`amount`)  from expense where soid=s.id) cost
                                FROM soitem s left join organization o on s.organization=o.id
                                left join soitemdetails d on s.socode=d.socode  group by s.id,s.socode,o.name 
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
            $columnSortOrder = 'DESC';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $mart=0;$otct=0;$mrct=0;$revt=0;$inct=0;$costt=0;$revt=0;$i=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $socode=$row2["socode"];$org=$row2["organization"];  $otc=$row2["otc"];$mrc=$row2["mrc"]; $rev=$row2["rev"]; $inc=$row2["inc"];$cost=$row2["cost"];
            $mar=$inc-$cost;$otct=$otct+$otc;$mrct=$mrct+$mrc;$inct=$inct+$inc;$costt=$costt+$cost;$mart=$mart+$mar;$revt=$revt+$rev;$i++;
           
            $data[] = array(
                    "id"=> $sl,
                    "socode"=> $socode,
            		"organization"=> $org,
            		"otc"=> number_format($otc,2),
            		"mrc"=> number_format($mrc,2),
            		"rev"=> number_format($rev,2),
            		"inc"=> number_format($inc,2),
            		"cost"=> number_format($cost,2),
            		"mar"=> number_format($mar,2),
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($otct,2));
        array_push($total, number_format($mrct,2));
        array_push($total, number_format($revt,2));
        array_push($total, number_format($inct,2));
        array_push($total, number_format($costt,2));
        array_push($total, number_format($mart,2));
        
    }
    else if($action=="rpt_so_customer")
    {
        
        $fdt = $_GET["dt_f"];
        $tdt = $_GET["dt_t"];
        
        if($fdt != ''){
            $date_qry = " and s.orderdate between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') ";
        }else{
            $date_qry = "";
        }
        
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and s.socode like  '%".$searchValue."%' or o.name like '%".$searchValue."%' or i.name like '%".$searchValue."%' ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,s.socode,o.name organization ,i.name product
                                ,(d.qty*d.otc) otc,(d.qtymrc*d.mrc) mrc
                                FROM soitem s left join organization o on s.organization=o.id
                                left join soitemdetails d on s.socode=d.socode
                                left join item i on d.productid=i.id 
                                WHERE 1=1
                                ".$date_qry;
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
            $columnSortOrder = 'DESC';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $mart=0;$otct=0;$mrct=0;$revt=0;$inct=0;$costt=0;$revt=0;$i=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $socode=$row2["socode"];$org=$row2["organization"];$product=$row2["product"];  $otc=$row2["otc"];$mrc=$row2["mrc"]; $rev=$row2["rev"]; $inc=$row2["inc"];$cost=$row2["cost"];
            $mar=$inc-$cost;$otct=$otct+$otc;$mrct=$mrct+$mrc;$inct=$inct+$inc;$costt=$costt+$cost;$mart=$mart+$mar;$revt=$revt+$rev;$i++;
           
            $data[] = array(
                    "id"=> $sl,
                    "socode"=> $socode,
            		"organization"=> $org,
            		"product"=> $product,
            		"otc"=> number_format($otc,2),
            		"mrc"=> number_format($mrc,2)
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($otct,2));
        array_push($total, number_format($mrct,2));
        
    }
     else if($action=="rpt_so_hold")
    {
        
        $fd = $_GET["fd1"];
        $td = $_GET["td1"];
        
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	//$searchQuery = " and (concat(e.`firstname`,e.`lastname`) like  '%".$searchValue."%' or a.`id` like '%".$searchValue."%' or h.`resourse_id` like '%".$searchValue."%' or  m.menuNm  like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT s.id,s.socode,o.name organization ,i.name product
                                ,(d.qty*d.otc) otc,(d.qtymrc*d.mrc) mrc
                                FROM soitem s left join organization o on s.organization=o.id
                                left join soitemdetails d on s.socode=d.socode
                                left join item i on d.productid=i.id 
                                where s.orderstatus=1
                                ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 's.id';
            $columnSortOrder = 'DESC';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $mart=0;$otct=0;$mrct=0;$revt=0;$inct=0;$costt=0;$revt=0;$i=0;
        while ($row2 = mysqli_fetch_assoc($empRecords)) {
           
            $socode=$row2["socode"];$org=$row2["organization"];$product=$row2["product"];  $otc=$row2["otc"];$mrc=$row2["mrc"]; $rev=$row2["rev"]; $inc=$row2["inc"];$cost=$row2["cost"];
            $mar=$inc-$cost;$otct=$otct+$otc;$mrct=$mrct+$mrc;$inct=$inct+$inc;$costt=$costt+$cost;$mart=$mart+$mar;$revt=$revt+$rev;$i++;
           
            $data[] = array(
                    "id"=> $sl,
                    "socode"=> $socode,
            		"organization"=> $org,
            		"product"=> $product,
            		"otc"=> number_format($otc,2),
            		"mrc"=> number_format($mrc,2)
            		
            	);
            $sl++;
        } 
        array_push($total, number_format($otct,2));
        array_push($total, number_format($mrct,2));
        
    }
    
    else if($action=="inventory_status")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        //$branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( i.code like  '%".$searchValue."%' or i.name  like '%".$searchValue."%' or c.name like '%".$searchValue."%' or i.barcode like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select i.code,i.name product,i.barcode,c.name catagory ,s.freeqty from stock s,item i,itmCat c
                                where i.id=s.product and i.catagory=c.id";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'i.name';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            $data[] = array(
                    "id"=> $sl,
            		"code"=> $row["code"],
            		"product"=> $row["product"],
            		"barcode"=> $row["barcode"],
            		"catagory"=> $row["catagory"],
            		"freeqty"=>$row["freeqty"],
            		
            	);
            $sl++;
        } 
    }
    
    else if($action=="inventory_value")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        //$branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( i.code like  '%".$searchValue."%' or i.name  like '%".$searchValue."%' or c.name like '%".$searchValue."%' 
        	                or i.barcode like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select i.code,i.name itmnm,c.name itmgrp,i.barcode itmcode,s.freeqty,s.costprice costperunit,s.freeqty*s.costprice totalcost 
                                from stock s,item i,itmCat c 
                                where i.id=s.product and i.catagory=c.id ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'i.name';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            $data[] = array(
                    "id"=> $sl,
            		"code"=> $row["code"],
            		"itmnm"=> $row["itmnm"],
            		"itmcode"=> $row["itmcode"],
            		"itmgrp"=> $row["itmgrp"],
            		"freeqty"=>$row["freeqty"],
            		"costperunit"=>number_format($row["costperunit"], 2),
            		"totalcost"=>number_format($row["totalcost"],2)
            		
            	);
            $sl++;
        } 
    }
    
    else if($action=="purchase_report")
    {
        
        $fdt= $_GET['dt_f'];

          $tdt= $_GET['dt_t'];
          
          if($fdt != ''){
                //$date_qry = " and i.`invoicedt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') "; 
                $date_qry = " and p.`makedt` between '$fdt' and '$tdt' "; 
            }else{
                $date_qry = "";
            }
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.poid like  '%".$searchValue."%' or p.voucher_no  like '%".$searchValue."%' or p.pi_no like '%".$searchValue."%' 
        	                or p.lc_tt_no like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no, p.lc_tt_date,p.payment_amount ,
                                sum(pi.com_invoice_val_bdt)com_invoice_val_bdt, sum(pi.freight_charges)freight_charges, sum(pi.global_taxes)global_taxes, 
                                sum(pi.cd)cd, sum(pi.rd)rd, sum(pi.sd)sd, sum(pi.vat)vat , sum(pi.tot_landed_cost)tot_landed_cost,sum(pi. tot_value)tot_value
                                from purchase_landing p,purchase_landing_item pi 
                                where p.id=pi.pu_id $date_qry";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery. " group by p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no,p.lc_tt_date,p.payment_amount");
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery."group by p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no,p.lc_tt_date,p.payment_amount";
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'p.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery."group by p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no,p.lc_tt_date,p.payment_amount order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        //echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            $data[] = array(
                    "id"=> $sl,
            		"containerno"=> $row["containerno"],
            		"poid"=> $row["poid"],
            		"voucher_no"=> $row["voucher_no"],
            		"pi_no"=> $row["pi_no"],
            		"pi_date"=>$row["pi_date"],
            		"lc_tt_no"=>$row["lc_tt_no"],
            		"lc_tt_date"=>$row["lc_tt_date"],
            		"payment_amount"=> number_format($row["payment_amount"], 2),
            		"com_invoice_val_bdt"=> number_format($row["com_invoice_val_bdt"], 2),
            		"freight_charges"=> number_format($row["freight_charges"], 2),
            		"global_taxes"=> number_format($row["global_taxes"], 2),
            		"cd"=>number_format($row["cd"], 2),
            		"rd"=>number_format($row["rd"], 2),
            		"sd"=> number_format($row["sd"], 2),
            		"vat"=> $row["vat"],
            		"tot_landed_cost"=> number_format($row["tot_landed_cost"], 2),
            		"tot_value"=> number_format($row["tot_value"], 2)
            		
            	);
            $sl++;
        } 
    }
    
    else if($action=="return_order_report")
    {
        
        $fdt= $_GET['dt_f'];

          $tdt= $_GET['dt_t'];
          
          if($fdt != ''){
                //$date_qry = " and i.`invoicedt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') "; 
                $date_qry = " and p.`makedt` between '$fdt' and '$tdt' "; 
            }else{
                $date_qry = "";
            }
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( i.name like  '%".$searchValue."%' or i.barcode  like '%".$searchValue."%' or b.name like '%".$searchValue."%' 
        	                or r.order_id like '%".$searchValue."%'  or r.ro_id like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT r.ro_id, r.order_id,rod.return_qty, i.name product, i.barcode, b.name warehouse, org.name customer, DATE_FORMAT(qu.orderdate,'%e/%c/%Y') orderdate
                                FROM `return_order` r LEFT JOIN return_order_details rod ON r.id=rod.ro_id LEFT JOIN qa_warehouse qaw ON rod.qaw_id=qaw.id 
                                LEFT JOIN qa q ON q.id=qaw.qa_id LEFT JOIN item i ON i.id=q.product_id LEFT JOIN branch b ON b.id=qaw.warehouse_id 
                                LEFT JOIN quotation qu ON qu.socode=r.order_id LEFT JOIN organization org ON org.id=qu.organization WHERE r.st = 2 ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'r.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        // echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            $data[] = array(
                    "id"=> $sl,
            		"ro_id"=> $row["ro_id"],
            		"order_id"=> $row["order_id"],
            		"orderdate"=> $row["orderdate"],
            		"customer"=> $row["customer"],
            		"product"=> $row["product"],
            		"barcode"=>$row["barcode"],
            		"warehouse"=>$row["warehouse"],
            		"return_qty"=>$row["return_qty"],
            		"status"=>"Accepted"
            		
            	);
            $sl++;
        } 
    }
    
    else if($action=="return_delivery_report")
    {
        
        $fdt= $_GET['dt_f'];

          $tdt= $_GET['dt_t'];
          
          if($fdt != ''){
                //$date_qry = " and i.`invoicedt` between DATE_FORMAT('$fdt', '%Y/%m/%d') and DATE_FORMAT('$tdt', '%Y/%m/%d') "; 
                $date_qry = " and p.`makedt` between '$fdt' and '$tdt' "; 
            }else{
                $date_qry = "";
            }
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( i.name like  '%".$searchValue."%' or i.barcode  like '%".$searchValue."%' or b.name like '%".$searchValue."%' 
        	                or r.order_id like '%".$searchValue."%'  or r.ro_id like '%".$searchValue."%' ) ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="SELECT d.order_id, d.do_id, i.name product, i.barcode, org.name customer, DATE_FORMAT(qu.orderdate,'%e/%c/%Y') orderdate, 
                                dod.returned_qty FROM `delivery_order_detail` dod 
                                LEFT JOIN delivery_order d ON d.id = dod.do_id LEFT JOIN qa_warehouse qaw ON qaw.id=dod.qa_id LEFT JOIN qa q ON q.id=qaw.qa_id 
                                LEFT JOIN item i ON i.id=q.product_id LEFT JOIN quotation qu ON qu.socode=d.order_id LEFT JOIN organization org ON org.id=qu.organization 
                                WHERE dod.`returned_qty` > 0 ";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
        
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'id'){
            $columnName = 'd.id';
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        // echo $empQuery;exit; 
        //exit;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        $sl = 1;
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            $data[] = array(
                    "id"=> $sl,
            		"do_id"=> $row["do_id"],
            		"order_id"=> $row["order_id"],
            		"orderdate"=> $row["orderdate"],
            		"customer"=> $row["customer"],
            		"product"=> $row["product"],
            		"barcode"=>$row["barcode"],
            		"returned_qty"=>$row["returned_qty"]
            		
            	);
            $sl++;
        } 
    }
    
    else if($action=="purchase_details")
    {
        
        $store = $_GET["store"]; if($store == '') $store = 0;
        //$branch = $_GET["branch"]; if($branch == '') $branch = 0;
        $cat = $_GET["cat"]; if($cat == '') $cat = 0;
        $bc1 = $_GET["barcode"];
        
        
        $searchQuery = " ";
        if($searchValue != ''){
        	$searchQuery = " and ( p.poid like  '%".$searchValue."%' or p.voucher_no  like '%".$searchValue."%' or p.pi_no like '%".$searchValue."%' 
        	                or p.lc_tt_no like '%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering   #c.`id`,
        
        $strwithoutsearchquery="select p.id,p.containerno, p.poid,p.voucher_no,p.pi_no,p.pi_date,p.lc_tt_no,p.lc_tt_date,p.payment_amount ,
                                pi.com_invoice_val_bdt com_invoice_val_bdt, pi.freight_charges freight_charges, pi.global_taxes global_taxes, pi.cd cd, pi.rd rd, 
                                pi.sd sd, pi.vat vat,pi.AT, pi.AIT AIT,  pi.tot_landed_cost tot_landed_cost,pi. tot_value tot_value
                                from purchase_landing p,purchase_landing_item pi 
                                where p.id=pi.pu_id";
            
        //echo $strwithoutsearchquery;die;
        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        
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
        $tcp=0;$tmp=0;
        while ($row = mysqli_fetch_assoc($empRecords)) {
           
            $data[] = array(
                    "id"=> $sl,
            		"containerno"=> $row["containerno"],
            		"poid"=> $row["poid"],
            		"voucher_no"=> $row["voucher_no"],
            		"pi_no"=> $row["pi_no"],
            		"pi_date"=>$row["pi_date"],
            		"lc_tt_no"=>$row["lc_tt_no"],
            		"lc_tt_date"=>$row["lc_tt_date"],
            		"payment_amount"=> number_format($row["payment_amount"], 2),
            		"com_invoice_val_bdt"=> number_format($row["com_invoice_val_bdt"], 2),
            		"freight_charges"=> number_format($row["freight_charges"], 2),
            		"global_taxes"=> number_format($row["global_taxes"], 2),
            		"cd"=>number_format($row["cd"], 2),
            		"rd"=>number_format($row["rd"], 2),
            		"sd"=>number_format($row["sd"], 2),
            		"vat"=> $row["vat"],
            		"tot_landed_cost"=> number_format($row["tot_landed_cost"], 2),
            		"tot_value"=> number_format($row["tot_value"], 2)
            		
            	);
            $sl++;
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
        "aaData" => $data,
        "total" => $total
    );
    
    echo json_encode($response);
}
