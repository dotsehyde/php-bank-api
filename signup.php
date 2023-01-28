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
        $id_number = mysqli_real_escape_string($db, $data->id_number);
        $password = mysqli_real_escape_string($db, $data->password);
        $name = mysqli_real_escape_string($db, $data->name);
        $dateCreated = date('Y-m-d\TH:i:s.Z\Z', time());
        if (empty($password)
        || empty($name) ||
        empty($id_number)
        ) {
            http_response_code(400);
            echo json_encode(["msg" => "Incomplete form details."]);
        } else {
            $balance =rand(50,10000);
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "SELECT id_number FROM users WHERE id_number = '$id_number'";
            $result = mysqli_query($db, $sql);
            $exist = mysqli_num_rows($result) > 0;
            if ($exist) {
                http_response_code(400);
                echo json_encode(["msg" => "User already exists"]);
                exit();
            }
            $query = "INSERT INTO users (name, id_number, password,balance ,created_at) VALUES ('$name','$id_number','$hash',$balance,'$dateCreated')";
            $res = mysqli_query($db, $query);
            if ($res) {
                $query = "SELECT * FROM users WHERE id_number = '$id_number' LIMIT 1";
                $data = mysqli_query($db, $query);
                while ($row = mysqli_fetch_assoc($data)) {
                    $temp = [
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "balance"=>$row['balance'],
                        "id_number" => $row['id_number'],
                        "dateCreated" => $row['created_at']
                    ];
                }
                http_response_code(200);
                echo json_encode($temp);
            } else {
                http_response_code(500);
                echo json_encode(["msg" => "User creation failed"]);
                exit();
            }
        }
    } else {
        http_response_code(400);
        echo json_encode("Path not available.");
    }
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode(["msg" => "Error: " . $th]);
}