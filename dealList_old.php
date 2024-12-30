<?php
require "common/conn.php";
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
    $currSection = 'deal';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/deal.php?res=0&msg='Insert Data'&mod=2");
    }
    if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'Deal Name')
                ->setCellValue('C1', 'Lead Name')
                ->setCellValue('D1', 'Deal Value')
    			->setCellValue('E1', 'Currency')
    			 ->setCellValue('F1', 'Deal Stage')
                ->setCellValue('G1', 'Deal Status')
                 ->setCellValue('H1', 'Deal Date')
                ->setCellValue('I1', 'Lost Reason')
                ->setCellValue('J1', 'Sales Forcast'); 
    			
        $firststyle='A2';
        $qry="SELECT  d.`id`,d.`name` dnm,c.`id` lid, c.`name` lnm ,o.`name` leadcompany ,d.`value`,cr.`name` curr,s.`name` stage,ds.`name` 'status',ds.`id` dsid,DATE_FORMAT(d.`dealdate`, '%d/%m/%Y') `dealdate` ,(case d. `status` when '5' then  (select `name` from deallostreason where id=d.lostreason) else '' end ) lost_rsn,round(IFNULL((d.`value`*s.`weight`/100),0),2) forcast FROM `deal` d,`contact` c,`organization` o,currency cr,dealtype s,dealstatus ds WHERE d.`lead`=c.`id` and d.leadcompany=o.id and d.curr=cr.`id`	and d.`stage`=s.`id` and d.`status`=ds.`id` order by c.`name`"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;$col6='F'.$urut;$col7='G'.$urut;$col8='H'.$urut;$col9='I'.$urut;$col9='J'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['dnm'])
    						->setCellValue($col3, $row['lnm'])
    					    ->setCellValue($col4, $row['value'])
    					     ->setCellValue($col5, $row['curr'])
    					     ->setCellValue($col6, $row['stage'])
    					    ->setCellValue($col7, $row['status'])
    					    ->setCellValue($col8, $row['dealdate'])
    					    ->setCellValue($col9, $row['lost_rsn'])
    					    ->setCellValue($col9, $row['forcast']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Deal');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'deal_'.$today.'.xls'; 
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
        <span>All Deal</span>
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
      			<div class="panel-heading"><h1>All Deal</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="dealList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
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
                            <th>Deal Name</th>
                            <th>Lead Name</th>
                            <th>Lead Company</th>
                            <th>Deal Value</th>
                            <th>Deal Stage </th>
                            <th>Deal Status </th>
                            <th>Deal Date </th>
                            <th>Lost Reason </th>
                            <th>Sales Forcast </th>
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
            $('#listTable').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'phpajax/datagrid_list_all.php?action=deal'
                },
                'columns': [
                    { data: 'dnm' },
                    { data: 'lnm' },
                    { data: 'contacttype' },
                    { data: 'leadcompany' },
					{ data: 'value' },
                    { data: 'stage' },
                	{ data: 'status' },
                    { data: 'dealdate' },
                	{ data: 'lost_rsn' },
            		{ data: 'forcast' },
					{ data: 'edit' }
                ]
            });
        });
		
	
		
        </script> 
        <!-- Grid Status Menu -->
<script src="js/plugins/grid_status_menu/grid_status_menu.js"></script> 
<!-- End Grid Status Menu -->

<script>

function update_grid_status_menu(thisvalue,id, status_id){
	var dealdata = { dataid:id,statusid: status_id, modulename : 'deal', colname : 'status', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_deal_status.php?action=changedealstatus",
		  data: dealdata,
		  dataType: "text",
		  success: function(resultData) { messageAlert(resultData) }
	});
	saveData.error(function() { messageAlert("Something went wrong"); });

}

function update_grid_stage_menu(thisvalue,id, status_id){
	var dealdata = { dataid:id,stageid: stage_id, modulename : 'deal', colname : 'stage', selectedvalue : thisvalue}
	var saveData = $.ajax({
		  type: 'POST',
		  url: "phpajax/update_deal_status.php?action=changedealstage",
		  data: dealdata,
		  dataType: "text",
		  success: function(resultData) { messageAlert(resultData) }
	});
	saveData.error(function() { messageAlert("Something went wrong"); });

}
</script>

    
    </body></html>
  <?php }?>   
                    
  