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

$result = $order->getArchiveOrderUser($parts[5]);

$orders = array();
for ($i = 0; $i < (count($result)); $i++) {
    $order = array(
        "id" =>  $result[$i]["id"],
        "created" => $result[$i]["created"],
        "pickup" => $result[$i]["time"],
        "break" => $result[$i]["name"],
        "status" => $result[$i]["description"]
    );
    array_push($orders, $order);
}

if (empty($orders)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($orders);
}
