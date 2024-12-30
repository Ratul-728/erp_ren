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

                       

                     <form method="post" action="common/postqc.php" id="form1" enctype="multipart/form-data">  

                    <!-- START PLACING YOUR CONTENT HERE -->

                 

                    <?php include_once('phpajax/load_qc.php'); ?> 

                            <!-- /#end of panel -->    

                     <div class="button-bar">
						
                        <!--input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="postaction" value="Submit QC Report" id="update" <?php if($orderstatus==5){ ?> disabled <?php }?>  -->

                      

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
    
    
     $(".dopfaction").on("click",function(){
         
         var root = $(this).closest('.pf-parent');
         passqty = root.find(".passqty").val();
         failqty = root.find(".failedqty").val();
         
         var info = root.find(".passqty");
         
         
         //get all info
         var oid        = info.data("oid");
         var pid        = info.data("pid");
         var sid        = info.data("sid");
         var ordered    = info.data("ordered");
         var delivered  = info.data("delivered");
         var duedel     = info.data("duedel");         
         
         
         //alert(pid);
         
         
        $.ajax({
            type: 'post',
            url: 'phpajax/save_qc.php',
            data: { oid: oid, pid: pid, sid:sid,passqty:passqty,failqty:failqty},
            success: function(res) {
                //$('#result').html(response);
                alert(res);
            }
          });          
         
     });
    
     $(".passqty, .failedqty").on("keyup",function(){
        type = $(this).attr('name');

         var root = $(this).closest('.pf-parent');
         var passqty = root.find(".passqty").val();
         var failqty = root.find(".failedqty").val();         
         
         //get all info
         var oid = $(this).data("oid");
         var pid = $(this).data("pid");
         var sid = $(this).data("sid");
         var ordered = $(this).data("ordered");
         var sentqty = $(this).data("sentqty");
         var delivered = $(this).data("delivered");
         var duedel = $(this).data("duedel");
         var thisVal = $(this).val();
         //alert(thisVal);

         if(type == "passed"){
            // key = 'passqty';
            if((passqty+failqty) > sentqty){
                alert('Invalid number');
                 $(this).val(sentqty-failqty);
            }
         }else{
            if((passqty+failqty) > sentqty){
                alert('Invalid number');
                $(this).val(sentqty-passqty);
            }
         }
         
         
        
        
     });
    
    
    
    
$(".passqty, .failedqty").on("keyup",function(){
    
    
    
    //$(this).next('input').focus();
    
});    
</script>


</body>

</html>







<?php }?>