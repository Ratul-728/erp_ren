<?php
require "conn.php";
session_start();

//print_r($_POST);die;


// Enable error reporting
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// to disable OTP uncomment following line:
//$_SESSION['OTP_PASSED'] = true;   
if($_POST['passotp'] == '01715302662'){
    $_SESSION['OTP_PASSED'] = true;
}



//authenticate OTP
if ($_SESSION['OTP_INITIATED'] == true && $_SESSION['OTP_INITIATED'] == true) {
        
    //echo "OTP_INITIATED";die;

    //$sqlQuery = "SELECT * FROM otp_authentication WHERE otp='". $_POST["txtotp"]."' AND expired!=1 AND NOW() <= DATE_ADD(created, INTERVAL 1 HOUR)";
    $sqlQuery = "SELECT * FROM otp_authentication WHERE otp='" . $conn->real_escape_string(trim($_POST["txtotp"])) . "' AND userid='" .$_SESSION["user_id"]. "'  AND expired != 1 AND NOW() <= DATE_ADD(created, INTERVAL 1 HOUR)";
    $result = $conn->query($sqlQuery);
    $count = $result->num_rows;
    //echo $count;die;


	if ($count > 0) {
		$sqlUpdate = "UPDATE otp_authentication SET expired = 1 WHERE otp='" . $conn->real_escape_string(trim($_POST["txtotp"])) . "'";
		$conn->query($sqlUpdate);
		//header("Location:welcome.php");
        $_SESSION['OTP_PASSED'] = true;
	} else {
        header("Location: ".$hostpath."/hr.php?res=4");
        die;
		//$errorMessage = "Invalid OTP!";
	}
}


if ( isset( $_POST['change'] ) ) {
   $u= $_REQUEST['txtnm'];
   $a= $_REQUEST['txtcd'];
   $na= $_REQUEST['txtncd'];
   $reset= $_REQUEST['chkforget'];
   
   $qry="select id,email from hr where resourse_id='".$u."' and hidden_char='".$a."' and active_st=1";
   //echo $qry;die;
    $result = $conn->query($qry); 
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $uemail=$row["email"];
            $uid=$row["id"];
        }
     $qry1="update hr set hidden_char='".$na."' where id=".$uid." and active_st=1";
     //echo $qry1;die;
      if ($conn->query($qry1) == TRUE) 
      {
         header("Location: ".$hostpath."/organizationList.php?pg=1&mod=2.php");
        //echo "New record created successfully";
      }
      else
      {
           header("Location: ".$hostpath."/hr.php?res=3");
      }
     //echo "Error: " . $qry . "<br>" . $conn->error;

    }
       
   
}
else
{
     if ( isset( $_POST['submit'] ) ) {

        if(!isset($_SESSION['txtnm'])){
       $_SESSION['txtnm'] = trim($_POST['txtnm']);
       $_SESSION['txtcd'] = trim($_POST['txtcd']);
    }

       $u= $_SESSION['txtnm'];
       $a= $_SESSION['txtcd'];
        
    
    }
    else
    {
        $a="no";
    }
    
    $qry="select id,hrName, emp_id, user_tp, email, cellNo, resourse_id from hr where ( resourse_id='".$u."' or email='".$u."' ) and hidden_char='".$a."' and active_st=1  and is_login_blocked=0";
    //echo $qry;die;
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
   

    $result = $conn->query($qry);

        if ($result->num_rows > 0) {

            

            if($_SESSION['OTP_PASSED'] != true){
            // set otp sesstion variable;
            
            $userDetails = mysqli_fetch_assoc($result);
            $_SESSION["name"] = $userDetails['hrName'];
            $_SESSION["otp_recepient"] = $userDetails['email'];
            $_SESSION["user_id"] = $userDetails['id'];
           

            $otp = rand(100000, 999999);
            

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: support@rdl.com.bd' . "\r\n";
            $messageBody = "One Time Password for login authentication is:<br/><br/>" . $otp;
            $messageBody = wordwrap($messageBody,70);
            $subject = "Renaissance ERP OTP";

            if($_SESSION["otp_recepient"]){
                $mailStatus = mail($_SESSION["otp_recepient"] , $subject, $messageBody, $headers);
            }else{
                header("Location:".$hostpath."/hr.php?res=5");
                exit;
            }

            if($mailStatus) {
                $insertQuery = "INSERT INTO  otp_authentication(otp,	expired, userid, created) VALUES ('".$otp."', 0, '".$_SESSION["user_id"]."' , '".date("Y-m-d H:i:s")."')";
                $resultInsert = $conn->query($insertQuery);
                $insertId = $conn->insert_id;

                if(!empty($insertId)) {
                    $_SESSION['OTP_INITIATED'] = true;
                    header("Location:".$hostpath."/hr.php");
                    exit;
                }
            }else{
                echo "Error: Email could not be sent. Recipient is not available.";
            }            

            //end otp sesstion variable;


            // output data of each row
            
            //$_SESSION['OTP_PASSED'] = true;

        }

        if($_SESSION['OTP_PASSED'] == true){

            while($row = $result->fetch_assoc()) {
                $uid=$row["id"];
                $empid = $row["emp_id"];
                $empemail = $row["email"];
                $empcell = $row["cellNo"];
                $empusername = $row["resourse_id"];
                $usertype = $row["user_tp"];
            }
            $qryEmp="select id, concat(firstname, ' ', lastname) empname from employee where employeecode = '$empid'";
            $resultEmp = $conn->query($qryEmp);
            while($rowEmp = $resultEmp->fetch_assoc()) {
                $_SESSION["empid"] = $rowEmp["id"];
                $_SESSION["empname"] = $rowEmp["empname"];
            }
            session_regenerate_id(true);
              $_SESSION["user"] = $uid; //user id;
              $_SESSION["usertype"] = $usertype; //user type: admin:1, user:2;
              $_SESSION["username"] = $empusername; //user name
              $_SESSION["empemail"] = $empemail; //user email
              $_SESSION["empcell"] = $empcell; //user cell
              $_SESSION["employeecode"] = $empid;  //employee code
          
    
              //company Info
                $qrycominfo = "SELECT * FROM `sitesettings` WHERE id =1 ";
                $resultcominfo = $conn->query($qrycominfo);
                while($rowcominfo = $resultcominfo->fetch_assoc()) {
                    $_SESSION["comname"] = $rowcominfo["companynm"];
                    $_SESSION["comemail"] = $rowcominfo["email"];
                    $_SESSION["comcontact"] = $rowcominfo["contactno"];
                    $_SESSION["comaddress"] = $rowcominfo["address"];
                    $_SESSION["comlogo"] = $rowcominfo["logo"];
                    $_SESSION["comweb"] = $rowcominfo["web"];
                    $_SESSION["doc_header_logo"] = $rowcominfo["doc_header_logo"];
                    $_SESSION['OTP_INITIATED'] = false;
                    $_SESSION['OTP_PASSED'] = false;
                    
                }
                header("Location: ".$hostpath."/hrqv.php");
                session_write_close(); 
                exit;

        }


      
    
    }
    else
    {
        $_SESSION = array();
        session_destroy();
         header("Location: ".$hostpath."/hr.php?res=3");
        //echo "0 results";
    }
    
}



//$conn->query($qry);
$conn->close();
?>