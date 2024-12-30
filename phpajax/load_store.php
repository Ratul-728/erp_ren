<?php
require "../common/conn.php";

session_start();

$pid = $_REQUEST['pid'];

?> 

    <option value=""> Select Store</option>
    <?php 
        $qry = "SELECT b.name, cs.freeqty, b.id FROM `chalanstock` cs LEFT JOIN branch b ON cs.storerome=b.id WHERE cs.freeqty > 0 and cs.product = ".$pid;
        $result = $conn->query($qry);
		while ($row = $result->fetch_assoc()) { 
		    $branchid = $row["id"]; $branchnm = $row["name"]; $qty = $row["freeqty"];
		?>
		    <option value="<?= $branchid ?>" data-qty="<?= $qty ?>"> <?= $branchnm ?> [<?= $qty ?>]</option>
	<?php	
		}
    ?>