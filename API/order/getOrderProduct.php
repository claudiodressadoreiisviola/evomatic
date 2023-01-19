<?php
require __DIR__ . "/../../MODEL/order.php";
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts)) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$order = new Order;

$result = $order->getOrderProduct($parts[5]);

$ordersProduct = array();
for ($i = 0; $i < (count($result)); $i++) {
    $orderProduct = array(
        "id" =>  $result[$i]["id"],
        "category" => $result[$i]["category"],
        "name" => $result[$i]["name"],
        "description" => $result[$i]["description"],
        "quantity" => $result[$i]["quantity"],
        "price" => $result[$i]["price"]
    );
    array_push($ordersProduct, $orderProduct);
}

if (empty($ordersProduct)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($ordersProduct);
}
