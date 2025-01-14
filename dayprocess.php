<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "./common/conn.php";
include_once('./common/email_config.php');
include_once('./email_messages/email_user_message.php');
require_once('./common/phpmailer/PHPMailerAutoload.php');
/*
$qry="select q.orderdate,qd.productid,qw.warehouse,sum(qd.qty) qty ,'sold' st,sysdate() from quotation q,quotation_detail qd,quotation_warehouse qw
where q.orderdate=date_format(sysdate(),'%Y-%m-%d') and q.orderstatus in(1,3,4,5,6)
and q.socode=qd.socode and qd.id=qw.soitem_detail_id  and qw.qty>0
group by q.orderdate,qd.productid,qw.warehouse
union all 
select date_format(c.`makedt`,'%Y-%m-%d') orderdate,c.productid,cw.warehouse,c.`qty_canceled` qty ,'cancel' st from cancel_order c, quotation_warehouse cw where c.order_id=cw.socode and c.productid=cw.`pid` and  date_format(c.`makedt`,'%Y-%m-%d')=date_format(sysdate(),'%Y-%m-%d')
union all 
select i.iodt,id.product productid,i.issue_warehouse warehouse,id.qty,'issue' st from issue_order i ,issue_order_details id  where i.id=id.ioid and i.iodt =date_format(c.`makedt`,'%Y-%m-%d')
order  by orderdate,productid,warehouse";
$result = $conn->query($qry);
if ($result->num_rows > 0) 
{ 
    while ($row = $result->fetch_assoc()) 
    { 
        $ordt = $row["orderdate"];
        $prod = $row["productid"]; 
        $store = $row["warehouse"];
        $qty = $row["qty"];
        $st = $row["st"];
        $stqty=0;$stcost=0;$stmrp=0;$stexpdt ='';
        $gethist="select h.`freeqty`,h.`costprice`,h.`mrp`,h.`expirydt`,store from stockhist h where h.product=$prod and h.store=$store and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<='$ordt')";
       // echo $gethist;die;
        $resulthist=$conn->query($gethist);
        if ($resulthist->num_rows > 0) 
            {
                while ($rowhist = $resulthist->fetch_assoc()) 
                {
                    $stqty=$rowhist["freeqty"];
                    $stcost=$rowhist["costprice"];
                    $stmrp=$rowhist["mrp"];
                    $stexpdt=$rowhist["expirydt"];
                }
            }
      
        if($st=='cancel')
        {
           $freeqty=$stqty+$qty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; 
        }
        else
        {
            $freeqty=$stqty-$qty; if($freeqty<0){$freeqty=0;} $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt;
        }
        // echo $ordt.'-'.$prod.'-'.$store.'-'.$qty; 
         $histqry="insert into stockhist ( `product`,`freeqty`,`orderedqty`,`grsqcqty`,`costprice`,`mrp`,`expirydt`,`stockdate`,`store`) 
         values($prod,$freeqty,0,0,$cost,$mrp,'$expdt','$ordt',$store)";
       // echo $histqry;
         if ($conn->query($histqry) == TRUE) { $err="organization balance updared successfully";  }
         
    }
    
}
*/
$qryleavereset="update leave_available l,leaveType t  set l.total_days= t.day+floor(l.remaining_days/2)
,l.remaining_days= t.day+floor(l.remaining_days/2) 
where 
l.leave_type=t.id
and l.hrid in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and DATEDIFF(sysdate(),e.opdate)>365
and MONTH(e.opdate)=MONTH(sysdate()) 
and DAY(e.opdate)=DAY(sysdate())
and h.active_st=1
and e.leaveResetType='A' 
)
and l.leave_type=3";
 if ($conn->query($qryleavereset) == TRUE) { $err="Leave reset for Aniversary Basis employee";  }
 
$qryleavereset1="update leave_available l,leaveType t  set l.total_days= t.day+floor(l.remaining_days/2)
,l.remaining_days= t.day+floor(l.remaining_days/2) 
where 
l.leave_type=t.id
and l.hrid in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and MONTH(sysdate())= 7
and DAY(sysdate())=1
and h.active_st=1
and e.leaveResetType='B' 
)
and l.leave_type=3";
 if ($conn->query($qryleavereset1) == TRUE) { $err="Leave reset for old  employee";  } 

$qryleavereset2="update leave_available l,leaveType t set l.remaining_days=t.day where l.leave_type=t.id  and t.id<>3
and l.hrid 
in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and DATEDIFF(sysdate(),e.opdate)>365
and MONTH(e.opdate)=MONTH(sysdate()) 
and DAY(e.opdate)=DAY(sysdate())
and h.active_st=1
and e.leaveResetType='A'
)  ";

