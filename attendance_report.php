<?php
require "common/conn.php";


session_start();

//ini_set('display_errors',1);
$usr=$_SESSION["user"];

//echo $usr;die;

$res= $_GET['res'];
$msg= $_GET['msg'];








if($usr=='')
{ 	header("Location: ".$hostpath."/hr.php");
}
else
{
	

    $currSection = 'qaresult';
	// load session privilege;
	//include_once('common/inc_session_privilege.php');
	//echo '<pre>'; 	print_r($_SESSION);die;		echo '</pre>';	
	
    $currPage = basename($_SERVER['PHP_SELF']);
    

   
    
    
    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
     include_once('common_header.php');
    ?>
    <style>

        h1.report-title{
            font-family: roboto;
            text-transform: uppercase;
            font-size: 25px;
            margin-top: 10px;
            margin-bottom: 0px;
        }
        .attn-table-header{
            border: 1px solid #bbb9b9;
        }
        
        .attn-table-top{
            background-color: rgba(236,236,236,0.37);
            padding: 10px;
            margin-bottom:10px;
        }
        
        .attn-table-header td{
            padding: 10px;
            font-size: 13px;
        }
        .attn-table.table th{
            background-color: var(--theme)!important;
            color:#ffffff!important;
        }
        
        .attn-table th:nth-child(1){ text-align: center; width: 60px;white-space: nowrap;}
        .attn-table.table td:nth-child(8){ width: 60px; padding-left: 5px!important;}
        
        .attn-table th:nth-child(8)
        { width: 250px; white-space: nowrap;}
        
        .attn-table td:nth-child(1){ text-align: center;}
        
        .attn-table td{border-bottom:1px solid #dbd9d9!important;}
    
    
    </style>
    <body class="list">
        
    <?php
     include_once('common_top_body.php');
    ?>
    
    <div id="wrapper"> 
    
      <!-- Sidebar -->
    
      <div id="sidebar-wrapper" class="mCustomScrollbar">
      
      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>QA Test</span>
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
      			<!--<div class="panel-heading"><h1>All Service Order(Item)</h1></div>-->
    				<div class="panel-body">
    
        <span class="alertmsg">
        </span>
    
    
 
                	<form method="post" action="quotationList.php?mod=2" id="form1">
            
                     <div class="well list-top-controls"> 
                      <div class="row border">
                          
                          
                         
                          
                       <div class="col-sm-3 text-nowrap">
                            <h6>QA Test <i class="fa fa-angle-right"></i> Sold Items <i class="fa fa-angle-right"></i> Order ID: <?= $orderId ?> </h6>
                       </div>
                      
                       
                       
                        <div class="col-sm-9 text-nowrap"> 
                        
                            <div class="pull-right grid-panel form-inline d-none">

                                    <div class="form-group">
                                        <label for="">Filter by: </label>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-group styled-select">
                                            <select name="cmbstatus" id="cmbstatus" class="form-control" >
                                                <option value="0">All Status</option>
        <?php
    $qry1    = "select id,name from quotation_status order by name";
        $result1 = $conn->query($qry1);if ($result1->num_rows > 0) {while ($row1 = $result1->fetch_assoc()) {
            $tid = $row1["id"];
            $nm  = $row1["name"];
            ?>
                                                <option value="<?php echo $tid; ?>" <?php if ($icat == $tid) {echo "selected";} ?> ><?php echo $nm; ?></option>
        <?php }} ?>
                                            </select>
                                        </div>
                                    </div> 



                                <div class="form-group">
                                <input type="search" id="search-dttable" class="form-control">     
                                </div>
                                <div class="form-group">
                                <button type="submit" title="Create New"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-plus"></i></button>
                                </div>
                                <div class="form-group">
                                <!--input class="btn btn-default form-control" type="submit" name="export" value=" Export Data" id="export"  -->
                                <button type="submit" title="Export" name="export" id="export" class="form-control btn btn-default"><i class="fa fa-download"></i></button>
                                </div>

                                <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                            </div>
                        
                        </div>
                        
                        
                      </div>
                    </div>
                    
    
    				</form>

                        

			 <div class="row" id="printArea">
			     
				<div class="col-sm-12">
				    <div class="text-center attn-table-top">
				        <h1 class="report-title">Renaissance Decor Ltd</h1>
				        <table border="0" width="100%">
				            <tr>
				                <td width="15%">
				                    &nbsp;
				                </td>
				                <td width="70%">
				                    <span>Ashfia Tower, Level#4, Plot#76, road#11, Block-E, Banani.</span>
				                    <h4>Attendence Report For The Month Of November-2023</h4>
				                </td>
				                <td width="15%" valign="bottom" nowrap>
				                    <h4>DATE: 21-Feb-2024</h4>
				                </td>
				            </tr>
				        </table>
				    </div>
				    
			        <table border="0" width="100%" class="attn-table-header">
			            <tr>
			                <td width="100" valign="top">
			                  Employee ID<br>
			                  Designation<br>
			                  Section<br>
			                </td>
			                <td width="300" valign="top">
			                 : EMP-000001<br>
			                 : Assistant General Manager<br>
			                 : General<br>
			                </td>
			                <td width="100" valign="top">
                                Name<br>
                                Department<br>
                                TIN No<br>
                                Join Date<br>
			                </td>
			                <td valign="top">
                                : Salim Miah<br>
                                : Accounts & Finance<br>
                                : 122466311544<br>
                                : 1/14/2019<br>
			                </td>
			                <td width="80" valign="bottom" nowrap>
			                  <img src="images/profile_picture/selim.png">
			                </td>
			            </tr>
			        </table>
				     																					STATUS													

   
				        
				    <table width="100%" border="0" class="attn-table table table-striped">
				        <thead>
				        <tr>
				            <th>Sl. No</th>
				            <th>Date</th>
				            <th>Work Shift</th>
				            <th>In Time</th>
				            <th>Out Time</th>
				            <th>Late Minute</th>
				            <th>Status</th>
				            <th>Remarks</th>
				        </tr>
				        </thead>
				        <tbody>
				        <tr>
				            <td>1</td>
				            <td>25-11-2023</td>
				            <td>General</td>
				            <td>8:58:48 AM</td>
				            <td>7:54:38 PM</td>
				            <td>00h : 56m</td>
				            <td>Late</td>
				            <td>Day Off</td>
				        </tr>
				        <tr>
				            <td>1</td>
				            <td>25-11-2023</td>
				            <td>General</td>
				            <td>8:58:48 AM</td>
				            <td>7:54:38 PM</td>
				            <td>00h : 56m</td>
				            <td>Late</td>
				            <td>Day Off</td>
				        </tr>
				        <tr>
				            <td>1</td>
				            <td>25-11-2023</td>
				            <td>General</td>
				            <td>8:58:48 AM</td>
				            <td>7:54:38 PM</td>
				            <td>00h : 56m</td>
				            <td>Late</td>
				            <td>Day Off</td>
				        </tr>
				        <tr>

				            <td colspan="4"></td>
				            <td><b>Total Late Minute:</b></td>
				            <td><b>00h : 56m</b></td>
				            <td colspan="2"></td>
				            
				        </tr>
				        
				        </tbody>
				    </table>
				    
				    <div><h4>Attendence Summary:</h4></div>
			        <table border="0" width="100%" class="attn-table-header">
			            <tr>
			                <td width="110" nowrap>
			                  Days of Month<br>
			                  Present Days<br>
			                </td>
			                <td>
			                 : 29<br>
			                 : 20<br>
			                </td>
			                <td  width="110" nowrap>
                                Working Days<br>
                                Late Present<br>
			                </td>
			                <td valign="top">
                                : 20<br>
                                : 1<br>
			                </td>
			                <td  width="110" nowrap>
                                Weekend Days<br>
                                Leave Days<br>
			                </td>
			                <td valign="top">
                                : 10<br>
                                : 0<br>
			                </td>
			                <td  width="110" nowrap>
                                Holidays<br>
                                Absent Days<br>
			                </td>
			                <td valign="top">
                                : 0<br>
                                : 0<br>
			                </td>
			            </tr>
			        </table>
			        <br><br><br>
				   <table width="100%" class="attn-signature">
				       <tr>
				           <td><span>Signature of Employee</span></td>
				           <td align="right"><span>Authorized Signature</span></td>
				       </tr>
				   </table>
				   <br>

				    
				</div>
				 
     
              
		 
        				 
				 
			</div>
                   
       				   <div class="well" style="padding:5px">
				       <input type="button" onclick="printDiv('printArea')" class="btn btn-lg btn-primary" value="Print">
				   </div>                 
    <br>
    <br>
    <br>
                        
    				
    
                 </div>
            </div> 
            <!-- /#end of panel -->  
    
              <!-- START PLACING YOUR CONTENT HERE -->          
              </p>
            </div>
          </div>
        </div>
        
        <footer>
      <?php
        include_once('common_footer.php');
      ?>   
        </footer>
      </div><!-- page-content-wrapper -->
      
      
  
      
    </div>
    <!-- /#wrapper -->
    

		

		
		
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
		
     <script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        // Set the content of the body to the content of the div
        document.body.innerHTML = printContents;

        // Print the page
        window.print();

        // Restore the original content
        document.body.innerHTML = originalContents;
    }
</script>  
    
    </body></html>
  <?php }?>    
