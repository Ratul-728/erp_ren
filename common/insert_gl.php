<?php
//phpinfo();die;
//test this function

session_start();
//ini_set('display_errors',1);


$_SERVER['flag'] = 0;

//$debug = 1;
/*include_once('conn.php');
include_once('../rak_framework/connection.php');
include_once('../rak_framework/fetch.php');
include_once('../rak_framework/insert.php');
include_once('../rak_framework/edit.php');
include_once('../rak_framework/misfuncs.php');

//echo $date; die;
//$date='19/08/2023';
//test arrays
$glmstArr = array(
	'transdt' => $date,
	'refno' => 'REF NO 190823_1',
	'remarks' => 'MY remarks',
	'entryby' => '1',
);
	

	$gldetailArr[] = array(
		'sl'	 =>	1,
        'glac'	 =>	'102050203',	//glno
		'dr_cr' 	=>	'C',
		'amount' 	=>	'6000',
		'remarks' 	=>	'my gl detiail remarks',
		'entryby' 	=>	'1',
		'entrydate' 	=>	'19/08/2023'
);


	$gldetailArr[] = array(
		'sl'	 =>	2,
        'glac'	 =>	'102010101',	//glno
		'dr_cr' 	=>	'D',
		'amount' 	=>	'6000',
		'remarks' 	=>	'my gl detiail remarks 2',
		'entryby' 	=>	'1',
		'entrydate' 	=>	'19/08/2023'
);
*/

