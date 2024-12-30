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
<link href="../../assets/css/bootstrap.min.css" rel="stylesheet">
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
</style>    


 <div  id="printableArea"> 
    <div>
   <?php echo ("<h3>Chalan No # ".$poid."</h3>"); ?>
    </div>
    
    <div class="wrapper">
    <?php    
    $qry="select i.`barcode`,i.qty,i.poid ,i.mrp,pr.name prd from po p, poitem i,product pr
where p.poid=i.poid and i.itemid=pr.id and p.id=".$chid;
	$cols = 6;
	
    $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        $bc=$row["barcode"];
						$qty=$row["qty"];
						$mrp=number_format($row["mrp"],2);
						$prd=$row["prd"];
						$loop = $qty;
						
    ?>
					<div class="section">
					<h4>CODE: 
						<?php   echo $bc; ?>
						
					</h4> 
						<table>
                    <?php 
					$flag = 1;
					while($qty>0){
							
						
							
					if($flag == 1){	echo '<tr class="code">'; }
					?>
                     
						<td class="box">
                         <?php
						$itemTitle = substr($prd, 0, 45);
							//echo '<hr>'. $flag.'<hr>';
							echo '<div class="almas">ALMAS</div>';
							echo '<div class="itemname">'.$itemTitle.'</div>';		
							echo "<img alt='testing' src='barcode.php?codetype=Code39&size=40&text=".$bc."&print=true'/>";
							echo '<div class="price">Tk: '.$mrp.' +VAT</div>';
                            $qty--; 
						?>
						</td>
							<?php
							if($loop < 5){
								$nloop = (5 - $loop);
								//echo 'Loop :'. $nloop.'<br>';
								for($l = 1; $l<=$nloop; $l++){
									//echo '<br>'.$l.'<br>';
									echo '<td><img src="../images/blank.png" width="227" height="55"></td>';
									$loop--;
								}
							}
							?>
                            
                <?php  
					
					$flag++;
					if($flag == $cols){	echo '</tr>';	}
						
					if($flag==$cols){$flag = 1;}
						
					}
					
					
					
					?>
						</table>
					</div> <!--- end of div class="section" --->
		<?php
                    }
            } ?>
        <br>
   
            
    </div>
</div>    

<div>
    <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="printbtn"  onclick="printDiv('printableArea')">
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
</body>
</html>
<?php } 
//if(isset($_POST['generate_barcode']))
//{
 //$text=$bc;//$_POST['barcode_text'];
 //echo "<img alt='testing' src='barcode.php?codetype=Code39&size=40&text=".$text."&print=true'/>";
//}
?>