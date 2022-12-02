<?php
require '../../MODEL/offer.php';
header("Content-type: application/json; charset=UTF-8");
$parts = explode("/", $_SERVER["REQUEST_URI"]);

if(empty($parts[5]))
{
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$query = new Offer;
$result = $query->getOffer($parts[5]);

if($result != false)
{
    echo json_encode($result);
}else
{
    http_response_code(400);
    echo json_encode(["message" => "Insert a valid ID"]);
}

