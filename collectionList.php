<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["user"];
$res= $_GET['res'];
$msg= $_GET['msg'];

//Filter
$fdt = $_POST['filter_date_from'];
$tdt = $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("01/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

$filterorg = $_POST['filterorg'];

if ($filterorg != '') {
    $qrychorg    = "SELECT `name` FROM `organization` where id = " . $filterorg;
    $resultchorg = $conn->query($qrychorg);
    while ($rowchorg = $resultchorg->fetch_assoc()) {
        $filterorgnm = $rowchorg["name"];

    }
} else {
    $filterorgnm = '';
}

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'collection';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/collection.php?res=0&msg='Insert Data'&mod=3");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'TRANS DATE')
                ->setCellValue('C1', 'TRANS MODE')
                ->setCellValue('D1', 'TRANS REFERENCE')
    			->setCellValue('E1', 'ORGANIZATION')
    		    ->setCellValue('G1', 'AMOUNT')
                 ->setCellValue('H1', 'INVOICE')
                ->setCellValue('I1', 'CHEQUE DATE')
                 ->setCellValue('F1', 'CHEQUE CLEAR STATUS')
                ->setCellValue('G1', 'CHEQUE CLEAR DATE')
                	 ->setCellValue('F1', 'NARATION'); 
    			
        $firststyle='A2';
        $qry="SELECT cl.`id`, cl.`trdt`,cl.`invoice`, tr.name `transmode`,cl.`transref`,c.name `customer`, cl.`naration`,cl.`amount` FROM collection cl left join organization c on cl.`customerOrg`=c.id 
left join transmode tr on cl.transmode=tr.id order by cl.id"; 
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
    			            ->setCellValue($col2, $row['trdt'])
    						->setCellValue($col3, $row['transmode'])
    					    ->setCellValue($col4, $row['transref'])
    					     ->setCellValue($col5, $row['customer'])
    					     ->setCellValue($col6, $row['amount'])
    					    ->setCellValue($col7, $row['invoice'])
    					    ->setCellValue($col8, $row['chequedt'])
    					    ->setCellValue($col9, $row['chqclearst'])
    					    ->setCellValue($col7, $row['cleardt'])
    					    ->setCellValue($col8, $row['naration']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'collection_'.$today.'.xls'; 
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
    <style>
           .modal-dialog {
                width: 800px;
            }
        </style> 
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Collection</span>
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
      		<!--	<div class="panel-heading"><h1>All Collection</h1></div> -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>

                	<form method="post" action="collectionList.php?mod=3&pg=1" id="form1">
            
                     <div class="well list-top-controls"> 
                     <!-- <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->
                        <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Billing <i class="fa fa-angle-right"></i>All Collection</h6>
                       </div>
                       
                       <div class="col-sm-7 col-lg-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                          	<div class=" col-lg-3 col-md-6 col-sm-6 ">

                                                <!--<input type="text" class="form-control" id="itemName" placeholder="Item Name (White Roll)" name="itemName[]"> -->
                                                <div class="form-group styled-select mgt5">
                                                    <input list="cmbassign1" name ="cmbassign2" value = "<?=$filterorgnm ?>" autocomplete="Search From list"  class="dl-cmborg datalist" placeholder="Select Organization">
                                                    <datalist  id="cmbassign1" name = "cmbsupnm1" class="list-cmbassign form-control" >
                                                        <option value="">Select Organization</option>
    <?php $qryitm = "SELECT org.name, org.id FROM `collection` cl LEFT JOIN organization org ON org.id = cl.`customerOrg` GROUP BY `customerOrg` ORDER BY org.name";
    $resultitm        = $conn->query($qryitm);if ($resultitm->num_rows > 0) {while ($rowitm = $resultitm->fetch_assoc()) {
        $tid = $rowitm["id"];
        $nm  = $rowitm["name"]; ?>
                                                        <option data-value="<?php echo $tid; ?>" value="<?php echo $nm; ?>" ><?php echo $nm; ?></option>
    <?php }} ?>
                                                     </datalist>
                                                     <input type = "hidden" name = "filterorg" id = "filterorg" value = "<?=$filterorg ?>">
                                                </div>
                                        </div>


                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter" placeholder="Start Date" name="filter_date_from" id="filter_date_from" value="<?php echo $fdt; ?>" >
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control datepicker_history_filter" placeholder="End Date" name="filter_date_to" id="filter_date_to" value="<?php echo $tdt; ?>"  >
                            </div>
                            <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                            </div>
                            <div class="form-group">
                            <button type="submit" title="Add New" name="add" id="add" class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            <!--input type="submit" name="add" value="" id="add"  class="btn btn-md btn-info fa-plus  pull-right responsive-alignment-r2l"-->
                            </div>
                        </div>

                        </div>
                       
                        
                        
                        
                      </div>
                    </div>
                    
    
    				</form>
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>Trans Date</th>
                            <th>Customer</th>
                            <th>Trans Type</th>
                            <th>Reference No</th>
                            <th>Amount</th>
                            <th>INVOICE </th>
                            <th>Description </th>
                            <th></th>
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
            var ch = 1;
           var table1 = $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "dom": "rtiplf",
                "order": [[ 0, "desc" ]],
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=collec&filterorg=<?=$filterorg ?>&fdt=<?=$fdt ?>&tdt=<?=$tdt ?>'
                },
                'columns': [
                    { data: 'trdt' },
                    { data: 'customer' },
                    { data: 'transmode' },
                    { data: 'transref' },
					{ data: 'amount' },
                    { data: 'inv' },
                	{ data: 'naration' },
                	{ data: 'view', "orderable": false },
					{ data: 'edit', "orderable": false },
					{ data: 'del', "orderable": false },
                ],
                drawCallback:function(settings)
                {
                    //console.log(settings.json.total);
                    if(ch == 1){
                        setTimeout(function(){
                            
                            var tot1 = settings.json.total[0];
                            

                            var tf = '<tr> <td colspan="3"></td> <td style="color: #00abe3; font-weight:bold" align="right">Total</td> <td style="color: #00abe3; font-weight:bold">'
                            +tot1+' </td>';

                            $("#listTable").append(
                                $('<tfoot/>').append( tf )
                            );

                        },500);
                        ch++;
                    }


                }
            });
             $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })
            
        });
		
	    
	    function confirmationDelete(anchor)
            {
               var conf = confirm('Are you sure want to delete this record?');
               if(conf)
                  window.location=anchor.attr("href");
            }
		
		
        </script>  
        
        <script>
            $(document).on("change", ".dl-cmborg", function() {
                var g = $(this).val();
                var id = $('#cmbassign1 option[value="' + g +'"]').attr('data-value');
                $('#filterorg').val(id);
                //alert(id);
        
        
        	});
        </script>
    
		
<script>
		
$(".dataTable").on("click",".viewnprint",function(){
		
  	mylink = $(this).attr('href');
   //alert(mylink);
//	return false;
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'PAYMENT RECEIPT',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: false, // <-- Default value is false
							draggable: true, // <-- Default value is false
							cssClass: 'post-posdata',
							buttons: [{
								icon: 'glyphicon glyphicon-print',
								cssClass: 'btn-primary',
								label: ' Print',
								action: function(dialog) {
									
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									
									
									
								}
							},
								{
								icon: 'glyphicon glyphicon-print',
								cssClass: 'btn-primary',
								label: ' Done',
								action: function(dialog) {
									dialog.close();	
									
								}
							}]
						});		
  
  
  
  
  
  
  	return false;
});		
</script>		
		
    </body></html>
  <?php }?>    
