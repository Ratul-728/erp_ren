<?php
require "common/conn.php";
require "rak_framework/misfuncs.php";
require "common/user_btn_access.php";

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
     $currSection = 'rpt_group_wise_allocation';
     include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    $fd1=$_POST['from_dt'];
    $td1=$_POST['to_dt'];
    
    if ( isset( $_POST['export'] ) ) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'BitFlow')
                ->setCellValue('D2', 'Group Wise Item Allocation List')
                ->setCellValue('D3', '')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Item')
                ->setCellValue('C4', 'Item Code')
                ->setCellValue('D4', 'Category')
                ->setCellValue('E4', 'Description')
                ->setCellValue('F4', 'Warehouse')
                ->setCellValue('G4', 'Allocated to Cutomer'); 
                
        $firststyle='A7';
        
        $qry="select c.name catagory,i.code productCode,i.name Product,i.description ProductDescription
                                ,b.name Warehouse,s.orderedqty allocatedQty, i.image
                                from item i left join itmCat c on i.catagory=c.id
                                left join chalanstock s on s.product=i.id left join branch b on s.storerome=b.id
                                where s.orderedqty>0
                                order by i.id desc"; 
        //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $discounttot=0;$gtotal=0;$vat=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $col12='L'.$urut; $col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;$col19='S'.$urut;$col20='T'.$urut;$col21='W'.$urut;
                $col22='X'.$urut;$col23='Y'.$urut;$col24='Z'.$urut;$col25='AA'.$urut;$col26='AB'.$urut;$col27='AC'.$urut;$col28='AD'.$urut;$col29='AE'.$urut;$col30='AF'.$urut;$col31='AG'.$urut;$col32='AH'.$urut;
                
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row["Product"])
    						->setCellValue($col3, $row["productCode"])
    						->setCellValue($col4, $row["catagory"])
    						->setCellValue($col5, $row["ProductDescription"])
    						->setCellValue($col6, $row["Warehouse"])
    						->setCellValue($col7, $row["allocatedQty"]);	/* */
    			$laststyle=$title;	
            }
            $urut=$i+5;	$col3='I'.$urut;$col4='J'.$urut;
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Group Wise Item Allocation List');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'group_wise_item_allocation'.$today.'.xls'; 
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
        <span>INVENTORY</span>
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
      			<!-- <div class="panel-heading"><h1>All Product</h1></div>  -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
  
                	<form method="post" action="rpt_group_wise_allocation.php?mod=12" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="well list-top-controls">
                    <!--  <div class="row border">

                        <div class="col-xs-6 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>

                        <div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>-->
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Inventory <i class="fa fa-angle-right"></i> Groupwise Item Allocation</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            <input type="hidden" name="from_dt" id = "from_dt">
                            <input type="hidden" name="to_dt" id = "to_dt">

                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

                            <!--div class="form-group">
                            
                            <span id="pdfsource" url="pdf_purchase.php"></span>
								<?=getBtn('export')?>
							</div-->
							
							<div class="form-group">
                            
                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
								</ul>
							</div>

                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>

                        </div>


                      </div>
                    </div>
                         
                    </form>
                    
                    <!--div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="form-group"> <br><br>
                            <label>Summary </label><br>
                            <label >VAT Asseable | <span id = "vatasse"></span> </label><br>
                            <label >VAT Amount | <span id = "vatam"></span> </label><br>
                            <label >Price Incl VAT| <span id = "prinvat"></span> </label><br>
                        </div>          
                    </div-->
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable productList' width="100%">
                        <thead>
                            
							<tr>
								<th>Sl</th>
								<th>Image</th>
                                <th>Item</th>
                                <th>Item Code</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Warehouse</th>
                                <th>Allocated to Customer</th>
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
		
        
        
        <script>

$(document).ready(function(){			
			
function table_with_filter(url){
	
        	 var table1 =  $('#listTable').DataTable().destroy();
             var table1 = $('#listTable').DataTable({
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
				"order": [[ 0, "desc" ]],
				"dom": "rtiplf",
                'ajax': {
                    
					'url':url,
                },
                
				'columns': [
				    { data: 'id', orderable: false },
                    { data: 'image', orderable: false },
                    { data: 'Product' },
                    { data: 'productCode' },
                    { data: 'catagory'},
                    { data: 'ProductDescription', orderable: false },
                    { data: 'Warehouse' },
                    { data: 'allocatedQty' },
					
                ],
				 
            });
	
			
	
            
            //new $.fn.dataTable.FixedHeader( table1 );
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
		}
	
	
	
	//general call on page load
	url = 'phpajax/datagrid_list_all.php?action=rpt_group_wise_allocation';
	table_with_filter(url);	

        }); //$(document).ready(function(){	
		
		
		
        </script> 
        
        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
		
				var pdfurl = "pdf_group_wise_item_allocation.php";
				location.href=pdfurl;
				
			});
			
		</script>

        
    </body></html>
  <?php }?>    
