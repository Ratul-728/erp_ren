<?php
require "common/conn.php";
session_start();
$usr = $_SESSION["user"];
if ($usr == '') {header("Location: " . $hostpath . "/hr.php");
} else {
    $res  = $_GET['res'];
    $msg  = $_GET['msg'];
    $itid = $_GET['id'];

    if ($res == 1) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }
    if ($res == 2) {
        echo "<script type='text/javascript'>alert('" . $msg . "')</script>";
    }

    if ($res == 4) {

        $qry = "SELECT `vouchno`, DATE_FORMAT(`transdt`,'%e/%c/%Y') trdt, `refno`, `remarks` FROM `glmst` WHERE id = " . $itid;
        if ($conn->connect_error) {
            echo "Connection failed: " . $conn->connect_error;
        } else {
            $result = $conn->query($qry);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $trdt  = $row["trdt"];
                    $vouch = $row["vouchno"];
                    $ref   = $row["refno"];
                    $desc  = $row["remarks"];
                }
            }
        }
        $mode = 2; //update mode
        //echo "<script type='text/javascript'>alert('".$dt."')</script>";

    } else {
        $trdt  = '';
        $vouch = '';
        $ref   = '';
        $desc  = '';
        $mode  = 1; //Insert mode

    }

    /* common codes need to place every page. Just change the section name according to section
    these 2 variables required to detecting current section and current page to use in menu.
     */
    $currSection = 'glmaster';
    $currPage    = basename($_SERVER['PHP_SELF']);
    ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<?php include_once 'common_header.php'; ?>

<style>
    .custom-container {
    padding: 0 100px;
}
table.coa-table  li{
    list-style:none;
}
table.coa-table {
    width: 100%;
    text-align: center;
}
/*
table.coa-table tr td:nth-child(2) li , table.coa-table tr td:nth-child(2) li ul li{
    height: 20px;

}
.coa-table thead tr th:first-child{
    width: 85%;
}
.coa-table thead tr th{
    background: #00abe3;
    margin: 10px 0;
    padding: 10px 0;
    border: thin solid lightgray;
    color: white;
}

.coa-table thead tr th:nth-child(2){
    width: 15%;
    text-align: center;
}


.coa-table tbody tr td{

    margin: 10px 0;
    padding: 10px 0;
    border: thin solid lightgray;
}
.coa-table tbody tr td:first-child{
    text-align: left;
    padding-left: 20px;
}

/* caret style */

.tree-view ul, #myUL {
  list-style-type: none;
}



.tree-view .caret {
  cursor: pointer;
  -webkit-user-select: none; /* Safari 3.1+ */
  -moz-user-select: none; /* Firefox 2+ */
  -ms-user-select: none; /* IE 10+ */
  user-select: none;
}

.tree-view .caret::before {
  content: "\25B6";
  color: black;
  display: inline-block;
  margin-right: 6px;
}

.tree-view .caret-down::before {
  -ms-transform: rotate(90deg); /* IE 9 */
  -webkit-transform: rotate(90deg); /* Safari */'
  transform: rotate(90deg);
}

.tree-view .nested {
  display: none;
}

.tree-view .active {
  display: block;
}

</style>

<body class="form">
<?php include_once 'common_top_body.php'; ?>

<div id="wrapper">
  <!-- Sidebar -->
    <div id="sidebar-wrapper" class="mCustomScrollbar">
        <div class="section">
  	        <i class="fa fa-group  icon"></i>
            <span>GL Master Details</span>
        </div>
        <?php include_once 'menu.php'; ?>
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

                        <div class="tree-view custom-container">
                            <div class="row text-center">
                                <h3>Bithut Limited</h3>
                                <h6>Balance Sheet</h6>
                                <h6>As of 6 March, 2022</h6>
                            </div>
                            <!--table-- class="coa-table">
                                <thead>
                                    <tr>
                                    <th></th>
                                    <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--tr>
                                        <td>
                                            <ul>
                                                <li>Asset
                                                    <ul>
                                                        <li>Current Asset</li>
                                                        <li>Other asset</li>
                                                    </ul>
                                                </li>
                                                <li>Additional Assets</li>
                                            </ul>
                                    </td>
                                    <td>
                                          <ul>
                                                <li>
                                                    <ul>
                                                        <li></li>
                                                        <li>$200</li>
                                                    </ul>
                                                </li>
                                                <li>$322</li>
                                            </ul>
                                    </td>

                                    </tr>
                                    <tr>
                                        <td>

                                        </td>
                                    </tr>
                                </tbody>
                                <thead></thead>

                            </table-->


                    <ul id="myUL">
                    <li><span class="caret">Beverages</span>
                        <ul class="nested">
                        <li>Water</li>
                        <li>Coffee</li>
                        <li><span class="caret">Tea</span>
                            <ul class="nested">
                            <li>Black Tea</li>
                            <li>White Tea</li>
                            <li><span class="caret">Green Tea</span>
                                <ul class="nested">
                                <li>Sencha</li>
                                <li>Gyokuro</li>
                                <li>Matcha</li>
                                <li>Pi Lo Chun</li>
                                </ul>
                            </li>
                            </ul>
                        </li>
                        </ul>
                    </li>
                    </ul>


                        </div>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- /#page-content-wrapper -->
<?php include_once 'common_footer.php'; ?>

<!--Number formatting-->

  <script>
                    var toggler = document.getElementsByClassName("caret");
                    var i;

                    for (i = 0; i < toggler.length; i++) {
                    toggler[i].addEventListener("click", function() {
                        this.parentElement.querySelector(".nested").classList.toggle("active");
                        this.classList.toggle("caret-down");
                    });
                    }
                    </script>

</body>
</html>
<?php } ?>