<?php
session_start();
require "../common/conn.php";
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');
include_once('../rak_framework/connection.php');
require_once('../common/insert_gl.php');

include_once('../common/email_config.php');
include_once('../email_messages/email_user_message.php');
require_once('../common/phpmailer/PHPMailerAutoload.php');



                    
if(!$_SESSION["user"])
{
	header("Location: ".$hostpath."/hr.php"); 
}
else
{
        
    $hrid = $_SESSION['user'];
    
    $amt= 0;
    $inv_amt=  0;
    $dueamt=  0;
    $wltamt= 0;
    $inv_id= 0;
    $rem= 0;
    $mode= 0;
    $cmbdrcr='';
    	    
    $cmbmode = '' ; 
    $ref = '';   
    $chqdt = ''; 
    $cmbsupnm = '';
    $descr = '';        
    $curr = 0;        
    $glac =0;
    $orgid=0;
      //echo $cmbsupnm;die;
    $chqclearst=0;$st=0; //$hrid= '1';
    // print_r($data); die; 
    
    
    foreach($_POST["ajxdata"] as $key => $val)
    {
	    $data[$val['name']] = $val['value'];
    }
    
    
    //$response = array("msg" => print_r($data));die;
    
    //accounting 
          ///*
            $cashgl = fetchByID('glmapping','buisness',3,'mappedgl');	
            //$bankgl = fetchByID('glmapping','buisness',4,'mappedgl');
            $customergl = fetchByID('glmapping','buisness',6,'mappedgl');
            $servicegl = fetchByID('glmapping','buisness',15,'mappedgl');
         // */
    
    
    	
    	$whichTab =  $_POST["ajxdata"][10]["value"]; //paytab: 0=From Wallet, 1=Cash Receive
    	
    	if($whichTab==0)
    	{
    	    
    	    //$amt= $_POST["ajxdata"][7]["value"]; //$data['paidmnt2']
    		$amt= $data['paidmnt2'];
    		
    	    //$inv_amt= $_POST["ajxdata"][6]["value"]; // $data['payable2']
    		$inv_amt= $data['payable2'];
    		
    	    //$dueamt= $_POST["ajxdata"][8]["value"]; //$data['duemnt2'];
    		$dueamt= $data['duemnt2'];
    			
    	   // $wltamt=$_POST["ajxdata"][5]["value"]; //$data['walletmnt'];
    		$wltamt=$data['walletmnt'];
    			
    	    //$inv_id=$_POST["ajxdata"][12]["value"]; //$data['invoiceno'];
    		$inv_id=$data['invoiceno'];
    		
    	    //$rem=$_POST["ajxdata"][9]["value"]; // $data['note2'];
    		$rem= $data['note2'];
    			
    	    $cmbdrcr='W';
    	     
    	     $orgidqry="SELECT si.customer, si.type FROM `service_invoice` si  WHERE si.invoice='$inv_id'";
                    $orresult = $conn->query($orgidqry); 
                    if ($orresult->num_rows > 0)
                    { 
                        while($orgrow = $orresult->fetch_assoc()) 
                        { 
                           $orgid= $orgrow['customer'];
                           $servtype = $orgrow["type"];
                        }
                        
                    }
                    $cmbsupnm=$orgid;
    	    
    	}
    	else
    	{
    	    //$amt= $_POST["ajxdata"][1]["value"]; //$data[paidmnt]
    		$amt = $data['paidmnt'];
    		
    	    //$inv_amt= $_POST["ajxdata"][0]["value"];  //$data[payable]
    		$inv_amt = $data['payable'];
    		
    	    //$dueamt= $_POST["ajxdata"][2]["value"]; //$data[duemnt]
    		$dueamt = $data['duemnt'];
    		
    	    //$wltamt=$_POST["ajxdata"][5]["value"]; //[walletmnt] 
    		$wltamt =	$data['walletmnt'];
    		
    	    //$inv_id=$_POST["ajxdata"][12]["value"]; //[invoiceno] 
    		$inv_id =	$data['invoiceno'];
    		
    	    //$rem=$_POST["ajxdata"][4]["value"];//$data[note]
    		$rem = $data['note'];
    		
    	    //$mode=$_POST["ajxdata"][3]["value"]; //$data[paywith]
    		$mode= $data['paywith'];
    		
    	    $cmbdrcr='C';
    	    
    	    $wltamt=$wltamt+$amt;
    	  
          $cmbmode = $_POST["ajxdata"][3]["value"];   
          if($mode="Cash"){$mode=1;}  else{$mode=2;}// elseif($mode="Check"){$mode=2;}elseif($mode="Check"){$mode=2;}
          
          $ref = $inv_id; 
          
          
          //$cmbsupnm = $_POST['org_id'];
          //$amt = $_POST['amt'];             if($amt==''){$amt='0';}
          
         // echo 'hello';die;
         
          $descr = "Fund Receved from customer for paid against Invoice";
          $curr = 1; 
          $glac = $cashgl;
          
          $chqclearst=0;$st=0; //$hrid= '1';
          
           $orgidqry="SELECT si.customer, si.type FROM `service_invoice` si  WHERE si.invoice='$inv_id'";
                    $orresult = $conn->query($orgidqry); 
                    if ($orresult->num_rows > 0)
                    { 
                        while($orgrow = $orresult->fetch_assoc()) 
                        { 
                           $orgid= $orgrow['customer'];
                           $servtype = $orgrow["type"];
                        }
                        
                    }
        $cmbsupnm=$orgid;
          /* -----collection Block -----*/
          
          
          
          $qrycoll="insert into collection( `treat_from`, `trdt`,`transmode`, `transref`, `chequedt`, `customerOrg`, `naration`, `amount`, `chqclearst`, `st`,currencycode, `makeby`, `makedt`, `glac`) 
            values('1' , '".date("Y-m-d H:i:s")."','".$cmbmode."','".$ref."','".date("Y-m-d H:i:s")."' ,'".$cmbsupnm."','".$descr."',".$amt.",".$chqclearst.",".$st.",'".$curr."','".$hrid."','".date("Y-m-d H:i:s")."' ,'".$glac."')" ;
            $err="A receive created successfully";
             
            $orgbalqry="update organization set balance=balance+".$amt." where id=".$cmbsupnm;
            if ($conn->query($orgbalqry) == TRUE) { $err="organization balance updared successfully";  }
            
            
            $curbal = fetchByID('organization','id',$cmbsupnm,'balance');
              
            $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
            values('".date("Y-m-d H:i:s")."','$cmbsupnm','$cmbmode','C','$ref',$amt,$curbal,'Fund Receive',$hrid,'".date("Y-m-d H:i:s")."')";
                //echo $itqry;die;
                
            if ($conn->query($orgwallet) == TRUE) { $err="organization Wallet updated successfully";  }
             
            $note="Customer Payment: Payment Amount ".$amt." received ";
            $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
            values('".$cmbsupnm."',6,'".date("Y-m-d H:i:s")."','".$note."','',0,".$amt.",'".$hrid."','".date("Y-m-d H:i:s")."')" ;
            if ($conn->query($qry_othr) == TRUE) { $err="Another record created successfully";  } 
            
            if ($conn->query($qrycoll) == TRUE) { $err=$err."Collection OK";  }else{ $err=$err."GL failed";}
            
            /*--------collection Block ----*/
           
    	}
    	
    	/*--------wallet block----*/
    	
    	    if($amt>$dueamt)
            {
                $amt=$dueamt;
            }
            
            $qry="insert into invoicepayment(  `invoicid`, `transdt`, `transmode`, `amount`, `remarks`, `makeby`, `makedate`) 
                    values('".$inv_id."','".date("Y-m-d H:i:s")."','".$cmbdrcr."',".$amt.",'".$rem."',".$hrid.",'".date("Y-m-d H:i:s")."')" ;
                    
            if ($conn->query($qry) == TRUE) 
            {
				//echo $response[0];
            } 
            else 
            {
                 $err=$err."Payment Failed";
                 $response = array("msg" =>$err,);
    	            echo json_encode($response);
            }        
            
            if($wltamt<$amt)
            {
               $err="Error: Wallet Balance is insufficient to pay ";
                 $response = array("msg" =>$err,);
            }
            else
            {
                
                //$err="A receive created successfully";
               //echo $qry;die;
                
    			if($amt==$dueamt){
    			    $payst='4';
    			} else if($amt<$dueamt){$payst=5;} else if($amt>$inv_amount){$payst=3;} else {$payst=1;}  
    			
                    $invsqry="UPDATE `service_invoice` set `paidamt`=paidamt+".$amt." ,`dueamt`=dueamt-".$amt.",`paymnetst`=".$payst." where `invoice`='$inv_id'";
                   // echo $itqry;die;
                    if ($conn->query($invsqry) == TRUE) { $err=$err."Invoice Ok,";  } else{$err=$err."Invoice update failed,";}
                 
                        $curbal=0;
                        $updorgqry="UPDATE `organization` set `balance`=balance-".$amt." where `id`=".$orgid;
                        if ($conn->query($updorgqry) == TRUE) { $err=$err."Balance Ok,"; }else{$err=$err."Balance Update Failed,";}
                     
                        $orgupdbalqry="select balance from organization where id=".$orgid;
                        $resultbl = $conn->query($orgupdbalqry);
                        if ($resultbl->num_rows > 0) {while ($rowbal = $resultbl->fetch_assoc()) { $curbal = $rowbal["balance"];}}
                     
                        $orgwallet="insert into organizationwallet(`transdt`,`orgid`,`transmode`,`dr_cr`,`trans_ref`,`amount`,balance,`remarks`,`makeby`,`makedt`)
                            values('".date("Y-m-d H:i:s")."',$orgid,'Auto','D','$inv_id',$amt,$curbal,'Payed against invoice',$hrid,'".date("Y-m-d H:i:s")."')";
                      //echo $orgwallet;die;
                        if ($conn->query($orgwallet) == TRUE) { $err=$err."Wallet Ok,";  }else{$err=$err."wallet update failed,";}
                     
                        //echo $itqry;die;
                
                
                    $note="Customer bill adjustment against purchase: Paid Amount ".$amt." received ";
                    $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
                        values('".$orgid."',6,'".date("Y-m-d H:i:s")."','".$note."','',0,".$amt.",'".$hrid."','".date("Y-m-d H:i:s")."')" ;
                    if ($conn->query($qry_othr) == TRUE) { $err=$err."CRM OK";  }else{$err=$err."History Failed";} 
                 
                 
                 
                    /* Accounnting */
                    
                            
             
            /* */               
            $descr="Voucher againts purchase -".$inv_id; 
              $refno=$inv_id;
             $vouchdt= date("d/m/Y");
               
             $glmstArr = array(
            	'transdt' => $vouchdt,
            	'refno' => $refno,
            	'remarks' => $descr,
            	'entryby' => $hrid,
            );
            	
            
            
            //$tlandingcost=0;
            if($whichTab==1)
            	{
            	$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$cashgl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Cash collection for payment against service invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Cash collection for payment against service invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            	
              	$gldetailArr[] = array(
            		'sl'	 =>	3,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Customer Paid Against service Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	4,
                    'glac'	 =>	$servicegl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Customer Paid Against service Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );           	    
            	}
            	else
            	{
            		$gldetailArr[] = array(
            		'sl'	 =>	1,
                    'glac'	 =>	$customergl,	//glno
            		'dr_cr' 	=>	'D',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Customer Paid Against service Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            
            
            	$gldetailArr[] = array(
            		'sl'	 =>	2,
                    'glac'	 =>	$servicegl,	//glno
            		'dr_cr' 	=>	'C',
            		'amount' 	=>	$amt,
            		'remarks' 	=>	'Customer Paid Against service Invoice',
            		'entryby' 	=>	$hrid,
            		'entrydate' 	=>	$vouchdt
            );
            	}
            		insertGl($glmstArr,$gldetailArr);
                    
                    //$response = array("msg" => print_r($gldetailArr),"invno"=>$inv_id);
                        //  echo json_encode($response); die;    
        
                       
                     
                   }
                   
                   if($servtype == 1){
                       $emid = 20;
                       $cusid = 21;
                   }else{
                       $emid = 22;
                       $cusid = 23;
                   }
           
                    //Mail to Management
                    $qrymail = "SELECT id,active FROM `email` WHERE id = ".$emid;
                      $resultmail = $conn->query($qrymail);
                      while($rowmail = $resultmail->fetch_assoc()){
                          $active = $rowmail["active"];
                          $emailid = $rowmail["id"];
                        if($active == 1){
                              $recipientNames = array();
                              $recipientEmails = array();
                              $ccEmails = array();
                              $qrySendTo = "SELECT emp.office_email, etc.type, concat(emp.firstname, ' ', emp.lastname) empname 
                                            FROM `email_to_cc` etc LEFT JOIN employee emp ON emp.id=etc.employee WHERE emailid = ".$emailid;
                              $resultSendTo = $conn->query($qrySendTo);
                              while($rowst = $resultSendTo->fetch_assoc()){
                                  $recipientNames[] = $rowst["empname"];
                                  if($rowst["type"] == 1){
                                      $recipientEmails[] = $rowst["office_email"];
                                  }else if($rowst["type"] == 2){
                                      $ccEmails[] = $rowst["office_email"];
                                  }
                              }
    
                            if (!empty($recipientEmails)){
                                $mailsubject = "Fund Received For #INVOICE: $inv_id";
        
                                $message = "Dear ".$_SESSION["comname"].",<br>
                                        We recieved a payment for invoice $inv_id <br><br>
                                        
                                        Invoice: $inv_id<br>
                                        Payment amount: ".number_format($amt, 2, '.', '').". <br>
                                        Due: $dueamt-$amt. <br><br>
                                        
                                        <br>Thanks,<br>
                                        ". $_SESSION["comname"]."<br>
                                        ";
                                            
                                sendBitFlowMailArray($recipientNames, $recipientEmails, $mailsubject, $message, $ccEmails);
                            }
                        }
                      }
                      
                      //Mail to Customer
                    $qrymail = "SELECT active FROM `email` WHERE id = ".$cusid;
                      $resultmail = $conn->query($qrymail);
                      while($rowmail = $resultmail->fetch_assoc()){
                          $active = $rowmail["active"];
                          
                        if($active == 1){
                              $qrymail = "SELECT `name`,`email` FROM `organization` WHERE id = ".$orgid;
                              $resultmail = $conn->query($qrymail);
                              //echo $qrymail;die;
                              while($rowmail = $resultmail->fetch_assoc()){
                                  $name_to = $rowmail["name"];
                                  $email_to = $rowmail["email"];
                                  $hrname = $rowmail["hrName"];
                              }
                              
                            $mailsubject = "Fund Received For #INVOICE: $inv_id";
    
                            $message = "Dear $name_to,<br>
                                    We recieved a payment for invoice $inv_id <br><br>
                                    
                                    Invoice: $inv_id<br>
                                    Payment amount: ".number_format($amt, 2, '.', '').". <br>
                                    Due: $dueamt-$amt. <br><br>
                                    
                                    <br>Thanks,<br>
                                    ". $_SESSION["comname"]."<br>
                                    ";
                                    
                        	
                        	sendBitFlowMail($name_to,$email_to, $mailsubject,$message);
                        }
                      }
                    
        	
        	//sendBitFlowMail($name_to,$email_to, $mailsubject,$message);
    	
    	/*--------wallet block----*/
    	
    	
    	$response = array("msg" =>"Amount of ".number_format($amt, 2, '.', '')." against  invoice of '".$inv_id."'  has been paid","invno"=>$inv_id);
        echo json_encode($response);
}
?>