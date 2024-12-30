<?php
require "conn.php";

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] ) || isset( $_POST['view'] ) ) 
    {
        
     $maxid="SELECT (max(`id`)+1) cd FROM `deal`";
        $resultmid = $conn->query($maxid); if ($resultmid->num_rows > 0) {while($rowmid = $resultmid->fetch_assoc()) { $dealid= $rowmid["cd"];}} 
        
        $nm = $_POST['nm'];
        $cmbstage = $_POST['cmbstage']; //if($cmbstage==''){$cmbstage='NULL';}
        $org = $_POST['org_id'];        //if($org==''){$org='NULL';}
        $cmbld = $_POST['cmbsupnm'];       //if($cmbld==''){$cmbld='NULL';}
        $ddt = $_POST['ddt'];           //if($ddt==''){$ddt='NULL';}
        $cmbhrmgr = $_POST['cmbhrmgr']; //if($cmbhrmgr==''){$cmbhrmgr='NULL';} 
        $cmdt = $_POST['cmdt'];         //if($cmdt==''){$cmdt='NULL';}
        $fldt = $_POST['fldt'];         //if($fldt==''){$fldt='NULL';}
        $cmbstat = $_POST['cmbstat'];   //if($cmbstat==''){$cmbstat='NULL';}
        $cmblost = $_POST['cmblost'];   //if($cmblost==''){$cmblost='NULL';}
        
        $details = $_POST['details'];   //if($details==''){$details='NULL';}
           
       // echo $dealid;die;
        $tot_amt=0;
        $item = $_POST['itemName'];
        $msu = $_POST['measureUnit'];
        $oqty = $_POST['quantity_otc'];
        $oqtym = $_POST['quantity_mrc'];
        $unpo = $_POST['unitprice_otc']; 
        $unpm = $_POST['unitprice_mrc'];
        $scale = $_POST['scale'];  
        $probability = $_POST['probability'];
        $curr_nm = $_POST['curr'];
        $hrid = $_POST['usrid'];
              
       if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$prodnm=$item[$i];$mu=$msu[$i];$qty=$oqty[$i];$qtym=$oqtym[$i];$upo=$unpo[$i]; $upm=$unpm[$i]; $scl=$scale[$i];$prb=$probability[$i]; $currnm=$curr_nm[$i];
                        //if($descr==''){$descr='NULL';} if($mu==''){$mu='NULL';} if($qty==''){$qty='NULL';} if($qtym==''){$qtym='NULL';} if($scl==''){$scl='NULL';}if($prb==''){$prb='NULL';}if($currnm==''){$currnm='1';}
                        if($upo==''){$upo=0;}
                        if($upm==''){$upm=0;}
                        $amt=($qty*$upo)+($qtym*$upm);
                        $tot_amt=$tot_amt+$amt;
                        $otcv=$qty*$upo;
                        $mrcv=$qtym*$upm;
                        
                        $itqry="insert into dealitem( `socode`, `sosl`, `productid`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`,  `scale`, `probability`,`currency`, `makeby`, `makedt`)
                                values( '".$dealid."','".$itmsl."','".$prodnm."','".$mu."','".$qty."','".$qtym."','".$upo."','".$upm."','".$scl."','".$prb."','".$currnm."','".$hrid."',SYSDATE())";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="dealItem added successfully";  }
                         
                          /*$dlrptqry="INSERT INTO `rpt_sales_deal`( `deal_id`, `name`, `contType`, `cus_id`, `cus_nm`, `orderdate`, `yr`, `mnth`, `dy`, `hrid`, `hrName`, `itmid`, `itmnm`, `otc`, `mrc`
                          , `stage`, `st`, `prob`, `itm_cat`, `size`, `pattern`, `orgn`, `scale`, `probability`) 
                          VALUES (".$dealid.",'".$nm."','Customer',".$cmbld.",(select `name` from  `contact` where id=".$cmbld."),STR_TO_DATE('".$cmdt."', '%d/%m/%Y'),DATE_FORMAT(STR_TO_DATE('26/11/2019', '%d/%m/%Y'), '%Y')
                          ,DATE_FORMAT(STR_TO_DATE('26/11/2019', '%d/%m/%Y'), '%m'),DATE_FORMAT(STR_TO_DATE('26/11/2019', '%d/%m/%Y'), '%d'),".$cmbhrmgr.",(select hrName from hr where id=".$cmbhrmgr."),".$prodnm."
                          ,(select name from item where id=".$prodnm."),round(".$otcv.",2),round(".$mrcv.",2)
                          ,(select name from dealtype where id=".$cmbstage."),(select name from dealstatus where id=".$cmbstat."),100
                          ,(select c.name from itmCat c,item i where i.`catagory`=c.`id` and i.id=".$prodnm."),(select size from item where id=".$prodnm.")
                          ,(select c.name from pattern c,item i where i.`pattern`=c.`id` and i.id=".$prodnm."),(select c.name from organization c,contact i where i.`organization`=c.`id` and i.id=".$cmbld.")
                          ,'".$scl."',".$prb.")"; 
                          
                          if ($conn->query($dlrptqry) == TRUE) { $err="dealItem added successfully";  }*/
                    }
            }  
        
        
        
        
        
        //$value = $_POST['value'];       if($value==''){$value='NULL';}
       
        
       // $hrid= '1';
        $make_date=date('Y-m-d H:i:s');
         
        $qry="insert into deal(  `name`, `lead`, `leadcompany`, `value`,  `stage`, `status`, `remarks`, `lostreason`, `makeby`, `makedate`, `dealdate`,`accmgr`, `comercialdate`,`nextfollowupdate`) 
        values('".$nm."','".$cmbld."','".$org."','".$tot_amt."','".$cmbstage."','".$cmbstat."','".$details."','".$cmblost."','".$hrid."','".$make_date."',STR_TO_DATE('".$ddt."', '%d/%m/%Y'),'".$cmbhrmgr."',STR_TO_DATE('".$cmdt."', '%d/%m/%Y'),STR_TO_DATE('".$fldt."', '%d/%m/%Y'))" ;
        $err="Deal created successfully";
        
              
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        
        $did= $_REQUEST['itid'];
        $nm = $_POST['nm'];
        $cmbstage = $_POST['cmbstage']; //if($cmbstage==''){$cmbstage='NULL';}
        $org = $_POST['org_id'];        //if($org==''){$org='NULL';}
        $cmbld = $_POST['cmbsupnm'];       //if($cmbld==''){$cmbld='NULL';}
        $ddt = $_POST['ddt'];           //if($ddt==''){$ddt='NULL';}
        $cmbhrmgr = $_POST['cmbhrmgr']; //if($cmbhrmgr==''){$cmbhrmgr='NULL';} 
        $cmdt = $_POST['cmdt'];         //if($cmdt==''){$cmdt='NULL';}
        $fldt = $_POST['fldt'];         //if($fldt==''){$fldt='NULL';}
        $cmbstat = $_POST['cmbstat'];   //if($cmbstat==''){$cmbstat='NULL';}
        $cmblost = $_POST['cmblost'];   //if($cmblost==''){$cmblost='NULL';}
        
        $details = $_POST['details'];   //if($details==''){$details='NULL';}
        
        
        
        $tot_amt=0;
        $item = $_POST['itemName'];
        $msu = $_POST['measureUnit'];
        $oqty = $_POST['quantity_otc'];
        $oqtym = $_POST['quantity_mrc'];
        $unpo = $_POST['unitprice_otc']; 
        $unpm = $_POST['unitprice_mrc'];
        $scale = $_POST['scale'];  
        $probability = $_POST['probability'];
        $curr_nm = $_POST['curr'];
        $hrid = $_POST['usrid'];
         
         $delqry="delete from dealitem where socode='".$did."'";
        if ($conn->query($delqry) == TRUE) { $err="dealDetails deleted successfully";  }
        
         $delrptqry="delete from rpt_sales_deal where deal_id='".$did."'";
        if ($conn->query($delrptqry) == TRUE) { $err="dealDetails deleted successfully";  }
       
        
         if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$prodnm=$item[$i];$mu=$msu[$i];$qty=$oqty[$i];$qtym=$oqtym[$i];$upo=$unpo[$i]; $upm=$unpm[$i]; $scl=$scale[$i];$prb=$probability[$i];$currnm=$curr_nm[$i];
                        //if($descr==''){$descr='NULL';} if($mu==''){$mu='NULL';} if($qty==''){$qty='NULL';} if($qtym==''){$qtym='NULL';} if($scl==''){$scl='NULL';}if($prb==''){$prb='NULL';} if($currnm==''){$currnm='1';}
                        if($upo==''){$upo=0;}
                        if($upm==''){$upm=0;}
                        $amt=($qty*$upo)+($qtym*$upm);
                        $tot_amt=$tot_amt+$amt;
                       
                        
                        $itqry="insert into dealitem( `socode`, `sosl`, `productid`, `mu`, `qty`, `qtymrc`, `otc`, `mrc`,  `scale`, `probability`,`currency`, `makeby`, `makedt`)
                                values( '".$did."','".$itmsl."','".$prodnm."','".$mu."','".$qty."','".$qtym."','".$upo."','".$upm."','".$scl."','".$prb."','".$currnm."','".$hrid."',SYSDATE())";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="dealItem added successfully";  }
                         
                          /*$dlrptqry="INSERT INTO `rpt_sales_deal`( `deal_id`, `name`, `contType`, `cus_id`, `cus_nm`, `orderdate`, `yr`, `mnth`, `dy`, `hrid`, `hrName`, `itmid`, `itmnm`, `otc`, `mrc`
                          , `stage`, `st`, `prob`, `itm_cat`, `size`, `pattern`, `orgn`, `scale`, `probability`) 
                          VALUES (".$did.",'".$nm."','Customer',".$cmbld.",(select `name` from  `contact` where id=".$cmbld."),STR_TO_DATE('".$cmdt."', '%d/%m/%Y'),DATE_FORMAT(STR_TO_DATE('26/11/2019', '%d/%m/%Y'), '%Y')
                          ,DATE_FORMAT(STR_TO_DATE('26/11/2019', '%d/%m/%Y'), '%m'),DATE_FORMAT(STR_TO_DATE('26/11/2019', '%d/%m/%Y'), '%d'),".$cmbhrmgr.",(select hrName from hr where id=".$cmbhrmgr."),".$prodnm."
                          ,(select name from item where id=".$prodnm."),round(".$otcv.",2),round(".$mrcv.",2)
                          ,(select name from dealtype where id=".$cmbstage."),(select name from dealstatus where id=".$cmbstat."),100
                          ,(select c.name from itmCat c,item i where i.`catagory`=c.`id` and i.id=".$prodnm."),(select size from item where id=".$prodnm.")
                          ,(select c.name from pattern c,item i where i.`pattern`=c.`id` and i.id=".$prodnm."),(select c.name from organization c,contact i where i.`organization`=c.`id` and i.id=".$cmbld.")
                          ,'".$scl."',".$prb.")"; 
                          
                          if ($conn->query($dlrptqry) == TRUE) { $err="dealItem added successfully";  }*/
                    }
            }  
        
        
        
        //$value = $_POST['value'];       if($value==''){$value='NULL';}
        
       
        $qry="update deal set `name`='".$nm."',`lead`='".$cmbld."',`leadcompany`='".$org."', `value`='".$tot_amt."',`stage`='".$cmbstage."',`status`='".$cmbstat."',`remarks`='".$details.
        "',`lostreason`='".$cmblost."',`dealdate`= STR_TO_DATE('".$ddt."', '%d/%m/%Y'),`accmgr`='".$cmbhrmgr."',`comercialdate`=STR_TO_DATE('".$cmdt."', '%d/%m/%Y') ,`nextfollowupdate`=STR_TO_DATE('".$fldt."', '%d/%m/%Y') where `id`=".$did."";
        $err="Deal updated successfully";
      
        //echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
        //Mail
        if ( isset( $_POST['add'] ) ){
            $qrymail = "SELECT concat(emp.firstname, ' ' ,emp.lastname) hrnm, emp.office_email FROM `hraction` hr LEFT JOIN `employee` emp ON hr.`hrid` = emp.id WHERE `postingdepartment` = 23";
            $resultmail = $conn->query($qrymail);
            while($rowmail = $resultmail->fetch_assoc()){
                $name_to = $rowmail["hrnm"];
                $email_to = $rowmail["office_email"];
                
                $mailsubject = "New Lead: $nm";

                $message = "<b>Dear $name_to,</b><br>
                        Good News! New Lead has been created.<br><br>
                        
                        <b>Title: $nm </b>.<br>
                        Description: $details. <br><br>
                        
                        Kindly review it from your profile.<br><br>
                        
                        <b>Thanks,<br>
                        Bitflow System</b><br>
                ";
                
                    //echo $email_to;die;        
                            
                	
                //sendBitFlowMail($name_to,$email_to, $mailsubject,$message);
            }
            
        }
        if(isset( $_POST['view'] )){
            header("Location: ".$hostpath."/deal_view_inv.php?id=".$last_id."&mod=2");
        }else{
            header("Location: ".$hostpath."/dealList.php?res=1&msg=".$err."&mod=2&pg=1");
        }
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/deal.php?res=2&msg=".$err."&id=''");
    }
    
    $conn->close();
}
?>