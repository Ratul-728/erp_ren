<?php





/*  ########### END CHECK ID HASH WIHT LINK ###########*/

//// building security layer

/*

Usage:



Send str string through url:

<div class="td"><input type="button" class="btn" value="Detail" onClick="location.href='job_detail.php?id=<?=$data[$i]['id']?>&str=<?=rakHash($data[$i]['id']);?>'"></div>





Check the right str query string;



	$id = $_REQUEST["id"]; //in this case the value is 12345

	$hash = $_REQUEST["str"];

	if (chkRakHash($id, $hash)) {

	  //no tampering detected, proceed with other processing

		  $_REQUEST['id'] = $_REQUEST['id'];

	} else {

	  //tampering of data detected

		  $_REQUEST['id'] = '';

		  echo 'Unexpected Query String......';

		  exit();

	}

*/

//if(function_exists('hash_equals')){echo 'hash_equals exists';}else{ echo 'hash_equals not exists'; }



function rakHash($id){

	global $secret;

	$hash = hash_hmac('sha256', $id, $secret);

	//echo '<hr>H1 '.$hash.'<hr>';

	return $hash;

	

}



function chkRakHash($id, $hash){

	global $secret;

	$str1 = hash_hmac('sha256', $id, $secret);

	$str2 = $hash;





			if(strlen($str1) != strlen($str2))

			{

				return false;

			}

			else

			{

				//return true;

				

				$res = $str1 ^ $str2;

				$ret = 0;

				for($i = strlen($res) - 1; $i >= 0; $i--)

				{

					$ret |= ord($res[$i]);

				}

				return !$ret;

				

			}





}



	function loadHashIDCheck(){

			require_once('check_hash_id.php');

	}

	

/*  ########### END CHECK ID HASH WIHT LINK ###########*/



function formatDate($str)

{



	if($str == '0000-00-00 00:00:00'){

		return '-';

	}

	else

	{

		if (preg_match ("/ /", $str)) 

		{ 

			$str = explode(" ",$str);

			$str = $str[0]; 

		} 

		$formattedDate = explode('-',$str);

		

		//return as 15 Jan 2008

		return $formattedDate[2].' '.date ("M", mktime (0,0,0,$formattedDate[1],1,1)).' '.$formattedDate[0];

	}

}



function formatDateTime($str)

{



	if($str == '0000-00-00 00:00:00'){

		return '-';

	}

	else

	{

		$time = explode(" ",$str);

		if (preg_match ("/ /", $str)) 

		{ 

			$str = explode(" ",$str);

			$str = $str[0]; 

		} 

		$formattedDate = explode('-',$str);

		

		//return as 15 Jan 2008

		return $formattedDate[2].' '.date ("M", mktime (0,0,0,$formattedDate[1],1,1)).' '.$formattedDate[0].',  '.date("h:i a", strtotime($time[1]));

		//return $str;

	}

}







function formatDateReverse($str)
{
    if(strstr($str,"/")){
        $formattedDate = explode("/",$str);
	   //$str gets d/m/y
	   return $formattedDate[2].'-'.$formattedDate[1].'-'.$formattedDate[0];        
    }
   	

}



function formateDateToMySql($str)

{

	return date("Y-m-d", strtotime($str));

}



function formatDateAndTIme($str)

{

	return date("d M Y, g:i a", strtotime($str));

}







function formatDate2($str)

{

	if (preg_match ("/ /", $str)) 

	{ 

   		$str = explode(" ",$str);

		$str = $str[0]; 

	} 

	$formattedDate = explode('-',$str);

	

	//return as 15 Jan 2008

	if($str){

	return $formattedDate[2].'/'.$formattedDate[1].'/'.$formattedDate[0];

	}else

	{

		return '';

		}

	

}











function formatUrl($url) {



    if(!(strpos($url, "http://") === 0)

	&& !(strpos($url, "https://") === 0)) {



        $url = "http://$url";

	

	}

	

    return $url;

	//echo $url;

	echo 'CALLED';

}