function insertGl($glmstArr,$gldetailArr)
{
    global $date;

    //extract($_POST);
    //print_r($gldetailArr);
    $vouchno = getFormatedUniqueID('glmst','id','VO-',8,"0");	//change table name
    // fetchtByID('tablename','id',$id,'email'); //to get email by id from tablename;
    //$companyid = fetchtByID('hr','resource_id',$entryby,'');

    $transdt = formatDateReverse($glmstArr['transdt']);
	
    $insertMasterData = array(
	 'TableName' => 'glmst',	//master data tale
	 'FetchByKey' => 'id',
	 'FetchByValue' =>  '',


	 'vouchno' => $vouchno,
	 'transdt' => $transdt,
	 'refno' => $glmstArr['refno'],
	 'remarks' => $glmstArr['remarks'],
	 'entryby' => $glmstArr['entryby'],
	 'entrydate' => $date,
	 //'companyid' => $companyid,
	 'status' => 'A'	
    );
    insertData($insertMasterData,$msg,$success,$insertId); 
	//mysql data format: yyyy-mm-dd;

    foreach($gldetailArr as $value)
    {    
	    $entrydt=formatDateReverse($value['entrydate']);
	    $insertChildData = array(
        'TableName' => 'gldlt',
        'FetchByKey' => 'id',
        'FetchByValue' =>  '',

        'vouchno' 	=>	$vouchno,	
		'sl' 	=>	$value['sl'],
		'glac'	 =>	$value['glac'],	//glno
		'dr_cr' 	=>	$value['dr_cr'],
		'amount' 	=>	$value['amount'],
		'remarks' 	=>	$value['remarks'],
		'entryby' 	=>	$value['entryby'],
		'entrydate' 	=>	formatDateReverse($value['entrydate']),
		'status' 	=>	'A'
	    );
	    insertData($insertChildData,$msg,$success,$insertId);
        $glac = $value['glac'];
	
	    $nature = fetchByID('coa','glno',$glac,'dr_cr');
	
	    if(($value['dr_cr'] == 'C' && $nature=='D')||($value['dr_cr'] == 'D' && $nature=='C')  )
	    {
		    $amount= $value['amount']*(-1);
	    } 
        else 
        {
		    $amount=$value['amount'];
	    }
    	//udpate chart of accounts;
    	//level 5
    	$lvl = fetchByID('coa','glno',$glac,'lvl');
   
	    if($lvl == 5)
	    {
        //echo  "<h1>$lvl</h1>";
		//update coa set  closingbal=closingbal+$amount where glno=$glac
		//updateByID($tableName,$colToUpdate,$updatableValue,$condition)
		    //level 5
		    $condition =  'glno = "'.$glac.'"';
			updateByID('coa','closingbal+',$amount,$condition);
		    
		    $condition =  "glno = '$glac' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
			updateByID('coa_mon','closingbal+',$amount,$condition);
		
		    //lavel 4
		    $ctlgl4 = fetchByID('coa','glno',$glac,'ctlgl');
		    $condition =  'glno = "'.$ctlgl4.'"';
			updateByID('coa','closingbal+',$amount,$condition);

		    $condition = "glno = '$ctlgl4' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
			updateByID('coa_mon','closingbal+',$amount,$condition);
		
		    //level 3
		    $ctlgl3 = fetchByID('coa','glno',$ctlgl4,'ctlgl');
			$condition =  'glno = "'.$ctlgl3.'"';
			updateByID('coa','closingbal+',$amount,$condition);

		    $condition =  "glno = '$ctlgl3' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
			updateByID('coa_mon','closingbal+',$amount,$condition);	
		
		    //level 2
		    $ctlgl2 = fetchByID('coa','glno',$ctlgl3,'ctlgl');
		    $condition =  'glno = "'.$ctlgl2.'"';
			updateByID('coa','closingbal+',$amount,$condition);  //issue
        
		    $condition =  "glno = '$ctlgl2' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
		    updateByID('coa_mon','closingbal+',$amount,$condition);
		
		    //level 1
		    $ctlgl1 = fetchByID('coa','glno',$ctlgl2,'ctlgl');
		    $condition =  'glno = "'.$ctlgl1.'"';
			updateByID('coa','closingbal+',$amount,$condition); //issue
      
		    $condition =  "glno = '$ctlgl1' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
			updateByID('coa_mon','closingbal+',$amount,$condition);		
		}
    	else if($lvl == 4)
    	{
    	    $condition =  "glno = '$glac'";
    		updateByID('coa','closingbal+',$amount,$condition);
    		
    		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
    		$condition =  "glno = '$glac' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		//lavel 3
    		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
    		
    		$condition =  "glno = '$ctlgl'";
    		updateByID('coa','closingbal+',$amount,$condition);
    
    		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		//level 2
    		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
    		
    		$condition =  "glno = '$ctlgl'";
    		updateByID('coa','closingbal+',$amount,$condition);
    
    		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);	
    		
    		//level 1
    		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
    		
    		$condition =  "glno = '$ctlgl'";
    		updateByID('coa','closingbal+',$amount,$condition);
    
    		$condition =  "glno = '$ctlg.' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    			
    		
    	}
    	else if($lvl == 3)
    	{
    	
    		$condition =  "glno = '$glac'";
    		updateByID('coa','closingbal+',$amount,$condition);
    		
    		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
    		$condition =  "glno = '$glac'  and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		//lavel 2
    		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
    		
    		$condition =  "glno = '$ctlgl'";
    		updateByID('coa','closingbal+',$amount,$condition);
    
    		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		//level 1
    		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
    		
    		$condition =  "glno = '$ctlgl'";
    		updateByID('coa','closingbal+',$amount,$condition);
    
    		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);	
    	}
    	else if($lvl == 2)
    	{
    		//level 2
    		$condition =  "glno = '$glac'";
    		updateByID('coa','closingbal+',$amount,$condition);
    		
    		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
    		$condition =  "glno = '$glac' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		//lavel 1
    		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
    		
    		$condition =  "glno = '$ctlgl'";
    		updateByID('coa','closingbal+',$amount,$condition);
    
    		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    	}
    	else
    	{
    		//level 1
    		$condition =  "glno = '$glac'";
    		updateByID('coa','closingbal+',$amount,$condition);
    		
    		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
    		$condition =  "glno = '$glac' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    	}		
	    $lvl = "";
    }//foreach($sl as $value){

}

