<?php
function fetchMetalDetail($LineNo,&$Bid,&$Ask,&$exchangeRate)
{
		global $dbname, $link;
		 
		 

		$str	= 'SELECT * from rawvalues WHERE LineNo="'.$LineNo.'"';
		$strEx	= 'SELECT * from rawvalues WHERE LineNo=20';
		
		$resultEx = mysql_query($strEx);
		$exchangeRate = mysql_fetch_row($resultEx);
		$exchangeRate = $exchangeRate[4];
		 
		  $result = mysqli_query($link,  $str);
		  
		   while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) 
		   { 
			$ID = $row["ID"];
			$Bid = $row["Val3"];
			$Ask = $row["Val4"];
		   }
}
?>