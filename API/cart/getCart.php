<?php
require '../../MODEL/cart.php';
require '../../MODEL/product.php';
header("Content-type:application/json;charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

$query = new Cart;
$result = $query->getCart($parts[5]);

$queryProd = new Product;
$productsCart=array();
for($i=0; $i<(count($result));$i++)
{
    $resultProd = $queryProd->getProduct($result[$i]["id"]);

    $productCart = array(
        "id" =>  $result[$i]["id"],
        "name" =>  $resultProd["name"],
        "price" => $resultProd["price"],
        "inStock" => $resultProd["quantity"],
        "description" => $resultProd["description"],
        "quantity_cart" => $result[$i]["quantity_cart"]
    );
    array_push($productsCart, $productCart);
}


http_response_code(200);
echo json_encode($productsCart);
