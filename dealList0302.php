<?php
require "common/conn.php";
$pgcnt= $_GET['pg'];
$limitst=($pgcnt-1)*150;
$limitnd=150;
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
                    
                    <div class="table-responsive filterable">
                    
<!-- Grid Status Menu -->
<link href="js/plugins/grid_status_menu/grid_status_menu.css" rel="stylesheet">
<!-- End Grid Status Menu -->

                    
                        <table  class="table table-grid table-striped table-hover">
                                <thead>
                                     <tr class="filters">
                                        <th>Sl</th>
                                        <th><input type="text" class="form-control" placeholder="Deal Name" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Lead Name" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Lead Company" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Deal Value" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Currency" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Deal Stage" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Deal Status" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Deal Date" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Lost Reason" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Sales Forcast" disabled></th>
                                        <th><button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button></th>
                                    </tr>
                                </thead>
                                <tbody>       
                           
    <?php 
    
	
	//generation status combo
	$statusStr = 'SELECT * FROM dealstatus';
	//echo $statusStr;
	
	
		$statusResult = $conn->query($statusStr);
		if ($statusResult->num_rows > 0){
			while($statusRow = $statusResult->fetch_assoc()){
				$thisClass = str_replace(" ","_",$statusRow['name']);
				$statusCombo .= '<li class="col-xs-6"><a href="javascript:void(0)" data-statusid="'.$statusRow['id'].'" class="'.strtolower($thisClass).'">'.$statusRow['name'].'</a></li>';
				
			 }
		}
  //end generation status combo		
		
	//generation stage combo
	    $stagesStr = 'SELECT * FROM dealtype order by sl';

		$stageResult = $conn->query($stagesStr);
		if ($stageResult->num_rows > 0){
			while($stageRow = $stageResult->fetch_assoc()){
				$thisClass = str_replace(" ","_",$stageRow['name']);
				$stageCombo .= '<li class="col-xs-6"><a href="javascript:void(0)" data-stageid="'.$stageRow['id'].'" class="'.strtolower($thisClass).'">'.$stageRow['name'].'</a></li>';
				
			 }
		}
	//end generation stage combo
	
    $qry="SELECT  d.`id`,d.`name` dnm,c.`id` lid, c.`name` lnm ,o.`name` leadcompany ,round(d.`value`,2) value,s.`name` stage,ds.`name` 'status',ds.`id` dsid,DATE_FORMAT(d.`dealdate`, '%d/%m/%Y') `dealdate` ,(case d. `status` when '5' then  (select `name` from deallostreason where id=d.lostreason) else '' end ) lost_rsn,round(IFNULL((d.`value`*s.`weight`/100),0),2) forcast 
