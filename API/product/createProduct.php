<?php
require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->price) || empty($data->description) || empty($data->quantity) || empty($data->category) || empty($data->ingredients) || empty($data->tags) || empty($data->nutritional_values)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$product = new Product();

$result = $product->createProduct($data->name, $data->price, $data->description, $data->quantity, $data->ingredients, $data->tags, $data->category, $data->nutritional_values);

echo json_encode($result);
