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
    $currSection = 'productin';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/productin.php?res=0&msg='Insert Data'");
    }
    if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'SL.')
                ->setCellValue('B1', 'PO NO')
                ->setCellValue('C1', 'SUPPLIER')
                ->setCellValue('D1', 'ORDER DATE')
    			->setCellValue('E1', 'TOTAL AMOUNT')
    			 ->setCellValue('F1', 'VAT')
                ->setCellValue('G1', 'TAX')
                 ->setCellValue('H1', 'INVOICE AMOUNT')
                ->setCellValue('I1', 'DELIVERY DATE'); 
    			
        $firststyle='A2';
        $qry="SELECT  p.`poid`,s.`name` , p.`orderdt`, p.`tot_amount`,p.`vat`,p.`tax`, p.`invoice_amount`,p.`delivery_dt` FROM `po` p,`suplier` s  WHERE p.supid=s.id order by p.`id`"; 
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
    			            ->setCellValue($col2, $row['poid'])
    						->setCellValue($col3, $row['name'])
    					    ->setCellValue($col4, $row['orderdt'])
    					     ->setCellValue($col5, $row['tot_amount'])
    					     ->setCellValue($col6, $row['vat'])
    					    ->setCellValue($col7, $row['tax'])
    					    ->setCellValue($col8, $row['invoice_amount'])
    					    ->setCellValue($col9, $row['delivery_dt']);	/* */
    			$laststyle=$title;	
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('PO');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'po_'.$today.'.xls'; 
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
        <span>All Stock In</span>
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
      			<div class="panel-heading"><h1>Stock in  Product</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="productList.php" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                       
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Create New Product " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>
                    </div>
                    
                    <div class="table-responsive filterable">
                        <table  class="table table-grid table-striped table-hover">
                            <thead>
                                     <tr class="filters">
                                        <th>Sl</th>
                                        <th>Picture</th>
                                          <th><a href="#">Manufacture Code</a></th>
                                          <th><a href="#">Model Code</a></th>
                                          <th><a href="#">Product Name</a></th>
                                          <th><a href="#">Color</a></th>
                                          <th><a href="#">Size</a></th>
                                          <th><a href="#">Pattern</a></th>
                                          <th><a href="#">Catagory</a></th>
                                          <th><a href="#">Quantity </a></th>
                                          <th><a href="#">Date </a></th>
                                          <th><a href="#">Remarks </a></th>
                                          <th>&nbsp;</th>
                                    </tr>
                            </thead>
                            <tbody>  
    <?php 
    
    $qry="SELECT i.`id`,m.mocode moid, p.modelCode `productid`,p.productName,c.Name color,p.size,pt.Name pattern,p.cost,i.`quantity`, i.`Remarks`,  DATE_FORMAT(i.makedt, '%d/%m/%Y') dt,p.prodPhoto FROM `productIn` i,`product` p,mo m,color c,pattern pt WHERE i.`productid`=p.id and m.id=i.moid and p.color=c.id and p.pattern=pt.id order by i.`id` asc LIMIT ".$limitst. ",".$limitnd;
    $sl=0;
    //echo $qry; die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    else
    {
           $inputData = array(
               'id' => '',
               'moid' => '',
               'productid' => '',
               'productName' => '',
               'color' => '',
               'size' => '',
               'pattern' => '',
               'quantity' => '',
               'dt' => '',
               'prodPhoto' => '',
               'Remarks' => ''
               );      
    
    
        $dbRows = 0;
        
       $result = $conn->query($qry); 
       if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
          { 
                $uid=$row["id"];
                $sl++;
                $seturl="productin.php?res=4&msg='Update Data'&id=".$uid;
                  $photo="common/upload/product/".$row["productid"].".jpg";
               
     ?>                        
                            
                            <tr>
                               <td><?php echo $sl;?></td>
                                <td><img src=<?php echo $photo; ?> width="100" height="100"></td>
                               <td><?php echo $row["moid"]?></td>
                              <td><?php echo $row["productid"]?></td>
                              <td><?php echo $row["productName"];?></td>
                              <td><?php echo $row["color"];?></td>
                              <td><?php echo $row["size"];?></td>
                              <td><?php echo $row["pattern"];?></td>
                              <td><?php echo $row["quantity"];?></td>
                               <td><?php echo $row["dt"];?></td>
                                <td><?php echo $row["Remarks"];?></td>
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
                          <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="productinList.php?pg=1">1</a></li>
                          <li class="paginate_button <?php if ($pgcnt==2){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="2" aria-controls="datatable3" href="productinList.php?pg=2">2</a></li>
                          <li class="paginate_button <?php if ($pgcnt==3){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="3" aria-controls="datatable3" href="productinList.php?pg=3">3</a></li>
                          <li class="paginate_button <?php if ($pgcnt==4){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="4" aria-controls="datatable3" href="productinList.php?pg=4">4</a></li>
                          <li class="paginate_button <?php if ($pgcnt==5){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="5" aria-controls="datatable3" href="productinList.php?pg=5">5</a></li>
                         
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
