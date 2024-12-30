<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$mod= $_GET['mod'];
$inv=$_GET['invid'];
if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{

    $qry="select  inv.invoiceno,inv.invoicedt,inv.invoicemonth,inv.soid,inv.organization,o.name orgnm
,o.street,o.area,area.name arnm,o.district,ds.name dsnm,o.state,st.name stnm,o.zip,o.country,cn.name cnnm,
o.contactno,o.email,o.website,ofc.Name ofcnm,ofc.street ofcst,ofc.area ofcar,ofc.email ofceml,ofc.web ofcweb
from invoice inv
left join organization o on inv.organization=o.id
left join area on o.area=area.id left JOIN district ds on o.district=ds.id
left join state st on o.state=st.id left join country cn on o.country=cn.id
,companyoffice ofc
where inv.invoiceno='".$inv."'";
    	  //echo  $qry;die;
    	$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 { 
							 $invno=$rowinv["invoiceno"]; 
							 $invdt=$rowinv["invoicedt"]; 
							 $invmnth=$rowinv["invoicemonth"];
							 $sof=$rowinv["soid"]; 
							 $orgnm=$rowinv["orgnm"]; 
							 $strt=$rowinv["street"]; 
							 $arnm=$rowinv["arnm"];  
							 $dsnm=$rowinv["dsnm"]; 
							 $stnm=$rowinv["stnm"];
							 $zip=$rowinv["zip"]; 
							 $cnnm=$rowinv["cnnm"];
							 $invcnt=$rowinv["contactno"]; 
							 $inveml=$rowinv["email"];
							 $invweb=$rowinv["website"]; 
							 $ofcnm=$rowinv["ofcnm"]; 
							 $ofcst=$rowinv["ofcst"];
							 $ofcar=$rowinv["ofcar"];  
							 $ofceml=$rowinv["ofceml"];
							 $ofcweb=$rowinv["ofcweb"];  
							 $invmnthnm=date('F', mktime(0, 0, 0, $invmnth,10)); 
            			 }
    				}

$padding2x5 = 'style="padding: 2px 5px;"';

$loop='';
$tot=0;
$dqry="select d.sosl,d.socode,d.product,i.name pnm, i.image pimg ,(case when d.billtype= 1 then 'OTC' else 'MRC' END ) bltp, d.qty ,d.amount,cr.shnm
from invoice s left join invoicedetails d on s.invoiceno=d.invoiceno
left join item i on d.product=i.id
left join currency cr on d.currency=cr.id
where s.invoiceno='".$invno."' order by d.sosl";

$resultd= $conn->query($dqry);
if ($resultd->num_rows > 0){
	 while($rowsd = $resultd->fetch_assoc()){ 
	  
	
	  	$sosl=$rowsd["sosl"]; 
		$pnm=$rowsd["pnm"];
		$pimg=$rowsd["pimg"];
		$crnm=$rowsd["shnm"];
		$tp=$rowsd["bltp"];
	  	$q=$rowsd["qty"]; 
		$amt=$rowsd["amount"];
	  	$totamt=($q*$amt);
		$tot=$tot+$totamt;

$loop.='<tr>
			<td align="center" '.$padding2x5.'> '.$sosl.'</td>
			<td><table border="0" cellpadding="0" cellspacing="0"><tr><td width="10%"><img src="common/upload/item/'.$pimg.'.jpg" width="25"></td><td valig="middle">' .$pnm.'</td></tr></table></td>
			<td '.$padding2x5.'> ' .$crnm.'</td>
			<td '.$padding2x5.'> ' .$tp.'</td>
			<td '.$padding2x5.'  align="right">'.number_format($q,0,".",",").'</td>
			<td '.$padding2x5.'  align="right">  ' .number_format($amt,2,".",",").'</td>
			<td '.$padding2x5.' align="right"> '.number_format($totamt,2,".",",").'</td>
	    </tr>';
 }
$vat=$tot*0.0;
$sc=$tot*0.0;
$amount=$tot+$vat+$sc;
}





