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

$result = $product->getNutritionalValue($parts[5]);

$nutritionalValue = array(
  "kcal" =>  $result["kcal"],
  "fats" => $result["fats"],
  "saturated_fats" => $result["saturated_fats"],
  "carbohydrates" => $result["carbohydrates"],
  "sugars" => $result["sugars"],
  "proteins" => $result["proteins"],
  "fiber" => $result["fiber"],
  "salt" => $result["salt"],
);

echo json_encode($nutritinalValue);
