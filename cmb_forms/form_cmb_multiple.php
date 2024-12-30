<?php
ini_set('display_errors',0);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Add <?=$_REQUEST['title']?></title>
<link rel="icon" href="../images/favicon.png">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/font-awesome4.0.7.css" rel="stylesheet">
<link href="../css/fonts.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">
<link href="../css/style_extended.css" rel="stylesheet">
</head>
<body class="cmb-form">

  

	<form id="cmdForm">
<?php
//print_r($_REQUEST);
//echo count($_REQUEST['field'])
?>
      <div style="heig ht:20px; border:0px solid #000; text-align:center" class="alertmsg">&nbsp;</div>
      <div class="row">
      
<?php
foreach ($_REQUEST['field'] as $key=>$value){
?>     
      
      	<div class="col-xs-12">
          <div class="form-group">
            <input type="text" placeholder="Add <?=$value?>" class="form-control" id="<?=$key?>" name="column[<?=$key?>]" autocomplete="off">
          </div>        
        </div>
<?php
}
?>        

      	<div class="col-xs-12">
          <div class="form-group">
              <input type="hidden" name="cmbname" value="<?=$_REQUEST['cmbname']?>">
              <input type="hidden" name="cmbtitle" value="<?=$_REQUEST['title']?>">
              <input class="btn btn-lg btn-default cmb-submit" type="button" name="add_customer" value="Save" on click="javascript:saveData();">
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


<!-- iCheck code for Checkbox and radio button -->

<script src="../js/plugins/icheck/icheck.js"></script>
<script language="javascript">
$(document).ready(function(){
  $('input').iCheck({
  checkboxClass: 'icheckbox_square-blue',
  radioClass: 'iradio_square-blue',
  increaseArea: '20%'
});
});
</script>
<!-- end iCheck code for Checkbox and radio button -->


<script src="../js/custom.js"></script></body>

<script>
function closeModal(){
	//parent.$('#iframeModal').modal('hide');
//	$('#iframeModal', window.parent.document).find(".cmb-modal").modal('hide');
	window.parent.CloseModal(window.frameElement);
	
	//alert('d');
}

$(".cmb-submit").click(function(){



	var postData = $("#cmdForm").serialize();
	
	//alert(postData);
	
	 $.ajax({
            type: "POST",
            url: "../phpajax/add_cmb_multiple.php",

			data:postData,
			beforeSend: function(){
					$(".alertmsg").html("Saving data...");
				},
		 
        }).done(function(data){
            //root.find(".measure-unit").html(data);
			
			//$(".cmd-child").empty();
			//$(".cmd-child").find('option').not(':first').empty();
			//$(".cmd-child").append(data);
			
			//messageAlert(data);
			
			var obj = JSON.parse(data);
			messageCmbAlert(obj.msg)
			
			//messageCmbAlert(data);
			//messageAlert(obj.id);
			<?php
			if($_REQUEST['idisvalue']=='true'){
			?>	
			window.parent.GetNewCmdItem(obj.value,obj.value,obj.cmbname);
			<?php
			}else{
			?>
			window.parent.GetNewCmdItem(obj.id,obj.value,obj.cmbname);
			<?php
			}
			?>
			
			//root.find(".measure-unit").attr('style','border:1px solid red!important;');
        });	
		
		
	
});




</script>

</body>
</html>
