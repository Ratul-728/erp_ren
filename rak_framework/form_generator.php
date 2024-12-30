<?php
/*
	
	Varsion: 1.5.1;
	Last updated: Jan 20 2015

	Modified:
	1. Rows attr added in textarea
	
	******	
	
	
	Varsion: 1.5;
	Last updated: Dec 17 2014

	Modified:
	1. Disabled field added
	
	******	
	
	Last updated: 21st April 2014
	raihan@rakplanet.com
*/

//function getField($label,$name,$type,$value,$required,$selected_id, $search_field,$is_add_new,$upload_path,$disabled,$rows)
function getField($allParams)
{
	extract($allParams);
	global $id, $table, $module, $click_action, $postaction, $ckeincluded;
	switch($type)
	{
		case 'text':
		$str_value .= ($value)?'value="'.$value.'" ':'';
		$str_value .= ($required == 'true')?'required ':'';
		$str_value .= ($disabled == 'true')?'disabled ':'';
		$str_value .= ($css)?$css:'';
		$str_value .= ($css_class)?'class="'.$css_class.'"':'';
		$str_text_nextto_this = ($text_nextto_this)?' <label class="text_nextto_this">'.$text_nextto_this.'</label>':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><input type="text" name="'.$name.'" '.$str_value.'>'.$str_text_nextto_this.'</div>';
		break;

		case 'password':
		$str_value .= ($value)?'value="'.$value.'" ':'';
		$str_value .= ($required == 'true')?'required ':'';
		$str_value .= ($disabled == 'true')?'disabled ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><input type="password" name="'.$name.'" '.$str_value.'></div>';
		break;


		case 'email':
		$str_value .= ($value)?'value="'.$value.'" ':'';
		$str_value .= ($required == 'true')?'required ':'';
		$str_value .= ($disabled == 'true')?'disabled ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><input type="email" name="'.$name.'" '.$str_value.'></div>';
		break;

		
		case 'date':
		$str_value .= ($value)?'value="'.$value.'" ':'';
		$str_value .= ($required == 'true')?'required ':'';
		$str_value .= ($disabled == 'true')?'disabled ':'';

		$str_jqsnipps = '<script> $(function() {$("#datepicker_'.$name.'" ).datepicker({  dateFormat: "yy-mm-dd",	  changeMonth: true,  changeYear: true,	});});</script>';

		return $str_jqsnipps.' <div class="td"><label>'.$label.'</label></div><div class="td"><input type="text" name="'.$name.'" '.$str_value.' id="datepicker_'.$name.'"></div>';
		break;		



		case 'datetime':
		$str_value .= ($value)?'value="'.$value.'" ':'';
		$str_value .= ($required == 'true')?'required ':'';
		$str_value .= ($disabled == 'true')?'disabled ':'';

		$str_jqsnipps = '<script> $("#datetimepicker_'.$name.'").datetimepicker({	format: "Y-m-d H:i:00",  formatTime:"h:i a",});</script>';

		return ' <div class="td"><label>'.$label.'</label></div><div class="td"><input type="text" name="'.$name.'" '.$str_value.' id="datetimepicker_'.$name.'"></div>'.$str_jqsnipps;
		break;	



		case 'file':
		

				
		if($postaction == 'edit'){
			
			$ext = explode('.', basename($value));
			$ext = strtolower($ext[1]);
			
			if($ext == 'jpg' || $ext == 'gif' || $ext == 'png'){$picture_url = $value;}
			
			
			if($picture_url){
				$str_value = ($value)?'
					<div class="picture_holder">
						<ul>
							<li class="view"><a title="View" href="'.$value.'" target="_blank"><img src="'.$picture_url.'" wi dth="50" id="'.$name.'" border="0"></a></li>
							<li class="delete"><a title="Delete" href="javascript:void(0);" class="deleteAction" picture_path="'.$value.'" prdct_id="'.$id.'" picture_name="'.$name.'")"></a></li>
						</ul>
					</div>':'';
			}
			else
			{
				$str_value = ($value)?'
					<div class="picture_holder">
						<ul>
							<li class="view"><a title="View" href="'.$value.'" target="_blank">&nbsp;</a></li>
							<li class="delete"><a title="Delete" href="javascript:void(0);" class="deleteAction" picture_path="'.$value.'" prdct_id="'.$id.'" picture_name="'.$name.'")"></a></li>
						</ul>
					</div>':'';
					
			}
		}
	
		$isrequired = ($required == 'true')?'required ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><input type="file" name="'.$name.'" '.$isrequired.' >'.$str_value.'</div>';
		break;


		case 'textarea':
		$isrequired = ($required == 'true')?'required ':'';
		$str_value = ($value)?$value:'';
		$disabled = ($disabled == 'true')?'disabled ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><textarea '.$disabled.'  name="'.$name.'" '.$isrequired.' rows="'.$rows.'">'.$str_value.'</textarea></div>';
		break;
		
		
		case 'rich_textarea':
		
			
		if($ckeincluded != 1){

			$_SESSION['KCFINDER']['disabled'] = false; // enables the file browser in the admin
			$_SESSION['KCFINDER']['uploadURL'] = '../'.$upload_path; // URL for the uploads folder
			$_SESSION['KCFINDER']['uploadDir'] = '../'.$upload_path; // path to the uploads


			$str_richtext_jssnipp1 ='<script type="text/javascript" src="../rak_framework/ckeditor/ckeditor.js"></script>';
			$str_richtext_jssnipp1 .='<script type="text/javascript" src="../rak_framework/ckeditor/config.js"></script>';
			
			$ckeincluded = 1;
		}

		$str_richtext_jssnipp2 ='
		<script type="text/javascript">
			CKEDITOR.replace( "'.$name.'", {
				 filebrowserBrowseUrl: "kcfinder/browse.php?type=files",
				 filebrowserImageBrowseUrl: "../rak_framework/kcfinder/browse.php?type=images",
				 filebrowserFlashBrowseUrl: "../rak_framework/kcfinder/browse.php?type=flash",
				 filebrowserUploadUrl: "../rak_framework/kcfinder/upload.php?type=files",
				 filebrowserImageUploadUrl: "../rak_framework/kcfinder/upload.php?type=images",
				 filebrowserFlashUploadUrl: "../rak_framework/kcfinder/upload.php?type=flash"
			});
		</script>		
		';
		
		$isrequired = ($required == 'true')?'required ':'';
		$str_value = ($value)?$value:'';
		return $str_richtext_jssnipp1.' <div class="td"><label>'.$label.'</label></div><div class="td"><textarea '.$isrequired.'  name="'.$name.'" id="'.$name.'">'.$str_value.'</textarea> '.$str_richtext_jssnipp2.' </div>';
		break;		


		case 'select':
		//print_r($search_field);
		//echo $search_field['search_key'];
		
		$str_is_add_new = ($is_add_new == 'true')?'<div style="float:left; position:absolute;right:44%"><ul class="actions"><li style="padding-top:6px;"><a title="Add New" href="'.$name.'_manager.php?click_action=edit&postaction=insert&return='.$module.'||'.$click_action.'||'.$postaction.'||'.$id.'" class="addnew"><span>Add New</span></a></li></ul></div>':'';
		
		$str_value .='<option value="">Select '.$label.'</option>';
		if(count($value)>0)
		{
			//print_r($value);
			
			

			
			foreach($value as $option){
			
			$keys   =   array_keys($option);
//			print_r($keys);

				
				$str_selected = ($selected_id == $option['id'])?'selected':'';
				//$str_value .='<option value="'.$option['id'].'" '.$str_selected.'>'.$option['name'].'</option>';
				$str_value .='<option value="'.$option[$keys[0]].'" '.$str_selected.'>'.$option[$keys[1]].'</option>';
				$str_selected ='';
			}
		}
		
		$str_required = ($required == 'true')?'required ':'';
		$disabled = ($disabled == 'true')?'disabled ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><div style="position:relative;  border:0px solid #ccc;	"><div class="styled-select">'.$str_is_add_new.'<select  name="'.$name.'" '.$str_required.' '.$disabled.'>'.$str_value.'</select></div></div></div>';
		break;
		
		
		case 'radio':
		//print_r($value);
		
		//$str_value .='<div>Choose '.$label.'</div>';
		foreach($value as $option){
			$str_selected = ($selected_id == $option['id'])?'checked':'';
			$str_value .='<span><input type="radio"  name="'.$name.'" value="'.$option['id'].'" id="'.$option['name'].'_'.$option['id'].'" '.$str_selected.'><label for="'.$option['name'].'_'.$option['id'].'">'.$option['name'].'</label></span>';
			$str_selected ='';
		}
		return '<div class="td"><label>'.$label.'</label></div><div class="td radio">'.$str_value.'</div>';
		break;	
		
		case 'checkbox':
		//print_r($selected_id);
		
		//$str_value .='<div>Choose '.$label.'</div>';
		$isCheckedCounter = 0;
		//echo '<hr>';
		//print_r($selected_id);
		//echo '<hr>';
		foreach($value as $option){
			
			if(is_array($selected_id)){
				$str_selected = (in_array($option['id'],$selected_id))?'checked':'';
			}else{
				$str_selected = ($selected_id[$isCheckedCounter] == $option['id'])?'checked':'';
			}
			
			if(strtolower($option['name']) == strtolower('Select-All') || strtolower($option['name']) == strtolower('Select All')){
				$id_name = substr($name,0,-2).'_all';
				$css_class = substr($name,0,-2);
				$chk_value = '';
			}else{
				$css_class = substr($name,0,-2);
				$id_name = substr($name,0,-2).'_'.$option['id'];
				$chk_value = $option['id'];
			}
			$str_value .='<span><input type="checkbox" class="'.$css_class.'" name="'.$name.'" value="'.$chk_value.'" id="'.$id_name.'" '.$str_selected.'><label for="'.$id_name.'">'.$option['name'].'</label></span>';
			$str_selected ='';
			$isCheckedCounter++;
		}
		return '<div class="td '.$css_class.' checkbox"><label>'.$label.'</label></div><div class="td  '.$css_class.' checkbox">'.$str_value.'</div>';
		break;			
		
		case 'hidden':
		$str_value = ($value)?'value="'.$value.'"':'';
		return '<input type="hidden" name="'.$name.'" '.$str_value.'>';
		break;
		
		
		case 'button':
		//print_r($value);
		
		
		foreach($value as $button){
			
			$str_onclick .= ($button['onclick'])?'onclick="javascript:'.$button['onclick'].'"':'';
			$str_css_class .= ($button['css_class'])?'class="'.$button['css_class'].'"':'';
			$str_btn_id .= ($button['btn_id'])?'id="'.$button['btn_id'].'"':'';
			$str_value .='<span><input type="'.$button['btn_type'].'" name="'.$button['btn_name'].'" value="'.$button['btn_value'].'"  '.$str_css_class.' '.$str_btn_id.' '.$str_onclick.' ></span>';
			$str_onclick ='';
			$str_css_class ='';
			$str_btn_id ='';
			
		}
		return '<div class="td"><label>'.$label.'</label></div><div class="td">'.$str_value.'</div>';
		break;			
		
	}
}

