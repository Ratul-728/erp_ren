<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $itid= $_GET['id'];

    
    if ($res==1)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }
    if ($res==2)
    {
        echo "<script type='text/javascript'>alert('".$msg."')</script>"; 
    }

    if ($res==4 || $res== 6)
    {
        $qry="SELECT a.`id`, a.`tikcketno`, a.`sub`, a.`organization`, a.`issuetype`, a.`issuesubtype`, a.`severity`, concat_ws(' ', b.firstname, b.lastname) `assigned`, a.`status`, a.`reporter`, a.`channel`, a.`issuedetails`, a.`issuedate`,DATE_FORMAT(`probabledate`,'%e/%c/%Y') `probabledate`, a.`product`, a.`accountmanager` FROM `issueticket` a LEFT JOIN employee b ON a.`assigned` = b.id where a.id=".$itid; 
       // echo $qry; die;
        if ($conn->connect_error)
        {
            echo "Connection failed: " . $conn->connect_error;
        }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                    { 
                        $issid=$row["id"];$tikcketno=$row["tikcketno"]; $sub=$row["sub"];  $organization=$row["organization"];$issuetype=$row["issuetype"];  $issuesubtype=$row["issuesubtype"];
                        $severity=$row["severity"];  $assigned=$row["assigned"]; $status=$row["status"]; $reporter=$row["reporter"];$channel=$row["channel"];  
                        $issuedetails=$row["issuedetails"]; $issuedate=$row["issuedate"]; $probabledate=$row["probabledate"];$product=$row["product"];  $accountmanager=$row["accountmanager"]; 
                    }
            }
        }
        if($res == 4)
            $mode=2;//update mode
        else{
            $mode = 3; //Copy mode
        }
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
                       $issid='';$tikcketno='Auto'; $sub='';  $organization='';$issuetype='';  $customer='';
                        $severity='';  $assigned=''; $status=''; $reporter='';$channel='';  
                        $issuedetails=''; $issuedate=''; $probabledate='';$product='';  $accountmanager=''; 
    $mode=1;//Insert mode
                    
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'issueadmin';
    $currPage = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?>
<link rel="stylesheet" href="./css/ak-bit.css">
<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Issue Ticket</span>
        </div>
        <?php  include_once('menu.php');?>
	    <div style="height:54px;">
	    </div> 
    </div>
    </div>
    </body>
    </html>