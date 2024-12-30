<?php
require "common/conn.php";
ini_set('display_errors',0);

session_start();

$doId = $_GET["doid"];
//$doId = "DO-000009";

$qryInfo="SELECT org.name, so.orderdate, org.orgcode, org.contactno, so.remarks, d.order_id FROM delivery_order d LEFT JOIN `qa` q ON d.order_id=q.order_id 
            LEFT JOIN `soitem` so ON q.order_id=so.socode LEFT JOIN `organization` org ON org.id=so.organization 
            WHERE d.do_id = '".$doId."' LIMIT 1;";
$resultInfo = $conn->query($qryInfo);
while ($rowinfo = $resultInfo->fetch_assoc()) {
    $customerName = $rowinfo["name"];
    $customerId = $rowinfo["orgcode"];
    $orderDate = $rowinfo["orderdate"];
    $customerContact = $rowinfo["contactno"];
    $deliveryAddress = $rowinfo["remarks"];
    $orderId = $rowinfo["order_id"];
}

    $qryInfo="SELECT `id`,`type`, `machinary`, `equipment`, `supervisor`, `labor_qty`,DATE_FORMAT(delivery_start, '%d/%m/%Y %h:%i %p') `delivery_start`,DATE_FORMAT(delivery_end, '%d/%m/%Y %h:%i %p') `delivery_end`, `acknowledgement`, `st`
             FROM `resourceplan` WHERE doid = '".$doId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $plan = $rowinfo["type"];
        $machinary = $rowinfo["machinary"];
        $equipment = $rowinfo["equipment"];
        $supervisor = $rowinfo["supervisor"];
        $laborQty = $rowinfo["labor_qty"];
        $deliveryStart = $rowinfo["delivery_start"];
        $deliveryEnd = $rowinfo["delivery_end"];
        $resourceId = $rowinfo["id"];
    }
    
    //Supervisor Name
    $qryInfo="SELECT concat(emp.firstname, ' ', emp.lastname) empnm FROM employee emp WHERE emp.id = '".$supervisor."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $supervisorname = $rowinfo["empnm"];
    }
    
    //Logistic Team
    $logisTeam = "";
    $qryInfo="SELECT lt.name FROM assign_logistic_team alt left join logistic_team lt on lt.id=alt.logisticteamid WHERE alt.resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $logisTeam .= $rowinfo["name"].", ";
    }
    if($logisTeam != "") $logisTeam = rtrim($logisTeam, ", ");
    
    //Technical Team
    $technicalTeam = "";
    $qryInfo="SELECT concat(emp.firstname, ' ', emp.lastname) empnm FROM assign_technical_team atl left join hr h on h.id = atl.empid LEFT JOIN employee emp ON h.emp_id=emp.employeecode WHERE atl.resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $technicalTeam .= $rowinfo["empnm"].", ";
    }
    if($technicalTeam != "") $technicalTeam = rtrim($technicalTeam, ", ");
    
    //QA Team
    $qaTeam = "";
    $qryInfo="SELECT concat(emp.firstname, ' ', emp.lastname) empnm FROM assign_qa_team atl left join hr h on h.id = atl.empid LEFT JOIN employee emp ON h.emp_id=emp.employeecode WHERE atl.resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $qaTeam .= $rowinfo["empnm"].", ";
    }
    if($qaTeam != "") $qaTeam = rtrim($qaTeam, ", ");
    
    //Other Team
    $otherTeam = "";
    $qryInfo="SELECT concat(emp.firstname, ' ', emp.lastname) empnm FROM assign_other_team atl left join hr h on h.id = atl.empid LEFT JOIN employee emp ON h.emp_id=emp.employeecode WHERE atl.resourceid = '".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $otherTeam .= $rowinfo["empnm"].", ";
    }
    if($otherTeam != "") $otherTeam = rtrim($otherTeam, ", ");
    
    //Transportation/Device Needed
    $pickup = 0; $covered_van = 0; $high_ace = 0; $trolley = 0;
    $qryInfo="SELECT qty,trid FROM `assign_transportation` WHERE `resourceid` ='".$resourceId."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        if($rowinfo["trid"] == 1){
            $pickup = $rowinfo["qty"];
        }
        if($rowinfo["trid"] == 2){
            $covered_van = $rowinfo["qty"];
        }
        if($rowinfo["trid"] == 3){
            $high_ace = $rowinfo["qty"];
        }
        if($rowinfo["trid"] == 4){
            $trolley = $rowinfo["qty"];
        }
    }
    
    //Plan
    $qryInfo="SELECT name, id FROM `resource_type` WHERE id = '".$plan."'";
    $resultInfo = $conn->query($qryInfo);
    while ($rowinfo = $resultInfo->fetch_assoc()) {
        $plantype = $rowinfo["name"];
    }
    


