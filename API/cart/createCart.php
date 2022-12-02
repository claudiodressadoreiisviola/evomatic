<?php
require __DIR__ .'/../../MODEL/cart/cart.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);
$cart = new Cart;

if($cart->addCart($parts[5])){
    http_response_code(201);
    echo json_encode(["message" => "Cart added successfully"]);
}else{
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
}