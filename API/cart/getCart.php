<?php
require '../../MODEL/cart.php';


$parts = explode("/", $_SERVER["REQUEST_URI"]);

$query = new Cart;
$result = $query->getCart($parts[5]);

$productsCart=array();
for($i=0; $i<(count($result));$i++)
{
    $productCart=array(
        "name" =>  $result[$i]["name"],
        "price" => $result[$i]["price"],
        "description" => $result[$i]["description"]
    );
    array_push($productsCart,$productCart);
}

if(empty($productsCart)){
    var_dump(http_response_code(404));
}
else{
    echo json_encode($productsCart);
    var_dump(http_response_code(200));
}