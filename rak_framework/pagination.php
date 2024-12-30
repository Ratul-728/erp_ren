<?php

$pageIndex = ((int)$_REQUEST['pageIndex'] == 0) ? 1 : (int)$_GET['pageIndex'];

if($_REQUEST['pageIndex']){$_SESSION['pageIndex'] = $_REQUEST['pageIndex'];}else{$pageIndex = 1;}
if($_SESSION['pageIndex']){$pageIndex = $_SESSION['pageIndex'];}

//print_r($data);
//echo $pageIndex;

if($rowPerPage == 'all'){$rowPerPage = '5000';}




//echo '>>>>'. $rowPerPage;
if($_REQUEST['rowPerPage'] || $rowPerPage){$_SESSION['rowPerPage'] = $rowPerPage;}	
//$rowPerPage = (!$rowPerPage )?10:$rowPerPage; 
if(!$rowPerPage && !$_SESSION['rowPerPage'])
{
	$rowPerPage = '10';
}
else
{
	$rowPerPage = $_SESSION['rowPerPage'];
}

	if(@array_key_exists('columns', $data)) 
	{
		$totalRecords = count($data)-1;
	}
	else
	{
		$totalRecords = count($data);
	}
	
	
	if($_REQUEST['featured'] == 'true')
	{
		if($totalRecords > 500)
		{
			$totalRecords = 500;
		}
	}
$totalPages = ceil($totalRecords/$rowPerPage);
//echo "Pages".$totalPages."<br>";
//echo "Total REc".$totalRecords."<br>";
if($pageIndex > $totalPages)
$pageIndex = $totalPages;

$startIndex = ($pageIndex-1)*$rowPerPage;
if($totalRecords > $rowPerPage)
$endIndex = $startIndex + $rowPerPage;
else
$endIndex = $totalRecords;

if($pageIndex ==  $totalPages)
{
	if($totalRecords < $totalPages*$rowPerPage)
	$endIndex = $totalRecords;
}


//echo "total page <p>".$endIndex."</p>";

if($totalRecords > 0)
{	
	$rangeStart = $startIndex+1;

	if(($totalRecords < $rowPerPage) || ($pageIndex == $totalPages))
	{
		$rangeEnd = $totalRecords;
	}
	else
	{
		$rangeEnd = $endIndex;
	}
	$range = "Showing ".$rangeStart."&nbsp;-&nbsp;".$rangeEnd;
}







	function Pagination($data, $limit = null, $current = null, $adjacents = null)
	{
		$result = array();
	
		if (isset($data, $limit) === true)
		{
			$result = range(1, ceil($data / $limit));
	
			if (isset($current, $adjacents) === true)
			{
				if (($adjacents = floor($adjacents / 2) * 2 + 1) >= 1)
				{
					$result = array_slice($result, max(0, min(count($result) - $adjacents, intval($current) - ceil($adjacents / 2))), $adjacents);
				}
			}
		}
	
		return $result;
	}
	
	$total = $totalRecords;
	$per_page = $rowPerPage;
	$current_page = $pageIndex;
	$adjacent_links = 4;
	
	$pageLinkArray = Pagination($total, $per_page, $current_page, $adjacent_links);
	
	//print_r($pageLinkArray);
	
	$adtinlQstrArr = $_REQUEST;
	unset($adtinlQstrArr['pageIndex']);
	unset($adtinlQstrArr['rowPerPage']);
	unset($adtinlQstrArr['msg']);
	
	//print_r($adtinlQstrArr);
	
	
	foreach($adtinlQstrArr as $aqakey => $adtinlQstrArrValue)
	{
		if($adtinlQstrArrValue)
		{
			$adtinlQstr .= '&'.$aqakey.'='.$adtinlQstrArrValue;
		}
		
	}
	
	foreach($pageLinkArray as $value)
	{
		$str_active = ($value == $pageIndex)?'class="active"':'';
		$str_pageLink .= '<li><a '.$str_active.' href="'.$_SERVER['PHP_SELF'].'?pageIndex='.$value.'&rowPerPage='.$rowPerPage.$adtinlQstr.'">'.$value.'</a>';
	}
	
	
	
	if($rowPerPage){$queryString .= '&rowPerPage='.$rowPerPage;}	
	 $nextPage = $pageIndex+1;
	 $prevPage = $pageIndex-1;
	 
	 $str_display_pagination .= '<ul class="paging_numbers">';
	 if($show_back_next != 'false')
	 $str_display_pagination .=  ($pageIndex > 1)?'<li><a href="'.$_SERVER['PHP_SELF'].'?pageIndex='.$prevPage.$queryString.$adtinlQstr.'" class="back">Previous</a></li>':'<li><a href="javascript:void();" class="disabled">Previous</a></li>';
	 $str_display_pagination .=  $str_pageLink;
	 if($show_back_next != 'false')
	 $str_display_pagination .=  ($totalPages > $pageIndex)?'<li><a href="'.$_SERVER['PHP_SELF'].'?pageIndex='.$nextPage.$queryString.$adtinlQstr.'" class="next">Next</a></li>':'<li><a href="javascript:void();" class="disabled">Next</a></li>';
	 $str_display_pagination .= '<ul>';
	
	
	
	
	//generate paging table
	
	
	/* total item stat */
	$stat_str_s = ($totalRecords > 1)?'s':'';
	$stat_total_items .= 'Total Item'.$stat_str_s;
	$stat_total_items .= ': '.$totalRecords;
	
	
	
	$state_startIndex = $startIndex+1;
	$state_showing_range = ($totalRecords > 0)?' Showing '.$state_startIndex.' to '.$endIndex.'&nbsp;&nbsp;':'';	
	
	
	/* cmb string */
	$cmb_records_per_page_array = array('10','20','50','100','5000');
	foreach($cmb_records_per_page_array as $option)
	{
		$str_option_selected = ($option == $rowPerPage)?'selected':'';
		if($option == '5000'){
			$cmb_records_per_page_options .= '<option value="'.$option.'" '.$str_option_selected.'>All</option>';
		}else{
			$cmb_records_per_page_options .= '<option value="'.$option.'" '.$str_option_selected.'>'.$option.'</option>';
		}

	}
	
	$cmb_records_per_page = '
               <form action="'.$_SERVER['PHP_SELF'].'" name="frmRowPerPage" class="frmRowPerPage" method="post">
                <select name="rowPerPage">
				'.$cmb_records_per_page_options.'
                </select>
                </form>';
				//echo $cmb_records_per_page;
	
	
	

	
	global $show_paging_stat, $show_paging_combo, $show_paging_number ;
	
	//echo $show_paging_stat;
	
	$str_paging_table .= '
<div class="paging">

<div class="table">
  <div class="tr">';
  
  if($show_paging_stat != 'false'){
  $str_paging_table .= '
    <div class="td" style="white-space:nowrap;">
		'.$stat_total_items.'
		|
		'.$state_showing_range.'
    </div>';
  }
	
   if($show_paging_combo != 'false'){
	$str_paging_table .= '
    <div class="td">
    
        <div class="table">
          <div class="tr">
            <div class="td">Show</div>
            <div class="td">
				'.$cmb_records_per_page.'
            </div>
            <div class="td"> records per page</div>
          </div>
        </div>    
    
    </div>
	';
   }
   
   if($show_paging_number != 'false'){
   $str_paging_table .= '
    <div class="td">
			'.$str_display_pagination.'
    </div>';
	}
	
	$str_paging_table .= '
  </div>
</div>


</div>	
';

?>

