<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
     $acm= $_GET['accm'];$yr= $_GET['yer'];$mn= $_GET['mnt'];$itct= $_GET['itid'];
    // echo $acm;die;
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpttaracv';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/hr.php?res=0&msg='Insert Data'");
    }
    if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Year.')
                ->setCellValue('B1', 'Month.')
                ->setCellValue('C1', 'Account manager')
                ->setCellValue('D1', 'SOF')
                ->setCellValue('E1', 'Organization')
                ->setCellValue('F1', 'Effective Date')
                ->setCellValue('G1', 'Item')
                ->setCellValue('H1', 'Catagory')
                ->setCellValue('I1', 'Currency')
                ->setCellValue('J1', 'OTC Qty')
                ->setCellValue('K1', 'OTC Amount')
                ->setCellValue('L1', 'MRC')
                ->setCellValue('M1', 'MRC Amount')
                ->setCellValue('N1', 'Previous Achievment')
    			->setCellValue('O1', 'Achievement'); 
    			
        $firststyle='A2';
        $qry="select '".$yr."' yr, monthname(str_to_date(".$mn.",'%m')) mn,'".$acm."' acm,s.socode,org.name organization,s.effectivedate,i.name item,ct.name ctnm, cr.shnm currency
                ,d.qty,d.otc,d.qtymrc,d.mrc ,((ifnull(d.qty,0)*ifnull(d.otc,0))+(ifnull(d.qtymrc,0)*ifnull(d.mrc,0))) acv
                ,(ifnull((select sum((ifnull(d1.qty,0)*ifnull(d1.otc,0))+(ifnull(d1.qtymrc,0)*ifnull(d1.mrc,0))) from soitemdetails d1 where d1.socode=s.oldsocode and d1.productid=d.productid),0))p_acv
                from soitem s left join soitemdetails d on s.socode=d.socode 
	                left join organization org  on s.organization=org.id
                    left join item i on d.productid=i.id
                    left join currency cr on d.currency=cr.id
                    left join itmCat ct on i.catagory=ct.id 
                where DATE_FORMAT(s.effectivedate,'%Y')=".$yr." 
                and  convert(DATE_FORMAT(s.effectivedate,'%m'),UNSIGNED)='".$mn."'
                and ct.id=".$itct."
                and s.organization in 
                (
                	select org.id from organization org where org.salesperson in
                	(
                 		select h.id from hr h where h.hrName ='".$acm."'   
                	)
                ) "; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $acvp=$row['acv']/$row['target']*100;
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $row['yr'])
    			            ->setCellValue($col2, $row['mn'])
    						->setCellValue($col3, $row['acm'])
    					    ->setCellValue($col4, $row['socode'])
    					    ->setCellValue($col5, $row['organization']) 
					        ->setCellValue($col6, $row['effectivedate']) 
					        ->setCellValue($col7, $row['item'])
					        ->setCellValue($col8, $row['ctnm'])
    					    ->setCellValue($col9, $row['currency']) 
					        ->setCellValue($col10, $row['qty']) 
					        ->setCellValue($col11, $row['otc'])
					        ->setCellValue($col12, $row['qtymrc'])
					        ->setCellValue($col13, $row['mrc'])
					        ->setCellValue($col14, $row['p_acv'])
					        ->setCellValue($col15, $row['acv']-$row['p_acv']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Target_Achievment_Details');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'tar_acv_det'.$today.'.xls'; 
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
      			<div class="panel-heading"><h1>All Item</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="rpt_accmgr_acv_detail.php?accm=<?php echo $acm ?>&yer=<?php echo $yr ?>&mnt=<?php echo $mn ?>&itid=<?php echo $itct ?>" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-6 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-6">

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
                    </div>
    
    				</form>
                    
 <link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div cl ass="table-responsive">
                    <!-- Table -->
                    <table id='crmActivityTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Account Manager</th>
                            <th>SOF</th>
                            <th>Organization</th>
                            <th>Efective Date</th>
                            <th>Item</th>
                            <th>Item Catagory</th>
                             <th>Currency</th>
                            <th>OTC Qty</th>
                            <th>OTC Amount</th>
                            <th>MRC Qty</th>
                            <th>MRC Amount</th>
                            <th>Previous Achievment</th>
                            <th>Achievment Amount</th>
                        </tr>
                        </thead>
                        
                    </table>
                </div>
    
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
    
        <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>



        
        <!-- Script -->
        <script>
        $(document).ready(function(){
            $('#crmActivityTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 25,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,	
                'ajax': {
                    'url':'phpajax/datagrid_report.php?action=accmgr_acv&accm=<?php echo $acm ?>&yer=<?php echo $yr ?>&mnt=<?php echo $mn ?>&ct=<?php echo $itct ?>&mod=2'
                },
                'columns': [
                    { data: 'yr' },
                    { data: 'mnth' },
                    { data: 'accmgr' },
                    { data: 'socode' },
                    { data: 'organization' },
                    { data: 'effectivedate' },
                    { data: 'item' },
                    { data: 'ctnm' },
                    { data: 'currency' },
                    { data: 'qty' },
                    { data: 'otc' },
                    { data: 'qtymrc' },
					{ data: 'mrc' },
					{ data: 'pacv' },
					{ data: 'acv' },
                ]
            });
        });
		
		

		
        </script>    
    
    </body></html>
  <?php }?>    