?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$invid?></title>
<link rel="icon" href="images/favicon.png">
<link href="css/fonts.css" rel="stylesheet">	
<link rel="stylesheet" href="css/icheck-bootstrap.min.css" />
<style>
<?php
$fontPath = $hostpath."/fonts/";
?>	
@font-face {
    font-family: 'roboto_condensedbold';
    src: url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.eot');
    src: url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.woff') format('woff'),
         url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.ttf') format('truetype'),
         url('<?=$fontPath?>roboto_boldcondensed_macroman/RobotoCondensed-Bold-webfont.svg#roboto_condensedbold') format('svg');
    font-weight: normal;
    font-style: normal;

}
	
@font-face {
    font-family: 'roboto';
    src: url('<?=$fontPath?>roboto-regular/roboto-regular-webfont.woff2') format('woff2'),
         url('<?=$fontPath?>roboto-regular/roboto-regular-webfont.woff') format('woff');
    font-weight: normal;
    font-style: normal;

}	

@font-face {
    font-family: 'robotobold';
    src: url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.eot');
    src: url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.woff') format('woff'),
         url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.ttf') format('truetype'),
         url('<?=$fontPath?>roboto_bold_macroman/Roboto-Bold-webfont.svg#robotobold') format('svg');
    font-weight: normal;
    font-style: normal;

}

	.strong, strong,b{
		font-family: robotobold;
		font-weight: normal;
	}	
.inv-header th{
    text-align: center;
}

.inv-header th:nth-child(2){
    text-align: left;
}
.print-wrapper{
    padding: 5px 0px;
}
body{
    font-family: roboto;
    font-size: 13px;
	padding: 0;
}
h1{
    font-family: roboto_condensedbold;
    font-size: 30px;
    margin: 0;
    border: 0px solid #000;
}
.tbl-header{margin-bottom: 5px;}

.tbl-orderinfo{margin-top: 30px;}

.tbl-orderinfo td{
    width: 33%;
    border: 0px solid #000;
   
}

.tbl-orderinfo h5{
    font-size: 13px;
    margin: 0;
    line-height: 1.2em;
}

.tbl-orderinfo p{
    line-height: 1.2em;
     font-size: 12px;
}

.tbl-header tr td:first-child{
    width: 350px;
}

hr{
    background-color:transparent;
    border: 0;
    border-bottom: 1px solid #6d6d6d;
    margin: 0;
}
.tbl-address{margin:10px 0px; }
.tbl-address td{
    font-size: 11px;
    width: 25%;
    padding:0 10px;
    border-left: 2px solid #ada0a0;
}

.tbl-address td:first-child{
    border-left: 0;
    padding-left: 0;
}


.tbl-items{margin-top: 20px; margin-bottom: 40px;}

.tbl-items th{
    padding: 5px 8px;
    font-size: 11px;
    white-space: nowrap;
}

.tbl-items td{
    padding: 5px;
    font-size: 13px;
}

.tbl-items th.number{width: 80px!important;}

.tbl-items td.number{width: 80px!important; text-align: right;}

.tbl-item-detail td{
    font-family: 13px;
}

.tbl-item-detail td:first-child{
    width: 30%;
}

