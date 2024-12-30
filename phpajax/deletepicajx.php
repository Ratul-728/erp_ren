<?php

if($_REQUEST ['action'] == 'deletepic' && isset($_REQUEST['pictodelete'])){
    
   
    $filename = basename($_REQUEST['pictodelete']);
    

     
    if(@unlink('./employee_picture/thumbs/'.$filename)){
        //echo 'Picture removed';
    }else{
        echo 0;
    }
    
   if(@unlink('./employee_picture/original/'.$filename)){
        //echo 'Picture removed';
    }else{
        echo 0;
    }
    
    
      /*if(@unlink('../post_picture/slider/'.$filename)){
        echo 'Picture removed';
    }else{
        echo 0;
    } */
}
?>