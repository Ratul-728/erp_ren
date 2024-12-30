<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'inv_soitem_ed';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/inv_soitem.php?res=0&msg='Insert Data'&mod=3");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'SO Code')
                ->setCellValue('C1', 'Account Type')
                ->setCellValue('D1', 'Name')
    			->setCellValue('E1', 'Organization')
                ->setCellValue('F1', 'Effective date')
                ->setCellValue('G1', 'Order date')
                ->setCellValue('H1', 'Termination date')
                ->setCellValue('I1', 'Termination Reason')
                ->setCellValue('J1', 'Item')
                ->setCellValue('K1', 'Unit')
                ->setCellValue('L1', 'OTC Qty')
                ->setCellValue('M1', 'OTC Amt')
                ->setCellValue('N1', 'MRC Qty')
                ->setCellValue('O1', 'MRC Amt')
                ->setCellValue('P1', 'Currency')
                ->setCellValue('Q1', 'Total')
                ->setCellValue('R1', 'Invoice Amt')
                ->setCellValue('S1', 'Account Manager')
                ->setCellValue('T1', 'POC ')
                ->setCellValue('U1', 'SAN ')
                ->setCellValue('V1', 'Remarks '); 

        $firststyle='A2';

        $qry="SELECT s.`id`, s.`socode`,tp.`name` `type` ,c.`name` `customer`,o.`name` organization, date_format(s.`effectivedate`,'%d/%m/%y') `effectivedate`
, date_format(s.`orderdate`,'%d/%m/%y') `orderdt`, date_format(s.`terminationDate`,'%d/%m/%y') `termdt`
,(case  when ifnull(s.`terminationcause`,0)>1 THEN 
   (select name from terminationcause where id=s.`terminationcause`)
   else 'N/A' end) termcs
,it.`name` itm,mu.`name` mu,round(sd.qty,0) otcqty,round(sd.otc,2) otcamt,round(qtymrc,0) mrcqty,round(sd.mrc,2) mrcamt,cr.shnm crnm
,round(((sd.qty*sd.otc)+(sd.qtymrc*sd.mrc)),2) total,round(s.`invoiceamount`,2) invamt,concat(e.firstname,'',e.lastname)  accmgr
,st.name `status`,s.`remarks`, concat(e1.firstname,'',e1.lastname) `poc`
FROM `soitem` s left join soitemdetails sd on sd.socode=s.socode join `contacttype` tp on  s.`srctype`=tp.`id` join`contact` c on s.`customer`=c.`id` join `organization` o on o.`orgcode`=c.organization  
left join `hr` h on o.`salesperson`=h.`id`  left join employee e on h.`emp_id`=e.`employeecode`
left join `hr` h1 on s.`poc`=h1.`id`  left join employee e1 on h1.`emp_id`=e1.`employeecode`
left join  sostatus st on s.`status`= st.id
left join item it on sd.productid=it.id
left join mu on sd.mu=mu.id left join currency cr on sd.currency=cr.id
WHERE  1=1  order by s.`socode` asc";
//WHERE  s.`status`<>6 order by s.`socode` asc"; 

       // echo  $qry;die;

        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut; $col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut; $col14='N'.$urut;$col15='O'.$urut;$col16='P'.$urut;$col17='Q'.$urut;$col18='R'.$urut;$col19='S'.$urut;$col20='T'.$urut;$col21='U'.$urut;$col22='V'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['socode'])
    						->setCellValue($col3, $row['type'])
    					    ->setCellValue($col4, $row['customer'])
					        ->setCellValue($col5, $row['organization'])
					        ->setCellValue($col6, $row['effectivedate'])
				            ->setCellValue($col7, $row['orderdt'])
    						->setCellValue($col8, $row['termdt'])
    					    ->setCellValue($col9, $row['termcs'])
					        ->setCellValue($col10, $row['itm'])
					        ->setCellValue($col11, $row['mu'])
				            ->setCellValue($col12, $row['otcqty'])
    						->setCellValue($col13, $row['otcamt'])
    					    ->setCellValue($col14, $row['mrcqty'])
					        ->setCellValue($col15, $row['mrcamt'])
				            ->setCellValue($col16, $row['crnm'])
					        ->setCellValue($col17, $row['total'])
				            ->setCellValue($col18, $row['invamt'])
    						->setCellValue($col19, $row['accmgr'])
    						->setCellValue($col20, $row['poc'])
    					    ->setCellValue($col21, $row['status'])
					        ->setCellValue($col22, $row['remarks']);	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'SO_'.$today.'.xls'; 

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
        <span>Service Order(Item)</span>
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
      			<!--<div class="panel-heading"><h1>All Service Order(Item)</h1></div>-->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    
    
 
                	<form method="post" action="inv_soitemList.php?mod=3" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Products <i class="fa fa-angle-right"></i> All Service Order(Item)</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
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
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Account Type</th>
                            <th>Contact Person</th>
                            <th>Company</th>
                            <th>Order Number</th>
                            <th>DATE</th>
                            <th>CUR </th>
                            <th>OTC </th>
                            <th>ACCOUNT MANAGER </th>
                            <th>EDIT</th>
                            <!--th>INVOICE</th -->
                            <th>DELETE</th>
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
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
    
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
                    'url':'phpajax/datagrid_list_all.php?action=inv_soitem_ed'
                },
                'columns': [
                    { data: 'id' },
                    { data: 'srctype' },
                    { data: 'hrName' },
                    { data: 'organization' },
					{ data: 'socode' },
                    { data: 'orderdate' },
                    { data: 'shnm' },
                	{ data: 'otc' },
            		//{ data: 'mrc' },
            		{ data: 'poc' },
					{ data: 'edit', "orderable": false  },
				//	{ data: 'inv', "orderable": false  },
					{ data: 'del', "orderable": false  }
                ]
            });
            
            //new $.fn.dataTable.FixedHeader( table1 );
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
        });
		
	
		
        </script>  
    
    </body></html>
  <?php }?>    
