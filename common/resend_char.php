<?php
require "conn.php";

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/hc.php?res=01&msg='New Entry'&id=''&mod=4");
}
else
{   
    if ( isset( $_POST['add'] ) ) {
       // $make_yr=date('Y');
      //  $getpo="SELECT concat(YEAR(CURDATE()),(max(substring(poid,5))+1)) po FROM `po`";
        $hrid= $_REQUEST['cmbempnm']; 
        $np = $_POST['np'];               if($np==''){$np='NULL';}
        $cnp = $_POST['cnp'];               if($cnp==''){$dob='NULL';}
        
        $make_date=date('Y-m-d H:i:s');
        $op_date =date('Y-m-d');
        
        //$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        //$passw =substr(str_shuffle($chars),0,8);
      
   
      
        $gethc="select email from hr where id ='" .$hrid."'";    
        if ($conn->connect_error) { echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $getresult = $conn->query($gethc); 
            if ($getresult->num_rows > 0)
            {
                while($getrow = $getresult->fetch_assoc()) 
                    { 
                       $eml=$getrow["email"];
                    }
                       if($np==$cnp)
                       {
                        $qry="update hr set `hidden_char`='".$cnp."' where id='".$hrid."'";
                        $err="Password sent to you through email, Please check your inbox";
                       }
                       else
                       {
                        $err="Your New Password and Confirm password is not match !!!";
                       }
            }
            else
            {
               $err="Unauthentic Attempt !!";
            }
        }
      
        
        
        //echo $itqry;die;
         //if ($conn->query($usrqry) == TRUE) { $err="User added successfully"; mail($empEmail,"Password For CRM BitFlow",$passw); }
        
  //echo $qry; die;
    }
   
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                mail($eml,"Password For Renaissance","Your password for Renaissance: ".$cnp);
                header("Location: ".$hostpath."/hc_char_modi.php?res=1&msg=".$err."&id=".$aid."&mod=5");
    } else {
         //$err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/hc_char_modi.php?res=2&msg=".$err."&id=''&mod=5");
    }
    
    $conn->close();
}
?>