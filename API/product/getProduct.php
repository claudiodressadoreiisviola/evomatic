<?php
require __DIR__ . '/../../MODEL/product.php';
require __DIR__ . '/../../MODEL/favourite.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$product = new Product();
$favourite = new Favourite();
$fav = false;

$result = $product->getProduct($parts[5]);
$resultFav = $favourite->getArchiveFavourite($parts[6]);
for ($j = 0; $j < (count($resultFav)); $j++){
    if ($resultFav[$j]["id"] == $parts[5])
        $fav = true;
}
$nutritionalValue = $product->getNutritionalValue($parts[5]);
$productArchiveProduct = array(
    "id" => $parts[5],
    "name" => $result["name"],
    //"category" => $result["category"], 
    "price" => $result["price"],
    "description" => $result["description"],
    "quantity" => $result["quantity"],
    "nutritional value" => $nutritionalValue,
    "favourite" => $fav
);

if ($result != false) {
    echo json_encode($productArchiveProduct);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Product not found"]);
}
