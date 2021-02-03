<?php 

require_once dirname(__FILE__) . '/../config.php';

if(isset($_POST['search'])){
    $search = mysqli_real_escape_string($mysqli,$_POST['search']);

    $query = "SELECT * FROM ".$tbl." WHERE TelegramUser like '%" . $search . "%'";
    $result = mysqli_query($mysqli,$query);
    
    while($row = mysqli_fetch_array($result) ){
        $response[] = array("email"=>$row['buyerEmail'],"pass"=>$row['pass'],"city"=>$row['city'],"label"=>$row['TelegramUser']);
    }

    echo json_encode($response);
}

if(isset($_POST['search_city'])){
    $search_city = mysqli_real_escape_string($mysqli,$_POST['search_city']);

    $query = "SELECT city FROM ".$tbl." WHERE city like '%" . $search_city . "%'";
    $result = mysqli_query($mysqli,$query);
    
    while($row = mysqli_fetch_array($result) ){
        $response[] = array("city"=>$row['city'],"label"=>$row['city']);
    }

    echo json_encode($response);
}

exit;