<?php
require __DIR__ . '/../../MODEL/cart.php';
header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->user) || empty($data->product)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
}

$cart = new Cart;

if ($cart->removeProduct($data->user, $data->product)) {
    http_response_code(201);
    echo json_encode(["message" => "Product removed successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
}
