//<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $title= $_REQUEST['leavetitle'];               //if($title==''){$title='NULL';}
        $day = $_REQUEST["leaveday"]; if($day == '') $day = 0;
        
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
        $day_contractual = $_POST["leaveday_contractual"];
        $paid = $_POST["paid"]; if($paid == "") $paid = 0;
    
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
         
        $qry="INSERT INTO `leaveType`(`title`, `day`, day_contractual, `remarks`, `makedt`, `makeby`, `st`, paid) VALUES ('".$title."',".$day.", '$day_contractual' ,'".$details."','".$make_date."','".$hrid."', 1, '$paid')";
        $err="Leave created successfully";
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $title= $_REQUEST['leavetitle'];               //if($title==''){$title='NULL';}
        $day = $_REQUEST["leaveday"]; if($day == '') $day = 0;
        $details = $_POST['details'];           //if($details==''){$details='NULL';}
        $day_contractual = $_POST["leaveday_contractual"];
        $paid = $_POST["paid"]; if($paid == "") $paid = 0;
        
        $qry="UPDATE `leaveType` SET `title`='".$title."',`day`='".$day."',`remarks`='".$details."', day_contractual = '$day_contractual', paid = '$paid' WHERE id = ".$tid;
        //echo $qry;die;
        $err="Item updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/leavetypeList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/leavetypeList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>