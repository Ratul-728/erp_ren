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
    if($action=="sales_forcast")
    {
        $qry = "CALL psp_salse_forcast(1)";
        $result1 = $con->query($qry);
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (r.dt like '%".$searchValue."%' or s.`contType` like '%".$searchValue."%' or  s.`cus_nm` like'%".$searchValue."%' 
        	or  s.`hrName` like'%".$searchValue."%'  or s.`itmnm` like'%".$searchValue."%'  or  s.`itm_cat` like'%".$searchValue."%'  or  s.`size` like'%".$searchValue."%'
        	or  s.`pattern` like'%".$searchValue."%'  or s.`orgn` like'%".$searchValue."%'  or  s.`socode` like'%".$searchValue."%'  or  s.`orderdate` like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT s.`socode`, s.`contType`, s.`cus_id`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`da`, s.`hrid`, s.`hrName`, s.`itmid`, s.`itmnm`, s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`
,(case when r.yr=s.yr and r.month=s.mnth then 'New' Else 'Existing' end ) stat
,(case when r.yr=s.yr and r.month=s.mnth then round((s.`mrc`*(r.dy-s.`da`+1))/r.dy,2) Else s.`mrc` end ) pmrc
,(case when r.yr=s.yr and r.month=s.mnth then round(s.`otc`,2) Else 0 end ) otcvalue
,(case when r.`dt`>sysdate() then 'Forcast' else 'Actual' end) frcst
,s.poc,s.currnm
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ";
        
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
            		"dt"=>$row['dt'],
        			"contType"=>$row['contType'],
            		"cus_nm"=>$row['cus_nm'],
            		"hrName"=>$row['hrName'],
            		"poc"=>$row['poc'],
            		"itmnm"=>$row['itmnm'],
            		"itm_cat"=>$row['itm_cat'],
            		"size"=>$row['size'],
        			"pattern"=>$row['pattern'],
            		"orgn"=>$row['orgn'],
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
    if($action=="rpt_sof")
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
        
        $strwithoutsearchquery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
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
        $strwithsearchquery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
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
        
        
         $empQuery="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,cr.shnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
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
    if($action=="terminat_so")
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
        
        $strwithoutsearchquery="SELECT DATE_FORMAT(s.`terminationDate`, '%d-%m-%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d-%m-%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc 
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
        $strwithsearchquery="SELECT DATE_FORMAT(s.`terminationDate`, '%d-%m-%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d-%m-%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc 
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
        
        
         $empQuery="SELECT DATE_FORMAT(s.`terminationDate`, '%d-%m-%Y') tdt,c.name `terminationcause`,h.hrName,i.name itmnm
,ic.name itmcat,p.name comtp,i.size ,o.name ornm,s.socode,DATE_FORMAT(s.effectivedate , '%d-%m-%Y') efdt,cr.shnm,round(d.otc,2) otc,round(d.mrc,2) mrc 
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
    if($action=="deal_forcast")
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
        
        $strwithoutsearchquery="SELECT s.`name` socode, s.`contType`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`dy` da, s.`hrName`, s.`itmnm`
        , s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`,s.`st` stat
,round(s.`mrc`,2) pmrc,round(s.`otc`,2) otcvalue,'Forcast'  frcst
,s.`scale`,round(s.`probability`,0)probability
,round(((s.`otc`+s.`mrc`)*s.probability/100),2) revenue,s.currency
FROM  `rpt_sales_deal` s  ,`reportmanth` r  
WHERE   ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ";
        
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
    if($action=="accmgr_acv")
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
    if($action=="orgwalllist")
    {
        ## Search 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (o.`name` like '%".$searchValue."%' or i.`name` like '%".$searchValue."%' or o.`contactno` like'%".$searchValue."%' 
        	or  o.`email` like'%".$searchValue."%'  or o.`website` like'%".$searchValue."%'  or concat(e.firstname,'',e.lastname) like'%".$searchValue."%'
        	or  o.balance like'%".$searchValue."%') ";
        }
        
        ## Total number of records without filtering
        
        $strwithoutsearchquery="SELECT o.`id`,o.`name`,i.`name` `industry`,o.`contactno`,o.`email`,o.`website`
,concat(e.firstname,'',e.lastname) accmgr,o.balance
FROM organization o left join businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`
left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` where 1=1 ";
        
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
          $dtl="organazionwallet.php?orgid=".$row['id']."&mod=3";
            $data[] = array(
            		"name"=>'<a href="'. $dtl.'">'.$row['name'].'</a>',
            		"contactno"=>$row['contactno'],
            		"email"=>$row['email'],
            		"website"=>$row['website'],
            		"accmgr"=>$row['accmgr'],
            		"balance"=>$row['balance']
            	);
        		
        }
    }
    if($action=="orgwall")
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
        
        $strwithoutsearchquery="SELECT w.`id`, w.`transdt`,o.name org,m.name `transmode`, (case w.`dr_cr` when 'd' then 'Debit' else 'Credit' end) drcr, w.`trans_ref`, w.`amount`, w.`remarks` FROM `organizationwallet` w left join organization o on w.`orgid`=o.id left join transmode m on w.`transmode`=m.id
where w.`orgid`='".$org_id."'  ";
        
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
            		"transdt"=>$row['transdt'],
        			"org"=>$row['org'],
            		"drcr"=>$row['drcr'],
            		"transmode"=>$row['transmode'],
            		"trans_ref"=>$row['trans_ref'],
            		"remarks"=>$row['remarks'],
            		"amount"=>$row['amount'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>'
            	);
        		
        }
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
