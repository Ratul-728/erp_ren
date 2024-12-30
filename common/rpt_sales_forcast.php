<?php
require "common/conn.php";
$pgcnt= $_GET['pg'];
$limitst=($pgcnt-1)*150;
$limitnd=150;
$fromdt='2015-01-01';
$todt = '2020-12-31';
$smn= date("m", strtotime($fromdt));
$lmn= date("m", strtotime($todt));
$lyr= date("Y", strtotime($todt));
//echo $lyr;die;
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'salesforcast';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/rpt_sales_forcast.php?res=0&msg='Insert Data'");
    }
    if ( isset( $_POST['export'] ) )
    {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Month.')
                ->setCellValue('B1', 'Contact Type')
                ->setCellValue('C1', 'Customer')
                ->setCellValue('D1', 'Account Manager')
                ->setCellValue('E1', 'Item')
                ->setCellValue('F1', 'Item Catagory')
                ->setCellValue('G1', 'Company Type')
                ->setCellValue('H1', 'License Type')
                ->setCellValue('I1', 'Organization')
    			->setCellValue('J1', 'SO Code')
    			 ->setCellValue('K1', 'Effective Date')
                ->setCellValue('L1', 'MRC')
                 ->setCellValue('M1', 'OTC')
                 ->setCellValue('N1', 'Stage')
                 ->setCellValue('O1', 'Probability')
                 ->setCellValue('P1', 'Status')
                 ->setCellValue('Q1', 'Forcast'); 
    			
        $firststyle='A2';
        $qry="SELECT s.`socode`, s.`contType`, s.`cus_id`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`da`, s.`hrid`, s.`hrName`, s.`itmid`, s.`itmnm`, s.`otc`, s.`mrc`, s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`
,(case when r.yr=s.yr and r.month=s.mnth then 'New' Else 'Existing' end ) stat
,(case when r.yr=s.yr and r.month=s.mnth then round((s.`mrc`*(r.dy-s.`da`))/r.dy,2) Else s.`mrc` end ) pmrc
,(case when r.yr=s.yr and r.month=s.mnth then round(s.`otc`,2) Else 0 end ) otcvalue
,(case when r.`dt`>sysdate() then 'Forcast' else 'Actual' end) frcst
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE s.`socode`='ANTGR003' 
and  ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))"; 
       // echo  $qry;die;
       
     
      
       
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;
                $i++; 
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $row['dt'])
        			            ->setCellValue($col2, $row['contType'])
        						->setCellValue($col3, $row['cus_nm'])
    							->setCellValue($col4, $row['hrName'])
        					    ->setCellValue($col5, $row['itmnm'])
        					    ->setCellValue($col6, $row['itm_cat']) 
        					     ->setCellValue($col7, $row['size'])
        					      ->setCellValue($col8, $row['pattern'])
        					      ->setCellValue($col9, $row['orgn'])
        					     ->setCellValue($col10, $row['socode'])
        					     ->setCellValue($col11, $row['orderdate'])
        					    ->setCellValue($col12,$row['pmrc'])
        					    ->setCellValue($col13, $row['otcvalue'])
        					    ->setCellValue($col14, $row['stage'])
        					    ->setCellValue($col15, $row['Probability'])
        					    ->setCellValue($col16, $row['stat'])
        					    ->setCellValue($col17, $row['frcst']);	/* */
        			$laststyle=$title;	
                }
            }
      
        $objPHPExcel->getActiveSheet()->setTitle('sales_forcast');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'sales_forcast_'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileNm));
        ob_clean();
        flush();
        readfile($fileNm);
        exit;
    }
    
    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Item</span>
      </div>
      
    <?php
        include_once('menu.php');
    ?>
      
      	<div style="height:54px;">
    	</div>
      </div>
    
      <!-- END #sidebar-wrapper --> 
      
      <!-- Page Content -->
      <div id="page-content-wrapper">
        <div class="container-fluid xyz">
          <div class="row">
            <div class="col-lg-12">
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
    
    
              <div class="panel panel-info">
      			<div class="panel-heading"><h1>All Sales</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="rpt_sales_forcast.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                         <!-- <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"> -->
                        </div>
                      </div>
                    </div>
                    
                    <div class="table-responsive filterable">
                        <table  class="table table-grid table-striped table-hover">
                                <thead>
                                     <tr class="filters">
                                        <th><a href="#">Month</a></th>
                                        <th><a href="#">Contact Type</a></th>
                                        <th><a href="#">Customer</a></th>
                                        <th><a href="#">Account Manager</a></th>
                                        <th><a href="#">Item</a></th>
                                        <th><a href="#">Item Catagory</a></th>
                                        <th><a href="#">Company Type</a></th>
                                        <th><a href="#">Linecse Type</a></th>
                                        <th><a href="#">Organization</a></th>
                                        <th><a href="#">SO Code</a></th>
                                        <th><a href="#">Effective Date</a></th>  
                                        <th><a href="#">MRC</a></th>
                                        <th><a href="#">OTC</a></th>
                                        <th><a href="#">Probability</a></th>
                                        <th><a href="#">Status</a></th>
                                        <th><a href="#">Forcast</a></th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>       
                           
    <?php 
    
    $qry="SELECT s.`socode`, s.`contType`, s.`cus_id`, s.`cus_nm`, s.`orderdate`, s.`yr`, s.`mnth`, s.`da`, s.`hrid`, s.`hrName`, s.`itmid`, s.`itmnm`, s.`otc`, s.`mrc`
    , s.`stage`, s.`prob`, s.`itm_cat`, s.`size`, s.`pattern`, s.`orgn`,r.yr,r.month,r.dy,r.`dt`
