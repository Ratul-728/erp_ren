<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}else{

$qry="select count(id) cid,sum(`tot_family_member`) fm,sum(`tot_infant`)inf,sum(`tot_children`)tc,sum(`tot_boy`)tb,sum(`tot_girl`)tg,sum(`tot_women`)tw,sum(`tot_man`)tm,sum(`tot_sr_women`)tsw,sum(`tot_sr_man`)tsm,sum(case `has_disable_member` when 1 then 1 else 0 end )td,avg(`monthly_income`)ami,sum(case `has_family_latrin` when 1 then 1 else 0 end ) fl,sum(`tot_member_wt_profession`) tmwf,sum(case `secoend_proffession` when 1 then 1 else 0 end)tsf FROM `survey_form` where  `servey_st`=1";

//echo $qry;

$result = $conn->query($qry); 
 while($row = $result->fetch_assoc()) 
      { 
           $fm=$row["fm"];
           $ami=$row["ami"];
           $tc=$row["tc"];
           $noservey=$row["cid"];
           $inf=$row["inf"];
           $tm=$row["tb"]+$row["tm"]+$row["tsm"];
           $tf=$row["tg"]+$row["tw"]+$row["tsw"];
           $td=$row["td"];
           $fl=$row["fl"];
           $tmwf=$row["tmwf"];
           $tsf=$row["tsf"];
            
           
      }
	  

     
}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

<link rel="icon" href="images/favicon.png">
<title>BitFlow</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/font-awesome4.0.7.css" rel="stylesheet">
<link href="css/fonts.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/simple-sidebar.css" rel="stylesheet">


<link href="js/plugins/scrollbar/jquery.mCustomScrollbar.css" rel="stylesheet">

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

 <link rel="stylesheet" href="css/app.css" id="maincss">



 <link href="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.css" rel="stylesheet" type="text/css"/>
 <link href="js/plugins/datepicker/datepicker-0.5.2/datepicker_style.css" rel="stylesheet" type="text/css"/>



</head>
<body class="dashboard">


<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="dashboard.php"><img src="images/logo-bitcables.png" alt="BizGIent"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav parent-menu">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <?php 
				$qrysb="SELECT `id`, `Name`, `sl`,`landport` FROM `module` order by sl"; 
				$resultsb= $conn->query($qrysb);
				if ($resultsb->num_rows > 0){
					 while($rowsb = $resultsb->fetch_assoc()){ $mnsl=$rowsb["sl"]; $slnm=$rowsb["Name"]; $url1=$rowsb["landport"]."?mod=".$rowsb["id"]; ?>
        <li <?php if ($mod==$rowsb["id"]){ ?> class="active" <?php }?>><a href=<?php echo $url1;?>><?php echo $slnm;?> </a></li>
		 <?php 			 }
				}?>
        <!--<li class="active"><a href="dashboard.php">Inventory</a></li>
        <li><a href="dashboard.php">POS</a></li> 
        <li><a href="dashboard.php">HR</a></li>
        <li><a href="dashboard.php">CRM</a></li>
        <li><a href="dashboard.php">Payment</a></li> -->

        
      </ul>
      <ul class="nav navbar-nav navbar-right user-menu">
        <li><a href="../navbar/"><span class="fa fa-gear"></span> Setting</a></li>
        
        <li class="dropdown"> <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle-o"></span> <span class="caret"></span> </a>
          <ul class="dropdown-menu">
             <li><a href="hc_char_modi.php?mod=5">Change Password</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="hr.php?res=2">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</nav>
