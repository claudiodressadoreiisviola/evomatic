<?php
require __DIR__ . '/../../MODEL/pickup.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$query = new PickUp;
$result = $query->getArchivePickup();

$archivePickups = array();
for ($i = 0; $i < (count($result)); $i++) {
    $archivePickup = array(
        "id" =>  $result[$i]["id"],
        "name" => $result[$i]["name"]
    );
    array_push($archivePickups, $archivePickup);
}

if (!empty($archivePickups)) {
    echo json_encode($archivePickups);
} else {
    http_response_code(404);
    echo json_encode(["message" => "Can't find any Pickup"]);
}
