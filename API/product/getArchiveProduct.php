<?php
// API solo paninara

require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$product = new Product();

$result = $product->getArchiveProduct();

$productArchiveProducts = array();
for ($i = 0; $i < (count($result)); $i++) {
    $nutritionalValue = $product->getNutritionalValue($i);
    $productArchiveProduct = array(
        "name" => $result[$i]["name"],
        "price" => $result[$i]["price"],
        "description" => $result[$i]["description"],
        "quantity" => $result[$i]["quantity"],
        "nutritional value" => $nutritionalValue
    );
    array_push($productArchiveProducts, $productArchiveProduct);
}

if (empty($productArchiveProducts)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($productArchiveProducts);
}
