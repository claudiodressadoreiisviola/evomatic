<?php
require '../../MODEL/cart.php';
require '../../MODEL/product.php';
header("Content-type:application/json;charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if (empty($parts[5]))
{
    http_response_code(400);
    echo json_encode(["message" => []]);
}

$query = new Cart;
$result = $query->getCart($parts[5]);

$queryProd = new Product;
$productsCart=array();
for($i=0; $i<(count($result));$i++)
{
    $resultProd = $queryProd->getProduct($result[$i]["id"]);
    $productsCart[$i] = $resultProd;
}

http_response_code(200);
echo json_encode($productsCart);
