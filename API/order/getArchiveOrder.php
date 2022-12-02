<?php

require __DIR__ . "/../../MODEL/order.php";
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$order = new Order();

$result = $order->getArchiveOrder();

$orders = array();
for ($i = 0; $i < (count($result)); $i++) {
    $order = array(
        "id" =>  $result[$i]["oid"],
        "user" => $result[$i]["ue"],
        "created" => $result[$i]["oc"],
        "pickup" => $result[$i]["pn"],
        "break" => $result[$i]["bt"],
        "status" => $result[$i]["sd"]
    );
    array_push($orders, $order);
}

if (empty($orders)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($orders);
}
