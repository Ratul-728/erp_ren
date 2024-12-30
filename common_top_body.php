<?php 
//company Info
			
$comname = $_SESSION["comname"];
$comemail = $_SESSION["comemail"];
$comcontact = $_SESSION["comcontact"];
$comaddress = $_SESSION["comaddress"];
$comlogo = $_SESSION["comlogo"];
$comweb = $_SESSION["comweb"];
$headerLogo = $_SESSION["doc_header_logo"]

//print_r($_SESSION);die;
			
?>
<!-- Fixed navbar -->
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid nav-left-padding">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
      <?php if($_SESSION["user"] != ''){
          $logodir = "hrqv.php";
      }else{
          $logodir = "profile.php";
      }?>
      <a class="navbar-brand" href="<?= $logodir ?>"><img src="./assets/images/site_setting_logo/<?=$comlogo ?>" alt="Bithut ERP"></a> </div>
    <div id="navbar" class="navbar-collapse collapse">
      <!--<ul class="nav navbar-nav">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
        <li class="active"><a href="dashboard.php">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
        <li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
            <li role="separator" class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a href="#">One more separated link</a></li>
          </ul>
        </li>
      </ul>-->
       <ul class="nav navbar-nav parent-menu">
        <li class="active" > &nbsp;
          <button class="navbar-toggle collapse in" data-toggle="collapse" id="menu-toggle-2"> <span class="fa fa-navicon" aria-hidden="true"></span></button>
        </li>
         <?php 
                $mod= $_GET['mod'];
				$qrysb="SELECT  distinct d.`id`, d.`Name`, d.`sl`,d.`landport` 
                        FROM  `mainMenu` m, hrAuth a, module d  
                        WHERE a.menuid=m.`id` and ifnull(m.isreport,0)<>1 and a.hrid=".$usr." and m.modl=d.id and d.st=1 order by d.sl"; //echo $qrysb;die;
				    $resultsb= $conn->query($qrysb);
				    if($resultsb->num_rows > 0){
					   while($rowsb = $resultsb->fetch_assoc()){ 
                           $mnsl=$rowsb["sl"]; 
                           $slnm=$rowsb["Name"]; 
                           $url1=$rowsb["landport"]."?mod=".$rowsb["id"]; 
           ?>
                        <li <?php if ($mod==$rowsb["id"]){ ?> class="active" <?php }?>>
                            <a href=<?php echo $url1;?>><?php echo $slnm;?><span class="caret"></span> </a>
                        </li>
		 <?php 			 }
				}?>
       <!-- <li class="active"><a href="dashboard.php">POS</a></li> 
        <li><a href="dashboard.php">HR</a></li>
        <li><a href="dashboard.php">CRM</a></li>
        <li><a href="dashboard.php">Payment</a></li> -->

        
      </ul>
      
      <ul class="nav navbar-nav navbar-right user-menu">
        <!-- <li><a href="../navbar/"><span class="fa fa-gear"></span> Setting</a></li> -->
        
        <li class="dropdown"> <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="fa fa-user-circle-o"></span> <span class="caret"></span> </a>
          <ul class="dropdown-menu">
            <?php if($_SESSION["user"] != ''){ ?>
                <li><a href="hrqv.php">Account</a></li>
            <?php } else { ?>
                <li><a href="profile.php">Account</a></li>
            <?php } ?>
<!--            <?=($_SESSION['usertype']==1)?'<li><a href="systemsetting.php?mod=5">System Setting</a></li>':''?>-->
<?php
    include_once("rak_framework/connection.php");
    include_once("rak_framework/fetch.php");
    $pagePriv = fetchTotalRecordByCondition('hrAuth','hrid = "'.$_SESSION['user'].'" AND menuid = 108','menu_priv');
    if($pagePriv == 1){
        
?>
             <li><a href="systemsetting.php?mod=5">System Setting</a></li>
<?php
    }
?>
            <li><a href="employee_profile.php">Profile</a></li>
            <li><a href="hc_char_modi.php">Change Password</a></li>
            <li role="separator" class="divider"></li>
        <?php if($_SESSION["user"] != ""){
            $dr = "hr.php?logout=1";
        }else{
            $dr = "customer_login.php";
        }
        ?>
            <li><a href="<?= $dr ?>">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
    <!--/.nav-collapse --> 
  </div>
</nav>
<!-- Fixed navbar -->
