<?php
require_once("../common/conn.php");
	//echo $_REQUEST['param1'];
	//exit();
//$pram=$_REQUEST['param1'];	
//extract($_REQUEST['param1']);

//$link1="export_dashboard.php?chart=1&".$yearC;
$linearray = split('&', $_REQUEST['param1']);
//print join(":", $linearray);
//exit();
$yr="";$mn="";$am="";$itm="";$comtp="";$licn="";$itmc="";$org="";$st="";$qr="";
foreach ($linearray as $key => $child) 
{
    $pos=strpos($child,'=');
    $str1=substr($child,0,$pos);
    $str2=substr($child,$pos+1);
    if($str1=='yearC'){ $yr.=$str2.",";  }
    else if($str1=='monthC'){$mn.=$str2."','";}
    else if($str1=='accmngr'){$am.=$str2."','";}
    else if($str1=='item'){$itm.=$str2."','"; }
    else if($str1=='comtype'){$comtp.=$str2."','";}
    else if($str1=='licencetype'){$licn.=$str2."','"; }
    else if($str1=='itemcat'){ $itmc.=$str2."','";}
    else if($str1=='org'){$org.=$str2."','";}
    else if($str1=='status'){$st.=$str2."','";}
    else{ }
}
if($yr!=""){ $qr.=" and s.yr in(".$yr."'' )";} else{$qr.="and s.yr>=YEAR(CURDATE()) ";}
if($mn!=""){ $qr.=" and DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') in('".$mn."')";}
if($am!=""){ $qr.=" and s.hrName in('".$am."')";}
if($itm!=""){ $qr.=" and s.itmnm in('".$itm."')";}
if($comtp!=""){ $qr.=" and s.size in('".$comtp."')";}
if($licn!=""){ $qr.=" and s.pattern in('".$licn."')";}
if($itmc!=""){ $qr.=" and s.itm_cat in('".$itmc."')";}
if($org!=""){ $qr.=" and s.orgn in('".$org."')";}
//print($qr);
//exit();

$link1="export_dashboard.php?chart=1&".$_REQUEST['param1'];
$link2="export_dashboard.php?chart=2&".$_REQUEST['param1'];
$link3="export_dashboard.php?chart=3&".$_REQUEST['param1'];
$link4="export_dashboard.php?chart=4&".$_REQUEST['param1'];
$link5="export_dashboard.php?chart=5&".$_REQUEST['param1'];
$link6="export_dashboard.php?chart=6&".$_REQUEST['param1'];
?>


<!-- START chart row-->
<div class="row dashbaord-filter">
    <div class="col-lg-4 col-md-6">
        <div id="panelChart4" class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title"><a href="<?php echo $link1 ?>">Sales Order Timeline</a></div>
            </div>
            <div class="panel-body">
    			<div style="margin: 10px;">
    				<div id="salesOrderTimeline" style="height: 250px; width: 100%; transform: scale(1); "></div>
    			</div>
    			   
            </div>
        </div>
    </div>					  

    <div class="col-lg-4 col-md-6">
        <div id="panelChart4" class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title"><a href="export_dashboard.php?chart=2">Account Manager Performance</a></div>
            </div>
            <div class="panel-body">
    			<div style="margin: 10px;">
    				<div id="accManagerPerformance" style="height: 250px; width: 100%; "></div>
    			</div>
            </div>
        </div>
    </div>
					  
    <div class="col-lg-4 col-md-6">
        <div id="panelChart4" class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title"><a href="export_dashboard.php?chart=3">Categorywise Visual</a></div>
            </div>
            <div class="panel-body">
				<div style="margin: 10px;">
					<div id="categorywiseVisual2" style="height: 250px; width: 100%; "></div>
				</div>
				   
            </div> 
        </div>
     </div>					  
						  
    <div class="col-lg-4 col-md-6">
        <div id="panelChart4" class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title"><a href="export_dashboard.php?chart=4">Franchise Wise Visual</a></div>
            </div>
            <div class="panel-body">
				<div style="margin: 10px;">
					<div id="franchiseWiseVisual" style="height: 250px; width: 100%; "></div>
				</div>
				   
            </div>
        </div>
    </div>
					  
    <div class="col-lg-4 col-md-6">
        <div id="panelChart4" class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title"><a href="export_dashboard.php?chart=5">Existing V SNew Sales</a></div>
            </div>
            <div class="panel-body">
				<div style="margin: 10px;">
					<div id="existingVSNewSales2" style="height: 250px; width: 100%; "></div>
				</div>
				   
            </div>
        </div>
    </div>
                  
    <div class="col-lg-4 col-md-6">
        <div id="panelChart4" class="panel panel-default">
            <div class="panel-heading">
               <div class="panel-title"><a href="export_dashboard.php?chart=6">Product Wise Sales</a></div>
            </div>
            <div class="panel-body">
				<div style="margin: 10px;">
					<div id="productWiseSales" style="height: 250px; width: 100%; "></div>
				</div>
				   
            </div>
        </div>
    </div>
                  
