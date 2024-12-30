<?php
session_start();
$action = $_REQUEST['action'];  

if($action == "datagrid_quotation_price_approval") { 
    
    include_once "../common/conn.php";
    include_once('../rak_framework/fetch.php');
    require "../common/user_btn_access.php";
    ini_set('display_errors', 0);  // Enable error display during development for debugging
    
    $con = $conn;
    
    // Read value (initialize variables to handle sorting and pagination)
    $searchValue = isset($_REQUEST['search']['value']) ? $_REQUEST['search']['value'] : '';  // Search value
    $row = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;  // Starting row for pagination
    $rowperpage = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;  // Rows per page for pagination
    $columnIndex = isset($_REQUEST['order'][0]['column']) ? $_REQUEST['order'][0]['column'] : 0;  // Column index for sorting
    $columnSortOrder = isset($_REQUEST['order'][0]['dir']) ? $_REQUEST['order'][0]['dir'] : 'asc';  // Sort order ('asc' or 'desc')
    $columnName = isset($_REQUEST['columns'][$columnIndex]['data']) ? $_REQUEST['columns'][$columnIndex]['data'] : 'id';  // Column name for sorting

    // Search query if a search value exists
    $searchQuery = "";
    if($searchValue != '') {
        $searchQuery = " AND (p.barcode LIKE '%".$searchValue."%' 
                          OR p.name LIKE '%".$searchValue."%' 
                          OR p.rate LIKE '%".$searchValue."%' 
                          OR a.new_price LIKE '%".$searchValue."%' 
                          OR u.hrName LIKE '%".$searchValue."%'
                          OR a.order_id LIKE '%".$searchValue."%'
                          OR o.name LIKE '%".$searchValue."%')";  // Added search for order_id and customer name
    }

    // Query for total records without filtering
    $strwithoutsearchquery = "
        SELECT
            a.id,
            a.order_id,
            p.image,
            p.cost AS purchase_price,
            p.rate AS sale_price,
            a.new_price AS new_sale_price,
            a.reason,
            DATE_FORMAT(a.makedt, '%e/%c/%Y') requested_on,
            p.barcode,
            p.id item_id,
            p.name AS product,
            u.hrName AS requested_by,
            o.orgcode AS customer_id,
            o.name AS customer,
            concat(emp.firstname, ' ', emp.lastname) approved, 
            date_format(a.`approvedt`,'%d/%b/%Y') `approvedt`,
            a.state
        FROM approval_quotation_price_change a
        LEFT JOIN item p ON a.item_id = p.id
        LEFT JOIN hr u ON a.makeby = u.id
        LEFT JOIN organization o ON a.customer_id = o.id
        LEFT JOIN hr h2 ON h2.id=a.approved_by 
        LEFT JOIN employee emp ON emp.employeecode=h2.emp_id
        WHERE 1=1 " . $searchQuery;

    // Execute the query for total records without search
    $result = mysqli_query($con, $strwithoutsearchquery);
    $totalRecords = $result->num_rows;

    // Fetch records with search, sorting, and pagination
    $empQuery = $strwithoutsearchquery . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT " . $row . "," . $rowperpage;

    $empRecords = mysqli_query($con, $empQuery);
    $data = array();
    $sl = $row;  // Start row index

    // Fetch the records and build the response data array
    while ($row = mysqli_fetch_assoc($empRecords)) {
        
        $seturl = "phpajax/update_approval.php?action=quotation_price_approval&res=4&msg='Update Data'&id=".$row['id']."&st=". $row['state']."&mod=24&item_id=".$row['item_id']."&order_id=".$row['order_id']."&saleprice=".urlencode($row['sale_price']);


        // Generate button array
        $btns = array();
        $btns[0] = array('accept', $seturl, 'class="btn btn-info btn-xs actionbtn"  title="Approval Action"');

        // Determine the action button based on the state
        if ($row["state"] == 0) {
            $urlas = '<a class="btn btn-info btn-xs actionbtn" title="Action" href="' . $seturl . '"><i class="fa fa-check"></i></a>';
        } else if ($row["state"] == 1) {
            $urlas = '<a class="btn btn-info btn-xs" title="Action" disabled>Accepted<i class="fa fa-check"></i></a>';
        } else {
            $urlas = '<a class="btn btn-info btn-xs" title="Action" disabled>Declined<i class="fa fa-check"></i></a>';
        }

        // Image URL
        $photo = "assets/images/products/300_300/" . $row["image"];
        
        // Build the data array for each row
        $sl++;
        $data[] = array(
            "id" => $row['id'],
            "order_id" => $row['order_id'],
            "barcode" => $row['barcode'],
            "image" => '<img src=' . $photo . ' width="50" height="50">',
            "product" => $row['product'] .'<br>('.$row['barcode'].')',
            "purchase_price" => $row['purchase_price'],
            "sale_price" => $row['sale_price'],
            "new_sale_price" => $row['new_sale_price'],
            "reason" => $row['reason'],
            "requested_by" => $row['requested_by'],
            "requested_on" => $row['requested_on'],
            "customer_id" => $row['customer_id'],
            "customer" => $row['customer'] .' ('.$row['customer_id'].')',
            "approved" => $row['approved'],
            "approvedt" => $row['approvedt'],
            "state" => $row['state'],
            "action_buttons"=> $urlas
        );
    }

    // Prepare the response array
    $response = array(
        "draw" => intval($_REQUEST['draw']),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecords,
        "aaData" => $data,
    );

    // Return the response in JSON format
    echo json_encode($response);
}
?>
