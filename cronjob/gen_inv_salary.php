<?php


require "../common/conn.php";

include_once('./email_config.php');
include_once('../email_messages/email_user_message.php');

require_once('phpmailer/PHPMailerAutoload.php');


session_start();
$usrid=1;

//Data set
$sp = "psp_invoice_gen";
$yr = date('Y');
$mn = date('m');
$newmnth = date('M');


//Invoice
$cnt=0;
$getqry="select count(*) cnt from invoicedetails where billtype= 2 and invoiceyr='".$yr."' and invoicemoth='".$mn."'";
//echo $getqry;die;
$resultget= $conn->query($getqry);
if ($resultget->num_rows > 0){ while($rowinv = $resultget->fetch_assoc()){ $cnt=$rowinv["cnt"];}}
    if($cnt==0)
    {
        $qry = "CALL ".$sp."(".$yr.",".$mn.",".$usrid.")";
        //echo $qry;die;
        $err="Invoice generated for '".$mn."'-'".$yr."'  succesfully";
    }
    else
    {
       $errflag=1;
       $err="Invoice already generated for '".$mn."'-'".$yr."'  Earlier"; 
    }
 
 
//Salary
$sp = "psp_salary_gen";
$cnt=0;
$proc_mnt="".$yr."-".$mn."-01" ;
            
$delqry="delete from monthlysalary where salaryyear='$yr' and salarymonth='$mn' ";
if ($conn->query($delqry) == TRUE) {$msg0="Salary clear  for month $mn"; }
            
$getqry="select a.hrid,a.compansation,b.pakage ,b.privilagedfund,(c.basic+(b.increment*c.increment)) basic,c.maxgross
from hrcompansation a ,hrcompansationdetails b,compansationSetup c where a.companCode=b.hrcompCode and a.compansation=c.id
and b.effectivedate=(select max(effectivedate) from hrcompansationdetails where hrcompCode=b.hrcompCode and b.pakage=1 and effectivedate  <='$proc_mnt')";
            
$resultget= $conn->query($getqry);
if ($resultget->num_rows > 0)
{ 
    while($rowinv = $resultget->fetch_assoc())
    { 
        $hrid=$rowinv["hrid"];$comp=$rowinv["compansation"];$pakg=$rowinv["pakage"];$privfnd=$rowinv["privilagedfund"];$basic=$rowinv["basic"];$max=$rowinv["maxgross"];
			        
        if($basic>$max){$basic=$max;}
	        $insquery="insert into monthlysalary(salaryyear,salarymonth,hrid,makeby,makedt) values('$yr','$mn',$hrid,$usrid,sysdate())";
	        if ($conn->query($insquery) == TRUE) {$msg1="salary created for "; }
			       //echo $insquery.'</br></br>';
            $getpkg="select p.benifittp,m.mapp,(case p.isPercentage when 1 then $basic*p.befitamount*.01 else p.befitamount end) amt from pakageSetupdetails p
                            left join benifitmapping m on p.benifittp=m.benifittp where p.pakage=$pakg and p.scale=$comp and cycle=1";
                    //echo  $getpkg;       
            $resultgetpkg= $conn->query($getpkg);
			if ($resultgetpkg->num_rows > 0)
		    { 
	            while($rowpkg = $resultgetpkg->fetch_assoc())
		        {
		                $colid=$rowpkg["benifittp"];$col=$rowpkg["mapp"];$amt=$rowpkg["amt"];
		                $updqry="update monthlysalary set $col=$amt where hrid=$hrid and salaryyear='$yr' and salarymonth='$mn'";
		                if ($conn->query($updqry) == TRUE) {$msg2="benifit created "; }
		                    // echo $updqry.'</br>';
		        }
		     }
		            
	}
}
            
$err=$msg1." and ".$msg2;
$errflag=3;

echo $err;

$name_to = "Admin";

$email_to = "mgt@bithut.com.bd";

$mailsubject = "Salary and Invoice Generate Alert";

$message = "Dear $name_to,<br>
            Salary and invoice for the month of $newmnth, $yr has been generated.<br>
                
            <br>Thanks,<br>
            Bitflow System<br>
            ";
                
sendBitFlowMail($name_to,$email_to, $mailsubject,$message);

 //write log when it run;
  
    $file = 'gen_inv_salary.txt';
    $text = "Executed on: " . date("d/m/Y h:i:s A");
    $text = $text.' - '.$error . "\n";
    
    
    file_put_contents($file, $text, FILE_APPEND);

?>