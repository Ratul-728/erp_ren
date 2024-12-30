<?php
require "conn.php";
 $hrid = $_POST['usrid'];
 
if ( isset( $_POST['cancel'] ) ) 
{
      header("Location: ".$hostpath."/soitemList.php?res=01&msg='New Entry'&id=''&mod=2");
}
else
{
    $errflag=0;
    $poid=0;
    $amtbdt=0;
    if ( isset( $_POST['add'] ) ) 
    {
       
        $poid= $_REQUEST['po_id'];
        $tot_amt=0;$tot_otc=0;
        $item = $_POST['itemName'];
        $itemvat = $_POST['vat'];
        $itemait = $_POST['ait'];
        $convrt = $_POST['convrt'];
        $oqty = $_POST['quantity_otc'];
        $unpo = $_POST['unitprice_otc'];
        $dscr = $_POST['remarks'];
        $curr = $_POST['curr'];
        
        $invoicedt=$_REQUEST['invoicedt'];
        $cost=0;$cmbstore=1;
        $invmn= substr($_REQUEST['invoicedt'],3,2);
        $invyr= substr($_REQUEST['invoicedt'],6,4);
        $invdt= $invyr.'-'.$invmn.'-01';
        //echo $invdt;
        $qryinvno="select  max(substring(invoiceno,7,5)) mx FROM invoice where substring(invoiceno,1,4)='".$invyr."' and substring(invoiceno,5,2)='".$invmn."'";
        //echo $qryinvno;die;
        $resinv = $conn->query($qryinvno); 
       // echo $resinv->num_rows;die;
        if ($resinv->num_rows > 0)
        { 
            while($row1 = $resinv->fetch_assoc())
            { 
                $invmx= str_pad(($row1["mx"]+1), 5, "0", STR_PAD_LEFT);  
            }
            $invno=$invyr.$invmn.$invmx;
        }
        else
        {
            $invno=$invyr.$invmn.'00001';
        }
           //echo count($item);die;
           
        for ($i=0;$i<count($item);$i++)
        {
            $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];$mu=$msu[$i];$qty=$oqty[$i];$upo=$unpo[$i];$cur=$curr[$i]; $rate=$convrt[$i];  $vat=$itemvat[$i];$ait=$itemait[$i]; 
            if($descr==''){$descr='NULL';}  if($qty==''){$qty='NULL';} if($upo==''){$upo=0;}if($upm==''){$upm=0;} if($rate==''){$rate=1;}
            $amt=($qty*$upo); $tot_amt=$tot_amt+$amt; $tot_otc=$tot_otc+($qty*$upo); $amtbdt=$amtbdt+($amt*$rate); $totvat=$totvat+($vat*$amt/100);$totait=$totait+($ait*$amt/100);
            if($qty!='NULL')
            {
                $qryinvdet="INSERT INTO `invoicedetails`( `socode`,`invoiceno`, `sosl`, `billtype`, `invoicemoth`, `invoiceyr`, `invoicedt`, `product`,vat,ait, `qty`, `amount`,`currency`,`rate`,`note`, `makeby`, `makedt`) 
                values('".$poid."',".$invno.",".$itmsl.",1,'".$invmn."','".$invyr."',STR_TO_DATE('".$invoicedt."', '%d/%m/%Y'),".$itmmnm.",".$vat.",".$ait.",".$qty.",".$upo.",".$cur.",".$rate.",'".$descr."',".$hrid.",sysdate())";
                 //echo $qryinvdet;die;
                 if ($conn->query($qryinvdet) == TRUE) { $err="invoice added successfully";  } else{$errflag=1;}
            }
        }
        
        // echo "add";die;
        $vat= $totvat; $tax= $totait; $invoice_amount= $tot_amt+$vat+$tax; $org = $_POST['cmborg']; 
        if($tot_otc>0)
        {
            $qryinv="INSERT INTO `invoice`( `invoiceno`, `invoicedt`,`invyr`, `invoicemonth`, `soid`, `organization`, `invoiceamt`,`amount_bdt`, `paidamount`, `dueamount`,  `invoiceSt`, `paymentSt`, `makeby`,`makedt`) 
            values('".$invno."',STR_TO_DATE('".$invoicedt."', '%d/%m/%Y'),'".$invyr."','".$invmn."','".$poid."','".$org."',".$tot_otc.",".$amtbdt.",0,".$amtbdt.",1,1,".$hrid.",sysdate())";
           // echo $qryinv;die;
             if ($conn->query($qryinv) == TRUE) { $err="Invoice created successfully";  }else{$errflag=1;}
        }
            
        $cusqry="update contact set currbal=currbal-".$amtbdt." where id=".$sup_id." and status=1";
        //echo $qry;die;
        if ($conn->query($cusqry) == TRUE) { $err="SO created successfully";  }else{$errflag=1;}
              
        $note="Service Order: SO ID".$poid." with amount ".$amtbdt." was issued by USER";
        $qry_othr="insert into comncdetails(`contactid`,`comntp`, `comndt`, `note`, `place`, `status`, `value`, `makeby`, `makedt`) 
        values(".$sup_id.",8,STR_TO_DATE('".$po_dt."', '%d/%m/%Y %h:%i %p'),'".$note."','',0,".$amtbdt.",".$hrid.",sysdate())" ;
         if ($conn->query($qry_othr) == TRUE) { $err="An other record created successfully";  } else{$errflag=1;}
         
    }
   
   
   
    if($errflag==0) {header("Location: ".$hostpath."/soitemList.php?res=1&msg=".$err."&id=".$poid."&mod=3&pg=1"); }
    else { header("Location: ".$hostpath."/soitemList.php?res=2&msg=".$err."&id=''&mod=3");  }
    
    $conn->close();
}
?>