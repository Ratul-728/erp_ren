<?php

require "../common/conn.php";
session_start();
if(!$_SESSION['user']){exit('Invalid access');}

if (isset($_GET['term'])) {
    $searchTerm = $_GET['term'];
    $qryitm = $conn->prepare("
        SELECT i.id, i.image, i.name, i.barcode, 
               round(i.vat, 2) vat, round(i.ait, 2) ait, 
               round(i.rate, 2) rate, round(i.cost, 2) cost, 
               (COALESCE(s.freeqty,0) + COALESCE(s.futureqty,0) + COALESCE(s.backorderqty,0)) freeqty
        FROM item i
        LEFT JOIN stock s ON i.id = s.product 
        WHERE (COALESCE(s.freeqty,0) + COALESCE(s.futureqty,0) + COALESCE(s.backorderqty,0)) > 0
        AND i.name LIKE ?
        ORDER BY i.name
    ");
    
    $qryitm->bind_param("s", $likeTerm);
    $likeTerm = "%$searchTerm%";
    $qryitm->execute();
    $resultitm = $qryitm->get_result();

    $items = [];
    while ($rowitm = $resultitm->fetch_assoc()) {
        $items[] = [
            'id' => $rowitm['id'],
            'image' => $rowitm['image'],
            'name' => $rowitm['name'],
            'barcode' => $rowitm['barcode'],
            'stock' => $rowitm['freeqty'],
            'cost' => $rowitm['rate'],
            'prdcost' => $rowitm['cost'],
            'vat' => $rowitm['vat'],
            'ait' => $rowitm['ait']
        ];
    }

    echo json_encode($items);
}
?>
