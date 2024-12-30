<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require "common/conn.php";
require "rak_framework/connection.php";
require "rak_framework/fetch.php";
require_once "common/PHPExcel.php";

session_start();

$usr=$_SESSION["user"];
if($usr=='')
{ header("Location: ".$hostpath."/hr.php"); 
}
else
{
    $res= $_GET['res'];
    $msg= $_GET['msg'];
    $aid= $_GET['id'];
    
    $mnid = $_POST['cmbmonth']; if($mnid == '')$mnid = intval(date('n'));
    $yearid = $_POST["cmbyear"]; if($yearid == '')$yearid = date('Y');
    $cmbdept = $_POST["cmbdept"];

    if ($res==4)
    {
        $qry="SELECT `id`, `hrid`, `menuid`, `menu_priv` FROM `hrAuth` where id= ".$aid; 
       // echo $qry; die;
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
                       $uid=$row["id"];$hrid=$row["hrid"];
                        $menuid=$row["menuid"]; $menu_priv=$row["menu_priv"];  
                    }
            }
        }
    $mode=2;//update mode
    //echo "<script type='text/javascript'>alert('".$dt."')</script>";
    
    }
    else
    {
        $uid='';$hrid='0'; $menuid='0'; $menu_priv='0'; 
    $mode=1;//Insert mode
                     
    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
    */
    $currSection = 'assignemployee';
    $currPage = basename($_SERVER['PHP_SELF']);
    
    /*if ( isset( $_POST['submit'] ) ) {
           header("Location: ".$hostpath."/common/addpriv.php");
    }*/
    $mnhrid = $_POST['cmbempnm'];
    $modid  = $_POST["cmbmodule"]; if($modid == '') $modid = 0;
    if($mnhrid==''){$mnhrid=$hrid;}
    
    if ( isset( $_POST['export'] ) ) {
        
        $objPHPExcel = new PHPExcel(); 

        $startDate = new DateTime("$yearid-$mnid-01");
        $endDate = new DateTime("$yearid-$mnid-" . date('t', strtotime("$yearid-$mnid-01")));
        
        $dateInterval = new DateInterval('P1D'); // Create a DateInterval of 1 day
        $dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day')); // Generate a range of dates
        
        $formattedDates = [];
        foreach ($dateRange as $date) {
            $formattedDates[] = $date->format('j/M/Y'); // Format and store each date in day/month/year format
        }
        
        $columnIndex = 1;
        
        $cellReference = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . '1';
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellReference, "Employee");
        
        foreach($formattedDates as $todate){
            $columnIndex++;
            
            $cellReference = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . '1';
        
            // Set formatted date value
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellReference, $todate);
        }

                                
    			
        $firststyle='A2';
        $rowIndex = 3;
        $qryGetDep = "SELECT * FROM `department`";
        $resultGetDep = $conn->query($qryGetDep);
        while($rowGetDept = $resultGetDep->fetch_assoc()) {
            $qryEmp = "SELECT concat(e.firstname, ' ', e.lastname) empname, e.id empid
                                        FROM employee e left join hr h on h.emp_id=e.employeecode
                                        Where h.active_st = 1 and e.id != 136 and e.department = ".$rowGetDept["id"];
                    $resultEmp = $conn->query($qryEmp);
                    
                    while($rowEmp = $resultEmp->fetch_assoc()) { 
                        $empnm=$rowEmp["empname"];
                        $empid=$rowEmp["empid"];
                        
                        $columnIndex = 1;
                        $cellReference = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
                        
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellReference, $empnm);
                        foreach($formattedDates as $todate){
                            
                            $assignshift = 'No shift assign';
                            
                            $qryCh = "SELECT s.title `shift` FROM `assignshifthist` ash left join Shifting s on ash.shift = s.id
                                    WHERE ash.`empid` = '$empid' and ash.`effectivedt` = STR_TO_DATE('$todate', '%e/%b/%Y')";
                            $resultCh = $conn->query($qryCh); 
                            while($rowCh = $resultCh->fetch_assoc()) {
                                $assignshift = $rowCh["shift"];
                            }
                            $columnIndex++;
                            
                            $cellReference = PHPExcel_Cell::stringFromColumnIndex($columnIndex) . $rowIndex;
                        
                            // Set formatted date value
                            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellReference, $assignshift);
                        }
                        $rowIndex++;
                    }
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Assign Shift Report');
        $objPHPExcel->setActiveSheetIndex(0);
        $today=date("YmdHis");
        $fileNm="data/".'assign_shift'.$today.'.xls'; 
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save($fileNm);
        
        
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$fileNm);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($fileNm));
        ob_clean();
        flush();
        readfile($fileNm);
        exit;
    }
    