<!-- Fixed navbar -->






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


             	  <div class="row dashboard-filter">
                      <div class="col-xs-12">
                         <div class="panel panel-default">
                               
                             <div class="panel-heading">
                               <div class="row">
                               
                               
                            	    <div class="col-sm-2  col-md-4 col-lg-1 col-lg-offset-7 md-text-right">
                                        <label>Filter</label>
                                    </div>                            	    
                                    <div class="col-sm-5  col-md-4 col-lg-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" autocomplete="off" placeholder="Start Date" name="po_dt" id="po_dt" value="<?php echo $orderdt;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                    
                            	    <div class="col-sm-5 col-md-4 col-lg-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" autocomplete="off"  placeholder="End Date" name="po_dt" id="po_dt" value="<?php echo $orderdt;?>" required>
                                            <div class="input-group-addon">
                                                <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>                                                                     
                               
                               
                               
                               
                               </div> 
                               </div>                               
                               
                         </div>
                      </div>
                  </div>



		<!-- START widgets box-->

			<div class="row widget-wrapper">
               <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <!-- START widget-->
                  <div class="panel widget bg-primary bg-bithut">
                     <div class="row row-table">

                        <div class="col-xs-12 pv-lg text-center">
                           <div class="h2 mt0"><?php echo  $fm;?></div>
                           <div class="text-uppercase">Collection</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <!-- START widget-->
                  <div class="panel widget bg-purple bg-bithut">
                     <div class="row row-table">

                        <div class="col-xs-12 pv-lg  text-center">
                           <div class="h2 mt0"><?php echo $tm; ?>
                              <!--small>Total Channels</small-->
                           </div>
                           <div class="text-uppercase">Bill</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6">
                  <!-- START widget-->
                  <div class="panel widget bg-orange bg-bithut">
                     <div class="row row-table">

                        <div class="col-xs-12 pv-lg  text-center">
                           <div class="h2 mt0"><?php echo $tf; ?></div>
                           <div class="text-uppercase">Manufectured</div>
                        </div>
                     </div>
                  </div>
               </div>
               
              

               <div class="col-lg-2 col-md-4  col-sm-6 col-xs-6">
                  <!-- START widget-->
                  <div class="panel widget bg-orange bg-bithut">
                     <div class="row row-table">

                        <div class="col-xs-12 pv-lg  text-center">
                           <div class="h2 mt0"><?php echo $inf; ?></div>
                           <div class="text-uppercase">inventory</div>
                        </div>
                     </div>
                  </div>
               </div>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <!-- START widget-->
                  <div class="panel widget bg-primary bg-bithut">
                     <div class="row row-table">

                        <div class="col-xs-12 pv-lg  text-center">
                           <div class="h2 mt0"><?php echo  $td;?></div>
                           <div class="text-uppercase"> Sell.</div>
                        </div>
                     </div>
                  </div>
               </div>
               
               <div class="col-lg-2 col-md-4 col-sm-6 col-xs-6">
                  <!-- START widget-->
                  <div class="panel widget bg-purple bg-bithut">
                     <div class="row row-table">

                        <div class="col-xs-12 pv-lg  text-center">
                           <div class="h2 mt0"><?php echo $fl; ?>
                              <!--small>Total Channels</small-->
                           </div>
                           <div class="text-uppercase">New</div>
                        </div>
                     </div>
                  </div>
               </div>
              

               <?php /*
               <div class="col-lg-4 col-sm-6">
                  <!-- START widget-->
                  <div class="panel widget bg-purple">
                     <div class="row row-table">
                        <div class="col-xs-4 text-center bg-purple-dark pv-lg">
                           <em class="fa fa-youtube-play fa-3x"></em>
                        </div>
                        <div class="col-xs-8 pv-lg">
                           <div class="h2 mt0"><?php echo $ami; ?>
                              <!--small>Total Channels</small-->
                           </div>
                           <div class="text-uppercase">Cheque Due</div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4  col-sm-12">
                  <!-- START widget-->
                  <div class="panel widget bg-orange">
                     <div class="row row-table">
                        <div class="col-xs-4 text-center bg-orange-dark pv-lg">
                           <em class="fa fa-dropbox fa-3x"></em>
                        </div>
                        <div class="col-xs-8 pv-lg">
                           <div class="h2 mt0"><?php echo $tmwf; ?></div>
                           <div class="text-uppercase">Return</div>
                        </div>
                     </div>
                  </div>
               </div>
             	*/
				?>
            </div>    
		<!-- END widgets box-->
        
       
        



              <!-- START chart row-->
             	  <div class="row">
                  
                  
                  
                  
                  <div class="col-lg-12">
                     <div id="panelChart4" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Sale History</div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-area flot-chart"></div>
                        </div>
                     </div>
                  </div>
               
               


                  <div class="col-lg-4 col-md-6">
                     <div id="panelChart6" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Products in Stock
                            
                           </div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-donut flot-chart"></div>
                        </div>
                     </div>
                  </div>
                  
                    <div class="col-lg-4  col-md-6">
                     <div id="panelChart5" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Gender wise Family Head Count </div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie flot-chart"></div>
                        </div>
                     </div>
                  </div>                
                  


					<div class="col-lg-4 col-md-12">
                     <div id="panelChart3" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Bar - Stacked</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar flot-chart"></div>
                        </div>
                     </div>
                  </div> 


                  
                  
                  
               </div>
               <!-- END row-->
               
               
               <!-- START row-->
               <!-- <div class="row">


                  <div class="row">
                  <div class="col-lg-4  col-md-6">
                     <div id="panelChart3" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Scholl Not going reason Count </div>
                        </div>
                        <div class="panel-body">
                           <div class="chart-pie2 flot-chart"></div>
                        </div>
                     </div>
                  </div>
				<!--	<div class="col-lg-4 col-md-12">
                     <div id="panelChart3" class="panel panel-default">
                        <div class="panel-heading">
                           <div class="panel-title">Bar - Stacked</div>
                        </div>
                        <div class="panel-body">
                           <div class="indicator show">
                              <span class="spinner"></span>
                           </div>
                           <div class="chart-bar-stacked flot-chart"></div>
                        </div>
                     </div>
                  </div> 
                  
                                 
               </div> -->
               <!-- END  chart row-->



          <!-- START PLACING YOUR CONTENT HERE -->          
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /#page-content-wrapper -->



