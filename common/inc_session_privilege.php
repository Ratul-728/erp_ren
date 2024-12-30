<?php
	include_once('rak_framework/fetch.php'); 
	include_once('rak_framework/listgrabber.php');
	
	$debug = 0; 
 
	$currSectionID = fetchByID('mainMenu','currSection',$currSection,'id');
	//echo $currSectionID;die;
	//assign privilege SESSION;
	
	$arrPrivQuery = array('hrid' => $_SESSION['user'],'menuid' => $currSectionID);
	
	 
	
	$thisPriv = fetchSingleDataByArray('hrAuth',$arrPrivQuery,'menu_priv');
	
	//echo $thisPriv;die; 
	
	$_SESSION['currSection'] = $currSection;
	$_SESSION['currSectionID'] = $currSectionID;
	$_SESSION['currPriv'] = $thisPriv; 
	
//******************************* CHECK PRIVILEGE OF THIS PAGE FOR ME CURRENT USER;
//check user priv for this page; menu id for this page is 108
$pagePriv = fetchTotalRecordByCondition('hrAuth','hrid = "'.$_SESSION['user'].'" AND menuid = "'.$currSectionID.'"','menu_priv');
//echo "<hr>Do I have permission on this page?<br><b>".$pagePriv ."</b><hr>";die;


    //$_GET['mod'] is the base of following code; 
    
    if($_REQUEST['mod']){
        $_SESSION['mod'] = $_REQUEST['mod'];
    }
    
    $modl = ($_GET['mod'])?$_GET['mod']:$_SESSION['mod'];
   
    if($_SESSION['user']){
        

        
        
        $query = "SELECT 
                    m.id, 
                    m.menuNm, 
                    m.currSection, 
                    a.menuid, 
                    a.menu_priv, 
                    m.urllist, 
                    m.isnode,
                    m.parentnode,
                    m.lvl
                      FROM mainMenu m, hrAuth a
                      WHERE a.menuid = m.id 
                      AND ifnull(m.isreport,0) <> 1 
                      AND a.hrid = ".$_SESSION['user']." 
                      AND m.modl = ".$modl."
                      AND m.activeSt = 1 
                      AND m.isnode = 1
                     
                      ORDER BY m.menu_sl";
           
        //   AND lvl = 1
       

        $result = $conn->query($query);
        
        
        $data = array();
        
        if ($result === false) {
            // Handle the error, log it, or display a user-friendly message
            error_log("Database query failed: " . $conn->error);
            die("An error occurred while processing your request. Please try again later.");
        }

        while ($row = $result->fetch_assoc()) {
           // $data[] = $row;
            if ($row['lvl'] == 2) {
                // Overwrite id with parentnode if lvl is 2
                $row['id'] = $row['parentnode'];
            }
            $data[] = $row;           
        }
        
        $_SESSION['privdata'] = $data;
        
        if($_SESSION["user"] == 173 && $debug == 1 && basename($_SERVER['PHP_SELF']) != 'priv.php'){
            echo "<hr>". $query."<hr>";
            // Now $data contains the result in array format
            echo "<pre>";
            print_r($data); 
            echo "</pre>";
            echo "<br>pagePriv: ".$pagePriv;
            echo "<br>page: ".$data[0]['urllist'];
            die;  
        }
        
        
}


if($pagePriv == 0){
        if(count($data) > 0){
            header("location:".$data[0]['urllist']."&mod=".$modl);
        }else{
            //die;
            echo 'Unauthorized access!<br><hr><button onclick="location.href=\'hrqv.php\';"> < Go to Dashboard </button>'; die;
        }
}
//******************************* END CHECKING PRIVILEGE FOR THIS PAGE FOR ME;


?>