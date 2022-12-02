<?php
// API solo paninara

require __DIR__ . '/../../MODEL/product.php';
header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5])) {
    http_response_code(404);
    echo json_encode(["message" => "Insert a valid ID"]);
    exit();
}

$product = new Product();

$result = $product->deleteProduct($parts[5]);

if ($result == 1) {
    echo json_encode(["message" => "Product deleted successfully"]);
} else {
    http_response_code(400);
    echo json_encode(["message" => "Product not found"]);
}
