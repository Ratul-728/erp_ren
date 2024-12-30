<?php
require "common/conn.php";

$fromdt='2015-01-01';
$todt = '2020-12-31';

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
    $currSection = 'rpt_sof';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/rpt_sof.php?res=0&msg='Insert Data'");
    }
    if ( isset( $_POST['export'] ) )
    {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Contact Type')
                ->setCellValue('B1', 'Customer')
                ->setCellValue('C1', 'Account Manager')
                ->setCellValue('D1', 'Item')
                ->setCellValue('E1', 'Item Catagory')
                ->setCellValue('F1', 'Company Type')
                ->setCellValue('G1', 'License Type')
                ->setCellValue('H1', 'Organization')
    			->setCellValue('I1', 'SO Code')
    			 ->setCellValue('J1', 'Effective Date')
                ->setCellValue('K1', 'MRC')
                 ->setCellValue('L1', 'OTC')
                 ->setCellValue('M1', 'Stage')
                 ->setCellValue('N1', 'Probability')
                 ->setCellValue('O1', 'Status')
                 ->setCellValue('P1', 'POC'); 
    			
        $firststyle='A2';
        $qry="SELECT a.`socode`,'Customer' contType ,d.`name`  cus_nm, a.`effectivedate` orderdate, org.salesperson `hrid` ,concat(em.firstname,' ',em.lastname) `hrName` ,c.`name` itmnm,round((IFNULL(b.`qty`,0)*IFNULL(b.`otc`,0)),2) otc
,round((IFNULL(b.`mrc`,0)*IFNULL(`qtymrc`,0)),2) mrc,'Order Placed' stage,'100%' prob ,f.`name` itm_cat
,c.size,g.`name` pattern,org.`name`  orgn , concat(e1.firstname,'',e1.lastname) `poc` FROM `soitem` a left join `soitemdetails` b on a.`socode`=b.`socode` left join `item` c on b.`productid`=c.`id` left join `contact` d on a.`customer`=d.`id`   left join `itmCat` f  on c.`catagory`=f.`id`   
left join `pattern` g on c.`pattern`=g.`id`left join organization org on a.`organization`=org.`id`
left join `hr` e on org.`salesperson`=e.`id`  left join employee em on e.`emp_id`=em.`employeecode`
left join `hr` h1 on a.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
where  (a.terminationDate>sysdate() or a.terminationDate is null)"; 
       // echo  $qry;die;
       //s.`socode`='ANTGR003' and  
     
      
       
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;
                $i++; 
                    $objPHPExcel->setActiveSheetIndex(0)
        			            ->setCellValue($col1, $row['contType'])
        						->setCellValue($col2, $row['cus_nm'])
    							->setCellValue($col3, $row['hrName'])
        					    ->setCellValue($col4, $row['itmnm'])
        					    ->setCellValue($col5, $row['itm_cat']) 
        					     ->setCellValue($col6, $row['size'])
        					      ->setCellValue($col7, $row['pattern'])
        					      ->setCellValue($col8, $row['orgn'])
        					     ->setCellValue($col9, $row['socode'])
        					     ->setCellValue($col10, $row['orderdate'])
        					    ->setCellValue($col11,$row['mrc'])
        					    ->setCellValue($col12, $row['otc'])
        					    ->setCellValue($col13, $row['stage'])
        					    ->setCellValue($col14, $row['Probability'])
        					    ->setCellValue($col15, $row['stat'])
        					    ->setCellValue($col15, $row['poc']);	/* */
        			$laststyle=$title;	
                }
            }
      
        $objPHPExcel->getActiveSheet()->setTitle('sales_forcast');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'sales_data_'.$today.'.xls'; 
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
<?php  include_once('common_header.php');?>

