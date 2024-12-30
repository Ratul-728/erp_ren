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
    $currSection = 'po';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/po.php?res=0&msg='Insert Data'&mod=3");
    }
    if ( isset( $_POST['export'] ) ) 
    {
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'PO NO')
                ->setCellValue('C1', 'SUPPLIER')
                ->setCellValue('D1', 'ORDER DATE')
    			->setCellValue('E1', 'TOTAL AMOUNT')
    			 ->setCellValue('F1', 'VAT')
                ->setCellValue('G1', 'TAX')
                 ->setCellValue('H1', 'INVOICE AMOUNT')
                ->setCellValue('I1', 'DELIVERY DATE'); 
        $firststyle='A2';
        $qry="SELECT  p.`poid`,s.`name` , p.`orderdt`, p.`tot_amount`,p.`vat`,p.`tax`, p.`invoice_amount`,p.`delivery_dt` FROM `po` p,`suplier` s  WHERE p.supid=s.id order by p.`id`"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['poid'])
    						->setCellValue($col3, $row['name'])
    					    ->setCellValue($col4, $row['orderdt'])
    					     ->setCellValue($col5, $row['tot_amount'])
    					     ->setCellValue($col6, $row['vat'])
    					    ->setCellValue($col7, $row['tax'])
    					    ->setCellValue($col8, $row['invoice_amount'])
    					    ->setCellValue($col9, $row['delivery_dt']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'po_'.$today.'.xls'; 
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
        <span>All PO</span>
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
      			<div class="panel-heading"><h1>All PO</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="poList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>
                    </div>
                    
    
    				</form>
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div>
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>PO NO</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Total Amount</th>
                            <th>Invoice Amount</th>
                            <th>Delivery Date </th>
                            <th></th>
                            <th></th>
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
            $('#listTable').DataTable({
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
                    'url':'phpajax/datagrid_list_all.php?action=po'
                },
                'columns': [
                    { data: 'poid' },
                    { data: 'name' },
                    { data: 'orderdt' },
                    { data: 'tot_amount' },
					{ data: 'invoice_amount' },
                    { data: 'delivery_dt' },
					{ data: 'edit', "orderable": false  },
                    { data: 'del' }
                ]
            });
        });
		
	
			function confirmationDelete(anchor)
            {
               var conf = confirm('Are you sure want to delete this record?');
               if(conf)
                  window.location=anchor.attr("href");
            }
		
        </script>  
        </script>  
    
    </body></html>
  <?php }?>    
