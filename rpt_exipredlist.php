<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    $td=$_POST['to_dt']; if($td==''){$td=date('d/m/Y', strtotime('+7 days'));}
    
    $bc1=$_POST['bc'];
    
   // $dagent=$_POST['cmbsupnm']; if($dagent==''){$dagent=0;}
    
    $branch = $_POST["cmbbranch"];
    

    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rpt_expired_stock';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/product.php?res=0&msg='Insert Data'&mod=1");
    }
   if ( isset( $_POST['export'] ) ) {
        //echo $fd1; die;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('D1', 'BitPos')
                ->setCellValue('D2', 'Expired Stock Report')
                ->setCellValue('A4', 'Sl')
                ->setCellValue('B4', 'Type')
                ->setCellValue('C4', 'Product')
                ->setCellValue('D4', 'Barcode')
                ->setCellValue('E4', 'Store')
                ->setCellValue('F4', 'Expired Date')
                ->setCellValue('G4', 'Quantity')
                ->setCellValue('H4', 'Cost Rate')
                ->setCellValue('I4', 'Cost Price')
                ->setCellValue('J4', 'MRP')
                ->setCellValue('K4', 'MRP Price'); 
    			
        $firststyle='A7';
        if($fdt != ''){
            $date_qry = " and  s.expirydt< STR_TO_DATE('".$tdt."','%Y/%m/%d')";
        }else{
            $date_qry = "";
        }
        $qry="SELECT t.name tn,p.name pn,s.freeqty,s.costprice,p.rate mrp,r.name str,s.barcode,DATE_FORMAT(s.expirydt,'%e/%c/%Y') expirydt 
                FROM chalanstock s LEFT JOIN item p ON s.product = p.id 
                LEFT JOIN itemtype t ON p.catagory=t.id 
                LEFT JOIN branch r ON s.storerome=r.id where s.`freeqty`>0 and (s.barcode like '%".$bc1."%' or '".$bc1."'='') and ( r.id = ".$branch." or ".$branch." = '' )
                    order by t.name,p.name"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;$tcp=0;$tmp=0;
            while($row2 = $result->fetch_assoc()) 
            { 
                $gnm=$row2["gn"];$cnm=$row2["cn"]; $tnm=$row2["tn"]; $prod=$row2["pn"];$str=$row2["str"];  
                $freeqty=$row2["freeqty"]; $cup=$row2["costprice"]; $mup=$row2["mrp"];$bc=$row2["barcode"];$exp=$row2["expirydt"];
                $cp=$freeqty*$cup;$mp=$freeqty*$mup; 
                $tcp=$tcp+$cp;$tmp=$tmp+$mp;
   
                $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    						->setCellValue($col2, $tnm)
    						->setCellValue($col3, $prod)
    						->setCellValue($col4, $bc)
    						->setCellValue($col5, $str)
    						->setCellValue($col6, $exp)
    						->setCellValue($col7, number_format($freeqty,0))
    						->setCellValue($col8, number_format($cup,2))
    						->setCellValue($col9, number_format($cp,2))
    						->setCellValue($col10, number_format($mup,2))
    						->setCellValue($col11, number_format($mp,2));	/* */
    			$laststyle=$title;	
            }
            $urut=$i+6;	$col3='H'.$urut;$col4='I'.$urut;$col5='K'.$urut;
            $objPHPExcel->setActiveSheetIndex(0)
					
				    ->setCellValue($col3, 'Total')
					->setCellValue($col4, number_format($tcp,2))
					->setCellValue($col5, number_format($tmp,2));
			
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Expired  Stock Report ');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'expired_stock'.$today.'.xls'; 
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
        <span>Expire Stock Report</span>
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
  
                	<form method="post" action="rpt_exipredlist.php?mod=12" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                       <div class="well list-top-controls">
                
                          <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Invenctory <i class="fa fa-angle-right"></i> Expired Stocklist Report </h6>
                       </div>

                        

                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            
                            <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbbranch" id="cmbbranch" class="form-control" >
                                            <option value="0">Store</option>
    <?php
$qry1    = "SELECT `id`, `name`  FROM `branch` where status = 'A' order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($branch == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div>
                            
                            <div class="form-group">
                                    <input type="text" class="no-mg-btn form-control" id="bc" name="bc" placeholder="Bar Code" value="<?php echo $barcode; ?>"  >

                                </div>
                            
                             <div class="form-group">
                                <input type="text" class="form-control datepicker" id="to_dt" name="to_dt"  value="<?php echo $td; ?>" placeholder = "Date">
                            </div> 
                                
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control"  placeholder="Search by Key">
                            </div>

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
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable productList' width="100%">
                        <thead>
                            
							<tr>
								<th>Sl</th>
								<th>Catagory</th>
								<th>Product</th>
								<th>Barcode </th>
								<th>Store</th>
								<th>Expired Date</th>
								<th>Qty</th>
								<th>Cost Rate </th>
								<th>Cost Price</th>
								<th>MRP</th>
								<th>MRP Total</th>
							</tr>
                        </thead>
                        
                        <!--<tfoot id="dtfoot">
                            <tr>
                                <td id="total_label" colspan="8" align="right">Total</td>
                                <td id="total_cost" colspan="1"  ></td>
                                <td id="total_mrp"></td>
                                <td></td>
                            </tr>
                        </tfoot> 
                        
                        <tfoot>
                            <tr>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th>Total</th>
								<th id="total_cost"></th>
								<th></th>
								<th id="total_mrp"></th>
							</tr>
                        </tfoot> -->
                        
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
                    { data: 'id' },
                    { data: 'tn'},
                    { data: 'pn' },
                    { data: 'barcode', orderable: false },
                    { data: 'str' },
                    { data: 'expirydt' },
                    { data: 'freeqty' },
                    { data: 'costprice' },
                    { data: 'costpr', orderable: false },
                    { data: 'mrp'},
                    { data: 'mrptotal', orderable: false }
					
                ],
                
                drawCallback:function(settings)
                {
                    
                    
                        setTimeout(function(){
                        //$('#total_cost').html(settings.json.total[0]);
                        //$('#total_mrp').html(settings.json.total[1]);
                        var tot1 = Number(settings.json.total[0]).toFixed(2);
                        var tot2 = Number(settings.json.total[1]).toFixed(2);
                        
                        var tf = '<tr> <td style="color: #00abe3; font-weight:bold" colspan="8" align="right">Total</td> <td style="color: #00abe3; font-weight:bold">'+tot1+' </td><td></td> <td style="color: #00abe3; font-weight:bold">'+tot2+' </td>';
                        $("#listTable").append(
                            $('<tfoot/>').append( tf )
                        );
                        
                    },500);
                    
     
                }
				 
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
	var dt = $('#to_dt').val();
	url = 'phpajax/datagrid_list_all.php?action=rpt_expire&dt_f'+dt;
	table_with_filter(url);	
    
    //Status
    $("#cmbbranch,#bc, #to_dt").on("change, input", function() {
            
            var branch = $('#cmbbranch').val();
            var bc = $('#bc').val();
            var dt = $('#to_dt').val();
            
            var url = 'phpajax/datagrid_list_all.php?action=rpt_expire&branch='+branch+'&barcode='+bc;
			
			 setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script> 
        

        <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
				
				var branch = $('#cmbbranch').val();
                var bc = $('#bc').val();
				var pdfurl = "pdf_expiredlist.php?branch="+branch+"&barcode="+bc+"";
				location.href=pdfurl;
				
			});
			
			$('#to_dt').on("changeDate", function (e) {
    alert("Date changed: ", e.target.value);
});
			
		</script>
        
    
    </body></html>
  <?php }?>    
