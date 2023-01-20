<?php
require __DIR__ . '/../../../MODEL/favourite.php';
require __DIR__ . '/../../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[6])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$favourite = new Favourite();

$result = $favourite->getArchiveFavourite($parts[6]);
$product = new Product;

$archiveFavourites = array();
for ($i = 0; $i < (count($result)); $i++) {
    $resultProd = $product->getProduct($result[$i]["pid"]);
    $archiveFavourite = array(
        "id" => $result[$i]["pid"],
        "name" => $resultProd["name"],
        "price" => $resultProd["price"],
        "description" => $resultProd["description"]
    );
    array_push($archiveFavourites, $archiveFavourite);
}

if (empty($archiveFavourites)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($archiveFavourites);
}
