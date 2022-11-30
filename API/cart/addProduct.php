<?php
require __DIR__ . '/../../MODEL/cart.php';

$parts = explode("/", $_SERVER["REQUEST_URI"]);

//'localhost/evomatic/API/cart/addProduct.php/id_cart/id_product/quantity/id_user'

$cart = new Cart;

$result = $cart->addCart($parts[8]);
$result2 = $cart->addCartProduct($parts[5], $parts[6], $parts[7]);
$result3 = $cart->updateQuantity($parts[6]);
$result4 = $cart->updatePrice();


echo json_encode($result);
echo json_encode($result2);
echo json_encode($result3);
echo json_encode($result4);
