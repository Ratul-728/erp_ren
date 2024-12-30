<?php
require "common/conn.php";
require "common/user_btn_access.php";

session_start();
$usr=$_SESSION["user"];
$filterst = $_POST["filterst"]; if($filterst == '') $filterst = 0;

if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'wallet';
    include_once('common/inc_session_privilege.php');
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/organization.php?res=0&msg='Insert Data'&mod=7");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'ORGANIZATION')
                ->setCellValue('C1', 'PHONE')
                ->setCellValue('D1', 'EMAIL')
    			->setCellValue('E1', 'WEBSITE')
    		    ->setCellValue('F1', 'ACCOUNT MANAGER')
                 ->setCellValue('G1', 'BALANCE')
                ->setCellValue('H1', 'INVOICE AMOUNT')
                 ->setCellValue('I1', 'PAID AMOUNT')
                 ->setCellValue('J1', 'DUE AMOUNT')
            	 ->setCellValue('K1', 'STATUS'); 
    			
        $firststyle='A2';
   
        $qry="SELECT o.`id`,o.`name`,i.`name` `industry`,o.`contactno`,o.`email`,o.`website`
,concat(e.firstname,'',e.lastname) accmgr,o.balance,sum(COALESCE(b.invoiceamt,0)) invoiceamt
,sum(COALESCE(b.paidamount,0)) pidAmt,sum(COALESCE(b.dueamount,0)) dueAmt
FROM organization o left join businessindustry i  on  o.`industry`=i.`id` left join operationstatus op on o.operationstatus=op.`id`
left join hr h on o.salesperson=h.id  left join employee e on h.`emp_id`=e.`employeecode` 
left join  invoice b on o.id=b.organization
where 1=1 group by  o.`id`,o.`name`,i.`name`,o.`contactno`,o.`email`,o.`website`,e.firstname,e.lastname,o.balance"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                if($row['invoiceamt']==0){$st='No Purchase Yet';} else if($row['dueAmt']<=0){$st='No Due';} else {$st='Due';}
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col10='J'.$urut;$col11='K'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['name'])
    						->setCellValue($col3, $row['contactno'])
    					    ->setCellValue($col4, $row['email'])
					        ->setCellValue($col5, $row['website'])
					        ->setCellValue($col6, $row['accmgr'])
    					    ->setCellValue($col7, $row['balance'])
    					    ->setCellValue($col8, $row['invoiceamt'])
    					    ->setCellValue($col9, $row['pidAmt'])
					        ->setCellValue($col10, $row['dueAmt'])
    					    ->setCellValue($col11, $st)  ;	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ORGANIZATION WALLET');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'organization'.$today.'.xls'; 
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
        <span>ACCOUNTING</span>
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
    
    
              <!--div class="panel panel-info">
      		<!--	<div class="panel-heading"><h1>All Organization</h1></div> 
    				<div class="panel-body" -->
    
    <span class="alertmsg">
    </span>
    
                	<form method="post" action="organizationwalletList.php?pg=1&mod=7" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                           <div class="col-sm-3 text-nowrap">
                            <h6>Accounting <i class="fa fa-angle-right"></i> Wallet</h6>
                       </div>
                       
                        <div class="col-sm-9 text-nowrap"> 
                         <div class="pull-right grid-panel form-inline">
                             <div class="form-group">
                            <div class="form-group ">
                        		<select name="filterst" id="filterst" class="form-control">
                                	<option value="0">Payment Status</option>
                                
                                    <option value="1" <?php if ($filterst == 1) {echo "selected";} ?>>No Purchase Yet</option>
                                    <option value="2" <?php if ($filterst == 2) {echo "selected";} ?>>Due</option>
                                    <option value="3" <?php if ($filterst == 3) {echo "selected";} ?>>No Due</option>
                                

                				</select>
                    	  </div>
            		</div>
                             <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div>
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                             <div class="form-group">
                                <?= getBtn('export') ?>
                            </div>
                            </div>
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <!--<div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div-->
                      </div>
                    </div>
                    
    
    				</form>
                    

