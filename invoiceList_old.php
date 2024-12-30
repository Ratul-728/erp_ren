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
        <span>Invoice List</span>
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
      			<div class="panel-heading"><h1>Invoice List</h1></div>
    				<div class="panel-body">
    
    <span class="alertmsg">
    </span>
    <br>
                	<form method="post" action="" id="form1">
            

                    
                    
						
						
                 <div class="well list-top-controls">
                  <div class="row border">
                    <div class="col-sm-11 text-nowrap"> 
                      <input name="search" placeholder="Operator Name" type="text" id="search" class="search" >
					  <input name="search" placeholder="Operator Type" type="text" id="search" class="search" >
				      <input name="search" autocomplete="off" placeholder="Start Date" type="text" id="search" class="search datetimepicker" >
					  <input name="search" autocomplete="off" placeholder="End Date" type="text" id="search" class="search datetimepicker" >
                      <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                    </div>
                    <div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div>
                    <div class="col-sm-1">
                      <input type="submit" name="create_customer" value="Download" id="create_customer" class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                    </div>
                  </div>
                </div>						
						
						
						
						
                        <table  class="table table-grid table-striped table-hover">
                                <thead>
                                     <tr>
										 <th><input type="checkbox"  name="1" class="mycheckbox" value="1"></th>
                                        <th>Sl</th>
                                        <th>Organization</th>
                                        <th>Invoice No.</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Invoice Amount</th>
                                        <th>Paid Amount</th>
                                        <th>Due Amount</th>
                                        <th>Due Date</th>
										<th>Invoice Status</th>
										<th>Payment Status</th>
										<th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
									
                                <tr>
									<td><input type="checkbox"  class="checkbox" name="1" value="1"></td>
                                    <td>1</td>
                                    <td>InterCloud Limited</td>
                                    <td>INT001001</td>
                                    <td>11/1/2019</td>
                                    <td>11/1/2019</td>
                                    <td>100000</td>
                                    <td>0</td>
                                    <td>100000</td>
                                    <td>11/1/2019</td>
									<td>Sent</td>
									<td><kbd class="due">Due</kbd></td>
									<td nowrap class="action">
										<span>
											<a href="#" class="invoice-view" title="View"><i class="fa fa-search"></i></a>
											<a href="#" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>
											<a href="#" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>
											<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a>
										</span>								</td>
                                </tr>
                                <tr>
									<td><input type="checkbox"  class="checkbox" name="1" value="1"></td>
                                    <td>1</td>
                                    <td>InterCloud Limited</td>
                                    <td>INT001001</td>
                                    <td>11/1/2019</td>
                                    <td>11/1/2019</td>
                                    <td>100000</td>
                                    <td>0</td>
                                    <td>100000</td>
                                    <td>11/1/2019</td>
									<td>Sent</td>
									<td><kbd class="over-due">Over Due</kbd></td>
									<td nowrap class="action">
										<span>
											<a href="#" class="invoice-view" title="View"><i class="fa fa-search"></i></a>
											<a href="#" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>
											<a href="#" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>
											<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a>
										</span>
									</td>
										
                                </tr>
                                <tr>
									<td><input type="checkbox"  class="checkbox" name="1" value="1"></td>
                                    <td>1</td>
                                    <td>InterCloud Limited</td>
                                    <td>INT001001</td>
                                    <td>11/1/2019</td>
                                    <td>11/1/2019</td>
                                    <td>100000</td>
                                    <td>0</td>
                                    <td>100000</td>
                                    <td>11/1/2019</td>
									<td>Sent</td>
									<td><kbd class="over-paid">Over Paid</kbd></td>
									<td nowrap class="action">
										<span>
											<a href="#" class="invoice-view" title="View"><i class="fa fa-search"></i></a>
											<a href="#" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>
											<a href="#" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>
											<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a>
										</span>
									</td>
										
                                </tr>
                                <tr>
									<td><input type="checkbox"  class="checkbox" name="1" value="1"></td>
                                    <td>1</td>
                                    <td>InterCloud Limited</td>
                                    <td>INT001001</td>
                                    <td>11/1/2019</td>
                                    <td>11/1/2019</td>
                                    <td>100000</td>
                                    <td>0</td>
                                    <td>100000</td>
                                    <td>11/1/2019</td>
									<td>Sent</td>
									<td><kbd class="paid">Paid</kbd></td>
									<td nowrap class="action">
										<span>
											<a href="#" class="invoice-view" title="View"><i class="fa fa-search"></i></a>
											<a href="#" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>
											<a href="#" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>
											<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a>
										</span>
									</td>
										
                                </tr>									
                        </tbody>
                    </table>
               									
                           
    &nbsp;&nbsp;<br>

                       

    
    
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
                          <li class="paginate_button <?php if ($pgcnt==1){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="1" aria-controls="datatable3" href="poList.php?pg=1">1</a></li>
                          <li class="paginate_button <?php if ($pgcnt==2){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="2" aria-controls="datatable3" href="poList.php?pg=2">2</a></li>
                          <li class="paginate_button <?php if ($pgcnt==3){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="3" aria-controls="datatable3" href="poList.php?pg=3">3</a></li>
                          <li class="paginate_button <?php if ($pgcnt==4){ echo 'active';} ?>"><a tabindex="0" data-dt-idx="4" aria-controls="datatable3" href="poList.php?pg=4">4</a></li>
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
<script language="javascript">

	$(document).ready(function(){
	  $('input').iCheck({
	  checkboxClass: 'icheckbox_square-blue',
	  radioClass: 'iradio_square-blue',
	  increaseArea: '20%'
	});


	$('input.mycheckbox').on('ifChecked', function(event){
	  $('input').iCheck('check'); 
	});
	
	$('input.mycheckbox').on('ifUnchecked', function(event){
	  $('input').iCheck('uncheck'); 
	});
});

</script>    
		
    </body></html>
  <?php }?>    
