<?php
function detailTableView($data)
{
	global $is_edit, $is_delete, $id; 
	
	//unset($data);
//########### Start paging



	
	echo '<div class="table detailview">';
	
	
	if(count($data) > 0){
		
		
	$rows = $data['rows'];
	//print_r($columns);
	//exit;
	
	array_pop($data); //removed last $data['rows'] information from $data;		
	
	
	foreach($rows as $key => $value)
	{
		
		echo '<div class="tr">';
		if(is_array($value))
		{
			if($value['action'] == 'sectionTitle'){
				$definedClass = 'sectionTitle';
					echo '<div class="td '.$definedClass.'"><b>'.$value['column_title'].'</b></div>';
				}else{
					echo '<div class="td"><b>'.$value['column_title'].'</b></div>';
				}
			
		}
		else
		{
			echo '<div class="td"><b>'.$value.'</b></div>';
		}
		
		
		
		if(is_array($value))
		{
				//fetchByID($tblName,$QueriedByID,$QueriedByIDValue,$ReturnValue);
				
				echo '<div class="td '.$definedClass.'">';
				
				switch ($value['action'])
				{
					case 'fetchByID':
					echo fetchByID($value['from_table'],$value['find_by'],$data[$key],$value['find']);
					break;
					
					case 'fetchByIDFromArray':

					echo '<div class="list_cmb_data">';
					//echo $value['viewStyle'];
					$tag = ($value['viewStyle'] == 'list')?'li':'span';
					if(count($value['dataArray'])>0){
						foreach($value['dataArray'] as $FromArraykey => $FromArrayvalue){
							$dataKeyToList = $FromArrayvalue[$value['dataKeyToList']].'<br>';
							
							echo '<'.$tag.'>'.fetchByID($value['from_table'],$value['find_by'],$dataKeyToList,$value['find']).'</'.$tag.'>';
							
						}
					}else{ echo 'NA';}
					echo '</div>';
					break;					
					
					case 'showValue':
					echo $value['value'];
					break;
					

					case 'picture':
					echo '<img class="grid_thumb" src="'.$data[$key].'">';
					break;
					
					case 'download':
					echo '<a href="'.$data[$key].'" class="grid_download">Download</a>';
					break;
					
									
					
					case 'getHtmlBlock':
					
//					echo getHTMLBlock($value['url'],$value['start_tag'],$value['start_tag'],$value['end_tag'],$value['show_tags']);
					//echo 'I am here '.$value['url'] ;
					
					echo '
					<div class="ajaxLoadedContent" id="'.$value['ajax_id'].'">loading...</div>
					<script>
							$("#'.$value['ajax_id'].'").load("phpajax/load_htmlblock.php?url='.urlencode($value['url']).'&start_tag='.urlencode($value['start_tag']).'&end_tag='.urlencode($value['end_tag']).'&show_tags='.urlencode($value['show_tags']).'");

					</script>
										
					';
					break;
					
					case 'sectionTitle':
					
//					echo getHTMLBlock($value['url'],$value['start_tag'],$value['start_tag'],$value['end_tag'],$value['show_tags']);
					//echo 'I am here '.$value['url'] ;
					
					echo '&nbsp;';
					break;					

					case 'externalLink':

					if($value['link']){
									
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
										//echo $newparam;
										
										
										if($newparam){
											$url = $url.'?'.$newparam;
										}
										
					}else{$url = 'javascript:void()';}
						//echo $url;
					echo '<a href="'.$url.'" class="'.$value['cssClasss'].'" '.$value['attr'].'><span>'.$value['linkTitle'].'</span></a>';
					//$value['link'];	//this is to apply any function to this value; e.g. 'action'=>'formatDate' this will work as formatDate($data[$i][$key])
					$newparam = '';
					
					break;

					case 'link':
					//echo '<a href="'.$data[$key].'" class="grid_link" target="_blank">View '.$value['column_title'].'</a>';
					

					
					if(strstr($data[$key], '.com')){
							$url = formatUrl($data[$key]);
						}else{
							$url = $data[$key];
							}
					
					echo '<ul class="actions"><li><a title="View '.$value['column_title'].'" class="grid_link" href="../'.$httpPath.$url.'" target="_blank"><span>View '.$key.'<span></span></span></a></li>';
					break;	

	
					
					default:
					echo $value['action']($data[$key]);	//this is to apply any function to this value; e.g. 'action'=>'formatDate' this will work as formatDate($data[$i][$key])
					break;
					
				}
				
/*				if($value['action'] == 'fetchByID')
				{
					echo fetchByID($value['from_table'],$value['find_by'],$data[$key],$value['find']);
				}
				else if($value['action'] == 'picture')
				{
					echo '<img class="grid_thumb" src="'.$data[$key].'">';
				}
				else 
				{
					echo $value['action']($data[$key]);	//this is to apply any function to this value; e.g. 'action'=>'formatDate' this will work as formatDate($data[$i][$key])
				}*/
				
				echo '</div>';
				
				//echo '<div class="td">'.$value['from_table'].'- '.$key.'- '.$data[$i][$key].'- '.$value['find'].'</div>';
		}
		else
		{
			
			echo '<div class="td">'.$data[$key].'</div>';
		}		
		
		
		
		
		echo '</div>';
		
		$definedClass = '';
		$countLoop++;
	}


	echo '<div class="tr">';
	echo '<div class="td">';
	if($is_edit == 'true' || $is_delete == 'true'){		echo '<b>Actions</b>';}
	echo '&nbsp;</div>';
	echo '<div class="td">';
	if($is_edit == 'true'){		echo '<input type="button" name="edit" onclick="location.href=\''.$_SERVER['PHP_SELF'].'?click_action=edit&postaction=edit&id='.$id.'&view=detail\'" value="Edit">';}
	if($is_delete == 'true'){	echo '<input type="button" name="delete"  onClick="confirmDelete(\''.$_SERVER['PHP_SELF'].'?click_action=detail&postaction=delete&id='.$id.'\')"	on click="location.href=\''.$_SERVER['PHP_SELF'].'?click_action=detail&postaction=delete&id='.$id.'\'" value="Delete">';}
	echo '</div>';
	echo '</div>';

	
	
	
}
else
{
	echo '<div class="tr"><div class="td">No data found</div></div>';
}	
	
	
	echo '</div>';
	




?>

<?php
}
?>