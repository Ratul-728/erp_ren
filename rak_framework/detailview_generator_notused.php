<?php
function getField($label,$name,$type,$value,$required,$selected_id,$search_field)
{
	global $id;
	switch($type)
	{
		case 'text':
		$str_value .= ($value)?'value="'.$value.'" ':'';
		$str_value .= ($required == 'true')?'required ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><input type="text" name="'.$name.'" '.$str_value.'></div>';
		break;

		case 'file':
		$str_value = ($value)?'<div class="picture_holder"><img src="'.$value.'" height="50" id="'.$name.'" border="0"><div class="delete"><a title="Delete" href="javascript:void(0);" class="deleteAction" picture_path="'.$value.'" prdct_id="'.$id.'" picture_name="'.$name.'")"></a></div></div>':'';
		//$str_value .= ($required == 'true')?'required ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td">'.$str_value.'<input type="file" name="'.$name.'" ></div>';
		break;


		case 'textarea':
		$str_value = ($value)?$value:'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><textarea  name="'.$name.'">'.$str_value.'</textarea></div>';
		break;


		case 'select':
		//print_r($search_field);
		//echo $search_field['search_key'];

		$str_value .='<option value="">Select '.$label.'</option>';
		if(count($value)>0)
		{
			foreach($value as $option){
				$str_selected = ($selected_id == $option['id'])?'selected':'';
				$str_value .='<option value="'.$option['id'].'" '.$str_selected.'>'.$option['name'].'</option>';
				$str_selected ='';
			}
		}
		$str_required = ($required == 'true')?'required ':'';
		return '<div class="td"><label>'.$label.'</label></div><div class="td"><div class="styled-select"><select  name="'.$name.'" '.$str_required.'>'.$str_value.'</select></div></div>';
		break;
		
		
		case 'radio':
		//print_r($value);
		
		//$str_value .='<div>Choose '.$label.'</div>';
		foreach($value as $option){
			$str_selected = ($selected_id == $option['id'])?'checked':'';
			$str_value .='<span><input type="radio" name="'.$name.'" value="'.$option['id'].'" id="'.$option['id'].'" '.$str_selected.'><label for="'.$option['id'].'">'.$option['name'].'</label></span>';
			$str_selected ='';
		}
		return '<div class="td"><label>'.$label.'</label></div><div class="td">'.$str_value.'</div>';
		break;	
		
		case 'checkbox':
		//print_r($selected_id);
		
		//$str_value .='<div>Choose '.$label.'</div>';
		$isCheckedCounter = 0;
		foreach($value as $option){
			$str_selected = ($selected_id[$isCheckedCounter] == $option['id'])?'checked':'';
			$str_value .='<span><input type="checkbox" name="'.$name.'" value="'.$option['id'].'" id="'.$option['id'].'" '.$str_selected.'><label for="'.$option['id'].'">'.$option['name'].'</label></span>';
			$str_selected ='';
			$isCheckedCounter++;
		}
		return '<div class="td"><label>'.$label.'</label></div><div class="td">'.$str_value.'</div>';
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
	
	echo '<form '.$formname.' '.$action.' '.$method.' '.$enctype.' > ';
	
	
	
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
		if($_value['type'] != 'hidden'){echo '<div class="tr">';}
		echo getField($_value['label'],$_value['name'],$_value['type'],$_value['value'],$_value['required'],$_value['selected_id'],$_value['search_field']);
		if($_value['type'] != 'hidden'){echo '</div>';}
		
		
		
		if($debug==1){
		echo '<hr>';
		}
	}
	echo '</div>';
	echo '</form>';
}

?>