<!-- #page-footer -->
<div class="container-fluid">
  <div class="page_footer">
    <div class="row">
      <div class="col-xs-2"><a class="" href="http://www.bithut.biz/" target="_blank" bo><img src="images/logo_bithut_sm.png" height="30" border="0"></a></div>
      <div class="col-xs-10  copyright">Copyright © <a class="" href="http://www.bithut.biz/" target="_blank">Bithut Ltd.</a></div>
    </div>
  </div>
</div>        
<!-- /#page-footer -->



<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script> 
<script src="js/bootstrap.min.js"></script> 
<script src="js/sidebar_menu.js"></script> 
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug --> 
<script src="js/ie10-viewport-bug-workaround.js"></script> 
<!-- Bootstrap core JavaScript
    ================================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<script src="js/custom.js"></script> 
    


 <!-- FLOT CHART-->  
 <script src="js/plugins/Flot/jquery.flot.js"></script>
   <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
   <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
   <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
   <script src="js/plugins/Flot/jquery.flot.time.js"></script>
   <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
   <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
   	<script src="js/demo-flot.js"></script>

  
<script language="javascript">
// ################## all chart data 

// CHART AREA
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Thousand",
      "color": "#00ABE3",
      "data": [
        ["Jan 18", 6000],
        ["Feb 18", 2000],
        ["Mar 18", 4000],
        ["Apr 18", 3000],
        ["May 18", 2000],
        ["Jul 18", 1000],
        ["Aug 18", 6500],
        ["Sep 18", 1300],
        ["Oct 18", 4000],
        ["Nov 18", 6200],
        ["Dec 18", 2000]
      ]
    }/*, {
      "label": "Recurrent",
      "color": "#7dc7df",
      "data": [
        ["Mar", 13],
        ["Apr", 44],
        ["May", 44],
        ["Jun", 27],
        ["Jul", 38],
        ["Aug", 11],
        ["Sep", 39]
      ]
    }*/];

    var options = {
                    series: {
                        lines: {
                            show: true,
                            fill: 0.8
                        },
                        points: {
                            show: true,
                            radius: 4
                        }
                    },
					legend: {
						show: false,
						},						
                    grid: {
                        borderColor: '#eee',
                        borderWidth: 1,
                        hoverable: true,
                        backgroundColor: '#fcfcfc'
                    },
                    tooltip: true,
                    tooltipOpts: {
                        content: function (label, x, y) { return x + ' : ' + y; }
                    },
                    xaxis: {
                        tickColor: '#fcfcfc',
                        mode: 'categories'
                    },
                    yaxis: {
                        min: 0,
                        tickColor: '#eee',
                        // position: 'right' or 'left'
                        tickFormatter: function (v) {
                            return v + ' ';
                        }
                    },
                    shadowSize: 0
                };

    var chart = $('.chart-area');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);





