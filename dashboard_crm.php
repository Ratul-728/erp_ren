<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<link rel="stylesheet" href="css/app.css" id="maincss">
<body class="dashboard dashboard-crm">


<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <a class="navbar-brand" href="dashboard_blank.php"><img src="images/logo-bitcables.png" alt="BizGIent"></a> </div>
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
          



             	  



		
        
       
        <style>
		.crm-report-iframe {
    display:block;
    width:100%;
    height:100vh;
}
		</style>



              <!-- START chart row-->
          <div class="row">
          	<div class="col-lg-12 col-md-12">
            	<iframe w idth="100%" he ight="100vh" class="crm-report-iframe" src="https://app.powerbi.com/view?r=eyJrIjoiZTU1MzY2ZTQtZWE5My00MWJmLTk3NDMtZDdiMTg5NzAxMjg1IiwidCI6IjVmODIyMjNjLWJmNzAtNDA4NC04YjFjLTZlMGFhMzZjYTY3ZiIsImMiOjEwfQ%3D%3D" frameborder="0" allowFullScreen="true"></iframe>
            </div>
          </div>          	  
               <!-- END row-->
               
               




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



  <script language="javascript">
$(function(){
      var $ppc = $('.progress-pie-chart'),
        percent = parseInt($ppc.data('percent')),
        deg = 360*percent/100;
      if (percent > 50) {
        $ppc.addClass('gt-50');
      }
      $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
      $('.ppc-percents span').html(percent+'%');
    });
   
     
  </script>
  


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
 <!--script src="js/plugins/Flot/jquery.flot.js"></script-->
 <script src="js/plugins/charts/flot-funnel/js/jquery.flot.js"></script>
 <script src="js/plugins/charts/flot-funnel/js/jquery.flot.funnel.js"></script>
   <script src="js/plugins/flot.tooltip/js/jquery.flot.tooltip.min.js"></script>
   <script src="js/plugins/Flot/jquery.flot.resize.js"></script>
   <script src="js/plugins/Flot/jquery.flot.pie.js"></script>
   <script src="js/plugins/Flot/jquery.flot.time.js"></script>
   <script src="js/plugins/Flot/jquery.flot.categories.js"></script>
   <script src="js/plugins/flot-spline/js/jquery.flot.spline.min.js"></script>
   <script src="js/plugins/charts/graph-key/graph-key.js"></script>
   <script src="js/plugins/charts/pie-chart/pie-chart.js"></script>
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

var myColor = ["#39ca74","#e54d42","#f0c330","#3999d8","#35485d"];
var myData = [80,23,15,7,1];
var myLabel = ["Hello","Hi","Howdy","Wadup","Yo"];

})(window, document, window.jQuery);





// CHART FUNNEL
(function(window, document, $, undefined){
$(function() {
		var data = [], series = 5;

		
		data = [{
		 "label": "Series 1",
		 "data": 10
		},
		{
		 "label": "Series 2",
		 "data": 20
		},
		{
		 "label": "Series 3",
		 "data": 15
		},
		{
		 "label": "Series 4",
		 "data": 5
		},
		{
		 "label": "Series 5",
		 "data": 6
		}
		];


	
        var placeholder = $("#flot-funnel");
		$.plot('#flot-funnel', data, {
		    series: {
		        funnel: {
		            show: true,
					stem: {
						height: 0,
						width: 0
					},
					margin: {
                        //right: 0.15
					},
		            label: {
						show: true,
						align: "center",
						threshold: 0.05,
						formatter: labelFormatter
                    },
					highlight: {
						opacity: 0.2
					}
				},
		    },
			grid: {
				hoverable: true,
				clickable: true
			}
		});
		
        
		placeholder.bind("plotclick", function(event, pos, obj) {

			if (!obj) {
				return;
			}

			alert(obj.series.label + ": " + obj.series.value);
		});
		
    	function labelFormatter(label, series) {
    		return "<div style='font-size:11pt; text-align:center; padding:2px; color:#fff;'>"+series.value+"</div>";
    	}
    		
	});
	
})(window, document, window.jQuery);




// GRAPH KEY
(function(window, document, $, undefined){
// Document ready

$(function(){
   // Dummy Data
  var dataSet = [
    {
      active: 5,
      newCount: 4,
      newFromBatch: 40,
      urgent: 1,
      userId: "molly",
      userName: "Molly"
    },
    {
      active: 21,
      newCount: 2,
      newFromBatch: 5,
      urgent: 10,
      userId: "jack",
      userName: "Jack"
    },  
    {
      active: 25,
      newCount: 4,
      newFromBatch: 3,
      urgent: 20,
      userId: "tracy",
      userName: "Tracy"
    },  
    {
      active: 10,
      newCount: 24,
      newFromBatch: 4,
      urgent: 2,
      userId: "nolan",
      userName: "Nolan"
    },  
  ];
 
  var dataSingle = {
      active: 25,
      newCount: 4,
      newFromBatch: 3,
      urgent: 20,
      userId: "ryan",
      userName: "Ryan Scofield"
  };
 
  // Initialize Graph
  graph.init(dataSet);
 
  $('#teamGraph').on('click', function(e){
    graph.init(dataSet);
  });
 
  $('#userGraph').on('click', function(e){
    graph.init(dataSingle);
  });
});

})(window, document, window.jQuery);
</script> 












<!-- Date Time Picker  ==================================== -->
<script src="js/plugins/datetimepicker/js/moment-with-locales.js"></script>
<script src="js/plugins/datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script language="javascript">
$(document).ready(function(){

			
	
			
         $('.datepicker').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });	
			
         $('.datetimepicker').datetimepicker({
					//inline:true,
					//sideBySide: true,
					format: "DD/MM/YYYY LT",
					//format: 'LT',
					//keepOpen:true,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });	
 });  
</script>
<!-- end Date Picker  ==================================== -->



<!--script src="js/app.js"></script-->   
  <!-- END FLOT CHART--> 
  
  

  
  
</body></html>
