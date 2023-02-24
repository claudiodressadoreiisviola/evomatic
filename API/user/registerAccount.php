<?php
require __DIR__ . '/../../MODEL/user.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->surname) || empty($data->email) || empty($data->password) || empty($data->year) || empty($data->section) || empty($data->schoolYear) || empty($data->type) || empty($data->active)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$user = new User();

$result = $user->registerUser($data->name, $data->surname, $data->email, $data->password, $data->year, $data->section, $data->schoolYear, $data->type, $data->active);

echo json_encode($result);
?>
