<?php
session_start();

require "common/conn.php";
require "rak_framework/misfuncs.php";
require "rak_framework/fetch.php";


$usr=$_SESSION["user"];
$mod= $_GET['mod'];


$fdt = $_REQUEST['filter_date_from'];
$tdt = $_REQUEST['filter_date_to'];
$fvouch = $_REQUEST["vouchno"];
if($fvouch =='') $fvouch = 0;

if ($fdt != '') {$fdquery=" and e.trdt >=STR_TO_DATE('".$fdt."','%d/%m/%Y')";}
if ($tdt != '') {$tdquery=" and e.trdt <=STR_TO_DATE('".$tdt."','%d/%m/%Y')";}
if ($fdt == '') {$fdt = date("1/m/Y");}
if ($tdt == '') {$tdt = date("d/m/Y");}

//echo $fdt;

$datef = str_replace('/', '-', $fdt);
$datet = str_replace('/', '-', $fdt);

$datef =  date("d-m-Y", strtotime($datef));
$datet =  date("d-m-Y", strtotime($datet));


//settings;
$reportTitle = "Expense";


//end settings;
/*******/
$startDate = $fdt;
$endDate = $tdt;



if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}
else
{
	
$tdStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; border:1px solid #efefef; white-space:nowrap;"';

$sl = 1;
$loop = "";
$totjul = 0; $totaug= 0; $totsep = 0; $totoct = 0; $totnov = 0; $totdec = 0; $totjan = 0; $totfeb = 0; $totmar = 0; $totapr = 0; $totmay = 0; $totjun = 0; 
    $qry="SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted, nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2023' and m.mn='07'),0)jul ,
    nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2023' and m.mn='08'),0)aug ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2023' 
    and m.mn='09'),0)sep ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2023' and m.mn='10'),0)oct ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno 
    and m.yr='2023' and m.mn='11'),0)nov ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2023' and m.mn='12'),0)dece ,nvl((select m.closingbal from `coa_mon` m 
    where c.glno=m.glno and m.yr='2024' and m.mn='01'),0)jan ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2024' and m.mn='02'),0)feb ,nvl((select m.closingbal 
    from `coa_mon` m where c.glno=m.glno and m.yr='2024' and m.mn='03'),0)mar ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2024' and m.mn='04'),0)apr ,
    nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2024' and m.mn='05'),0)may ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and m.yr='2024' 
    and m.mn='06'),0)jun FROM `coa` c where substr(c.`glno`,1,1)=4 and c.lvl=1 ";
    	  //echo  $qry;die;
		//dumpTxt($qry);
    			$resultinv= $conn->query($qry);
    				if ($resultinv->num_rows > 0){
    					 while($rowinv = $resultinv->fetch_assoc())
    					 {
    					     $pctrl=$rowinv['ctlgl'];
					         $totjul += $rowinv['jul']; $totaug += $rowinv['aug']; $totsep += $rowinv['sep']; $totoct += $rowinv['oct']; $totnov += $rowinv['nov']; $totdec += $rowinv['dece']; 
					        $totjan += $rowinv['jan']; $totfeb += $rowinv['feb']; $totmar += $rowinv['mar']; $totapr += $rowinv['apr']; $totmay += $rowinv['may']; $totjun += $rowinv['jun']; 


	$loop .='						 
	<tr>
		<td '.$tdStyle.'>'.$sl.'</td>
		<td '.$tdStyle.' nowrap>'.$rowinv["glno"].'</td>
		<td '.$tdStyle.'>'.$rowinv["glnm"].'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jul'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['aug'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['sep'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['oct'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['nov'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['dece'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jan'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['feb'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['mar'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['apr'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['may'],2).'</td>
		<td '.$tdStyle.'>'.number_format($rowinv['jun'],2).'</td>

	</tr>								 
		';
		 //Level 2
                $qry1 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                        nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                        FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=2 and c.ctlgl=$pctrl
                        order by c.`glno`"; //echo $qry1;die;
                $result1 = $conn->query($qry1);
                while ($row1 =  $result1->fetch_assoc()) 
                {
                    $sl++;
                    $pctrl1=$row1['ctlgl'];
                    $totjul += $row1['jul']; $totaug += $row1['aug']; $totsep += $row1['sep']; $totoct += $row1['oct']; $totnov += $row1['nov']; $totdec += $row1['dece']; 
					$totjan += $row1['jan']; $totfeb += $row1['feb']; $totmar += $row1['mar']; $totapr += $row1['apr']; $totmay += $row1['may']; $totjun += $row1['jun']; 

                    
                    $loop .='						 
                    	<tr>
                    		<td '.$tdStyle.'>'.$sl.'</td>
                    		<td '.$tdStyle.' nowrap>'.$row1["glno"].'</td>
                    		<td '.$tdStyle.'>'.$row1["glnm"].'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['jul'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['jul'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['aug'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['sep'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['oct'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['nov'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['dece'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['jan'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['feb'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['mar'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['apr'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['may'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row1['jun'],2).'</td>
                    
                    	</tr>								 
                    		';
                    		
                    		//Level 3
                    $qry2 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                            nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='01'),0)jan
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                            ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                            FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=3 and c.ctlgl=$pctrl1
                            order by c.`glno` ";//order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                    $result2 = $conn->query($qry2);
                    while ($row2 =  $result2->fetch_assoc()) 
                    {
                        $sl++;
                        $pctrl2=$row2['ctlgl'];
                        $totjul += $row2['jul']; $totaug += $row2['aug']; $totsep += $row2['sep']; $totoct += $row2['oct']; $totnov += $row2['nov']; $totdec += $row2['dece']; 
					        $totjan += $row2['jan']; $totfeb += $row2['feb']; $totmar += $row2['mar']; $totapr += $row2['apr']; $totmay += $row2['may']; $totjun += $row2['jun']; 

                        $loop .='						 
                    	<tr>
                    		<td '.$tdStyle.'>'.$sl.'</td>
                    		<td '.$tdStyle.' nowrap>'.$row2["glno"].'</td>
                    		<td '.$tdStyle.'>'.$row2["glnm"].'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['jul'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['jul'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['aug'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['sep'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['oct'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['nov'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['dece'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['jan'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['feb'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['mar'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['apr'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['may'],2).'</td>
                    		<td '.$tdStyle.'>'.number_format($row2['jun'],2).'</td>
                    
                    	</tr>								 
                    		';
                    		
                    		//Level 4
                        $qry3 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                                nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                                ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                                FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=4 and c.ctlgl=$pctrl2
                                order by c.`glno`";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                        $result3 = $conn->query($qry3);
                        while ($row3 =  $result3->fetch_assoc()) 
                        {
                            $sl++;
                            $pctrl3=$row3['ctlgl'];
                            $totjul += $row3['jul']; $totaug += $row3['aug']; $totsep += $row3['sep']; $totoct += $row3['oct']; $totnov += $row3['nov']; $totdec += $row3['dece']; 
					        $totjan += $row3['jan']; $totfeb += $row3['feb']; $totmar += $row3['mar']; $totapr += $row3['apr']; $totmay += $row3['may']; $totjun += $row3['jun']; 

                            
                            $loop .='						 
                            	<tr>
                            		<td '.$tdStyle.'>'.$sl.'</td>
                            		<td '.$tdStyle.' nowrap>'.$row3["glno"].'</td>
                            		<td '.$tdStyle.'>'.$row3["glnm"].'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['jul'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['jul'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['aug'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['sep'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['oct'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['nov'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['dece'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['jan'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['feb'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['mar'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['apr'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['may'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row3['jun'],2).'</td>
                            
                            	</tr>								 
                            		';
                            		
                            //Level 5
                            $qry4 = "SELECT c.glno,c.glnm,c.glno ctlgl,c.lvl,c.isposted,
                                        nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='07'),0)jul
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='08'),0)aug
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='09'),0)sep
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='10'),0)oct
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='11'),0)nov
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2023' and  m.mn='12'),0)dece
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='01'),0)jan
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='02'),0)feb
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='03'),0)mar
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='04'),0)apr
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='05'),0)may
                                        ,nvl((select m.closingbal from `coa_mon` m where c.glno=m.glno and  m.yr='2024' and  m.mn='06'),0)jun
                                        FROM `coa` c where   substr(c.`glno`,1,1)=4 and c.lvl=5 and c.ctlgl=$pctrl3
                                        order by c.`glno`";// order by '".$columnName."' '".$columnSortOrder."' limit ".$fixrow." , ".$rowperpage;
                            $result4 = $conn->query($qry4);
                            while ($row4 =  $result4->fetch_assoc()) 
                            {
                                $sl++;
                                $pctrl4=$row4['ctlgl'];
                                $totjul += $row4['jul']; $totaug += $row4['aug']; $totsep += $row4['sep']; $totoct += $row4['oct']; $totnov += $row4['nov']; $totdec += $row4['dece']; 
					        $totjan += $row4['jan']; $totfeb += $row4['feb']; $totmar += $row4['mar']; $totapr += $row4['apr']; $totmay += $row4['may']; $totjun += $row4['jun']; 

                                
                                $loop .='						 
                            	<tr>
                            		<td '.$tdStyle.'>'.$sl.'</td>
                            		<td '.$tdStyle.' nowrap>'.$row4["glno"].'</td>
                            		<td '.$tdStyle.'>'.$row4["glnm"].'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['jul'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['jul'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['aug'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['sep'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['oct'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['nov'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['dece'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['jan'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['feb'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['mar'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['apr'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['may'],2).'</td>
                            		<td '.$tdStyle.'>'.number_format($row4['jun'],2).'</td>
                            
                            	</tr>								 
                            		';
                            }
                            		
                            
                        }
                    }
                }
                
                $sl++;
							 
            			 }
    				}
    				

$thStyle = 'style="padding:5px ; padding:5px 10px; font-family:arial; font-size:8px; background-color:#efefef; border:1px solid #c0c0c0;"';
$tfStyle = 'style="padding:7px ; padding:7px 12px; font-family:arial; font-size:10px; background-color:#efefef; border:1px solid #c0c0c0; font-weight:bold;"';

$height5px = '<img src="" height="5">';


$footerContent ='
'.$_SESSION["comname"].'. / '.str_replace(", Bangladesh","",str_replace("\n"," ",fetchByID('sitesettings',id,1,'address'))).' / '.fetchByID('sitesettings',id,1,'hotline').' / '.fetchByID('sitesettings',id,1,'email').' / '.fetchByID('sitesettings',id,1,'web').' / 
';

$headerContent = '<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <thead>
    <tr>
        <td width="50%"><img width="200" valign="3" src="./assets/images/site_setting_logo/' . $_SESSION["doc_header_logo"] . '" alt=""></td>
        <td width="50%" align="right">' . $height5px . '<h1 style="color:#c0c0c0">' . strtoupper($reportTitle) . '</h1></td>
    </tr>
    </thead>
</table>';

require_once("tcpdf_min/tcpdf.php");
	
	
// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    // Page header and footer
    public function Header() {
        //global $headerContent;
        // Set the header content
        $this->SetY(10);
        $this->Cell(0, 0, $headerContent, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function Footer() {
        global $footerContent;
        // Position at 15 mm from the bottom
        $text = "Page: " . $this->getAliasNumPage() . '/' . $this->getAliasNbPages();
        $this->SetY(-15);
        $this->Cell(0, 10, $footerContent . $text, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

	
$obj_pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
$obj_pdf->SetTitle($reportTitle);
$obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetDefaultMonospacedFont('arial');
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$obj_pdf->SetMargins(PDF_MARGIN_LEFT - 10, 5, PDF_MARGIN_RIGHT - 10);
$obj_pdf->SetPrintHeader(true);
$obj_pdf->SetPrintFooter(true);
$obj_pdf->SetAutoPageBreak(true, 10);
$obj_pdf->SetFont('Helvetica', '', 6);

$content='';
$content.='







<table width="100%" border="0"  cellspacing="0" cellpadding="0">
   <thead>
	<tr>
		<td width="50%"><img width="200" valign="3" src="./assets/images/site_setting_logo/'.$_SESSION["doc_header_logo"].'" alt=""></td>
		<td width="50%" align="right">'.$height5px.'<h1 style="color:#c0c0c0">'.strtoupper($reportTitle).'</h1></td>
	</tr>
	</thead>		
</table>




<div style="font-size:8px;padding-bottom:50px;">
Showing '.$reportTitle.' report from '.$startDate.' to '.$endDate.' 
</div>
<br>

<table width="100%" border="1"  cellspacing="0" cellpadding="3">
   <thead>
	<tr>
		<td '.$thStyle.'>SL.</td>
		<td '.$thStyle.'>Gl No</td>
		<td '.$thStyle.'>Account Name</td>
		<td '.$thStyle.'>Total</td>
		<td '.$thStyle.'>July</td>
		<td '.$thStyle.'>Aug</td>
		<td '.$thStyle.'>Sep</td>
		<td '.$thStyle.'>Oct</td>
		<td '.$thStyle.'>Nov</td>
		<td '.$thStyle.'>Dec</td>
		<td '.$thStyle.'>Jan</td>
		<td '.$thStyle.'>Feb</td>
		<td '.$thStyle.'>Mar</td>
		<td '.$thStyle.'>Apr</td>
		<td '.$thStyle.'>May</td>
		<td '.$thStyle.'>Jun</td>
	</tr>
	</thead>
	<tbody>
		'.$loop.'
	</tbody>
	<tfoot>
        <tr>
        
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'></th>
            <th '.$tfStyle.'>Total</th>
            <th '.$tfStyle.'>'.number_format($totjul, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totjul, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totaug, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totsep, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totoct, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totnov, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totdec, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totjan, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totfeb, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totmar, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totapr, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totmay, 2, ".", ",").'</th>
            <th '.$tfStyle.'>'.number_format($totjun, 2, ".", ",").'</th>
        </tr>
    </tfoot>
</table>					
						
';
$obj_pdf->AddPage();
$obj_pdf->writeHTML($content);
	
$fdt = date("d/m/Y");
$tdt = date("d/m/Y");
$fielprefix = str_replace(" ","_",$reportTitle);
$obj_pdf->OutPut($fielprefix."_".$datef."_to_".$datet.".pdf","I");
//echo $content;
}



?>

