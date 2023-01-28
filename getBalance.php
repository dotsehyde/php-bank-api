<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization");

include_once('./config.php');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
   
            $id = $_GET['id'];
            $sql = "SELECT balance from users where id = $id";
            $res = mysqli_query($db, $sql);
            if ($res) {
                $row = mysqli_fetch_assoc($res);
                http_response_code(200);
                echo json_encode(
                    [
                        "balance" => $row['balance'], 
                    ]
                );
            } else {
                http_response_code(400);
                echo json_encode(["msg" => "Account Balance not available"]);
            }
      
    } else {
        http_response_code(400);
        echo json_encode(["msg" => "Path not available."]);
    }
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode(["msg" => "Error: " . $th]);
}