FROM `deal` d left join `contact` c on d.`lead`=c.`id`
		left join `organization` o on d.leadcompany=o.id
        left join dealtype s on d.`stage`=s.`id`
        left join dealstatus ds  on d.`status`=ds.`id`
   order by c.`name` asc LIMIT ".$limitst. ",".$limitnd;
    $sl=0;
    //echo $qry; die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    else
    {
           $inputData = array(
               'id' => '',
               'dnm' => '',
               'lid' => '',
               'lnm' => '',
               'leadcompany' => '',
               'value' => '',
               'curr' => '',
               'stage' => '',
               'status' => '',
               'dealdate' => '',
               'lost_rsn' => '',
               'forcast' => ''
               );      
    
    
        $dbRows = 0;
        
       $result = $conn->query($qry); 
       if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
          { 
                $uid=$row["id"];
                $lid=$row["lid"];
                $sl++;
                $seturl="deal.php?res=4&msg='Update Data'&id=".$uid."&mod=2";
                $conthisturl="contactDetail.php?id=".$lid."&mod=2";
               
               
     ?>                        
                            
                                <tr>
                                    <td><?php echo $sl;?></td>
                                    <td><?php echo $row["dnm"]?></td>
                                    <td><a class="btn btn-info btn-xs"  href="<?php echo $conthisturl;?>"><?php echo $row["lnm"];?></a></td>
                                   <!-- <td><?php echo $row["lnm"];?></td> 
                                    <td><?php echo $row["contacttype"];?></td>-->
                                    <td><?php echo $row["leadcompany"];?></td>
                                    <td><?php echo $row["value"];?></td>
                                    <td><?php echo $row["curr"];?></td>
                                     <!--<td><?php echo $row["stage"];?></td>-->
                                    <td class="stage <?=strtolower(str_replace(" ","_",$row['stage']));?> dropdown">
                                      <div class="">
                                        <a class="bit-btn dropdown-toggle" id="menu2" type="button" data-toggle="dropdown" data-id="<?=$row['id']?>">
                                            <span>
                                                <?php echo $row["stage"];?>
                                                <span class="caret"></span>
                                            </span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-mega">
                                            <ul class="row">
                                              <?=$stageCombo?>
                                            </ul>                                    
                                        </div>
                                      </div>                                         
                                        
                                    </td>
                                    <td class="status <?=strtolower(str_replace(" ","_",$row['status']));?> dropdown">
                                      <div class="">
                                        <a class="bit-btn dropdown-toggle" id="menu1" type="button" data-toggle="dropdown" data-id="<?=$row['id']?>">
                                            <span>
                                                <?php echo $row["status"];?>
                                                <span class="caret"></span>
                                            </span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-mega">
                                            <ul class="row">
                                              <?=$statusCombo?>
                                            </ul>                                    
                                        </div>
                                      </div>                                         
                                        
                                    </td>
                                    <td><?php echo $row["dealdate"];?></td>
                                    <td><?php echo $row["lost_rsn"];?></td>
                                    <td><?php echo $row["forcast"];?></td>
                                    <td><a class="btn btn-info btn-xs"  href="<?php echo $seturl;?>">Edit</a></td>
                                </tr>
    <?php
    
    
    		$dbCols = 0;
    		foreach($inputData as $key => $value)
    		{
    			$data[$dbRows][$key] = $row[$key];
    			$dbCols++;
    		}
    		$dbRows++;
    }
    }
    else {echo "error";}
    }
    
    ?>
                       
                        </tbody>
                    </table>
                </div>
    
    
    <?php
        include_once('pagination.php');
        $nrows=$result->num_rows;
        if($nrows<150){$maxrows=$nrows;}
        else{$maxrows=150;}
         $npg=floor($nrows/150);
    ?>
                    <div class="pull-left">
                        Showing <?echo $limitst;?> to <?php echo $maxrows+$limitst; ?> of <?=$nrows->num_rows?> entries
                        
                        <?php
                        $conn->close();
                        ?>
                    </div>
                    <div class="pull-right">
                        <ul class="pagination " style="border: 0px solid #000000; margin-top: 0px;">
                            <?php  if($pgcnt>$npg){ ?>
                                <li id="datatable3_previous" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="#">Previous</a></li>
                            <?php } else {?>
                                <li id="datatable3_previous" class="paginate_button next"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="dealList.php?pg="<?php echo $pgcnt-1;?>"">Previous</a></li>
                            <?php } ?>
                            <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="dealList.php?pg=1">1</a></li>
                            <?php for($i=2;$i<=$npg;$i++){ ?>
                            <li class="paginate_button <?php if ($pgcnt==$i){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="dealList.php?pg="<?php echo $i;?>""><?php echo $i;?></a></li>
                            <?php } if($pgcnt<$npg){ ?>
                                <li id="datatable3_next" class="paginate_button next"><a tabindex="0" data-dt-idx="16" aria-controls="datatable3" href="dealList.php?pg="<?php echo $pgcnt+1;?>"">Next</a></li>
                            <?php } else {?>
                                <li id="datatable3_next" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="16" aria-controls="datatable3" href="#">Next</a></li>
                            <?php } ?>
                        </ul>	
                    </div>
    				</form>
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
