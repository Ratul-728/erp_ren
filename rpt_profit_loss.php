<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
//$fd=$_POST['from_dt'];
//$td=$_POST['to_dt'];
//echo $usr;die;
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
   require_once "common/PHPExcel.php"; 
    $totamount=0;
    $i=0;
    //$f=date("d/m/Y");
    //$t=date("d/m/Y");
    $pmn='06';//$_POST['cmbmonth'];
    $pyr=$_POST['cmbyr'];
    if($pyr==''){$pyr=date("Y");}
    
  if ( isset( $_POST['export'] ) ) 
  {
       $prevyr=$pyr-1;
       'As on '.date("F", strtotime($pmn)) .','.$pyr;
        $objPHPExcel = new PHPExcel(); 
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B2', 'Bithut.com.bd.')
                ->setCellValue('B3', 'Statement of Profit or Loss or other comprehensive income')
                ->setCellValue('A5', 'In Taka')
                ->setCellValue('B5', 'As On '.$pyr)
                ->setCellValue('C5', 'As on '.$prevyr); 
    			
        $firststyle='A5';
        $i=0;
        //turnover
		$turnover=0;$turnoverprev=0;
		$lvl1_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=1;";
		//echo $lvl1_t;die;
		$result1_t = $conn->query($lvl1_t);
		 if ($result1_t->num_rows > 0)
            {
                $row1_t = $result1_t->fetch_assoc();
                $title1= $row1_t["title"];
                 $gl1= $row1_t["gl"]; 
            }
		
		$lvl2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl1) and yr='$pyr' and mn=6";
		//echo $lvl2;die;
		$result2 = $conn->query($lvl2);
		 if ($result2->num_rows > 0)
            {
                $row2 = $result2->fetch_assoc();
                $closingbal2= $row2["closingbal"];
                
            }
        $lvl2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl1) and yr='$prevyr' and mn=6";
		//echo $lvl2prv;die;
		$result2prv = $conn->query($lvl2prv);    
        if ($result2prv->num_rows > 0)
            {
                $row2prv = $result2prv->fetch_assoc();
                $closingbal2prv= $row2prv["closingbal"];
            } 
            
		$turnover=$closingbal2;$turnoverprev=$closingbal2prv;
		$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue($col1,$title1)
		            ->setCellValue($col2,$turnover)
		            ->setCellValue($col3, $turnoverprev);
		            
		//cogs
		    $cogs=0;$cogsprev=0;
		$lvl2_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=2";
	//	echo $lvl2_t;die;
		$result2_t = $conn->query($lvl2_t);
		 if ($result2_t->num_rows > 0)
            {
                $row2_t = $result2_t->fetch_assoc();
                $title2= $row2_t["title"];
                 $gl2= $row2_t["gl"];
            }
		
		$lvl2_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl2)and yr='$pyr' and mn=6";
	//	echo $lvl2_2;die;
		$result2_2 = $conn->query($lvl2_2);
		 if ($result2_2->num_rows > 0)
            {
                $row2_2 = $result2_2->fetch_assoc();
                $closingbal2_2= $row2_2["closingbal"];
            }
            
        $lvl2_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl2) and yr='$prevyr' and mn=6";
		$result2_2prv = $conn->query($lvl2_2prv);    
        if ($result2_2prv->num_rows > 0)
            {
                $row2_2prv = $result2_2prv->fetch_assoc();
                $closingbal2_2prv= $row2_2prv["closingbal"];
            }  
                
        //closingstock for curr yr   
        $qryclosingstock="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$pyr' and mn=6";
		$resultclosingstock = $conn->query($qryclosingstock);
		 if ($resultclosingstock->num_rows > 0)
            {
                $rowclosingstock = $resultclosingstock->fetch_assoc();
                $closingstock= $rowclosingstock["closingbal"];
            }    
            
        // opening stock for curr yr
        $qtyopeningstock="SELECT sum(opbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyr' and mn=7";
		//echo $lvl2_2prv_1;die;
		$result_openingstock = $conn->query($qtyopeningstock);
		 if ($result_openingstock->num_rows > 0)
            {
                $rowopeningstock = $result_openingstock->fetch_assoc();
                $openingstock= $rowopeningstock["closingbal"];
            }    
            
         //closingstock for prev yr   
        $qryclosingstock_prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyr' and mn=6";
		$resultclosingstock_prv= $conn->query($qryclosingstock_prv);
		 if ($resultclosingstock_prv->num_rows > 0)
            {
                $rowclosingstock_prv = $resultclosingstock_prv->fetch_assoc();
                $closingstock_prv= $rowclosingstock_prv["closingbal"];
            }    
            
        // opening stock for prev yr
         $prevyrop=$prevyr-1;
        $qry_openingstoc_prv="SELECT sum(opbal) closingbal FROM `coa_mon`   where glno ='102010100' and yr='$prevyrop' and mn=7";
		//echo $lvl2_2prv_1;die;
		$result_openingstock_prv = $conn->query($qry_openingstoc_prv);
		 if ($result_openingstock_prv->num_rows > 0)
            {
                $rowopeningstovkprv = $result_openingstock_prv->fetch_assoc();
                $openingstoc_prv= $rowopeningstovkprv["closingbal"];
            }      
                
          $cogs=$closingbal2_2-$closingstock+$openingstock;$cogsprev=$closingbal2_2prv-$closingstock_prv+$openingstoc_prv;             
		$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title2)
		            ->setCellValue($col2,$cogs)
		            ->setCellValue($col3, $cogsprev); 
		            
		            
        $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Gross Profit')
		            ->setCellValue($col2,($turnover-$cogs))
		            ->setCellValue($col3, ($turnoverprev-$cogsprev)); 
        //administritive expense
    	    $adminexp=0;$adminexpprv=0;
    	$lvl3_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=3";
    	//echo $lvl2_t;die;
    	$result3_t = $conn->query($lvl3_t);
    	 if ($result3_t->num_rows > 0)
            {
                $row3_t = $result3_t->fetch_assoc();
                $title3= $row3_t["title"];
                 $gl3= $row3_t["gl"];
            }
    	
    	$lvl3_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl3) and yr='$pyr' and mn=6";
    	$result3_2 = $conn->query($lvl3_2);
    	 if ($result3_2->num_rows > 0)
            {
                $row3_2 = $result3_2->fetch_assoc();
                $closingbal3_2= $row3_2["closingbal"];
            }
        $lvl3_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl3) and yr='$prevyr' and mn=6";
    	$result3_2prv = $conn->query($lvl3_2prv);    
        if ($result3_2prv->num_rows > 0)
            {
                $row3_2prv = $result3_2prv->fetch_assoc();
                $closingbal3_2prv= $row3_2prv["closingbal"];
            } 
          $adminexp= $closingbal3_2; $adminexpprv=$closingbal3_2prv;
          
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title3)
		            ->setCellValue($col2,$adminexp)
		            ->setCellValue($col3, $adminexpprv);  
		
		//selling and marketing
		    $sellexp=0; $sellexpprv=0;
		$lvl4_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=4";
		//echo $lvl2_t;die;
		$result4_t = $conn->query($lvl4_t);
		 if ($result4_t->num_rows > 0)
            {
                $row4_t = $result4_t->fetch_assoc();
                $title4= $row4_t["title"];
                 $gl4= $row4_t["gl"];
            } 
		
		$lvl4_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl4) and yr='$pyr' and mn=6";
		$result4_2 = $conn->query($lvl4_2);
		 if ($result4_2->num_rows > 0)
            {
                $row4_2 = $result4_2->fetch_assoc();
                $closingbal4_2= $row4_2["closingbal"];
            }
        $lvl4_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl4) and yr='$prevyr' and mn=6";
		$result4_2prv = $conn->query($lvl4_2prv);    
        if ($result4_2prv->num_rows > 0)
            {
                $row4_2prv = $result4_2prv->fetch_assoc();
                $closingbal4_2prv= $row4_2prv["closingbal"];
            } 
            
            $sellexp=$closingbal4_2;$sellexpprv=$closingbal4_2prv;            
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title4)
		            ->setCellValue($col2,$sellexp)
		            ->setCellValue($col3, $sellexpprv); 
		            
        $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Operating Profit')
		            ->setCellValue($col2,($turnover-$cogs-$adminexp-$sellexp))
		            ->setCellValue($col3, ($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv)); 
		            
        //financial expense
		    $finansexp=0;$finansexpprv=0;
		$lvl5_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=5";
		//echo $lvl2_t;die;
		$result5_t = $conn->query($lvl5_t);
		 if ($result5_t->num_rows > 0)
            {
                $row5_t = $result5_t->fetch_assoc();
                $title5= $row5_t["title"];
                 $gl5= $row5_t["gl"];
            }
		
		$lvl5_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl5) and yr='$pyr' and mn=6";
		$result5_2 = $conn->query($lvl5_2);
		 if ($result5_2->num_rows > 0)
            {
                $row5_2 = $result5_2->fetch_assoc();
                $closingbal5_2= $row5_2["closingbal"];
            }
        $lvl5_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl5) and yr='$prevyr' and mn=6";
		$result5_2prv = $conn->query($lvl5_2prv);    
        if ($result5_2prv->num_rows > 0)
            {
                $row5_2prv = $result5_2prv->fetch_assoc();
                $closingbal5_2prv= $row5_2prv["closingbal"];
            }  
             $finansexp=$closingbal5_2;$finansexpprv=$closingbal5_2prv;
             
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title5)
		            ->setCellValue($col2,$finansexp)
		            ->setCellValue($col3,$finansexpprv);      
             
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Profit Before Tax')
		            ->setCellValue($col2,($turnover-$cogs-$adminexp-$sellexp-$finansexp))
		            ->setCellValue($col3,($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv-$finansexpprv)); 
		  //tax expense
			    $taxexp=0; $taxexpprv=0;
			$lvl6_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=6;";
			//echo $lvl2_t;die;
			$result6_t = $conn->query($lvl6_t);
			 if ($result6_t->num_rows > 0)
                {
                    $row6_t = $result6_t->fetch_assoc();
                    $title6= $row6_t["title"];
                     $gl6= $row6_t["gl"];
                }
			
			$lvl6_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl6) and yr='$pyr' and mn=6";
			$result6_2 = $conn->query($lvl6_2);
			 if ($result6_2->num_rows > 0)
                {
                    $row6_2 = $result6_2->fetch_assoc();
                    $closingbal6_2= $row6_2["closingbal"];
                }
            $lvl6_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl6) and yr='$prevyr' and mn=6";
			$result6_2prv = $conn->query($lvl6_2prv);    
            if ($result6_2prv->num_rows > 0)
                {
                    $row6_2prv = $result6_2prv->fetch_assoc();
                    $closingbal6_2prv= $row6_2prv["closingbal"];
                }  
                 $taxexp=$closingbal6_2; $taxexpprv=$closingbal6_2prv; 
         $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title6)
		            ->setCellValue($col2,$taxexp)
		            ->setCellValue($col3,$taxexpprv); 
		            
		         // income tax
			    $inctax=0; $inctaxprv=0;
			$lvl7_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=7;";
			//echo $lvl2_t;die;
			$result7_t = $conn->query($lvl7_t);
			 if ($result7_t->num_rows > 0)
                {
                    $row7_t = $result7_t->fetch_assoc();
                    $title7= $row7_t["title"];
                     $gl7= $row7_t["gl"];
                }
			
			$lvl7_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl7) and yr='$pyr' and mn=6";
			$result7_2 = $conn->query($lvl7_2);
			 if ($result7_2->num_rows > 0)
                {
                    $row7_2 = $result7_2->fetch_assoc();
                    $closingbal7_2= $row7_2["closingbal"];
                }
            $lvl7_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl7) and yr='$prevyr' and mn=6";
			$result7_2prv = $conn->query($lvl7_2prv);    
            if ($result7_2prv->num_rows > 0)
                {
                    $row7_2prv = $result7_2prv->fetch_assoc();
                    $closingbal7_2prv= $row7_2prv["closingbal"];
                } 
               $inctax=$closingbal7_2; $inctaxprv=$closingbal7_2prv;    
		    
		    $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title7)
		            ->setCellValue($col2,$inctax)
		            ->setCellValue($col3,$inctaxprv); 
		      
		$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Total Income Tax Expense')
		            ->setCellValue($col2,($taxexp+$inctax))
		            ->setCellValue($col3,($taxexpprv+$inctaxprv)); 
		 $i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Profit/(loss) for the year')
		            ->setCellValue($col2,($turnover-$cogs-$adminexp-$sellexp-$finansexp-$taxexp-$inctax))
		            ->setCellValue($col3,($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv-$finansexpprv-$taxexpprv-$inctaxprv));   
		   
		   //other comprehensive
			    $otherinc=0; $otherincprv=0;
			$lvl8_t="SELECT `title`,GROUP_CONCAT(`gl`) gl FROM `rpt_gl_map` where `Report Menu`=94 and sl=8;";
			//echo $lvl2_t;die;
			$result8_t = $conn->query($lvl8_t);
			 if ($result8_t->num_rows > 0)
                {
                    $row8_t = $result8_t->fetch_assoc(); 
                    $title8= $row8_t["title"];
                     $gl8= $row8_t["gl"];
                }
			
			$lvl8_2="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl8) and yr='$pyr' and mn=6";
			$result8_2 = $conn->query($lvl8_2);
			 if ($result8_2->num_rows > 0)
                {
                    $row8_2 = $result8_2->fetch_assoc();
                    $closingbal8_2= $row8_2["closingbal"];
                }
            $lvl8_2prv="SELECT sum(closingbal) closingbal FROM `coa_mon`   where glno in($gl8) and yr='$prevyr' and mn=6";
			$result8_2prv = $conn->query($lvl8_2prv);    
            if ($result8_2prv->num_rows > 0)
                {
                    $row8_2prv = $result8_2prv->fetch_assoc();
                    $closingbal8_2prv= $row8_2prv["closingbal"];
                } 
                
                $otherinc=$closingbal8_2;$otherincprv=$closingbal8_2prv;  
                
                  
		$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,$title8)
		            ->setCellValue($col2,$otherinc)
		            ->setCellValue($col3,$otherincprv);     
         
     	$i++;
        $urut=$i+5;	$col1='A'.$urut; $col2='B'.$urut;$col3='C'.$urut;
        $objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue($col1,'Total Comprehensive Income')
		            ->setCellValue($col2,($turnover-$cogs-$adminexp-$sellexp-$finansexp-$taxexp-$inctax+$otherinc))
		            ->setCellValue($col3,($turnoverprev-$cogsprev-$adminexpprv-$sellexpprv-$finansexpprv-$taxexpprv-$inctaxprv+$otherincprv));         
		            
        $objPHPExcel->getActiveSheet()->setTitle('Statement of Profit and Loss');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'fs_rdl'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileNm));
        ob_clean();
        flush();
        readfile($fileNm);
        exit;
    }
    else
    {
    $mode=1;//Insert mode
    }
    
    $currSection = 'rpt_profit_loss';
    $currPage = basename($_SERVER['PHP_SELF']);
