<?php


require "conn.php";
include_once('../rak_framework/fetch.php');
print_r($_SESSION);

//getGridBtns($currSection), //getGridBtns('Feature"),

function getGridBtns($currSection){
    
    //get menu ID from mainMenu by $currSecton;
    $sectionID = fetchByID('mainMenu','currSection',$currSection,'id');
    echo $sectionID;
    
    $str = 'SELECT * FROM `hrAuth` WHERE hrid='.$_SESSION['USER'].' AND menuid=(SELECT id FROM `mainMenu` WHERE currSection="'.$currSection.'")';
    //get button array fom hr auth;
    
    
    
    //SELECT * FROM `hrAuth` WHERE `menuid` = 124 AND hrid=1
    
    
    //get
    
    
    
}

getGridBtns($currSection);


?>