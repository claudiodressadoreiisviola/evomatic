<?php
// API solo paninara
require __DIR__ . '/../../MODEL/product.php';
require __DIR__ . '/../../MODEL/favourite.php';
header("Content-type: application/json; charset=UTF-8");
$parts = explode("/", $_SERVER["REQUEST_URI"]);
$product = new Product();
$favourite = new Favourite();
$fav;
$result = $product->getArchiveProduct();
$resultFav = $favourite->getArchiveFavourite($parts[5]);
$productArchiveProducts = array();
for ($i = 0; $i < (count($result)); $i++) {
    $fav = false;
    for ($j = 0; $j < (count($resultFav)); $j++){
        if ($resultFav[$j]["id"] == $result[$i]["id"])
            $fav = true;
    }
    $nutritionalValue = $product->getNutritionalValue($i);
    $productArchiveProduct = array(
        "id" => $result[$i]["id"],
        "name" => $result[$i]["name"],
        "price" => $result[$i]["price"],
        "description" => $result[$i]["description"],
        "quantity" => $result[$i]["quantity"],
        "nutritional value" => $nutritionalValue,
        "favourite" => $fav
    );
    array_push($productArchiveProducts, $productArchiveProduct);
}
if (empty($productArchiveProducts)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($productArchiveProducts);
}