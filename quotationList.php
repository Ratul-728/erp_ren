<?php
//ini_set('display_errors',1);
session_start();
require "common/conn.php";
require "common/user_btn_access.php";




$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];





if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the tiontion name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'quotation';
	// load session privilege;
	include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           //header("Location: ".$hostpath."/quotationEntry.php?res=0&msg='Insert Data'&mod=3");
           header("Location: ".$hostpath."/quotationEntry.php?postaction=Save&mode=insert&mod=3");
    }
   if ( isset( $_POST['export'] ) ) {

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'ORDER ID')
                ->setCellValue('C1', 'SALES TYPE')
                ->setCellValue('D1', 'CUSTOMER ID')
    			->setCellValue('E1', 'CUSTOMER NAME')
                ->setCellValue('F1', 'ORDER STATUS')
                ->setCellValue('G1', 'ORDER DATE'); 

        $firststyle='A2';

        $qry="SELECT s.`id`, s.makedt makedt, s.`socode`, o.`name` organization, o.orgcode, s.`orderdate`, date_format(s.`orderdate`,'%d/%b/%Y') `orderdate_formated`, s.orderstatus, orst.name `quotationstatusname`, 
(select MIN(DATE_FORMAT( ti.expted_deliverey_date,'%d/%b/%Y')) from quotation_warehouse ti where ti.socode=s.socode) AS expted_deliverey_date, inv.id iid, inv.paymentSt, (case when s.srctype=2 then (select name from project where id=s.project) else 'Retail' end) saletp 
FROM `quotation` s 
left join `organization` o on o.id=s.organization 
left join `invoice` inv on inv.`soid`=s.socode

left join `quotation_status` orst on s.`orderstatus`=orst.`id`  WHERE  1=1 order by s.`id` desc";
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
    						->setCellValue($col3, $row['saletp'])
    					    ->setCellValue($col4, $row['orgcode'])
					        ->setCellValue($col5, $row['organization'])
					        ->setCellValue($col6, $row['quotationstatusname'])
				            ->setCellValue($col7, $row['orderdate_formated']);
    						-	/* */

    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('SO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'QUOTATION_'.$today.'.xls'; 

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
<style>
.deldatewrap{
    display: flex;
}

.deldatewrap span{
  flex: 1;
}
.deldatewrap a{
  flex: 1;
    text-align: right;
    color: #094446;
}
</style>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>SALES</span>
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
    
    
 
                	<form method="post" action="quotationList.php?mod=3" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Sales <i class="fa fa-angle-right"></i> All Quotations</h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
							
                                <div class="form-group">
  									<label for="">Filter by: </label>
                                </div>
							
                                <div class="form-group">
                                    <div class="form-group styled-select">
                                        <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                            <option value="0">All Status</option>
    <?php
$qry1    = "select id,name from quotation_status order by name";
    $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
        $tid = $row1["id"];
        $nm  = $row1["name"];
        ?>
                                            <option value="<?php echo $tid; ?>" <?php if ($icat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
    <?php }} ?>
                                        </select>
                                    </div>
                                </div> 
							
             						
							
                            <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            <div class="form-group">
                               <?=getBtn('create')?>
                            </div>
                            <div class="form-group">
                                   <?=getBtn('export')?>
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

                            <th>Created</th>
                            <th>Order ID</th>
                            <th>Sales Type</th>
                            <!--th>Invoice ID</th-->
                            <th>Order Date</th>
                            <th>Expected Delivery Date</th>
                            <th>Customer ID</th>
                            <th>Customer Name</th>
							<th>Order Status</th>
							<th>QC Status</th>
							<th>Delivery Status</th>
                            <!--th>Order Amount</th-->
                            <!--th>Account Manager</th-->
                            <th style="width:150px;">Actions</th>
                            <!--th>Delete</th-->							
							
							
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
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
		
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
                    //'url':'phpajax/datagrid_saleorder.php?action=inv_soitem'
					'url':url,
                },
