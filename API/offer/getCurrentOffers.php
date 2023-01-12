<?php
require '../../MODEL/offer.php';
header("Content-type: application/json; charset=UTF-8");

//$parts = explode("/", $_SERVER["REQUEST_URI"]);

$query = new Offer;
$result = $query->getCurrentOffers();

$productsOffer = array();
for ($i = 0; $i < (count($result)); $i++) {
    $productOffer = array(
        "name" =>  $result[$i]["name"],
        "id" => $result[$i]["id"],
        "price" => $result[$i]["price"],
        "description" => $result[$i]["description"]

    );
    array_push($productsOffer , $productOffer );
}

if (empty($productsOffer)) {
    http_response_code(404);
} else {
    echo json_encode($productsOffer);
    http_response_code(200);
}