// CHART DONUT
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [ { "color" : "#39C558",
        "data" : 60,
        "label" : "Oneman <br>Casual"
      },
      { "color" : "#00b4ff",
        "data" : 90,
        "label" : "Oneman <br>Formal"
      },
      { "color" : "#FFBE41",
        "data" : 50,
        "label" : "Oneman <br>Half Sleeve"
      },
      { "color" : "#ff3e43",
        "data" : 80,
        "label" : "Oneman <br>Full Sleeve"
      },
      { "color" : "#937fc7",
        "data" : 116,
        "label" : "Brand <br>Casual"
      },
      { "color" : "#937fc7",
        "data" : 116,
        "label" : "Brand <br>Formal"
      },
      { "color" : "#937fc7",
        "data" : 116,
        "label" : "Brand <br>Half Sleeve"
      },
      { "color" : "#937fc7",
        "data" : 116,
        "label" : "Brand <br>Full Sleeve"
      }	  	    
    ];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0.5, // This makes the donut shape
							label: {
								show: true,

								},

                        },
						
                    	},
					legend: {
								show: false,
								},						
                };

    var chart = $('.chart-donut');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);




// CHART BAR
// ----------------------------------- 

(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Tweets",
      "color": "#51bff2",
      "data": [
        ["Jan", 56],
        ["Feb", 81],
        ["Mar", 97],
        ["Apr", 44],
        ["May", 24],
        ["Jun", 85],
        ["Jul", 94],
        ["Aug", 78],
        ["Sep", 52],
        ["Oct", 17],
        ["Nov", 90],
        ["Dec", 62]
      ]
    }];

   var options = {
			series: {
				stack: true,
				bars: {
					align: 'center',
					lineWidth: 3,
					show: true,
					barWidth: 0.6,
					fill: 0.9,
					
				}
			},
			
			grid: {
				borderColor: '#eee',
				borderWidth: 1,
				hoverable: true,
				backgroundColor: '#fcfcfc'
			},
			tooltip: true,
			tooltipOpts: {
				content: function (label, x, y) { return x + ' : ' + y; }
			},
			xaxis: {
				tickColor: '#fcfcfc',
				mode: 'categories'
			},
			yaxis: {
				// position: 'right' or 'left'
				tickColor: '#eee'
			},
			shadowSize: 0
		};



    var chart = $('.chart-bar');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);





// CHART PIE
// ----------------------------------- 
(function(window, document, $, undefined){

  $(function(){

    var data = [{
      "label": "Premium <br>Primary",
      "color": "#4acab4",
      "data": 16
    }, {
      "label": "Regular<br> Primary",
      "color": "#ffea88",
      "data": 5
    }, {
      "label": "Premium <br>Secondary",
      "color": "#ff8153",
      "data": 10
    }, {
      "label": "Regular<br> Secondary",
      "color": "#878bb6",
      "data": 10
    }];

    var options = {
                    series: {
                        pie: {
                            show: true,
                            innerRadius: 0,
                            label: {
                                show: true,
                                radius: 0.8,
                                formatter: function (label, series) {
                                    return '<div class="flot-pie-label" style="color:#000">' +
                                    label + ' : ' +
                                    //Math.round(series.percent) +
									Math.round(series.data[0][1])
									
                                  //  '%</div>';
                                },
                                background: {
                                    opacity: 0.8,
                                    color: ''
                                }
                            }
                        }
                    },
				legend: {
						show: false,
						},						
                };

    var chart = $('.chart-pie');
    if(chart.length)
      $.plot(chart, data, options);

  });

})(window, document, window.jQuery);



</script>

<!-- Date Picker  ==================================== -->
<script src="js/plugins/datepicker/datepicker-0.5.2/dist/datepicker.min.js"></script>
<script language="javascript">
$(document).ready(function(){
   	$( ".datepicker" ).datepicker({
	  format: 'yyyy-mm-dd'
	});
 });  
</script>
<!-- end Date Picker  ==================================== -->



<script src="js/app.js"></script>   
   
  <!-- END FLOT CHART--> 
</body></html>