</div>
<!-- END  chart row-->	
<!-- START chart table-->
<!--
<div class="row table-row dashbaord-filter">
					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr class="tbl-footer">
							  <td>Salse</td>
							  
							</tr>
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">Existing</th>
							  <th scope="col">New</th>
							  
							</tr>

<?php $totnew1=0;$totexs1=0;
$qry1="SELECT   s.`yr`,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn
,sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)+s.`otc`) Else 0 end ))nt
,format(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)+s.`otc`) Else 0 end )),2) n
,sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then s.`mrc` Else  0 end ))et
,format(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then s.`mrc` Else  0 end )),2) exs
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr))
and s.yr>=YEAR(CURDATE())
group by s.`yr`, s.`mnth`";

$res1 = $conn->query($qry1); if ($res1->num_rows > 0) {while($rows1 = $res1->fetch_assoc()) 
      { $yr= $rows1["yr"];  $mn= $rows1["mn"];  $new=$rows1["n"]; $exs=$rows1["exs"]; $totnew1=$totnew1+$rows1["nt"];$totexs1=$totexs1+$rows1["et"];
?>  							
							<tr>
							  <td><?php echo $mn.'-'.$yr;?></td>
							  <td><?php echo $exs; ?></td>
							  <td><?php echo $new; ?></td>
							</tr>
<?php }}?>							
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td><?php echo $totexs1; ?></td>
							  <td><?php echo $totnew1; ?></td>
							  
							</tr>
						</table>

					  </div>

					 
					 <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
						    <tr class="tbl-footer">
							  <td>Salse..</td>
							</tr>
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
<?php 
$totm2=0;$toto2=0;
$qry2="SELECT  s.`yr`,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn
,sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ) ot
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end)mt
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.`yr`, s.`mnth`  ";
$res2 = $conn->query($qry2); if ($res2->num_rows > 0) {while($rows2 = $res2->fetch_assoc()) 
      {   $yr2= $rows2["yr"];$mn2= $rows2["mn"];  $m2=$rows2["pmrc"]; $o2=$rows2["otcvalue"]; $totm2=$totm2+$rows2["mt"];$toto2=$toto2+$rows2["ot"];
?> 								
							<tr>
							  <td><?php echo $mn2.'-'.$yr2;?></td>
							  <td><?php echo $m2; ?></td>
							  <td><?php echo $o2; ?></td>
							</tr>
<?php }}?>							
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td><?php echo $totm2; ?></td>
							  <td><?php echo $toto2; ?></td>
							</tr> 
						</table>
					  </div> 

					  <div class="col-lg-2  col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr class="tbl-footer">
							  <td>AM</td>
							</tr>
							<tr>
							  <th scope="col">Manager</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
<?php 
$totm3=0;$toto3=0;
$qry3="SELECT  s.`hrName`
,sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ) ot
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end) mt
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.`hrName`  ";
$res3 = $conn->query($qry3); if ($res3->num_rows > 0) {while($rows3 = $res3->fetch_assoc()) 
      {   $am3= $rows3["hrName"];  $m3=$rows3["pmrc"]; $o3=$rows3["otcvalue"]; $totm3=$totm3+$rows3["mt"];$toto3=$toto3+$rows3["ot"];
?> 							
							<tr>
							  <td><?php echo $am3; ?></td>
							  <td><?php echo $m3; ?></td>
							  <td><?php echo $o3; ?></td>
							</tr>
<?php }}?>							
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td><?php echo $totm3; ?></td>
							  <td><?php echo $toto3; ?></td>
							</tr>
						</table>
					  </div>                

					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr class="tbl-footer">
							  <td>Vs</td>
							</tr>
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
							<tr>
							  <td>Jan 20</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td>12,774,968</td>
							  <td>39,060</td>
							</tr>
						</table>
					  </div> 
					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr class="tbl-footer">
							  <td>Cat..</td>
							</tr>
							
							<tr>
							  <th scope="col">Catagory</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
