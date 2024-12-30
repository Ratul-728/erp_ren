<?php

 session_start();
include_once("../common/conn.php");
include_once('../rak_framework/fetch.php');
include_once("../rak_framework/edit.php");
include_once("../rak_framework/log.php");

          extract($_REQUEST);
//print_r($_REQUEST);die;


            $fetchQCArrayVal = array('soid' => $oid,'product' => $pid,'store' => $sid);
            $thisRowID = fetchSingleDataByArray('qcsum',$fetchQCArrayVal,'id');

            
             $where_cond = "id=".$thisRowID;
			 $currPQty = fetchByID( 'qcsum','id', $thisRowID,'passqty');
             $currFQty = fetchByID( 'qcsum','id', $thisRowID,'failqty');

			 $new_pqty = $currPQty + $passqty;
             $new_fqty = $currFQty + $failqty;

             if(updateByID('qcsum','passqty',$new_pqty,$where_cond)){$msg .= $new_pqty." QC Passed record updated \n";}
             if(updateByID('qcsum','failqty',$new_fqty,$where_cond)){$msg .= $new_fqty." QC Failed record updated";}

            echo $msg;

?>