,(case when r.yr=s.yr and r.month=s.mnth then 'New' Else 'Existing' end ) stat
,(case when r.yr=s.yr and r.month=s.mnth then round((s.`mrc`*(r.dy-s.`da`))/r.dy,2) Else s.`mrc` end ) pmrc
,(case when r.yr=s.yr and r.month=s.mnth then round(s.`otc`,2) Else 0 end ) otcvalue
,(case when r.`dt`>sysdate() then 'Forcast' else 'Actual' end) frcst
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE s.`socode`='ANTGR003' 
and  ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))
order by s.`hrid`,s.`socode`,r.`dt` asc LIMIT ".$limitst. ",".$limitnd;
    $sl=0;
   // echo $qry; die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    else
    {
           $inputData = array(
               'socode' => '',
               'contType' => '',
               'cus_nm' => '',
               'orderdate' => '',
               'hrName' => '',
               'itmnm' => '',
               'org' => '',
               'itm_cat' => '',
               'size' => '',
               'pattern' => '', 
               'otc' => '',
               'mrc' => '',
               'stage' => '',
               'prob' => ''
               );      
    
    
        $dbRows = 0;
        $today=date('m');$todayy=date('Y');//$stat='Existing';$forcast='Actual';
       $result = $conn->query($qry); 
       if ($result->num_rows > 0) {
          //$earliest_month='07';$latest_month='11';
            $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                ?>
                    <tr>
                        <td><?php echo  $row["dt"];?></td>
                        <td><?php echo $row["contType"]?></td>
                        <td><?php echo $row["cus_nm"];?></td>
                        <td><?php echo $row["hrName"];?></td>
                        <td><?php echo $row["itmnm"];?></td>
                        <td><?php echo $row["itm_cat"];?></td>
                        <td><?php echo $row["size"];?></td>
                         <td><?php echo $row["pattern"];?></td>
                        <td><?php echo $row["org"];?></td>
                        <td><?php echo $row["socode"];?></td>
                        <td><?php echo $row["efdt"];?></td>
                        <td><?php echo $row["pmrc"];?></td>
                         <td><?php echo $row["otcvalue"];?></td>
                        <td><?php echo $row["stage"];?></td>
                        <td><?php echo $row["prob"];?></td>
                        <td><?php echo $row["stat"];?></td>
                        <td><?php echo $row["frcst"];?></td>
                        <td></td> 
                    </tr>
                              
    <?php
               } } 
    
    		    $dbCols = 0;
        		foreach($inputData as $key => $value)
        		{
        			$data[$dbRows][$key] = $row[$key];
        			$dbCols++;
        		}
        		$dbRows++;
            }
    
    ?>
                       
                        </tbody>
                    </table>
                </div>
    
    
    <?php
        include_once('pagination.php');
        $nrows=$result->num_rows;
        if($nrows<150){$maxrows=$nrows;}
        else{$maxrows=150;}
        $npg=floor($nrows/150);
    ?>
                    <div class="pull-left">
                        Showing <?echo $limitst;?> to <?php echo $maxrows+$limitst; ?> of <?=$nrows->num_rows?> entries
                        
                        <?php
                        $conn->close();
                        ?>
                        
                    </div>
                    <div class="pull-right">
                        <ul class="pagination " style="border: 0px solid #000000; margin-top: 0px;">
                             <?php  if($pgcnt>$npg){ ?>
                            <li id="datatable3_previous" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="#">Previous</a></li>
                        <?php } else {?>
                            <li id="datatable3_previous" class="paginate_button next"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="rptsalesforcast.php?pg="<?php echo $pgcnt-1;?>"">Previous</a></li>
                        <?php } ?>
                        <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="rptsalesforcast.php?pg=1">1</a></li>
                        <?php for($i=2;$i<=$npg;$i++){ ?>
                        <li class="paginate_button <?php if ($pgcnt==$i){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="rptsalesforcast.php?pg="<?php echo $i;?>""><?php echo $i;?></a></li>
                        <?php } if($pgcnt<$npg){ ?>
                            <li id="datatable3_next" class="paginate_button next"><a tabindex="0" data-dt-idx="16" aria-controls="datatable3" href="rptsalesforcast.php?pg="<?php echo $pgcnt+1;?>"">Next</a></li>
                        <?php } else {?>
                            <li id="datatable3_next" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="16" aria-controls="datatable3" href="#">Next</a></li>
                        <?php } ?>
                        </ul>
                    </div>
    
    				</form>
    
                 </div>
            </div> 
            <!-- /#end of panel -->  
    
              <!-- START PLACING YOUR CONTENT HERE -->          
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /#page-content-wrapper -->
    
    <?php
        include_once('common_footer.php');
    ?>
    
    </body></html>
  <?php }?>    
