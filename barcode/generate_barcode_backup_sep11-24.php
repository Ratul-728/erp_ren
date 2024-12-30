<?php
require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $chid= $_GET['id'];
    $poid= $_GET['chid'];
?> 
<?php
    // include_once('../common_header.php');
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Barcode  - RDL ERP</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" href="../images/favicon.png">
</head>
<body class="form">
<style>
	body{margin: 0;}
.infobar-heading{
   padding-top:10px;
}
#printableArea h3{
    margin-top: 0;
    padding: 5px 5px;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 19px;
    border-bottom: 0px solid #000;
    padding-bottom: 10px;
    background: #efefef;
}

#printableArea h4{
    font-size: 15px;
    font-weight: bold;
    padding-left: 10px;
}

#printableArea .section{
    border: 0px solid #000;
    padding: 0px;
    display: table;
}

#printableArea .section  td{
    text-align: center;
    border: 1px dashed #c0c0c0;
    padding-top: 3px;
}

#printableArea .wrapper > {
    display: block;
    float: left;
    border: 1px solid #c0c0c0;
    
    padding-top: 20px;
}
	
	
#printableArea .code .box .almas{
    font-weight: bold;
    font-size: 10px;
    margin-top: 0;
}

#printableArea .code .box .itemname{
    font-size: 8px;
    text-transform: uppercase;
    padding-bottom: 3px;
    font-weight: bold;
}

#printableArea .code .box .price{
    font-size: 8px;
    text-transform: uppercase;
    padding-bottom: 3px;
    font-weight: bold;
}


#printableArea .section  td{
    padding-top: 5px;
}
.printit{
    cursor:pointer;
}

#printableArea22, 
#printableArea22 div{
    text-align: center;
}

#printableArea22 img{
    text-align: center;
    width: 300px;
}

.parent{
    width: 1200px;
    display: flex;
    flex-wrap: wrap;
    border: 0px solid red;
    margin-bottom: 20px;
}
.printit div{
    text-align: center;
}

#printableArea22 img{
    text-align: center;
    width: 300px;
}

#printableArea  .printit{
    text-align: center;
    border: 1px dashed #c0c0c0;
    padding: 15px;
    width: 300px;
}

#printableArea22 .printit{
    padding:15px;
    border: 1px dashed #c0c0c0;
    
}


