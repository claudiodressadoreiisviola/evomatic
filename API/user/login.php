<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$user = new User();
$id = $user->login($data->email, $data->password);

if ($id > 0) {
    echo json_encode($id);
} else {
    http_response_code(400);
    echo json_encode(["id" => "-1"]);
}