?>

<?php
     include_once('common_header.php');
?>
<body class="form">
    
<?php
    include_once('common_top_body.php');
?>

<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>ACCOUNTING </span>
        </div>
        <?php include_once('menu.php'); ?>
       
        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper --> 
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <div class="container-fluid pagetop">
            <div class="row">
                <div class="col-lg-12" >
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                       
                     <form method="post" action="rpt_profit_loss.php?pg=1&mod=7" id="form1" enctype="multipart/form-data">  
                        <!-- START PLACING YOUR CONTENT HERE -->
                        <div class="button-bar">
                            <!--<div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <label for="po_dt">Order Date*</label>
                                </div>     
                            </div> -->
                            <div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Year </div>
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <select name="cmbyr" id="cmbyr" class="form-control" required>
                                    <?php $yr=date("Y");?> 
                                        <option value="<? echo $yr; ?>" <? if ($pyr == $yr) { echo "selected"; } ?>><? echo $yr; ?></option>
                                        <option value="<? echo $yr-1; ?>" <? if ($pyr == $yr-1) { echo "selected"; } ?>><? echo $yr-1; ?></option>
                                        <option value="<? echo $yr+1; ?>" <? if ($pyr == $yr+1) { echo "selected"; } ?>><? echo $yr+1; ?></option>
                                    </select>
                                </div>     
                            </div>
                            <!--div class="col-lg-1 col-md-6 col-sm-6">
                                <div class="input-group">
                                    <div >Month</div> 
                                </div>     
                            </div> 
                            <div class="col-lg-3 col-md-6 col-sm-6">
                                
                                <div class="input-group">
                                    <select name="cmbmonth" id="cmbmonth" class="form-control" required>
