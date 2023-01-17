<?php
require __DIR__ .'/../../MODEL/cart.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$cart = new Cart;
echo $cart->removeCart($parts[5]);

if($cart->removeCart($parts[5])){
    http_response_code(201);
    echo json_encode(["message" => "Cart removed successfully"]);
}else{
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
}