require_once("tcpdf_min/tcpdf.php");
$obj_pdf= new TCPDF('P',PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle("Invoice");
$obj_pdf->SetHeaderData('','',PDF_HEADER_TITLE,PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA,'',PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('helvetica');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$obj_pdf->SetMargins(PDF_MARGIN_LEFT,'5',PDF_MARGIN_RIGHT);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_TOP_LEFT-10, PDF_MARGIN_RIGHT);	
$obj_pdf->SetPrintHeader(false);
$obj_pdf->SetPrintFooter(false);
$obj_pdf->SetAutoPageBreak(TRUE,10);
$obj_pdf->SetFont('helvetica','',8);

$content='';
$content.='
<html xmlns="http://www.w3.org/1999/xhtml">

<div id="wrapper">
    <div id="page-content-wrapper">
        <div >
            <div >
                <div >
                
                <p>
          	        <div id="printableArea"  >
		  		        <div >
			            <table width="85%" align="left" border="0"  cellspacing="0" cellpadding="0">
				            <tr>
					            <td width="70%"><img width="100" src="./assets/images/site_setting_logo/'.$_SESSION["comlogo"].'" alt=""></td>
				                <td align="right"><h1>INVOICE</h1></td>
				            </tr>
				            <tr>
					            <td>&nbsp;</td>
					            <td>&nbsp;</td>
				            </tr>				
					        <tr>
					            <td>
						            <div>'.
$ofcnm.' <br>'.
nl2br($ofcst).'<br>'.
$cnnm.'<br>'.
$ofceml.'<br>'.
$ofcweb.'
						            </div>
					            </td>
					            <td>
					                <table cellspacing="0" class="tbl_lbl2" cellpadding="0" align="right">
					                    <tr>
					                        <td>Date</td>
					                        <td>: '.$invdt.'</td>
				                        </tr>
					                    <tr>
					                        <td>Invoice #</td>
					                        <td>: '.$invno.'</td>
			                            </tr>
					                    <tr>
					                        <td>Order Number</td>
					                        <td>: '.$sof.'</td>
				                        </tr>
					                    <tr>
					                        <td>For Month</td>
					                        <td>: '.$invmnthnm.'</td>
				                        </tr>
			                        </table>
						        </td>
			                </tr>
		                </table>
			            

   <br><br><br>

			<table width="100%" border="0"   cellspacing="0" cellpadding="0">
				  <tbody>
				    <tr>
				      <td width="50%"><strong>Bill To:</strong></td>

				      <td width="50%" style="padding-left:20px;"><strong>Delivery Note:</strong> </td>
			        </tr>
				    <tr>
				      <td valign="top">
'. $orgnm. ' <br>
'. $strt. ' <br>
'. $arnm . ' , ' . $dsnm . ', '.$stnm.'-'. $zip.' <br>
'.$cnnm . ' <br>
Phone: '. $invcnt. ' <br>
'. $inveml. ' <br>
'.$invweb. '
				    </td>
				    <td valign="top" style="padding-left:20px;">
    					    '. $deliveryto. '

					  </td>
			        </tr>
			      </tbody>
		    </table>



    		            <br><br><br>
';

$content.='
<table width="100%" border="1"  cellspacing="0" cellpadding="5">
    <tbody>
        <tr>
		    <th width="5%" align="center" nowrap '.$padding2x5.'>Sl</th>
		    <th width="20%" align="center" nowrap '.$padding2x5.'>Products</th>
		    <th width="15%" align="center" nowrap '.$padding2x5.'>Qty</th>
		    <th width="15%" align="center" nowrap '.$padding2x5.'>Bill Type</th>
		    <th width="15%" align="center" style="text-align: center" nowrap '.$padding2x5.'> Qty</th>
		    <th width="15%" align="center" nowrap '.$padding2x5.'>Unit price</th>
		    <th width="15%" nowrap '.$padding2x5.'>Amount</th>
	    </tr>
';
$content.=$loop;
$content.='
                                            <tr>
                                                <td colspan="5" rowspan="5" style="border-left:1px solid #fff; border-bottom:1px solid #fff;">&nbsp;</td>
                                            	<td align="right" '.$padding2x5.'><strong>Total</strong></td>
                                            	<td '.$padding2x5.' align="right">'.number_format($tot,2,".",",").'</td>
                                            </tr>
                                            <tr>
                                                <td align="right" '.$padding2x5.'>VAT (15%)</td>
                                                <td '.$padding2x5.' align="right">'.number_format($vat,2,".",",").'</td>
                                            </tr>
                                            <tr>
                                                <td align="right" '.$padding2x5.'>SC(10%)</td>
                                                <td '.$padding2x5.' align="right">'.number_format($sc,2,".",",").'</td>
                                            </tr>
                                            <tr>
                                                <td  '.$padding2x5.' align="right"><strong>Amount</strong></td>
                                                <td '.$padding2x5.' align="right">'.number_format($amount,2,".",",").'</td>
                                            </tr>
                                		</tbody>
                                    </table>	
                                    <br>
                                    <div style="width: 300px; font-size: 12px;">					
                                    Make all checks payable to <strong>'.$ofcnm.'</strong>.<br>"If you have any questions concerning this invoice, then contact Support at "'.$ofceml.'"."<br>
                                    <br>
                                    </div>	
                                	<strong>Thank you for your business!</strong>
                                	
                                </div>
                            </div>	
                            <br>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</HTML>
';
	//echo $content;die;
	
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
$obj_pdf->OutPut("invoice.pdf","I");
//echo $content;
}
?>
