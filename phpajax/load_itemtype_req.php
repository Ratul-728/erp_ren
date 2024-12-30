<?php
require "../common/conn.php";
$con = $conn;
//print_r($_REQUEST);die;

$nameid = $_REQUEST["nameid"];
$action = $_REQUEST["postaction"];
?>
<select class="js-example-basic-multiple  form-control" name="attval[<?php echo $nameid;?>][]" multiple="multiple" required>
<?php


//$attr = array('Red','Green','Blue','White','Black','Brown','Purple','Orange');
//print_r($attr);

//foreach($attr as $key => $val){
//	$str .= '<option>'.$val.'</option>';
//}

if($action == "loadattrval"){
     $qryitm="SELECT req.id, req.`requision_no`, reqdet.qty FROM `requision` req LEFT JOIN requision_details reqdet ON req.requision_no = reqdet.requision_no WHERE (req.status = 2 or req.status = 3 ) and reqdet.product = ".$nameid;
     //echo $qryitm;die;
     $resultitm = $conn->query($qryitm); 
     if ($resultitm->num_rows > 0) 
     {
         while($rowitm = $resultitm->fetch_assoc()) 
            { 
             $tid= $rowitm["id"];  $nm=$rowitm["requision_no"]; 
             $qty = $rowitm["qty"];
             $str .= '<option>'.$nm.'</option>';
            }
     }
}else if($action == "loadvendor"){
    $qryitm="SELECT id, name FROM organization WHERE vendor = 1";
     //echo $qryitm;die;
     $resultitm = $conn->query($qryitm); 
     if ($resultitm->num_rows > 0) 
     {
         while($rowitm = $resultitm->fetch_assoc()) 
            { 
             $tid= $rowitm["id"];  $nm=$rowitm["name"];
             $str .= '<option>'.$nm.'</option>';
            }
     }
}

echo $str;

?>
</select>