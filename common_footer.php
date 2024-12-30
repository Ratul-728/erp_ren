
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



<input type="hidden" id="urlqeurystring">	

<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="js/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<script src="js/bootstrap.min.js"></script>
<script src="js/sidebar_menu.js"></script>
<script src="js/cookie.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="js/ie10-viewport-bug-workaround.js"></script>
<!-- Bootstrap core JavaScript
    ================================================== -->

    <!-- FONTAWESOME CDN =================> -->
    <!--link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/svg-with-js.min.css" integrity="sha512-W3ZfgmZ5g1rCPFiCbOb+tn7g7sQWOQCB1AkDqrBG1Yp3iDjY9KYFh/k1AWxrt85LX5BRazEAuv+5DV2YZwghag==" crossorigin="anonymous" referrerpolicy="no-referrer" /-->



<!-- Date Time Picker  ==================================== -->
<script src="js/plugins/datetimepicker/js/moment-with-locales.js"></script>
<script src="js/plugins/datetimepicker/js/bootstrap-datetimepicker.js"></script>


<!--Date range picker-->
<script src="js/plugins/daterangepicker/daterangepicker.js"></script>

<!-- Html2Pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>


<script language="javascript">
$(document).ready(function(){



        $(document).on('focus','.datetimepicker-wh', function(){
			
            $(this).datetimepicker({
       
                  minDate: moment().startOf('day').add(0, 'days').toDate() ,
				 format: "DD/MM/YYYY",
				 //format: 'LT',
                 //debug:true,
				 //keepOpen:true,
                 //showClear:true,
                useCurrent:false,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });    
    });
 
	
	
	
	
	


         $('.datepicker_comtype').datetimepicker({
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

         $('.datepicker, .datepicker_history_filter').datetimepicker({
					//inline:true,
					//sideBySide: true,
				format: "DD/MM/YYYY",
			 	
					//format: 'LT',
				 //keepOpen:true,
			 	//inline: true,
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


<!-- JQUERY TEMPO TIME PICKER PLUGIN -->
<!--link rel="stylesheet" href="js/plugins/timepicker-jq/dist/wickedpicker.min.js"-->
 <!-- FLOT CHART -->


   <!--Krajee File input-->
   <script src="js/plugins/krajeFileInput/fileinput.min.js"></script>

<!--	<script src="js/plugins/Flot/jquery.flot.barlabels.js"></script>
<script src="js/plugins/Flot/jquery.flot.valuelabels.min.js"></script>-->
<!--   	<script src="js/demo-flot.js"></script>-->
<!-- 	<script src="js/app.js"></script>   -->

<!-- scrollbar  ==================================== -->
<script src="js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- end scrollbar  ==================================== -->


<!-- iCheck code for Checkbox and radio button -->
<script src="js/plugins/icheck/icheck.js"></script>
<script language="javascript">
    
    
$(document).on('ready', function() {
  // Call initializeiCheck() function when the AJAX content is loaded
  //initializeiCheck();
});
    
function initializeiCheck() {
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
  });
}    
    /*
$(document).ready(function(){
    
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
});
});
*/
</script>
<!-- end iCheck code for Checkbox and radio button -->

<!-- SWEET ALERT -->
<script src="js/plugins/sweetalert/sweetalert.min.js"></script>


<script src="js/custom.js"></script>

<script>
  function CloseModal(frameElement) {
     if (frameElement) {
        var dialog = $(frameElement).closest(".modal");
        if (dialog.length > 0) {
            dialog.modal("hide");
        }
     }
}

  function GetNewCmdItem(id,value,cmbname){
	  //alert(value+" "+id+" "+cmbname);
		$('select[name='+cmbname+']').append('<option value="'+id+'" selected="selected">'+value+'</option>');
	}
</script>



<!-- Add new -->
<script>


        $(document).ready(function(){










            //Search functionality and add value to new input box
            /*
             $('.input-box').on("keyup", function() {
             //alert("h");
            var value = $(this).val().toLowerCase();
            $(this).closest(".input-ul li ").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            $('.addnew').text("+Add Item" + " (" + value + ")");
            $('.addnew').click(function(){
                alert("S");
                 $('.ds-add-list').attr('style','display:block');
                 $('.addinpBox').val(value);
                  $('.ds-list').attr('style','display:none');
            });
            });

            */
            //   $(document).ready(function(){
            //keyup comment





        })


    </script>

<link rel="stylesheet" href="js/plugins/jquery-confirm/dist/jquery-confirm.css">
<script src="js/plugins/jquery-confirm/dist/jquery-confirm.js"></script>

<!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script-->
<script src="js/bootstrap-dialog.min.js"></script>
<script src="js/plugins/printThis/printThis.js"></script>

	<script>
		
<?php if($_REQUEST['changedid']){
		$changedid = $_REQUEST['changedid'];
		?>

$(document).ready(function(){

	setTimeout(function(){
	
		$(".rowid_<?=$changedid?>").closest("tr").addClass("updatedtr");

	setTimeout(function(){
		$(".rowid_<?=$changedid?>").closest("tr").removeClass("updatedtr");
		}, 10050);
		
}, 1050);		
});			
		
 <?php }?>  		
		
		</script>


<script>

		//convert pdf trigger;

			



			

		</script>

<!-- for single page print -->

<script>
$(document).ready(function(){
	
//show print View
	
	$(".print-view").on("click",function(){
	
		var contents = $("#printSinglePage").html();

		BootstrapDialog.show({

								title: 'Profit Loss Report',
								//message: '<div id="printableArea">'+data.trim()+'</div>',
								//message: $('<div id="printableArea2"></div>').load(mylink),
								message: $('<div></div>').html(contents),
								type: BootstrapDialog.TYPE_PRIMARY,
								closable: true, // <-- Default value is false
								closeByBackdrop: false,
								draggable: false, // <-- Default value is false
								cssClass: 'print-view',
								buttons: [

									{
									icon: 'glyphicon glyphicon-chevron-left',
									cssClass: 'btn-default',
									label: ' Cancel',
									action: function(dialog) {
										dialog.close();	
									}
								},
								{
                                    icon: 'glyphicon glyphicon-download',
                                    cssClass: 'btn-success',
                                    label: ' Download PDF',
                                    action: function(dialog) {
                                        
                                        var element = document.getElementById('printSinglePage');
                                        var opt = {
                                            margin: [10, 0, 10, 0], // Margins in millimeters
                                            filename: 'report',
                                            image: { type: 'jpeg', quality: 0.98 },
                                            html2canvas: { scale: 3 } , // Adjust scale for quality
                                            jsPDF: { unit: 'mm', format: 'a3', orientation: 'portrait' }
                                        };
                                        html2pdf().set(opt).from(element).save(); // Generate and download PDF
                                    }
                                },
									{


									icon: 'glyphicon glyphicon-ok',
									cssClass: 'btn-primary',
									label: ' Print',
									action: function(dialog) {

									//alert('Print access restricted');

											dialog.close();
										
											
											
                                            $("#printSinglePage").printThis({
                                                importCSS: true,  // Import the CSS from your page
                                                importStyle: true, // Import the styles from your page
                                                pageTitle: "Profit Loss Report"  // Optional: title of the printed page
                                            });	
                                            
                                            
                                         


										},

								}],
								onshown: function(dialog){  $('.btn-primary').focus();},
							});		


		return false;
		
	});		

	
	
	
	
function dateRangePopup(){
	
        $(document).on('focus','.datepicker-popup', function(){
			
            $(this).datetimepicker({
       
                 //minDate: moment().startOf('day').add(1, 'days').toDate() ,
				 format: "DD/MM/YYYY",
				 //format: 'LT',
                 //debug:true,
				 //keepOpen:true,
                 showClear:true,
                 useCurrent:false,
                 icons: {
                 time: "fa fa-clock-o",
                 date: "fa fa-calendar",
                 up: "fa fa-angle-up",
                 down: "fa fa-angle-down"
                }
            });    
    });	
	
	
		var contents = '<div style="display:flex"><input type="text" class="form-control  datepicker-popup" autocomplete="off" placeholder="From Date" name="from_dt" id="from_dt2"  value="" >'+
						'<input type="text" class="form-control  datepicker-popup" autocomplete="off" placeholder="To Date" name="to_dt" id="to_dt2"  value="" ></div>';

		BootstrapDialog.show({

								title: 'Select Date Range',
								message: $('<div></div>').html(contents),
								type: BootstrapDialog.TYPE_PRIMARY,
								closable: true, // <-- Default value is false
								closeByBackdrop: false,
								draggable: false, // <-- Default value is false
								cssClass: 'print-view',
								buttons: [

									{
									icon: 'glyphicon glyphicon-chevron-left',
									cssClass: 'btn-default',
									label: ' Cancel',
									action: function(dialog) {
										dialog.close();	
									}
								},
									{


									icon: 'glyphicon glyphicon-ok',
									cssClass: 'btn-primary',
									label: ' PDF Export',
									action: function(dialog) {
										
									var urlqeurystring = $("#urlqeurystring").val();
									
									var moreurldata = (urlqeurystring)?'&'+urlqeurystring:'';

									var fdate = $("#from_dt2").val();
									var tdate = $("#to_dt2").val();
									
									if(!fdate || !tdate){
									    swal("Both date fields are required!");
									    return false;
									}
									
									//=01/09/2024  2024-09-01
            						fdatearr = fdate.split("/");
            						tdatearr = tdate.split("/");
            						
            						var fdate = fdatearr[2]+'-'+fdatearr[1]+'-'+fdatearr[0];
            						var tdate = tdatearr[2]+'-'+tdatearr[1]+'-'+tdatearr[0];
            						
									var filename = $("#pdfsource").attr("url");
									var datestring = '&dt_f='+fdate+'&dt_t='+tdate+''
									var pdfurl = filename+"?filter_date_from="+fdate+"&filter_date_to="+tdate+moreurldata+datestring;
									//alert(pdfurl);
									//return false;
									window.open(pdfurl); 
									dialog.close(); 
																				


										},

								}],
								onshown: function(dialog){  $('.btn-primary').focus();},
							});		


		return false;
		
	};			

	
	
	
$("#exportpdf").on("click",function(){
				
	
				var urlqeurystring = $("#urlqeurystring").val(); //pls define this value somewhere in hidden field if you have a keyword or combo filter value;
				var isDate = $("#isdate").val();
				//alert(urlqeurystring);
				
				//for daterangepicker;
				var drange = $("#filter_date_from").val();
				
				if(!drange && isDate != 'false'){
					//alert("Please define a date range to print this report");
					
					    dateRangePopup();
					
					//return false;
				}else{
					
					//check if it is a daterange;
					
					
					const substr = ' - ';
                    if(isDate != 'false'){
    					if(drange.includes(substr)){
    						//daterangepicker
    						datearr = drange.split(" - ");
    						var fdate = datearr[0];
    						var tdate = datearr[1];	
    						
    						//=01/09/2024  2024-09-01
    						fdatearr = fdate.split("/");
    						tdatearr = tdate.split("/");
    						
    						var fdate = fdatearr[2]+'-'+fdatearr[1]+'-'+fdatearr[0];
    						var tdate = tdatearr[2]+'-'+tdatearr[1]+'-'+tdatearr[0];
    					//	alert(fdate);
    						
    						
    					}else{
    						//manual datepicker
    						var fdate = $("#from_dt").val();
    						//alert(fdate);
    						var tdate = $("#to_dt").val();							
    					}
                    }
					
					
					var moreurldata = (urlqeurystring)?'&'+urlqeurystring:'';
					var filename = $("#pdfsource").attr("url");
					var datestring = '&dt_f='+fdate+'&dt_t='+tdate+''
					var pdfurl = filename+"?filter_date_from="+fdate+"&filter_date_to="+tdate+moreurldata+datestring;
					//location.href=pdfurl;
					//alert(pdfurl);
					window.open(pdfurl); 
				}

				

			});	
	
	
	
	
});
</script>



