<?php
require "common/conn.php";

session_start();
$usr = $_SESSION["user"];
$res = $_GET['res'];
$msg = $_GET['msg'];

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    
    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'requisition';
    $currPage    = basename($_SERVER['PHP_SELF']);


    

    ?>
    <!doctype html>
    <html xmlns="http://www.w3.org/1999/xhtml">
    <?php
include_once 'common_header.php';
    ?>

<style>
 .styled-select{
    padding-right: 40px;
}
    

_.cost-sheet td{border: 0px;}
_.venrow .box{
    border: 5px solid #fff;
    padding: 8px;
}

.cost-sheet tr th:first-child{
    width: 150px;
}

.cost-sheet tr.disabled{
     background-color: #e3e3e3;
}

.cost-sheet tr.disabled  *{
     background-color: #e4e4e4;
    color: #b6b6b6;
}

.cost-sheet tr td.product{
    background-color: #eeeeee;
    font-weight: bold;

}

.cost-sheet tr td.market-rate{
     background-color: #489265; 
    color: #fff;
    border: 1px solid #888787;
}




.cost-sheet .product .icheckbox_square-blue{
    margin-right: 5px;
}

.cost-sheet .product{
    position: relative;
}
.cost-sheet .product label{
    position: absolute;
    width: 100%;
    padding: 10px;;
    margin: 0;
    left: 0;
    top: 0;
    cursor: pointer;
}
 
    

.vendor{
    cursor: pointer;
}

 .vendor:hover{
    background-color: #67c064;
    color: #fff;
}

.selected-vendor.yes{
    background-color: #5ec45b;
    color: #fff;
    position: relative;
}

.selected-vendor.not{
    
    color: #333333;
    position: relative;
}

 .selected-vendor:after{
             content: '\f00c';
            color: rgb(255,255,255);
            font: normal normal normal 14px/1 FontAwesome;
            right: 15px;
            top:40%;
            position: absolute;   
}

.vendor{
        position: relative;
    }

 .vendor:hover:after {
            content: '\f00c';
            color: green;
            font: normal normal normal 14px/1 FontAwesome;
            right: 15px;
            top:40%;
            position: absolute;
 }

 .vendor.checked{
        position: relative;
     
    background-color:#afc2ae;
    color: #fff;     
    }

 .vendor.checked:after {
            content: '\f00c';
            color: green;
            font: normal normal normal 14px/1 FontAwesome;
            right: 15px;
            top:40%;
            position: absolute;
 }
  
    
    
    
.full-sreen-cart{
  right: -350px;
  position: absolute;
  position: fixed;
  z-index: 999;
  width: 320px;
  background-color: rgba(255,255,255,1);
  box-shadow: -1px 3px 4px 0px rgba(50, 50, 50, 0.38);
  visibility: hidden;
  opacity: 0;
  transform: translateX(20px);
  transition: 0.6s;
  color: #000;
  -moz-transition: all .3s ease-in-out;
    top: 0;
    bottom: 0;
}

.full-sreen-cart.expand {
  position: fixed;
  z-index: 9999;
  width: 320px;
  right: 0px !important;
}

.full-sreen-cart.expand {
  visibility: visible;
  opacity: 1;
  transform: translateX(0);
  transform: translateX(0%);
  right: 0;
}

    .panel-body{width:100%;}
    
.cost-sheet-wrapper{
   transform: translateX(0);
  transform: translateX(0%);
transform: translateX(20px);
  transition: 0.6s;    
  -moz-transition: all .3s ease-in-out;
    width:calc(100% - 320px);
    
}
    
.screen-close .fa-close{
    color: rgb(161,161,161);
}

.screen-close {
  outline: none;
  border: 0;
  position: absolute;
  top: 20px;
  right: 20px;
  line-height: 1.3;
  border: 0;
  width: 40px;
  height: 40px;
  padding: 0;
  font-size: 20px;
    font-weight: normal!important;
    
}

