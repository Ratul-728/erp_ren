<?php
require "common/conn.php";

session_start();
$usr=$_SESSION["customer"];
$res= $_GET['res'];
$msg= $_GET['msg'];

$currSection = 'issuecustomer';
$currPage = basename($_SERVER['PHP_SELF']);

if($usr=='')
{ header("Location: ".$hostpath."/customer_login.php");
}
else
{
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'issuecustomer';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/issuecustomer.php?res=0&msg='Insert Data'&mod=7");
    }
  /*  if ( isset( $_POST['announce'] ) ) {
           header("Location: ".$hostpath."/announcement.php?res=0&msg='Insert Data'&mod=6");
    }
    if ( isset( $_POST['sms'] ) ) {
           header("Location: ".$hostpath."/sms.php?res=0&msg='Insert Data'&mod=6");
    }*/
   if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'TICKET NO')
                ->setCellValue('C1', 'ISSUE DATE')
                ->setCellValue('D1', 'SUBJECT')
    			->setCellValue('E1', 'PRIOROTY')
    		    ->setCellValue('F1', 'STAGE')
                 ->setCellValue('G1', 'STATUS') ;
                
    			
        $firststyle='A2';
        $qry="SELECT t.`id` ,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%m/%y') issuedate
        ,date_format(t.`probabledate`,'%d/%m/%y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`
,t.`severity`,'New' stg,h1.hrName `assigned`,st.stausnm `status`,h2.hrName `reporter`,cn.name `channel`,h3.hrName `accountmanager` 
FROM issueticket t left join organization o on t.organization=o.id
left join item i on t.product=i.id left join issuetype tp on t.issuetype =tp.id left join issuesubtype sb on t.issuesubtype=sb.id
left join hr h1 on t.assigned =h1.id left join hr h2 on t.reporter=h2.id left join hr h3 on t.accountmanager=h3.id
left join issuestatus st on t.status=st.id left join issuechannel cn on t.channel=cn.id where t.makeby=".$usr." order by t.tikcketno"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['tikcketno'])
    						->setCellValue($col3, $row['issuedate'])
    					    ->setCellValue($col4, $row['sub'])
    					     ->setCellValue($col5, $row['severity'])
    					     ->setCellValue($col6, $row['stg'])
    					    ->setCellValue($col7, $row['status']);	/* */
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
        <span>All Issue</span>
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
      			<div class="panel-heading"><h1>All Issue</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="issuecustomerList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                            <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-default">
                             
                        </div>
                        
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <!--<div class="col-sm-1">
                          <input class="btn btn-md btn-info   pull-right responsive-alignment-r2l" type="submit" name="export" value=" Export Data" id="export"  >
                        </div> -->
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
                            <th>Issue Date </th>
                            <th>Subject</th>
                            <th>Priority</th>
                            <th>Stage</th>
                            <th>Status </th>
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
            var table = $('#listTable').DataTable({
                'processing': true,
				'fixedHeader': true,
                'serverSide': true,
                'serverMethod': 'post',
				'pageLength': 10,
				'scrollX': true,
				'bScrollInfinite': true,
				'bScrollCollapse': true,
				/*scrollY: 550,*/
				//'select':true,
				'deferRender': true,
				'scroller': true,
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=issuecus'
                },
                'columns': [
                    { data: 'tikcketno' },
                    { data: 'issuedate' },
                    { data: 'sub' },
                    { data: 'severity' },
					{ data: 'stg' },
                    { data: 'status' },
					{ data: 'edit',"orderable": false },
					{ data: 'del', "orderable": false }
                ]
            });
            
            
            $('#listTable tbody').on( 'click', 'tr', function () {
                var d = table.row( this ).data();
                //console.log( table.row( this ).data() );
                //alert(d["tikcketno"]);
                var  loc = "issue-details.php?isit=".concat(d["tikcketno"])
                window.location=loc;
            });
            
        });
		
	
		
        </script>  
    
    </body></html>
  <?php }?>    