<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>                   
                    
                <div >
                    <!-- Table -->
                    <table id='listTable' class='display dataTable actionbtn' width="100%">
                        <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Customer</th>
                            <!--th>Organization Phpne</th>
                            <th>Organization Email</th>
                            <th>Website </th>
                            <th>Accont Manager </th-->
                            <th>Balance </th>
                            <th>Invoice Amount </th>
                            <th>Paid Amount </th>
                            <th>Due Amount </th>
                            <th>Status </th>
                            <th>Withdrawal</th>
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
            var table1= $('#listTable').DataTable({
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
				"dom": "rtiplf",
                'ajax': {
                    'url':'phpajax/datagrid_report.php?action=orgwalllist&filterst=<?= $filterst ?>'
                },
                'columns': [
                    { data: 'orgcode' },
                    { data: 'name' },
                    //{ data: 'contactno' },
					//{ data: 'email' },
                    //{ data: 'website' },
                    //{ data: 'accmgr' },
                	{ data: 'balance' },
                	{ data: 'invoiceamt' },
                	{ data: 'paidAmt' },
                	{ data: 'dueAmt' },
                	{ data: 'status' },
                	{ data: 'withdrawal'}
                ]
            });
            
            setTimeout(function(){
			    table1.columns.adjust().draw();
            }, 350);
            
            $('#search-dttable').keyup(function(){
                  table1.search($(this).val()).draw() ;
            })   
        });
		
	
	//show payment dialog
$(document).ready(function(){
    
  
    
	$(".dataTable").on("click",".withdrawal",function(){
		
   

  	mylink3 = $(this).attr('href');
   
  
  
  
  
  
  		BootstrapDialog.show({
							
							title: 'WITHDRAWAL PAYMENT',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea1"></div>').load(mylink3),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: false, // <-- Default value is false
							draggable: false, // <-- Default value is false
							cssClass: 'post-posdata',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Cancel',
								action: function(dialog3) {
									dialog3.close();	
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
								cssClass: 'btn-primary',
								label: 'Withdrawal',
								action: function(dialog3) {
									
									ajxdata = $('#payment_form').serializeArray();
									//alert(ajxdata);
									
									
										
										if(!$("#withdrawal_amt").val()){
											swal("Alert", "Please enter valid amount", "warning");
											return false;
										}
										if($("#withdrawal_amt").val() > $("#walletmnt").val()){
											swal("Alert", "Organization do not have enough balance!", "warning");
											return false;
										}
										var wlpayment = parseFloat($("#withdrawal_amt").val());
										var walletmnt = parseFloat($("#walletmnt").val());
										if(wlpayment > walletmnt){
											swal("Alert", "Insufficient Wallet Balance!", "warning");
											return false;
										}										
										
									
									
									
									
									
									
									
									//alert("test");
									
									$.ajax({
										type: "POST",
										dataType: 'text',
										url: "phpajax/send_approval_withdrawal.php",
										data: { ajxdata : ajxdata},

									}).done(function(data){
										  if(data == "Approval successfully send for Withdrawal"){
										      swal({
                                                    title: "Withdrawal Request",
                                                    text: data,
                                                    icon: "success",
                                                }).then(function() {
                                                    dialog3.close();
                                                    location.reload();
                                                });
										  }else{
										      swal({
                                                    title: "Withdrawal Request",
                                                    text: data,
                                                    icon: "error",
                                                }).then(function() {
                                                    dialog3.close();
                                                    location.reload();
                                                });
										  }
										  
										
										dialog3.close();
										
									}).fail(function(jqXHR, textStatus, errorThrown) {
                                        console.error("AJAX request failed:", textStatus, errorThrown);
                                    });										
									
									
									

									
									
									
									
								},
								
							}],
						}); //BootstrapDialog.show({	
  
  
  
  
  
  
  	return false;
});	






});
  
	
        </script>  
    
    </body></html>
  <?php }?>    
