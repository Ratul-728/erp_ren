<?php
require "conn.php";
session_start();

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/soitem.php?res=01&msg='New Entry'&id=''&mod=2");
}
else
{
     $errflag=0;
     $poid=0;
     $err= 0;
     
    if ( isset( $_POST['add'] ) ) 
    {
        $title = $_POST["title"];
        $grade = $_POST["grade"];
        $package = $_POST["package"];
        
        $detid = $_POST["detid"];
        $benefit = $_POST["ben"];
        $benamount = $_POST["bamount"];
        $percent = $_POST["per"];
        $cycle = $_POST["cycle"];
        
        
        
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
        $item = $_POST['ben'];
     
        
           if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $ben = $benefit[$i];$bamount = $benamount[$i]; $per = $percent[$i]; if($per == '') $per = 0;$cycl = $cycle[$i];
                            if($bamount > 0){
                            $itqry="INSERT INTO `pakageSetupdetails`( `pakage`, `scale`, `benifittp`, `befitamount`, `isPercentage`, `cycle`, `makedt`, `makeby`) 
                                                    VALUES (".$package.",".$grade.",".$ben.",".$bamount.",".$per.",".$cycl.",'".$make_date."',".$hrid.")";
                            if ($conn->query($itqry) == TRUE) {   } else{$err++;}
                        }
                    }
            }
            
            $qry= "INSERT INTO `pakageSetup`( `Title`,`pakage`,`scale`,`makedt`,`makeby`) Values('".$title."',".$package.",".$grade.",'".$make_date."',".$hrid." )";
            //echo $qry;die;
            $err="Pakage setup added successfully";
        
            if ($conn->query($qry) == TRUE) {  $err = "Pakage setup added successfully";  }
            else{ $errflag++; }
            
            $msg = "Package Setup add successfully";
               
    }
    if ( isset( $_POST['update'] ) ) {
        $id = $_REQUEST["serid"];
        
        $title = $_POST["title"];
        $grade = $_POST["grade"];
        $package = $_POST["package"];
        
        $detid = $_POST["detid"];
        $benefit = $_POST["ben"];
        $benamount = $_POST["bamount"];
        $percent = $_POST["per"];
        $cycle = $_POST["cycle"];
        
        
        
        $hrid= $_SESSION["user"];
        $make_date=date('Y-m-d H:i:s');
        $item = $_POST['ben'];
       
         if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $ben = $benefit[$i];$bamount = $benamount[$i]; $per = $percent[$i]; if($per == '') {$per = 0;}$cycl = $cycle[$i];$cdetid=$detid[$i];
                        //echo $percent[$i];die;
                        if($bamount > 0){
                         if($cdetid==0)
                            {
                                $detqry="INSERT INTO `pakageSetupdetails`( `pakage`, `scale`, `benifittp`, `befitamount`, `isPercentage`, `cycle`, `makedt`, `makeby`) 
                                        VALUES (".$package.",".$grade.",".$ben.",".$bamount.",".$per.",".$cycl.",'".$make_date."',".$hrid.")";
                                        //echo $detqry;die;
                            }
                            else
                            {
                                $detqry="update pakageSetupdetails set pakage=".$package.",scale=".$grade.",benifittp=".$ben.",befitamount=".$bamount."
                                ,isPercentage=".$per.",cycle='".$cycl."'  where id=".$cdetid;   
                            
                                //echo $detqry;die;
                            }
                            if ($conn->query($detqry) == TRUE) {}
                            else{ $errflag++;}
                        }
                    }
            }
        
        $qry = "UPDATE `pakageSetup` SET `Title`='".$title."',`pakage`=".$package.",`scale`=".$grade." WHERE id = ".$id;
        //echo $qry;die;
        
        if ($conn->query($qry) == TRUE) { $msg = "Package Setup updated successfully";  }
        else{
            $err++;
        }
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    
    if($err==0)
    {
            header("Location: ".$hostpath."/packageSetupList.php?res=1&msg=".$msg."&id=".$poid."&mod=4&pg=1");
    }
     else
    {
        $msg = "Something went wrong";
        header("Location: ".$hostpath."/packageSetupList.php?res=2&msg=".$msg."&id=''&mod=4");
       
    }
    
    $conn->close();
}
?>