.rest-vendor{
    margin-top: 60px;
    height: 92vh;
    border: 1px solid #d5d5d5;
    padding: 20px;
    overflow-y: scroll;
}

.rest-vendor table{width: 100%;}

.rest-vendor table td:first-child{
 background-color:#00ABE3!important;  
    color: #fff;
    font-weight: normal;
    width:20px;
    
}

.rest-vendor  td{
    font-size: 13px;
}

.table > tbody > tr > td:last-child{
    padding: 5px!important;
}
    
    
</style>        
        
    <body class="list">

<div class="full-sreen-cart enabled">
		<button class="screen-close  rounded-crcl" type="button" style=" z-index: 2;"> <i class="fa fa-close"></i></button>
        
        <div class="clear">&nbsp;</div>
        <div class="rest-vendor">
            <input type="hidden" id="rv_prid" value="">
            <label for="">Select Other Vendor for  (iMac | ID: <span id="show_prid"></span>)</label>
            <table class="cost-sheet  table table-striped" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td>Vendor 5</td>
                    <td class="vendor" id="20">
                        <span>85000<br>
                         ABC Company 2</span>
                        <input type="hidden" class="vendorid" value="210" name="vendorid">
                    </td>
                </tr>
                <tr>
                    <td>Vendor 5</td>
                    <td class="vendor" id="20">
                        <span>85000<br>
                         ABC Company 2</span>
                        <input type="hidden" class="vendorid" value="222" name="vendorid">
                    </td>
                </tr>
                <tr>
                    <td>Vendor 5</td>
                    <td class="vendor checked" id="20">
                        <span>85000<br>
                         ABC Company 2</span>
                        <input type="hidden" class="vendorid" value="111" name="vendorid">
                    </td>
                </tr>
                <tr>
                    <td>Vendor 5</td>
                    <td class="vendor" id="20">
                        <span>85000<br>
                         ABC Company 2</span>
                        <input type="hidden" class="vendorid" value="654" name="vendorid">
                    </td>
                </tr>                
            </table>
        
        </div>
    </div>        
        
        
    <?php
include_once 'common_top_body.php';
    ?>
        

        
        
    <div id="wrapper">

      <!-- Sidebar -->

      <div id="sidebar-wrapper" class="mCustomScrollbar">

      <div class="section">
      	<i class="fa fa-group  icon"></i>
        <span>Requisition</span>
      </div>

    <?php
include_once 'menu.php';
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
      		<!--	<div class="panel-heading"><h1>All Action Type</h1></div> -->
    				<div class="panel-body ">

    <span class="alertmsg">
    </span>


                        
                            
                	<form method="post" action="requisitionList.php" id="cost_form">

                     <div class="well list-top-controls">
                      <!--<div class="row border">

                        <div class="col-xs-6 text-nowrap">
                             <input class="btn btn-default" type="submit" name="export" value=" Export Data" id="export"  >
                        </div>
                        <!--div class="hidden-lg hidden-md hidden-sm"> &nbsp; </div-->
                        <!--<div class="col-xs-6">
                          <input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l">
                        </div>
                      </div> -->
                       <div class="row border">




                       <div class="col-sm-3 text-nowrap">
                            <h6>Requisition <i class="fa fa-angle-right"> </i> Cost Sheet Comparison Report</h6>
                       </div>



                        <div class="col-sm-9 text-nowrap">

                        <div class="pull-right grid-panel form-inline">
                            <div class="form-group">
                                <div class="styled-select">
                                  <select name="cat" id="cat" class="form-control">
                                        <option>RFQ</option>
                                        <option value="1">REQ-1</option>
                                        <option value="2">REQ-2</option>
                                    </select>
                                </div>
                                
                            </div> 
                            
                            <div class="form-group">
                                <div class="form-group styled-select">
                                    <select name="cat" id="cat" class="form-control">
                                        <option>Status</option>
                                        <option value="1">Open</option>
                                        <option value="2">Closed</option>
                                    </select>
                                </div>
                               
                            </div>
                            
                            <div class="form-group">
                              <div class="styled-select">
                                    <select name="cat" id="cat" class="form-control">
                                        <option value="">Branch</option>
                                        <option value="1">Uttara</option>
                                        <option value="2">Banani</option>
                                    </select>
                                </div>
                                
                            </div>                            
  
                          <div class="form-group">
                            <button type="submit" title="Filter"  id="add"  name="add"  class="form-control btn btn-default"><i class="fa fa-filter"></i></button>
                            </div>


                          <!--input type="submit" name="add" value="+ Create New Item " id="add"  class="btn btn-md btn-info   pull-right responsive-alignment-r2l"-->
                        </div>

                        </div>


                      </div>
                    </div>


    				</form>


