<?php
require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$product = new Product();

$result = $product->getProductAllergens($parts[5]);

$productAllergens = array();
for ($i = 0; $i < (count($result)); $i++) {
    $productAllergen = array(
        "id" =>  $result[$i]["id"],
        "name" => $result[$i]["name"]
    );
    array_push($productAllergens, $productAllergen);
}

if (empty($productAllergens)) {
    http_response_code(404);
} else {
    http_response_code(200);
    echo json_encode($productAllergens);
}
