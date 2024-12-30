<?php
/**
 * ----------------------------------------------
 * RAK FRAMEWORK
 * Version 2
 * Last Updated: 25th December, 2013
 * Developer: Md. Abul Kashem (Raihan)
 * Email: raihan@rakplanet.com
 * ----------------------------------------------
 */
 

/*
Example:

	$table = 'table_user';
	$inputData = array(
	'table' => $table,
	'user_key' => 'email',
	'user_value' => $data_array['email'],
	'password_key' => 'password',
	'password_value' => $data_array['password'],
	'target' => 'home.php',
	'sessions' => array(
		'TABLE' => $table,
		'USER_KEY' => 'email',
		'USER' => $data_array['email'],
		'PASSWORD_KEY' => 'password',
		'PASSWORD' => $data_array['password'],
		'LOGGEDIN' => 1,
		'USER_ID' => fetchByID('table_user','email',$data_array['email'],'id'),
		'USER_TYPE' => fetchByID('table_user','email',$data_array['email'],'user_type')
		)
	
	);
		
	login($inputData,$msg);
*/
function login($inputData,&$msg)
{

	if($inputData['usertype_value'] == 1 || $inputData['usertype_value'] == 2 || $inputData['usertype_value'] == 3){
		

		global $date, $link;
				 
	
				 $password =  md5($inputData['password_value']);
				 echo $password;
				// exit();
				 
				 
				 $cntStr = 'SELECT count(*) FROM '.$inputData['table'].'';
				// echo $cntStr;
				
				 
				 $cntResult = mysqli_query($link, $cntStr);

				 //$cntResult = mysqli_fetch_array($link, $cntResult)
				 
				 $cntResult = mysqli_fetch_array($cntResult,MYSQLI_NUM) or die(mysqli_error($link));
				 //echo $cntResult[0];
				
				
				 //exit();
		
				 
				 
				 

				 
				 $str = 'SELECT * FROM '.$inputData['table'].' WHERE '.$inputData['user_key'].' ="'.$inputData['user_value'].'" AND  '.$inputData['password_key'].' ="'.$password.'"';
				 //echo $str;
				 //exit();
				 $result = mysqli_query($link,  $str)
				 or die(mysqli_error($link));
				 $record = mysqli_num_rows($result);
				// echo $record;
				 
				if($record < 1 && $cntResult[0] == 0)
				{
				
					if(($UserName == 'admin') && ($Password2 == 'admin'))
					{
						$record = 1;
						//session_register("FIRST_LOGIN");
						$_SESSION['FIRST_LOGIN'] = 1;
					}
				}
				
			  
				 if($record > 0)
				 {
					 foreach($inputData['sessions'] as $key => $value)
					 {
						 //echo $key .'-'.$value.'<br>';
						 $_SESSION[$key] = $value;
					 }
					 header('Location: '.$inputData['target']);
				 
				  }	
				else
				{
					$msg = 'Invalid Access Information';
				}	
	
	}//if($inputData['usertype_value'] == 2 || $inputData['usertype_value'] == 3){
	else
	{
		$msg = 'Access denied';
	}

} 



