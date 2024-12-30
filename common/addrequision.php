<?php
require "conn.php";
session_start();

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');

$hrid = $_SESSION["user"];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/deal.php?res=01&msg='New Entry'&id=''");
}
else
{
    if ( isset( $_POST['add'] )) 
    {
        
        $maxid="SELECT (max(`id`)+1) cd FROM `requision`";
        $resultmid = $conn->query($maxid); if ($resultmid->num_rows > 0) {while($rowmid = $resultmid->fetch_assoc()) { $reqid= $rowmid["cd"];}} if($reqid == '') $reqid = 1;
        $reqno = "REQ-".$reqid;
        
        $req_date = $_POST['req_date'];
        $req_branch = $_POST['req_branch']; //if($cmbstage==''){$cmbstage='NULL';}
        $req_by = $_POST['req_by'];
       // echo $dealid;die;
        $tot_amt=0;
        $item = $_POST['itemName'];
        $req_quantity = $_POST['req_quantity'];
        $req_priority = $_POST['priority'];
        $req_note = $_POST['req_note'];
        
       if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$prodnm=$item[$i];$req_qty=$req_quantity[$i];$req_prty=$req_priority[$i];$req_nt=$req_note[$i];
                        $itqry="INSERT INTO `requision_details`( `requision_no`, `product`, `qty`, `note`, `priority`)
                                values( '".$reqno."','".$prodnm."','".$req_qty."','".$req_nt."','".$req_prty."')";
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
         
        $qry="INSERT INTO `requision`(`date`, `requision_no`, `branch`, `requision_by`, `make_by`, `make_dt`) 
        values(STR_TO_DATE('".$req_date."', '%d/%m/%Y'),'".$reqno."','".$req_branch."','".$req_by."','".$hrid."',sysdate())" ;
        $err="Requisition created successfully";
        
              
     //echo $qry;die;   
        
   //echo $totalup; die;
    }
    if ( isset( $_POST['update'] ) ) {
        
        $did= $_REQUEST['itid'];
        $maxid="SELECT requision_no FROM `requision` where id = ".$did;
        $resultmid = $conn->query($maxid); if ($resultmid->num_rows > 0) {while($rowmid = $resultmid->fetch_assoc()) { $req_no= $rowmid["requision_no"];}}
        $req_date = $_POST['req_date'];
        $req_branch = $_POST['req_branch']; //if($cmbstage==''){$cmbstage='NULL';}
        $req_by = $_POST['req_by'];
       // echo $dealid;die;
        $item = $_POST['itemName'];
        $req_quantity = $_POST['req_quantity'];
        $req_priority = $_POST['priority'];
        $req_note = $_POST['req_note'];
        
        //print_r($_POST);die;
         
         $delqry="delete from `requision_details` where `requision_no`='".$req_no."'";
        if ($conn->query($delqry) == TRUE) { $err="dealDetails deleted successfully";  }
        
        
         if (is_array($item))
            {
                for ($i=0;$i<count($item);$i++)
                    {
                        $itmsl=$i+1;$prodnm=$item[$i];$req_qty=$req_quantity[$i];$req_prty=$req_priority[$i];$req_nt=$req_note[$i];
                        $itqry="INSERT INTO `requision_details`( `requision_no`, `product`, `qty`, `note`, `priority`)
                                values( '".$req_no."','".$prodnm."','".$req_qty."','".$req_nt."','".$req_prty."')";
                         //echo $itqry;die;
                         if ($conn->query($itqry) == TRUE) { $err="dealItem added successfully";  }
                         
                    }
            }  
        
        
        
        //$value = $_POST['value'];       if($value==''){$value='NULL';}
        
       
        $qry="UPDATE `requision` SET `date`=STR_TO_DATE('".$req_date."', '%d/%m/%Y'),`branch`= '".$req_branch."',`requision_by`='".$req_by."' WHERE `id`=".$did."";
        $err="Requisition updated successfully";
      
        //echo $qry;die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
        $last_id = $conn->insert_id;
        /*//Mail
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
            
        }*/
            header("Location: ".$hostpath."/requisitionList.php?res=1&msg=".$err."&mod=14&pg=1");
        
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/requisitionList.php?mod=14&res=2&msg=".$err."");
    }
    
    $conn->close();
}
?>