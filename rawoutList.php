<?php
require "common/conn.php";
$pgcnt= $_GET['pg'];
$limitst=($pgcnt-1)*150+1;
$limitnd=150;
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'rawout';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/rawout.php?res=0&msg='Insert Data'");
    }
    /*if ( isset( $_POST['filterServey'] ) ) {
         $filter=$_REQUEST['search'];
    }
    */
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
        <span>All Items</span>
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
      		<!--	<div class="panel-heading"><h1>Raw Item Out List</h1></div> -->
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
   
                	<form method="post" action="rawout.php" id="form1"> 
            
                     <div class="well list-top-controls">
                      <!--<div class="row border">
                        <!--<div class="col-sm-11 text-nowrap"> <span>Servey ID</span>
                          <input name="search" type="text" id="search" class="search" >
                          <button class="btn btn-default" type="submit" name="filterServey" id="addServey" ><i class="glyphicon glyphicon-search"></i></button>
                        </div>-->
                        <!--<div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export" disabled >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Add New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->
                        <div class="row border">
                          
                          
                          
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>Products <i class="fa fa-angle-right"></i>Raw Item Out List</h6>
                       </div>
                       
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                        <div class="pull-right grid-panel form-inline">
                           <!-- <div class="form-group">
                            <input type="search" id="search-dttable" class="form-control">     
                            </div> -->
                            <div class="form-group">
                            <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                            </div>
                           <!-- <div class="form-group">
                            <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  
                            <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                            </div> -->

                            <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
    
    
    
                    <div class="table-responsive filterable">
                        <table  class="table table-striped table-hover">
                           <thead>
                            <tr class="filters">
                              <th><input type="text" class="form-control" placeholder="Sl" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Item" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Picture" disabled></th> 
                              <th><input type="text" class="form-control" placeholder="Unit" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Quantity" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Store" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Out By" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Reason" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Reference" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Date" disabled></th>
                              <th><button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button></th>
                            </tr>
                            </thead>
                            <tbody>
    <?php 
    
    $qry="SELECT o.`id`, i.`name` item,i.`image`, m.`name` `mu`, o.`qty`,s.`name` `store`, h.hrName `outby`,(case o.`reason` when 1 then 'Transfer' when 2 then 'Factory' else 'Others' end) reason, o.`reference`, date_format(o.`trdate`,'%d/%m/%y') dt FROM `rawout` o,`item` i,`store` s,`hr` h,`mu` m where o.itemid=i.id and o.mu=m.id and o.storeid=s.id and o.outby=h.id";
    /*if($filter!='')
    {
    $qry=" ".$qry." and id= ".$filter;   
    }
    */
    $sl=0;
    //echo $qry; die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    else
    {
           $inputData = array(
               'id' => '',
               'item' => '',
                'image' => '',
                'mu' => '',
                'qty' => '',
                'store' => '',
                'outby' => '',
                'reason' => '',
                'reference' => '',
                'dt' => ''
               );      
    
    
        $dbRows = 0;
        
       $result = $conn->query($qry); 
       if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
          { 
                $uid=$row["id"];
                $sl++;
                $seturl="rawout.php?res=4&msg='Update Data'&id=".$uid;
                $photo="common/upload/item/".$row["image"].".jpg";
     ?>                        
                            
                            <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $row["item"];?></td>
                              
                              <td><img src=<?php echo $photo; ?> width="100" height="100"></td>
                              
                              <td><?php echo $row["mu"];?></td>
                              <td><?php echo $row["qty"];?></td>
                              <td><?php echo $row["store"];?></td>
                              <td><?php echo $row["outby"];?></td>
                              <td><?php echo $row["reason"];?></td>
                              <td><?php echo $row["reference"];?></td>
                              <td><?php echo $row["dt"];?></td>
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
                          <?php while($npg>0){ ?>
                          <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="supplierList.php?pg="<?php echo $npg;?>""><?php echo $npg;?></a></li>
                          <?php $npg--;}?>
                          <li id="datatable3_next" class="paginate_button next"><a tabindex="0" data-dt-idx="3" aria-controls="datatable3" href="#">Next</a></li>
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
