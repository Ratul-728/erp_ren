<?php
require "../common/conn.php";
include_once('../rak_framework/fetch.php');
require_once("../rak_framework/edit.php");
session_start();

if($_SESSION["user"]){

    //print_r($_POST);
    //die;

    $hrid = $_POST['hrid'];
    $status = $_POST['status'];

    if($status == 0){
        $return = updateByID('hr','is_login_blocked',1,'id='.$hrid);
        if($return){
            $response = array(
                "status" => "success",
                "message" => "User blocked successfully"
            );
        }
        else{
            $response = array(
                "status" => "error",
                "message" => "Error in blocking user"
            );
        }        
    }else{
        $return = updateByID('hr','is_login_blocked',0,'id='.$hrid);
        if($return){
            $response = array(
                "status" => "success",
                "message" => "User unblocked successfully"
            );
        }
        else{
            $response = array(
                "status" => "error",
                "message" => "Error in unblocked user"
            );
        } 
    }

   
    echo json_encode($response);
    exit();
}