//				'columnDefs': [{
//				'render': function(data,id){ return id},
//				'targets': 0,
//				'className': 'root_'+id,
//			}],

				'columns': [
                    { data: 'makedt','bVisible': false },
                    { data: 'socode','render': function (socode) {
						return '<span class="rowid_'+ socode +'">' + socode +'</span>'
						}
					},
					{ data: 'saletp'},
                    //{ data: 'invoice'},
                    { data: 'orderdate' },
					{ data: 'expted_deliverey_date' },
					{ data: 'orgcode' },
                    { data: 'organization' },
					{ data: 'orderstatus' },	//Order Status
					{ data: 'qcstatus' },	//Order Status
					{ data: 'delistatus' },	//Order Status
                	//{ data: 'otc' },	//Order Amount
            		//{ data: 'poc' },	//accoutn manager
					{ data: 'action_buttons', 'orderable':false},
					
					//{ data: 'edit', "orderable": false  },
					//{ data: 'inv', "orderable": false  },
					//{ data: 'del', "orderable": false  }
					
					
                ],
				 
            });
	
			
	
            
            //new $.fn.dataTable.FixedHeader( table1 );
            setTimeout(function(){
			   // table1.columns.adjust().draw();
            }, 350);
            
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })            
            
		}
	
	
	
	//general call on page load
	//url = 'phpajax/datagrid_quotation.php?action=quotation&currSection='.$currSection;
    //url = 'phpajax/datagrid_quotation.php?action=quotation&currSection=<?=$currSection?>';
    url = 'phpajax/datagrid_quotation.php?action=quotation';
	table_with_filter(url);	
	

        //Status
        $("#cmbstatus").on("change", function() {

            var status = $(this).val();
			//status = parseInt(status.trim());
            //var user = $('#filteruser').val();
            //var paidto = $('#filterpaidto').val();
            //var enddt = $('#end_dt').val();
            //var startdt = $('#start_dt').val();
            //var url = 'phpajax/datagrid_saleorder.php?action=inv_soitem&user='+user+'&cmbstatus='+status+'&paidto='+paidto+'&startdt='+startdt+'&enddt='+enddt;
			url = 'phpajax/datagrid_quotation.php?action=quotation&cmbstatus='+status;
			
			//alert(status);
			
            
			
            setTimeout(function(){
				table_with_filter(url);
			    
            }, 350);			

        });	
        
    //Approval
	$(document).on('click', '.approval', function(event) {
            var url = $(this).attr('href');
		    swal({
              title: 'Warning',
              text: 'Do you want to send approval for qc without payment?',
              icon: 'warning',buttons: {
            cancel: {
              text: "Cancel",
              value: null,
              visible: true,
              className: "",
              closeModal: true,
            },
            accept: {
              text: "Send for approval",
              value: true,
              visible: true,
              className: "",
              closeModal: true
            }
            },
            })
            .then((willProceed) => {
              if (willProceed === true) {
                location.href = url;
              }
            });


			return false;

		});
	
			
			
			
			
			
//delete row
			
$("#listTable_wrapper").on("click",".griddelbtn", function() {

			var url = $(this).attr('href');
	  //alert(url);
	  //swal(url);
	//return false;


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
					
	
	
	
	
			
			
			
        }); //$(document).ready(function(){	
		
		
		
        </script>  
		
		
<script>

function dateRangePopup(){
	
        $(document).on('focus','.datepicker-popup', function(){
			
            $(this).datetimepicker({
       
                 //minDate: moment().startOf('day').add(1, 'days').toDate() ,
				 format: "DD/MM/YYYY",
				 //format: 'LT',
                 //debug:true,
				 //keepOpen:true,
                 showClear:true,
                 useCurrent:false,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });    
    });	
	
}


