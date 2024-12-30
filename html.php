<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="icon" href="assets/images/site_setting_logo/favicon_rdl.png">
<title>Bitflow</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/style_extended.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">
<link rel="stylesheet" href="css/icheck-bootstrap.min.css" />


<link href="js/plugins/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">


<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

<!--Date Time Picker CSS -->
<link href="js/plugins/datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
<!--end Date Time Picker CSS -->

<!--Date Range picker-->
<link href="js/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css"/>	
	
<!--Krajee File input-->
<link rel="stylesheet" href="js/plugins/krajeFileInput/fileinput.min.css">
<!-- Krajee fileinput end -->
 <link rel="stylesheet" href="css/app.css" id="maincss">
 <link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>
 
<!-- New Datatable -->
<link href="js/plugins/newdtable/dataTables.responsive.css" rel="stylesheet" type="text/css"/>
<link href="js/plugins/newdtable/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
<!-- end new datatable -->
<!--icheck box CSS -->
<link href="js/plugins/icheck/skins/square/blue.css" rel="stylesheet">
<!--end icheck box CSS -->

<!-- TEMPO TIME PICKER CSS-->
<!--link rel="stylesheet" href="js/plugins/timepicker-jq/dist/wickedpicker.min.css">
<link rel="stylesheet"
    href="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/build/css/bootstrap-datetimepicker.css"-->


<!-- CUSTOM CSS -->
<link href="css/ak-bit.css" rel="stylesheet">

<style>

.bhbs-header{
	text-align: center;
}
.bhbs-header{
    border-bottom: 1px solid #efefef;
}
.bhbs-header h2{font-size: 20px;margin-bottom:0;}
.bhbs-header h1{font-size: 30px;margin-top:5px;}
.tbl-bhbs-wrapper{
     border: 0px solid #efefef;
    padding: 0px;
}

.tbl-bhbs td:first-child{}
.tbl-bhbs td:nth-child(2),.tbl-bhbs td:nth-child(3){width: 100px; text-align: right;}

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

.tbl-bhbs td:nth-child(1),.tbl-bhbs td:nth-child(2), .tbl-bhbs th{
    border-right:1px solid #efefef;
}

/* gaps */
.tbl-bhbs td.gp-1{padding-left: 30px;}
.tbl-bhbs td.gp-2{padding-left: 60px;}
.tbl-bhbs td.gp-3{padding-left: 90px;}
.tbl-bhbs td.gp-4{padding-left: 120px;}