<?php 
$totm4=0;$toto4=0;
$qry4="SELECT  s.itm_cat
,sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end )ot
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end) mt
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.itm_cat ";
$res4 = $conn->query($qry4); if ($res4->num_rows > 0) {while($rows4 = $res4->fetch_assoc()) 
      {   $cat= $rows4["itm_cat"];  $m4=$rows4["pmrc"]; $o4=$rows4["otcvalue"]; $totm4=$totm4+$rows4["mt"];$toto4=$toto4+$rows4["ot"];
?>							
							<tr>
							  <td><?php echo $cat; ?></td>
							  <td><?php echo $m4; ?></td>
							  <td><?php echo $o4; ?></td>
							</tr>
<?php }}?>							
							
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td><?php echo $totm4; ?></td>
							  <td><?php echo $toto4; ?></td>
							</tr>
						</table>
					  </div>	
					  <div class="col-lg-2 col-md-6">
						<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-bordered">
							<tr class="tbl-footer">
							  <td>Pro..</td>
							</tr>
							<tr>
							  <th scope="col">Month</th>
							  <th scope="col">MRC</th>
							  <th scope="col">OCT</th>
							</tr>
<?php 
$totm5=0;$toto5=0;
$qry5="SELECT  s.itmnm
,sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ) ot
,format(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end) mt
,format(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) and s.yr>=YEAR(CURDATE()) 
group by s.itmnm ";
$res5 = $conn->query($qry5); if ($res5->num_rows > 0) {while($rows5 = $res5->fetch_assoc()) 
      {   $itm= $rows5["itmnm"];  $m5=$rows5["pmrc"]; $o5=$rows5["otcvalue"]; $totm5=$totm5+$rows5["mt"];$toto5=$toto5+$rows5["ot"];
?>							
							
							<tr>
							  <td><?php echo $itm; ?></td>
							  <td><?php echo $m5; ?></td>
							  <td><?php echo $o5; ?></td>
							</tr>
<?php }} ?>							
							<tr class="tbl-footer">
							  <td>Grand Total</td>
							  <td><?php echo $totm5; ?></td>
							  <td><?php echo $toto5; ?></td>
							</tr>
						</table>
					  </div> 	
                  
               </div> -->
<!-- END chart table-->	

<!-- END CANVAS JS CHART-->
	
	
<!-- morrisjs CHART-->
<script src="js/plugins/morrisjs/morris.js"></script>
<script src="js/plugins/morrisjs/raphael-min.js"></script>	
<script src="js/plugins/morrisjs/prettify.min.js"></script>

	
<script language="javascript">
	window.onload = $(function () {	

	
	
Morris.Bar({
  element: 'salesOrderTimeline',
  data: [
<?php $qry11="SELECT   s.`yr`,DATE_FORMAT(STR_TO_DATE(s.mnth, '%m'), '%b') mn
,round(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)+s.`otc`) Else 0 end )),2) n
,round(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end )),2) exs
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ".$qr." group by s.`yr`, s.`mnth`";

$res11 = $conn->query($qry11); if ($res11->num_rows > 0) {while($rows11 = $res11->fetch_assoc()) 
      { $yr11= $rows11["yr"];  $mn11= $rows11["mn"];  $new11=$rows11["n"]; $exs11=$rows11["exs"]; 
?>     
    //{x: <?php echo $mn;?>, y: <?php echo $new;?>, z: <?php echo $exs;?>},
    {x: '<?php echo $mn11.'-'.$yr11;?>', y: <?php echo $new11;?>, z: <?php echo $exs11;?>},
<?php }}?>  
  ],
	xkey: 'x',
	ykeys: ['y', 'z'],
	labels: ['Existing', 'New'],
	horizontal: false,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});
	
	
