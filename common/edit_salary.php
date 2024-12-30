<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['update'] ) ) {
        
        $sid= $_REQUEST['sid'];
        $house= $_REQUEST['house']; $basic= $_REQUEST['basic']; $medical= $_REQUEST['medical']; $transport = $_REQUEST['transport'];
        $late= $_REQUEST['late']; $ait= $_REQUEST['ait']; $adv= $_REQUEST['adv']; $loan= $_REQUEST['loan']; $others= $_REQUEST['others']; 
        
        $total = ($basic + $house + $medical + $transport) - ($late + $ait);
        
        // $qry="UPDATE `monthlysalary` SET `benft_1`='".$basic."', `benft_2`='".$house."' , `benft_3`='".$medical."' , `benft_4`='".$transport."' ,
        // `benft_5`='".$late."' , `benft_11`='".$ait."', `total` = '".$total."' 
        // WHERE ID = ".$sid;
        
        $qry="UPDATE `monthlysalary` SET `benft_1`='".$basic."', `benft_5`='".$late."' , `benft_11`='".$ait."'
        , `advance`='".$adv."', `loans`='".$loan."', `others`='".$others."'
        , `total` = '".$total."' 
        WHERE ID = ".$sid;
        
        // echo $qry;die;
        $err="salary updated successfully"; 

    } 
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/rpt_salary_sheet.php?res=1&msg=".$err."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/rpt_salary_sheet.php?res=2&msg=".$err."&mod=4");
    }
    
    $conn->close();
}
?>