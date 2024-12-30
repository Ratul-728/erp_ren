<?php

require_once("common/conn.php");
session_start();

$usr=$_SESSION["user"];

if($usr==''){ 
	header("Location: ".$hostpath."/hr.php");
}

else

{

    $res= $_GET['res'];

    $msg= $_GET['msg'];

    $id= $_GET['id'];


    if ($res==4)

    {

        $qry="SELECT `id`, `year`, `appraisalType`, `hrid`, `managerrecomandation`, `hrdrecommendation`, `mdrecomendation`, `hraction`, `effectivedt` FROM `appraisal` WHERE id = ".$id; 

        //echo $qry; die;

        if ($conn->connect_error)

        {

            echo "Connection failed: " . $conn->connect_error;

        }

        else

        {

            $result = $conn->query($qry); 

            if ($result->num_rows > 0)

            {

                while($row = $result->fetch_assoc()) 

                    { 
                        $year=$row["year"];  $hrr=$row["hrdrecommendation"];   $mdr=$row["mdrecomendation"];  $hraction=$row["hraction"];
                        $atype=$row["appraisalType"];   
                        $hrid=$row["hrid"];
                        $mnr=$row["managerrecomandation"];
                        $effectivedt=$row["effectivedt"];
                        
                        $effectivedt = implode("/", array_reverse(explode("-", $effectivedt)));

                    }

            }

        }

    $mode=2;//update mode

    //echo "<script type='text/javascript'>alert('".$dt."')</script>"; 

    }

    else

    {
        $year = ''; $atype = ''; $hrid = ''; $mnr = ''; $hrr = ''; $mdr = ''; $hraction = ''; $effectivedt = '';
        $mode=1;//Insert mode

                    

    }



    /* common codes need to place every page. Just change the section name according to section

    these 2 variables required to detecting current section and current page to use in menu.

    */

    $currSection = 'appraisal';

    $currPage = basename($_SERVER['PHP_SELF']);

?>

<!doctype html>

<html xmlns="http://www.w3.org/1999/xhtml">

<?php  include_once('common_header.php');?>



<body class="form">

<?php  include_once('common_top_body.php');?>



<div id="wrapper"> 

  <!-- Sidebar -->

    <div id="sidebar-wrapper" class="mCustomScrollbar">

        <div class="section">

  	        <i class="fa fa-group  icon"></i>

            <span>Appraisal Details</span>

        </div>

        <?php  include_once('menu.php');?>

	    <div style="height:54px;">

	    </div>

    </div>

   <!-- END #sidebar-wrapper --> 

   <!-- Page Content -->

    <div id="page-content-wrapper">

        <div class="container-fluid pagetop">

            <div class="row">

                <div class="col-lg-12">

                    <p>&nbsp;</p> <p>&nbsp;</p>

                    <p>

                        <form method="post" action="common/addappraisal.php"  id="form1" enctype="multipart/form-data"> <!--onsubmit="javascript:return WebForm_OnSubmit();" -->

                            <div class="panel panel-info">

      			                <div class="panel-heading"><h1>Appraisal Information</h1></div>

				                <div class="panel-body">

                                    <span class="alertmsg"></span> <br> <p>(Field Marked * are required) </p>

                                    <div class="row">
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            
                                            <div class="form-group">

                                                <label for="code">Year *</label>
                                                
                                                <input type = "hidden" name = "iid" value = "<?= $_GET['id'] ?>">

                                                <input type="text" class="form-control" id="year" name="year" value="<?php echo $year;?>" required>

                                            </div>        

                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbitmcat">Appraisal Type*</label>
                                                <div class="form-group styled-select">
                                                    <select name="atype" id="atype" class="form-control">
<?php $qrycoms="SELECT `id`, `title` FROM `appraisalType` order by id"; $resultcoms = $conn->query($qrycoms); if ($resultcoms->num_rows > 0) {while($rowcoms = $resultcoms->fetch_assoc()) 

      { 
          $comsid= $rowcoms["id"];  $comsnm=$rowcoms["title"];
?>                                                          

                                                    <option value="<?php echo $comsid; ?>" <?php if ($comsid == $atype) { echo "selected"; } ?>><?php echo $comsnm; ?></option>
<?php  }}?>                                                       
                                                  </select>
                                              </div>
                                          </div>
                                        </div>

            	                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">Employee </label>
                                                <div class="form-group styled-select">
                                                    <select name="empid" id="empid" class="form-control">
<?php $qrymu="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["empname"];
?>                                                          

                                                    <option value="<?php echo $mid; ?>" <?php if ($hrid == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>  
                                                    </select>
                                                </div>
                                          </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            
                                            <div class="form-group">

                                                <label for="code">Manager Recomandation *</label>

                                                <input type="text" class="form-control" id="mnr" name="mnr" value="<?php echo $mnr;?>" required>

                                            </div>        

                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            
                                            <div class="form-group">

                                                <label for="code">HR Recomandation *</label>

                                                <input type="text" class="form-control" id="hrr" name="hrr" value="<?php echo $hrr;?>" required>

                                            </div>        

                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            
                                            <div class="form-group">

                                                <label for="code">MD Recomandation *</label>

                                                <input type="text" class="form-control" id="mdr" name="mdr" value="<?php echo $mdr;?>" required>

                                            </div>        

                                        </div>
                                        
                                         <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="cmbprdtp">HR Action </label>
                                                <div class="form-group styled-select">
                                                    <select name="hraction" id="hraction" class="form-control">
<?php $qrymu="SELECT `id`, concat(`firstname`, ' ', `lastname`) empname FROM `employee` order by empname"; $resultmu = $conn->query($qrymu); if ($resultmu->num_rows > 0) {while($rowmu = $resultmu->fetch_assoc()) 

      { 
          $mid= $rowmu["id"];  $mnm=$rowmu["empname"];
?>                                                          

                                                    <option value="<?php echo $mid; ?>" <?php if ($hraction == $mid) { echo "selected"; } ?>><?php echo $mnm; ?></option>
<?php  }}?>  
                                                    </select>
                                                </div>
                                          </div>
                                        </div>
                                        
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                        <label for="effect_dt">Effective Date*</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control datepicker" id="effective_dt" name="effective_dt" value="<?php echo $effectivedt;?>" required>
                                            <div class="input-group-addon">
                                             <span class="glyphicon glyphicon-th"></span>
                                            </div>
                                        </div>     
                                    </div>
                                                                


                                    </div>

                                </div>

                            </div> 

                            <!-- /#end of panel -->      

                            <div class="button-bar">

                                <?php if($mode==2) { ?>

    	                        <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="update" value="Update Appraisal Value"  id="update" > <!-- onclick="javascript:messageAlert('Event is fired from the page and messaged is pased via parameter. will be desolve in 5 sec')" -->

                                <?php } else {?>

                                <input  dat a-to="pagetop" class="btn btn-lg btn-default top" type="submit" name="add" value="Add Appraisal Value"  id="submit" >

                                <?php } ?>  
                            <a href = "./appraisalList.php?pg=1&mod=4">
                                <input class="btn btn-lg btn-default" type="button" name="cancel" value="Cancel"  id="cancel" >
                            </a>

                            </div>        

                        </form>       

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- /#page-content-wrapper -->

<?php    include_once('common_footer.php');?>
<?php

    if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>
</body>

</html>

<?php }?>