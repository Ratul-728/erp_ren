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
    $currSection = 'quotation_price_approval';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/approval_itm_rate_change.php?res=0&msg='Insert Data'&mod=7");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'BARCODE')
                ->setCellValue('C1', 'PRODUCT')
                ->setCellValue('D1', 'CURRENT RATE')
                ->setCellValue('E1', 'NEWRATE')
    			->setCellValue('F1', 'REASON')
    			->setCellValue('G1', 'REQUESTED BY')
    			->setCellValue('H1', 'REQUEST DATE');
    			
        $firststyle='A2';
        $qry="SELECT p.barcode,p.name prod,p.rate,p.image,c.newrate,c.reason,u.hrName,DATE_FORMAT(c.makedt,'%e/%c/%Y') trdt reqby FROM `approval_item_price_change` c,item p,hr u where c.product=p.id and c.makeby=u.id and c.approvst=0 order by c.makedt asc";

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
    			            ->setCellValue($col2, $row['barcode'])
    						->setCellValue($col3, $row['prod'])
    					    ->setCellValue($col4, $row['rate'])
    					    ->setCellValue($col6, $row['newrate'])
    					    ->setCellValue($col7, $row['reason'])
    					    ->setCellValue($col8, $row['hrName'])
    					    ->setCellValue($col9, $row['trdt']);
    					     	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'rateapproval_'.$today.'.xls'; 
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

                	<form method="post" action="approval_itm_rate_change.php" id="form1">
            
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
                            <h6>Approval <i class="fa fa-angle-right"></i>ITEM RATE CHANGE </h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                          <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            

                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
                    
    
    				</form>
         <style>
         .ajax-img-up{
            border: 0px solid #000!important;
            display: flex;
            text-align: left;
        }
        .ajax-img-up ul{
          margin-bottom: 0;
          margin-left: 0!important;
            padding-left: 0px;
        }
        
        .ajax-img-up li{
          display: block;
          width: 40px;
          height: 40px;
          border: 1px solid #888787;
          position: relative;
          margin: 3px;
          border-radius: 0px;
          border-radius: 5px;
        }
        
        
        .ajax-img-up li img{
          width: 100%;
          height: 100%;
          border-radius: 5px;
        }
        
        .scroll-wrap{
                overflow-x: scroll;
            }

         </style>

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable' width="100%">
                        <thead>
                        <tr>
                            <th>ORDER ID</th>
                            <!--<th>BARCODE</th>--> 
                            <th>PHOTO</th>
                            <th>PRODUCT</th>
                            <th>PURCHASE</th>
                            <th>SALE PRICE</th>
                            <th>REQUESTED SALE PRICE</th>
                            <th>REASONE</th>
                            <th>REQUEST BY</th>
                            <th>REQUEST DATE</th>
                            <th>CUSTOMER</th>
                            <th>ACTION BY</th>
                            <th>ACTION DATE</th>
                            <th>ACTION</th>
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
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                "dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_quotation_price_approval.php?action=datagrid_quotation_price_approval'
                },
                'order': [0, 'desc'],
                'columns': [
                    { data: 'order_id', "orderable": false},
                    //{ data: 'barcode', "orderable": false },
                    { data: 'image', "orderable": false},
                    { data: 'product', "orderable": false },
                    { data: 'purchase_price', "orderable": false },
                    { data: 'sale_price', "orderable": false },
					{ data: 'new_sale_price', "orderable": false },
					{ data: 'reason', "orderable": false },
					{ data: 'requested_by', "orderable": false },
					{ data: 'requested_on', "orderable": false },
					{ data: 'customer', "orderable": false },
					{ data: 'approved', "orderable": false },
					{ data: 'approvedt', "orderable": false },
					{ data: 'action_buttons', "orderable": false }
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
            title: "Quotation Product Rate Change Approval",
            text: "Are you sure you want to proceed with this rate change? This action cannot be undone.",
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
                location.href = url+"&st=1";
                //location.href = "";
              } else if (willProceed === false) {
                location.href = url+"&st=2";
                //location.href = "";
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
  $(".dataTables_wrapper").on("click",".picture-preview",function(){

		
  	    mylink = $(this).attr('href');
		
    //alert(mylink);
   // return false;
   

  
  		BootstrapDialog.show({
							
							title: 'Defect Picture',
    						message: $('<div id="printableArea4" align="center"><img src="'+mylink+'" width="100%"></div>'),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: true, // <-- Default value is false
							cssClass: 'picture-preview',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Close',
								action: function(dialog2) {
									dialog2.close();	
									
									
								}
							}],
							//onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
                        return false;
  
  
  
  
      	
    });
    
    $('#listTable').wrap('<div class="scroll-wrap"></div>');

});
</script>      
        
    
    </body></html>
  <?php }?>    
