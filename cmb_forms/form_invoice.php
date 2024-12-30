<?php
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $invoice= $_REQUEST['invid'];
    $invamt= $_REQUEST['invamount'];
     $balamt= $_REQUEST['orgbal'];
    $orgid= $_REQUEST['orgid'];
    $invdue= $_GET['duea'];
    $amt=0;
    if($invdue>=$balamt){$amt=$balamt;}else{$amt=$invdue;}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add Invoice</title>
<link rel="icon" href="../images/favicon.png">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/font-awesome4.0.7.css" rel="stylesheet">
<link href="../css/fonts.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style_extended.css" rel="stylesheet">
<style>
.alertmsg{
        -webkit-transition: width 150ms ease-in;
    -moz-transition: width 150ms ease-in;
    -o-transition: width 150ms ease-in;
    transition: width 150ms ease-in;
     transition: width 150ms ease-in;
}



</style>
</head>
<body class="cmb-form">

	<form id="cmdForm">

      <div style="min-height:35px; border:0px solid #000; text-align:center" class="alertmsg"></div>
      
      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <input type="hidden"  name="invoiceid" id="invoiceid" value="<?php echo $invoice;?>"> 
                <input type="hidden"  name="invamount" id="invamount" value="<?php echo $invamt;?>">
                <input type="hidden"  name="orgbal" id="orgbal" value="<?php echo $balamt;?>">
                <input type="hidden"  name="orgid" id="orgid" value="<?php echo $orgid;?>">
                <input type="hidden"  name="due" id="due" value="<?php echo $invdue;?>">
                    
                <input type="hidden"  name="cmbdrcr" id="cmbdrcr" value="W"> 
	            <input type="hidden"  name="usrid" id="usrid" value="<?php echo $usr;?>">
	            <label for="amt">Available Balance </label>
                <input type="text" class="form-control" id="wlt" name="wlt"  value="<?php echo number_format($balamt,2);?>" disabled>
                <!--label for="amt">Invoice  Amount *</label>
                <input type="text" class="form-control" id="invamt" name="invamt"  value="<?php echo $invamt;?>"  disabled>
                <label for="amt">Invoice Due Amount *</label>
                <input type="text" class="form-control" id="damt" name="damt"  value="<?php echo $invdue;?>" disabled-->
                <label for="amt">Payable Amount *</label>
                <input type="text" class="form-control" id="amt" name="amt"  value="<?php echo $amt;?>" required>
            </div>        
        </div>
        <!--<div class="col-lg-3 col-md-6 col-sm-6">
            <div class="form-group">
                <label for="cmbdrcr"> Transaction Mode*</label>
                <div class="form-group styled-select">
                <select name="cmbdrcr" id="cmbdrcr" class="form-control" required>
                    <option value="" >Select</option>
                    <option value="W">Debit Wallet</option>
                    <option value="C" >Cash Received</option>
                </select>
                </div>
            </div>        
        </div>-->
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                <label for="rem">Remarks *</label>
                <input type="text" class="form-control" id="rem" name="rem" >
            </div>        
        </div>
         <div class="col-xs-12">
          <div class="form-group">
              <input class="btn btn-lg btn-default cmb-submit" type="button" name="add" value="Save" on click="javascript:saveData();">
              <input class="btn btn-lg btn-default" type="button" name="cancel" value="Close"  id="cancel" onClick="closeModal()">
          </div>        
        </div>                 
   
      </div>
 
  
	</form>





<!-- Bootstrap core JavaScript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="../js/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="../js/jquery.min.js"><\/script>')</script> 
<script src="../js/bootstrap.min.js"></script> 

<!-- scrollbar  ==================================== -->
<script src="../js/plugins/scrollbar/jquery.mCustomScrollbar.concat.min.js"></script> 
<!-- end scrollbar  ==================================== -->




<script src="../js/custom.js"></script></body>

<script>
function closeModal(){
	//parent.$('#iframeModal').modal('hide');
//	$('#iframeModal', window.parent.document).find(".cmb-modal").modal('hide');
	//window.parent.CloseModal(window.frameElement);
	 //window.close();
	//alert('d');
	window.parent.closeModal();
	
}





$(".cmb-submit").click(function(){



	var postData = $("#cmdForm").serialize();
	
	//alert(postData);
	
	 $.ajax({
            type: "POST",
            url: "../common/addinvoice.php",

			data:postData,
			beforeSend: function(){
				//	$(".alertmsg").html("Saving data...");
				},
		 
        }).done(function(data){
    		
			var obj = JSON.parse(data);
			

            var parentBody = window.parent.document.body
            
           // $("#invformfrm", parentBody).height("400");
            

           messageAlertLong(obj.msg,'alert-success');
          $("#invformfrm", parentBody).animate({height:450},200).delay(4000);
          $("#invformfrm", parentBody).animate({height:380},200);


        });	
		
		
	
});




</script>

</body>
</html>