if ($conn->query($qryleavereset2) == TRUE) { $err="Leave reset for Anniversary  employee";  } 

$qryleavereset3="update leave_available l,leaveType t set l.remaining_days=t.day where l.leave_type=t.id  and t.id<>3
and l.hrid 
in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and MONTH(sysdate())= 7
and DAY(sysdate())=1
and h.active_st=1
and e.leaveResetType='B'
)  ";

if ($conn->query($qryleavereset3) == TRUE) { $err="Leave reset for Old  employee";  } 



$qry="select p.`gnr_date` orderdate,i.productId productid,p.warehouse,i.qty,'purchase' st 
from purchase_landing p, purchase_landing_item i where p.id=i.pu_id and p.`gnr_date`=sysdate()
union all
select q.orderdate,qd.productid,qw.warehouse,sum(qd.qty) qty ,'sold'  st from quotation q,quotation_detail qd,quotation_warehouse qw
where q.orderdate=sysdate() and q.orderstatus in(1,3,4,5,6)
and q.socode=qd.socode and qd.id=qw.soitem_detail_id  and qw.qty>0
group by q.orderdate,qd.productid,qw.warehouse
union all 
select date_format(c.makedt,'%Y-%m-%d') orderdate,c.productid,cw.warehouse,c.qty_canceled qty ,'cancel' st from cancel_order c, quotation_warehouse cw where c.order_id=cw.socode and c.productid=cw.pid
	and  date_format(c.makedt,'%Y-%m-%d')=sysdate()
union all
SELECT date_format(r.approvedt,'%Y-%m-%d') orderdate,q.product_id productid,qw.warehouse_id warehouse ,rd.return_qty qty,'return' st FROM return_order r ,return_order_details rd,qa_warehouse qw,qa q where r.id=rd.ro_id and rd.qaw_id=qw.id 
and q.id=qw.qa_id and r.st='2' and date_format(r.approvedt,'%Y-%m-%d')=sysdate()"; 
$result = $conn->query($qry);
if ($result->num_rows > 0) 
{ 
    while ($row = $result->fetch_assoc())  
    { 
        $ordt = $row["orderdate"];
        $prod = $row["productid"]; 
        $store = $row["warehouse"];
        $qty = $row["qty"];
        $st = $row["st"];
        $stqty=0;$stcost=0;$stmrp=0;$stexpdt ='';
        $gethist="select h.`freeqty`,h.orderedqty,h.grsqcqty,h.`costprice`,h.`mrp`,h.`expirydt`,h.store from stockhist h where h.product=$prod and h.store=$store and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<='$ordt'  and stockdate>='2024-11-30')";
       // echo $gethist;die;
        $resulthist=$conn->query($gethist);
        if ($resulthist->num_rows > 0) 
            {
                while ($rowhist = $resulthist->fetch_assoc()) 
                {
                    $stqty=$rowhist["freeqty"];
                    $ordqty=$rowhist["orderedqty"];
                    $grsqty=$rowhist["grsqcqty"];
                    $stcost=$rowhist["costprice"];
                    $stmrp=$rowhist["mrp"];
                    $stexpdt=$rowhist["expirydt"];
                }
            }
      
        if($st=='cancel')
        {
           $freeqty=$stqty+$qty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; 
        }
        else if($st=='return')
        {
            $freeqty=$stqty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; $grsqty=$grsqty+$qty;
        }
        else
        {
            $freeqty=$stqty-$qty; if($freeqty<0){$freeqty=0;} $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt;$ordqty=$ordqty+$qty;
        }
        // echo $ordt.'-'.$prod.'-'.$store.'-'.$qty; 
         $histqry="insert into stockhist ( `product`,`freeqty`,`orderedqty`,`grsqcqty`,`costprice`,`mrp`,`expirydt`,`stockdate`,`store`) 
         values($prod,$freeqty,0,$grsqty,$cost,$mrp,'$expdt','$ordt',$store)";
       // echo $histqry;
         if ($conn->query($histqry) == TRUE) { $err="organization balance updated successfully";  }else{ $err="Error when balance updating";  }
         
    }
    
}


//echo "success";

  
    $file = 'dayprocess.txt';
    $text = "Executed on: " . date("d/m/Y h:i:s A");
    $text = $text.' - '.$err . "\n";
    
    
    file_put_contents($file, $text, FILE_APPEND);

