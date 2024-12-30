<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

$fdt = $_POST['filter_date_from'];
$pyr=$_POST['cmbyr'];
$pmn=$_POST['cmbmonth']; if($pmn == '') $pmn = date('m');
$pmn = str_pad($pmn, 2, "0", STR_PAD_LEFT);

//$tdt= $_POST['filter_date_to'];
if ($fdt == '') {$fdt = date("d/m/Y");}
//if($tdt==''){$tdt=date("d/m/Y");}

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    require_once "common/PHPExcel.php";
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'rpt_balance_sheet';
    $currPage    = basename($_SERVER['PHP_SELF']);

    if (isset($_POST['add'])) {
        header("Location: " . $hostpath . "/hraction.php?res=0&msg='Insert Data'&mod=4");
    }
    if (isset($_POST['export'])) {

        //Sub Query
        $assetqry    = "select @asset=COALESCE(closingBal,0) asset from coa_mon where glno='100000000' and mn='$pmn' and yr='$pyr'";
        $assetresult = mysqli_query($con, $assetqry);
        while ($assetrow = mysqli_fetch_assoc($assetresult)) {
            $asset = $assetrow["asset"];
        }

        $liabilityqry    = "select @liability =COALESCE(closingBal,0) liability from coa_mon where glno='200000000' and mn='$pmn' and yr='$pyr'";
        $liabilityresult = mysqli_query($con, $liabilityqry);
        while ($liabilityrow = mysqli_fetch_assoc($liabilityresult)) {
            $liability = $liabilityrow["liability"];
        }

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'SL.')
            ->setCellValue('B1', 'Asset/Liability')
           // ->setCellValue('C1', 'Amount')
            ->setCellValue('C1', 'Level')
            ->setCellValue('D1', 'GL Account')
            ->setCellValue('E1', 'GL Name')
            ->setCellValue('F1', 'Closing Balance')
            //->setCellValue('G1', 'P')
            ;
        
        $firststyle = 'A2';
        $qry        = "select
                                (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                            	from coa_mon a 
                            	where substring(a.glno,1,1) in('1','2') 
                               and mn='$pmn' and yr='$pyr' and a.status='A'
                            	order by a.glno";
        //echo  $qry;die;
        $result = $conn->query($qry);
        if ($result->num_rows > 0) {$i = 0;
            while ($row = $result->fetch_assoc()) {
                $urut  = $i + 2;
                $col1  = 'A' . $urut;
                $col2  = 'B' . $urut;
                $col3  = 'C' . $urut;
                $col4  = 'D' . $urut;
                $col5  = 'E' . $urut;
                $col6  = 'F' . $urut;
               
                $i++;
                if ($row["dr_cr"] == 'C') {
                    $dr = $row["amount"];
                    $cr = 0;
                } else {
                    $cr = $row["amount"];
                    $dr = 0;
                }
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1, $i)
                    ->setCellValue($col2, $row['asslib'])
                    //->setCellValue($col3, $row['assLib_amount'])
                    ->setCellValue($col3, $row['lvl'])
                    ->setCellValue($col4, $row['glno'])
                    ->setCellValue($col5, $row["glnm"])
                    ->setCellValue($col6, $row["closingBal"])
                    //->setCellValue($col8, $row["p"])
                    ;

                //$laststyle=$title;
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Balance Sheet');
        $objPHPExcel->setActiveSheetIndex(0);
        $today     = date("YmdHis");
        $fileNm    = "data/" . 'rpt_balance_sheet' . $today . '.xls';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $fileNm);
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
include_once 'common_header.php';
    ?>

    <body class="list">

    <?php
include_once 'common_top_body.php';
    ?>
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Balance Sheet Report</span>
      </div>

    <?php
include_once 'menu.php';
    ?>

      	<div style="height:54px;">
    	</div>
      </div>

      <!-- END #sidebar-wrapper -->
      
<style>

.bhbs-wrapper{}
.bhbs-header{
	text-align: center;
}
.bhbs-header{
    border-bottom: 1px solid #efefef;
}
.bhbs-header h2{font-size: 20px;margin-bottom:0;}
.bhbs-header h1{font-size: 30px;margin-top:5px;}
.tbl-bhbs-wrapper{
     border: 1px solid #efefef;
    padding: 15px;
}

.tbl-bhbs td:first-child{}
.tbl-bhbs td:last-child{width: 100px}

.tbl-bhbs td, .tbl-bhbs th{
    padding: 5px;
    border-bottom: 1px solid #efefef;
}

.tbl-bhbs tr {
    -webkit-transition: background-color 010ms linear;
    -ms-transition: background-color 100ms linear;
    transition: background-color 100ms linear;
}	
	
.tbl-bhbs tr:hover{
    background-color: #f8fbff;
}

	
	
.tbl-bhbs th{
    
    background-color: #efefef;
    font-size: 16px;
}

.tbl-bhbs td:first-child, .tbl-bhbs th{
    border-right:1px solid #efefef;
}

/* gaps */
.tbl-bhbs td.gp-1{padding-left: 30px;}
.tbl-bhbs td.gp-2{padding-left: 60px;}
.tbl-bhbs td.gp-3{padding-left: 90px;}
.tbl-bhbs td.gp-4{padding-left: 120px;}


.total-title, .total-amount{font-weight: bold;}
.total-amount{border-bottom: 3px solid #000!important;}
.last-amount{border-bottom: 1px solid #000!important;}	

.tbl-bhbs .end-parent{
    background-color: #f4e7e7;
    font-size: 16px;
}

.tbl-bhbs .end-parent .total-amount{
    border-bottom: 3px solid #000!important;
}

.tbl-bhbs .end-parent .total-amount{
    padding: 0px;
}

.tbl-bhbs .end-parent span{
    display: block;
    margin-bottom:2px!important;
    border-bottom:2px solid #111;
    padding: 5px;
}
	
.tbl-bhbs .end-parent.assets{ background-color: #cadcf8;}
.tbl-bhbs .end-parent.liabilities{ background-color: #f8caca;}
	
	
</style>


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
      			<!--<div class="panel-heading"><h1>All Hr Action</h1></div>-->
    				<div class="panel-body">

    <span class="alertmsg">
    </span>

                	<form method="post" action="rpt_balance_sheet_new.php?pg=1&mod=7" id="form1">

                     <div class="well list-top-controls">
                    
                          <div class="row boarder">
                              <div class="col-sm-3 text-nowrap">
                            <h6>Account <i class="fa fa-angle-right"></i>Report<i class="fa fa-angle-right"></i>Balance Sheet Report</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">

                            
                                <div class="form-group">
                                    <select name="cmbyr" id="cmbyr" class="form-control" required>
<?php $yr=date("Y");?>          
                                        <option value="<? echo $yr-1; ?>" <? if ($pyr == $yr-1) { echo "selected"; } ?>><? echo $yr-1; ?></option>
                                        <option value="<? echo $yr; ?>" <? if ($pyr == $yr) { echo "selected"; } ?>><? echo $yr; ?></option>
                                        <option value="<? echo $yr+1; ?>" <? if ($pyr == $yr+1) { echo "selected"; } ?>><? echo $yr+1; ?></option>
                                    </select>
                                </div>
                               
                                <div class="form-group">
                                        <select name="cmbmonth" id="cmbmonth" class="form-control" required>
<?php $mon= date('F');for($i=1;$i<=12;$i++){?>          
                                            <option value="<? echo  str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <? if (str_pad($i, 2, "0", STR_PAD_LEFT) == $pmn) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
<?php } ?>                    
                                        </select>
                                    </div>
                                

                            
                            <div class="form-group">
                                <button type="submit" title="View data"  id="vew"  name="view"  class="form-control btn btn-default"><i class="fa fa-search"></i></button>
                            </div>
                            <div class="form-group">
                            
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                            </div>

                            
                        </div>

                        </div>
      <div class="col-lg-8 col-md-12">			  
			  
<div class="bhbs-wrapper">
		<div class="bhbs-header">
        	<h2><?= $comname ?></h2>
			<h1>Balance Sheet</h1>
		</div>       
        	<div class="tbl-bhbs-wrapper">
				
				<table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody>
			
					<tr>
					  <th>ASSETS</th>
					  <th>Mar-16-22</th>
					</tr>
				<?php 
				
			    //Sub Query
                $assetqry = "select @asset=COALESCE(closingBal,0) asset from coa_mon where glno='100000000' and mn='$pmn' and yr='$pyr'";
                $assetresult = mysqli_query($con, $assetqry);
                while ($assetrow = mysqli_fetch_assoc($assetresult)){
                    $asset = $assetrow["asset"];
                }
                
                $liabilityqry = "select @liability =COALESCE(closingBal,0) liability from coa_mon where glno='200000000' and mn='$pmn' and yr='$pyr'";
                $liabilityresult = mysqli_query($con, $liabilityqry);
                while ($liabilityrow = mysqli_fetch_assoc($liabilityresult)){
                    $liability = $liabilityrow["liability"];
                }
                
                $empQuery="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 1 and substring(a.glno,1,1) = 1
                                    	order by a.glno"; 
                $result = $conn->query($empQuery);
                
                while ($row = $result->fetch_assoc())
                {
                    $glLvl1 = $row["glno"];

                        $empQuery2="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 2 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl1."'
                                    	order by a.glno"; 
                $result2 = $conn->query($empQuery2);
                
                while ($row2 = $result2->fetch_assoc()) 
                {
                    $glLvl2 = $row2["glno"];
			?>
					<tr class="current-assets">
					  <td class="gp-1"><strong><?= $row2["glnm"]; ?></strong></td>
					  <td>&nbsp;</td>
					</tr>
					
					<?php
					    $empQuery3="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 3 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl2."'
                                    	order by a.glno"; 
                        $result3 = $conn->query($empQuery3);
                
                        while ($row3 = $result3->fetch_assoc()) 
                        {
                            $glLvl3 = $row3["glno"];
					?>
					
    					<tr class="cash-bank-balance">
    					  <td class="gp-2"><?= $row3["glnm"] ?></td>
    					  <td>&nbsp;</td>
    					</tr>
    					
    					
    				<?php
					    $empQuery4="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 4 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl3."'
                                    	order by a.glno";
                        $result4 = $conn->query($empQuery4);
                
                        while ($row4 = $result4->fetch_assoc()) 
                        {
                            $glLvl4 = $row4["glno"];
					?>
					    <tr class="cash-at-bank">
    					  <td class="gp-3"><?= $row4["glnm"] ?></td>
    					  <td>&nbsp;</td>
					    </tr>
					    
					    <?php
    					    $empQuery5="             select
                                            (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                            (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                            a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                        	from coa_mon a 
                                        	where substring(a.glno,1,1) in('1','2') 
                                            and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 5 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl4."'
                                        	order by a.glno";
                            $result5 = $conn->query($empQuery5);
                
                            while ($row5 = $result5->fetch_assoc()) 
                            {
                                $glLvl5 = $row5["glno"];
					    ?>
					        <tr>
        					  <td  class="gp-4"><?= $row5["glnm"] ?></td>
        					  <td><?= number_format($row5["closingBal"]) ?></td>
        					</tr>
					    
					    <?php } ?>
					    
					    <tr class="cash-at-bank">
    					  <td  class="gp-3 total-title">Total <?= $row4["glnm"] ?></td>
    					  <td class="total-amount"><?= number_format($row4["closingBal"]) ?></td>
    					</tr>
					
					<?php } ?>
					    <tr class="cash-bank-balance">
    					  <td  class="gp-2 total-title">Total <?= $row3["glnm"] ?></td>
    					  <td class="total-amount"><?= number_format($row3["closingBal"]) ?></td>
    					</tr>
					        
					
					<?php } ?>
					
					<tr class="current-assets">
					  <td  class="gp-1 total-title">Total <?= $row2["glnm"] ?></td>
					  <td class="total-amount"><?= number_format($row2["closingBal"]) ?></td>
					</tr>
					
			<?php } ?>
			
			        <tr class="end-parent assets">
					  <td  class="total-title">TOTAL <?= $row["glnm"] ?></td>
					  <td class="total-amount"><span><?= number_format($row["closingBal"]) ?></span></td>
					</tr>
			
			    <?php } ?>
			
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>


					  <th>LIABILITIES &amp; EQUITY</th>
					  <th>&nbsp;</th>
					</tr>
					
					<?php 
					
					$empQuery="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 1 and substring(a.glno,1,1) = 2
                                    	order by a.glno"; 
                $result = $conn->query($empQuery);
                
                while ($row = $result->fetch_assoc())
                {
                    $glLvl1 = $row["glno"];

                        $empQuery2="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 2 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl1."'
                                    	order by a.glno"; 
                $result2 = $conn->query($empQuery2);
                
                while ($row2 = $result2->fetch_assoc()) 
                {
                    $glLvl2 = $row2["glno"];
			?>
					<tr class="current-assets">
					  <td class="gp-1"><strong><?= $row2["glnm"]; ?></strong></td>
					  <td>&nbsp;</td>
					</tr>
					
					<?php
					    $empQuery3="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 3 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl2."'
                                    	order by a.glno"; 
                        $result3 = $conn->query($empQuery3);
                
                        while ($row3 = $result3->fetch_assoc()) 
                        {
                            $glLvl3 = $row3["glno"];
					?>
					
    					<tr class="cash-bank-balance">
    					  <td class="gp-2"><?= $row3["glnm"] ?></td>
    					  <td>&nbsp;</td>
    					</tr>
    					
    					
    				<?php
					    $empQuery4="             select
                                        (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                        (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                        a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                    	from coa_mon a 
                                    	where substring(a.glno,1,1) in('1','2') 
                                        and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 4 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl3."'
                                    	order by a.glno";
                        $result4 = $conn->query($empQuery4);
                
                        while ($row4 = $result4->fetch_assoc()) 
                        {
                            $glLvl4 = $row4["glno"];
					?>
					    <tr class="cash-at-bank">
    					  <td class="gp-3"><?= $row4["glnm"] ?></td>
    					  <td>&nbsp;</td>
					    </tr>
					    
					    <?php
    					    $empQuery5="             select
                                            (case substring(a.glno,1,1) when '1' then 'Asset' when '2' then 'Liabiality' else 'others' end) asslib,
                                            (case  substring(a.glno,1,1) when '1' then COALESCE('".$asset."',0) when '2' then COALESCE('".$liability."',0) else  0 end) assLib_amount ,
                                            a.lvl ,a.glno ,a.glnm ,a.closingBal ,STR_TO_DATE('".$fdt."','%d/%m/%Y') p
                                        	from coa_mon a 
                                        	where substring(a.glno,1,1) in('1','2') 
                                            and mn='$pmn' and yr='$pyr' and a.status='A' and a.lvl = 5 and a.glno != a.ctlgl and a.ctlgl = '".$glLvl4."'
                                        	order by a.glno";
                            $result5 = $conn->query($empQuery5);
                
                            while ($row5 = $result5->fetch_assoc()) 
                            {
                                $glLvl5 = $row5["glno"];
					    ?>
					        <tr>
        					  <td  class="gp-4"><?= $row5["glnm"] ?></td>
        					  <td><?= number_format($row5["closingBal"]) ?></td>
        					</tr>
					    
					    <?php } ?>
					    
					    <tr class="cash-at-bank">
    					  <td  class="gp-3 total-title">Total <?= $row4["glnm"] ?></td>
    					  <td class="total-amount"><?= number_format($row4["closingBal"]) ?></td>
    					</tr>
					
					<?php } ?>
					    <tr class="cash-bank-balance">
    					  <td  class="gp-2 total-title">Total <?= $row3["glnm"] ?></td>
    					  <td class="total-amount"><?= number_format($row3["closingBal"]) ?></td>
    					</tr>
					        
					
					<?php } ?>
					
					<tr class="current-assets">
					  <td  class="gp-1 total-title">Total <?= $row2["glnm"] ?></td>
					  <td class="total-amount"><?= number_format($row2["closingBal"]) ?></td>
					</tr>
					
			<?php } ?>
			

					
					<tr class="end-parent liabilities">
					  <td  class="total-title">TOTAL LIABILITIES & EQUITY</td>
					  <td class="total-amount"><span><?= number_format($row["closingBal"]) ?></span></td>
					</tr>
			
			    <?php } ?>
					
					<tr>
					  <td>&nbsp;</td>
					  <td>&nbsp;</td>
					</tr>				
				  </tbody>
				</table>
				
			</div>

</div>
             
          
    </div>
  </div>
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
include_once 'common_footer.php';
    ?>
    <?php

    if ($res == 1) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }

    if ($res == 2) {
        echo "<script type='text/javascript'>messageAlert('" . $msg . "')</script>";
    }
    ?>


    </body></html>
  <?php } ?>
