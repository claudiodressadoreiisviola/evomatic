<?php
require '../../MODEL/offer.php';
header("Content-type: application/json; charset=UTF-8");
$data = json_decode(file_get_contents("php://input"));

if(empty($data->price) || empty($data->start) || empty($data->expiry || empty($data->description)|| empty($data->products))){
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
}

$offer = new Offer;

if($offer -> createOffer($data->price, $data->start, $data->expiry, $data->description, $data->products)){
    http_response_code(200);
    echo json_encode(["message" => "Product addede successfully"]);
}else{
    http_response_code(400);
    echo json_encode(["message" => "Fill every field"]);
}
