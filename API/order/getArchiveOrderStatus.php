<?php
require __DIR__ . "/../../MODEL/order.php";
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$order = new Order();

$result = $order->getArchiveOrderStatus($parts[5]);

$ordersStatus = array();
for ($i = 0; $i < (count($result)); $i++) {
    $orderStatus = array(
        "id" =>  $result[$i]["oid"],
        "user" => $result[$i]["ue"],
        "created" => $result[$i]["oc"],
        "pickup" => $result[$i]["pn"],
        "break" => $result[$i]["bt"],
        "status" => $result[$i]["sd"]
    );
    array_push($ordersStatus, $orderStatus);
}

if (empty($ordersStatus)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($ordersStatus);
}
