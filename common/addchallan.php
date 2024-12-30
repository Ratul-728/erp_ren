<?php
require "conn.php";

include_once('../rak_framework/fetch.php');
include_once('../rak_framework/edit.php');
//echo "ok";die;

// echo "<pre>"; print_r($_POST); echo "<pre>"; die;

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/challanList.php?res=01&msg='New Entry'&id=''&mod=12");
}
else
{
    if ( isset( $_POST['add'] ) )
    {
       // $make_yr=date('Y');
       /* $getpo="select lpad((cast(max(substr(poid,15,4)) AS UNSIGNED)+1),4,'0') maxpo from po where substr(poid,3,6)=date_format(sysdate(),'%m%Y')";
        $respo = $conn->query($getpo);
        while($rowpo = $respo->fetch_assoc())
        {
        $poid=$rowpo["maxpo"];
        }
        */
       // $poid = getFormatedUniqueID('quotation','id','QT-',6,"0");
     $poid = getFormatedUniqueID('po','id','CH-',6,"0");
     $purchase_id = $_POST['typeId'];
      
      $adv= $_REQUEST['po_id'];
      $tot_amt=0;
      $item = $_POST['itemName'];
      $sup = $_POST['org_id'];
      $oqty = $_POST['quantity_otc'];
      $unp = $_POST['unitprice_otc'];
      $mrp = $_POST['unitpricemrc'];
      $dscr = $_POST['description']; 
      $exdt = $_POST['expdt'];
      $store = $_POST['storeName'];
      
      $challanno=$poid;
      $hrid = $_POST['usrid'];
      $po_dt= $_REQUEST['delivery_dt'];
        
      //echo $po_dt;die;
    
        if (is_array($item))
        {
            for ($i=0;$i<count($item);$i++)
                {
                    $itmsl=$i+1;$itmmnm=$item[$i];$descr=$dscr[$i];//if($descr==''){$descr='null';}
                    $exprdt=$exdt[$i];//if($exprdt==''){$exprdt='null';}
                    $storerm=$store[$i];if($storerm==''){$storerm='1';}
                    $mrpc=$mrp[$i]; $mrpc = str_replace(",", "", $mrpc);
                    $qty=$oqty[$i];$up=$unp[$i];if($up == '') $up = 0; $amt=$qty*$up;
                    $tot_amt=$tot_amt+$amt;
                    
                   /*  $supcode=str_pad($sup,4,"0",STR_PAD_LEFT);
                    
                    $qrybc1="SELECT lpad(p.id,8,0) prd, p.code itmtp FROM item p where p.id=".$itmmnm;
                    $resbc1 = $conn->query($qrybc1);
                    while($row1bc = $resbc1->fetch_assoc())
                    {
                        $itm=$row1bc["prd"];
                        $itmtp=$row1bc["itmtp"];
                    }
                    
                    $qrybc2="SELECT barcode FROM item where id= $itmmnm";
                    $resbc2 = $conn->query($qrybc2);
                    while($row2bc = $resbc2->fetch_assoc())
                    {
                        $barcode=$row2bc["barcode"];
                    }
                   $qrybc2="SELECT barcodewithstore FROM `companyoffice` ";
                    $resbc2 = $conn->query($qrybc2);
                    while($row2bc = $resbc2->fetch_assoc())
                    {
                        $storewise=$row2bc["barcodewithstore"];
                    }
                    
                    
                    if($storewise=='Y'){$barcode=$supcode.$itmtp.$itm.$storerm;}
                    else {$barcode=$supcode.$itmtp.$itm;}
                    */
                    //echo $barcode;die;
                   
                    $bc =fetchByID('item',id,$itmmnm,'barcode');
                    $barcode=str_pad($bc,8,"0",STR_PAD_LEFT);
                    
                    $itqry="insert into poitem( `poid`,`purchase_id`, `item_sl`, `itemid`, `qty`, `unitprice`,mrp,description, `amount`, `status`,barcode,storerome)
                                    values( '$challanno','$purchase_id',$itmsl,$itmmnm,$qty,$up,$mrpc,'$descr',$amt,'A',$barcode,$storerm )";
                         // echo $itqry;die;
                    // if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
                     $isstock=0;
                     $isstock = fetchByID('stock','product',$itmmnm,'id');
                    //echo $isstock;die; 
                     if($isstock==0)
                     {
                        $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
                        $resstore = $conn->query($qrystore);
                        while($rowstore = $resstore->fetch_assoc())
                        {
                            $isstore=$rowstore["cnt"];
                        }
                        if($isstore==0)
                        {
                            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
					      echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
                        else
                        {
                            $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm)";
					        echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
			            $strQryStock ="INSERT INTO stock( `product`, `freeqty`, `bookqty`, `orderedqty`, `deliveredqty`, repairedqty, `costprice`, `prevprice`) VALUES($itmmnm,$qty,0,0,0,0,$up,0)";
			            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
                     }
                     else
                     {
                        $qrystore="SELECT count(*) cnt FROM chalanstock where product= $itmmnm and storerome=$storerm";
                        $resstore = $conn->query($qrystore);
                        while($rowstore = $resstore->fetch_assoc())
                        {
                            $isstore=$rowstore["cnt"];
                        }
                        if($isstore==0)
                        {
                            $strQryChalanstock = "INSERT INTO chalanstock(product,freeqty,costprice,barcode,storerome) VALUES($itmmnm,$qty,$up,$barcode,$storerm)";
					      // echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
                        else
                        {
                            $strQryChalanstock = "update chalanstock set freeqty=freeqty+$qty,costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) where product=$itmmnm and storerome=$storerm)";
					        // echo $strQryChalanstock;die;
					        if ($conn->query($strQryChalanstock) == TRUE) { $err="$qty qtn added in chalanstock in main branch";  }
                        }
                       
                       
                        $strQryStock = "update stock set `freeqty`=freeqty+$qty, costprice=((costprice*freeqty)+($qty*$up))/(freeqty+$qty) , `prevprice`=costprice ";
			            if ($conn->query($strQryStock) == TRUE) { $err="$qty qtn added in stock";  }	
                     }
                    //$sqlsp = "CALL psp_stock('".$itmmnm."','".$qty."','".$up."','I','".$mrpc."','".$barcode."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$storerm."')";
                     //echo $sqlsp;die;
                    //if ($conn->query($sqlsp) == TRUE) { $err="stock added successfully";  }
                }
        }
           
      // echo "add";die;
     
      $pby = $_POST['cmbhr'];
        $sup_id= $_REQUEST['org_id']; $po_dt= $_REQUEST['po_dt'];// $curr_id= $_REQUEST['cmbcur'];
        $totamt= $tot_amt;
        $vat= $tot_amt*.15; $tax= $tot_amt*.05; $invoice_amount= $tot_amt+$vat+$tax; $delivery_dt= $_REQUEST['delivery_dt']; $st='A';
       // $att1='na';
        $qry="insert into po(`adviceno`,`poid`,supid,`orderdt`,`tot_amount`,`invoice_amount`,`vat`,`tax`,`delivery_dt`,`hrid`,`status`,`makedt`)
        values('".$adv."','".$challanno."','".$sup."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$totamt."',0,0,0,STR_TO_DATE('".$delivery_dt."', '%d/%m/%Y'),'".$hrid."','".$st."',sysdate())" ;
        
        //echo $qry; die;
        $err="PO created successfully";
        
        
        
        /* Accounnting */
         $vouch = 10000000000; 
         $getgl="SELECT mappedgl FROM glmapping where id=10 ";// Saleable products from clients
         $resultgl = $conn->query($getgl);
            if ($resultgl->num_rows > 0) {while ($rowgl = $resultgl->fetch_assoc()) { $glno = $rowgl["mappedgl"];}}
            
        $getglinv="SELECT mappedgl FROM glmapping where id=4 ";// Vendor payment 
         $resultglinv = $conn->query($getglinv);
            if ($resultglinv->num_rows > 0) {while ($rowglinv = $resultglinv->fetch_assoc()) { $glnoinv = $rowglinv["mappedgl"];}} 
         
         $glmqry="INSERT INTO `glmst`(`vouchno`, `transdt`, `refno`, `remarks`, `entryby`, `entrydate`) 
                        VALUES ('".$vouch."',STR_TO_DATE('".$po_dt."', '%d/%m/%Y'),'".$challanno."-".$sup."','Purchase Product through challan','".$hrid."',sysdate())";
             
            // echo $glmqry;die;           
        if ($conn->query($glmqry) == TRUE)
        {
            $last = $conn->insert_id;
            $vouch += $last;
            //Updtae Voucher
            $qryup = "UPDATE `glmst` SET `vouchno`=".$vouch." WHERE id = ".$last;
            $conn->query($qryup);  
            
            $glqry1="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',1,'".$glnoinv."','C','".$totamt."','Challan  Made','".$hrid."',sysdate())";
               //echo  $glqry1;die;              
            if ($conn->query($glqry1) == TRUE) { $err="STOCK added successfully";  }else{ $errflag++;}
            
            $glqry2="INSERT INTO `gldlt`(`vouchno`, `sl`, `glac`, `dr_cr`, `amount`,`remarks`, `entryby`, `entrydate` ) 
                                            VALUES ('".$vouch."',2,'".$glno."','D','".$totamt."','Challan Made','".$hrid."',sysdate())";
                             
            if ($conn->query($glqry2) == TRUE) { $err="STOCK added successfully";  }else{ $errflag++;}
        }
        
        
        
     $retur="challanList.php";
    }
    if ( isset( $_POST['update'] ) )
    {
    
      $adv= $_REQUEST['po_id'];
      $tot_amt=0;
      $item = $_POST['itmnm'];
      $sup = $_POST['cmbsupnm'];
      $oqty = $_POST['quantity_otc'];
      $unp = $_POST['unitprice_otc'];
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
            $up=$rowpoitm["unitprice"];     //if($up==''){$up='Null';}
            $mrpc=$rowpoitm["mrp"];         //if($mrpc==''){$mrpc='Null';}
            $barcode=$rowpoitm["barcode"];  //if($barcode==''){$barcode='Null';}
            $exprdt=$rowpoitm["expirydt"];  //if($exprdt==''){$exprdt='Null';}
            $storerm=$rowpoitm["storerome"];//if($storerm==''){$storerm='Null';}
            
            $sqlsp = "CALL psp_stock('".$itmmnm."','".$qty."','".$up."','O','".$mrpc."','".$barcode."',STR_TO_DATE('".$exprdt."', '%d/%m/%Y'),'".$storerm."')";
            //echo $sqlsp;die;
            if ($conn->query($sqlsp) == TRUE) { $err="";  }
        }
     
        //insert poietmdel from poitem
        $insdelitm="insert into poitemdel(`id`, `poid`, `item_sl`, `itemid`, `description`, `muid`, `qty`, `unitprice`, `mrp`, `amount`, `status`, `barcode`, `expirydt`, `storerome`)
SELECT  `id`, `poid`, `item_sl`, `itemid`, `description`, `muid`, `qty`, `unitprice`, `mrp`, `amount`, `status`, `barcode`, `expirydt`, `storerome` FROM `poitem` WHERE poid='".$challanno."'";
         if ($conn->query($insdelitm) == TRUE) { $err="";  } 
        
        $delitm = "delete from poitem where poid='".$challanno."'";
        if ($conn->query($delitm) == TRUE) { $err="";  } 
      
      // insert new poitem
      //echo ($sup); die;
        if (is_array($item))
        {
             //echo count($item);
            for ($i=0;$i<count($item);$i++)
                {
                    $arritmmnm=$item[$i];
                        //item id
                        $qryitminfo = "SELECT `id` FROM `item` WHERE name = '".$arritmmnm."'";
                        $resultitminfo = $conn->query($qryitminfo);
                        while($rowitminfo = $resultitminfo->fetch_assoc()){
                            $itmmnm = $rowitminfo["id"];
                        }
                        //echo $itmmnm;die;
                    $itmsl=$i+1;$descr=$dscr[$i];//if($descr==''){$descr='null';}
                    $exprdt=$exdt[$i];//if($exprdt==''){$exprdt='null';}
                    $storerm=$store[$i];if($storerm==''){$storerm='1';}
                    $mrpc=$mrp[$i]; $mrpc = str_replace(",", "", $mrpc);
                    $qty=$oqty[$i];$up=$unp[$i]; $amt=$qty*$up;
                    $tot_amt=$tot_amt+$amt;
                    $supcode=str_pad($sup,4,"0",STR_PAD_LEFT);
                    
                    $qrybc1="SELECT lpad(p.id,8,0) prd, p.code itmtp FROM item p where p.id=".$itmmnm;
                    //echo $qrybc1;die;
                    $resbc1 = $conn->query($qrybc1);
                    while($row1bc = $resbc1->fetch_assoc())
                    {
                        $itm=$row1bc["prd"];
                        $itmtp=$row1bc["itmtp"];
                    }
                    
                     $qrybc2="SELECT barcode FROM item where id= $itmmnm";
                    $resbc2 = $conn->query($qrybc2);
                    while($row2bc = $resbc2->fetch_assoc())
                    {
                        $barcode=$row2bc["barcode"];
                    }
                    /*$qrybc2="SELECT barcodewithstore FROM `companyoffice` ";
                    $resbc2 = $conn->query($qrybc2);
                    while($row2bc = $resbc2->fetch_assoc())
                    {
                        $storewise=$row2bc["barcodewithstore"];
                    }
                   
                     if($storewise=='Y'){$barcode=$supcode.$itmtp.$itm.$storerm;}
                    else {$barcode=$supcode.$itmtp.$itm;}
                    */
                    //$barcode=$supcode.$itmtp.$itm.$storerm;
                    //echo $barcode;die;
                    $itqry="insert into poitem( `poid`, `item_sl`, `itemid`, `qty`, `unitprice`,mrp,description, `amount`, `status`,expirydt,barcode,storerome)
                            values( '".$challanno."','".$itmsl."','".$itmmnm."','".$qty."','".$up."','".$mrpc."','".$descr."','".$amt."','A',STR_TO_DATE('".$exprdt."', '%d/%m/%Y'),'".$barcode."','".$storerm."' )";
                           //echo $itqry;die;
                     if ($conn->query($itqry) == TRUE) { $err="Item added successfully";  }
                     
                      $sqlsp = "CALL psp_stock('".$itmmnm."','".$qty."','".$up."','I','".$mrpc."','".$barcode."',STR_TO_DATE('".$exprdt."', '%d/%m/%Y'),'".$storerm."')";
     
                    if ($conn->query($sqlsp) == TRUE) { $err="stock added successfully";  }
                } 
               
        }
           
      
       //update po
        $totamt= $tot_amt;
        $vat= $tot_amt*.15; $tax= $tot_amt*.05; $invoice_amount= $tot_amt+$vat+$tax;
        $qry="update po set tot_amount='".$totamt."'  where  poid='".$challanno."'";
        $err="PO created successfully";
        
        $retur="challanList.php";
     //echo $qry; die;
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    
    if ($conn->query($qry) == TRUE) {
                header("Location: ".$hostpath."/".$retur."?pg=1&res=1&msg=".$err."&id=".$poid."&mod=12&changedid=".$challanno);
    } else {
         $err="Error: " . $qry . "<br>" . $conn->error;
          header("Location: ".$hostpath."/".$retur."?res=2&msg=".$err."&id=''&mod=12");
    }
    
    $conn->close();
}
?>