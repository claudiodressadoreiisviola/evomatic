<?php
require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->surname) || empty($data->email) || empty($data->password) || empty($data->year) || empty($data->section) || empty($data->schoolYear) || empty($data->active)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$product = new Product();

$result = $product->registerUser($name, $surname, $email, $password, $year, $section, $schoolYear, $year);

echo json_encode($result);
?>