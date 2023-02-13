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

$result = $product->getProduct($parts[5]);
$category = $product->getProductCategory($parts[5]);
$nutritionalValue = $product->getNutritionalValue($parts[5]);
$productArchiveProduct = array(
    "id" => $parts[5],
    "name" => $result["name"],
    "category" => $category[0]["name"],
    "price" => $result["price"],
    "description" => $result["description"],
    "quantity" => $result["quantity"],
    "nutritional value" => $nutritionalValue,
);

if ($result != false) {
    echo json_encode($productArchiveProduct);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Product not found"]);
}