<?php
require "common/conn.php";
session_start();
$id=$_SESSION["user"];
$mod= $_REQUEST['mod'];
if($id=='')
{ 
    header("Location: ".$hostpath."/hr.php");
}
else
{
?>
<ul class="sidebar-nav nav-pills nav-stacked" id="menu">
	<?php
    	//hide dashbaord menu in following pages;
		$hideInMod = array(4);
		
		if(!in_array($_REQUEST['mod'],$hideInMod) && ($mod == 3 || $mod == 4)){
	?>
    <li <?=($currSection == 'dashboard')?'class="active"':''?>> <a href="dashboard_bill.php?mod=3"><span class="fa-stack fa-lg pull-left"><i class="fa fa-dashboard fa-stack-1x "></i></span> Dashboard</a></li>
	<?php
		}
	?>
<?php
   // $id=$_SESSION["user"];   // echo $id;
        $qry="SELECT m.`id`,m.`menuNm`,m.`icon_class`,m.`currSection`,a.menuid,a.menu_priv,m.`urllist` ,m.isnode  FROM `mainMenu` m,hrAuth a  
            WHERE a.menuid=m.`id` and ifnull(m.isreport,0)<>1 and a.hrid=".$id."  and m.modl=".$mod." and m.`activeSt`=1 and lvl=1 order by m.menu_sl"; 
        if ($conn->connect_error) { echo "Connection failed: " . $conn->connect_error; }
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                { 
                    $uid=$row["id"];$menuNm=$row["menuNm"]; $currSection1=$row["currSection"]; $menuid=$row["menuid"]; $menu_priv=$row["menu_priv"];
                    $icon_class=$row["icon_class"];	$isnode=$row["isnode"]; $urlm=$row["urllist"]."&mod=".$mod;
                    if($isnode==0)
                    {    
?>
                        <li  <?=($currSection == $currSection1)?'':''?>> <a href="dashboard_blank.php"><span class="fa-stack fa-lg pull-left"><i class="fa <?=$icon_class?> fa-stack-1x "></i></span><?php echo $menuNm; ?><!--<i class="arrow fa fa-angle-down">--></i></a> 
                            <ul class="sidebar-nav nav-pills nav-stacked" id="menu">
<?php                           $qrylvl2="SELECT m.`id`,m.`menuNm`,m.`icon_class`,m.`currSection`,a.menuid,a.menu_priv,m.`urllist` ,m.isnode  FROM `mainMenu` m,hrAuth a  
                                    WHERE a.menuid=m.`id` and ifnull(m.isreport,0)<>1 and a.hrid=".$id."  and m.modl=".$mod." and m.`activeSt`=1 and lvl=2
                                    and parentnode=".$uid."  order by m.menu_sl"; 
   
                            if ($conn->connect_error){echo "Connection failed: " . $conn->connect_error;}
                            else
                            {
                                $resultlvl2 = $conn->query($qrylvl2); 
                                if ($resultlvl2->num_rows > 0)
                                {
                                    while($rowlvl2 = $resultlvl2->fetch_assoc()) 
                                    { 
                                        $uid2=$rowlvl2["id"];$menuNm2=$rowlvl2["menuNm"]; $currSection2=$rowlvl2["currSection"];$menuid2=$rowlvl2["menuid"];$menu_priv2=$rowlvl2["menu_priv"];
                        				$icon_class2=$rowlvl2["icon_class"];$isnode2=$rowlvl2["isnode"];$urlm2=$rowlvl2["urllist"]."&mod=".$mod;
?>  
                                <li  <?=($currSection == $currSection2)?'class="active"':''?>> <a href="<?php echo $urlm2;?>"><span class="fa-stack fa-lg pull-left"><i class="fa <?=$icon_class?> fa-stack-1x "></i></span><?php echo $menuNm2; ?><!--<i class="arrow fa fa-angle-down"></i>--></a></li> 
<?php                               }
                                }
                            }
?>
                            </ul>
                        </li>
<?php               } 
                    else
                    {
?>
                        <li  <?=($currSection == $currSection1)?'class="active"':''?>> <a href="<?php echo $urlm;?>"><span class="fa-stack fa-lg pull-left"><i class="fa <?=$icon_class?> fa-stack-1x "></i></span><?php echo $menuNm; ?><!--<i class="arrow fa fa-angle-down">--></i></a></li> 
<?php            
                    }
                }
            }
        }
?>

<?php if($mod == 5 || $mod == 6 || $mod == 24){
    
}else{ ?>
    <li <?=($currSection == $currSection1 )?'':''?>> <a href="dashboard_blank.php"><span class="fa-stack fa-lg pull-left"><i class="fa fa fa-bar-chart fa-stack-1x "></i></span> Report <!-- <i class="arrow fa fa-angle-down"></i>--></a>
<?php } ?>        
<ul class="sidebar-nav nav-pills nav-stacked" id="menu">
<?php
    //$id=$_SESSION["user"];   // echo $id;
        $qry="SELECT m.`id`,m.`menuNm`,m.`icon_class`,m.`currSection`,a.menuid,a.menu_priv,m.`urllist`  FROM `mainMenu` m,hrAuth a  
        WHERE a.menuid=m.`id` and m.isreport=1 and a.hrid=".$id."  and m.modl=".$mod." and m.`activeSt`=1 order by m.menu_sl"; 
        if ($conn->connect_error){echo "Connection failed: " . $conn->connect_error;}
        else
        {
            $result = $conn->query($qry); 
            if ($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc()) 
                { 
                    $uid=$row["id"];$menuNm=$row["menuNm"]; $currSection1=$row["currSection"]; $menuid=$row["menuid"]; $menu_priv=$row["menu_priv"];
				    $icon_class=$row["icon_class"]; $urlm=$row["urllist"]."&mod=".$mod;
?>
            <li  <?=($currSection == $currSection1)?'class="active"':''?>> <a href="<?php echo $urlm;?>"><span class="fa-stack fa-lg pull-left"><i class="fa <?=$icon_class?> fa-stack-1x "></i></span><?php echo $menuNm; ?><!--<i class="arrow fa fa-angle-down"> --></i></a></li> 
<?php            
                }
            }
        }
?>
        </ul>    
    </li>
</ul>
<?php }?>