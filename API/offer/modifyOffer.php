<?php
require '../../MODEL/offer.php';
header("Content-type: application/json; charset=UTF-8");


$data = json_decode(file_get_contents("php://input"));


if (empty($data->id_offer) || empty($data->price) || empty($data->expiry)) {
    http_response_code(404);
    echo json_encode(["message" => "Fill every field"]);
    die();
}

$query = new Offer;

$result = $query->ModifyOffer($data->id_offer, $data->price, $data->expiry);

if ($result != false) {
    echo json_encode($result);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Insert a valid ID"]);
}
