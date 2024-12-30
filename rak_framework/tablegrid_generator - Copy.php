<?php
/*
Code Version: 1.0; 
Last Updated: 29th January, 2014;
Developer: Raihan Abul Kashem (raihan@rakplanet.com)
Cell: 8801715302662
*/
//print_r($_REQUEST);
function generateTableGrid($data,$rowPerPage)
{

	global $is_detail, $is_edit, $is_delete, $is_paging,$is_return, $module;
	//echo ' - '.$module.' - ';

	//print_r($data);

//########### Start paging



	if($is_paging == 'false')
	{
		$pageIndex =1;
		$startIndex=1;
		$totalRecords = count($data)-1;
		$endIndex = $totalRecords;
		$totalPages = 1;
		require_once('pagination.php');
	}else{
		require_once('pagination.php');
	}


			
			// echo $str_display_pagination;
           
	
	
	//print_r($data);
	
	if($totalRecords > 0){
		if($is_paging != 'false'){
		echo '<div class="grid">';
		echo $str_paging_table;
		echo '</div>';
		}
	}
	
//echo $totalRecords;
	




//filter
	$columns = $data['columns'];
	
	
	
	
	//print_r($columns);


	echo '<div class="grid">';


	//################################	filter 
	
	if(search_array("searchBox", $columns)){
		
		foreach($columns as $key => $value){
			
			if($value['column_to_search']){
				$searchBox = '<div class="th" style="width:5%;padding-left:2px;">'.$value['column_title'].':</div><div class="th" style="width:10%;padding-left:0;padding-right:0;"> <input type="text" name="'.$value['column_to_search'].'"style="width:100px;"></div><div class="th" style="width:5%;padding-left:2px;"><input type="submit" value="Go"  style="height:28px"></div>';
			}
			
			
			//$searchBox .= $value['column_to_search'];
			
		}
			
	}
	
	

	
	
	if(search_array("fetchByID", $columns))
	{


//find if filter arrayData exists
//print_r($data);

	if(findKey($data, "dataArray")){

		echo  '<form  action="'.$_SERVER['PHP_SELF'].'" name="ccmb_filter" class="ccmb_filter" method="post" enctype="multipart/form-data" style="display:block;width:100%; outline: none; border:0px solid #000; outline-width: 0;">';
	
		echo '<div class="table filter">';
		
		
		echo '<div class="tr">';
		
		echo '<div class="th" style="width:30%;"> Filter by:</div>';
		echo $searchBox;
		
		foreach($columns as $key => $value)
		{
			if(is_array($value))
			{
				
				
				
				/* getByMonthYear  */
				
				//print_r($value['customQuery']);
				if($value['customQuery']['action'] == 'getByMonthYear')
				{
					//print_r($value['dataArary']);
					//sort($value['customQuery']['dataArary']);
					foreach($value['customQuery']['dataArary'] as $cqvalue)
					{
						$keys   =   array_keys($cqvalue);
						//echo date("F", strtotime($value['date'])).'<br>';
						$monthArray[date("n", strtotime($cqvalue[$keys[0]]))] = date("F", strtotime($cqvalue[$keys[0]]));
						ksort($monthArray);
						//echo $value['date'].'<br>';
						//print_r($monthArray);
					}
					//echo $value['customQuery']['selected_id'];
					echo '
						<div class="th" style="border:0px solid #ccc; white-space:nowrap!important;">
	
							<div class="table tbl_nasted1" style="margin:0px;">
								<div class="tr">
									<div class="td" style="padding:0px 5px;">Month</div>
									<div class="td" style="padding:0px 5px;">
	
	
							<div class="styled-select"  style="width:120px">
							<select name="month_year" id="month_year"  onchange="this.form.submit();">
							  <option  value="">All Months</option>
							';
								  
								 $year = date("Y");
								
							   foreach($monthArray as $key => $mvalue)
								  {
								   if(trim($key.'_'.$year) == trim($value['customQuery']['selected_id'])){ $isSelected = 'selected'; }
								   
								   echo '<option value="'.$key.'_'.$year.'" '.$isSelected.'>'.$mvalue.'</option> ';
								   
								   $isSelected = '';
								  }
								  
							 
							echo '</select>
							</div>
	
	
	</div>
	</div>
	</div>
	
	
						</div>';
	
				}
				
				/* end getByMonthYear  */
				
				if($value['action'] == 'fetchByID')
				{
					if(count($value['dataArray'])>0){
					
					//echo '<div class="th">go go</div>';
				
					//$str_value .='<option value="">'.$value['column_title'].'</option>';	
					$str_value .='<option value="">Show All</option>';	
					foreach($value['dataArray'] as $option){
						
				$keys   =   array_keys($option);
	//			print_r($keys);					
						
						$str_selected = ($value['selected_id'] == $option[$keys[0]])?' selected="selected" ':'';
						//$str_value .='<option value="'.$option['id'].'" '.$str_selected.'>'.$option['name'].'</option>';
						$str_value .='<option value="'.$option[$keys[0]].'" '.$str_selected.'>'.$option[$keys[1]].'</option>';
						$str_selected ='';
						$strlen[$key][] = strlen($option[$keys[1]]);
						
					}
					$maxNumber = max($strlen[$key]);
	
					$divWidth = ($maxNumber*8)-20;
					if($divWidth<100){$divWidth = 100;}
					$selectWidth = $divWidth+70;
					
					//echo $divWidth.'<br>';
					
					$str_required = ($required == 'true')?'required ':'';
					echo '<div class="th" style="border:0px solid #ccc; white-space:nowrap!important;">
							<div class="table tbl_nasted1" style="margin:0px;">
								<div class="tr">
									<div class="td" style="padding:0px 5px;">'.$value['column_title'].'</div>
									<div class="td" style="padding:0px 5px;">
													<div class="styled-select '.$value['name'].'"  style="width:'.$divWidth.'px">
														<select   name="'.$value['name'].'" '.$str_required.' onchange="this.form.submit()"  style="width:'.$selectWidth.'px">'.$str_value.'</select>
													</div>
									</div>
								</div>
							</div>
						</div>';
					$str_value = '';
					}//if(count($value['dataArray'])>0){
				}//if($value['action'] == 'fetchByID')
	
			}
	
		}
		
		//echo '<div class="th" style="width:10%;">&nbsp;</div>';	
		
		echo '</div>'; //.tr
		echo '</div>'; //.table
	
		echo '</form>';	

		}//if(search_array("dataArray", $columns)){	

	}
	

//################################ end filter
//print_r($strlen);


	
	
	
	echo '<div class="table">';
	
	
	
		
		
	
	//print_r($columns);
	//exit;
	
	array_pop($data); //removed last column information from $data;		
	//print_r($data);
	
	echo '<div class="tr">';
	
	foreach($columns as $key => $value)
	{
		if(is_array($value))
		{
			
			
			if($value['action'] == 'sortable' || $value['action'] == 'formatDate')
			{
				if($key == $value['orderby']){
					echo '<div class="th"><a href="'.$_SERVER['PHP_SELF'].'?asdsorder='.$value['asdsorder'].'&orderby='.$key.'&pageIndex='.$_SESSION['pageIndex'].'&rowPerPage='.$_SESSION['rowPerPage'].'" class="order_'.$value['asdsorder'].'">'.$value['column_title'].'</a></div>';
				}else{
					echo '<div class="th"><a href="'.$_SERVER['PHP_SELF'].'?asdsorder='.$value['asdsorder'].'&orderby='.$key.'&pageIndex='.$_SESSION['pageIndex'].'&rowPerPage='.$_SESSION['rowPerPage'].'" >'.$value['column_title'].'</a></div>';
				}
			}
			else if($value['action'] == 'formatDate')
			{
				if($key == $value['orderby']){
				echo '<div class="th"><a href="'.$_SERVER['PHP_SELF'].'?asdsorder='.$value['asdsorder'].'&orderby='.$key.'&pageIndex='.$_SESSION['pageIndex'].'&rowPerPage='.$_SESSION['rowPerPage'].'" class="order_'.$value['asdsorder'].'">'.$value['column_title'].'</a></div>';
				}else{
				echo '<div class="th"><a href="'.$_SERVER['PHP_SELF'].'?asdsorder='.$value['asdsorder'].'&orderby='.$key.'&pageIndex='.$_SESSION['pageIndex'].'&rowPerPage='.$_SESSION['rowPerPage'].'">'.$value['column_title'].'</a></div>';
				}

			}
			
			

			else if($value['action'] == 'fetchByID' && $value['asdsorder'])
			{
				if($key == $value['orderby']){
				echo '<div class="th"><a href="'.$_SERVER['PHP_SELF'].'?asdsorder='.$value['asdsorder'].'&orderby='.$key.'&pageIndex='.$_SESSION['pageIndex'].'&rowPerPage='.$_SESSION['rowPerPage'].'" class="order_'.$value['asdsorder'].'">'.$value['column_title'].'</a></div>';
				}else{
				echo '<div class="th"><a href="'.$_SERVER['PHP_SELF'].'?asdsorder='.$value['asdsorder'].'&orderby='.$key.'&pageIndex='.$_SESSION['pageIndex'].'&rowPerPage='.$_SESSION['rowPerPage'].'">'.$value['column_title'].'</a></div>';
				}

			}else
			{
				echo '<div class="th">'.$value['column_title'].'</div>';	
			}

		}
		else
		{
			echo '<div class="th">'.$value.'</div>';
		}
	}
	
	if($is_detail == 'true' ||	$is_edit == 'true' || $is_delete == 'true'){	
		echo '<div class="th">Actions</div>';
	}
	
	
	echo '</div>'; //.tr
	
	
	// data looping started here;;;;;
	
	
	
	if($totalRecords > 0){

	for ($i=$startIndex; $i<$endIndex; $i++) {


		echo '<div class="tr">';
		
		foreach($columns as $key => $value)
		{
			
			
			if(is_array($value))
			{
					//fetchByID($tblName,$QueriedByID,$QueriedByIDValue,$ReturnValue);
					
					if($value['cssClasss'])
					{
						
						
						
						//print_r($value['cssClasss']);
						
						
						if(is_array($value['cssClasss']))
						{
							foreach($value['cssClasss'] as $cssValue)
							{
								if(strstr($cssValue,"key_and_value_of_")){
									$datakey = substr($cssValue, strlen("key_and_value_of_"));
									$class = ' '.$datakey.'_'.$data[$i][$datakey].' ';
								}else if(strstr($cssValue,"value_of_")){
									$datakey = substr($cssValue, strlen("value_of_"));
									$class .= ' '.$data[$i][$datakey].' ';									
								}else{
									$class .= ' '.$cssValue.' ';
								}
							}
							
							echo '<div class="td '.$class.'">';
							
						}
						else
						{
						
								if(strstr($value['cssClasss'],"key_and_value_of_")){
									$datakey = substr($value['cssClasss'], strlen("key_and_value_of_"));
									$class = $datakey.'_'.$data[$i][$datakey];
									echo '<div class="td '.$class.'">';
								}elseif(strstr($value['cssClasss'],"value_of_")){
									$datakey = substr($value['cssClasss'], strlen("value_of_"));
									$class = $data[$i][$datakey];
									echo '<div class="td '.$class.'">';
								}else{
									echo '<div class="td '.$value['cssClasss'].'">';
								}
						}
						$class = '';
						
					}else{
					echo '<div class="td">';
					}
					



					
				switch ($value['action'])
				{
					case 'fetchByID':
					echo fetchByID($value['from_table'],$value['find_by'],$data[$i][$key],$value['find']);
					break;
					
					case 'searchBox':
					echo $data[$i][$key];
					break;
					
					case 'picture':
					echo '<img class="grid_thumb" src="'.$data[$i][$key].'">';
					break;
					
					case 'sortable':
					echo $data[$i][$key];
					break;				
					
					
					case 'download':
					//echo '<a href="'.$data[$i][$key].'" class="grid_download">Download</a>';
					echo '<ul class="actions"><li><a title="Download" class="grid_download" href="'.$data[$i][$key].'"><span>Download<span></span></span></a></li>';
					
					break;
					
					case 'link':
					//echo '<a href="'.$data[$i][$key].'" class="grid_link" target="_blank">Open</a>';
					if(strstr($data[$i][$key], '.com')){
							$url = formatUrl($data[$i][$key]);
						}else{
							$url = $data[$i][$key];
							}
					
					echo '<ul class="actions"><li><a title="Open" class="grid_link" href="'.$url.'" target="_blank"><span>Open<span></span></span></a></li>';
					break;					
					
					case 'defineCssInRow':
					
					//totally customized only for this project, please remove this part for all other project unless this type;
					if($data[$i][$key] > 0){
						echo '<span>'.$data[$i][$key].' P</span>';
						}else{
							$printable = str_replace('-', '', $data[$i][$key]);
						echo '<span>'.$printable.' L</span>';	
						}
						
					break;
					
					
					case 'defineCss':
						echo '<span>'.$data[$i][$key].'</span>';
					break;					


					case 'defineCssAsKeyAndVal':
					
					//totally customized only for this project, please remove this part for all other project unless this type;
					if($value['from_table']){
						
							echo fetchByID($value['from_table'],$value['find_by'],$data[$i][$key],$value['find']);
							
						}else{
						echo '<span>'.$data[$i][$key].'</span>';
						}
						
					break;


					case 'externalLink':

	
						if(strstr($value['link'],"?")){
							
							$lnk = explode("?",$value['link']);
							$url = $lnk[0];
							$paramstr = $lnk[1];
						}
						$params = explode("&",$paramstr);

						foreach ($params as $para){

							$kv = explode("=",$para);
							
							if(strstr($kv[1],'value_of_'))
							{
								$datakey = substr($kv[1], strlen("value_of_"));
								$newparam .= $kv[0].'='.$data[$i][$datakey].'&';
							}else{
								$newparam .= $kv[0].'='.$kv[1].'&';
							}
						}
						$newparam = substr($newparam,0, -1);
//						echo $newparam;
						
						
						if($newparam){
							$url = $url.'?'.$newparam;
						}
						//echo $url;
					echo '<a href="'.$url.'"><span>'.$value['linkTitle'].'</span></a>';
					//$value['link'];	//this is to apply any function to this value; e.g. 'action'=>'formatDate' this will work as formatDate($data[$i][$key])
					$newparam = '';
					
					break;
									
					case 'blockUnblock':
					break;					
					
					
					case 'serial':
					echo $i+1;
					break;						
					
					
					default:
					echo $value['action']($data[$i][$key]);	//this is to apply any function to this value; e.g. 'action'=>'formatDate' this will work as formatDate($data[$i][$key])
					break;										
					
				}					
					
					
					
					echo '</div>';
					
					//echo '<div class="td">'.$value['from_table'].'- '.$key.'- '.$data[$i][$key].'- '.$value['find'].'</div>';
			}
			else{
				
				echo '<div class="td">'.$data[$i][$key].'</div>';
			}			
			
		
			
		}
		
	if($is_detail == 'true' ||	$is_edit == 'true' || $is_delete == 'true'){	
	
			
			echo '<div class="td"><ul class="actions">';
			//echo .$data[$i]['id'].'</div>';



			if(isset($value['action'])){
			if($value['action'] == 'blockUnblock')
			{
				
				$block_action = $value['blockaction'];
				$column = $value['column'];
		
				if($data[$i][$column] == $value['action_val_1'])
				{
					$block_action = $value['action_val_2'];
					$css_class = 'unblock';
					$title = $value['action_title_1'];
					
				}
				else
				{
					$block_action = $value['action_val_1'];
					$css_class = 'block';
					$title = $value['action_title_2'];
				}
	
					//echo '<li><a title="'.$title.'" href="'.$_SERVER['PHP_SELF'].'?postaction=block&id='.$data[$i]['id'].'&action='.$block_action.'" class="'.$css_class.'"><span>Detail<span></a></li>';
					echo '<li><a title="'.$title.'" href="'.$module.'_manager.php?postaction=block&id='.$data[$i]['id'].'&action='.$block_action.'" onclick="return confirm(\'Are you sure you want to '.strtolower($title).'  this record?\')" class="'.$css_class.'"><span>Detail<span></a></li>';
					$block_action = '';
			}
			}
			
			
			
			if($is_detail != 'false'){
				//echo '<li><a title="Detail" href="'.$_SERVER['PHP_SELF'].'?click_action=detail&postaction=detail&id='.$data[$i]['id'].'" class="detail"><span>Detail<span></a></li>';
				echo '<li><a title="Detail" href="'.$module.'_manager.php?click_action=detail&postaction=detail&id='.$data[$i]['id'].'" class="detail"><span>Detail</span></a></li>';
			}
			if($is_edit != 'false'){
				//echo '<li><a title="Edit" href="'.$_SERVER['PHP_SELF'].'?click_action=edit&postaction=edit&id='.$data[$i]['id'].'" class="edit"><span>Edit<span></a></li>';
				echo '<li><a title="Edit" href="'.$module.'_manager.php?click_action=edit&postaction=edit&id='.$data[$i]['id'].'" class="edit"><span>Edit</span></a></li>';
			}
			if($is_delete != 'false'){
				//echo '<li><a title="Delete" href="'.$_SERVER['PHP_SELF'].'?postaction=delete&id='.$data[$i]['id'].'" onclick="return confirm(\'Are you sure you want to delete this record?\')" class="delete"><span>Delete<span></a></li>';
				echo '<li><a title="Delete" href="'.$module.'_manager.php?postaction=delete&id='.$data[$i]['id'].'" onclick="return confirm(\'Are you sure you want to delete this record?\')" class="delete"><span>Delete</span></a></li>';
			}
			echo '</ul></div>';
		

	}
		
		
		echo '</div>';
	


	}//if($is_detail == 'true' ||	$is_edit == 'true' || $is_delete == 'true'){	
	
}	//if($totalRecords > 0){
else
{
	echo '<div>
			<div style="padding:7px;color:#B8281F;">No record found</div>
		  </div>';
}	
	
	
	echo '</div>'; //.table
echo '</div>';	//.gird
	




	if($totalRecords > 0){
		if($is_paging != 'false'){
		echo '<div class="grid">';
		echo $str_paging_table;
		echo '</div>';
		}
	}
	
	unset($data);
	unset($_SESSION);
}
?>