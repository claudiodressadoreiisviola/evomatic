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
        if ($resultFav[$j]["pid"] == $result[$i]["pid"])
            $fav = true;
    }
    $nutritionalValue = $product->getNutritionalValue($i);
    $resultTags = $product->getProductTags($result[$i]["pid"]);
    $resultIngredients = $product->getProductIngredients($result[$i]["pid"]);
    $resultAllergen = $product->getProductAllergens($result[$i]["pid"]);
    $productArchiveProduct = array(
        "id" => $result[$i]["pid"],
        "name" => $result[$i]["pname"],
        "category" => $result[$i]["category"],
        "price" => $result[$i]["price"],
        "description" => $result[$i]["description"],
        "quantity" => $result[$i]["quantity"],
        "nutritional value" => $nutritionalValue,
        "tags" => $resultTags,
        "ingredients" => $resultIngredients,
        "allergens" => $resultAllergen,
        "favourite" => $fav,
        "active" => $result[$i]["active"]
    );
    array_push($productArchiveProducts, $productArchiveProduct);
}
if (empty($productArchiveProducts)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($productArchiveProducts);
}