<body class="form">
    <?php  include_once('common_top_body.php');?>
    <div id="wrapper"> 
    <!-- Sidebar -->
        <div id="sidebar-wrapper" class="mCustomScrollbar">
            <div class="section">
  	            <i class="fa fa-group  icon"></i>
                <span>Contact</span>
            </div>
            <?php  include_once('menu.php');?>
   	        <div style="height:54px;"></div>
        </div>
  <!-- END #sidebar-wrapper --> 
  <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid pagetop">
                <div class="row">
                    <div class="col-lg-12">
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                        <p>
                    <!-- START PLACING YOUR CONTENT HERE -->
          
       <!--	<form method="post" action="common/addcomntdetails.php" onsubmit="javascript:return WebForm_OnSubmit();" id="form1">  -->   
                        <form method="post"   id="comnform">
                            <div class="panel mother-panel panel-info">
  			                    <div class="panel-heading">
            		                <h1>&nbsp;&nbsp;Contacts <i class="fa fa-angle-right"></i><?php echo $name;?> </h1>
            		                <input type="hidden"  name="cdid" id="cdid" value="<?php echo $cid;?>">
            		                <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
                                </div>
				                <div class="panel-body">
                                    <span class="alertmsg"></span>
                                    <div class="row dashboard-filter">
                                        <div class="col-xs-12">
                                            <div class="inner-tab-top">
                                                <div class="inner-tab-wrapper">       
                                                    <div class="row">               
                                            		    <div class="col-xs-12 col-lg-8">
                                                            <ul class="inner-tabs">
                                                                <li><a href="contactDetail.php?id=<?=$id?>&mod=2"><i class="fa fa-comments-o"></i><span class="inner-tabs-title">General</span></a></li>
                                                                <li><a href="#"><i class="fa fa-file-text-o"></i><span class="inner-tabs-title">Invoices</span></a></li>
                                                                <li class="active"><a href="contactOrderHistory.php?id=<?=$id?>&mod=2"><i class="fa fa-shopping-basket"></i><span class="inner-tabs-title">Orders<span class="hidden-md hidden-xs">History</span></span></a></li>
                                                                <li><a href="#"><i class="fa fa-dollar"></i><span class="inner-tabs-title">Payment<span class="hidden-md hidden-xs">History</span></span></a></li>
                                                                <li><a href="#"><i class="fa fa-user"></i><span class="inner-tabs-title">Profiles</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                	<?php 
														
														if(!$fdt){
														$fdt = $dateBehind;
													 	$tdt = $dateAhead;
														}
													 ?>
													
                                                <div class="tab-calendar">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-4 co l-lg-offset-7   sm-text-right md-text-right">
                                                            <label>Filter</label>
                                                        </div>                            	    
                                                        <div class="col-lg-5 col-md-5  col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt;?>" >
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>     
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-sm-4">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt;?>"  >
                                                                <div class="input-group-addon">
                                                                    <span class="glyphicon glyphicon-th"></span>
                                                                </div>
                                                            </div>     
                                                        </div>  
                                                    </div>
                                                </div>
                                            </div>  
                                            <br> 
                                        </div> 
                                    </div> 
                                    <div class="row  b">
    	                                <div class="col-lg-12">
											
											<!-- contact order history -->
											
											
<style>
.dataTables_wrapper{
    border-top:0;
    margin-top: -5px;
}											
</style>
											
 <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div class="table-responsive" >
                    <!-- Table -->
                    <table id='crmActivityTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>Contact Type</th>
                            <th>Customer</th>
                            <th>Account Manager</th>
                            <th>Item</th>
                            <th>Item Catagory</th>
                            <th>Company Type</th>
                            <th>Linecse Type</th>
                            <th>Organization</th>
                            <th>SO Code</th>
                            <th>POC</th>
                            <th>Effective Date</th>
                            <th>MRC</th>
                            <th>OTC</th>
                            <th>Stage</th>
                            <th>Probability</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        
                    </table>
                </div>											
											
					

											
											<!-- end contact order history -->
                                        </div>
        
                                        
                                    </div>
                                </div>
                            </div> 
                        </form>
        <!-- /#end of panel --> 
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- /#page-content-wrapper -->
<?php    include_once('common_footer.php');?>

        <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>
	
	
	
	
    <!-- Script -->
        <script>
        $(document).ready(function(){
            $('#crmActivityTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/activity_grid.php?action=order'
                },
                'columns': [
                    { data: 'contType' },
                    { data: 'cus_nm' },
                    { data: 'hrName' },
                    { data: 'itmnm' },
                    { data: 'itm_cat' },
					{ data: 'size' },
				    { data: 'pattern' },
                    { data: 'orgn' },
                    { data: 'socode' },
                    { data: 'poc' },
                    { data: 'orderdate' },
                    { data: 'pmrc' },
                    { data: 'otcvalue' },
					{ data: 'stage' },
				    { data: 'prob' },
                    { data: 'stat' }
                ]
            });
        });
		
		

		
        </script>   
	

</body>
</html>

<?php }?>