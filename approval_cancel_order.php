<?php
require "common/conn.php";
require "common/user_btn_access.php";

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
    $currSection = 'approval_cancel_order';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/approval_transfer_stock.php?res=0&msg='Insert Data'&mod=7");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'VOUCH NO')
                ->setCellValue('C1', 'TRANS DATE')
                ->setCellValue('D1', 'REFERENCE')
    			->setCellValue('E1', 'REMARKS');
    			
        $firststyle='A2';
        $qry="SELECT `vouchno`, DATE_FORMAT(`transdt`,'%e/%c/%Y') trdt, `refno`, `remarks` FROM `glmst` WHERE status = 'A' "; 
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
    			            ->setCellValue($col2, $row['vouchno'])
    						->setCellValue($col3, $row['trdt'])
    					    ->setCellValue($col4, $row['refno'])
    					     ->setCellValue($col5, $row['remarks']);
    					     	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'glmaster_'.$today.'.xls'; 
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
        <span>APPROVAL</span>
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

                	<form method="post" action="approval_cancel_order.php" id="form1">
            
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
                            <h6>Approval <i class="fa fa-angle-right"></i>Cancel Order</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                          <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
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
                            <th>SL.</th>
                            <th>Cancel ID</th>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Customer</th>
                            <th>Product</th>
                            <th>Order Qty</th>
                            <th>Cancel Qty</th>
                            <th>Request By</th>
                            <th>Action By</th>
                            <th>Action</th>
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

<!--inv Modal view-->
<div class="autoModal modal fade text-center" id="invpay-modal">
  <div class="modal-dialog invpay-form" role="document">
    <div class="modal-content bg-gray">
      <div class="modal-header inv-modal-headerx">
        <h5>Cancel Order</h5>
      </div>

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
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_approval.php?action=cancelorder' 
                },
                'order': [0, 'desc'],
                'columns': [
                    { data: 'sl' },
                    { data: 'ro_id', "orderable": false },
                    { data: 'order_id', "orderable": false },
                    { data: 'orderdate' },
                    { data: 'customer', "orderable": false },
                    { data: 'product', "orderable": false },
                    { data: 'orderqty', "orderable": false },
                    { data: 'cancelqty', "orderable": false },
                    { data: 'requestby', "orderable": false },
                    { data: 'approveby', "orderable": false },
					{ data: 'action', "orderable": false }
                ]
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
        //Action
        //delete row
			
        $("#listTable").on("click",".actionbtn", function() {

			var url = $(this).attr('href');

            swal({
            title: "Cancel Order Approval",
            text: "Are you sure you want to proceed with this Cancel order? This action cannot be undone.",
            icon: "info",
            buttons: {
            cancel: {
              text: "Cancel",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            decline: {
              text: "Decline",
              value: false,
              visible: true,
              className: "btn-danger",
              closeModal: true,
            },
            accept: {
              text: "Accept",
              value: true,
              visible: true,
              className: "swal-accept-btn",
              closeModal: true
            }
            },
            })
            .then((willProceed) => {
              if (willProceed === true) {
                location.href = url+"&st=2";
              } else if (willProceed === false) {
                location.href = url+"&st=0";
              } 
            });


			return false;

	
	    });
		//delete row
			
        $("#listTable").on("click",".griddelbtn", function() {

			var url = $(this).attr('href');

			  swal({
			  title: "Are you sure?",
			  text: "Once deleted, you will not be able to recover this order!",
			  icon: "warning",
			  buttons: true, 
			  dangerMode: true,
			  buttons: ['Cancel', 'Confirm Delete'],
			})
			.then((willDelete) => {
			  if (willDelete) {
				location.href=url;
				//swal("Order has been deleted!", {
				 // icon: "success",
			   // });
			  } else {
				//swal("Your imaginary file is safe!");
				  return false;
			  }
			});

			return false;

	
	    });
		
        </script>  
        
        <script>
$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?socode="+$(this).data('socode');
	
   //alert(mylink);
  
  
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: $(this).data('st'),
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea2"></div>').load(mylink),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: false, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog) {
									dialog.close();	
									/*
									$("#printableArea2").printThis({
										importCSS: true, 
										importStyle: true,
									});
									*/
									
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary <?=(!checkBtnAccess('print'))?'disabled':''?>',
								label: ' Print',
								action: function(dialog) {
									<?php if(checkBtnAccess('print')){?>
									
                                    $("#printableArea2").printThis({
										importCSS: false, 
										importStyle: true,
									});
		                          <?php }else{?>
                                    alert('Print access restricted');
                                    <?php }?>
									
									dialog.close();	
									
									},
								
							}],
							onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});
});
</script>
    
    </body></html>
  <?php }?>    
