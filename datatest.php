<?php
require "common/conn.php";
include_once('rak_framework/fetch.php'); 

$qry = "select m.transdt,m.isfinancial,c.glnm,d.* from glmst m, gldlt d,coa c where c.glno=d.glac and  m.vouchno=d.vouchno and  m.isfinancial in ('0','A') and m.vouchno in 
(
    select vouchno from gldlt where glac='203010400' and dr_cr='C'
 )";
$result = $conn->query($qry);
            if ($result->num_rows > 0)
            {
                while ($row = $result->fetch_assoc()) 
                {
                 echo   $row["transdt"].'-'.$row["isfinancial"].'-'.$row["glnm"].'-'.$row["glnm"];
                }
            }

?>