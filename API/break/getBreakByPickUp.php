<?php
require __DIR__ . '/../../MODEL/pause.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$query = new Pause;

$result = $query->getBreakByPickup($parts[5]);

$breaksPickup = array();
for ($i = 0; $i < (count($result)); $i++) {
    $breakPickup = array(
        "name" =>  $result[$i]["name"],
        "time" => $result[$i]["time"]
    );
    array_push($breaksPickup, $breakPickup);
}

if (!empty($breaksPickup)) {
    echo json_encode($breaksPickup);
} else {
    http_response_code(404);
    echo json_encode(["message" => "This pickup doesn't have any break"]);
}