?>
require_once('./common/phpmailer/PHPMailerAutoload.php');
/*
$qry="select q.orderdate,qd.productid,qw.warehouse,sum(qd.qty) qty ,'sold' st,sysdate() from quotation q,quotation_detail qd,quotation_warehouse qw
where q.orderdate=date_format(sysdate(),'%Y-%m-%d') and q.orderstatus in(1,3,4,5,6)
and q.socode=qd.socode and qd.id=qw.soitem_detail_id  and qw.qty>0
group by q.orderdate,qd.productid,qw.warehouse
union all 
select date_format(c.`makedt`,'%Y-%m-%d') orderdate,c.productid,cw.warehouse,c.`qty_canceled` qty ,'cancel' st from cancel_order c, quotation_warehouse cw where c.order_id=cw.socode and c.productid=cw.`pid` and  date_format(c.`makedt`,'%Y-%m-%d')=date_format(sysdate(),'%Y-%m-%d')
union all 
select i.iodt,id.product productid,i.issue_warehouse warehouse,id.qty,'issue' st from issue_order i ,issue_order_details id  where i.id=id.ioid and i.iodt =date_format(c.`makedt`,'%Y-%m-%d')
order  by orderdate,productid,warehouse";
$result = $conn->query($qry);
if ($result->num_rows > 0) 
{ 
    while ($row = $result->fetch_assoc()) 
    { 
        $ordt = $row["orderdate"];
        $prod = $row["productid"]; 
        $store = $row["warehouse"];
        $qty = $row["qty"];
        $st = $row["st"];
        $stqty=0;$stcost=0;$stmrp=0;$stexpdt ='';
        $gethist="select h.`freeqty`,h.`costprice`,h.`mrp`,h.`expirydt`,store from stockhist h where h.product=$prod and h.store=$store and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<='$ordt')";
       // echo $gethist;die;
        $resulthist=$conn->query($gethist);
        if ($resulthist->num_rows > 0) 
            {
                while ($rowhist = $resulthist->fetch_assoc()) 
                {
                    $stqty=$rowhist["freeqty"];
                    $stcost=$rowhist["costprice"];
                    $stmrp=$rowhist["mrp"];
                    $stexpdt=$rowhist["expirydt"];
                }
            }
      
        if($st=='cancel')
        {
           $freeqty=$stqty+$qty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; 
        }
        else
        {
            $freeqty=$stqty-$qty; if($freeqty<0){$freeqty=0;} $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt;
        }
        // echo $ordt.'-'.$prod.'-'.$store.'-'.$qty; 
         $histqry="insert into stockhist ( `product`,`freeqty`,`orderedqty`,`grsqcqty`,`costprice`,`mrp`,`expirydt`,`stockdate`,`store`) 
         values($prod,$freeqty,0,0,$cost,$mrp,'$expdt','$ordt',$store)";
       // echo $histqry;
         if ($conn->query($histqry) == TRUE) { $err="organization balance updared successfully";  }
         
    }
    
}
*/
/*
$qryleavereset="update leave_available l,leaveType t  set l.total_days= t.day+floor(l.remaining_days/2)
,l.remaining_days= t.day+floor(l.remaining_days/2) 
where 
l.leave_type=t.id
and l.hrid in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and DATEDIFF(sysdate(),e.opdate)>365
and MONTH(e.opdate)=MONTH(sysdate()) 
and DAY(e.opdate)=DAY(sysdate())
and h.active_st=1
and e.leaveResetType='A' 
)
and l.leave_type=3";
 if ($conn->query($qryleavereset) == TRUE) { $err="Leave reset for Aniversary Basis employee";  }
 
$qryleavereset1="update leave_available l,leaveType t  set l.total_days= t.day+floor(l.remaining_days/2)
,l.remaining_days= t.day+floor(l.remaining_days/2) 
where 
l.leave_type=t.id
and l.hrid in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and MONTH(sysdate())= 7
and DAY(sysdate())=1
and h.active_st=1
and e.leaveResetType='B' 
)
and l.leave_type=3";
 if ($conn->query($qryleavereset1) == TRUE) { $err="Leave reset for old  employee";  } 

$qryleavereset2="update leave_available l,leaveType t set l.remaining_days=t.day where l.leave_type=t.id  and t.id<>3
and l.hrid 
in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and DATEDIFF(sysdate(),e.opdate)>365
and MONTH(e.opdate)=MONTH(sysdate()) 
and DAY(e.opdate)=DAY(sysdate())
and h.active_st=1
and e.leaveResetType='A'
)  ";

if ($conn->query($qryleavereset2) == TRUE) { $err="Leave reset for Anniversary  employee";  } 

$qryleavereset3="update leave_available l,leaveType t set l.remaining_days=t.day where l.leave_type=t.id  and t.id<>3
and l.hrid 
in
(
select h.id
from hr h,employee e 
where h.emp_id=e.employeecode
and MONTH(sysdate())= 7
and DAY(sysdate())=1
and h.active_st=1
and e.leaveResetType='B'
)  ";

if ($conn->query($qryleavereset3) == TRUE) { $err="Leave reset for Old  employee";  } 

*/