function company_login($inputData,&$msg)
{

	

		global $date, $link;
				 
	
				 $password =  md5($inputData['password_value']);
				 //echo $dbname;
				 
			 
				// $str = 'SELECT * FROM '.$inputData['table'].' WHERE '.$inputData['user_key'].' ="'.$inputData['user_value'].'" AND  '.$inputData['password_key'].' ="'.$password.'" AND   is_active="1"';
				 $str = 'SELECT * FROM '.$inputData['table'].' WHERE '.$inputData['user_key'].' ="'.$inputData['user_value'].'" AND  '.$inputData['password_key'].' ="'.$password.'" AND   is_email_varified="1"';
				 //echo $str;
				 ///exit();
				 $result = mysqli_query($link,  $str)
				 or die(mysqli_error($link));
				 $record = mysqli_num_rows($result);
				// echo $record;
				 
		
			  
				 if($record > 0)
				 {
					 foreach($inputData['sessions'] as $key => $value)
					 {
						 //echo $key .'-'.$value.'<br>';
						 $_SESSION[$key] = $value;
					 }
					 
					 

					  $is_active = fetchByID('company','email',$_SESSION['USER'],'is_active');
					  if($is_active == 0){ 
					  		header('location: document_manager.php?postaction=edit'.$_SESSION['FIRST_LOGIN'].''); 
					  }else{
							header('Location: '.$inputData['target']);
						}
					 
				 
				  }	
				else
				{
					
					//create fail flag on try 3
						if (isset($_SESSION['loginCount']))
						{
						   $_SESSION['loginCount']++;
						   if ($_SESSION['loginCount'] > 2)
						   {
							 //echo 'Bog Off!';
							 $_SESSION['RESET_PASS'] = 1;
							 //exit;
						   }
						} else {
						   $_SESSION['loginCount'] = 1;
						}					
					$msg = 'Invalid Access Information';
				}	
} 

function admin_login($inputData,&$msg)
{

	

		global $date, $link;
				 
	
				 $password =  md5($inputData['password_value']);
				 //echo $dbname;
				 
			 
				 $str = 'SELECT * FROM '.$inputData['table'].' WHERE '.$inputData['user_key'].' ="'.$inputData['user_value'].'" AND  '.$inputData['password_key'].' ="'.$password.'"';
				// echo $str;
				 ///exit();
				 $result = mysqli_query($link,  $str)
				 or die(mysqli_error($link));
				 $record = mysqli_num_rows($result);
				// echo $record;
				 
		
			  
				 if($record > 0)
				 {
					 foreach($inputData['sessions'] as $key => $value)
					 {
						 //echo $key .'-'.$value.'<br>';
						 if(strstr($key,"[") && strstr($key,"]")){
							 	
								$arrName = explode("[",$key);
								$arrIndex = substr($arrName[1], 0, -1);
								
								$arrName = $arrName[0];
								//echo $tkey;
								$_SESSION[$arrName][$arrIndex] = $value;
							 }else{
						 		$_SESSION[$key] = $value;
							 }
					 }
					 
					 
					 
					/*
					Special User Type
					*/
						if(!$_SESSION['USER_TYPE']){
							if(	(fetchByID('admin','email',$_SESSION['USER'][1],'user_type') == '8574111') && 
								(fetchByID('admin','email',$_SESSION['USER'][2],'user_type') == '8574111') && 
								(fetchByID('admin','email',$_SESSION['USER'][3],'user_type') == '8574111')){
								 $_SESSION['USER_TYPE'] = '8574111';
							}
						}					 
					 
										 
					 
					 
					 
					 //header('Location: '.$inputData['target']);
				 
				  }	
				else
				{
					//create fail flag on try 3
						if (isset($_SESSION['loginCount']))
						{
						   $_SESSION['loginCount']++;
						   if ($_SESSION['loginCount'] > 2)
						   {
							 //echo 'Bog Off!';
							 $_SESSION['RESET_PASS'] = 1;
							 //exit;
						   }
						} else {
						   $_SESSION['loginCount'] = 1;
						}

					
					$msg = 'Invalid Access Information';
				}	
} 


/*
Example how to call:

	$table = 'user';
	$inputData = array(
	'table' => $table,
	'user_key' => 'email',
	'user_value' => $data_array['email'],
	'password_key' => 'password',
	'password_value' => $data_array['password'],
	'usertype_key' => 'user_type',
	'usertype_value' => fetchByID('user','email',$data_array['email'],'user_type'),
	'is_validated' => fetchByID('user','email',$data_array['email'],'is_validated'),
	'target' => $target,
	'sessions' => array(
		'TABLE' => $table,
		'USER_KEY' => 'email',
		'USER' => $data_array['email'],
		'PASSWORD_KEY' => 'password',
		'PASSWORD' => md5($data_array['password']),
		'LOGGEDIN' => 1,
		'USER_ID' => fetchByID('user','email',$data_array['email'],'id'),
		'USER_TYPE' => fetchByID('user','email',$data_array['email'],'user_type')
		)
	
	);
		
	userslogin($inputData,$msg);

*/
 