<link href='js/plugins/datagrid/datatables.css' rel='stylesheet' type='text/css'>

                <div >
                    <!-- Table -->
                    <table  class="display cost-sheet actionbtn no-footerd dataTable" border="0" width="100%" cellpadding="5" cellspacing="10">
                        <thead>
                          <tr>
                              <th>Products</th>
                              <th>Market Rate</th>
                              <th>Selected</th>
                              <th>Vendor 1</th>
                              <th>Vendor 2</th>
                              <th>Vendor 3</th>
                              <th>Vendor 4</th>
                              <th>More</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="venrow" id="prrowid_51" data-prid="51">
                              <td class="product">

                                  
                                      
                                   <label for="imac"><input type="checkbox" name="pid[]" value="51">  iMac</label>
                                    
                               </td>
                              <td class="market-rate">200000</td>
                              <td class="selected-vendor not">
                                    <span>Not Selected</span>
                                  <input type="hidden" class="checked-vendor-value" value="" name="vendor-value[1]">
                              
                              </td>
                              <td class="vendor"  id="110"><span>90000<br>
                                ABC Company 1
                                  </span>
                                  <input type="hidden" class="vendorid" value="110" name="vendorid">
                              </td>
                              <td class="vendor"  id="20"><span>95000<br>
                                  ABC Company 2</span>
                              <input type="hidden" class="vendorid" value="20" name="vendorid">
                              </td>
                              <td class="vendor"  id="2"><span>97000<br>
                                ABC Company</span></td>
                              <td class="vendor" id="3">90000<br>
                                ABC Company 3</td>
                              <td><button class="btn btn-info btn-xs cart-tigger" title="More"><i class="fa fa-angle-right"></i></button></td>
                          </tr>
                          <tr class="venrow" id="pid5">
                              <td class="product">

                                  
                                      
                                   <label for="iPhone"><input type="checkbox" name="pid[]"  value="5">  iPhone</label>
                                    
                               </td>
                              <td class="market-rate">60000</td>
                              <td class="selected-vendor not">
                                    <span>Not Selected</span>
                                  <input type="hidden" class="checked-vendor-value" value="" name="vendor-value[11]">
                              
                              </td>
                              <td class="vendor"  id="110"><span>90000<br>
                                ABC Company 1
                                  </span>
                                  <input type="hidden" class="vendorid" value="110" name="vendorid">
                              </td>
                              <td class="vendor"  id="20"><span>95000<br>
                                  ABC Company 2</span>
                              <input type="hidden" class="vendorid" value="20" name="vendorid">
                              </td>
                              <td class="vendor"  id="2"><span>97000<br>
                                ABC Company</span></td>
                              <td class="vendor" id="3">90000<br>
                                ABC Company 3</td>
                              <td><button class="btn btn-info btn-xs" title="More"><i class="fa fa-angle-right"></i></button></td>
                          </tr>
                          <tr class="venrow" id="pid1">
                              <td class="product">

                                  
                                      
                                   <label for="Laptop"><input type="checkbox" name="pid[]"  value="1">  Laptop</label>
                                    
                               </td>
                              <td class="market-rate">80000</td>
                              <td class="selected-vendor not">
                                    <span>Not Selected</span>
                                  <input type="hidden" class="checked-vendor-value" value="" name="vendor-value[10]">
                              
                              </td>
                              <td class="vendor"  id="110"><span>90000<br>
                                ABC Company 1
                                  </span>
                                  <input type="hidden" class="vendorid" value="110" name="vendorid">
                              </td>
                              <td class="vendor"  id="20"><span>95000<br>
                                  ABC Company 2</span>
                              <input type="hidden" class="vendorid" value="20" name="vendorid">
                              </td>
                              <td class="vendor"  id="2"><span>97000<br>
                                ABC Company</span></td>
                              <td class="vendor" id="3">90000<br>
                                ABC Company 3</td>
                              <td><button class="btn btn-info btn-xs" title="More"><i class="fa fa-angle-right"></i></button></td>
                          </tr>
                            
                      </tbody>

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


