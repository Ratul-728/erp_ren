<?php

require "common/conn.php";
include_once('rak_framework/fetch.php');
session_start();

//ini_set("display_errors",1);

$usr=$_SESSION["user"];

//echo $usr;die;

if($usr=='')

{ header("Location: ".$hostpath."/hr.php");

}

else
{

    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $did= $_GET['id'];
   // $serno= $_GET['id'];
    $totamount=0;

   if ($res==1)
    {

        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
         $mode=1;

    }

    else if ($res==2)
    {

        echo "<script type='text/javascript'>alert('".$msg."')</script>";

         $mode=1;

    }

    else

    {

                             

    $mode=1;//Insert mode

                        

    }

    

    $currSection = 'cusorderdelivery';

    $currPage = basename($_SERVER['PHP_SELF']);

?>



<?php

     include_once('common_header.php');

?>

<body class="form">

    

<?php

    include_once('common_top_body.php');

?>



<div id="wrapper"> 

    <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Delivery Order</span>

        </div>

        <?php include_once('menu.php'); ?>

       

        <div style="height:54px;"></div>

    </div>

    <!-- END #sidebar-wrapper --> 

    <!-- Page Content -->

    <div id="page-content-wrapper">

        <div class="container-fluid pagetop">

            <div class="row">

                <div class="col-lg-12" >

<p>&nbsp;</p>
                  

    <p>

                       

                     <form method="post" action="common/postdelivery.php" id="form1" enctype="multipart/form-data">  

                    <!-- START PLACING YOUR CONTENT HERE -->

                 

                    <?php include_once('phpajax/load_order_delivery.php'); ?> 

                            <!-- /#end of panel -->    

                     <div class="button-bar">
						
                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="postaction" value="Send to QC" id="update" <?php if($orderstatus==5){ ?> disabled <?php }?>  >

                       <!-- <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')"> -->

                    </div>    

                    </form>
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


<script>

//delivery script
	

	

</script>


<script>





$(".input-barcode").first().focus();



// $(".input-barcode").keypress(function(){

//     alert($(this).val());

//     $(this).next().focus();

    



// });





var $input = $('input[type=text]');

$input.on('keyup', function(e) {

    if (e.which === 13) {

        var ind = $input.index(this);

        $input.eq(ind + 1).focus()

    }

});  





$(":input").keypress(function(event){

    if (event.which == '10' || event.which == '13') {

        event.preventDefault();

    }

});





    

    function printDiv(divName) {

     var printContents = document.getElementById(divName).innerHTML;

     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;

}





</script>

<script>


    $(document).ready(function(){
	
	
		//input number only validateion
		//put class .numonly to apply this. alpha will no take, only number and float
		
		$('.numonly').keyup(function(e){
		  if(/[^0-9.]/g.test(this.value))
		  {
			// Filter non-digits from input value.
			this.value = this.value.replace(/[^0-9.]/g, '');
			  
		  }
            $(this).select();
		});	
        
        
        
        $("input[type=text]").focus(function(){
            $(this).select();
        }); 
        
    });      
</script>

<script>
//check send QC so it should not be sent more than due qty;
    
    $(".sent-qty1").on("change",function(){
  var elem = $(this).closest(".prdct-row");
  var duedl = elem.find('.due-delivery').val();


  	var sum1 = 0;
    var sum2 = 0;
  	elem.find('table').each(function(){
    	var s1 = parseInt($(this).find('.sent-qty1').val());
      var s2 = parseInt($(this).find('.sent-qty2').val());
			var nn1 = parseInt(s1) || 0;
      var nn2 = parseInt(s2) || 0;
       sum1 += nn1;
       sum2 += nn2;
  	});
  	
    var sum = sum1+sum2;
    //alert(duedl+'_'+sum);
  
  
  
    if(sum > duedl){
      alert("You cannot send more than "+duedl+" qty to QC");
  		 $(this).val("");
    }


});

$(".stdisabled").prop('disabled', true);
    
</script>


</body>

</html>







<?php }?>