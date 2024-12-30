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
    $currSection = 'issueadmin';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/issueadmin.php?res=0&msg='Insert Data'&mod=6");
    }
    if ( isset( $_POST['announce'] ) ) {
           header("Location: ".$hostpath."/announcement.php?res=0&msg='Insert Data'&mod=6");
    }
    if ( isset( $_POST['sms'] ) ) {
           header("Location: ".$hostpath."/sms.php?res=0&msg='Insert Data'&mod=6");
    }
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'TICKET NO')
                ->setCellValue('C1', 'ORGANIZATION')
                ->setCellValue('D1', 'SUBJECT')
    			->setCellValue('E1', 'ISSUE DATE')
    		    ->setCellValue('F1', 'DUE DATE')
                ->setCellValue('G1', 'PRODUCT')
                ->setCellValue('H1', 'ISSUE TYPE')
                 ->setCellValue('I1', 'ISSUE SUB TYPE')
                ->setCellValue('J1', 'SEVERETY')
                ->setCellValue('K1', 'ASSIGNED')
                ->setCellValue('L1', 'STATUS')
                ->setCellValue('M1', 'REPORTER')
                ->setCellValue('N1', 'CHANNEL')
                ->setCellValue('O1', 'ACCOUNT MANAGER');
                
    			
        $firststyle='A2';
        $qry="SELECT t.`id` id,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%m/%y') issuedate
        ,date_format(t.`probabledate`,'%d/%m/%y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`
,p.name `severity`,concat_ws(' ',emp.`firstname`,emp.`lastname`) assigned,st.stausnm `status`,h2.hrName `reporter`,cn.name `channel`,h3.hrName `accountmanager` 
FROM issueticket t left join organization o on t.organization=o.id
left join item i on t.product=i.id left join issuetype tp on t.issuetype =tp.id left join issuesubtype sb on t.issuesubtype=sb.id
 left join hr h2 on t.reporter=h2.id left join hr h3 on t.accountmanager=h3.id left join employee emp on t.`assigned`=emp.id
left join issuestatus st on t.status=st.id left join issuechannel cn on t.channel=cn.id left join issuepriority p on t.severity=p.id where 1=1  order by t.id asc"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;
                $col10='J'.$urut;$col11='K'.$urut;$col12='L'.$urut;$col13='M'.$urut;$col14='N'.$urut;$col15='O'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['tikcketno'])
    						->setCellValue($col3, $row['organization'])
    					    ->setCellValue($col4, $row['sub'])
    					     ->setCellValue($col5, $row['issuedate'])
    					     ->setCellValue($col6, $row['probabledate'])
    					    ->setCellValue($col7, $row['product'])
    					    ->setCellValue($col8, $row['issuetype'])
    					    ->setCellValue($col9, $row['issuesubtype'])
    					    ->setCellValue($col10, $row['severity'])
    					    ->setCellValue($col11, $row['assigned'])
    					    ->setCellValue($col12, $row['status'])
    					    ->setCellValue($col13, $row['reporter'])
    					    ->setCellValue($col14, $row['channel'])
    					    ->setCellValue($col15, $row['accountmanager']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('ISSUE');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'issue_'.$today.'.xls'; 
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
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    
    <script src="  https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/js/all.min.js"></script>
    
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>All Issue</span>
      </div>
      
    <?php
        include_once('menu.php');
    ?>
    <?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
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
      			<div class="panel-heading"><h1>All Issue</h1></div>
    				<div class="panel-body">
    
    <!-- <span class="alertmsg">
    </span> -->
    <div class="alertmsg"></div> <!-- Give Msg -->
    <br>
                	<form method="post" action="issueadminList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                        <!-- <a href = "./issueadmin.php?res=0&msg='Insert Data'&mod=6"> -->
                            <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-default">
                        <!-- </a> -->
                             <input class="btn btn-default" type="submit" name="announce" value=" Announcement" id="announce"  >
                             <input class="btn btn-default" type="submit" name="sms" value=" Send SMS" id="sms"  >
                             
                            <!-- Drop Down -->
                            <!-- Status -->
                <div class="col-lg-2 col-md-2 col-sm-11 column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="ststype" id="ststype" class="form-control">
							  <option value="">Select Status</option>
	<?php 
    $qry8="SELECT `id`, `stausnm` FROM `issuestatus` order by `stausnm` ";  $result8 = $conn->query($qry8);   if ($result8->num_rows > 0) { while($row8 = $result8->fetch_assoc())
    { 
              $tid8= $row8["id"];  $nm8=$row8["stausnm"];
    ?>          
                                <option value="<? echo $tid8; ?>"><? echo $nm8; ?></option>
    <?php }}?>
							  
							</select>
						  </div>
						</div>					
				</div>
				            <!-- Assigned -->
				<div class="col-lg-2 col-md-2 col-sm-11 column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="asigntype" id="asigntype" class="form-control" >
							  <option value="">Select Person</option>
	<?php $qryitm="select `id`,  concat_ws(' ',`firstname`,`lastname`) `name`  FROM `employee` order by name"; $resultitm = $conn->query($qryitm); if ($resultitm->num_rows > 0) {while($rowitm = $resultitm->fetch_assoc()) 
              { 
                  $tid= $rowitm["id"];  $nm=$rowitm["name"];
    ?>        
                                <option value="<? echo $tid; ?>"><? echo $nm; ?></option>
    <?php }}?>
							  
							</select>
						  </div>
						</div>					
				</div>
				            <!-- Product -->
				<div class="col-lg-2 col-md-2 col-sm-11 column">
						<div class="form-group">
						  <div class="form-group styled-select">
							<select name="protype" id="protype" class="form-control" >
							  <option value="">Select Product</option>
	<?php 
    $qry1="SELECT `id`,`name` FROM `item`  order by `name` ";  $result1 = $conn->query($qry1);   if ($result1->num_rows > 0) { while($row1 = $result1->fetch_assoc())
    { 
              $tid1= $row1["id"];  $nm1=$row1["name"];
    ?>           
                                <option value="<? echo $tid1; ?>"><? echo $nm1; ?></option>
    <?php }}?>
							  
							</select>
						  </div>
						</div>					
				</div>
                        </div>
                        
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input class="btn btn-md btn-info   pull-right responsive-alignment-r2l" type="submit" name="export" value=" Export Data" id="export"  >
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
                            <th>Issue Ticket</th>
                            <th>Action</th>
                            <th>Oganization </th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Issue Date</th>
                            <th>Assigned</th>
                            <th>Close Date</th>
                            <th>Product </th>
                            <th>Issue Type </th>
                            <th>Issue Sub Type</th>
                            <th>Severity </th>
                            <th>Create By</th>
                            <th>Reporter</th>
                            <th>Channel </th>
                            <th>Account Manager </th>
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
            var url = "phpajax/datagrid_list_all.php?action=issue";
            var sval = "";
            var aval = "";
            var proval = "";
            
            table = $('#listTable').DataTable({
                'processing': true,
				'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 100,
				'scrollX': true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				'deferRender': true,
				'scroller': true,
				//'retrieve': true,
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=issue'
                },
                'columns': [
                    //{ data: 'tikcketno' },
                    { data: 'tikcketno' },
                    { data: 'humbar', 'orderable': false},
                    { data: 'organization' },
                    { data: 'sub' },
                    { data: 'status' },
                    { data: 'issuedate' },
                    { data: 'assigned' },
                    { data: 'probabledate' },
                    { data: 'product' },
                	{ data: 'issuetype' },
                	{ data: 'issuesubtype' },
                    { data: 'severity' },
                    { data: 'createby' },
					{ data: 'reporter' },
                    { data: 'channel' },
                	{ data: 'accountmanager' },
					{ data: 'edit' },
					{ data: 'del' }
                ]
            });
        });
        
		//Table
	    function funtable(url){
	        table.destroy();
            table = $('#listTable').DataTable({
                'processing': true,
				'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 100,
				'scrollX': true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				'deferRender': true,
				'scroller': true,
				//'retrieve': true,
                'ajax': {
                    'url':url,
                },
                'columns': [
                    { data: 'tikcketno' },
                    { data: 'humbar', 'orderable': false},
                    { data: 'organization' },
                    { data: 'sub' },
                    { data: 'status' },
                    { data: 'issuedate' },
                    { data: 'assigned' },
                    { data: 'probabledate' },
                    { data: 'product' },
                	{ data: 'issuetype' },
                	{ data: 'issuesubtype' },
                    { data: 'severity' },
                    { data: 'createby' },
					{ data: 'reporter' },
                    { data: 'channel' },
                	{ data: 'accountmanager' },
					{ data: 'edit' },
					{ data: 'del' }
                ]
            });
	    }
	    
	    //Action
	    function action(pval, pid){
	        $.ajax({
                
				
				url:"phpajax/upaction.php",
				method:"POST",
				data:{val:pval,id:pid},
				
				success:function(res)
				{
				    var link = "issueadmin.php?res=6&msg='Copy Data'&mod=6&id="+res;
				    if(res != "Successfully Update!" && res != "Something went Wrong"){
				        window.location.href = link;
				    } else{
    					$('.display-msg').html(res);
    					
    					 messageAlertLong(res,'alert-success');
				    }
				}
			});
	    }
		
        </script>
        
        <!-- Change dropdown -->
        <script>
        //Status
        $(document).on("change", "#ststype", function() {
            var sval = $(this).val();
            var aval = $('#asigntype').val();
            var proval = $('#protype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval;
            funtable(url);
        });
        //Assigned
        $(document).on("change", "#asigntype", function() {
            var aval = $(this).val();
            var sval = $('#ststype').val();
            var proval = $('#protype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval;
            funtable(url);
        });
        //Product
        $(document).on("change", "#protype", function() {
            var proval = $(this).val();
            var aval = $('#asigntype').val();
            var sval = $('#ststype').val();
            var url = "phpajax/datagrid_list_drop.php?sval="+sval+"&aval="+aval+"&proval="+proval;
            funtable(url);
        });
        
        /*$(document).ready(function(){
	        $('#listTable tbody').on( 'click', 'td', function () {
	            var al =  table.cell( this ).data();
	            alert(al);
    	        $('#listTable tbody').on( 'click', 'tr', function () {
                    var d = table.row( this ).data();
                    var  loc = "issue-details.php?isit=".concat(d["tikcketno"]);
                    alert(loc);
                    //window.location=loc;
                });
            });
		}); */
		
		$(document).ready(function(){
	        $('#listTable tbody').on( 'click', 'td', function () {
	            
	            var celldata = table.cell( this ).data();
                var cellindex = table.cell( this ).index().columnVisible;
                var row_clicked = $(this).closest('tr');
                
                var tikcketno = table.row(row_clicked).data()['tikcketno'];
                
                //var data = table.row( this ).data();
                
                //alert(tikcketno);
                
                //console.log(cellindex);
                
                if(cellindex !=1){
                    var  loc = 'issue-details.php?isit='+tikcketno;
                    window.location=loc;
                }
                
            });
		}); 
		
        </script>
    
    </body></html>
  <?php }?>    
