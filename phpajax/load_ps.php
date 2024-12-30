<?php

require "../common/conn.php";
session_start();
$usr=$_SESSION["user"];
//echo $usr;die;
if($usr=='')
{
  header("Location: ".$hostpath."/hr.php");
}
else
{
    $output = '';
    $benifitype = $_POST["val"];
    $qry = "SELECT * FROM `benifitype` WHERE benifittype = '".$benifitype."'";
    $result = $conn->query($qry);
    while($row = $result->fetch_assoc()) {
        $id  = $row["id"];
        $tittle  = $row["title"];
        $output .= '                                     <div class="col-lg-3 col-md-6 col-sm-6">
                      	                                    
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <input type="hidden" id="ben" name="ben[]" value="'.$id.'">
                                                                    <input type="text" class="form-control" name="tittle" id="tittle[]" value="'.$tittle.'" disabled>
                                                                </select>
                                                                </div>
                                                            </div> 
                                                        </div>
                          	                            <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <input type="number" step = "any" class="form-control" id="bamount" name="bamount[]" value="" >
                                                            </div> 
                                                        </div> <!-- this block is for Benefit Amount--> 
                                                       
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-group styled-select">
                                                                    <select name="per[]" id="per" class="form-control">
                                                                        <option value="1" >No</option>
                                                                        <option value="2" >Yes</option>
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>
                                                        
                                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-group styled-select" >
                                                                    <select name="cycle[]" id="cycle" class="form-control">
                                                                        <option value="1">Monthly</option>
                                                                        <option value="2">Daily</option>
                                                                        <option value="3">Quarterly</option>
                                                                        <option value="4">Yearly</option>
                                                                    </select>
                                                                </div>
                                                            </div>        
                                                        </div>';
    }
}

echo $output;

?>