@media (min-width: 768px) {
  .modal-dialog {
    width: 400px;
    margin: 30px auto;
  }
}
    
    
</style>    


 <div  id="printableArea" class = "col-lg-12 col-md-12 col-sm-12"> 
    <div>
   <?php echo ("<h3>Chalan No # ".$poid."</h3>"); ?>
    </div>
    
    <div class="wrapper">
    <?php    
   // echo $poid;die;
    if($poid=='0')
    {
      $qry="select i.`barcode`,COALESCE(p.qty,0) qty ,i.name prdnm,(i.rate+i.rate*i.vat*0.01) rate,i.parts ,i.length,i.lengthunit,i.width,i.widthunit
      ,i.height,i.heightunit
      ,i.colortext color,ic.name itmcat,b.title brand
from  item i left join purchase_landing_item p on p.productId=i.id LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` LEFT JOIN brand b on i.brand=b.id 
left join color c on i.color=c.id
        where i.id=".$chid;
        //echo $qry;die;
    }
    else
    {
        
        $qry="select i.`barcode`,COALESCE(p.qty,0) qty ,i.name ,(i.rate+i.rate*i.vat*0.01) rate,i.parts ,i.length,i.lengthunit,i.width,i.widthunit
        ,i.height,i.heightunit
        ,i.colortext color,ic.name itmcat,b.title brand
from  item i left join purchase_landing_item p on p.productId=i.id  
left join purchase_landing pl on p.pu_id=pl.id LEFT JOIN `itmCat` ic ON  i.`catagory`=ic.`id` 
LEFT JOIN brand b on i.brand=b.id left join color c on i.color=c.id pl.id=".$chid;//i.qty
    }
    
   ////,c.Name color// echo $qry;die;

	
    $result = $conn->query($qry); 
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        { 
            $bc=$row["barcode"];
            $bc_parrt=$row["parts"];
			$qty=$row["qty"];
			$mrp=$row["rate"];
			$prdnm=$row["prdnm"];
			$length=$row["length"].'-'.$row["lengthunit"];
			$width=$row["width"].'-'.$row["widthunit"];
			$height=$row["height"].'-'.$row["heightunit"];
			$color=$row["color"];
			$itmcat=$row["itmcat"];
			$brand=$row["brand"];
			//if($bc_parrt>1){$bc=$bc.'-'.$bc_parrt;}
						
						
?>
			<div class="section">
			<h4>CODE: 
				<?php   echo $bc; ?>
			</h4> 
			
<?php 
				$flag = 1;
				while($qty>=0)
				{
				    
				   
?>
                     <div class="parent">
					<div class="printit">
                     <?php
					    $itemTitle = substr($prd, 0, 45);
						//echo '<hr>'. $flag.'<hr>';
						echo '<div class="itemname">'.$prdnm.'</div>';	
						echo '<div class="almas">'.$brand.'</div>';
						echo '<div class="almas">'.$itmcat.'</div>';
						echo "<div><img alt='".$bc."' src='barcode.php?codetype=Code39&size=40&text=".$bc."&print=true'/></div>";
						echo '<div class="price">Tk: '.$mrp.' Including VAT</div>';
						echo '<div class="price"> length: '.$length.' width: '.$width.' height: '.$height.'</div>';
						echo '<div class="price"> Total Parts: '.$bc_parrt.'  Color: '.$color.' </div>';
						echo '</div>';
                        $qty--; 
                        $partscount=$bc_parrt;
                        while($partscount>0)
                        {
                            
?>
                           
<?php 
                                $partscount--; $partsno=($bc_parrt-$partscount);
                                $itemTitle = substr($prd, 0, 45);
                                $partsbc=$bc."-".$partsno;
							    echo '<div class="printit"><div class="itemname">'.$prdnm.'</div>';	
							    echo '<div class="almas">'.$brand.'</div>';
							    echo '<div class="almas">'.$itmcat.'</div>';
							    echo "<div><img alt='".$bc."' src='barcode.php?codetype=Code39&size=40&text=".$partsbc."&print=true'/></div>";
							    echo '<div class="price">Tk: '.$mrp.' Including VAT</div>';
							    echo '<div class="price"> length: '.$length.' width: '.$width.' height: '.$height.'</div>';
							    echo '<div class="price"> Parts no: '.$partsno.'  Color: '.$color.' </div></div>';
?> 
                            
<?php   
                             
 
                                
                        }//while($partscount>1)
                            
?>
						
                            
<?php  

                        
						echo '</div>';
						
				} //while($qty>=0){
					
					
					
?>
						
					</div> <!--- end of div class="section" --->
<?php
        }
    } 
?>
        <br>
   
            
    </div>
</div>    

<div>
    <input class="btn btn-lg btn-primary" type="submit" name="cancel" value="Print"  id="printbtn"  onclick="printDiv('printableArea')">
</div>

<?php
//	include_once('../common_footer.php');
?>
<script>
    /*
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     //document.body.innerHTML = printContents;
     //document.getElementById('header').style.display = 'none';
    //document.getElementById('footer').style.display = 'none';
    document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}
*/
function printDiv(divName) {
    //var printContents = document.getElementById(divName).innerHTML;
    //var originalContents = document.body.innerHTML;
    //document.body.innerHTML = printContents;
    //document.getElementById('header').style.display = 'none';
    document.getElementById('printbtn').style.display = 'none';
	print();
}

</script>
<script src="../js/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/bootstrap-dialog.min.js"></script>
<script src="../js/plugins/printThis/printThis.js"></script>



<script>







$(".printit").on("click", function() {
    var mylink = $(this).html();

    // Create a temporary container to hold the content and apply centering styles
    var tempContainer = $('<div id="tempContainer"></div>').css({
        position: 'absolute',
        top: '-9999px',
        left: '-9999px',
        width: '400px',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        textAlign: 'center',
        flexDirection: 'column',
        border: '1px dashed #c0c0c0',
    }).html(mylink).appendTo('body');

    // Ensure the barcode image has a width of 300px
    tempContainer.find('img').css({
        width: '1500px!important',
        height: 'auto'
    });

    // Convert the content to an image using html2canvas with a high scale for better resolution
    html2canvas(tempContainer[0], { scale: 3 }).then(function(canvas) {
        var imageData = canvas.toDataURL("image/png");

        // Now show the image in the BootstrapDialog
        BootstrapDialog.show({
            title: $(this).data('st'),
            message: '<div id="printableArea22"><img src="' + imageData + '" alt="Printable Content" /></div>',
            type: BootstrapDialog.TYPE_PRIMARY,
            closable: true,
            closeByBackdrop: false,
            draggable: false,
            cssClass: 'show-invoice',
            buttons: [
                {
                    icon: 'glyphicon glyphicon-chevron-left',
                    cssClass: 'btn-default',
                    label: 'Cancel',
                    action: function(dialog) {
                        dialog.close();
                    }
                },
                {
                    icon: 'glyphicon glyphicon-ok',
                    cssClass: 'btn-primary',
                    label: 'Print',
                    action: function(dialog) {
                        $("#printableArea22 img").printThis({
                            importCSS: false,
                            importStyle: true,
                        });
                        dialog.close();
                    }
                }
            ],
            onshown: function(dialog) {
                $('.btn-primary').focus();
            }
        });

        // Remove the temporary container after capturing
        tempContainer.remove();
    });

    return false;
});





</script>

</body>
</html>
<?php } 
//if(isset($_POST['generate_barcode']))
//{
 //$text=$bc;//$_POST['barcode_text'];
 //echo "<img alt='testing' src='barcode.php?codetype=Code39&size=40&text=".$text."&print=true'/>";
//}
?>