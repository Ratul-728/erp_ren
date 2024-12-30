<?php
require "conn.php";
session_start();
error_reporting(E_ALL);

// Enable displaying errors
ini_set('display_errors', 1);
// echo 'hi';die;
if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/rawitem.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $htype = $_REQUEST['htype'];               if($htype ==''){$htype='1';}
        $details = $_REQUEST["details"];
        
        $make_date=date('Y-m-d H:i:s');
        $date = $_POST['action_dt'];           if($date==''){$date=date('Y-m-d');}
        $date_to = $_POST['action_dt_to'];           if($date_to==''){$date_to=date('Y-m-d');}
        $hrid= $_SESSION["user"];
        
        $date = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$date);
        $date_to = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$date_to);
        
        // Convert dates to DateTime objects
        $datetimestamp =  strtotime($date);
        $date = date('Y-m-d', $datetimestamp);
        
        $date_to =  strtotime($date_to);
        $date_to = date('Y-m-d', $date_to);
        
        //echo $date;die;
        // Check if $date is greater than $date_to
        if ($date > $date_to) 
        {
            $err =  'From date is greater than to date';
            header("Location: ".$hostpath."/holidayList.php?res=2&msg=".$err."&id=''&mod=4");
            
        } 
        else 
        {
            // Loop through dates from $date to $date_to
           // $interval = new DateInterval('P1D'); // 1 day interval
            //$daterange = new DatePeriod($date, $interval, $date_to->modify('+1 day')); // Include $date_to in the loop
            //echo $date;die;
           // $currdate=$date;
        //    $curdatetimestamp=strtotime($currdate);
          //  $nextdaytimestamp=$datetimestamp+(24*60*60);
            //$nextdate=date('Y-m-d', $nextdaytimestamp);
            $errFlag = 0;
            $currentTimestamp = strtotime($date);
            $targetTimestamp = strtotime($date_to);
            
            $qry="INSERT INTO `Holiday`(`holidaytype`, `date`, `details`, `makedt`, `makeby`, `st`) VALUES (".$htype.",'".$date."', '".$details."', '".$make_date."',".$hrid.", 1)";
            //echo $qry;//die;
            if ($conn->query($qry) == FALSE) {$errFlag ++; }
            
            do {
                // Add one day to the current timestamp
                $currentTimestamp += 24 * 60 * 60;
                
                // Format the new timestamp to Y-m-d format
                $nextDay = date('Y-m-d', $currentTimestamp);
                
                $qry="INSERT INTO `Holiday`(`holidaytype`, `date`, `details`, `makedt`, `makeby`, `st`) VALUES (".$htype.",'".$nextDay."', '".$details."', '".$make_date."',".$hrid.", 1)";
                //echo $qry;die;
               if ($conn->query($qry) == FALSE) {$errFlag ++; }
                // Output the next day
                //echo "Next day: " . $nextDay . "\n";
               // echo $qry;
            } while ($currentTimestamp < $targetTimestamp);
           // die;
            
            /*foreach ($daterange as $date) {
                $qry="INSERT INTO `Holiday`(`holidaytype`, `date`, `details`, `makedt`, `makeby`, `st`) VALUES (".$htype.",'".$date."', '".$details."', '".$make_date."',".$hrid.", 1)";
                //echo $qry;die;
                if ($conn->query($qry) == FALSE) {
                    $errFlag ++;
                }
            }*/
        }
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        $tid= $_REQUEST['iid'];
        $htype = $_REQUEST['htype'];               if($htype ==''){$htype='1';}
        $details = $_REQUEST["details"];
        
        $make_date=date('Y-m-d H:i:s');
        $date = $_POST['action_dt'];           if($date==''){$date=date('Y-m-d');}
        $date = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/","$3-$2-$1",$date);
        
        $qry="UPDATE `Holiday` SET `holidaytype`=".$htype.",`date`='".$date."',`details`='".$details."' WHERE ID = ".$tid;
        //echo $qry;die;
        $err="item updated successfully";

    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($errFlag == 0) {
                header("Location: ".$hostpath."/holidayList.php?res=1&msg=".$err."&id=".$poid."&mod=4");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/holidayList.php?res=2&msg=".$err."&id=''&mod=4");
    }
    
    $conn->close();
}
?>