.tbl-items th{border: 1px solid #7a7a7a;}
.tbl-items td{border: 1px solid #7a7a7a;}

.tbl-items td table td{border: 0}

.no-lb-border{
    border-bottom: 0px!important;
    border-left: 0px!important;
}
.tbl-items th.itemtbl-footer{ text-align: right; white-space: nowrap;}
.tbl-items{background-color: transparent;border-collapse: collapse;}


.terms-wrapper hr{
    margin:0px;    
}

h2{
    font-size: 15px;
    margin: 6px 0;
}

h3{
    font-size: 12px;
    margin: 0px 0;
}

.terms-wrapper ol{
    margin-left: 0;
    padding-left: 0;
    margin-top: 0;

}
.terms-wrapper li{
    font-size: 11px;
    margin-left: 15px;
    
}

h4{font-size: 12px;}	
	
	
@media print {

.print-wrapper{
    padding: 10px 40px;
}
body{
    font-family: roboto;
    font-size: 13px;
}
h1{
    font-family: roboto_condensedbold;
    font-size: 30px;
    margin: 0;
    border: 0px solid #000;
}
.tbl-header{margin-bottom: 5px;}

.tbl-orderinfo{margin-top: 30px;}

.tbl-orderinfo td{
    width: 33%;
    border: 0px solid #000;
   
}

.tbl-orderinfo h5{
    font-size: 13px;
    margin: 0;
    line-height: 1.2em;
}

.tbl-orderinfo p{
    line-height: 1.2em;
     font-size: 12px;
}

.tbl-header tr td:first-child{
    width: 350px;
}

hr{
    background-color:transparent;
    border: 0;
    border-bottom: 1px solid #6d6d6d;
    margin: 0;
}
.tbl-address{margin:10px 0px; }
.tbl-address td{
    font-size: 6pt;
    width: 25%;
    padding:0 10px;
    border-left: 2px solid #ada0a0;
}

.tbl-address td:first-child{
    border-left: 0;
    padding-left: 0;
}


.tbl-items{margin-top: 20px; margin-bottom: 40px;}

.tbl-items th{
    padding: 5px 8px;
    font-size: 13px;
    white-space: nowrap;
}

.tbl-items td{
    padding: 5px;
    font-size: 13px;
}

.tbl-items th.number{width: 80px!important;}

.tbl-items td.number{width: 80px!important; text-align: right;}

.tbl-item-detail td{
    font-family: 13px;
}

.tbl-item-detail td:first-child{
    width: 30%;
}

.tbl-items th{border: 1px solid #7a7a7a;}
.tbl-items td{border: 1px solid #7a7a7a;}

.tbl-items td table td{border: 0}

.no-lb-border{
    border-bottom: 0px!important;
    border-left: 0px!important;
}
.tbl-items th.itemtbl-footer{ text-align: right; white-space: nowrap;}
.tbl-items{background-color: transparent;border-collapse: collapse;}

.tbl-items{ break-after: always;}

.terms-wrapper {
    padding-top:60px;    
}	
.terms-wrapper hr{
    margin:0px;    
}

h2{
    font-size: 10pt;
    margin: 6px 0;
}

h3{
    font-size: 7pt;
    margin: 0px 0;
}

.terms-wrapper ol{
    margin-left: 0;
    padding-left: 0;
    margin-top: 0;

}
.terms-wrapper li{
    font-size: 6pt;
    margin-left: 15px;
    
}

h4{font-size: 8pt;}
	
}
	
	
	
h5,th,h3,h4,h2,strong{
   font-weight: normal;
   font-family: robotobold; 
}

.mgt-approval{
    border: 1px solid #000;
    height: 60px;
    padding: 10px;
    margin-bottom: 20px;
}

h3{
    background-color: #EFEFEF;
    padding: 8px;
    margin-top: 20px!important;
    margin-bottom: 10px!important;
}

.logotable{
    margin-bottom: 0;
}

.product_detail td{
    padding: 10px;
}
	
.product_checklist > tbody > tr > td, 
.tbl-orderinfo td{
   padding: 10px; 
}

kbd.approved
{
  background-color: #3fb06e;
  border: 0 !important;
  box-shadow: none;
}
kbd
{
  font-family: robotoregular;
  padding: 6px 8px!important;
}
kbd
{
  padding: 2px 4px;
  font-size: 90%;
  color: #fff;
  background-color: #333;
  border-radius: 3px;
  -webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
  box-shadow: inset 0 -1px 0 rgba(0,0,0,.25);
}
</style>	
</head>

<body>

<div class="print-wrapper">
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl-header logotable">
  <tbody>
    <tr>
      <td><img src="<?=$hostpath?>/assets/images/site_setting_logo/logo_letterhead.png" width="100%"></td>
	  <td align="right"><h1>RESOURCE PLAN</h1></td>
    </tr>
  </tbody>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="10" class="tbl-orderinfo">
  <tbody>
    <tr>
		<td valign="top">
			<b>Order ID: <?= $orderId ?></b><br>
			<b>Order Date: <?= $orderDate ?></b><br>
			Customer ID: <?= $customerId ?><br>
			Customer Name: <?= $customerName ?><br>
			Customer Contact: <?= $customerContact ?><br>
			Delivery Address: <?= $deliveryAddress ?>
			
		</td>
		
		<td valign="top" align="right">
		    <b>Delivery ID: <?= $doId ?></b><br>
            <b>Supervisor: <?= $supervisorname ?></b><br>
            <b>Transporation plan for <?= $plantype ?></b><br>
            <b>Delivery Start: <?= $deliveryStart ?></b><br>
            <b>Delivery End: <?= $deliveryEnd ?></b><br>
		</td>			
		
    </tr>
  </tbody>
</table>	



<table width="100%" border="1" cellspacing="0" cellpadding="10"  class="product_checklist">
  <tbody>
    <tr class="inv-header">
      <th colspan=4> Team </th>
      <th style="width:50px; text-align:center">LABOR QTY</th>
      <th colspan=4>Transportation/Device Needed</th>
    </tr>
    <tr>
      <td align="center"><b>Logistic Team:</b> <?= $logisTeam ?></td>
      <td align="center"><b>Technical Team:</b> <?= $technicalTeam ?></td>
      <td align="center"><b>QA Team:</b> <?= $qaTeam ?></td>
      <td align="center"><b>Other Team:</b> <?= $otherTeam ?></td>
      
      <td align="center"><?=$laborQty?></td>
      
      <td align="center"><?=$pickup ?> Pickup Van</td>
      <td align="center"><?= $covered_van?> Covered Van</td>
      <td align="center"><?= $high_ace ?> High Ace</td>
      <td align="center"><?= $trolley ?> Trolley</td>
      
    </tr>

</table>	
<br>



<div class="checklist">
    <table border="0" cellpadding="5" cellspacing="2">
        <tr>
            <td style="border-bottom:1px solid #919191;padding-bottom:6px;">
                <b>MACHINARY:</b><br>
                <?= $machinary ?><br>
            </td>
        </tr>
        <tr>
            <td style="border-bottom:1px solid #919191;padding-bottom:6px;">
                <b>SPECIAL EQUIPMENT:</b><br>
                <?= $equipment ?><br>
            </td>
        </tr>
</table>

</div>




</body>
</html>

