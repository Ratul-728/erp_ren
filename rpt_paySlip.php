<?php
require "common/conn.php";
session_start();
$usr=$_SESSION["user"];
$empid=$_GET['eid']; 
$yr=$_GET['yr']; 
$mn=$_GET['mn'];
$month_name = date("F", mktime(0, 0, 0, $mn, 10));
//echo $usr;die;
if($usr=='')
{ header("Location: ".$hostpath."/hr.php");
}
else
{
     $qry="SELECT e.id,s.salaryyear,MONTHNAME(STR_TO_DATE(s.salarymonth, '%m')) mnth,s.hrid,concat(e.firstname,' ',e.lastname) emp, e.employeecode empcode
                            ,s.benft_1 ,s.benft_2 ,s.benft_3 ,s.benft_4 ,s.benft_5 
                            ,s.benft_6 ,s.benft_7 ,s.benft_8 ,s.benft_9 ,s.benft_10,s.benft_11,s.benft_13,s.privilage,s.total,s.notes
                            , dept.name deptname, desi.name desiname
                            FROM monthlysalary s left JOIN hr h  ON s.hrid=h.id left join employee e on h.emp_id=e.employeecode left JOIN hraction hra ON hra.hrid = s.hrid 
                            LEFT JOIN department dept ON dept.id = hra.postingdepartment 
                            LEFT JOIN designation desi ON desi.id = hra.designation
                            where s.salaryyear='$yr' and s.salarymonth='$mn' and e.id=$empid"; 
       //echo  $qry;die;
        $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        { 
            while($row = $result->fetch_assoc()) 
            { 
                $nm=$row['emp'];
                $code=$row['empcode'];
                $basic=$row['benft_1'];
                $hRent=$row['benft_2'];
                $medical=$row['benft_3'];
                $transport=$row['benft_4'];
                $late=$row['benft_5'];
                $ait=$row['benft_11'];
                $tot1=$basic+$hRent+$medical+$transport;
                $tot2=$ait+$late;
                $tot=$row['total'];
                $notes=$row['notes'];
                $othererning=0;
                $otherdeduction=0;
            }
        }
       $fdt=date("d/m/Y")
    
?>

<?php
     include_once('common_header.php');
?>
<body class="form">
    
<?php
    include_once('common_top_body.php');
?>

    <link href="css/fonts.css" rel="stylesheet">
    <style>
        body {
            padding: 0;
            margin: 0;
        }

        .wrapperPayslip {
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .wrapperPayslip table {
            font-size: 15px;
            width: 100%;
            border: 0px solid #000;
        }

        .wrapperPayslip table.header td {
            width: 50%;
        }

        .wrapperPayslip table.header td:last-child {
            text-align: right;

        }

        .wrapperPayslip .title {
            font-size: 25px;
            border: 0px solid #000;
            padding: 20px 0px;
            margin: 0;
        }

        .wrapperPayslip hr {
            margin: 0;
            margin-bottom: 5px;
        }

        .wrapperPayslip .address td {
            width: 33%;

        }

        .wrapperPayslip .address {
            margin-bottom: 5px;
        }

        .wrapperPayslip .detail th {
            background-color: #043335;
            color: #fff;
            padding: 5px;

        }

        .wrapperPayslip .detail td {
            padding: 5px 10px;
            font-size: 13px;
        }

        .wrapperPayslip .detail th:nth-child(1),
        .wrapperPayslip .detail th:nth-child(3) {
            text-align: left;
            width: 35%;
        }

        .wrapperPayslip .detail th:nth-child(2),
        .wrapperPayslip .detail th:nth-child(4) {
            text-align: right;
        }

        .wrapperPayslip .detail td:nth-child(2),
        .wrapperPayslip .detail td:nth-child(4) {
            text-align: right;
        }

        .wrapperPayslip .detail th:nth-child(1) {}


        .wrapperPayslip .line {
            border-right: 1px solid #043335;
        }
        .wrapperPayslip dir{
    margin: 5;
    padding-left: 15px;
}

.printButton{
    padding: 20px;
}

.printButton input{
    background-color: #043335;
    color: #fff;
    padding: 7px 15px;
    border: 0;
    font-weight: bold;
    border-radius: 3px;
    cursor: pointer;
}

    </style>

<div id="wrapper"> 
    <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>Stock</span>
        </div>
        <?php include_once('menu.php'); ?>
       
        <div style="height:54px;"></div>
    </div>
    <!-- END #sidebar-wrapper --> 
    <!-- Page Content -->
    <div id="wrapperPayslip" class="wrapperPayslip">
        <table class="header">
            <tr>
                <td><img src="assets/images/site_setting_logo/logo_payslip.png" width="150" alt=""></td>
                <td>
                    <h1 class="title">PAY SLIP</h1>
                    <h1 class="title"><?php echo 'For the month  #'.$month_name.'-'.$yr;?> </h1>
                    
                </td>
            </tr>
        </table>
        <hr>
        <table class="address">
            <tr>
                <td><strong>Name:</strong>: <?php echo $nm; ?></td>
                <td style="text-align: center;"><strong>Employee ID:</strong>: <?php echo $code; ?></td>
                <td style="text-align: right;"><strong>Date:</strong>: <?php echo $fdt;?></td>
            </tr>
        </table>
        <table border="0" width="100%" class="detail" cellpadding="0" cellspacing="0">
            <tr>
                <th>Earnings</th>
                <th>Amount</th>
                <th>Deduction</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>
                    <strong>Salary</strong>
                    <dir>
                        Basic<br>
                        Rent<br>
                        Transportation<br>
                        Medical
                    </dir>
                </td>
                <td class="line">
                    <?php echo number_format($basic,2);?><br>
                    <?php echo number_format($hRent,2);?><br>
                    <?php echo number_format($transport,2);?><br>
                    <?php echo number_format($medical,2);?><br>

                </td>
                <td>
                    <strong>Others</strong>
                    <dir>
                        Loan<br>
                        Advance<br>
                        Late<br>
                        TAX
                    </dir>
                </td>
                <td>
                    <?php echo '-'?><br>
                    <?php echo '-'?><br>
                    <?php echo number_format($late,2);?><br>
                    <?php echo number_format($ait,2);?><br>
                </td>
            </tr>
             <tr>
                <td><strong>Sub Total</strong></td>
                <td class="line"><strong><?php echo number_format($tot1,2);?></strong></td>
                <td><strong>Sub Total</strong></td>
                <td><strong><?php echo number_format($tot2,2);?></strong></td>
            </tr>
            <tr>
                <td>
                    <strong>Other</strong>
                    <dir>
                        Bonus<br>
                        Allowance
                    </dir>
                </td>
                <td class="line">
                    <?php echo '-'?><br>
                    <?php echo '-'?>
                </td>
                <td>
                    <strong>Others</strong>
                    <dir>

                    </dir>
                </td>
                <td>

                </td>
            </tr>


            <tr>
                <td><strong>Others Total</strong></td>
                <td class="line"><strong><?php echo number_format($othererning,2);?></strong></td>
                <td><strong>Others Total</strong></td>
                <td><strong><?php echo number_format($otherdeduction,2);?></strong></td>
            </tr>
            <tr>
                <td><strong>Total Earning</strong></td>
                <td class="line"><strong><?php echo number_format($tot1+$othererning,2);?></strong></td>
                <td><strong>Total Deduction</strong></td>
                <td><strong><?php echo number_format($tot2+$otherdeduction,2);?></strong></td>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th style="text-align:right!important;"><strong>Net Pay</strong></th>
                <th><strong><?php echo number_format($tot,2);?></strong></th>
            </tr>
        </table>
        <table class="address">
            <tr>
                Notes:<?php echo $notes;?>
            </tr>
        </table>
    </div>

    <div class="printButton">
        <input type="button" onclick="printDiv('wrapperPayslip')" value="Print">
    </div>
    
   
</div>
<!-- /#page-content-wrapper -->

<?php
	include_once('common_footer.php');
?>


<script>
    
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
}


</script>

</body>
</html>



<?php }?>