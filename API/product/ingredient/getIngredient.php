<?php
require __DIR__ . '/../../../MODEL/ingredient.php';
header("Content-type: application/json; charset=UTF-8");
$parts = explode("/", $_SERVER["REQUEST_URI"]);

$ingredient = new Ingredient;

$result = $ingredient->getIngredient($parts[6]);

echo json_encode($result);

?>
