
<?php 
require "common/conn.php";
session_start();
$usr=$_SESSION["customer"];

$isti = $_GET["isit"];

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
   <?php
     include_once('common_header.php');
    ?>

<style>
        .row-body {
            background-color: white;

            padding: 10px;
        }

        .row-head {
            background-color: white;
        }

        .col-md-8,
        .col-md {
            border: 1px solid lightgray;

        }

        .col-divider {
            padding: 0px, 10px;
            margin: 0px 10px;
        }

        .big-col,
            {
            padding: 10px;
        }

        .small-col {
            padding: 0px;
        }

        .issue-header,
        .status {
            margin: 15px;

        }

        .issue-header {
            margin-bottom: 0px;
        }

        .status {
            margin-left: 20px;
        }

        .cus-hr {
            margin: 5px;
        }

        #issue-title {
            border: none;
            border-bottom: 0.5px solid lightgray;
            width: 80%;
            font-size: 18px;
            box-shadow: 2px;
            margin: 15px;

        }

        .originate-img,
        .res-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .cng-op {
            float: right;
            color: gray;
        }

        .res-block {
            margin: 5px 0px;
        }

        .head-bar {
            color: white;
            font-size: 16px;
            padding: 10px;
            background-color: rgb(81, 185, 255);
        }

        .add-op {}

        .small-col-op {
            padding: 10px;
        }

        .issue-row {
            background-color: rgb(81, 185, 255);
            color: white;
        }

        .issue-details {
            margin: 15px;
        }

        .fa-fire {
            color: orangered;
            margin-top: 5px;
            margin-left: 3px;
        }



        .priority {
            float: right;
            margin-top: 17px;
        }

        .image-sec img {
            max-width: 150px;
            margin: 5px;
        }

        .button-close {
            padding: 10px 40px;
            background-color: rgb(81, 185, 255);;
            color:white;
            border: none;
            margin: 10px;
        }

        .button-edit {
            padding: 10px 40px;
            background-color: white;
            color: rgb(44, 44, 44);
            border: 0.5px solid rgb(196, 196, 196);
            margin: 10px;
        }
        .originate, .responsible, .start-date, .end-date, .issue-type, .prod-title{
            margin-bottom: 10px;
        }
    </style>

<body class="form deal-entry">
    <?php  include_once('common_top_body.php');?>

<div id="wrapper">
     <?php
        include_once('menu.php');
    ?>
    <br>
    <br>
<?php $qry="SELECT a.`id` id, a.`tikcketno`, a.`sub`, a.`organization`, c.name `issuetype`, e.name `issuesubtype`, d.name `severity`, concat_ws(' ', b.firstname, b.lastname) `assigned`, f.stausnm `status`, 
a.`reporter`, a.`channel`, a.`issuedetails`, a.`issuedate`,DATE_FORMAT(`probabledate`,'%e/%c/%Y') `probabledate`, g.name `product`, a.`accountmanager`, h.name makeby, j.hrName acm,i.name orname
FROM `issueticket` a 
LEFT JOIN employee b ON a.`assigned` = b.id 
LEFT JOIN issuetype c ON a.`issuetype` = c.id
LEFT JOIN issuepriority d ON a.`severity` = d.id
LEFT JOIN issuesubtype e ON a.`issuesubtype` = e.id
LEFT JOIN issuestatus f ON a.`status` = f.id
LEFT JOIN item g ON a.`product` = g.id
LEFT JOIN contact h ON a.`makeby` = h.id
LEFT JOIN organization i ON a.organization = i.id
LEFT JOIN hr j ON i.salesperson = j.id
where a.tikcketno='".$isti."'"; 
       //echo $qry; die;
       $result = $conn->query($qry); 
        if ($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc()){
                
?>
    <div class="container">
        <div class="row ">
            <div class="col-md-8 big-col row-head">
                <div class="row issue-row">
                    <div class="issue-header">
                        <h1><?= $row["tikcketno"] ?></h1>
                    </div>
                </div>
                <div class="row-body">
                    <div class="row">
                        <div class="status">Status: <?= $row["status"] ?></div>
                    </div>
                    <div class="row priority">
                        <?php if($row["severity"] == "High"){ ?>
                        <span>High Priority  </span> <i class="fas fa-fire"></i>
                        <?php }else{ ?>
                            <span><?= $row["severity"] ?> Priority </span><i class="fas fa-burn"></i>
                        <?php } ?>
                    </div>

                    <hr class="cus-hr">
                    <div class="title">
                        <div id="issue-title">
                            <h6><?= $row["sub"] ?> </h6>
                        </div>
                    </div>

                    <div class="issue-details"><?= $row["issuedetails"] ?>
                    </div>

                    <div class="image-sec">
            <?php $qryimg = "SELECT photo FROM `issuephoto` where issueticket = '".$isti."'"; 
                  $resultimg = $conn->query($qryimg); 
                  while($rowimg = $resultimg->fetch_assoc()){
            ?>
                        <a href=""><img src="images/upload/issue/<?= $rowimg["photo"] ?>" alt=""> </a>
            <?php } ?>

                    </div>

                    <br>

                    <hr class="cus-hr">

                    <div class="action-btn row">
                        <a href = "./issuecustomerList.php"><button type="button" class="button-close">Close</button></a>
                        <a href = "./issuecustomer.php?res=4&msg=Update Data&id=<?= $row["id"] ?>"><button type="button" class="button-edit">Edit</button></a>
                    </div>
                </div>
            </div>
            <div class="col-divider">

            </div>
            <div class="col-md small-col">

                <div class="head-bar">
                    Pending Since: <?= $row["issuedate"] ?>
                </div>
                <div class="small-col-op">
                    <div class="prod-title">
                        <h5>Company</h5>
                        
                        <hr class="cus-hr">

                        <span><?= $row["orname"] ?></span>
                    </div>
             

                    <div class="issue-type">
                        <h5>Issue Type</h5>
                        <hr class="cus-hr">

                        <span><?= $row["issuetype"] ?></span>
                    </div>
                    

                    <div class="originate">
                        <h5>Originated By:</h5>

                        <hr class="cus-hr">

                        <span> <img class="originate-img" src="images/profile_picture/profile.jpg" alt=""> <?= $row["makeby"] ?></span>

                    </div>

                    

                    <div class="reponsible">
                        <div class="res-block">
                            <h5>Responsible Person</h5>
                            
                            <hr class="cus-hr">

                            <span> <img class="res-img" src="images/profile_picture/profile.jpg" alt=""> <?= $row["acm"] ?></span>
                        </div>
                        
                    </div>

                   

                    <div class="start-date">
                        <h5>Created on</h5>
                        <hr class="cus-hr">

                        <span> <?= $row["issuedate"] ?></span>
                    </div>

                

                    <div class="end-date">
                        <h5>Deadline</h5>
                        <hr class="cus-hr">

                        <span> 8/3/2021</span>
                    </div>

                </div>


            </div>


        </div>
        </div>

<?php }} ?>

<?php
        include_once('common_footer.php');
    ?>

        <script src="./assets/js/jquery.min.js"></script>
        <script src="./assets/js/bootstrap.min.js"></script>
        <script src="./assets/js/fontawesome.js"></script>

</body>

</html>