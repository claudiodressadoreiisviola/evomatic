<?php
require '../../MODEL/offer.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$query = new Offer;
$result = $query->getArchiveOffer();

$productsCart = array();
for ($i = 0; $i < (count($result)); $i++) {
    $productCart = array(
        "id" =>  $result[$i]["id"],
        "name" =>  $result[$i]["name"],
        "price" => $result[$i]["price"],
        "expiry" => $result[$i]["expiry"],
        "description" => $result[$i]["description"]
    );
    array_push($productsCart, $productCart);
}

if (empty($productsCart)) {
    http_response_code(404);
} else {
    echo json_encode($productsCart);
    http_response_code(200);
}
