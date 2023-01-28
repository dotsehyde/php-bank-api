<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-credentials: true");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization");
include_once('./config.php');
try {
    //code...
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input", true));
        $to = mysqli_real_escape_string($db, $data->to);
        $from = mysqli_real_escape_string($db, $data->from);
        $amount = mysqli_real_escape_string($db, $data->amount);
        $dateCreated = date('Y-m-d\TH:i:s.Z\Z', time());
        $query = "INSERT INTO transactions (amount, sender, receiver ,created_at) VALUES ('$amount','$from','$to','$dateCreated')";
        $res = mysqli_query($db, $query);
        if($res){
            $toBalance  = mysqli_fetch_assoc( mysqli_query($db, "SELECT balance from users where id = $to"))['balance'];
            $fromBalance  =mysqli_fetch_assoc( mysqli_query($db, "SELECT balance from users where id = $from"))['balance'];
            $to_newBal= floatval ($toBalance )+ floatval ($amount);
            $from_newBal = floatval ($fromBalance) - floatval ($amount);
            mysqli_query($db,"UPDATE users SET balance = '$to_newBal' WHERE id = $to;");
            mysqli_query($db,"UPDATE users SET balance = '$from_newBal' WHERE id = $from;");
            http_response_code(200);
            echo json_encode(["msg" => "Transfer Successful"]);
        }else{
            http_response_code(500);
            $err = mysqli_error($db);
            echo json_encode(["msg" => "Transfer Failed\n".$err]); 
        }
    } else {
        http_response_code(400);
        echo json_encode("Path not available.");
    }
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode(["msg" => "Error: " . $th]);
}