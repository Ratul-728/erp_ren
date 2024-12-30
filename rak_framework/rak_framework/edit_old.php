<?php
/**
 * ----------------------------------------------
 * Loreeto Updating Functionalities.
 * Version 0.1
 * Developer: Md. Abul Kashem (Raihan)
 * Email: raihanak@yahoo.com
 * ----------------------------------------------
 */
 
function updateUser($id,$UserName,$Password,$Email,&$msg,&$success)
{	
global $date,$dbname,$link;
	 
	 
	$Password =  md5($Password);
	$str = 'UPDATE `user` SET `UserName` = "'.$UserName.'",
							  `Password` = "'.$Password.'",
							  `Email` = "'.$Email.'"
		  WHERE `ID` = '.$id.'';
	//echo $str;
	
		$result = mysqli_query($link,  $str);
		 //or die("Invalid query: " . mysqli_error($link));
		if($result)
		{
		$msg = '<b><font face="arial" size=2 color=green>'.$UserName.'s  user info updated successfully</b></font>';
		$success = 1;
		}
		else
		{
		$msg = '<b><font face="arial" size=2 color=red>Could not update '.$UserName.'s user info</b></font>';
		}
}

//Image Updater // $RowIDName,$RowIDValue are Row ID and Its Value
function updateSingleImage($tblName,$ColumnName,$RowIDName,$RowIDValue,$_FILES,&$msg,&$Success)
{
		//Store image data in string;
	if($_FILES['photograph']['name'] != '')
	{
		if($_FILES[$ColumnName]['type']=='image/gif' || $_FILES[$ColumnName]['type']=='image/pjpeg' || $_FILES[$ColumnName]['type']=='image/x-png')
		{
			if($_FILES[$ColumnName]['tmp_name'])
			{
				$filename = $_FILES[$ColumnName]['tmp_name'];
				$handle = fopen($filename, "r");
				$ImgContents = fread($handle, filesize($filename));
				fclose($handle);
				
				//update db
				// common db info
				global $date,$dbname,$link;
				
				$str = 'UPDATE `'.$tblName.'` SET `'.$ColumnName.'` = "'.mysql_escape_string($ImgContents).'" WHERE `'.$RowIDName.'` = '.$RowIDValue.'';
				//echo $str;
						$result = mysqli_query($link,  $str)
						 or die(mysqli_error($link));
						if($result)
						{
						$msg = '<b><span class="message"><font face="arial" size=2 color=green>Image updated successfully</span></b></font>';
						$success = 1;
						}
						else
						{
						$msg = '<b><span class="message"><font face="arial" size=2 color=red>Could not update image</font></span></b>';
						$success = 0;
						}
			}
		}
		else
		{
			$msg = '<b><span class="message"><font face="arial" size=2 color=red>Image format "'.$_FILES[$ColumnName]['type'].'" not supported</b></span>';
			$uploadError = 1;
		}
	}//if($_FILES['MemberPhoto'][name] != '')
	//End image storing;
}

function updateStudent($studentid,$sid,$classid,$firstname,$lastname,$dob,$gender,$fathername,$mothername,$address,$city,$nationality,$postalcode,$phonenum1,$phonenum2,$mobile,$bloodgroup,$email,$guardianname,$dateenroll,$roll,$previousschool,$lastclass,$notes,&$msg,&$success)
{	
	// common db info
	global $date,$dbname,$link;
	
	
	$str = 'UPDATE `tblstudents` SET 
								`sid` = "'.$sid.'",
								`classid` = "'.$classid.'",
								`firstname` = "'.$firstname.'",
								`lastname` = "'.$lastname.'",
								`dob` = "'.$dob.'",
								`gender` = "'.$gender.'",
								`fathername` = "'.$fathername.'",
								`mothername` = "'.$mothername.'",
								`address` = "'.$address.'",
								`city` = "'.$city.'",
								`nationality` = "'.$nationality.'",
								`postalcode` = "'.$postalcode.'",
								`phonenum1` = "'.$phonenum1.'",
								`phonenum2` = "'.$phonenum2.'",
								`mobile` = "'.$mobile.'",
								`bloodgroup` = "'.$bloodgroup.'",
								`email` = "'.$email.'",
								`guardianname` = "'.$guardianname.'",
								`dateenroll` = "'.$dateenroll.'",
								`roll` = "'.$roll.'",
								`previousschool` = "'.$previousschool.'",
								`lastclass` = "'.$lastclass.'",
								`notes` = "'.$notes.'"
			
		  	WHERE `studentid` = '.$studentid.'';
			//echo "ID ".$id;
	
		$result = mysqli_query($link,  $str)
		 or die(mysqli_error($link));
		if($result)
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=green>Student data updated successfully</span></b></font>';
		$success = 1;
		}
		else
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=red>Could not update Student data</font></span></b>';
		}
}

