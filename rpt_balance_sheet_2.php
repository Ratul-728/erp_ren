<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr=='')
{ 
	header("Location: ".$hostpath."/hr.php");
}
else{

}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>
<body class="dashboard">
<?php include_once 'common_top_body.php'; ?>
<div id="wrapper"> 
  <!-- Sidebar -->

  <div id="sidebar-wrapper" class="mCustomScrollbar">
  
  <div class="section">
  	<i class="fa fa-group  icon"></i>
    <span>Buiesness POS</span>
  </div>
  <?php
    include_once('menu.php');
	
?>
	<div style="height:54px;">
	</div>
    
    
  </div>
  <!-- /#sidebar-wrapper --> 
  
  
  
  
  
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

    <div class="row">
      <div class="col-lg-8 col-md-12">			  
			  
<div class="bhbs-wrapper">
		<div class="bhbs-header">
        	<h2>Renaissance Decor</h2>
			<h1>Balance Sheet</h1>
		</div>       
        	<div class="tbl-bhbs-wrapper">
				
				<table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tbody>
					<tr>
					  <th>ASSETS</th>
					  <th>&nbsp;</th>
					  <!--th>Mar-16-22</th-->
					</tr>
					<?php 
					$lvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='100000000' and c.yr='2022' and c.mn='05'";
					$result2 = $conn->query($lvl2);
                    if ($result2->num_rows > 0) 
                    {
                        while ($row2 = $result2->fetch_assoc())
                        {
                            $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$closingbal2= $row2["closingbal"];
					?>
        					<tr class="cur rent-assets">
        					  <td class="gp-1"><strong><?php echo $glnm2;?></strong></td>
        					  <td>&nbsp;</td>
        					</tr>
        			<?php 
        			        $lvl3="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl2 and c.yr='2022' and c.mn='05'";
    					    $result3 = $conn->query($lvl3);
                            if ($result3->num_rows > 0) 
                            {
                                while ($row3 = $result3->fetch_assoc())
                                {
                                    $gl3= $row3["glno"]; $glnm3= $row3["glnm"];$closingbal3= $row3["closingbal"];
                    ?>  
                                    <tr class="cash-bank-balance">
            					        <td class="gp-2"><?php echo $glnm3;?></td>
                					    <td>&nbsp;</td>
            					    </tr> 
				    <?php 
            			            $lvl4="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl3 and c.yr='2022' and c.mn='05'";
        					        $result4 = $conn->query($lvl4);
                                    if ($result4->num_rows > 0) 
                                    {
                                        while ($row4 = $result4->fetch_assoc())
                                        {
                                            $gl4= $row4["glno"]; $glnm4= $row4["glnm"];$closingbal4= $row4["closingbal"];
                        ?>  
                                            <tr class="cash-bank-balance">
                					            <td class="gp-3"><?php echo $glnm4;?></td>
                    					        <td>&nbsp;</td>
                					        </tr>  
    		        <?php 
                			                $lvl5="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl4 and c.yr='2022' and c.mn='05'";
                					        $result5 = $conn->query($lvl5);
                                            if ($result5->num_rows > 0) 
                                            {
                                                while ($row5 = $result5->fetch_assoc())
                                                {
                                                    $gl5= $row5["glno"]; $glnm5= $row5["glnm"];$closingbal5= $row5["closingbal"];
                    ?>  
                                                    <tr class="cash-bank-balance">
                        					            <td class="gp-4"><?php echo $glnm5;?></td>
                            					        <td><?php echo $closingbal5;?></td>
                        					        </tr>  
        		    <?php
                                                }
                                            }
                    ?>                        
                                            <tr class="govment-authority">
					                            <td  class="gp-3 total-title">Total <?php echo $glnm4;?></td>
					                            <td class="total-amount"><?php echo $closingbal4;?></td>
				                            </tr>
					<?php
                                        }
                                    }
                    ?>
                                    <tr class="accounts-payable">
				                        <td  class="gp-2 total-title">Total <?php echo $glnm3;?></td>
					                    <td class="total-amount"><?php echo $closingbal3;?></td>
					                </tr>
                    <?php
                                }
                            }
                    ?>
                                <tr class="current-liabilities">
					                <td  class="gp-1 total-title">Total <?php echo $glnm2;?></td>
					                <td class="total-amount"><?php echo $closingbal2;?></td>
				                </tr>
                    <?php
        			    }
        			}
        			?>
        			<tr class="end-parent liabilities">
					  <td  class="total-title">TOTAL ASSET</td>
					  <?php
					    $lvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='100000000' and c.yr='2022' and c.mn='05'";
					    $result1 = $conn->query($lvl1);
                        $row1 = $result1->fetch_assoc();
                        $closingbal1= $row1["closingbal"];
                            ?>
					  <td class="total-amount"><span><?php echo $closingbal1;?></span></td>
					</tr>
					
                    <tr>
					  <th>LIABILITIES &amp; EQUITY</th>
					  <th>&nbsp;</th>
					</tr>
					<?php 
					$lvl2="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl='200000000' and c.yr='2022' and c.mn='05'";
					$result2 = $conn->query($lvl2);
                    if ($result2->num_rows > 0) 
                    {
                        while ($row2 = $result2->fetch_assoc())
                        {
                            $gl2= $row2["glno"]; $glnm2= $row2["glnm"];$closingbal2= $row2["closingbal"];
					?>
        					<tr class="cur rent-assets">
        					  <td class="gp-1"><strong><?php echo $glnm2;?></strong></td>
        					  <td>&nbsp;</td>
        					</tr>
        			<?php 
        			        $lvl3="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl2 and c.yr='2022' and c.mn='05'";
    					    $result3 = $conn->query($lvl3);
                            if ($result3->num_rows > 0) 
                            {
                                while ($row3 = $result3->fetch_assoc())
                                {
                                    $gl3= $row3["glno"]; $glnm3= $row3["glnm"];$closingbal3= $row3["closingbal"];
                    ?>  
                                    <tr class="cash-bank-balance">
            					        <td class="gp-2"><?php echo $glnm3;?></td>
                					    <td>&nbsp;</td>
            					    </tr> 
				    <?php 
            			            $lvl4="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl3 and c.yr='2022' and c.mn='05'";
        					        $result4 = $conn->query($lvl4);
                                    if ($result4->num_rows > 0) 
                                    {
                                        while ($row4 = $result4->fetch_assoc())
                                        {
                                            $gl4= $row4["glno"]; $glnm4= $row4["glnm"];$closingbal4= $row4["closingbal"];
                        ?>  
                                            <tr class="cash-bank-balance">
                					            <td class="gp-3"><?php echo $glnm4;?></td>
                    					        <td>&nbsp;</td>
                					        </tr>  
    		        <?php 
                			                $lvl5="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.ctlgl=$gl4 and c.yr='2022' and c.mn='05'";
                					        $result5 = $conn->query($lvl5);
                                            if ($result5->num_rows > 0) 
                                            {
                                                while ($row5 = $result5->fetch_assoc())
                                                {
                                                    $gl5= $row5["glno"]; $glnm5= $row5["glnm"];$closingbal5= $row5["closingbal"];
                    ?>  
                                                    <tr class="cash-bank-balance">
                        					            <td class="gp-4"><?php echo $glnm5;?></td>
                            					        <td><?php echo $closingbal5;?></td>
                        					        </tr>  
        		    <?php
                                                }
                                            }
                    ?>                        
                                            <tr class="govment-authority">
					                            <td  class="gp-3 total-title">Total <?php echo $glnm4;?></td>
					                            <td class="total-amount"><?php echo $closingbal4;?></td>
				                            </tr>
					<?php
                                        }
                                    }
                    ?>
                                    <tr class="accounts-payable">
				                        <td  class="gp-2 total-title">Total <?php echo $glnm3;?></td>
					                    <td class="total-amount"><?php echo $closingbal3;?></td>
					                </tr>
                    <?php
                                }
                            }
                    ?>
                                <tr class="current-liabilities">
					                <td  class="gp-1 total-title">Total <?php echo $glnm2;?></td>
					                <td class="total-amount"><?php echo $closingbal2;?></td>
				                </tr>
                    <?php
        			    }
        			}
        			?>
        			<tr class="end-parent liabilities">
					  <td  class="total-title">TOTAL LIABILITIES</td>
					  <?php
					    $lvl1="SELECT c.glno,c.glnm,c.closingbal FROM `coa_mon` c  where c.glno='200000000' and c.yr='2022' and c.mn='05'";
					    $result1 = $conn->query($lvl1);
                        $row1 = $result1->fetch_assoc();
                        $closingbal1= $row1["closingbal"];
                            ?>
					  <td class="total-amount"><span><?php echo $closingbal1;?></span></td>
					</tr>
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



          <!-- START PLACING YOUR CONTENT HERE -->          
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /#page-content-wrapper -->



<!-- #page-footer -->
<?php include_once 'common_footer.php'; ?>
<script>


$(".tbl-bhbs tr").mouseover(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background-color","#E6F0FF");
 	 	//$("."+thisClass).css("font-weight","bold");
  
});

$(".tbl-bhbs tr").mouseleave(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background","transparent");
 		// $("."+thisClass).css("font-weight","normal");
});
	
	
</script>


  <!-- END FLOT CHART--> 
</body></html>