?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php  include_once('common_header.php');?> 
<style>
.privillages{
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 15px;
}
.privillages > div{
    padding: 0px 5px;
    margin-right: 5px;
    margin-bottom: 5px;
    border-bottom: 0px solid #c0c0c0;
    border-radius: 0px;
/*     background-color: #eeeeee; */
}

.privillages  input{
    margin: 0;
    padding: 0;
}  
    
.row.table-bordered div[class*="col-"] {
    padding-top: 15px;
    
}



.icheck-primary{
    margin-bottom: 0!important;
}
    
.row-striped:nth-of-type(odd){
  background-color: #efefef;
}

.row-striped:nth-of-type(even){
  background-color: #ffffff;
}
    .row-striped input[readonly]{
    background-color:#ffffff;
}

//Table
.tablecls {
    border-collapse: collapse;
    width: 100%;
}
.thdate {
  font-size: 18px;
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  background-color: #4a8a8f;
  color: white;
}

.tdemp {
    
    font-size: 14px;
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

.tdemp:nth-child(even) {
    background-color: #f2f2f2;
}

.tdemp:hover {
    background-color: #e3e3e3;
}

.table-container {
    height: calc(100vh - 250px);
    overflow-y: auto;
  }
  
  select{
    font-family: roboto, sans-serif!important;
}


.table-container{
  border-collapse: collapse;
  display: block; 
  overflow: auto; 
}

.table-container thead {
  transform: translateZ(0);
}
.table-container thead th {
    text-align: center;
}

.table-container tbody td:first-child {
    text-align: right!important;
    background-color: #4a8a8f;
    color: #FFF;
    transform: translateZ(0);
}

.panel.panel-info h6 {
    margin-top: 0px!important;
}
  </style>
</style>
<body class="form">
<?php  include_once('common_top_body.php');?>

<div id="wrapper"> 
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
            <i class="fa fa-group  icon"></i>
            <span>Assign Shift</span>
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
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <!--h1 class="page-title">Customers</a></h1-->
                    <p>
                    <!-- START PLACING YOUR CONTENT HERE -->
                        <form method="post" action="#"  id="form1">     
                            <div class="panel panel-info">
                                
                                <div class="panel-body">
                                    <span class="alertmsg"></span>
                                    <div class="well list-top-controls">
                                        <div class="row border">
                                            <div class="col-sm-3  text-nowrap">
                                                <h6>HR <i class="fa fa-angle-right"></i> Assign Shift</h6>
                                            </div>
                                            <div class="col-sm-9 text-nowrap">
                                                <div class="pull-right grid-panel form-inline">
                                                    (Field Marked * are required) 
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="row">
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="id" id="id" value="<?php echo $uid;?>"> 
                                                <label for="cmbmonth"> Month </label>
                                                <div class="form-group styled-select">
                                                <select name="cmbmonth" id="cmbmonth" class="form-control" >
    <?php 
    for ($m = 1; $m <= 12; ++$m) {
    $month = date('F', mktime(0, 0, 0, $m, 1)); 
    ?>  
													
                                                    <option value="<? echo $m; ?>" <? if ($mnid == $m) { echo "selected"; } ?>><? echo $month; ?></option>
    <?php 
    }
    
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="module" id="module" value="<?php echo $modid;?>"> 
                                                <label for="cmbyear">Year</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbyear" id="cmbyear" class="form-control" >
    <?php 
        $currentYear = date('Y');
        for ($i = -2; $i <= 5; $i++) {
            $year = (int)$currentYear + $i;
        
    ?>          
                                                    <option value="<? echo $year; ?>" <? if ($yearid == $year) { echo "selected"; } ?>><? echo $year; ?></option>
    <?php 
          }
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <input type="hidden"  name="module" id="module" value="<?php echo $modid;?>"> 
                                                <label for="cmbdept">Department</label>
                                                <div class="form-group styled-select">
                                                <select name="cmbdept" id="cmbdept" class="form-control" >
    <?php 
        $qrydept="SELECT `id`, `name` FROM `department` order by name"; 
        $resultdept = $conn->query($qrydept); 
        while($rowdept = $resultdept->fetch_assoc()) { 
            $uid=$rowdept["id"];$deptname=$rowdept["name"];
            if($cmbdept == '') $cmbdept = $uid;
            
        
    ?>          
                                                    <option value="<? echo $uid; ?>" <? if ($cmbdept == $uid) { echo "selected"; } ?>><? echo $deptname; ?></option>
    <?php 
          }
    ?>   
                                                </select>
                                                </div>
                                            </div>        
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-6">
                                            <label for="find"> </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="submit" name="find" value="Get"  id="find" > 
                                            </div>
                                            
                                        </div>
                                        <div class="col-lg-1 col-md-6 col-sm-6">
                                            <label for="export"> </label>
                                            <div class="form-group">
                            
                                            <button type="button" title="Export" name="export" id="export" class="form-control btn btn-default dropdown-toggle"   data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false"><i class="fa fa-download"></i></button>
                								<ul class="dropdown-menu exp-dropdown" aria-labelledby="export">
                									<li><button type="button" title="PDF" name="exportpdf" id="exportpdf" class="form-control"><i class="fa fa-file-pdf-o"></i> PDF</button></li>
                									<li><button type="submit" title="Excel" name="export" id="export" class="form-control"><i class="fa fa-file-excel-o"></i> Excel</button></li>
                								</ul>
                							</div>
                                            
                                        </div>
                                        <div class="col-lg-3 col-md-6 col-sm-6">
                                            <label for="copy"> </label>
                                            <div class="form-group">
                                                <input class="btn btn-lg btn-default" type="button" name="copy" value="Copy for Next Month"  id="copy" > 
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </form> 
                    <style>
                        
.tablecls th{
    padding: 10px 10px!important;
    font-size: 15px;
    font-weight: normal;
    text-align: center;
    font-family: roboto;
}
.tablecls td{padding: 5px;font-family: roboto;}
.tablecls td select{
    border-radius: 0;
    border: 1px solid #d5d5d5;
    padding: 5px;
    font-family: roboto;
    width: 120px;
}

.tablecls  tr >  td:first-child{
    white-space: nowrap;
} 
                        
                    </style>
                    <?php
                        if($_POST){
                    ?>
                        <form method="post" action="#"  id="form1">
                        <div class="table-container">
                            <table class = "tablecls display actionbtn no-footer " width="100%">
                                <thead>
                                    <tr>
                                      <th style="padding-left: 20px;" class = "thdate">Employee</th>
                            <?php
                                $startDate = new DateTime("$yearid-$mnid-01");
                                $endDate = new DateTime("$yearid-$mnid-" . date('t', strtotime("$yearid-$mnid-01")));
                                
                                $dateInterval = new DateInterval('P1D'); // Create a DateInterval of 1 day
                                $dateRange = new DatePeriod($startDate, $dateInterval, $endDate->modify('+1 day')); // Generate a range of dates
                                
                                $formattedDates = [];
                                foreach ($dateRange as $date) {
                                    $formattedDates[] = $date->format('j/M/Y'); // Format and store each date in day/month/year format
                                }
                                $totaldates = count($formattedDates);
                                
                                foreach($formattedDates as $todate){
                                    
                            ?>
                                <th style="padding-left: 20px;" class = "thdate"><?= $todate ?></th>
                            <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <!-- Replace the below row with actual employee names -->
                        <?php
                            $qryEmp = "SELECT concat(e.firstname, ' ', e.lastname) empname, e.id empid
                                        FROM employee e left join hr h on h.emp_id=e.employeecode
                                        Where h.active_st = 1 and e.id != 136 and e.id != 134 and e.department = ".$cmbdept; //Remove mehreen asaf
                            $resultEmp = $conn->query($qryEmp); 
                            while($rowEmp = $resultEmp->fetch_assoc()) { 
                                $empid=$rowEmp["empid"];$empnm=$rowEmp["empname"];
                        ?>
                                <tr class = "tdemp">
                                  <td><?= $empnm ?></td>
                                <?php
                                
                                    foreach($formattedDates as $todate){
                                        //Check already assigned or not
                                        $assignshift = '';
                                        $qryCh = "SELECT `shift` FROM `assignshifthist` WHERE `empid` = '$empid' and `effectivedt` = STR_TO_DATE('$todate', '%e/%b/%Y')";
                                        $resultCh = $conn->query($qryCh); 
                                        while($rowCh = $resultCh->fetch_assoc()) {
                                            $assignshift = $rowCh["shift"];
                                        }
                                ?>
                                  <td>
                                    <select class = "form-control shiftselect">
                                        <option value="0">Select Shift</option>
                                    <?php
                                        $qryShift = "SELECT * FROM `Shifting`";
                                        $resultShift = $conn->query($qryShift); 
                                        while($rowShift = $resultShift->fetch_assoc()) {
                                            $shiftid = $rowShift["id"];$shiftnm = $rowShift["title"];
                                    ?>
                                      <option value="<?= $shiftid ?>" data-emp = "<?= $empid ?>" data-date="<?= $todate ?>" <?php if($shiftid == $assignshift) echo "selected" ?>><?= $shiftnm ?></option>
                                    <?php } ?>
                                    </select>
                                  </td>
                                <?php } ?>
                                
                                </tr>
                        <?php } ?>
                                <!-- Repeat the above row structure for each employee -->
                              </tbody>
                            </table> 
                        </div>
                            <!-- /#end of panel -->  
                            <br><br>
                        </form> 
                        
                        <?php
                        }
                        ?>
                        <!-- START PLACING YOUR CONTENT HERE -->          
                    </p> 
                </div>
            </div>    
        </div>
    </div>
</div>
<!-- /#page-content-wrapper -->
    
    
<?php    include_once('common_footer.php');
if ($res==1){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }

    if ($res==2){
        echo "<script type='text/javascript'>messageAlert('".$msg."')</script>"; 
    }
?>

<script>
    $(document).ready(function(){
        // Attach click event handler to the button with id 'copy'
        $('#copy').click(function(){
            
            var dept = $('#cmbdept').val();
            var year = $('#cmbyear').val();
            var month = $('#cmbmonth').val();
			  swal({
			  title: "Are you sure you want to copy?",
			  text: "Once copied, it will replaced next month assigned shift!",
			  icon: "warning",
			  buttons: true,
			  dangerMode: true,
			  buttons: ['Cancel', 'Confirm Copy'],
			})
			.then((willDelete) => {
			  if (willDelete) {
				$.ajax({
                    url: 'phpajax/copyassignshift.php', 
                    method: 'POST', 
                    data: { 
                      dept: dept,
                      year: year,
                      month: month
                    },
                    success: function(response) {
                      messageAlert(response);
                    },
                    error: function(xhr, status, error) {
                      messageAlert("Error ["+status+"]: "+error);
                    }
                });
				
			  } else {
				
				  return false;
			  }
			});

			return false;

	
	    });
    });
</script>
<script>
$(function() {
  $('.table-container').scroll(function(ev) {
    /**
     * where the table scroll, change the position of header and first column
     */
    $('thead th').css('transform', 'translateY(' + this.scrollTop + 'px)');
    $('tbody td:first-child').css('transform', 'translateX(' + this.scrollLeft + 'px)');
  });
});

</script>


<script>
  $(document).ready(function() {
    $('.shiftselect').change(function() {
      
      var shiftId = $(this).val();
      
      var empId = $(this).find('option:selected').data('emp');
      var dateValue = $(this).find('option:selected').data('date');
      
      $.ajax({
        url: 'phpajax/assignshift.php', 
        method: 'POST', 
        data: { 
          shiftId: shiftId,
          empId: empId,
          date: dateValue
        },
        success: function(response) {
          messageAlert(response);
        },
        error: function(xhr, status, error) {
          messageAlert("Error ["+status+"]: "+error);
        }
      });
    });
  });
</script>

<?php
if($_POST['cmbempnm']){
?>

 <script>
$(document).ready(function(){
  
            //$(".icheck-primary  input[type=checkbox]").change(function() {
                
            //$(".icheck-primary  input[type=checkbox]").on('ifChanged',function() {
            $(".icheck-primary  input[type=checkbox]").on('change',function() {
                
                var isChecked = $(this).is(":checked");
                //var isChecked = this.checked;
                var chkValue;
              	var thisKey = $(this).attr('name');
                 var part = thisKey.split("_");
                 var key = part[0];
                 var mnuId = part[1];

                if (isChecked) {
                    chkValue = 1;
                } else {
                    chkValue = 0;
                }
              
               //alert('key:'+key+' | menuid:'+mnuId+' | val:'+chkValue);
              
              
        $.ajax({
          url: 'phpajax/setpriv.php', 
          method: 'POST',
          data: {
            key: key,
            menuid: mnuId,
            val:chkValue,
            targetuser:<?=$_POST['cmbempnm']?>,
          },
          success: function(response) {
            // Handle the successful response here
            //console.log('Success:', response);
            messageAlert(response);
          },
          error: function(xhr, textStatus, errorThrown) {
            // Handle any errors that occur during the request
            console.error('Error:', errorThrown);
            messageAlert(response);
          }
        });
  
   });
});//$(document).ready(function() {    
</script>   
    <?php
    }
    ?>
    <script>
		//convert pdf trigger;
			
			$("#exportpdf").on("click",function(){
                alert("I am here");
                var month = $('#cmbmonth').val();
        	    var year = $('#cmbyear').val();
        	    var dept = $('#cmbdept').val();
            
				var pdfurl = 'pdf_assign_shift.php?mod=4&month='+month+'&year='+year+'&dept='dept;
				location.href=pdfurl;
				
			});
		
		</script>
    
    
</body>
</html>
<?php }?>