function updateEmployee(&$employeeid,&$firstname,&$lastname,&$title,&$emailname ,&$address,&$city,&$postalcode,&$country,&$homephone,&$mobile,&$birthdate,&$datehired,&$salary,&$spousename,&$notes)
{	
	// common db info
	global $date,$dbname,$link;
	
	
	$str = 'UPDATE `tblemployees` SET 
								`firstname` = "'.$firstname.'",
								`lastname` = "'.$lastname.'",
								`title` = "'.$title.'",
								`emailname` = "'.$emailname.'",
								`address` = "'.$address.'",
								`city` = "'.$city.'",
								`postalcode` = "'.$postalcode.'",
								`country` = "'.$country.'",
								`homephone` = "'.$homephone.'",
								`mobile` = "'.$mobile.'",
								`birthdate` = "'.$birthdate.'",
								`datehired` = "'.$datehired.'",
								`salary` = "'.$salary.'",
								`spousename` = "'.$spousename.'",
								`notes` = "'.$notes.'"
			
		  	WHERE `employeeid` = '.$employeeid.'';
			//echo "ID ".$id;
	
		$result = mysqli_query($link,  $str)
		 or die(mysqli_error($link));
		if($result)
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=green>Employee data updated successfully</span></b></font>';
		$success = 1;
		}
		else
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=red>Could not update Employee data</font></span></b>';
		}
}

function updateBook($bookid,$title,$subjectid,$classid,$author,$copyrightyear,$publishername,$placeofpublication,$editionnumber,$notes,&$msg,&$success)
{	
	// common db info
	global $date,$dbname,$link;
	
	
	$str = 'UPDATE `tblbook` SET 
							`bookid` = "'.$bookid.'",
							`title` = "'.$title.'",
							`subjectid` = "'.$subjectid.'",
							`classid` = "'.$classid.'",
							`author` = "'.$author.'",
							`copyrightyear` = "'.$copyrightyear.'",
							`publishername` = "'.$publishername.'",
							`placeofpublication` = "'.$placeofpublication.'",
							`purchaseprice` = "'.$purchaseprice.'",
							`editionnumber` = "'.$editionnumber.'",
							`notes` = "'.$notes.'"

			
		  	WHERE `bookid` = '.$bookid.'';
			//echo "ID ".$id;
	
		$result = mysqli_query($link,  $str)
		 or die(mysqli_error($link));
		if($result)
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=green>Employee data updated successfully</span></b></font>';
		$success = 1;
		}
		else
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=red>Could not update Employee data</font></span></b>';
		}
}

// For uploading multiple images
function updateMultipleImage($_FILES,$dataType,$id,&$imgMsg,&$imgSuccess)
{
global $date,$dbname,$link;


			if($dataType == 'english')
			{
				$ImgTableName = 'imageen';
			}
			else
			{
				$ImgTableName = 'imagejp';
			}

// grab all images id;
	$IDstr = 'SELECT ID from '.$ImgTableName.' WHERE HotelID='.$id.' ORDER BY ID';
	$IDresult = mysql_query($IDstr);
	if(mysqli_num_rows($IDresult)>0)
	  {
	  $track = 1;
	   while ($row = mysqli_fetch_array($IDresult, MYSQL_ASSOC)) 
	   { 
		$imgID[$track] = $row["ID"];
		$track++;
	   } 
	}
	

	for($i = 1; $i <= count($_FILES); $i++)
	{
	
		if($_FILES['Image'.$i]['name'])
		{
			$filename = $_FILES['Image'.$i]['tmp_name'];
			$handle = fopen($filename, "r");
			$contents = fread($handle, filesize($filename));
			fclose($handle);
			//echo $contents;
			//echo $_FILES['Image'.$i]['tmp_name']."<br>";
///#### start image insert to database;
				if($imgID)
				$str = 'UPDATE	`'.$ImgTableName.'` SET
							`Name` = "'.$_FILES['Image'.$i]['name'].'",
							`Type` = "'.$_FILES['Image'.$i]['type'].'",
							`Size` = "'.$_FILES['Image'.$i]['size'].'", 
							`Contents`  = "'.mysql_escape_string($contents).'",
							`HotelID` = "'.$id.'"
							WHERE `ID` = '.$imgID[$i].'';
echo $str;
				$result = mysqli_query($link,  $str)
			 	or die("Invalid query: " . mysqli_error($link));
			 if($result)
				{
				$imgMsg = '<b><span  class="message"><font face="arial" size=2 color=green>Image successfully updated</b></span></font>';
				}
				else
				{
				$imgMsg .= '<b><span  class="message"><font face="arial" size=2 color=red>Could not upload image '.$_FILES['Image'.$i]['name'].'</b></span></font>';
				}
///#### end insert image to database			
		}
	}
}
function updateNotice($noticeid,$noticesubject,$description,&$msg,&$success)
{	
	// common db info
	global $date,$dbname,$link;
	
	
	$str = 'UPDATE `tblnotice` SET 
								`noticesubject` = "'.$noticesubject.'",
								`description` = "'.$description.'"
			
		  	WHERE `noticeid` = '.$noticeid.'';
			//echo "ID ".$id;
	
		$result = mysqli_query($link,  $str)
		 or die(mysqli_error($link));
		if($result)
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=green>Notice updated successfully</span></b></font>';
		$success = 1;
		}
		else
		{
		$msg = '<b><span class="message"><font face="arial" size=2 color=red>Could not update Notice</font></span></b>';
		}
}
/// End of Loreeto update coding
?>