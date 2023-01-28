<?php

require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if(empty($data->product) || !(empty($data->active) && $data->active == 0)){
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$product = new Product();

if ($product->changeProductActive($data->product, $data->active)) {
    echo json_encode(["message" => "Product \"active\" changed successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Product not found"]);
}
