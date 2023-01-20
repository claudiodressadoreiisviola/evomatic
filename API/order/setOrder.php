<?php
require __DIR__ . '/../../MODEL/order.php';
require __DIR__ . '/../../MODEL/cart.php';

header("Content-type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"));

if (empty($data->user) || empty($data->created) || empty($data->break) || empty($data->status) || empty($data->pickup)) {
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$order = new Order();

$cart = new Cart();

if ($order->setOrder($data->user, $data->created, $data->break, $data->status, $data->pickup) == 1) {
    $order->setProductOrder($data->user);
    $cart->removeCart($data->user);

    http_response_code(201);
    echo json_encode(["message" => "Updated successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Update unsuccessfull"]);
}

