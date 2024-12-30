<?php
function listMetalType(&$LineNo,&$metalType)
{
			global $dbname, $link;
			 
			 
		 	 $str	= 'SELECT LineNo,Head1 from rawheader ORDER BY `ID` LIMIT 1, 4';
		 
			  $result = mysqli_query($link,  $str)
			  or die(mysqli_error($link));
			  $track = 0;
			   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
			   { 
				$ID[$track] = $row["ID"];
				$metalType[$track] = explode("[",$row["Head1"]);
				$metalType[$track] = $metalType[$track][0];
				$LineNo[$track] = $row["LineNo"];
				$track++;
			   } 
			  
}
function listMetalPrice(&$ID,&$metalType,&$Bid,&$Ask,&$exchangeRate)
{
			global $outer_dbname;
			 
			 mysql_select_db($outer_dbname);
		 	 $str	= 'SELECT * from spot ORDER BY `ID` LIMIT 0, 4';
			 $strEx	= 'SELECT * from spot WHERE Metal="CAD"';
		 
		 	$resultEx = mysql_query($strEx);
			$exchangeRate = mysql_fetch_row($resultEx);
			$exchangeRate = $exchangeRate[2];
		 
			  $result = mysqli_query($link,  $str)
			  or die(mysqli_error($link));
			  $track = 0;
			   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
			   { 
				$ID[$track] = $row["ID"];
				$metalType[$track] = $row["Metal"];
				$Bid[$track] = $row["Bid"];
				$Ask[$track] = $row["Ask"];
				$track++;
			   } 
			  
}
function listMultiCurr(&$CurrencyName,&$CurrencyRate)
{
	global $dbname, $link;
	 
	 
	
	$str = 'SELECT Val3 from rawvalues WHERE LineNo=20';
	$result = mysqli_query($link,  $str);
	$exchangeRateCAD = mysql_fetch_row($result);
	$CurrencyRate[0] = $exchangeRateCAD[0];
	$CurrencyName[0] = 'CAD';

	$str	= 'SELECT Val3 from rawvalues WHERE LineNo=21';
	$result = mysqli_query($link,  $str);
	$exchangeRateEURO = mysql_fetch_row($result);
	$CurrencyRate[1] = $exchangeRateEURO[0];
	$CurrencyName[1] = 'EURO';

	$str	= 'SELECT Val3 from rawvalues WHERE LineNo=25';
	$result = mysqli_query($link,  $str);
	$exchangeRateUK = mysql_fetch_row($result);
	$CurrencyRate[2] = $exchangeRateUK[0];
	$CurrencyName[2] = 'GBP';
}
?>