function insertGlfin($glmstArr,$gldetailArr)
{
    global $date;

    //extract($_POST);
    //print_r($gldetailArr);
    $vouchno = getFormatedUniqueID('glmst','id','VO-',8,"0");	//change table name
    // fetchtByID('tablename','id',$id,'email'); //to get email by id from tablename;
    //$companyid = fetchtByID('hr','resource_id',$entryby,'');

    $transdt = formatDateReverse($glmstArr['transdt']);
	
    $insertMasterData = array(
	 'TableName' => 'glmst',	//master data tale
	 'FetchByKey' => 'id',
	 'FetchByValue' =>  '',


	 'vouchno' => $vouchno,
	 'transdt' => $transdt,
	 'refno' => $glmstArr['refno'],
	 'remarks' => $glmstArr['remarks'],
	 'entryby' => $glmstArr['entryby'],
	 'entrydate' => $date,
	 'isfinancial' => 'Y',
	 'status' => 'A'	
    );
    insertData($insertMasterData,$msg,$success,$insertId); 
	//mysql data format: yyyy-mm-dd;

    foreach($gldetailArr as $value)
    {    
	    $entrydt=formatDateReverse($value['entrydate']);
	    $insertChildData = array(
        'TableName' => 'gldlt',
        'FetchByKey' => 'id',
        'FetchByValue' =>  '',

        'vouchno' 	=>	$vouchno,	
		'sl' 	=>	$value['sl'],
		'glac'	 =>	$value['glac'],	//glno
		'dr_cr' 	=>	$value['dr_cr'],
		'amount' 	=>	$value['amount'],
		'remarks' 	=>	$value['remarks'],
		'entryby' 	=>	$value['entryby'],
		'entrydate' 	=>	formatDateReverse($value['entrydate']),
		'status' 	=>	'A'
	    );
	    insertData($insertChildData,$msg,$success,$insertId);
        $glac = $value['glac'];
	
	    $nature = fetchByID('coa','glno',$glac,'dr_cr');
	    $finance = fetchByID('coa','glno',$glac,'oflag');
	
	    if(($value['dr_cr'] == 'C' && $nature=='D')||($value['dr_cr'] == 'D' && $nature=='C')  )
	    {
		    $amount= $value['amount']*(-1);
	    } 
        else 
        {
		    $amount=$value['amount'];
	    }
    	//udpate chart of accounts;
    	//level 5
    	$lvl = fetchByID('coa','glno',$glac,'lvl');
        
        if($finance=='N')
        {
    	    if($lvl == 5)
    	    {
            //echo  "<h1>$lvl</h1>";
    		//update coa set  closingbal=closingbal+$amount where glno=$glac
    		//updateByID($tableName,$colToUpdate,$updatableValue,$condition)
    		    //level 5
    		    $condition =  'glno = "'.$glac.'"';
    			updateByID('coa','closingbal+',$amount,$condition);
    		    
    		    $condition =  "glno = '$glac' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		    //lavel 4
    		    $ctlgl4 = fetchByID('coa','glno',$glac,'ctlgl');
    		    $condition =  'glno = "'.$ctlgl4.'"';
    			updateByID('coa','closingbal+',$amount,$condition);
    
    		    $condition = "glno = '$ctlgl4' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		    //level 3
    		    $ctlgl3 = fetchByID('coa','glno',$ctlgl4,'ctlgl');
    			$condition =  'glno = "'.$ctlgl3.'"';
    			updateByID('coa','closingbal+',$amount,$condition);
    
    		    $condition =  "glno = '$ctlgl3' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closingbal+',$amount,$condition);	
    		
    		    //level 2
    		    $ctlgl2 = fetchByID('coa','glno',$ctlgl3,'ctlgl');
    		    $condition =  'glno = "'.$ctlgl2.'"';
    			updateByID('coa','closingbal+',$amount,$condition);  //issue
            
    		    $condition =  "glno = '$ctlgl2' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		    updateByID('coa_mon','closingbal+',$amount,$condition);
    		
    		    //level 1
    		    $ctlgl1 = fetchByID('coa','glno',$ctlgl2,'ctlgl');
    		    $condition =  'glno = "'.$ctlgl1.'"';
    			updateByID('coa','closingbal+',$amount,$condition); //issue
          
    		    $condition =  "glno = '$ctlgl1' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closingbal+',$amount,$condition);		
    		}
        	else if($lvl == 4)
        	{
        	    $condition =  "glno = '$glac'";
        		updateByID('coa','closingbal+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        		//lavel 3
        		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closingbal+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        		//level 2
        		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closingbal+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);	
        		
        		//level 1
        		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closingbal+',$amount,$condition);
        
        		$condition =  "glno = '$ctlg.' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        			
        		
        	}
        	else if($lvl == 3)
        	{
        	
        		$condition =  "glno = '$glac'";
        		updateByID('coa','closingbal+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac'  and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        		//lavel 2
        		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closingbal+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        		//level 1
        		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closingbal+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);	
        	}
        	else if($lvl == 2)
        	{
        		//level 2
        		$condition =  "glno = '$glac'";
        		updateByID('coa','closingbal+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        		//lavel 1
        		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closingbal+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        	}
        	else
        	{
        		//level 1
        		$condition =  "glno = '$glac'";
        		updateByID('coa','closingbal+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closingbal+',$amount,$condition);
        		
        	}		
    	    $lvl = "";
        }
        else
        {
             if($lvl == 5)
    	    {
            //echo  "<h1>$lvl</h1>";
    		//update coa set  closingbal=closingbal+$amount where glno=$glac
    		//updateByID($tableName,$colToUpdate,$updatableValue,$condition)
    		    //level 5
    		    $condition =  'glno = "'.$glac.'"';
    			updateByID('coa','closing_bal_fin+',$amount,$condition);
    		    
    		    $condition =  "glno = '$glac' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
    		
    		    //lavel 4
    		    $ctlgl4 = fetchByID('coa','glno',$glac,'ctlgl');
    		    $condition =  'glno = "'.$ctlgl4.'"';
    			updateByID('coa','closing_bal_fin+',$amount,$condition);
    
    		    $condition = "glno = '$ctlgl4' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
    		
    		    //level 3
    		    $ctlgl3 = fetchByID('coa','glno',$ctlgl4,'ctlgl');
    			$condition =  'glno = "'.$ctlgl3.'"';
    			updateByID('coa','closing_bal_fin+',$amount,$condition);
    
    		    $condition =  "glno = '$ctlgl3' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closing_bal_fin+',$amount,$condition);	
    		
    		    //level 2
    		    $ctlgl2 = fetchByID('coa','glno',$ctlgl3,'ctlgl');
    		    $condition =  'glno = "'.$ctlgl2.'"';
    			updateByID('coa','closing_bal_fin+',$amount,$condition);  //issue
            
    		    $condition =  "glno = '$ctlgl2' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    		    updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
    		
    		    //level 1
    		    $ctlgl1 = fetchByID('coa','glno',$ctlgl2,'ctlgl');
    		    $condition =  'glno = "'.$ctlgl1.'"';
    			updateByID('coa','closing_bal_fin+',$amount,$condition); //issue
          
    		    $condition =  "glno = '$ctlgl1' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
    			updateByID('coa_mon','closing_bal_fin+',$amount,$condition);		
    		}
        	else if($lvl == 4)
        	{
        	    $condition =  "glno = '$glac'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac' AND mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        		//lavel 3
        		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        		//level 2
        		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);	
        		
        		//level 1
        		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        
        		$condition =  "glno = '$ctlg.' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        			
        		
        	}
        	else if($lvl == 3)
        	{
        	
        		$condition =  "glno = '$glac'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac'  and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        		//lavel 2
        		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        		//level 1
        		$ctlgl = fetchByID('coa','glno',$ctlgl,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);	
        	}
        	else if($lvl == 2)
        	{
        		//level 2
        		$condition =  "glno = '$glac'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        		//lavel 1
        		$ctlgl = fetchByID('coa','glno',$glac,'ctlgl');
        		
        		$condition =  "glno = '$ctlgl'";
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        
        		$condition =  "glno = '$ctlgl' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        	}
        	else
        	{
        		//level 1
        		$condition =  "glno = '$glac'"; 
        		updateByID('coa','closing_bal_fin+',$amount,$condition);
        		
        		//update coa_mon set closingbal=closingbal+$amount where glno=$glac  and mn=MONTH(p_transdt) and year=YEAR(p_transdt)
        		$condition =  "glno = '$glac' and mn= lpad(MONTH('$entrydt'),2,'0') AND yr=YEAR('$entrydt')";
        		updateByID('coa_mon','closing_bal_fin+',$amount,$condition);
        		
        	}		
    	    $lvl = "";
        }
    }//foreach($sl as $value){

}

//function insertGl(){

//call function
//insertGl($glmstArr,$gldetailArr);
?>