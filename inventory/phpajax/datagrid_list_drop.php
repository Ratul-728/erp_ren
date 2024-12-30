<?php

require "../common/conn.php";
$con = $conn;

## Read value
$draw = $_POST['draw']; 
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$sval= $_GET['sval'];
$aval = $_GET['aval'];
$proval = $_GET['proval'];
$isstype = $_GET["isstype"];

## Search 
 
        $searchQuery = " ";
        if($searchValue != '')
        {
        	$searchQuery = " and (t.`tikcketno` like '%".$searchValue."%' or  date_format(t.`issuedate`,'%d/%m/%y') like '%".$searchValue."%' or  t.`issuedetails` like '%".$searchValue."%' or 
                  t.`sub` like '%".$searchValue."%' or o.name like '%".$searchValue."%' or t.`severity` like '%".$searchValue."%' or i.name like '%".$searchValue."%' or tp.name like '%".$searchValue."%' or sb.name like '%".$searchValue."%'
                 or  date_format(t.`probabledate`,'%d/%m/%y') like '%".$searchValue."%'  or t.`severity` like '%".$searchValue."%' or h1.hrName like '%".$searchValue."%' 
                 or st.stausnm like '%".$searchValue."%' or h2.hrName like '%".$searchValue."%' or cn.name like '%".$searchValue."%' or concat_ws(' ',emp.`firstname`,emp.`lastname`) like '%".$searchValue."%') ";
        }
        ## Total number of records without filtering   #c.`id`,
        $strwithoutsearchquery="SELECT t.`id` id,t.`tikcketno`,o.name `organization`,t.`sub`,date_format(t.`issuedate`,'%d/%m/%y') issuedate
        ,date_format(t.`probabledate`,'%d/%m/%y') `probabledate`,i.name `product`,tp.name `issuetype`,sb.name `issuesubtype`, h1.hrName createby,
        p.name `severity`,concat_ws(' ',emp.`firstname`,emp.`lastname`) assigned,st.stausnm `status`,h2.hrName `reporter`,cn.name `channel`,h3.hrName `accountmanager` 
        
FROM issueticket t left join organization o on t.organization=o.id 
left join hr h1 on t.makeby=h1.id
left join item i on t.product=i.id 
left join issuetype tp on t.issuetype =tp.id 
left join issuesubtype sb on t.issuesubtype=sb.id
left join hr h2 on t.reporter=h2.id 
left join hr h3 on t.accountmanager=h3.id 
left join employee emp on t.`assigned`=emp.id
left join issuestatus st on t.status=st.id 
left join issuechannel cn on t.channel=cn.id 
left join issuepriority p on t.severity=p.id where 1=1 ";

        $sel = mysqli_query($con,$strwithoutsearchquery);
        $totalRecords = $sel->num_rows;
        ## Total number of records with filtering # c.`id`,
        $strwithsearchquery=$strwithoutsearchquery.$searchQuery;
  // s.`status`<>6 
        $sel = mysqli_query($con,$strwithsearchquery);
        $totalRecordwithFilter = $sel->num_rows;
        
        if($columnName == 'humbar'){
            $columnName = "t.`id`";
            $columnSortOrder = "DESC";
        }
        
        $sortqry = "";
        
        if($sval !== ''){
            $sortqry .= " and t.status = ".$sval." ";
        }
        if($aval !== ''){
            $sortqry .= " and t.assigned = ".$aval." ";
        }
        if($proval !== ''){
            $sortqry .= " and t.organization = ".$proval." ";
        }
        if($isstype != ''){
            $sortqry .= " and t.issuetype = ".$isstype." ";
        }
        
        $empQuery=$strwithoutsearchquery.$searchQuery.$sortqry."   order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
        $empRecords = mysqli_query($con, $empQuery);
        $data = array();
        
        //echo $empQuery;die;
        
        while ($row = mysqli_fetch_assoc($empRecords)) 
        {
           //$payur="javascript:openpopup('cmb_forms/form_invoice.php?invid=".$row['invoiceno']."&invamount=".$row['invoiceamt']."')";
           $setdelurl="common/delobj.php?obj=issueticket&ret=issueadminList&mod=6&id=".$row['id'];
           $seturl="issueadmin.php?res=4&msg='Update Data'&id=".$row['id']."&mod=6";
          // $invst='<kbd class="'.$row['invoiceSt'].'">'.$row['name'].'</kbd>';
          // $invpaymentSt='<kbd class="'.$row['paymentSt'].'">'.$row['paySt'].'</kbd>';
          // $action='<span><a href="invoice.php?invid='.$row['invoiceno'].' &mod=2" class="invoice-view" title="View"><i class="fa fa-search"></i></a>
           //<a href="invoice_pdf.php?invid='.$row['invoiceno'].' &mod=2" class="invoice-download" title="Download"><i class="fa fa-download"></i></a>
           //<a href="'.$payur.'" class="invoice-pay" title="Pay"><i class="fa fa-dollar"></i></a>
           //<a href="#" class="invoice-regenerate" title="Re-generate"><i class="fa fa-refresh"></i></a></span>';
            $humbar = '<div class="dropdown dropright">
                                        <div class="fas fa-bars dropdown-toggle" 
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" name = "action" id = "action">
                                            
                                        </div>
                                        <div class="dropdown-menu postitem-status" aria-labelledby="dropdownMenuButton" name = "action" id = "action">
                                            <a class="dropdown-item" hr ef="javscript:void(0)" onclick = "action(1,'.$row["id"].')">Pending</a><br>
                                            <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(2,'.$row["id"].')">Resolved</a><br>
                                            <a class="dropdown-item" h ref="javscript:void(0)" onclick = "action(3,'.$row["id"].')">Copy</a><br>
                                            <a class="dropdown-item" hr ef="javscript:void(0)" onclick = "action(4,'.$row["id"].')">Edit</a><br>
                                            <a class="dropdown-item" hr ef="javscript:void(0)" onclick = "action(5,'.$row["id"].')">Delete</a>
                                        <?php } ?>
                                             
                                        </div>
                                    </div>';
            if($row['probabledate'] == ''){
                $cdate = "Still Active";
            }else{
                $cdate = $row['probabledate'];
            }
            
            $data[] = array(
                    "humbar"=>$humbar,
                    //"tikcketno"=>$columnName,
                    "tikcketno"=>$row['tikcketno'],
            		"organization"=>$row['organization'],
            		"sub"=>$row['sub'],
            		"issuedate"=>$row['issuedate'],
        			"probabledate"=>$cdate,
            		"product"=>$row['product'],
    				"issuetype"=>$row['issuetype'],
    				"issuesubtype"=>$row['issuesubtype'],
            		"severity"=>$row['severity'],
            		"assigned"=>$row['assigned'],
            		"status"=>$row['status'],
            		"createby"=>$row["createby"],
            		"createdt"=>$row["issuedate"],
        			"reporter"=>$row['reporter'],
            		"channel"=>$row['channel'],
    				"accountmanager"=>$row['accountmanager'],
            		"edit"=>'<a class="btn btn-info btn-xs"  href="'. $seturl.'">Edit</a>',
            		"del"=>'<a class="btn btn-info btn-xs" onclick="javascript:confirmationDelete($(this));return false;"  href="'. $setdelurl.'" >Delete</a>'
            	);
        } 
    
   
        //$data[] = array('dt'=>$empQuery);
        //$seturl="contact.php?res=4&msg='Update Data'&id=".$uid."&mod=2";
        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );
        
        echo json_encode($response);

?>