.total-title, .total-amount{font-weight: bold;}
.total-amountX{border-bottom: 0px solid #000!important;}
    

    
.last-amount{border-bottom: 1px solid #000!important;}	

.tbl-bhbs .end-parent{
    background-color: #f4e7e7;
    font-size: 16px;
}

.tbl-bhbs .end-parent .total-amount{
    border-bottom: 0px solid #000!important;
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
	
.tbl-bhbs td.bordertop{
    border-top: 2px solid #000!important;
}

    
    
.tbl-header{ background-color: #efefef;}
    
.tbl-footer{ background-color: #efefef;}    
    
.tbl-header td:first-child{}
    
.tbl-header td:nth-child(2),
.tbl-footer td:nth-child(2){
    width: 100px; 
    text-align: right;
}
 
    
.tbl-header td,.tbl-footer td{
    padding: 8px;
}   
    
.tr-parent{
    border:1px solid rgb(199,199,199);
}
.tr-parent > td:first-child{
    
    border-right:1px solid rgb(199,199,199);
}    
    
.tbl-bhbs .end-parent span {
  display: block;
  margin-bottom: 2px !important;
  border-bottom: 2px solid #111;
  padding: 5px;
}

.tbl-bhbs .end-parent .total-amount {
  border-bottom: 3px solid #000 !important;
}

.tbl-bhbs{
    border: 1px solid rgb(199,199,199);
}

.tbl-header{border-bottom: 1px solid #a7a7a7!important;}
 </style>	

</head>

<body>
	
<div class="panel-body">
                            <div class="row">
                        		

                        	    <div class="col-lg-8 col-md-12 col-sm-12">
                                     <div class="bhbs-header">
                                    	<h2>Renaissance Decor</h2>
                            			<h1>Statement of Profit or Loss or other comprehensive income</h1>
                            		</div>        
                                </div> 
                                <div class="col-lg-12">
      
                                </div>
                                <div class="po-product-wrapper"> 
                                    <div class="color-block">
 		                                
                                        <div class="col-lg-8 col-md-12">
                                            





 											<div class="tbl-bhbs-wrapper">
                                				<table class="tbl-bhbs" width="100%" border="0" cellspacing="0" cellpadding="0">
                                				  <tbody>
                                					<tr>
                                					  <td class="tbl-header total-title">In Taka </td>
                                					  <td width="100" class="tbl-header total-title">As on ,2023</td>
                                					  <td width="100" class="tbl-header total-title">As on ,2022</td>
                                					  
                                					</tr>
                                					
                                					
                                        			<tr>
                                        			                                    					  <td>Turnover</td>
                                					  <td><span>424800.0000</span></td>
                                					  <td><span></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					                                    					  <td>Cost Of Good Sold</td>
                                					  <td><span>1374306.0000</span></td>
                                					  <td><span></span></td>
                                					</tr>
                                					
                                					<tr>
                                					     <td class="bordertop total-title">Gross Profit</td>
                                					    <td class="bordertop total-amount"><span>-949506</span></td>
                                					    <td class="bordertop total-amount"><span>0</span></td>
                                					 </tr>
                                					 
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					 
                                					<tr>
                                					                                    					  <td>Administrative Expense</td>
                                					  <td><span>0.0000</span></td>
                                					  <td><span></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					                                    					  <td>Selling and Marketing Expense</td>
                                					  <td><span>0.0000</span></td>
                                					  <td><span></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					     <td class="bordertop total-title">Operating Profit</td>
                                					    <td class="bordertop total-amount"><span>-949506</span></td>
                                					    <td class="bordertop total-amount"><span>0</span></td>
                                					 </tr>
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					
                                					<tr>
                                					                                    					  <td>Financial Expense</td>
                                					  <td><span>0.0000</span></td>
                                					  <td><span></span></td>
                                					</tr> 
                                					
                                					<tr>
                                					     <td class="bordertop total-title">Profit Before Tax</td>
                                					    <td class="bordertop total-amount"><span>-949506</span></td>
                                					    <td class="bordertop total-amount"><span>0</span></td>
                                					 </tr>
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					 
                            					 	<tr>
                                					                                    					  <td>Tax Expense</td>
                                					  <td><span>0.0000</span></td>
                                					  <td><span></span></td>
                                					</tr>
                                					
                                					<tr>
                                					                                    					  <td>Deffred tax income</td>
                                					  <td><span>0.0000</span></td>
                                					  <td><span></span></td>
                                					</tr>
                                					
                                					<tr>
                                					    <td class="title">Total Income Tax Expense</td>
                                					    <td class="total-amount"><span>0</span></td>
                                					    <td class="total-amount"><span>0</span></td>
                                					 </tr>
                                					
                                					<tr>
                                					     <td class="bordertop total-title">Profit/(loss) for the year</td>
                                					    <td class="bordertop total-amount"><span>-949506</span></td>
                                					    <td class="bordertop total-amount"><span>0</span></td>
                                					 </tr> 
                                					<tr><td colspan="3">&nbsp;</td></tr>
                            						<tr>
                                					                                    					  <td>Other Comprehensive Income</td>
                                					  <td><span></span></td>
                                					  <td><span></span></td>
                                					</tr>
                                					<tr><td colspan="3">&nbsp;</td></tr>
                                					<tr class="end-parent assets">
                                					     <td class="total-title">Total Comprehensive Income</td>
                                					    <td class="bordertop total-amount"><span>-949506</span></td>
                                					    <td class="bordertop total-amount"><span>0</span></td>
                                					 </tr> 
                                        					
                                				  </tbody>
                                				</table>
                                			</div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </div>	
	
</body>
</html>