function arrayToString($array, $separator){

		foreach ($array as $key => $value) { 

			$string .= $value.$separator; 

		}

		return $string;

	}





// converts multidimentional array to a flat array..

function arrayFlatten($array) {

    if (!is_array($array)) {

        // nothing to do if it's not an array

        return array($array);

    }



    $result = array();

    foreach ($array as $value) {

        // explode the sub-array, and add the parts

        $result = array_merge($result, arrayFlatten($value));

    }



    return $result;

}







function getFileExtension($file_name){

	$file_type = explode(".",$file_name);

	$n = count($file_type);

	$n =  $n-1;

	return $file_type[$n];

}



// This function will keep track of all transaction between mother banks and other accounts.

// it will update the log and reflect the mother bank;

// Version 1.0

// Date: Aug31-08

// Developer: Raihan A.K.



/* Transaction Points:

	1. Primary Share

	-------------------

		* Apply Share

		* Edit Applied Share

		* Share Full Rejection

		* Share Partially Rejected

		* Undo Rejection Share

		* Delete a share lot

	2. Secondary Share

		* Share Buy

		* Share Sell

		* Share Edit

*/

function recordTransaction($desc,$amount,$type,$trnsDate,&$msg)

{

	global $date;

	//fetch balance from mother bank acc fetchByID($tblName,$QueriedByID,$QueriedByIDValue,$ReturnValue)

	$currentBalance	= fetchByID('tbl_mother_bank_information','user_id',$_SESSION['USER_ID'],'current_balance');

	

	//reflect Mother Bank ; values 'dr', 'cr';

	if($type == 'cr')

	{

		$updatedBalance = $currentBalance+$amount;

	}

	else

	{

		$updatedBalance = $currentBalance-$amount;

	}

	



	

	

	if($trnsDate)

	{

		$trnsDate = $trnsDate;

	}

	else

	{

		$trnsDate = $date;

	}

	

	//insert data to tbl_transaction_history table

	 global $dbname, $link;

	 

	 



	//insert

	$str = 'INSERT INTO tbl_transaction_history (description,user_id,amount_'.$type.',current_balance,date_of_trns) VALUES ("'.$desc.'","'.$_SESSION['USER_ID'].'","'.$amount.'", "'.$updatedBalance.'","'.$trnsDate.'")';

	

	$result = mysqli_query($link,  $str)

	

	or die("insert recordTransaction error: " . mysqli_error($link));

	

	if($result)

	{

		$msg = '<span class="message"><b><font face="arial" size=2 color=green>Data successfully added</b></font></span>';

	}

	else

	{

		$msg = '<span class="message"><b><font face="arial" size=2 color=red>Could not add data</b></font></span>';

	}

	





	//update Mother Bank

	$condition = 'user_id = '.$_SESSION['USER_ID'];

	

	updateByID('tbl_mother_bank_information','current_balance',$updatedBalance,$condition);



}



function formatTime($str)

{

	if (preg_match ("/ /", $str)) 

	{ 

   		$str = explode(" ",$str);

		$str = $str[1]; 

	} 

	//$formattedDate = explode('-',$str);

	return $str; //$formattedDate[2].'/'.$formattedDate[1].'/'.$formattedDate[0];

}



function getYearOnly($str)

{

	if (preg_match ("/ /", $str)) 

	{ 

   		$str = explode(" ",$str);

		$str = $str[0]; 

	} 

	$formattedDate = explode('-',$str);

	//return $formattedDate[2].'/'.$formattedDate[1].'/'.$formattedDate[0];

	return $formattedDate[0];

	//echo $formattedDate[0];

}

function getMonthOnly($str)

{

	if (preg_match ("/ /", $str)) 

	{ 

   		$str = explode(" ",$str);

		$str = $str[0]; 

	} 

	$formattedDate = explode('-',$str);

	//return $formattedDate[2].'/'.$formattedDate[1].'/'.$formattedDate[0];

	return $formattedDate[1];

	//echo $formattedDate[0];

}



function getDayOnly($str)

