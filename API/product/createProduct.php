<?php
// ERRATO -> API solo paninara
/*
require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->price) || empty($data->description) || empty($data->quantity) || empty($data->active) || empty($date->$ingredients_ids) || empty($data->tags_ids) || empty($data->nutritional_value)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$product = new Product();

$result = $product->createProduct($data->name, $data->price, $data->description, $data->quantity, $data->active, $data->ingredients_ids, $data->tags_ids, $data->nutritional_value);

echo json_encode($result);
*/