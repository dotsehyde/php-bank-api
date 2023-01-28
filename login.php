<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-credentials: true");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization");

include_once('./config.php');
// echo $data['email'];
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //get raw data
        $data = json_decode(file_get_contents("php://input", true));
        $email = mysqli_real_escape_string($db, $data->email);
        $password = mysqli_real_escape_string($db, $data->password);
        $query = "SELECT * FROM users WHERE id_number = '$id_number'";
        $res = mysqli_query($db, $query);
        if (mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                if (password_verify($password, $row['password'])) {
                    $temp = [
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "id_number" => $row['id_number'],
                        "dateCreated" => $row['created_at'],
                    ];
                    http_response_code(200);
                    echo json_encode($temp);
                    exit();
                } else {
                    http_response_code(400);
                    echo json_encode(["msg" => "Incorrect credentials"]);
                    exit();
                }
            }
        } else {
            http_response_code(404);
            echo json_encode(["msg" => "User does not exist"]);
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode("Path not available.");
    }
} catch (\Throwable $th) {
    http_response_code(500);
    echo json_encode(["msg" => "Server Error: " . $th]);
}