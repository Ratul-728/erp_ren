<?php
require "../common/conn.php";

session_start();

$user = $_SESSION["user"];
//print_r($_POST);die;





$name = $_POST["newItem"];

$type = $_POST["type"];
 
 
if($type == "department"){   
    $qry="insert into department (`name`)  values('".$name."')" ;
}
else if($type == "jobarea"){   
    $qry="insert into JobArea (`Title`)  values('".$name."')" ;
}
else if($type == "jobtype"){   
    $qry="insert into JobType (`Title`, makedt, makeby, st)  values('".$name."', sysdate(), '".$user."', 1)" ;
}
else if($type == "designation"){   
    $qry="insert into designation (`name`)  values('".$name."')" ;
}
else if($type == "project"){   
    $qry="insert into project (`name`)  values('".$name."')" ;
}
else if($type == "issue_warehouse"){   
    $address = $_POST["address"];
    $qry="insert into issue_warehouse (`name`, `address`, `makeby`, `makedt`)  values('".$name."', '".$address."' , '".$user."', sysdate())" ;
}
//print_r($_POST);
//echo $qry; die;
        
if ($conn->query($qry) == TRUE) {
    $last_id = $conn->insert_id;
    
    $response = array(

        "id" => $last_id,
    
        "name" => $name
        
    );
        
    echo json_encode($response);exit();

}



?>