function generateForm($form)
{
	$formname = ($form[0]['formname'])	?	'name="'.$form[0]['formname'].'"':'';
	$action = ($form[0]['action'])		?	'action="'.$form[0]['action'].'"':'';
	$method = ($form[0]['method'])		?	'method="'.$form[0]['method'].'"':'';
	$enctype = ($form[0]['enctype'])	?	'enctype="'.$form[0]['enctype'].'"':'';
	
	echo '<form '.$formname.' '.$action.' '.$method.' '.$enctype.' id="'.$form[0]['formname'].'" > ';
	
	
	
	$form = array_slice($form, 1); //removed form information from $form[0];
	
	echo '<div class="table">';
	foreach($form as $_value)
	{
		if($debug==1){
		echo $_value['label'];
		echo '--';
		echo $_value['name'];
		echo '--';
		echo $_value['type'];
		echo '--';
		}
		//print_r($_value['selected_array']);
		
		if($_value['type'] == 'sectionTitle'){
			echo '<div class="tr"><div class="td sectionTitle">';
			echo '<strong>'.$_value['label'].'</strong>';
			echo '</div><div class="td sectionTitle"></div></div>';
		}else{
		
		if($_value['type'] != 'hidden'){echo '<div class="tr">';}
		
			//echo getField($_value['label'],$_value['name'],$_value['type'],$_value['value'],$_value['required'],$_value['selected_id'],$_value['search_field'],$_value['is_add_new'],$_value['upload_path'],$_value['disabled'],$_value['rows']);
		
			echo getField($_value);
		
		if($_value['type'] != 'hidden'){echo '</div>';}
		
		}
		
		if($debug==1){
		echo '<hr>';
		}
	}
	echo '</div>';
	echo '</form>';
}

?>