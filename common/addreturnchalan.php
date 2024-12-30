<?php
require "conn.php";

//echo "ok";die;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/challanList.php?res=01&msg='New Entry'&id=''&mod=3");
}
else
{
    if ( isset( $_POST['add'] ) )
    {
     
        $poid=$_POST['pid'];
         $po_dt= $_REQUEST['po_dt'];
       // $make_yr=date('Y');
        $getpo="select lpad((cast(max(substr(`chalanno`,16,4)) AS UNSIGNED)+1),4,'0') maxpo from returnpo where substr(chalanno,4,6)=date_format(sysdate(),'%m%Y')";
        $respo = $conn->query($getpo);
        while($rowpo = $respo->fetch_assoc())
        {
        $po=$rowpo["maxpo"];
        }
       if ($po==''){$po='0001';}
     
      $tot_amt=0;
      $bc = $_POST['bar'];
      $chalan = $_POST['xchalan'];
      $quantity = $_POST['quantity'];
      $cp = $_POST['xcp'];
      $unittotal = $_POST['unittotal'];
      $dscr = $_POST['description']; 
     
      
      $challanno='R'.date(dmYHis).$po;
      $hrid = $_POST['usrid'];
      //echo $chalan[0];die;
    
        if (is_array($bc))
        {
            for ($i=0;$i<count($bc);$i++)
                {
                    $itmsl=$i+1;$bcd=$bc[$i];$xchalan=$chalan[$i];
                    $rqty=$quantity[$i];$xcp=$cp[$i];
                    $rem=$dscr[$i]; //if($rem==''){$rem='null';}  
                    $amt=$rqty*$xcp;
                    $tot_amt=$tot_amt+$amt;
                    
                    $getprod="select `product`,`freeqty`,`storerome`,`expirydt` from chalanstock where barcode='".$bcd."'";
                    $respoitemqry = $conn->query($getprod);
                    while($rowchalan = $respoitemqry->fetch_assoc())
                    {
                        $itmmnm=$rowchalan["product"]; $freeqty=$rowchalan["freeqty"];$storerm=$rowchalan["storerome"];$exprdt=$rowchalan["expirydt"];
                    } 
                     
                     if($rqty>$freeqty){$rqty=$freeqty;}
                     
                     $squpdchlnstock = "update chalanstock set freeqty=freeqty-".$rqty." where  barcode='".$bcd."'";
                     if ($conn->query($squpdchlnstock) == TRUE) { $err="";  }
                     
                     $squpdstock = "update stock set freeqty=freeqty-".$rqty." where  product=".$itmmnm;
                     if ($conn->query($squpdstock) == TRUE) { $err="";  }
                     
                    //echo $barcode;die;
                    $itqry="insert into returnpoitem( `chalanno`,orginalchalanno, `barcode`,product, `qty`, `cp`, `unittotal`, `remarks`, `makeby`, `makedt`)
                            values( '".$challanno."','".$xchalan."','".$bcd."',".$itmmnm.",".$rqty.",".$xcp.",".$amt.",'".$rem."',".$hrid.",sysdate())";
                           //echo $itqry;die;
                    if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  } 
                }
        }
           
      // echo "add";die;
     
       // $curr_id= $_REQUEST['cmbcur'];
        $totamt= $tot_amt;
       
       // $att1='na';
        $rqry="insert into returnpo(`chalanno`,`returndt`,`totalamount`,`makeby`,`makedt`)
        values('".$challanno."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),".$totamt.",".$hrid.",sysdate())" ;
        $err="PO created successfully";
        if ($conn->query($rqry) == TRUE) { $err="Item added successfully";  } 
        
        $rqry="update po set status='R' where id=$poid";
        
     //echo $qry; die;
     $retur="chalanreturnList.php";
    }
    if ( isset( $_POST['update'] ) )
    {
    /*
      $adv= $_REQUEST['po_id'];
      $tot_amt=0;
      $item = $_POST['itemName'];
      $sup = $_POST['cmbsupnm'];
      $oqty = $_POST['quantity'];
      $unp = $_POST['unitpriceotc'];
      $mrp = $_POST['unitpricemrc'];
      $dscr = $_POST['description']; 
      $exdt = $_POST['expdt'];
      $store = $_POST['storeName'];
      $challanno= $_POST['challanno'];
      
      $hrid = $_POST['usrid'];
      
      // delete existing poitem and update stock
      $poitemqry="select i.id,i.`poid`,i.`itemid`,i.`qty`,i.`unitprice`,i.`mrp`,i.`barcode`,i.`expirydt`,i.`storerome` from poitem i,po p where i.poid=p.poid and p.poid='".$challanno."'";
        //echo $poitemqry;die;
        $respoitemqry = $conn->query($poitemqry);
        while($rowpoitm = $respoitemqry->fetch_assoc())
        {
            $itmid=$rowpoitm["id"];
            $itmmnm=$rowpoitm["itemid"];
            $qty=$rowpoitm["qty"];
            $up=$rowpoitm["unitprice"];     if($up==''){$up='Null';}
            $mrpc=$rowpoitm["mrp"];         if($mrpc==''){$mrpc='Null';}
            $barcode=$rowpoitm["barcode"];  if($barcode==''){$barcode='Null';}
            $exprdt=$rowpoitm["expirydt"];  if($exprdt==''){$exprdt='Null';}
            $storerm=$rowpoitm["storerome"];if($storerm==''){$storerm='Null';}
            
            $sqlsp = "CALL psp_stock(".$itmmnm.",".$qty.",".$up.",'O',".$mrpc.",'".$barcode."',STR_TO_DATE('".$exprdt."', '%d/%m/%Y'),".$storerm.")";
            if ($conn->query($sqlsp) == TRUE) { $err="";  }
        }
     
        //insert poietmdel from poitem
        $insdelitm="insert into poitemdel(`id`, `poid`, `item_sl`, `itemid`, `description`, `muid`, `qty`, `unitprice`, `mrp`, `amount`, `status`, `barcode`, `expirydt`, `storerome`)
SELECT  `id`, `poid`, `item_sl`, `itemid`, `description`, `muid`, `qty`, `unitprice`, `mrp`, `amount`, `status`, `barcode`, `expirydt`, `storerome` FROM `poitem` WHERE poid='".$challanno."'";
         if ($conn->query($insdelitm) == TRUE) { $err="";  } 
        
        $delitm = "delete from poitem where poid='".$challanno."'";
        if ($conn->query($delitm) == TRUE) { $err="";  } 
      
      // insert new poitem
        if (is_array($item))
        {
             //echo count($item);
            for ($i=0;$i<count($item);$i++)
                {
                    $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];if($descr==''){$descr='null';}
                    $exprdt=$exdt[$i];if($exprdt==''){$exprdt='null';}$storerm=$store[$i];if($storerm==''){$storerm='1';}
                    $mrpc=$mrp[$i]; $qty=$oqty[$i];$up=$unp[$i]; $amt=$qty*$up;
                    $tot_amt=$tot_amt+$amt;
                    $supcode=str_pad($sup,4,"0",STR_PAD_LEFT);
                    
                    $qrybc1="SELECT lpad(p.id,8,0) prd,c.almasode itmtp FROM product p
left join catagorygrouping cg on p.catagory=cg.itemtype
left join  catagory c on cg.itemcatagory=c.id
where p.id=".$itmmnm;
 //if($i==1){echo $qrybc1;die;}
                    $resbc1 = $conn->query($qrybc1);
                    while($row1bc = $resbc1->fetch_assoc())
                    {
                        $itm=$row1bc["prd"];
                        $itmtp=$row1bc["itmtp"];
                    }
                    
                   
                    $barcode=$supcode.$itmtp.$itm;
                    //echo $barcode;die;
                    $itqry="insert into poitem( `poid`, `item_sl`, `itemid`, `qty`, `unitprice`,mrp,description, `amount`, `status`,expirydt,barcode,storerome)
                            values( '".$challanno."',".$itmsl.",".$itmmnm.",".$qty.",".$up.",".$mrpc.",'".$descr."',".$amt.",1,STR_TO_DATE('".$exprdt."', '%d/%m/%Y'),'".$barcode."',".$storerm." )";
                           //echo $itqry;die;
                     if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
                     
                      $sqlsp = "CALL psp_stock(".$itmmnm.",".$qty.",".$up.",'I',".$mrpc.",'".$barcode."',STR_TO_DATE('".$exprdt."', '%d/%m/%Y'),".$storerm.")";
     
                    if ($conn->query($sqlsp) == TRUE) { $err="stock added successfully";  }
                } 
               
        }
           
      
       //update po
        $totamt= $tot_amt;
        $vat= $tot_amt*.15; $tax= $tot_amt*.05; $invoice_amount= $tot_amt+$vat+$tax;
        $qry="update po set tot_amount=".$totamt."  where  poid='".$challanno."'";
        $err="PO created successfully";
        
        $retur="chalaneditList.php";
     //echo $qry; die;
    
      */  
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/".$retur."?pg=1&res=1&msg=".$err."&id=".$poid."&mod=3");
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/".$retur."?res=2&msg=".$err."&id=''&mod=3");
    }
    
    $conn->close();
}
?>