Morris.Bar({
  element: 'franchiseWiseVisual',
  data: [
<?php $qry21="SELECT  s.`size`
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,round(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ".$qr." group by s.`size` ";

$res21 = $conn->query($qry21); if ($res21->num_rows > 0) {while($rows21 = $res21->fetch_assoc()) 
      {   $fr= $rows21["size"];  $p21=$rows21["pmrc"]; $o21=$rows21["otcvalue"]; 
?> 
    {company: '<?php echo $fr;?>', otc: <?php echo $o21;?>, mrc: <?php echo $p21;?>},
<?php }} ?>    
  ],
	xkey: 'company',
	ykeys: ['otc', 'mrc'],
	labels: ['OTC', 'MRC'],
	horizontal: false,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});

Morris.Bar({
  element: 'categorywiseVisual2',
  data: [
     
<?php $qry31="SELECT  s.`itm_cat`
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,round(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ".$qr." group by s.`itm_cat`  ";
$res31 = $conn->query($qry31); if ($res31->num_rows > 0) {while($rows31 = $res31->fetch_assoc()) 
      {   $ct= $rows31["itm_cat"];  $m31=$rows31["pmrc"]; $o31=$rows31["otcvalue"]; 
?>
    {service: '<?php echo $ct;?>', otc: <?php echo $o31;?>, mrc: <?php echo $m31;?>},
<?php }} ?>    
  ], 
	xkey: 'service',
	ykeys: ['otc', 'mrc'],
	labels: ['OTC', 'MRC'],
	horizontal: true,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});
	
	
	

	

	
/*Morris.Pie({
  element: 'categorywiseVisual2',
  data: [
    {value: 4181563, label: 'Cloud'},
    {value: 2175498, label: 'Telephony'},
    {value: 5, label: 'Blank'},
    {value: 3125844, label: 'Internet/Data'},
  ],
  formatter: function (x) { return x + "%"},
	resize: true,
	showLabel: true,
	
});

*/
	
	
	
	
Morris.Bar({
  element: 'accManagerPerformance',
  data: [
<?php $qry41="SELECT  s.`hrName`
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2)otcvalue
,round(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ".$qr." group by s.`hrName`  ";
$res41 = $conn->query($qry41); if ($res41->num_rows > 0) {while($rows41 = $res41->fetch_assoc()) 
      {   $am= $rows41["hrName"];  $m41=$rows41["pmrc"]; $o41=$rows41["otcvalue"]; 
?>      
    {x: '<?php echo $am;?>', y: <?php echo $o41;?>, z: <?php echo $m41;?>},
<?php }}?>    
 
  ],
  xkey: 'x',
  ykeys: ['y', 'z'],
  labels: ['OTC', 'MRC'],
  horizontal: true,
  stacked: true,
resize: true,	
});	


Morris.Bar({
  element: 'existingVSNewSales2',
  data: [
      <?php $qry51="SELECT  round(sum((case when r.yr=s.yr and r.month=s.mnth then (((s.`mrc`*(r.dy-s.`da`+1))/r.dy)) Else 0 end )),2) nmrc
,round(sum((case when (r.yr=s.yr and r.month>s.mnth) or (r.yr>s.yr) then (s.`mrc`) Else  0 end )),2) emrc
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2) otcvalue
FROM  `rpt_sales_so` s  ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ".$qr." ";
$res51 = $conn->query($qry51); if ($res51->num_rows > 0) {while($rows51 = $res51->fetch_assoc()) 
      {   $nm= $rows51["nmrc"];  $em=$rows51["emrc"]; $no=$rows51["otcvalue"]; $eo=0;
?>      
    {x: 'Existing', otc: <?php echo $eo;?>, mrc: <?php echo $em;?>},
    {x: 'New', otc: <?php echo $no;?>, mrc: <?php echo $nm;?>},
<?php }} ?>    
  ],
	xkey: 'x',
	ykeys: ['otc', 'mrc'],
	labels: ['OTC', 'MRC'],
	horizontal: false,
	stacked: true,
	
	resize: true,
	redraw: true,
	behaveLikeLine: true,
	pointFillColors:['#ffffff'],
	pointStrokeColors: ['black'],
	lineColors:['gray','red']	
});
	

	
//event fire;
	

	

// chart 6
var day_data = [
<?php $qry61="SELECT  s.`itmnm`
,round(sum(case when r.yr=s.yr and r.month=s.mnth then (s.`otc`) Else 0 end ),2) otcvalue
,round(sum(case when r.yr=s.yr and r.month=s.mnth then ((s.`mrc`*(r.dy-s.`da`+1))/r.dy) Else s.`mrc` end),2)  pmrc 
FROM  `rpt_sales_so` s ,`reportmanth` r  
WHERE ((r.yr=s.yr and r.month>=s.mnth) or (r.yr>s.yr)) ".$qr." group by s.`itmnm`   ";
$res61 = $conn->query($qry61); if ($res61->num_rows > 0) {while($rows61 = $res61->fetch_assoc()) 
      {   $itm= $rows61["itmnm"];  $m61=$rows61["pmrc"]; $o61=$rows61["otcvalue"]; 
?>         
 {"period": "<?php echo $itm;?>", "otc": <?php echo $o61;?>, "mrc": <?php echo $m61;?>},
<?}} ?>  
 
];
Morris.Bar({
  element: 'productWiseSales',
  data: day_data,
  xkey: 'period',
  ykeys: ['otc', 'mrc'],
  labels: ['OTC', 'MRC'],
	horizontal: true,
	resize: true,
  xLabelAngle: 60
});	
	
	
	

	
});	
	
</script>
<!-- END morrisjs CHART-->