<?php
require "conn.php";
 $usrid = $_POST['usrid'];

if ( isset( $_POST['cancel'] ) ) {
      header("Location: ".$hostpath."/data_generate_proc.php?res=01&msg='New Entry'&id=''&mod=2");
}

else
{
     $errflag=0;
     $poid=0;
    if ( isset( $_POST['psp'] ) || 1 ) 
    {
         $sp = $_POST['cmbprocnm'];
         $yr = $_POST['cmbyr'];
         $mn = $_POST['cmbmonth'];
         $dt="01/$mn/$yr";
         
         //print_r($_POST);
        //echo $sql;die;
        if($sp=='psp_salse_forcast')
        {
             $qry = "CALL ".$sp."(1)";
            $err="Sales Forcast Data is updated";
        }
        else if($sp=='psp_deal_forcast')
        {
             $qry = "CALL ".$sp."(1)";
            $err="Deal Forcast Data is updated";
        }
        else if($sp=='psp_invoice_gen')
        {
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
        }
        else if($sp=='psp_salary_gen')
        {
            $cnt=0;$isapprove=0;
            $proc_mnt="".$yr."-".$mn."-01" ;
            $getqry="select approvest from monthlysalary where salaryyear=$yr and salarymonth=$mn";
            
            $resultget= $conn->query($getqry);
			if ($resultget->num_rows > 0)
			{ 
			    while($rowget = $resultget->fetch_assoc())
			    {
			       $isapprove= $rowget["approvest"];
			    }
			} 
            
            //echo $isapprove; die;
            
            if($isapprove==0)
            {
                $mnp=$mn-1;
                $startday=$yr.'-'.$mnp.'-25';
                $endday=$yr.'-'.$mn.'-24';
                $datefrom = strtotime($startday);
                $dateto = strtotime($endday);
                $datediff = $dateto - $datefrom;
                //echo 
                $datediff=round($datediff / (60 * 60 * 24));
                 $totholiday=0;
                $holiday="SELECT count(*) cnt FROM Holiday h where h.date BETWEEN '$startday' and '$endday'"; 
               // echo $holiday;die;
                $resulholiday= $conn->query($holiday); 
    			if ($resulholiday->num_rows > 0)
    			{ 
    			    while($rowholiday = $resulholiday->fetch_assoc())
    			    { 
    			        $totholiday=$rowholiday["cnt"];
    			    }
    			}   
                //echo $totholiday;die; 
                $delqry="delete from monthlysalary where salaryyear=$yr and salarymonth=$mn and approvest=0";
                 if ($conn->query($delqry) == TRUE) {$msg0="Salary reverse for month $mn"; }
                 
                $basicqry="SELECT e.id hrid,h.id shr,h.attendance_id attid,s.gross,e.`employeecode`,e.`active_st`,e.`gender`  FROM `employee` e,hr h,gross_salary s
where e.id=s.empid and e.employeecode=h.emp_id and e.`active_st`='A' and s.`effectivedate`<'$endday' ";
                /*and h.emp_id='EMP-001000'
                "select a.hrid,a.compansation,a.privilagedfund,b.employeecode
,COALESCE(c.basic,0) basic,COALESCE(a.increment,0) incr_no,COALESCE(c.increment,0) incr
,(COALESCE(c.basic,0)+(COALESCE(a.increment,0)*COALESCE(c.increment,0))) curbasic,COALESCE(c.maxgross,0) maxgross 
 from hrcompansation a LEFT JOIN compansationSetup c ON a.compansation=c.id 
 left join employee b on a.hrid=b.id
 where a.st = 1 and a.effectivedate<='2024-01-31'";*/
 //effectivedate <='$proc_mnt')";
                //echo $basicqry;die;
              //  echo $resultbasic->num_rows;
                $resultbasic= $conn->query($basicqry); 
    			if ($resultbasic->num_rows > 0)
    			{ 
    			    while($rowbasic = $resultbasic->fetch_assoc())
    			    { 
    			        $hrid=$rowbasic["hrid"];$gross=$rowbasic["gross"];$attid=$rowbasic["attid"];$shr=$rowbasic["shr"];
    			        $gen=$rowbasic["gender"];
    			        $totatt=0; $totleave=0;$netatt=0;$pgross=$gross;
    			        
    			        $leaveqry="select  COALESCE(sum((`endday`-`startday`)+1),0) lvno from `leave`  where hrid=$hrid and ((startday>='$startday' ) and (endday<='$endday'));";
    			       //echo $leaveqry;die;
    			        $resultleave= $conn->query($leaveqry); 
    			        if ($resultleave->num_rows > 0)
    			         {
    			            while($rowleave = $resultleave->fetch_assoc())
    			            {
    			                $totleave=$rowleave["lvno"];
    			            }
    			         } 
    			         
    			         //off day calculation
    			         $totoffday=0;
    			         $offdayqry="select count(sf.effectivedt) offday from assignshifthist sf
where sf.empid=$hrid
and (sf.effectivedt between '$startday' and '$endday') 
and sf.shift=6
and sf.effectivedt not in
(
select t.date from attendance_test t where t.hrid=$attid and t.date  between '$startday' and '$endday'
)";
    			       
    			      // echo $offdayqry;die;
    			        $resultoffday= $conn->query($offdayqry); 
    			        if ($resultoffday->num_rows > 0)
    			         {
    			            while($rowoffday = $resultoffday->fetch_assoc())
    			            {
    			                $totoffday=$rowoffday["offday"];
    			            }
    			         } 
    			        //echo $totoffday;die;
    			        
    			        $attqury="select count(*) attno from attendance_test t where t.hrid=$attid 
    			        and (t.date between '$startday' and '$endday') and t.intime<'13:30:00'";
    			        
    			        $resultatt= $conn->query($attqury); 
    			        if ($resultatt->num_rows > 0)
    			         {
    			            while($rowatt = $resultatt->fetch_assoc()) 
    			            {
    			                $totatt=$rowatt["attno"]; 
    			            }
    			         } 
    			         
    			        $netatt=$totatt+$totleave+$totholiday+$totoffday;
    			        if($netatt>$datediff){$netatt=$datediff;}
    			        //echo $datediff;die;
    			        $gross=$gross*$netatt/$datediff;
    			        $basic=$pgross*70*0.01;$rent=$pgross*15*0.01;$medical=$pgross*10*0.01;$conv=$pgross*5*0.01;
    			        
    			        //if($basic>$max){$basic=$max;}
    			        $insquery="insert into monthlysalary(salaryyear,salarymonth,hrid,privilage,benft_1,benft_2,benft_3,benft_4,makeby,makedt) values('$yr','$mn',$shr,0,$basic,$rent,$medical,$conv,$usrid,sysdate())";
    			       if ($conn->query($insquery) == TRUE) {$msg1="salary created for the month $mn"; }
    			       
    			       $totlate=0;$cumlate=0;$pday='';$late=0;$cumLatenote='';
    			       $lateqry="select t.date attday from attendance_test t
                                left join Shifting st on st.id=nvl((SELECT s.shift FROM assignshifthist s  where s.st=1 and s.empid=(select e.id from employee e  where e.employeecode=(select h.`emp_id` from hr h where h.attendance_id=$attid)) and s.effectivedt=t.date),3)
                                where t.hrid=$attid and (t.date between '$startday' and '$endday')
                                and t.intime<'13:30:00'
                                and TIMEDIFF(t.intime,st.starttime)>'00:15:59'  
                                ORDER BY `t`.`date` ASC";
                                //echo $lateqry; die;
                        $resullate= $conn->query($lateqry); 
    			        if ($resullate->num_rows > 0)
    			         {
    			            while($rowlate = $resullate->fetch_assoc())
    			            {
    			               $totlate=$totlate+1;
    			                $day=$rowlate["attday"];
    			                //echo $day.'#'.$pday.'-';
    			                if($day==$pday){$cumlate=$cumlate+1;} else{$cumlate=1; }
    			                if($cumlate>=3){$late=1;}
    			                $pday=$day;
    			            }
    			         } 
                          //echo $totleave;die;
                         if($totlate>=5){$latededuct=$basic*2/$datediff;} elseif($late==1){$latededuct=$basic*1/$datediff;$cumLatenote="Have Cumilitive late";} else{$latededuct=0;$cumLatenote="No Cumilitive late";}
                         // echo $late;die;
                        //benft_13--AIT
                        /* tds calculation*/
                        $yearlygross=0;$festibalbonus=0;$taxableincome=0;
                        $yearlygross=$pgross*12;$festibalbonus=$pgross*0.6*2;
                        $tinc=($yearlygross+$festibalbonus);
                        $taxableincome=$tinc*2/3;
                        $nettax=0;
                        //echo $pgross.'-pg'.$yearlygross.'yg'.$taxableincome.'-ti-'.$gen.'-g';
                        if($gen=='M' and $taxableincome>350000 ){$taxableincome=$taxableincome-350000;} 
                        else if ($gen=='F' and $taxableincome>400000 ) {$taxableincome=$taxableincome-400000;}
                        else {$taxableincome=0;};
                      // echo $taxableincome.'-tia';
                        /*tds calculation  46000-ti23000-nt1859.1666666667-mt */
                        if($taxableincome>=100000)
                        {
                            $nettax=$nettax+5000;$taxableincome=$taxableincome-100000;
                            if($taxableincome>=300000)
                            {
                                $nettax=$nettax+30000;$taxableincome=$taxableincome-300000;
                                if($taxableincome>=400000)
                                {
                                    $nettax=$nettax+60000;$taxableincome=$taxableincome-400000;
                                     if($taxableincome>=500000)
                                     {
                                         $nettax=$nettax+100000;$taxableincome=$taxableincome-500000;
                                         $nettax=$nettax+$taxableincome*0.25;
                                         
                                     } 
                                     else
                                     {
                                         $nettax=$nettax+$taxableincome*0.20;
                                         
                                     }
                                    
                                } 
                                else
                                {
                                    $nettax=$nettax+$taxableincome*0.15;
                                    
                                }
                                
                            } 
                            else
                            {
                                $nettax=$nettax+$taxableincome*0.1;
                                
                            }
                            
                        } 
                        else
                        {
                            $nettax=$nettax+$taxableincome*0.05;
                            
                        }
                        
                        
                       
                      // echo $nettax.'-nt';
                        if($nettax>0)
                        {
                        $rebate=0;//$nettax*0.03;
                        if($nettax<5000){$nettax=5000;}
                        $monthlynettax=($nettax-$rebate)/12;
                        $tds=number_format($monthlynettax,2);
                        }
                        else
                        {
                        $monthlynettax=0;
                        $tds=0;
                        }
                       // echo $monthlynettax.'-mt';
                         $updqry="update monthlysalary set benft_5=$latededuct,benft_11=$monthlynettax,total=$basic+$rent+$medical+$conv-$latededuct-$monthlynettax,notes='Present:$totatt days ,Leave:$totleave days , holyday:$totholiday days,Offdays : $totoffday,late:$totlate days ,$cumLatenote , yearly total income $tinc and monthly TDS : $tds ' where hrid=$shr and salaryyear='$yr' and salarymonth='$mn'";
    		                   //echo $updqry;die; 
    		                     if ($conn->query($updqry) == TRUE) {$msg2="Late deduction created "; }
                      //  die;
    			       //echo $insquery.'</br></br>';
    			        /*$getpkg="select p.benifittp,m.mapp,p.befitamount,p.isPercentage
                                ,(case t.benifitnature when 2 then -1 else 1 end)*(case p.isPercentage when 1 then $basic*p.befitamount*.01 else p.befitamount end) amt 
                                ,t.title,t.benifitnature
                                from pakageSetupdetails p
                                left join benifitmapping m on p.benifittp=m.benifittp 
                                left join benifitype t on p.benifittp=t.id
                                where p.scale=$comp and cycle=1";
    			        */
    			       // select p.benifittp,m.mapp,(case p.isPercentage when 1 then $basic*p.befitamount*.01 else p.befitamount end) amt 
    			        //from pakageSetupdetails p
                          //      left join benifitmapping m on p.benifittp=m.benifittp where p.pakage=$pakg and p.scale=$comp and cycle=1";
                        //echo  $getpkg;       
                       /* $resultgetpkg= $conn->query($getpkg);
    			        if ($resultgetpkg->num_rows > 0)
    		            { 
    	                    while($rowpkg = $resultgetpkg->fetch_assoc())
    		                {
    		                     $colid=$rowpkg["benifittp"];$col=$rowpkg["mapp"];$amt=$rowpkg["amt"];
    		                     $updqry="update monthlysalary set $col=$amt where hrid=$hrid and salaryyear='$yr' and salarymonth='$mn'";
    		                     if ($conn->query($updqry) == TRUE) {$msg2="benifit created "; }
    		                    // echo $updqry.'</br>';
    		                }
    		            }*/
    		            
    			    }
    			    
    			    $err=$msg1." and ".$msg2;
    			}
    			else
    			{
    			    $err= "No Data Found";
    			}
            }
            else
            {
              $err= "Sallary Already approved";  
            }
            
          //  die;
            /*if($cnt==0)
            {
            $qry = "CALL ".$sp."(".$yr.",".$mn.",".$hrid.")";
            $err="Invoice generated for '".$mn."'-'".$yr."'  succesfully";
            }
            else
            {
               $errflag=1;
               $err="Invoice already generated for '".$mn."'-'".$yr."'  Earlier"; 
            }*/
            $errflag=3;
        }
        else if($sp=='psp_month_end')
        {
            
            $duplyqry = "select * from coa_mon where yr='$yr' and mn='$mn'";
            //echo $duplyqry; die;
            $resultdupl= $conn->query($duplyqry);
			if ($resultdupl->num_rows == 0)
			{
			
                 $resetqry = "update coa set opbal=closingbal";
    			        $conn->query($resetqry);
                
                $getqry="select ctlgl , (sum(dr)-sum(cr)) amt 
                FROM
                (
                select a.glno ctlgl
                ,(case when d.dr_cr ='D' then d.amount else 0 end) dr
                ,(case when d.dr_cr ='C' then d.amount else 0 end) cr
                from coa a,gldlt d
                where a.glno=d.glac and a.lvl=5
                and d.entrydate between str_to_date('$dt','%d/%m/%Y') and LAST_DAY(str_to_date('$dt','%d/%m/%Y'))
                )u
                group by ctlgl
                order by ctlgl";
               // echo $getqry; die;
                $resultget= $conn->query($getqry);
    			if ($resultget->num_rows > 0)
    			{
    			    while($rowinv = $resultget->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv["ctlgl"];$amt=$rowinv["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			    
    			}
    			
    			$getqry1="select ctlgl , (sum(dr)-sum(cr)) amt 
                FROM
                (
                select a.glno ctlgl
                ,(case when d.dr_cr ='D' then d.amount else 0 end) dr
                ,(case when d.dr_cr ='C' then d.amount else 0 end) cr
                from coa a,gldlt d
                where a.glno=d.glac and a.lvl=4
                and d.entrydate between str_to_date('$dt','%d/%m/%Y') and LAST_DAY(str_to_date('$dt','%d/%m/%Y'))
                )u
                group by ctlgl
                order by ctlgl";
    			
    			$resultget1= $conn->query($getqry1);
    			if ($resultget1->num_rows > 0)
    			{
    			    while($rowinv1 = $resultget1->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv1["ctlgl"];$amt=$rowinv1["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry2="select ctlgl , (sum(dr)-sum(cr)) amt 
                FROM
                (
                select a.glno ctlgl
                ,(case when d.dr_cr ='D' then d.amount else 0 end) dr
                ,(case when d.dr_cr ='C' then d.amount else 0 end) cr
                from coa a,gldlt d
                where a.glno=d.glac and a.lvl=3
                and d.entrydate between str_to_date('$dt','%d/%m/%Y') and LAST_DAY(str_to_date('$dt','%d/%m/%Y'))
                )u
                group by ctlgl
                order by ctlgl";
    			
    			$resultget2= $conn->query($getqry2);
    			if ($resultget2->num_rows > 0)
    			{
    			    while($rowinv2 = $resultget2->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv2["ctlgl"];$amt=$rowinv2["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry3="select ctlgl , (sum(dr)-sum(cr)) amt 
                FROM
                (
                select a.glno ctlgl
                ,(case when d.dr_cr ='D' then d.amount else 0 end) dr
                ,(case when d.dr_cr ='C' then d.amount else 0 end) cr
                from coa a,gldlt d
                where a.glno=d.glac and a.lvl=2
                and d.entrydate between str_to_date('$dt','%d/%m/%Y') and LAST_DAY(str_to_date('$dt','%d/%m/%Y'))
                )u
                group by ctlgl
                order by ctlgl";
    			
    			$resultget3= $conn->query($getqry3);
    			if ($resultget3->num_rows > 0)
    			{
    			    while($rowinv3 = $resultget3->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv3["ctlgl"];$amt=$rowinv3["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry4="select a.ctlgl,sum(a.closingbal) amt from coa a where a.lvl=5 group by a.ctlgl";
    			$resultget4= $conn->query($getqry4);
    			if ($resultget4->num_rows > 0)
    			{
    			    while($rowinv4 = $resultget4->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv4["ctlgl"];$amt=$rowinv4["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry5="select a.ctlgl,sum(a.closingbal) amt from coa a where a.lvl=4 group by a.ctlgl";
    			$resultget5= $conn->query($getqry5);
    			if ($resultget5->num_rows > 0)
    			{
    			    while($rowinv5 = $resultget5->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv5["ctlgl"];$amt=$rowinv5["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry6="select a.ctlgl,sum(a.closingbal) amt from coa a where a.lvl=3 group by a.ctlgl";
    			$resultget6= $conn->query($getqry6);
    			if ($resultget6->num_rows > 0)
    			{
    			    while($rowinv6 = $resultget6->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv6["ctlgl"];$amt=$rowinv6["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry7="select a.ctlgl,sum(a.closingbal) amt from coa a where a.lvl=2 group by a.ctlgl";
    			$resultget7= $conn->query($getqry7);
    			if ($resultget7->num_rows > 0)
    			{
    			    while($rowinv7 = $resultget7->fetch_assoc())
    			    { 
    			        $ctlgl=$rowinv7["ctlgl"];$amt=$rowinv7["amt"];
    			        $qry = "update coa set closingbal=closingbal+$amt  where glno=$ctlgl";
    			        $conn->query($qry);
    			    }
    			}
    			
    			$getqry8="INSERT INTO `coa_mon`( `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal`, `yr`, `mn`, `entryby`, `entrydate`, `status`, `companyid`)
    select  `glno`, `glnm`, `ctlgl`, `isposted`, `dr_cr`, `lvl`, `opbal`, `closingbal`, '$yr', '$mn', $usrid, sysdate(), `status`, `companyid` from coa";
    			$resultget8= $conn->query($getqry8);
    		
                $errflag=1;
                $err="Month end completed";
			}
			else
			{
			   $err="Alredy procesed earlier"; 
			   $errflag=1;
			}
            
        }
        else
        {
            $err="No process found";
        }
    }
    if ( isset( $_POST['update'] ) )
    {
        
    }
   
    if ($conn->connect_error) {
       echo "Connection failed: " . $conn->connect_error;
    }
    if($errflag==0)
    {
        if ($conn->query($qry) == TRUE) 
        {
                header("Location: ".$hostpath."/data_generate_proc.php?res=1&msg=".$err."&id=''&mod=5&pg=1");
        } 
        else
        {
             $err="Error: " . $qry . "<br>" . $conn->error;
              header("Location: ".$hostpath."/data_generate_proc.php?res=2&msg=".$err."&id=''&mod=5");
        }
    }
    else if($errflag==3)
    {
        
     header("Location: ".$hostpath."/data_generate_proc.php?res=1&msg=".$err."&id=''&mod=5&pg=1");
    }
     else
    {
        header("Location: ".$hostpath."/data_generate_proc.php?res=2&msg=".$err."&id=''&mod=5");
       
    }
    
    
    $conn->close();
}
?>