<?php $mon= date('F');for($i=1;$i<=12;$i++){?>          
                                            <option value="<? echo  str_pad($i, 2, "0", STR_PAD_LEFT); ?>" <? if (str_pad($i, 2, "0", STR_PAD_LEFT) == $pmn) { echo "selected"; } ?>><? echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
<?php } ?>                    
                                        </select>
                                </div>     
                            </div-->
                             
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="view" value="View" id="view"  >
                            <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="export" value="Export " id="export">
                            <!--input class="btn btn-lg btn-default" type="submit" name="cancel" value="Print"  id="cancel"  onclick="printDiv('printableArea')"-->
							<input class="btn btn-lg btn-default print-view" type="button" name="cancel" value="Print">
							
                        </div>
                            <?php include_once('phpajax/rpt_load_profit_loss.php'); ?> 
        <!-- /#end of panel -->
                    </form>
                    
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
		
<?php
	include_once('common_footer.php');
?>


<script>
    
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}


</script>
<script>


$(".tbl-bhbs tr").mouseover(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background-color","#E6F0FF");
 	 	//$("."+thisClass).css("font-weight","bold");
  
});

$(".tbl-bhbs tr").mouseleave(function(){
    var thisClass = $(this).attr("class");
    $("."+thisClass).css("background","transparent");
 		// $("."+thisClass).css("font-weight","normal");
});
	
	
</script>




	




</body>
</html>



<?php }?>