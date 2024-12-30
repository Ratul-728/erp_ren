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
    $currSection = 'item';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    if ( isset( $_POST['add'] ) ) {
           header("Location: ".$hostpath."/item.php?res=0&msg='Insert Data'&mod=1");
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
      			<div class="panel-heading"><h1>All Item</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="itemList.php" id="form1">
            
                     <div class="well list-top-controls">
                      <div class="row border">
                        <!--<div class="col-sm-11 text-nowrap"> <span>Servey ID</span>
                          <input name="search" type="text" id="search" class="search" >
                          <button class="btn btn-default" type="submit" name="filterServey" id="addServey" ><i class="glyphicon glyphicon-search"></i></button>
                        </div>-->
                        <div class="col-sm-11 text-nowrap"> 
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export" disabled >
                        </div>
                        <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                        <div class="col-sm-1">
                          <input type="submit" name="add" value="+ Add New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div>
                    </div>
    
    
    
                    <div class="table-responsive filterable">
                        <table  class="table table-striped table-hover">
                           <thead>
                            <tr class="filters">
                              <th><input type="text" class="form-control" placeholder="ID" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Item Code" disabled></th>
                              <th><input type="text" class="form-control" placeholder="Name Of Item" disabled></th> 
                              <th><input type="text" class="form-control" placeholder="Description" disabled></th>
                              <th><button class="btn btn-default btn-xs btn-filter"><span class="glyphicon glyphicon-filter"></span> Filter</button></th>
                            </tr>
                            </thead>
                            <tbody>
    <?php 
    
    $qry="SELECT `id`, `code`, `name`, `description` FROM `item`";
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
               'code' => '',
                'name' => '',
                'description' => ''
               );      
    
    
        $dbRows = 0;
        
       $result = $conn->query($qry); 
       if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) 
          { 
                $uid=$row["id"];
                $sl++;
                $seturl="item.php?res=4&msg='Update Data'&id=".$uid;
               
     ?>                        
                            
                            <tr>
                              <td><?php echo $sl;?></td>
                              <td><?php echo $row["code"];?></td>
                              <td><?php echo $row["name"];?></td>
                              <td><?php echo $row["description"];?></td>
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
        $npg=$nrows/150;
        //echo $npg;die;
    ?>
                    <div class="pull-left">
                        Showing <?echo $limitst;?> to <? echo $maxrows+$limitst; ?> of <?=$nrows->num_rows?> entries
                        <?php
                             $conn->close();
                        ?>
                        
                    </div>
                    <div class="pull-right">
                        <ul class="pagination " style="border: 0px solid #000000; margin-top: 0px;">
                          <li id="datatable3_previous" class="paginate_button previous disabled"><a tabindex="0" data-dt-idx="0" aria-controls="datatable3" href="#">Previous</a></li>
                          <?php while($npg>1){ ?>
                          <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="supplierList.php?pg="<?php echo $npg;?>""><?php echo $npg;?></a></li>
                          <?php }?>
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
