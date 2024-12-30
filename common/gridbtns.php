<?php
session_start();
//print_r($_SESSION);

require_once("conn.php");
ini_set('display_errors', 1);
//echo 'dd';die;

include_once('../rak_framework/fetch.php');
//include_once('../rak_framework/listgrabber.php');

//getGridBtns($currSection), //getGridBtns('Feature"),
$currSection = $_REQUEST['currSection'];
function getGridBtns($currSection){
/*    
    //get menu ID from mainMenu by $currSecton;
    $sectionID = fetchByID('mainMenu','currSection',$currSection,'id');
    echo $sectionID;
    
    //get button array fom  hrAuth table;
  
    
 //route
 $inputRouteData = array(
 'TableName' => 'route',
 'OrderBy' => 'name',
 'ASDSOrder' => 'ASC',
 
 'id' => '',
 'name' => ''
 );
 
 listData($inputRouteData,$routeArray);
 
  
 print_r($routeArray);
    
  */  
    
    
$db_name="bithut_bitflow";
$mysql_username="bithut_kazi";
$mysql_password="asdf1234X";

$server_name="localhost";
//$server_name="143.95.233.87";
$conn=mysqli_connect($server_name,$mysql_username,$mysql_password,$db_name);
    
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
    
    
    
  $str = 'SELECT * FROM hrAuth WHERE hrid='.$_SESSION['user'].' AND menuid=(SELECT id FROM mainMenu WHERE currSection="'.$currSection.'")';
  //echo  $str ;
  $result =  mysqli_query($conn,$str); 
    

    
    while ($row = mysqli_fetch_assoc($result)) {
        //quotation buttons
        if($currSection == 'quotation'){
            
            if($row["btn_view"] == 1){
            echo '<a data-socode="QT-000299" href="quotation_view.php" class="show-invoice btn btn-info btn-xs" title="View Quotation" target="_blank"><i class="fa fa-eye"></i></a> | ';
            }
            if($row["btn_delete"] == 1)
            echo '<a class="btn btn-info btn-xs" title="Edit" href="quotationEntry.php?res=4&id=299mod=2"><i class="fa fa-edit"></i></a> | ';
            
            if($row['btn_delete'] == 1)
            echo '<a class="btn btn-info btn-xs griddelbtn" title="Delete" href="common/delobj.php?obj=quotation&amp;ret=quotationList&amp;mod=2&amp;id=299"><i class="fa fa-remove"></i></a>';
        }
    }
    
}

getGridBtns($currSection);


?>