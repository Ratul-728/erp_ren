<?php
require "common/conn.php";
$pgcnt= $_GET['pg'];
$limitst=($pgcnt-1)*25;
$limitnd=25;
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
    $currSection = 'rptcrmactvty';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/rawitem.php?res=0&msg='Insert Data'");
    }
    if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'DATE')
                ->setCellValue('C1', 'ACCOUNT MANAGER')
                ->setCellValue('D1', 'ACTIVITY TYPE')
    			->setCellValue('E1', 'CUSTOMER'); 
    			
        $firststyle='A2';
        $qry="SELECT DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') dt,h.hrName,t.`name` acttp ,c.`name` cus FROM `comncdetails` d,`contact` c,`hr` h ,`comnctype` t
where d.`contactid`=c.`id` and c.`makeby`=h.`id` and d.`comntp`=t.`id` and c.`contacttype`=1 order by d.`comndt` desc"; 
       // echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { $i=0;
            while($row = $result->fetch_assoc()) 
            { 
                $urut=$i+2;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;$col4='D'.$urut;$col5='E'.$urut;
                $i++;
                $objPHPExcel->setActiveSheetIndex(0)
    			            ->setCellValue($col1, $i)
    			            ->setCellValue($col2, $row['dt'])
    						->setCellValue($col3, $row['hrName'])
    					    ->setCellValue($col4, $row['acttp'])
    					     ->setCellValue($col5, $row['cus']) ;	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('CRM_ACTIVITY');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'CRM_ACTIVITY'.$today.'.xls'; 
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
        <span>All Item</span>
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
      			<div class="panel-heading"><h1>All Item</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="rptcrmactvty.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <!--<input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"> -->
                        </div>
                      </div>
                    </div>
                    
                    <div class="table-responsive filterable">
                        <table  class="table table-grid table-striped table-hover">
                                <thead>
                                     <tr class="filters">
                                        <th>Sl</th>
                                        <th><input type="text" class="form-control" placeholder="Date" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Account Manager" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Activity Type" disabled></th>
                                        <th><input type="text" class="form-control" placeholder="Customer" disabled></th>
                                        <th><button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button></th>
                                    </tr>
                                </thead>
                                <tbody>       
                           
    <?php 
    
    $qry="SELECT DATE_FORMAT(d.`comndt`,'%e/%c/%Y %h:%i:%s %p') dt,h.hrName,t.`name` acttp ,c.`name` cus FROM `comncdetails` d,`contact` c,`hr` h ,`comnctype` t
where d.`contactid`=c.`id` and c.`makeby`=h.`id` and d.`comntp`=t.`id` and c.`contacttype`=1 order by d.`comndt` desc LIMIT ".$limitst. ",".$limitnd;
    $sl=0;
    //echo $qry; die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    else
    {
           $inputData = array(
               'dt' => '',
               'hrName' => '',
               'acttp' => '',
               'cus' => ''
               );      
    
    
        $dbRows = 0;
        
       $result = $conn->query($qry); 
       if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
          {  $sl++;
                
     ?>                        
                            
                                <tr>
                                    <td><?php echo $sl;?></td>
                                    <td><?php echo $row["dt"]?></td>
                                    <td><?php echo $row["hrName"];?></td>
                                    <td><?php echo $row["acttp"];?></td>
                                    <td><?php echo $row["cus"];?></td>
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
        if($nrows<25){$maxrows=$nrows;}
        else{$maxrows=25;}
         $npg=$nrows/25;
         //echo $npg;die;
    ?>
                    <div class="pull-left">
                        Showing <?echo $limitst;?> to <?php echo $maxrows+$limitst; ?> of <?=$nrows->num_rows?> entries
                        
                        <?php
                        $conn->close();
                        ?>
                        
                    </div>
                    <div class="pull-right">
                        <ul class="pagination " style="border: 0px solid #000000; margin-top: 0px;">
                          <li id="datatable3_previous" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="#">Previous</a></li>
                          <?php while($npg>=1){ ?>
                          <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="rptcrmactvty.php?pg=<?php echo $npg+1;?>&mod=2"><?php echo $npg+1;?></a></li>
                          <?php $npg--; }?>
                          <li id="datatable3_next" class="paginate_button next"><a tabindex="0" data-dt-idx="16" aria-controls="datatable3" href="#">Next</a></li>
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
    
    </body></html>
  <?php }?>    