<script>
//$(".cost-sheet .vendor").wrapInner('<div class="box"></box>');
$(".cost-sheet").on("click",".vendor",function(){
  	var vval = $(this).find(".vendorid").val();
  	var root = $(this).closest(".venrow");
  	root.find(".selected-vendor").removeClass("not");
    root.find(".selected-vendor").addClass("yes");
  
   root.find(".checked-vendor-value").val(vval);
   root.find(".checked-vendor-value").attr("value",vval);
  
  $(".rest-vendor .vendor").removeClass("checked");	
  root.find(".vendor").removeClass("checked");
    
  $(this).addClass("checked");
  
  var info  = $(this).find("span").html();
  root.find(".selected-vendor").find("span").html(info);
  
}); 

$(".rest-vendor").on("click",".vendor",function(){   
   	var vval = $(this).find(".vendorid").val();
  	var rv_prid = $("#rv_prid").val();
  
  $("#prrowid_"+rv_prid).find(".vendor").removeClass("checked");	
  $(".rest-vendor .vendor").removeClass("checked");	
  $(this).addClass("checked");
    
  	$("#prrowid_"+rv_prid).find(".selected-vendor").removeClass("not");
    $("#prrowid_"+rv_prid).find(".selected-vendor").addClass("yes");
    
    //copy info
    var info  = $(this).find("span").html();
    $("#prrowid_"+rv_prid).find(".selected-vendor").find("span").html(info);
   // $("#prrowid_"+rv_prid).find("value",thisprid);
    
   $("#prrowid_"+rv_prid).find(".checked-vendor-value").val(vval);
   $("#prrowid_"+rv_prid).find(".checked-vendor-value").attr("value",vval);    
    
});    
    
    
/*******/

//$(".venrow input[type=hidden]").prop("disabled", true);
  /*  
$('.venrow input[name=pid]').on('ifChecked', function(event){
  $(this).closest(".venrow").removeClass("disabled");
  $(this).closest(".venrow").addClass("enabled");
  $(this).closest(".venrow").find("input[type=hidden]").prop("disabled", false);
});

$('.venrow input[name=pid]').on('ifUnchecked', function(event){
  $(this).closest(".venrow").addClass("disabled");
  $(this).closest(".venrow").removeClass("enabled");
  $(this).closest(".venrow").find("input[type=hidden]").prop("disabled", true);
});
   */
    // full screen cart 
    $('.cost-sheet').on('click','.cart-tigger', function(e) {
        $('.full-sreen-cart').toggleClass("expand");
        $('.panel-body').toggleClass("cost-sheet-wrapper");
        
        //send data
        var thisprid = $(this).closest(".venrow").data("prid");
       // alert(thisprid);
        $("#show_prid").html(thisprid);
        $("#rv_prid").val(thisprid);
        $("#rv_prid").attr("value",thisprid);
        
        
        //$('.panel-body').attr("style","width:calc(100% - 320px");
        
	    e.preventDefault();
    });

    $('.screen-close').on('click', function(e) {
        $('.full-sreen-cart').removeClass("expand");
        $('.panel-body').removeClass("cost-sheet-wrapper");
        e.preventDefault();
    });		
	

	    
    
</script>



    </body></html>
  <?php } ?>