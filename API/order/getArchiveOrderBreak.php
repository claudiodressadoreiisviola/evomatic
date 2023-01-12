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

$result = $order->getArchiveOrderBreak($parts[5]);

$ordersBreak = array();
for ($i = 0; $i < (count($result)); $i++) {
    $orderBreak = array(
        "id" =>  $result[$i]["oid"],
        "user" => $result[$i]["ue"],
        "created" => $result[$i]["oc"],
        "pickup" => $result[$i]["pn"],
        "status" => $result[$i]["sd"]
    );
    array_push($ordersBreak, $orderBreak);
}

if (empty($ordersBreak)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($ordersBreak);
}
