<?php
require "common/conn.php";

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
    $currSection = 'invoice';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/invoice.php?res=0&msg='Insert Data'&mod=2");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Invoice ')
                 ->setCellValue('C1', 'Year')
                ->setCellValue('D1', 'Month')
                ->setCellValue('E1', 'SOF')
    			->setCellValue('F1', 'Company')
                ->setCellValue('G1', 'Amount')
                ->setCellValue('H1', 'Paid ')
                ->setCellValue('I1', 'Due ')
                ->setCellValue('J1', 'Due Date')
                ->setCellValue('K1', 'Invoice Status')
                ->setCellValue('L1', 'Payment Status'); 

        $firststyle='A2';

        $qry="SELECT  1 sl,i.`invoiceno`, i.`invyr`, i.`invoicemonth`, i.`soid`, o.name `organization`, i.`invoiceamt` invoiceamt, format(i.`paidamount`,2)paidamount, format(i.`dueamount`,2)dueamount, i.`duedt`, s.`name`,s.`dclass` `invoiceSt`,p.`name` paySt,p.`dclass` `paymentSt`,o.balance orgbal,o.id orgid FROM `invoice` i  left join invoicestatus s  on i.invoiceSt=s.id left join invoicepaystatus p on i.paymentSt=p.id  left join organization o on i.organization=o.id where 1=1    order by i.`invoiceno` asc";
//WHERE  s.`status`<>6 order by s.`socode` asc"; 

       // echo  $qry;die;

        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut; $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['invoiceno'])
    			            ->setCellValue($col3, $row['invyr'] )
    						->setCellValue($col4,date('F', mktime(0, 0, 0, $row['invoicemonth'], 10)))
    					    ->setCellValue($col5, $row['soid'])
					        ->setCellValue($col6, $row['organization'])
					        ->setCellValue($col7, number_format($row['invoiceamt'],2))
				            ->setCellValue($col8, number_format($row['paidamount'],2))
    						->setCellValue($col9, number_format($row['dueamount'],2))
    					    ->setCellValue($col10, $row['duedt'])
					        ->setCellValue($col11, $row['name'])
				            ->setCellValue($col12, $row['paySt']);	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Invoice');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'invoicelist_'.$today.'.xls'; 

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
    
    if ( isset( $_POST['pdf'] ) ) 
    {
        
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
        <span>Invoice</span>
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
            <div class="col-lg-12 col-xs-11">
            
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            
              <!--h1 class="page-title">Customers</a></h1-->
              <p>
              <!-- START PLACING YOUR CONTENT HERE -->
    
    
              <div class="panel panel-info">
      		
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
     
                	<form method="post" action="invoiceList.php" id="form1">
                         <div class="well list-top-controls"> 
                                  <div class="row border">
                                      
                                        <div class="col-sm-3 text-nowrap">
                                                <h6>Billing <i class="fa fa-angle-right"></i>All Invoice</h6>
                                                
            							</div>		      
                                          
                                   
                                   
                                  
                                    <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                                    <div class="col-sm-9 pull-right">
                                        <!-- pull-right up n down magic here-->
                                        
                                        <div class=" pull-right invoice text-nowrap"> 
            										 
                                                        <span id="icon-inp"> <input class="btn btn-default" type="submit" name="export" value="" id="export"  > </span>
                                                         </div>
                                                         	<div class="  pull-right col-lg-4 col-md-4 col-sm-4">
            											<div class="input-group">
            												<input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt;?>"  >
            												<div class="input-group-addon">
            													<span class="glyphicon glyphicon-th"></span>
            												</div>
            											</div>     
            										</div> 
            											<div class="  pull-right col-lg-4 col-md-4  col-sm-4">
            											<div class="input-group">
            												<input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt;?>" >
            												<div class="input-group-addon">
            													<span class="glyphicon glyphicon-th"></span>
            												</div>
            											</div>     
            										</div>
            									</div>	
                                    
                                  </div>
                                </div>
    				</form>
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    
                    <!-- Table -->
					
                    
					<table id="listTable" class="table display dataTable no-footer actionbtns" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;">
						
					<!--table id="listTable" class="display dataTable no-footer actio nbtn" width="100%" role="grid" aria-describedby="listTable_info" style="width: 100%;"-->
                        <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Invoice</th>
                            <th>Year </th>
                            <th>Month </th>
                            <th>SOF</th>
                            <th>Company</th>
                            <th>Amount</th>
                            <th>Paid</th>
                            <th>Due</th>
                            <th>Due Date </th>
                            <th>Invoice Status </th>
                            <th>Payment Status </th>
							<th width="1%"><span>Action</span></th>
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
   
    <div id = "divBackground" style="position: fixed; z-index: 999; height: 100%; width: 100%; top: 0; left:0; background-color: Black; filter: alpha(opacity=60); opacity: 0.6; -moz-opacity: 0.8;display:none">

    </div>
    
    
    
    <!-- /#page-content-wrapper -->
    
    <?php
        include_once('common_footer.php');
    ?>
    
  <style>

.invpay-form{
  width: 330px;

}

  </style>
    
     <!--inv Modal view-->  
     



     
<div class="autoModal modal fade" id="invpay-modal">
  <div class="modal-dialog invpay-form" role="document">
    <div class="modal-content bg-gray">
      
      <div class="modal-body inv-modal-body">

        Loading...
        
      </div>
      <!--model body--> 
    </div>
  </div>
</div>
 <!--end inv Modal view-->    
  <script>
  
  window.closeModal = function(){
    $('#invpay-modal').modal('hide');
};




  </script>
    
    
    
    
    
     <!-- Datatable JS -->       
		<script src="js/plugins/datagrid/datatables.min.js"></script>
        
        <!-- Script -->
        <script>
        $(document).ready(function(){
           var table1 = $('#listTable').DataTable({
                processing: true,
				fixedHeader: true,
                serverSide: true,
                serverMethod: 'post',
				pageLength: 50,
				scrollX: true,
				bScrollInfinite: true,
				bScrollCollapse: true,
				/*scrollY: 550,*/
				deferRender: true,
				scroller: true,	
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=invoice'
                },
                'columns': [
                    { data: 'sl', "orderable": false , "class":"action"  },
                    { data: 'invoiceno' },
                    { data: 'invyr' },
                    { data: 'invoicemonth' },
                    { data: 'soid' },
                    { data: 'organization' },
					{ data: 'invoiceamt' },
                    { data: 'paidamount' },
                    { data: 'dueamount' },
                	{ data: 'duedt' },
            		{ data: 'invoiceSt' },
            		{ data: 'paymentSt' },
					{ data: 'edit', "orderable": false , "class":"action" }
                ]
            });
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
        });
		
	
		
        </script>  
        <script type="text/javascript">
    
    function openpopup(popurl){
       var popUpObj;
    popUpObj=window.open(popurl,"ModalPopUp","toolbar=no," +"scrollbars=no," + "location=no," + "statusbar=no," + "menubar=no," + "resizable=0," + "modal=yes,"+
    "width=400," +"height=310," + "left = 290," +"top=200"  );
    popUpObj.focus();
   // LoadModalDiv();
    
    
    }
    </script>
    
    </body>
    
    
    
    </html>
  <?php }?>    