$(document).ready(function(){
	
//show INVOICE
	
	$(".dataTable").on("click",".show-invoice.btn",function(){
		
  	mylink = $(this).attr('href')+"?socode="+$(this).data('socode')+"&qtype=quotation&status="+$(this).data('stcode');
	filenm = 'quotation_'+$(this).data('socode')+'.pdf';
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
    								}
    							},
    							{
                                    icon: 'glyphicon glyphicon-download',
                                    cssClass: 'btn-success <?=(!checkBtnAccess('print'))?'disabled':''?>',
                                    label: ' Download PDF',
                                    action: function(dialog) {
                                        <?php if(checkBtnAccess('print')){?>
                                            var element = document.getElementById('printableArea2');
                                            var opt = {
                                                margin: [10, 10, 10, 10], // Margins in millimeters
                                                filename: filenm,
                                                image: { type: 'jpeg', quality: 0.98 },
                                                html2canvas: { scale: 2 }, // Adjust scale for quality
                                                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                                            };
                                            html2pdf().set(opt).from(element).save(); // Generate and download PDF
                                        <?php } else { ?>
                                            alert('Download access restricted');
                                        <?php } ?>
                                        dialog.close();	
                                    }
                                },
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary <?=(!checkBtnAccess('print'))?'disabled':''?>',
								label: ' Print',
								action: function(dialog) {
									<?php
									 //echo 'PRINTPER  '.checkBtnAccess('print');
									if(checkBtnAccess('print')){?>
									
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
		
    $(".dataTable").on("click","a.pop-deliverydate",function(){
        
        var root = $(this);
        var orderid = $(this).data('socode');
    		
      	var mylink = $(this).attr('href')+"?socode="+$(this).data('socode');
    	
      // alert(mylink);
      
      dateRangePopup();
      
      
      
      
      
      BootstrapDialog.show({
    							
    							title: 'Change Delivery Date: Order ID: '+orderid,
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
    								cssClass: 'btn-primary <?=(!checkBtnAccess('change-delivery-date'))?'disabled':''?>',
    								label: ' Update Delivery Date',
    								action: function(dialog) {
    					
    									//write date update ajax code here;
    									
    									var formData = $("#dateChangeForm").serialize();
    									
                                        $.ajax({
                                                    type: "POST",
                                                    url: "phpajax/update_delivery_date.php",
                                                    data: formData,
                                                    success: function (res) {
                                                        // Handle success response
                                                        
                                                        
                                                        
                                                        
                                                        swal({
                                                			  title: "Success!",
                                                			  text: res.msg,
                                                			  icon: "success",
                                                			 
                                                			  
                                                			  closeButton: true,
                                                                                                             			  
                                                			})
                                                			console.log(res.date);
                                                		root.closest("tr").find(".deldatewrap span").html(res.date);
                                                		root.closest("tr").find(".deldatewrap").parent().css('backgroundColor', '#FBC8A3C2');
                                                		setTimeout(function() {root.closest("tr").find(".deldatewrap").parent().css('backgroundColor', 'transparent');},5000);
                                                        
                                                    },
                                                    error: function () {
                                                        // Handle error response
                                                        BootstrapDialog.show({
                                                            title: 'Error',
                                                            message: 'Failed to save data!',
                                                            type: BootstrapDialog.TYPE_DANGER
                                                        });
                                                    }
                                                });    									
    									
    									
    									
    									
    									
    									dialog.close();	
    									
    									},
    								
    							}],
    							onshown: function(dialog){  $('.btn-primary').focus();},
    						});	
      
      
      
      
      
      
      	return false;
    });	
}); //$(document).ready(function(){
</script>

<?php
    if($_REQUEST['result']){
?>

<script>



    var postaction  = '<?=$_REQUEST['postaction']?>';
    var result      = '<?=$_REQUEST['result']?>';
    var ward;
        if(postaction == 'Save'){ward = 'Quotation';}
        if(postaction == 'Revision'){ward = 'Revision';}
        if(postaction == 'Order'){ward = 'Order';}
    
    
    if(result == 1){
        swal({
                title: "Success",
                text: ward + " info saved successfully!",
                icon: "success",
                timer: 5000
            
        });
    }
    if(result == 2){
        swal({title: "Error", text: "Error occured during "+ward+ "info in the system!", icon: "error", timer:5000});
    }
    
</script>

<?php
    }
?>
    
    </body></html>
  <?php }?>    