{

	if (preg_match ("/ /", $str)) 

	{ 

   		$str = explode(" ",$str);

		$str = $str[0]; 

	} 

	$formattedDate = explode('-',$str);

	//return $formattedDate[2].'/'.$formattedDate[1].'/'.$formattedDate[0];

	return $formattedDate[2];

	//echo $formattedDate[0];

}



function phpMaillerAltMsg($message)

{

	$altMessage = str_replace("<br>", "\n", $message);

	$altMessage = str_replace("<b>", "", $altMessage);

	$altMessage = str_replace("</b>", "", $altMessage);

	return $altMessage;

}



function sendEmail($userID,$email,$pwd, &$msg)

{

	$headers  = "MIME-Version: 1.0\r\n";

	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	

	$to = $email;

	$fromMail = 'mail@eshna.com';

	//$to = 'info@allpestcontrol.ca';

	$subject = 'Welcome to Eshna';

	$body = '

	<font face=arial>

  Hello '.$userID.',<br><br>

	Thank you for your interest with us. You have successfully registered and submitted your resume.<br><br>



	Following is you account information to access your user panel to edit your resume.<br><br>



	User name: '.$userID.'<br>

	Password:'.$pwd.'

	<br><br>

	Soon you will hear from us.

	<br><br>

	Thank you

	<br><br>

	Eshna Web Team

  </font>

	';

	//echo $body;



	$headers .= "From: Eshna Account Info <".$fromMail.">\r\n";



	

	/* and now mail it */



	if(mail($to, $subject, $body, $headers))

	{

		$msg = '<font face=arial color=green size=2><b>Mail send successfully</font>';

	}

	else

	{

		$msg = '<font face=arial color=red size=2><b>Problem occured. Could not send mail.</b></font>';

	}

}

//#### Function for fetching csv data from other server;

function extrair_url_fopen ($url) 

{ 

	$contents = file_get_contents($url);

	return $contents;

}



//#### Function for dumping updated rate to dump/data.csv file;

function writeCSV($contents)

{

	$filename = 'dump/data.csv';

	$fp = fopen($filename, "w");

	@chmod ($filename, 0777);

	$write = fputs($fp, $contents);

	fclose($fp);

}

//#### Function for writing error log file;

function writeLog($errTxt)

{

	$filename = 'log.txt';

	$fp = fopen($filename, "a");

	@chmod ($filename, 0777);

	$newErr = date("F j, Y, g:i a")." - ".$errTxt."\n";

	$write = fputs($fp, $newErr);

	fclose($fp);

}



function returnYears(&$fromYear,&$toYear)

{

	$fromYear = date("Y")-$fromYear;

	$toYear = date("Y")-$toYear;

}



function returnAge($years)

{



	if($years != '0000'){

	$years = date("Y")-$years;

	return $years;

	}else{

	 $years = 0;

	 return $years;

	}

}





function search_array($needle, $haystack) 

{

	if(in_array($needle, $haystack))

	{

	  return true;

	}



	foreach($haystack as $element)

	{

		if(is_array($element) && search_array($needle, $element))

			return true;

	}

	return false;

}



function findKey($array, $keySearch)

{

    // check if it's even an array

    if (!is_array($array)) return false;



    // key exists

    if (array_key_exists($keySearch, $array)) return true;



    // key isn't in this array, go deeper

    foreach($array as $key => $val)

    {

        // return true if it's found

        if (findKey($val, $keySearch)) return true;

    }



    return false;

}





function http_content_type($url = '', $default_mime = 'application/octet-stream')

{

    if ($headers = @array_change_key_case(get_headers($url, true)) AND isset($headers['content-type']))

    {

        $headers['content-type'] = strtolower(is_array($headers['content-type']) ? end($headers['content-type']) : $headers['content-type']);

        $headers['content-type'] = explode(';', $headers['content-type']);

            

        return trim($headers['content-type'][0]);

    }

    

    return $default_mime;

} 

//example to call

//$mime = http_content_type('http://www.example.com/file.zip');