$qry="select p.`gnr_date` orderdate,i.productId productid,p.warehouse,i.qty,'purchase' st 
from purchase_landing p, purchase_landing_item i where p.id=i.pu_id and p.`gnr_date`>'2024-11-30'
union all
select q.orderdate,qd.productid,qw.warehouse,sum(qd.qty) qty ,'sold'  st from quotation q,quotation_detail qd,quotation_warehouse qw
where q.orderdate>='2024-11-30' and q.orderstatus in(1,3,4,5,6)
and q.socode=qd.socode and qd.id=qw.soitem_detail_id  and qw.qty>0
group by q.orderdate,qd.productid,qw.warehouse
union all 
select date_format(c.makedt,'%Y-%m-%d') orderdate,c.productid,cw.warehouse,c.qty_canceled qty ,'cancel' st from cancel_order c, quotation_warehouse cw where c.order_id=cw.socode and c.productid=cw.pid
	and  date_format(c.makedt,'%Y-%m-%d')>'2024-11-30'
union all
SELECT date_format(r.approvedt,'%Y-%m-%d') orderdate,q.product_id productid,qw.warehouse_id warehouse ,rd.return_qty qty,'return' st FROM return_order r ,return_order_details rd,qa_warehouse qw,qa q where r.id=rd.ro_id and rd.qaw_id=qw.id 
and q.id=qw.qa_id and r.st='2' and date_format(r.approvedt,'%Y-%m-%d')>'2024-11-30'"; 
$result = $conn->query($qry);
if ($result->num_rows > 0) 
{ 
    while ($row = $result->fetch_assoc())  
    { 
        $ordt = $row["orderdate"];
        $prod = $row["productid"]; 
        $store = $row["warehouse"];
        $qty = $row["qty"];
        $st = $row["st"];
        $stqty=0;$stcost=0;$stmrp=0;$stexpdt ='';
        $gethist="select h.`freeqty`,h.orderedqty,h.grsqcqty,h.`costprice`,h.`mrp`,h.`expirydt`,h.store from stockhist h where h.product=$prod and h.store=$store and h.stockdate=(select max(stockdate) from stockhist where product=h.product and store=h.store and stockdate<='$ordt'  and stockdate>='2024-11-30')";
       // echo $gethist;die;
        $resulthist=$conn->query($gethist);
        if ($resulthist->num_rows > 0) 
            {
                while ($rowhist = $resulthist->fetch_assoc()) 
                {
                    $stqty=$rowhist["freeqty"];
                    $ordqty=$rowhist["orderedqty"];
                    $grsqty=$rowhist["grsqcqty"];
                    $stcost=$rowhist["costprice"];
                    $stmrp=$rowhist["mrp"];
                    $stexpdt=$rowhist["expirydt"];
                }
            }
      
        if($st=='cancel')
        {
           $freeqty=$stqty+$qty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; 
        }
        else if($st=='return')
        {
            $freeqty=$stqty;  $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt; $grsqty=$grsqty+$qty;
        }
        else
        {
            $freeqty=$stqty-$qty; if($freeqty<0){$freeqty=0;} $cost=$stcost;$mrp=$stmrp;$expdt=$stexpdt;$ordqty=$ordqty+$qty;
        }
        // echo $ordt.'-'.$prod.'-'.$store.'-'.$qty; 
         $histqry="insert into stockhist ( `product`,`freeqty`,`orderedqty`,`grsqcqty`,`costprice`,`mrp`,`expirydt`,`stockdate`,`store`) 
         values($prod,$freeqty,0,$grsqty,$cost,$mrp,'$expdt','$ordt',$store)";
       // echo $histqry;
         if ($conn->query($histqry) == TRUE) { $err="organization balance updated successfully";  }else{ $err="Error when balance updating";  }
         
    }
    
}


//echo "success";

  
    $file = 'dayprocess.txt';
    $text = "Executed on: " . date("d/m/Y h:i:s A");
    $text = $text.' - '.$err . "\n";
    
    
    file_put_contents($file, $text, FILE_APPEND);

?>