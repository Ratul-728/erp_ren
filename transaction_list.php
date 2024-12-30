<?php


require "common/conn.php";
require "rak_framework/listgrabber.php";
require "rak_framework/fetch.php";
require "rak_framework/misfuncs.php";


session_start();
// ini_set('display_errors',1);

$usr = $_SESSION["user"];

$rpid = $_GET["transref"];

//print_r($_SESSION);

if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {

    $isfinance = ($_SESSION['treatfrom'] == 'acc')?1:2;
    //$treatfrom = ($_SESSION['treatfrom'] == 'acc')?1:2;
    $treatfrom=1;
     $inputData = array(
     'TableName' => 'collection',
     'OrderBy' => 'trdt',
     'ASDSOrder' => 'DESC',
         
     'treat_from' => $treatfrom,
     'id' => '',
     'trdt' => '',
     'transmode' => '',
     'transref' => $rpid,
     'checkno' => '',
     'bank' => '',
     'chequedt' => '',
     'customerOrg' => '',
     'naration' => '',
     'amount' => '',
     'glac' => '',
     'amount' => '',
     'financadjstmnt'=>'',
     'costcenter' => '',
     'chqclearst' => '',
     'st' => '',
     'makeby' => '',
     'makedt' => '',
     'invoice' => '',
     'document' => '',
     'currencycode' => ''
      );
      
       listData($inputData,$transArray);
       
       $gridArr = array();
      
      //dd($transArray);
     

      
      
      
}
      ?>