function convert_number_to_words($number) {

    

    $hyphen      = '-';

    $conjunction = ' and ';

    $separator   = ', ';

    $negative    = 'negative ';

    $decimal     = ' point ';

    $dictionary  = array(

        0                   => 'zero',

        1                   => 'one',

        2                   => 'two',

        3                   => 'three',

        4                   => 'four',

        5                   => 'five',

        6                   => 'six',

        7                   => 'seven',

        8                   => 'eight',

        9                   => 'nine',

        10                  => 'ten',

        11                  => 'eleven',

        12                  => 'twelve',

        13                  => 'thirteen',

        14                  => 'fourteen',

        15                  => 'fifteen',

        16                  => 'sixteen',

        17                  => 'seventeen',

        18                  => 'eighteen',

        19                  => 'nineteen',

        20                  => 'twenty',

        30                  => 'thirty',

        40                  => 'fourty',

        50                  => 'fifty',

        60                  => 'sixty',

        70                  => 'seventy',

        80                  => 'eighty',

        90                  => 'ninety',

        100                 => 'hundred',

        1000                => 'thousand',

        1000000             => 'million',

        1000000000          => 'billion',

        1000000000000       => 'trillion',

        1000000000000000    => 'quadrillion',

        1000000000000000000 => 'quintillion'

    );

    

    if (!is_numeric($number)) {

        return false;

    }

    

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

        // overflow

        trigger_error(

            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,

            E_USER_WARNING

        );

        return false;

    }



    if ($number < 0) {

        return $negative . convert_number_to_words(abs($number));

    }

    

    $string = $fraction = null;

    

    if (strpos($number, '.') !== false) {

        list($number, $fraction) = explode('.', $number);

    }

    

    switch (true) {

        case $number < 21:

            $string = $dictionary[$number];

            break;

        case $number < 100:

            $tens   = ((int) ($number / 10)) * 10;

            $units  = $number % 10;

            $string = $dictionary[$tens];

            if ($units) {

                $string .= $hyphen . $dictionary[$units];

            }

            break;

        case $number < 1000:

            $hundreds  = $number / 100;

            $remainder = $number % 100;

            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

            if ($remainder) {

                $string .= $conjunction . convert_number_to_words($remainder);

            }

            break;

        default:

            $baseUnit = pow(1000, floor(log($number, 1000)));

            $numBaseUnits = (int) ($number / $baseUnit);

            $remainder = $number % $baseUnit;

            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];

            if ($remainder) {

                $string .= $remainder < 100 ? $conjunction : $separator;

                $string .= convert_number_to_words($remainder);

            }

            break;

    }

    

    if (null !== $fraction && is_numeric($fraction)) {

        $string .= $decimal;

        $words = array();

        foreach (str_split((string) $fraction) as $number) {

            $words[] = $dictionary[$number];

        }

        $string .= implode(' ', $words);

    }

    

    return $string;

}



function dirToArray($dir) { 

   

   $result = array(); 



   $cdir = scandir($dir); 

   

   foreach ($cdir as $key => $value) 

   { 

      if (!in_array($value,array(".",".."))) 

      { 

			 if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) 

         { 

            $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value); 

         } 

         else 

         { 

            $result[] = $value; 

         } 

      } 

   } 

   

   return $result; 

} 






function dd($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}


function dumpTxt($data) {
    
   
    $filepath = __DIR__ . '/dumTxt.txt';

    // Check if $data is an array
    if (is_array($data)) {
        $content = print_r($data, true);
    } else {
        $content =  $data;
    }

    // Write the content to the text file
    if (file_put_contents($filepath, $content) !== false) {
        return "Variable successfully written to the file.";
    } else {
        return "Error writing variable to the file.";
    }
}


function dumpTxt2($data) {
    // File path
    $filePath = __DIR__ . '/dumTxt.txt'; // Adjust the file path as needed

    
    $file = fopen($filePath, 'w');

    if ($file) {
    
        fwrite($file, $data);

        // Close the file
        fclose($file);

        //echo "Data has been successfully saved to 'dumTxt.txt' file.";
    } else {
       // echo "Error opening the file for writing.";
    }
}





?>
