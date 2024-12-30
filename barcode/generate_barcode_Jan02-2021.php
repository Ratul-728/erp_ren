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
    
<?php
    //include_once('../common_top_body.php');
?>
<!--<div id="wrapper"> 
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Challan Order</span>
        </div>
        <?php include_once('menu.php'); ?>
       
        <div style="height:54px;"></div>
    </div>-->

 <div  id="printableArea"> 
    <div>
   <?php echo ("Chalan No # ".$poid); ?>
    </div>
    <br>
    <div class="wrapper">
    <?php    
    $qry="select i.`barcode`,i.qty,i.poid from po p, poitem i where p.poid=i.poid and p.id=".$chid;

    $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        $bc=$row["barcode"];$qty=$row["qty"];
    ?>
        <div>
                     <?php   echo($bc); ?>
         <br>               
                      <?php  echo("=========================="); ?>
        </div> <br>               
                    <?php     while($qty>0)
                        {?>
                    <div class="code">       
                         <?php   echo "<img alt='testing' src='barcode.php?codetype=Code39&size=20&text=".$bc."&print=true'/>";
                            $qty--; ?>
                    </div><br>        
                <?php    }
                    }
            } ?>
        <br>
        <div>
            <?php  echo("=======================End"); ?>
        </div>    
            
    </div>
</div>    

<div>
    <input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')">
</div>

<?php
//	include_once('../common_footer.php');
?>
<script>
    
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