<!doctype html>
<html>
<head>
<meta charset="utf-8">
<!--<link href="css/bootstrap.min.css" rel="stylesheet">-->
<title>Transaction List</title>
<style>
   .slip-list-wrap table {
        
         border:1px solid #efefef;
    }
   .slip-list-wrap  table td, .slip-list-wrap table th{
        padding: 5px;
        
    }
    .slip-list-wrap table tr th{
        background-color: #efefef;
    }
    .slip-list-wrap table td{border-right:1px solid #efefef;}
    
.slip-list-wrap tr:nth-child(odd) {background: #efefef}
.slip-list-wrap tr:nth-child(even) {background: #FFF}
    
</style>

</head>

<body>
    
    <?php
      echo '<div class="slip-list-wrap"><table class="table-stripe" border="0" width="100%">';
      echo "<th>Tr. Date</th>";
     // echo "<th>Description</th>";
      echo "<th>Amount</th>";
      echo "<th>Paid with</th>";
      echo "<th>Cheue No.</th>";
      echo "<th>Cheque Approved Status.</th>";
      echo "<th>Cheque Date.</th>";
      echo "<th>Bank</th>";
      echo "<th>User</th>";
      echo "<th>Doc.</th>"; 
      echo "<th>Print</th>";
      if($isfinance=='2'){echo "<th>Reserve.</th>";}
      foreach($transArray as $val){
          echo "<tr>";
            echo '<td>'.formatDate2($val['trdt']).'</td>';
            //echo '<td>'.$val['naration'].'</td>';
            echo '<td align="center">'.number_format($val['amount'],2).'</td>';
            echo '<td>'.fetchByID('transmode','id',$val['transmode'],'name').'</td>';
            echo '<td>'.$val['checkno'].'</td>';
            if($val['checkno'] != ""){
                echo '<td>Accepted</td>';
            }else{
                echo '<td></td>';
            }
            $chkdt = ($val['chequedt'] == "0000-00-00")?'-':formatDate2($val['chequedt']);
            echo '<td>'.$chkdt.'</td>';
            echo '<td>'.fetchByID('bank',id,$val['bank'],'name').'</td>';
            echo '<td align="center">'.fetchByID('hr','id',$val['makeby'],'hrName').'</td>';
            if($val['document']){
            echo '<td align="center"  class="dataTable2"><a href="'.$val['document'].'" t_arget="_blank" class="btn btn-info btn-xs slip-document"><i class="fa fa-eye"></i></a></td>';
            }else{
                echo '<td align="center"  class="dataTable2">-</td>';
            }
              echo '<td align="center"  class="dataTable2"><a href="transaction_slip.php?trid='.$val['id'].'" class="btn btn-info btn-xs slip-print"><i class="fa fa-print"></i></a></td>';
             if($isfinance=='2' and $val['transmode']=='1' and $val['financadjstmnt']==0 )
             {
                echo '<td align="center"  class="dataTable2"><a href="transaction_reserve.php?trid='.$val['id'].'" data-trxnid='.$val['id'].' class="btn btn-info btn-xs edit-reserve"><i class="fa fa-edit"></i></a></td>';  
             }
           
          echo "</tr>";
      }
      
      $qryinvpay=" select id,`transdt`,`amount`,'Wallet' paywith,'' chno,'' chdt,'' bnk,`makeby` ,'' doc from invoicepayment where invoicid='$rpid'  and transmode='W'";
      $resinvpay=$conn->query($qryinvpay);
      while ($rowinvpay = $resinvpay->fetch_assoc())
      {
        echo "<tr>";
            echo '<td>'.formatDate2($rowinvpay['transdt']).'</td>';
            //echo '<td>'.$val['naration'].'</td>';
            echo '<td align="center">'.number_format($rowinvpay['amount'],2).'</td>';
            echo '<td> Wallet </td>'; 
            echo '<td></td>';
            echo '<td></td>';
            echo '<td> </td>';
            echo '<td> </td>';
            echo '<td align="center">'.fetchByID('hr','id',$rowinvpay['makeby'],'hrName').'</td>';
            if($rowinvpay['doc']){
            echo '<td align="center"  class="dataTable2"><a href="'.$rowinvpay['doc'].'" t_arget="_blank" class="btn btn-info btn-xs slip-document"><i class="fa fa-eye"></i></a></td>';
            }else{
                echo '<td align="center"  class="dataTable2">-</td>';
            }
              echo '<td align="center"  class="dataTable2"><a href="transaction_slip.php?trid='.$rowinvpay['id'].'" class="btn btn-info btn-xs slip-print"><i class="fa fa-print"></i></a></td>';
             if($isfinance=='2' and $val['transmode']=='1' and $val['financadjstmnt']==0 )
             {
                echo '<td align="center"  class="dataTable2"><a href="transaction_reserve.php?trid='.$rowinvpay['id'].'" data-trxnid='.$rowinvpay['id'].' class="btn btn-info btn-xs edit-reserve"><i class="fa fa-edit"></i></a></td>';  
             }
           
          echo "</tr>";
          
          
      }
      
//Cheque approval 
$qryAc = "SELECT ac.checkno, ac.checkdt, b.name bank, ac.amount, ac.st, h.hrName 
          FROM `approval_check` ac LEFT JOIN bank b ON b.id=ac.bank LEFT JOIN hr h ON h.id=ac.makeby 
          WHERE ac.st in (0,1) and ac.invoice = '$rpid'";
// echo $qryAc;die;
$resultAc = $conn->query($qryAc);
while ($rowAc = $resultAc->fetch_assoc()){
    if($rowAc["st"] == 0){
        $st = "Declined";
    }else{
        $st = "Pending";
    }
    echo "<tr>";
            echo '<td></td>';
            echo '<td align="center">'.$rowAc['amount'].'</td>';
            echo '<td>Cheque</td>';
            echo '<td>'.$rowAc['checkno'].'</td>';
            echo '<td>'.$st.'</td>';
            $chkdt = ($rowAc['checkdt'] == "0000-00-00")?'-':formatDate2($rowAc['checkdt']);
            echo '<td>'.$chkdt.'</td>';
            echo '<td>'.$rowAc["bank"].'</td>';
            echo '<td align="center">'.$rowAc["hrName"].'</td>';
            echo '<td align="center"  class="dataTable2">-</td>';
            echo '<td align="center"  class="dataTable2">-</td>';
           
    echo "</tr>";
}
                
      echo "</table></div>";    
    ?>
<!--<script src="js/jquery.min.js"></script>-->
<script>window.jQuery || document.write('<script src="js/jquery.min.js"><\/script>')</script>
<!--<script src="js/bootstrap.min.js"></script>    -->
<!--<script src="js/bootstrap-dialog.min.js"></script>-->
<script src="js/plugins/printThis/printThis.js"></script>    
<script>
$(".dataTable2").on("click",".slip-print.btn",function(){

		
  	    mylink2 = $(this).attr('href');
		
   //alert(mylink);
  
  		BootstrapDialog.show({
							
							title: 'PAYMENT RECEIPT',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						message: $('<div id="printableArea4"></div>').load(mylink2),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: true, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Close',
								action: function(dialog2) {
									dialog2.close();	
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								hotkey: 13, // Enter.
								action: function(dialog2) {
									
									$("#printableArea4").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog2.close();	
									
									},
								
							}],
							//onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
});

$(".dataTable2").on("click",".edit-reserve.btn",function(){

		
mylink2 = $(this).attr('href');
var trxnid = $(this).attr('data-trxnid');
//alert(mylink);

BootstrapDialog.show({
                  
                  title: 'PAYMENT RECEIPT',
                  //message: '<div id="printableArea">'+data.trim()+'</div>',
                  message: $('<div id="printableArea4"></div>').load(mylink2),
                  type: BootstrapDialog.TYPE_PRIMARY,
                  closable: true, // <-- Default value is false
                  closeByBackdrop: false,
                  draggable: true, // <-- Default value is false
                  cssClass: 'show-invoice',
                  buttons: [
                      
                      {
                      icon: 'glyphicon glyphicon-chevron-left',
                      cssClass: 'btn-default',
                      label: ' Close',
                      action: function(dialog2) {
                          dialog2.close();	
                          
                          
                      }
                  },
                      {
                      
                      
                      icon: 'glyphicon glyphicon-ok',
                      cssClass: 'btn-primary',
                      label: ' Update',
                      hotkey: 13, // Enter.
                      action: function(dialog2) {
                          
                        //call ajax to post reserved value;
                        var reserved = $('#reserved').val();
                        
                        
                        $.ajax({   
                            url: "update_reserve.php",
                            type: "POST",
                            data: {reserved: reserved, trxnid: trxnid},
                            success: function(data){
                                swal("Update", data, "success");
                            },
                            error: function(xhr, status, error){
                                swal("Error", "An error occurred while updating the reserve: " + error, "error");
                            }
                        });
                         

                          
                          dialog2.close();	
                          
                          },
                      
                  }],
                  //onshown: function(dialog){  $('.btn-primary').focus();},
              });		






return false;
});


$(".dataTable2").on("click",".slip-document.btn",function(){

		
  	    mylink2 = $(this).attr('href');
		
   //alert(mylink2);
   
   var imagetag = '<img src="'+mylink2+'" width="500">';
  
  		BootstrapDialog.show({
							
							title: 'DOCUMENT',
							//message: '<div id="printableArea">'+data.trim()+'</div>',
    						//message: $('<div id="printableArea4"></div>').load(imagetag),
    						message: $('<div id="printableArea4" align="center"><img src="'+mylink2+'" width="200"></div>'),
							type: BootstrapDialog.TYPE_PRIMARY,
							closable: true, // <-- Default value is false
							closeByBackdrop: false,
							draggable: true, // <-- Default value is false
							cssClass: 'show-invoice',
							buttons: [
								
								{
								icon: 'glyphicon glyphicon-chevron-left',
								cssClass: 'btn-default',
								label: ' Close',
								action: function(dialog2) {
									dialog2.close();	
									
									
								}
							},
								{
								
								
								icon: 'glyphicon glyphicon-ok',
								cssClass: 'btn-primary',
								label: ' Print',
								hotkey: 13, // Enter.
								action: function(dialog2) {
									
									$("#printableArea4").printThis({
										importCSS: false, 
										importStyle: true,
									});
		
									
									dialog2.close();	
									
									},
								
							}],
							//onshown: function(dialog){  $('.btn-primary').focus();},
						});		
  
  
  
  
  
  
  	return false;
}); 
</script>
 
    
    
    </body>
    
</html>