function userslogin($inputData,&$msg){
	if($inputData['usertype_value']){
	
		if($inputData['is_validated'] == 'yes'){
			
			
			
		global $date,$dbname,$link;
				 
	
				 $password =  md5($inputData['password_value']);
				 //echo $dbname;
				 
				 
				 $cntStr = 'SELECT count(*) FROM '.$inputData['table'].'';
				 
				 $cntResult = mysqli_query($link, $cntStr);
				 $cntResult = mysqli_fetch_array($cntResult)
				 or die(mysqli_error($link));
				//echo $cntResult[0];
		
	
				 
				 
				// $str_usertype_existance = ($inputData['usertype_key'])?' AND  '.$inputData['usertype_key'].' ="'.$inputData['usertype_value'].'"':'';

				 
				 $str = 'SELECT * FROM '.$inputData['table'].' WHERE '.$inputData['user_key'].' ="'.$inputData['user_value'].'" AND  '.$inputData['password_key'].' ="'.$password.'" AND  '.$inputData['usertype_key'].' ='.$inputData['usertype_value'].'';
				 //echo $str;
				 //exit();
				 $result = mysqli_query($link,  $str)
				 or die(mysqli_error($link));
				 $record = mysqli_num_rows($result);
				// echo $record;
				 

			  
				 if($record > 0)
				 {
					 foreach($inputData['sessions'] as $key => $value)
					 {
						 //echo $key .'-'.$value.'<br>';
						 $_SESSION[$key] = $value;
					 }
					 if($inputData['target'] == 'ajax'){
					 	$msg = 'true';
					 }else{
						 header('Location: '.$inputData['target']);
						 }
				 
				  }	
				else
				{
					$msg = 'Invalid Access Information';
				}	
		}//if($inputData['is_validated']){
		else
		{
			$msg = 'Your account is not validated yet! Check your email to validate';
		}
	
	}//if($inputData['usertype_value'] == 2 || $inputData['usertype_value'] == 3){
	else
	{
		$msg = 'Access denied. Please register first';
	}
} 



function performaxLogin($inputData,&$msg)
{

			
	global $date,$dbname,$link;
	 

	 $password =  md5($inputData['password_value']);
	 //echo $dbname;
	 
	 
	 $cntStr = 'SELECT count(*) FROM '.$inputData['table'].'';
	 
	 $cntResult = mysqli_query($link, $cntStr);
	 $cntResult = mysqli_fetch_array($cntResult)
	 or die(mysqli_error($link));
	//echo $cntResult[0];


	 
	 
	// $str_usertype_existance = ($inputData['usertype_key'])?' AND  '.$inputData['usertype_key'].' ="'.$inputData['usertype_value'].'"':'';

	 
	 $str = 'SELECT * FROM '.$inputData['table'].' WHERE '.$inputData['user_key'].' ="'.$inputData['user_value'].'" AND  '.$inputData['password_key'].' ="'.$password.'"';
	 //echo $str;
	 //exit();
	 $result = mysqli_query($link,  $str)
	 or die(mysqli_error($link));
	 $record = mysqli_num_rows($result);
	// echo $record;
	 

  
	 if($record > 0)
	 {
		 foreach($inputData['sessions'] as $key => $value)
		 {
			 //echo $key .'-'.$value.'<br>';
			 $_SESSION[$key] = $value;
		 }
		 if($inputData['target'] == 'ajax'){
			$msg = 'true';
		 }else{
			 header('Location: '.$inputData['target']);
			 }
	  }	
	else
	{
		$msg = '<span style="color:red">Invalid Access Information</span>';
	}	


} 


?>